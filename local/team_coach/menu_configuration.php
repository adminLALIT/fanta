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
require_login();
$PAGE->set_url('/local/team_coach/menu_configuration.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Menu Configuration');
$PAGE->set_heading('Menu Configuration');
$PAGE->navbar->add('Menu List', 'menu_list.php');
$PAGE->navbar->add('Menu Configuration');
echo $OUTPUT->header();
echo $OUTPUT->heading('Menu Configuration');
// Instantiate simplehtml_form. 
$mform = new configuration_form();

// Form processing and displaying is done here
if ($mform->is_cancelled()) {
    // Handle form cancel operation, if cancel button is present on form.
    redirect("$CFG->wwwroot/local/team_coach/menu_list.php");

} else if ($fromform = $mform->get_data()) {
    $fromform->userid = $USER->id;
    $fromform->time_created = time();
    // In this case you process validated data. $mform->get_data() returns data posted in form.
    $insert = $DB->insert_record('menu_configuration', $fromform, $returnid=true, $bulk=false);
    if ($insert) {
        redirect('menu_configuration.php', 'Record saved successfully', null, \core\output\notification::NOTIFY_INFO);
    }
} else {
  // This branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed.
  // Or on the first display of the form.

  // Set default data (if any)
  $mform->set_data($toform);
  // Displays the form.
  $mform->display();
}
echo $OUTPUT->footer();
?>