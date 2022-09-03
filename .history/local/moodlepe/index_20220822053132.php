<?php
if (isset($_GET['reset'])) {

    $reset = $_GET['reset'];

    if ($reset === "ok") {
?>
        <script>
            // $('#reset').modal('show');

            // document.onload(function() {
            // var s = $('#reset').modal('show');
            // document.getElementById('#reset') = modal('show');

            $(document).ready(function() {
                // $(".reset").load("reset.modal('show')");
                <?php echo "toi"; ?>
            });
            // });

            // Swal.fire('Any fool can use a computer');

            // $('#reset').modal('show');
        </script>
<?php
        // echo "<script> alert('Le mot de passe a été réinitialisé avec succès') </script>";

    }
}
?> <?php

    if (isset($_GET['suppr'])) {

        $suppression = $_GET['suppr'];

        if ($suppression == "ok") {
            echo "<script> alert('La suppression a été effectué avec succès') </script>";
        }
    }


    if (isset($_GET['userexist'])) {

        $userexist = $_GET['userexist'];

        if ($userexist == "ok") {
            echo "<script> alert('Cet utilisateur est un administrateur') </script>";
        }
    }



    if (isset($_GET['emailoruser'])) {

        $emailoruser = $_GET['emailoruser'];

        if ($emailoruser == "ok") {
            echo "<script> alert('Cet utilisateur existe déjà') </script>";
        }
    }







    if (isset($_GET['mod'])) {

        $modification = $_GET['mod'];

        if ($modification == "ok") {
            echo "<script> alert('La modification a été effectué avec succès') </script>";
        }
    }

    if (isset($_GET['create'])) {

        $modification = $_GET['create'];

        if ($modification == "ok") {
            echo "<script> alert('La création a été effectué avec succès') </script>";
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
    // echo $userid;

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

    // echo "l'id de notre role est : " . $verify_role_id;

    // RECUPERER L'ID DU ROLE DE L'UTILISATEUR CONNECTE



    $role_check = $mysql->query("SELECT r.id,r.name, a.userid FROM mdl_role r JOIN mdl_role_assignments a ON r.id = a.roleid WHERE a.userid='$userid' AND a.roleid='$verify_role_id'", MYSQLI_USE_RESULT);

    while ($row = $role_check->fetch_assoc()) {
        $roleid =  $row['id'];
    }

    if (isset($roleid)) {



        if ($roleid == $verify_role_id) {
            // C'EST ICI QUE TOUT SE PASSE

            $context = context_system::instance();
            $PAGE->set_context($context);
            $PAGE->set_url(new moodle_url('/local/moodlepe/index.php'));
            $PAGE->set_pagelayout('standard');
            $PAGE->set_title($SITE->fullname);
            $PAGE->set_heading(get_string('name', 'local_moodlepe'));

            echo $OUTPUT->header();

            // // RECUPERER L'ID DE L'UTILISATEUR CONNECTE
            // $userid = $USER->id;


            // RECUPERER L'ID DE LA COHORTE DE L'UTILISATEUR CONNECTE

            $cohort_check = $mysql->query("SELECT c.id FROM mdl_cohort c JOIN mdl_cohort_members m ON c.id = m.cohortid WHERE m.userid = '$userid'", MYSQLI_USE_RESULT);

            while ($row = $cohort_check->fetch_assoc()) {
                $cohortid =  $row['id'];
            }

            // RECUPERER LA LISTE D'UTILISATEURS 
            // ****************************************************************************************************************************************************************************************************
            $userlist = $mysql->query("SELECT u.id, u.firstname ,u.lastname ,u.username, u.email,c.name  FROM mdl_cohort c JOIN mdl_cohort_members m  ON c.id = m.cohortid JOIN mdl_user u  ON u.id = m.userid WHERE m.cohortid='$cohortid'AND u.id!='$userid' ", MYSQLI_USE_RESULT);


            $users = array();
            while ($row = $userlist->fetch_assoc()) {
                $users[] = array('id' => $row['id'], 'nom' => $row['lastname'], 'prenom' => $row['firstname'], 'username' => $row['username'], 'email' => $row['email'], 'entreprise' => $row['name']);
            }

    ?>
        <!-- -->

        <?php ?>


        <!-- *********************************** AJOUTER UN UTILISATEUR****************************************** -->
        <form action="/moodlepe/local/moodlepe/user/add.php">
            <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="group" id="yui_3_17_2_1_1660412984554_753">
                <fieldset class="w-100 m-0 p-0 border-0" id="yui_3_17_2_1_1660412984554_752">
                    <div class="d-flex flex-wrap align-items-center" id="yui_3_17_2_1_1660412984554_751">

                        <div class="form-group  fitem  " id="yui_3_17_2_1_1660412984554_750">
                            <span data-fieldtype="submit" id="yui_3_17_2_1_1660412984554_749">
                                <input type="submit" class="btn
                        btn-primary
                        
                    
                    " id="id_submitbutton" value="Ajouter un utilisateur" data-initial-value="Ajouter un utilisateur">
                            </span>
                            <div class="form-control-feedback invalid-feedback" id="id_error_submitbutton">

                            </div>
                        </div>

                </fieldset>
                <div class="form-control-feedback invalid-feedback" id="fgroup_id_error_buttonar">

                </div>
            </div>
        </form>
        <!-- *********************************** FIN AJOUTER UN UTILISATEUR****************************************** -->





        <div class="no-overflow">
            <div class="table-responsive">
                <table class="admintable generaltable table-sm" id="users">
                    <thead>
                        <tr>
                            <th class="header c0 centeralign" style="" scope="col"> Prénom / Nom</th>
                            <th class="header c1 centeralign" style="" scope="col"> Adresse de courriel </th>
                            <th class="header c4" style="" scope="col"> Nom d'utilisateur</th>
                            <th class="header c4" style="" scope="col"> Entreprise</th>
                            <th class="header c5" style="" scope="col"> Modifier</th>
                            <td class="header c6 lastcol" style=""></td>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        foreach ($users as $user) {
                            echo "<tr class=''>";
                            echo "<td class='centeralign cell c0' style=''>" . $user['nom'] . ' ' . $user['prenom'] . "</td>";
                            echo "<td class='centeralign cell c1' style=''>" . $user['email'] . "</td>";
                            echo "<td class='centeralign cell c1' style=''>" . $user['username'] . "</td>";
                            echo "<td class='centeralign cell c1' style=''>" . $user['entreprise'] . "</td>";
                            // echo "<td class='cell c4' style=''>maintenant</td>";
                        ?>
                            <td class="cell c5" style="" id="yui_3_17_2_1_1660560784812_579">
                                <!-- <input type="submit" style="color:blue" onclick="getid('<?php echo $user['username'] ?>');" value="Modifier"> -->
                                <!--  -->

                                <a class="icon fa fa-cog fa-fw " title="Modifier" role="img" aria-label="Modifier" onclick="getid('<?php echo $user['username'] ?>');"></a>
                                <!--  -->
                                <a class="icon fa fa-eye fa-fw " onclick="resetpassword('<?php echo $user['username'] ?>');get_username('<?php echo $user['username'] ?>');" title="Réinitialiser le mot de passe" role="img" aria-label="Réinitialiser le mot de passe"></a>
                                <!--  -->
                                <a class="icon fa fa-trash fa-fw" onclick="check('<?php echo $user['username'] ?>');get_user_name('<?php echo $user['username'] ?>');" title='Supprimer' role='img' aria-label='Supprimer'></i></a>

                            </td>

                        <?php
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        </table>

        <!-- MODAL REINITIALISATION DU MOT DE PASSE -->
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Voulez vous reinitialiser le mot de passe de <span id="recup_username"> </span> ? </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button id="suppr_user" type="submit" class="btn btn-primary" onclick="ash('<?php echo $user['username'] ?>');">Continuer</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL REINITIALISATION DU MOT DE PASSE -->


        <!-- SUPPRIMER UN UTILISATEUR -->
        <!-- Modal -->
        <div class="modal fade" id="suppr" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel7" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel7">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Voulez vous supprimer l'utilisateur <span id="suppr_recup_username"> </span> ? </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button id="send_username" type="submit" class="btn btn-primary" onclick="kokoe();">Continuer</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- SUPPRIMER UN UTILISATEUR -->



        <div class='modal fade' id="reset" tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel8' aria-hidden='true'>
            <div class='modal-dialog' role='document'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='exampleModalLabel8'>Réinitialiser le mot de passe</h5>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                    <div class='modal-body'>
                        Le mot de passe a été réinitialisé avec succès
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-secondary' data-dismiss='modal'>Ok</button>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            var check = function(username) {
                // if (confirm('Voulez-vous vraiment supprimer l\'utilisateur « ' + username + ' » ?')) {
                //     window.location.href = "/moodlepe/local/moodlepe/user/delete.php?username=" + username;
                // } else {
                //     return false;
                // };
                $('#suppr').modal('show');

            }


            var getid = function(username) {
                // return id;
                window.location.href = "/moodlepe/local/moodlepe/user/update.php?username=" + username;
            };


            var resetpassword = function(username) {
                // if (confirm('Voulez-vous vraiment réinitialiser le mot de passe de l\'utilisateur « ' + username + ' » ?')) {
                //     window.location.href = "/moodlepe/local/moodlepe/user/resetpassword.php?username=" + username;
                // } else {
                //     return false;
                // };

                $('#exampleModal').modal('show');
                // var send = username;
                // var btn = document.getElementById('send_username');
            }

            var get_username = function(id) {
                var div = document.getElementById('recup_username');
                div.innerHTML = id;
            }


            var get_user_name = function(id) {
                var div = document.getElementById('suppr_recup_username');
                div.innerHTML = id;
            }

            var ash = function(username) {
                // return id;
                window.location.href = "/moodlepe/local/moodlepe/user/resetpassword.php?username=" + username;
            };

            var kokoe = function() {
                // return id;
                var username1 = get_user_name(id);
                window.location.href = "/moodlepe/local/moodlepe/user/delete.php?username=" + username1;
            };

            function pass(value) {
                "a"
                return value;
            }

            function receive_pass(x, func) {
                console.log(func(x));
            }

            receive_pass("David", pass);
        </script>
        <!-- </form> -->

<?php
            echo $OUTPUT->footer();

            // TERMINUS
        } else {
            //ON LE REDIRIGE
            echo "1 , mon role est : " . $roleid;
            // header("Location: /moodlepe/index.php");
            die();
        }
    } else {
        // ON LE REDIRIGE

        header("Location: /moodlepe/index.php");
        die();
    }

    die();
