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
		

		if(!is_array($input_to_send)){
			//return $input_to_send;
		} 
		
		

		foreach ($input_to_send as $key => $value) 
		{
			if(is_array($value)) continue;
			
			if( strpos( $value , "<GET_VAR:"  ) !== false )
			{
				//ponerle el valor
				$var_name = substr( $value, 9, -1 ) ;
				
				if(array_key_exists ( $var_name, $TESTER_SCRIPT_BARS )){
					$input_to_send->$key = $TESTER_SCRIPT_BARS[ $var_name ];					
				}else{
					echo "<div style='color:blue'>";
					echo "VAR `" . $var_name . "` has not been set</div><br>";
				}

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
	public function parseOuputTesterScripting(  /* json */ $actual_output , $r){
		global $TESTER_SCRIPT_BARS ;	

		//buscar en expected output <SET_VAR:VARIABLE>
		$expected = json_decode( $this->test->output );
		
		//if($expected == null) var_dump( $this->test->output ); $actual_output;
		
		foreach ( $expected as $key => $value) 
		{
			//si hay un var set !
			if( strpos( $value , "<SET_VAR:"  ) !== false )
			{
				//var_dump($actual_output);
				//echo "SET_VAR->".$key . "(". $actual_output->$key .")...<br>" ;
				
				$var_name = substr( $value, 9, -1 ) ;
				if( !isset($actual_output->$key) ){
					
					echo "<b style='cursor:pointer;color:red;' onClick='$(\"#end01293\").slideToggle()'>" . self::$n . "] " . $this->test->description .  "...[FAILED]</b><br>";
					$this->printTestInfo( $r, "end01293");

					echo "<div style='color:blue'>TESTER SCRIPTING FATAL ERROR: `".$key."` is not defined in response.</div><br>";
					die;
					
				}
				echo "<div style='color:blue'>" . $var_name . " = " . $actual_output->$key ." </div><br>";

				$TESTER_SCRIPT_BARS [ $var_name ] = $actual_output->$key;

				//var_dump($TESTER_SCRIPT_BARS );
			}else{
				//echo " NO !<br>" ;
			}
		}

		
		return $actual_output;
	}




	private function printTestInfo($r, $html_id){
		?>
			<div style="display:none;" id="<?php echo $html_id; ?>">
				<table border="0" style="width:100%">
					<tr>
						<tr>
							<td>
								<b>Linea</b>
							</td>
							<td><?php echo $this->test->line; ?>
							</td>
						</tr>						
						<td><b>Url</b></td>
						<td><?php 
						echo $r["url"]["scheme"] . "://";
						echo $r["url"]["host"] ;
						echo $r["url"]["path"];
						?></td>
					</tr>
					<tr>
						<td><b>Metodo</b></td>
						<td>
						<?php
							echo $this->test->method;
						?>
						</td>
					</tr>
					<tr>
						<td>
							<b>Enviado</b>
						</td>
						<td><code><?php echo trim($this->test->input);?></code>
						</td>
					</tr>
					<tr>
						<td>
							<b>Esperado</b>
						</td>
						<td><code><?php echo trim($this->test->output);?></code>
						</td>
					</tr>
					<tr>
						<td>
							<b>Repuesta</b>
						</td>
						<td><code><?php echo $r["content"]; ?></code>
						</td>
					</tr>
										
				</table>
			</div>
			<br>
		<?php
	}
	
	
	
	
	

	public function test(){

		$html_id = rand();

		/* *********************************
		 *
	     * ********************************* */
	
		$jsn_t_send = json_decode( $this->test->input );
	
		if($jsn_t_send == NULL){
			echo "<b style='color:red;' >" . self::$n . "] " . $this->test->description .  "...[FAILED: INPUT IS NOT JSON]<br>";
			echo "HERE IS THE INPUT I TRIED TO SEND: </b><br><code>" . $this->test->input  ."</code>";
			die;
		}
	
		$r = HTTPClient::Request(
				$this->test->method,
				$this->test->url, 
				$this->parseInputTesterScripting( $jsn_t_send )
			);


		self::$n++;
		

		/* *********************************
		 *	301 Moved Permanently
	     * ********************************* */		
		if( strpos( $r["header"], "HTTP/1.1 301 Moved Permanently" ) !==  false) {
			//find the new location and re-test

			$loc = strpos( $r["header"], "Location: " ) + 10;

			$nloc = strpos ( $r["header"], "<br>", $loc );

			$this->test->url = substr( $r["header"], $loc, $nloc - $loc - 1);

			//echo "Redirected to: " . $this->test->url . "<br>";

			$r = HTTPClient::ForcedUrlRequest( 
					$this->test->method,
					$this->test->url, 
					$this->parseInputTesterScripting(json_decode( $this->test->input  ))
				);
			
			
		}


		/* *********************************
		 *	400 BAD REQUEST
	     * ********************************* */
		if( strpos( $r["header"], "HTTP/1.1 400 BAD REQUEST" ) !==  false) {
			echo "<b style='color:orange;' onClick='$(\"#".$html_id."\").slideToggle()'>" . self::$n . "] " . $this->test->description .  "...[FAILED: 400 BAD REQUEST]</b><br>";
			$this->printTestInfo($r, $html_id);
			return;
		}


		/* *********************************
		 *	404 Not Found
	     * ********************************* */
		if( strpos( $r["header"], "HTTP/1.1 404 Not Found" ) !==  false) {
			echo "<b style='cursor:pointer;color:red;' onClick='$(\"#".$html_id."\").slideToggle()'>" . self::$n . "] " . $this->test->description .  "...[FAILED: 404 NOT FOUND]</b><br>";
			$this->printTestInfo($r, $html_id);
			return;
		}

		


		/* *********************************
		 *	Parsear la salida, y  ponerle
	     * ********************************* */
		try{
			$reality = $this->parseOuputTesterScripting ( json_decode( $r["content"] ), $r );
					
		}catch(Exception $e){
			$this->printTestInfo($r, $html_id);
			return;
			
		}



		/* *********************************
		 *	Si la respuesta del parseo es 
		 *  null, algo anda muy mal
	     * ********************************* */
		if(is_null($reality)) {
			echo "<b style='cursor:pointer;color:red;' onClick='$(\"#".$html_id."\").slideToggle()'>" . self::$n . "] " . $this->test->description .  "...[FAILED : RESPONSE IS NOT JSON]</b><br>";
			$this->printTestInfo($r, $html_id);
			return;
		}




		/* *********************************
		 *	Ver si paso viendo el status
	     * ********************************* */
		$expected = json_decode( $this->test->output );
		$PASSED = $reality->status === $expected->status;	
		if(!$PASSED){
			echo "<b style='cursor:pointer;color:red;' onClick='$(\"#".$html_id."\").slideToggle()'>" . self::$n . "] " . $this->test->description .  "...[FAILED]</b><br>";
			$this->printTestInfo($r, $html_id);
			return;
		}


		/* *********************************
		 *	Ver si paso viendo los demas parametros
	     * ********************************* */
		foreach ($expected as $ek => $ev) 
		{
			
			if( !property_exists($reality, $ek) )	{
				echo "<b style='cursor:pointer;color:red;' onClick='$(\"#".$html_id."\").slideToggle()'>" . self::$n . "] " . $this->test->description .  "...[FAILED: MISSING `".$ek."` RETURN PARAMETER]</b><br>";
				$this->printTestInfo($r, $html_id);				
				return;
			}

		}

		
		echo "<b  style='cursor:pointer; color:green;' onClick='$(\"#".$html_id."\").slideToggle()'>" . self::$n . "] " . $this->test->description .  "...[OK]</b><br>"; 
		$this->printTestInfo($r, $html_id);
		
	}

}