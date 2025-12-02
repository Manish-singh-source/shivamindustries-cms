<?php
include 'header.php';

// Set India timezone for correct time display
date_default_timezone_set('Asia/Kolkata');



// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Get enquiry ID from URL
$enquiryId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$enquiry = null;

// Read enquiries from CSV file
$crmFile = '../crm_quotes.csv';

if (file_exists($crmFile) && $enquiryId > 0) {
    $file = fopen($crmFile, 'r');
    if ($file) {
        // Skip header row
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
                        <li class=" "><a href="enquiries.php">Enquiries</a></li>
                        <li class="active" aria-current="page">- Enquiry Details</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="enquiries.php" class="btn btn-outline-primary"><i class="bi bi-arrow-left me-2"></i>Back to Enquiries</a>
            </div>
        </div>
        <!--end breadcrumb-->
        

        <?php if (!$enquiry): ?>
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle me-2"></i>Enquiry not found or invalid ID.
        </div>
        <?php else: ?>

        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-lg-row flex-column align-items-start align-items-lg-center justify-content-between gap-3">
                    <div class="flex-grow-1">
                        <h4 class="fw-bold">Enquiry #<?php echo $enquiry['id']; ?></h4>
                        <p class="mb-0">Received on: <strong><?php echo date('M d, Y h:i A', strtotime($enquiry['date'])); ?></strong></p>
                    </div>
                    <div class="overflow-auto">
                        <!-- <a href="mailto:<?php echo htmlspecialchars($enquiry['email']); ?>" class="btn btn-primary">
                            <i class="bi bi-envelope me-2"></i>Reply via Email
                        </a> -->
                        <a href="tel:<?php echo htmlspecialchars($enquiry['phone']); ?>" class="btn btn-success">
                            <i class="bi bi-telephone me-2"></i>Call Customer
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-8 d-flex">
                <div class="card w-100">
                    <div class="card-body">
                        <h5 class="mb-3 fw-bold">Product Details</h5>
                        <div class="d-flex align-items-center gap-4 mb-4">
                            <?php if (!empty($enquiry['product_image'])): ?>
                            <div class="product-box">
                                <img src="../<?php echo htmlspecialchars($enquiry['product_image']); ?>" width="120" class="rounded-3" alt="<?php echo htmlspecialchars($enquiry['product']); ?>">
                            </div>
                            <?php endif; ?>
                            <div class="product-info">
                                <h4 class="fw-bold text-primary"><?php echo htmlspecialchars($enquiry['product']); ?></h4>
                                <p class="mb-0 fs-5">Quantity Requested: <strong><?php echo htmlspecialchars($enquiry['quantity']); ?></strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4 d-flex">
                <div class="card w-100">
                    <div class="card-body">
                        <h4 class="card-title mb-4 fw-bold">Quick Actions</h4>
                        <div class="d-grid gap-2">
                            <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#sendQuoteModal">
                                <i class="bi bi-envelope me-2"></i>Send Quote
                            </button> -->
                            <a href="tel:<?php echo htmlspecialchars($enquiry['phone']); ?>" class="btn btn-success">
                                <i class="bi bi-telephone me-2"></i>Call Customer
                            </a>
                            <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $enquiry['phone']); ?>?text=Hello <?php echo urlencode($enquiry['first_name']); ?>, regarding your quote request for <?php echo urlencode($enquiry['product']); ?> we have prepared a quote for you. Please find the details below:" class="btn btn-outline-success" target="_blank">
                                <i class="bi bi-whatsapp me-2"></i>WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->

        <h5 class="fw-bold mb-4">Customer Details</h5>
        <div class="card">
            <div class="card-body">
                <div class="row g-3 row-cols-1 row-cols-lg-4">
                    <div class="col">
                        <div class="d-flex align-items-start gap-3 border p-3 rounded">
                            <div class="detail-icon fs-5">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            <div class="detail-info">
                                <p class="fw-bold mb-1">Customer Name</p>
                                <p class="mb-0"><?php echo htmlspecialchars($enquiry['first_name'] . ' ' . $enquiry['last_name']); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="d-flex align-items-start gap-3 border p-3 rounded">
                            <div class="detail-icon fs-5">
                                <i class="bi bi-building"></i>
                            </div>
                            <div class="detail-info">
                                <h6 class="fw-bold mb-1">Company</h6>
                                <p class="mb-0"><?php echo htmlspecialchars($enquiry['company'] ?: 'N/A'); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="d-flex align-items-start gap-3 border p-3 rounded">
                            <div class="detail-icon fs-5">
                                <i class="bi bi-envelope-fill"></i>
                            </div>
                            <div class="detail-info">
                                <h6 class="fw-bold mb-1">Email</h6>
                                <a href="mailto:<?php echo htmlspecialchars($enquiry['email']); ?>" class="mb-0"><?php echo htmlspecialchars($enquiry['email']); ?></a>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="d-flex align-items-start gap-3 border p-3 rounded">
                            <div class="detail-icon fs-5">
                                <i class="bi bi-telephone-fill"></i>
                            </div>
                            <div class="detail-info">
                                <h6 class="fw-bold mb-1">Phone</h6>
                                <a href="tel:<?php echo htmlspecialchars($enquiry['phone']); ?>" class="mb-0"><?php echo htmlspecialchars($enquiry['phone']); ?></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="d-flex align-items-start gap-3 border p-3 rounded">
                            <div class="detail-icon fs-5">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div class="detail-info">
                                <h6 class="fw-bold mb-1">Address</h6>
                                <p class="mb-0"><?php echo htmlspecialchars($enquiry['address'] ?: 'N/A'); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="d-flex align-items-start gap-3 border p-3 rounded">
                            <div class="detail-icon fs-5">
                                <i class="bi bi-calendar-check-fill"></i>
                            </div>
                            <div class="detail-info">
                                <h6 class="fw-bold mb-1">Enquiry Date</h6>
                                <p class="mb-0"><?php echo date('M d, Y h:i A', strtotime($enquiry['date'])); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end row-->
            </div>
        </div>

        <?php endif; ?>

    </div>
</main>
<!--end main wrapper-->

<!-- Send Quote Modal -->
<?php if ($enquiry): ?>
<div class="modal fade" id="sendQuoteModal" tabindex="-1" aria-labelledby="sendQuoteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendQuoteModalLabel"><i class="bi bi-envelope me-2"></i>Send Quote to <?php echo htmlspecialchars($enquiry['first_name']); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="sendQuoteForm">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>Fill in the quote details below. This will open your email client with the quote ready to send.
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Customer Name</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($enquiry['first_name'] . ' ' . $enquiry['last_name']); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="quoteEmail" value="<?php echo htmlspecialchars($enquiry['email']); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Product</label>
                            <input type="text" class="form-control" id="quoteProduct" value="<?php echo htmlspecialchars($enquiry['product']); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Requested Quantity</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($enquiry['quantity']); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Quote Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="text" class="form-control" id="quotePrice" placeholder="Enter price" required>
                                <select class="form-select" id="quotePriceUnit" style="max-width: 120px;">
                                    <option value="per kg">per kg</option>
                                    <option value="per litre">per litre</option>
                                    <option value="per unit">per unit</option>
                                    <option value="per ton">per ton</option>
                                    <option value="total">Total</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Validity</label>
                            <select class="form-select" id="quoteValidity">
                                <option value="7 days">7 Days</option>
                                <option value="15 days" selected>15 Days</option>
                                <option value="30 days">30 Days</option>
                                <option value="45 days">45 Days</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Delivery Time</label>
                            <select class="form-select" id="quoteDelivery">
                                <option value="Immediate">Immediate</option>
                                <option value="2-3 days">2-3 Days</option>
                                <option value="1 week" selected>1 Week</option>
                                <option value="2 weeks">2 Weeks</option>
                                <option value="3-4 weeks">3-4 Weeks</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Payment Terms</label>
                            <select class="form-select" id="quotePayment">
                                <option value="100% Advance">100% Advance</option>
                                <option value="50% Advance, 50% on Delivery">50% Advance, 50% on Delivery</option>
                                <option value="Net 15 Days">Net 15 Days</option>
                                <option value="Net 30 Days">Net 30 Days</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Additional Notes</label>
                            <textarea class="form-control" id="quoteNotes" rows="3" placeholder="Any additional terms, conditions or notes..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="sendWhatsAppQuote">
                        <i class="bi bi-whatsapp me-2"></i>Send via WhatsApp
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-envelope me-2"></i>Send via Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!--start overlay-->
<div class="overlay btn-toggle"></div>
<!--end overlay-->

<!--start footer-->
<footer class="page-footer">
    <p class="mb-0">Copyright © 2025. All right reserved.</p>
</footer>
<!--end footer-->

<!--bootstrap js-->
<script src="assets/js/bootstrap.bundle.min.js"></script>
<!--plugins-->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
<script src="assets/plugins/metismenu/metisMenu.min.js"></script>
<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
<script src="assets/js/main.js"></script>

<?php if ($enquiry): ?>
<script>
// Send Quote Form Handler
document.getElementById('sendQuoteForm').addEventListener('submit', function(e) {
    e.preventDefault();

    var email = document.getElementById('quoteEmail').value;
    var product = document.getElementById('quoteProduct').value;
    var price = document.getElementById('quotePrice').value;
    var priceUnit = document.getElementById('quotePriceUnit').value;
    var validity = document.getElementById('quoteValidity').value;
    var delivery = document.getElementById('quoteDelivery').value;
    var payment = document.getElementById('quotePayment').value;
    var notes = document.getElementById('quoteNotes').value;

    if (!price) {
        alert('Please enter the quote price');
        return;
    }

    var subject = 'Quote for ' + product + ' - Shiva Industries';
    var body = 'Dear <?php echo htmlspecialchars($enquiry['first_name']); ?>,\n\n';
    body += 'Thank you for your enquiry. Please find below our quotation:\n\n';
    body += '━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n';
    body += 'QUOTATION DETAILS\n';
    body += '━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n';
    body += 'Product: ' + product + '\n';
    body += 'Quantity: <?php echo htmlspecialchars($enquiry['quantity']); ?>\n';
    body += 'Price: ₹' + price + ' ' + priceUnit + '\n';
    body += 'Delivery: ' + delivery + '\n';
    body += 'Payment Terms: ' + payment + '\n';
    body += 'Quote Validity: ' + validity + '\n';
    if (notes) {
        body += '\nAdditional Notes:\n' + notes + '\n';
    }
    body += '\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n';
    body += 'Please feel free to contact us for any queries.\n\n';
    body += 'Best Regards,\nShiva Industries\n';
    body += 'Phone: +91-XXXXXXXXXX\n';
    body += 'Email: info@shivaindustries.com';

    window.location.href = 'mailto:' + email + '?subject=' + encodeURIComponent(subject) + '&body=' + encodeURIComponent(body);
});

// WhatsApp Quote Handler
document.getElementById('sendWhatsAppQuote').addEventListener('click', function() {
    var price = document.getElementById('quotePrice').value;
    var priceUnit = document.getElementById('quotePriceUnit').value;
    var validity = document.getElementById('quoteValidity').value;
    var delivery = document.getElementById('quoteDelivery').value;
    var payment = document.getElementById('quotePayment').value;
    var notes = document.getElementById('quoteNotes').value;
    var product = document.getElementById('quoteProduct').value;

    if (!price) {
        alert('Please enter the quote price');
        return;
    }

    var phone = '<?php echo preg_replace('/[^0-9]/', '', $enquiry['phone']); ?>';
    var message = 'Dear <?php echo htmlspecialchars($enquiry['first_name']); ?>,\n\n';
    message += 'Thank you for your enquiry. Here is our quotation:\n\n';
    message += '📦 *Product:* ' + product + '\n';
    message += '📊 *Quantity:* <?php echo htmlspecialchars($enquiry['quantity']); ?>\n';
    message += '💰 *Price:* ₹' + price + ' ' + priceUnit + '\n';
    message += '🚚 *Delivery:* ' + delivery + '\n';
    message += '💳 *Payment:* ' + payment + '\n';
    message += '⏰ *Validity:* ' + validity + '\n';
    if (notes) {
        message += '\n📝 *Notes:* ' + notes + '\n';
    }
    message += '\nPlease let us know if you have any questions.\n\n';
    message += 'Best Regards,\n*Shiva Industries*';

    window.open('https://wa.me/' + phone + '?text=' + encodeURIComponent(message), '_blank');
});
</script>
<?php endif; ?>

</body>
</html>