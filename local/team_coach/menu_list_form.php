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
        $columns = array('name', 'menu_name', 'menu_url', 'menu_index');
        $this->define_columns($columns);

        // Define the titles of columns to show in header.
        $headers = array('Theme', 'Name', 'URL', 'Index');
        $this->define_headers($headers);
    }

}

?>