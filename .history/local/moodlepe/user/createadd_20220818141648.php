<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

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

    // $user = array();
    // $user[] = array(
    //     'firstname' => $firstname,
    //     'lastname' => $lastname,
    //     'username' => $username,
    //     'email' => $email,
    //     'password' => $password,
    //     'entreprise' => $entreprise
    // );

    // print_r($user);
    // echo "</br>";


    // REQUETES POUR RECUPERER LE CHAMP VOULU DANS LA BASE DE DONNEE S'IL COORESPOND A LA SAISIE DE L'UTILISATEUR
    $check_user_username = $mysql->query("SELECT * FROM mdl_user WHERE UPPER(username)=UPPER('$username')");
    $check_user_firstname = $mysql->query("SELECT * FROM mdl_user WHERE UPPER(firstname)=UPPER('$firstname')");
    $check_user_lastname = $mysql->query("SELECT * FROM mdl_user WHERE UPPER(lastname)=UPPER('$lastname')");
    $check_user_email = $mysql->query("SELECT * FROM mdl_user WHERE UPPER(email)=UPPER('$email')");

    // WHERE UPPER(LastName) = UPPER('AnGel')
    // VERIFICATION DE L'EXISTANCE DU NOM D'UTILISATEUR DE L'UTILISATEUR
    $row1 = $check_user_username->fetch_array(MYSQLI_ASSOC);
    $verify_user_username = $row1["username"];
    if (empty($verify_user_username)) {
        $username_result = 0;
    } else {
        // if ($verify_user_username == $username) {
        if (strcasecmp($verify_user_username, $username) == 0) {
            $username_result = 1;
        } else {
            $username_result = 0;
        }
    }

    // VERIFICATION DE L'EXISTANCE DU PRENOM DE L'UTILISATEUR
    $row2 = $check_user_firstname->fetch_array(MYSQLI_ASSOC);
    $verify_user_firstname = $row2["firstname"];

    if (empty($verify_user_firstname)) {
        $firstname_result = 0;
    } else {
        // if ($verify_user_firstname == $firstname) {
        if (strcasecmp($verify_user_firstname, $firstname) == 0) {
            $firstname_result = 1;
        } else {
            $firstname_result = 0;
        }
    }

    // VERIFICATION DE L'EXISTANCE DU NOM DE FAMILLE DE L'UTILISATEUR
    $row3 = $check_user_lastname->fetch_array(MYSQLI_ASSOC);
    $verify_user_lastname = $row3["lastname"];
    if (empty($verify_user_lastname)) {
        $lastname_result = 0;
    } else {
        // if ($verify_user_lastname == $lastname) {
        if (strcasecmp($verify_user_lastname, $lastname) == 0) {
            $lastname_result = 1;
        } else {
            $lastname_result = 0;
        }
    }


    // VERIFICATION DE L'EXISTANCE DE L'EMAIL DE L'UTILISATEUR
    $row4 = $check_user_email->fetch_array(MYSQLI_ASSOC);
    $verify_user_email = $row4["email"];

    if (empty($verify_user_email)) {
        $email_result = 0;
    } else {
        // if ($verify_user_email == $email) {
        if (strcasecmp($verify_user_email, $email) == 0) {
            $email_result = 1;
        } else {
            $email_result = 0;
        }
    }


    // ***************************************************** VERIFICATIONS *************************************************************
    if ($lastname_result == 1 || $email_result == 1) {


        //RECUPERE L'ID DE L'UTILISATEUR SAISIE
        $check_user_id_1 = $mysql->query("SELECT * FROM mdl_user WHERE UPPER(email)=UPPER('$email')", MYSQLI_USE_RESULT);
        while ($row = mysqli_fetch_assoc($check_user_id_1)) {
            $user_id = $row['id'];
            $user_username = strtoupper($row['username']);
            $user_lastname = strtoupper($row['lastname']);
            $user_firstname = strtoupper($row['firstname']);
            $user_email = strtoupper($row['email']);
        }

        echo $user_firstname;
        // ON VERIFIE S'IL EST DEJA MEMBRE DE NOTRE SOCIETE (ON SORT LE COMPTE D'ENREGISTREMENTS, SI C'est null cest qu'il n'y en a pas)

        $check_cohort_member = $mysql->query("SELECT COUNT(*) AS total FROM mdl_cohort_members WHERE cohortid='$entrepriseid' AND userid='$user_id'", MYSQLI_USE_RESULT);

        while ($row = mysqli_fetch_assoc($check_cohort_member)) {
            $total = $row['total'];
            $t = $row['id'];
        }
        echo $total, $t;
        // die();
        // S'IL NE L'EST PAS, ON L'AJOUTE DANS LA SOCIETE
        if ($total == 0) {
            if
            // if (empty($check_cohort_member)) {

            echo "you";
            // IL EXISTE DONC ON RECUPERE SON ID ET ON L'INSCRIT DANS NOTRE ENTREPRISE

            //CREE UNE NOUVELLE INSTANCE DE COHORT_MEMBERS 

            // $mdl_cohort_members  =  "INSERT INTO mdl_cohort_members (id,cohortid, userid, timeadded) VALUES ('','$entrepriseid', '$user_id','$timecreated')";
            // $mysql->query($mdl_cohort_members);
            // ***************************************$check_me = $mysql->query("SELECT * FROM mdl_user WHERE UPPER(email)=UPPER('$email')", MYSQLI_USE_RESULT);
            // while ($row = mysqli_fetch_assoc($check_user_id_1)) {
            //     $user_id = $row['id'];
            // }

            // ***************************************sizeof($check_me);
            // ***************************************die();


            // $mdl_cohort_members  = $mysql->query("INSERT INTO mdl_cohort_members (id,cohortid, userid, timeadded) VALUES ('','$entrepriseid', '$user_id','$timecreated')", MYSQLI_USE_RESULT);

            // *************************************************************
            $mdl_cohort_members  = $mysql->query("INSERT INTO mdl_cohort_members SET cohortid = '$entrepriseid', userid = '$user_id', timeadded = '$timecreated') ", MYSQLI_USE_RESULT);
            // *************************************************************

            // echo $mdl_cohort_members;
            // die();

            // // echo $mdl_cohort_members;
            // ENREGISTRE L'UTILISATEUR DANS LA TABLE DE NOTRE PLUGIN

            // 
            // $mdl_local_moodlepe_suppr  = "INSERT INTO mdl_local_moodlepe_suppr (id,username, firstname, lastname,email ,password ,enterprise ,timecreated ,active ,suppr ,suppr_user_id ,userid)VALUES ('','$username', '$firstname', '$lastname','$email', '$password', '$entreprise','$timecreated', 't','f', '$authuserid', '$user_id')";
            // $mysql->query($mdl_local_moodlepe_suppr);

            // *************************************************************
            $mdl_local_moodlepe_suppr  = $mysql->query("INSERT INTO mdl_local_moodlepe_suppr SET username = '$username', firstname = '$firstname', lastname =  '$lastname',email  = '$email',password  = '$password',enterprise =  '$entreprise',timecreated = '$timecreated' ,active = 't' ,suppr = 'f',suppr_user_id  = '$authuserid',userid = '$user_id')", MYSQLI_USE_RESULT);
            // *************************************************************
            // ON LE RETOURNE SUR LA PAGE AVEC LA LISTE D'UTILISATEURS
            // *************************************************************
            // header("Location: /moodlepe/local/moodlepe/index.php?create=ok");
            // *************************************************************
            // 
            die();
        }
        // 

        // ON LE RETOURNE SUR LA PAGE AVEC LA LISTE D'UTILISATEURS
        echo 'je suis ce je suis';
        echo " </br> ";
        print_r($check_cohort_member);
        // header("Location: /moodlepe/local/moodlepe/index.php");
        die();
    } else {

        echo "3";
        // S'IL N'EXISTE PAS, ON LE CRE ET ON LE MET DANS LA COHORTE DE L'ADMINISTRATEUR D'ENTREPRISE

        // $mdl_user  =
        $mysql->query("INSERT INTO mdl_user ( auth, confirmed, deleted, timezone,  username, firstname, lastname,mnethostid, lang,  password, email, timecreated, timemodified ) VALUES ('$auth', '$confirmed', '$deleted','$timezone','$username', '$firstname', '$lastname','$mnethostid', '$lang', '$password', '$email',  '$timecreated', '$timemodified')", MYSQLI_USE_RESULT);

        //RECUPERE L'ID DE L'UTILISATEUR QU'IL VIENT DE CREER 
        $check_user_id = $mysql->query("SELECT * FROM mdl_user WHERE email='$email'", MYSQLI_USE_RESULT);

        while ($row = mysqli_fetch_assoc($check_user_id)) {
            $verify_user_id = $row['id'];
        }

        //CREE UNE NOUVELLE INSTANCE DE COHORT_MEMBERS 
        $mdl_cohort_members  = $mysql->query("INSERT INTO mdl_cohort_members (cohortid, userid, timeadded ) VALUES ('$entrepriseid', '$verify_user_id','$timecreated')", MYSQLI_USE_RESULT);

        // ENREGISTRE L'UTILISATEUR DANS LA TABLE DE NOTRE PLUGIN
        //   $mdl_local_moodlepe_suppr  =
        $mysql->query("INSERT INTO mdl_local_moodlepe_suppr (username, firstname, lastname,email ,password ,enterprise ,timecreated ,active ,suppr ,suppr_user_id ,userid) VALUES ('$username', '$firstname', '$lastname','$email', '$password', '$entreprise','$timecreated', 't','f', '$authuserid', '$verify_user_id')", MYSQLI_USE_RESULT);


        // ON LE RETOURNE SUR LA PAGE AVEC LA LISTE D'UTILISATEURS
        header("Location: /moodlepe/local/moodlepe/index.php");
        die();
    }
} else {

    // ON LE RETOURNE SUR LA PAGE AVEC LA LISTE D'UTILISATEURS
    header("Location: /moodlepe/local/moodlepe/index.php");
    die();
}

mysqli_close($mysql);
