<?php
// Set India timezone
date_default_timezone_set('Asia/Kolkata');

// Get enquiry ID from URL
$enquiryId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$enquiry = null;
$allRows = [];
$crmFile = '../crm_quotes.csv';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $updateId = (int)$_POST['id'];

    if (file_exists($crmFile)) {
        $file = fopen($crmFile, 'r');
        $header = fgetcsv($file);
        $allRows[] = $header;
        $currentId = 1;

        while (($row = fgetcsv($file)) !== FALSE) {
            if ($currentId == $updateId) {
                // Update the row with new data
                $row[1] = $_POST['first_name'];
                $row[2] = $_POST['last_name'];
                $row[3] = $_POST['email'];
                $row[4] = $_POST['phone'];
                $row[5] = $_POST['company'];
                $row[6] = $_POST['address'];
                $row[7] = $_POST['product'];
                $row[8] = $_POST['quantity'];
            }
            $allRows[] = $row;
            $currentId++;
        }
        fclose($file);

        // Write back to CSV
        $file = fopen($crmFile, 'w');
        foreach ($allRows as $row) {
            fputcsv($file, $row);
        }
        fclose($file);

        header('Location: enquiries.php?updated=1');
        exit;
    }
}

// Read enquiry data
if (file_exists($crmFile) && $enquiryId > 0) {
    $file = fopen($crmFile, 'r');
    if ($file) {
        $header = fgetcsv($file);
        $id = 1;
        while (($row = fgetcsv($file)) !== FALSE) {
            if (count($row) >= 9 && $id == $enquiryId) {
                $enquiry = [
                    'id' => $id,
                    'date' => $row[0] ?? '',
                    'first_name' => $row[1] ?? '',
                    'last_name' => $row[2] ?? '',
                    'email' => $row[3] ?? '',
                    'phone' => $row[4] ?? '',
                    'company' => $row[5] ?? '',
                    'address' => $row[6] ?? '',
                    'product' => $row[7] ?? '',
                    'quantity' => $row[8] ?? '',
                    'product_image' => $row[9] ?? ''
                ];
                break;
            }
            $id++;
        }
        fclose($file);
    }
}
?>

<?php include 'header.php'; ?>

<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">CMS</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="index.php"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item"><a href="enquiries.php">Enquiries</a></li>
                        <li class="breadcrumb-item active">Edit Enquiry</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="enquiries.php" class="btn btn-outline-primary"><i class="bi bi-arrow-left me-2"></i>Back</a>
            </div>
        </div>
        <!--end breadcrumb-->

        <?php if (!$enquiry): ?>
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle me-2"></i>Enquiry not found or invalid ID.
        </div>
        <?php else: ?>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 fw-bold">Edit Enquiry #<?php echo $enquiry['id']; ?></h5>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="<?php echo $enquiry['id']; ?>">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($enquiry['first_name']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($enquiry['last_name']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($enquiry['email']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($enquiry['phone']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company</label>
                            <input type="text" class="form-control" name="company" value="<?php echo htmlspecialchars($enquiry['company']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Product <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="product" value="<?php echo htmlspecialchars($enquiry['product']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="quantity" value="<?php echo htmlspecialchars($enquiry['quantity']); ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="3"><?php echo htmlspecialchars($enquiry['address']); ?></textarea>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" name="update" class="btn btn-primary"><i class="bi bi-check-lg me-2"></i>Update Enquiry</button>
                        <a href="enquiries.php" class="btn btn-secondary ms-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
        
        <?php endif; ?>

    </div>
</main>
<!--end main wrapper-->

<?php include 'footer.php'; ?>

