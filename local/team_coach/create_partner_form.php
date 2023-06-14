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

class partner_form extends moodleform
{
    // Add elements to form
    public function definition()
    {
        global $CFG, $user, $DB;
        $mform = $this->_form; // Don't forget the underscore! 
        $editoroptions = $this->_customdata['editoroptions'];
        $id = $this->_customdata['id'];
        list($instance) = $this->_customdata;
        if ($id) {
            $disable = 'readonly';
            $where = 'where id = '.$instance->theme_id.''; 
        }
        else {
            
            $disable = '';
            $where = null; 
        }
        $select_theme =  $DB->get_records_sql_menu("SELECT id, name FROM {theme_detail} $where");
        // Add the new key-value pair at the beginning of the array
        $select_theme = array('' => 'Select') + $select_theme;

        $accept_types = ['.png', '.jpg', '.gif', '.svg', '.jpeg', '.ico'];
        $mform->addElement('hidden', 'partnerid', $id);

        $mform->addElement('select', 'theme_id', get_string('select_theme','local_team_coach'), $select_theme);
        $mform->addRule('theme_id', get_string('required'), 'required', null, 'server');

        $mform->addElement('text', 'partner_name', get_string('partner_name', 'local_team_coach'), $disable);
        $mform->addRule('partner_name', get_string('required'), 'required', 'extraruledata', 'server', false, false);
        $mform->settype('partner_name', PARAM_TEXT);

        $mform->addElement('filemanager', 'partner_filemanager', get_string('partner_logo', 'local_team_coach'), null, $editoroptions);
        if (!$id) {
            # code...
            $mform->addRule('partner_filemanager', get_string('required'), 'required');
        }

        $mform->addElement('text', 'partner_link', get_string('partner_link', 'local_team_coach'));
        $mform->addRule('partner_link', get_string('required'), 'required', 'extraruledata', 'server', false, false);
        $mform->settype('partner_link', PARAM_TEXT);

        $this->add_action_buttons();
        $this->set_data($instance);  
    }
    // Custom validation should be added here.
    function validation($data, $files)
    {
        global $CFG, $DB, $USER;
     
        $validated = array();
        $data = (object)$data;
        $data->partner_name = trim($data->partner_name);
        if ($data->partner_name) {
            if(!$data->partnerid){

                if ($DB->record_exists('theme_partner', array('partner_name' => $data->partner_name, 'userid' => $USER->id))) {
                    $validated['partner_name'] = get_string('partnerexists', 'local_team_coach');
                }
            }
        }
        return $validated;
    }
}
