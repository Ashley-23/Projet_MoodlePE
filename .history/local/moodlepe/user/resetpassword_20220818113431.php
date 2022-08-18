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
$authusername = $USER->username;
$timecreated = time();



if (isset($_GET['username'])) {

    $username = $_GET['username'];

    // echo "Mon username est " . $username;

    // RECUPERER L'ID D'UN UTILISATEUR 

    $utilisateur = $mysql->query("SELECT * FROM mdl_user WHERE username='$username'", MYSQLI_USE_RESULT);


    while ($row = $utilisateur->fetch_assoc()) {
        $userid =  $row['id'];
        $userlastname =  $row['lastname'];
        $userfirstname =  $row['firstname'];
        $userpassword =  $row['password'];
        $userusername =  $row['username'];
        $useremail =  $row['email'];
    }

    // echo "</br> mon id est : " . $userid;
    // echo "</br> mon nom d'utilisateur est : " . $userusername;
    // echo "</br> mon prénom est : " . $userfirstname;
    // echo "</br> mon nom de famille est : " . $userlastname;
    // echo "</br> Et mon mot de passe est : " . $userpassword;
    // echo " </br> Le nouveau mot de passe est : " . " moi";

    //REINITIALISE LE MOT DE PASSE DE L'UTILISATEUR DANS NOTRE TABLE mdl_local_moodlepe_suppr

    // *********************** LES METS EN MINUSCULE
    $lower_lastname = strtolower($userlastname);
    $lower_firstname = strtolower($userfirstname);
    // *******************REND LA PREMIERE LETTRE EN MAJUSCULE
    $ucfirst_firstname = ucfirst($lower_firstname);
    // *******************SUPPRIME LES ESPACES

    $final_firstname = str_replace(' ', '', $ucfirst_firstname);
    $final_lastname = str_replace(' ', '', $lower_lastname);



    // 
    $resetpassword = "#" . $final_firstname . "*" . $final_lastname . "*1234";



    echo "</br> Le nouveau mot de passe sera : " . $resetpassword;


    //REINITIALISE LE MOT DE PASSE DE L'UTILISATEUR DANS NOTRE TABLE mdl_user
    // $update_mdl_local_moodlepe_suppr = "UPDATE mdl_local_moodlepe_suppr SET password='$resetpassword' WHERE id='$userid'";
    // $mysql->query($update_mdl_local_moodlepe_suppr);


    //REINITIALISE LE MOT DE PASSE DE L'UTILISATEUR DANS NOTRE TABLE mdl_user
    // $update_mdl_user = "UPDATE mdl_user SET password='$email' WHERE id='$resetpassword'";
    // $mysql->query($update_mdl_user);



    // ****************************************************************************************************

    // header("Location: /moodlepe/local/moodlepe/index.php?suppr=ok");
    die();
} else {
    header("Location: /moodlepe/local/moodlepe/index.php?supprerreur=ok");
}
