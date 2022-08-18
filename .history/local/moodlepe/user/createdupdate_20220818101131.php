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



if (isset($_POST['id']) || isset($_POST['username']) || isset($_POST['lastname']) || isset($_POST['firstname']) || isset($_POST['email'])) {
    $id = $_POST['id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $email = $_POST['email'];


    echo "</br> Id : " . $id . "</br> Username : " . $username . "</br> Firstname : " . $firstname . "</br> Lastname : " . $lastname . "</br> Email : " . $email;

    $update_mdl_user = "UPDATE mdl_user SET username='$username', firstname='$firstname', lastname='$lastname', email='$email' WHERE id='$id'";


    $update_mdl_local_moodlepe_suppr = "UPDATE mdl_local_moodlepe_suppr SET username='$username', firstname='$firstname', lastname='$lastname', email='$email' WHERE userid='$id'";
mysql-
    echo "</br> TABLE USER : " . $update_mdl_user;

    echo "</br> TABLE mdl_local_moodlepe_suppr : " . $update_mdl_local_moodlepe_suppr;
    // header("Location: /moodlepe/local/moodlepe/index.php?mod=ok");
} else {

    // ON LE RETOURNE SUR LA PAGE AVEC LA LISTE D'UTILISATEURS
    header("Location: /moodlepe/local/moodlepe/index.php?moderreur=ok");
    die();
}

mysqli_close($mysql);
