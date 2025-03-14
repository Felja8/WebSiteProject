<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Database connection
$host = "localhost";
$user = "root";  
$pass = "";  
$dbname = "contact_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $company = $conn->real_escape_string($_POST['cname']);
    $email = $conn->real_escape_string($_POST['email']);
    $message = $conn->real_escape_string($_POST['message']);

    // Insert into database
    $sql = "INSERT INTO messages (name, company_name, email, message) VALUES ('$name', '$company', '$email', '$message')";

    if ($conn->query($sql) === TRUE) {
        // Send Email Notification
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'dimbelfeliks@gmail.com'; // Your Gmail
            $mail->Password = 'yijl sosp bope qobu
'; // Use App Password (Not your real Gmail password)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Set Email
            $mail->setFrom('dimbelfeliks@gmail.com', 'Feliks');
            $mail->addAddress('dimbelfeliks@gmail.com'); // You receive the message here

            $mail->isHTML(true);
            $mail->Subject = "NOTIFICATION: Someone wants to hire you!";
            $mail->Body = "
                <h2>New Job Inquiry</h2>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Company:</strong> $company</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Message:</strong><br>$message</p>
            ";

            $mail->send();
            echo "<script>alert('Message Sent & Saved Successfully!'); window.location.href='index.html';</script>";
        } catch (Exception $e) {
            echo "Message saved but email failed: " . $mail->ErrorInfo;
        }
    } else {
        echo "Database error: " . $conn->error;
    }
}

$conn->close();
?>