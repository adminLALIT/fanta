<?php

function local_team_coach_extend_navigation(global_navigation $nav){
    global $CFG;
   $nav->add('Team Coach',
        new moodle_url($CFG->wwwroot . '/local/team_coach/'),
        navigation_node::TYPE_SYSTEM,
        null,
        'local_team_coach',
    )->showinflatnavigation = true;
}
?>