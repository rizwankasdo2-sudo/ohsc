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

    $pagename = "view_task.php";
    $pagetitle = "Show task";

           include("classes/connection.php");

       include("includes/admin-header.php");

       include("includes/admin-menu.php");

 //update action and status as bulk/group

?>

   <div id="outer_contanier">

     

     <div id="board">

     

       <div id="left_corner">

       </div>

       

       <div id="midle_repeat">

         

         <div id="productsheading" style="width:300px; margin-top:0px;">

         
            <div class="form-group">
           <h3>View Course Task</h3>
           <!--<input type="button" class="btn btn-success btn-lg" onclick="redirect(this)" value="Back" name="back" id="back">-->
           </div>
           <?php 
           /*
           if($_GET['msg'] != ""){
            ?>
             <br /> <br />
            <h4 align="left" name="" class="h4" style="color:green;"><?php echo $_GET['msg']; ?></h4>
            <?php

           }

           ?>
           <?php 
           if($_GET['msg1'] != ""){
            ?>
            <h4 align="left" name="" class="h4" style="color:green;"><?php echo $_GET['msg1']; ?></h4>
            <?php

           }*/

           ?>






         

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
          <?php  if(isset($_GET['id']))
          {
          $id = $_GET['id'];
          $sql = 'select * from course_task where id ='.$id;
          $query=$db->query($sql);
          $num= $query->num_rows;
          $result = $query->fetch_assoc();
          }
          if(isset($_GET['del']) && is_numeric($_GET['del']))
          {
          $del = $_GET['del'];
          $sql_delete = "DELETE FROM course_task WHERE id='$del'";
          $delete=$db->query($sql_delete);
          echo '<script>window.close();</script>';
          exit;
          }

          if($_GET['msg']!="") { ?>
          <div class="check_main">
          <div class="check">
          <div class="good"><img src="images/check.gif" alt="check" class="icon" /><?php echo $_GET['msg'];?></div>
          </div>
        <?php } ?>

        <div class="in">
          <div class="">
               <?php
         $fromDate = strtotime($result['completionDate']);
             $date = date('d-m-Y',$fromDate);
         $fromTime = strtotime($result['completionTime']);
             $time = date('h:m A',$fromTime);
         $status = $result['status'];
         ?>
               <div class="form-group">
                    <label>Task Status:</label>
                    <p><?php if($status == 0){echo 'Un Complete';}else{echo 'Completed';}?></p>
               </div>
               <div class="form-group">
                    <label>Task For:</label>
                    <p><?php if(isset($result['taskFor'])){echo $result['taskFor'];}?></p>
               </div>
               <div class="form-group">
                    <label>Completion Date:</label>
                    <p><?php echo $date; ?></p>
               </div>
               <div class="form-group">
                    <label>Completion Time:</label>
                    <p><?php echo $time; ?></p>
               </div>
               <div class="form-group">
                    <label>Description:</label>
                    <p><?php if(isset($result['taskDescription'])){echo $result['taskDescription'];}?></p>
               </div>
               
              
        </div>
        


        
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
    
            $('#photoimg').live('change', function()      { 
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