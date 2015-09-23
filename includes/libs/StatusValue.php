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
 * @since 1.25
 */
class StatusValue {
	/** @var bool */
	protected $ok = true;
	/** @var array */
	protected $errors = array();

	/** @var mixed */
	public $value;
	/** @var array Map of (key => bool) to indicate success of each part of batch operations */
	public $success = array();
	/** @var int Counter for batch operations */
	public $successCount = 0;
	/** @var int Counter for batch operations */
	public $failCount = 0;

	/**
	 * Factory function for fatal errors
	 *
	 * @param string|MessageSpecifier $message Message key or object
	 * @return Status
	 */
	public static function newFatal( $message /*, parameters...*/ ) {
		$params = func_get_args();
		$result = new static();
		call_user_func_array( array( &$result, 'fatal' ), $params );
		return $result;
	}

	/**
	 * Factory function for good results
	 *
	 * @param mixed $value
	 * @return Status
	 */
	public static function newGood( $value = null ) {
		$result = new static();
		$result->value = $value;
		return $result;
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
	 * @return array
	 */
	public function getErrors() {
		return $this->errors;
	}

	/**
	 * Change operation status
	 *
	 * @param bool $ok
	 */
	public function setOK( $ok ) {
		$this->ok = $ok;
	}

	/**
	 * Change operation resuklt
	 *
	 * @param bool $ok Whether the operation completed
	 * @param mixed $value
	 */
	public function setResult( $ok, $value = null ) {
		$this->ok = $ok;
		$this->value = $value;
	}

	/**
	 * Add a new warning
	 *
	 * @param string|MessageSpecifier $message Message key or object
	 */
	public function warning( $message /*, parameters... */ ) {
		$this->errors[] = array(
			'type' => 'warning',
			'message' => $message,
			'params' => array_slice( func_get_args(), 1 )
		);
	}

	/**
	 * Add an error, do not set fatal flag
	 * This can be used for non-fatal errors
	 *
	 * @param string|MessageSpecifier $message Message key or object
	 */
	public function error( $message /*, parameters... */ ) {
		$this->errors[] = array(
			'type' => 'error',
			'message' => $message,
			'params' => array_slice( func_get_args(), 1 )
		);
	}

	/**
	 * Add an error and set OK to false, indicating that the operation
	 * as a whole was fatal
	 *
	 * @param string|MessageSpecifier $message Message key or object
	 */
	public function fatal( $message /*, parameters... */ ) {
		$this->errors[] = array(
			'type' => 'error',
			'message' => $message,
			'params' => array_slice( func_get_args(), 1 )
		);
		$this->ok = false;
	}

	/**
	 * Merge another status object into this one
	 *
	 * @param Status $other Other Status object
	 * @param bool $overwriteValue Whether to override the "value" member
	 */
	public function merge( $other, $overwriteValue = false ) {
		$this->errors = array_merge( $this->errors, $other->errors );
		$this->ok = $this->ok && $other->ok;
		if ( $overwriteValue ) {
			$this->value = $other->value;
		}
		$this->successCount += $other->successCount;
		$this->failCount += $other->failCount;
	}

	/**
	 * Returns a list of status messages of the given type
	 *
	 * Each entry is a map of (message:string or MessageSpecifier,params:array))
	 *
	 * @param string $type
	 * @return array
	 */
	public function getErrorsByType( $type ) {
		$result = array();
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
	 * @param string|MessageSpecifier $message Message key or object to search for
	 *
	 * @return bool
	 */
	public function hasMessage( $message ) {
		if ( $message instanceof MessageSpecifier ) {
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
	 * @param IStatusMessage|string $source Message key or object to search for
	 * @param IStatusMessage|string $dest Replacement message key or object
	 * @return bool Return true if the replacement was done, false otherwise.
	 */
	public function replaceMessage( $source, $dest ) {
		$replaced = false;

		foreach ( $this->errors as $index => $error ) {
			if ( $error['message'] === $source ) {
				$this->errors[$index]['message'] = $dest;
				$replaced = true;
			}
		}

		return $replaced;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		$status = $this->isOK() ? "OK" : "Error";
		if ( count( $this->errors ) ) {
			$errorcount = "collected " . ( count( $this->errors ) ) . " error(s) on the way";
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
			$hdr = sprintf( "+-%'-4s-+-%'-25s-+-%'-40s-+\n", "", "", "" );
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
					$params = array();
				}

				$out .= sprintf( "| %4d | %-25.25s | %-40.40s |\n",
					$i,
					$key,
					implode( " ", $params )
				);
				$i += 1;
			}
			$out .= $hdr;
		}

		return $out;
	}
}
