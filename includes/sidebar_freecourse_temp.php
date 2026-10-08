<script type="text/javascript">
$(document).ready(function(){
    $(".testing").hide();
});
</script>
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
                <li><a class="gentxt"><?php echo $benefit_row['heading']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
        <!-- End benefits -->
      </div>

    

    
    

    
    
    
    
    
<?php
$menuqry = $db->query("SELECT td.cid, tc.cname, tc.slug FROM tbl_diploma AS td join tbl_courses as tc on (td.cid = tc.cid) WHERE td.free_course = '1' GROUP BY cid ");
if(!$menuqry){ die(mysqli_error($db)); }
?>

            <div class="panel panel-primary cate sidebar">
              <div class="panel-heading"><b class="sub_heading gentxt_fbasic">Categories</b></div>
              <div class="sidebar panel-body">
                <ul>
                <?php
                while($meuresult = $menuqry->fetch_array())
                {
                ?>
                <li><a href="#freecatsec<?php echo $meuresult['cid'];?>" title="<?php echo str_replace(',', '', $meuresult['cname']); ?>" class="gentxt"><?php echo str_replace(',', '', $meuresult['cname']); ?></a></li>
            <?php } ?>
                
                </ul>
              </div>
            </div>
<div id="rite-cotainer">

<!--<div style="margin-top:20px;">
<a href="#"><img src="images/live-chat.gif" /></a>
</div>-->
    <b class="h4_heading gentxt_fbasic">Testimonials</b>
    <!-- Begin carousel -->
    <div id="carousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <?php
            $t_cou = 0;
            $testimonailqry = $db->query("SELECT * FROM `tbl_testimonial` WHERE IStatus=1 order by `testimonialid` desc");
            while($tresult = $testimonailqry->fetch_array()) { ?>
            <div class="item <?php if($t_cou==0){ echo 'active'; } ?>">
                <div class="testimonial">
                    <div class="testimonial-body">
                        <blockquote class="gentxt_fbasic"><?php echo $tresult['detail'];?></blockquote>
                        <b class="gentxt_fbasic"><?php echo $tresult['name']; ?></b>
                    </div>
                </div>
            </div>
            <?php
            $t_cou++;
            }
            ?>
        </div>
        <a href="#carousel" class="left carousel-control" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
        </a>
        <a href="#carousel" class="right carousel-control" data-slide="next">
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
    <iframe class="embed-responsive-item" width="265px" height="240px" src="<?php echo $tresult['video']; ?>" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>
</div>
<?php } ?>
       
</div>