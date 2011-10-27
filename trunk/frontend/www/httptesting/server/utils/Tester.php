<?php

$TESTER_SCRIPT_BARS = array();

class Tester{
	
		

	static private $n = 0;
	private $test;


	function __construct( $t ){
		$this->test = $t;
	}


	/*
	 * Si en los inputs hay <GET_VAR:VARIABLE> 
	 * la busca en la variable global de 
	 * TESTER_BARS
	 *
	 *
	 **/
	public function parseInputTesterScripting( /* json */ $input_to_send ){
		global $TESTER_SCRIPT_BARS ;	

		foreach ($input_to_send as $key => $value) 
		{
			if( strpos( $value , "<GET_VAR:"  ) !== false )
			{
				//ponerle el valor
				$var_name = substr( $value, 9, -1 ) ;

				//echo "<div style='color:blue'>"
				//echo "Getting " . $var_name . " = `" . $TESTER_SCRIPT_BARS[ $var_name ] ."` </div>";

				$input_to_send->$key = $TESTER_SCRIPT_BARS[ $var_name ];
			}
		}

		return $input_to_send;
	}



	/*
	 * Si en los ouutputs esperados hay <SET_VAR:VARIABLE> 
	 * la busca en la variable global de 
	 * TESTER_BARS y pone su valor segun lo que regreso
	 *
	 *
	 **/
	public function parseOuputTesterScripting(  /* json */ $actual_output ){
		global $TESTER_SCRIPT_BARS ;	

		//buscar en expected output <SET_VAR:VARIABLE>
		$expected = json_decode( $this->test->output );
		foreach ( $expected as $key => $value) 
		{
			//si hay un var set !
			if( strpos( $value , "<SET_VAR:"  ) !== false )
			{
				//var_dump($actual_output);
				//echo "SET_VAR->".$key . "(". $actual_output->$key .")...\n" ;
				
				$var_name = substr( $value, 9, -1 ) ;
				if( !isset($actual_output->$key) ){
					echo "<div style='color:red'>TESTER SCRIPTING: `".$key."` is not defined in response.</div>";
					throw new Exception("TESTER SCRIPTING FATAL ERROR");
					
				}
				echo "<div style='color:blue'>" . $var_name . " = " . $actual_output->$key ." </div>";

				$TESTER_SCRIPT_BARS [ $var_name ] = $actual_output->$key;

				//var_dump($TESTER_SCRIPT_BARS );
			}else{
				//echo " NO !\n" ;
			}
		}

		
		return $actual_output;
	}



	public function test(){

		$r = HTTPClient::POST( 
				$this->test->url, 
				$this->parseInputTesterScripting(json_decode( $this->test->input ))
			);

		
		if( strpos( $r["header"], "HTTP/1.1 301 Moved Permanently" ) !==  false) {
			//find the new location and re-test

			$loc = strpos( $r["header"], "Location: " ) + 10;

			$nloc = strpos ( $r["header"], "\n", $loc );

			$this->test->url = substr( $r["header"], $loc, $nloc - $loc - 1);

			//echo "Redirected to: " . $this->test->url . "\n";

			$r = HTTPClient::ForceUrlPOST( 
					$this->test->url, 
					$this->parseInputTesterScripting(json_decode( $this->test->input  ))
				);
			
			
		}

		self::$n++;

		//echo self::$n . "] " . $this->test->description .  "...";
 

		if( strpos( $r["header"], "HTTP/1.1 400 BAD REQUEST" ) !==  false) {
			echo "<b style='color:orange;'>" . self::$n . "] " . $this->test->description .  "...[FAILED: 400 BAD REQUEST]</b>\n";
			echo "SENT: " .json_encode( $this->parseInputTesterScripting(json_decode( $this->test->input )) )."\n";
			echo "EXPECTED: " . $this->test->output . "\n"; 
			echo "RESPONSE: " . $r["content"] . "\n\n";
			return;
		}



		if( strpos( $r["header"], "HTTP/1.1 404 Not Found" ) !==  false) {
			echo "<b style='color:red;'>" . self::$n . "] " . $this->test->description .  "...[FAILED: 404 NOT FOUND]</b>\n";
			return;
		}

		### BACK FROM SERVER !!! #####
		$reality = $this->parseOuputTesterScripting ( json_decode( $r["content"] ) );
		### BACK FROM SERVER !!! #####



		if(is_null($reality)) {
			echo "<b style='color:red;'>" . self::$n . "] " . $this->test->description .  "...[FAILED : RESPONSE IS NOT JSON]</b>\n";
			echo "RESPONSE: " . stripslashes($r["content"]) . "\n";
			return;
		}

		$expected = json_decode( $this->test->output );

		$PASSED = $reality->status === $expected->status;

		if(!$PASSED){
			echo "<b style='color:red;'>" . self::$n . "] " . $this->test->description .  "...[FAILED]</b>\n";
			echo "SENT: " .json_encode( $this->parseInputTesterScripting(json_decode( $this->test->input )) )."\n";
			echo "EXPECTED: " . $this->test->output . "\n"; 
			echo "RESPONSE: " . $r["content"] . "\n\n";
			return;
		}

		foreach ($expected as $ek => $ev) 
		{
			
			if( !property_exists($reality, $ek) )	{
				echo "<b style='color:red;'>" . self::$n . "] " . $this->test->description .  "...[FAILED: MISSING `".$ek."` RETURN PARAMETER]</b>\n";
				echo "RESPONSE: " . stripslashes($r["content"]) . "\n";
				return;
			}

		}

		
		echo "" . self::$n . "] " . $this->test->description .  "...[OK]\n"; 

	
		
	}

}