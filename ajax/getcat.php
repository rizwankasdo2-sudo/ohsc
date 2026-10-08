<?php
header("X-Robots-Tag: noindex, nofollow", true);
include("../admin/classes/connection.php");
$discount_result = $db->query("SELECT amount FROM tbl_discount WHERE id = '1' ");
$discount_row = $discount_result->fetch_array();
$send = '';
$categories_result = $db->query("SELECT cname, slug, excerpt, thumbnail, alternate FROM tbl_courses WHERE IStatus = '1' ORDER BY ordernumber Limit 8, 99999 ");
while ($categories_row = $categories_result->fetch_array()) {
    $send .= '<div class="col-sm-12 col-md-6 col-lg-3 mb-5 extcat" style="display:none;">
                    <div class="block-19">
                        <figure>
                            <a href="' . $base_url . '/courses/' . $categories_row['slug'] . '"><img src="images/' . $categories_row['thumbnail'] . '" alt="' . $categories_row['alternate'] . '" class="img-fluid"></a>';
                            if ($discount_row['amount'] != 0) {
                            $send .= '<div class="main_discount_home gentxt_fbasic">' . $discount_row['amount'] . '%<br />off</div>';
                            }
              $send .= '</figure>
                        <div class="text">
                            <h2 class="heading"><a href="' . $base_url . '/courses/' . $categories_row['slug'] . '" class="gentxt_fbasic">' . $categories_row['cname'] . '</a></h2>
                            <p class="gentxt">';
                            $send .= (strlen(strip_tags($categories_row['excerpt'])) <= 50) ? strip_tags($categories_row['excerpt']) : substr(strip_tags($categories_row['excerpt']), 0, 50) . '...';
                            $send .= '</p>
                            <div class="meta d-flex align-items-center">
                                <div class="price text-right"><span onclick="document.location.href=\'' . $base_url . '/courses/' . $categories_row['slug'] . '\';" class="curd gentxt_fbasic">Find Out More</span></div>
                            </div>
                        </div>
                    </div>
                </div>';
}
echo $send;

