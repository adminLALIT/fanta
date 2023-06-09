<?php

function local_team_coach_extend_navigation(global_navigation $nav){
    global $CFG;
   $manage_team_coach = $nav->add('Team Coach');

   $manage_team_coach->add('Manage Theme',
        new moodle_url($CFG->wwwroot . '/local/team_coach/team_list.php'),
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

function get_logo_by_theme_id($id){
    global $CFG;
    $context = context_system::instance();
    $fs = get_file_storage();
    // get the image
    $files = $fs->get_area_files($context->id, 'local_team_coach', 'image', $id, "timemodified", false);
      foreach ($files as $file) {
        $filename = $file->get_filename();
        $mimetype = $file->get_mimetype();
        $imageurl = file_encode_url($CFG->wwwroot . '/pluginfile.php', '/' . $context->id . '/local_team_coach/image/' . $id . '/' . $filename);
      }
      if ($imageurl) {
        # code...
        return $imageurl;
      }
      else {
        return null;
      }
}
?>