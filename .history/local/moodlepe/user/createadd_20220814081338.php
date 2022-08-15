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



$auth = 'manual';
$confirmed = 1;
$deleted = 0;
$timezone = '99';
$mnethostid = 1;
$lang = 'fr';
$timecreated = time();
$timemodified = time();




if (isset($_POST['username']) || isset($_POST['lastname']) || isset($_POST['firstname']) || isset($_POST['email']) || isset($_POST['entreprise']) || isset($_POST['password']) || isset($_POST['entrepriseid'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $entreprise = $_POST['entreprise'];
    $mdp = $_POST['password'];
    $password = hash_internal_user_password($mdp);
    $entrepriseid = $_POST['entrepriseid'];

    $user = array();
    $user[] = array(
        'firstname' => $firstname,
        'lastname' => $lastname,
        'username' => $username,
        'email' => $email,
        'password' => $password,
        'entreprise' => $entreprise
    );

    print_r($user);



    die();



























    // S'IL N'EXISTE PAS, 


    $mdl_user  = $mysql->query("INSERT INTO mdl_user ( auth, confirmed, deleted, timezone,  username, firstname, lastname,mnethostid, lang,  password, email, timecreated, timemodified )
     VALUES ('$auth', '$confirmed', '$deleted','$timezone','$username', '$firstname', '$lastname','$mnethostid', '$lang', '$password', '$email',  '$timecreated', '$timemodified')", MYSQLI_USE_RESULT);


    //


    //RECUPERE L'ID DE L'UTILISATEUR QU'IL VIENT DE CREER 
    $check_user_id = $mysql->query("SELECT * FROM mdl_user WHERE email='$email'", MYSQLI_USE_RESULT);

    while ($row = mysqli_fetch_assoc($check_user_id)) {
        $verify_user_id = $row['id'];
    }

    //CREE UNE NOUVELLE INSTANCE DE COHORT_MEMBERS 
    $mdl_cohort_members  = $mysql->query("INSERT INTO mdl_cohort_members (cohortid, userid, timeadded ) VALUES ('$entrepriseid', '$verify_user_id','$timecreated')", MYSQLI_USE_RESULT);

    // ENREGISTRE L'UTILISATEUR DANS LA TABLE DE NOTRE PLUGIN

    $mdl_local_moodlepe_suppr  = $mysql->query("INSERT INTO mdl_local_moodlepe_suppr 
    (username, firstname, lastname,email ,password ,enterprise ,timecreated ,active ,suppr ,suppr_user_id ,userid')
    VALUES ('$username', '$firstname', '$lastname','$email', '$password', '$entreprise','$timecreated', 't','f', '$authuserid', '$verify_user_id')", MYSQLI_USE_RESULT);
} else {
    header("Location: /moodlepe/local/moodlepe/index.php");
}

mysqli_close($mysql);
