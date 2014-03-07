  <?php

/**
 * Récupérer la véritable adresse IP d'un visiteur
 */
function get_ip() {
	// IP si internet partagé
	if (isset($_SERVER['HTTP_CLIENT_IP'])) {
		return $_SERVER['HTTP_CLIENT_IP'];
	}
	// IP derrière un proxy
	elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		return $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	// Sinon : IP normale
	else {
		return (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
	}
}

//connexion à mysql

  $host='localhost';
  $account='root';
  $password='8D5*N%ut';
  $dbname='newsletter';
  $connect=mysqli_connect($host,$account,$password,$dbname);

//récupération de l'email
 $email=$_POST['email'];


// Check connection
 if (mysqli_connect_error())
 {
 	$reponse = "Oups! Un problème est survenu";
 }

 elseif( isset($email)) {

 	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

 		$query = "SELECT mail, newsletter from personne where mail='$email'";
 		$result = mysqli_query($connect,$query);
 		$resultat = mysqli_fetch_array($result, MYSQLI_ASSOC);

 		if(mysqli_num_rows($result) < 1)
 		{

 			$reponse = 'L\'adresse '. $email .' n\'est pas abonnée à cette newsletter.';
 		}
 		else {	
 			$bool_newsletter=$resultat['newsletter'];
 			
 			if($bool_newsletter == 0 ){

 				$reponse = 'L\'adresse '. $email .' est déjà désabonnée de cette newsletter.';
 			}
 			else{
			 	$adresse_ip=get_ip();
			 	$sql= "UPDATE personne SET newsletter='0', adresse_ip_desabonnement='$adresse_ip', date_desabonnement=now() WHERE mail='$email'";

				$result=mysqli_query($connect,$sql);
				mysqli_close($connect);

				$reponse = 'Merci ! L\'adresse '. $email .' est désormais désabonnée de la newsletter Smart\'Unity !';
			}

		}

	}
	else {
		$reponse = 'Veuillez rentrer une adresse email valide';
	}
} 

else {
	$reponse = 'Tous les champs ne sont pas parvenus';
}

echo $reponse;





?>
 