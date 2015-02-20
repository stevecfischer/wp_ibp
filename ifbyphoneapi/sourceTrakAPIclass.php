<?php


class sourceTrakAPI

{

	function SourceTrak($setID, $_ibp_unique_id, $log_id, $api_key, $referer = null, $baseURI = null)
	{
		//Build api call
		//create array with form POST data

		$strPost = array(
			'api_key' => "$api_key",
			'ibp_api_version' => '2',
			'action' => 'sourcetrak.get_number',
			'set_id' => "$setID",
			'formatting' => 'true',
			'_ibp_unique_id' => "$_ibp_unique_id",
			'log_id' => "$log_id",
			'referrer' => "$referer",
			'baseURI' => "$baseURI"
		);


		//Ifbyphone API url

		$url = "https://sourcetrak.ifbyphone.com/ibp_api.php";


		//intialize cURL and send POST data

		$session = curl_init();

		curl_setopt($session, CURLOPT_POST, true);
		curl_setopt($session, CURLOPT_POSTFIELDS, $strPost);
		curl_setopt($session, CURLOPT_URL, $url);
		curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
        curl_setopt( $session, CURLOPT_SSL_VERIFYHOST, 0 );
        curl_setopt( $session, CURLOPT_SSL_VERIFYPEER, 0 );
        curl_setopt( $session, CURLOPT_FAILONERROR, true );

		$text = curl_exec($session);
		if( !$text ){
            $return .= "<br />cURL error number:".curl_errno( $session );
            $return .= "<br />cURL error:".curl_error( $session );
            return $return;
        }


		$xml = new SimpleXMLElement($text);


		$type = $xml->data[0]->sourceTrak->type;

		$log_id = $xml->data[0]->sourceTrak->log_id;

		$number = $xml->data[0]->sourceTrak->number;


		$sourceTrakarray  = array(
			'type' => $type,
			'log_id' => $log_id,
			'number' => $number
		);


		return $sourceTrakarray;


		curl_close($session);
	}
}
?>