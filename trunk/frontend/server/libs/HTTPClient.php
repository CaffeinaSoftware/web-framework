<?php


class HTTPClient{

	static private $url_base;



	static public function setUrlBase( $base ){
		self::$url_base = $base;
	}


	static public function Request( $method, $url, $data, $referer = "" ){
		if($method === "POST") return self::POST ( $url, $data, $referer );
		if($method === "GET") return self::GET ( $url, $data, $referer );
	}

	static public function ForcedUrlRequest( $method, $forced_url, $data, $referer = "" ){
		if($method === "POST") return self::ForceUrlPOST ( $forced_url, $data, $referer );
		if($method === "GET") return self::ForceUrlGET ( $forced_url, $data, $referer );		
	}


	static private function GET( $url, $data, $referer = "" ){
		try{

			$data = get_object_vars($data);

			$parsed_d = "?";

			foreach($data as $p => $k){
				$parsed_d .= $p . "=" . $k . "&";
			}

			return array(
			    'status' 	=> 'ok',
			    'header' 	=> "200",
			    'content' 	=> @file_get_contents( self::$url_base . $url . "/" . $parsed_d ),
				'url'		=> parse_url(self::$url_base . $url . "/" . $parsed_d)
			);	
		}catch(Exception $e){
			throw $e;
		}

	}



	static public function ForceUrlPOST ( $forced_url, $data, $referer = "" ) 
	{
		$url_base = self::$url_base;
		self::$url_base = "";
		$r = self::POST ( $forced_url, $data, $referer );

		self::$url_base = $url_base;
		return $r;

	}

	static public function POST ( $url, $data, $referer = "" )
	{
		// Convert the data array into URL Parameters like a=b&foo=bar etc.
		$data = http_build_query( $data);

		//echo "Target: " . (self::$url_base . $url) . "\n";

		// parse the given URL
		$url = parse_url(self::$url_base . $url);
		
		

		if ($url['scheme'] != 'http') { 
		    die('Error: Only HTTP request are supported !');
		}

		// extract host and path:
		$host = $url['host'];
		$path = $url['path'];

		// open a socket connection on port 80 - timeout: 30 sec
		

		$fp = fsockopen($host, 80, $errno, $errstr, 30);

		if ($fp){

		    // send the request headers:
		    ############### HARCODEADISISISISMO ##########################################
		    ############### HARCODEADISISISISMO ##########################################
		    ############### HARCODEADISISISISMO ##########################################
		    fputs($fp, "POST $path HTTP/1.1\r\n");
		    //fputs($fp, "POST $path?_instance_=123 HTTP/1.1\r\n");
		    ############### HARCODEADISISISISMO ##########################################
		    ############### HARCODEADISISISISMO ##########################################
		    ############### HARCODEADISISISISMO ##########################################
		    

		    fputs($fp, "Host: $host\r\n");

		    if ($referer != '')
		        fputs($fp, "Referer: $referer\r\n");

		    fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
		    fputs($fp, "Content-length: ". strlen($data) ."\r\n");
		    fputs($fp, "Connection: close\r\n\r\n");
		    fputs($fp, $data);

		    $result = ''; 
		    while(!feof($fp)) {
		        // receive the results of the request
		        $result .= fgets($fp, 128);
		    }
		}
		else { 
		    return array(
		        'status' => 'err', 
		        'error' => "$errstr ($errno)",
				'url'	=> $url
		    );
		}

		// close the socket connection:
		fclose($fp);

		// split the result header from the content
		$result = explode("\r\n\r\n", $result, 2);

		$header = isset($result[0]) ? $result[0] : '';
		$content = isset($result[1]) ? $result[1] : '';

		// return as structured array:
		return array(
		    'status' => 'ok',
		    'header' => $header,
		    'content' => $content,
			'url'	=> $url
		);
	}








}
