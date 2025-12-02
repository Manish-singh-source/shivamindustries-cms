<!DOCTYPE html>
<html>
<head>
    <title>Test Quote Form</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h2>Test Quote Form</h2>
    
    <div id="result"></div>
    
    <form id="testform">
        <input type="text" name="firstname" placeholder="First Name" required><br><br>
        <input type="text" name="lastname" placeholder="Last Name" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="text" name="phone" placeholder="Phone" required><br><br>
        <input type="text" name="company" placeholder="Company"><br><br>
        <input type="text" name="address" placeholder="Address"><br><br>
        <input type="text" name="product" placeholder="Product" value="Test Product" required><br><br>
        <input type="text" name="quantity" placeholder="Quantity" required><br><br>
        <input type="hidden" name="product_image" value="img/test.png"><br><br>
        <button type="submit">Submit</button>
    </form>

    <script>
        $(document).ready(function() {
            $('#testform').on('submit', function(e) {
                e.preventDefault();
                
                var formData = $(this).serialize();
                
                $('#result').html('Sending...');
                
                $.ajax({
                    type: 'POST',
                    url: 'quote-handler-simple.php',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        $('#result').html('<pre>' + JSON.stringify(response, null, 2) + '</pre>');
                    },
                    error: function(xhr, status, error) {
                        $('#result').html('Error: ' + error + '<br>Response: ' + xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>

