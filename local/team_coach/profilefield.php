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

// Include theme_form.php.
require_once('../../config.php');
require_once('profilefield_form.php');
global $CFG, $PAGE;
$id = optional_param('id', 0, PARAM_INT);
$return = new moodle_url('/local/team_coach/index.php');
$delete = optional_param('delete', 0, PARAM_BOOL);
$confirm = optional_param('confirm', 0, PARAM_BOOL);
$returnurl = optional_param('returnurl', '', PARAM_LOCALURL);
require_login();

$context = context_system::instance();
$PAGE->set_url('/local/team_coach/index.php');
$PAGE->set_context($context);
$PAGE->set_pagelayout('standard');
$PAGE->navbar->add('Manage Theme', new moodle_url('/local/team_coach/team_list.php'));
if ($id) {
    $PAGE->navbar->add('Edit Profile Fields');
} else {
    $PAGE->navbar->add('Add new Profile Fields');
}
$PAGE->set_title('Manage Profile Fields');
$PAGE->set_heading('Manage Profile Fields');


if ($id) {
    $instance = $DB->get_record('theme_profilefield', array(
    'id' => $id
    ), '*', MUST_EXIST);

    if ($delete && $instance->id) {

        if ($confirm && confirm_sesskey()) {
            // Delete existing files first.
            $DB->delete_records('theme_profilefield', ['id' => $instance->id]);
            redirect($returnurl);
        }
        $strheading = 'Delete theme profilefield Configuration';
        $PAGE->navbar->add($strheading);
        $PAGE->set_title($strheading);
        echo $OUTPUT->header();
        echo $OUTPUT->heading($strheading);
        $yesurl = new moodle_url('/local/team_coach/profilefield.php', array(
          'id' => $instance->id, 'delete' => 1,
          'confirm' => 1, 'sesskey' => sesskey(), 'returnurl' => $returnurl
        ));
        $message = "Do you really want to delete theme profilefield configuration ?";
        echo $OUTPUT->confirm($message, $yesurl, $returnurl);
        echo $OUTPUT->footer();
        die;
    }
} else {
    $instance = new stdClass();
    $instance->id = null;
}

$mform = new profession_form(null, array('id' => $id,
    $instance
));
// Form processing and displaying is done here.
if ($mform->is_cancelled()) {
    redirect($CFG->wwwroot . '/local/team_coach/profilefield_list.php');
    // Handle form cancel operation, if cancel button is present on form.
} else if ($fromform = $mform->get_data()) {

    $fromform->profilefield = implode(",", $fromform->profilefield);
    if ($fromform->id) {  // If we edit the theme.
        $fromform->time_modified = time();
        $updated = $DB->update_record('theme_profilefield', $fromform);
        if ($updated) {
            redirect($CFG->wwwroot . '/local/team_coach/profilefield_list.php', 'Record updated Successfully', null, \core\output\notification::NOTIFY_INFO);
        }
    } else {  
    $fromform->userid = $USER->id;
    $fromform->time_created = time();
    $learnid = $DB->insert_record('theme_profilefield', $fromform, $returnid = true, $bulk = false);
    }
    // In this case you process validated data. $mform->get_data() returns data posted in form.
    if ($learnid) {
        redirect($CFG->wwwroot . '/local/team_coach/team_list.php', 'Record Created Successfully', null, \core\output\notification::NOTIFY_INFO);
    }
}
echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
