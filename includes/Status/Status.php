<?php
/**
 * Generic operation result.
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
 */

namespace MediaWiki\Status;

use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\Language\Language;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\StubObject\StubUserLang;
use MessageLocalizer;
use RuntimeException;
use StatusValue;

/**
 * Generic operation result class
 * Has warning/error list, boolean status and arbitrary value
 *
 * "Good" means the operation was completed with no warnings or errors.
 *
 * "OK" means the operation was partially or wholly completed.
 *
 * An operation which is not OK should have errors so that the user can be
 * informed as to what went wrong. Calling the fatal() function sets an error
 * message and simultaneously switches off the OK flag.
 *
 * The recommended pattern for Status objects is to return a Status object
 * unconditionally, i.e. both on success and on failure -- so that the
 * developer of the calling code is reminded that the function can fail, and
 * so that a lack of error-handling will be explicit.
 *
 * @newable
 */
class Status extends StatusValue {
	/** @var callable|false */
	public $cleanCallback = false;

	/** @var MessageLocalizer|null */
	protected $messageLocalizer;

	private ?StatusFormatter $formatter = null;

	/**
	 * Succinct helper method to wrap a StatusValue
	 *
	 * This is useful when formatting StatusValue objects:
	 * @code
	 *     $this->getOutput()->addHtml( Status::wrap( $sv )->getHTML() );
	 * @endcode
	 *
	 * @param StatusValue|Status $sv
	 * @return static
	 */
	public static function wrap( $sv ) {
		if ( $sv instanceof static ) {
			return $sv;
		}

		$result = new static();
		$result->ok =& $sv->ok;
		$result->errors =& $sv->errors;
		$result->value =& $sv->value;
		$result->successCount =& $sv->successCount;
		$result->failCount =& $sv->failCount;
		$result->success =& $sv->success;
		$result->statusData =& $sv->statusData;

		return $result;
	}

	/**
	 * Backwards compatibility logic
	 *
	 * @param string $name
	 * @return mixed
	 * @throws RuntimeException
	 */
	public function __get( $name ) {
		if ( $name === 'ok' ) {
			return $this->isOK();
		}
		if ( $name === 'errors' ) {
			return $this->getErrors();
		}

		throw new RuntimeException( "Cannot get '$name' property." );
	}

	/**
	 * Change operation result
	 * Backwards compatibility logic
	 *
	 * @param string $name
	 * @param mixed $value
	 * @throws RuntimeException
	 */
	public function __set( $name, $value ) {
		if ( $name === 'ok' ) {
			$this->setOK( $value );
		} else {
			throw new RuntimeException( "Cannot set '$name' property." );
		}
	}

	/**
	 * Makes this Status object use the given localizer instead of the global one.
	 * If it is an IContextSource or a ResourceLoader Context, it will also be used to
	 * determine the interface language.
	 * @note This setting does not survive serialization. That's usually for the best
	 *   (there's no guarantee we'll still have the same localization settings after
	 *   unserialization); it is the caller's responsibility to set the localizer again
	 *   if needed.
	 * @param MessageLocalizer $messageLocalizer
	 *
	 * @deprecated since 1.42, use FormatterFactory::getStatusFormatter instead.
	 */
	public function setMessageLocalizer( MessageLocalizer $messageLocalizer ) {
		// TODO: hard deprecate after switching callers to StatusFormatter
		$this->messageLocalizer = $messageLocalizer;
		$this->formatter = null;
	}

	private function getFormatter(): StatusFormatter {
		if ( !$this->formatter ) {
			$context = RequestContext::getMain();

			// HACK: only works for IContextSource objects.
			if ( $this->messageLocalizer && $this->messageLocalizer instanceof IContextSource ) {
				$context = $this->messageLocalizer;
			}

			$formatterFactory = MediaWikiServices::getInstance()->getFormatterFactory();
			$this->formatter = $formatterFactory->getStatusFormatter( $context );
		}

		return $this->formatter;
	}

	/**
	 * Splits this Status object into two new Status objects, one which contains only
	 * the error messages, and one that contains the warnings, only. The returned array is
	 * defined as:
	 * [
	 *     0 => object(Status) # The Status with error messages, only
	 *     1 => object(Status) # The Status with warning messages, only
	 * ]
	 *
	 * @return Status[]
	 */
	public function splitByErrorType() {
		[ $errorsOnlyStatus, $warningsOnlyStatus ] = parent::splitByErrorType();
		// phan/phan#2133?
		'@phan-var Status $errorsOnlyStatus';
		'@phan-var Status $warningsOnlyStatus';

		if ( $this->messageLocalizer ) {
			$errorsOnlyStatus->setMessageLocalizer = $this->messageLocalizer;
			$warningsOnlyStatus->setMessageLocalizer = $this->messageLocalizer;
		}

		if ( $this->formatter ) {
			$errorsOnlyStatus->formatter = $this->formatter;
			$warningsOnlyStatus->formatter = $this->formatter;
		}

		$errorsOnlyStatus->cleanCallback = $warningsOnlyStatus->cleanCallback = $this->cleanCallback;

		return [ $errorsOnlyStatus, $warningsOnlyStatus ];
	}

	/**
	 * Returns the wrapped StatusValue object
	 * @return StatusValue
	 * @since 1.27
	 */
	public function getStatusValue() {
		return $this;
	}

	/**
	 * Get the error list as a wikitext formatted list
	 *
	 * All message parameters that were provided as strings will be escaped with wfEscapeWikiText.
	 * This is mostly a historical accident and often undesirable (T368821).
	 * - To avoid this behavior when producing the Status, pass MessageSpecifier objects to methods
	 *   such as `$status->fatal()`, instead of separate key and params parameters.
	 * - To avoid this behavior when consuming the Status, use the `$status->getMessages()` method
	 *   instead, and display each message separately (or combine then with `Message::listParams()`).
	 *
	 * @deprecated since 1.42, use StatusFormatter instead.
	 *
	 * @param string|false $shortContext A short enclosing context message name, to
	 *        be used when there is a single error
	 * @param string|false $longContext A long enclosing context message name, for a list
	 * @param string|Language|StubUserLang|null $lang Language to use for processing messages
	 * @return string
	 */
	public function getWikiText( $shortContext = false, $longContext = false, $lang = null ) {
		return $this->getFormatter()->getWikiText( $this, [
			'shortContext' => $shortContext,
			'longContext' => $longContext,
			'lang' => $lang,
			'cleanCallback' => $this->cleanCallback
		] );
	}

	/**
	 * Get a bullet list of the errors as a Message object.
	 *
	 * All message parameters that were provided as strings will be escaped with wfEscapeWikiText.
	 * This is mostly a historical accident and often undesirable (T368821).
	 * - To avoid this behavior when producing the Status, pass MessageSpecifier objects to methods
	 *   such as `$status->fatal()`, instead of separate key and params parameters.
	 * - To avoid this behavior when consuming the Status, use the `$status->getMessages()` method
	 *   instead, and display each message separately (or combine then with `Message::listParams()`).
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
	 * @deprecated since 1.42, use StatusFormatter instead.
	 *
	 * @param string|string[]|false $shortContext A message name or an array of message names.
	 * @param string|string[]|false $longContext A message name or an array of message names.
	 * @param string|Language|StubUserLang|null $lang Language to use for processing messages
	 * @return Message
	 */
	public function getMessage( $shortContext = false, $longContext = false, $lang = null ) {
		return $this->getFormatter()->getMessage( $this, [
			'shortContext' => $shortContext,
			'longContext' => $longContext,
			'lang' => $lang,
			'cleanCallback' => $this->cleanCallback
		] );
	}

	/**
	 * Try to convert the status to a PSR-3 friendly format. The output will be similar to
	 * getWikiText( false, false, 'en' ), but message parameters will be extracted into the
	 * context array with parameter names 'parameter1' etc. when possible.
	 * A predefined context array may be passed for convenience.
	 *
	 * @deprecated since 1.42, use StatusFormatter instead.
	 *
	 * @return array A pair of (message, context) suitable for passing to a PSR-3 logger.
	 * @phan-return array{0:string,1:(int|float|string)[]}
	 */
	public function getPsr3MessageAndContext( array $context = [] ): array {
		return $this->getFormatter()->getPsr3MessageAndContext( $this, $context );
	}

	/**
	 * Get the error message as HTML. This is done by parsing the wikitext error message
	 *
	 * All message parameters that were provided as strings will be escaped with wfEscapeWikiText.
	 * This is mostly a historical accident and often undesirable (T368821).
	 * - To avoid this behavior when producing the Status, pass MessageSpecifier objects to methods
	 *   such as `$status->fatal()`, instead of separate key and params parameters.
	 * - To avoid this behavior when consuming the Status, use the `$status->getMessages()` method
	 *   instead, and display each message separately (or combine then with `Message::listParams()`).
	 *
	 * @deprecated since 1.42, use StatusFormatter instead.
	 *
	 * @param string|false $shortContext A short enclosing context message name, to
	 *        be used when there is a single error
	 * @param string|false $longContext A long enclosing context message name, for a list
	 * @param string|Language|StubUserLang|null $lang Language to use for processing messages
	 * @return string
	 */
	public function getHTML( $shortContext = false, $longContext = false, $lang = null ) {
		return $this->getFormatter()->getHTML( $this, [
			'shortContext' => $shortContext,
			'longContext' => $longContext,
			'lang' => $lang,
			'cleanCallback' => $this->cleanCallback
		] );
	}

	/**
	 * Get the list of errors (but not warnings)
	 *
	 * @deprecated since 1.43 Use `->getMessages( 'error' )` instead
	 * @return array[] A list in which each entry is an array with a message key as its first element.
	 *         The remaining array elements are the message parameters.
	 * @phan-return non-empty-array[]
	 */
	public function getErrorsArray() {
		return $this->getStatusArray( 'error' );
	}

	/**
	 * Get the list of warnings (but not errors)
	 *
	 * @deprecated since 1.43 Use `->getMessages( 'warning' )` instead
	 * @return array[] A list in which each entry is an array with a message key as its first element.
	 *         The remaining array elements are the message parameters.
	 * @phan-return non-empty-array[]
	 */
	public function getWarningsArray() {
		return $this->getStatusArray( 'warning' );
	}

	/**
	 * Don't save the callback when serializing, because Closures can't be
	 * serialized and we're going to clear it in __wakeup anyway.
	 * Don't save the localizer, because it can be pretty much anything. Restoring it is
	 * the caller's responsibility (otherwise it will just fall back to the global request context).
	 * Same for the formatter, which is likely to contain a localizer.
	 * @return array
	 */
	public function __sleep() {
		$keys = array_keys( get_object_vars( $this ) );
		return array_diff( $keys, [ 'cleanCallback', 'messageLocalizer', 'formatter' ] );
	}

	/**
	 * Sanitize the callback parameter on wakeup, to avoid arbitrary execution.
	 */
	public function __wakeup() {
		$this->cleanCallback = false;
		$this->messageLocalizer = null;
		$this->formatter = null;
	}

}

/** @deprecated class alias since 1.41 */
class_alias( Status::class, 'Status' );
