<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/database.php";

function signInLocal($email, $password) {    
    $result = array(
        "status" => "error",
        "errors" => array()
    );
    
    if (isset($_SESSION[SESSION_PROFILE_ID]))
        session_unset();
    
    if (strlen($email) == 0 || !preg_match("/^([\w-\.]+@([\w-]+\.)+[\w-]{2,30})?$/", $email))
        $result["errors"][] = "Invalid email address";
    if (strlen($password) < 8)
        $result["errors"][] = "Invalid password";
    
    if (count($result["errors"]) > 0)
        return $result;
    
    $data = getDatabase();
    
    if (!$data->open()) {
        $result["errors"][] = "Error occurred while signing in";
        return $result;
    }
    
    $displayName = null;
    $passwordSalt = null;
    $passwordHash = null;
    
    $array = $data->getData(
        "SELECT LocalUser_ID, `profile`.Profile_ID, DisplayName, PasswordSalt, PasswordHash, IsEmailVerified, IsAdministrator " .
        "FROM localuser INNER JOIN `profile` ON localuser.Profile_ID = `profile`.Profile_ID " .
        "WHERE Email = '" . mysql_real_escape_string($email) . "'");
    
    if (count($array) == 0) {
        $result["errors"][] = "Your email address and/or password could not be found";
        return $result;
    }
    
    $passwordSalt = base64_decode($array[0]["PasswordSalt"]);
    $passwordHash = base64_decode($array[0]["PasswordHash"]);
    $attemptedPasswordHash = crypt($password, $passwordSalt);
    
    if ($attemptedPasswordHash != $passwordHash) {
        $result["errors"][] = "Your email address and/or password could not be found";
        return $result;
    }
    
    $_SESSION[SESSION_PROFILE_DISPLAYNAME] = $array[0]["DisplayName"];
    $_SESSION[SESSION_PROFILE_ID] = $array[0]["Profile_ID"];
    $_SESSION[SESSION_LOCALUSER_ID] = $array[0]["LocalUser_ID"];
    $_SESSION[SESSION_PROFILE_ISEMAILVERIFIED] = $array[0]["IsEmailVerified"];
    $_SESSION[SESSION_USER_ISADMINISTRATOR] = $array[0]["IsAdministrator"];
    
    $result["status"] = "success";
    
    return $result;
}
?>