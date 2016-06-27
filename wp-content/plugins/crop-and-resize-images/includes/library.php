<?php
/**
* name: stringProcess
* description: usefull string process class
* 
* @param string $string Input string
*
* @return string
*/	
class stringProcess {
	
	
	function stringProcess(){
		// $a - needle 1
		// $b - needle 2
		// $d - just in case... replace_between
		// $c - haystack
	}

	function starts_with( $a, $c ) {
		return substr( $a, 0, strlen( $c )) == $c;
	}
		
	function after ($a, $c){
		if ( !is_bool( strpos( $c, $a )))
		return substr( $c, strpos( $c,$a ) + strlen( $a ));
	}

	function after_last ($a, $c){
		if (!is_bool($this -> strrevpos($c, $a)))
		return substr($c, $this -> strrevpos($c, $a)+strlen($a));
	}

	function before ($a, $c)
	{
		return substr($c, 0, strpos($c, $a));
	}

	function before_last ($a, $c)
	{
		return substr($c, 0, $this -> strrevpos($c, $a));
	}

	function between ($a, $b, $c)
	{
		// if $a == $b
		if ( $a == $b ){
			
			$c = $this -> after ( $a, $c );
			return $this -> before( $b, $c );
		}
		return $this -> before($b, $this -> after($a, $c));
	}
	
	function remove_between ($a, $b, $c)
	{
		// if $a == $b
		if ( $a == $b ){
				
			return false;
		}
		//return $this -> before($a, $c).$this -> after($b, $c);
		return false;
		
	}
	
	function replace_between ($a, $b, $d, $c)
	{
		// if $a == $b
		if ( $a == $b ){
			
			return $c;
		}
		return $this -> before($a, $c).$this -> after($b, $c);
		
	}

	function between_last ($a, $b, $c)
	{
	 return $this -> after_last($a, $this -> before_last($b, $c));
	}


	function strrevpos($instr, $needle)
	{
		$rev_pos = strpos (strrev($instr), strrev($needle));
		if ($rev_pos===false) return false;
		else return strlen($instr) - $rev_pos - strlen($needle);
	} 

	function multi_between($a, $b, $c){
		$counter = 0;
		
		if ( $a == $b ){
			while ( $c ){
				//echo '<br />c before: '.$c;
				$c = $this -> after ( $a, $c );
				//echo '<br />c after: '.$c;
				$elements[$counter] = $this -> before( $b, $c );
				//echo '<br />el: '.$elements[$counter] ;
				$c = $this -> after( $b, $c );
				//echo '<br />c exit: '.$c;
				$counter++;
			}
		}
		else
		while ( $c ){
			
			$elements[$counter] = $this -> before($b, $c);
			//echo '<br />1: '.$elements[$counter];
			//echo '<br />a: '.$a;
			$elements[$counter] = $this -> after($a, $elements[$counter]);
			//echo '<br />2:'.$elements[$counter];
			$c = $this -> after($b, $c);
			//echo '<br />c: '.$c;
			$counter++;
		}
		if ( $elements[0] === false ) return false;
		
		return $elements;
	}
	
	function add_http( $a ) {
		
		if( !isset( $a )){
			return '';
		}
		
		if( empty( $a )){
			return '';
		}
		
		if ( $this -> starts_with( $a, 'http://' )){
			return $a;
		}
		else{
			return 'http://' . $a;
		}
	}	 
/*
 * after ('@', 'biohazard@online.ge');
 returns 'online.ge'
 from the first occurrence of '@'

 before ('@', 'biohazard@online.ge');
 returns 'biohazard'
 from the first occurrence of '@'

 between ('@', '.', 'biohazard@online.ge');
 returns 'online'
 from the first occurrence of '@'

 after_last ('[', 'sin[90]*cos[180]');
 returns '180]'
 from the last occurrence of '['

 before_last ('[', 'sin[90]*cos[180]');
 returns 'sin[90]*cos['
 from the last occurrence of '['

 between_last ('[', ']', 'sin[90]*cos[180]');
 returns '180'
 from the last occurrence of '[' 
 * */
}//endclass

function is_ajax(){
	
	if( defined( 'DOING_AJAX' )){
		return true;
	}
	else{
		return false;
	}
}

