<?php
// Set India timezone
date_default_timezone_set('Asia/Kolkata');

// Handle Delete Action
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];
    $crmFile = '../crm_quotes.csv';

    if (file_exists($crmFile)) {
        $allRows = [];
        $file = fopen($crmFile, 'r');
        if ($file) {
            $header = fgetcsv($file);
            $allRows[] = $header;
            $currentId = 1;
            while (($row = fgetcsv($file)) !== FALSE) {
                if ($currentId != $deleteId) {
                    $allRows[] = $row;
                }
                $currentId++;
            }
            fclose($file);

            // Write back to CSV
            $file = fopen($crmFile, 'w');
            foreach ($allRows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }
    }
    header('Location: enquiries.php?deleted=1');
    exit;
}

include 'header.php';

// Read enquiries from CSV file
$enquiries = [];
$crmFile = '../crm_quotes.csv';

if (file_exists($crmFile)) {
    $file = fopen($crmFile, 'r');
    if ($file) {
        // Skip header row
        $header = fgetcsv($file);
        $id = 1;
        while (($row = fgetcsv($file)) !== FALSE) {
            if (count($row) >= 9) {
                $enquiries[] = [
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
                $id++;
            }
        }
        fclose($file);
    }
}

// Reverse to show latest first
$enquiries = array_reverse($enquiries);
$totalEnquiries = count($enquiries);

// Pagination settings
$itemsPerPage = 15;
$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$totalPages = ceil($totalEnquiries / $itemsPerPage);

// Ensure current page is within bounds
if ($currentPage < 1) $currentPage = 1;
if ($currentPage > $totalPages && $totalPages > 0) $currentPage = $totalPages;

// Get items for current page
$offset = ($currentPage - 1) * $itemsPerPage;
$paginatedEnquiries = array_slice($enquiries, $offset, $itemsPerPage);

// Calculate showing range
$showingFrom = $totalEnquiries > 0 ? $offset + 1 : 0;
$showingTo = min($offset + $itemsPerPage, $totalEnquiries);
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
                        <li class=" active" aria-current="page">Total Enquiries</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>Enquiry deleted successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>Enquiry updated successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="product-count d-flex align-items-center gap-3 gap-lg-4 mb-4 fw-medium flex-wrap font-text1">
            <a href="javascript:;" class="active"><span class="me-1">All Enquiries</span><span class="text-secondary">(<?php echo $totalEnquiries; ?>)</span></a>
        </div>

        <div class="row g-3">
            <div class="col-auto">
                <div class="position-relative">
                    <input class="form-control px-5" type="search" placeholder="Search Enquiries" id="searchEnquiry">
                    <span class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50 fs-5">search</span>
                </div>
            </div>
            <div class="col-auto flex-grow-1 overflow-auto"></div>
            <div class="col-auto">
                <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                    <a href="../crm_quotes.csv" download class="btn btn-filter px-4"><i class="bi bi-box-arrow-right me-2"></i>Export CSV</a>
                </div>
            </div>
        </div><!--end row-->

        <div class="card mt-4">
            <div class="card-body">
                <div class="customer-table">
                    <div class="table-responsive white-space-nowrap">
                        <table class="table align-middle" id="enquiriesTable">
                            <thead class="table-light">
                                <tr>
                                    <th><input class="form-check-input" type="checkbox" id="selectAll"></th>
                                    <th>#ID</th>
                                    <th>Customer</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($paginatedEnquiries)): ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        No enquiries found
                                    </td>
                                </tr>
                                <?php else: ?>
                                <?php foreach ($paginatedEnquiries as $enquiry): ?>
                                <tr>
                                    <td><input class="form-check-input item-checkbox" type="checkbox" value="<?php echo $enquiry['id']; ?>"></td>
                                    <td><a href="enquiry-details.php?id=<?php echo $enquiry['id']; ?>">#<?php echo $enquiry['id']; ?></a></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="customer-pic">
                                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white" style="width:40px;height:40px;">
                                                    <?php echo strtoupper(substr($enquiry['first_name'], 0, 1)); ?>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="mb-0 customer-name fw-bold"><?php echo htmlspecialchars($enquiry['first_name'] . ' ' . $enquiry['last_name']); ?></p>
                                                <small class="text-muted"><?php echo htmlspecialchars($enquiry['company']); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><a href="mailto:<?php echo htmlspecialchars($enquiry['email']); ?>"><?php echo htmlspecialchars($enquiry['email']); ?></a></td>
                                    <td><a href="tel:<?php echo htmlspecialchars($enquiry['phone']); ?>"><?php echo htmlspecialchars($enquiry['phone']); ?></a></td>
                                    <td>
                                        <span class="badge bg-info text-dark"><?php echo htmlspecialchars($enquiry['product']); ?></span>
                                    </td>
                                    <td><span class="fw-bold"><?php echo htmlspecialchars($enquiry['quantity']); ?></span></td>
                                    <td><?php echo date('M d, Y h:i A', strtotime($enquiry['date'])); ?></td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="enquiry-details.php?id=<?php echo $enquiry['id']; ?>" class="btn btn-sm btn-info" title="View">
                                                <i class="fadeIn animated bx bx-show"></i>
                                            </a>
                                            <a href="enquiry-edit.php?id=<?php echo $enquiry['id']; ?>" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fadeIn animated bx bx-edit"></i>
                                            </a>
                                            <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $enquiry['id']; ?>)" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fadeIn animated bx bx-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                <div class="d-flex align-items-center justify-content-between mt-4">
                    <div class="text-muted">
                        Showing <strong><?php echo $showingFrom; ?></strong> to <strong><?php echo $showingTo; ?></strong> of <strong><?php echo $totalEnquiries; ?></strong> enquiries
                    </div>
                    <nav aria-label="Enquiries pagination">
                        <ul class="pagination mb-0">
                            <!-- Previous Button -->
                            <li class="page-item <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>

                            <?php
                            // Calculate range of pages to show
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($totalPages, $currentPage + 2);

                            // Always show first page
                            if ($startPage > 1): ?>
                                <li class="page-item"><a class="page-link" href="?page=1">1</a></li>
                                <?php if ($startPage > 2): ?>
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                <?php endif; ?>
                            <?php endif; ?>

                            <!-- Page Numbers -->
                            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <!-- Always show last page -->
                            <?php if ($endPage < $totalPages): ?>
                                <?php if ($endPage < $totalPages - 1): ?>
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                <?php endif; ?>
                                <li class="page-item"><a class="page-link" href="?page=<?php echo $totalPages; ?>"><?php echo $totalPages; ?></a></li>
                            <?php endif; ?>

                            <!-- Next Button -->
                            <li class="page-item <?php echo ($currentPage >= $totalPages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <?php endif; ?>

            </div>
        </div>

    </div>
</main>
<!--end main wrapper-->

<script>
// Search functionality
document.getElementById('searchEnquiry').addEventListener('keyup', function() {
    var searchValue = this.value.toLowerCase();
    var rows = document.querySelectorAll('#enquiriesTable tbody tr');

    rows.forEach(function(row) {
        var text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchValue) ? '' : 'none';
    });
});

// Select all checkbox
document.getElementById('selectAll').addEventListener('change', function() {
    var checkboxes = document.querySelectorAll('.item-checkbox');
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = this.checked;
    }.bind(this));
});

// Delete confirmation
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this enquiry? This action cannot be undone.')) {
        window.location.href = 'enquiries.php?delete=' + id;
    }
}
</script>


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

</body>
</html>