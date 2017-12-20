<?php $pathCurrent	= "./"; ?>
<?php include($pathCurrent."bot/bot_include.php"); ?>
<?php
	/* DECLARATION :: Namespaces (SDK-LINE) Section */
		
		use \LINE											as LINE;
		use \LINE\LINEBot\Constant\HTTPHeader				as LINEHTTPHeader;
		use \LINE\LINEBot\Event\MessageEvent\TextMessage	as LINETextMessage;
		use	\LINE\LINEBot\HTTPClient						as LINEHTTPClient;
?>
<?php
	/* CONNECTION :: LINE Section */
	
		$LINECurlHTTPClient	= new LINEHTTPClient\CurlHTTPClient($LINEChannelAccessToken	);
		$LINEHTTPClient		= new LINE\LINEBOT($LINECurlHTTPClient,$LINEChannelSecret);
		$LINESignature		= $_SERVER["HTTP_".LINEHTTPHeader::LINE_SIGNATURE];
	
	/* DATA :: INPUT from LINE-app Section */
	
		$INPUTContent		= file_get_contents('php://input');
		$INPUTEvents		= $LINEHTTPClient->parseEventRequest($INPUTContent,$LINESignature);
	
	/* OPERAtiON :: Send and Receive data to/from LINE and/or Database Section */
	
		foreach($INPUTEvents as $event){
			if($event instanceof LINETextMessage){
				$SRReplyToken	= $event->getReplyToken;
				$SRText			= $event->getText();
				
				$LINEHTTPClient->replyText($SRReplyToken,$SRText);
			}
		}
?>
<?php
	/* AREA :: Testing Section */
	
		//echo "LINESignature : ".$LINESignature.",".LINEHTTPHeader::LINE_SIGNATURE;
?>