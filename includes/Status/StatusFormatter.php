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
 */

namespace MediaWiki\Status;

use ApiMessage;
use Language;
use MediaWiki\Language\RawMessage;
use MediaWiki\Message\Message;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\StubObject\StubUserLang;
use MessageCache;
use MessageLocalizer;
use MessageSpecifier;
use StatusValue;
use UnexpectedValueException;

/**
 * Formatter for StatusValue objects.
 *
 * @since 1.42
 *
 * @see StatusValue
 */
class StatusFormatter {

	private MessageLocalizer $messageLocalizer;
	private MessageCache $messageCache;

	public function __construct( MessageLocalizer $messageLocalizer, MessageCache $messageCache ) {
		$this->messageLocalizer = $messageLocalizer;
		$this->messageCache = $messageCache;
	}

	/**
	 * @param array $params
	 * @param array $options
	 *
	 * @return array
	 */
	private function cleanParams( array $params, array $options = [] ) {
		$cleanCallback = $options['cleanCallback'] ?? null;

		if ( !$cleanCallback ) {
			return $params;
		}
		$cleanParams = [];
		foreach ( $params as $i => $param ) {
			$cleanParams[$i] = call_user_func( $cleanCallback, $param );
		}
		return $cleanParams;
	}

	/**
	 * Get the error list as a wikitext formatted list
	 *
	 * @param StatusValue $status
	 * @param array $options An array of options, supporting the following keys:
	 * - 'shortContext' (string|false|null) A short enclosing context message name, to
	 *        be used when there is a single error
	 * - 'longContext' (string|false|null) A long enclosing context message name, for a list
	 * - 'lang' (string|Language|StubUserLang|null) Language to use for processing messages
	 * - 'cleanCallback' (callable|null) A callback for sanitizing parameter values
	 *
	 * @return string
	 */
	public function getWikiText( StatusValue $status, array $options = [] ) {
		$shortContext = $options['shortContext'] ?? null;
		$longContext = $options['longContext'] ?? null;
		$lang = $options['lang'] ?? null;

		$rawErrors = $status->getErrors();
		if ( count( $rawErrors ) === 0 ) {
			if ( $status->isOK() ) {
				$status->fatal(
					'internalerror_info',
					__METHOD__ . " called for a good result, this is incorrect\n"
				);
			} else {
				$status->fatal(
					'internalerror_info',
					__METHOD__ . ": Invalid result object: no error text but not OK\n"
				);
			}
			$rawErrors = $status->getErrors(); // just added a fatal
		}

		if ( count( $rawErrors ) === 1 ) {
			$s = $this->getErrorMessage( $rawErrors[0], $options )->plain();
			if ( $shortContext ) {
				$s = $this->msgInLang( $shortContext, $lang, $s )->plain();
			} elseif ( $longContext ) {
				$s = $this->msgInLang( $longContext, $lang, "* $s\n" )->plain();
			}
		} else {
			$errors = $this->getErrorMessageArray( $rawErrors, $options );
			foreach ( $errors as &$error ) {
				$error = $error->plain();
			}
			$s = "<ul>\n<li>\n" . implode( "\n</li>\n<li>\n", $errors ) . "\n</li>\n</ul>\n";
			if ( $longContext ) {
				$s = $this->msgInLang( $longContext, $lang, $s )->plain();
			} elseif ( $shortContext ) {
				$s = $this->msgInLang( $shortContext, $lang, "\n$s\n" )->plain();
			}
		}
		return $s;
	}

	/**
	 * Get a bullet list of the errors as a Message object.
	 *
	 * $shortContext and $longContext can be used to wrap the error list in some text.
	 * $shortContext will be preferred when there is a single error; $longContext will be
	 * preferred when there are multiple ones. In either case, $1 will be replaced with
	 * the list of errors.
	 *
	 * $shortContext is assumed to use $1 as an inline parameter: if there is a single item,
	 * it will not be made into a list; if there are multiple items, newlines will be inserted
	 * around the list.
	 * $longContext is assumed to use $1 as a standalone parameter; it will always receive a list.
	 *
	 * If both parameters are missing, and there is only one error, no bullet will be added.
	 *
	 * @param StatusValue $status
	 * @param array $options An array of options, supporting the following keys:
	 * - 'shortContext' (string|false|null) A short enclosing context message name, to
	 *        be used when there is a single error
	 * - 'longContext' (string|false|null) A long enclosing context message name, for a list
	 * - 'lang' (string|Language|StubUserLang|null) Language to use for processing messages
	 * - 'cleanCallback' (callable|null) A callback for sanitizing parameter values
	 *
	 * @return Message
	 */
	public function getMessage( StatusValue $status, array $options = [] ) {
		$shortContext = $options['shortContext'] ?? null;
		$longContext = $options['longContext'] ?? null;
		$lang = $options['lang'] ?? null;

		$rawErrors = $status->getErrors();
		if ( count( $rawErrors ) === 0 ) {
			if ( $status->isOK() ) {
				$status->fatal(
					'internalerror_info',
					__METHOD__ . " called for a good result, this is incorrect\n"
				);
			} else {
				$status->fatal(
					'internalerror_info',
					__METHOD__ . ": Invalid result object: no error text but not OK\n"
				);
			}
			$rawErrors = $status->getErrors(); // just added a fatal
		}
		if ( count( $rawErrors ) === 1 ) {
			$s = $this->getErrorMessage( $rawErrors[0], $options );
			if ( $shortContext ) {
				$s = $this->msgInLang( $shortContext, $lang, $s );
			} elseif ( $longContext ) {
				$wrapper = new RawMessage( "* \$1\n" );
				$wrapper->params( $s )->parse();
				$s = $this->msgInLang( $longContext, $lang, $wrapper );
			}
		} else {
			$msgs = $this->getErrorMessageArray( $rawErrors, $options );
			$msgCount = count( $msgs );

			$s = new RawMessage( '* $' . implode( "\n* \$", range( 1, $msgCount ) ) );
			$s->params( $msgs )->parse();

			if ( $longContext ) {
				$s = $this->msgInLang( $longContext, $lang, $s );
			} elseif ( $shortContext ) {
				$wrapper = new RawMessage( "\n\$1\n", [ $s ] );
				$wrapper->parse();
				$s = $this->msgInLang( $shortContext, $lang, $wrapper );
			}
		}

		return $s;
	}

	/**
	 * Try to convert the status to a PSR-3 friendly format. The output will be similar to
	 * getWikiText( false, false, 'en' ), but message parameters will be extracted into the
	 * context array with parameter names 'parameter1' etc. when possible.
	 *
	 * @return array A pair of (message, context) suitable for passing to a PSR-3 logger.
	 * @phan-return array{0:string,1:(int|float|string)[]}
	 */
	public function getPsr3MessageAndContext( StatusValue $status ): array {
		$options = [ 'lang' => 'en' ];
		$errors = $status->getErrors();

		if ( count( $errors ) === 1 ) {
			// identical to getMessage( false, false, 'en' ) when there's just one error
			$message = $this->getErrorMessage( $errors[0], [ 'lang' => 'en' ] );

			if ( in_array( get_class( $message ), [ Message::class, ApiMessage::class ], true ) ) {
				// Fall back to getWikiText for rawmessage, which is just a placeholder for non-translated text.
				// Turning the entire message into a context parameter wouldn't be useful.
				if ( $message->getKey() === 'rawmessage' ) {
					return [ $this->getWikiText( $status, $options ), [] ];
				}
				// $1,$2... will be left as-is when no parameters are provided.
				$text = $this->msgInLang( $message->getKey(), 'en' )->plain();
				$params = $message->getParams();
			} elseif ( $message instanceof RawMessage ) {
				$text = $message->getTextOfRawMessage();
				$params = $message->getParamsOfRawMessage();
			} else {
				// Unknown Message subclass, we can't be sure how it marks parameters. Fall back to getWikiText.
				return [ $this->getWikiText( $status, $options ), [] ];
			}

			$context = [];
			$i = 1;
			foreach ( $params as $param ) {
				if ( is_array( $param ) && count( $param ) === 1 ) {
					// probably Message::numParam() or similar
					$param = reset( $param );
				}
				if ( is_int( $param ) || is_float( $param ) || is_string( $param ) ) {
					$context["parameter$i"] = $param;
				} else {
					// Parameter is not of a safe type, fall back to getWikiText.
					return [ $this->getWikiText( $status, $options ), [] ];
				}

				$text = str_replace( "\$$i", "{parameter$i}", $text );

				$i++;
			}

			return [ $text, $context ];
		}
		// Parameters cannot be easily extracted, fall back to getWikiText,
		return [ $this->getWikiText( $status, $options ), [] ];
	}

	/**
	 * Return the message for a single error
	 *
	 * The code string can be used a message key with per-language versions.
	 * If $error is an array, the "params" field is a list of parameters for the message.
	 *
	 * @param array|string $error Code string or (key: code string, params: string[]) map
	 * @param array $options
	 * @return Message
	 */
	private function getErrorMessage( $error, array $options = [] ) {
		$lang = $options['lang'] ?? null;

		if ( is_array( $error ) ) {
			if ( isset( $error['message'] ) && $error['message'] instanceof Message ) {
				// Apply context from MessageLocalizer even if we have a Message object already
				$msg = $this->msg( $error['message'] );
			} elseif ( isset( $error['message'] ) && isset( $error['params'] ) ) {
				$msg = $this->msg(
					$error['message'],
					array_map( static function ( $param ) {
						return is_string( $param ) ? wfEscapeWikiText( $param ) : $param;
					}, $this->cleanParams( $error['params'], $options ) )
				);
			} else {
				$msgName = array_shift( $error );
				$msg = $this->msg(
					$msgName,
					array_map( static function ( $param ) {
						return is_string( $param ) ? wfEscapeWikiText( $param ) : $param;
					}, $this->cleanParams( $error, $options ) )
				);
			}
		} elseif ( is_string( $error ) ) {
			$msg = $this->msg( $error );
		} else {
			throw new UnexpectedValueException( 'Got ' . get_class( $error ) . ' for key.' );
		}

		if ( $lang ) {
			$msg->inLanguage( $lang );
		}
		return $msg;
	}

	/**
	 * Get the error message as HTML. This is done by parsing the wikitext error message
	 *
	 * @param StatusValue $status
	 * @param array $options An array of options, supporting the following keys:
	 * - 'shortContext' (string|false|null) A short enclosing context message name, to
	 *        be used when there is a single error
	 * - 'longContext' (string|false|null) A long enclosing context message name, for a list
	 * - 'lang' (string|Language|StubUserLang|null) Language to use for processing messages
	 * - 'cleanCallback' (callable|null) A callback for sanitizing parameter values
	 *
	 * @return string
	 */
	public function getHTML( StatusValue $status, array $options = [] ) {
		$lang = $options['lang'] ?? null;

		$text = $this->getWikiText( $status, $options );
		$out = $this->messageCache->parse( $text, null, true, true, $lang );

		return $out instanceof ParserOutput
			? $out->getText( [ 'enableSectionEditLinks' => false ] )
			: $out;
	}

	/**
	 * Return an array with a Message object for each error.
	 *
	 * @param array $errors
	 * @param array $options
	 *
	 * @return Message[]
	 */
	private function getErrorMessageArray( $errors, array $options = [] ) {
		return array_map( function ( $e ) use ( $options ) {
			return $this->getErrorMessage( $e, $options );
		}, $errors );
	}

	/**
	 * @param string|MessageSpecifier $key
	 * @param string|string[] ...$params
	 * @return Message
	 */
	private function msg( $key, ...$params ): Message {
		return $this->messageLocalizer->msg( $key, ...$params );
	}

	/**
	 * @param string|MessageSpecifier $key
	 * @param string|Language|StubUserLang|null $lang
	 * @param mixed ...$params
	 * @return Message
	 */
	private function msgInLang( $key, $lang, ...$params ): Message {
		$msg = $this->msg( $key, ...$params );
		if ( $lang ) {
			$msg->inLanguage( $lang );
		}
		return $msg;
	}
}
