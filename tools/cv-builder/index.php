<?php
require_once dirname(__FILE__) . '/../includes/lock.php';
include dirname(__FILE__) . '/../../admin/classes/connection.php';
require_once dirname(__FILE__) . '/../../includes/functions.php';
require_once dirname(__FILE__) . '/../includes/registry.php';
ohsc_tools_ensure($db);
$asset = rtrim($base_url, '/') . '/tools/assets/';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <title>CV Builder | Oxford Home Study Centre</title>
    <meta name="description" content="Build a CV from your details. Choose a Modern, Classic, or Minimal template and download it.">
    <?php include dirname(__FILE__) . '/../../includes/header-home-new.php'; ?>
    <link rel="stylesheet" href="<?php echo ohsc_h($asset); ?>cv-builder.css?v=4">
    <main class="ohsc-cv" id="ohsc-cv">
        <div class="cv-wrap">
            <section class="cv-hero">
                <div class="cv-hero-copy">
                    <h1>Build a CV that <span>stands out.</span></h1>
                    <p>Create, edit and download your CV.</p>
                    <a class="cv-gold" href="#cv-form">Free CV builder</a>
                </div>
                <div class="cv-hero-art" aria-hidden="true">
                    <div class="cv-plant"></div>
                    <div class="cv-paper">
                        <strong>CV</strong>
                        <div class="cv-paper-person"></div>
                        <em></em>
                        <i></i>
                        <i></i>
                        <i class="short"></i>
                    </div>
                    <span class="cv-mic"></span>
                </div>
            </section>

            <section class="cv-builder" id="cv-form">
                <form class="cv-card" id="cvForm" action="#cv-form" novalidate>
                    <div class="cv-step-label">
                        <span id="cvStepNum">1</span>
                        <div>
                            <small id="cvStepCount">1 of 5</small>
                            <strong id="cvStepTitle">Personal details</strong>
                        </div>
                    </div>
                    <div class="cv-bar" aria-hidden="true"><b id="cvBar"></b></div>
                    <div id="cvFields"></div>
                    <p class="cv-error" id="cvError" hidden></p>
                    <div class="cv-form-actions">
                        <button type="button" class="cv-back" id="cvBack" hidden>Back</button>
                        <button type="submit" class="cv-next" id="cvNext">Save &amp; Continue</button>
                    </div>
                </form>
            </section>

            <section class="cv-preview-block" id="cv-preview">
                <div class="cv-section-head">
                    <div>
                        <h2><span class="cv-eye" aria-hidden="true"></span> Preview your CV</h2>
                        <p>See how your CV looks as you build it.</p>
                    </div>
                    <button type="button" class="cv-outline" id="cvOpenPreview">Open Preview</button>
                </div>
                <div class="cv-preview-frame">
                    <div id="cvLive"></div>
                </div>
                <p class="cv-sample-note" id="cvSampleNote">This is a sample CV. Your details replace it as you type.</p>
            </section>

            <section class="cv-templates" id="templates">
                <h2>Choose a design</h2>
                <p>Pick a template that suits your style.</p>
                <div class="cv-template-grid" id="cvTemplates">
                    <button type="button" class="is-on" data-template="modern">
                        <span class="mini modern"></span>
                        <strong>Modern</strong>
                    </button>
                    <button type="button" data-template="classic">
                        <span class="mini classic"></span>
                        <strong>Classic</strong>
                    </button>
                    <button type="button" data-template="minimal">
                        <span class="mini minimal"></span>
                        <strong>Minimal</strong>
                    </button>
                </div>
            </section>

            <section class="cv-how">
                <h2>How it works</h2>
                <p>Create a professional CV in a few simple steps.</p>
                <div class="cv-how-grid">
                    <article>
                        <span>1</span>
                        <h3>Add your details</h3>
                        <p>Enter your personal information, experience, education and skills.</p>
                    </article>
                    <article>
                        <span>2</span>
                        <h3>Choose a design</h3>
                        <p>Pick a professional template that suits your style.</p>
                    </article>
                    <article>
                        <span>3</span>
                        <h3>Download your CV</h3>
                        <p>Review and download your CV to apply for jobs.</p>
                    </article>
                </div>
            </section>

            <section class="cv-guides">
                <article>
                    <img src="<?php echo ohsc_h($asset); ?>career-office.jpg" alt="">
                    <div>
                        <h3>CV writing tips</h3>
                        <p>Practical advice to help you create a stronger CV.</p>
                        <a href="#cv-form">View CV tips</a>
                    </div>
                </article>
                <article>
                    <img src="<?php echo ohsc_h($asset); ?>eq-course-people.jpg" alt="">
                    <div>
                        <h3>CV examples</h3>
                        <p>Explore real CV examples for different jobs and industries.</p>
                        <a href="#templates">View CV examples</a>
                    </div>
                </article>
            </section>

            <section class="cv-resources">
                <div class="cv-section-head">
                    <div>
                        <h2>More free career resources</h2>
                        <p>Build your skills with our free expert resources.</p>
                    </div>
                    <a href="<?php echo ohsc_h(rtrim($base_url, '/') . '/courses'); ?>">View all resources</a>
                </div>
                <div class="cv-resource-grid">
                    <a href="<?php echo ohsc_h(rtrim($base_url, '/') . '/courses'); ?>">
                        <img src="<?php echo ohsc_h($asset); ?>career-service.jpg" alt="">
                        <strong>Interview preparation</strong>
                    </a>
                    <a href="<?php echo ohsc_h(rtrim($base_url, '/') . '/OHSC-Blog'); ?>">
                        <img src="<?php echo ohsc_h($asset); ?>career-data.jpg" alt="">
                        <strong>Career guides</strong>
                    </a>
                    <a href="#templates">
                        <img src="<?php echo ohsc_h($asset); ?>hero-typing.jpg" alt="">
                        <strong>CV templates</strong>
                    </a>
                    <a href="<?php echo ohsc_h(rtrim($base_url, '/') . '/courses'); ?>">
                        <img src="<?php echo ohsc_h($asset); ?>eq-course-talk.jpg" alt="">
                        <strong>Job search tips</strong>
                    </a>
                </div>
            </section>

            <section class="cv-cta">
                <div>
                    <h2>Ready to create your CV?</h2>
                    <p>Build a professional CV and take the next step in your career.</p>
                </div>
                <a class="cv-gold" href="#cv-form">Create my CV</a>
            </section>
        </div>

        <div class="cv-modal" id="cvModal" hidden>
            <div class="cv-modal-card" role="dialog" aria-modal="true" aria-labelledby="cvModalTitle">
                <div class="cv-modal-bar">
                    <h2 id="cvModalTitle">Your CV</h2>
                    <div>
                        <button type="button" class="cv-next" id="cvDownload">Download PDF</button>
                        <button type="button" class="cv-outline" id="cvClosePreview">Close</button>
                    </div>
                </div>
                <div class="cv-modal-sheet" id="cvModalSheet"></div>
            </div>
        </div>
    </main>
    <script>
        window.OHSC_CV = <?php echo json_encode(array(
            'css' => $asset . 'cv-builder.css?v=4',
            'html2pdf' => 'https://cdn.jsdelivr.net/npm/html2pdf.js@0.10.1/dist/html2pdf.bundle.min.js'
        ), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    </script>
    <script src="<?php echo ohsc_h($asset); ?>cv-builder.js?v=4"></script>
    <?php include dirname(__FILE__) . '/../../includes/footer-new.php'; ?>
</html>
