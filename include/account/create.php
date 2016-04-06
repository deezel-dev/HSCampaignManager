<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/lib/swiftmailer/swift_required.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/database.php";

function createLocalAccount($email, $displayName, $password, $confirmPassword, $isTermsAccepted) {
    $result = array(
        "status" => "error",
        "errors" => array()
    );
    
    if (strlen($email) == 0 || !preg_match("/^([\w-\.\+]+@([\w-]+\.)+[\w-]{2,30})?$/", $email))
        $result["errors"][] = array("field" => "email", "message" => "Invalid email address");
    if (!preg_match("/^[a-zA-Z0-9][a-zA-Z0-9][a-zA-Z0-9]+$/", $displayName))
        $result["errors"][] = array("field" => "displayName", "message" => "Invalid name, must be at least 3 characters long and alphanumeric");
    if (strlen($password) < 6 || !preg_match("/[a-zA-Z]/", $password) || !preg_match("/[0-9]/", $password) || !preg_match("/^[a-zA-Z0-9!@#\$%\^&*\(\)]*$/", $password))
        $result["errors"][] = array("field" => "password", "message" => "Invalid password");
    if ($password != $confirmPassword)
        $result["errors"][] = array("field" => "confirmPassword", "message" => "Passwords don't match");
    if (!$isTermsAccepted)
        $result["errors"][] = array("field" => "isTermsAccepted", "message" => "You must accept the Terms of Use before submitting a Flix suggestion or additional content.");
    
    if (count($result["errors"]) > 0)
        return $result;
    
    $profileId = null;
    
    $data = getDatabase();
    
    if (!$data->open()) {
        $result["errors"][] = array("field" => "email", "message" => "Error occurred while verifying email");
        return $result;
    }
    
    $passwordSalt = mcrypt_create_iv(24);
    $passwordHash = crypt($password, $passwordSalt);
    $emailVerificationCode = base64_encode(mcrypt_create_iv(24));
    
    $data->execute("LOCK TABLES `profile`, localuser");
    
        $array = $data->getData(
            "SELECT `profile`.Profile_ID, localuser.LocalUser_ID " .
            "FROM `profile` LEFT JOIN localuser ON localuser.Profile_ID = `profile`.Profile_ID " .
            "WHERE Email = '" . mysql_real_escape_string($email) . "'");
        if (count($array) > 0) {
            foreach ($array as $row) {
                if ($row["Profile_ID"] != null)
                    $profileId = $row["Profile_ID"];
                if($row["LocalUser_ID"] != null) {
                    $result["errors"][] = array("field" => "email", "message" => "This address is already registered on FlixAcademy");
                    break;
                }
            }
        }

        if (count($result["errors"]) > 0)
            return $result;

        if (is_null($profileId))
            $data->execute("INSERT INTO `profile` (Email, DisplayName, IsEmailVerified, EmailVerificationCode) " .
                           "VALUES ('" . mysql_real_escape_string($email) . "', '" . mysql_real_escape_string($displayName) . "', 0, '" . $emailVerificationCode . "')");

        $data->execute(
            "INSERT INTO localuser (Profile_ID, PasswordHash, PasswordSalt, IsAdministrator) " .
            "SELECT Profile_ID, '" . base64_encode($passwordHash) . "', '" . base64_encode($passwordSalt) . "', 0 " .
            "FROM `profile` WHERE Email = '" . mysql_real_escape_string($email) . "'");
    
        $array = $data->getData(
            "SELECT LocalUser_ID, `profile`.Profile_ID Profile_ID, DisplayName, IsEmailVerified " .
            "FROM localuser INNER JOIN `profile` ON localuser.Profile_ID = `profile`.Profile_ID " .
            "WHERE Email = '" . mysql_real_escape_string($email) . "'");
    
    $data->execute("UNLOCK TABLES");
    
    $_SESSION[SESSION_PROFILE_DISPLAYNAME] = $array[0]["DisplayName"];
    $_SESSION[SESSION_PROFILE_ID] = $array[0]["Profile_ID"];
    $_SESSION[SESSION_LOCALUSER_ID] = $array[0]["LocalUser_ID"];
    $_SESSION[SESSION_PROFILE_ISEMAILVERIFIED] = $array[0]["IsEmailVerified"];
    $_SESSION[SESSION_USER_ISADMINISTRATOR] = false;
    
    $result["status"] = "success";
    return $result;
}



function sendVerificationEmail($profileId) {
    $data = getDatabase();
    if (!$data->open())
        return false;
    
    $emailVerificationCode = null;
    $emailAddress = null;
    $displayName = null;
    
    $data->execute("LOCK TABLES `profile`");
    
    $array = $data->getData("SELECT DisplayName, Email, EmailVerificationCode FROM `profile` WHERE Profile_ID = " . $profileId);

    if (count($array) == 0) {
        $data->execute("UNLOCK TABLES");
        return false;
    }
    
    $emailAddress = $array[0]["Email"];
    $displayName = $array[0]["DisplayName"];

    if ($array[0]["EmailVerificationCode"] == null) {
        $emailVerificationCode = base64_encode(mcrypt_create_iv(24));
        $data->execute("UPDATE `profile` SET EmailVerificationCode = '" . $emailVerificationCode . "' WHERE Profile_ID = " . $profileId);
    }
    else
        $emailVerificationCode = $array[0]["EmailVerificationCode"];
    
    $data->execute("UNLOCK TABLES");

    $transport = Swift_SmtpTransport::newInstance('gator4083.hostgator.com', 465, "ssl")
      ->setUsername('staff@flixacademy.com')
      ->setPassword('FlixMailP@ssw0rd');

    $mailer = Swift_Mailer::newInstance($transport);

    $message = Swift_Message::newInstance('Verify your email address')
      ->setFrom(array('staff@flixacademy.com' => 'FlixAcademy Team'))
      ->setTo(array($emailAddress))
      ->setBody(
        "Hi " . $displayName . ",\n\n" .
        "Thank you for registering at FlixAcademy.com! To complete the registration process we just need you to click or paste this one link:\n\n" .
        "http://" . $_SERVER['HTTP_HOST'] . "/account/verifyemailcomplete.php?email=" . urlencode($emailAddress) . "&key=" . urlencode($emailVerificationCode) . "\n\n" .
        "If you believe you received this message in error, please reply to this email or let us know at staff@flixacademy.com.\n\n" .
        "Cheers,\n" .
        "The FlixAcademy Team");

    $result = $mailer->send($message);
    
    return $result > 0;
}



function verifyEmailWithToken($email, $token) {
    $data = getDatabase();
    if (!$data->open())
        return false;
    
    $array = $data->getData("SELECT Profile_ID FROM `profile` WHERE Email = '" . mysql_real_escape_string($email) . "' AND EmailVerificationCode = '" . mysql_real_escape_string($token) . "'");
    
    if (count($array) == 0)
        return false;
    
    $profileId = $array[0]["Profile_ID"];
    
    $data->execute("UPDATE `profile` SET EmailVerificationCode = NULL, IsEmailVerified = 1 WHERE Profile_ID = " . $profileId);
    return true;
}
?>