<?php
require_once('../../config.php');
global $USER;
if (isset($_POST['privacypolicy']) && $_POST['privacynotice']) {

    $username =  $_POST['name'];
    $email = $_POST['inputemail'];
    $query = $_POST['query'];
    $admin = get_admin();
    $fromuser = core_user::get_noreply_user();
    $foruser = core_user::get_user($admin->id);
    $subject = 'Team Coach Contact US';
    $messageText =  format_text_email('Dear Sir,<p> <br> I am <b>'.$username.'</b> and my email address is <b>'.$email.' </b> and i have query that <b> '.$query.' </b> "</p>', FORMAT_HTML);
  
    if (!$mailresults = email_to_user($foruser, $fromuser, $subject, $messageText)) {
        die("could not send email!");
    }
    else {
        redirect($CFG->wwwroot."/?query=sent#contact");
    }
}
