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

class configuration_form extends moodleform {
    // Add elements to form
    public function definition() {
        global $CFG, $user, $DB;
        $id = optional_param('id', 0, PARAM_INT);
     
        $mform = $this->_form; // Don't forget the underscore! 
        $select_theme =  $DB->get_records_sql_menu("SELECT id, name FROM {theme_detail}");
        // Add the new key-value pair at the beginning of the array
        $select_theme = array('' => 'Select') + $select_theme;
      
        $mform->addElement('select', 'theme_id', get_string('select_theme','local_team_coach'), $select_theme);
        $mform->addRule('theme_id', get_string('required'), 'required', null, 'server');

        $mform->addElement('text', 'menu_name', get_string('menu_name','local_team_coach'));
        $mform->addRule('menu_name', get_string('missingemail'), 'required', null, 'server');

        $mform->addElement('text', 'menu_url', get_string('menu_url','local_team_coach'));
        $mform->addRule('menu_url', get_string('required'), 'required', null, 'server');
        
        $mform->addElement('text', 'menu_index', get_string('menu_index','local_team_coach'));
        $mform->addRule('menu_index', get_string('number_error','local_team_coach'), 'numeric', null, 'server');
        $mform->addRule('menu_index', get_string('required'), 'required', null, 'server');

        $this->add_action_buttons();
         
    }
    // Custom validation should be added here.
    function validation($data, $files) {
        global $CFG, $DB, $USER;

        $validated = array();
        $data = (object)$data;
        if ($data->menu_name) {
           if ($DB->record_exists('menu_configuration',['userid' => $USER->id, 'menu_name' => $data->menu_name, 'theme_id' => $data->theme_id])) {
            $validated['menu_name'] = get_string('nameexists', 'local_team_coach');
           }
        }

        return $validated;
    }
}
