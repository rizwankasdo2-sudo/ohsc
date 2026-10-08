<!-- Begin footer section -->
<footer class="footer" id="footerSection">
    <div class="container">
        <div class="footer_content">
            <div class="row">
                <div class="col-lg-3 footer_col">
                    <div class="footer_column_title gentxt_fbasic">Popular Links</div>
                    <div class="footer_column_content">
                        <ul>
                            <?php getFooterLinks(1); ?>
                            <li class="footer_list_item"><a href="<?php echo $base_url; ?>/faq" class="gentxt_fbasic">FAQ</a></li>
                            <li class="footer_list_item"><a href="<?php echo $base_url; ?>/voucher-redemption" class="gentxt_fbasic">Voucher Redemption</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 footer_col">
                    <div class="footer_column_title gentxt_fbasic">Menu</div>
                    <div class="footer_column_content">
                        <ul>
                            <?php getFooterLinks(2); ?>
                            <li class="footer_list_item"><a href="https://www.trustpilot.com/review/oxfordhomestudy.com?stars=5" class="gentxt_fbasic">OHSC Reviews</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 footer_col">
                    <div class="footer_column_title gentxt_fbasic">Usefull Links</div>
                    <div class="footer_column_content">
                        <ul>
                            <?php getFooterLinks(3); ?>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 footer_col">
                    <div class="footer_column_title gentxt_fbasic">More Links</div>
                    <div class="footer_column_content">
                        <ul>
                            <li class="footer_list_item"><a href="<?php echo $base_url; ?>/free-online-courses-with-certificates" class="gentxt_fbasic">Free Courses</a></li>
                            <?php getFooterLinks(4); ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php include dirname(__FILE__) . '/footer-bar.php'; ?>
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
<script>
// Begin loading content
let front_content_loaded = false;
    
function loadFrontContent() {
  if (front_content_loaded) return;
  front_content_loaded = true;
  // Fetch all requests in parallel
  Promise.all([
    fetch("<?php echo $pdots; ?>ajax/general.php?task=addGA").then(r => r.text()),
  ])
  .then(([ga]) => {
    // Do all DOM updates together inside requestAnimationFrame
    requestAnimationFrame(() => {
      document.querySelector("#GASec").innerHTML = ga;
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