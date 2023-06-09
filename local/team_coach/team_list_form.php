<?php

/**
 * team_list_form class to be put in team_list_form.php of root of Moodle installation.
 *  for defining some custom column names and proccessing.
 */
class team_list_form extends table_sql
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
    $columns = array('url', 'name', 'theme_color', 'logo',  'lang', 'signup', 'action');
    $this->define_columns($columns);

    // Define the titles of columns to show in header.
    $headers = array('URL', 'Name', 'Theme Color', 'Logo', 'Language', 'Signup', 'Action');
    $this->define_headers($headers);
  }

  /**
   * This function is called for each data row to allow processing of the
   * signup value.
   *
   * @param object $values Contains object with all the values of record.
   */
  function col_signup($values)
  {
    // If the data is being downloaded than we don't want to show HTML.
    if ($values->signup == 1) {
      return 'Enable';
    } else {
      return 'Disable';
    }
  }

  /**
   * This function is called for each data row to allow processing of the
   * logo value.
   *
   * @param object $values Contains object with all the values of record.
   */
  function col_logo($values)
  {
    global $CFG, $DB;
    $imageurl = get_logo_by_theme_id($values->id);
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
    $baseurl = new moodle_url('/local/team_coach/team_list.php');
    $url = new moodle_url('index.php', array('delete'=>1, 'id'=>$values->id,'returnurl' => $baseurl));
    $buttons[] = html_writer::link($url, $OUTPUT->pix_icon('t/delete', 'Delete'));
    $url = new moodle_url('index.php', array('id' => $values->id));
    $buttons[] = html_writer::link($url, $OUTPUT->pix_icon('t/edit', 'Edit'));

    return implode(' ', $buttons);
  }

  /**
   * This function is called for each data row to allow processing of the
   * theme_color value.
   *
   * @param object $values Contains object with all the values of record.
   */

  function col_theme_color($values)
  {
    return html_writer::start_span('zombie') . html_writer::start_span('temp', ['style' => 'width:15px;height:15px;background:' . $values->theme_color . ';float: left;']) . html_writer::end_span() . html_writer::end_span();
  }


  /**
   * This function is called for each data row to allow processing of the
   * action value.
   *
   * @param object $values Contains object with all the values of record.
   * 
   */

  function col_lang($values)
  {
    $language = explode(",", $values->lang);
    $translations = get_string_manager()->get_list_of_translations();
    for ($i = 0; $i < count($language); $i++) {
      $lang[] = $translations[$language[$i]];
    }

    return implode(", ", $lang);
  }
}
