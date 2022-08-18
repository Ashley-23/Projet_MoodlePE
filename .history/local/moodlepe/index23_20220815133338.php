<?php


if (isset($_GET['suppr'])) {

    $suppression = $_GET['suppr'];

    if ($suppression == "ok") {
        echo "<script> alert('la suppression a été effectué avec succès') </script>";
    }
}

if (isset($_GET['mod'])) {

    $modification = $_GET['mod'];

    if ($modification == "ok") {
        echo "<script> alert('la modification a été effectué avec succès') </script>";
    }
}

if (isset($_GET['supprerreur'])) {

    $supprerreur = $_GET['supprerreur'];

    if ($supprerreur == "ok") {
        echo "<script> alert('Il y a eu une erreur dans la suppression') </script>";
    }
}


if (isset($_GET['moderreur'])) {

    $moderreur = $_GET['moderreur'];

    if ($moderreur == "ok") {
        echo "<script> alert('Il y a eu une erreur dans la modification') </script>";
    }
}
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

require_once('../../config.php');
require_once($CFG->dirroot . '/local/moodlepe/edit_database_name.php');

require_login();

if (isguestuser()) {
    throw new moodle_exception('noguest');
}

// RECUPERER L'ID DE L'UTILISATEUR CONNECTE
$userid = $USER->id;
echo $userid;

//CONNEXION AVEC MA BASE DE DONNEE
//OUVERTURE
$dbhostname = database_hostname();

$dbname =  database_name();

$dbusername = database_username();

$dbpassword = database_password();

$dbport = database_port();

$mysql = new mysqli($dbhostname, $dbusername, $dbpassword, $dbname, $dbport);

// RECUPERER L'ID DE NOTRE ROLE 

$role_name = "Administrateur pour les entreprises";
$role_shortname = "administrateur_entreprise";
$role_description = "role du plugin moodlepe";
$role_archetype = "manager";

$check_role_id = $mysql->query("SELECT * FROM mdl_role WHERE name='$role_name' AND shortname='$role_shortname' AND description ='$role_description'", MYSQLI_USE_RESULT);

while ($row = mysqli_fetch_assoc($check_role_id)) {
    $verify_role_id = $row['id'];
}

echo "l'id de notre role est : " . $verify_role_id;

// RECUPERER L'ID DU ROLE DE L'UTILISATEUR CONNECTE



$role_check = $mysql->query("SELECT r.id,r.name, a.userid FROM mdl_role r JOIN mdl_role_assignments a ON r.id = a.roleid WHERE a.userid='$userid'", MYSQLI_USE_RESULT);

while ($row = $role_check->fetch_assoc()) {
    $roleid =  $row['id'];
}

if (isset($roleid)) {
