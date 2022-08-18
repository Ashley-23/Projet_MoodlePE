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


echo $OUTPUT->header();
?>


<!-- *********************************** RETOURNER A LA LISTE****************************************** -->
<!-- <form action="/moodlepe/local/moodlepe/user/add.php"> -->
<form action="/moodlepe/local/moodlepe/index.php">
    <!-- <input type="submit" value="Restaurer un utilisateur"> -->


    <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="group" id="yui_3_17_2_1_1660412984554_753">
        <fieldset class="w-100 m-0 p-0 border-0" id="yui_3_17_2_1_1660412984554_752">
            <div class="d-flex flex-wrap align-items-center" id="yui_3_17_2_1_1660412984554_751">

                <div class="form-group  fitem  " id="yui_3_17_2_1_1660412984554_750">
                    <span data-fieldtype="submit" id="yui_3_17_2_1_1660412984554_749">
                        <input type="submit" class="btn
                        btn-primary
                        
                    
                    " id="id_submitbutton" value="Retourner à la liste" data-initial-value="Retourner à la liste">
                    </span>
                    <div class="form-control-feedback invalid-feedback" id="id_error_submitbutton">

                    </div>
                </div>

        </fieldset>
        <div class="form-control-feedback invalid-feedback" id="fgroup_id_error_buttonar">

        </div>
    </div>
</form>
<!-- *********************************** FIN RETOURNER A LA LISTE****************************************** -->


<?phpif (isset($_GET['username'])) { $username=$_GET['username']; echo "Mon username est " . $username; // RECUPERER L'ID D'UN UTILISATEUR $utilisateur=$mysql->query("SELECT * FROM mdl_user WHERE username='$username'", MYSQLI_USE_RESULT);


    while ($row = $utilisateur->fetch_assoc()) {
    $userid = $row['id'];
    }

    echo " Le nom de la personne a modifié est : " . $username . " et mon id est : " . $userid;

    echo "</br> Le nom de l'utilisateur connecté est : " . $authuserid;

    // $users = array();
    // while ($row = $userlist->fetch_assoc()) {
    // $users[] = array('id' => $row['id'], 'nom' => $row['lastname'], 'prenom' => $row['firstname'], 'username' => $row['username'], 'email' => $row['email'], 'entreprise' => $row['name']);
    // }

    // header("Location: /moodlepe/local/moodlepe/index.php?mod=ok");
    // die();
    } else {
    header("Location: /moodlepe/local/moodlepe/index.php?moderreur=ok");
    }