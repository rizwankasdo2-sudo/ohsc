    <!-- Begin modal for small resolution menu -->
    <div class="modal fade" id="mainMenuModal" tabindex="-1" aria-labelledby="mainMenuModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h2 class="modal-title fs-5" id="mainMenuModalLabel">Main Menu</h2>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="modalBody">
            Loading content...
          </div>
        </div>
      </div>
    </div>
    <script>
    document.getElementById('mainMenuModal').addEventListener('show.bs.modal', function () {
        const modalBody = document.getElementById('modalBody');

        // URL of the external content
        const url = '/ajax/general.php?task=show_main_menu&dontshowimg=1'; // Replace with your content URL

        // Show a loading state
        modalBody.innerHTML = 'Loading content...';

        // Fetch external content
        fetch(url)
        .then(response => {
            console.log('Response:', response);
            if (!response.ok) {
              throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text(); // Assume the response is HTML content
        })
        .then(data => {
            console.log('Data fetched:', data);
            modalBody.innerHTML = data; // Insert raw HTML
        })
        .catch(error => {
            console.error('Error fetching content:', error);
            modalBody.innerHTML = 'Failed to load content. Please try again.';
        });
    });
    </script>
    <!-- End modal for small resolution menu -->

    <!-- Begin previous footer content -->
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
                                <?php getFooterLinks(4); ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <?php include dirname(__FILE__) . '/../../includes/footer-bar.php'; ?>
        </div>
    </footer>
    <div id="GASec"></div>
    <!-- End previous footer content -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?php echo $pdots; ?>design-two/js/jquery-3.7.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <!-- Bootstrap Bundle with Popper -->
    <script src="<?php echo $pdots; ?>design-two/js/bootstrap.bundle.min.js"></script>
    <!-- Slick Slider -->
    <script src="<?php echo $pdots; ?>design-two/js/slick.min.js"></script>
    <!-- Font Awesome -->
    <script src="<?php echo $pdots; ?>design-two/js/all.min.js"></script>

    <script src="<?php echo $pdots; ?>design-two/js/scrollIt.min.js"></script>
    <!-- ofi -->
    <script src="<?php echo $pdots; ?>design-two/js/ofi.min.js"></script>
    <script src="<?php echo $pdots; ?>design-two/js/script.js"></script>
    <script>
    // Begin loading content
    let front_content_loaded = false;
        
    function loadFrontContent() {
        if (front_content_loaded) return;
        front_content_loaded = true;
    
        // Defer DOM changes to next frame → avoids forced reflow
        requestAnimationFrame(() => {
            // Load GA
            fetch("<?php echo $base_url ?>/ajax/general.php?task=addGA")
                .then(res => res.text())
                .then(html => {
                    document.querySelector("#GASec").innerHTML = html;
                })
                .catch(err => console.error("Failed to load GA:", err));
        });
    }
    
    function handleInteraction() {
        loadFrontContent();
        // Remove listeners after first trigger → no extra layout checks
        window.removeEventListener("scroll", handleInteraction);
        window.removeEventListener("mousemove", handleInteraction);
    }
    
    // Initial check: if page is already scrolled
    if (window.scrollY > 0) {
        loadFrontContent();
    } else {
        // Listen for first interaction only
        window.addEventListener("scroll", handleInteraction, { once: true });
        window.addEventListener("mousemove", handleInteraction, { once: true });
    }
    // End loading content
    </script>
  </body>
</html>