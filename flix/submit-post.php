<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/flix/submit.php";
header("Content-Type: text/json");

$submission = json_decode(file_get_contents("php://input"));

$r2 = submitFlix(
        $submission->{"entryName"},
        $submission->{"entryLink"},
        $submission->{"entrySubject"},
        $submission->{"profileID"});

        //$_SESSION[SESSION_PROFILE_ID]
?>