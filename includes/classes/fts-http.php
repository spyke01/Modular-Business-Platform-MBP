<?php
/***************************************************************************
 *                               fts-http.php
 *                            -------------------
 *   begin                : Thursday, August 8, 2013
 *   copyright            : ( C ) 2013 Paden Clayton
 *
 *
 ***************************************************************************/


 
class fts_http {
	/**
	* Request using PHP CURL functions
	* Requires curl library installed and configured for PHP
	* Returns response from the path
	*
	* @param string $path			Path for the request
	* @param string $method			Specifies POST or GET method
	* @param array $request_vars		Data for making the request to API
	*
	*/		
	public function request( $path, $method = 'GET', $request_vars = array() ) {
		$qs = '';
		
		foreach($request_vars AS $key => $value)
			$qs .= '&' . $key . '=' . urlencode($value);
		
		//construct full api url
		if(strtoupper($method) == 'GET')
			$path .= $qs;
		
		//initialize a new curl object            
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $path);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		//echo $url . '?' . $qs;
		
		switch(strtoupper($method)) {
			case "GET":
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
				break;
			case "POST":
				curl_setopt($ch, CURLOPT_POST, TRUE);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $qs);
				break;
		}
		
		if(FALSE === ($result = curl_exec($ch)))
			return "Curl failed with error " . curl_error($ch); 
		
		curl_close($ch);	
		
		return $result;
	}
}