<?php
// Include the connection.php file to establish a database connection
include("connection.php");
// Include the Menu class
include_once 'menu2.php';

// Include the Util class for utility constacontnts
include_once 'util.php';
$sessionId = $_POST['sessionId'];
$phoneNumber = $_POST['phoneNumber'];
$serviceCode = $_POST['serviceCode'];
$text = $_POST['text'];
$menu = new Menu($text, $sessionId, $pdo);
$text = $menu->middleware($text);

// Check if the phone number exists in the database
$result=$menu->isRegistered($phoneNumber);
if($result){
    $status=true;
}else{
    $status=false;
}
$isRegistered=$status;

if($text == "" && !$isRegistered){
    //Do something
    $menu -> mainMenuUnregistered();
}
else if($text == "" && $isRegistered){
    //Do something
    $menu -> mainMenuRegistered();
 
}
else if(!$isRegistered){
    //Do something
    $textArray = explode("*", $text);
    switch($textArray[0]){
        case 1:
            $menu->menuRegister($textArray,$phoneNumber);
            break;
        default:
            echo "END Invalid option, retry";
    }
} else {
  
    $textArray = explode("*", $text);

   
    if ($isRegistered) {
        switch ($textArray[0]) {
            case 1:
           
                $menu->menuApplyForJob($textArray,$phoneNumber);
                break;
            case 2:
               
                $menu->menuPay($textArray,$phoneNumber);
                break;
            case 3:
               
                $menu->menuViewProfile($textArray,$phoneNumber);
                break;
            case 4:
             
                $menu->menuTrackApplications($textArray,$phoneNumber);
                break;
            case 5:
               
                $menu->menuViewApplicationsAvailable($textArray,$phoneNumber);
                break;
            case 6:
        
                $menu->menuViewApplicationsPaymentHistory($textArray,$phoneNumber);
                break;
            case 7:
             
                $menu->menuUpdatePassword($textArray,$phoneNumber);
                break;
            default:
             
                echo "END Invalid choice\n";
        }
    }
}
?>
