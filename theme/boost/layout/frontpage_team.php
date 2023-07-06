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
 * A two column layout for the boost theme.
 *
 * @package   theme_boost
 * @copyright 2016 Damyon Wiese
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $DB, $COURSE, $PAGE, $OUTPUT, $USER;
user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
require_once($CFG->libdir . '/behat/lib.php');
require_once($CFG->dirroot . '/local/team_coach/lib.php');
$query = optional_param('query', '', PARAM_TEXT);
$regisstatus = optional_param('status', '', PARAM_TEXT);
$registered = '';
$msgerror = '';

// Show error.
if (!empty($regisstatus)) {
    if ($regisstatus == 'sucess') {
       $registered = get_string('registersuccess', 'theme_boost');
   }
   elseif ($regisstatus == 'invalidpassword') {
    $msgerror = get_string('invalidpassword', 'theme_boost');
   }
   else {
    $msgerror = get_string($regisstatus);
   }
}

if ($query) {
    $querystatus = get_string('querysent', 'theme_boost');
} else {
    $querystatus = '';
}

$remember = '';
// Show username if set.
$saveduser = get_moodle_cookie();
if (!empty($saveduser)) {
    $remember = 'checked';
}

$domain_get = explode('.', @$_SERVER['HTTP_HOST']);
$domain = $domain_get[0];
if (isloggedin()) {
    $navdraweropen = (get_user_preferences('drawer-open-nav', 'true') == 'true');
} else {
    $navdraweropen = false;
}

$extraclasses = [];
if ($navdraweropen) {
    $extraclasses[] = 'drawer-open-left';
}
$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = strpos($blockshtml, 'data-block=') !== false;
$buildregionmainsettings = !$PAGE->include_region_main_settings_in_header_actions();
// If the settings menu will be included in the header then don't add it here.
$regionmainsettingsmenu = $buildregionmainsettings ? $OUTPUT->region_main_settings_menu() : false;

$partnerlogs = [];
$sections = [];
$themesubanners = [];
$footercontent = [];
$bannercontent = [];
$profilecontent = [];
$lang = [];
$place = '';
$profession = '';
$policy = '';
$enableregister = false;

if ($DB->record_exists_sql("SELECT * FROM {theme_detail} WHERE url = '$domain'")) {

    // Get theme record.
    $themerecord = $DB->get_record_sql("SELECT * FROM {theme_detail} WHERE url = '$domain'");  
    if ($themerecord->signup == 1) {
        $enableregister = true;
    }
    $themecolor = $themerecord->theme_color;
    $language = explode(",", $themerecord->lang);
    $translations = get_string_manager()->get_list_of_translations();
    for ($i = 0; $i < count($language); $i++) {
      $lang[] = ['key' => $language[$i], 'value' => $translations[$language[$i]]];
    }

    $contactuslogo = get_contactus_logo_by_themeid($themerecord->id);
    // Get section data.
    $sectiondata = $DB->get_records('theme_section', ['theme_id' => $themerecord->id], $sort = 'section_index ASC');
    if ($sectiondata) {
        foreach ($sectiondata as $sectionvalue) {
            $logo_url = get_sectionimage_by_id($sectionvalue->id);
            $sections[] = ['logo' => $logo_url, 'title' => $sectionvalue->section_title, 'description' => $sectionvalue->descrip];
        }
    }

    // Get partner logos.
    $partnerdata = $DB->get_records('theme_partner', ['theme_id' => $themerecord->id]);
    if ($partnerdata) {
        foreach ($partnerdata as $partnerimage) {
            $logo_url = get_partnerimage_by_id($partnerimage->id);
            $partnerlogs[] = ['logo' => $logo_url, 'url' => $partnerimage->partner_link];
        }
    }

    // Get subbanners.
    $subbanners = $DB->get_records('theme_subbanner', ['themeid' => $themerecord->id]);
    if ($subbanners) {
        foreach ($subbanners as $subvalue) {
            $logo_url = get_subbanner_logo_by_bannerid($subvalue->id);
            $themesubanners[] = ['logo' => $logo_url, 'url' => $subvalue->bannerurl, 'title' => $subvalue->bannertitle, 'text' => $subvalue->bannertext, 'background' => $subvalue->backgroundcolor];
        }
    }

    // Get footer content.
    $footer = $DB->get_records('theme_footer_content', ['theme_id' => $themerecord->id], $sort = 'content_index ASC');
    if ($footer) {
        foreach ($footer as $content) {
            $footercontent[] = ['title' => $content->content_title, 'text' => $content->contentdesc];
        }
    }
    $active = ['active'];
    $i = 0;
    // Get banner content.
    $banner = $DB->get_records('theme_banner', ['theme_id' => $themerecord->id]);
    if ($banner) {
        foreach ($banner as $content) {
            $imageurl = get_banner_by_theme_id($content->id);
            if (!empty($active[$i])) {
                $initial = $active[$i];
            } else {
                $initial = '';
            }
            $bannercontent[] = ['image' => $imageurl, 'title' => $content->banner_title, 'text' => $content->banner_desc, 'active' => $initial];
            $i++;
        }
    }

    // Get profile field data.
    $profilefields = $DB->get_record('theme_profilefield', ['themeid' => $themerecord->id]);
    $fields =  explode(",", $profilefields->profilefield);
    $place = [];
    $profession = [];
    $policy = [];
    if (in_array("place", $fields)) {
        $place[] = ['place' => 'place'];
    }
    if (in_array("profession", $fields)) {
        $profession[] = ['profession' => 'profession'];
    }
    if (in_array("policy", $fields)) {
        $policy[] = ['policy' => 'policy'];
    }

} else {
    $themecolor = '#D9B5AF';
    $contactuslogo = '';
}

$loginurl = $CFG->wwwroot . "/login/index.php";
$errormsg = '';
$errorcode = optional_param('errorcode', '', PARAM_INT);

if ($errorcode == 1) {
    $errormsg = get_string("cookiesnotenabled");
} else if ($errorcode == 2) {
    $errormsg = get_string('username') . ': ' . get_string("invalidusername");
} else if ($errorcode == 3) {
    $errormsg = get_string("invalidlogin");
} else if ($errorcode == 4) {
    $errormsg = get_string('sessionerroruser', 'error');
} else if ($errorcode == 5) {
    $errormsg = get_string("unauthorisedlogin", "", $frm->username);
} else if (!empty($SESSION->loginerrormsg)) {
    // We had some errors before redirect, show them now.
    $errormsg = $SESSION->loginerrormsg;
    unset($SESSION->loginerrormsg);
}

$logourl = $OUTPUT->get_compact_logo_url();

$logouturl = $CFG->wwwroot . "/login/forgot_password.php";
$unplaceholder = get_string('unplaceholder', 'theme_boost');

$unfpassword = get_string('unfpassword', 'theme_boost');

$uncaccount = get_string('uncaccount', 'theme_boost');

$logintxttestimonials = get_string('logintxttestimonials', 'theme_boost');

$unlogin = get_string('unlogin', 'theme_boost');
$unpassword = get_string('password');
$coursesearch = get_string('coursesearch');
$bannerslider = '';
$go = get_string('go');
$templatecontext = [
    'sesskey' => sesskey(),
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'sidepreblocks' => $blockshtml,
    'logourl' => $logourl,
    'hasblocks' => $hasblocks,
    'bodyattributes' => $bodyattributes,
    'navdraweropen' => $navdraweropen,
    'errmsg' => $errormsg,
    'bannerslider' => $bannerslider,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'loginurl' => $loginurl,
    'logouturl' => $logouturl,
    'unplaceholder' => $unplaceholder,
    'unpassword' => $unpassword,
    'unfpassword' => $unfpassword,
    'uncaccount' => $uncaccount,
    'unlogin' => $unlogin,
    'fpcoursesearch' => $coursesearch,
    'fpgo' => $go,
    'logintxttestimonials' => $logintxttestimonials,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
    'partnerlogs' => $partnerlogs,
    'sections' => $sections,
    'themesubanners' => $themesubanners,
    'footercontent' => $footercontent,
    'bannercontent' => $bannercontent,
    'themecolor' => $themecolor,
    'status' => $querystatus,
    'contactuslogo' => $contactuslogo,
    'profilecontent' => $profilecontent,
    'place' => $place,
    'profession' => $profession,
    'policy' => $policy,
    'lang' => $lang,
    'registered' => $registered,
    'msgerror' => $msgerror,
    'enableregister' => $enableregister,
    'saveduser' => $saveduser,
    'remember' => $remember
];
$nav = $PAGE->flatnav;
$templatecontext['flatnavigation'] = $nav;
$templatecontext['firstcollectionlabel'] = $nav->get_collectionlabel();
echo $OUTPUT->render_from_template('theme_boost/frontpage_team', $templatecontext);
