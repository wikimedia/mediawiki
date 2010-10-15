<?php
/**
 * Fake parser that output the difference of two different parsers
 *
 * @file
 */

/**
 * @ingroup Parser
 */
class Parser_DiffTest
{
	var $parsers, $conf;
	var $shortOutput = false;

	var $dtUniqPrefix;

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

		global $wgHooks;
		static $doneHook = false;
		if ( !$doneHook ) {
			$doneHook = true;
			$wgHooks['ParserClearState'][] = array( $this, 'onClearState' );
		}
		if ( isset( $this->conf['shortOutput'] ) ) {
			$this->shortOutput = $this->conf['shortOutput'];
		}

		foreach ( $this->conf['parsers'] as $i => $parserConf ) {
			if ( !is_array( $parserConf ) ) {
				$class = $parserConf;
				$parserConf = array( 'class' => $parserConf );
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
				if ( is_object( $lastResult ) ) {
					if ( $lastResult != $currentResult ) {
						$mismatch = true;
					}
				} else {
					if ( $lastResult !== $currentResult ) {
						$mismatch = true;
					}
				}
			}
			$results[$i] = $currentResult;
			$lastResult = $currentResult;
		}
		if ( $mismatch ) {
			if ( count( $results ) == 2 ) {
				$resultsList = array();
				foreach ( $this->parsers as $i => $parser ) {
					$resultsList[] = var_export( $results[$i], true );
				}
				$diff = wfDiff( $resultsList[0], $resultsList[1] );
			} else {
				$diff = '[too many parsers]';
			}
			$msg = "Parser_DiffTest: results mismatch on call to $name\n";
			if ( !$this->shortOutput ) {
				$msg .= 'Arguments: ' . $this->formatArray( $args ) . "\n";
			}
			$msg .= 'Results: ' . $this->formatArray( $results ) . "\n" .
				"Diff: $diff\n";
			throw new MWException( $msg );
		}
		return $lastResult;
	}

	function formatArray( $array ) {
		if ( $this->shortOutput ) {
			foreach ( $array as $key => $value ) {
				if ( $value instanceof ParserOutput ) {
					$array[$key] = "ParserOutput: {$value->getText()}";
				}
			}
		}
		return var_export( $array, true );
	}

	function setFunctionHook( $id, $callback, $flags = 0 ) {
		$this->init();
		foreach  ( $this->parsers as $parser ) {
			$parser->setFunctionHook( $id, $callback, $flags );
		}
	}

	function onClearState( &$parser ) {
		// hack marker prefixes to get identical output
		if ( !isset( $this->dtUniqPrefix ) ) {
			$this->dtUniqPrefix = $parser->mUniqPrefix;
		} else {
			$parser->mUniqPrefix = $this->dtUniqPrefix;
		}
		return true;
	}
}
