    <!-- Begin footer section -->
    <footer class="footer">
        <div class="container">
            <div class="footer-top">
                <div class="footer-boxs">
                    <div class="footer-box logo-box">
                        <div class="head">
                            <h2>Popular Links</h2>
                        </div>
                        <div class="footer_column_content">
                            <ul>
                                <?php getFooterLinksSimple(1); ?>
                                <li><a href="<?php echo $base_url; ?>/faq">FAQ</a></li>
                                <li><a href="<?php echo $base_url; ?>/voucher-redemption">Voucher Redemption</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="footer-box menu">
                        <div class="head">
                            <h2>Menu</h2>
                        </div>
                        <div class="footer-links">
                            <ul>
                                <?php getFooterLinksSimple(2); ?>
                                <li><a href="https://www.trustpilot.com/review/oxfordhomestudy.com?stars=5">OHSC Reviews</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="footer-box links">
                        <div class="head">
                            <h2>Useful Links</h2>
                        </div>
                        <div class="footer-links">
                            <ul>
                                <?php getFooterLinksSimple(3); ?>
                            </ul>
                        </div>
                    </div>
                    <div class="footer-box links">
                        <div class="head">
                            <h2>More Links</h2>
                        </div>
                        <div class="footer-links">
                            <ul>
                                <li><a href="<?php echo $base_url; ?>/free-online-courses-with-certificates">Free Courses</a></li>
                                <?php getFooterLinksSimple(4); ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <?php include dirname(__FILE__) . '/footer-bar.php'; ?>
        </div>
    </footer>
    <div id="GASec"></div>
    <script src="<?php echo $pdots; ?>js/nd/jquery-3.7.1.min.js"></script>
    <script src="<?php echo $pdots; ?>assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo $pdots; ?>assets/js/owl.carousel.js"></script>
    <script src="<?php echo $pdots; ?>assets/js/app.js?v=<?php echo time(); ?>"></script>
    <script src="<?php echo $pdots; ?>js/nd/functions.js?v=34"></script>
    <script type="text/javascript" src="<?php echo $pdots; ?>js/custom.js?v=4"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        <?php if (strtolower(basename($_SERVER['PHP_SELF'])) != 'index.php' && strtolower(basename($_SERVER['PHP_SELF'])) != 'classification.php' && strtolower(basename($_SERVER['PHP_SELF'])) != 'post.php') { ?>
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
        <?php } ?>
        // Begin loading content
        let front_content_loaded = false;
        function loadFrontContent() {
            if (front_content_loaded) return;
            front_content_loaded = true;
            fetch("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css")
                .then(r => r.text())
                .then(cssText => {
                    const base = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/webfonts/";
                    const fixedCss = cssText.replace(
                    /url\(\.\.\/webfonts\//g,
                    `url(${base}`
                    );
                    const style = document.createElement("style");
                    style.textContent = fixedCss;
                    document.head.appendChild(style);
                })
                .catch(console.error);
            <?php /* // Load ads
            fetch("<?php echo $base_url ?>/ajax/general.php?task=show_ads")
                .then(res => res.text())
                .then(html => {
                    document.querySelector("#mainadsdiv").innerHTML = html;
                })
                .catch(err => console.error("Failed to load ads:", err));
            // Load GA
            fetch("<?php echo $base_url ?>/ajax/general.php?task=addGA")
                .then(res => res.text())
                .then(html => {
                    document.querySelector("#GASec").innerHTML = html;
                })
                .catch(err => console.error("Failed to load GA:", err));*/ ?>
            <?php if (strtolower(basename($_SERVER['PHP_SELF'])) == 'index.php') { ?>
            fetch("<?php echo $base_url ?>/ajax/general.php?task=Youtube_homepage")
                .then(res => res.text())
                .then(html => {
                    document.querySelector("#HYSec").innerHTML = html;
                })
                .catch(err => console.error("Failed to load Youtube video:", err));
            fetch("<?php echo $base_url ?>/ajax/general.php?task=show_new_home_faqs")
                .then(res => res.text())
                .then(html => {
                    document.querySelector("#homefaqsec").innerHTML = html;
                })
                .catch(err => console.error("Failed to load FAQs:", err));
            <?php
            }
            if (strtolower(basename($_SERVER['PHP_SELF'])) == 'classification.php' || strtolower(basename($_SERVER['PHP_SELF'])) == 'search-dev.php') { ?>
            learnerCarousel();
            fetch("<?php echo $base_url ?>/ajax/general.php?task=Youtube_classification")
                .then(res => res.text())
                .then(html => {
                    document.querySelector("#CYSec").innerHTML = html;
                })
                .catch(err => console.error("Failed to load Youtube video:", err));
            <?php
            }
            if (strtolower(basename($_SERVER['PHP_SELF'])) == 'free-courses.php') { ?>
            fetch("<?php echo $base_url ?>/ajax/general.php?task=load_mfcblgcon")
                .then(res => res.text())
                .then(html => {
                    document.querySelector("#blg_content").innerHTML = html;
                    blogsCarousel();
                })
                .catch(err => console.error("Failed to load blog content:", err));
            <?php
            }
            if (strtolower(basename($_SERVER['PHP_SELF'])) == 'course.php' && $diplomaresult['type'] == 3) { ?>
            fetch("<?php echo $base_url ?>/ajax/general.php?task=showcrssldrsec")
                .then(res => res.text())
                .then(html => {
                    document.querySelector("#crssldrsec").innerHTML = html;
                    loadNewCarousel('home-carousel');
                })
                .catch(err => console.error("Failed to load categories:", err));
            learnerCarousel();
            fetch("<?php echo $base_url ?>/ajax/general.php?task=load_mfcblgcon")
                .then(res => res.text())
                .then(html => {
                    document.querySelector("#blg_content").innerHTML = html;
                    blogsCarousel();
                })
                .catch(err => console.error("Failed to load blog content:", err));
            <?php
            }
            if (strtolower(basename($_SERVER['PHP_SELF'])) == 'classification_category.php' || strtolower(basename($_SERVER['PHP_SELF'])) == 'page.php') { ?>
            learnerCarousel();
            <?php } ?>
        }
        function handleInteraction() {
            loadFrontContent();
            window.removeEventListener("scroll", handleInteraction);
            window.removeEventListener("mousemove", handleInteraction);
        }
        if (window.scrollY > 0) {
            loadFrontContent();
        } else {
            window.addEventListener("scroll", handleInteraction, { once: true });
            window.addEventListener("mousemove", handleInteraction, { once: true });
        }
        // End loading content
    });
    </script>
</body>
