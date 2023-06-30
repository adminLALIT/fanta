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
require_once($CFG->dirroot.'/local/team_coach/lib.php');

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
// get partner logos
if ($DB->record_exists_sql("SELECT * FROM {theme_detail} WHERE url = '$domain'")) {
    $themerecord = $DB->get_record_sql("SELECT * FROM {theme_detail} WHERE url = '$domain'");
    $partnerdata = $DB->get_records('theme_partner', ['theme_id' => $themerecord->id]);
    foreach($partnerdata as $partnerimage){
        $logo_url = get_partnerimage_by_id($partnerimage->id);
        $partnerlogs[] = ['logo' => $logo_url, 'url' => $partnerimage->partner_link];
    }
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
    'partnerlogs'=> $partnerlogs
];
$nav = $PAGE->flatnav;
$templatecontext['flatnavigation'] = $nav;
$templatecontext['firstcollectionlabel'] = $nav->get_collectionlabel();
echo $OUTPUT->render_from_template('theme_boost/frontpage_team', $templatecontext);
