<?php
/*
 * lol_core.php Ver 0.3
 * Copyright (c) 2007 Jeff Jones, www.tetraboy.com, 2010 Happy-melon
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
function lol_core_parse( $code ) {
	return lol_core_replace( $code, 'forwards' );
}
function lol_core_unparse( $code ) {
	return lol_core_replace( $code, 'backwards' );
}

define( 'LOL_ANY', '(.+?)' );
define( 'LOL_LABEL', '([a-zA-Z0-9_-]+)' );
define( 'LOL_VAR', '\$([a-zA-Z0-9_-]+)' );
define( 'LOL_FNAME', '([a-zA-Z0-9_\-\>]+)' );
define( 'LOL_MNAME', '\$this\-\>([a-zA-Z0-9_\-\>]+)' );
define( 'LOL_VARLIST', '((?:'.LOL_VAR.'(?:,\s*)?)+)' );
define( 'LOL_CMT', '((\/\/|#) (.*))?');
function lol_core_replace($code, $dir ) {
	$backwards = array(
		'/^([\s]*)'.LOL_VAR.'( = null)?;/', '$1 I HAS $2',
		'/^([\s]*)var '.LOL_VARLIST.';/', '$1 I HAS UR $2',
		'/^([\s]*)global '.LOL_VARLIST.';/', '$1 I HAS UR $2 ON UR INTERNETS',
		'/^([\s]*)'.LOL_VAR.' += array\(\);/', '$1 I HAS $2 IZ EMPTY',
		'/^([\s]*)'.LOL_VAR.' += array\(/', '$1 I HAS $2 IZ BUCKET',
		'/^([\s]*)'.LOL_VAR.' += (.+);/', '$1 I HAS $2 IZ $3',
		'/^([\s]*)'.LOL_VAR.'++;/', '$1 $2 UPUP!',
		'/^([\s]*)'.LOL_VAR.' = '.LOL_FNAME.'\( (.+) \);/', '$1 $2 IZ ON UR $3 DOING $4',
		'/^([\s]*)'.LOL_MNAME.'\(\);/', '$1 IM ON UR SPECIAL $2',
		'/^([\s]*)'.LOL_MNAME.'\( (.+) \);/', '$1 IM ON UR SPECIAL $2 DOING $3',
		'/^([\s]*)'.LOL_FNAME.'\(\);/', '$1 IM ON UR $2',
		'/^([\s]*)'.LOL_FNAME.'\( (.+) \);/', '$1 IM ON UR $2 DOING $3',
	
		'/^([\s]*)class '.LOL_FNAME.' {/', '$1 IM IN UR SPECIAL $2',
		'/^([\s]*)const '.LOL_FNAME.' = (.+)/', '$1 I ALWAYZ HAS $2 IZ $3',
		'/^([\s]*)foreach \( '.LOL_VAR.' as \$(.+) \) {/', '$1 IM IN UR $2 ITZA $3',
		'/^([\s]*)while \( (.+) \) {/', '$1 STEALIN UR $2',
	
		'/^([\s]*)return (.*);/', '$1 I FOUND MAH $2',
	
		'/^([\s]*)'.LOL_VAR.' = (.*)/', '$1 $2 IZ $3',
		'/^([\s]*)'.LOL_VAR.' \.= (.*)/', '$1 $2 HAS MOAR $3',
		'/^([\s]*)'.LOL_MNAME.' = array\(\)/', '$1 UR SPECIAL $2 IZ EMPTY',
		'/^([\s]*)'.LOL_MNAME.' = array\(/', '$1 UR SPECIAL $2 IZ BUCKET',
		'/^([\s]*)'.LOL_MNAME.' = (.*)/', '$1 UR SPECIAL $2 IZ $3',
	
		'/^([\s]*)(?:private |protected |public )?function '.LOL_FNAME.' ?\(\) \{ (.+) \}/', '$1 SO IM LIKE $2 TESTING UR $3',
		'/^([\s]*)(?:private |protected |public )?function '.LOL_FNAME.' ?\( (.*) \) \{ (.+) \}/', '$1 SO IM LIKE $2 WITH UR $3 TESTING UR $4',
		'/^([\s]*)(?:private |protected |public )?function '.LOL_FNAME.' ?\( (.*) \) \{/', '$1 SO IM LIKE $2 WITH UR $3',
		'/^([\s]*)(?:private |protected |public )?function '.LOL_FNAME.' ?\(\) \{/', '$1 SO IM LIKE $2',
		'/^([\s]*)(?:private |protected |public )?static function '.LOL_FNAME.' ?\(\) \{/', '$1 SO IM ALWAYS LIKE $2',
		'/^([\s]*)(?:private |protected |public )?static function '.LOL_FNAME.' ?\( (.*) \) \{/', '$1 SO IM ALWAYS LIKE $2 WITH UR $3',
	
		'/^([\s]*)if \( (.+) \) \{/', '$1 IZ $2',
		'/^([\s]*)\} elseif \( (.+) \) \{/', '$1 ORLY $2',
		'/^([\s]*)\} else \{/', '$1 NOWAI',
	
		'/^([\s]*)(\/\/|\#) (.*)/', '$1 BTW $3',
		'/^([\s]*)\/\*\*?/', '$1 DO NOT WANT',
		'/^([\s]*)\*\//', '$1 WANT',
		'/^([\s]*)\* ?(.*)/', '$1 NOT WANT $2',
	
		'/^([\s]*)\);/', '$1 BUCKET',
		'/^([\s]*)\}/', '$1 KTHX',
	);
	
	$forwards = array(
		'/^([\s]*) I HAS UR '.LOL_VARLIST.' ON UR INTERNETS[\s]+/', '$1 global $2;',
		'/^([\s]*) I HAS UR '.LOL_VARLIST.'[\s]+/', '$1 var $2;',
		'/^([\s]*) I HAS '.LOL_LABEL.' IZ BUCKET[\s]+/', '$1\$$2 = array(',
		'/^([\s]*) I HAS '.LOL_LABEL.' IZ EMPTY[\s]+/', '$1 \$$2 = array();',
		'/^([\s]*) I HAS '.LOL_LABEL.' IZ '.LOL_ANY.''.LOL_CMT.'[\s]+$/', '$1 \$$2 = $3; $4',
		'/^([\s]*) I HAS '.LOL_LABEL.'[\s]+/', '$1 \$$2;',
		'/^([\s]*) '.LOL_LABEL.' IZ ON UR '.LOL_FNAME.' DOING '.LOL_ANY.''.LOL_CMT.'[\s]+$/', '$1 \$$2 = $3( $4 ); $5',
		'/^([\s]*) IM ON UR SPECIAL '.LOL_FNAME.' DOING '.LOL_ANY.''.LOL_CMT.'[\s]*$/', '$1 \$this->$2( $3 ); $4',
		'/^([\s]*) IM ON UR SPECIAL '.LOL_FNAME.'[\s]*/', '$1 \$this->$2();',
		'/^([\s]*) IM ON UR '.LOL_FNAME.' DOING(.+)'.LOL_CMT.'[\s]+/', '$1 $2( $3 ); $4',
		'/^([\s]*) IM ON UR '.LOL_FNAME.'[\s]+/', '$1 $2();',
		'/^([\s]*) '.LOL_LABEL.' UPUP[\s]+/', '$1 $2++',
	
		'/^([\s]*) IM IN UR SPECIAL '.LOL_FNAME.'[\s]+/', '$1 class $2 {',
		'/^([\s]*) I ALWAYZ HAS '.LOL_FNAME.' IZ (.+)[\s]+/', '$1 const $2 = $3',
		'/^([\s]*) IM IN UR '.LOL_LABEL.' ITZA (.+)[\s]+/', '$1 foreach ( \$$2 as \$$3 ) {',
		'/^([\s]*) STEALIN UR (.+)[\s]+/', '$1 while( $2 ) {',
	
		'/^([\s]*) I FOUND MAH (.*)[\s]+/', '$1 return $2;',
	
		'/^([\s]*) UR SPECIAL '.LOL_FNAME.' IZ EMPTY[\s]*/', '$1 \$this->$2 = array()',
		'/^([\s]*) UR SPECIAL '.LOL_FNAME.' IZ BUCKET[\s]+/', '$1 \$this->$2 = array(',
		'/^([\s]*) UR SPECIAL '.LOL_FNAME.' IZ '.LOL_ANY.''.LOL_CMT.'[\s]*$/', '$1 \$this->$2 = $3 $4',
		'/^([\s]*)'.LOL_LABEL.' IZ (.*)[\s]+/', '$1 \$$2 = $3',
		'/^([\s]*)'.LOL_LABEL.' HAS MOAR (.*)[\s]+/', '$1 \$$2 .= $3',
	
		'/^([\s]*) SO IM LIKE '.LOL_FNAME.' TESTING UR (.+)[\s]+/', '$1 function $2(){ $3 }',
		'/^([\s]*) SO IM LIKE '.LOL_FNAME.' WITH UR (.*) TESTING UR (.+)[\s]+/', '$1 function $2( $3 ) { $4 }',
		'/^([\s]*) SO IM LIKE '.LOL_FNAME.' WITH UR (.*)[\s]+/', '$1 function $2( $3 ) {',
		'/^([\s]*) SO IM LIKE '.LOL_FNAME.'[\s]+/', '$1 function $2(){',
		'/^([\s]*) SO IM ALWAYS LIKE '.LOL_FNAME.' WITH UR (.*)[\s]+/', '$1 static function $2( $3 ) {',
		'/^([\s]*) SO IM ALWAYS LIKE '.LOL_FNAME.'[\s]+/', '$1 static function $2(){',
	
		'/^([\s]*) IZ '.LOL_ANY.''.LOL_CMT.'[\s]+$/', '$1 if( $2 ) { $3',
		'/^([\s]*) ORLY '.LOL_ANY.''.LOL_CMT.'[\s]+$/', '$1 } elseif( $2 ) { $3',
		'/^([\s]*) NOWAI[\s]+/', '$1 } else {',
	
		'/^([\s]*) BTW (.*)[\s]*/', '$1 # $2',
		'/^([\s]*) DO NOT WANT[\s]*/', '$1 /**',
		'/^([\s]*) WANT[\s]*/', '$1 */',
		'/^([\s]*) NOT WANT (.*)[\s]*/', '$1 * $2',
	
		'/^([\s]*) BUCKET[\s]*/', '$1 );',
		'/^([\s]*) KTHX[\s]*/', '$1 }',
	);
	
	$code = is_array( $code )
		? $code
		: explode("\n",$code);

	$search = array();
	$replace = array();
	foreach( $$dir as $key => $var ){
		if( 1 & $key )	{
			$replace[] = $var;
		} else {
			$search[] = $var;
		}
	}
	
	$code = preg_replace( $search, $replace, $code );
	$code = implode( "\n", $code );
	return $code;
}



## Parser implementation ##
if( false ){ # set to false to disable
	$lol = file( dirname(__FILE__).'/Parser.lol', FILE_IGNORE_NEW_LINES );
	$php = lol_core_parse( $lol );
	try { 
		eval( $php );
	} catch ( Exception $e ){
		require_once( 'Parser.php' );
	}
} else {
	# You're no fun... 
	require_once( 'Parser.php' );
}




