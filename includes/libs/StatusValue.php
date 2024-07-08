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

use MediaWiki\Message\Converter;
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
 * The recommended pattern for Status objects is to return a StatusValue
 * unconditionally, i.e. both on success and on failure -- so that the
 * developer of the calling code is reminded that the function can fail, and
 * so that a lack of error-handling will be explicit.
 *
 * The use of Message objects should be avoided when serializability is needed.
 *
 * @newable
 * @since 1.25
 */
class StatusValue {

	/** @var bool */
	protected $ok = true;

	/** @var array[] */
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
	 * @param mixed ...$parameters
	 * @return static
	 */
	public static function newFatal( $message, ...$parameters ) {
		$result = new static();
		$result->fatal( $message, ...$parameters );
		return $result;
	}

	/**
	 * Factory function for good results
	 *
	 * @param mixed|null $value
	 * @return static
	 */
	public static function newGood( $value = null ) {
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
	 * @return array[]
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
	 * @return $this
	 */
	private function addError( array $newError ) {
		if ( $newError[ 'message' ] instanceof MessageSpecifier ) {
			$isEqual = static function ( $existingError ) use ( $newError ) {
				if ( $existingError['message'] instanceof MessageSpecifier ) {
					// compare attributes of both MessageSpecifiers
					return $newError['message'] == $existingError['message'];
				} else {
					return $newError['message']->getKey() === $existingError['message'] &&
						$newError['message']->getParams() === $existingError['params'];
				}
			};
		} else {
			$isEqual = static function ( $existingError ) use ( $newError ) {
				if ( $existingError['message'] instanceof MessageSpecifier ) {
					return $newError['message'] === $existingError['message']->getKey() &&
						$newError['params'] === $existingError['message']->getParams();
				} else {
					return $newError['message'] === $existingError['message'] &&
						$newError['params'] === $existingError['params'];
				}
			};
		}
		foreach ( $this->errors as $index => $existingError ) {
			if ( $isEqual( $existingError ) ) {
				if ( $newError[ 'type' ] === 'error' && $existingError[ 'type' ] === 'warning' ) {
					$this->errors[ $index ][ 'type' ] = 'error';
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
	 * @param string|MessageSpecifier|MessageValue $message Message key or object
	 * @param mixed ...$parameters
	 * @return $this
	 */
	public function warning( $message, ...$parameters ) {
		$message = $this->normalizeMessage( $message, $parameters );

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
	 * @param string|MessageSpecifier|MessageValue $message Message key or object
	 * @param mixed ...$parameters
	 * @return $this
	 */
	public function error( $message, ...$parameters ) {
		$message = $this->normalizeMessage( $message, $parameters );

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
	 * @param string|MessageSpecifier|MessageValue $message Message key or object
	 * @param mixed ...$parameters
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
	 * @param string $type
	 * @return array[]
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
	 * Returns true if the specified message is present as a warning or error
	 *
	 * @param string|MessageSpecifier|MessageValue $message Message key or object to search for
	 *
	 * @return bool
	 */
	public function hasMessage( $message ) {
		if ( $message instanceof MessageSpecifier ) {
			$message = $message->getKey();
		} elseif ( $message instanceof MessageValue ) {
			$message = $message->getKey();
		}

		foreach ( $this->errors as $error ) {
			if ( $error['message'] instanceof MessageSpecifier
				&& $error['message']->getKey() === $message
			) {
				return true;
			} elseif ( $error['message'] === $message ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * If the specified source message exists, replace it with the specified
	 * destination message, but keep the same parameters as in the original error.
	 *
	 * Note, due to the lack of tools for comparing IStatusMessage objects, this
	 * function will not work when using such an object as the search parameter.
	 *
	 * @param MessageSpecifier|MessageValue|string $source Message key or object to search for
	 * @param MessageSpecifier|MessageValue|string $dest Replacement message key or object
	 * @return bool Return true if the replacement was done, false otherwise.
	 */
	public function replaceMessage( $source, $dest ) {
		$replaced = false;

		$source = $this->normalizeMessage( $source );
		$dest = $this->normalizeMessage( $dest );

		foreach ( $this->errors as $index => $error ) {
			if ( $error['message'] === $source ) {
				$this->errors[$index]['message'] = $dest;
				$replaced = true;
			} elseif ( $error['message'] instanceof MessageSpecifier
				&& $error['message']->getKey() === $source ) {
				$this->errors[$index]['message'] = $dest;
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
		if ( isset( $this->value ) ) {
			$valstr = gettype( $this->value ) . " value set";
			if ( is_object( $this->value ) ) {
				$valstr .= "\"" . get_class( $this->value ) . "\" instance";
			}
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
			$i = 1;
			$out .= "\n";
			$out .= $hdr;
			foreach ( $this->errors as $error ) {
				if ( $error['message'] instanceof MessageSpecifier ) {
					$key = $error['message']->getKey();
					$params = $error['message']->getParams();
				} elseif ( $error['params'] ) {
					$key = $error['message'];
					$params = $error['params'];
				} else {
					$key = $error['message'];
					$params = [];
				}

				$type = $error['type'];
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

				$i++;
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
	 * @note this handles RawMessage poorly
	 *
	 * @param string|bool $type
	 * @return array[]
	 */
	protected function getStatusArray( $type = false ) {
		$result = [];

		foreach ( $this->getErrors() as $error ) {
			if ( $type === false || $error['type'] === $type ) {
				if ( $error['message'] instanceof MessageSpecifier ) {
					$result[] = array_merge(
						[ $error['message']->getKey() ],
						$error['message']->getParams()
					);
				} elseif ( $error['params'] ) {
					$result[] = array_merge( [ $error['message'] ], $error['params'] );
				} else {
					$result[] = [ $error['message'] ];
				}
			}
		}

		return $result;
	}

	/**
	 * @param MessageSpecifier|MessageValue|string $message
	 * @param array $parameters
	 *
	 * @return MessageSpecifier|string
	 */
	private function normalizeMessage( $message, array $parameters = [] ) {
		if ( $message instanceof MessageValue ) {
			$converter = new Converter();
			return $converter->convertMessageValue( $message );
		}

		return $message;
	}
}
