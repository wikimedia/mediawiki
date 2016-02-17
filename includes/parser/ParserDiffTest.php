<?php
/**
 * Fake parser that output the difference of two different parsers
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Parser
 */

/**
 * @ingroup Parser
 */
class ParserDiffTest
{
	public $parsers;
	public $conf;
	public $shortOutput = false;

	public function __construct( $conf ) {
		if ( !isset( $conf['parsers'] ) ) {
			throw new MWException( __METHOD__ . ': no parsers specified' );
		}
		$this->conf = $conf;
	}

	public function init() {
		if ( !is_null( $this->parsers ) ) {
			return;
		}

		if ( isset( $this->conf['shortOutput'] ) ) {
			$this->shortOutput = $this->conf['shortOutput'];
		}

		foreach ( $this->conf['parsers'] as $i => $parserConf ) {
			if ( !is_array( $parserConf ) ) {
				$class = $parserConf;
				$parserConf = [ 'class' => $parserConf ];
			} else {
				$class = $parserConf['class'];
			}
			$this->parsers[$i] = new $class( $parserConf );
		}
	}

	public function __call( $name, $args ) {
		$this->init();
		$results = [];
		$mismatch = false;
		$lastResult = null;
		$first = true;
		foreach ( $this->parsers as $i => $parser ) {
			$currentResult = call_user_func_array( [ &$this->parsers[$i], $name ], $args );
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
				$resultsList = [];
				foreach ( $this->parsers as $i => $parser ) {
					$resultsList[] = var_export( $results[$i], true );
				}
				$diff = wfDiff( $resultsList[0], $resultsList[1] );
			} else {
				$diff = '[too many parsers]';
			}
			$msg = "ParserDiffTest: results mismatch on call to $name\n";
			if ( !$this->shortOutput ) {
				$msg .= 'Arguments: ' . $this->formatArray( $args ) . "\n";
			}
			$msg .= 'Results: ' . $this->formatArray( $results ) . "\n" .
				"Diff: $diff\n";
			throw new MWException( $msg );
		}
		return $lastResult;
	}

	public function formatArray( $array ) {
		if ( $this->shortOutput ) {
			foreach ( $array as $key => $value ) {
				if ( $value instanceof ParserOutput ) {
					$array[$key] = "ParserOutput: {$value->getText()}";
				}
			}
		}
		return var_export( $array, true );
	}

	public function setFunctionHook( $id, $callback, $flags = 0 ) {
		$this->init();
		foreach ( $this->parsers as $parser ) {
			$parser->setFunctionHook( $id, $callback, $flags );
		}
	}
}
