<!-- Begin footer section -->
<div align="center" id="footerads"></div>
<footer class="footer" id="footerSection">
    <div class="container">
        <div class="footer_content">
            <div class="row">
                <div class="col-lg-3 footer_col">
                    <div class="logo_container">
                        <div class="logo">
                            <img src="<?php echo $pdots; ?>assets/img/OHSC Logo.png" alt="footer-logo" style="width: 137px;">
                        </div>
                    </div>
                    <p class="footer_about_text gentxt_fbasic" style="color:#fff">
                        Business Box,<br />Oswin Road,<br />United Kingdom<br />
                        Email: info@oxfordhomestudy.com
                    </p>
                </div>
                <div class="col-lg-3 footer_col">
                    <div class="footer_column_title gentxt_fbasic">Menu</div>
                    <div class="footer_column_content">
                        <ul>
                            <li class="footer_list_item"><a href="<?php echo $base_url ?>" class="gentxt_fbasic">Home</a></li>
                            <li class="footer_list_item"><a href="<?php echo $base_url . '/aboutus' ?>" class="gentxt_fbasic">About Us</a></li>
                            <li class="footer_list_item"><a href="<?php echo $base_url . '/free-online-courses-with-certificates' ?>" class="gentxt_fbasic">Free Courses</a></li>
                            <li class="footer_list_item"><a href="
                                <?php echo $base_url . '/voucher-redemption.php'?>" class="gentxt_fbasic">Voucher Redemption</a></li>
                            <!-- <li class="footer_list_item"><a href="/distance-learning" class="gentxt_fbasic">How Distance Learning Works</a></li> -->
                            <li class="footer_list_item"><a href="<?php echo $base_url . '/how-to-pay' ?>" class="gentxt_fbasic">How to Pay</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 footer_col">
                    <div class="footer_column_title gentxt_fbasic">Usefull Links</div>
                    <div class="footer_column_content">
                        <ul>
                            <li class="footer_list_item"><a href="<?php echo $base_url . '/faq' ?>" class="gentxt_fbasic">FAQ</a></li>
                            <li class="footer_list_item"><a href="<?php echo $base_url . '/contact' ?>" class="gentxt_fbasic">Contact Us</a></li>
                            <li class="footer_list_item"><a href="https://www.stafftrainingsolutions.co.uk/" class="gentxt_fbasic">Staff Training Online</a></li>
                            <li class="footer_list_item"><a href="<?php echo $base_url . '/home-study-courses' ?>" class="gentxt_fbasic">Home Study Courses</a></li>
                            <li class="footer_list_item"><a href="<?php echo $base_url . '/home-learning-courses' ?>" class="gentxt_fbasic">Home Learning Courses</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 footer_col">
                    <div class="footer_column_title gentxt_fbasic">More Links</div>
                    <div class="footer_column_content">
                        <ul>
                            <li class="footer_list_item"><a href="<?php echo $base_url . '/distance-learning-courses' ?>" class="gentxt_fbasic">Distance Learning Courses</a></li>
                            <li class="footer_list_item"><a href="<?php echo $base_url . '/oxford-home-study-college-distance-learning-scholarships' ?>" class="gentxt_fbasic">OHSC Scholarship</a></li>
                            <li class="footer_list_item"><a href="<?php echo $base_url . '/TermsAndConditions.php' ?>" class="gentxt_fbasic">Terms and Conditions</a></li>
                            <li class="footer_list_item"><a href="<?php echo $base_url . '/privacy-policy' ?>" class="gentxt_fbasic">Privacy Policy</a></li>
                            <li class="footer_list_item"><a href="https://www.trustpilot.com/review/oxfordhomestudy.com?stars=5" class="gentxt_fbasic">OHSC Reviews</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer_bar d-flex flex-column flex-sm-row align-items-center">
            <div class="footer_copyright">
                <span class="gentxt_fbasic" style="color: #fff;">
                    Copyright ©2024 All rights reserved.
                </span>
            </div>
            <div class="botlinkcon" style="color: #fff;">
                <a href="<?php echo $base_url . '/policy.php' ?>" class="botlink gentxt_fbasic" style="color: #fff;">Privacy Policy</a> | <a href="<?php echo $base_url . '/sitemap.html' ?>" class="botlink gentxt_fbasic" style="color: #fff;">Sitemap</a>
            </div>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css" />
            <div class="footer_social ml-lg-auto">
                <ul class="menu_social">
                    <li class="menu_social_item"><a class="social-icons" href="<?php echo $social_row['facebook']; ?>" target="_blank" aria-label="Follow us on Facebook"><i class="fa-brands fa-facebook-f"></i></a></li>
                    <li class="menu_social_item"><a class="social-icons" href="<?php echo $social_row['twitter']; ?>" target="_blank" aria-label="Follow us on Twitter"><i class="fa-brands fa-twitter"></i></a></li>
                    <li class="menu_social_item"><a class="social-icons" href="<?php echo $social_row['linkedin']; ?>" target="_blank" aria-label="Follow us on Linkedin"><i class="fa-brands fa-linkedin-in"></i></a></li>
                    <li class="menu_social_item"><a class="social-icons" href="<?php echo $social_row['youtube']; ?>" target="_blank" aria-label="Follow us on Youtube"><i class="fa-brands fa-youtube"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
<!-- End footer section -->
</div>
<div id="GASec"></div>
<link rel="preload" href="<?php echo $pdots; ?>js/nd/jquery-3.2.1.min.js" as="script">
<script src="<?php echo $pdots; ?>js/nd/jquery-3.2.1.min.js"></script>
<script src="<?php echo $pdots; ?>js/nd/functions.js?v=24"></script>
<script async="async" type="text/javascript" src="<?php echo $pdots; ?>js/custom.js?v=4" defer></script>

<?php if (strtolower(basename($_SERVER['PHP_SELF'])) != 'index.php' && strtolower(basename($_SERVER['PHP_SELF'])) != 'classification.php' && strtolower(basename($_SERVER['PHP_SELF'])) != 'post.php') { ?>
    <script>
        var coll = document.getElementsByClassName("collapsible");
        var i;
        for (i = 0; i < coll.length; i++) {
            coll[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var content = this.nextElementSibling;
                if (content.style.maxHeight) {
                    content.style.maxHeight = null;
                    content.style.borderBottom = 'none';
                } else {
                    content.style.maxHeight = content.scrollHeight + "px";
                    content.style.borderBottom = '1px #000 solid';
                }
            });
        }
    </script>
<?php } else { ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<?php } ?>
<script>
    $(".text_expand_link").click(function() {
        $(this).hide();
        $(".less_text, .less_text p").css('display', 'inline-block');
        $(".all_text").slideDown('slow');
        $(".all_text").css('display', 'inline-block');
        return false;
    });
    $("#text_shrink_link").click(function() {
        $(".all_text").slideUp('fast');
        $(".text_expand_link").show();
    });
</script>
<?php if (strtolower(basename($_SERVER['PHP_SELF'])) != 'index.php' && strtolower(basename($_SERVER['PHP_SELF'])) != 'classification.php') { ?>
    <script type="text/javascript" src="<?php echo $pdots; ?>js/bootstrap.min.js?v=4"></script>
<?php } ?>
<script src="<?php echo $pdots; ?>jquery-modal-master/jquery.modal.min.js?v=4"></script>
<?php if (strtolower(basename($_SERVER['PHP_SELF'])) == 'index.php' || strtolower(basename($_SERVER['PHP_SELF'])) == 'classification.php') { ?>
    <script src="./OwlCarousel2-2.3.4/docs/assets/owlcarousel/owl.carousel.min.js"></script>
    <script>
        $(document).ready(function() {
            <?php for ($i = 1; $i <= $_SESSION['total_classifications']; $i++) { ?>
                var carousel<?php echo $i; ?> = $('#clslider<?php echo $i; ?>');
                carousel<?php echo $i; ?>.owlCarousel({
                    loop: false,
                    margin: 10,
                    nav: true,
                    responsive: {
                        0: {
                            items: 1
                        },
                        768: {
                            items: 2
                        },
                        1200: {
                            items: 3
                        },
                        1300: {
                            items: 4
                        }
                    }
                });
            <?php } ?>
            // Begin student reviews owlcarousel
            var stdrevcarousel = $('#stdrevslider');
            stdrevcarousel.owlCarousel({
                loop: false,
                margin: 10,
                nav: true,
                responsive: {
                    0: {
                        items: 1
                    },
                    805: {
                        items: 2
                    },
                    1205: {
                        items: 2
                    },
                    1300: {
                        items: 3
                    }
                }
            });
            // End student reviews owlcarousel
        });
    </script>
<?php } ?>
<script>
// Begin loading content
let front_content_loaded = false;
    
function loadFrontContent() {
  if (front_content_loaded) return;
  front_content_loaded = true;

  // Fetch all requests in parallel
  Promise.all([
    fetch("<?php echo $pdots; ?>ajax/general.php?task=show_ads").then(r => r.text()),
    fetch("<?php echo $pdots; ?>ajax/general.php?task=addGA").then(r => r.text()),
    fetch("<?php echo $pdots; ?>ajax/general.php?task=show_ads").then(r => r.text())
  ])
  .then(([ads1, ga, ads2]) => {
    // Do all DOM updates together inside requestAnimationFrame
    requestAnimationFrame(() => {
      document.querySelector("#mainadsdiv").innerHTML = ads1;
      document.querySelector("#GASec").innerHTML = ga;
      document.querySelector("#footerads").innerHTML = ads2;
    });
  })
  .catch(err => console.error("Failed to load ads/GA:", err));
}

window.addEventListener("scroll", () => {
  if (!front_content_loaded && window.scrollY > 0) {
    loadFrontContent();
  }
}, { passive: true });
// End loading content
</script>