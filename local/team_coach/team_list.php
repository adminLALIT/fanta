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
require_once '../../config.php';
require "$CFG->libdir/tablelib.php";
require_once('lib.php');
require "team_list_form.php";

$return = new moodle_url('/local/team_coach/team_list.php');
$delete = optional_param('delete', 0, PARAM_INT);
$fileid = optional_param('fileid', 0, PARAM_INT);
$confirm   = optional_param('confirm', 0, PARAM_BOOL);
$returnurl = optional_param('returnurl', '', PARAM_LOCALURL);

require_login();
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url($CFG->wwwroot . '/local/team_coach/team_list.php');
$table = new team_list_form('uniqueid');
$PAGE->set_title('Manage Theme List');
$PAGE->set_heading('Manage Theme List');
$PAGE->set_pagelayout('standard');
echo $OUTPUT->header();
echo $OUTPUT->heading('Manage Theme Configuration');
echo '<div id="buttonid" style="float:right;">
<a href="'.$CFG->wwwroot.'/local/team_coach/menu_list.php"><button class="btn btn-secondary">Manage Theme Menu </button></a>
<a href="'.$CFG->wwwroot.'/local/team_coach/index.php"><button class="btn btn-secondary">Add New Theme </button></a></div><br><br>';
$table->no_sorting('action');
$table->no_sorting('logo');
// Work out the sql for the table.
$table->set_sql('*', "{theme_detail}", '1=1');
$table->define_baseurl("$CFG->wwwroot/local/team_coach/team_list.php");
$table->out(10, true);
echo $OUTPUT->footer();
