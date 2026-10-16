<?php
$newsreslt2 = $db->query("SELECT * FROM tbl_subject WHERE diplomaid  = $newsid")->fetch_array();
$newsreslt2 = ($newsreslt2) ? $newsreslt2 : array();
if (isset($_POST['main_form'])) {
    $main=mysqli_real_escape_string($db, $_POST['main']);
    $overview=mysqli_real_escape_string($db, $_POST['overview']);
    $coursecontent = mysqli_real_escape_string($db, $_POST['content']);
    $paymentoption = mysqli_real_escape_string($db, $_POST['payment']);
    $status  =  $_POST['status2'];
    $accreditation  =  $_POST['accreditation'];
    $course_faq  =  $_POST['course_faq'];
    $course_level  =  $_POST['course_level'];
    $awarding_body  =  $_POST['awarding_body'];
    $study_method  =  $_POST['study_method'];
    $course_duration  =  $_POST['course_duration'];
    $entry_requirements  =  $_POST['entry_requirements'];
    $start_date  =  $_POST['start_date'];
    $f_payment  =  $_POST['f_payment'];
    $i_payment  =  $_POST['i_payment'];
    $detail  =  $_POST['detail'];
    $course_feature  =  $_POST['course_feature'];
    $career_path  =  $_POST['career_path'];
    $learning_outcomes  =  $_POST['learning_outcomes'];
    $how_study_excerpt  =  $_POST['how_study_excerpt'];
    $how_study  =  $_POST['how_study'];
    if(isset($_FILES['broucher']['name']) && !empty($_FILES['broucher']['name'])){
        $dif = rand(1,9999);
        $broucher = $dif.$_FILES['broucher']['name'];
        $folder = '../images/broucher';
        $dest = $folder.'/'.$broucher;
        move_uploaded_file($_FILES['broucher']['tmp_name'], $dest);
    } else {
        $broucher = $_POST['broucher1'];
    }
    if(isset($_FILES['ab']['name']) && !empty($_FILES['ab']['name'])){
        $dif = rand(1,9999);
        $ab = $dif.$_FILES['ab']['name'];
        $folder = '../images/ab';
        $dest = $folder.'/'.$ab;
        move_uploaded_file($_FILES['ab']['tmp_name'], $dest);
    } else {
        $ab = $_POST['ab1'];
    }
    $did = $newsid;
    $for_whom = $_POST['for_whom'];
    $key_concepts = $_POST['key_concepts'];
    $query_start = 'INSERT INTO ';
    $query_end = '';
    if (count($newsreslt2) > 0) {
        $query_start = 'UPDATE ';
        $query_end = ' WHERE subjectid = ' . $newsreslt2['subjectid'];
    }
    $query = "$query_start tbl_subject SET diplomaid = $newsid, main='$main',courseoverview='$overview',coursecontent='$coursecontent',paymentoption='$paymentoption',
    accreditation='$accreditation',
    course_faq='$course_faq',
    course_level='$course_level',
    awarding_body='$awarding_body',
    study_method='$study_method',
    course_duration='$course_duration',
    entry_requirements='$entry_requirements',
    start_date='$start_date',
    f_payment='$f_payment',
    i_payment='$i_payment',
    broucher = '$broucher',
    detail = '$detail',
    course_feature = '$course_feature',
    career_path = '$career_path',
    learning_outcomes = '$learning_outcomes',
    ab = '$ab',
    IStatus='$status',
    for_whom = '$for_whom',
    key_concepts = '$key_concepts',
    how_study_excerpt = '$how_study_excerpt',
    how_study = '$how_study' $query_end";
    if (!$db->query ($query)) {
        $msg[] = "<li class='error_text'>Course detail not updated.</li>";
    } else {
        $msg[] = "<li class='success_text'>Course detail updated successfully.</li>";
        $newsreslt2 = $db->query("SELECT * FROM tbl_subject WHERE diplomaid  = $newsid")->fetch_array();
    }
}
?>
<div>
<div id="addproducts">
<div id="uper">
<div id="upertop">
    <h4>Course Detail</h4>
</div>
<div id="uperdetail">
<div id="general">

<div class="fieldrepeat">
    <p>Main Detail : </p>
    <div style="width:80%; float:left">
        <textarea id="main" name="main" class="ckeditor" cols="30" rows="15">
            <?php echo $newsreslt2['main']; ?>
        </textarea>
    </div>
</div>
<div class="clear">
</div>
<div class="fieldrepeat">
    <p>Course Overview : </p>
    <div style="width:80%; float:left">
        <textarea id="overview" name="overview" class="ckeditor" cols="30" rows="15">
            <?php echo $newsreslt2['courseoverview']; ?>
        </textarea>
    </div>
</div>
<div class="clear">
</div>
<div class="fieldrepeat">
    <p>Course Content : </p>
    <div style="width:80%; float:left">
        <textarea id="content" name="content" class="ckeditor" cols="30" rows="15">
            <?php echo $newsreslt2['coursecontent']; ?>
        </textarea>
    </div>
</div>
<div class="clear">
</div>
<div class="fieldrepeat">
    <p>Accreditation : </p>
    <div style="width:80%; float:left">
        <textarea id="accreditation" name="accreditation" class="ckeditor" cols="30" rows="15">
            <?php echo $newsreslt2['accreditation'];?>
        </textarea>
    </div>
</div>
<div class="clear">
</div>
<div class="fieldrepeat">
    <p>Course FAQ : </p>
    <div style="width:80%; float:left">
        <textarea id="course_faq" name="course_faq" class="ckeditor" cols="30" rows="15">
            <?php echo $newsreslt2['course_faq'];?>
        </textarea>
    </div>
</div>
<div class="clear"></div>
<div class="fieldrepeat">
    <p>Payment Option : </p>
    <div style="width:80%; float:left">
        <textarea id="payment" name="payment" class="ckeditor" cols="30" rows="15">
            <?php echo $newsreslt2['paymentoption']; ?>
        </textarea>
    </div>
</div>
<!-- <div class="clear"></div>
<div class="fieldrepeat">
    <p>Course Detail: </p>
    <div style="width:80%; float:left">
        <textarea id="detail" name="detail" class="ckeditor" cols="30" rows="15">
            <?php //echo $newsreslt2['detail']; ?>
        </textarea>
    </div>
</div> -->
<div class="clear"></div>
<div class="fieldrepeat">
    <p>Course Feature: </p>
    <div style="width:80%; float:left">
        <textarea id="course_feature" name="course_feature" class="ckeditor" cols="30" rows="15">
            <?php echo $newsreslt2['course_feature']; ?>
        </textarea>
    </div>
</div>
<div class="clear"></div>
<div class="fieldrepeat">
    <p>Career Path: </p>
    <div style="width:80%; float:left">
        <textarea id="career_path" name="career_path" class="ckeditor" cols="30" rows="15">
            <?php echo $newsreslt2['career_path']; ?>
        </textarea>
    </div>
</div>
<div class="clear"></div>
<div class="fieldrepeat">
    <p>Learning Outcomes: </p>
    <div style="width:80%; float:left">
        <textarea id="learning_outcomes" name="learning_outcomes" class="ckeditor" cols="30" rows="15">
            <?php echo $newsreslt2['learning_outcomes']; ?>
        </textarea>
    </div>
</div>
<div class="clear"></div>
<div class="fieldrepeat">
    <p>Course Level: </p>
    <input type="text" id="course_level" name="course_level" value="<?php echo $newsreslt2['course_level'];?>" class="productfieldlarge" style="width:300px;" />
</div>
<div class="clear">
</div>
<div class="fieldrepeat">
    <p>Awarding Body: </p>
    <input type="text" id="awarding_body" name="awarding_body" value="<?php echo $newsreslt2['awarding_body'];?>" class="productfieldlarge" style="width:300px;" />
</div>
<div class="clear">
</div>
<div class="fieldrepeat">
    <p>Study Method: </p>
    <input type="text" id="study_method:" name="study_method" value="<?php echo $newsreslt2['study_method'];?>" class="productfieldlarge" style="width:300px;" />
</div>
<div class="clear">
</div>
<div class="fieldrepeat">
    <p>Course Duration: </p>
    <input type="text" id="course_duration" name="course_duration" value="<?php echo $newsreslt2['course_duration'];?>" class="productfieldlarge" style="width:300px;" />
</div>
<div class="clear">
</div>
<div class="fieldrepeat">
    <p>Entry Requirements: </p>
    <input type="text" id="entry_requirements" name="entry_requirements" value="<?php echo $newsreslt2['entry_requirements'];?>" class="productfieldlarge" style="width:300px;" />
</div>
<div class="clear">
</div>
<div class="fieldrepeat">
    <p>Start Date: </p>
    <input type="text" id="start_date" name="start_date" value="<?php echo $newsreslt2['start_date'];?>" class="productfieldlarge" style="width:300px;" />
</div>
<div class="clear">
</div>
<div class="fieldrepeat">
    <p>Payatrader Full Payment: </p>
    <input type="text" id="f_payment" name="f_payment" value="<?php echo $newsreslt2['f_payment'];?>" class="productfieldlarge" style="width:300px;" />
</div>
<div class="clear">
</div>
<div class="fieldrepeat">
    <p>Payatrader Installment: </p>
    <input type="text" id="i_payment" name="i_payment" value="<?php echo $newsreslt2['i_payment'];?>" class="productfieldlarge" style="width:300px;" />
</div>
<div class="clear">
</div>
<div class="fieldrepeat">
    <p>Brochure: </p>
    <input type="file" id="broucher" name="broucher" class="productfieldlarge" style="width:300px;" />
    <input type="hidden" name="broucher1" value="<?php echo $newsreslt2['broucher'];?>"/>
    <?php if(!empty($newsreslt2['broucher'])){ ?>
        <?php echo $newsreslt2['broucher'];?>
        <img src="../images/broucher/<?php echo $newsreslt2['broucher']; ?>" style="width: 166px; height: 112px;margin-left: 23px;"/>
        <!--<a href="remove_broucher.php?sid=<?php echo $sid ?>&cid=<?php echo $cid; ?>&did=<?php echo $did; ?>" style="color: rgb(213, 69, 69);text-decoration: none;">Remove</a>-->
    <?php } ?>
</div>
<div class="clear">
</div>
<div class="fieldrepeat">
    <p>Awarding Body Thumbnail: </p>
    <input type="file" id="ab" name="ab" class="productfieldlarge" style="width:300px;" />
    <input type="hidden" name="ab1" value="<?php echo $newsreslt2['ab'];?>"/>
    <?php if(!empty($newsreslt2['ab'])){ ?>
        <img src="../images/ab/<?php echo $newsreslt2['ab']; ?>" style="width: 166px; height: 112px;margin-left: 23px;"/>
        <!--<a href="remove_ab.php?sid=<?php echo $sid ?>&cid=<?php echo $cid; ?>&did=<?php echo $did; ?>" style="color: rgb(213, 69, 69);text-decoration: none;">Remove</a>-->
    <?php } ?>
</div>
<div class="clear">
</div>
<div class="fieldrepeat">
    <p>Status : </p>
    <select name="status2" id="status2" class="status" style="width:150px;">
       <?php
        if ($newsreslt2['IStatus'] == 1)
        {
            echo "<option value=".$newsreslt2['IStatus']." 'selected'>Enable</option>";
            echo "<option value='0'>Disable</option>";
        }
        else
        {
            echo "<option value=".$newsreslt2['IStatus']." 'selected'>Disable</option>";
            echo "<option value='1'>Enable</option>";
        }
        ?>
    </select>
</div>
<div class="clear">
</div>
<div class="fieldrepeat">
    <p>Who Should Enrol:<br />(Use Commas to Separte) </p>
    <textarea id="for_whom" name="for_whom" cols="60" rows="10"><?php echo $newsreslt2['for_whom']; ?></textarea>
</div>
<div class="fieldrepeat">
    <p>Key Concepts:<br />(Use Commas to Separte) </p>
    <textarea id="key_concepts" name="key_concepts" cols="60" rows="10"><?php echo $newsreslt2['key_concepts']; ?></textarea>
</div>
<div class="clear">
</div>
<div class="fieldrepeat">
    <p>How to Study:<br />(Excerpt) </p>
    <textarea id="how_study_excerpt" name="how_study_excerpt" cols="60" rows="10"><?php echo $newsreslt2['how_study_excerpt']; ?></textarea>
</div>
<div class="clear">
</div>
<div class="fieldrepeat">
    <p>How to Study:<br />(Detail) </p>
    <textarea id="how_study" name="how_study" cols="60" rows="10"><?php echo $newsreslt2['how_study']; ?></textarea>
</div>
<div class="clear">
</div>
</div>
</div>
<div class="clear">&nbsp;
</div>
</div>
</div>
</div>
<div class="clear">&nbsp;</div>
<script>
    CKEDITOR.replace( 'main',
        {
            filebrowserBrowseUrl : 'cms/ckfinder/ckfinder.html',
            filebrowserImageBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Images',
            filebrowserFlashBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Flash',
            filebrowserUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
            filebrowserImageUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
            filebrowserFlashUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
        });
</script>
<script>
    CKEDITOR.replace( 'overview',
        {
            filebrowserBrowseUrl : 'cms/ckfinder/ckfinder.html',
            filebrowserImageBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Images',
            filebrowserFlashBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Flash',
            filebrowserUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
            filebrowserImageUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
            filebrowserFlashUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
        });
</script>
<script>
    CKEDITOR.replace( 'detail',
        {
            filebrowserBrowseUrl : 'cms/ckfinder/ckfinder.html',
            filebrowserImageBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Images',
            filebrowserFlashBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Flash',
            filebrowserUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
            filebrowserImageUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
            filebrowserFlashUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
        });
</script>
<script>
    CKEDITOR.replace( 'content',
        {
            filebrowserBrowseUrl : 'cms/ckfinder/ckfinder.html',
            filebrowserImageBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Images',
            filebrowserFlashBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Flash',
            filebrowserUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
            filebrowserImageUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
            filebrowserFlashUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
        });
</script>
<script>
    CKEDITOR.replace( 'payment',
        {
            filebrowserBrowseUrl : 'cms/ckfinder/ckfinder.html',
            filebrowserImageBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Images',
            filebrowserFlashBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Flash',
            filebrowserUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
            filebrowserImageUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
            filebrowserFlashUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
        });
</script>
<script>
    CKEDITOR.replace( 'accreditation',
        {
            filebrowserBrowseUrl : 'cms/ckfinder/ckfinder.html',
            filebrowserImageBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Images',
            filebrowserFlashBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Flash',
            filebrowserUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
            filebrowserImageUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
            filebrowserFlashUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
        });
</script>
<script>
    CKEDITOR.replace( 'course_faq',
        {
            filebrowserBrowseUrl : 'cms/ckfinder/ckfinder.html',
            filebrowserImageBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Images',
            filebrowserFlashBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Flash',
            filebrowserUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
            filebrowserImageUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
            filebrowserFlashUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
        });
</script>
<script>
    CKEDITOR.replace( 'course_feature',
        {
            filebrowserBrowseUrl : 'cms/ckfinder/ckfinder.html',
            filebrowserImageBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Images',
            filebrowserFlashBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Flash',
            filebrowserUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
            filebrowserImageUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
            filebrowserFlashUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
        });
</script>
<script>
    CKEDITOR.replace( 'career_path',
        {
            filebrowserBrowseUrl : 'cms/ckfinder/ckfinder.html',
            filebrowserImageBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Images',
            filebrowserFlashBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Flash',
            filebrowserUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
            filebrowserImageUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
            filebrowserFlashUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
        });
</script>
<script>
    CKEDITOR.replace( 'learning_outcomes',
        {
            filebrowserBrowseUrl : 'cms/ckfinder/ckfinder.html',
            filebrowserImageBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Images',
            filebrowserFlashBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Flash',
            filebrowserUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
            filebrowserImageUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
            filebrowserFlashUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
        });
</script>
