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
$id = optional_param('id', 0, PARAM_INT);
require_login();
$context = context_system::instance();
$PAGE->set_pagelayout('admin');
$PAGE->set_url('/local/team_coach/index.php');
$PAGE->set_context($context);
$PAGE->navbar->add('Theme');
$PAGE->set_title('Theme');
$PAGE->set_heading('Theme');
$PAGE->requires->js('/local/team_coach/amd/src/theme_color.js');
echo $OUTPUT->header();
echo $OUTPUT->heading('Theme');
//Instantiate simplehtml_form. 

$editoroptions = array('maxfiles' => 1,
    'maxbytes' => 262144,'subdirs' => 0, 'context' => $context,'accepted_types' => array('web_image'));

$instance = new stdClass();
$instance->id = null;    
$instance = file_prepare_standard_filemanager($instance, 'image',
        $editoroptions, context_system::instance(), 'local_team_coach', 'image', null);  

$mform = new theme_form($CFG->wwwroot.'/local/team_coach/index.php?id='.$id, array('editoroptions'=>$editoroptions, $instance));

//Form processing and displaying is done here.
if ($mform->is_cancelled()) {
  redirect($CFG->wwwroot . '/local/team_coach/index.php');
  //Handle form cancel operation, if cancel button is present on form.
} else if ($fromform = $mform->get_data()) {

  $language = $fromform->lang;
  $fromform->lang = implode(",",$language);
  // $new_name = $mform->get_new_filename('logo_path');
  // if ($new_name) {
  //   $path = 'logos/' . $new_name;
  //   $fullpath = "/local/team_coach/" . $path;
  //   $success = $mform->save_file('logo_path', $path, true);  // save file contents.
  //   $filename_sep = explode(".", $new_name);
  
  //   $context = \context_user::instance($USER->id);
  
  //   $file_record = new stdClass();
  //   $file_record->contextid = $context->id; // ID of context.
  //   $file_record->component = 'user'; // Component name.
  //   $file_record->filearea = 'draft'; // File area name.
  //   $file_record->itemid = 0; // Item ID (usually related to the table).
  //   $file_record->filepath = '/'; // File path within the file area.
  //   $file_record->filename = $new_name; // Filename.

  //   // Create a file record
  //   $fs = get_file_storage();
  //   $testdocx = $fs->get_file($context->id, 'user', 'draft', 0, '/', $new_name);
  //   if (!$testdocx) {
  //     // Save the file record in the database
  //     $id = $fs->create_file_from_pathname($file_record, $path);
  //     $fileid = $id->get_id(); // $storedFile is the object you provided
  
  //   }
  //   else {
  //     $file_record->filename = $filename_sep[0]."_".time().".".$filename_sep[1]; // Filename
  //         // Save the file record in the database.
  //         $id = $fs->create_file_from_pathname($file_record, $path);
  //         $fileid = $id->get_id(); // $storedFile is the object you provided.
      
  //   }
  //   if (file_exists($path)) {
  //     unlink($path);
  //   }
  // }





  if ($fromform->themeid) {  // if we edit the theme.
    $fromform->id = $fromform->themeid;
    if ($fileid) {
      $fromform->fileid = $fileid;
    }
    $fromform->time_modified = time();
   $updated = $DB->update_record('theme_detail', $fromform, $bulk=false);
    if ($updated) {
      redirect($CFG->wwwroot . '/local/team_coach/team_list.php', 'Record updated Successfully', null, \core\output\notification::NOTIFY_INFO);
    }
  }
  else {  // create a new theme.
  
    $fromform->userid = $USER->id;
    $fromform->fileid = $fileid;
    $fromform->time_created = time();
  
    $insert_record = $DB->insert_record('theme_detail', $fromform, $returnid = true, $bulk = false);
    $ins->timecreated = time();
    $editoroptions['context'] = $context;
    
    // save the upload image 
    $ins = file_postupdate_standard_filemanager($fromform, 'image',
            $editoroptions, context_system::instance(), 'local_team_coach', 'image', $insert_record);
    $ins->id = $insert_record;
  
    $ins->id =$DB->update_record('theme_detail', $ins);  

  }
  //In this case you process validated data. $mform->get_data() returns data posted in form.
  if ($insert_record) {

  
    redirect($CFG->wwwroot . '/local/team_coach/index.php', 'Record Created Successfully', null, \core\output\notification::NOTIFY_INFO);
  }
} else {
  // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed.
  // Or on the first display of the form.
if ($id) {
$toform = $DB->get_record('theme_detail',['id' =>$id]);
$mform->set_data($toform);
}
  //Set default data (if any).
  //displays the form.
  $mform->display();
}
echo $OUTPUT->footer();
