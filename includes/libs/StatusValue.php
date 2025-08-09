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

use Wikimedia\Assert\Assert;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\Message\MessageValue;

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
 * The recommended pattern for functions returning StatusValue objects is
 * to return a StatusValue unconditionally, both on success and on failure
 * (similarly to Option, Maybe, Promise etc. objects in other languages) --
 * so that the developer of the calling code is reminded that the function
 * can fail, and so that a lack of error-handling will be explicit.
 *
 * This class accepts any MessageSpecifier objects. The use of Message objects
 * should be avoided when serializability is needed. Use MessageValue in that
 * case instead.
 *
 * @newable
 * @stable to extend
 * @since 1.25
 */
class StatusValue implements Stringable {

	/**
	 * @var bool
	 * @internal Only for use by Status. Use {@link self::isOK()} or {@link self::setOK()}.
	 */
	protected $ok = true;

	/**
	 * @var array[]
	 * @internal Only for use by Status. Use {@link self::getErrors()} (get full list),
	 * {@link self::splitByErrorType()} (get errors/warnings), or
	 * {@link self::fatal()}, {@link self::error()} or {@link self::warning()} (add error/warning).
	 */
	protected $errors = [];

	/** @var mixed */
	public $value;

	/** @var bool[] Map of (key => bool) to indicate success of each part of batch operations */
	public $success = [];

	/** @var int Counter for batch operations */
	public $successCount = 0;

	/** @var int Counter for batch operations */
	public $failCount = 0;

	/** @var mixed arbitrary extra data about the operation */
	public $statusData;

	/**
	 * Factory function for fatal errors
	 *
	 * @param string|MessageSpecifier $message Message key or object
	 * @phpcs:ignore Generic.Files.LineLength
	 * @param MessageParam|MessageSpecifier|string|int|float|list<MessageParam|MessageSpecifier|string|int|float> ...$parameters
	 *   See Message::params()
	 */
	public static function newFatal( $message, ...$parameters ): static {
		$result = new static();
		$result->fatal( $message, ...$parameters );
		return $result;
	}

	/**
	 * Factory function for good results
	 *
	 * @param mixed|null $value
	 */
	public static function newGood( $value = null ): static {
		$result = new static();
		$result->value = $value;
		return $result;
	}

	/**
	 * Splits this StatusValue object into two new StatusValue objects, one which contains only
	 * the error messages, and one that contains the warnings, only. The returned array is
	 * defined as:
	 * [
	 *     0 => object(StatusValue) # the StatusValue with error messages, only
	 *     1 => object(StatusValue) # The StatusValue with warning messages, only
	 * ]
	 *
	 * @return static[]
	 */
	public function splitByErrorType() {
		$errorsOnlyStatusValue = static::newGood();
		$warningsOnlyStatusValue = static::newGood();
		$warningsOnlyStatusValue->setResult( true, $this->getValue() );
		$errorsOnlyStatusValue->setResult( $this->isOK(), $this->getValue() );

		foreach ( $this->errors as $item ) {
			if ( $item['type'] === 'warning' ) {
				$warningsOnlyStatusValue->errors[] = $item;
			} else {
				$errorsOnlyStatusValue->errors[] = $item;
			}
		}

		return [ $errorsOnlyStatusValue, $warningsOnlyStatusValue ];
	}

	/**
	 * Returns whether the operation completed and didn't have any error or
	 * warnings
	 *
	 * @return bool
	 */
	public function isGood() {
		return $this->ok && !$this->errors;
	}

	/**
	 * Returns whether the operation completed
	 *
	 * @return bool
	 */
	public function isOK() {
		return $this->ok;
	}

	/**
	 * @return mixed
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * Get the list of errors
	 *
	 * Each error is a (message:string or MessageSpecifier,params:array) map
	 *
	 * @deprecated since 1.43 Use `->getMessages()` instead
	 * @return array[]
	 * @phan-return array{type:'warning'|'error', message:string|MessageSpecifier, params:array}[]
	 */
	public function getErrors() {
		return $this->errors;
	}

	/**
	 * Change operation status
	 *
	 * @param bool $ok
	 * @return $this
	 */
	public function setOK( $ok ) {
		$this->ok = $ok;
		return $this;
	}

	/**
	 * Change operation result
	 *
	 * @param bool $ok Whether the operation completed
	 * @param mixed|null $value
	 * @return $this
	 */
	public function setResult( $ok, $value = null ) {
		$this->ok = (bool)$ok;
		$this->value = $value;
		return $this;
	}

	/**
	 * Add a new error to the error array ($this->errors) if that error is not already in the
	 * error array. Each error is passed as an array with the following fields:
	 *
	 * - type: 'error' or 'warning'
	 * - message: a string (message key) or MessageSpecifier
	 * - params: an array of string parameters
	 *
	 * If the new error is of type 'error' and it matches an existing error of type 'warning',
	 * the existing error is upgraded to type 'error'. An error provided as a MessageSpecifier
	 * will successfully match an error provided as the same string message key and array of
	 * parameters as separate array elements.
	 *
	 * @param array $newError
	 * @phan-param array{type:'warning'|'error', message:string|MessageSpecifier, params:array} $newError
	 * @return $this
	 */
	private function addError( array $newError ) {
		[ 'type' => $newType, 'message' => $newKey, 'params' => $newParams ] = $newError;
		if ( $newKey instanceof MessageSpecifier ) {
			Assert::parameter( $newParams === [],
				'$parameters', "must be empty when using a MessageSpecifier" );
			$newParams = $newKey->getParams();
			$newKey = $newKey->getKey();
		}

		foreach ( $this->errors as [ 'type' => &$type, 'message' => $key, 'params' => $params ] ) {
			if ( $key instanceof MessageSpecifier ) {
				$params = $key->getParams();
				$key = $key->getKey();
			}

			// This uses loose equality as we must support equality between MessageParam objects
			// (e.g. ScalarParam), including when they are created separate and not by-ref equal.
			if ( $newKey === $key && $newParams == $params ) {
				if ( $type === 'warning' && $newType === 'error' ) {
					$type = 'error';
				}
				return $this;
			}
		}

		$this->errors[] = $newError;

		return $this;
	}

	/**
	 * Add a new warning
	 *
	 * @param string|MessageSpecifier $message Message key or object
	 * @phpcs:ignore Generic.Files.LineLength
	 * @param MessageParam|MessageSpecifier|string|int|float|list<MessageParam|MessageSpecifier|string|int|float> ...$parameters
	 *   See Message::params()
	 * @return $this
	 */
	public function warning( $message, ...$parameters ) {
		return $this->addError( [
			'type' => 'warning',
			'message' => $message,
			'params' => $parameters
		] );
	}

	/**
	 * Add an error, do not set fatal flag
	 * This can be used for non-fatal errors
	 *
	 * @param string|MessageSpecifier $message Message key or object
	 * @phpcs:ignore Generic.Files.LineLength
	 * @param MessageParam|MessageSpecifier|string|int|float|list<MessageParam|MessageSpecifier|string|int|float> ...$parameters
	 *   See Message::params()
	 * @return $this
	 */
	public function error( $message, ...$parameters ) {
		return $this->addError( [
			'type' => 'error',
			'message' => $message,
			'params' => $parameters
		] );
	}

	/**
	 * Add an error and set OK to false, indicating that the operation
	 * as a whole was fatal
	 *
	 * @param string|MessageSpecifier $message Message key or object
	 * @phpcs:ignore Generic.Files.LineLength
	 * @param MessageParam|MessageSpecifier|string|int|float|list<MessageParam|MessageSpecifier|string|int|float> ...$parameters
	 *   See Message::params()
	 * @return $this
	 */
	public function fatal( $message, ...$parameters ) {
		$this->ok = false;
		return $this->error( $message, ...$parameters );
	}

	/**
	 * Merge another status object into this one
	 *
	 * @param StatusValue $other
	 * @param bool $overwriteValue Whether to override the "value" member
	 * @return $this
	 */
	public function merge( $other, $overwriteValue = false ) {
		if ( $this->statusData !== null && $other->statusData !== null ) {
			throw new RuntimeException( "Status cannot be merged, because they both have \$statusData" );
		} else {
			$this->statusData ??= $other->statusData;
		}

		foreach ( $other->errors as $error ) {
			$this->addError( $error );
		}
		$this->ok = $this->ok && $other->ok;
		if ( $overwriteValue ) {
			$this->value = $other->value;
		}
		$this->successCount += $other->successCount;
		$this->failCount += $other->failCount;

		return $this;
	}

	/**
	 * Returns a list of status messages of the given type
	 *
	 * Each entry is a map of:
	 *   - message: string message key or MessageSpecifier
	 *   - params: array list of parameters
	 *
	 * @deprecated since 1.43 Use `->getMessages( $type )` instead
	 * @param string $type
	 * @return array[]
	 * @phan-return array{type:'warning'|'error', message:string|MessageSpecifier, params:array}[]
	 */
	public function getErrorsByType( $type ) {
		$result = [];
		foreach ( $this->errors as $error ) {
			if ( $error['type'] === $type ) {
				$result[] = $error;
			}
		}

		return $result;
	}

	/**
	 * Returns a list of error messages, optionally only those of the given type
	 *
	 * If the `warning()` or `error()` method was called with a MessageSpecifier object,
	 * this method is guaranteed to return the same object.
	 *
	 * @since 1.43
	 * @param ?string $type If provided, only return messages of the type 'warning' or 'error'
	 * @phan-param null|'warning'|'error' $type
	 * @return MessageSpecifier[]
	 */
	public function getMessages( ?string $type = null ): array {
		Assert::parameter( $type === null || $type === 'warning' || $type === 'error',
			'$type', "must be null, 'warning', or 'error'" );
		$result = [];
		foreach ( $this->errors as $error ) {
			if ( $type === null || $error['type'] === $type ) {
				[ 'message' => $key, 'params' => $params ] = $error;
				if ( $key instanceof MessageSpecifier ) {
					$result[] = $key;
				} else {
					$result[] = new MessageValue( $key, $params );
				}
			}
		}

		return $result;
	}

	/**
	 * Returns true if the specified message is present as a warning or error.
	 * Any message using the same key will be found (ignoring the message parameters).
	 *
	 * @param string $message Message key to search for
	 * @return bool
	 */
	public function hasMessage( string $message ) {
		foreach ( $this->errors as [ 'message' => $key ] ) {
			if ( ( $key instanceof MessageSpecifier && $key->getKey() === $message ) ||
				$key === $message
			) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Returns true if any other message than the specified ones is present as a warning or error.
	 * Any messages using the same keys will be found (ignoring the message parameters).
	 *
	 * @param string ...$messages Message keys to search for
	 * @return bool
	 */
	public function hasMessagesExcept( string ...$messages ) {
		foreach ( $this->errors as [ 'message' => $key ] ) {
			if ( $key instanceof MessageSpecifier ) {
				$key = $key->getKey();
			}
			if ( !in_array( $key, $messages, true ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * If the specified source message exists, replace it with the specified
	 * destination message, but keep the same parameters as in the original error.
	 *
	 * Any message using the same key will be replaced (ignoring the message parameters).
	 *
	 * @param string $source Message key to search for
	 * @param MessageSpecifier|string $dest Replacement message key or object
	 * @return bool Return true if the replacement was done, false otherwise.
	 */
	public function replaceMessage( string $source, $dest ) {
		$replaced = false;

		foreach ( $this->errors as [ 'message' => &$message, 'params' => &$params ] ) {
			if ( $message === $source ||
				( $message instanceof MessageSpecifier && $message->getKey() === $source )
			) {
				$message = $dest;
				if ( $dest instanceof MessageSpecifier ) {
					// 'params' will be ignored now, so remove them from the internal array
					$params = [];
				}
				$replaced = true;
			}
		}

		return $replaced;
	}

	/**
	 * Returns a string representation of the status for debugging.
	 * This is fairly verbose and may change without notice.
	 *
	 * @return string
	 */
	public function __toString() {
		$status = $this->isOK() ? "OK" : "Error";
		if ( count( $this->errors ) ) {
			$errorcount = "collected " . ( count( $this->errors ) ) . " message(s) on the way";
		} else {
			$errorcount = "no errors detected";
		}
		if ( $this->value !== null ) {
			$valstr = get_debug_type( $this->value ) . " value set";
		} else {
			$valstr = "no value set";
		}
		$out = sprintf( "<%s, %s, %s>",
			$status,
			$errorcount,
			$valstr
		);
		if ( count( $this->errors ) > 0 ) {
			$hdr = sprintf( "+-%'-8s-+-%'-25s-+-%'-36s-+\n", "", "", "" );
			$out .= "\n" . $hdr;
			foreach ( $this->errors as [ 'type' => $type, 'message' => $key, 'params' => $params ] ) {
				if ( $key instanceof MessageSpecifier ) {
					$params = $key->getParams();
					$key = $key->getKey();
				}

				$keyChunks = mb_str_split( $key, 25 );
				$paramsChunks = mb_str_split( $this->flattenParams( $params, " | " ), 36 );

				// array_map(null,...) is like Python's zip()
				foreach ( array_map( null, [ $type ], $keyChunks, $paramsChunks )
					as [ $typeChunk, $keyChunk, $paramsChunk ]
				) {
					$out .= sprintf( "| %-8s | %-25s | %-36s |\n",
						$typeChunk,
						$keyChunk,
						$paramsChunk
					);
				}
			}
			$out .= $hdr;
		}

		return $out;
	}

	/**
	 * @param array $params Message parameters
	 * @param string $joiner
	 *
	 * @return string String representation
	 */
	private function flattenParams( array $params, string $joiner = ', ' ): string {
		$ret = [];
		foreach ( $params as $p ) {
			if ( is_array( $p ) ) {
				$r = '[ ' . self::flattenParams( $p ) . ' ]';
			} elseif ( $p instanceof MessageSpecifier ) {
				$r = '{ ' . $p->getKey() . ': ' . self::flattenParams( $p->getParams() ) . ' }';
			} elseif ( $p instanceof MessageParam ) {
				$r = $p->dump();
			} else {
				$r = (string)$p;
			}

			$ret[] = mb_strlen( $r ) > 100 ? mb_substr( $r, 0, 99 ) . "..." : $r;
		}
		return implode( $joiner, $ret );
	}

	/**
	 * Returns a list of status messages of the given type (or all if false)
	 *
	 * @internal Only for use by Status.
	 *
	 * @param string|bool $type
	 * @return array[]
	 */
	protected function getStatusArray( $type = false ) {
		$result = [];

		foreach ( $this->getErrors() as $error ) {
			if ( !$type || $error['type'] === $type ) {
				if ( $error['message'] instanceof MessageSpecifier ) {
					$result[] = [ $error['message']->getKey(), ...$error['message']->getParams() ];
				} else {
					$result[] = [ $error['message'], ...$error['params'] ];
				}
			}
		}

		return $result;
	}
}
