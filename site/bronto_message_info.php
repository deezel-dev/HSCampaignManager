<?php
/**
 * This example will match messages in your account that contain the word 'newsletter'
 * in their name.  It will then print out the message names and ids.
 */
 
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
 
  $filter = array('name' => array('operator' => 'Contains',
                  'value' => 'newsletter')
          );
 
  print "reading all matching messages\n";
  $messages = $client->readMessages(array('pageNumber' => 1,
                      'includeContent' => false,
                      'filter' => $filter))->return;
 
  // print matching message names and ids
  foreach ($messages as $message) {
    print "Name: " . $message->name . "; id: " . $message->id . "\n";
  }
 
} catch (Exception $e) {
  print "uncaught exception\n";
  print_r($e);
}
