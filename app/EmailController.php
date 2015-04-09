<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
//ini_set("log_errors", 1);

require_once(dirname(__FILE__) . '/ListController.php');

if (!isset($_SESSION)) {
	//any subdomains, including "www.mydomain.com" will be included in the session. 
	session_set_cookie_params('', '/', '.omer.bprowd.com', 0);
	session_start();
}

class EmailController{    
    private static $timeDelayInSeconds = 4;
    private static $emailLimit = 100;
    
    public static function sendReminder($email, $omerDayHebrew, $omerDayEnglish){                
        $emailSubject = 'Sefirah Reminder!';    
        $emailMessage = "Sefirah Reminder \n" . date("M jS, Y") . ": \r\n" .                                        
                        $omerDayHebrew . ". \r\n" . 
                        $omerDayEnglish . ". \r\n";  
        				
        $headers = "From: \"Sefirat HaOmer\" <ben@bprowd.com>"; // \r\n" .
        		    //"Reply-To: ben@bprowd.com \r\n";
        		    //'Bcc: bfeldman24@gmail.com' . "\r\n";
        
        return self::sendEmail($email, $emailSubject, $emailMessage, $headers);		        
    }
    
    public static function sendWelcomeMessage($email){                
        $emailSubject = 'Thanks for signing up with Sefirat HaOmer Reminders!';    
        $emailMessage = "Thanks for signing up with Sefirat HaOmer Reminders! \r\n" .                                        
                        "You will be alerted at 9:00PM EST every night of the Omer. \r\n" . 
                        "To stop receiving these notifications go to http://omer.bprowd.com/unsubscribe \r\n \r\n" .                        
                        "- Sefirah Reminders";  
        				
        $headers = "From: \"Sefirat HaOmer\" <ben@bprowd.com>"; // \r\n" .
        		    //"Reply-To: ben@bprowd.com \r\n";
        		    //'Bcc: bfeldman24@gmail.com' . "\r\n";
        
        return self::sendEmail($email, $emailSubject, $emailMessage, $headers);		        
    }   
                   
    public static function unsubscribeEmail($email){   
        $to = "ben@bprowd.com";
        $emailSubject = 'Unsubscribe from Sefirah Reminders!';    
        $emailMessage = $email . " wants to unsubscribe \r\n" .                                        
                        "- Sefirah Reminders";  
        				
        $headers = "From: \"Sefirat HaOmer\" <ben@bprowd.com>"; // \r\n" .
        		    //"Reply-To: ben@bprowd.com \r\n";
        		    //'Bcc: bfeldman24@gmail.com' . "\r\n";
        
        return self::sendEmail($to, $emailSubject, $emailMessage, $headers);		        
    }   
    
    
    public static function sendHtmlEmail($email, $from, $subject, $message){ 			
        $headers = "From: ".$from." \r\n" .
        		    "Reply-To: ".$from." \r\n" .
        		    "Bcc: bfeldman24@gmail.com \r\n" .
        		    "MIME-Version: 1.0\r\n" .
        		    "Content-Type: text/html; charset=ISO-8859-1\r\n"; 		    
        
        return self::sendEmail($email, $subject, $message, $headers);
    }
    
    
    private static function sendEmail($email, $subject, $message, $headers, $options = null){
        if (isset($_SESSION) && isset($_SERVER['REMOTE_ADDR'])){

            // Restrict duplicate emails
            if (isset($_SESSION['lastSendEmailTime']) && (time() - $_SESSION['lastSendEmailTime'] < self::$timeDelayInSeconds)){
                ListController::writeToFile("preventedDuplicateEmails", $email." - ".$subject);
                return "failed";
            }
            
            $_SESSION['lastSendEmailTime'] = time(); 
            
            // Restrict too many emails
            if (!isset($_SESSION['emailCounter'])){
                $_SESSION['emailCounter'] = 0;
            }
            
            if ($_SESSION['emailCounter'] > self::$emailLimit){
                ListController::writeToFile("tooManyEmails", $email." - ".$subject);
                return "failed";   
            }
            
            $_SESSION['emailCounter']++;
        }
          
        // Send  
        if(mail($email, $subject, $message, $headers, $options)){                                    
        	return "success";	
        }else{
        	return "failed";	
        } 
    }          
}
?>