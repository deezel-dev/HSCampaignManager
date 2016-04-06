<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/lib/swiftmailer/swift_required.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/database.php";
header("Content-Type: text/json");

$user = json_decode(file_get_contents("php://input"));

$addUser = addUser($user->{"email"});

if($addUser){
    echo(json_encode($addUser));
    //sendVerificationEmail($user->{"email"});
}

function addUser($email) {
    
    $response = false;

    if (!isset($_SERVER['REMOTE_ADDR']))
            $qipAddress = "NULL";
    else {
        $qipAddress = $_SERVER['REMOTE_ADDR'];
            
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $qipAddress .= "," . $_SERVER['HTTP_X_FORWARDED_FOR'];
            
    }
    
    $sql = "INSERT INTO prospects(email, ip_address)
            VALUES ('" . padSql($email) ."','" .$qipAddress . "')";    
      
    $data = getDatabase();
    
    if ($data->open()) {
        if($data->insertData($sql)){
            $response = true;
        }
    } 

    return $response;
    
}



function padSql($subject){
    return str_replace ("'","''",$subject);
  }

  function sendVerificationEmail($emailAddress) {
   
    $transport = Swift_SmtpTransport::newInstance('gator4083.hostgator.com', 465, "ssl")
      ->setUsername('staff@flixacademy.com')
      ->setPassword('FlixMailP@ssw0rd');

    $mailer = Swift_Mailer::newInstance($transport);

    $message = Swift_Message::newInstance('Thank you for supporting FlixAcademy')
      ->setFrom(array('staff@flixacademy.com' => 'FlixAcademy Team'))
      ->setTo(array($emailAddress))
      ->setBody(
        
        "Thank you for showing interest in FlixAcademy and bringing movies to the classroom." .
        "\n" . 
        "We want to engage students with multimedia lesson plans using the latest Hollywood movies and would like for you to get involved.  Here's how:" .
        "\n" . 
        "\n" . 
        "Cheers,\n" .
        "The FlixAcademy Team");

    $result = $mailer->send($message);
    
    return $result > 0;
}
?>