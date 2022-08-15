<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin strings are defined here.
 * 
 * @package     local_moodlepe
 * @category    string
 * @copyright   2022 SATCHIVI Kokoè Yasmine Ashley <ashleysatchivi92@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../../config.php');
require_once($CFG->dirroot . '/local/moodlepe/edit_database_name.php');


require_once($CFG->dirroot . '/local/moodlepe/lib.php');
require_once($CFG->dirroot . '/local/moodlepe/lang/en/local_moodlepe.php');
require_once($CFG->dirroot . '/local/moodlepe/message_form.php');

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/moodlepe/user/add.php'));
$PAGE->set_pagelayout('standard');
$PAGE->set_title($SITE->fullname);
$PAGE->set_heading(get_string('pluginname', 'local_moodlepe'));


require_login();

if (isguestuser()) {
    throw new moodle_exception('noguest');
}

//CONNEXION AVEC MA BASE DE DONNEE
//OUVERTURE

$dbhostname = database_hostname();

$dbname =  database_name();

$dbusername = database_username();

$dbpassword = database_password();

$dbport = database_port();

$mysql = new mysqli($dbhostname, $dbusername, $dbpassword, $dbname, $dbport);

$authuserid = $USER->id;

$timecreated = time();

// RECUPERER L'ID DE LA COHORTE DE L'UTILISATEUR CONNECTE

$cohort_check = $mysql->query("SELECT * FROM mdl_cohort c JOIN mdl_cohort_members m ON c.id = m.cohortid WHERE m.userid = '$authuserid'", MYSQLI_USE_RESULT);

while ($row = $cohort_check->fetch_assoc()) {
    $cohortid =  $row['cohortid'];
    $cohortname =  $row['name'];
}


$messageform = new local_moodlepe_message_form();

// if ($data = $messageform->get_data()) {

//     $message = required_param('message', PARAM_TEXT);

//     if (!empty($message)) {
//         // $record = new stdClass;
//         // $record->message = $message;
//         // $record->timecreated = time();
//         // $record->userid = $USER->id;

//         // $DB->insert_record('local_greetings_messages', $record);
//     } else {
//         echo "<script> alert('Je suis vide') </script>";
//     }
// }
echo $OUTPUT->header();
?>

<!-- // $messageform->display(); -->

<!-- <form method="post" action="post.php">
    ... Your form code goes here
</form> -->

<!-- <form action="/moodlepe/local/moodlepe/user/add.php"> -->
<form action="/moodlepe/local/moodlepe/index.php">
    <input type="submit" value="Restaurer un utilisateur">
</form>


<form method="post" action="">
    <table>
        <tr>
            <div>
                <td><label>Nom </label></td>
                <td><input type="text" placeholder="Entrez votre nom" required></td>
            </div>
        </tr>
        <tr>
            <div>
                <td> <label>Prénom </label></td>
                <td> <input type="text" placeholder="Entrez votre prénom" required></td>
            </div>
        </tr>
        <tr>
            <div>
                <td><label>Email </label></td>
                <td><input type="email" placeholder="Entrez votre adresse mail" required></td>
            </div>
        </tr>
        <tr>
            <div>
                <td><label>Mot de passe </label></td>
                <td><input type="password" placeholder="Entrez votre mot de passe" required></td>
            </div>
            <!-- *******************CHANGEMENT DE MOT DE PASSE ********************************* -->
            <div class="form-check d-flex">
                <input type="hidden" name="preference_auth_forcepasswordchange" value="0">
                <input type="checkbox" name="preference_auth_forcepasswordchange" class="form-check-input " value="1" id="id_preference_auth_forcepasswordchange">
                <label for="id_preference_auth_forcepasswordchange">
                    Imposer le changement du mot de passe
                </label>
                <div class="ml-2 d-flex align-items-center align-self-start">
                    <a class="btn btn-link p-0" role="button" data-container="body" data-toggle="popover" data-placement="right" data-content="<div class=&quot;no-overflow&quot;><p>Si ce réglage est activé, l'utilisateur sera invité à changer son mot de passe lors de la connexion suivante.</p>
</div> " data-html="true" tabindex="0" data-trigger="focus">
                        <i class="icon fa fa-question-circle text-info fa-fw " title="Aide sur Imposer le changement du mot de passe" role="img" aria-label="Aide sur Imposer le changement du mot de passe"></i>
                    </a>
                </div>
            </div>
            <!-- *******************CHANGEMENT DE MOT DE PASSE ********************************* -->
        </tr>

        <tr>
            <div id="fitem_id_firstname" class="form-group row  fitem   ">
                <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">

                    <label class="d-inline word-break " for="id_firstname">
                        Prénom
                    </label>

                    <div class="form-label-addon d-flex align-items-center align-self-start">
                        <div class="text-danger" title="Requis">
                            <i class="icon fa fa-exclamation-circle text-danger fa-fw " title="Requis" role="img" aria-label="Requis"></i>
                        </div>

                    </div>
                </div>
                <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
                    <input type="text" class="form-control " name="firstname" id="id_firstname" value="" size="30" maxlength="100">
                    <div class="form-control-feedback invalid-feedback" id="id_error_firstname">

                    </div>
                </div>
            </div>
        </tr>
        <tr>
            <td>
                <input type="submit" value="Créer">
                <input type="reset" value="Annuler">
            </td>
        </tr>
    </table>
</form>


<?php
echo $OUTPUT->footer();
