<?php



class Tester{
	

	static private $n = 0;
	private $test;


	function __construct( $t ){
		$this->test = $t;
	}


	public function test(){

		$r = HTTPClient::POST( 
				$this->test->url, 
				json_decode( $this->test->input )
			);

		
		if( strpos( $r["header"], "HTTP/1.1 301 Moved Permanently" ) !==  false) {
			//find the new location and re-test

			$loc = strpos( $r["header"], "Location: " ) + 10;

			$nloc = strpos ( $r["header"], "\n", $loc );

			$this->test->url = substr( $r["header"], $loc, $nloc - $loc - 1);

			//echo "Redirected to: " . $this->test->url . "\n";

			$r = HTTPClient::ForceUrlPOST( 
					$this->test->url, 
					json_decode( $this->test->input )
				);
			
			
		}

		self::$n++;

		echo self::$n . "] " . $this->test->description .  "...";


		if( strpos( $r["header"], "HTTP/1.1 400 BAD REQUEST" ) !==  false) {
			echo "<b style='color:orange;'>[FAILED: 400 BAD REQUEST]</b>\n";
			echo "EXPECTED: " . $this->test->output . "\n"; 
			echo "RESPONSE: " . $r["content"] . "\n\n";
			return;
		}



		if( strpos( $r["header"], "HTTP/1.1 404 Not Found" ) !==  false) {
			echo "<b style='color:red;'>[FAILED: 404 NOT FOUND]</b>\n";
			return;
		}

		$reality = json_decode( $r["content"] );

		if(is_null($reality)) {
			echo "<b style='color:red;'>[FAILED : RESPONSE IS NOT JSON]</b>\n";
			echo "RESPONSE: " . stripslashes($r["content"]) . "\n";
			return;
		}

		$expected = json_decode( $this->test->output );


		foreach ($expected as $ek => $ev) 
		{
			
			if( !property_exists($reality, $ek) )	{
				echo "<b style='color:red;'>[FAILED: MISSING `".$ek."` RETURN PARAMETER]</b>\n";
				return;
			}

		}

		$PASSED = $reality->status === $expected->status;


		if($PASSED) 
			echo "[OK]\n"; 

		else{
			echo "<b style='color:red;'>[FAILED]</b>\n";
			echo "EXPECTED: " . $this->test->output . "\n"; 
			echo "RESPONSE: " . $r["content"] . "\n\n";
		}
			
		
		
		
	}

}