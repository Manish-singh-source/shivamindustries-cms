<?php include 'header.php';?>
<?php include 'navbar.php';?>

<!-- Slider -->
<div class="slider-style-3 slider-default mt_10 mb_20">
    <div dir="ltr" class="swiper tf-swiper slider-effect-fade" data-swiper='{
                "slidesPerView": 1,
                "spaceBetween": 0,
                "loop": true,
                "autoplay": { 
                    "delay": 7000,
                    "disableOnInteraction": false,
                    "pauseOnMouseEnter": true
                },
                "centeredSlides": true,
                "speed": 800,
                "pagination": { "el": ".sw-pagination-slider", "clickable": true },
                "breakpoints": { 
                    "768": { "slidesPerView": 1.32, "spaceBetween": 24},
                    "1200": { "slidesPerView": 1.32, "spaceBetween": 40}
                }
            }'>
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <div class="slider-wrap">
                    <div class="image">
                        <img src="images/slider/handcraft/slider-1.jpg" data-src="images/slider/handcraft/slider-1.jpg"
                            alt="slider" class="lazyload">
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="slider-wrap ">
                    <div class="image">
                        <img src="images/slider/handcraft/slider-2.jpg" data-src="images/slider/handcraft/slider-2.jpg"
                            alt="slider" class="lazyload">
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="slider-wrap ">
                    <div class="image">
                        <img src="images/slider/handcraft/slider-3.jpg" data-src="images/slider/handcraft/slider-3.jpg"
                            alt="slider" class="lazyload">
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="wrap-pagination mb_20">
        <div class="container">
            <div class="d-flex sw-dots style-dark sw-pagination-slider justify-content-center"></div>
        </div>
    </div>
</div>
<!-- /Slider -->

<?php include 'footer.php';?>