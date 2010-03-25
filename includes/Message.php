<?php

/**
 * OBS!!! *EXPERIMENTAL* This class is still under discussion.
 *
 * This class provides methods for fetching interface messages and
 * processing them into variety of formats that are needed in MediaWiki.
 *
 * It is intented to replace the old wfMsg* functions that over time grew
 * unusable.
 *
 * Examples:
 * Fetching a message text for interface message
 *  $button = Xml::button( Message::key( 'submit' )->text() );
 * Messages can have parameters:
 *  Message::key( 'welcome-to' )->param( $wgSitename )->text(); // {{GRAMMAR}} and friends work correctly
 *  Message::key( 'are-friends' )->params( $user, $friend );
 *  Message::key( 'bad-message' )->rawParam( '<script>...</script>' )->escaped()
 * Sometimes the message text ends up in the database, so content language is needed.
 *  Message::key( 'file-log' )->params( $user, $filename )->inContentLanguage()->text()
 * Checking if message exists:
 *  Message::key( 'mysterious-message' )->exists()
 * If you want to use a different language:
 *  Message::key( 'email-header' )->language( $user->getOption( 'language' ) )->plain()
 *
 *
 * Comparison with old wfMsg* functions:
 *
 * Use full parsing.
 * Would correspond to wfMsgExt( 'key', array( 'parseinline' ), 'apple' );
 * $parsed = Message::key( 'key' )->param( 'apple' )->parse();
 * Parseinline is used because it is more useful when pre-building html.
 * In normal use it is better to use OutputPage::(add|wrap)WikiMsg.
 *
 * Places where html cannot be used. {{-transformation is done.
 * Would correspond to wfMsgExt( 'key', array( 'parsemag' ), 'apple', 'pear' );
 * $plain = Message::key( 'key' )->params( 'apple', 'pear' )->text();
 *
 * Shortcut for escaping the message too, similar to wfMsgHTML, but
 * parameters are not replaced after escaping by default.
 * $escaped = Message::key( 'key' )->rawParam( 'apple' )->escaped();
 *
 * TODO:
 * * test, can we have tests?
 * * sort out the details marked with fixme
 * * should we have _m() or similar global wrapper?
 *
 * @since 1.17
 */
class Message {
	/**
	 * In which language to get this message. True, which is the default,
	 * means the current interface language, false content language.
	 */
	protected $interface = true;
	/**
	 * In which language to get this message. Overrides the $interface
	 * variable.
	 */
	protected $language = null;
	/**
	 * The message key.
	 */
	protected $key;

	/**
	 * List of parameters which will be substituted into the message.
	 */
	protected $parameters = array();

	/**
	 * Constructor.
	 * @param $key String: message key
	 * @return Message: $this
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
	 * @return Message: $this
	 */
	public function key( $key ) {
		return new Message( $key );
	}

	/**
	 * Adds parameters to the parameter list of this message.
	 * @param $value String: parameter
	 * @return Message: $this
	 */
	public function param( $value ) {
		$this->parameters[] = $value;
		return $this;
	}

	/**
	 * Adds parameters to the parameter list of this message.
	 * @params Vargars: parameters
	 * @return Message: $this
	 */
	public function params( /*...*/ ) {
		$this->paramList( func_get_args() );
		return $this;
	}

	/**
	 * Adds a list of parameters to the parameter list of this message.
	 * @param $value Array: list of parameters, array keys will be ignored.
	 * @return Message: $this
	 */
	public function paramList( array $values ) {
		$this->parameters += array_values( $values );
		return $this;
	}

	/**
	 * Adds a parameters that is substituted after parsing or escaping.
	 * In other words the parsing process cannot access the contents
	 * of this type parameter, and you need to make sure it is
	 * sanitized beforehand.
	 * @param $value String: raw parameter
	 * @return Message: $this
	 */
	public function rawParam( $value ) {
		$this->parameters[] =  array( 'raw' => $value );
		return $this;
	}

	/**
	 * Request the message in any language that is supported.
	 * As a side effect interface message status is unconditionally
	 * turned off.
	 * @param $lang Mixed: langauge code or language object.
	 * @return Message: $this
	 */
	public function language( Language $lang ) {
		if ( is_string( $lang ) ) {
			$this->language = Language::factory( $lang );
		} else {
			$this->language = $lang;
		}
		$this->interface = false;
		return $this;
	}

	/**
	 * Request the message in the wiki's content language.
	 * @return Message: $this
	 */
	public function inContentLanguage() {
		$this->interface = false;
		$this->language = null;
		return $this;
	}

	/**
	 * Returns the message parsed from wikitext to HTML.
	 * @return String: HTML
	 */
	public function parse() {
		$string = $this->parseAsBlock( $string );
		$m = array();
		if( preg_match( '/^<p>(.*)\n?<\/p>\n?$/sU', $string, $m ) ) {
			$string = $m[1];
		}
		return $string;
	}

	/**
	 * Returns the message text. {{-transformation is done.
	 * @return String: Unescaped message text.
	 */
	public function text() {
		$string = $this->getMessageText();
		$string = $this->replaceParameters( 'before' );
		$string = $this->transformText( $string );
		$string = $this->replaceParameters( 'after' );
		return $string;
	}

	/**
	 * Returns the message text as-is, only parameters are subsituted.
	 * @return String: Unescaped untransformed message text.
	 */
	public function plain() {
		$string = $this->getMessageText();
		$string = $this->replaceParameters( 'before' );
		$string = $this->replaceParameters( 'after' );
		return $string;
	}

	/**
	 * Returns the parsed message text which is always surrounded by a block element.
	 * @return String: HTML
	 */
	public function parseAsBlock() {
		$string = $this->getMessageText();
		$string =  $this->replaceParameters( 'before' );
		$string = $this->parseText( $string );
		$string = $this->replaceParameters( 'after' );
		return $string;
	}

	/**
	 * Returns the message text. {{-transformation is done and the result
	 * is excaped excluding any raw parameters.
	 * @return String: Escaped message text.
	 */
	public function escaped() {
		$string = $this->getMessageText();
		$string = $this->replaceParameters( 'before' );
		$string = $this->transformText( $string );
		$string = htmlspecialchars( $string );
		$string = $this->replaceParameters( 'after' );
		return $string;
	}

	/**
	 * Check whether a message key has been defined currently.
	 * @return Bool: true if it is and false if not.
	 */
	public function exists() {
		return !wfEmptyMsg( $this->key, $this->getMessageText() );
	}

	/**
	 * Substitutes any paramaters into the message text.
	 * @param $type String: either before or after
	 * @return String
	 */
	protected function replaceParameters( $type = 'before' ) {
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

	/**
	 * Wrapper for what ever method we use to parse wikitext.
	 * @param $string String: Wikitext message contents
	 * @return Wikitext parsed into HTML
	 */
	protected function parseText( $string ) {
		global $wgOut;
		if ( $this->language !== null ) {
			// FIXME: remove this limitation
			throw new MWException( 'Can only parse in interface or content language' );
		}
		return $wgOut->parse( $string, /*linestart*/true, $this->interface() );
	}

	/**
	 * Wrapper for what ever method we use to {{-transform wikitext.
	 * @param $string String: Wikitext message contents
	 * @return Wikitext with {{-constructs replaced with their values.
	 */
	protected function transformText( $string ) {
		global $wgMessageCache;
		return $wgMessageCache->transform( $string, $this->interface, $this->language );
	}

	/**
	 * Wrapper for what ever method we use to get message contents
	 * @return Unmodified message contents
	 */
	protected function getMessageText() {
		global $wgMessageCache;
		return $wgMessageCache->get( $this->key, /*DB*/true, $this->language );
	}

}