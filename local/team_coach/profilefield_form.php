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

class profession_form extends moodleform
{
    // Add elements to form
    public function definition()
    {
        global $CFG, $user, $DB;
       
        $mform = $this->_form; // Don't forget the underscore! 
        list($instance) = $this->_customdata;
        $id = $this->_customdata['id'];

        if ($id) {
            $where = 'where id = ' . $instance->themeid . '';
        } else {
            $where = null;
        }
        $select_theme =  $DB->get_records_sql_menu("SELECT id, name FROM {theme_detail} $where");
        $profilefield =  $DB->get_records_sql_menu("SELECT shortname, name FROM {user_info_field}");
        // Add the new key-value pair at the beginning of the array
        $select_theme = array('' => 'Select') + $select_theme;
        $mform->addElement('hidden', 'id', $id);
        $mform->setType('id', PARAM_INT);

        $mform->addElement('select', 'themeid', get_string('select_theme', 'local_team_coach'), $select_theme, ['readonly']);
        $mform->addRule('themeid', get_string('required'), 'required', null, 'server');

        $options = array(
            'multiple' => true,
            'noselectionstring' => 'Select Profile Fields',
        );
        $mform->addElement('autocomplete', 'profilefield', get_string('profilefield', 'local_team_coach'), $profilefield, $options);
        $mform->addRule('profilefield', get_string('required'), 'required','extraruledata', 'server', false, false);

        $this->add_action_buttons();
        $this->set_data($instance);  
    }
    // Custom validation should be added here.
    function validation($data, $files)
    {
        global $CFG, $DB, $USER;
     
        $validated = array();
        $data = (object)$data;
        // $data->name = trim($data->name);
      
        return $validated;
    }
}
