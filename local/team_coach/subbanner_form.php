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

class subanner_form extends moodleform
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
            $where = 'where id = '.$instance->themeid.''; 
        }
        else {
            
            $disable = '';
            $where = null; 
        }
        $selecttheme =  $DB->get_records_sql_menu("SELECT id, name FROM {theme_detail} $where");
        // Add the new key-value pair at the beginning of the array
        $selecttheme = array('' => 'Select') + $selecttheme;
        $accept_types = ['.png', '.jpg', '.gif', '.svg', '.jpeg', '.ico'];

        $mform->addElement('hidden', 'bannerid', $id);
        $mform->settype('bannerid', PARAM_INT);

        $mform->addElement('select', 'themeid', get_string('select_theme','local_team_coach'), $selecttheme);
        $mform->addRule('themeid', get_string('required'), 'required', null, 'server');

        $mform->addElement('text', 'bannertitle', get_string('bannertitle', 'local_team_coach'), $disable);
        $mform->addRule('bannertitle', get_string('required'), 'required', 'extraruledata', 'server', false, false);
        $mform->settype('bannertitle', PARAM_TEXT);

        $mform->addElement('filemanager', 'banner_filemanager', get_string('banner_logo', 'local_team_coach'), null, $editoroptions);
        if (!$id) {
            $mform->addRule('banner_filemanager', get_string('required'), 'required');
        }
        
        $mform->addElement('textarea', 'bannertext', get_string("bannertext", "local_team_coach"), 'wrap="virtual" rows="10" cols="100"');
        $mform->addRule('bannertext', get_string('required'), 'required');
        
        $mform->addElement('filemanager', 'bannerimg_filemanager', get_string('banner_img', 'local_team_coach'), null, $editoroptions);
        if (!$id) {
            $mform->addRule('bannerimg_filemanager', get_string('required'), 'required');
        }
        $mform->addElement('text', 'textcolor', get_string('textcolor', 'local_team_coach'));
        $mform->addRule('textcolor', get_string('required'), 'required', 'extraruledata', 'server', false, false);
        $mform->setType('textcolor', PARAM_TEXT);

        $mform->addElement('text', 'backgroundcolor', get_string('backgroundcolor', 'local_team_coach'));
        $mform->addRule('backgroundcolor', get_string('required'), 'required', 'extraruledata', 'server', false, false);
        $mform->setType('backgroundcolor', PARAM_TEXT);
        
        $mform->addElement('editor', 'subbannerdesc_editor', get_string('subbannerdesc_editor', 'local_team_coach'), null, $editoroptions);
        $mform->addRule('subbannerdesc_editor', get_string('required'), 'required', 'extraruledata', 'server', false, false);
        $mform->setType('subbannerdesc_editor', PARAM_RAW);

        $mform->addElement('text', 'bannerurl', get_string('bannerurl', 'local_team_coach'));
        $mform->addRule('bannerurl', get_string('required'), 'required', 'extraruledata', 'server', false, false);
        $mform->settype('bannerurl', PARAM_TEXT);

        $this->add_action_buttons();
        $this->set_data($instance);  
    }
    // Custom validation should be added here.
    function validation($data, $files)
    {
        global $CFG, $DB, $USER;
     
        $validated = array();
        $data = (object)$data;
        $data->partner_name = trim($data->bannertitle);
        if ($data->bannertitle) {
            if(!$data->bannerid){
                if ($DB->record_exists('theme_subbanner', array('bannertitle' => $data->bannertitle, 'userid' => $USER->id))) {
                    $validated['bannertitle'] = get_string('titleexist', 'local_team_coach');
                }
            }
        }
        return $validated;
    }
}
