<?php

/**
 * Plugin version and other meta-data are defined here.
 *
 * @package     local_moodlepe
 * @copyright   2022 SATCHIVI Kokoè Yasmine Ashley <ashleysatchivi92@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


/**
 * Lets the user edit role definitions. 
 *
 * Responds to actions:
 *   add       - add a new role (allows import, duplicate, archetype)
 *   export    - save xml role definition
 *   edit      - edit the definition of a role
 *   view      - view the definition of a role
 *
 * @package    core_role
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */



require_once('../../config.php');
require_once($CFG->dirroot . '/local/moodlepe/edit_database_name.php');


require_once($CFG->libdir . '/gdlib.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/user/editadvanced_form.php');
require_once($CFG->dirroot . '/user/editlib.php');
require_once($CFG->dirroot . '/user/profile/lib.php');
require_once($CFG->dirroot . '/user/lib.php');
require_once($CFG->dirroot . '/webservice/lib.php');

global $DB;

$systemcontext = context_system::instance();

//CONNEXION AVEC MA BASE DE DONNEE
//OUVERTURE

$dbhostname = database_hostname();

$dbname =  database_name();

$dbusername = database_username();

$dbpassword = database_password();

$dbport = database_port();

$mysql = new mysqli($dbhostname, $dbusername, $dbpassword, $dbname, $dbport);

$role_name = "Administrateur pour les entreprises";
$role_shortname = "administrateur_entreprise";
$role_description = "role du plugin moodlepe";
$role_archetype = "manager";


// recupération de l'id du rôle qu'on a créé avec le fichier «install.php»

$check_role_id = $mysql->query("SELECT * FROM mdl_role WHERE name='$role_name' AND shortname='$role_shortname' AND description ='$role_description'", MYSQLI_USE_RESULT);

while ($row = mysqli_fetch_assoc($check_role_id)) {
    $roleid = $row['id'];
}

$contextid = 1;
$permission = 1;
$timemodified = time();
$modifierid = 2;






// *********************************** 1 *************************************
$c1  = "INSERT INTO mdl_role_capabilities (contextid, roleid, capability, permission, timemodified, modifierid) VALUES ('$contextid','$roleid','moodle/site:configview','$permission','$timemodified','$modifierid')";

$mysql->query($c1);




// *********************************** 2 *************************************
$c2  = "INSERT INTO mdl_role_capabilities (contextid, roleid, capability, permission, timemodified, modifierid) VALUES ('$contextid','$roleid','moodle/site:uploadusers','$permission','$timemodified','$modifierid')";

$mysql->query($c2);





// *********************************** 3 *************************************
$c3  = "INSERT INTO mdl_role_capabilities (contextid, roleid, capability, permission, timemodified, modifierid) VALUES ('$contextid','$roleid','moodle/course:create','$permission','$timemodified','$modifierid')";

$mysql->query($c3);




// *********************************** 4 *************************************
$c4  = "INSERT INTO mdl_role_capabilities (contextid, roleid, capability, permission, timemodified, modifierid) VALUES ('$contextid','$roleid','moodle/course:request','$permission','$timemodified','$modifierid')";

$mysql->query($c4);




// *********************************** 5 *************************************
$c5  = "INSERT INTO mdl_role_capabilities (contextid, roleid, capability, permission, timemodified, modifierid) VALUES ('$contextid','$roleid','moodle/site:approvecourse','$permission','$timemodified','$modifierid')";

$mysql->query($c5);




// *********************************** 6 *************************************
$c6  = "INSERT INTO mdl_role_capabilities (contextid, roleid, capability, permission, timemodified, modifierid) VALUES ('$contextid','$roleid','moodle/role:switchroles','$permission','$timemodified','$modifierid')";

$mysql->query($c6);




// *********************************** 7 *************************************
$c7  = "INSERT INTO mdl_role_capabilities (contextid, roleid, capability, permission, timemodified, modifierid) VALUES ('$contextid','$roleid','moodle/course:creategroupconversations','$permission','$timemodified','$modifierid')";

$mysql->query($c7);




// *********************************** 8 *************************************
$c8  = "INSERT INTO mdl_role_capabilities (contextid, roleid, capability, permission, timemodified, modifierid) VALUES ('$contextid','$roleid','moodle/course:managegroups','$permission','$timemodified','$modifierid')";

$mysql->query($c8);




// *********************************** 9 *************************************
$c9  = "INSERT INTO mdl_role_capabilities (contextid, roleid, capability, permission, timemodified, modifierid) VALUES ('$contextid','$roleid','mod/forum:cantogglefavourite','$permission','$timemodified','$modifierid')";

$mysql->query($c9);





// *********************************** 10 *************************************
$c10  = "INSERT INTO mdl_role_capabilities (contextid, roleid, capability, permission, timemodified, modifierid) VALUES ('$contextid','$roleid','moodle/blog:create','$permission','$timemodified','$modifierid')";

$mysql->query($c10);





// *********************************** 11 *************************************
$c11  = "INSERT INTO mdl_role_capabilities (contextid, roleid, capability, permission, timemodified, modifierid) VALUES ('$contextid','$roleid','moodle/blog:manageentries','$permission','$timemodified','$modifierid')";

$mysql->query($c11);





// *********************************** 12 *************************************
$c12  = "INSERT INTO mdl_role_capabilities (contextid, roleid, capability, permission, timemodified, modifierid) VALUES ('$contextid','$roleid','moodle/blog:search','$permission','$timemodified','$modifierid')";

$mysql->query($c12);





// *********************************** 13 *************************************
$c13  = "INSERT INTO mdl_role_capabilities (contextid, roleid, capability, permission, timemodified, modifierid) VALUES ('$contextid','$roleid','moodle/blog:view','$permission','$timemodified','$modifierid')";

$mysql->query($c13);





// *********************************** 14 *************************************
$c14  = "INSERT INTO mdl_role_capabilities (contextid, roleid, capability, permission, timemodified, modifierid) VALUES ('$contextid','$roleid','moodle/blog:viewdrafts','$permission','$timemodified','$modifierid')";

$mysql->query($c14);





// *********************************** 15 *************************************
$c15  = "INSERT INTO mdl_role_capabilities (contextid, roleid, capability, permission, timemodified, modifierid) VALUES ('$contextid','$roleid','moodle/course:recommendactivity','$permission','$timemodified','$modifierid')";

$mysql->query($c15);





// *********************************** 16 *************************************
$c16  = "INSERT INTO mdl_role_capabilities (contextid, roleid, capability, permission, timemodified, modifierid) VALUES ('$contextid','$roleid','moodle/question:config','$permission','$timemodified','$modifierid')";

$mysql->query($c16);



// *********************************** 17 *************************************
$c17  = "INSERT INTO mdl_role_capabilities (contextid, roleid, capability, permission, timemodified, modifierid) VALUES ('$contextid','$roleid','moodle/site:sendmessage','$permission','$timemodified','$modifierid')";

$mysql->query($c17);



// *********************************** 18 *************************************
$c18  = "INSERT INTO mdl_role_capabilities (contextid, roleid, capability, permission, timemodified, modifierid) VALUES ('$contextid','$roleid','moodle/user:readuserposts','$permission','$timemodified','$modifierid')";

$mysql->query($c18);





// *********************************** 19 *************************************
$c19  = "INSERT INTO mdl_role_capabilities (contextid, roleid, capability, permission, timemodified, modifierid) VALUES ('$contextid','$roleid','moodle/grade:edit','$permission','$timemodified','$modifierid')";

$mysql->query($c19);





// *********************************** 20 *************************************
$c20  = "INSERT INTO mdl_role_capabilities (contextid, roleid, capability, permission, timemodified, modifierid) VALUES ('$contextid','$roleid','moodle/user:editownprofile','$permission','$timemodified','$modifierid')";

$mysql->query($c20);



// *********************************** 21 *************************************
$c21  = "INSERT INTO mdl_role_capabilities (contextid, roleid, capability, permission, timemodified, modifierid) VALUES ('$contextid','$roleid','moodle/course:changefullname','$permission','$timemodified','$modifierid')";

$mysql->query($c21);



// *********************************** 22 *************************************
$c22  = "INSERT INTO mdl_role_capabilities (contextid, roleid, capability, permission, timemodified, modifierid) VALUES ('$contextid','$roleid','moodle/course:changeshortname','$permission','$timemodified','$modifierid')";

$mysql->query($c22);



// *********************************** 23 *************************************
$c23  = "INSERT INTO mdl_role_capabilities (contextid, roleid, capability, permission, timemodified, modifierid) VALUES ('$contextid','$roleid','moodle/course:changesummary','$permission','$timemodified','$modifierid')";

$mysql->query($c23);




// *********************************** 24 *************************************
$c24  = "INSERT INTO mdl_role_capabilities (contextid, roleid, capability, permission, timemodified, modifierid) VALUES ('$contextid','$roleid','moodle/user:changeownpassword','$permission','$timemodified','$modifierid')";

$mysql->query($c24);




// *********************************** 25 *************************************
$c25  = "INSERT INTO mdl_role_capabilities (contextid, roleid, capability, permission, timemodified, modifierid) VALUES ('$contextid','$roleid','mod/forum:addinstance','$permission','$timemodified','$modifierid')";

$mysql->query($c25);





// *********************************** 26 *************************************
$c26  = "INSERT INTO mdl_role_capabilities (contextid, roleid, capability, permission, timemodified, modifierid) VALUES ('$contextid','$roleid','moodle/user:readuserposts','$permission','$timemodified','$modifierid')";

$mysql->query($c26);





// *********************************** 27 *************************************
$c27  = "INSERT INTO mdl_role_capabilities (contextid, roleid, capability, permission, timemodified, modifierid) VALUES ('$contextid','$roleid','moodle/user:readuserblogs','$permission','$timemodified','$modifierid')";

$mysql->query($c27);










echo "<script> alert('L action a été effectué avec succès') </script>";
// mdl_role_context_levels
mysqli_close($mysql);
