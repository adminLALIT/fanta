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

// moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class section_form extends moodleform {
    // Add elements to form
    public function definition() {
        global $CFG, $user, $DB;
        $id = optional_param('id', 0, PARAM_INT);
     
        $mform = $this->_form; // Don't forget the underscore! 
        $editoroptions = $this->_customdata['editoroptions'];
        list($instance) = $this->_customdata;
        if ($id) {
            $where = 'where id = '.$instance->theme_id.''; 
        }
        else {
            $where = null; 
        }
        $select_theme =  $DB->get_records_sql_menu("SELECT id, name FROM {theme_detail} $where");
        // Add the new key-value pair at the beginning of the array
        $select_theme = array('' => 'Select') + $select_theme;
        
        $mform->addElement('hidden', 'sectionid', $id);

        $mform->addElement('select', 'theme_id', get_string('select_theme', 'local_team_coach'), $select_theme);
        $mform->addRule('theme_id', get_string('required'), 'required', null, 'server');

        $mform->addElement('text', 'section_title', get_string('section_title', 'local_team_coach'));
        $mform->addRule('section_title', get_string('required'), 'required', null, 'server');
        $mform->setType('section_title', PARAM_TEXT);

        $mform->addElement('editor', 'descrip_editor', get_string('descrip_editor', 'local_team_coach'), null, $editoroptions);
        $mform->addRule('descrip_editor', get_string('numberonly', 'local_team_coach'), 'required', 'extraruledata', 'server', false, false);
        $mform->setType('descrip_editor', PARAM_RAW);
        
        $mform->addElement('text', 'section_index', get_string('section_index','local_team_coach'));
        $mform->addRule('section_index', get_string('numberonly', 'local_team_coach'), 'numeric', 'extraruledata', 'server', false, false);
        $mform->addRule('section_index', get_string('required'), 'required', 'extraruledata', 'server', false, false);
        $mform->setType('section_index', PARAM_INT);

        $mform->addElement('filemanager', 'section_filemanager', get_string('section_img', 'local_team_coach'), null, $editoroptions);
        if (!$id) {
            # code...
            $mform->addRule('section_filemanager', get_string('required'), 'required', null, 'server');
        }

        $this->add_action_buttons();
        $this->set_data($instance);
         
    }
    // Custom validation should be added here.
    function validation($data, $files) {
        global $CFG, $DB, $USER;

        $validated = array();
        $data = (object)$data;
        // if ($data->section_title) {
        //     if (!$data->themeid) {
        //         if ($DB->record_exists('theme_section',['userid' => $USER->id, 'section_title' => $data->section_title, 'theme_id' => $data->theme_id])) {
        //          $validated['section_title'] = get_string('sectiontitleexists', 'local_team_coach');
        //         }
        //     } 
        // }
        return $validated;
    }
}
