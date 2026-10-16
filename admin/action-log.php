<?php
session_start();

ob_start();

if($_SESSION['adminid']== "") {

	header("location:index.php");
	exit();

} ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>


<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Course Information</title>

<link rel="stylesheet" href="../Resources/admin_css/admin-style.css" />

<link rel="stylesheet" href="../Resources/admin_css/products.css" />

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css" />

<script type="text/javascript" src="../Resources/admin_js/csshorizontalmenu.js"></script>



<!--[if lte IE 6]>

	<script type="text/javascript" src="../Resources/admin_js/supersleight-min.js"></script>

<![endif]-->

<style>
    table
    {
        border-collapse: collapse;

    }
    table thead tr
    {
        background: #f5f5f5;
    }
    table td, table th
    {
        border: 1px solid #ddd;
        padding: 10px 10px 10px 10px;
        text-align: left;
        font-family: "Helvetica", "Arial", sans-serif;
        font-size: 13px;
    }
    table tr:nth-child(even)
    {
        background: #f5f5f5;
    }
    #addaction{
    	width: 600px;

    }
    .h3{
    	font-size: 16px;
    }

</style>



</head >



<body>



 <div id="wrap_dashboard">

   

   <?php

    $pagename = "manage-action.php";
	$pagetitle = "Course Actions";

           include("classes/connection.php");

		   include("includes/admin-header.php");

		   include("includes/admin-menu.php");

 //update action and status as bulk/group



	require "includes/pagination.php";

$page = 1;
$size = 50;

if (isset($_GET['page'])){
    $page = (int) $_GET['page'];
}




?>

   <div id="outer_contanier">
     <?php 
           if($_GET['msg'] != ""){
            ?>
           
            <h4 align="left" name="" class="alert alert-success" style="color:green;"><?php echo $_GET['msg']; ?></h4>
            <?php

           }

           ?>
           <?php 
           if($_GET['msg1'] != ""){
            ?>
            <h4 align="left" name="" class="alert alert-success" style="color:green;"><?php echo $_GET['msg1']; ?></h4>
            <?php

           }

           ?>
     

     <div id="board">

     

       <div id="left_corner">

       </div>

       

       <div id="midle_repeat">

         

         <div id="productsheading" style="width:300px; margin-top:0px;">

         

           <h3>Course Action Log</h3><input type="button" class="btn btn-success" onclick="redirect(this)" value="Back" name="back" id="back"> 
          






         

         </div>

         

         <!--<div id="adddel">

         

           <ul>

           

             <li style="float:right"><a href="add_task.php">Add</a></li>

           

           </ul>

         

         </div>-->

       

       </div>

       

       <div id="right_corner">
		
       </div>

       

       <div id="main_content">

       

         <div id="productslistsearch">

           <div class="clear">&nbsp;</div>

           <div class="in" style="margin-top:0px">
      

        
       
        
        
        <div class="container-fluid">
         <table class="table table-striped table-bordered table-hover" id="example">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Course</th>
                    <th>Action</th>
                    <th>Date</th>
                </tr>
            </thead>
          
            <tbody>
              <?php 
            $count = 1;
            $select="select * from course_action_log";
            $result=$db->query($select,$con);
            $num= $result->num_rows;

            if($num > 0){
            	while($rows= $result->fetch_assoc())
            	{


            ?>
                         
                     <tr>
                     <td><?php echo $count;?></td>
                     <td><?php //echo $rows['courseInfoId'];
                     
                    $infoId = $rows['courseInfoId'];
                    $sql = "select diplomaid from tbl_info where infoid = $infoId";
                    $infoquery = $db->query($sql);
                    $infoResult = $infoquery->fetch_array();
                    $courseId = $infoResult['diplomaid'];
                    $course_query = "select name from tbl_diploma where diplomaid='$courseId'";
                    $courseinfo = $db->query($course_query);
                    $courseResult = $courseinfo->fetch_array();
                    echo $courseResult['name'];

                    
                      ?></td>
                     <td><?php
                     $actionId = $rows['courseActionId'];
                     $actionQuery = "select title from course_action where id = $actionId";
                     $infoquery = $db->query($actionQuery);
                     $infoResult = $infoquery->fetch_array();
                     echo $infoResult['title'];
                      ?></td>
                     <td><?php
                        $dbDate = strtotime($rows['dateTime']);
                        $actionDate = date('m/d/Y',$dbDate);
                        $actionTime = date('h:i a',$dbDate);
                        echo $actionDate;
          ?>
                    <span style="display:block"><?php echo $actionTime; ?></span>


                    </td>

                  
                  
                   
                </tr>

                <?php 
               $count++;
                 	}
            }
            else {
            	echo "No rows found:";}
            



                ?>
                     
            </tbody>
               
        </table>
	




        </div>
        
       
         
         
         
    </div>

         

         </div>

         

         <div class="clear">&nbsp;</div>

              

       </div>

     

     </div>
  
   

   </div>

 
<script type="text/javascript">
   function redirect(){
    //alert('pak');
    window.location.href="info.php";
   } 

 </script>
 
 
<script src="ajax/jquery-1.12.3.min.js"></script>
<script  async="async" type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"></script>

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript" >
/*
 $(document).ready(function() { 
		
            $('#photoimg').live('change', function()			{ 
			           $("#preview").html('');
			    $("#preview").html('<img src="loader.gif" alt="Uploading...."/>');
			$("#imageform").ajaxForm({
						target: '#preview'
		}).submit();
		
			});
        }); 
        */
</script>
<!-- Include Date Range Picker -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

<script>
/*
var jq = $.noConflict();
    jq(document).ready(function(){
        var date_input=jq('input[name="date"]'); //our date input has the name "date"
        var container=jq('.bootstrap-iso form').length>0 ? jq('.bootstrap-iso form').parent() : "body";
        date_input.datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
        })
    });
    */
</script>
<script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.2.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>




<script>
 
  $(document).ready(function() {
    $('#example').DataTable( {

         dom: 'Bfrtip',
        "ordering": true,
        "bPaginate": true,
		"info": true,
		//"searching": false,

		buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [ 1, 2, 3, 4, 5, 6, 7, 10]
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 10]
                }
            },
            {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [ 1, 2, 3, 4, 5, 6, 7, 10]
                }
            }
            
        ]


		 
    } );
	 table.buttons( 0, null ).container().prependTo(
        table.table().container()
    );
} );
</script>





<script src="ajax/custom.js"></script>

</body>

</html>