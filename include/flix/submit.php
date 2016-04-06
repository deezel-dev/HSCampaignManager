<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/database.php';

function submitFlix($entryName, $entryLink, $entrySubject, $profileId) {
    $data = getDatabase();
    
    if ($data->open()) {
        if (!isset($entryName) && !isset($entryLink)) {
            $data->close();
            return;
        } else

            $qentryName = "'" . mysql_real_escape_string($entryName) . "'";
            $qentryLink = "'" . mysql_real_escape_string($entryLink) . "'";
            $qentrySubjects = "'" . mysql_real_escape_string($entrySubject) . "'";
        
        if (!isset($profileId) || $profileId == null)
            $qprofileId = "NULL";
        else
            $qprofileId = "'" . mysql_real_escape_string($profileId) . "'";
        
        if (!isset($_SERVER['REMOTE_ADDR']))
            $qipAddress = "NULL";
        else {
            $qipAddress = $_SERVER['REMOTE_ADDR'];
            
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
                $qipAddress .= "," . $_SERVER['HTTP_X_FORWARDED_FOR'];
            
            $qipAddress = "'" . $qipAddress . "'";
        }
        
        $sql = "INSERT INTO productsuggestion (entry, movie_link, subject, email, Profile_ID) VALUES (" .
            ($qentryName) . ", " .
            ($qentryLink) . ", " .
            ($qentrySubjects) . ", " .
            ($qipAddress) . ", " .
            ($qprofileId). ")";

        $data->insertData($sql);
    }
    
    return; 
}


?>