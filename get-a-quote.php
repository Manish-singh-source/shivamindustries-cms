<?php include 'header.php'; ?>
<?php include 'navbar.php'; ?>

<?php
// Get product details from URL parameters
$productName = isset($_GET['product']) ? htmlspecialchars($_GET['product']) : '';
$productImage = isset($_GET['image']) ? htmlspecialchars($_GET['image']) : 'img/getaquotes.jpg';

$status = isset($_GET['status']) ? $_GET['status'] : 'unknown';
$message = isset($_GET['msg']) ? urldecode($_GET['msg']) : 'Something went wrong.';
?>

<!-- Title Page -->
<section class="tf-page-title">
    <div class="container">
        <div class="box-title text-center">
            <h4 class="title">Get a Quote</h4>

        </div>
    </div>
</section>
<!-- /Title Page -->
<!-- Cart Section -->
<div class="flat-spacing-13">
    <div class="container">
        <div class="row">
            <div class="col-xl-8">
                <!-- error response -->
                <?php if ($status === 'error'): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i><?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>
                <!-- success response -->
                <?php if ($status === 'success'): ?>
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-2"></i><?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>
                <form class="tf-checkout-cart-main" method="POST" action="quote-handler.php">
                    <div class="box-ip-checkout">
                        <div class="title text-lg fw-medium">Get a Quote</div>
                        <div class="grid-2 mb_16">
                            <div class="tf-field style-2 style-3">
                                <input class="tf-field-input tf-input" id="firstname" placeholder=" " type="text"
                                    name="firstname" required>
                                <label class="tf-field-label" for="firstname">First name</label>
                            </div>
                            <div class="tf-field style-2 style-3">
                                <input class="tf-field-input tf-input" id="lastname" placeholder=" " type="text"
                                    name="lastname" required>
                                <label class="tf-field-label" for="lastname">Last name</label>
                            </div>
                        </div>
                        <fieldset class="tf-field style-2 style-3 mb_16">
                            <input class="tf-field-input tf-input" id="email" type="email" name="email"
                                placeholder=" " required>
                            <label class="tf-field-label" for="email">Email</label>
                        </fieldset>
                        <fieldset class="tf-field style-2 style-3 mb_16">
                            <input class="tf-field-input tf-input" id="company" type="text" name="company"
                                placeholder=" ">
                            <label class="tf-field-label" for="company">Company Name</label>
                        </fieldset>
                        <fieldset class="tf-field style-2 style-3 mb_16">
                            <input class="tf-field-input tf-input" id="phone" type="text" name="phone" placeholder="" required>
                            <label class="tf-field-label" for="phone">Phone</label>
                        </fieldset>
                        <fieldset class="tf-field style-2 style-3 mb_16">
                            <input class="tf-field-input tf-input" id="address" type="text" name="address"
                                placeholder="">
                            <label class="tf-field-label" for="address">Address</label>
                        </fieldset>
                        <fieldset class="tf-field style-2 style-3 mb_16">
                            <input class="tf-field-input tf-input" id="product" type="text" name="product"
                                placeholder=" " value="<?php echo $productName; ?>" required>
                            <label class="tf-field-label" for="product">Product Name</label>
                        </fieldset>
                        <fieldset class="tf-field style-2 style-3 mb_16">
                            <input class="tf-field-input tf-input" id="quantity" type="text" name="quantity"
                                placeholder="" required>
                            <label class="tf-field-label" for="quantity">Quantity</label>
                        </fieldset>
                        <input type="hidden" name="product_image" id="product_image" value="<?php echo $productImage; ?>">
                        <input type="text" name="hidden_field" style="display:none;" tabindex="-1">
                        <div class="g-recaptcha col mb-3" data-sitekey="6Le_1BssAAAAAKasNnmhNaLSEgbiVP6Rzn4ONrH_">
                        </div>
                        <div class="btn-order">
                            <button type="submit" class="tf-btn btn-dark2 animate-btn w-100 text-transform-none">Submit Quote Request</button>
                        </div>

                    </div>

                </form>
            </div>
            <div class="col-xl-4">
                <?php if ($productName): ?>
                    <div class="mb-3">
                        <h5 class="fw-medium mb-3">Product Details</h5>
                        <div class="product-quote-card" style="border: 1px solid #e5e5e5; border-radius: 10px; padding: 20px; background: #fff;">
                            <img src="<?php echo $productImage; ?>" alt="<?php echo $productName; ?>" style="border-radius:10px; width: 100%; margin-bottom: 15px;">
                            <h6 class="fw-medium text-center"><?php echo $productName; ?></h6>
                        </div>
                    </div>
                <?php else: ?>
                    <img src="img\getaquotes.jpg" alt="" style="border-radius:10px">
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- /Cart Section -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<!-- Footer -->
<?php include 'footer.php'; ?>