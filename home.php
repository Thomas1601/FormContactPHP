<?php
session_start();
    if (isset($_SESSION["username"])) {
        $username = $_SESSION["username"];
        session_write_close();
    } else {
    // puisque le nom d'utilisateur n'est pas défini en session, l'utilisateur n'est pas connecté
    // il essaie d'accéder à cette page sans autorisation
    // donc effaçons toutes les variables de session et le redirigeons vers l'index
        session_unset();
        session_write_close();
        $url = "index.php";
        header("Location: $url");
    }
?>

<HTML>
<HEAD>
<TITLE>Compte de <?php  echo $_SESSION['username'] ?></TITLE>
<link href="assets/css/phpTom-style.css" type="text/css" rel="stylesheet" />
<link href="assets/css/user-registration.css" type="text/css" rel="stylesheet" />
</HEAD>
<BODY>
	<div class="phpTom-container">
		<div class="page-header">
			<span class="login-signup"><a href="logout.php">Logout</a></span>
		</div>
		<div class="page-content">Bienvenue <br>
            <p> <?php echo "Bonjour : " .$_SESSION["username"]. ", tu as " .$_SESSION["age"]. " ans . "; ?></p>
        </div>
	</div>
</BODY>
</HTML>