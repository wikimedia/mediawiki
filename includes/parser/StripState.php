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

	protected $tempType, $tempMergePrefix;
	protected $circularRefGuard;
	protected $recursionLevel = 0;

	const UNSTRIP_RECURSION_LIMIT = 20;

	/**
	 * @param string $prefix
	 */
	public function __construct( $prefix ) {
		$this->prefix = $prefix;
		$this->data = array(
			'nowiki' => array(),
			'general' => array()
		);
		$this->regex = "/{$this->prefix}([^\x7f]+)" . Parser::MARKER_SUFFIX . '/';
		$this->circularRefGuard = array();
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

		$oldType = $this->tempType;
		$this->tempType = $type;
		$text = preg_replace_callback( $this->regex, array( $this, 'unstripCallback' ), $text );
		$this->tempType = $oldType;
		return $text;
	}

	/**
	 * @param array $m
	 * @return array
	 */
	protected function unstripCallback( $m ) {
		$marker = $m[1];
		if ( isset( $this->data[$this->tempType][$marker] ) ) {
			if ( isset( $this->circularRefGuard[$marker] ) ) {
				return '<span class="error">'
					. wfMessage( 'parser-unstrip-loop-warning' )->inContentLanguage()->text()
					. '</span>';
			}
			if ( $this->recursionLevel >= self::UNSTRIP_RECURSION_LIMIT ) {
				return '<span class="error">' .
					wfMessage( 'parser-unstrip-recursion-limit' )
						->numParams( self::UNSTRIP_RECURSION_LIMIT )->inContentLanguage()->text() .
					'</span>';
			}
			$this->circularRefGuard[$marker] = true;
			$this->recursionLevel++;
			$ret = $this->unstripType( $this->tempType, $this->data[$this->tempType][$marker] );
			$this->recursionLevel--;
			unset( $this->circularRefGuard[$marker] );
			return $ret;
		} else {
			return $m[0];
		}
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
		$subState = new StripState( $this->prefix );
		$pos = 0;
		while ( true ) {
			$startPos = strpos( $text, $this->prefix, $pos );
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
		$mergePrefix = Parser::getRandomString();

		foreach ( $otherState->data as $type => $items ) {
			foreach ( $items as $key => $value ) {
				$this->data[$type]["$mergePrefix-$key"] = $value;
			}
		}

		$this->tempMergePrefix = $mergePrefix;
		$texts = preg_replace_callback( $otherState->regex, array( $this, 'mergeCallback' ), $texts );
		$this->tempMergePrefix = null;
		return $texts;
	}

	/**
	 * @param array $m
	 * @return string
	 */
	protected function mergeCallback( $m ) {
		$key = $m[1];
		return "{$this->prefix}{$this->tempMergePrefix}-$key" . Parser::MARKER_SUFFIX;
	}

	/**
	 * Remove any strip markers found in the given text.
	 *
	 * @param string $text Input string
	 * @return string
	 */
	public function killMarkers( $text ) {
		return preg_replace( $this->regex, '', $text );
	}
}
