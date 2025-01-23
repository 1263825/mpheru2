<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

// Include the PHPMailer class
require 'php-mailer/class.phpmailer.php';

// Your email address
$to_admin = 'info@mpherugroup.co.za';
$to_client = $email; // Client's email
$from = 'info@mpherugroup.co.za';

$subject_admin = "New Appointment Request";  // Subject for admin email
$subject_client = "Thank you for contacting us!"; // Subject for client email

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve form data
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES);
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES);
    $phone = htmlspecialchars($_POST['number'], ENT_QUOTES);
    $services = htmlspecialchars($_POST['services'], ENT_QUOTES);
    $size_of_work = htmlspecialchars($_POST['size_of_work'], ENT_QUOTES);
    $size_unit = htmlspecialchars($_POST['size_unit'], ENT_QUOTES);
    $urgency = htmlspecialchars($_POST['urgency'], ENT_QUOTES);
    $description = htmlspecialchars($_POST['message'], ENT_QUOTES);

    // Prepare the fields to include in the admin email
    $fields = array(
        array('text' => 'Name', 'val' => $name),
        array('text' => 'Email', 'val' => $email),
        array('text' => 'Phone Number', 'val' => $phone),
        array('text' => 'Service', 'val' => $services),
        array('text' => 'Size of work', 'val' => $size_of_work),
        array('text' => 'Size Unit', 'val' => $size_unit),
        array('text' => 'Urgency', 'val' => $urgency),
        array('text' => 'Message', 'val' => $description)
    );

    // Construct the HTML email body for admin
    $message_admin = '
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
            .email-container { background-color: #ffffff; padding: 20px; border-radius: 8px; }
            h2 { color: #333333; }
            .details { margin-bottom: 10px; }
            .details span { font-weight: bold; }
            .footer { text-align: center; font-size: 12px; color: #777777; margin-top: 20px; }
        </style>
    </head>
    <body>
        <div class="email-container">
            <h2>New Appointment Request from ' . $name . '</h2>
            <div class="details">
                <span>Name:</span> ' . $name . '<br>
                <span>Email:</span> ' . $email . '<br>
                <span>Phone Number:</span> ' . $phone . '<br>
                <span>Service:</span> ' . $services . '<br>
                <span>Size of Work:</span> ' . $size_of_work . '<br>
                <span>Size Unit:</span> ' . $size_unit . '<br>
                <span>Urgency:</span> ' . $urgency . '<br>
                <span>Message:</span> ' . nl2br($description) . '<br>
            </div>
            <div class="footer">
                <p>Thank you for choosing Mpheru Group. We will get back to you shortly.</p>
            </div>
        </div>
    </body>
    </html>';

    // Construct the HTML email body for client
    $message_client = '
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
            .email-container { background-color: #ffffff; padding: 20px; border-radius: 8px; }
            h2 { color: #333333; }
            .footer { text-align: center; font-size: 12px; color: #777777; margin-top: 20px; }
        </style>
    </head>
    <body>
        <div class="email-container">
            <h2>Thank you for contacting us, ' . $name . '!</h2>
            <p>We have received your appointment request and will be in touch soon to discuss further details. Our team is working on your request and will get back to you shortly.</p>
            <div class="footer">
                <p>Thank you for choosing Mpheru Group. We value your inquiry!</p>
            </div>
        </div>
    </body>
    </html>';

    // Set up email headers for admin
    $headers_admin = "Reply-To: $from\r\n";
    $headers_admin .= "Return-Path: $from\r\n";
    $headers_admin .= "From: $from\r\n";
    $headers_admin .= "Content-Type: text/html; charset=UTF-8\r\n";  // Ensure email is sent as HTML

    // Set up email headers for client
    $headers_client = "Reply-To: $from\r\n";
    $headers_client .= "Return-Path: $from\r\n";
    $headers_client .= "From: $from\r\n";
    $headers_client .= "Content-Type: text/html; charset=UTF-8\r\n";  // Ensure email is sent as HTML

    // Send the email to admin
    $admin_sent = mail($to_admin, $subject_admin, $message_admin, $headers_admin);

    // Send the email to the client
    $client_sent = mail($to_client, $subject_client, $message_client, $headers_client);

    if ($admin_sent && $client_sent) {
        // On success, return a JSON response
        $arrResult = array('response' => 'success');
        echo json_encode($arrResult);
    } else {
        // On failure, return a JSON error response
        $arrResult = array('response' => 'error');
        echo json_encode($arrResult);
    }

} else {
    // If not a POST request, return an error response
    $arrResult = array('response' => 'invalid_request');
    echo json_encode($arrResult);
}
?>
