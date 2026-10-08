<footer class="blog-footer">
    <div class="blog-footer__main">
        <div class="blog-container">
            <div class="blog-footer__grid">
                <div>
                    <h3 class="blog-footer__title">About OHSC</h3>
                    <p class="blog-footer__text">Oxford Home Study Centre offers flexible, affordable online courses designed to help learners worldwide gain new skills and advance their careers.</p>
                    <a href="<?php echo $base_url; ?>/about-us" class="blog-footer__link">Learn more about OHSC &rarr;</a>
                </div>
                <div>
                    <div class="blog-footer__features">
                        <div class="blog-footer__feature">
                            <div class="blog-footer__feature-icon"><i class="fa-solid fa-gift"></i></div>
                            <span>100% Free</span>
                        </div>
                        <div class="blog-footer__feature">
                            <div class="blog-footer__feature-icon"><i class="fa-solid fa-certificate"></i></div>
                            <span>CPD Accredited</span>
                        </div>
                        <div class="blog-footer__feature">
                            <div class="blog-footer__feature-icon"><i class="fa-solid fa-clock"></i></div>
                            <span>Learn Anytime</span>
                        </div>
                        <div class="blog-footer__feature">
                            <div class="blog-footer__feature-icon"><i class="fa-solid fa-globe"></i></div>
                            <span>Global Access</span>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="blog-footer__title">Stay Connected</h3>
                    <p class="blog-footer__social-text">Follow OHSC for the latest courses, blog posts and learning tips.</p>
                    <div class="blog-footer__social">
                        <a href="<?php echo $social_row['facebook']; ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="<?php echo $social_row['twitter']; ?>" target="_blank" rel="noopener noreferrer" aria-label="X"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="<?php echo $social_row['linkedin']; ?>" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                        <a href="<?php echo $social_row['youtube']; ?>" target="_blank" rel="noopener noreferrer" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                        <?php if (!empty($social_row['instagram'])) { ?>
                        <a href="<?php echo $social_row['instagram']; ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="blog-footer__bar">
        <div class="blog-container">
            <div class="blog-footer__bar-inner">
                <span>Copyright &copy;<?php echo date('Y'); ?> All rights reserved.</span>
                <div class="blog-footer__bar-links">
                    <a href="<?php echo $base_url; ?>/privacy-policy">Privacy Policy</a>
                    <a href="<?php echo $base_url; ?>/terms-and-conditions">Terms &amp; Conditions</a>
                    <a href="<?php echo $base_url; ?>/cookie-policy">Cookie Policy</a>
                </div>
            </div>
        </div>
    </div>
</footer>
