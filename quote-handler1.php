<?php

// Set India timezone FIRST before any date operations
date_default_timezone_set('Asia/Kolkata');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors in output
ini_set('log_errors', 1);

// Start output buffering to catch any errors
ob_start();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Redirect to quote page if accessed directly
    header('Location: get-a-quote.php');
    exit;
}

// Set response header for AJAX
header('Content-Type: application/json');

// Error handler function
function sendErrorResponse($message, $details = '') {
    ob_clean(); // Clear any output
    echo json_encode([
        'status' => 'error',
        'message' => $message,
        'details' => $details
    ]);
    exit;
}

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

// Validate required fields
if (empty($firstname)) {
    sendErrorResponse('First name is required');
}
if (empty($lastname)) {
    sendErrorResponse('Last name is required');
}
if (empty($email)) {
    sendErrorResponse('Email is required');
}
if (empty($phone)) {
    sendErrorResponse('Phone is required');
}
if (empty($product)) {
    sendErrorResponse('Product name is required');
}
if (empty($quantity)) {
    sendErrorResponse('Quantity is required');
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendErrorResponse('Invalid email address');
}

// Gmail SMTP Configuration
$smtp_host = 'smtp.gmail.com';
$smtp_port = 587;
$smtp_username = 'support@technofra.com';
$smtp_password = 'kcdi vqko dwgv yaku'; // App password
$smtp_from = 'support@technofra.com';
$smtp_from_name = 'Shivam Industries';

// Email configuration
$to = 'support@technofra.com';
$subject = 'New Quote Request - ' . $product;

// Create email body
$emailBody = "
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #0D0D0D; color: white; padding: 20px; text-align: center; }
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
            <h2>New Quote Request</h2>
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
            " . (!empty($product_image) ? "<div class='field'><img src='https://{$_SERVER['HTTP_HOST']}/{$product_image}' alt='{$product}' class='product-image'></div>" : "") . "
        </div>
        <div class='footer'>
            <p>This quote request was submitted from Shivam Industries website</p>
            <p>Date: " . date('Y-m-d H:i:s') . "</p>
        </div>
    </div>
</body>
</html>
";

// Function to send email via SMTP
function sendSMTPEmail($to, $subject, $body, $from, $from_name, $reply_to, $smtp_host, $smtp_port, $smtp_user, $smtp_pass) {
    $headers = "From: {$from_name} <{$from}>\r\n";
    $headers .= "Reply-To: {$reply_to}\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    // For Gmail SMTP, we'll use PHP's mail() with proper configuration
    // In production, you should use PHPMailer or similar library
    // For now, using mail() with ini_set for SMTP

    ini_set('SMTP', $smtp_host);
    ini_set('smtp_port', $smtp_port);
    ini_set('sendmail_from', $from);
    
    $mailSent = mail($to, $subject, $body, $headers);

    return print_r($mailSent);
}

// Send email
$mailSent = sendSMTPEmail(
    $to,
    $subject,
    $emailBody,
    $smtp_from,
    $smtp_from_name,
    $email,
    $smtp_host,
    $smtp_port,
    $smtp_username,
    $smtp_password
);

print_r($mailSent->ErrorInfo);
exit();

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
if ($mailSent && $crmSaved) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Quote request submitted successfully! We will contact you shortly.'
    ]);
} elseif ($mailSent) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Quote request submitted successfully! (CRM save failed)'
    ]);
} elseif ($crmSaved) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Quote saved to CRM! (Email send failed - please check SMTP settings)'
    ]);
} else {
    sendErrorResponse('Failed to send quote request. Please try again.');
}

ob_end_flush();
?>

