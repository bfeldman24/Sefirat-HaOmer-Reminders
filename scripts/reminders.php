<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
//ini_set("log_errors", 1);

header('Content-Type:text/plain');

require_once(dirname(__FILE__) . '/../app/EmailController.php');

if (date("Y") > 2015){
    // Get day of the omer from the hebrew date
    // 1st day of Pesach (nissan 15th) = Day 0 of the Omer
    // 2nd day of Pesasch = Day 1 of the Omer
    $hebYear = 3760 + date("Y"); // 3760 = 5775 - 2015
    $jd = jewishtojd(8,15,$hebYear);
    $timestamp = jdtounix($jd);
    $omerDay = date("z") - date("z", $timestamp); // z = day of the year out of 365
}
else{
    $omerDay = date("z") - 92;    
}

// Exit if the omer is over
if ($omerDay < 1 || $omerDay > 49){
    exit();   
}


// Get Sefirah Text
$omerFileName = dirname(__FILE__) . '/../data/omer-count.csv';            					
$omerFile = fopen($omerFileName,"r");
$omerDayHebrew = null;
$omerDayEnglish = null;

while(! feof($omerFile))
{
    $omerCount = fgetcsv($omerFile, 0, '|');        
    
    if (!empty($omerCount) && is_array($omerCount)){    
            $day = $omerCount[0];
            
            if ($day == $omerDay){
                $omerDayHebrew = $omerCount[1];
                $omerDayEnglish = $omerCount[2];
                break; 
            }
    }
}    

fclose($omerFile);


$headerText = "Sefirah Reminder";
if (date("N") == 5){ // 5 = Friday
    $headerText .= " for tonight (do not say now)";
}

echo $headerText . "\n";
echo $omerDayHebrew . "\n";
echo $omerDayEnglish . "\n";

// Send Reminders to Users
$signupFile = dirname(__FILE__) . '/../data/omer-signup.csv';            					
$userFile = fopen($signupFile,"r");

echo date("M jS, Y") . "\n";
echo date("z") - 92;
echo "\n";


while(! feof($userFile))
{
    $user = fgetcsv($userFile);        
    
    if (!empty($user) && is_array($user)){    
            $email = $user[0];
            $success = false;
            
            if (!empty($email) && strpos($email, "@") !== false && strlen($email) > 8){ // 8 = strlen("ab@12.il")
                $success = EmailController::sendReminder($email, $headerText, $omerDayHebrew, $omerDayEnglish);
            }
            
            $successMsg = $success ? "SUCCESS" : "FAILED";
            echo $successMsg . " - " . $email . "\n";
    }
}    

fclose($userFile);
?>