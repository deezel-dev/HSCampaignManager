<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/lib/swiftmailer/swift_required.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/database.php";



function sendPasswordResetEmail($email) {
    $data = getDatabase();
    if (!$data->open())
        return false;
    
    $data->execute("UPDATE `profile` SET PasswordResetCode = '" . base64_encode(mcrypt_create_iv(24)) . "' WHERE Email = '" . mysql_real_escape_string($email) . "' AND PasswordResetCode IS NULL");
    $array = $data->getData("SELECT DisplayName, PasswordResetCode FROM `profile` INNER JOIN localuser ON `profile`.Profile_ID = localuser.Profile_ID WHERE Email = '" . mysql_real_escape_string($email) . "'");

    if (count($array) == 0)
        return false;
    
    $resetCode = $array[0]["PasswordResetCode"];
    $displayName = $array[0]["DisplayName"];

    $transport = Swift_SmtpTransport::newInstance('gator4083.hostgator.com', 465, "ssl")
      ->setUsername('staff@flixacademy.com')
      ->setPassword('FlixMailP@ssw0rd');

    $mailer = Swift_Mailer::newInstance($transport);

    $message = Swift_Message::newInstance('Password reset request')
      ->setFrom(array('staff@flixacademy.com' => 'FlixAcademy Team'))
      ->setTo(array($email))
      ->setBody(
        "Hi " . $displayName . ",\n\n" .
        "A request was made to reset your FlixAcademy.com password. If this is not the case, you may safely ignore this email. To proceed with the reset, please follow the below link:\n\n" .
        "http://" . $_SERVER['HTTP_HOST'] . "/account/forgotpassword-form.php?email=" . urlencode($email) . "&key=" . urlencode($resetCode) . "\n\n" .
        "Cheers,\n" .
        "The FlixAcademy Team");

    $result = $mailer->send($message);
    
    return true;
}



function isValidPasswordResetRequestWithToken($email, $token) {
    $data = getDatabase();
    if (!$data->open())
        return false;
    
    $array = $data->getData(
        "SELECT 0 FROM `profile` INNER JOIN localuser ON `profile`.Profile_ID = localuser.Profile_ID " .
        "WHERE Email = '" . mysql_real_escape_string($email) . "' AND PasswordResetCode = '" . mysql_real_escape_string($token) . "'");
    
    return (count($array) > 0);
}



function updatePasswordWithToken($email, $token, $password, $confirmPassword) {
    $result = array(
        "status" => "error",
        "errors" => array()
    );
    
    if (!isValidPasswordResetRequestWithToken($email, $token))
        $result["errors"][] = array("field" => "email", "message" => "Unable to verify your identity");
    else {
        if (strlen($password) < 8 || !preg_match("/[a-zA-Z]/", $password) || !preg_match("/[0-9]/", $password) || !preg_match("/^[a-zA-Z0-9!@#\$%\^&*\(\)]*$/", $password))
            $result["errors"][] = array("field" => "password", "message" => "Invalid password");
        if ($password != $confirmPassword)
            $result["errors"][] = array("field" => "confirmPassword", "message" => "Passwords don't match");
    }
    
    if (count($result["errors"]) > 0)
        return $result;
    
    $profileId = null;
    
    $data = getDatabase();
    
    if (!$data->open()) {
        $result["errors"][] = array("field" => "password", "message" => "Error occurred while updating your password. It has not been updated.");
        return $result;
    }
    
    $passwordSalt = mcrypt_create_iv(24);
    $passwordHash = crypt($password, $passwordSalt);
    
    $array = $data->getData(
        "SELECT `profile`.Profile_ID, localuser.LocalUser_ID " .
        "FROM `profile` INNER JOIN localuser ON localuser.Profile_ID = `profile`.Profile_ID " .
        "WHERE Email = '" . mysql_real_escape_string($email) . "'");
    
    if (count($array) > 0) {
        $profileId = $array[0]["Profile_ID"];
        $localUserId = $array[0]["LocalUser_ID"];
    }
    else {
        $result["errors"][] = array("field" => "email", "message" => "Could not locate your account");
        return $result;
    }
    
    $data->execute(
        "UPDATE localuser SET PasswordHash = '" . base64_encode($passwordHash) . "', PasswordSalt = '" . base64_encode($passwordSalt) . "' " .
        "WHERE LocalUser_ID = " . $localUserId);
    
    $result["status"] = "success";
    return $result;
}
?>