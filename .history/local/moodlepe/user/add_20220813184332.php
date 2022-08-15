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

$timecreated = time();

// RECUPERER L'ID DE LA COHORTE DE L'UTILISATEUR CONNECTE

$cohort_check = $mysql->query("SELECT * FROM mdl_cohort c JOIN mdl_cohort_members m ON c.id = m.cohortid WHERE m.userid = '$authuserid'", MYSQLI_USE_RESULT);

while ($row = $cohort_check->fetch_assoc()) {
    $cohortid =  $row['cohortid'];
    $cohortname =  $row['name'];
}


$messageform = new local_moodlepe_message_form();

// if ($data = $messageform->get_data()) {

//     $message = required_param('message', PARAM_TEXT);

//     if (!empty($message)) {
//         // $record = new stdClass;
//         // $record->message = $message;
//         // $record->timecreated = time();
//         // $record->userid = $USER->id;

//         // $DB->insert_record('local_greetings_messages', $record);
//     } else {
//         echo "<script> alert('Je suis vide') </script>";
//     }
// }
echo $OUTPUT->header();
?>

<!-- // $messageform->display(); -->

<!-- <form method="post" action="post.php">
    ... Your form code goes here
</form> -->


<!-- *********************************** RESTAURER UN UTILISATEUR EXISTANT****************************************** -->
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
                        
                    
                    " name="submitbutton" id="id_submitbutton" value="Restaurer un utilisateur" data-initial-value="Créer utilisateur">
                    </span>
                    <div class="form-control-feedback invalid-feedback" id="id_error_submitbutton">

                    </div>
                </div>

        </fieldset>
        <div class="form-control-feedback invalid-feedback" id="fgroup_id_error_buttonar">

        </div>
    </div>
</form>

<!-- *********************************** FIN RESTAURER UN UTILISATEUR EXISTANT****************************************** -->




<!-- *********************************CREER UN UTILISATEUR DANS SON ENTREPRISE**************************************** -->

<form method="post" action="">





    <!-- ***************************************** USERNAME ( NOM D'UTILISATEUR ) ********************************* -->
    <div class="form-group row  fitem   ">
        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0" id="yui_3_17_2_1_1660412984554_729">

            <label class="d-inline word-break " for="id_username">
                Nom d'utilisateur
            </label>

            <div class="form-label-addon d-flex align-items-center align-self-start">
                <div class="text-danger" title="Requis">
                    <i class="icon fa fa-exclamation-circle text-danger fa-fw " title="Requis" role="img" aria-label="Requis"></i>
                </div>

            </div>
            <div class="form-label-addon d-flex align-items-center align-self-start" id="yui_3_17_2_1_1660412984554_728">
                <a class="btn btn-link p-0" role="button" data-container="body" data-toggle="popover" data-placement="right" data-content="<div class=&quot;no-overflow&quot;><p>Certains plugins d'authentification ne permettent pas la modification du nom d'utilisateur.</p>
</div> " data-html="true" tabindex="0" data-trigger="focus" data-original-title="" title="" id="yui_3_17_2_1_1660412984554_727">
                    <i class="icon fa fa-question-circle text-info fa-fw " title="Aide sur Nom d'utilisateur" role="img" aria-label="Aide sur Nom d'utilisateur" id="yui_3_17_2_1_1660412984554_732"></i>
                </a>
            </div>

        </div>
        <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
            <input type="text" class="form-control " name="username" id="id_username" value="" size="20" required>
            <div class="form-control-feedback invalid-feedback" id="id_error_username">

            </div>
        </div>
    </div>
    <!-- ***************************************** FIN USERNAME ( NOM D'UTILISATEUR ) ********************************* -->



    <!-- **************************************   NOM DE FAMILLE   ******************************************* -->
    <div id="fitem_id_lastname" class="form-group row  fitem   ">
        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">

            <label class="d-inline word-break " for="id_lastname">
                Nom
            </label>

            <div class="form-label-addon d-flex align-items-center align-self-start">
                <div class="text-danger" title="Requis">
                    <i class="icon fa fa-exclamation-circle text-danger fa-fw " title="Requis" role="img" aria-label="Requis"></i>
                </div>

            </div>
        </div>
        <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
            <input type="text" class="form-control " name="lastname" id="id_lastname" value="" size="30" maxlength="100" required>
            <div class="form-control-feedback invalid-feedback" id="id_error_lastname">

            </div>
        </div>
    </div>
    <!-- **************************************   FIN NOM DE FAMILLE   ******************************************* -->



    <!-- ********************************** PRENOM ****************************************** -->
    <div id="fitem_id_firstname" class="form-group row  fitem   ">
        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">

            <label class="d-inline word-break " for="id_firstname">
                Prénom
            </label>

            <div class="form-label-addon d-flex align-items-center align-self-start">
                <div class="text-danger" title="Requis">
                    <i class="icon fa fa-exclamation-circle text-danger fa-fw " title="Requis" role="img" aria-label="Requis"></i>
                </div>

            </div>
        </div>
        <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
            <input type="text" class="form-control " name="firstname" id="id_firstname" value="" size="30" maxlength="100" required>
            <div class="form-control-feedback invalid-feedback" id="id_error_firstname">

            </div>
        </div>
    </div>
    <!-- ********************************** FIN PRENOM ****************************************** -->




    <!-- *************************************************** EMAIL **************************************************** -->
    <div id="fitem_id_email" class="form-group row  fitem   ">
        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">

            <label class="d-inline word-break " for="id_email">
                Adresse de courriel
            </label>

            <div class="form-label-addon d-flex align-items-center align-self-start">
                <div class="text-danger" title="Requis">
                    <i class="icon fa fa-exclamation-circle text-danger fa-fw " title="Requis" role="img" aria-label="Requis"></i>
                </div>

            </div>
        </div>
        <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
            <input type="email" class="form-control " name="email" id="id_email" value="" size="30" maxlength="100" required>
            <div class="form-control-feedback invalid-feedback" id="id_error_email">

            </div>
        </div>
    </div>
    <!-- *************************************************** FIN  EMAIL **************************************************** -->



    <!-- **************************************** MOT DE PASSE ******************************************************** -->


    <div id="fitem_id_passwordpolicyinfo" class="form-group row  fitem femptylabel  ">
        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">

            <div class="form-label-addon d-flex align-items-center align-self-start">

            </div>
        </div>
        <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="static">
            <div class="form-control-static">
                Le mot de passe doit comporter au moins 8 caractère(s), au moins 1 chiffre(s), au moins 1 minuscule(s), au moins 1 majuscule(s), au moins 1 caractère(s) spéciaux tels que *, - ou #
            </div>
            <div class="form-control-feedback invalid-feedback" id="id_error_passwordpolicyinfo">

            </div>
        </div>
    </div>

    <div id="fitem_id_newpassword" class="form-group row  fitem   ">
        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">

            <label class="d-inline word-break " for="id_newpassword">
                Mot de passe
            </label>
            <div class="form-label-addon d-flex align-items-center align-self-start">
                <div class="text-danger" title="Requis">
                    <i class="icon fa fa-exclamation-circle text-danger fa-fw " title="Requis" role="img" aria-label="Requis"></i>
                </div>

            </div>

            <div class="form-label-addon d-flex align-items-center align-self-start">
                <a class="btn btn-link p-0" role="button" data-container="body" data-toggle="popover" data-placement="right" data-content="<div class=&quot;no-overflow&quot;><p>Saisissez un nouveau mot de passe en respectant le format exigé.</p>
</div> " data-html="true" tabindex="0" data-trigger="focus">
                    <i class="icon fa fa-question-circle text-info fa-fw " title="Aide sur Nouveau mot de passe" role="img" aria-label="Aide sur Nouveau mot de passe"></i>
                </a>
            </div>
        </div>
        <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
            <input type="password" class="form-control " name="password" id="id_password" value="" size="30" maxlength="100" required pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,24}$">
            <div class="form-control-feedback invalid-feedback" id="id_error_newpassword">

            </div>

            <!-- **************************************** FIN MOT DE PASSE ******************************************************** -->


            <div class="fdescription required">
                Ce formulaire comprend des champs requis, marqués <i class="icon fa fa-exclamation-circle text-danger fa-fw " title="Champ requis" role="img" aria-label="Champ requis"></i>.
            </div>

            <input name="entreprise" id="" value="">
            <!-- type="hidden" -->
            <!-- ***************************BOUTTON DE VALIDATION ET D'ANNULATION ********************** -->
            <div class=" col-md-9 form-inline align-items-start felement" data-fieldtype="group" id="yui_3_17_2_1_1660412984554_753">
                <fieldset class="w-100 m-0 p-0 border-0" id="yui_3_17_2_1_1660412984554_752">
                    <div class="d-flex flex-wrap align-items-center" id="yui_3_17_2_1_1660412984554_751">

                        <div class="form-group  fitem  " id="yui_3_17_2_1_1660412984554_750">
                            <span data-fieldtype="submit" id="yui_3_17_2_1_1660412984554_749">
                                <input type="submit" class="btn
                        btn-primary
                        
                    
                    " name="submitbutton" id="id_submitbutton" value="Créer utilisateur" data-initial-value="Créer utilisateur">
                            </span>
                            <div class="form-control-feedback invalid-feedback" id="id_error_submitbutton">

                            </div>
                        </div>

                        <div class="form-group  fitem   btn-cancel">
                            <span data-fieldtype="submit">
                                <input type="reset" class="btn
                        
                        btn-secondary
                    
                    " name="cancel" id="id_cancel" value="Annuler" data-skip-validation="1" data-cancel="1" onclick="skipClientValidation = true; return true;">
                            </span>
                            <div class="form-control-feedback invalid-feedback" id="id_error_cancel">

                            </div>
                        </div>
                    </div>
                </fieldset>
                <div class="form-control-feedback invalid-feedback" id="fgroup_id_error_buttonar">

                </div>
            </div>
            <!-- ***************************BOUTTON DE VALIDATION ET D'ANNULATION ********************** -->





            <!-- 
    <table>
        <tr>
            <div>
                <td><label>Nom </label></td>
                <td><input type="text" placeholder="Entrez votre nom" required></td>
            </div>
        </tr>
        <tr>
            <div>
                <td> <label>Prénom </label></td>
                <td> <input type="text" placeholder="Entrez votre prénom" required></td>
            </div>
        </tr>
        <tr>
            <div>
                <td><label>Email </label></td>
                <td><input type="email" placeholder="Entrez votre adresse mail" required></td>
            </div>
        </tr>
        <tr>
            <div>
                <td><label>Mot de passe </label></td>
                <td><input type="password" placeholder="Entrez votre mot de passe" required></td>
            </div>

        </tr>

        <tr>
            <div class="form-group row  fitem   ">
                <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">

                    <label class="d-inline word-break " for="id_firstname">
                        Prénom
                    </label>

                    <div class="form-label-addon d-flex align-items-center align-self-start">
                        <div class="text-danger" title="Requis">
                            <i class="icon fa fa-exclamation-circle text-danger fa-fw " title="Requis" role="img" aria-label="Requis"></i>
                        </div>

                    </div>
                </div>
                <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
                    <input type="text" class="form-control " name="firstname" id="id_firstname" value="" size="30" maxlength="100" required>
                    <div class="form-control-feedback invalid-feedback" id="id_error_firstname">

                    </div>
                </div>
            </div>
        </tr>
        <tr>
            <td>
                <input type="submit" value="Créer">
                <input type="reset" value="Annuler">
            </td>
        </tr>
    </table> -->
</form>



<!-- ***********************************************CREER UN UTILISATEUR DANS SON ENTREPRISE**************************************** -->


<?php
echo $OUTPUT->footer();
