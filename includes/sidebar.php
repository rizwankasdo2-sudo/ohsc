<div class="col-lg-3 col-lg-pull-9">
    <?php
    $menuqry = $db->query("SELECT * FROM `tbl_courses` WHERE IStatus=1 AND previous_free_category = 0  ORDER BY ordernumber ASC");
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
                    <li><a href="<?php echo $base_url; ?>/courses/<?php echo $meuresult['slug']; ?>"
                            title="<?php echo str_replace(',', '', $meuresult['cname']); ?>"
                            class="gentxt_basic"><?php echo str_replace(',', '', $meuresult['cname']); ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div id="rite-cotainer">
        <b class="h4_heading gentxt_fbasic">Testimonials</b>
        <div id="carousel1" class="carousel1 slide main_slider" data-ride="carousel">
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
        </div>
    </div>
    <?php
    if (!empty($_SESSION['cvideo'])) {
    ?>
        <div class="more-about-video">
            <iframe class="embed-responsive-item" width="265px" height="240px"
                src="https://www.youtube.com/embed/<?php echo $_SESSION['cvideo']; ?>" frameborder="0" gesture="media"
                allow="encrypted-media" allowfullscreen></iframe>
        </div>
    <?php
        unset($_SESSION['cvideo']);
    } ?>
</div>