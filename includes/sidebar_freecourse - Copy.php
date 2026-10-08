<script type="text/javascript">
$(document).ready(function(){
    $(".testing").hide();
});
  function change()
  {
  
  var values = $('#degree').val();
  
  var vls = 'vl='+values;
  $.ajax({ url: '<?php echo $base_url; ?>/degree.php',
           data: vls,
           type: 'post',
          success: function(output) {
            
             if(output==1)
             {
                $(document).ready(function(){

    $(".testing").hide();
  $("#test").hide();
  $(".ta").hide();
  
});
               
               } else {
            $(document).ready(function(){

    $(".testing").show();
    $(".ta").show();
    $("#test").show();
});
           $('#test').html(output);
           
               }
           }
     });
   }
</script>
    <div class="col-lg-3 col-lg-pull-9">
    <div class="get_full_info">

<form id="form1" name="form1" method="post" class="form-horizontal clearfix" action="<?php echo $base_url; ?>/info.php">

<div class="form-group">
<div class="col-xs-12">
   <select  class="form-control" onchange="change();" id="degree" name="degree" required >
    <option selected disabled value="">I Am Interested in</option>
    <?php 
    $menuqry = $db->query("SELECT * FROM `tbl_courses` WHERE IStatus=1 order by ordernumber asc");
    while($meuresult = $menuqry->fetch_array()) { ?>
        <option value="<?php echo $meuresult['cid'];?>"><?php echo $meuresult['cname']; ?></option>
    <?php } ?>  

   </select>
   </div>
  </div>
  
  
<div class="form-group testing">
    <div class="col-sm-12" id="test">
    </div>
</div>
 <div class="form-group">
  <div class="col-sm-12">
    <input class="form-control" name="fname" placeholder="First Name" type="text" required />
  </div>
</div>
   <div class="form-group">
  <div class="col-sm-12">
    <input class="form-control" name="lname" placeholder="Last Name" type="text" required />
  </div>
    </div>
  <div class="form-group">
  <div class="col-xs-12">
    <input class="form-control" name="mobile" type="text" placeholder="Mobile"/>
</div>
</div>
 <div class="form-group">
  <div class="col-xs-12">
    <input class="form-control" name="email" type="email" placeholder="Email" required />
  </div>
</div>
    <div class="col-xs-12" style="margin-left: -15px;"><input  name="Get Information Kit" type="submit" value="Get Information Kit" class="btn btn-primary" style="background: #61487c;"/></div>
 

</form>
    
    
    
    
    
<?php
$menuqry = $db->query("SELECT * FROM `tbl_courses` WHERE IStatus=1 ORDER BY ordernumber ASC");
if(!$menuqry){ die(mysqli_error($db)); }
?>

            <div class="panel panel-primary cate sidebar">
              <div class="panel-heading"><b class="sub_heading">Categories</b></div>
              <div class="sidebar panel-body">
                <ul>
                <?php
                while($meuresult = $menuqry->fetch_array())
                {
                ?>
                <li><a href="<?php echo $base_url; ?>/courses/<?php echo $meuresult['slug'];?>" title="<?php echo str_replace(',', '', $meuresult['cname']); ?>"><?php echo str_replace(',', '', $meuresult['cname']); ?></a></li>
            <?php } ?>
                
                </ul>
              </div>
            </div>
<div id="rite-cotainer">

<!--<div style="margin-top:20px;">
<a href="#"><img src="images/live-chat.gif" /></a>
</div>-->
    <b class="h4_heading testing">Testimonials</b>
    <div id="carousel1" class="carousel1 slide main_slider" data-ride="carousel">

        <div class="carousel-inner">
<?php
$t_cou = 0;
$testimonailqry = $db->query("SELECT * FROM `tbl_testimonial` WHERE IStatus=1 order by `testimonialid` desc");
while($tresult = $testimonailqry->fetch_array()){ ?>

            <div class="item <?php if($t_cou==0){ echo 'active'; } ?>">

                <div class="testimonial">

                    <div class="testimonial-body">
                        <blockquote><?php echo $tresult['detail'];?></blockquote>
                        <b><?php echo $tresult['name']; ?></b>
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
$videoeselect = $db->query("SELECT video FROM `video` WHERE video_id=1");

 

if ($videoeselect->num_rows) 
{
  $tresult = $videoeselect->fetch_array();

 ?>
  
        <div class="more-about-video">
         
          <iframe class="embed-responsive-item" width="265px" height="240px" src="<?php echo $tresult['video']; ?>" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>
        </div>
   
<?php } ?> 

      
</div>
</div>