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
// GNU General Public License for more create_subbanners.
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
require_once('subbanner_form.php');
global $CFG, $PAGE;

$id = optional_param('id', 0, PARAM_INT);
$return = new moodle_url('/local/team_coach/subbanner_list.php');
$delete = optional_param('delete', 0, PARAM_BOOL);
$confirm = optional_param('confirm', 0, PARAM_BOOL);
$returnurl = optional_param('returnurl', '', PARAM_LOCALURL);

require_login();
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/local/team_coach/create_subbanner.php');
$PAGE->set_pagelayout('admin');
$PAGE->navbar->add('Manage Sub banners', new moodle_url('/local/team_coach/subbanner_list.php'));
if ($id) {
    $heading = 'Edit Sub Banner';
} else {
    $heading = 'Add New Sub Banner';
}
$PAGE->navbar->add($heading);
$PAGE->requires->js('/local/team_coach/amd/src/theme_color.js');

$editoroptions = array(
    'maxfiles' => 1,
    'maxbytes' => 262144, 'subdirs' => 0, 'context' => $context, 'accepted_types' => array('web_image')
);

if ($id) {
    $editoroptions['subdirs'] = file_area_contains_subdirs(context_system::instance(), 'local_team_coach', 'subbannerdesc', $id);
    $instance = $DB->get_record('theme_subbanner', array('id' => $id), '*', MUST_EXIST);
    $instance = file_prepare_standard_editor($instance, 'subbannerdesc', $editoroptions, context_system::instance(), 'local_team_coach', 'subbannerdesc', $instance->id);
    file_prepare_standard_filemanager(
        $instance,
        'banner',
        $editoroptions,
        context_system::instance(),
        'local_team_coach',
        'banner',
        $instance->id
    );
    file_prepare_standard_filemanager(
        $instance,
        'bannerimg',
        $editoroptions,
        context_system::instance(),
        'local_team_coach',
        'bannerimg',
        $instance->id
    );
    if ($delete && $instance->id) {

        if ($confirm && confirm_sesskey()) {
            // Delete existing files first.
            $fs = get_file_storage();
            $fs->delete_area_files(context_system::instance()->id, 'local_team_coach', 'subbannerdesc', $instance->id);
            $fs->delete_area_files(context_system::instance()->id, 'local_team_coach', 'banner', $instance->id);
            $fs->delete_area_files(context_system::instance()->id, 'local_team_coach', 'bannerimg', $instance->id);
            $DB->delete_records('theme_subbanner', ['id' => $instance->id]);
            redirect($returnurl);
        }
        $strheading = 'Delete Theme Sub Banner';
        $PAGE->navbar->add($strheading);
        $PAGE->set_title($strheading);
        echo $OUTPUT->header();
        echo $OUTPUT->heading($strheading);
        $yesurl = new moodle_url('/local/team_coach/create_subbanner.php', array(
            'id' => $instance->id, 'delete' => 1,
            'confirm' => 1, 'sesskey' => sesskey(), 'returnurl' => $returnurl
        ));
        $message = "Do you really want to delete theme Sub Banner content?";
        echo $OUTPUT->confirm($message, $yesurl, $returnurl);
        echo $OUTPUT->footer();
        die;
    }
} else {
    $editoroptions['subdirs'] = 0;
    $instance = new stdClass();
    $instance->id = null;
    $instance = file_prepare_standard_editor($instance, 'subbannerdesc', $editoroptions, context_system::instance(), 'local_team_coach', 'subbannerdesc', null);
}
//Instantiate simplehtml_form 
$mform = new subanner_form($CFG->wwwroot . '/local/team_coach/create_subbanner.php?id=' . $id, array(
    'editoroptions' => $editoroptions, 'id' => $id,  $instance
));

//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect($return);
} else if ($fromform = $mform->get_data()) {
    //In this case you process validated data. $mform->get_data() returns data posted in form.
    $fromform->userid = $USER->id;

    if ($fromform->bannerid) {  // If we edit the theme.
        $fromform->id = $fromform->bannerid;
        $fromform->time_modified = time();
        if ($fromform->subbannerdesc_editor) {
            $data = file_postupdate_standard_editor($fromform, 'subbannerdesc', $editoroptions, context_system::instance(), 'local_team_coach', 'subbannerdesc', $fromform->bannerid);
            $DB->set_field('theme_subbanner', 'subbannerdesc', $data->subbannerdesc, array('id' => $fromform->bannerid));
            $DB->set_field('theme_subbanner', 'subbannerdescformat', $data->subbannerdescformat, array('id' => $fromform->bannerid));
        }
        file_postupdate_standard_filemanager(
            $fromform,
            'banner',
            $editoroptions,
            context_system::instance(),
            'local_team_coach',
            'banner',
            $fromform->id
        );
        $ins = file_postupdate_standard_filemanager(
            $fromform,
            'bannerimg',
            $editoroptions,
            context_system::instance(),
            'local_team_coach',
            'bannerimg',
            $fromform->id
        );
        $ins->id = $fromform->bannerid;
        $updated = $DB->update_record('theme_subbanner', $ins);
        if ($updated) {
            redirect($return, 'Record Updated Successfully', null, \core\output\notification::NOTIFY_INFO);
        }
    } else {
        $fromform->time_created = time();
        $learnid = $DB->insert_record('theme_subbanner', $fromform, $returnid = true, $bulk = false);
        $editoroptions['context'] = $context;
        $data = file_postupdate_standard_editor($fromform, 'subbannerdesc', $editoroptions, context_system::instance(), 'local_team_coach', 'subbannerdesc',  $learnid);
        $DB->set_field('theme_subbanner', 'subbannerdesc', $data->subbannerdesc, array('id'=>$learnid));
        $DB->set_field('theme_subbanner', 'subbannerdescformat', $data->subbannerdescformat, array('id'=>$learnid));

        file_postupdate_standard_filemanager(
            $fromform,
            'banner',
            $editoroptions,
            context_system::instance(),
            'local_team_coach',
            'banner',
            $learnid
        );

        $ins = file_postupdate_standard_filemanager(
            $fromform,
            'bannerimg',
            $editoroptions,
            context_system::instance(),
            'local_team_coach',
            'bannerimg',
            $learnid
        );
        $ins->id = $learnid;
        $updated = $DB->update_record('theme_subbanner', $ins);
        
        redirect($return, 'Sub Banner created successfully', null, \core\output\notification::NOTIFY_INFO);
    }
}
$PAGE->set_title($heading);
$PAGE->set_heading($heading);
echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
