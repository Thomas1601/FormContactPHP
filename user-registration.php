<?php
use PhpTom\Member;
if (! empty($_POST["signup-btn"])) {
    require_once './Model/Member.php';
    $member = new Member();
    $registrationResponse = $member->registerMember();
}
?>
<HTML>
<HEAD>
    <TITLE>User Registration</TITLE>
    <link href="assets/css/phpTom-style.css" type="text/css" rel="stylesheet" />
    <link href="assets/css/user-registration.css" type="text/css" rel="stylesheet" />
    <script src="vendor/jquery/jquery-3.3.1.js" type="text/javascript"></script>
</HEAD>
<BODY>
<div class="phpTom-container">
    <div class="sign-up-container">
        <div class="login-signup">
            <a href="index.php">Login</a>
        </div>
        <div class="">
            <form name="sign-up" action="" method="post"
                  onsubmit="return signupValidation()">
                <div class="signup-heading">Registration</div>
                <?php
                if (! empty($registrationResponse["status"])) {
                    ?>
                    <?php
                    if ($registrationResponse["status"] == "error") {
                        ?>
                        <div class="server-response error-msg"><?php echo $registrationResponse["message"]; ?></div>
                        <?php
                    } else if ($registrationResponse["status"] == "success") {
                        ?>
                        <div class="server-response success-msg"><?php echo $registrationResponse["message"]; ?></div>
                        <?php
                    }
                    ?>
                    <?php
                }
                ?>
                <div class="error-msg" id="error-msg"></div>
                <div class="row">
                    <div class="inline-block">
                        <div class="form-label">
                            Username<span class="required error" id="username-info"></span>
                        </div>
                        <input class="input-box-330" type="text" name="username" id="username">
                    </div>
                </div>
                <div class="row">
                    <div class="inline-block">
                        <div class="form-label">
                            age<span class="required error" id="age-info"></span>
                        </div>
                        <input class="input-box-330" type="number" size="2" name="age" id="age">
                    </div>
                </div>
                <div class="row">
                    <div class="inline-block">
                        <div class="form-label">
                            Email<span class="required error" id="email-info"></span>
                        </div>
                        <input class="input-box-330" type="email" name="email" id="email">
                    </div>
                </div>
                <div class="row">
                    <div class="inline-block">
                        <div class="form-label">
                            Password<span class="required error" id="signup-password-info"></span>
                        </div>
                        <input class="input-box-330" type="password" name="signup-password" id="signup-password">
                    </div>
                </div>
                <div class="row">
                    <div class="inline-block">
                        <div class="form-label">
                            Confirm Password<span class="required error" id="confirm-password-info"></span>
                        </div>
                        <input class="input-box-330" type="password"
                               name="confirm-password" id="confirm-password">
                    </div>
                </div>
                <div class="row">
                    <input class="btn" type="submit" name="signup-btn"
                           id="signup-btn" value="Sign up">
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function signupValidation() {
        var valid = true;
        //Supprime l’élément sélectionné (et ses éléments enfant)
        $("#username").removeClass("error-field"); //
        $("#age").removeClass("error-field");
        $("#email").removeClass("error-field");
        $("#password").removeClass("error-field");
        $("#confirm-password").removeClass("error-field");

        // Retournez l’attribut de valeur.
        var UserName = $("#username").val(); // Nous récupérons la valeur de nos inputs que l'on fait passer à connexion.php
        var Age = $("#age").val();
        var Email = $("#email").val();
        var Password = $('#signup-password').val();
        var ConfirmPassword = $('#confirm-password').val();

        //Expression régulière pour le username, le mail et l'âge
        var usernameRegex = /^[A-Z][a-zàéèêëîïôöûüùç.]+([ -][A-Z][a-zàéèêëîïôöûüùç.])*/;
        var emailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
        var ageRegex = /^(1[0-9]|7[0-9]|8[0])$/;

// La méthode .hide() permet de cacher les éléments.
        $("#username-info").html("").hide();
        $("#age-info").html("").hide();
        $("#email-info").html("").hide();
// Retirez l’espace blanc des deux côtés d’une chaîne
        if (UserName.trim() == "") {
            $("#username-info").html("required.").css("color", "#ee0000").show();
            $("#username").addClass("error-field");
            valid = false;
        }
        if (Age <= 17 || Age >= 81){
            $("#age-info").html("age entre 18-80 ans.").css("color", "#ee0000").show();
            $("#age").addClass("error-field");
            valid = false;
// La méthode test() teste pour une correspondance dans une chaîne
        }else if (!ageRegex.test(Age) && (age = "")) {
            $("#age-info").html("age invalid.").css("color", "#ee0000").show();
            $("#age").addClass("error-field");
            valid = false;
        }
        if (Email == "") {
            $("#email-info").html("required").css("color", "#ee0000").show();
            $("#email").addClass("error-field");
            valid = false;
        } else if (Email.trim() == "") {
            $("#email-info").html("Invalid email address.").css("color", "#ee0000").show();
            $("#email").addClass("error-field");
            valid = false;
        } else if (!emailRegex.test(Email)) {
            $("#email-info").html("Invalid email address.").css("color", "#ee0000")
                .show();
            $("#email").addClass("error-field");
            valid = false;
        }
        if (Password.trim() == "") {
            $("#signup-password-info").html("required.").css("color", "#ee0000").show();
            $('#signup-password').addClass("error-field");
            valid = false;
        }
        if (ConfirmPassword.trim() == "") {
            $("#confirm-password-info").html("required.").css("color", "#ee0000").show();
            $("#confirm-password").addClass("error-field");
            valid = false;
        }
        if(Password != ConfirmPassword){
            $("#error-msg").html("Les 2 mots de passe doivent être identiques.").show();
            valid=false;
        }
        if (valid == false) {
            $('.error-field').first().focus();
            valid = false;
        }
        return valid;
    }
</script>
</BODY>
</HTML>