<?php



class Tester{
	

	  /*


 		*
		*/
	private $test;

	function __construct( $t ){
		$this->test = $t;
	}



	public function test(){

		//$r = HTTPClient::POST( "/personal/rol/nuevo/", array( "asdf" => "asf" ) );

		$r = HTTPClient::POST( 
				$this->test->url, 
				json_decode( $this->test->input )
			);

		//var_dump($r);
		//echo $r["header"];
		echo $r["content"];
	}

}