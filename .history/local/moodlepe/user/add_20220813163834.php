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

<form method="post" action="">
    <table>
        <tr>
            <div>
                <td><label>Nom </label></td>
                <td><input type="text" placeholder="Entrez votre nom"></td>
            </div>
        </tr>
        <tr>
            <div>
                <label>Prénom </label>
                <input type="text" placeholder="Entrez votre prénom">
            </div>
        </tr>
        <tr>
            <div>
                <label>Email </label>
                <input type="email" placeholder="Entrez votre adresse mail">
            </div>
        </tr>
        <tr>
            <div>
                <label>Mot de passe </label>
                <input type="password" placeholder="Entrez votre mot de passe">
            </div>
        </tr>
        <tr>
            <td> <input type="button" value="Créer"></td>
            <td> <input type="reset" value="Annuler"></td>
        </tr>
    </table>
</form>


<?php
echo $OUTPUT->footer();
