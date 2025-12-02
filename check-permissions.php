<?php
echo "<h2>System Check</h2>";

echo "<h3>1. PHP Version</h3>";
echo "PHP Version: " . phpversion() . "<br>";

echo "<h3>2. POST Method Test</h3>";
echo "Request Method: " . $_SERVER['REQUEST_METHOD'] . "<br>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<strong style='color:green'>✓ POST request received!</strong><br>";
    echo "POST Data: <pre>" . print_r($_POST, true) . "</pre>";
} else {
    echo "<strong style='color:orange'>⚠ GET request (this is normal when opening page directly)</strong><br>";
}

echo "<h3>3. File Write Permissions</h3>";
$testFile = 'test_write.txt';
if (file_put_contents($testFile, 'Test write: ' . date('Y-m-d H:i:s'))) {
    echo "<strong style='color:green'>✓ Can write files</strong><br>";
    echo "Test file created: $testFile<br>";
    unlink($testFile);
} else {
    echo "<strong style='color:red'>✗ Cannot write files - check permissions</strong><br>";
}

echo "<h3>4. CSV Test</h3>";
$csvFile = 'test_csv.csv';
$file = fopen($csvFile, 'w');
if ($file) {
    fputcsv($file, ['Name', 'Email', 'Phone']);
    fputcsv($file, ['Test User', 'test@example.com', '1234567890']);
    fclose($file);
    echo "<strong style='color:green'>✓ CSV file created successfully</strong><br>";
    echo "File: $csvFile<br>";
    echo "Content:<br><pre>" . file_get_contents($csvFile) . "</pre>";
    unlink($csvFile);
} else {
    echo "<strong style='color:red'>✗ Cannot create CSV file</strong><br>";
}

echo "<h3>5. Current Directory</h3>";
echo "Current Dir: " . getcwd() . "<br>";
echo "Script: " . __FILE__ . "<br>";

echo "<h3>6. Files in Directory</h3>";
$files = glob('*.php');
echo "PHP Files: " . count($files) . "<br>";
foreach ($files as $file) {
    echo "- $file<br>";
}

echo "<h3>7. Test AJAX POST</h3>";
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<button onclick="testPost()">Test POST Request</button>
<div id="postResult"></div>

<script>
function testPost() {
    $.ajax({
        type: 'POST',
        url: 'check-permissions.php',
        data: { test: 'data', name: 'Test User' },
        success: function(response) {
            $('#postResult').html('<strong style="color:green">✓ POST request successful!</strong><br>' + response);
        },
        error: function(xhr, status, error) {
            $('#postResult').html('<strong style="color:red">✗ POST request failed!</strong><br>' + error);
        }
    });
}
</script>

