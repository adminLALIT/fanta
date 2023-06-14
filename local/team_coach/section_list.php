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
 * Run the code checker from the web.
 *
 * @package    local_team_coach
 * @copyright  2011 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once '../../config.php';
require "$CFG->libdir/tablelib.php";
require_once('lib.php');
require_once("menu_list_mform.php");
require "section_list_form.php";

$return = new moodle_url('/local/team_coach/section_list.php');
$delete = optional_param('delete', 0, PARAM_INT);
$fileid = optional_param('fileid', 0, PARAM_INT);
$confirm   = optional_param('confirm', 0, PARAM_BOOL);
$returnurl = optional_param('returnurl', '', PARAM_LOCALURL);

require_login();
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url($CFG->wwwroot . '/local/team_coach/section_list.php');
$PAGE->set_title('Manage Section List');
$PAGE->set_heading('Manage Section List');
$PAGE->set_pagelayout('standard');
$PAGE->navbar->add('Manage theme', new moodle_url('/local/team_coach/team_list.php'));
$PAGE->navbar->add('Manage Section', new moodle_url('/local/team_coach/section_list.php'));
$PAGE->navbar->add('Section List');
echo $OUTPUT->header();
echo $OUTPUT->heading('Manage Sections');
// Perform the redirection before any page output
if (optional_param('cancel', false, PARAM_BOOL)) {
    redirect(new moodle_url('/local/team_coach/section_list.php'));
}

$table = new section_list_form('uniqueid');
$mform = new menu_list_mform();
if ($mform->is_cancelled()) {
    // Handle form cancel operation, if cancel button is present on form.
} else if ($fromform = $mform->get_data()) {
if ($fromform->theme_id) {
    $where = 'td.id = '.$fromform->theme_id.'';
}
else {
    $where = '1=1';
}
}
else {
    $where = '1=1';
}

echo html_writer::start_tag('div', ['id'=>'buttonid', 'style'=>'float:right;']);
echo html_writer::link($CFG->wwwroot.'/local/team_coach/section.php', 'Add New Section', ['class'=>'btn btn-secondary']);
echo '&nbsp;&nbsp;&nbsp;';
echo html_writer::end_tag('div');
echo '<br><br>';

$table->no_sorting('action');
// $table->no_sorting('logo');
// Work out the sql for the table.
$from = '{theme_section} ts JOIN {theme_detail} td ON ts.theme_id = td.id';
$table->set_sql('ts.*, td.name', $from, $where);
$table->define_baseurl("$CFG->wwwroot/local/team_coach/section_list.php");
$mform->display();
$table->out(10, true);
echo $OUTPUT->footer();
