<?php

function local_team_coach_extend_navigation(global_navigation $nav){
    global $CFG;
   $manage_team_coach = $nav->add('Team Coach');

   $manage_team_coach->add('Create form',
        new moodle_url($CFG->wwwroot . '/local/team_coach/'),
        navigation_node::TYPE_SYSTEM,
        null,
        'local_team_coach',
    )->showinflatnavigation = true;

    $manage_team_coach->add('List',
    new moodle_url($CFG->wwwroot . '/local/team_coach/team_list.php'),
    navigation_node::TYPE_SYSTEM,
    null,
    'local_team_coach',
)->showinflatnavigation = true;

}
?>