<?php
// Simple quote handler for debugging
header('Content-Type: application/json');

try {
    // Check if POST request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }
    
    // Get form data
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $email = $_POST['email'] ?? '';
    $company = $_POST['company'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $product = $_POST['product'] ?? '';
    $quantity = $_POST['quantity'] ?? '';
    $product_image = $_POST['product_image'] ?? '';
    
    // Validate
    if (empty($firstname) || empty($lastname) || empty($email) || empty($phone) || empty($product) || empty($quantity)) {
        throw new Exception('Please fill all required fields');
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address');
    }
    
    // Email details
    $to = 'support@technofra.com';
    $subject = 'New Quote Request - ' . $product;
    
    // Simple email body
    $message = "New Quote Request\n\n";
    $message .= "Customer Details:\n";
    $message .= "Name: {$firstname} {$lastname}\n";
    $message .= "Email: {$email}\n";
    $message .= "Phone: {$phone}\n";
    $message .= "Company: {$company}\n";
    $message .= "Address: {$address}\n\n";
    $message .= "Product Details:\n";
    $message .= "Product: {$product}\n";
    $message .= "Quantity: {$quantity}\n";
    $message .= "Image: {$product_image}\n\n";
    $message .= "Date: " . date('Y-m-d H:i:s');
    
    // Email headers
    $headers = "From: Shivam Industries <noreply@shivaminds.com>\r\n";
    $headers .= "Reply-To: {$email}\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Try to send email
    $mailSent = @mail($to, $subject, $message, $headers);
    
    // Save to CRM
    $crmFile = 'crm_quotes.csv';
    $fileExists = file_exists($crmFile);
    
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
    
    $crmSaved = false;
    $file = @fopen($crmFile, 'a');
    if ($file) {
        if (!$fileExists) {
            fputcsv($file, ['Date', 'First Name', 'Last Name', 'Email', 'Phone', 'Company', 'Address', 'Product', 'Quantity', 'Product Image']);
        }
        fputcsv($file, $crmData);
        fclose($file);
        $crmSaved = true;
    }
    
    // Response
    if ($crmSaved) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Quote request submitted successfully! We will contact you shortly.',
            'email_sent' => $mailSent,
            'crm_saved' => $crmSaved
        ]);
    } else {
        throw new Exception('Failed to save quote. Please check file permissions.');
    }
    
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>

