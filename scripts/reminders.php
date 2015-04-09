<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
//ini_set("log_errors", 1);

header('Content-Type:text/plain');

require_once(dirname(__FILE__) . '/../app/EmailController.php');

// Get Sefirah Text
$omerFileName = dirname(__FILE__) . '/../data/omer-count.csv';            					
$omerFile = fopen($omerFileName,"r");


if (date("Y") > 2015){
    // Get day of the omer using the 2nd night of pesach 
    $hebYear = 3760 + date("Y"); // 3760 = 5775 - 2015
    $jd = jewishtojd(8,15,$hebYear); // 2nd night of pesach - 1
    $timestamp = jdtounix($jd);
    $omerDay = date("z") - date("z", $timestamp); // z = day of the year out of 365
}
else{
    $omerDay = date("z") - 92;    
}

$omerDayHebrew = null;
$omerDayEnglish = null;

// Exit if the omer is over
if ($omerDay < 1 || $omerDay > 49){
    exit();   
}

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
                $success = EmailController::sendReminder($email, $omerDayHebrew, $omerDayEnglish);
            }
            
            $successMsg = $success ? "SUCCESS" : "FAILED";
            echo $successMsg . " - " . $email . "\n";
    }
}    

fclose($userFile);
?>