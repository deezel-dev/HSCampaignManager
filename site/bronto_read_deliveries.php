<?php
/**
 * This example will read the details of any delivery made in your Bronto
 * account in the last 24 hours, and print out what message was sent, to how
 * many contacts, how many messages were actually delivered, and how many of
 * those contacts opened or clicked on links in your message.
 */
 
$client = new SoapClient('https://api.bronto.com/v4?wsdl', array('trace' => 1, 
                                 'features' => SOAP_SINGLE_ELEMENT_ARRAYS));

$json = "";

setlocale(LC_ALL, 'en_US');
 
try {
  $token = "405A3A08-E33D-466C-86C6-8A3ED6627377";
 
  //print "logging in\n";
  $sessionId = $client->login(array('apiToken' => $token))->return;
 
  $session_header = new SoapHeader("http://api.bronto.com/v4",
                   'sessionHeader',
                   array('sessionId' => $sessionId));
  $client->__setSoapHeaders(array($session_header));
 
  // compute date/time 30 days ago
  $startDate = date('c', time() - (2* 24 * 60 * 60 * 30)); // 24 hours * 60 minutes * 60 seconds * 30 days;
 
  // set up a filter to read deliveries in the last 24 hours
  $filter = array('start' => array('operator' => 'After',
                   'value' => $startDate,
                   ),
          'status' => 'sent',
          );
 
 // print "reading deliveries completed from past 24 hours\n";
  $deliveries = $client->readDeliveries(array('pageNumber' => 1,
                        'includeRecipients' => false,
                        'includeContent' => false,
                        'filter' => $filter,
                        )
                      )->return;
 
 $json.="<table>";
 $json.="<tr>";
 $json.="<td>name</td>";
 $json.="<td>sent at</td>";
 $json.="<td>numSends</td>";
 $json.="<td>numDeliveries</td>";
 $json.="<td>numOpens</td>";
 $json.="<td>numClicks</td>";
 $json.="<td>numConversions</td>";
 $json.="<td>revenue</td>";
 $json.="</tr>";
  // print matching results
  foreach ($deliveries as $delivery) {
    // get name of the message sent.
    $msgFilter = array('id' => $delivery->messageId);
    $message = array_pop($client->readMessages(array('pageNumber' => 1,
                             'includeContent' => false,
                             'filter' => $msgFilter))->return);
    $startString = strftime('%c', strtotime($delivery->start));
   $json.="<tr>";
     $json.="<td>" . $message->name . "</td>";
     $json.="<td>" . $startString . "</td>";   
     $json.="<td>" . $delivery->numSends . "</td>";
     $json.="<td>" . $delivery->numDeliveries. "</td>";
     $json.="<td>" . $delivery->numOpens. "</td>";
     $json.="<td>" . $delivery->numClicks . "</td>";
     $json.="<td>" . $delivery->numConversions . "</td>";
     $json.="<td>" . $delivery->revenue . "</td>";
     //$json.="<td>" . "" . "</td>";
    $json.="</tr>";
   
    //print "Message: \"" . $message->name . "\" sent at: " . $startString . "\n";
    //print "\tSent: " . $delivery->numSends . "\n\tDelivered: " . $delivery->numDeliveries . " (" .
      //number_format((($delivery->numDeliveries / $delivery->numSends) * 100), 0, '.', ',') . "%)\n";
    //print "\tOpens: " . $delivery->numOpens . "\n\tClicks: " . $delivery->numClicks . "\n";
  }
 $json.="<table>";
 print $json;
 
} catch (Exception $e) {
  print "uncaught exception\n";
  print_r($e);
}
