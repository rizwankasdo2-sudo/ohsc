<?php
header("X-Robots-Tag: noindex, nofollow", true);
include("../admin/classes/connection.php");
?>
<!-- Begin categories  modal -->
<!DOCTYPE html>
<html>
    <head>
        <title>Popular Categories</title>
    </head>
    <body>
        <div>
            <div class="modal-header" style="background-color:#E8E8E8;">
                <div style="position: relative; width: 100%;">
                    <div style="display:table-row">
                        <div style="display:table-cell;vertical-align:middle;"><img id="popheadicon" width="70" height="70" alt="View Categories" src="/images/nd/Forensic Accounting5581568807054.png" /></div>
                        <div style="display:table-cell;vertical-align:middle;">
                            <h1 class="modal-title" style="display:inline-block;" id="popheadname">Popular Categories</h1>
                        </div>
                    </div>
                </div>
                <a href="#" class="close" rel="modal:close">X</a>
            </div>
            <div class="modal-body">
                <ul class="child-subjects list-unstyled twocol" id="popcourselist">
                    <li><a href="<?php echo $base_url; ?>/risk-management-courses">Risk Management Courses</a></li>
                    <li><a href="<?php echo $base_url; ?>/forensic-accounting">Forensic Accounting</a></li>
                    <li><a href="<?php echo $base_url; ?>/construction-management-courses">Construction Management Courses</a></li>
                    <li><a href="<?php echo $base_url; ?>/fashion-designing-courses">Fashion Designing Courses</a></li>
                    <li><a href="<?php echo $base_url; ?>/project-management-courses">Project Management Courses</a></li>
                    <li><a href="<?php echo $base_url; ?>/supply-chain-management-courses">Supply Chain Management Courses</a></li>
                    <li><a href="<?php echo $base_url; ?>/hospitality-management-courses">Hospitality Management Courses</a></li>
                    <li><a href="<?php echo $base_url; ?>/nutrition-courses">Nutrition Courses</a></li>
                    <li><a href="<?php echo $base_url; ?>/beauty-courses-online">Beauty Therapy Courses</a></li>
                    <li><a href="<?php echo $base_url; ?>/customer-service-courses">Customer Service Courses</a></li>
                    <li><a href="<?php echo $base_url; ?>/courses/marketing-online-courses">Marketing Courses</a></li>
                    <li><a href="<?php echo $base_url; ?>/wedding-planning-courses">Wedding Planning Courses</a></li>
                    <li><a href="<?php echo $base_url; ?>/travel-and-tourism-courses">Travel and Tourism Courses</a></li>
                    <li><a href="<?php echo $base_url; ?>/public-relations-courses">Public Relations Courses</a></li>
                    <li><a href="<?php echo $base_url; ?>/human-resources-courses">Human Resources Courses</a></li>
                    <li><a href="<?php echo $base_url; ?>/interior-design-courses">Interior Design Courses</a></li>
                    <li><a href="<?php echo $base_url; ?>/event-management-courses">Event Management Courses</a></li>
                    <li><a href="<?php echo $base_url; ?>/workplace-health-and-safety-courses">Workplace Health and Safety Courses</a></li>
                    <li><a href="<?php echo $base_url; ?>/accounting-and-finance-courses">Accounting and Finance Courses</a></li>
                    <li><a href="<?php echo $base_url; ?>/professional-training-and-coaching-courses">Professional Training and Coaching Courses</a></li>
                    <?php
                    $category_result = $db->query("SELECT cname, slug FROM tbl_courses WHERE IStatus = '1' GROUP BY cname ORDER BY ordernumber ASC");
                    while ($category_row = $category_result->fetch_array()) {
                        if ($category_row['famous_category'] == '1') {
                    ?>
                    <li><a href="<?php echo $base_url . '/courses/' . $category_row['slug']; ?>"><?php echo $category_row['cname']; ?></a></li>
                    <?php }} ?>
                </ul>
            </div>
        </div>
    </body>
</html>
<!-- End categories modal -->