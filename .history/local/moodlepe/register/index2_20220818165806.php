<?php

/**
 * Plugin version and other meta-data are defined here.
 *
 * @package     local_moodlepe
 * @copyright   2022 SATCHIVI Kokoè Yasmine Ashley <ashleysatchivi92@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('../../../config.php');
require_once($CFG->dirroot . '/local/moodlepe/edit_database_name.php');

//CONNEXION AVEC MA BASE DE DONNEE
//OUVERTURE

$dbhostname = database_hostname();

$dbname =  database_name();

$dbusername = database_username();

$dbpassword = database_password();

$dbport = database_port();

$mysql = new mysqli($dbhostname, $dbusername, $dbpassword, $dbname, $dbport);

// $mysql = new mysqli("", "root", "", "moodle", 3306);

// TRAVAIL AVEC LE NOMBRE DE LA LIGNE 
/* Récupère un tableau d'objets */
// while ($row = $skya->fetch_row()) {
// 	echo $row[7];
// }



// TRAVAIL AVEC LE NOM DE LA LIGNE 
// while ($row = mysqli_fetch_assoc($skya)) {
// 	echo $row['username'] ;
// }



/**
 * Allows you to edit a users profile
 *
 * @copyright 1999 Martin Dougiamas http://dougiamas.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package core_user
 */
require_once('../../../config.php');
require_once($CFG->libdir . '/gdlib.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/user/editadvanced_form.php');
require_once($CFG->dirroot . '/user/editlib.php');
require_once($CFG->dirroot . '/user/profile/lib.php');
require_once($CFG->dirroot . '/user/lib.php');
require_once($CFG->dirroot . '/webservice/lib.php');

global $DB;

$systemcontext = context_system::instance();



// ELEMENTS DE LA CREATION DE L'UTILISATEUR
$id = -1;
$mnethostid = 1;
$lang = "fr";

// ELEMENTS DE LA CREATION DU RÔLE
$role_contextid = 1;
$role_descriptionformat = 1;
$role_visible = 1;
$timecreated = time();
$timemodified = time();


// ELEMENTS DE LA COHORTE

$insertion_cohorte_contextid = 1;
$insertion_cohorte_descriptionformat = 1;
$insertion_cohorte_visible = 1;




$usercontext = null;
$editoroptions = array(
	'maxfiles' => 0,
	'maxbytes' => 0,
	'trusttext' => false,
	'forcehttps' => false,
	'context' => $coursecontext
);



// Verification : si les valeurs sont vides... retour à la page des coordonnées
if (isset($_POST['userfirstname']) || isset($_POST['userlastname']) || isset($_POST['username']) || isset($_POST['useremail']) || isset($_POST['userentreprise1']) || isset($_POST['userentreprise2']) || isset($_POST['userpassword1']) || isset($_POST['userpassword2']) || isset($_POST['offre'])) {
	$prenom = $_POST['userfirstname'];
	$nom = $_POST['userlastname'];
	$nomutilisateur = $_POST['username'];
	$email = $_POST['useremail'];
	$entreprise1 = $_POST['userentreprise1'];
	$entreprise2 = $_POST['userentreprise2'];
	$mdp1 = $_POST['userpassword1'];
	$mdp2 = $_POST['userpassword2'];
	$offer_id = $_POST['offre'];

	// *************************POUR RECUPERER LE NOM ET LE PRIX DE L'OFFRE CHOISI PAR L'UTILISATEUR ********************************
	if ($offer_id == 1) {
		$product_name = "Gratuit";
		$product_price = 0;
	} elseif ($offer_id == 2) {
		$product_name = "Basic";
		$product_price = 50000;
	} elseif ($offer_id == 3) {
		$product_name = "Premium";
		$product_price = 100000;
	}
	// *************************POUR RECUPERER LE NOM ET LE PRIX DE L'OFFRE CHOISI PAR L'UTILISATEUR ********************************
	// echo "<script> alert($offer_id) </script>";
	// echo "ma valeur" . $offer_id;
	// die();


	if ($entreprise1 == $entreprise2 && $mdp1 == $mdp2) {
		$userfirstname = $prenom;
		$userlastname = $nom;
		$username = $nomutilisateur;
		$useremail = $email;
		$userentreprise = $entreprise2;
		$userpassword =	$mdp2;
		$timecreated = time();

		//CRE UNE NOUVELLE INSTANCE D'UTILISATEUR
		if ($id == -1) {
			// Creating new user.
			$user = new stdClass();

			$user->auth = 'manual';
			$user->confirmed = 1;
			$user->deleted = 0;
			$user->timezone = '99';
			$user->username = $username;
			$user->firstname = ucfirst($userfirstname);
			$user->lastname = strtolower($userlastname);
			$user->mnethostid = $mnethostid;
			$user->lang = $lang;
			$user->password = hash_internal_user_password($userpassword);
			$user->email = $useremail;
			$user->timecreated = time();
			$user->timemodified = time();
		}
		// print_r($user);
		// var_dump($user);

		//CREE UNE NOUVELLE INSTANCE DE COHORT 

		$cohort = new stdClass();

		$cohort->contextid = $insertion_cohorte_contextid;
		$cohort->name = $userentreprise;
		$cohort->idnumber = $userentreprise;
		$cohort->descriptionformat = $insertion_cohorte_descriptionformat;
		$cohort->visible = $insertion_cohorte_visible;
		$cohort->timecreated = time();
		$cohort->timemodified = time();





		// REQUETES POUR RECUPERER LE CHAMP VOULU DANS LA BASE DE DONNEE S'IL COORESPOND A LA SAISIE DE L'UTILISATEUR
		$check_user_username = $mysql->query("SELECT * FROM mdl_user WHERE username='$username'");
		$check_user_firstname = $mysql->query("SELECT * FROM mdl_user WHERE firstname='$userfirstname'");
		$check_user_lastname = $mysql->query("SELECT * FROM mdl_user WHERE lastname='$userlastname'");
		$check_user_email = $mysql->query("SELECT * FROM mdl_user WHERE email='$useremail'");
		//**************VERIFIE LE NOM DE LA COHORT
		$check_user_cohort_name = $mysql->query("SELECT * FROM mdl_cohort WHERE name='$userentreprise'");
		//**************VERIFIE L'ID' DE LA COHORT
		$check_user_cohort_idnumber = $mysql->query("SELECT * FROM mdl_cohort WHERE idnumber='$userentreprise'");
		//**************VERIFIE LE NOM LONG DU ROLE
		$check_user_role_name = $mysql->query("SELECT * FROM mdl_role WHERE name='$userentreprise'");
		//**************VERIFIE LE NOM COURT DU ROLE
		$check_user_role_shortname = $mysql->query("SELECT * FROM mdl_role WHERE shortname='$userentreprise'");

		// VERIFICATION DE L'EXISTANCE DU NOM D'UTILISATEUR

		// while ($row = mysqli_fetch_assoc($check_user_username)) {
		// 	$verify_user_username = $row['username'];
		// }


		// var_dump($row1["firstname"]);


		// $row1 = $check_user_firstname->fetch_array(MYSQLI_ASSOC);
		// $verify_user_firstname = $row1["firstname"];


		// $row3 = $verify_user_lastname->fetch_array(MYSQLI_ASSOC);
		// $verify_user_firstname = $row1["firstname"];

		// $row4 = $check_user_firstname->fetch_array(MYSQLI_ASSOC);
		// $verify_user_firstname = $row1["firstname"];

		// $row = $check_user_username->fetch_array(MYSQLI_ASSOC);
		// $verify_user_username = $row["username"];


		$row1 = $check_user_username->fetch_array(MYSQLI_ASSOC);
		$verify_user_username = $row1["username"];
		if (empty($verify_user_username)) {
			$username_result = 0;
		} else {
			if ($verify_user_username == $username) {
				$username_result = 1;
			} else {
				$username_result = 0;
			}
		}

		// VERIFICATION DE L'EXISTANCE DU PRENOM DE L'UTILISATEUR

		// while ($row = mysqli_fetch_assoc($check_user_firstname)) {
		// 	$verify_user_firstname = $row['firstname'];
		// }
		$row2 = $check_user_firstname->fetch_array(MYSQLI_ASSOC);
		$verify_user_firstname = $row2["firstname"];

		if (empty($verify_user_firstname)) {
			$firstname_result = 0;
		} else {
			if ($verify_user_firstname == $userfirstname) {
				$firstname_result = 1;
			} else {
				$firstname_result = 0;
			}
		}

		// VERIFICATION DE L'EXISTANCE DU NOM DE FAMILLE DE L'UTILISATEUR

		// while ($row = mysqli_fetch_assoc($check_user_lastname)) {
		// 	$verify_user_lastname = $row['lastname'];
		// }
		$row3 = $check_user_lastname->fetch_array(MYSQLI_ASSOC);
		$verify_user_lastname = $row3["lastname"];
		if (empty($verify_user_lastname)) {
			$lastname_result = 0;
		} else {
			if ($verify_user_lastname == $userlastname) {
				$lastname_result = 1;
			} else {
				$lastname_result = 0;
			}
		}


		// VERIFICATION DE L'EXISTANCE DE L'EMAIL DE L'UTILISATEUR

		// while ($row = mysqli_fetch_assoc($check_user_email)) {
		// 	$verify_user_email = $row['email'];
		// }
		$row4 = $check_user_email->fetch_array(MYSQLI_ASSOC);
		$verify_user_email = $row4["email"];

		if (empty($verify_user_email)) {
			$email_result = 0;
		} else {
			if ($verify_user_email == $useremail) {
				$email_result = 1;
			} else {
				$email_result = 0;
			}
		}



		// VERIFICATION DU NOM DE LA COHORT

		// while ($row = mysqli_fetch_assoc($check_user_cohort_name)) {
		// 	$verify_cohort_name = $row['name'];
		// }

		$row5 = $check_user_cohort_name->fetch_array(MYSQLI_ASSOC);
		$verify_cohort_name = $row5["name"];

		if (empty($verify_cohort_name)) {
			$cohort_name_result = 0;
		} else {
			if ($verify_cohort_name == $userentreprise) {
				$cohort_name_result = 1;
			} else {
				$cohort_name_result = 0;
			}
		}

		// VERIFICATION DE L'IDNUMBER DE LA COHORT

		// while ($row = mysqli_fetch_assoc($check_user_cohort_idnumber)) {
		// 	$verify_cohort_idnumber = $row['idnumber'];
		// }
		$row6 = $check_user_cohort_idnumber->fetch_array(MYSQLI_ASSOC);
		$verify_cohort_idnumber = $row6["idnumber"];

		if (empty($verify_cohort_idnumber)) {
			$cohort_idnumber_result = 0;
		} else {
			if ($verify_cohort_idnumber == $userentreprise) {
				$cohort_idnumber_result = 1;
			} else {
				$cohort_idnumber_result = 0;
			}
		}


		// VERIFICATION DU NOM DU ROLE 

		// while ($row = mysqli_fetch_assoc($check_user_role_name)) {
		// 	$verify_role_name = $row['name'];
		// }
		$row7 = $check_user_role_name->fetch_array(MYSQLI_ASSOC);
		$verify_role_name = $row7["name"];

		if (empty($verify_role_name)) {
			$role_name_result = 0;
		} else {
			if ($verify_role_name == $userentreprise) {
				$role_name_result = 1;
			} else {
				$role_name_result = 0;
			}
		}

		// VERIFICATION DU NOM COURT DU ROLE 

		// while ($row = mysqli_fetch_assoc($check_user_role_shortname)) {
		// 	$verify_role_shortname = $row['shortname'];
		// }
		$row8 = $check_user_role_shortname->fetch_array(MYSQLI_ASSOC);
		$verify_role_shortname = $row8["shortname"];

		if (empty($verify_role_shortname)) {
			$role_shortname_result = 0;
		} else {
			if ($verify_role_shortname == $userentreprise) {
				$role_shortname_result = 1;
			} else {
				$role_shortname_result = 0;
			}
		}

		echo "<script> alert($offer_id) </script>";

		// LES REDIRECTIONS AU CAS OU UN CHAMP EXISTE 

		// *******************USERNAME EXISTE
		if ($username_result == 1) {
			// // 
			$usernameerror = "usernameerror";
			header("Location: /moodlepe/local/moodlepe/register/index.php?erreur=$usernameerror&offre=$offer_id");
			die();
		}


		// *******************EMAIL EXISTE
		if ($email_result == 1) {
			// 
			$emailerror = "emailerror";
			header("Location: /moodlepe/local/moodlepe/register/index.php?erreur=$emailerror&offre=$offer_id");
			die();
		}


		// *******************PRENOM ET NOM DE FAMILLE EXISTE
		if ($firstname_result == 1 && $lastname_result == 1) {
			// 
			$lastorfirstnameerror = "lastorfirstnameerror";
			header("Location: /moodlepe/local/moodlepe/register/index.php?erreur=$lastorfirstnameerror&offre=$offer_id");
			die();
		}

		// *******************NOM DE LA COHORTE, EXISTE
		if ($cohort_idnumber_result == 1 || $cohort_name_result == 1 || $role_name_result  == 1 || $role_shortname_result  == 1) {
			// 
			$entrepriseerror = "entrepriseerror";
			header("Location: /moodlepe/local/moodlepe/register/index.php?offre=$offer_id&erreur=$entrepriseerror");
			die();
		}




		// echo "</br> username saisi : " . $username;
		// echo "</br> verify username : " . $verify_user_username;
		// echo '</br>';

		// echo "</br> firstname saisi : " . $userfirstname;
		// echo "</br> verify firstname : " . $verify_user_firstname;
		// echo '</br>';

		// echo "</br> lastname saisi : " . $userlastname;
		// echo "</br> verify lastname : " . $verify_user_lastname;
		// echo '</br>';

		// echo "</br> email saisi : " . $useremail;
		// echo "</br> verify email : " . $verify_user_email;
		// echo '</br>';

		// echo "</br> entreprise saisi : " . $userentreprise;
		// echo "</br> verify cohort name  : " . $verify_cohort_name;
		// die();

		// UTILISATEURS

		$DB->insert_record('user', $user);


		// $mdl_user  = "INSERT INTO mdl_user ( auth, confirmed, deleted,timezone,  username, firstname, lastname,mnethostid, lang,  password, email, timecreated, timemodified ) VALUES ('manual', 1, 0,99,'$username', '$userfirstname', '$userlastname',1, '$lang', '$userpassword', '$useremail',  '$timecreated', '$timemodified')";




		// $mysql->query($moodle_pe_user);



		//RECUPERE L'ID DE L'UTILISATEUR QU'IL VIENT DE CREER 
		$check_user_id = $mysql->query("SELECT * FROM mdl_user WHERE email='$useremail'", MYSQLI_USE_RESULT);

		while ($row = mysqli_fetch_assoc($check_user_id)) {
			$verify_user_id = $row['id'];
		}

		//CRE UNE NOUVELLE INSTANCE DE LA BASE DE DONNEE DE MON PLUGIN LOCAL
		$record = new stdClass;
		$record->username = $username;
		$record->firstname = strtolower($userfirstname);
		$record->lastname = strtoupper($userlastname);
		$record->password = hash_internal_user_password($userpassword);
		$record->email = $useremail;
		$record->enterprise = $userentreprise;
		$record->timecreated = time();
		$record->user_id = $verify_user_id;
		$record->offreid = $offer_id;
		$record->nomoffre = $product_name;
		$record->prixoffre = $product_price;

		// UTILISATEURS DANS MON PLUGIN

		$DB->insert_record('local_moodlepe_user', $record);
		// $moodle_pe_user  = "INSERT INTO mdl_local_moodlepe_user (username, firstname, lastname, password, email, enterprise, timecreated ) VALUES ('$username', '$userfirstname', '$userlastname', '$userpassword', '$useremail', '$userentreprise', '$timecreated')";


		// COHORT 
		$DB->insert_record('cohort', $cohort);

		// $mdl_cohort  = "INSERT INTO mdl_cohort (contextid, name, idnumber, descriptionformat, visible, timecreated, timemodified ) VALUES ('$insertion_cohorte_contextid', '$userentreprise', '$userentreprise', '$insertion_cohorte_descriptionformat', '$insertion_cohorte_visible', '$timecreated', '$timemodified')";

		// $mysql->query($mdl_cohort);





		//RECUPERE L'ID DE LA COHORTE QU'IL VIENT DE CREER 
		$check_cohorte_id = $mysql->query("SELECT * FROM mdl_cohort WHERE name='$userentreprise' AND idnumber='$userentreprise'", MYSQLI_USE_RESULT);

		while ($row = mysqli_fetch_assoc($check_cohorte_id)) {
			$verify_cohort_id = $row['id'];
		}




		//CREE UNE NOUVELLE INSTANCE DE COHORT_MEMBERS 
		$cohort_members = new stdClass();

		$cohort_members->cohortid = $verify_cohort_id;
		$cohort_members->userid = $verify_user_id;
		$cohort_members->timeadded = time();

		// INSCRIPTION DE L'UTILISATEUR DANS LA NOUVELLE COHORTE
		$DB->insert_record('cohort_members', $cohort_members);

		// $mdl_cohort_members  = "INSERT INTO mdl_cohort_members (cohortid, userid, timeadded ) VALUES ('$verify_user_id', '$verify_user_id','$timecreated')";

		// $mysql->query($mdl_cohort_members);

		// ATTRIBUTION DE "NOTRE" ROLE SYSTEM L'UTILISATEUR CREE

		$role_name = "Administrateur pour les entreprises";
		$role_shortname = "administrateur_entreprise";
		$role_description = "role du plugin moodlepe";
		$role_archetype = "manager";

		$check_role_id = $mysql->query("SELECT * FROM mdl_role WHERE name='$role_name' AND shortname='$role_shortname' AND description ='$role_description'", MYSQLI_USE_RESULT);

		while ($row = mysqli_fetch_assoc($check_role_id)) {
			$verify_role_id = $row['id'];
		}



		$role_assignment_contextid = 1;
		$role_assignments_modifierid = 2;

		$role_assignement = new stdClass();
		$role_assignement->roleid = $verify_role_id;
		$role_assignement->contextid = $role_assignment_contextid;
		$role_assignement->userid = $verify_user_id;
		$role_assignement->timemodified = time();
		$role_assignement->modifierid = $role_assignments_modifierid;

		// INSCRIPTION DE L'UTILISATEUR dans le role créé 
		$DB->insert_record('role_assignments', $role_assignement);
		// $mdl_role_assignments  = "INSERT INTO mdl_role_assignments (roleid, contextid, userid, timemodified, modifierid ) VALUES ('$verify_role_id', '$role_assignment_contextid','$verify_user_id','$timecreated', '$role_assignments_modifierid')";

		// $mysql->query($mdl_role_assignments);


		// *********************************CREER UNE CATEGORIE DU SERVICE


		$mdl_course_categories  =  "INSERT INTO mdl_course_categories (name, descriptionformat, parent , sortorder, coursecount, visible, visibleold, timemodified, depth, path) VALUES ('$userentreprise', 1,0,'',0,1,1,'$timecreated',1,'')";
		$mysql->query($mdl_course_categories);

		$update_mdl_course_categories = "UPDATE mdl_course_categories SET password='$final_password' WHERE id='$useren'";
		$mysql->query($update_mdl_course_categories);
		// *********************************CREER UNE CATEGORIE DU SERVICE
		// REDIRIGER VERS LA PAGE D'ACCUEIL 
		$success = "succes";

		// header("Location: /moodlepe/index.php");
		header("Location: /moodlepe/index.php");
		die();
	} else {
		// RETOURNE SUR LA PAGE AVEC UN MESSAGE D'ERREUR A AFFICHER
		$echec = "echec";
		header("Location: /moodlepe/local/moodlepe/register/index.php?erreur=$echec&offre=$offer_id");
	}
}

mysqli_close($mysql);


// TRAVAIL AVEC LE NOMBRE DE LA LIGNE 
/* Récupère un tableau d'objets */
// while ($row = $skya->fetch_row()) {
// 	echo $row[7];
// }



// TRAVAIL AVEC LE NOM DE LA LIGNE 
// while ($row = mysqli_fetch_assoc($skya)) {
// 	echo $row['username'] ;
// }
