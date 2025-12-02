<?php
include 'header.php';

// Set India timezone
date_default_timezone_set('Asia/Kolkata');

// Read enquiries from CSV file
$enquiries = [];
$todayEnquiries = 0;
$weekEnquiries = 0;
$crmFile = '../crm_quotes.csv';

$today = date('Y-m-d');
$weekStart = date('Y-m-d', strtotime('-7 days'));

if (file_exists($crmFile)) {
    $file = fopen($crmFile, 'r');
    if ($file) {
        $header = fgetcsv($file);
        while (($row = fgetcsv($file)) !== FALSE) {
            if (count($row) >= 9) {
                $enquiryDate = date('Y-m-d', strtotime($row[0]));
                $enquiries[] = $row;

                if ($enquiryDate == $today) {
                    $todayEnquiries++;
                }
                if ($enquiryDate >= $weekStart) {
                    $weekEnquiries++;
                }
            }
        }
        fclose($file);
    }
}

$totalEnquiries = count($enquiries);
$enquiries = array_reverse($enquiries); // Latest first
?>

  <!--start main wrapper-->
  <main class="main-wrapper">
    <div class="main-content">
      <!--breadcrumb-->
      <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Dashboard</div>
        <div class="ps-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
              </li>
              <li class=" active" aria-current="page">Enquiries</li>
            </ol>
          </nav>
        </div>
        <div class="ms-auto">
          <a href="enquiries.php" class="btn btn-primary"><i class="bi bi-list-ul me-2"></i>View All Enquiries</a>
        </div>
      </div>
      <!--end breadcrumb-->


      <div class="row">
        
        <div class="col-12 col-lg-4 col-xxl-4 d-flex">
          <div class="card rounded-4 w-100">
            <div class="card-body">
              <div class="mb-3 d-flex align-items-center justify-content-between">
                <div
                  class="wh-42 d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary">
                  <span class="material-icons-outlined fs-5">shopping_cart</span>
                </div>
                <div>
                  <span class="text-success d-flex align-items-center">+24%<i
                      class="material-icons-outlined">expand_less</i></span>
                </div>
              </div>
              <div>
                <h4 class="mb-0"><?php echo $totalEnquiries; ?></h4>
                <p class="mb-3">Total Enquiries</p>
                <div id="chart1"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-lg-4 col-xxl-4 d-flex">
          <div class="card rounded-4 w-100">
            <div class="card-body">
              <div class="mb-3 d-flex align-items-center justify-content-between">
                <div
                  class="wh-42 d-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 text-success">
                  <span class="material-icons-outlined fs-5">attach_money</span>
                </div>
                <div>
                  <span class="text-success d-flex align-items-center">+14%<i
                      class="material-icons-outlined">expand_less</i></span>
                </div>
              </div>
              <div>
                <h4 class="mb-0"><?php echo $todayEnquiries; ?></h4>
                <p class="mb-3">Today’s Enquiries</p>
                <div id="chart2"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-lg-6 col-xxl-4 d-flex">
          <div class="card rounded-4 w-100">
            <div class="card-body">
              <div class="mb-3 d-flex align-items-center justify-content-between">
                <div
                  class="wh-42 d-flex align-items-center justify-content-center rounded-circle bg-info bg-opacity-10 text-info">
                  <span class="material-icons-outlined fs-5">visibility</span>
                </div>
                <div>
                  <span class="text-danger d-flex align-items-center">-35%<i
                      class="material-icons-outlined">expand_less</i></span>
                </div>
              </div>
              <div>
                <h4 class="mb-0"><?php echo $weekEnquiries; ?></h4>
                <p class="mb-3">This Week's Enquiries (Last 7 Days)</p>
                <div id="chart3"></div>
              </div>
            </div>
          </div>
        </div>       

      </div><!--end row-->


      <div class="row mt-4">
        <div class="col-12 d-flex">
          <div class="card rounded-4 w-100">
            <div class="card-body">
              <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="">
                  <h5 class="mb-0">Recent Enquiries</h5>
                </div>
                <div>
                  <a href="enquiries.php" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table align-middle mb-0 table-striped table-hover">
                  <thead class="table-light">
                    <tr>
                      <th>Date & Time</th>
                      <th>Customer</th>
                      <th>Product</th>
                      <th>Quantity</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $recentEnquiries = array_slice($enquiries, 0, 5); // Show only 5 recent
                    if (empty($recentEnquiries)): ?>
                    <tr>
                      <td colspan="5" class="text-center text-muted py-4">No enquiries found</td>
                    </tr>
                    <?php else:
                    $id = $totalEnquiries;
                    foreach ($recentEnquiries as $row):
                    ?>
                    <tr>
                      <td>
                        <div class="">
                          <h6 class="mb-0"><?php echo date('d M, Y', strtotime($row[0])); ?></h6>
                          <p class="mb-0 text-muted"><?php echo date('h:i A', strtotime($row[0])); ?></p>
                        </div>
                      </td>
                      <td>
                        <div class="">
                          <h6 class="mb-0"><?php echo htmlspecialchars($row[1] . ' ' . $row[2]); ?></h6>
                          <p class="mb-0 text-muted"><?php echo htmlspecialchars($row[3]); ?></p>
                        </div>
                      </td>
                      <td>
                        <span class="badge bg-info text-dark"><?php echo htmlspecialchars($row[7]); ?></span>
                      </td>
                      <td>
                        <span class="fw-bold"><?php echo htmlspecialchars($row[8]); ?></span>
                      </td>
                      <td>
                        <a href="enquiry-details.php?id=<?php echo $id; ?>" class="btn btn-sm btn-info" title="View">
                          <i class="fadeIn animated bx bx-show"></i>
                        </a>
                      </td>
                    </tr>
                    <?php $id--; endforeach; endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div><!--end row-->

    </div>
  </main>
  <!--end main wrapper-->


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
  <!--plugins-->
  <script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
  <script src="assets/plugins/metismenu/metisMenu.min.js"></script>
  <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
  <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
  <script src="assets/js/dashboard2.js"></script>
  <script src="assets/js/main.js"></script>


</body>
</html>