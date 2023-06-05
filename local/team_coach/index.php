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

//include theme_form.php.
require_once('../../config.php');
require_once('form.php');
global $CFG, $PAGE;
require_login();
$PAGE->set_url('/local/team_coach/index.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->navbar->add('Theme');
$PAGE->set_title('Theme');
$PAGE->set_heading('Theme');
$PAGE->requires->js('/local/team_coach/amd/src/theme_color.js');
echo $OUTPUT->header();
echo $OUTPUT->heading('Theme');
//Instantiate simplehtml_form. 
$mform = new theme_form();

//Form processing and displaying is done here.
if ($mform->is_cancelled()) {
  redirect($CFG->wwwroot.'/local/team_coach/index.php');
    //Handle form cancel operation, if cancel button is present on form.
} else if ($fromform = $mform->get_data()) {

  $new_name = $mform->get_new_filename('logo_path');
  $path= 'logos/'.$new_name;
  $fullpath = "/local/team_coach/". $path;
  $success = $mform->save_file('logo_path', $path, true);  // save file contents

  $fromform->userid = $USER->id;
  $fromform->logo_path = $fullpath;
  $fromform->time_created = time();
  //In this case you process validated data. $mform->get_data() returns data posted in form.
 $insert_record = $DB->insert_record('theme_detail', $fromform, $returnid=true, $bulk=false);
 if ($insert_record) {
  redirect($CFG->wwwroot.'/local/team_coach/index.php', 'Record Created Successfully', null, \core\output\notification::NOTIFY_INFO);

 }
} else {
  // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed.
  // Or on the first display of the form.

  //Set default data (if any).
  $mform->set_data($toform);
  //displays the form
  $mform->display();
}
echo $OUTPUT->footer();
?>