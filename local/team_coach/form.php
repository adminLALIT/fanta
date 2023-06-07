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

class theme_form extends moodleform {
    // Add elements to form
    public function definition() {
        global $CFG,$user;
        $id = optional_param('id', 0, PARAM_INT);
     
        
        $mform = $this->_form; // Don't forget the underscore! 
        $editoroptions = $this->_customdata['editoroptions'];
        list($instance) = $this->_customdata;        

        $accept_types = ['.png','.jpg','.gif','.svg','.jpeg','.ico'];
        $mform->addElement('hidden', 'themeid', $id);

        $mform->addElement('text', 'url', get_string('url','local_team_coach')); 
        $mform->addRule('url', get_string('required'), 'required', null, 'server');

        $mform->addElement('text', 'name', get_string('name','local_team_coach')); 
        $mform->addRule('name', get_string('required'), 'required', null, 'server');
        // $mform->addRule('name', get_string('required'), 'lettersonly', null, 'server');

        $mform->addElement('filemanager', 'logo_path', get_string('logo','local_team_coach'), null, $editoroptions);
        if (!$id) {
            # code...
            $mform->addRule('logo_path', get_string('required'), 'required', null, 'server');
        }

        $mform->addElement('text', 'theme_color', get_string('theme_color','local_team_coach')); 
        $mform->addRule('theme_color', get_string('required'), 'required', null, 'server');
        $purpose = user_edit_map_field_purpose($user->id, 'lang');
        $translations = get_string_manager()->get_list_of_translations();

        $select = $mform->addElement('select', 'lang', get_string('preferredlanguage'), $translations, $purpose);
        $lang = empty($user->lang) ? $CFG->lang : $user->lang;
        $mform->setDefault('lang', $lang);
        $mform->addRule('lang', get_string('required'), 'required', null, 'server');
        $select->setMultiple(true);
        $select->setSelected(array('val1', 'val2'));

         $mform->addElement('select', 'signup', get_string('login_signup','local_team_coach'),[''=>'select','1'=>'Enable','2'=>'Disable']); 

        $mform->addRule('signup', get_string('required'), 'required', null, 'server');
        $this->add_action_buttons();
         
    }
    // Custom validation should be added here.
    function validation($data, $files) {
        global $CFG, $DB, $USER;

        $validated = array();
        $data = (object)$data;
        $data->name = trim($data->name);
        $url_column = $DB->sql_compare_text('url');
        if (!$data->themeid) {
            if ($DB->record_exists('theme_detail', array('name' => $data->name, 'userid' => $USER->id))) {
                $validated['name'] = get_string('nameexists','local_team_coach');
            }
            if ($DB->record_exists('theme_detail', array($url_column => $data->url, 'userid' => $USER->id))) {
                $validated['url'] = get_string('urlexists','local_team_coach');
            }
        }
        else {
            if (!$DB->record_exists('theme_detail', array('name' => $data->name, 'userid' => $USER->id, 'id'=> $data->themeid))) {
                if ($DB->record_exists('theme_detail', array('name' => $data->name, 'userid' => $USER->id))) {
                    $validated['name'] = get_string('nameexists','local_team_coach');
                }
            }

            if (!$DB->record_exists('theme_detail', array($url_column => $data->url, 'userid' => $USER->id, 'id'=> $data->themeid))) {
                if ($DB->record_exists('theme_detail', array($url_column => $data->url, 'userid' => $USER->id))) {
                    $validated['url'] = get_string('urlexists','local_team_coach');
                }
            }
        }
        return $validated;
    }
}
