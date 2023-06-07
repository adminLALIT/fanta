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

    /**
     * This function is called for each data row to allow processing of the
     * username value.
     *
     * @param object $values Contains object with all the values of record.
     * @return $string Return username with link to profile or username only
     *     when downloading.
     */
    // function col_username($values) {
    //     // If the data is being downloaded than we don't want to show HTML.
    //     if ($this->is_downloading()) {
    //         return $values->username;
    //     } else {
    //         return '<a href="/user/profile.php?id='.$values->id.'">'.$values->username.'</a>';
    //     }
    // }

}

?>