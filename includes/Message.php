<?php
/**
 * The Message class provides methods which fullfil two basic services:
 *  - fetching interface messages
 *  - processing messages into a variety of formats
 *
 * First implemented with MediaWiki 1.17, the Message class is intented to
 * replace the old wfMsg* functions that over time grew unusable.
 * @see https://www.mediawiki.org/wiki/New_messages_API for equivalences
 * between old and new functions.
 *
 * You should use the wfMessage() global function which acts as a wrapper for
 * the Message class. The wrapper let you pass parameters as arguments.
 *
 * The most basic usage cases would be:
 *
 * @code
 *     // Initialize a Message object using the 'some_key' message key
 *     $message = wfMessage( 'some_key' );
 *
 *     // Using two parameters those values are strings 'value1' and 'value2':
 *     $message = wfMessage( 'some_key',
 *          'value1', 'value2'
 *     );
 * @endcode
 *
 * @section message_global_fn Global function wrapper:
 *
 * Since wfMessage() returns a Message instance, you can chain its call with
 * a method. Some of them return a Message instance too so you can chain them.
 * You will find below several examples of wfMessage() usage.
 *
 * Fetching a message text for interface message:
 *
 * @code
 *    $button = Xml::button(
 *         wfMessage( 'submit' )->text()
 *    );
 * @endcode
 *
 * A Message instance can be passed parameters after it has been constructed,
 * use the params() method to do so:
 *
 * @code
 *     wfMessage( 'welcome-to' )
 *         ->params( $wgSitename )
 *         ->text();
 * @endcode
 *
 * {{GRAMMAR}} and friends work correctly:
 *
 * @code
 *    wfMessage( 'are-friends',
 *        $user, $friend
 *    );
 *    wfMessage( 'bad-message' )
 *         ->rawParams( '<script>...</script>' )
 *         ->escaped();
 * @endcode
 *
 * @section message_language Changing language:
 *
 * Messages can be requested in a different language or in whatever current
 * content language is being used. The methods are:
 *     - Message->inContentLanguage()
 *     - Message->inLanguage()
 *
 * Sometimes the message text ends up in the database, so content language is
 * needed:
 *
 * @code
 *    wfMessage( 'file-log',
 *        $user, $filename
 *    )->inContentLanguage()->text();
 * @endcode
 *
 * Checking whether a message exists:
 *
 * @code
 *    wfMessage( 'mysterious-message' )->exists()
 *    // returns a boolean whether the 'mysterious-message' key exist.
 * @endcode
 *
 * If you want to use a different language:
 *
 * @code
 *    $userLanguage = $user->getOption( 'language' );
 *    wfMessage( 'email-header' )
 *         ->inLanguage( $userLanguage )
 *         ->plain();
 * @endcode
 *
 * @note You cannot parse the text except in the content or interface
 * @note languages
 *
 * @section message_compare_old Comparison with old wfMsg* functions:
 *
 * Use full parsing:
 *
 * @code
 *     // old style:
 *     wfMsgExt( 'key', array( 'parseinline' ), 'apple' );
 *     // new style:
 *     wfMessage( 'key', 'apple' )->parse();
 * @endcode
 *
 * Parseinline is used because it is more useful when pre-building HTML.
 * In normal use it is better to use OutputPage::(add|wrap)WikiMsg.
 *
 * Places where HTML cannot be used. {{-transformation is done.
 * @code
 *     // old style:
 *     wfMsgExt( 'key', array( 'parsemag' ), 'apple', 'pear' );
 *     // new style:
 *     wfMessage( 'key', 'apple', 'pear' )->text();
 * @endcode
 *
 * Shortcut for escaping the message too, similar to wfMsgHTML(), but
 * parameters are not replaced after escaping by default.
 * @code
 *     $escaped = wfMessage( 'key' )
 *          ->rawParams( 'apple' )
 *          ->escaped();
 * @endcode
 *
 * @section message_appendix Appendix:
 *
 * @todo
 * - test, can we have tests?
 * - this documentation needs to be extended
 *
 * @see https://www.mediawiki.org/wiki/WfMessage()
 * @see https://www.mediawiki.org/wiki/New_messages_API
 * @see https://www.mediawiki.org/wiki/Localisation
 *
 * @since 1.17
 * @author Niklas Laxström
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
	 *
	 * @var Language
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
	 * Format for the message.
	 * Supported formats are:
	 * * text (transform)
	 * * escaped (transform+htmlspecialchars)
	 * * block-parse
	 * * parse (default)
	 * * plain
	 */
	protected $format = 'parse';

	/**
	 * Whether database can be used.
	 */
	protected $useDatabase = true;

	/**
	 * Title object to use as context
	 */
	protected $title = null;

	/**
	 * @var string
	 */
	protected $message;

	/**
	 * Constructor.
	 * @param $key: message key, or array of message keys to try and use the first non-empty message for
	 * @param $params Array message parameters
	 * @return Message: $this
	 */
	public function __construct( $key, $params = array() ) {
		global $wgLang;
		$this->key = $key;
		$this->parameters = array_values( $params );
		$this->language = $wgLang;
	}

	/**
	 * Factory function that is just wrapper for the real constructor. It is
	 * intented to be used instead of the real constructor, because it allows
	 * chaining method calls, while new objects don't.
	 * @param $key String: message key
	 * @param Varargs: parameters as Strings
	 * @return Message: $this
	 */
	public static function newFromKey( $key /*...*/ ) {
		$params = func_get_args();
		array_shift( $params );
		return new self( $key, $params );
	}

	/**
	 * Factory function accepting multiple message keys and returning a message instance
	 * for the first message which is non-empty. If all messages are empty then an
	 * instance of the first message key is returned.
	 * @param Varargs: message keys (or first arg as an array of all the message keys)
	 * @return Message: $this
	 */
	public static function newFallbackSequence( /*...*/ ) {
		$keys = func_get_args();
		if ( func_num_args() == 1 ) {
			if ( is_array($keys[0]) ) {
				// Allow an array to be passed as the first argument instead
				$keys = array_values($keys[0]);
			} else {
				// Optimize a single string to not need special fallback handling
				$keys = $keys[0];
			}
		}
		return new self( $keys );
	}

	/**
	 * Adds parameters to the parameter list of this message.
	 * @param Varargs: parameters as Strings, or a single argument that is an array of Strings
	 * @return Message: $this
	 */
	public function params( /*...*/ ) {
		$args = func_get_args();
		if ( isset( $args[0] ) && is_array( $args[0] ) ) {
			$args = $args[0];
		}
		$args_values = array_values( $args );
		$this->parameters = array_merge( $this->parameters, $args_values );
		return $this;
	}

	/**
	 * Add parameters that are substituted after parsing or escaping.
	 * In other words the parsing process cannot access the contents
	 * of this type of parameter, and you need to make sure it is
	 * sanitized beforehand.  The parser will see "$n", instead.
	 * @param Varargs: raw parameters as Strings (or single argument that is an array of raw parameters)
	 * @return Message: $this
	 */
	public function rawParams( /*...*/ ) {
		$params = func_get_args();
		if ( isset( $params[0] ) && is_array( $params[0] ) ) {
			$params = $params[0];
		}
		foreach( $params as $param ) {
			$this->parameters[] = self::rawParam( $param );
		}
		return $this;
	}

	/**
	 * Add parameters that are numeric and will be passed through
	 * Language::formatNum before substitution
	 * @param Varargs: numeric parameters (or single argument that is array of numeric parameters)
	 * @return Message: $this
	 */
	public function numParams( /*...*/ ) {
		$params = func_get_args();
		if ( isset( $params[0] ) && is_array( $params[0] ) ) {
			$params = $params[0];
		}
		foreach( $params as $param ) {
			$this->parameters[] = self::numParam( $param );
		}
		return $this;
	}

	/**
	 * Set the language and the title from a context object
	 *
	 * @param $context IContextSource
	 * @return Message: $this
	 */
	public function setContext( IContextSource $context ) {
		$this->inLanguage( $context->getLanguage() );
		$this->title( $context->getTitle() );

		return $this;
	}

	/**
	 * Request the message in any language that is supported.
	 * As a side effect interface message status is unconditionally
	 * turned off.
	 * @param $lang Mixed: language code or Language object.
	 * @return Message: $this
	 */
	public function inLanguage( $lang ) {
		if ( $lang instanceof Language || $lang instanceof StubUserLang ) {
			$this->language = $lang;
		} elseif ( is_string( $lang ) ) {
			if( $this->language->getCode() != $lang ) {
				$this->language = Language::factory( $lang );
			}
		} else {
			$type = gettype( $lang );
			throw new MWException( __METHOD__ . " must be "
				. "passed a String or Language object; $type given"
			);
		}
		$this->interface = false;
		return $this;
	}

	/**
	 * Request the message in the wiki's content language,
	 * unless it is disabled for this message.
	 * @see $wgForceUIMsgAsContentMsg
	 * @return Message: $this
	 */
	public function inContentLanguage() {
		global $wgForceUIMsgAsContentMsg;
		if ( in_array( $this->key, (array)$wgForceUIMsgAsContentMsg ) ) {
			return $this;
		}

		global $wgContLang;
		$this->interface = false;
		$this->language = $wgContLang;
		return $this;
	}

	/**
	 * Enable or disable database use.
	 * @param $value Boolean
	 * @return Message: $this
	 */
	public function useDatabase( $value ) {
		$this->useDatabase = (bool) $value;
		return $this;
	}

	/**
	 * Set the Title object to use as context when transforming the message
	 *
	 * @param $title Title object
	 * @return Message: $this
	 */
	public function title( $title ) {
		$this->title = $title;
		return $this;
	}

	/**
	 * Returns the message parsed from wikitext to HTML.
	 * @return String: HTML
	 */
	public function toString() {
		$string = $this->getMessageText();

		# Replace parameters before text parsing
		$string = $this->replaceParameters( $string, 'before' );

		# Maybe transform using the full parser
		if( $this->format === 'parse' ) {
			$string = $this->parseText( $string );
			$m = array();
			if( preg_match( '/^<p>(.*)\n?<\/p>\n?$/sU', $string, $m ) ) {
				$string = $m[1];
			}
		} elseif( $this->format === 'block-parse' ){
			$string = $this->parseText( $string );
		} elseif( $this->format === 'text' ){
			$string = $this->transformText( $string );
		} elseif( $this->format === 'escaped' ){
			$string = $this->transformText( $string );
			$string = htmlspecialchars( $string, ENT_QUOTES, 'UTF-8', false );
		}

		# Raw parameter replacement
		$string = $this->replaceParameters( $string, 'after' );

		return $string;
	}

	/**
	 * Magic method implementation of the above (for PHP >= 5.2.0), so we can do, eg:
	 *     $foo = Message::get($key);
	 *     $string = "<abbr>$foo</abbr>";
	 * @return String
	 */
	public function __toString() {
		return $this->toString();
	}

	/**
	 * Fully parse the text from wikitext to HTML
	 * @return String parsed HTML
	 */
	public function parse() {
		$this->format = 'parse';
		return $this->toString();
	}

	/**
	 * Returns the message text. {{-transformation is done.
	 * @return String: Unescaped message text.
	 */
	public function text() {
		$this->format = 'text';
		return $this->toString();
	}

	/**
	 * Returns the message text as-is, only parameters are subsituted.
	 * @return String: Unescaped untransformed message text.
	 */
	public function plain() {
		$this->format = 'plain';
		return $this->toString();
	}

	/**
	 * Returns the parsed message text which is always surrounded by a block element.
	 * @return String: HTML
	 */
	public function parseAsBlock() {
		$this->format = 'block-parse';
		return $this->toString();
	}

	/**
	 * Returns the message text. {{-transformation is done and the result
	 * is escaped excluding any raw parameters.
	 * @return String: Escaped message text.
	 */
	public function escaped() {
		$this->format = 'escaped';
		return $this->toString();
	}

	/**
	 * Check whether a message key has been defined currently.
	 * @return Bool: true if it is and false if not.
	 */
	public function exists() {
		return $this->fetchMessage() !== false;
	}

	/**
	 * Check whether a message does not exist, or is an empty string
	 * @return Bool: true if is is and false if not
	 * @todo FIXME: Merge with isDisabled()?
	 */
	public function isBlank() {
		$message = $this->fetchMessage();
		return $message === false || $message === '';
	}

	/**
	 * Check whether a message does not exist, is an empty string, or is "-"
	 * @return Bool: true if is is and false if not
	 */
	public function isDisabled() {
		$message = $this->fetchMessage();
		return $message === false || $message === '' || $message === '-';
	}

	/**
	 * @param $value
	 * @return array
	 */
	public static function rawParam( $value ) {
		return array( 'raw' => $value );
	}

	/**
	 * @param $value
	 * @return array
	 */
	public static function numParam( $value ) {
		return array( 'num' => $value );
	}

	/**
	 * Substitutes any paramaters into the message text.
	 * @param $message String: the message text
	 * @param $type String: either before or after
	 * @return String
	 */
	protected function replaceParameters( $message, $type = 'before' ) {
		$replacementKeys = array();
		foreach( $this->parameters as $n => $param ) {
			list( $paramType, $value ) = $this->extractParam( $param );
			if ( $type === $paramType ) {
				$replacementKeys['$' . ($n + 1)] = $value;
			}
		}
		$message = strtr( $message, $replacementKeys );
		return $message;
	}

	/**
	 * Extracts the parameter type and preprocessed the value if needed.
	 * @param $param String|Array: Parameter as defined in this class.
	 * @return Tuple(type, value)
	 */
	protected function extractParam( $param ) {
		if ( is_array( $param ) && isset( $param['raw'] ) ) {
			return array( 'after', $param['raw'] );
		} elseif ( is_array( $param ) && isset( $param['num'] ) ) {
			// Replace number params always in before step for now.
			// No support for combined raw and num params
			return array( 'before', $this->language->formatNum( $param['num'] ) );
		} elseif ( !is_array( $param ) ) {
			return array( 'before', $param );
		} else {
			trigger_error(
				"Invalid message parameter: " . htmlspecialchars( serialize( $param ) ),
				E_USER_WARNING
			);
			return array( 'before', '[INVALID]' );
		}
	}

	/**
	 * Wrapper for what ever method we use to parse wikitext.
	 * @param $string String: Wikitext message contents
	 * @return string Wikitext parsed into HTML
	 */
	protected function parseText( $string ) {
		return MessageCache::singleton()->parse( $string, $this->title, /*linestart*/true, $this->interface, $this->language )->getText();
	}

	/**
	 * Wrapper for what ever method we use to {{-transform wikitext.
	 * @param $string String: Wikitext message contents
	 * @return string Wikitext with {{-constructs replaced with their values.
	 */
	protected function transformText( $string ) {
		return MessageCache::singleton()->transform( $string, $this->interface, $this->language, $this->title );
	}

	/**
	 * Returns the textual value for the message.
	 * @return Message contents or placeholder
	 */
	protected function getMessageText() {
		$message = $this->fetchMessage();
		if ( $message === false ) {
			return '&lt;' . htmlspecialchars( is_array($this->key) ? $this->key[0] : $this->key ) . '&gt;';
		} else {
			return $message;
		}
	}

	/**
	 * Wrapper for what ever method we use to get message contents
	 *
	 * @return string
	 */
	protected function fetchMessage() {
		if ( !isset( $this->message ) ) {
			$cache = MessageCache::singleton();
			if ( is_array( $this->key ) ) {
				if ( !count( $this->key ) ) {
					throw new MWException( "Given empty message key array." );
				}
				foreach ( $this->key as $key ) {
					$message = $cache->get( $key, $this->useDatabase, $this->language );
					if ( $message !== false && $message !== '' ) {
						break;
					}
				}
				$this->message = $message;
			} else {
				$this->message = $cache->get( $this->key, $this->useDatabase, $this->language );
			}
		}
		return $this->message;
	}

}
