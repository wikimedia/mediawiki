<?php

/**
 * Various core parser functions, registered in Parser::firstCallInit()
 */

class CoreParserFunctions {

	static function ns( $parser, $part1 = '' ) {
		global $wgContLang;
		$found = false;
		if ( intval( $part1 ) || $part1 == "0" ) {
			$text = $wgContLang->getNsText( intval( $part1 ) );
			$found = true;
		} else {
			$param = str_replace( ' ', '_', strtolower( $part1 ) );
			$index = Namespace::getCanonicalIndex( strtolower( $param ) );
			if ( !is_null( $index ) ) {
				$text = $wgContLang->getNsText( $index );
				$found = true;
			}
		}
		if ( $found ) {
			return $text;
		} else {
			return array( 'found' => false );
		}
	}

	static function urlencode( $parser, $s = '' ) {
		return urlencode( $s );
	}

	static function lcfirst( $parser, $s = '' ) {
		global $wgContLang;
		return $wgContLang->lcfirst( $s );
	}

	static function ucfirst( $parser, $s = '' ) {
		global $wgContLang;
		return $wgContLang->ucfirst( $s );
	}

	static function lc( $parser, $s = '' ) {
		global $wgContLang;
		return $wgContLang->lc( $s );
	}

	static function uc( $parser, $s = '' ) {
		global $wgContLang;
		return $wgContLang->uc( $s );
	}

	static function localurl( $parser, $s = '', $arg = null ) { return self::urlFunction( 'getLocalURL', $s, $arg ); }
	static function localurle( $parser, $s = '', $arg = null ) { return self::urlFunction( 'escapeLocalURL', $s, $arg ); }
	static function fullurl( $parser, $s = '', $arg = null ) { return self::urlFunction( 'getFullURL', $s, $arg ); }
	static function fullurle( $parser, $s = '', $arg = null ) { return self::urlFunction( 'escapeFullURL', $s, $arg ); }

	static function urlFunction( $func, $s = '', $arg = null ) {
		$found = false;
		$title = Title::newFromText( $s );
		# Due to order of execution of a lot of bits, the values might be encoded
		# before arriving here; if that's true, then the title can't be created
		# and the variable will fail. If we can't get a decent title from the first
		# attempt, url-decode and try for a second.
		if( is_null( $title ) )
			$title = Title::newFromUrl( urldecode( $s ) );
		if ( !is_null( $title ) ) {
			if ( !is_null( $arg ) ) {
				$text = $title->$func( $arg );
			} else {
				$text = $title->$func();
			}
			$found = true;
		}
		if ( $found ) {
			return $text;
		} else {
			return array( 'found' => false );
		}
	}

	function formatNum( $parser, $num = '' ) {
		return $parser->getFunctionLang()->formatNum( $num );
	}
	
	function grammar( $parser, $case = '', $word = '' ) {
		return $parser->getFunctionLang()->convertGrammar( $word, $case );
	}

	function plural( $parser, $text = '', $arg0 = null, $arg1 = null, $arg2 = null, $arg3 = null, $arg4 = null ) {
		return $parser->getFunctionLang()->convertPlural( $text, $arg0, $arg1, $arg2, $arg3, $arg4 );
	}

	function displaytitle( $parser, $param = '' ) {
		$parserOptions = new ParserOptions;
		$local_parser = clone $parser;
		$t2 = $local_parser->parse ( $param, $parser->mTitle, $parserOptions, false );
		$parser->mOutput->mHTMLtitle = $t2->GetText();

		# Add subtitle
		$t = $parser->mTitle->getPrefixedText();
		$parser->mOutput->mSubtitle .= wfMsg('displaytitle', $t);
		return '';
	}

	function isRaw( $param ) {
		static $mwRaw;
		if ( !$mwRaw ) {
			$mwRaw =& MagicWord::get( 'rawsuffix' );
		}
		if ( is_null( $param ) ) {
			return false;
		} else {
			return $mwRaw->match( $param );
		}
	}

	function statisticsFunction( $func, $raw = null ) {
		if ( self::isRaw( $raw ) ) {
			return call_user_func( $func );
		} else {
			global $wgContLang;
			return $wgContLang->formatNum( call_user_func( $func ) );
		}
	}

	function numberofpages( $parser, $raw = null ) { return self::statisticsFunction( 'wfNumberOfPages', $raw ); }
	function numberofusers( $parser, $raw = null ) { return self::statisticsFunction( 'wfNumberOfUsers', $raw ); }
	function numberofarticles( $parser, $raw = null ) { return self::statisticsFunction( 'wfNumberOfArticles', $raw ); }
	function numberoffiles( $parser, $raw = null ) { return self::statisticsFunction( 'wfNumberOfFiles', $raw ); }
	function numberofadmins( $parser, $raw = null ) { return self::statisticsFunction( 'wfNumberOfAdmins', $raw ); }

	function pagesinnamespace( $parser, $namespace = 0, $raw = null ) {
		$count = wfPagesInNs( intval( $namespace ) );
		if ( self::isRaw( $raw ) ) {
			global $wgContLang;
			return $wgContLang->formatNum( $count );
		} else {
			return $count;
		}
	}

	function language( $parser, $arg = '' ) {
		global $wgContLang;
		$lang = $wgContLang->getLanguageName( strtolower( $arg ) );
		return $lang != '' ? $lang : $arg;
	}
	
	function padleft( $parser, $string = '', $length = 0, $char = 0 ) {
		return str_pad( $string, $length, (string)$char, STR_PAD_LEFT );
	}
	
	function padright( $parser, $string = '', $length = 0, $char = 0 ) {
		return str_pad( $string, $length, (string)$char, STR_PAD_RIGHT );
	}
	
}

?>
