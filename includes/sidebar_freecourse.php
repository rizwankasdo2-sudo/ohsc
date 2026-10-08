    <div class="col-lg-3 col-lg-pull-9">
        <div class="panel panel-primary cate sidebar">
            <div class="panel-heading">
                <a href="" title="Get Full Information" style="font-size: 24px;color:  #fff!important;font-weight: 400;text-decoration:  none;"><b class="sub_heading gentxt_fbasic">Course Benefits</b></a>
            </div>
            <!-- Begin benefits -->
            <div class="sidebar panel-body">
                <ul>
                    <?php
                    $benefit_result = $db->query("SELECT heading, description, img FROM benefits ORDER BY sort_order ASC");
                    while ($benefit_row = $benefit_result->fetch_array()) {
                    ?>
                        <li><span class="gentxt"><?php echo $benefit_row['heading']; ?></span></li>
                    <?php } ?>
                </ul>
            </div>
            <!-- End benefits -->
        </div>
        <?php
        $menuqry = $db->query("SELECT td.cid, td.slug AS dslug, tc.cname, tc.slug FROM tbl_diploma AS td, tbl_courses AS tc WHERE td.type = '3' AND td.cid = tc.cid ");
        if (!$menuqry) {
            die(mysqli_error($db));
        }
        ?>
        <div class="panel panel-primary cate sidebar">
            <div class="panel-heading"><b class="sub_heading gentxt_fbasic">Categories</b></div>
            <div class="sidebar panel-body">
                <ul>
                    <?php
                    while ($meuresult = $menuqry->fetch_array()) {
                    ?>
                        <li><a href="<?php echo $base_url . '/courses/' . $meuresult['slug'] . '/' . $meuresult['dslug']; ?>" title="<?php echo str_replace(',', '', $meuresult['cname']); ?>" class="gentxt"><?php echo str_replace(',', '', $meuresult['cname']); ?></a></li>
                    <?php } ?>

                </ul>
            </div>
        </div>
        <?php /*<div align="center">
            <a href="https://www.learnartificialintelligence.ai/" aria-label="Visit LAI Website"><img src="<?php echo $base_url; ?>/images/LAI_side_new.webp" width="269" height="269" alt="LAI Website" border="0" /></a>
        </div>*/ ?>
        <div id="rite-cotainer">
            <b class="h4_heading gentxt_fbasic">Testimonials</b>
            <!-- Begin carousel -->
            <div id="carousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <?php
                    $t_cou = 0;
                    $testimonailqry = $db->query("SELECT * FROM `tbl_testimonial` WHERE IStatus=1 order by `testimonialid` desc");
                    while ($tresult = $testimonailqry->fetch_array()) { ?>
                        <div class="item <?php if ($t_cou == 0) {
                                                echo 'active';
                                            } ?>">
                            <div class="testimonial">
                                <div class="testimonial-body">
                                    <blockquote class="gentxt_fbasic"><?php echo $tresult['detail']; ?></blockquote>
                                    <b class="gentxt_fbasic"><?php echo $tresult['name']; ?></b>
                                </div>
                            </div>
                        </div>
                    <?php
                        $t_cou++;
                    }
                    ?>
                </div>
                <a href="#carousel" class="left carousel-control" data-slide="prev" aria-label="Load Previous">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                </a>
                <a href="#carousel" class="right carousel-control" data-slide="next" aria-label="Load Next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                </a>
            </div>
            <!-- End carousel -->
        </div>
        <?php
        $videoeselect = $db->query("SELECT video FROM `video` WHERE video_id=1");
        if ($videoeselect->num_rows) {
            $tresult = $videoeselect->fetch_array();
        ?>
            <div class="more-about-video">
                <iframe class="embed-responsive-item" width="265px" height="240px" src="" frameborder="0" gesture="media" allow="encrypted-media" id="youtubevcon" title="Watch Video" allowfullscreen></iframe>
            </div>
            <script>
                // Begin loading content
                iframe_content_loaded = 'no';
                if (document.documentElement.scrollTop != 0) {
                    if (iframe_content_loaded == 'no') {
                        loadIFrameContent();
                    }
                }
                document.onscroll = function() {
                    if (iframe_content_loaded == 'no') {
                        loadIFrameContent();
                    }
                }
                document.onmousemove = function() {
                    if (iframe_content_loaded == 'no') {
                        loadIFrameContent();
                    }
                }

                function loadIFrameContent() {
                    if (iframe_content_loaded == 'no') {
                        iframe_content_loaded = 'yes';
                        document.getElementById('youtubevcon').src = '<?php echo $tresult['video']; ?>';
                    }
                }
                // End loading content
            </script>
        <?php } ?>
    </div>