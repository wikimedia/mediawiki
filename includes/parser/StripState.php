<?php

/**
 * @todo document, briefly.
 * @ingroup Parser
 */
class StripState {
	protected $prefix;
	protected $data;
	protected $regex;

	protected $tempType, $tempMergePrefix;

	function __construct( $prefix ) {
		$this->prefix = $prefix;
		$this->data = array(
			'nowiki' => array(),
			'general' => array()
		);
		$this->regex = "/{$this->prefix}([^\x7f]+)" . Parser::MARKER_SUFFIX . '/';
	}

	/**
	 * Add a nowiki strip item
	 * @param $marker
	 * @param $value
	 */
	function addNoWiki( $marker, $value ) {
		$this->addItem( 'nowiki', $marker, $value );
	}

	/**
	 * @param $marker
	 * @param $value
	 */
	function addGeneral( $marker, $value ) {
		$this->addItem( 'general', $marker, $value );
	}

	/**
	 * @throws MWException
	 * @param $type
	 * @param $marker
	 * @param $value
	 */
	protected function addItem( $type, $marker, $value ) {
		if ( !preg_match( $this->regex, $marker, $m ) ) {
			throw new MWException( "Invalid marker: $marker" );
		}

		$this->data[$type][$m[1]] = $value;
	}

	/**
	 * @param $text
	 * @return mixed
	 */
	function unstripGeneral( $text ) {
		return $this->unstripType( 'general', $text );
	}

	/**
	 * @param $text
	 * @return mixed
	 */
	function unstripNoWiki( $text ) {
		return $this->unstripType( 'nowiki', $text );
	}

	/**
	 * @param  $text
	 * @return mixed
	 */
	function unstripBoth( $text ) {
		$text = $this->unstripType( 'general', $text );
		$text = $this->unstripType( 'nowiki', $text );
		return $text;
	}

	/**
	 * @param $type
	 * @param $text
	 * @return mixed
	 */
	protected function unstripType( $type, $text ) {
		// Shortcut 
		if ( !count( $this->data[$type] ) ) {
			return $text;
		}

		wfProfileIn( __METHOD__ );
		$this->tempType = $type;
		do {
			$oldText = $text;
			$text = preg_replace_callback( $this->regex, array( $this, 'unstripCallback' ), $text );
		} while ( $text !== $oldText );
		$this->tempType = null;
		wfProfileOut( __METHOD__ );
		return $text;
	}

	/**
	 * @param $m array
	 * @return array
	 */
	protected function unstripCallback( $m ) {
		if ( isset( $this->data[$this->tempType][$m[1]] ) ) {
			return $this->data[$this->tempType][$m[1]];
		} else {
			if( preg_match( $this->regex, $m[0] ) ) {
				return "<strong class='error'>".htmlspecialchars( wfMsg( "stripstate-error" ) )."</strong>";
			}
			return $m[0];
		}
	}

	/**
	 * Get a StripState object which is sufficient to unstrip the given text. 
	 * It will contain the minimum subset of strip items necessary.
	 *
	 * @param $text string
	 *
	 * @return StripState
	 */
	function getSubState( $text ) {
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
	 * @param $otherState StripState
	 * @param $texts Array
	 * @return Array
	 */
	function merge( $otherState, $texts ) {
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

	protected function mergeCallback( $m ) {
		$key = $m[1];
		return "{$this->prefix}{$this->tempMergePrefix}-$key" . Parser::MARKER_SUFFIX;
	}
}

