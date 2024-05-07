<?php
include_once 'menu2.php';
include_once 'connection.php';

// Function to handle user registration
// function menuRegister($textArray, $phoneNumber, $conn) {
//     $level = count($textArray);
    
//     if ($level == 1) {
//         echo "CON Enter your full name:\n";
//     } elseif ($level == 2) {
//         echo "CON Enter your email address:\n";
//     } elseif ($level == 3) {
//         echo "CON Select your certificate level:\n";
//         echo "1. PHD\n";
//         echo "2. A0\n";
//         echo "3. A1\n";
//         echo "4. Cancel\n";
//     } elseif ($level == 4) {
//         if ($textArray[3] == 4) {
//             echo "END Registration canceled.\n";
//         } else {
//             // Check if name is provided, otherwise prompt for it
//             if (empty($textArray[0])) {
//                 echo "CON Enter your full name:\n";
//             } else {
//                 // Name is provided, check if email is provided, otherwise prompt for it
//                 if (empty($textArray[1])) {
//                     echo "CON Enter your email address:\n";
//                 } else {
//                     // Email is provided, prompt for gender
//                     echo "CON Select your gender:\n";
//                     echo "1. Male\n";
//                     echo "2. Female\n";
//                 }
//             }
//         }
//     } elseif ($level == 5) {
//         // Gender is provided, prompt for password
//         echo "CON Enter your password:\n";
//     } elseif ($level == 6) {
//         // All fields are provided, register the user
//         registerUser($textArray, $phoneNumber, $conn);
//     }
// }

// Function to register the user
function registersms($arr,$phn) {
        $expp=explode(" ",$arr);
        $exp=count($expp);
        if ($exp==6) {
            $name=$expp[0];
            $email=$expp[1];
            $certificate=$expp[2];
            $gender=$expp[3];
            $pin=$expp[4];
            $confirmpin=$expp[5];
            $co=new PDO("mysql:host=localhost;dbname=jobapp","root","");
            $q="SELECT * from users where phone='$phn'";
            $stm=$co->prepare($q) ;
            $stm->execute();
            $res=$stm->fetchAll(PDO::FETCH_ASSOC) ;
            if ($pin==$confirmpin) {
                $ppass=md5($pin);
                if (count($res)==0) {
                    $query= "INSERT INTO users(name,phone,email,sex,certificate,password) values (:name,:phone,:email,:sex,:certificate,:password)";
                    $statement=$co->prepare($query);
                    if ($statement->execute(
                        array("name"=>$name,"phone"=>$phn,"email"=>$email,"sex"=>$gender,"certificate"=>$certificate,"password"=>$pin)
                    )) {
                        echo "END you are registered now\n99. go to main menu";
                    }
                }
                else{
                    echo " END you are already registered\n99. go to main menu";
                }
            }
            else{
                echo "END pin not match\n99. go to main menu";
            }
        }
    }

// Receive data from the gateway
$phoneNumber = $_POST['phoneNumber'];
$text = $_POST['text']; // Format: "name id email pin"

registersms($text,$phoneNumber);

// // Check if the received SMS contains enough information for registration
// if (count($text) >= 6) {
//     // Call the menuRegister function
//     menuRegister($text, $phoneNumber, $pdo);
// } else {
//     // Check which field is empty and prompt for it
//     $missingFields = [];
//     if (empty($text[0])) {
//         $missingFields[] = "name";
//     }
//     if (empty($text[1])) {
//         $missingFields[] = "Email";
//     }
//     if (empty($text[2])) {
//         $missingFields[] = "certificate";
//     }
//     if (empty($text[3])) {
//         $missingFields[] = "gender";
//     }
//     if (empty($text[4])) {
//         $missingFields[] = "pin";
//     }


//     if (count($missingFields) > 0) {
//         // If any field is missing, prompt for the missing fields
//         $message = "CON Fill your ";
//         $message .= implode(", ", $missingFields);
//         echo $message;
//     } else {
//         // If all fields are provided but count is less than 4
//         echo "END Please provide all required information";
//     }
// }
?>
