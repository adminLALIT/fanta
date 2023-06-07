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


    $manage_team_coach->add('Menu Configuration',
    new moodle_url($CFG->wwwroot . '/local/team_coach/menu_configuration.php'),
    navigation_node::TYPE_SYSTEM,
    null,
    'local_team_coach',
)->showinflatnavigation = true;


    $manage_team_coach->add('Menu List',
    new moodle_url($CFG->wwwroot . '/local/team_coach/menu_list.php'),
    navigation_node::TYPE_SYSTEM,
    null,
    'local_team_coach',
)->showinflatnavigation = true;
}

function local_team_coach_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {

    if ($context->contextlevel != CONTEXT_SYSTEM) {
        send_file_not_found();
    }

    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'local_team_coach', $filearea, $args[0], '/', $args[1]);

    send_stored_file($file);
}
?>