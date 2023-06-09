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
 * Event observers used in similarity Plagiarism plugin.
 *
 * @package   local_team_coach
 * @author     Dan Marsden <dan@danmarsden.com>, Ramindu Deshapriya <rasade88@gmail.com>
 * @copyright  2011 Dan Marsden http://danmarsden.com
 * @copyright  2015 Ramindu Deshapriya <rasade88@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Class local_team_coach_observer
 *
 * @package   local_team_coach
 * @copyright 2017 Dan Marsden
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class local_team_coach_observer {
    /**
     * Observer function to handle the assessable_uploaded event in mod_assign.
     * @param \assignsubmission_file\event\assessable_uploaded $event
     */
    public static function selfregistration(\core\event\config_log_created $event) {
        global $CFG, $DB, $USER;
      
            $eventdata = $event->get_data();
            if ($eventdata['other']['name'] == 'registerauth') {
                if ($DB->record_exists('signup_value',['userid'=>$USER->id])) {
                    $recordid = $DB->get_record('signup_value',['userid'=>$USER->id]);
                    $update = new stdClass();
                    $update->id = $recordid->id;
                    $update->signup = $eventdata['other']['value'];
                    $update->time_modified = time();
                    $DB->update_record('signup_value',$update);
                }
                else {
                    $insert = new stdClass();
                    $insert->userid = $USER->id;
                    $insert->signup = $eventdata['other']['value'];
                    $insert->time_created = time();
                    $DB->insert_record('signup_value',$insert);
                }
            }
           
    }      
}
