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
// GNU General Public License for more banners.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Run the code checker from the web.
 *
 * @package    local_team_coach
 * @copyright  2011 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('../../config.php');
require_once($CFG->dirroot . '/local/team_coach/lib.php');

global $DB, $COURSE, $PAGE, $OUTPUT, $USER, $CFG;

$id = optional_param('id', 0, PARAM_INT);

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/local/team_coach/content.php');

if ($id) {
    $PAGE->navbar->add('Content');
}

if ($id) {

    $subvalue = $DB->get_record('theme_subbanner', ['id' => $id]);
    if ($subvalue) {
            $logo_url = get_subbanner_logo_by_bannerid($subvalue->id);
            $image_url = get_subbanner_image_by_bannerid($subvalue->id);
            $themesubanners[] = ['logo' => $logo_url, 'url' => $subvalue->bannerurl, 'title' => $subvalue->bannertitle, 'text' => $subvalue->bannertext, 'background' => $subvalue->backgroundcolor, 'id' => $subvalue->id, 'image' => $image_url, 'desc' => $subvalue->subbannerdesc];
            $PAGE->set_title($subvalue->bannertitle);
            $PAGE->set_heading($subvalue->bannertitle);
    }
}

$data = [
    'themesubanners' => $themesubanners,
];

echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_team_coach/content', $data);
echo $OUTPUT->footer();
