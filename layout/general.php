<?php

$hasheading = ($PAGE->heading);
$hasnavbar = (empty($PAGE->layout_options['nonavbar']) && $PAGE->has_navbar());
$hasfooter = (empty($PAGE->layout_options['nofooter']));
$hasheader = (empty($PAGE->layout_options['noheader']));

$hassidepre = (empty($PAGE->layout_options['noblocks']) && $PAGE->blocks->region_has_content('side-pre', $OUTPUT));
$hassidepost = (empty($PAGE->layout_options['noblocks']) && $PAGE->blocks->region_has_content('side-post', $OUTPUT));

$showsidepre = ($hassidepre && !$PAGE->blocks->region_completely_docked('side-pre', $OUTPUT));
$showsidepost = ($hassidepost && !$PAGE->blocks->region_completely_docked('side-post', $OUTPUT));

$haslogo = (!empty($PAGE->theme->settings->logo_url));
$custommenu = $OUTPUT->custom_menu();
$hascustommenu = (empty($PAGE->layout_options['nocustommenu']) && !empty($custommenu));

$courseheader = $coursecontentheader = $coursecontentfooter = $coursefooter = '';

if (empty($PAGE->layout_options['nocourseheaderfooter'])) {
    $courseheader = $OUTPUT->course_header();
    $coursecontentheader = $OUTPUT->course_content_header();
    if (empty($PAGE->layout_options['nocoursefooter'])) {
        $coursecontentfooter = $OUTPUT->course_content_footer();
        $coursefooter = $OUTPUT->course_footer();
    }
}

$layout = 'pre-and-post';
if ($showsidepre && !$showsidepost) {
    if (!right_to_left()) {
        $layout = 'side-pre-only';
    } else {
        $layout = 'side-post-only';
    }
} else if ($showsidepost && !$showsidepre) {
    if (!right_to_left()) {
        $layout = 'side-post-only';
    } else {
        $layout = 'side-pre-only';
    }
} else if (!$showsidepost && !$showsidepre) {
    $layout = 'content-only';
}
$bodyclasses[] = $layout;

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes() ?>>
<head>
    <title><?php echo $PAGE->title ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->pix_url('favicon', 'theme')?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body id="<?php p($PAGE->bodyid) ?>" class="<?php p($PAGE->bodyclasses.' '.join($bodyclasses)) ?>">

<?php echo $OUTPUT->standard_top_of_body_html() ?>






<header role="banner" class="navbar navbar-fixed-top">
    <nav role="navigation" class="navbar-inner">
        <div class="container-fluid">
           <img src="<?php echo $OUTPUT->pix_url('logo4', 'theme'); ?>" alt="" height="50" width="90" style="float:left; margin-top:7px; margin-right:10px"/ /> 
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="nav-collapse collapse">
            <?php if ($hascustommenu) {
                echo $custommenu;
            } ?>
            <ul class="nav pull-right">
            <li><?php echo $PAGE->headingmenu ?></li>
            <li class="navbar-text"><?php echo $OUTPUT->login_info() ?></li>
            </ul>
            </div>
        </div>
    </nav>
</header>

<div id="page" class="container-fluid">

<?php if ($hasheader) { ?>
<header id="page-header" class="clearfix">
    <?php
        if (!$haslogo) { ?>
            
            <?php
        } else { ?>
             <a class="logo" href="<?php echo $CFG->wwwroot; ?>" title="<?php print_string('home'); ?>">
             <img src="<?php echo $PAGE->theme->settings->logo_url; ?>">
             </a>
             
            <?php
        } ?>
    <?php if ($hasnavbar) { ?>
        <nav class="breadcrumb-button"><?php echo $PAGE->button; ?></nav>
        <?php echo $OUTPUT->navbar(); ?>
    <?php } ?>

    <?php if (!empty($courseheader)) { ?>
        <div id="course-header"><?php echo $courseheader; ?></div>
    <?php } ?>
</header>
<?php } ?>

<div id="page-content" class="row-fluid">

<?php if ($layout === 'pre-and-post') { ?>
    <div id="region-bs-main-and-pre" class="span9">
    <div class="row-fluid">
    <section id="region-main" class="span8 pull-right">
<?php } else if ($layout === 'side-post-only') { ?>
    <section id="region-main" class="span9">
<?php } else if ($layout === 'side-pre-only') { ?>
    <section id="region-main" class="span9 pull-right">
<?php } else if ($layout === 'content-only') { ?>
    <section id="region-main" class="span12">
<?php } ?>


    <?php echo $coursecontentheader; ?>
    <?php echo $OUTPUT->main_content() ?>
    <?php echo $coursecontentfooter; ?>
    </section>


<?php if ($layout !== 'content-only') {
          if ($layout === 'pre-and-post') { ?>
            <aside id="region-pre" class="span4 block-region desktop-first-column region-content">
    <?php } else if ($layout === 'side-pre-only') { ?>
            <aside id="region-pre" class="span3 block-region desktop-first-column region-content">
    <?php } ?>
          <?php
                if (!right_to_left()) {
                    echo $OUTPUT->blocks_for_region('side-pre');
                } else if ($hassidepost) {
                    echo $OUTPUT->blocks_for_region('side-post');
                }
            ?>
            </aside>
    <?php if ($layout === 'pre-and-post') {
          ?></div></div><?php // Close row-fluid and span9.
   }

    if ($layout === 'side-post-only' OR $layout === 'pre-and-post') { ?>
        <aside id="region-post" class="span3 block-region region-content">
        <?php if (!right_to_left()) {
                  echo $OUTPUT->blocks_for_region('side-post');
              } else {
                  echo $OUTPUT->blocks_for_region('side-pre');
              } ?>
        </aside>
    <?php } ?>
<?php } ?>
</div>

<footer id="page-footer">
    <p class="helplink"><?php echo page_doc_link(get_string('moodledocslink')) ?></p>
    <?php echo $OUTPUT->standard_footer_html(); ?>
</footer>

<?php echo $OUTPUT->standard_end_of_body_html() ?>

</div>

<?php if (!empty($PAGE->theme->settings->enablejquery)) {?>

<script src="<?php echo $CFG->wwwroot;?>/theme/bootstrap/javascript/jquery.js"></script>
<script src="<?php echo $CFG->wwwroot;?>/theme/bootstrap/javascript/bootstrap.min.js"></script>

<?php }?>

</body>
</html>
