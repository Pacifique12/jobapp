<?php
include("connection.php");

class Menu {
    private $text;
    private $sessionId;
    private $currentStep;
    private $pdo; // Add PDO property

    public function isRegistered($phoneNumber){
        // Prepare the SQL statement using parameter binding to prevent SQL injection
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE phone = :phoneNumber");
        // Bind the parameter
        $stmt->bindParam(':phoneNumber', $phoneNumber);
        // Execute the query
        $stmt->execute();
        // Fetch the result as an associative array
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Check if a user with the given phone number exists
        if($result){
            return $result;  // User is registered
        }else{
            return false; // User is not registered
        }
    }
    

    // Constructor with PDO injection
    public function __construct($text, $sessionId, $pdo) {
        $this->text = $text;
        $this->sessionId = $sessionId;
        $this->pdo = $pdo; // Assign PDO instance

        // Set the current step based on the length of the textArray
        $this->currentStep = count(explode("*", $text));
    }

    // Main menu for unregistered users
    public function mainMenuUnregistered() {
        $response = "CON Welcome to PRO Company Job Application \n";
        $response .= "1. Register\n";
        echo $response;
    }
    public function menuRegister($textArray,$phoneNumber) {
        $level = count($textArray);
    
        if ($level == 1) {
            echo "CON Enter your full name:\n";
        }  elseif ($level == 2) {
            echo "CON Enter your email address:\n";
        } elseif ($level == 3) {
            echo "CON Select your certificate level:\n";
            echo "1. PHD\n";
            echo "2. A0\n";
            echo "3. A1\n";
            echo "4. Cancel\n";
        } elseif ($level == 4) {
            if ($textArray[3] == 4) {
                echo "END Registration canceled.\n";
            } else {
                $certificateLevels = ["1" => "PHD", "2" => "A0", "3" => "A1"];
                $certificate = $certificateLevels[$textArray[3]];
    
                echo "CON Select your gender:\n";
                echo "1. Male\n";
                echo "2. Female\n";
            }
        } elseif ($level == 5) {
            $certificateLevels = ["1" => "PHD", "2" => "A0", "3" => "A1"];
            $certificate = $certificateLevels[$textArray[3]];
            $gender = ($textArray[4] == 1) ? "Male" : "Female";
    
            echo "CON Enter your password:\n";
        } elseif ($level == 6) {
            $certificateLevels = ["1" => "PHD", "2" => "A0", "3" => "A1"];
            $certificate = $certificateLevels[$textArray[3]];
            $gender = ($textArray[4] == 1) ? "Male" : "Female";
    
            // Generate username based on the provided name
            $nameParts = explode(" ", $textArray[1]);
            $username = strtolower($nameParts[0]) . rand(100, 999);
    
            // Hash the entered password
            $hashedPassword = password_hash($textArray[5], PASSWORD_DEFAULT);
    
            // Insert user data into the database
            $stmt = $this->pdo->prepare("INSERT INTO users (name,phone, email, job, certificate, sex, username, password) VALUES (?, ?,?, ?, ?, ?, ?, ?)");
            $stmt->execute([$textArray[1],$phoneNumber, $textArray[2], 'Unknown', $certificate, $gender, $username, $hashedPassword]);
            $userId = $this->pdo->lastInsertId();
            $inser=$this->pdo->prepare("INSERT INTO `balance` (`b_id`, `st_id`, `amount`, `status`) VALUES (NULL, '$userId ', '10000', 'Active')");
            $inser->execute();
            echo "END Registration successful. Your username is: $username\n";
        }
    }
    



    public function menuApplyForJob($textArray,$phoneNumber) {
        $userData = $this->isRegistered($phoneNumber);
    
        if ($userData) {
            $userId = $userData['ID'];
    
            // Fetch available jobs from the database
            $stmt = $this->pdo->prepare("SELECT * FROM jobs");
            $stmt->execute();
            $availableJobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Check if there are available jobs
            if ($availableJobs) {
                // Check if the input array has the expected elements
                if (isset($textArray[1])) {
                    $jobIndex = (int)$textArray[1] - 1; // Convert user input to array index
    
                    // Check if the selected job index is valid
                    if ($jobIndex >= 0 && $jobIndex < count($availableJobs)) {
                        $selectedJob = $availableJobs[$jobIndex];
                        $jobId = $selectedJob['id']; // Assuming job ID is retrieved from the database
    
                        // Update user's job in the users table
                        $stmt = $this->pdo->prepare("UPDATE users SET job = ? WHERE ID = ?");
                        $stmt->execute([$selectedJob['job_title'], $userId]);

                        $sele=$this->pdo->prepare("SELECT * FROM applicant_details WHERE user_id='$userId' and job_id='$jobId'");
                        $sele->execute();
                        if($sele->rowCount()>0){
                            echo "END Already apply this jobe";
                        }else{
                            // Insert application details into the database
                            $stmt = $this->pdo->prepare("INSERT INTO applicant_details (user_id, job_id, application_status) VALUES (?, ?, ?)");
                            $stmt->execute([$userId, $jobId, 'Pending']);
        
                            // Prompt the user with a success message
                            echo "END Application for " . $selectedJob['job_title'] . " at " . $selectedJob['company_name'] . " submitted successfully.\n";
                        }
                       
                    } else {
                        echo "END Invalid job selection. Please try again.\n";
                    }
                } else {
                    // Prompt the user to select a job from the list
                    $response = "CON Available Jobs for Application:\n";
                    foreach ($availableJobs as $index => $job) {
                        $response .= ($index + 1) . ". " . $job['job_title'] . " at " . $job['company_name'] . "\n";
                    }
                    $response .= "Enter the number of the job you want to apply for:\n";
                    echo $response;
                }
            } else {
                // Display a message if no jobs are available
                echo "END No jobs available at the moment. Please try again later.\n";
            }
        } else {
            echo "END User not found. Please try again later.\n";
        }
    }
    

// Menu for application fees payment
// Menu for application fees payment
public function menuPay($textArray, $phoneNumber) {
    $level = count($textArray);

    if ($level == 1) {
        echo "CON Enter amount\n";
    } else if ($level == 2) {
        echo "CON Enter PIN\n";
    } else if ($level == 3) {
        $response = "CON Payment process can continue...\n";
        $response .= "1. Confirm\n";
        $response .= "2. Cancel\n";
        $response .= Util::$GO_BACK . " Back\n";
        $response .= Util::$GO_TO_MAIN_MENU .  " Main menu\n";
        echo $response;
    } else if ($level == 4 && $textArray[3] == 1) {
        $amount = $textArray[1];
        $pin = $textArray[2];

        // Retrieve user ID using phone number
        $userData = $this->isRegistered($phoneNumber);
        if ($userData) {
            $userId = $userData['ID'];
            $passwordHash = $userData['password'];

            // Check if PIN is correct
            if (password_verify($pin, $passwordHash)) {
                // Check if user has sufficient balance
                $stmtBalance = $this->pdo->prepare("SELECT amount FROM balance WHERE st_id = ?");
                $stmtBalance->execute([$userId]);
                $balanceRow = $stmtBalance->fetch(PDO::FETCH_ASSOC);
                if ($balanceRow && $balanceRow['amount'] >= $amount) {
                    // Deduct amount from balance
                    $stmtBalancee = $this->pdo->prepare("SELECT * FROM payments WHERE user_id = ?");
                    $stmtBalancee->execute([$userId]);
                    if($stmtBalancee->rowCount()>0){
                        echo "END Already paid before";
                    }else{
                        $stmtUpdateBalance = $this->pdo->prepare("UPDATE balance SET amount = amount - ? WHERE st_id = ?");
                        if ($stmtUpdateBalance->execute([$amount, $userId])) {
                            // Insert payment details
                            $stmtInsertPayment = $this->pdo->prepare("INSERT INTO payments (user_id, amount) VALUES (?, ?)");
                            if ($stmtInsertPayment->execute([$userId, $amount])) {
                                
                            echo "END Your payment of $amount RWF has been processed successfully.\n";
                                
                            } else {
                                echo "END Error inserting payment details.\n";
                            }
                        } else {
                            echo "END Error deducting balance.\n";
                        }
                    }
                } else {
                    echo "END Insufficient balance.\n";
                }
            } else {
                echo "END Incorrect PIN.\n";
            }
        } else {
            echo "END User not found.\n";
        }
    }else{
        echo "Invalid option";
    }
}






  // Method to process input based on the modified $textArray
public function processInput($textArray,$phoneNumber) {
    // Set the current step based on the length of the textArray
    $this->currentStep = count($textArray);

    // Check if the user is registered and process input accordingly
    if (!$this->isRegistered($phoneNumber)) {
        switch ($textArray[0]) {
            case 1:
                // Handle registration for an unregistered user
                $this->menuRegister($textArray,$phoneNumber);
                break;
            case Util::$GO_BACK:
                // Call the goBack method
                $this->goBack($textArray,$phoneNumber);
                break;
            case Util::$GO_TO_MAIN_MENU:
                // Call the goToMainMenu method
                $this->goToMainMenu($textArray,$phoneNumber);
                break;
            default:
                // Display an error message for invalid options
                echo "END Invalid option, please retry";
        }

    } else {
        // Check input for a registered user
        switch ($textArray[0]) {
            case 1:
                // Handle applying for a job
                $this->menuApplyForJob($textArray,$phoneNumber);
                break;
            case 2:
                // Handle sending money
                $this->menuPay($textArray,$phoneNumber);
                break;
            case 3:
                // Handle viewing profile
                $this->menuViewProfile($textArray,$phoneNumber);
                break;
            case 4:
                // Handle tracking applications
                $this->menuTrackApplications($textArray,$phoneNumber);
                break;
            case 5:
                // Handle viewing available applications
                $this->menuViewApplicationsAvailable($textArray,$phoneNumber);
                break;
            case 6:
                // Handle viewing applications payment history
                $this->menuViewApplicationsPaymentHistory($textArray,$phoneNumber);
                break;
            case 7:
                // Handle updating password
                $this->menuUpdatePassword($textArray,$phoneNumber);
                break;
            case Util::$GO_BACK:
                // Call the goBack method
                $this->goBack($textArray,$phoneNumber);
                break;
            case Util::$GO_TO_MAIN_MENU:
                // Call the goToMainMenu method
                $this->goToMainMenu($textArray,$phoneNumber);
                break;
            default:
                // Display an error message for invalid choices
                echo "END Invalid choice\n";
        }
    }
}

// Method for handling goBack logic
public function goBack($textArray,$phoneNumber) {
    // Check the level of the input
    $level = count($textArray);

    // Check if there are at least two steps to go back
    if ($level > 2) {
        // Remove the last element from $textArray to go back one step
        array_pop($textArray);
        array_pop($textArray);

        // Set the current step based on the length of the modified $textArray
        $this->currentStep = count($textArray);

        // Continue processing based on the modified $textArray
        $this->processInput($textArray,$phoneNumber);
    } else {
        // Display a message indicating that there are not enough previous steps to go back to
        echo "END There are not enough previous steps to go back to\n";
    }
}
// Method for handling goToMainMenu logic
public function goToMainMenu($textArray,$phoneNumber) {
    $response = "END Redirecting to Main Menu\n";
    echo $response;

    // Display the main menu based on user status
    if ($this->isRegistered($phoneNumber)) {
        $this->mainMenuRegistered();
    } else {
        $this->mainMenuUnregistered();
    }
}
public function menuViewProfile($textArray,$phoneNumber) {
    
    $userData = $this->isRegistered($phoneNumber);

    if ($userData) {
        // Display user profile details
        $response = "END Profile Details:\n";
        $response .= "Name: " . $userData['name'] . "\n";
        $response .= "Email: " . $userData['email'] . "\n";
        $response .= "Phone: " . $userData['phone'] . "\n";
        // $response .= "1. Cancel\n";
        // $response .= "99. Go Back to Main Menu\n";
        echo $response;
    } else {
        // If user details not found, display an error message
        echo "END User not found. Please try again later.\n";
    }
}

// // Method for handling cancel action
// public function cancelAction() {
//     echo "END Action canceled.\n";
// }

// // Method for handling go back to main menu action
// public function goMainMenu() {
//     echo "END Redirecting to Main Menu.\n";
//     if ($this->isRegistered) {
//         $this->mainMenuRegistered();
//     } else {
//         $this->mainMenuUnregistered();
//     }
// }


// Method for tracking applications
public function menuTrackApplications($textArray, $phoneNumber) {
    $userData = $this->isRegistered($phoneNumber);

    if ($userData) {
        $userId = $userData['ID'];

        // Fetch application history from the database for the user
        $stmt = $this->pdo->prepare("
            SELECT j.job_title, j.company_name, a.application_status, u.feedback 
            FROM applicant_details a
            INNER JOIN jobs j ON a.job_id = j.id
            LEFT JOIN users u ON a.user_id = u.ID
            WHERE a.user_id = ?
        ");

        $stmt->execute([$userId]);
        $applicationHistory = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($applicationHistory) {
            // Display application history to the user
            $response = "END Application History:\n";
            foreach ($applicationHistory as $index => $application) {
                $response .= ($index + 1) . ". Job Title: " . $application['job_title'] . "\n";
                $response .= "   Company: " . $application['company_name'] . "\n";
                $response .= "   Status: " . $application['application_status'] . "\n";
                // Check if feedback exists and append it to the response if available
                if (!empty($application['feedback'])) {
                    $response .= "   Feedback: " . $application['feedback'] . "\n";
                }
            }
            echo $response;
        } else {
            // If no application history found, display a message
            echo "END No application history available.\n";
        }
    } else {
        // If user not found, display an error message
        echo "END User not found. Please try again later.\n";
    }
}


   // Method for viewing available applications
public function menuViewApplicationsAvailable($textArray,$phoneNumber) {
    
    $userData = $this->isRegistered($phoneNumber);

    if ($userData) {
        $userId = $userData['ID'];

        // Retrieve jobs not applied by the user from the database
        $stmt = $this->pdo->prepare("
            SELECT j.job_title, j.company_name 
            FROM jobs j
            LEFT JOIN applicant_details ad ON j.id = ad.job_id AND ad.user_id = ?
            WHERE ad.job_id IS NULL
        ");
        $stmt->execute([$userId]);
        $availableJobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($availableJobs) {
            // Display available jobs to the user
            $response = "END Available Jobs for Application:\n";
            foreach ($availableJobs as $index => $job) {
                $response .= ($index + 1) . ". " . $job['job_title'] . " at " . $job['company_name'] . "\n";
            }
            echo $response;
        } else {
        
            echo "END No available jobs for application.\n";
        }
    } else {
        echo "END User not found. Please try again later.\n";
    }
}
public function menuUpdatePassword($textArray,$phoneNumber) {
   
    $userData =$this->isRegistered($phoneNumber);

    if ($userData) {
        $userId = $userData['ID'];
        $hashedPasswordFromDB = $userData['password'];
        
        if (count($textArray) == 1) {
          
            echo "CON Enter old password:\n";
        } elseif (count($textArray) == 2) {
            $enteredOldPassword = $textArray[1];
            if (!password_verify($enteredOldPassword, $hashedPasswordFromDB)) {
                echo "END Incorrect old password. Please retry.\n";
            } else {
    
                echo "CON Enter new password:\n";
            }
        } elseif (count($textArray) == 3) {
        
            echo "CON Confirm new password:\n";
        } elseif (count($textArray) == 4) {
            // If this is the fourth step, compare new password with confirmation
            $newPassword = $textArray[2];
            $confirmedPassword = $textArray[3];

            if ($newPassword !== $confirmedPassword) {
                // If passwords don't match, display error message
                echo "END Passwords do not match. Please retry.\n";
            } else {
                // If passwords match, hash the new password and update it in the database
                $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $this->pdo->prepare("UPDATE users SET Password = ? WHERE ID = ?");
                $stmt->execute([$hashedNewPassword, $userId]);
                
                // Display a success message
                echo "END Password updated successfully.\n";
            }
        } else {
            // Handle unexpected number of steps (should not reach here)
            echo "END Unexpected error. Please retry.\n";
        }
    } else {
        // If user not found, display an error message
        echo "END User not found. Please try again later.\n";
    }
}

public function menuViewApplicationsPaymentHistory($textArray,$phoneNumber) {
    
    $userData = $this->isRegistered($phoneNumber);

    if ($userData) {
        $userId = $userData['ID'];

        // Retrieve payment history with associated job details for the user
        $stmt = $this->pdo->prepare("
            SELECT p.payment_id, p.amount, p.payment_date, j.job_title, j.company_name 
            FROM payments p 
            LEFT JOIN jobs j ON p.payment_id = j.id
             WHERE p.user_id = ?
        ");
        $stmt->execute([$userId]);
        $paymentHistory = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($paymentHistory) {
            // Display payment history with job details to the user
            $response = "END Payment History:\n";
            foreach ($paymentHistory as $index => $payment) {
                $response .= "Payment ID: " . $payment['payment_id'] . "\n";
                $response .= "Job Title: " . $payment['job_title'] . "\n";
                $response .= "Company: " . $payment['company_name'] . "\n";
                $response .= "Amount: " . $payment['amount'] . "\n";
                $response .= "Date: " . $payment['payment_date'] . "\n";
                $response .= "---------------------\n";
            }
            echo $response;
        } else {
            // If no payment history found for the user, display a message
            echo "END No payment history available for this user.\n";
        }
    } else {
        // If user not found, display an error message
        echo "END Error: User ID not found.\n";
    }
}

public function middleware($text){
    //remove entries for going back and going to the main menu
    return $this->goBack2($this->goToMainMenu2($text));
}

public function goBack2($text){
    //1*4*5*1*98*2*1234
    $explodedText = explode("*",$text);
    while(array_search(Util::$GO_BACK, $explodedText) != false){
        $firstIndex = array_search(Util::$GO_BACK, $explodedText);
        array_splice($explodedText, $firstIndex-1, 2);
    }
    return join("*", $explodedText);
}

public function goToMainMenu2($text){
    //1*4*5*1*99*2*1234*99
    $explodedText = explode("*",$text);
    while(array_search(Util::$GO_TO_MAIN_MENU, $explodedText) != false){
        $firstIndex = array_search(Util::$GO_TO_MAIN_MENU, $explodedText);
        $explodedText = array_slice($explodedText, $firstIndex + 1);
    }
    return join("*",$explodedText);
}


    // Main menu for registered users
    public function mainMenuRegistered() {
        echo "CON Welcome To YYY Application Portal:\n";
        echo "1. Apply For Job\n";
        echo "2. Pay Application\n";
        echo "3. View Profile\n";
        echo "4. Track Applications\n";
        echo "5. View Available Applications\n";
        echo "6. View Applications Payment History\n";
        echo "7. Update Password\n";
    }
}


?>
