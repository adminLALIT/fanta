<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

$CFG->dbtype    = 'xxxxx';
$CFG->dblibrary = 'xxxxx';
$CFG->dbhost    = 'xxxxx';
$CFG->dbname    = 'xxxxx';
$CFG->dbuser    = 'xxxxx';
$CFG->dbpass    = 'xxxxx';
$CFG->prefix    = 'mdl_';
$CFG->dboptions = array (
  'dbpersist' => 0,
  'dbport' => 3306,
  'dbsocket' => '',
  'dbcollation' => 'utf8mb4_general_ci',
);

$mysqli = new mysqli("localhost", $CFG->dbuser, $CFG->dbpass, $CFG->dbname);


$maindomain           = 'yislms.com';
$userdomain = '';
$domain_get=explode('.', @$_SERVER['HTTP_HOST']);

if (count($domain_get) > 2) {
  
  $rows = $mysqli->query("SELECT * FROM mdl_theme_detail WHERE url = '".$domain_get[0]."'");
  
  if ($rows->num_rows > 0) {
    $data = $rows->fetch_object();
    $userdomain = $domain_get[0].'.';  // define tenant domain
    $www_root     = 'https://'.$userdomain.''.$maindomain.'/penta/moodle';
    $CFG->wwwroot  =  $www_root;
  }
  
}
else {
  $CFG->wwwroot   = 'https://yislms.com/penta/moodle';
}

$CFG->dataroot  = '/home/yislms/moodledata_penta';
$CFG->admin     = 'admin';

$CFG->directorypermissions = 0777;

require_once(__DIR__ . '/lib/setup.php');

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!
