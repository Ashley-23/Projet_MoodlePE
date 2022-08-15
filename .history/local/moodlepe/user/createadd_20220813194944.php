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

// RECUPERER L'ID DE LA COHORTE DE L'UTILISATEUR CONNECTE

// $cohort_check = $mysql->query("SELECT * FROM mdl_cohort c JOIN mdl_cohort_members m ON c.id = m.cohortid WHERE m.userid = '$authuserid'", MYSQLI_USE_RESULT);

// while ($row = $cohort_check->fetch_assoc()) {
//     $cohortid =  $row['cohortid'];
//     $cohortname =  $row['name'];
// }



if (isset($_POST['username']) || isset($_POST['lastname']) || isset($_POST['firstname']) || isset($_POST['email']) || isset($_POST['entreprise']) || isset($_POST['password']) ) {
	$prenom = $_POST['firstname'];
	$nom = $_POST['lastname'];
	$nomutilisateur = $_POST['username'];
	$email = $_POST['email'];
	$entreprise1 = $_POST['entreprise1'];
	$mdp1 = $_POST['password1'];
	$mdp2 = $_POST['userpassword2'];
	$offer_id = $_POST['offre'];



echo $OUTPUT->header();
echo  "Je suis là !!!";
echo $OUTPUT->footer();
