<?php
/**
 * Test table class to be put in test_table.php of root of Moodle installation.
 *  for defining some custom column names and proccessing
 */
class menu_list extends table_sql {

    /**
     * Constructor
     * @param int $uniqueid all tables have to have a unique id, this is used
     *      as a key when storing table properties like sort order in the session.
     */
    function __construct($uniqueid) {
        parent::__construct($uniqueid);
        // Define the list of columns to show.
        $columns = array('name', 'menu_name', 'menu_url', 'menu_index', 'action');
        $this->define_columns($columns);

        // Define the titles of columns to show in header.
        $headers = array('Theme', 'Name', 'URL', 'Index', 'Action');
        $this->define_headers($headers);
    }

    function col_action($value){
        global $CFG, $DB, $OUTPUT;
        $baseurl = new moodle_url('/local/team_coach/menu_list.php');
        $url = new moodle_url('menu_configuration.php', array('delete'=>1, 'id'=>$value->id,'returnurl' => $baseurl));
        $buttons[] = html_writer::link($url, $OUTPUT->pix_icon('t/delete', 'Delete'));
        $url = new moodle_url('menu_configuration.php', array('id' => $value->id));
        $buttons[] = html_writer::link($url, $OUTPUT->pix_icon('t/edit', 'Edit'));
    
        return implode(' ', $buttons);
    }

}

?>