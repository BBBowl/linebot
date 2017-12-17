<?php
 
$strAccessToken = 'Ko4MCTrbiN2xbBBgAA/HQER2KmlURT84NLsTgu9i0zed077f6jAj8a/w0dCRaAHGuiY3R+7EGn0xfE6Saow6c+pH6xUVy56ee1BXJqaqiKXzDLB52pgXgjsx/GatuXQQtchq2fYbUhZyPymVvbVX9QdB04t89/1O/w1cDnyilFU=';
 
$content = file_get_contents('php://input');
$arrJson = json_decode($content, true);
 
$strUrl = "https://api.line.me/v2/bot/message/reply";
 
$arrHeader = array();
$arrHeader[] = "Content-Type: application/json";
$arrHeader[] = "Authorization: Bearer {$strAccessToken}";
$_msg = $arrJson['events'][0]['message']['text'];
 
 
$api_key="3rDixgr4hhgtGJCF2yfp6vag35Q10820";
$url = 'https://api.mlab.com/api/1/databases/line-bbbot/collections/linebot?apiKey='.$api_key.'';
$json = file_get_contents('https://api.mlab.com/api/1/databases/duckduck/collections/linebot?apiKey='.$api_key.'&q={"question":"'.$_msg.'"}');
$data = json_decode($json);
$isData=sizeof($data);
 
if (strpos($_msg, 'สอนเป็ด') !== false) {
  if (strpos($_msg, 'สอนเป็ด') !== false) {
    $x_tra = str_replace("สอนเป็ด","", $_msg);
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
    $arrPostData = array();
    $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
    $arrPostData['messages'][0]['type'] = "text";
    $arrPostData['messages'][0]['text'] = 'ขอบคุณที่สอนเป็ด';
  }
}else{
  if($isData >0){
   foreach($data as $rec){
    $arrPostData = array();
    $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
    $arrPostData['messages'][0]['type'] = "text";
    $arrPostData['messages'][0]['text'] = $rec->answer;
   }
  }else{
    $arrPostData = array();
    $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
    $arrPostData['messages'][0]['type'] = "text";
    $arrPostData['messages'][0]['text'] = 'ก๊าบบ คุณสามารถสอนให้ฉลาดได้เพียงพิมพ์: สอนเป็ด[คำถาม|คำตอบ]';
  }
}
 
 
$channel = curl_init();
curl_setopt($channel, CURLOPT_URL,$strUrl);
curl_setopt($channel, CURLOPT_HEADER, false);
curl_setopt($channel, CURLOPT_POST, true);
curl_setopt($channel, CURLOPT_HTTPHEADER, $arrHeader);
curl_setopt($channel, CURLOPT_POSTFIELDS, json_encode($arrPostData));
curl_setopt($channel, CURLOPT_RETURNTRANSFER,true);
curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($channel);
curl_close ($channel);
?>