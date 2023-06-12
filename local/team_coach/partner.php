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
// GNU General Public License for more partners.
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
require_once('lib.php');
require_once('create_partner_form.php');
global $CFG, $PAGE;

$id = optional_param('id', 0, PARAM_INT);
$return = new moodle_url('/local/team_coach/partner_list.php');
$delete = optional_param('delete', 0, PARAM_BOOL);
$confirm = optional_param('confirm', 0, PARAM_BOOL);
$returnurl = optional_param('returnurl', '', PARAM_LOCALURL);

require_login();
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/local/team_coach/partner.php');
$PAGE->set_pagelayout('admin');
$PAGE->navbar->add('Manage Partner Logo', new moodle_url('/local/team_coach/partner_list.php'));
if ($id) {
    $PAGE->navbar->add('Edit Partner Logo');
} else {
    $PAGE->navbar->add('Add New Partner Logo');
}
$PAGE->set_title('Manage Partner Logo');
$PAGE->set_heading('Manage Partner Logo');

$editoroptions = array(
    'maxfiles' => 1,
    'maxbytes' => 262144, 'subdirs' => 0, 'context' => $context, 'accepted_types' => array('web_image')
);

if ($id) {
    $editoroptions['subdirs'] = file_area_contains_subdirs(context_system::instance(), 'local_team_coach', 'descrip', $id);
    $instance = $DB->get_record('theme_partner', array('id' => $id), '*', MUST_EXIST);
    $instance = file_prepare_standard_editor($instance, 'descrip', $editoroptions, context_system::instance(), 'local_team_coach', 'descrip', $instance->id);
    file_prepare_standard_filemanager(
        $instance,
        'partner',
        $editoroptions,
        context_system::instance(),
        'local_team_coach',
        'partner',
        $instance->id
    );

    if ($delete && $instance->id) {

        if ($confirm && confirm_sesskey()) {
            // Delete existing files first.
            $fs = get_file_storage();
            $fs->delete_area_files(context_system::instance()->id, 'local_team_coach', 'partner', $instance->id);
            $DB->delete_records('theme_partner', ['id' => $instance->id]);
            redirect($returnurl);
        }
        $strheading = 'Delete Theme partner';
        $PAGE->navbar->add($strheading);
        $PAGE->set_title($strheading);
        echo $OUTPUT->header();
        echo $OUTPUT->heading($strheading);
        $yesurl = new moodle_url('/local/team_coach/partner.php', array(
            'id' => $instance->id, 'delete' => 1,
            'confirm' => 1, 'sesskey' => sesskey(), 'returnurl' => $returnurl
        ));
        $message = "Do you really want to delete theme partner logo?";
        echo $OUTPUT->confirm($message, $yesurl, $returnurl);
        echo $OUTPUT->footer();
        die;
    }
} else {
    $editoroptions['subdirs'] = 0;
    $instance = new stdClass();
    $instance->id = null;
    $instance = file_prepare_standard_filemanager(
        $instance,
        'partner',
        $editoroptions,
        context_system::instance(),
        'local_team_coach',
        'partner',
        null
    );
}
//Instantiate simplehtml_form 
$mform = new partner_form($CFG->wwwroot . '/local/team_coach/partner.php?id=' . $id, array(
    'editoroptions' => $editoroptions, 'id'=> $id,  $instance
));

//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect($return);
} else if ($fromform = $mform->get_data()) {
    //In this case you process validated data. $mform->get_data() returns data posted in form.
    $fromform->userid = $USER->id;
    
    if ($fromform->partnerid) {  // If we edit the theme.
        $fromform->id = $fromform->partnerid;
        $fromform->time_modified = time();     
        $ins = file_postupdate_standard_filemanager(
            $fromform,
            'partner',
            $editoroptions,
            context_system::instance(),
            'local_team_coach',
            'partner',
            $fromform->id
        );
        $ins->id = $fromform->partnerid;
        $updated = $DB->update_record('theme_partner', $ins);
        if ($updated) {
            redirect($CFG->wwwroot . '/local/team_coach/partner_list.php', 'Record updated Successfully', null, \core\output\notification::NOTIFY_INFO);
        }
    } else {
        $fromform->time_created = time();
        $learnid = $DB->insert_record('theme_partner', $fromform, $returnid = true, $bulk = false);
        $editoroptions['context'] = $context;
        $ins = file_postupdate_standard_filemanager(
            $fromform,
            'partner',
            $editoroptions,
            context_system::instance(),
            'local_team_coach',
            'partner',
            $learnid
        );
        $ins->id = $learnid;
        $ins->id = $DB->update_record('theme_partner', $ins);
        redirect($return, 'Partner Created Successfully', null, \core\output\notification::NOTIFY_INFO);
    }
}
echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
