<?php
/**
 * Holder for stripped items when parsing wiki markup.
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
 * @todo document, briefly.
 * @ingroup Parser
 */
class StripState {
	protected $prefix;
	protected $data;
	protected $regex;

	protected $parser;

	protected $circularRefGuard;
	protected $recursionLevel = 0;
	protected $highestRecursionLevel = 0;
	protected $expandSize = 0;

	const UNSTRIP_RECURSION_LIMIT = 20;
	const UNSTRIP_SIZE_LIMIT = 5000000;

	/**
	 * @param Parser|null $parser
	 */
	public function __construct( Parser $parser = null ) {
		$this->data = [
			'nowiki' => [],
			'general' => []
		];
		$this->regex = '/' . Parser::MARKER_PREFIX . "([^\x7f<>&'\"]+)" . Parser::MARKER_SUFFIX . '/';
		$this->circularRefGuard = [];
		$this->parser = $parser;
	}

	/**
	 * Add a nowiki strip item
	 * @param string $marker
	 * @param string $value
	 */
	public function addNoWiki( $marker, $value ) {
		$this->addItem( 'nowiki', $marker, $value );
	}

	/**
	 * @param string $marker
	 * @param string $value
	 */
	public function addGeneral( $marker, $value ) {
		$this->addItem( 'general', $marker, $value );
	}

	/**
	 * @throws MWException
	 * @param string $type
	 * @param string $marker
	 * @param string $value
	 */
	protected function addItem( $type, $marker, $value ) {
		if ( !preg_match( $this->regex, $marker, $m ) ) {
			throw new MWException( "Invalid marker: $marker" );
		}

		$this->data[$type][$m[1]] = $value;
	}

	/**
	 * @param string $text
	 * @return mixed
	 */
	public function unstripGeneral( $text ) {
		return $this->unstripType( 'general', $text );
	}

	/**
	 * @param string $text
	 * @return mixed
	 */
	public function unstripNoWiki( $text ) {
		return $this->unstripType( 'nowiki', $text );
	}

	/**
	 * @param string $text
	 * @return mixed
	 */
	public function unstripBoth( $text ) {
		$text = $this->unstripType( 'general', $text );
		$text = $this->unstripType( 'nowiki', $text );
		return $text;
	}

	/**
	 * @param string $type
	 * @param string $text
	 * @return mixed
	 */
	protected function unstripType( $type, $text ) {
		// Shortcut
		if ( !count( $this->data[$type] ) ) {
			return $text;
		}

		$callback = function ( $m ) use ( $type ) {
			$marker = $m[1];
			if ( isset( $this->data[$type][$marker] ) ) {
				if ( isset( $this->circularRefGuard[$marker] ) ) {
					return $this->getWarning( 'parser-unstrip-loop-warning' );
				}

				if ( $this->recursionLevel > $this->highestRecursionLevel ) {
					$this->highestRecursionLevel = $this->recursionLevel;
				}
				if ( $this->recursionLevel >= self::UNSTRIP_RECURSION_LIMIT ) {
					return $this->getLimitationWarning( 'unstrip-depth',
						self::UNSTRIP_RECURSION_LIMIT );
				}

				$value = $this->data[$type][$marker];
				if ( $value instanceof Closure ) {
					$value = $value();
				}

				$this->expandSize += strlen( $value );
				if ( $this->expandSize > self::UNSTRIP_SIZE_LIMIT ) {
					return $this->getLimitationWarning( 'unstrip-size',
						self::UNSTRIP_SIZE_LIMIT );
				}

				$this->circularRefGuard[$marker] = true;
				$this->recursionLevel++;
				$ret = $this->unstripType( $type, $value );
				$this->recursionLevel--;
				unset( $this->circularRefGuard[$marker] );

				return $ret;
			} else {
				return $m[0];
			}
		};

		$text = preg_replace_callback( $this->regex, $callback, $text );
		return $text;
	}

	/**
	 * Get warning HTML and register a limitation warning with the parser
	 *
	 * @param string $type
	 * @param int $max
	 * @return string
	 */
	private function getLimitationWarning( $type, $max = '' ) {
		if ( $this->parser ) {
			$this->parser->limitationWarn( $type, $max );
		}
		return $this->getWarning( "$type-warning", $max );
	}

	/**
	 * Get warning HTML
	 *
	 * @param string $message
	 * @param int $max
	 * @return string
	 */
	private function getWarning( $message, $max = '' ) {
		return '<span class="error">' .
			wfMessage( $message )
				->numParams( $max )->inContentLanguage()->text() .
			'</span>';
	}

	/**
	 * Get an array of parameters to pass to ParserOutput::setLimitReportData()
	 *
	 * @unstable Should only be called by Parser
	 * @return array
	 */
	public function getLimitReport() {
		return [
			[ 'limitreport-unstrip-depth',
				[
					$this->highestRecursionLevel,
					self::UNSTRIP_RECURSION_LIMIT
				],
			],
			[ 'limitreport-unstrip-size',
				[
					$this->expandSize,
					self::UNSTRIP_SIZE_LIMIT
				],
			]
		];
	}

	/**
	 * Get a StripState object which is sufficient to unstrip the given text.
	 * It will contain the minimum subset of strip items necessary.
	 *
	 * @param string $text
	 *
	 * @return StripState
	 */
	public function getSubState( $text ) {
		$subState = new StripState;
		$pos = 0;
		while ( true ) {
			$startPos = strpos( $text, Parser::MARKER_PREFIX, $pos );
			$endPos = strpos( $text, Parser::MARKER_SUFFIX, $pos );
			if ( $startPos === false || $endPos === false ) {
				break;
			}

			$endPos += strlen( Parser::MARKER_SUFFIX );
			$marker = substr( $text, $startPos, $endPos - $startPos );
			if ( !preg_match( $this->regex, $marker, $m ) ) {
				continue;
			}

			$key = $m[1];
			if ( isset( $this->data['nowiki'][$key] ) ) {
				$subState->data['nowiki'][$key] = $this->data['nowiki'][$key];
			} elseif ( isset( $this->data['general'][$key] ) ) {
				$subState->data['general'][$key] = $this->data['general'][$key];
			}
			$pos = $endPos;
		}
		return $subState;
	}

	/**
	 * Merge another StripState object into this one. The strip marker keys
	 * will not be preserved. The strings in the $texts array will have their
	 * strip markers rewritten, the resulting array of strings will be returned.
	 *
	 * @param StripState $otherState
	 * @param array $texts
	 * @return array
	 */
	public function merge( $otherState, $texts ) {
		$mergePrefix = wfRandomString( 16 );

		foreach ( $otherState->data as $type => $items ) {
			foreach ( $items as $key => $value ) {
				$this->data[$type]["$mergePrefix-$key"] = $value;
			}
		}

		$callback = function ( $m ) use ( $mergePrefix ) {
			$key = $m[1];
			return Parser::MARKER_PREFIX . $mergePrefix . '-' . $key . Parser::MARKER_SUFFIX;
		};
		$texts = preg_replace_callback( $otherState->regex, $callback, $texts );
		return $texts;
	}

	/**
	 * Remove any strip markers found in the given text.
	 *
	 * @param string $text
	 * @return string
	 */
	public function killMarkers( $text ) {
		return preg_replace( $this->regex, '', $text );
	}
}
