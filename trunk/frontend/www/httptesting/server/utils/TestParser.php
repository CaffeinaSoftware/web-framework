<?php


class TestParser{
	

	private $raw_tests ;
	private $tests = array();


	function __construct($tests){
		$this->raw_tests = $tests;
	}

	public function parse(){
		$lines = explode("\n", $this->raw_tests);

		for ($l=0; $l < sizeof($lines); $l++) { 

			$currLine = trim( $lines[ $l ] );

			if(strlen($currLine) == 0) continue;


			if($currLine == "#beginTest"){

				//test case begins
				$t = new Test();
				$t->description = trim( $lines[ $l + 1 ] );
				$t->url 		= trim( $lines[ $l + 2 ] );
				$t->input 		= trim( $lines[ $l + 3 ] );
				$t->output 		= trim( $lines[ $l + 4 ] );

				$t->description = substr ( $t->description, strpos( $t->description, " " ) + 1);
				$t->url = substr ( $t->url, strpos( $t->url, " " ) + 1);
				$t->input = substr ( $t->input, strpos( $t->input, " " ) + 1);
				$t->output = substr ( $t->output, strpos( $t->output, " " ) + 1);

				#vamos a ver si hay parseo de variables
				//$in 	= json_decode($t->input  );
				//$out 	= json_decode($t->output );


				array_push($this->tests , $t);

				$l += 5;

			}
		}

		$na = array();
		$s = sizeof($this->tests);

		for ($i=0; $i < $s ; $i++)
		{ 
			$na[ $i ] = $this->tests[ $s - $i - 1];
		}

		$this->tests = $na;
		unset($na);

	}



	public function hasNextTest(){
		return sizeof( $this->tests ) > 0;
	}


	public function nextTest(){
		return array_pop( $this->tests );
	}

}



class Test{
	
	public $description;
	public $url;
	public $input;
	public $output;
	public $method;

	function __construct($m = "POST"){
		$this->method 	 = $m;
	}
}