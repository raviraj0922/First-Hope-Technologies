<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Collect form data
$name       = $_POST['name'] ?? '';
$contact    = $_POST['contact'] ?? '';
$email      = $_POST['email'] ?? '';
$dob        = $_POST['dob'] ?? '';
$gender     = $_POST['gender'] ?? '';
$area       = $_POST['area'] ?? '';
$propertyAddress = $_POST['propertyAddress'] ?? '';
$ownerAddress    = $_POST['ownerAddress'] ?? '';
$details    = $_POST['details'] ?? '';

$mail = new PHPMailer(true);

try {
    // Email to Admin
    $mail->setFrom('firsthopetechnology@gmail.com', 'First Hope');
    $mail->addAddress('firsthope2022@gmail.com', 'First Hope');

    $mail->isHTML(true);
    $mail->Subject = "New Property Submission from $name";
    $mail->Body = "
        <h2>New Property Submission Details</h2>
        <p><strong>Name:</strong> $name</p>
        <p><strong>Contact:</strong> $contact</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Date of Birth:</strong> $dob</p>
        <p><strong>Gender:</strong> $gender</p>
        <p><strong>Total Property Area:</strong> $area</p>
        <p><strong>Property Address:</strong> $propertyAddress</p>
        <p><strong>Owner Address:</strong> $ownerAddress</p>
        <p><strong>Discussion:</strong><br>$details</p>
    ";

    // Attachments from temp location
    if ($_FILES['location_photo']['error'] == 0) {
        $mail->addAttachment($_FILES['location_photo']['tmp_name'], $_FILES['location_photo']['name']);
    }
    if ($_FILES['photo']['error'] == 0) {
        $mail->addAttachment($_FILES['photo']['tmp_name'], $_FILES['photo']['name']);
    }

    $mail->send();

    // Thank You Email to User
    $userMail = new PHPMailer(true);
    $userMail->setFrom('firsthopetechnology@gmail.com', 'First Hope');
    $userMail->addAddress($email, $name);
    $userMail->isHTML(true);
    $userMail->Subject = 'Thank you for your property submission';
    $userMail->Body = "
        <p>Dear $name,</p>
        <p>Thank you for submitting your property details. We have received your request and will be in touch shortly.</p>
        <p>Best regards,<br>First Hope Team</p>
    ";
    $userMail->send();

    $_SESSION['success'] = "Your property details have been submitted successfully.";
    header("Location: invest-earn.html");
    exit();
} catch (Exception $e) {
    $_SESSION['error'] = "Error sending email: " . $mail->ErrorInfo;
    header("Location: invest-earn.html");
    exit();
}
?>
