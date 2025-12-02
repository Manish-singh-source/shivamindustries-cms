<?php

require(__DIR__ . '/PHPMailer/PHPMailerAutoload.php');
require 'PHPMailer/class.phpmailer.php';
require 'PHPMailer/class.smtp.php';

// Server-Side Validation
// Get form data
$firstname = isset($_POST['firstname']) ? trim($_POST['firstname']) : '';
$lastname = isset($_POST['lastname']) ? trim($_POST['lastname']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$company = isset($_POST['company']) ? trim($_POST['company']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$address = isset($_POST['address']) ? trim($_POST['address']) : '';
$product = isset($_POST['product']) ? trim($_POST['product']) : '';
$quantity = isset($_POST['quantity']) ? trim($_POST['quantity']) : '';
$product_image = isset($_POST['product_image']) ? trim($_POST['product_image']) : '';
$hidden_field = trim($_POST['hidden_field']); // Honeypot field

date_default_timezone_set('Asia/Kolkata');
$currentDateTime = date('Y-m-d H:i:s');

$errors = [];

// // Honeypot
if (!empty($hidden_field)) {
    $errors[] = "Bot detected!";
}

// Email Validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email address.";
}

// Phone Number Validation
if (!preg_match('/^\d{10}$/', $phone)) {
    $errors[] = "Invalid phone number. Please enter a 10-digit number.";
}

// // Field Validation
if (empty($firstname) || empty($email) || empty($phone)) {
    $errors[] = "All fields are required.";
}

// reCAPTCHA Verification
$recaptcha_secret = '6Le_1BssAAAAALHIgGQRpxWZ1WhoUxChYzK2FxQV';
$recaptcha_response = $_POST['g-recaptcha-response'];

$recaptcha_data = [
    'secret' => $recaptcha_secret,
    'response' => $recaptcha_response,
    'remoteip' => $_SERVER['REMOTE_ADDR']
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($recaptcha_data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$recaptcha_result = curl_exec($ch);
curl_close($ch);

$recaptcha_decoded = json_decode($recaptcha_result, true);
if (!$recaptcha_decoded['success']) {
    $errors[] = "reCAPTCHA verification failed. Please try again.";
}

// Stop on validation errors
if (!empty($errors)) {
    echo '<script>';
    echo 'alert("' . implode('\\n', $errors) . '");';
    echo 'window.location.href = "get-a-quote.php";';
    echo '</script>';
    exit;
}
// Prepare email content for admin
$htmlbody = "
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #078e8745; color: #000; padding: 20px; text-align: center; }
        .content { background-color: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
        .field { margin-bottom: 15px; }
        .label { font-weight: bold; color: #555; }
        .value { color: #000; }
        .product-image { max-width: 200px; margin: 15px 0; border-radius: 8px; }
        .footer { text-align: center; padding: 15px; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <img src='img/shivam-logo.png' alt='Shivam Industries Logo'/>
            <h2>New Enquiry Request</h2>
        </div>
        <div class='content'>
            <h3>Customer Information</h3>
            <div class='field'>
                <span class='label'>Name:</span> 
                <span class='value'>{$firstname} {$lastname}</span>
            </div>
            <div class='field'>
                <span class='label'>Email:</span> 
                <span class='value'>{$email}</span>
            </div>
            <div class='field'>
                <span class='label'>Phone:</span> 
                <span class='value'>{$phone}</span>
            </div>
            <div class='field'>
                <span class='label'>Company:</span> 
                <span class='value'>{$company}</span>
            </div>
            <div class='field'>
                <span class='label'>Address:</span> 
                <span class='value'>{$address}</span>
            </div>
            
            <h3 style='margin-top: 25px;'>Product Details</h3>
            <div class='field'>
                <span class='label'>Product Name:</span> 
                <span class='value'>{$product}</span>
            </div>
            <div class='field'>
                <span class='label'>Quantity:</span> 
                <span class='value'>{$quantity}</span>
            </div>
              
        </div>
        <div class='footer'>
            <p>This quote request was submitted from Shivam Industries website</p>
            <p>Date: " . date('Y-m-d H:i:s') . "</p>
        </div>
    </div>
</body>
</html>
";



// Prepare email content for client
$client_htmlbody = '
<html>
    <head>
        <title>Thank You for Contacting Us</title>
    </head>
    <body>
        <table>
            <tbody>
                <tr>
                    <td valign="middle" align="center">
                        <table width="630" cellspacing="0" cellpadding="0" border="1">
                            <tbody>
                                <tr>
                                    <td valign="middle" align="center">
                                        <table width="630" cellspacing="0" cellpadding="0" border="0">
                                            <tbody>
                                                <tr>
                                                    <td valign="middle" align="middle" style="background-color:gray;">
                                                        <table width="570" cellspacing="10" cellpadding="0" border="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td valign="middle" align="left" style="width:75%">
                                                                        <img style="display: inline-block; position: relative; max-width: 100%" src="https://2.imimg.com/data2/QF/US/MY-165916/shivam-industries-mumbai-logo-90x90.jpg" width="40%" height="40%"  border="0">
                                                                    </td>
                                                                    <td valign="middle" align="left"></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="middle" align="center">
                                                        <table width="620" cellspacing="20" cellpadding="0" border="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td valign="middle" align="center">
                                                                        <font style="font-size: 13px" color="#333333" face="Arial, Helvetica, sans-serif">
                                                                            <b>Hi ' . htmlspecialchars($firstname) . ',</b>                                                                            
                                                                        </font>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td valign="middle" align="center">
                                                                        <table width="580" cellspacing="10" cellpadding="0" border="0" bgcolor="#DCECF5">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td valign="middle" align="center">
                                                                                        <table width="570" cellspacing="10" cellpadding="0" border="0">
                                                                                            <tbody>
                                                                                                <tr>
                                                                                                    <td valign="middle" align="left">
                                                                                                        <font style="font-size: 12px" color="#333333" face="Arial, Helvetica, sans-serif">
                                                                                                        <span>We are delighted to welcome you to the Shivam industries  family! We hope you enjoy using our services. We have created a request for you. Our business advisor will get in touch with you in the next 12 hours and guide you through all your requirements. In the meanwhile, If you have any specific queries you mail us at: <a href="mailto:info@technofra.com">info@technofra.com</a></span><br />
                                                                                                        <span>Please visit our Website: <a href="https://www.shivaminds.com/">Shivam industries</a> for more services. Our priority is to ensure that you get help & support from our team business advisor as quick and stress-free as possible - by keeping you updated on the progress. Again, thank you for deciding to work with us. We hope we can give you the same satisfaction as what our loyal clients have been experiencing from us. We look forward to a long-term relationship.</span><br />
                                                                                                        </font>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td valign="middle" align="center">
                                                                                        <table width="600" cellspacing="10" cellpadding="0" border="0">
                                                                                            <tbody>
                                                                                                <tr>
                                                                                                    <td valign="middle" align="center">
                                                                                                        <table width="580" cellspacing="0" cellpadding="0" border="0">
                                                                                                            <tbody>
                                                                                                                <tr>
                                                                                                                    <td valign="middle" align="left">
                                                                                                                        <font style="font-size: 12px" color="#333333" face="Arial, Helvetica, sans-serif">
                                                                                                                            Best Regards,<br />
                                                                                                                            <span>Team Shivam industries </span><br />
                                                                                                                            <span>Email: <a href="#">sales@shivaminds.com</a></span><br />
                                                                                                                            <span>Contact No:+91 9987 65 1501</span>
                                                                                                                        </font>
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                            </tbody>
                                                                                                        </table>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>';



// Admin Email Setup// Admin Email Setup
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'sales@shivaminds.com';
$mail->Password = 'ggez jsph xoxl ggmo';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('sales@shivaminds.com', 'Shivam industries');
$mail->addAddress('sales@shivaminds.com');
$mail->isHTML(true);
$mail->Subject = 'Inquiry from Shivam industries Website (' . $currentDateTime . ')';
$mail->Body = $htmlbody;

if (!$mail->send()) {
    // Log error if needed: error_log($mail->ErrorInfo);
    header('Location: failed.php');
    exit;
}

// Client Email Setup
$client_mail = new PHPMailer();
$client_mail->IsSMTP();
$client_mail->Host = 'smtp.gmail.com';
$client_mail->SMTPAuth = true;
$client_mail->Username = 'sales@shivaminds.com';
$client_mail->Password = 'ggez jsph xoxl ggmo';
$client_mail->SMTPSecure = 'ssl';
$client_mail->Port = 465;

$client_mail->setFrom('sales@shivaminds.com', 'Shivam industries');
$client_mail->addAddress($email);
$client_mail->isHTML(true);
$client_mail->Subject = 'Thank You for Contacting Shivam industries (' . $currentDateTime . ')';
$client_mail->Body = $client_htmlbody;

$mailSent = true;
if (!$client_mail->send()) {
    // Log error if needed: error_log($client_mail->ErrorInfo);
    header('Location: failed.php');
    exit;
}

// header('Location: index.php');



// Save to CRM (CSV file)
$crmFile = 'crm_quotes.csv';
$fileExists = file_exists($crmFile);

// Prepare CRM data
$crmData = [
    date('Y-m-d H:i:s'),
    $firstname,
    $lastname,
    $email,
    $phone,
    $company,
    $address,
    $product,
    $quantity,
    $product_image
];

// Open file for appending
$file = fopen($crmFile, 'a');
if ($file) {
    // Add header if file is new
    if (!$fileExists) {
        fputcsv($file, ['Date', 'First Name', 'Last Name', 'Email', 'Phone', 'Company', 'Address', 'Product', 'Quantity', 'Product Image']);
    }

    // Add data
    fputcsv($file, $crmData);
    fclose($file);
    $crmSaved = true;
} else {
    $crmSaved = false;
}

// Clear output buffer and return response
ob_clean();

// Return response
// if ($mailSent && $crmSaved) {
//     // send success response to get-a-quote.php

//     echo json_encode([
//         'status' => 'success',
//         'message' => 'Quote request submitted successfully! We will contact you shortly.'
//     ]);
// } elseif ($mailSent) {
//     echo json_encode([
//         'status' => 'success',
//         'message' => 'Quote request submitted successfully! (CRM save failed)'
//     ]);
// } elseif ($crmSaved) {
//     echo json_encode([
//         'status' => 'success',
//         'message' => 'Quote saved to CRM! (Email send failed - please check SMTP settings)'
//     ]);
// } else {
//     sendErrorResponse('Failed to send quote request. Please try again.');
// }


if ($mailSent && $crmSaved) {

    // Redirect to success page with message
    header("Location: get-a-quote.php?status=success&msg=" . urlencode("Quote request submitted successfully! We will contact you shortly."));
    exit;
} elseif ($mailSent) {

    header("Location: get-a-quote.php?status=partial&msg=" . urlencode("Quote request submitted successfully! (CRM save failed)"));
    exit;
} elseif ($crmSaved) {

    header("Location: get-a-quote.php?status=partial&msg=" . urlencode("Quote saved to CRM! (Email send failed - please check SMTP settings)"));
    exit;
} else {

    header("Location: get-a-quote.php?status=error&msg=" . urlencode("Failed to send quote request. Please try again."));
    exit;
}

ob_end_flush();
