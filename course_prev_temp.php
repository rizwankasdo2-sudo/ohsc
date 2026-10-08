<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require('stripe_config.php');
include("admin/classes/connection.php");
include("functions.php");
include_once('includes/constants.php');
if(isset($_GET['slug']) && isset($_GET['slug2']))
{
    $slug = $_GET['slug'];
    $slug2 = $slug;
    $catSlug = $_GET['slug2'];
} else {
    header('HTTP/1.1 404 Not Found');
    include('page-not-found.php');
    exit();
}
$diplomaqry = $db->query("SELECT * FROM `tbl_diploma` WHERE slug='$slug'");
if($diplomaqry->num_rows == 0){
    header('HTTP/1.1 404 Not Found');
    include('page-not-found.php');
    exit();
}
$diplomaresult = $diplomaqry->fetch_array();
$did = $diplomaresult['diplomaid'];
$catCid = $diplomaresult['cid'];
$course_state = $diplomaresult['course_state'];
$programqry = $db->query("SELECT cid, cname, slug, cvideo, expected_growth, current_growth_rate FROM `tbl_courses` WHERE cid='$catCid' AND slug='$catSlug'");
if($programqry->num_rows == 0){
    header('HTTP/1.1 404 Not Found');
    include('page-not-found.php');
    exit();
}
$result = $programqry->fetch_array();
$program = $result['cid'];
$mainqry = $db->query("SELECT * FROM `tbl_subject` WHERE `diplomaid`=$did AND IStatus=1");
$mainresult = $mainqry->fetch_array();
$course_duration_array = explode(' ', trim($mainresult['course_duration']));
$discount_percent = 0;
if ($course_state == 0) {
    $fullpayment = $db->query("SELECT * FROM `tbl_payment` WHERE `diplomaid`=$did AND IStatus=1");
    $fullnum = $fullpayment->num_rows;
    $paymentresult = $fullpayment->fetch_array();
    $total = floatval($paymentresult['payment']);
    $discount = $paymentresult['discount'];
    $discounted = $total-$discount;
    $full = $discounted;
    if ($total != $discounted) {
        $discount_percent = ($discount * 100) / $total;
        $discount_percent = (floor($discount_percent) != $discount_percent) ? number_format($discount_percent, 2) : $discount_percent;
    }
}
$classification_row = $db->query("SELECT * FROM classification WHERE id = " . $diplomaresult['classification'])->fetch_array();
$temp_row = $db->query("SELECT pagetitle, metadescription, metatitle, robots FROM `tbl_caourseseo` WHERE diplomaid = $did")->fetch_array();
$site_title = $temp_row['pagetitle'];
$site_description = $temp_row['metadescription'];
$site_keywords = $temp_row['metatitle'];
$site_robots = $temp_row['robots'];
require_once('design-two/includes/header.php');
if ($result['special_discount']) {
  $discount_amount = number_format(($total * $result['special_discount']) / 100, 2, '.', '');
  $discounted_amount = ($total - $discount_amount);
} elseif ($discount_row['amount'] != 0) {
  $discount_amount = number_format(($total * $discount_row['amount']) / 100, 2, '.', '');
  $discounted_amount = ($total - $discount_amount);
}
// Begin actual Stripe amount paid calculation
if ($result['special_discount'] || $discount_row['amount'] != 0) {
  //$stripe_pay = (floor($discounted_amount) == $discounted_amount) ? $discounted_amount :  $discounted_amount * 100;
  $stripe_pay = $discounted_amount * 100;
  $stripe_pay_actual = $discounted_amount;
} else {
  //$stripe_pay = (floor($full) == $full) ? $full : $full * 100;
  $stripe_pay = $full * 100;
  $stripe_pay_actual = $full;
}
// End actual Stripe amount paid calculation
if ($special_discount) {
  $discount_percent = $special_discount;
} else if ($discount_row['amount']) {
  $discount_percent = $discount_row['amount'];
}
?>
    <!-- Begin Stripe CSS -->
    <style>
    .stripe-button-el {
        color: var(--red);
        font-family: "MontserratMedium";
        font-size: 17px;
        line-height: 117.647%;
        padding: 14px 20px;
        display: inline-flex;
        align-items: center;
        transition: all ease-in-out 400ms;
        -webkit-transition: all ease-in-out 400ms;
        -ms-transition: all ease-in-out 400ms;
        -o-transition: all ease-in-out 400ms;
        -moz-transition: all ease-in-out 400ms;
        border-radius: 5px;
        border: 2px solid var(--red);
        background: #fff;
        margin:19px 0 10px 0;
    }
    .stripe-button-el span{
        color: #fff;
        background: transparent !important;
        -webkit-box-shadow:none !important;
        box-shadow:none !important;
        font-size:18px !important;
        font-family:'MontserratBold' !important;
    }
    .stripe-button-el:hover span{ color: var(--red); }
    .sticky-inner .stripe-button-el, .special-box  .stripe-button-el{
        text-align: center;
        color: white;
        width: 100%;
        background-color: var(--red);
        border: 1px solid var(--red);
        font-size: 18px;
        display: flex;
        justify-content: center;
        margin: 19px 0 10px 0;
        font-family: 'MontserratBold';
    }
    .sticky-inner .stripe-button-el:hover, .special-box  .stripe-button-el:hover {
        color: var(--red);
        background-color: white;
    }
    @media screen and (max-width:767px){
        .stripe-button-el{
            padding: 10px 8px;
        }
        .stripe-button-el span{
            font-size: 14.766px !important;
            font-family: 'MontserratSemiBold' !important;
        }
    }
    </style>
    <!-- End Stripe CSS -->
    <!-- content start -->
    <div class="content">
      <div class="layout-wrapper">
        <div class="container">
          <div class="layout-wrapper-content">
            <!-- Static Content -->
            <div class="main">
              <!-- Banner Section -->
              <div class="banner-wrapper">
                <div class="container">
                  <div class="banner-main main-content">
                    <ul class="top-box">
                      <li class="detail">
                          <a href="<?php echo $base_url; ?>">Home</a>
                      </li>
                      <li class="detail">
                          <a href="<?php echo $base_url . '/' . $classification_row['slug'] . '/' . $result['slug']; ?>"><?php echo $result['cname']; ?></a>
                      </li>
                      <li class="detail"><?php echo $diplomaresult['name']; ?></li>
                    </ul>
                    <div class="main-title">
                      <h1>
                      <?php echo $diplomaresult['name']; ?>
                      </h1>
                    </div>
                    <div class="ratings">
                      <div class="rating-star">
                        <p><?php echo $diplomaresult['admin_rating']; ?></p>
                        <div class="icon">
                          <i class="<?php getRatingCSSDTwo(1, $diplomaresult['admin_rating']); ?>"></i>
                          <i class="<?php getRatingCSSDTwo(2, $diplomaresult['admin_rating']); ?>"></i>
                          <i class="<?php getRatingCSSDTwo(3, $diplomaresult['admin_rating']); ?>"></i>
                          <i class="<?php getRatingCSSDTwo(4, $diplomaresult['admin_rating']); ?>"></i>
                          <i class="<?php getRatingCSSDTwo(5, $diplomaresult['admin_rating']); ?>"></i>
                        </div>
                      </div>
                      <div class="rating-star no-rating">
                        <figure>
                          <img
                            src="<?php echo $base_url; ?>/design-two/images/user.svg"
                            alt="user"
                            class="img-fluid"
                          />
                        </figure>
                        <p><?php echo number_format($diplomaresult['admin_total_enrolled']); ?> Students</p>
                      </div>
                    </div>
                    <div class="banner-feature">
                      <div class="d-block position-relative detail">
                        <span>Duration</span>
                        <p><?php if(!empty($mainresult['course_duration'])){  echo $mainresult['course_duration']; } else { echo "N/A"; } ?></p>
                      </div>
                      <div class="d-block position-relative detail">
                        <span>Study</span>
                        <p>Online</p>
                      </div>
                      <div class="d-block position-relative detail">
                        <span>Requirements</span>
                        <p>No Requirements</p>
                      </div>
                      <div class="d-block position-relative detail">
                        <span>Certificate</span>
                        <p>On Completion</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- for mobile view of sticky section -->
              <div
                class="sticky-box d-block d-xl-none main-content"
                id="sticky-box"
              >
                <div class="sticky-inner">
                  <?php if (!empty($result['cvideo'])) { ?>
                  <iframe width="390" height="266" src="https://www.youtube.com/embed/<?php echo $result['cvideo']; ?>" class="videoiframe"></iframe>
                  <!--<div class="videobg">
                    <img
                      src="<?php //echo $base_url; ?>/design-two/images/videoBG.png"
                      alt="videoBG"
                      class="img-fluid videoImg"
                    />
                    <div class="playBtn">
                      <img
                        src="<?php //echo $base_url; ?>/design-two/images/triangle.svg"
                        alt="triangle"
                        class="img-fluid"
                      />
                    </div>
                  </div>-->
                  <?php } ?>
                  <div class="sticky-content">
                    <div class="mid-detail">
                      <p>Full Course</p>
                    </div>
                    <div class="price-box">
                      <div class="real-price">
                        <p>&pound;<?php echo ($result['special_discount'] || $discount_row['amount'] != 0) ? $discounted_amount : $full; ?></p>
                        <span>&pound;<?php echo $total; ?></span>
                      </div>
                      <?php if ($discount_percent) { ?>
                      <div class="off-price"><?php echo $discount_percent; ?>% OFF</div>
                      <?php } ?>
                    </div>
                    <div class="rating-star">
                      <p><?php echo $diplomaresult['admin_rating']; ?></p>
                      <div class="icon">
                        <i class="<?php getRatingCSSDTwo(1, $diplomaresult['admin_rating']); ?>"></i>
                        <i class="<?php getRatingCSSDTwo(2, $diplomaresult['admin_rating']); ?>"></i>
                        <i class="<?php getRatingCSSDTwo(3, $diplomaresult['admin_rating']); ?>"></i>
                        <i class="<?php getRatingCSSDTwo(4, $diplomaresult['admin_rating']); ?>"></i>
                        <i class="<?php getRatingCSSDTwo(5, $diplomaresult['admin_rating']); ?>"></i>
                      </div>
                      <span>(<?php echo $diplomaresult['total_ratings']; ?> reviews)</span>
                    </div>
                    <form method="post" action="<?php echo $base_url ?>/paynow">
                        <script src="https://checkout.stripe.com/checkout.js" class="stripe-button" data-key="<?php echo $publishable_key; ?>" data-amount="<?php echo $stripe_pay; ?>" data-name="OHSC" data-description="About OHSC" data-image="" data-currency="gbp" data-label="Join this Course"></script>
                        <input type="hidden" name="portal_course_id" value="<?php echo $diplomaresult['portal_course_id']; ?>" />
                        <input type="hidden" name="stripe_pay" value="<?php echo $stripe_pay; ?>" />
                        <input type="hidden" name="stripe_pay_actual" value="<?php echo $stripe_pay_actual; ?>" />
                    </form>
                    <span class="guarantee">30-Days money-back guarantee</span>
                    <h5>Course Includes:</h5>
                    <ul>
                      <li>
                        <img src="<?php echo $base_url; ?>/design-two/images/ci1.svg" alt="ci" class="img-fluid" />
                        Online Study Mode
                      </li>
                      <li>
                        <img src="<?php echo $base_url; ?>/design-two/images/ci2.svg" alt="ci" class="img-fluid" />
                        <?php if(!empty($mainresult['course_duration'])){  echo $mainresult['course_duration']; } else { echo ''; } ?>
                      </li>
                      <li>
                        <img src="<?php echo $base_url; ?>/design-two/images/ci3.svg" alt="ci" class="img-fluid" />
                        Study at your own Pace
                      </li>
                      <li>
                        <img src="<?php echo $base_url; ?>/design-two/images/ci4.svg" alt="ci" class="img-fluid" />
                        Completion Certificate
                      </li>
                    </ul>
                  </div>
                </div>
                <!-- aaspect-box -->
                <div class="aspects-box main-content">
                  <div class="table-head">
                    <h3>Key Aspects of Course</h3>
                  </div>
                  <ul>
                    <li>
                      <img
                        src="<?php echo $base_url; ?>/design-two/images/ka1.svg"
                        alt="icon"
                        class="img-fluid object-fit-contain"
                      />
                      Level 5 Diploma
                    </li>
                    <li>
                      <img
                        src="<?php echo $base_url; ?>/design-two/images/ka2.svg"
                        alt="icon"
                        class="img-fluid object-fit-contain"
                      />
                      Full Online Course
                    </li>
                    <li>
                      <img
                        src="<?php echo $base_url; ?>/design-two/images/ka3.svg"
                        alt="icon"
                        class="img-fluid object-fit-contain"
                      />
                      CPD Approved
                    </li>
                    <li>
                      <img
                        src="<?php echo $base_url; ?>/design-two/images/ka4.svg"
                        alt="icon"
                        class="img-fluid object-fit-contain"
                      />
                      Employer Approved
                    </li>
                    <li>
                      <img
                        src="<?php echo $base_url; ?>/design-two/images/ka5.svg"
                        alt="icon"
                        class="img-fluid object-fit-contain"
                      />
                      Fully Endorsed Course
                    </li>
                    <li>
                      <img
                        src="<?php echo $base_url; ?>/design-two/images/ka6.svg"
                        alt="icon"
                        class="img-fluid object-fit-contain"
                      />
                      450 Hours Training
                    </li>
                    <li>
                      <img
                        src="<?php echo $base_url; ?>/design-two/images/ka7.svg"
                        alt="icon"
                        class="img-fluid object-fit-contain"
                      />
                      No Entry Requirements
                    </li>
                    <li>
                      <img
                        src="<?php echo $base_url; ?>/design-two/images/ka8.svg"
                        alt="icon"
                        class="img-fluid object-fit-contain"
                      />
                      Boost Your Resume
                    </li>
                    <li>
                      <img
                        src="<?php echo $base_url; ?>/design-two/images/ka9.svg"
                        alt="icon"
                        class="img-fluid object-fit-contain"
                      />
                      Own Pace Study
                    </li>
                    <li>
                      <img
                        src="<?php echo $base_url; ?>/design-two/images/ka10.svg"
                        alt="icon"
                        class="img-fluid object-fit-contain"
                      />
                      Premium Course Material
                    </li>
                    <li>
                      <img
                        src="<?php echo $base_url; ?>/design-two/images/ka11.svg"
                        alt="icon"
                        class="img-fluid object-fit-contain"
                      />
                      Completion Certificate
                    </li>
                  </ul>
                  <div class="special-box">
                    <div class="price-box">
                      <div class="special-offer">Special offer</div>
                      <div class="real-price">
                        <p>&pound;<?php echo ($result['special_discount'] || $discount_row['amount'] != 0) ? $discounted_amount : $full; ?></p>
                        <span>&pound;<?php echo $total; ?></span>
                      </div>
                    </div>
                    <img
                      src="<?php echo $base_url; ?>/design-two/images/payGroup.svg"
                      alt="paygroup"
                      class="img-fluid d-block payGroup"
                    />
                    <form method="post" action="<?php echo $base_url ?>/paynow">
                        <script src="https://checkout.stripe.com/checkout.js" class="stripe-button" data-key="<?php echo $publishable_key; ?>" data-amount="<?php echo $stripe_pay; ?>" data-name="OHSC" data-description="About OHSC" data-image="" data-currency="gbp" data-label="Enrol in Course"></script>
                        <input type="hidden" name="portal_course_id" value="<?php echo $diplomaresult['portal_course_id']; ?>" />
                        <input type="hidden" name="stripe_pay" value="<?php echo $stripe_pay; ?>" />
                        <input type="hidden" name="stripe_pay_actual" value="<?php echo $stripe_pay_actual; ?>" />
                    </form>
                    <!--<button class="btn btn-red">
                      Enrol in Course
                      <?php //if ($discount_percent) { ?>
                      <span><?php //echo $discount_percent; ?>% OFF</span>
                      <?php //} ?>
                    </button>--->
                  </div>
                </div>
                <!-- aaspect-box -->
              </div>

              <!-- Tabbing Section -->
              <div class="key-concepts-main">
                <div class="container">
                  <div class="key-concepts-wrap main-content">
                    <ul id="pills-tab" role="tablist">
                      <li class="nav-item" data-scroll-nav="0">
                        <a href="javascrip:;"> Overview </a>
                      </li>
                      <li class="nav-item" data-scroll-nav="1">
                        <a href="javascrip:;"> Syllabus </a>
                      </li>
                      <li class="nav-item" data-scroll-nav="2">
                        <a href="javascrip:;"> Certifications </a>
                      </li>
                      <li class="nav-item" data-scroll-nav="3">
                        <a href="javascrip:;"> Learner Stories </a>
                      </li>
                      <li class="nav-item" data-scroll-nav="4">
                        <a href="javascrip:;"> FAQs </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <!-- Tabbing Section -->

              <div class="tab-1-wrapper main-content" data-scroll-index="0">
                <div class="detail">
                    <?php echo $mainresult['courseoverview']; ?>
                </div>
                <div class="concepts-details">
                <?php
                  if (trim($mainresult['key_concepts'])) {
                      $key_concepts = explode(',', $mainresult['key_concepts']);
                  ?>
                  <div class="small-title text-center">
                    <span>Learning Outcomes</span>
                  </div>
                  <div class="concepts-details-wrap">
                    <div class="left-concepts">
                      <ul>
                        <?php for ($i = 0; $i < count($key_concepts); $i += 2) { ?>
                        <li><?php echo $key_concepts[$i]; ?></li>
                        <?php } ?>
                      </ul>
                    </div>
                    <div class="right-concepts">
                      <ul>
                        <?php for ($i = 1; $i < count($key_concepts); $i += 2) { ?>
                        <li><?php echo $key_concepts[$i]; ?></li>
                        <?php } ?>
                      </ul>
                    </div>
                  </div>
                  <?php } ?>
                </div>
              </div>

              <!--who-->
              <div class="who-main">
                <div class="container">
                  <div class="main-content">
                    <div class="who-wrap">
                      <div class="red-subtitle">
                        <div class="small-title text-center">
                          <span
                            >Who should learn <br />
                            this course</span
                          >
                        </div>
                      </div>
                      <div class="who-inr">
                        <ul>
                          <?php
                          $for_whom = explode(',', $mainresult['for_whom']);
                          foreach ($for_whom as $i) {
                          ?>
                          <li><?php echo $i; ?></li>
                          <?php } ?>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!--who-->

              <!-- SYLLABUS Main Section -->
              <div id="syllabus" class="syllabus-main" data-scroll-index="1">
                <div class="container">
                  <div class="main-content">
                    <div class="syllabus-wrap">
                      <div class="syllabus-title">
                        <div class="section-title">
                          <h3>SYLLABUS</h3>
                        </div>
                      </div>
                      <div class="syllabus-inr-wrap">
                        <?php
                        $module_result = $db->query("SELECT title, excerpt, description FROM tbl_modules WHERE diplomaid = $did");
                        $i = 0;
                        while ($module_row = $module_result->fetch_array()) {
                            $i++;
                        ?>
                        <div class="syllabus-content">
                          <div class="syllabus-inner">
                            <div class="syllabus-inner-detail">
                              <div class="small-detail">
                                <p>Module <?php echo $i; ?></p>
                              </div>
                              <div class="mid-detail">
                                <p><?php echo $module_row['title']; ?></p>
                              </div>
                              <div class="detail">
                                <p><?php echo $module_row['excerpt']; ?></p>
                              </div>
                              <div class="detail moddet" style="display:none;">
                                <p><br /><?php echo $module_row['description']; ?></p>
                              </div>
                            </div>
                          </div>
                          <a rel="noopener noreferrer" href="javascript:;" class="mod_accordion">
                            <i class="fa-solid fa-plus"></i>Show Details</a
                          >
                        </div>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- SYLLABUS Main Section -->

              <!--future-->
              <div class="future-main">
                <div class="container">
                  <div class="main-content">
                    <div class="future-wrap">
                      <div class="red-subtitle">
                        <div class="small-title text-center">
                          <span>Future market Growth</span>
                        </div>
                      </div>
                      <div class="future-inr-wrp">
                        <div class="row">
                          <div
                            class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6"
                          >
                            <div class="future-block">
                              <a rel="noopener noreferrer" href="javascript:;"
                                ><?php echo $result['expected_growth']; ?> Billion</a
                              >
                              <div class="small-detail">
                                <p>
                                  Expected <?php echo $result['cname']; ?> market growth by the end
                                  of <?php echo date('Y', strtotime('+1 year')); ?>
                                </p>
                              </div>
                            </div>
                          </div>
                          <div
                            class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6"
                          >
                            <div class="future-block">
                              <a rel="noopener noreferrer" href="javascript:;"
                                ><?php echo $result['current_growth_rate']; ?>% Growth</a
                              >
                              <div class="small-detail">
                                <p>
                                  Increased in growth in different learning
                                  organizations
                                </p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!--future-->

              <!--career-->
              <div class="career-main">
                <div class="container">
                  <div class="main-content">
                    <div class="career-wrap">
                      <div class="red-subtitle">
                        <div class="small-title text-center">
                          <span>Career opportunities</span>
                        </div>
                      </div>
                      <div class="career-inr-wrp">
                        <ul
                          class="nav nav-pills mb-3"
                          id="pills-tab"
                          role="tablist"
                        >
                          <?php
                          $careers = getCareerCategories($catCid);
                          $j = 0;
                          foreach ($careers as $i) {
                              $j++;
                          ?>
                          <li class="nav-item" role="presentation">
                          <button
                              class="nav-link <?php echo ($j == 1) ? 'active' : ''; ?>"
                              id="pills-home-tab<?php echo $i['id']; ?>"
                              data-bs-toggle="pill"
                              data-bs-target="#pills-home<?php echo $i['id']; ?>"
                              type="button"
                              role="tab"
                              aria-controls="pills-home<?php echo $i['id']; ?>"
                              aria-selected="true"
                            >
                              <?php echo $i['title']; ?>
                            </button>
                          </li>
                          <?php } ?>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                          <?php
                          $j = 0;
                          foreach ($careers as $i) {
                              $j++;
                          ?>
                            <div
                              class="tab-pane fade <?php echo ($j == 1) ? 'show active' : ''; ?>"
                              id="pills-home<?php echo $i['id']; ?>"
                              role="tabpanel"
                              aria-labelledby="pills-home-tab<?php echo $i['id']; ?>"
                              tabindex="0"
                            >
                            <div class="career-content-block">
                              <div class="career-content">
                                <div class="small-detail">
                                  <p>Average Salary</p>
                                </div>
                                <div class="mid-detail">
                                  <p>£<?php echo $i['average_salary_from']; ?>k - £<?php echo $i['average_salary_to']; ?>k <span>per Annum</span></p>
                                </div>
                              </div>
                              <div class="career-content">
                                <div class="small-detail">
                                  <p>Hiring Companies</p>
                                </div>
                                <ul>
                                  <li>
                                    <figure>
                                      <img
                                        src="<?php echo $pdots; ?>images/hiring_company_logos/<?php echo $i['hiring_company_logo_1']; ?>"
                                        alt="Hiring Company Logo 1"
                                        class="img-fluid"
                                      />
                                    </figure>
                                  </li>
                                  <li>
                                    <figure>
                                      <img
                                        src="<?php echo $pdots; ?>images/hiring_company_logos/<?php echo $i['hiring_company_logo_2']; ?>"
                                        alt="Hiring Company Logo 2"
                                        class="img-fluid"
                                      />
                                    </figure>
                                  </li>
                                  <li>
                                    <figure>
                                      <img
                                        src="<?php echo $pdots; ?>images/hiring_company_logos/<?php echo $i['hiring_company_logo_3']; ?>"
                                        alt="Hiring Company Logo 3"
                                        class="img-fluid"
                                      />
                                    </figure>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!--career-->

              <!-- certification Section -->
              <div
                id="certification"
                class="certification-main"
                data-scroll-index="2"
              >
                <div class="container">
                  <div class="main-content">
                    <div class="certification-wrap">
                      <div class="section-title">
                        <h5>Certifications</h5>
                      </div>
                      <div class="certification-inr-wrap">
                        <div class="row">
                          <?php
                          $certificate_result = $db->query("SELECT title, tag_line, earn_by, pic FROM certificates LIMIT 0, 2");
                          while ($certificate_row = $certificate_result->fetch_array()) {
                          ?> 
                          <div
                            class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6"
                          >
                            <div class="certification-block">
                              <figure>
                                <img
                                  src="<?php echo $pdots . 'images/certificates/' . $certificate_row['pic']; ?>"
                                  alt="<?php echo $certificate_row['title']; ?>"
                                  class="img-fluid" width="340" height="429" 
                                />
                              </figure>
                              <div class="certification-detail">
                                <figure>
                                  <img
                                    src="<?php echo $pdots; ?>/design-two/images/certificate-detail.svg"
                                    alt="certificate-detail"
                                    class="img-fluid" width="24" height="35" 
                                  />
                                </figure>
                                <div class="small-title">
                                  <p><?php echo $certificate_row['title']; ?></p>
                                </div>
                                <div class="mid-detail">
                                  <p>
                                  <?php echo $certificate_row['tag_line']; ?>
                                  </p>
                                </div>
                                <div class="small-detail">
                                  <p>
                                    Earn By: <?php echo $certificate_row['earn_by']; ?>
                                  </p>
                                </div>
                              </div>
                            </div>
                          </div>
                          <?php } ?>
                        </div>
                      </div>
                      <!--<a
                        rel="noopener noreferrer"
                        href="javascript:;"
                        class="btn btn-red"
                        >View more Certifications<i
                          class="fa-solid fa-arrow-right-long"
                        ></i
                      ></a>-->
                    </div>
                  </div>
                </div>
              </div>
              <!-- certification Section -->

              <!-- stories Section -->
              <?php
              $learners_result = $db->query("SELECT about_course, photo, name, surname, country FROM review_form WHERE course_title = $did AND rating = 5 LIMIT 0, 2");
              if ($learners_result->num_rows > 0) {
              ?>
              <div id="stories" class="stories-main" data-scroll-index="3">
                <div class="container">
                  <div class="main-content">
                    <div class="stories-wrap">
                      <div class="section-title">
                        <h5>Learner Stories</h5>
                      </div>
                      <div class="stories-slider slider">
                        <?php while ($learners_row = $learners_result->fetch_array()) { ?>
                        <div class="stories-block">
                          <div class="stories-inr">
                            <div class="stories-img">
                              <figure>
                                <img
                                  src="<?php echo $pdots; ?>images/form_photo/<?php echo ($learners_row['photo']) ? $learners_row['photo'] : 'download.webp'; ?>"
                                  alt="<?php echo $learners_row['name'] . ' ' . $learners_row['surname']; ?>"
                                  class="img-fluid" height="86" width="86"
                                />
                              </figure>
                            </div>
                            <div class="stories-detail">
                              <figure>
                                <img
                                  src="<?php echo $pdots; ?>design-two/images/quote.svg"
                                  alt="quote"
                                  class="img-fluid"
                                />
                              </figure>
                              <div class="small-detail">
                                <p>
                                <?php echo (strlen($learners_row['about_course']) < 300) ? $learners_row['about_course'] : substr($learners_row['about_course'], 0, 300) . '...'; ?>
                                </p>
                              </div>
                              <div class="mid-detail">
                                <p>
                                <?php echo $learners_row['name'] . ' ' . $learners_row['surname']; ?>                                  <span><?php echo $learners_row['country']; ?></span>
                                </p>
                              </div>
                            </div>
                          </div>
                        </div>
                        <?php } ?>
                      </div>
                      <!--<a
                        rel="noopener noreferrer"
                        href="javascript:;"
                        class="btn btn-red"
                        >View more Learner Stories
                        <i class="fa-solid fa-arrow-right-long"></i
                      ></a>-->
                    </div>
                  </div>
                </div>
              </div>
              <?php } ?>
              <!-- stories Section -->

              <!--faqs-->
              <div id="faqs" class="faqs-main" data-scroll-index="4">
                <div class="container">
                  <div class="main-content">
                    <div class="faqs-wrap">
                      <div class="section-title">
                        <h6>Frequently Asked Questions (FAQs)</h6>
                      </div>
                      <div class="accordion" id="accordionExample">
                        <?php
                        $faq_result = $db->query("SELECT faqid, title, detail FROM tbl_faq WHERE category_id = $catCid OR category_id = 0");
                        while ($faq_row = $faq_result->fetch_array()) {
                        ?>
                        <div class="accordion-item">
                          <h2 class="accordion-header">
                            <button
                              class="accordion-button collapsed"
                              type="button"
                              data-bs-toggle="collapse"
                              data-bs-target="#collapseOne<?php echo $faq_row['faqid']; ?>"
                              aria-expanded="false"
                              aria-controls="collapseTwo"
                            >
                              <span
                                ><?php echo $faq_row['title']; ?></span
                              >
                            </button>
                          </h2>
                          <div
                            id="collapseOne<?php echo $faq_row['faqid']; ?>"
                            class="accordion-collapse collapse"
                            data-bs-parent="#accordionExample"
                          >
                            <div class="accordion-body">
                            <?php echo $faq_row['detail']; ?>
                            </div>
                          </div>
                        </div>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!--faqs-->
            </div>
            <!-- Sticky Section -->
            <div class="sticky-box d-none d-xl-block" id="sticky-box">
              <div class="sticky-inner">
                <?php if (!empty($result['cvideo'])) { ?>
                <iframe width="390" height="266" src="https://www.youtube.com/embed/<?php echo $result['cvideo']; ?>"></iframe>
                <!--<div class="videobg">
                  <img
                    src="<?php //echo $base_url; ?>/design-two/images/videoBG.png"
                    alt="videoBG"
                    class="img-fluid videoImg"
                  />
                  <div class="playBtn">
                    <img
                      src="<?php //echo $base_url; ?>/design-two/images/triangle.svg"
                      alt="triangle"
                      class="img-fluid"
                    />
                  </div>
                </div>-->
                <?php } ?>
                <div class="sticky-content">
                  <div class="mid-detail">
                    <p>Full Course</p>
                  </div>
                  <div class="price-box">
                    <div class="real-price">
                      <p>&pound;<?php echo ($result['special_discount'] || $discount_row['amount'] != 0) ? $discounted_amount : $full; ?></p>
                      <span>&pound;<?php echo $total; ?></span>
                    </div>
                    <?php if ($discount_percent) { ?>
                    <div class="off-price"><?php echo $discount_percent; ?>% OFF</div>
                    <?php } ?>
                  </div>
                  <div class="rating-star">
                    <p><?php echo $diplomaresult['admin_rating']; ?></p>
                    <div class="icon">
                      <i class="<?php getRatingCSSDTwo(1, $diplomaresult['admin_rating']); ?>"></i>
                      <i class="<?php getRatingCSSDTwo(2, $diplomaresult['admin_rating']); ?>"></i>
                      <i class="<?php getRatingCSSDTwo(3, $diplomaresult['admin_rating']); ?>"></i>
                      <i class="<?php getRatingCSSDTwo(4, $diplomaresult['admin_rating']); ?>"></i>
                      <i class="<?php getRatingCSSDTwo(5, $diplomaresult['admin_rating']); ?>"></i>
                    </div>
                    <span>(<?php echo $diplomaresult['total_ratings']; ?> reviews)</span>
                  </div>
                  <form method="post" action="<?php echo $base_url ?>/paynow">
                      <script src="https://checkout.stripe.com/checkout.js" class="stripe-button" data-key="<?php echo $publishable_key; ?>" data-amount="<?php echo $stripe_pay; ?>" data-name="OHSC" data-description="About OHSC" data-image="" data-currency="gbp" data-label="Join this Course"></script>
                      <input type="hidden" name="portal_course_id" value="<?php echo $diplomaresult['portal_course_id']; ?>" />
                      <input type="hidden" name="stripe_pay" value="<?php echo $stripe_pay; ?>" />
                      <input type="hidden" name="stripe_pay_actual" value="<?php echo $stripe_pay_actual; ?>" />
                  </form>
                  <span class="guarantee">30-Days money-back guarantee</span>
                  <h5>Course Includes:</h5>
                  <ul>
                    <li>
                      <img src="<?php echo $base_url; ?>/design-two/images/ci1.svg" alt="ci" class="img-fluid" />
                      Online Study Mode
                    </li>
                    <li>
                      <img src="<?php echo $base_url; ?>/design-two/images/ci2.svg" alt="ci" class="img-fluid" />
                      <?php if(!empty($mainresult['course_duration'])){  echo $mainresult['course_duration']; } else { echo ''; } ?>
                    </li>
                    <li>
                      <img src="<?php echo $base_url; ?>/design-two/images/ci3.svg" alt="ci" class="img-fluid" />
                      Study at your own Pace
                    </li>
                    <li>
                      <img src="<?php echo $base_url; ?>/design-two/images/ci4.svg" alt="ci" class="img-fluid" />
                      Completion Certificate
                    </li>
                  </ul>
                </div>
              </div>
              <!-- aaspect-box -->
              <div class="aspects-box main-content">
                <div class="table-head">
                  <h3>Key Aspects of Course</h3>
                </div>
                <ul>
                  <?php
                  $key_aspects_result = $db->query("SELECT title, icon FROM key_aspects WHERE diplomaid = 0 OR diplomaid = $did");
                  while ($key_aspects_row = $key_aspects_result->fetch_array()) {
                  ?>
                  <li>
                    <img
                      src="<?php echo $pdots . 'images/key_aspects/' . $key_aspects_row['icon']; ?>"
                      alt="<?php echo $key_aspects_row['title']; ?>"
                      class="img-fluid object-fit-contain" width="20" height="20" 
                    />
                    <?php echo $key_aspects_row['title']; ?>
                  </li>
                  <?php } ?>
                </ul>
                <div class="special-box">
                  <div class="price-box">
                    <div class="special-offer">Special offer</div>
                    <div class="real-price">
                      <p>&pound;<?php echo ($result['special_discount'] || $discount_row['amount'] != 0) ? $discounted_amount : $full; ?></p>
                      <span>&pound;<?php echo $total; ?></span>
                    </div>
                  </div>
                  <img
                    src="<?php echo $base_url; ?>/design-two/images/payGroup.svg"
                    alt="paygroup"
                    class="img-fluid d-block payGroup"
                  />
                  <form method="post" action="<?php echo $base_url ?>/paynow">
                      <script src="https://checkout.stripe.com/checkout.js" class="stripe-button" data-key="<?php echo $publishable_key; ?>" data-amount="<?php echo $stripe_pay; ?>" data-name="OHSC" data-description="About OHSC" data-image="" data-currency="gbp" data-label="Enrol in Course"></script>
                      <input type="hidden" name="portal_course_id" value="<?php echo $diplomaresult['portal_course_id']; ?>" />
                      <input type="hidden" name="stripe_pay" value="<?php echo $stripe_pay; ?>" />
                      <input type="hidden" name="stripe_pay_actual" value="<?php echo $stripe_pay_actual; ?>" />
                  </form>
                  <!--<button class="btn btn-red">
                    Enrol in Course
                    <?php //if ($discount_percent) { ?>
                    <span><?php //echo $discount_percent; ?>% OFF</span>
                    <?php //} ?>
                  </button>-->
                </div>
              </div>
              <!-- aaspect-box -->
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- content end -->
    <script>
    // Begin handling course module accordions
    var acc = document.getElementsByClassName("mod_accordion");
    var i;
    for (i = 0; i < acc.length; i++) {
      acc[i].addEventListener("click", function() {
        /* Toggle between hiding and showing the active panel */
        var panel = this.closest('.syllabus-content').querySelector('.moddet');
        if (panel.style.display === "block") {
          panel.style.display = "none";
          this.innerHTML = '<i class="fa-solid fa-plus"></i>Show Details</a>';
        } else {
          panel.style.display = "block";
          this.innerHTML = '<i class="fa-solid fa-minus"></i>Hide Details</a>';
        }
      });
    }
    // End handling course module accordions
    </script>
    <?php require_once('design-two/includes/footer.php'); ?>
