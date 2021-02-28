<?php
namespace PhpTom;

class Member{
    private $ds;

    function __construct(){
        require_once './lib/DataSource.php';
        $this->ds = new DataSource();
    }

    /**
     * Vérifier si le username existe en BDD
     *
     * @param string $username
     * @return boolean
     */
    public function isUsernameExists($username){
        $query = 'SELECT * FROM tbl_member where username = ?';
        $paramType = 's';
        $paramValue = array(
            $username
        );
        $resultArray = $this->ds->select($query, $paramType, $paramValue);
        $count = 0;
        if (is_array($resultArray)) {
            $count = count($resultArray);
        }
        if ($count > 0) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    /**
     * Vérifier si le mail existe en BDD
     *
     * @param string $email
     * @return boolean
     */
    public function isEmailExists($email){
        $query = 'SELECT * FROM tbl_member where email = ?';
        $paramType = 's';
        $paramValue = array(
            $email
        );
        $resultArray = $this->ds->select($query, $paramType, $paramValue);
        $count = 0;
        if (is_array($resultArray)) {
            $count = count($resultArray);
        }
        if ($count > 0) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    /**
     * s'inscrire / enregistrer un utilisateur
     *
     * @return string[] registration status message
     */
    public function registerMember(){
        $isUsernameExists = $this->isUsernameExists($_POST["username"]);
        $isEmailExists = $this->isEmailExists($_POST["email"]);
        if ($isUsernameExists) {
            $response = array(
                "status" => "error",
                "message" => "Username already exists."
            );
        } else if ($isEmailExists) {
            $response = array(
                "status" => "error",
                "message" => "Email already exists."
            );
        } else {
            if (! empty($_POST["signup-password"])) {

// Le mot de passe de PHP_hash est un bon choix pour stocker les mots de passe
//Ne pas faire son propre cryptage si on est pas sûr
                $hashedPassword = password_hash($_POST["signup-password"], PASSWORD_DEFAULT);
            }
            $query = 'INSERT INTO tbl_member (username, password, email) VALUES (?, ?, ?)';
            $paramType = 'sss';
            $paramValue = array(
                $_POST["username"],
                $hashedPassword,
                $_POST["email"]
            );
            $memberId = $this->ds->insert($query, $paramType, $paramValue);
            if (! empty($memberId)) {
                $response = array(
                    "status" => "success",
                    "message" => "You have registered successfully."
                );
            }
        }
        return $response;
    }

    public function getMember($username){
        $query = 'SELECT * FROM tbl_member where username = ?'; // selection de l'ensemble des colonnes de la table
        $paramType = 's'; // définition du type du paramètre (STRING=s, INTEGER=i, ...)
        $paramValue = array(
            $username
        );
        $memberRecord = $this->ds->select($query, $paramType, $paramValue);
        return $memberRecord;
    }

    /**
     * Connexion d'un utilisateur
     *
     * @return string
     */
    public function loginMember(){
        $memberRecord = $this->getMember($_POST["username"]);
        $loginPassword = 0;
        if (! empty($memberRecord)) {
            if (! empty($_POST["login-password"])) {
                $password = $_POST["login-password"];
            }
            $hashedPassword = $memberRecord[0]["password"];
            $loginPassword = 0;
            if (password_verify($password, $hashedPassword)) {
                $loginPassword = 1;
            }
        } else {
            $loginPassword = 0;
        }
        if ($loginPassword === 1) {
// connexion réussie  stockage du username et récupération de lâge du membre dans la session
            session_start();
            $_SESSION["username"] = $memberRecord[0]["username"];
            $_SESSION["age"] = $memberRecord[0] ["age"];
            session_write_close();
            $url = "./home.php";
            header("Location: $url");
        } else if ($loginPassword === 0) {
            $loginStatus = "Invalid username or password.";
            return $loginStatus;
        }
    }
}