<?php

/**
OBS!!! *EXPERIMENTAL* This class is still under discussion.

This class provides methods for fetching interface messages and
processing them into variety of formats that are needed in MediaWiki.

It is intented to replace the old wfMsg* functions that over time grew
unusable.

Examples:

Use full parsing.
Would correspond to wfMsgExt( 'key', array( 'parseinline' ), 'apple' );
$parsed = Message::key( 'key' ).param( 'apple' ).parse();
Parseinline is used because it is more useful when pre-building html.
In normal use it is better to use OutputPage::(add|wrap)WikiMsg.

Places where html cannot be used. {{-transformation is done.
Would correspond to wfMsgExt( 'key', array( 'parsemag' ), 'apple', 'pear' );
$plain = Message::key( 'key' ).params( 'apple', 'pear' ).text();

Shortcut for escaping the message too, similar to wfMsgHTML, but
parameters are not replaced after escaping by default.
$escaped = Message::key( 'key' ).rawParam( 'apple' ).escaped();

TODO:
* document everything
* test, can we have tests?
* sort out the details marked with fixme
* should we have _m() or similar global wrapper?

@since 1.17
*/
class Message {
	// FIXME: public or protected?

	// FIXME: handle language properly
	public $language;
	public $key;
	public $parameters = array();

	/**
	 * Constructor.
	 * @param $key String: message key
	 */
	public function __construct( $key ) {
		$this->key = $key;
	}

	/**
	 * Factory function that is just wrapper for the real constructor. It is
	 * intented to be used instead of the real constructor, because it allows
	 * chaining method calls, while new objects don't.
	 * //FIXME: key or get or something else?
	 * @param $key String: message key
	 * @return Message: reference to the object
	 */
	public function key( $key ) {
		return new Message( $key );
	}

	/**
	 * Adds parameters to the paramater list of this message.
	 * @params String: parameter
	 * @return Message: reference to the object
	 */
	public function param( $value ) {
		$this->parameters[] = $value;
		return $this;
	}

	/**
	 * Adds parameters to the paramater list of this message.
	 * @params Vargars: parameters
	 * @return Message: reference to the object
	 */
	public function params( /*...*/ ) {
		$this->paramList( func_get_args() );
		return $this;
	}

	/**
	 * Adds a list of parameters to the parameter list of this message.
	 * @param $value Array: list of parameters, array keys will be ignored.
	 * @return Message: reference to the object
	 */

	public function paramList( array $values ) {
		$this->parameters += array_values( $values );
		return $this;
	}

	/**
	 *
	 */
	public function rawParam( $value ) {
		$this->parameters[] =  array( 'raw' => $value );
		return $this;
	}

	public function parse() {
		$string = $this->parseAsBlock( $string );
		$m = array();
		if( preg_match( '/^<p>(.*)\n?<\/p>\n?$/sU', $string, $m ) ) {
			$string = $m[1];
		}
		return $string;
	}

	public function text() {
		$string = $this->getMessageText();
		$string = $this->replaceParameters( 'before' );
		$string = $this->parseText( $string );
		$string = $this->replaceParameters( 'after' );
		$m = array();
		if( preg_match( '/^<p>(.*)\n?<\/p>\n?$/sU', $string, $m ) ) {
			$string = $m[1];
		}
		return $string;
	}

	public function plain() {
		$string = $this->getMessageText();
		$string = $this->replaceParameters( 'before' );
		$string = $this->transformText( $string );
		$string = $this->replaceParameters( 'after' );
		return $string;
	}

	public function parseAsBlock() {
		$string = $this->getMessageText();
		$string =  $this->replaceParameters( 'before' );
		$string = $this->parseText( $string );
		$string = $this->replaceParameters( 'after' );
		return $string;
	}

	public function escaped() {
		return htmlspecialchars( $this->plain() );
	}

	protected function replaceParamaters( $type = 'before' ) {
		$replacementKeys = array();
		foreach( $args as $n => $param ) {
			if ( $type === 'before' && !isset( $param['raw'] ) ) {
				$replacementKeys['$' . ($n + 1)] = $param;
			} elseif ( $type === 'after' && isset( $param['raw'] ) ) {
				$replacementKeys['$' . ($n + 1)] = $param['raw'];
			}
		}
		$message = strtr( $message, $replacementKeys );
		return $message;
	}

	// FIXME: handle properly
	protected function getLanguage() {
		return $this->language === null ? true : $this->language;
	}

	protected function parseText( $string ) {
		global $wgOut;
		return $wgOut->parse( $string, true, (bool) $this->getLanguage() );
	}

	protected function transformText( $string ) {
		global $wgMessageCache;
		if ( !isset( $wgMessageCache ) ) return $string;
		// FIXME: handle second param correctly
		return $wgMessageCache->transform( $string, true, $this->getLanguage() );
	}

	protected function getMessageText() {
		return wfMsgGetKey( $this->key, /*DB*/true, $this->getLanguage(), /*Transform*/false );
	}

}