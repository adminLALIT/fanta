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

class frontpage_form extends moodleform {
    // Add elements to form
    public function definition() {
        global $CFG, $user, $DB;
        $id = optional_param('id', 0, PARAM_INT);
     
        $mform = $this->_form; // Don't forget the underscore! 
        $editoroptions = $this->_customdata['editoroptions'];
        list($instance) = $this->_customdata;

        $select_theme =  $DB->get_records_sql_menu("SELECT id, name FROM {theme_detail}");
        // Add the new key-value pair at the beginning of the array
        $select_theme = array('' => 'Select') + $select_theme;
        
        $mform->addElement('hidden', 'themeid', $id);

        $mform->addElement('select', 'theme_id', get_string('select_theme','local_team_coach'), $select_theme);
        $mform->addRule('theme_id', get_string('required'), 'required', null, 'server');

        $mform->addElement('text', 'banner_title', get_string('banner_title','local_team_coach'));
        $mform->addRule('banner_title', get_string('missingemail'), 'required', null, 'server');

        $mform->addElement('textarea', 'banner_desc', get_string('banner_desc','local_team_coach'), 'rows="10" cols="100"');
        
        $mform->addElement('filemanager', 'banner_filemanager', get_string('banner_img', 'local_team_coach'), null, $editoroptions);
        if (!$id) {
            # code...
            $mform->addRule('banner_filemanager', get_string('required'), 'required', null, 'server');
        }

        $this->add_action_buttons();
        $this->set_data($instance);
         
    }
    // Custom validation should be added here.
    function validation($data, $files) {
        global $CFG, $DB, $USER;

        $validated = array();
        $data = (object)$data;
        if ($data->banner_title) {
            if (!$data->themeid) {
                if ($DB->record_exists('theme_banner',['userid' => $USER->id, 'banner_title' => $data->banner_title, 'theme_id' => $data->theme_id])) {
                 $validated['banner_title'] = get_string('bannertitleexists', 'local_team_coach');
                }
            } 
        }
        return $validated;
    }
}
