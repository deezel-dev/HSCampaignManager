<?php
/**
 * This example will return all mailing lists in your account.  It will then
 * print each list's name; the number of active contacts on the list; and the list id.
 */
 

$json = "";

$client = new SoapClient('https://api.bronto.com/v4?wsdl', array('trace' => 1, 
                                 'features' => SOAP_SINGLE_ELEMENT_ARRAYS));
 
try {
  $token = "405A3A08-E33D-466C-86C6-8A3ED6627377";
 
  print "logging in\n";
  $sessionId = $client->login(array('apiToken' => $token))->return;
 
  $session_header = new SoapHeader("http://api.bronto.com/v4",
                   'sessionHeader',
                   array('sessionId' => $sessionId));
  $client->__setSoapHeaders(array($session_header));
 
  $filter = array();
 
  print "reading all lists\n";
  $lists = $client->readLists(array('pageNumber' => 1,
                    'filter' => $filter))->return;
 
 $json.= "<table>";
  // print matching list names, number of contacts on the list, and ids
  foreach ($lists as $list) {
    //print "Name: " . $list->name . "; contacts: " . $list->activeCount . "; id: " . $list->id . "\n";
    $json.= "<tr><td>" . $list->name . "</td><td>" . $list->activeCount . "</td></tr>";
  }
 $json.= "<table>";
 print $json
 
} catch (Exception $e) {
  print "uncaught exception\n";
  print_r($e);
}
