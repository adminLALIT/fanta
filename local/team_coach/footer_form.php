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

class content_form extends moodleform
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
            $where = 'where id = ' . $instance->theme_id . '';
        } else {
            $where = null;
        }
        $select_theme =  $DB->get_records_sql_menu("SELECT id, name FROM {theme_detail} $where");
        // Add the new key-value pair at the beginning of the array
        $select_theme = array('' => 'Select') + $select_theme;

        $accept_types = ['.png', '.jpg', '.gif', '.svg', '.jpeg', '.ico'];
        $mform->addElement('hidden', 'contentid', $id);

        $mform->addElement('select', 'theme_id', get_string('select_theme', 'local_team_coach'), $select_theme, ['readonly']);
        $mform->addRule('theme_id', get_string('required'), 'required', null, 'server');

        $mform->addElement('text', 'content_title', get_string('content_title', 'local_team_coach'));
        $mform->addRule('content_title', get_string('required'), 'required', 'extraruledata', 'server', false, false);
        $mform->setType('content_title', PARAM_TEXT);

        $mform->addElement('editor', 'contentdesc_editor', get_string('contentdesc_editor', 'local_team_coach'), null, $editoroptions);
        $mform->addRule('contentdesc_editor', get_string('required'), 'required', 'extraruledata', 'server', false, false);
        $mform->setType('contentdesc_editor', PARAM_RAW);

        $mform->addElement('text', 'content_index', get_string('content_index', 'local_team_coach'));
        $mform->addRule('content_index', get_string('numberonly', 'local_team_coach'), 'numeric', 'extraruledata', 'server', false, false);
        $mform->addRule('content_index', get_string('required'), 'required', 'extraruledata', 'server', false, false);
        $mform->setType('content_index', PARAM_RAW);

        $this->add_action_buttons();
        $this->set_data($instance);
    }
    // Custom validation should be added here.
    function validation($data, $files)
    {
        global $CFG, $DB, $USER, $id;

        $validated = array();
        $data = (object)$data;
        $data->content_title = trim($data->content_title);
        if ($data->content_title) {
            if ($data->contentid) {
               
                if ($DB->record_exists('theme_footer_content', ['userid' => $USER->id, 'content_title' => $data->content_title, 'theme_id' => $data->theme_id])) {
                    if (!$DB->record_exists('theme_footer_content', ['id' => $data->contentid, 'content_title' => $data->content_title, 'theme_id' => $data->theme_id])) {
                        $validated['content_title'] = get_string('footercontentexists', 'local_team_coach');
                    }
                }
                if ($DB->record_exists('theme_footer_content', ['userid' => $USER->id, 'content_index' => $data->content_index, 'theme_id' => $data->theme_id])) {

                    if (!$DB->record_exists('theme_footer_content', ['id' => $data->contentid, 'userid' => $USER->id, 'content_index' => $data->content_index, 'theme_id' => $data->theme_id])) {
                        $validated['content_index'] = get_string('indexexists', 'local_team_coach');
                    }
                }
            } else {

                if ($DB->record_exists('theme_footer_content', ['userid' => $USER->id, 'content_index' => $data->content_index, 'theme_id' => $data->theme_id])) {

                    $validated['content_index'] = get_string('indexexists', 'local_team_coach');
                }
                if ($DB->record_exists('theme_footer_content', ['userid' => $USER->id, 'content_title' => $data->content_title, 'theme_id' => $data->theme_id])) {

                    $validated['content_title'] = get_string('footercontentexists', 'local_team_coach');
                }
            }
        }
        return $validated;
    }
}
