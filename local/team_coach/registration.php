<?php
require_once('../../config.php');
require_once($CFG->dirroot . '/user/lib.php');
require_once($CFG->dirroot . '/user/editlib.php');
require_once($CFG->dirroot . '/auth/email/auth.php');
require_once($CFG->libdir . '/authlib.php');
require_once($CFG->dirroot . '/login/lib.php');

global $CFG, $DB, $PAGE;

$PAGE->set_url($CFG->dirroot . '/local/team_coach/registration.php');
$PAGE->set_context(context_system::instance());

$data = (object)$_POST;
if (!empty($data)) {
    $data->id = -1;
    if (empty($data->auth)) {
        // User editing self.
        $authplugin = get_auth_plugin($data->auth);
        unset($data->auth); // Can not change/remove.
    } else {
        $authplugin = get_auth_plugin($data->auth);
    }

    $data->username = trim($data->getusername);

    if (isset($data->email)) {
        if (empty($CFG->allowaccountssameemail)) {
            // Make a case-insensitive query for the given email address.
            $select = $DB->sql_equal('email', ':email', false) . ' AND mnethostid = :mnethostid AND id <> :userid';
            $params = array(
                'email' => $data->email,
                'mnethostid' => $CFG->mnet_localhost_id,
                'userid' => $data->id
            );
            // If there are other user(s) that already have the same email, show an error.
            if ($DB->record_exists_select('user', $select, $params)) {
                redirect($CFG->wwwroot . '/?status=emailexists#register');
                // $err['email'] = get_string('emailexists');
            }
        }
    }

    if (!empty($data->username)) {
        if ($DB->record_exists('user', array('username' => $data->username, 'mnethostid' => $CFG->mnet_localhost_id))) {
            redirect($CFG->wwwroot . '/?status=usernameexists');
        }
        // Check allowed characters.
        if ($data->username !== core_text::strtolower($data->username)) {
            redirect($CFG->wwwroot . '/?status=usernamelowercase#register');
        } else {
            if ($data->username !== core_user::clean_field($data->username, 'username')) {
                redirect($CFG->wwwroot . '/?status=invalidusername#register');
            }
        }
    }

    if (!empty($data->confirmpassword)) {
        $errmsg = ''; // Prevent eclipse warning.
        if (!check_password_policy($data->confirmpassword, $errmsg, $data)) {
            redirect($CFG->wwwroot . '/?status=invalidpassword#register');
        } else {
            $data->password = $data->confirmpassword;
        }
    }

    $user = signup_setup_new_user($data);
    
    // Plugins can perform post sign up actions once data has been validated.
    core_login_post_signup_requests($user);
    $obj =  new auth_plugin_email();
    $obj->user_signup($user, true); // prints notice and link to login/index.php
    exit;
}
