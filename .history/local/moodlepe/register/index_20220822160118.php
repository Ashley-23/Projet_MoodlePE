<?php
if (!isset($_GET['erreur'])) {
	echo "";
} else {

	$erreur = $_GET['erreur'];
	if ($erreur == "echec") {
		$errormessage = "! Le nom de votre entreprise ou de votre mot de passe n'est pas identique à la vérification. Veuillez ressaisir les champs";
	}
	if ($erreur == "lastorfirstnameerror") {
		// (NOM ET PRENOM) EXISTENT DEJA
		$nomprenom_errormessage = "! Le nom et le prénom saisis existent déjà";
	}
	if ($erreur == "emailerror") {
		// EMAIL EXISTE DEJA 
		$email_errormessage = "! Cet email existe déjà";
	}
	if ($erreur == "usernameerror") {
		// USERNAME EXISTE DEJA 
		$username_errormessage = "! Ce nom d'utilisateur existe déjà";
	}
	if ($erreur == "entrepriseerror") {
		// ENTREPRISE EXISTE DEJA
		$entreprise_errormessage = "! Cette entreprise existe déjà";
	}
}


// VERIFICATIONS POUR L'AFFICHAGE DES PRIX
if (!isset($_GET['offre'])) {
	header('Location: index.php');
	exit();
}

$offer_id = $_GET['offre'];

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

// FIN VERIFICATIONS POUR L'AFFICHAGE DES PRIX

require_once('../../../config.php');
// require_once('config.php');

if (isloggedin() == false) {
	// redirect($CFG->wwwroot . '/ash.php');
	// echo "<script> alert('false') </script>";
?>




	<!DOCTYPE html>
	<html>

	<head>
		<meta charset="utf-8">
		<title>RegistrationForm_v1 by Colorlib</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<link rel="icon" href="http://localhost:8080/moodlepe/pix/i/moodle_host.png">
		<!-- MATERIAL DESIGN ICONIC FONT -->
		<link rel="stylesheet" href="register/fonts/material-design-iconic-font/css/material-design-iconic-font.min.css">

		<!-- STYLE CSS -->
		<link rel="stylesheet" href="register/css/style.css">
	</head>

	<body bgcolor="#8f00ff">
		<!-- //VERIFICATION DES MOT DE PASSE -->
		<script type="text/javascript">
			var check = function() {
				if (document.getElementById('password').value ==
					document.getElementById('confirm_password').value) {
					document.getElementById('message').style.color = 'green';
					document.getElementById('message').innerHTML = 'Validé';
				} else {
					document.getElementById('message').style.color = 'red';
					document.getElementById('message').innerHTML = 'Non validé';
				}
			}

			var et = function() {
				if (document.getElementById('entreprise').value ==
					document.getElementById('confirm_entreprise').value) {
					document.getElementById('emessage').style.color = 'green';
					document.getElementById('emessage').innerHTML = 'Validé';
				} else {
					document.getElementById('emessage').style.color = 'red';
					document.getElementById('emessage').innerHTML = 'Non validé';
				}
			}
		</script>
		<!-- //FIN VERIFICATION DES MOTS DE PASSE -->
		<!-- style="background-image: url('register/images/bg-registration-form-1.jpg');" -->
		<div class="wrapper">
			<div class="inner">
				<div class="image-holder">
					<img src="register/images/signup-image.jpg" alt="">
					<div>
						<h4>
							<span>
								<h1>Votre choix</h1>
							</span>
						</h4>



						<!--            <ul class="list-group mb-3">
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">  Gratuit</h6>
                <small class="text-muted">Description</small>
              </div>
              <span class="text-muted"> 0 </span>
            </li>
            
            <li class="list-group-item d-flex justify-content-between">
              <span>Total (F CFA)</span>
              <strong> 0 </strong>
            </li>
          </ul> -->

						<table>
							<tr>
								<td>
									<h2> Description : </h2>
								</td>
								<td></td>
								<td></td>
								<td></td>
								<td>
									<h2> <?php echo $product_name; ?> </h2>
								</td>
							</tr>
							<tr>
								<td>
									<h2> Total (FCFA) </h2>
								</td>
								<td></td>
								<td></td>
								<td></td>
								<td>
									<h2> <?php echo $product_price; ?> </h2>
								</td>
							</tr>
						</table>



					</div>




				</div>
				<form method="POST" action="/moodlepe/local/moodlepe/register/index2.php">
					<h3>COMPTE ENTREPRISE</h3>
					<!-- span -->
					<span style="color:red">
						<?php echo $errormessage; ?>
					</span>
					<!-- span -->
					<div class="form-group">
						<input type="text" placeholder="Prénom(s)" class="form-control" required name="userfirstname">
						<input type="text" placeholder="Nom" class="form-control" required name="userlastname">
					</div>
					<span style="color:red">
						<?php echo $nomprenom_errormessage; ?>
					</span>
					<div class="form-wrapper">
						<input type="text" placeholder="Nom d'utilisateur" class="form-control" required name="username">
						<i class="zmdi zmdi-account"></i>
					</div>
					<span style="color:red">
						<?php echo $username_errormessage; ?>
					</span>
					<!-- span -->
					<div class="form-wrapper">
						<input type="email" placeholder="Email" class="form-control" required name="useremail">
						<i class="zmdi zmdi-email"></i>
					</div>
					<span style="color:red">
						<?php echo $email_errormessage; ?>
					</span>
					<!-- span -->
					<!-- 	<div class="form-wrapper">
						<input type="text" placeholder="Nom de l'entreprise" class="form-control">
						<select name="" id="" class="form-control">
							<option value="" disabled selected>Gender</option>
							<option value="male">Male</option>
							<option value="femal">Female</option>
							<option value="other">Other</option>
						</select>
						<i class="zmdi zmdi-caret-down" style="font-size: 17px"></i>
					</div>
					<div class="form-wrapper">
						<input type="text" placeholder="Confirmez le nom de l'entreprise" class="form-control">
						<i class="zmdi zmdi-caret-down" style="font-size: 17px"></i>
					</div> -->
					<div class="form-group">
						<input type="text" placeholder="Entreprise" class="form-control" id="entreprise" onkeyup='et();' required name="userentreprise1">
						<input type="text" placeholder="Entreprise" class="form-control" id="confirm_entreprise" onkeyup='et();' required name="userentreprise2">
					</div>

					<span id='emessage'></span>

					<span style="color:red">
						<?php echo $entreprise_errormessage; ?>
					</span>
					<!-- span -->

					<div class="form-wrapper">
						<span class="has-text-white h4">(+228) 22 50 30 82 </span>
						<input type="password" placeholder="Mot de passe" class="form-control" id="password" required name="userpassword1">
						<!-- <i class="zmdi zmdi-lock"></i> -->
					</div>
					<div class="form-wrapper">
						<input type="password" placeholder="Confirmer le mot de passe" class="form-control" id="confirm_password" onkeyup='check();' required name="userpassword2">
						<!-- <i class="zmdi zmdi-lock"></i> -->
						<span id='message'></span>
					</div>
					<!-- ENVOIE DE L'OFFRE CHOISI POUR POUVOIR REVENIR SUR CETTE PAGE EN CAS D'ERREUR -->
					<input type="hidden" value="<?php echo $offer_id ?>" name="offre">

					<!-- ENVOIE DE L'OFFRE CHOISI POUR POUVOIR REVENIR SUR CETTE PAGE EN CAS D'ERREUR -->
					<!-- <div > -->
					<button type="submit" class="form-control">Valider
						<i class="zmdi zmdi-arrow-right"></i>
					</button>

					<button type="reset" class="form-control">Annuler
						<i class="zmdi zmdi-arrow-right"></i>
					</button>
					<!-- <input type="reset" value="Annuler" > -->
					<!-- </div> -->
					<p> <a href="/moodlepe/index.php"> Retourner à l'accueil</a> </p>

				</form>
			</div>
		</div>

	</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

	</html>


<?php
}
?>