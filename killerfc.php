<?php
$access_token = "EAAKdnocRZBlIBAKSZCBD332dZBZC01c6D3OVJOkjZCQwOfSlZA34JSpUi3zKkxARvedezT0aEZASRKX1QyYYSyZAcMJZA80YuALb3LI1Yucq8t9wXJehZC6bP8IOZBUx8P7uIHVj0JT0UtlfvoJnVCMbLsCQWEvvjf87JagOffcN1OA69mnmZBTYsjgx";
$verify_token = "killer_fc";
$hub_verify_token = null;
if(isset($_REQUEST['hub_challenge'])) {
 $challenge = $_REQUEST['hub_challenge'];
 $hub_verify_token = $_REQUEST['hub_verify_token'];
}
if ($hub_verify_token === $verify_token) {
 echo $challenge;
}
$input = json_decode(file_get_contents('php://input'), true);
$sender = $input['entry'][0]['messaging'][0]['sender']['id'];
$message = $input['entry'][0]['messaging'][0]['message']['text'];
$message_to_reply = '';
/**
 * Some Basic rules to validate incoming messages
 */

$api_key="<mLAP API KEY>";
$url = 'https://api.mlab.com/api/1/databases/duckduck/collections/linebot?apiKey='.$api_key.'';
$json = file_get_contents('https://api.mlab.com/api/1/databases/duckduck/collections/linebot?apiKey='.$api_key.'&q={"question":"'.$message.'"}');
$data = json_decode($json);
$isData=sizeof($data);
if (strpos($message, 'killerfc') !== false) {
  if (strpos($message, 'killerfc') !== false) {
    $x_tra = str_replace("killerfc","", $message);
    $pieces = explode("|", $x_tra);
    $_question=str_replace("[","",$pieces[0]);
    $_answer=str_replace("]","",$pieces[1]);
    //Post New Data
    $newData = json_encode(
      array(
        'question' => $_question,
        'answer'=> $_answer
      )
    );
    $opts = array(
      'http' => array(
          'method' => "POST",
          'header' => "Content-type: application/json",
          'content' => $newData
       )
    );
    $context = stream_context_create($opts);
    $returnValue = file_get_contents($url,false,$context);
    $message_to_reply = 'ຂອບໃຈທີ່ສອນທີມນັກຂ້າ';
  }
}else{
  if($isData >0){
   foreach($data as $rec){
     $message_to_reply = $rec->answer;
   }
  }else{
    $message_to_reply = 'ເຮ້ຍ! ມຶງສອນກູພິມດຸມີແຕ່ພິມ killerfc[ຄຳຖາມ|ຄຳຕອບ]';
  }
}
//API Url
$url = 'https://graph.facebook.com/v2.6/me/messages?access_token='.$access_token;
//Initiate cURL.
$ch = curl_init($url);
//The JSON data.
$jsonData = '{
    "recipient":{
        "id":"'.$sender.'"
    },
    "message":{
        "text":"'.$message_to_reply.'"
    }
}';
//Encode the array into JSON.
$jsonDataEncoded = $jsonData;
//Tell cURL that we want to send a POST request.
curl_setopt($ch, CURLOPT_POST, 1);
//Attach our encoded JSON string to the POST fields.
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
//Set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
//Execute the request
if(!empty($input['entry'][0]['messaging'][0]['message'])){
    $result = curl_exec($ch);
}
?>
