<!DOCTYPE html>
<html>

<head>
    <title>Direct Test - Quote Handler</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .success {
            color: green;
            background: #d4edda;
            padding: 15px;
            border-radius: 5px;
        }

        .error {
            color: red;
            background: #f8d7da;
            padding: 15px;
            border-radius: 5px;
        }

        form {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 5px;
            max-width: 500px;
        }

        input,
        button {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
        }

        button {
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }
    </style>
</head>

<body>
    <h2>Direct Form Test (No AJAX)</h2>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Process the form directly here
        $firstname = $_POST['firstname'] ?? '';
        $lastname = $_POST['lastname'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $company = $_POST['company'] ?? '';
        $address = $_POST['address'] ?? '';
        $product = $_POST['product'] ?? '';
        $quantity = $_POST['quantity'] ?? '';
        $product_image = $_POST['product_image'] ?? '';

        // Validate
        if (empty($firstname) || empty($lastname) || empty($email) || empty($phone) || empty($product) || empty($quantity)) {
            echo '<div class="error">Please fill all required fields</div>';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo '<div class="error">Invalid email address</div>';
        } else {
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

            $file = @fopen($crmFile, 'a');
            if ($file) {
                if (!$fileExists) {
                    fputcsv($file, ['Date', 'First Name', 'Last Name', 'Email', 'Phone', 'Company', 'Address', 'Product', 'Quantity', 'Product Image']);
                }
                fputcsv($file, $crmData);
                fclose($file);

                echo '<div class="success">';
                echo '<h3>✓ Success! Quote saved to CRM</h3>';
                echo '<p><strong>Name:</strong> ' . htmlspecialchars($firstname . ' ' . $lastname) . '</p>';
                echo '<p><strong>Email:</strong> ' . htmlspecialchars($email) . '</p>';
                echo '<p><strong>Phone:</strong> ' . htmlspecialchars($phone) . '</p>';
                echo '<p><strong>Product:</strong> ' . htmlspecialchars($product) . '</p>';
                echo '<p><strong>Quantity:</strong> ' . htmlspecialchars($quantity) . '</p>';
                echo '<p><strong>CRM File:</strong> crm_quotes.csv</p>';
                echo '</div>';

                // Try to send email
                $to = 'support@technofra.com';
                $subject = 'New Quote Request - ' . $product;
                $message = "New Quote Request\n\n";
                $message .= "Name: {$firstname} {$lastname}\n";
                $message .= "Email: {$email}\n";
                $message .= "Phone: {$phone}\n";
                $message .= "Company: {$company}\n";
                $message .= "Product: {$product}\n";
                $message .= "Quantity: {$quantity}\n";

                $headers = "From: noreply@shivaminds.com\r\n";
                $headers .= "Reply-To: {$email}\r\n";

                $mailSent = @mail($to, $subject, $message, $headers);

                if ($mailSent) {
                    echo '<div class="success">✓ Email sent successfully!</div>';
                } else {
                    echo '<div class="error">⚠ Email not sent (SMTP not configured in XAMPP)</div>';
                }
            } else {
                echo '<div class="error">Failed to save to CRM. Check file permissions.</div>';
            }
        }
    }
    ?>

    <form method="POST" action="">
        <h3>Fill Quote Form</h3>
        <input type="text" name="firstname" placeholder="First Name *" required>
        <input type="text" name="lastname" placeholder="Last Name *" required>
        <input type="email" name="email" placeholder="Email *" required>
        <input type="text" name="phone" placeholder="Phone *" required>
        <input type="text" name="company" placeholder="Company">
        <input type="text" name="address" placeholder="Address">
        <input type="text" name="product" placeholder="Product *" value="Crotonaldehyde" required>
        <input type="text" name="quantity" placeholder="Quantity *" required>
        <input type="hidden" name="product_image" value="img/products/crotonaldehyde.png">
        <button type="submit">Submit Quote</button>
    </form>

    <hr>
    <p><strong>Note:</strong> This is a direct form submission test (no AJAX). It will save data to crm_quotes.csv file.</p>
</body>

</html>