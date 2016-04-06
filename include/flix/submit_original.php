<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/database.php';

function submitFlix($entry, $profileId) {
    $data = getDatabase();
    
    if ($data->open()) {
        if (!isset($entry)) {
            $data->close();
            return;
        } else
            $qentry = "'" . mysql_real_escape_string($entry) . "'";
        
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

        $sql = "INSERT INTO productsuggestion (entry, email, Profile_ID) VALUES (" .
            ($qentry) . ", " .
            ($qipAddress) . ", " .
            ($qprofileId). ")";
        
        $data->insertData($sql);
    }
    
    return;
}

?>