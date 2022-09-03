<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Moodle frontpage.
 *
 * @package    core
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (!file_exists('./config.php')) {
    header('Location: install.php');
    die;
}

require_once('config.php');
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->libdir . '/filelib.php');

redirect_if_major_upgrade_required();

$urlparams = array();
if (
    !empty($CFG->defaulthomepage) &&
    ($CFG->defaulthomepage == HOMEPAGE_MY || $CFG->defaulthomepage == HOMEPAGE_MYCOURSES) &&
    optional_param('redirect', 1, PARAM_BOOL) === 0
) {
    $urlparams['redirect'] = 0;
}
$PAGE->set_url('/', $urlparams);
$PAGE->set_pagelayout('frontpage');
$PAGE->add_body_class('limitedwidth');
$PAGE->set_other_editing_capability('moodle/course:update');
$PAGE->set_other_editing_capability('moodle/course:manageactivities');
$PAGE->set_other_editing_capability('moodle/course:activityvisibility');

// Prevent caching of this page to stop confusion when changing page after making AJAX changes.
$PAGE->set_cacheable(false);

require_course_login($SITE);

$hasmaintenanceaccess = has_capability('moodle/site:maintenanceaccess', context_system::instance());

// If the site is currently under maintenance, then print a message.
if (!empty($CFG->maintenance_enabled) and !$hasmaintenanceaccess) {
    print_maintenance_message();
}

$hassiteconfig = has_capability('moodle/site:config', context_system::instance());

if ($hassiteconfig && moodle_needs_upgrading()) {
    redirect($CFG->wwwroot . '/' . $CFG->admin . '/index.php');
}

// If site registration needs updating, redirect.
\core\hub\registration::registration_reminder('/index.php');

if (get_home_page() != HOMEPAGE_SITE) {
    // Redirect logged-in users to My Moodle overview if required.
    $redirect = optional_param('redirect', 1, PARAM_BOOL);
    if (optional_param('setdefaulthome', false, PARAM_BOOL)) {
        set_user_preference('user_home_page_preference', HOMEPAGE_SITE);
    } else if (!empty($CFG->defaulthomepage) && ($CFG->defaulthomepage == HOMEPAGE_MY) && $redirect === 1) {
        // At this point, dashboard is enabled so we don't need to check for it (otherwise, get_home_page() won't return it).
        redirect($CFG->wwwroot . '/my/');
    } else if (!empty($CFG->defaulthomepage) && ($CFG->defaulthomepage == HOMEPAGE_MYCOURSES) && $redirect === 1) {
        redirect($CFG->wwwroot . '/my/courses.php');
    } else if (!empty($CFG->defaulthomepage) && ($CFG->defaulthomepage == HOMEPAGE_USER)) {
        $frontpagenode = $PAGE->settingsnav->find('frontpage', null);
        if ($frontpagenode) {
            $frontpagenode->add(
                get_string('makethismyhome'),
                new moodle_url('/', array('setdefaulthome' => true)),
                navigation_node::TYPE_SETTING
            );
        } else {
            $frontpagenode = $PAGE->settingsnav->add(get_string('frontpagesettings'), null, navigation_node::TYPE_SETTING, null);
            $frontpagenode->force_open();
            $frontpagenode->add(
                get_string('makethismyhome'),
                new moodle_url('/', array('setdefaulthome' => true)),
                navigation_node::TYPE_SETTING
            );
        }
        redirect($CFG->wwwroot . '/ash.php');;
    }
}



course_view(context_course::instance(SITEID));

$PAGE->set_pagetype('site-index');
$PAGE->set_docs_path('');
$editing = $PAGE->user_is_editing();
$PAGE->set_title($SITE->fullname);
$PAGE->set_heading($SITE->fullname);
if (has_capability('moodle/course:update', context_system::instance())) {
    $PAGE->set_secondary_navigation(true);
    $PAGE->set_secondary_active_tab('coursehome');
} else {
    $PAGE->set_secondary_navigation(false);
}

$courserenderer = $PAGE->get_renderer('core', 'course');

if ($hassiteconfig) {
    $editurl = new moodle_url('/course/view.php', ['id' => SITEID, 'sesskey' => sesskey()]);
    $editbutton = $OUTPUT->edit_button($editurl);
    $PAGE->set_button($editbutton);
}
if (isloggedin()) {
    // Trigger event.


    echo $OUTPUT->header();

    $siteformatoptions = course_get_format($SITE)->get_format_options();
    $modinfo = get_fast_modinfo($SITE);
    $modnamesused = $modinfo->get_used_module_names();

    // Print Section or custom info.
    if (!empty($CFG->customfrontpageinclude)) {
        // Pre-fill some variables that custom front page might use.
        $modnames = get_module_types_names();
        $modnamesplural = get_module_types_names(true);
        $mods = $modinfo->get_cms();

        include($CFG->customfrontpageinclude);
    } else if ($siteformatoptions['numsections'] > 0) {
        echo $courserenderer->frontpage_section1();
    }
    // Include course AJAX.
    include_course_ajax($SITE, $modnamesused);

    echo $courserenderer->frontpage();

    if ($editing && has_capability('moodle/course:create', context_system::instance())) {
        echo $courserenderer->add_new_course_button();
    }
    echo $OUTPUT->footer();

    // echo "<script> alert('nous y sommes') </script>";

}
if (isloggedin() == false) {
    // redirect($CFG->wwwroot . '/ash.php');

?>

    <!doctype html>

    <!--
 // WEBSITE: https://themefisher.com
 // TWITTER: https://twitter.com/themefisher
 // FACEBOOK: https://www.facebook.com/themefisher
 // GITHUB: https://github.com/themefisher/
-->

    <html lang="en">

    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="sulfur,business,company,agency,multipurpose,modern,bootstrap4">

        <!-- <meta name="author" content="themefisher.com"> -->
        <!-- <?php
                require_once('config.php');
                $PAGE->set_title($SITE->fullname);
                ?> -->


        <!-- <title>Enov| Html5 Business template</title> -->
        <title>MoodlePE | Site vitrine</title>
        <!-- <link rel="icon" href="http://localhost:8080/moodlepe/theme/image.php/adaptable/theme/1659349644/favicon"> -->
        <link rel="icon" href="http://localhost:8080/moodlepe/pix/i/moodle_host.png">
        <!-- <img class=" moodle-logo" src="pix/moodlelogo.svg" alt="Moodle logo" /> -->

        <!-- bootstrap.min css -->
        <link rel="stylesheet" href="local/moodlepe/showcase/ashleyshowcase/theme/plugins/bulma/bulma.min.css">
        <!-- Icon Font Css -->
        <link rel="stylesheet" href="local/moodlepe/showcase/ashleyshowcase/theme/plugins/themify/css/themify-icons.css">
        <link rel="stylesheet" href="local/moodlepe/showcase/ashleyshowcase/theme/plugins/fontawesome/css/all.css">
        <!-- AOS css -->
        <link rel="stylesheet" href="local/moodlepe/showcase/ashleyshowcase/theme/plugins/animate-css/animate.css">
        <link rel="stylesheet" href="local/moodlepe/showcase/ashleyshowcase/theme/plugins/aos/aos.css">
        <link rel="stylesheet" href="local/moodlepe/showcase/ashleyshowcase/theme/plugins/magnific-popup/dist/magnific-popup.css">
        <link rel="stylesheet" href="local/moodlepe/showcase/ashleyshowcase/theme/plugins/modal-video/modal-video.min.css">
        <!-- Owl Carousel CSS -->
        <link rel="stylesheet" href="local/moodlepe/showcase/ashleyshowcase/theme/plugins/slick-carousel/slick/slick.css">
        <link rel="stylesheet" href="local/moodlepe/showcase/ashleyshowcase/theme/plugins/slick-carousel/slick/slick-theme.css">

        <!-- Main Stylesheet -->
        <link rel="stylesheet" href="local/moodlepe/showcase/ashleyshowcase/theme/css/style.css">

    </head>

    <body>


        <!-- navigation -->
        <header class="navigation">
            <div class="header-top is-hidden-mobile">
                <div class="container">
                    <div class="columns is-justify-content-space-between is-align-items-center">
                        <div class="column is-9">
                            <div class="header-top-info">
                                <a href="tel:+23-345-67890"><i class="fa fa-phone mr-2"></i><span>(+228) 22 50 30 82 </span></a>
                                <a href="mailto:support@gmail.com"><i class="fa fa-envelope mr-2"></i><span>ashleysatchivi92@gmail.com</span></a>
                                <!-- <a href="themefisher.com"><i class="fa fa-globe mr-2"></i>Themefisher.com</a> -->
                            </div>
                        </div>
                        <div class="column is-3">
                            <div class="header-top-socials has-text-centered has-text-right-tablet">
                                <!--  <a href="https://www.facebook.com/themefisher" target="_blank"><i class="ti-facebook"></i></a>
                        <a href="https://twitter.com/themefisher" target="_blank"><i class="ti-twitter"></i></a>
                        <a href="https://github.com/themefisher/" target="_blank"><i class="ti-github"></i></a> -->
                                <a href="https://github.com/Ashley-23/Projet_MoodlePE.git" target="_blank"><i class="ti-github"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <nav id="navbar" class="navbar main-nav">
                <div class="container">
                    <div class="navbar-brand ml-0">
                        <a class="navbar-item" href="">
                            <!-- <img src="images/logo/logo.png" alt="logo"> -->
                            <h2 class="has-text-white" style="font-size:2rem">MoodlePE</h2>
                        </a>
                        <button role="button" class="navbar-burger burger" data-hidden="true" data-target="navigation">
                            <span aria-hidden="true"></span>
                            <span aria-hidden="true"></span>
                            <span aria-hidden="true"></span>
                        </button>
                    </div>

                    <div class="navbar-menu mr-0" id="navigation">
                        <ul class="navbar-end">
                            <li class="navbar-item">
                                <a class="navbar-link is-arrowless" href="#accueil">Accueil</a>
                            </li>

                            <li class="navbar-item">
                                <a class="navbar-link is-arrowless" href="#a-propos">A propos</a>
                            </li>

                            <li class="navbar-item has-dropdown is-hoverable">
                                <a class="navbar-link is-arrowless">Services +</a>
                                <div class="navbar-dropdown">
                                    <a class="navbar-item" href="#info">Informations</a>
                                    <a class="navbar-item" href="#formation-en-ligne">Formations en ligne</a>
                                    <a class="navbar-item" href="#nos-chiffres">Nos Chiffres</a>
                                    <a class="navbar-item" href="#nos-fonctionnalites">Nos fonctionnalités</a>
                                </div>
                            </li>

                            <li class="navbar-item">
                                <a class="navbar-link is-arrowless" href="#nos-offre">Nos Offres</a>
                            </li>

                            <li class="navbar-item has-dropdown is-hoverable">
                                <a class="navbar-link is-arrowless">Entreprises +</a>
                                <div class="navbar-dropdown">
                                    <a class="navbar-item" href="#equipe">Blog Grid</a>
                                    <a class="navbar-item" href="#invitation">Invitations</a>
                                    <a class="navbar-item" href="#nos-sponsors">Nos sponsors</a>
                                </div>
                            </li>


                            <li class="navbar-item">
                                <a class="navbar-link is-arrowless" href="#contact">Contact</a>
                            </li>
                            <li class="navbar-item">
                                <a class="navbar-link is-arrowless quote-btn bg-primary rounded-btn letter-spacing is-uppercase" href="/moodlepe/login/index.php">
                                    Connexion <i class="ti-angle-right"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <!-- nav part end -->

        <!-- Slider Start -->
        <section class="slider" id="accueil">
            <div class="container">
                <div class="columns is-justify-content-center">
                    <div class="column is-9-widescreen is-12-desktop">
                        <div class="has-text-centered">
                            <span class="is-block mb-4 is-uppercase">Préparez-vous à une nouvelle expérience</span>
                            <h1 class="animated fadeInUp mb-6 has-text-white">Entamons cette nouvelle expérience ensemble grâce à notre plateforme de formation en ligne.</h1>
                            <a href="#nos-offre" class="btn btn-main animated fadeInUp m-1">Decouvrir<i class="btn-icon fa fa-angle-right ml-2"></i></a>
                            <a href="/moodlepe/login/index.php" class="btn btn-solid-border animated fadeInUp m-1">Connexion</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="mt--6 is-relative slider-cta" id="a-propos">
            <div class="container">
                <div class="columns is-desktop is-align-items-center bg-primary rounded">
                    <div class="column is-8-desktop">
                        <h3 class="mb-4 has-text-white">Pour arriver à vos fins, vous avez à votre disposition tout un arsenal de supports.</h3>
                        <p class="text-white-50">
                            Nous nous efforçons de proposer à nos clients les meilleurs cours, les plus pertinents pour un apprentissage professionnel.
                            Nous ajoutons donc en permanence des cours à notre sélection afin de nous assurer que nos cours sont à jour et pertinents. </p>
                    </div>
                    <div class="column is-4-desktop has-text-right">
                        <!--             <a href="#!" class="btn btn-white mb-0">Learn more</a>
 -->
                    </div>
                </div>
            </div>
        </section>
        <!-- Slider End -->

        <!-- Section Intro Start -->
        <section class="section intro" id="info">
            <div class="container">
                <div class="columns is-desktop is-justify-content-space-between">
                    <div class="column is-5-desktop">
                        <div class="pt-5 mb-4 mb-lg-0">
                            <h2 class="mt-3 text-md font-secondary">
                                Nous fournissons les meilleures solutions à nos clients
                            </h2>
                        </div>
                    </div>

                    <div class="column is-6-desktop">
                        <div class="columns">
                            <div class="column is-6-desktop is-6-tablet" data-aos="fade-up" data-aos-delay="200">
                                <div class="intro-item mb-4 mb-lg-0">
                                    <i class="ti-wand text-color"></i>
                                    <h4 class="mt-4 mb-3">Sécurité</h4>
                                    <p>Nous vous garantissons la meilleur des sécurités lors de vos apprentissages.</p>
                                </div>
                            </div>
                            <div class="column is-6-desktop is-6-tablet">
                                <div class="intro-item mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="300">
                                    <i class="ti-medall text-color"></i>
                                    <h4 class="mt-4 mb-3">Environnement de travail</h4>
                                    <p>Nous vous offrons un meilleur environnement de travail pour une meilleure rentabilité.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Section Intro END -->

        <!-- Section About Start -->
        <section class="about" id="formation-en-ligne">
            <div class="container">
                <div class="columns is-desktop is-align-items-center">
                    <div class="column is-6-desktop">
                        <div class="about-img" data-aos="fade-right" data-aos-delay="200" style="line-height: 0">
                            <img src="local/moodlepe/showcase/ashleyshowcase/theme/images/blog/gestion.jpg" alt="" class="">
                        </div>
                    </div>
                    <div class="column is-6-desktop">
                        <div class="about-item">
                            <h2 class="mt-3 mb-4 font-secondary"> Formation en ligne<br> </h2>
                            <p class="mb-5">
                                La plupart des formations en présentiel se font en groupe et obligent à suivre un rythme imposé par un professeur. Il y a un programme et des horaires stricts à suivre et à tenir, ce qui ne laisse pas le choix à l’apprenant de choisir la manière dont il veut apprendre.
                                Nous vous permettons de suivre les cours dans un délai donné mais en vous donnant la possibilité d'aller à votre rythme.
                            </p>

                            <!-- <a href="#" class="btn btn-main">Get started</a> -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Section About End -->

        <!-- section Counter Start -->
        <section class="section counter" id="nos-chiffres">
            <div class="container">
                <div class="columns is-multiline">
                    <div class="column is-3-desktop is-6-tablet">
                        <div class="counter-item has-text-centered mt-5 " data-aos="fade-up" data-aos-delay="100">
                            <h3 class="mb-0"><span class="counter-stat ">80</span> +</h3>
                            <p class="text-muted">Cours</p>
                        </div>
                    </div>
                    <div class="column is-3-desktop is-6-tablet">
                        <div class="counter-item has-text-centered mt-5" data-aos="fade-up" data-aos-delay="200">
                            <h3 class="mb-0"><span class="counter-stat ">13 </span>+ </h3>
                            <p class="text-muted">Entreprises</p>
                        </div>
                    </div>
                    <div class="column is-3-desktop is-6-tablet">
                        <div class="counter-item has-text-centered mt-5 " data-aos="fade-up" data-aos-delay="300">
                            <h3 class="mb-0"><span class="counter-stat ">20</span>+ </h3>
                            <p class="text-muted">Formateurs</p>
                        </div>
                    </div>
                    <div class="column is-3-desktop is-6-tablet">
                        <div class="counter-item has-text-centered mt-5" data-aos="fade-up" data-aos-delay="400">
                            <h3 class="mb-0"><span class="counter-stat ">1223</span>+ </h3>
                            <p class="text-muted">Fonctionnaires </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- section Counter End  -->

        <!--  Section Services Start -->
        <section class="section service bg-gray" id="nos-fonctionnalites">
            <div class="container">
                <div class="columns is-justify-content-center">
                    <div class="column is-6-widescreen is-8-desktop is-10-tablet has-text-centered">
                        <div class="section-title">
                            <h2 class="mb-4">Nos fonctionnalités</h2>
                            <p>
                                Vouloir ne suffit pas, il faut osez, et vous devez être courageux. La Volonté doit être maintenu ! La force motrice doit toujours être Maintenu !.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="columns is-multiline is-justify-content-center">
                    <div class="column is-4-widescreen is-6-tablet">
                        <div class="card px-5 py-6 has-text-centered" data-aos="fade-up" data-aos-delay="100">
                            <i class="ti-desktop text-color text-lg"></i>
                            <h4 class="mb-3 mt-4">Accessible</h4>
                            <p>
                                Notre plateforme vous fournir les meilleurs informations et vous est accessible en tout temps.
                            </p>
                        </div>
                    </div>
                    <div class="column is-4-widescreen is-6-tablet">
                        <div class="card px-5 py-6 has-text-centered " data-aos="fade-up" data-aos-delay="200">
                            <i class="ti-layers text-color text-lg"></i>
                            <h4 class="mb-3 mt-4 ">Intuitive</h4>
                            <p>Il vous est très facile de naviguer sur notre plateforme et de vous y retrouver.</p>
                        </div>
                    </div>
                    <div class="column is-4-widescreen is-6-tablet">
                        <div class="card px-5 py-6 has-text-centered " data-aos="fade-up" data-aos-delay="300">
                            <i class="ti-bar-chart text-color text-lg"></i>
                            <h4 class="mb-3 mt-4 ">Suivi</h4>
                            <p>Nous vous offrons un suivi minutieux dans l'apprentissage de vos cours.</p>
                        </div>
                    </div>
                    <div class="column is-4-widescreen is-6-tablet">
                        <div class="card px-5 py-6 has-text-centered" data-aos="fade-up" data-aos-delay="200">
                            <i class="ti-vector text-color text-lg"></i>
                            <h4 class="mb-3 mt-4 ">Bonus</h4>
                            <p>
                                « Afin de créer une bonne expérience d’apprentissage , le rôle d’instructeur est facultatif, mais le rôle d’apprenant est essentiel. »
                            </p>
                        </div>
                    </div>
                    <div class="column is-4-widescreen is-6-tablet">
                        <div class="card px-5 py-6 has-text-centered" data-aos="fade-up" data-aos-delay="300">
                            <i class="ti-android text-color text-lg"></i>
                            <h4 class="mb-3 mt-4 ">Applications</h4>
                            <p>
                                Nous ne sommes pas là pour remplacer votre équipe interne, nous sommes là pour nous associe.
                            </p>
                        </div>
                    </div>
                    <div class="column is-4-widescreen is-6-tablet">
                        <div class="card px-5 py-6 has-text-centered" data-aos="fade-up" data-aos-delay="400">
                            <i class="ti-pencil-alt text-color text-lg"></i>
                            <h4 class="mb-3 mt-4 ">Cours</h4>
                            <p>Nous vous accompagnons dans votre processus d'apprentissage.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--  Section Services End -->

        <!-- Section Pricing Start -->
        <section class="section pricing bg-primary is-relative" id="nos-offre">
            <div class="hero-img bg-overlay"></div>
            <div class="container">
                <div class="columns is-justify-content-center">
                    <div class="column is-6-widescreen is-8-desktop is-10-tablet has-text-centered">
                        <div class="section-title">
                            <h2 class="has-text-white mb-4">Nos Offres</h2>
                            <p class="text-white-50">Donnez et Recevez par l'Enseignement et l'Apprentissage. </br>
                                Quelque soit votre choix, tirez le meilleur de nos offres.</p>
                        </div>
                    </div>
                </div>
                <div class="columns is-multiline is-justify-content-center">
                    <div class="column is-4-desktop is-6-tablet">
                        <div class="card has-text-centered mb-lg-0 mb-3" data-aos="fade-down" data-aos-delay="100">
                            <div class="py-6">
                                <div class="pricing-header mb-4 border-bottom pb-4">
                                    <span class="mb-3 h5 plan">Gratuit</span>
                                    <p class="text-muted">Utilisateurs / Mois</p>
                                    <h2>0</h2>
                                    <small>FCFA</small>
                                </div>
                                <ul class="lh-45 mt-3 text-black">
                                    <li>Up to 1 User</li>
                                    <li>Max 100 Item</li>
                                    <li>500 Queries</li>
                                    <li>Basic Statistics</li>
                                </ul>
                                <a href="/moodlepe/local/moodlepe/register/index.php?offre=1" class="btn btn-solid-border mt-5 ">Souscrire</a>
                            </div>
                        </div>
                    </div>
                    <div class="column is-4-desktop is-6-tablet">
                        <div class="card has-text-centered mb-lg-0 mb-3 " data-aos="fade-down" data-aos-delay="300">
                            <div class="py-6">
                                <div class="pricing-header mb-4 border-bottom pb-4 active">
                                    <span class="mb-3 h5 plan ">Basic</span>
                                    <p class="text-muted">Utilisateurs / Mois</p>
                                    <h2>50 000</h2>
                                    <small>FCFA</small>
                                </div>
                                <ul class="lh-45 mt-3 text-black">
                                    <li>Up to 5 User</li>
                                    <li>Max 1000 Item</li>
                                    <li>5000 Queries</li>
                                    <li>Standard Statistics</li>
                                </ul>
                                <a href="/moodlepe/local/moodlepe/register/index.php?offre=2" class="btn btn-main mt-5" aria-disabled="">Souscrire</a>
                            </div>
                        </div>
                    </div>
                    <div class="column is-4-desktop is-6-tablet">
                        <div class="card has-text-centered mb-lg-0 mb-3" data-aos="fade-down" data-aos-delay="500">
                            <div class="py-6">
                                <div class="pricing-header mb-4 border-bottom pb-4">
                                    <span class="mb-3 h5 plan">Premium</span>
                                    <p class="text-muted">Utilisateurs / Mois</p>
                                    <h2>100 000</h2>
                                    <small>FCFA</small>
                                </div>
                                <ul class="lh-45 mt-3 text-black">
                                    <li>Unlimited User</li>
                                    <li>Unlimited Item</li>
                                    <li>Unlimited Queries</li>
                                    <li>Full Statistics</li>
                                </ul>
                                <a href="/moodlepe/local/moodlepe/register/index.php?offre=3" class="btn btn-solid-border mt-5">Souscrire</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Section Pricing End -->

        <!--  Section Team Start -->
        <section class="section team position-relative" id="equipe">
            <div class="container">
                <div class="columns is-justify-content-center">
                    <div class="column is-6-widescreen is-8-desktop is-10-tablet has-text-centered">
                        <div class="section-title">
                            <h2 class="mb-4">Amazing Team</h2>
                            <p>We provide a wide range of creative services adipisicing elit. Autem maxime rem modi eaque, voluptate. Beatae officiis neque </p>
                        </div>
                    </div>
                </div>
                <div class="columns is-multiline is-justify-content-center">
                    <div class="column is-4-desktop is-6-tablet">
                        <div class="team-item-wrap" data-aos="fade-left" data-aos-delay="200">
                            <img src="local/moodlepe/showcase/ashleyshowcase/theme/images/team/team-1.jpg" alt="" class=" w-100 rounded">
                            <div class="team-item-content">
                                <p class="text-sm mb-0">Project Manager</p>
                                <h3 class="mt-0 mb-2 text-capitalize">Justin hammer</h3>
                                <ul class="team-social list-inline ">
                                    <li class="list-inline-item">
                                        <a href="#" class="facebook"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="#" class="twitter"><i class="fab fa-twitter" aria-hidden="true"></i></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="#" class="instagram"><i class="fab fa-instagram" aria-hidden="true"></i></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="#" class="linkedin"><i class="fab fa-linkedin-in" aria-hidden="true"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="column is-4-desktop is-6-tablet">
                        <div class="team-item-wrap" data-aos="fade-left" data-aos-delay="400">
                            <img src="local/moodlepe/showcase/ashleyshowcase/theme/images/team/team-2.jpg" alt="" class=" w-100 rounded">
                            <div class="team-item-content">
                                <p class="text-sm mb-0">Project Manager</p>
                                <h3 class="mt-0 mb-2 text-capitalize">Mikel emily</h3>
                                <ul class="team-social list-inline ">
                                    <li class="list-inline-item">
                                        <a href="#" class="facebook"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="#" class="twitter"><i class="fab fa-twitter" aria-hidden="true"></i></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="#" class="instagram"><i class="fab fa-instagram" aria-hidden="true"></i></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="#" class="linkedin"><i class="fab fa-linkedin-in" aria-hidden="true"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="column is-4-desktop is-6-tablet">
                        <div class="team-item-wrap" data-aos="fade-left" data-aos-delay="600">
                            <img src="local/moodlepe/showcase/ashleyshowcase/theme/images/team/team-3.jpg" alt="" class=" w-100 rounded">
                            <div class="team-item-content">
                                <p class="text-sm mb-0">Project Manager</p>
                                <h3 class="mt-0 mb-2 text-capitalize">David Spensor</h3>
                                <ul class="team-social list-inline ">
                                    <li class="list-inline-item">
                                        <a href="#" class="facebook"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="#" class="twitter"><i class="fab fa-twitter" aria-hidden="true"></i></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="#" class="instagram"><i class="fab fa-instagram" aria-hidden="true"></i></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="#" class="linkedin"><i class="fab fa-linkedin-in" aria-hidden="true"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <!--  Section Team End -->

        <!-- Section Cta Start -->
        <section class="section cta-section is-relative" id="invitation">
            <!-- <div class="bg-2 w-50 bg-absolute is-hidden-touch"></div> -->
            <!-- <div class="bg-23"></div> -->
            <div class="container">
                <div class="columns is-desktop is-align-items-center">
                    <div class="column is-6-desktop">
                        <div class="about-img" data-aos="fade-right" data-aos-delay="200" style="line-height: 0">
                            <img src="local/moodlepe/showcase/ashleyshowcase/theme/images/blog/formation.png" alt="" class="">
                        </div>
                    </div>
                    <div class="columns is-justify-content-flex-end">
                        <!-- <div class="column is-7-desktop"> -->
                        <!-- <div class="is-flex is-align-items-center p-5 is-relative ml-4"> -->
                        <div class="cta-content-block">
                            <h2 class="mt-2 mb-4 font-secondary">Vous avez besoin d'une bonne formation ?</h2>
                            <p>
                                Vous êtes au bon endroit !
                                </br>
                                Consulter nos offres et marchons main dans la main pour vous offrir le meilleur.
                            </p>
                        </div>
                        <!-- </div> -->
                        <!-- </div> -->
                    </div>
                </div>
        </section>
        <!--  Section Cta End-->

        <!-- Section Blog Start -->
        <section class="section bg-gray blog-latest" id="nos-sponsors">
            <div class="container">
                <div class="columns is-justify-content-center">
                    <div class="column is-6-widescreen is-8-desktop is-10-tablet has-text-centered">
                        <div class="section-title">
                            <h2 class="content-title mb-4">Nos sponsors</h2>
                            <p>
                                Toute l’équipe de développement, remercie vivement tous nos partenaires et sponsors pour le soutien moral et financier que vous nous avez accordés.
                                Nous tenons à vous remercier sincèrement pour votre générosité et pour toute l'aide que vous nous avez fourni.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="columns is-multiline is-justify-content-center">
                    <div class="column is-4-desktop is-6-tablet">
                        <div class="shadow-sm-2 blog-item bg-white rounded" data-aos="fade-up" data-aos-delay="200" data-aos-duration="500">
                            <img src="local/moodlepe/showcase/ashleyshowcase/theme/images/blog/logo_iai.jpg" class=" rounded-top" alt="logo d'iai-togo" width="400" height="300">
                            <div class="p-4">
                                <!-- <div class="is-flex w-100 is-justify-content-space-between">
                            <span class="text-black">Marketing</span>
                            <span >by jonas talk</span>
                            <span >3 days ago</span>
                        </div> -->
                                <h3 class="mb-2 mt-2">IAI-TOGO</h3>
                                <p class="mb-4">
                                    “ L’Institut Africain d'Informatique Représentation du TOGO est Une école De Formation Des Ingénieurs De Travaux Informatiques.
                                    L'IAI-TOGO a formé une partie substantielle du corps des Ingénieurs et des Ingénieurs des Travaux en TIC de ses pays membres.”
                                </p>
                                <a href="https://www.iai-togo.tg/" class="btn btn-main">Voir plus</a>
                            </div>
                        </div>
                    </div>
                    <div class="column is-4-desktop is-6-tablet">
                        <div class="shadow-sm-2 blog-item bg-white rounded" data-aos="fade-up" data-aos-delay="300" data-aos-duration="500">
                            <img src="local/moodlepe/showcase/ashleyshowcase/theme/images/blog/logo_dashmake.jpg" class=" rounded-top" alt="logo de dashmake">
                            <div class="p-4">
                                <!--  <div class="is-flex w-100 is-justify-content-space-between">
                            <span class="text-black">Development</span>
                            <span>by jonas talk</span>
                            <span >3 days ago</span>
                        </div> -->
                                <h3 class="mb-2 mt-2">DASHMAKE</h3>
                                <p class="mb-4">
                                    “ Dashmake est l'une des startups numériques les plus prometteuses du Togo
                                    et spécialisée dans les solutions informatiques pour le grand public (applications web/mobile, l’ingénierie réseaux, les jeux vidéos et la publicité 2D/3D).”
                                </p>
                                <a href="https://www.dashmake.com/fr" class="btn btn-main">Voir plus</a>
                            </div>
                        </div>
                    </div>
                    <div class="column is-4-desktop is-6-tablet">
                        <div class="shadow-sm-2 blog-item bg-white rounded" data-aos="fade-up" data-aos-delay="400" data-aos-duration="500">
                            <img src="local/moodlepe/showcase/ashleyshowcase/theme/images/blog/logo_togocom.jpg" class=" rounded-top" alt="logo de togocom" width="400">
                            <div class="p-4">
                                <!-- <div class="is-flex w-100 is-justify-content-space-between">
                            <span class="text-black">Design</span>
                            <span >by jonas talk</span>
                            <span >3 days ago</span>
                        </div> -->
                                <h3 class="mb-2 mt-2">TOGOCOM </h3>
                                <p class="mb-4">
                                    “ Togo Télécom est l'opérateur historique de téléphonie fixe au Togo. Actuellement l'opérateur de téléphone fixe et mobile ont fusionné en 2017 pour devenir Groupe TogoCom. ”
                                </p>
                                <a href="https://togocom.tg/" class="btn btn-main">Voir plus</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Section Blog End -->

        <!-- footer Start -->
        <footer class="footer section" id="contact">
            <div class="container">
                <div class="columns is-multiline is-justify-content-center">
                    <div class="column is-4-desktop is-5-tablet">
                        <div class="widget mb-0">
                            <div class="logo mb-4">
                                <h2 class="has-text-white" style="font-size:2rem">MoodlePE</h2>
                            </div>
                            <a href="mailto:ashleysatchivi92@gmail.com@gmail.com" class="has-text-white">support@moodlepe.com</a>
                            <p class="mt-4 mb-3">Adidogomé, Avé-maria, rue Kogben Doumati, <br> Lomé, TOGO</p>
                            <a href="tel:+23-456-6588"><span class="has-text-white h4">(+228) 22 50 30 82 </span></a>
                        </div>
                    </div>

                    <div class="column is-2-desktop is-5-tablet">
                        <div class="widget has-text-white mb-0">
                            <h4 class="text-capitalize mb-4 has-text-white">Company</h4>

                            <ul class="list-unstyled footer-menu lh-35">
                                <li><a href="#">Terms & Conditions</a></li>
                                <li><a href="#">Privacy Policy</a></li>
                                <li><a href="#">Support</a></li>
                                <li><a href="#">FAQ</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="column is-2-desktop is-5-tablet">
                        <div class="widget mb-0">
                            <h4 class="text-capitalize mb-4 has-text-white">Ressources</h4>

                            <ul class="list-unstyled footer-menu lh-35">
                                <li><a href="https://docs.moodle.org/400/en/Main_page">Moodle</a></li>
                                <li><a href="#">Blogs</a></li>
                                <li><a href="#">Forums</a></li>
                                <li><a href="#">Youtube</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="column is-2-desktop is-5-tablet">
                        <div class="widget mb-0">
                            <h4 class="text-capitalize mb-4 has-text-white">Sponsors</h4>
                            <ul class="list-unstyled footer-socials">
                                <li><a href="https://www.iai-togo.tg/">IAI-TOGO</a></li>
                                <li><a href="https://www.dashmake.com/fr">DASHMAKE</i></a></li>
                                <li><a href="https://togocom.tg/">TOGOCOM</a></li>
                            </ul>

                        </div>
                    </div>
                </div>

                <div class="footer-btm pt-4 pb-3">
                    <div class="columns is-justify-content-center">
                        <div class="column has-text-centered">
                            <div class="copyright">
                                <p>&copy; Copyright 2020 Design &amp; Developed by <a href="mailto:ashleysatchivi92@gmail.com" target="_blank" class=" has-text-white">SATCHIVI Kokoè Yasmine Ashley</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <!-- 
   Essential Scripts
   =====================================-->

        <!-- Main jQuery -->
        <script src="local/moodlepe/showcase/ashleyshowcase/theme/plugins/jquery/jquery.js"></script>
        <script src="local/moodlepe/showcase/ashleyshowcase/theme/js/contact.js"></script>
        <!--  Magnific Popup-->
        <script src="local/moodlepe/showcase/ashleyshowcase/theme/plugins/magnific-popup/dist/jquery.magnific-popup.min.js"></script>
        <!-- Slick Slider -->
        <script src="local/moodlepe/showcase/ashleyshowcase/theme/plugins/slick-carousel/slick/slick.min.js"></script>
        <!-- Counterup -->
        <script src="local/moodlepe/showcase/ashleyshowcase/theme/plugins/counterup/jquery.waypoints.min.js"></script>
        <script src="local/moodlepe/showcase/ashleyshowcase/theme/plugins/counterashleyshowcase/theme/up/jquery.counterup.min.js"></script>
        <script src="local/moodlepe/showcase/ashleyshowcase/theme/plugins/shuffle/shuffle.min.js"></script>
        <script src="local/moodlepe/showcase/ashleyshowcase/theme/plugins/aos/aos.js"></script>
        <script src="local/moodlepe/showcase/ashleyshowcase/theme/plugins/animate-css/wow.min.js"></script>
        <script src="local/moodlepe/showcase/ashleyshowcase/theme/plugins/modal-video/jquery-modal-video.min.js"></script>

        <!-- Google Map -->
        <script src="local/moodlepe/showcase/ashleyshowcase/theme/plugins/google-map/map.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAkeLMlsiwzp6b3Gnaxd86lvakimwGA6UA&callback=initMap"></script>

        <script src="local/moodlepe/showcase/ashleyshowcase/theme/js/script.js"></script>

    </body>

    </html>

<?php
}
?>