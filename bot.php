<?php
	/* DATA :: INPUT from LINE-app */
	
		$INPUTContent	= file_get_contents('php://input');
		$INPUTJSON 		= json_decode($INPUTContent,true);
		$INPUTMsg		= $INPUTJSON['events'][0]['message']['text'];
	
	/* DATA :: LINE Section */
	
		$LINEMsgID				= "";
		$LINEChannelAccessToken	= "vnBkVAGyRkYmSaOzUk/HUIpLWBAq1yHL6/ONQrJtWWxgBSnd7N5wypBp1xvL+QLUuiY3R+7EGn0xfE6Saow6c+pH6xUVy56ee1BXJqaqiKV8fhTOLyqrOkehWJB3E3wo2ERLXOGRhHEZT/0JtX18uwdB04t89/1O/w1cDnyilFU=";
		$LINEURLMain			= "https://api.line.me/v2/bot/message";
		$LINEURLByMsgType		= array(
									"POSTReply"		=> $LINEURLMain."/reply",
									"POSTPush" 		=> $LINEURLMain."/push",
									"POSTMulticast" => $LINEURLMain."/multicast",
									"GETContent"	=> $LINEURLMain."/".$LINEMsgID."/content"
								  );

	/* DATA and OPERATION :: Database Section */
	
		$DBAPIKey		= "gEd3E2gniu_jJsug_KdfopukhrMghFyC";
		$DBQueryString	= '&q={"request":"'.$INPUTMsg.'"}';
		$DBName			= "duckduck";
		$DBUsername		= "linebot2";
		$DBURLMain		= "https://api.mlab.com/api/1/databases/".$DBName."/collections/".$DBUsername."?apiKey=".$DBAPIKey;
		$DBURLQuery		= file_get_contents($DBURLMain.$DBQueryString);
		
		$DBJSON			= json_decode($DBURLQuery);
		$DBDataSize		= sizeof($DBJSON);
	
	/* DATA and OPERATION :: Send value(s) to Database and/or reply back to LINE-app Section */
	
		$SVCmdType	= array(
						"สอน"
					  );
		$SVCmdSign	= array(
						"[",
						"]"
					  );
		
		$SVMethod	= array("POST","GET");
		$SVHeader	= array(
						"Content-Type: application/json",
						"Authorization: Bearer {".$LINEChannelAccessToken."}"
					  );
					  
		if(strpos($INPUTMsg,$SVCmdType) != false){
			if(strpos($INPUTMsg,$SVCmdType[0]) != false){
				$SVValueExtraction	= explode("|",str_replace($SVCmdSign,"",str_replace($SVCmdType[0],"",$INPUTMsg)));
				$SVRequest			= $SVValueExtraction[0];
				$SVReply			= $SVValueExtraction[1];
				
				$SVJSON				= json_encode(
										array(
											"request"	=> $SVRequest,
											"reply"		=> $SVReply
										)
									  );
				$SVOption			= array(
										"http"	=> array(
													"method"	=> $SVMethod[0],
													"header"	=> $SVHeader[0],
													"content"	=> $SVJSON
												   )
									  );
				
				$SVContext 			= stream_context_create($SVOption);
				file_get_contents($DBURLMain,false,$SVContent);
				
				$SVPOSTValue['replyToken']			= $INPUTJSON['events'][0]['replyToken'];
				$SVPOSTValue['messages'][0]['type']	= "text";
				$SVPOSTValue['messages'][0]['text']	= "ขอบคุณสำหรับการสอนคร๊าบบบ";
				
				$LINEURLFinal	= $LINEURLByMsgType['POSTReply'];
			}
		}else{
			if(!empty($DBDataSize)){
				foreach($DBJSON as $SVReply){
					$SVPOSTValue['replyToken']			= $INPUTJSON['events'][0]['replyToken'];
					$SVPOSTValue['messages'][0]['type']	= "text";
					$SVPOSTValue['messages'][0]['text']	= $SVReply->reply;
				}
			}else{
				$SVPOSTValue['replyToken']			= $INPUTJSON['events'][0]['replyToken'];
				$SVPOSTValue['messages'][0]['type']	= "text";
				$SVPOSTValue['messages'][0]['text']	= "แง แง แง, พูดอะไรก็ไม่รู้ สอนผมหน่อย สอนผมหน่อย ผมจะได้รู้เรื่อง, นะ นะ นะคร๊าบบบ. สอนผมแบบนี้นะ : สอน[คำสอน|คำตอบ]";
			}
		}
		
		$SVCURL			= curl_init();
			curl_setopt($SVCURL,CURLOPT_URL,$LINEURLFinal);
			curl_setopt($SVCURL,CURLOPT_HEADER,false);
			curl_setopt($SVCURL,CURLOPT_POST,true);
			curl_setopt($SVCURL,CURLOPT_HTTPHEADER,$SVHeader);
			curl_setopt($SVCURL,CURLOPT_POSTFIELDS,json_encode($SVPOSTValue));
			curl_setopt($SVCURL,CURLOPT_RETURNTRANSFER,true);
			curl_setopt($SVCURL,CURLOPT_SSL_VERIFYPEER,false);
		$SVCURLResult	= curl_exec($SVCURL);
		curl_close($SVCURL);
		
		//echo "RESULT : ".$SVCURLResult;
		//echo "<br>";
		echo "INPUTContent : ";
		print_r($INPUTContent);
		echo "<br>";
		echo "SVPOSTValue : ";
		print_r($SVPOSTValue);
?>