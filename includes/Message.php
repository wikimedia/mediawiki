<?php
/**
 * Fetching and processing of interface messages.
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
 * @author Niklas Laxström
 */

/**
 * The Message class provides methods which fulfil two basic services:
 *  - fetching interface messages
 *  - processing messages into a variety of formats
 *
 * First implemented with MediaWiki 1.17, the Message class is intended to
 * replace the old wfMsg* functions that over time grew unusable.
 * @see https://www.mediawiki.org/wiki/Manual:Messages_API for equivalences
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
 * @note You can parse the text only in the content or interface languages
 *
 * @section message_compare_old Comparison with old wfMsg* functions:
 *
 * Use full parsing:
 *
 * @code
 *     // old style:
 *     wfMsgExt( 'key', [ 'parseinline' ], 'apple' );
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
 *     wfMsgExt( 'key', [ 'parsemag' ], 'apple', 'pear' );
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
 */
class Message implements MessageSpecifier, Serializable {
	/** Use message text as-is */
	const FORMAT_PLAIN = 'plain';
	/** Use normal wikitext -> HTML parsing (the result will be wrapped in a block-level HTML tag) */
	const FORMAT_BLOCK_PARSE = 'block-parse';
	/** Use normal wikitext -> HTML parsing but strip the block-level wrapper */
	const FORMAT_PARSE = 'parse';
	/** Transform {{..}} constructs but don't transform to HTML */
	const FORMAT_TEXT = 'text';
	/** Transform {{..}} constructs, HTML-escape the result */
	const FORMAT_ESCAPED = 'escaped';

	/**
	 * Mapping from Message::listParam() types to Language methods.
	 * @var array
	 */
	protected static $listTypeMap = [
		'comma' => 'commaList',
		'semicolon' => 'semicolonList',
		'pipe' => 'pipeList',
		'text' => 'listToText',
	];

	/**
	 * In which language to get this message. True, which is the default,
	 * means the current user language, false content language.
	 *
	 * @var bool
	 */
	protected $interface = true;

	/**
	 * In which language to get this message. Overrides the $interface setting.
	 *
	 * @var Language|bool Explicit language object, or false for user language
	 */
	protected $language = false;

	/**
	 * @var string The message key. If $keysToTry has more than one element,
	 * this may change to one of the keys to try when fetching the message text.
	 */
	protected $key;

	/**
	 * @var string[] List of keys to try when fetching the message.
	 */
	protected $keysToTry;

	/**
	 * @var array List of parameters which will be substituted into the message.
	 */
	protected $parameters = [];

	/**
	 * @var string
	 * @deprecated
	 */
	protected $format = 'parse';

	/**
	 * @var bool Whether database can be used.
	 */
	protected $useDatabase = true;

	/**
	 * @var Title Title object to use as context.
	 */
	protected $title = null;

	/**
	 * @var Content Content object representing the message.
	 */
	protected $content = null;

	/**
	 * @var string
	 */
	protected $message;

	/**
	 * @since 1.17
	 * @param string|string[]|MessageSpecifier $key Message key, or array of
	 * message keys to try and use the first non-empty message for, or a
	 * MessageSpecifier to copy from.
	 * @param array $params Message parameters.
	 * @param Language $language [optional] Language to use (defaults to current user language).
	 * @throws InvalidArgumentException
	 */
	public function __construct( $key, $params = [], Language $language = null ) {
		if ( $key instanceof MessageSpecifier ) {
			if ( $params ) {
				throw new InvalidArgumentException(
					'$params must be empty if $key is a MessageSpecifier'
				);
			}
			$params = $key->getParams();
			$key = $key->getKey();
		}

		if ( !is_string( $key ) && !is_array( $key ) ) {
			throw new InvalidArgumentException( '$key must be a string or an array' );
		}

		$this->keysToTry = (array)$key;

		if ( empty( $this->keysToTry ) ) {
			throw new InvalidArgumentException( '$key must not be an empty list' );
		}

		$this->key = reset( $this->keysToTry );

		$this->parameters = array_values( $params );
		// User language is only resolved in getLanguage(). This helps preserve the
		// semantic intent of "user language" across serialize() and unserialize().
		$this->language = $language ?: false;
	}

	/**
	 * @see Serializable::serialize()
	 * @since 1.26
	 * @return string
	 */
	public function serialize() {
		return serialize( [
			'interface' => $this->interface,
			'language' => $this->language ? $this->language->getCode() : false,
			'key' => $this->key,
			'keysToTry' => $this->keysToTry,
			'parameters' => $this->parameters,
			'format' => $this->format,
			'useDatabase' => $this->useDatabase,
			'title' => $this->title,
		] );
	}

	/**
	 * @see Serializable::unserialize()
	 * @since 1.26
	 * @param string $serialized
	 */
	public function unserialize( $serialized ) {
		$data = unserialize( $serialized );
		$this->interface = $data['interface'];
		$this->key = $data['key'];
		$this->keysToTry = $data['keysToTry'];
		$this->parameters = $data['parameters'];
		$this->format = $data['format'];
		$this->useDatabase = $data['useDatabase'];
		$this->language = $data['language'] ? Language::factory( $data['language'] ) : false;
		$this->title = $data['title'];
	}

	/**
	 * @since 1.24
	 *
	 * @return bool True if this is a multi-key message, that is, if the key provided to the
	 * constructor was a fallback list of keys to try.
	 */
	public function isMultiKey() {
		return count( $this->keysToTry ) > 1;
	}

	/**
	 * @since 1.24
	 *
	 * @return string[] The list of keys to try when fetching the message text,
	 * in order of preference.
	 */
	public function getKeysToTry() {
		return $this->keysToTry;
	}

	/**
	 * Returns the message key.
	 *
	 * If a list of multiple possible keys was supplied to the constructor, this method may
	 * return any of these keys. After the message has been fetched, this method will return
	 * the key that was actually used to fetch the message.
	 *
	 * @since 1.21
	 *
	 * @return string
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * Returns the message parameters.
	 *
	 * @since 1.21
	 *
	 * @return array
	 */
	public function getParams() {
		return $this->parameters;
	}

	/**
	 * Returns the message format.
	 *
	 * @since 1.21
	 *
	 * @return string
	 * @deprecated since 1.29 formatting is not stateful
	 */
	public function getFormat() {
		wfDeprecated( __METHOD__, '1.29' );
		return $this->format;
	}

	/**
	 * Returns the Language of the Message.
	 *
	 * @since 1.23
	 *
	 * @return Language
	 */
	public function getLanguage() {
		// Defaults to false which means current user language
		return $this->language ?: RequestContext::getMain()->getLanguage();
	}

	/**
	 * Factory function that is just wrapper for the real constructor. It is
	 * intended to be used instead of the real constructor, because it allows
	 * chaining method calls, while new objects don't.
	 *
	 * @since 1.17
	 *
	 * @param string|string[]|MessageSpecifier $key
	 * @param mixed $param,... Parameters as strings.
	 *
	 * @return Message
	 */
	public static function newFromKey( $key /*...*/ ) {
		$params = func_get_args();
		array_shift( $params );
		return new self( $key, $params );
	}

	/**
	 * Transform a MessageSpecifier or a primitive value used interchangeably with
	 * specifiers (a message key string, or a key + params array) into a proper Message.
	 *
	 * Also accepts a MessageSpecifier inside an array: that's not considered a valid format
	 * but is an easy error to make due to how StatusValue stores messages internally.
	 * Further array elements are ignored in that case.
	 *
	 * @param string|array|MessageSpecifier $value
	 * @return Message
	 * @throws InvalidArgumentException
	 * @since 1.27
	 */
	public static function newFromSpecifier( $value ) {
		$params = [];
		if ( is_array( $value ) ) {
			$params = $value;
			$value = array_shift( $params );
		}

		if ( $value instanceof Message ) { // Message, RawMessage, ApiMessage, etc
			$message = clone( $value );
		} elseif ( $value instanceof MessageSpecifier ) {
			$message = new Message( $value );
		} elseif ( is_string( $value ) ) {
			$message = new Message( $value, $params );
		} else {
			throw new InvalidArgumentException( __METHOD__ . ': invalid argument type '
				. gettype( $value ) );
		}

		return $message;
	}

	/**
	 * Factory function accepting multiple message keys and returning a message instance
	 * for the first message which is non-empty. If all messages are empty then an
	 * instance of the first message key is returned.
	 *
	 * @since 1.18
	 *
	 * @param string|string[] $keys,... Message keys, or first argument as an array of all the
	 * message keys.
	 *
	 * @return Message
	 */
	public static function newFallbackSequence( /*...*/ ) {
		$keys = func_get_args();
		if ( func_num_args() == 1 ) {
			if ( is_array( $keys[0] ) ) {
				// Allow an array to be passed as the first argument instead
				$keys = array_values( $keys[0] );
			} else {
				// Optimize a single string to not need special fallback handling
				$keys = $keys[0];
			}
		}
		return new self( $keys );
	}

	/**
	 * Get a title object for a mediawiki message, where it can be found in the mediawiki namespace.
	 * The title will be for the current language, if the message key is in
	 * $wgForceUIMsgAsContentMsg it will be append with the language code (except content
	 * language), because Message::inContentLanguage will also return in user language.
	 *
	 * @see $wgForceUIMsgAsContentMsg
	 * @return Title
	 * @since 1.26
	 */
	public function getTitle() {
		global $wgContLang, $wgForceUIMsgAsContentMsg;

		$title = $this->key;
		if (
			!$this->language->equals( $wgContLang )
			&& in_array( $this->key, (array)$wgForceUIMsgAsContentMsg )
		) {
			$code = $this->language->getCode();
			$title .= '/' . $code;
		}

		return Title::makeTitle( NS_MEDIAWIKI, $wgContLang->ucfirst( strtr( $title, ' ', '_' ) ) );
	}

	/**
	 * Adds parameters to the parameter list of this message.
	 *
	 * @since 1.17
	 *
	 * @param mixed ... Parameters as strings or arrays from
	 *  Message::numParam() and the like, or a single array of parameters.
	 *
	 * @return Message $this
	 */
	public function params( /*...*/ ) {
		$args = func_get_args();

		// If $args has only one entry and it's an array, then it's either a
		// non-varargs call or it happens to be a call with just a single
		// "special" parameter. Since the "special" parameters don't have any
		// numeric keys, we'll test that to differentiate the cases.
		if ( count( $args ) === 1 && isset( $args[0] ) && is_array( $args[0] ) ) {
			if ( $args[0] === [] ) {
				$args = [];
			} else {
				foreach ( $args[0] as $key => $value ) {
					if ( is_int( $key ) ) {
						$args = $args[0];
						break;
					}
				}
			}
		}

		$this->parameters = array_merge( $this->parameters, array_values( $args ) );
		return $this;
	}

	/**
	 * Add parameters that are substituted after parsing or escaping.
	 * In other words the parsing process cannot access the contents
	 * of this type of parameter, and you need to make sure it is
	 * sanitized beforehand.  The parser will see "$n", instead.
	 *
	 * @since 1.17
	 *
	 * @param mixed $params,... Raw parameters as strings, or a single argument that is
	 * an array of raw parameters.
	 *
	 * @return Message $this
	 */
	public function rawParams( /*...*/ ) {
		$params = func_get_args();
		if ( isset( $params[0] ) && is_array( $params[0] ) ) {
			$params = $params[0];
		}
		foreach ( $params as $param ) {
			$this->parameters[] = self::rawParam( $param );
		}
		return $this;
	}

	/**
	 * Add parameters that are numeric and will be passed through
	 * Language::formatNum before substitution
	 *
	 * @since 1.18
	 *
	 * @param mixed $param,... Numeric parameters, or a single argument that is
	 * an array of numeric parameters.
	 *
	 * @return Message $this
	 */
	public function numParams( /*...*/ ) {
		$params = func_get_args();
		if ( isset( $params[0] ) && is_array( $params[0] ) ) {
			$params = $params[0];
		}
		foreach ( $params as $param ) {
			$this->parameters[] = self::numParam( $param );
		}
		return $this;
	}

	/**
	 * Add parameters that are durations of time and will be passed through
	 * Language::formatDuration before substitution
	 *
	 * @since 1.22
	 *
	 * @param int|int[] $param,... Duration parameters, or a single argument that is
	 * an array of duration parameters.
	 *
	 * @return Message $this
	 */
	public function durationParams( /*...*/ ) {
		$params = func_get_args();
		if ( isset( $params[0] ) && is_array( $params[0] ) ) {
			$params = $params[0];
		}
		foreach ( $params as $param ) {
			$this->parameters[] = self::durationParam( $param );
		}
		return $this;
	}

	/**
	 * Add parameters that are expiration times and will be passed through
	 * Language::formatExpiry before substitution
	 *
	 * @since 1.22
	 *
	 * @param string|string[] $param,... Expiry parameters, or a single argument that is
	 * an array of expiry parameters.
	 *
	 * @return Message $this
	 */
	public function expiryParams( /*...*/ ) {
		$params = func_get_args();
		if ( isset( $params[0] ) && is_array( $params[0] ) ) {
			$params = $params[0];
		}
		foreach ( $params as $param ) {
			$this->parameters[] = self::expiryParam( $param );
		}
		return $this;
	}

	/**
	 * Add parameters that are time periods and will be passed through
	 * Language::formatTimePeriod before substitution
	 *
	 * @since 1.22
	 *
	 * @param int|int[] $param,... Time period parameters, or a single argument that is
	 * an array of time period parameters.
	 *
	 * @return Message $this
	 */
	public function timeperiodParams( /*...*/ ) {
		$params = func_get_args();
		if ( isset( $params[0] ) && is_array( $params[0] ) ) {
			$params = $params[0];
		}
		foreach ( $params as $param ) {
			$this->parameters[] = self::timeperiodParam( $param );
		}
		return $this;
	}

	/**
	 * Add parameters that are file sizes and will be passed through
	 * Language::formatSize before substitution
	 *
	 * @since 1.22
	 *
	 * @param int|int[] $param,... Size parameters, or a single argument that is
	 * an array of size parameters.
	 *
	 * @return Message $this
	 */
	public function sizeParams( /*...*/ ) {
		$params = func_get_args();
		if ( isset( $params[0] ) && is_array( $params[0] ) ) {
			$params = $params[0];
		}
		foreach ( $params as $param ) {
			$this->parameters[] = self::sizeParam( $param );
		}
		return $this;
	}

	/**
	 * Add parameters that are bitrates and will be passed through
	 * Language::formatBitrate before substitution
	 *
	 * @since 1.22
	 *
	 * @param int|int[] $param,... Bit rate parameters, or a single argument that is
	 * an array of bit rate parameters.
	 *
	 * @return Message $this
	 */
	public function bitrateParams( /*...*/ ) {
		$params = func_get_args();
		if ( isset( $params[0] ) && is_array( $params[0] ) ) {
			$params = $params[0];
		}
		foreach ( $params as $param ) {
			$this->parameters[] = self::bitrateParam( $param );
		}
		return $this;
	}

	/**
	 * Add parameters that are plaintext and will be passed through without
	 * the content being evaluated.  Plaintext parameters are not valid as
	 * arguments to parser functions. This differs from self::rawParams in
	 * that the Message class handles escaping to match the output format.
	 *
	 * @since 1.25
	 *
	 * @param string|string[] $param,... plaintext parameters, or a single argument that is
	 * an array of plaintext parameters.
	 *
	 * @return Message $this
	 */
	public function plaintextParams( /*...*/ ) {
		$params = func_get_args();
		if ( isset( $params[0] ) && is_array( $params[0] ) ) {
			$params = $params[0];
		}
		foreach ( $params as $param ) {
			$this->parameters[] = self::plaintextParam( $param );
		}
		return $this;
	}

	/**
	 * Set the language and the title from a context object
	 *
	 * @since 1.19
	 *
	 * @param IContextSource $context
	 *
	 * @return Message $this
	 */
	public function setContext( IContextSource $context ) {
		$this->inLanguage( $context->getLanguage() );
		$this->title( $context->getTitle() );
		$this->interface = true;

		return $this;
	}

	/**
	 * Request the message in any language that is supported.
	 *
	 * As a side effect interface message status is unconditionally
	 * turned off.
	 *
	 * @since 1.17
	 * @param Language|string $lang Language code or Language object.
	 * @return Message $this
	 * @throws MWException
	 */
	public function inLanguage( $lang ) {
		if ( $lang instanceof Language ) {
			$this->language = $lang;
		} elseif ( is_string( $lang ) ) {
			if ( !$this->language instanceof Language || $this->language->getCode() != $lang ) {
				$this->language = Language::factory( $lang );
			}
		} elseif ( $lang instanceof StubUserLang ) {
			$this->language = false;
		} else {
			$type = gettype( $lang );
			throw new MWException( __METHOD__ . " must be "
				. "passed a String or Language object; $type given"
			);
		}
		$this->message = null;
		$this->interface = false;
		return $this;
	}

	/**
	 * Request the message in the wiki's content language,
	 * unless it is disabled for this message.
	 *
	 * @since 1.17
	 * @see $wgForceUIMsgAsContentMsg
	 *
	 * @return Message $this
	 */
	public function inContentLanguage() {
		global $wgForceUIMsgAsContentMsg;
		if ( in_array( $this->key, (array)$wgForceUIMsgAsContentMsg ) ) {
			return $this;
		}

		global $wgContLang;
		$this->inLanguage( $wgContLang );
		return $this;
	}

	/**
	 * Allows manipulating the interface message flag directly.
	 * Can be used to restore the flag after setting a language.
	 *
	 * @since 1.20
	 *
	 * @param bool $interface
	 *
	 * @return Message $this
	 */
	public function setInterfaceMessageFlag( $interface ) {
		$this->interface = (bool)$interface;
		return $this;
	}

	/**
	 * Enable or disable database use.
	 *
	 * @since 1.17
	 *
	 * @param bool $useDatabase
	 *
	 * @return Message $this
	 */
	public function useDatabase( $useDatabase ) {
		$this->useDatabase = (bool)$useDatabase;
		$this->message = null;
		return $this;
	}

	/**
	 * Set the Title object to use as context when transforming the message
	 *
	 * @since 1.18
	 *
	 * @param Title $title
	 *
	 * @return Message $this
	 */
	public function title( $title ) {
		$this->title = $title;
		return $this;
	}

	/**
	 * Returns the message as a Content object.
	 *
	 * @return Content
	 */
	public function content() {
		if ( !$this->content ) {
			$this->content = new MessageContent( $this );
		}

		return $this->content;
	}

	/**
	 * Returns the message parsed from wikitext to HTML.
	 *
	 * @since 1.17
	 *
	 * @param string|null $format One of the FORMAT_* constants. Null means use whatever was used
	 *   the last time (this is for B/C and should be avoided).
	 *
	 * @return string HTML
	 */
	public function toString( $format = null ) {
		if ( $format === null ) {
			$ex = new LogicException( __METHOD__ . ' using implicit format: ' . $this->format );
			\MediaWiki\Logger\LoggerFactory::getInstance( 'message-format' )->warning(
				$ex->getMessage(), [ 'exception' => $ex, 'format' => $this->format, 'key' => $this->key ] );
			$format = $this->format;
		}
		$string = $this->fetchMessage();

		if ( $string === false ) {
			// Err on the side of safety, ensure that the output
			// is always html safe in the event the message key is
			// missing, since in that case its highly likely the
			// message key is user-controlled.
			// '⧼' is used instead of '<' to side-step any
			// double-escaping issues.
			// (Keep synchronised with mw.Message#toString in JS.)
			return '⧼' . htmlspecialchars( $this->key ) . '⧽';
		}

		# Replace $* with a list of parameters for &uselang=qqx.
		if ( strpos( $string, '$*' ) !== false ) {
			$paramlist = '';
			if ( $this->parameters !== [] ) {
				$paramlist = ': $' . implode( ', $', range( 1, count( $this->parameters ) ) );
			}
			$string = str_replace( '$*', $paramlist, $string );
		}

		# Replace parameters before text parsing
		$string = $this->replaceParameters( $string, 'before', $format );

		# Maybe transform using the full parser
		if ( $format === self::FORMAT_PARSE ) {
			$string = $this->parseText( $string );
			$string = Parser::stripOuterParagraph( $string );
		} elseif ( $format === self::FORMAT_BLOCK_PARSE ) {
			$string = $this->parseText( $string );
		} elseif ( $format === self::FORMAT_TEXT ) {
			$string = $this->transformText( $string );
		} elseif ( $format === self::FORMAT_ESCAPED ) {
			$string = $this->transformText( $string );
			$string = htmlspecialchars( $string, ENT_QUOTES, 'UTF-8', false );
		}

		# Raw parameter replacement
		$string = $this->replaceParameters( $string, 'after', $format );

		return $string;
	}

	/**
	 * Magic method implementation of the above (for PHP >= 5.2.0), so we can do, eg:
	 *     $foo = new Message( $key );
	 *     $string = "<abbr>$foo</abbr>";
	 *
	 * @since 1.18
	 *
	 * @return string
	 */
	public function __toString() {
		// PHP doesn't allow __toString to throw exceptions and will
		// trigger a fatal error if it does. So, catch any exceptions.

		try {
			return $this->toString( self::FORMAT_PARSE );
		} catch ( Exception $ex ) {
			try {
				trigger_error( "Exception caught in " . __METHOD__ . " (message " . $this->key . "): "
					. $ex, E_USER_WARNING );
			} catch ( Exception $ex ) {
				// Doh! Cause a fatal error after all?
			}

			return '⧼' . htmlspecialchars( $this->key ) . '⧽';
		}
	}

	/**
	 * Fully parse the text from wikitext to HTML.
	 *
	 * @since 1.17
	 *
	 * @return string Parsed HTML.
	 */
	public function parse() {
		$this->format = self::FORMAT_PARSE;
		return $this->toString( self::FORMAT_PARSE );
	}

	/**
	 * Returns the message text. {{-transformation is done.
	 *
	 * @since 1.17
	 *
	 * @return string Unescaped message text.
	 */
	public function text() {
		$this->format = self::FORMAT_TEXT;
		return $this->toString( self::FORMAT_TEXT );
	}

	/**
	 * Returns the message text as-is, only parameters are substituted.
	 *
	 * @since 1.17
	 *
	 * @return string Unescaped untransformed message text.
	 */
	public function plain() {
		$this->format = self::FORMAT_PLAIN;
		return $this->toString( self::FORMAT_PLAIN );
	}

	/**
	 * Returns the parsed message text which is always surrounded by a block element.
	 *
	 * @since 1.17
	 *
	 * @return string HTML
	 */
	public function parseAsBlock() {
		$this->format = self::FORMAT_BLOCK_PARSE;
		return $this->toString( self::FORMAT_BLOCK_PARSE );
	}

	/**
	 * Returns the message text. {{-transformation is done and the result
	 * is escaped excluding any raw parameters.
	 *
	 * @since 1.17
	 *
	 * @return string Escaped message text.
	 */
	public function escaped() {
		$this->format = self::FORMAT_ESCAPED;
		return $this->toString( self::FORMAT_ESCAPED );
	}

	/**
	 * Check whether a message key has been defined currently.
	 *
	 * @since 1.17
	 *
	 * @return bool
	 */
	public function exists() {
		return $this->fetchMessage() !== false;
	}

	/**
	 * Check whether a message does not exist, or is an empty string
	 *
	 * @since 1.18
	 * @todo FIXME: Merge with isDisabled()?
	 *
	 * @return bool
	 */
	public function isBlank() {
		$message = $this->fetchMessage();
		return $message === false || $message === '';
	}

	/**
	 * Check whether a message does not exist, is an empty string, or is "-".
	 *
	 * @since 1.18
	 *
	 * @return bool
	 */
	public function isDisabled() {
		$message = $this->fetchMessage();
		return $message === false || $message === '' || $message === '-';
	}

	/**
	 * @since 1.17
	 *
	 * @param mixed $raw
	 *
	 * @return array Array with a single "raw" key.
	 */
	public static function rawParam( $raw ) {
		return [ 'raw' => $raw ];
	}

	/**
	 * @since 1.18
	 *
	 * @param mixed $num
	 *
	 * @return array Array with a single "num" key.
	 */
	public static function numParam( $num ) {
		return [ 'num' => $num ];
	}

	/**
	 * @since 1.22
	 *
	 * @param int $duration
	 *
	 * @return int[] Array with a single "duration" key.
	 */
	public static function durationParam( $duration ) {
		return [ 'duration' => $duration ];
	}

	/**
	 * @since 1.22
	 *
	 * @param string $expiry
	 *
	 * @return string[] Array with a single "expiry" key.
	 */
	public static function expiryParam( $expiry ) {
		return [ 'expiry' => $expiry ];
	}

	/**
	 * @since 1.22
	 *
	 * @param int $period
	 *
	 * @return int[] Array with a single "period" key.
	 */
	public static function timeperiodParam( $period ) {
		return [ 'period' => $period ];
	}

	/**
	 * @since 1.22
	 *
	 * @param int $size
	 *
	 * @return int[] Array with a single "size" key.
	 */
	public static function sizeParam( $size ) {
		return [ 'size' => $size ];
	}

	/**
	 * @since 1.22
	 *
	 * @param int $bitrate
	 *
	 * @return int[] Array with a single "bitrate" key.
	 */
	public static function bitrateParam( $bitrate ) {
		return [ 'bitrate' => $bitrate ];
	}

	/**
	 * @since 1.25
	 *
	 * @param string $plaintext
	 *
	 * @return string[] Array with a single "plaintext" key.
	 */
	public static function plaintextParam( $plaintext ) {
		return [ 'plaintext' => $plaintext ];
	}

	/**
	 * @since 1.29
	 *
	 * @param array $list
	 * @param string $type 'comma', 'semicolon', 'pipe', 'text'
	 * @return array Array with "list" and "type" keys.
	 */
	public static function listParam( array $list, $type = 'text' ) {
		if ( !isset( self::$listTypeMap[$type] ) ) {
			throw new InvalidArgumentException(
				"Invalid type '$type'. Known types are: " . join( ', ', array_keys( self::$listTypeMap ) )
			);
		}
		return [ 'list' => $list, 'type' => $type ];
	}

	/**
	 * Substitutes any parameters into the message text.
	 *
	 * @since 1.17
	 *
	 * @param string $message The message text.
	 * @param string $type Either "before" or "after".
	 * @param string $format One of the FORMAT_* constants.
	 *
	 * @return string
	 */
	protected function replaceParameters( $message, $type = 'before', $format ) {
		$replacementKeys = [];
		foreach ( $this->parameters as $n => $param ) {
			list( $paramType, $value ) = $this->extractParam( $param, $format );
			if ( $type === $paramType ) {
				$replacementKeys['$' . ( $n + 1 )] = $value;
			}
		}
		$message = strtr( $message, $replacementKeys );
		return $message;
	}

	/**
	 * Extracts the parameter type and preprocessed the value if needed.
	 *
	 * @since 1.18
	 *
	 * @param mixed $param Parameter as defined in this class.
	 * @param string $format One of the FORMAT_* constants.
	 *
	 * @return array Array with the parameter type (either "before" or "after") and the value.
	 */
	protected function extractParam( $param, $format ) {
		if ( is_array( $param ) ) {
			if ( isset( $param['raw'] ) ) {
				return [ 'after', $param['raw'] ];
			} elseif ( isset( $param['num'] ) ) {
				// Replace number params always in before step for now.
				// No support for combined raw and num params
				return [ 'before', $this->getLanguage()->formatNum( $param['num'] ) ];
			} elseif ( isset( $param['duration'] ) ) {
				return [ 'before', $this->getLanguage()->formatDuration( $param['duration'] ) ];
			} elseif ( isset( $param['expiry'] ) ) {
				return [ 'before', $this->getLanguage()->formatExpiry( $param['expiry'] ) ];
			} elseif ( isset( $param['period'] ) ) {
				return [ 'before', $this->getLanguage()->formatTimePeriod( $param['period'] ) ];
			} elseif ( isset( $param['size'] ) ) {
				return [ 'before', $this->getLanguage()->formatSize( $param['size'] ) ];
			} elseif ( isset( $param['bitrate'] ) ) {
				return [ 'before', $this->getLanguage()->formatBitrate( $param['bitrate'] ) ];
			} elseif ( isset( $param['plaintext'] ) ) {
				return [ 'after', $this->formatPlaintext( $param['plaintext'], $format ) ];
			} elseif ( isset( $param['list'] ) ) {
				return $this->formatListParam( $param['list'], $param['type'], $format );
			} else {
				$warning = 'Invalid parameter for message "' . $this->getKey() . '": ' .
					htmlspecialchars( serialize( $param ) );
				trigger_error( $warning, E_USER_WARNING );
				$e = new Exception;
				wfDebugLog( 'Bug58676', $warning . "\n" . $e->getTraceAsString() );

				return [ 'before', '[INVALID]' ];
			}
		} elseif ( $param instanceof Message ) {
			// Match language, flags, etc. to the current message.
			$msg = clone $param;
			if ( $msg->language !== $this->language || $msg->useDatabase !== $this->useDatabase ) {
				// Cache depends on these parameters
				$msg->message = null;
			}
			$msg->interface = $this->interface;
			$msg->language = $this->language;
			$msg->useDatabase = $this->useDatabase;
			$msg->title = $this->title;

			// DWIM
			if ( $format === 'block-parse' ) {
				$format = 'parse';
			}
			$msg->format = $format;

			// Message objects should not be before parameters because
			// then they'll get double escaped. If the message needs to be
			// escaped, it'll happen right here when we call toString().
			return [ 'after', $msg->toString( $format ) ];
		} else {
			return [ 'before', $param ];
		}
	}

	/**
	 * Wrapper for what ever method we use to parse wikitext.
	 *
	 * @since 1.17
	 *
	 * @param string $string Wikitext message contents.
	 *
	 * @return string Wikitext parsed into HTML.
	 */
	protected function parseText( $string ) {
		$out = MessageCache::singleton()->parse(
			$string,
			$this->title,
			/*linestart*/true,
			$this->interface,
			$this->getLanguage()
		);

		return $out instanceof ParserOutput ? $out->getText() : $out;
	}

	/**
	 * Wrapper for what ever method we use to {{-transform wikitext.
	 *
	 * @since 1.17
	 *
	 * @param string $string Wikitext message contents.
	 *
	 * @return string Wikitext with {{-constructs replaced with their values.
	 */
	protected function transformText( $string ) {
		return MessageCache::singleton()->transform(
			$string,
			$this->interface,
			$this->getLanguage(),
			$this->title
		);
	}

	/**
	 * Wrapper for what ever method we use to get message contents.
	 *
	 * @since 1.17
	 *
	 * @return string
	 * @throws MWException If message key array is empty.
	 */
	protected function fetchMessage() {
		if ( $this->message === null ) {
			$cache = MessageCache::singleton();

			foreach ( $this->keysToTry as $key ) {
				$message = $cache->get( $key, $this->useDatabase, $this->getLanguage() );
				if ( $message !== false && $message !== '' ) {
					break;
				}
			}

			// NOTE: The constructor makes sure keysToTry isn't empty,
			//       so we know that $key and $message are initialized.
			$this->key = $key;
			$this->message = $message;
		}
		return $this->message;
	}

	/**
	 * Formats a message parameter wrapped with 'plaintext'. Ensures that
	 * the entire string is displayed unchanged when displayed in the output
	 * format.
	 *
	 * @since 1.25
	 *
	 * @param string $plaintext String to ensure plaintext output of
	 * @param string $format One of the FORMAT_* constants.
	 *
	 * @return string Input plaintext encoded for output to $format
	 */
	protected function formatPlaintext( $plaintext, $format ) {
		switch ( $format ) {
		case self::FORMAT_TEXT:
		case self::FORMAT_PLAIN:
			return $plaintext;

		case self::FORMAT_PARSE:
		case self::FORMAT_BLOCK_PARSE:
		case self::FORMAT_ESCAPED:
		default:
			return htmlspecialchars( $plaintext, ENT_QUOTES );

		}
	}

	/**
	 * Formats a list of parameters as a concatenated string.
	 * @since 1.29
	 * @param array $params
	 * @param string $listType
	 * @param string $format One of the FORMAT_* constants.
	 * @return array Array with the parameter type (either "before" or "after") and the value.
	 */
	protected function formatListParam( array $params, $listType, $format ) {
		if ( !isset( self::$listTypeMap[$listType] ) ) {
			$warning = 'Invalid list type for message "' . $this->getKey() . '": '
				. htmlspecialchars( $listType )
				. ' (params are ' . htmlspecialchars( serialize( $params ) ) . ')';
			trigger_error( $warning, E_USER_WARNING );
			$e = new Exception;
			wfDebugLog( 'Bug58676', $warning . "\n" . $e->getTraceAsString() );
			return [ 'before', '[INVALID]' ];
		}
		$func = self::$listTypeMap[$listType];

		// Handle an empty list sensibly
		if ( !$params ) {
			return [ 'before', $this->getLanguage()->$func( [] ) ];
		}

		// First, determine what kinds of list items we have
		$types = [];
		$vars = [];
		$list = [];
		foreach ( $params as $n => $p ) {
			list( $type, $value ) = $this->extractParam( $p, $format );
			$types[$type] = true;
			$list[] = $value;
			$vars[] = '$' . ( $n + 1 );
		}

		// Easy case: all are 'before' or 'after', so just join the
		// values and use the same type.
		if ( count( $types ) === 1 ) {
			return [ key( $types ), $this->getLanguage()->$func( $list ) ];
		}

		// Hard case: We need to process each value per its type, then
		// return the concatenated values as 'after'. We handle this by turning
		// the list into a RawMessage and processing that as a parameter.
		$vars = $this->getLanguage()->$func( $vars );
		return $this->extractParam( new RawMessage( $vars, $params ), $format );
	}
}

/**
 * Variant of the Message class.
 *
 * Rather than treating the message key as a lookup
 * value (which is passed to the MessageCache and
 * translated as necessary), a RawMessage key is
 * treated as the actual message.
 *
 * All other functionality (parsing, escaping, etc.)
 * is preserved.
 *
 * @since 1.21
 */
class RawMessage extends Message {

	/**
	 * Call the parent constructor, then store the key as
	 * the message.
	 *
	 * @see Message::__construct
	 *
	 * @param string $text Message to use.
	 * @param array $params Parameters for the message.
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct( $text, $params = [] ) {
		if ( !is_string( $text ) ) {
			throw new InvalidArgumentException( '$text must be a string' );
		}

		parent::__construct( $text, $params );

		// The key is the message.
		$this->message = $text;
	}

	/**
	 * Fetch the message (in this case, the key).
	 *
	 * @return string
	 */
	public function fetchMessage() {
		// Just in case the message is unset somewhere.
		if ( $this->message === null ) {
			$this->message = $this->key;
		}

		return $this->message;
	}

}
