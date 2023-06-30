<?php

/**
 * team_list_form class to be put in team_list_form.php of root of Moodle installation.
 *  for defining some custom column names and proccessing.
 */
class partner_list extends table_sql
{
  /**
   * Constructor
   * @param int $uniqueid all tables have to have a unique id, this is used
   *      as a key when storing table properties like sort order in the session.
   */
  function __construct($uniqueid)
  {
    parent::__construct($uniqueid);
    // Define the list of columns to show.
    $columns = array('name', 'partner_name', 'partner_link', 'partner_logo',  'action');
    $this->define_columns($columns);

    // Define the titles of columns to show in header.
    $headers = array('Theme', 'Partner Name', 'Link', 'Logo', 'Action');
    $this->define_headers($headers);
  }
  /**
   * This function is called for each data row to allow processing of the
   * logo value.
   *
   * @param object $values Contains object with all the values of record.
   */
  function col_partner_logo($values)
  {
    global $CFG, $DB;
    $imageurl = get_partnerimage_by_id($values->id);
    return html_writer::div("<img src='$imageurl' width='80' height='80'>");
  }

  /**
   * This function is called for each data row to allow processing of the
   * action value.
   *
   * @param object $values Contains object with all the values of record.
   */

  function col_action($values)
  {
    global $CFG, $DB, $OUTPUT;
    $baseurl = new moodle_url('/local/team_coach/partner_list.php');
    $url = new moodle_url('partner.php', array('delete' => 1, 'id' => $values->id, 'returnurl' => $baseurl));
    $buttons[] = html_writer::link($url, $OUTPUT->pix_icon('t/delete', 'Delete'));
    $url = new moodle_url('partner.php', array('id' => $values->id));
    $buttons[] = html_writer::link($url, $OUTPUT->pix_icon('t/edit', 'Edit'));

    return implode(' ', $buttons);
  }
}