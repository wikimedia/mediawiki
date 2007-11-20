<?php

class Parser_DiffTest
{
	var $parsers, $conf;

	function __construct( $conf ) {
		if ( !isset( $conf['parsers'] ) ) {
			throw new MWException( __METHOD__ . ': no parsers specified' );
		}
		$this->conf = $conf;
	}

	function init() {
		if ( !is_null( $this->parsers ) ) {
			return;
		}
		foreach ( $this->conf['parsers'] as $i => $parserConf ) {
			if ( !is_array( $parserConf ) ) {
				$class = $parserConf;
				$parserconf = array( 'class' => $parserConf );
			} else {
				$class = $parserConf['class'];
			}
			$this->parsers[$i] = new $class( $parserConf );
		}
	}

	function __call( $name, $args ) {
		$this->init();
		$results = array();
		$mismatch = false;
		$lastResult = null;
		$first = true;
		foreach ( $this->parsers as $i => $parser ) {
			$currentResult = call_user_func_array( array( &$this->parsers[$i], $name ), $args );
			if ( $first ) {
				$first = false;
			} else {
				if ( $lastResult !== $currentResult ) {
					$mismatch = true;
				}
			}
			$results[$i] = $currentResult;
			$lastResult = $currentResult;
		}
		if ( $mismatch ) {
			throw new MWException( "Parser_DiffTest: results mismatch on call to $name\n" .
				'Arguments: ' . var_export( $args, true ) . "\n" . 
				'Results: ' . var_export( $results, true ) . "\n" );
		}
		return $lastResult;
	}

	function setFunctionHook( $id, $callback, $flags = 0 ) {
		$this->init();
		foreach  ( $this->parsers as $i => $parser ) {
			$parser->setFunctionHook( $id, $callback, $flags );
		}
	}
}

