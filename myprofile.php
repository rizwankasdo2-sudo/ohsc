<?php
ob_start();
session_start();
include('classes/front.php');
if(!$_SESSION['verified']){
    $front->redirect($base.'login.php');
}

// Get personal details
$personalDetails = $front->findByThis('customer_id', $_SESSION['user_id'], 'personal_details');
// Get user details
$sql = $db->query("SELECT * FROM users WHERE id = '" . $_SESSION['user_id'] . "'");
$rr = $sql->fetch_array();
// Get business details
$businessDetails = $front->findByThis('customer_id', $_SESSION['user_id'], 'business_details');
$pd = trim(mysqli_real_escape_string($db, filter_var($_GET['pd'], FILTER_SANITIZE_STRING)));
?>

<!doctype html>
<html>
<head>
    <!-- Begin Google Adsense code -->
	<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8850775732328921"
     crossorigin="anonymous"></script>
	<!-- End Google Adsense code -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Details</title>
    <link rel="shortcut icon" type="image/jpg" href="img/favicon.jpg">
    <script type="text/javascript">
    function switchSection(show_div, active_btn) {
        $('#show_1').css('display', 'none');
        $('#show_2').css('display', 'none');
        $('#show_3').css('display', 'none');
        $('#'+show_div).css('display', 'block');
        $('#gdbtn').removeClass('user_active');
        $('#pdbtn').removeClass('user_active');
        $('#cdbtn').removeClass('user_active');
        $('#'+active_btn).addClass('user_active');
    }
    </script>
    <?php include('header.php');?>
    <div class="br_container"></div>
    <?php include('bars.php'); ?>
    <div class="container">
        <div class="row clearfix">
            <div class="col-md-3 col-sm-12 col-xs-12 quick">
            <?php include('user_sidebar.php'); ?>
            </div>
            <div class="col-md-9 col-sm-12 col-xs-12 mt">
                <?php if(!empty($_SESSION['message'])){ ?>
                <div class="alert <?php if(!empty($_SESSION['msg'])){
                        echo 'alert-success';  unset($_SESSION['msg']);
                    } elseif(!empty($_SESSION['err'])) {
                        echo 'alert-danger'; unset($_SESSION['err']); } ?>"><?php echo $_SESSION['message']; ?></div>
                    <?php
                    unset($_SESSION['message']);
                } ?>
                <ul class="breadcrumb my_style_breadcrumb">
                    <li><a href="<?php echo $base; ?>welcome.php">Welcome</a><span>/</span></li>                 
                    <li class="active">My Profile</li>
                </ul>
                <h1 class="heading" style="text-align:left;">My Profile</h1>
                <button  type="button" onclick="switchSection('show_1', 'gdbtn');" class="btn btn-md btn-primary user_active load_btn mpbtns" id="gdbtn"> General Details</button>
                <button type="button" onclick="switchSection('show_2', 'pdbtn');" class="btn btn-md btn-primary load_btn mpbtns" id="pdbtn"> Personal Details</button>
                <button type="button" onclick="switchSection('show_3', 'cdbtn');" class="btn btn-md btn-primary load_btn mpbtns" id="cdbtn"> Company Details</button>
            </div>
            <!-- Begin General Details Div -->
            <div class="col-md-9 col-sm-12 col-xs-12 mt" id="show_1">
            	<h1 class="heading text-left">General Details</h1>
            	<div class="table-responsive">
            		<table class="table table-bordered table-hover">
            			<tr>
            				<th style="white-space:normal;width:50%;">Registration number</th>
            				<td style="white-space:normal;width:50%;">
            					<?php 
            					if (!empty($rr['m_number'])) {
            					    echo $rr['m_number'];
            					}
            					?>
            				</td>
            			</tr>
            			<tr>
            			    <th style="white-space:normal;">Title</th>
            			    <td style="white-space:normal;"><?php echo $personalDetails['customer_title']; ?></td>
            			</tr>
            			<tr>
            			    <th style="white-space:normal;">First Name</th>
            			    <td style="white-space:normal;"><?php echo $personalDetails['first_name']; ?></td>
            			</tr>
            			<tr>
            			    <th style="white-space:normal;">Last Name/Family Name</th>
            			    <td style="white-space:normal;"><?php echo $personalDetails['surname']; ?></td>
            			</tr>
            			<tr>
            				<th style="white-space:normal;">Date of birth</th>
            				<td style="white-space:normal;"><?php if (trim($personalDetails['dob']) != '') { echo date('d-F-Y', strtotime($personalDetails['dob'])); } ?></td>
            		    </tr>
            		    <tr>
            		    	<th style="white-space:normal;">Status</th>
            		    	<td style="white-space:normal;">
            		    	    <?php  
            		    	    if ($rr['status'] == 1) {
            		    	        echo 'Active';
            		    	    } elseif ($rr['status'] == 3) {
            		    	        echo 'Suspended';
            		    	    } else {
            		    	        echo 'Inactive';
            		    	    }
            		    	    ?>
            		    	</td>
            		    </tr>
            		    <tr>
            		    	<th style="white-space:normal;">Registrations Date </th>
            		    	<td style="white-space:normal;">
            		    		<?php 
            		    		if (!empty($rr['reg_date'])) {
            		    		    echo date('d-F-Y', strtotime($rr['reg_date']));
            		    		}
            		    		?>
            		    	</td>
            		    </tr>
            		</table>
            	</div>
            </div>
            <!-- End General Details Div -->
            <!-- Begin Personal Details Div -->
            <div class="col-md-9 col-sm-12 col-xs-12 mt" id="show_2" style="display:none;">
                <a href="" class="pull-right edit_btn">Edit</a>
                <a href="" class="pull-right save_btn">Save</a>
                <h1 class="heading text-left">Personal Details</h1>
            	<div class="table-responsive">
                	<table class="table table-bordered table-hover">
                    	<form method="post" action="" class="personal_details_form">
                    		<tr>
                    			<th style="width:40%;white-space:normal;">Email</th>
                    			<td style="width:60%;white-space:normal;">
                    			    <span class="span_display"><?php echo $personalDetails['email']; ?></span>
                    			    <div class="form-group" id="personalEmail">
                    			        <input type="email" class="form-control input_display" name="email" value="<?php echo $personalDetails['email']; ?>" id="personal_email" autocomplete="off" />
                    			    </div>
                    			</td>
                    		</tr>
                    		<tr class="input_display">
                    			<th style="white-space:normal;">Repeat Email</th>
                    			<td style="white-space:normal;">
                    				<div class="form-group" id="personalReEmail">
                    					<input type="email" class="form-control" name="email" value="" id="personal_re_email" placeholder="Repeat your email" autocomplete="off" />
                    				</div>
                    			</td>
                    		</tr>
                    		<tr>
                    			<th style="white-space:normal;">Mobile</th>
                    			<td style="white-space:normal;">
                    				<span class="span_display"><?php echo $personalDetails['mobile']; ?></span>
                    			    <div class="form-group" id="personalMobile">
                    			    	<input type="number" class="form-control input_display" name="mobile" value="<?php echo $personalDetails['mobile']; ?>" id="mobile" />
                    			    </div>
                    			</td>
                    		</tr>
                    		<tr>
                    			<th style="white-space:normal;">Address</th>
                    			<td style="white-space:normal;">
                    				<span class="span_display"><?php echo $personalDetails['address_line_1']; ?></span>
                    				<div class="form-group" id="personalAddress">
                    					<input type="text" class="form-control input_display" name="address_line_1" value="<?php echo $personalDetails['address_line_1']; ?>" id="address_line_1" />
                    				</div>
                    			</td>
                    		</tr>
                    		<tr>
                    		    <th style="white-space:normal;">Town</th>
                    		    <td style="white-space:normal;">
                    		        <span class="span_display"><?php echo $personalDetails['town']; ?></span>
                    		        <div class="form-group" id="personalTown">
                    		            <input type="text" class="form-control input_display" name="town" value="<?php echo $personalDetails['town']; ?>" id="town" />
                    		        </div>
                    		    </td>
                    		</tr>
                    		<tr>
                    			<th style="white-space:normal;">Postcode</th>
                    			<td style="white-space:normal;">
                    				<span class="span_display"><?php echo $personalDetails['postcode']; ?></span>
                    				<div class="form-group" id="personalPostcode">
                    					<input type="text" class="form-control input_display" name="postcode" value="<?php echo $personalDetails['postcode']; ?>" id="postcode" />
                    				</div>
                    			</td>
                    		</tr>
                    		<tr>
                    			<th style="white-space:normal;">Country</th>
                    			<td style="white-space:normal;">
                    			    <span class="span_display"><?php echo $personalDetails['country']; ?></span>
                        			<div class="form-group" id="personalCountry">
                        			    <select name="country" class="form-control input_display" id="country">
                        			        <option value="">Select</option>
                        			        <?php 
                        			        $sql = $db->query("SELECT * FROM country ORDER BY name ASC ");
                        			        while ($country_record = $sql->fetch_array()) {
                        			        ?>
                        			            <option value="<?php echo $country_record['name']; ?>" <?php if ($personalDetails['country'] ==  $country_record['name']) { echo 'selected'; } ?>><?php echo $country_record['name']; ?></option>
                        			        <?php } ?>
                        			    </select>
                        			</div>
                    		    </td>
                    		</tr>
                    		<tr>
                    			<th style="white-space:normal;">Date of Birth</th>
                    			<td style="white-space:normal;">
                    			    <span class="span_display"><?php echo (trim($personalDetails['dob']) != '') ? date('d M, Y', strtotime($personalDetails['dob'])) : ''; ?></span>
                    			    <div class="form-group" id="personalDOB">
                    			        <table>
                    			            <tr>
                    			                <td>
                                			        <select name="day" id="day" class="form-control input_display" required>
                                                        <option value="">Day</option>
                                                        <?php for($i=1; $i<=31; $i++){ ?>
                                                            <option value="<?php echo $i; ?>" <?php if(date('d', strtotime($personalDetails['dob'])) == $i && trim($personalDetails['dob']) != ''){ echo 'selected'; } ?>><?php echo $i; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                    			                <td>
                                			        <select name="month" id="month" class="form-control input_display" required>
                                                        <option value="">Month</option>
                                                        <?php for($i=1; $i<=12; $i++){ ?>
                                                            <option value="<?php echo $i; ?>" <?php if(date('m', strtotime($personalDetails['dob'])) == $i && trim($personalDetails['dob']) != ''){ echo 'selected'; } ?>><?php echo $i; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                    			                <td>
                                			        <select name="year" id="year" class="form-control input_display" required>
                                                        <option value="">Year</option>
                                                        <?php for($i=1930; $i <= (date('Y', time()) - 8); $i++){ ?>
                                                            <option value="<?php echo $i; ?>" <?php if(date('Y', strtotime($personalDetails['dob'])) == $i && trim($personalDetails['dob']) != ''){ echo 'selected'; } ?>><?php echo $i; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                            </tr>
                                        </table>
                    			    </div>
                    			</td>
                    		</tr>
                    	</form>
                    </table>
                </div>
        	</div>
        	<!-- End Personal Details Div -->
        	<!-- Begin Company Details Div -->
            <div class="col-md-9 col-sm-12 col-xs-12 mt" id="show_3" style="display:none;">
            	<a href="" class="pull-right edit_btn1">Edit</a>
                <a href="" class="pull-right save_btn1">Save</a>
                <h1 class="heading" style="text-align:left;">Company Details</h1>
                <div class="table-responsive">
                	<table class="table table-bordered table-hover">
                		<tr>
                			<th style="width:40%;white-space:normal;">Employment Status</th>
                			<td style="width:60%;white-space:normal;">
                				<span class="span_display1"><?php echo $businessDetails['em_status']; ?></span>
                				<div class="form-group" id="professionalEmpStatus">
                					<select name="emp_status" id="compEmpStatus" class="form-control input_display1">
                						<option value="">Select</option>
                						<option value="Employed" <?php if ($businessDetails['em_status'] == 'Employed') { echo 'selected'; } ?>>Employed</option>
                						<option value="Self Employed" <?php if ($businessDetails['em_status'] == 'Self Employed') { echo 'selected'; } ?>>Self Employed</option>
                						<option value="Studying" <?php if($businessDetails['em_status'] == 'Studying'){echo 'selected';} ?>>Studying</option>
                						<option value="Unemployed" <?php if ($businessDetails['em_status'] == 'Unemployed') { echo 'selected'; } ?>>Unemployed</option>
                					</select>
                				</div>
                			</td>
                		</tr>
                		<tr>
                		    <th style="white-space:normal;">Job Title</th>
                		    <td style="white-space:normal;">
                		        <span class="span_display1"><?php echo ($businessDetails['em_status'] != 'Studying' && $businessDetails['em_status'] != 'Unemployed') ? $businessDetails['job_title'] : ''; ?></span>
                		        <div class="form-group" id="professionalJobTitle">
                		            <input type="text" name="job_title" id="compJobTitle" class="form-control input_display1" placeholder="Enter title of your job" value="<?php echo ($businessDetails['em_status'] != 'Studying' && $businessDetails['em_status'] != 'Unemployed') ? $businessDetails['job_title'] : ''; ?>" <?php echo ($businessDetails['em_status'] == 'Studying' || $businessDetails['em_status'] == 'Unemployed') ?  'disabled' : ''; ?>>
                		        </div>
                		    </td>
                		</tr>
                		<tr>
                		    <th style="white-space:normal;">Company Name</th>
                		    <td style="white-space:normal;">
                		        <span class="span_display1"><?php echo ($businessDetails['em_status'] != 'Studying' && $businessDetails['em_status'] != 'Unemployed') ? $businessDetails['organization'] : ''; ?></span>
                		        <div class="form-group" id="professionalCompanyName">
                		            <input type="text" name="comp_name" id="compName" class="form-control input_display1" placeholder="Enter name of your company" value="<?php echo ($businessDetails['em_status'] != 'Studying' && $businessDetails['em_status'] != 'Unemployed') ? $businessDetails['organization'] : ''; ?>" required <?php echo ($businessDetails['em_status'] == 'Studying' || $businessDetails['em_status'] == 'Unemployed') ?  'disabled' : ''; ?>>
                		        </div>
                		    </td>
                		</tr>
                		<tr>
                            <th style="white-space:normal;">Company Size by Number of Employees</th>
                            <td style="white-space:normal;">
                                <span class="span_display1"><?php echo ($businessDetails['em_status'] != 'Studying' && $businessDetails['em_status'] != 'Unemployed') ? $front->getTitle($businessDetails['company_size'], 'size') : ''; ?></span>
                                <div class="form-group" id="professionalCompSize">
                                    <select name="comp_size" id="compSize" class="form-control input_display1" <?php echo ($businessDetails['em_status'] == 'Studying' || $businessDetails['em_status'] == 'Unemployed') ?  'disabled' : ''; ?>>
                                        <option value="">Select</option>
                                        <?php 
                                        $sql = $front->findAll('size');
                                        while ($company_size = $sql->fetch_array()) {
                                        ?>
                                            <option value="<?php echo $company_size['id']; ?>" <?php if ($businessDetails['company_size'] == $company_size['id'] && $businessDetails['em_status'] != 'Studying' && $businessDetails['em_status'] != 'Unemployed') { echo 'selected'; } ?>><?php echo $company_size['title']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th style="white-space:normal;">Seniority Level in the Company</th>
                            <td style="white-space:normal;">
                                <span class="span_display1"><?php echo ($businessDetails['em_status'] != 'Studying' && $businessDetails['em_status'] != 'Unemployed') ? $front->getTitle($businessDetails['rank'], 'rank') : ''; ?></span>
                                <div class="form-group" id="professionalLevel">
                                    <select name="seniority_level" id="compSeniority" class="form-control input_display1" <?php echo ($businessDetails['em_status'] == 'Studying' || $businessDetails['em_status'] == 'Unemployed') ?  'disabled' : ''; ?>>
                                        <option value="">Select</option>
                                        <?php 
                                        $sql = $front->findAll('rank');
                                        while ($seniority_record = $sql->fetch_array()) {
                                        ?>
                                            <option value="<?php echo $seniority_record['id']; ?>" <?php if ($businessDetails['rank'] == $seniority_record['id'] && $businessDetails['em_status'] != 'Studying' && $businessDetails['em_status'] != 'Unemployed') { echo 'selected'; } ?>><?php echo $seniority_record['title']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th style="white-space:normal;">Town</th>
                            <td style="white-space:normal;">
                                <span class="span_display1"><?php echo ($businessDetails['em_status'] != 'Studying' && $businessDetails['em_status'] != 'Unemployed') ? $businessDetails['town'] : ''; ?></span>
                                <div class="form-group" id="professionalTown">
                                    <input type="text" name="town" id="compTown" class="form-control input_display1" placeholder="Enter name of your Town or City" value="<?php echo ($businessDetails['em_status'] != 'Studying' && $businessDetails['em_status'] != 'Unemployed') ? $businessDetails['town'] : ''; ?>" <?php echo ($businessDetails['em_status'] == 'Studying' || $businessDetails['em_status'] == 'Unemployed') ?  'disabled' : ''; ?>>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th style="white-space:normal;">Country</th>
                            <td style="white-space:normal;">
                                <span class="span_display1"><?php echo ($businessDetails['em_status'] != 'Studying' && $businessDetails['em_status'] != 'Unemployed') ? $businessDetails['country'] : ''; ?></span>
                                <div class="form-group" id="professionalCountry">
                                    <select name="country" id="compCountry" class="form-control input_display1" <?php echo ($businessDetails['em_status'] == 'Studying' || $businessDetails['em_status'] == 'Unemployed') ?  'disabled' : ''; ?>>
                                        <option value="">Select</option>
                                        <?php 
                                        $sql = $db->query("SELECT * FROM country ORDER BY name ASC ");
                                        while ($country_record = $sql->fetch_array()) {
                                        ?>
                                            <option value="<?php echo $country_record['name']; ?>" <?php if ($businessDetails['country'] ==  $country_record['name'] && $businessDetails['em_status'] != 'Studying' && $businessDetails['em_status'] != 'Unemployed') { echo 'selected'; } ?>><?php echo $country_record['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
        	</div>
        	<!-- End Company Details Div -->
        </div>
    </div>
</div>
<?php if (isset($_GET['pd'])) { ?>
<script type="text/javascript">
<?php if ($pd == '1') { ?>
switchSection('show_2', 'pdbtn');
<?php } elseif ($pd == '2') { ?>
switchSection('show_3', 'cdbtn');
<?php } ?>
</script>
<?php }include('footer.php');?>