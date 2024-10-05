<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    // Initialize PHPMailer
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();                                       // Send using SMTP
        $mail->Host = 'smtp.gmail.com';                         // Set the SMTP server to send through
        $mail->SMTPAuth = true;                                // Enable SMTP authentication
        $mail->Username = 'firsthopetechnologies@gmail.com';   // SMTP username
        $mail->Password = 'Info@2024';                          // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;    // Enable TLS encryption
        $mail->Port = 587;                                     // TCP port to connect to

        // Recipients - Sending to admin
        $mail->setFrom('firsthopetechnologies@gmail.com', 'https://firsthopetechnologies.com/');
        $mail->addAddress('https://firsthopetechnologies.com/', 'First Hope Technologies');       // Add admin email
        
        // Content for admin
        $mail->isHTML(true);                                   // Set email format to HTML
        $mail->Subject = "New Contact Form Submission: " . $subject;
        $mail->Body    = "You have received a new query from your website contact form.<br><br>" .
                         "<strong>Name:</strong> $name<br>" .
                         "<strong>Email:</strong> $email<br>" .
                         "<strong>Subject:</strong> $subject<br>" .
                         "<strong>Message:</strong><br>$message";

        $mail->send();

        // Send confirmation to the user
        $mail->clearAddresses();                               // Clear admin email
        $mail->addAddress($email, $name);                      // User's email
        
        // Content for user
        $mail->Subject = "Thank you for contacting us!";
        $mail->Body    = "Dear $name,<br><br>" .
                         "Thank you for reaching out. We have received your message and will get back to you shortly.<br><br>" .
                         "<strong>Your Message:</strong><br>$message";

        $mail->send();

        echo json_encode(['message' => 'Your message has been sent successfully.']);
    } catch (Exception $e) {
        echo json_encode(['error' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
    }
}
?>
