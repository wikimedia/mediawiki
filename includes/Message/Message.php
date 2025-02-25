<?php
/**
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

namespace MediaWiki\Message;

use Content;
use InvalidArgumentException;
use Language;
use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\Language\RawMessage;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Parser\Parser;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\StubObject\StubUserLang;
use MediaWiki\Title\Title;
use MessageContent;
use MessageSpecifier;
use RuntimeException;
use Serializable;
use Stringable;
use Wikimedia\Assert\Assert;

/**
 * The Message class deals with fetching and processing of interface message
 * into a variety of formats.
 *
 * First implemented with MediaWiki 1.17, the Message class is intended to
 * replace the old wfMsg* functions that over time grew unusable.
 * @see https://www.mediawiki.org/wiki/Manual:Messages_API for equivalences
 * between old and new functions.
 *
 * The preferred way to create Message objects is via the msg() method of
 * of an available RequestContext and ResourceLoader Context object; this will
 * ensure that the message uses the correct language. When that is not
 * possible, the wfMessage() global function can be used, which will cause
 * Message to get the language from the global RequestContext object. In
 * rare circumstances when sessions are not available or not initialized,
 * that can lead to errors.
 *
 * The most basic usage cases would be:
 *
 * @code
 *     // Initialize a Message object using the 'some_key' message key
 *     $message = $context->msg( 'some_key' );
 *
 *     // Using two parameters those values are strings 'value1' and 'value2':
 *     $message = $context->msg( 'some_key',
 *          'value1', 'value2'
 *     );
 * @endcode
 *
 * @section message_global_fn Global function wrapper:
 *
 * Since msg() returns a Message instance, you can chain its call with a method.
 * Some of them return a Message instance too so you can chain them.
 * You will find below several examples of msg() usage.
 *
 * Fetching a message text for interface message:
 *
 * @code
 *    $button = Xml::button(
 *         $context->msg( 'submit' )->text()
 *    );
 * @endcode
 *
 * A Message instance can be passed parameters after it has been constructed,
 * use the params() method to do so:
 *
 * @code
 *     $context->msg( 'welcome-to' )
 *         ->params( $wgSitename )
 *         ->text();
 * @endcode
 *
 * {{GRAMMAR}} and friends work correctly:
 *
 * @code
 *    $context->msg( 'are-friends',
 *        $user, $friend
 *    );
 *    $context->msg( 'bad-message' )
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
 *    $context->msg( 'mysterious-message' )->exists()
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
 * @newable
 * @ingroup Language
 */
class Message implements MessageSpecifier, Serializable {
	/** Use message text as-is */
	public const FORMAT_PLAIN = 'plain';
	/** Use normal wikitext -> HTML parsing (the result will be wrapped in a block-level HTML tag) */
	public const FORMAT_BLOCK_PARSE = 'block-parse';
	/** Use normal wikitext -> HTML parsing but strip the block-level wrapper */
	public const FORMAT_PARSE = 'parse';
	/** Transform {{..}} constructs but don't transform to HTML */
	public const FORMAT_TEXT = 'text';
	/** Transform {{..}} constructs, HTML-escape the result */
	public const FORMAT_ESCAPED = 'escaped';

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
	 * @var Language|null Explicit language object, or null for user language
	 */
	protected $language = null;

	/**
	 * @var callable|null A callable which returns the current user language,
	 *   or null to get it from global state.
	 */
	protected $userLangCallback;

	/**
	 * @var string The message key. If $keysToTry has more than one element,
	 * this may change to one of the keys to try when fetching the message text.
	 */
	protected $key;

	/**
	 * @var string[] List of keys to try when fetching the message.
	 * @phan-var non-empty-list<string>
	 */
	protected $keysToTry;

	/**
	 * @var array List of parameters which will be substituted into the message.
	 */
	protected $parameters = [];

	/**
	 * @var bool If messages in the local MediaWiki namespace should be loaded; false to use only
	 *  the compiled LocalisationCache
	 */
	protected $useDatabase = true;

	/**
	 * @var ?PageReference page object to use as context.
	 */
	protected $contextPage = null;

	/**
	 * @var Content|null Content object representing the message.
	 */
	protected $content = null;

	/**
	 * @var string|null|false
	 */
	protected $message;

	/**
	 * @stable to call
	 * @since 1.17
	 * @param string|string[]|MessageSpecifier $key Message key, or array of
	 * message keys to try and use the first non-empty message for, or a
	 * MessageSpecifier to copy from.
	 * @param array $params Message parameters.
	 * @param Language|null $language [optional] Language to use (defaults to current user language).
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

		if ( is_string( $key ) ) {
			$this->keysToTry = [ $key ];
			$this->key = $key;
		} elseif ( is_array( $key ) ) {
			if ( !$key ) {
				throw new InvalidArgumentException( '$key must not be an empty list' );
			}
			$this->keysToTry = $key;
			$this->key = reset( $this->keysToTry );
		} else {
			throw new InvalidArgumentException( '$key must be a string or an array' );
		}

		$this->parameters = array_values( $params );
		// User language is only resolved in getLanguage(). This helps preserve the
		// semantic intent of "user language" across serialize() and unserialize().
		$this->language = $language;
	}

	/**
	 * @see Serializable::serialize()
	 * @since 1.26
	 * @return string
	 */
	public function serialize(): string {
		return serialize( $this->__serialize() );
	}

	/**
	 * @see Serializable::serialize()
	 * @since 1.38
	 * @return array
	 */
	public function __serialize() {
		return [
			'interface' => $this->interface,
			'language' => $this->language ? $this->language->getCode() : null,
			'key' => $this->key,
			'keysToTry' => $this->keysToTry,
			'parameters' => $this->parameters,
			'useDatabase' => $this->useDatabase,
			// Optimisation: Avoid cost of TitleFormatter on serialize,
			// and especially cost of TitleParser (via Title::newFromText)
			// on retrieval.
			'titlevalue' => ( $this->contextPage
				? [ 0 => $this->contextPage->getNamespace(), 1 => $this->contextPage->getDBkey() ]
				: null
			),
		];
	}

	/**
	 * @see Serializable::unserialize()
	 * @since 1.38
	 * @param string $serialized
	 */
	public function unserialize( $serialized ): void {
		$this->__unserialize( unserialize( $serialized ) );
	}

	/**
	 * @see Serializable::unserialize()
	 * @since 1.26
	 * @param array $data
	 */
	public function __unserialize( $data ) {
		if ( !is_array( $data ) ) {
			throw new InvalidArgumentException( __METHOD__ . ': Invalid serialized data' );
		}
		$this->interface = $data['interface'];
		$this->key = $data['key'];
		$this->keysToTry = $data['keysToTry'];
		$this->parameters = $data['parameters'];
		$this->useDatabase = $data['useDatabase'];
		$this->language = $data['language']
			? MediaWikiServices::getInstance()->getLanguageFactory()
				->getLanguage( $data['language'] )
			: null;

		// Since 1.35, the key 'titlevalue' is set, instead of 'titlestr'.
		if ( isset( $data['titlevalue'] ) ) {
			$this->contextPage = new PageReferenceValue(
				$data['titlevalue'][0],
				$data['titlevalue'][1],
				PageReference::LOCAL
			);
		} elseif ( isset( $data['titlestr'] ) ) {
			// TODO: figure out what's needed to remove this codepath
			$this->contextPage = Title::newFromText( $data['titlestr'] );
		} else {
			$this->contextPage = null;
		}
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
	 * Returns the Language of the Message.
	 *
	 * @since 1.23
	 *
	 * @return Language
	 */
	public function getLanguage() {
		// Defaults to null which means current user language
		if ( $this->language !== null ) {
			return $this->language;
		} elseif ( $this->userLangCallback ) {
			return ( $this->userLangCallback )();
		} else {
			return RequestContext::getMain()->getLanguage();
		}
	}

	/**
	 * Factory function that is just wrapper for the real constructor. It is
	 * intended to be used instead of the real constructor, because it allows
	 * chaining method calls, while new objects don't.
	 *
	 * @since 1.17
	 *
	 * @param string|string[]|MessageSpecifier $key
	 * @param mixed ...$params Parameters as strings.
	 *
	 * @return self
	 */
	public static function newFromKey( $key, ...$params ) {
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
	 * When the MessageSpecifier object is an instance of Message, a clone of the object is returned.
	 * This is unlike the `new Message( … )` constructor, which returns a new object constructed from
	 * scratch with the same key. This difference is mostly relevant when the passed object is an
	 * instance of a subclass like RawMessage or ApiMessage.
	 *
	 * @param string|array|MessageSpecifier $value
	 * @param-taint $value tainted
	 * @return self
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
			$message = clone $value;
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
	 * instance of the last message key is returned.
	 *
	 * @since 1.18
	 *
	 * @param string|string[] ...$keys Message keys, or first argument as an array of all the
	 * message keys.
	 * @param-taint ...$keys tainted
	 *
	 * @return self
	 */
	public static function newFallbackSequence( ...$keys ) {
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
		$forceUIMsgAsContentMsg = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::ForceUIMsgAsContentMsg );

		$contLang = MediaWikiServices::getInstance()->getContentLanguage();
		$lang = $this->getLanguage();
		$title = $this->key;
		if (
			!$lang->equals( $contLang )
			&& in_array( $this->key, (array)$forceUIMsgAsContentMsg )
		) {
			$title .= '/' . $lang->getCode();
		}

		return Title::makeTitle(
			NS_MEDIAWIKI, $contLang->ucfirst( strtr( $title, ' ', '_' ) ) );
	}

	/**
	 * Adds parameters to the parameter list of this message.
	 *
	 * @since 1.17
	 *
	 * @param mixed ...$args Parameters as strings or arrays from
	 *  Message::numParam() and the like, or a single array of parameters.
	 *
	 * @return self $this
	 */
	public function params( ...$args ) {
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
	 * @param mixed ...$params Raw parameters as strings, or a single argument that is
	 * an array of raw parameters.
	 * @param-taint ...$params html,exec_html
	 *
	 * @return self $this
	 */
	public function rawParams( ...$params ) {
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
	 * @param mixed ...$params Numeric parameters, or a single argument that is
	 * an array of numeric parameters.
	 *
	 * @return self $this
	 */
	public function numParams( ...$params ) {
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
	 * @param int|int[] ...$params Duration parameters, or a single argument that is
	 * an array of duration parameters.
	 *
	 * @return self $this
	 */
	public function durationParams( ...$params ) {
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
	 * @param string|string[] ...$params Expiry parameters, or a single argument that is
	 * an array of expiry parameters.
	 *
	 * @return self $this
	 */
	public function expiryParams( ...$params ) {
		if ( isset( $params[0] ) && is_array( $params[0] ) ) {
			$params = $params[0];
		}
		foreach ( $params as $param ) {
			$this->parameters[] = self::expiryParam( $param );
		}
		return $this;
	}

	/**
	 * Add parameters that are date-times and will be passed through
	 * Language::timeanddate before substitution
	 *
	 * @since 1.36
	 *
	 * @param string|string[] ...$params Date-time parameters, or a single argument that is
	 * an array of date-time parameters.
	 *
	 * @return self $this
	 */
	public function dateTimeParams( ...$params ) {
		if ( isset( $params[0] ) && is_array( $params[0] ) ) {
			$params = $params[0];
		}
		foreach ( $params as $param ) {
			$this->parameters[] = self::dateTimeParam( $param );
		}
		return $this;
	}

	/**
	 * Add parameters that are dates and will be passed through
	 * Language::date before substitution
	 *
	 * @since 1.36
	 *
	 * @param string|string[] ...$params Date parameters, or a single argument that is
	 * an array of date parameters.
	 *
	 * @return self $this
	 */
	public function dateParams( ...$params ) {
		if ( isset( $params[0] ) && is_array( $params[0] ) ) {
			$params = $params[0];
		}
		foreach ( $params as $param ) {
			$this->parameters[] = self::dateParam( $param );
		}
		return $this;
	}

	/**
	 * Add parameters that represent user groups
	 *
	 * @since 1.38
	 *
	 * @param string|string[] ...$params User Group parameters, or a single argument that is
	 * an array of user group parameters.
	 *
	 * @return self $this
	 */
	public function userGroupParams( ...$params ) {
		if ( isset( $params[0] ) && is_array( $params[0] ) ) {
			$params = $params[0];
		}
		foreach ( $params as $param ) {
			$this->parameters[] = self::userGroupParam( $param );
		}
		return $this;
	}

	/**
	 * Add parameters that represent stringable objects
	 *
	 * @since 1.38
	 *
	 * @param Stringable|Stringable[] ...$params stringable parameters,
	 * or a single argument that is an array of stringable parameters.
	 *
	 * @return self $this
	 */
	public function objectParams( ...$params ) {
		if ( isset( $params[0] ) && is_array( $params[0] ) ) {
			$params = $params[0];
		}
		foreach ( $params as $param ) {
			$this->parameters[] = self::objectParam( $param );
		}
		return $this;
	}

	/**
	 * Add parameters that are times and will be passed through
	 * Language::time before substitution
	 *
	 * @since 1.36
	 *
	 * @param string|string[] ...$params Time parameters, or a single argument that is
	 * an array of time parameters.
	 *
	 * @return self $this
	 */
	public function timeParams( ...$params ) {
		if ( isset( $params[0] ) && is_array( $params[0] ) ) {
			$params = $params[0];
		}
		foreach ( $params as $param ) {
			$this->parameters[] = self::timeParam( $param );
		}
		return $this;
	}

	/**
	 * Add parameters that are time periods and will be passed through
	 * Language::formatTimePeriod before substitution
	 *
	 * @since 1.22
	 *
	 * @param int|float|(int|float)[] ...$params Time period parameters, or a single argument that is
	 * an array of time period parameters.
	 *
	 * @return self $this
	 */
	public function timeperiodParams( ...$params ) {
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
	 * @param int|int[] ...$params Size parameters, or a single argument that is
	 * an array of size parameters.
	 *
	 * @return self $this
	 */
	public function sizeParams( ...$params ) {
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
	 * @param int|int[] ...$params Bit rate parameters, or a single argument that is
	 * an array of bit rate parameters.
	 *
	 * @return self $this
	 */
	public function bitrateParams( ...$params ) {
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
	 * @param string|string[] ...$params plaintext parameters, or a single argument that is
	 * an array of plaintext parameters.
	 *
	 * @return self $this
	 */
	public function plaintextParams( ...$params ) {
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
	 * @return self $this
	 */
	public function setContext( IContextSource $context ) {
		$this->userLangCallback = static function () use ( $context ) {
			return $context->getLanguage();
		};
		$this->inUserLanguage();
		$this->page( $context->getTitle() );

		return $this;
	}

	/**
	 * Request the message in any language that is supported.
	 *
	 * As a side effect interface message status is unconditionally
	 * turned off.
	 *
	 * @since 1.17
	 * @param Language|StubUserLang|string $lang Language code or Language object.
	 * @return self $this
	 */
	public function inLanguage( $lang ) {
		$previousLanguage = $this->language;

		if ( $lang instanceof Language ) {
			$this->language = $lang;
		} elseif ( is_string( $lang ) ) {
			if ( !$this->language instanceof Language || $this->language->getCode() != $lang ) {
				$this->language = MediaWikiServices::getInstance()->getLanguageFactory()
					->getLanguage( $lang );
			}
		} elseif ( $lang instanceof StubUserLang ) {
			$this->language = null;
		} else {
			// Always throws. Moved here as an optimization.
			Assert::parameterType( [ Language::class, StubUserLang::class, 'string' ], $lang, '$lang' );
		}

		if ( $this->language !== $previousLanguage ) {
			// The language has changed. Clear the message cache.
			$this->message = null;
		}
		$this->interface = false;
		return $this;
	}

	/**
	 * Request the message in the user's current language, overriding any
	 * explicit language that was previously set. Set the interface flag to
	 * true.
	 *
	 * @since 1.42
	 * @return $this
	 */
	public function inUserLanguage(): self {
		if ( $this->language ) {
			// The language has changed. Clear the message cache.
			$this->message = null;
		}
		$this->language = null;
		$this->interface = true;
		return $this;
	}

	/**
	 * Request the message in the wiki's content language,
	 * unless it is disabled for this message.
	 *
	 * @since 1.17
	 * @see $wgForceUIMsgAsContentMsg
	 *
	 * @return self $this
	 */
	public function inContentLanguage(): self {
		$forceUIMsgAsContentMsg = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::ForceUIMsgAsContentMsg );
		if ( in_array( $this->key, (array)$forceUIMsgAsContentMsg ) ) {
			return $this;
		}

		$this->inLanguage( MediaWikiServices::getInstance()->getContentLanguage() );
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
	 * @return self $this
	 */
	public function setInterfaceMessageFlag( $interface ) {
		$this->interface = (bool)$interface;
		return $this;
	}

	/**
	 * @since 1.17
	 *
	 * @param bool $useDatabase If messages in the local MediaWiki namespace should be loaded; false
	 *  to use only the compiled LocalisationCache
	 *
	 * @return self $this
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
	 * @deprecated since 1.37. Use ::page instead
	 *
	 * @param Title $title
	 *
	 * @return self $this
	 */
	public function title( $title ) {
		return $this->page( $title );
	}

	/**
	 * Set the page object to use as context when transforming the message
	 *
	 * @since 1.37
	 *
	 * @param ?PageReference $page
	 *
	 * @return self $this
	 */
	public function page( ?PageReference $page ) {
		$this->contextPage = $page;
		return $this;
	}

	/**
	 * Returns the message as a Content object.
	 * @deprecated since 1.38, MessageContent class is hard-deprecated.
	 * @return Content
	 */
	public function content() {
		wfDeprecated( __METHOD__, '1.38' );
		if ( !$this->content ) {
			$this->content = new MessageContent( $this );
		}

		return $this->content;
	}

	/**
	 * Returns the message formatted a certain way.
	 *
	 * @since 1.17
	 * @param string $format One of the FORMAT_* constants.
	 * @return string Text or HTML
	 */
	public function toString( string $format ): string {
		return $this->format( $format );
	}

	/**
	 * Returns the message formatted a certain way.
	 *
	 * @param string $format One of the FORMAT_* constants.
	 * @return string Text or HTML
	 * @suppress SecurityCheck-DoubleEscaped phan false positive
	 */
	private function format( string $format ): string {
		$string = $this->fetchMessage();

		if ( $string === false ) {
			// Err on the side of safety, ensure that the output
			// is always html safe in the event the message key is
			// missing, since in that case its highly likely the
			// message key is user-controlled.
			// '⧼' is used instead of '<' to side-step any
			// double-escaping issues.
			// (Keep synchronised with mw.Message#toString in JS.)
			return '⧼' . Sanitizer::escapeCombiningChar( htmlspecialchars( $this->key ) ) . '⧽';
		}

		if ( in_array( $this->getLanguage()->getCode(), [ 'qqx', 'x-xss' ] ) ) {
			# Insert a list of alternative message keys for &uselang=qqx.
			if ( $string === '($*)' ) {
				$keylist = implode( ' / ', $this->keysToTry );
				$string = "($keylist$*)";
			}
			# Replace $* with a list of parameters for &uselang=qqx.
			if ( strpos( $string, '$*' ) !== false ) {
				$paramlist = '';
				if ( $this->parameters !== [] ) {
					$paramlist = ': $' . implode( ', $', range( 1, count( $this->parameters ) ) );
				}
				$string = str_replace( '$*', $paramlist, $string );
			}
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
			$string = Sanitizer::escapeCombiningChar( $string );
		}

		# Raw parameter replacement
		$string = $this->replaceParameters( $string, 'after', $format );

		return $string;
	}

	/**
	 * Magic method implementation of the above, so we can do, eg:
	 *     $foo = new Message( $key );
	 *     $string = "<abbr>$foo</abbr>";
	 *
	 * @since 1.18
	 *
	 * @return string
	 * @return-taint escaped
	 */
	public function __toString() {
		return $this->format( self::FORMAT_PARSE );
	}

	/**
	 * Fully parse the text from wikitext to HTML.
	 *
	 * @since 1.17
	 *
	 * @return string Parsed HTML.
	 * @return-taint escaped
	 */
	public function parse() {
		return $this->format( self::FORMAT_PARSE );
	}

	/**
	 * Returns the message text. {{-transformation occurs (substituting the template
	 * with its parsed result).
	 *
	 * @since 1.17
	 *
	 * @return string Unescaped message text.
	 * @return-taint tainted
	 */
	public function text() {
		return $this->format( self::FORMAT_TEXT );
	}

	/**
	 * Returns the message text as-is, only parameters are substituted.
	 *
	 * @since 1.17
	 *
	 * @return string Unescaped untransformed message text.
	 * @return-taint tainted
	 */
	public function plain() {
		return $this->format( self::FORMAT_PLAIN );
	}

	/**
	 * Returns the parsed message text which is always surrounded by a block element.
	 *
	 * @since 1.17
	 *
	 * @return string HTML
	 * @return-taint escaped
	 */
	public function parseAsBlock() {
		return $this->format( self::FORMAT_BLOCK_PARSE );
	}

	/**
	 * Returns the message text. {{-transformation (substituting the template with its
	 * parsed result) is done and the result is HTML escaped excluding any raw parameters.
	 *
	 * @since 1.17
	 *
	 * @return string HTML escaped message text.
	 * @return-taint escaped
	 */
	public function escaped() {
		return $this->format( self::FORMAT_ESCAPED );
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
	 * @param-taint $raw html,exec_html
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
	 * @since 1.36
	 *
	 * @param string $dateTime
	 *
	 * @return string[] Array with a single "datetime" key.
	 */
	public static function dateTimeParam( string $dateTime ) {
		return [ 'datetime' => $dateTime ];
	}

	/**
	 * @since 1.36
	 *
	 * @param string $date
	 *
	 * @return string[] Array with a single "date" key.
	 */
	public static function dateParam( string $date ) {
		return [ 'date' => $date ];
	}

	/**
	 * @since 1.36
	 *
	 * @param string $time
	 *
	 * @return string[] Array with a single "time" key.
	 */
	public static function timeParam( string $time ) {
		return [ 'time' => $time ];
	}

	/**
	 * @since 1.38
	 *
	 * @param string $userGroup
	 *
	 * @return string[] Array with a single "group" key.
	 */
	public static function userGroupParam( string $userGroup ) {
		return [ 'group' => $userGroup ];
	}

	/**
	 * @since 1.38
	 *
	 * @param Stringable $object
	 *
	 * @return Stringable[] Array with a single "object" key.
	 */
	public static function objectParam( Stringable $object ) {
		return [ 'object' => $object ];
	}

	/**
	 * @since 1.22
	 *
	 * @param int|float $period
	 *
	 * @return int[]|float[] Array with a single "period" key.
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
				"Invalid type '$type'. Known types are: " . implode( ', ', array_keys( self::$listTypeMap ) )
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
	protected function replaceParameters( $message, $type, $format ) {
		// A temporary marker for $1 parameters that is only valid
		// in non-attribute contexts. However if the entire message is escaped
		// then we don't want to use it because it will be mangled in all contexts
		// and its unnecessary as ->escaped() messages aren't html.
		$marker = $format === self::FORMAT_ESCAPED ? '$' : '$\'"';
		$replacementKeys = [];
		foreach ( $this->parameters as $n => $param ) {
			[ $paramType, $value ] = $this->extractParam( $param, $format );
			if ( $type === 'before' ) {
				if ( $paramType === 'before' ) {
					$replacementKeys['$' . ( $n + 1 )] = $value;
				} else /* $paramType === 'after' */ {
					// To protect against XSS from replacing parameters
					// inside html attributes, we convert $1 to $'"1.
					// In the event that one of the parameters ends up
					// in an attribute, either the ' or the " will be
					// escaped, breaking the replacement and avoiding XSS.
					$replacementKeys['$' . ( $n + 1 )] = $marker . ( $n + 1 );
				}
			} elseif ( $paramType === 'after' ) {
				$replacementKeys[$marker . ( $n + 1 )] = $value;
			}
		}
		return strtr( $message, $replacementKeys );
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
			} elseif ( isset( $param['datetime'] ) ) {
				return [ 'before', $this->getLanguage()->timeanddate( $param['datetime'] ) ];
			} elseif ( isset( $param['date'] ) ) {
				return [ 'before', $this->getLanguage()->date( $param['date'] ) ];
			} elseif ( isset( $param['time'] ) ) {
				return [ 'before', $this->getLanguage()->time( $param['time'] ) ];
			} elseif ( isset( $param['group'] ) ) {
				return [ 'before', $this->getLanguage()->getGroupName( $param['group'] ) ];
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
			} elseif ( isset( $param['object'] ) ) {
				$obj = $param['object'];
				if ( $obj instanceof UserGroupMembershipParam ) {
					return [
						'before',
						$this->getLanguage()->getGroupMemberName( $obj->getGroup(), $obj->getMember() )
					];
				} else {
					return [ 'before', $obj->__toString() ];
				}
			} else {
				LoggerFactory::getInstance( 'Bug58676' )->warning(
					'Invalid parameter for message "{msgkey}": {param}',
					[
						'exception' => new RuntimeException,
						'msgkey' => $this->getKey(),
						'param' => htmlspecialchars( serialize( $param ) ),
					]
				);

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
			$msg->contextPage = $this->contextPage;

			// DWIM
			if ( $format === 'block-parse' ) {
				$format = 'parse';
			}

			// Message objects should not be before parameters because
			// then they'll get double escaped. If the message needs to be
			// escaped, it'll happen right here when we call toString().
			return [ 'after', $msg->format( $format ) ];
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
		$out = MediaWikiServices::getInstance()->getMessageCache()->parse(
			$string,
			$this->contextPage,
			/*linestart*/true,
			$this->interface,
			$this->getLanguage()
		);

		return $out instanceof ParserOutput
			? $out->getText( [
				'allowTOC' => false,
				'enableSectionEditLinks' => false,
				// Wrapping messages in an extra <div> is probably not expected. If
				// they're outside the content area they probably shouldn't be
				// targeted by CSS that's targeting the parser output, and if
				// they're inside they already are from the outer div.
				'unwrap' => true,
				'userLang' => $this->getLanguage(),
			] )
			: $out;
	}

	/**
	 * Wrapper for what ever method we use to {{-transform wikitext (substituting the
	 * template with its parsed result).
	 *
	 * @since 1.17
	 *
	 * @param string $string Wikitext message contents.
	 *
	 * @return string Wikitext with {{-constructs substituted with its parsed result.
	 */
	protected function transformText( $string ) {
		return MediaWikiServices::getInstance()->getMessageCache()->transform(
			$string,
			$this->interface,
			$this->getLanguage(),
			$this->contextPage
		);
	}

	/**
	 * Wrapper for whatever method we use to get message contents.
	 *
	 * @since 1.17
	 *
	 * @return string|false
	 */
	protected function fetchMessage() {
		if ( $this->message === null ) {
			$cache = MediaWikiServices::getInstance()->getMessageCache();

			foreach ( $this->keysToTry as $key ) {
				$message = $cache->get( $key, $this->useDatabase, $this->getLanguage() );
				if ( $message !== false && $message !== '' ) {
					break;
				}
			}

			// NOTE: The constructor makes sure keysToTry isn't empty,
			//       so we know that $key and $message are initialized.
			// @phan-suppress-next-next-line PhanPossiblyUndeclaredVariable False positive
			// @phan-suppress-next-line PhanPossiblyNullTypeMismatchProperty False positive
			$this->key = $key;
			// @phan-suppress-next-line PhanPossiblyUndeclaredVariable False positive
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
				return Sanitizer::escapeCombiningChar( htmlspecialchars( $plaintext, ENT_QUOTES ) );
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
			$e = new InvalidArgumentException;
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
			[ $type, $value ] = $this->extractParam( $p, $format );
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
		// @phan-suppress-next-line SecurityCheck-DoubleEscaped RawMessage is safe here
		return $this->extractParam( new RawMessage( $vars, $params ), $format );
	}
}

/** @deprecated class alias since 1.42 */
class_alias( Message::class, 'Message' );
