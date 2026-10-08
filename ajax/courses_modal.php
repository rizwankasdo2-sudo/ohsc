<?php
header("X-Robots-Tag: noindex, nofollow", true);
include("../admin/classes/connection.php");
?>
<!-- Begin popular courses modal -->
<!DOCTYPE html>
<html>
    <head>
        <title>Popular Courses</title>
    </head>
    <body>
        <div>
            <div class="modal-header" style="background-color:#E8E8E8;">
                <div style="position: relative; width: 100%;">
                    <div style="display:table-row">
                        <div style="display:table-cell;vertical-align:middle;"><img id="popheadicon" width="70" height="70" alt="View Popular Courses" src="./images/nd/Forensic Accounting5581568807054.png" /></div>
                        <div style="display:table-cell;vertical-align:middle;">
                            <h1 class="modal-title" style="display:inline-block;" id="popheadname">Popular Courses</h1>
                        </div>
                    </div>
                </div>
                <a href="#" class="close" rel="modal:close">X</a>
            </div>
            <div class="modal-body">
                    <ul class="child-subjects list-unstyled twocol" id="popcourselist">
                        <?php
                        $popc_result = $db->query("SELECT cid, slug, name FROM tbl_diploma WHERE famous_course = '1' ORDER BY ordernumber ASC");
                        while ($popc_row = $popc_result->fetch_array()) {
                            $temp_result = $db->query("SELECT slug FROM tbl_courses WHERE cid = '" . $popc_row['cid'] . "' ");
                            $temp_row = $temp_result->fetch_array();
                        ?>
                        <li><a href="<?php echo $base_url . '/courses/' . $temp_row['slug'] . '/' . $popc_row['slug']; ?>"><?php echo $popc_row['name']; ?></a></li>
                        <?php } ?>
                    </ul>
            </div>
        </div>
    </body>
</html>
<!-- End courses modal -->