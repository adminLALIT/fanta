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

require_once "../../config.php";
require_once "$CFG->libdir/tablelib.php";
require_once "menu_list_form.php";

$context = context_system::instance();
require_login();
$PAGE->set_context($context);
$PAGE->set_url($CFG->wwwroot . '/local/team_coach/menu_list.php');
$PAGE->set_title('Menu List');
$PAGE->set_heading('Menu List');
$PAGE->set_pagelayout('standard');
echo $OUTPUT->header();
$download = optional_param('download', '', PARAM_ALPHA);

$table = new menu_list('uniqueid');

$field = 'mc.*, td.name';
$from = "{menu_configuration} mc JOIN {theme_detail} td ON td.id = mc.theme_id";
// Work out the sql for the table.
$table->set_sql($field, $from, '1=1');

$table->define_baseurl("$CFG->wwwroot/local/team_coach/menu_list.php");

echo $OUTPUT->single_button($CFG->wwwroot.'/local/team_coach/menu_configuration.php', 'Add Menu');
$table->out(10, true);
echo $OUTPUT->footer();