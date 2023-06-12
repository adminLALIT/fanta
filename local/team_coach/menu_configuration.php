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
require_once ('menu_configuration_form.php');

global $CFG, $PAGE;
$id = optional_param('id', 0, PARAM_INT);
$delete = optional_param('delete', 0, PARAM_BOOL);
$confirm = optional_param('confirm', 0, PARAM_BOOL);
$returnurl = optional_param('returnurl', '', PARAM_LOCALURL);

require_login();
$PAGE->set_url('/local/team_coach/menu_configuration.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('admin');
$PAGE->set_title('Menu Configuration');
$PAGE->set_heading('Menu Configuration');
$PAGE->navbar->add('Menu List', 'menu_list.php');
$PAGE->navbar->add('Menu Configuration');

// Instantiate simplehtml_form. 
if ($id) {
    $instance = $DB->get_record('menu_configuration', array(
    'id' => $id
    ), '*', MUST_EXIST);

    if ($delete && $instance->id) {

        if ($confirm && confirm_sesskey()) {
            $DB->delete_records('menu_configuration', ['id' => $instance->id]);
            redirect($returnurl);
        }
        $strheading = 'Delete theme menu Configuration';
        $PAGE->navbar->add($strheading);
        $PAGE->set_title($strheading);
        echo $OUTPUT->header();
        echo $OUTPUT->heading($strheading);
        $yesurl = new moodle_url('/local/team_coach/menu_configuration.php', array(
          'id' => $instance->id, 'delete' => 1,
          'confirm' => 1, 'sesskey' => sesskey(), 'returnurl' => $returnurl
        ));
        $message = "Do you really want to delete theme menu configuration ?";
        echo $OUTPUT->confirm($message, $yesurl, $returnurl);
        echo $OUTPUT->footer();
        die;
    }
}
else {
    $instance = new stdClass();
    $instance->id = null;
}
$mform = new configuration_form($CFG->wwwroot . '/local/team_coach/menu_configuration.php?id=' . $id, array('id' => $id, $instance ));

// Form processing and displaying is done here
if ($mform->is_cancelled()) {
    // Handle form cancel operation, if cancel button is present on form.
    redirect("$CFG->wwwroot/local/team_coach/menu_list.php");

} else if ($fromform = $mform->get_data()) {
    $fromform->userid = $USER->id;
   if ($fromform->menuid) {
    $fromform->id = $fromform->menuid;
    $fromform->time_modified = time();
    $DB->update_record('menu_configuration', $fromform, $bulk=false);
    redirect($CFG->wwwroot . '/local/team_coach/menu_list.php', 'Record updated successfully', null, \core\output\notification::NOTIFY_INFO);

   }
    $fromform->time_created = time();
    // In this case you process validated data. $mform->get_data() returns data posted in form.
    $insert = $DB->insert_record('menu_configuration', $fromform, $returnid=true, $bulk=false);
    if ($insert) {
        redirect($CFG->wwwroot . '/local/team_coach/menu_list.php', 'Record saved successfully', null, \core\output\notification::NOTIFY_INFO);
    }
}
echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
?>