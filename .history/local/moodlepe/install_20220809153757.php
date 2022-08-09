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
 * Code to be executed after the plugin's database scheme has been installed is defined here.
 *
 * @package     local_moodlepe
 * @category    upgrade
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


// defined('MOODLE_INTERNAL') || die();
require_once('../../config.php');
require_once($CFG->dirroot . '/local/moodlepe/edit_database_name.php');
require_once($CFG->libdir . '/gdlib.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/user/editadvanced_form.php');
require_once($CFG->dirroot . '/user/editlib.php');
require_once($CFG->dirroot . '/user/profile/lib.php');
require_once($CFG->dirroot . '/user/lib.php');
require_once($CFG->dirroot . '/webservice/lib.php');



require_once($CFG->dirroot . '/local/moodlepe/rolechange/classes/define_role.php');

require_once($CFG->dirroot . '/' . $CFG->admin . '/roles/lib.php');

global $DB;

$context = context_system::instance();

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


$role = new stdClass();

$role->id = 0;
$role->name = $role_name;
$role->shortname = $role_shortname;
$role->description = $role_description;
$role->sortorder = 9;
$role->archetype = $role_archetype;




// CREATION DU ROLE DANS LA BASE DE DONNEES 
// print_r($role);
$DB->insert_record('role', $role);

// $role_insert  = "INSERT INTO mdl_role (id,name, shortname, description, sortorder, archetype) VALUES (,'$role_name', '$role_shortname', '$role_description', ,'$role_archetype')";

// $mysql->query($role_insert);



// mysqli_query($mysql, "INSERT INTO mdl_role (name, shortname, description, archetype) VALUES ('$role_name', '$role_shortname', '$role_description', '$role_archetype')");


// recupération de l'id du rôle qu'on vient de créer

$check_role_id = $mysql->query("SELECT * FROM mdl_role WHERE name='$role_name' AND shortname='$role_shortname' AND description ='$role_description'", MYSQLI_USE_RESULT);

while ($row = mysqli_fetch_assoc($check_role_id)) {
    $verify_role_id = $row['id'];
}





// *******************************************************************CONTEXT LEVEL CREATION************************************* 

$contextlevel1 = 10;
// $contextlevel2 = 30;
// $contextlevel3 = 40;
// $contextlevel4 = 50;

// cl10
$context_level1  = "INSERT INTO mdl_role_context_levels (roleid, contextlevel) VALUES ('$verify_role_id', '$contextlevel1')";

$mysql->query($context_level1);

// cl30   
$context_level2  = "INSERT INTO mdl_role_context_levels (roleid, contextlevel) VALUES ('$verify_role_id', '$contextlevel2')";

$mysql->query($context_level2);
// cl40

$context_level3  = "INSERT INTO mdl_role_context_levels (roleid, contextlevel) VALUES ('$verify_role_id', '$contextlevel3')";

$mysql->query($context_level3);
// cl50

$context_level4  = "INSERT INTO mdl_role_context_levels (roleid, contextlevel) VALUES ('$verify_role_id', '$contextlevel4')";

$mysql->query($context_level4);




// ******************************************************************* ALLOW ASSIGN CREATION*************************************
// mdl_role_allow_assign

$allowassign1 = 2;
$allowassign2 = 3;
$allowassign3 = 4;
$allowassign4 = 5;
//

// // allow_assign1
// $allow_assign1 = new stdClass();

// $allow_assign1->roleid = $verify_role_id;
// $allow_assign1->allowassign = $allowassign1;
// $DB->insert_record('role_allow_assign', $allow_assign1);

$allow_assign1  = "INSERT INTO mdl_role_allow_assign (roleid, allowassign) VALUES ('$verify_role_id', '$allowassign1')";

$mysql->query($allow_assign1);

// allow_assign2

$allow_assign2  = "INSERT INTO mdl_role_allow_assign (roleid, allowassign) VALUES ('$verify_role_id', '$allowassign2')";

$mysql->query($allow_assign2);


// allow_assign3

$allow_assign3  = "INSERT INTO mdl_role_allow_assign (roleid, allowassign) VALUES ('$verify_role_id', '$allowassign3')";

$mysql->query($allow_assign3);

// allow_assign4

$allow_assign4  = "INSERT INTO mdl_role_allow_assign (roleid, allowassign) VALUES ('$verify_role_id', '$allowassign4')";

$mysql->query($allow_assign4);




// ******************************************************************* ALLOW OVERRIDE CREATION*************************************

// mdl_role_allow_override
$allowoverride1 = 2;
$allowoverride2 = 3;
$allowoverride3 = 4;
$allowoverride4 = 5;
$allowoverride5 = 6;
$allowoverride6 = 7;
$allowoverride7 = 8;

// allow_override1
// $allow_override1 = new stdClass();

// $allow_override1->roleid = $verify_role_id;
// $allow_override1->allowoverride = $allowoverride1;
// $DB->insert_record('role_allow_override', $allow_override1);


$allow_override1  = "INSERT INTO mdl_role_allow_override (roleid, allowoverride) VALUES ('$verify_role_id', '$allowoverride1')";

$mysql->query($allow_override1);


// allow_override2

$allow_override2  = "INSERT INTO mdl_role_allow_override (roleid, allowoverride) VALUES ('$verify_role_id', '$allowoverride2')";

$mysql->query($allow_override2);

// allow_override3

$allow_override3  = "INSERT INTO mdl_role_allow_override (roleid, allowoverride) VALUES ('$verify_role_id', '$allowoverride3')";

$mysql->query($allow_override3);


// allow_override4

$allow_override4  = "INSERT INTO mdl_role_allow_override (roleid, allowoverride) VALUES ('$verify_role_id', '$allowoverride4')";

$mysql->query($allow_override4);

// allow_override5

$allow_override5  = "INSERT INTO mdl_role_allow_override (roleid, allowoverride) VALUES ('$verify_role_id', '$allowoverride5')";

$mysql->query($allow_override5);

// allow_override6

$allow_override6  = "INSERT INTO mdl_role_allow_override (roleid, allowoverride) VALUES ('$verify_role_id', '$allowoverride6')";

$mysql->query($allow_override6);

// allow_override7

$allow_override7  = "INSERT INTO mdl_role_allow_override (roleid, allowoverride) VALUES ('$verify_role_id', '$allowoverride7')";

$mysql->query($allow_override7);




// ******************************************************************* ALLOW SWITCH CREATION*************************************
// mdl_role_allow_switch
$allowswitch1 = 2;
$allowswitch2 = 3;
$allowswitch3 = 4;
$allowswitch4 = 5;
$allowswitch5 = 6;


// allow_switch1

// $allow_switch1 = new stdClass();
// $allow_switch1->roleid = $verify_role_id;
// $allow_switch1->allowoverride = $allowswitch1;
// $DB->insert_record('role_allow_switch', $allow_switch1);

$allow_switch1  = "INSERT INTO mdl_role_allow_switch (roleid, allowswitch) VALUES ('$verify_role_id', '$allowswitch1')";

$mysql->query($allow_switch1);

// allow_switch2

$allow_switch2  = "INSERT INTO mdl_role_allow_switch (roleid, allowswitch) VALUES ('$verify_role_id', '$allowswitch2')";

$mysql->query($allow_switch2);

// allow_switch3

$allow_switch3  = "INSERT INTO mdl_role_allow_switch (roleid, allowswitch) VALUES ('$verify_role_id', '$allowswitch3')";

$mysql->query($allow_switch3);

// allow_switch4

$allow_switch4  = "INSERT INTO mdl_role_allow_switch (roleid, allowswitch) VALUES ('$verify_role_id', '$allowswitch4')";

$mysql->query($allow_switch4);

// allow_switch5

$allow_switch5  = "INSERT INTO mdl_role_allow_switch (roleid, allowswitch) VALUES ('$verify_role_id', '$allowswitch5')";

$mysql->query($allow_switch5);


// ******************************************************************* ALLOW VIEW CREATION*************************************

// mdl_role_allow_view
$allowview1 = 2;
$allowview2 = 3;
$allowview3 = 4;
$allowview4 = 5;
$allowview5 = 6;
$allowview6 = 7;
$allowview7 = 8;



// allow_view1

$allow_view1  = "INSERT INTO mdl_role_allow_view (roleid, allowview) VALUES ('$verify_role_id', '$allowview1')";

$mysql->query($allow_view1);


// allow_view2


$allow_view2  = "INSERT INTO mdl_role_allow_view (roleid, allowview) VALUES ('$verify_role_id', '$allowview2')";

$mysql->query($allow_view2);



// allow_view3

$allow_view3  = "INSERT INTO mdl_role_allow_view (roleid, allowview) VALUES ('$verify_role_id', '$allowview3')";

$mysql->query($allow_view3);



// allow_view4

$allow_view4  = "INSERT INTO mdl_role_allow_view (roleid, allowview) VALUES ('$verify_role_id', '$allowview4')";

$mysql->query($allow_view4);



// allow_view5


$allow_view5  = "INSERT INTO mdl_role_allow_view (roleid, allowview) VALUES ('$verify_role_id', '$allowview5')";

$mysql->query($allow_view5);



// allow_view6

$allow_view6  = "INSERT INTO mdl_role_allow_view (roleid, allowview) VALUES ('$verify_role_id', '$allowview6')";

$mysql->query($allow_view6);



// allow_view7

$allow_view7  = "INSERT INTO mdl_role_allow_view (roleid, allowview) VALUES ('$verify_role_id', '$allowview7')";

$mysql->query($allow_view7);


// ******************************************************************* CAPABILITES *************************************



// // mdl_role_context_levels
// mysqli_close($mysql);

// echo "<script> alert('j\'ai fini ow') </script>";

echo "<script> alert('Laction a été effectué avec succès') </script>";
// mdl_role_context_levels
mysqli_close($mysql);
