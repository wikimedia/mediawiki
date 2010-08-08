<?php
/**
 * A tool for running hook functions.
 *
 * Copyright 2004, 2005 Evan Prodromou <evan@wikitravel.org>.
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA
 *
 * @author Evan Prodromou <evan@wikitravel.org>
 * @see hooks.txt
 * @file
 */


/**
 * Call hook functions defined in $wgHooks
 *
 * Because programmers assign to $wgHooks, we need to be very
 * careful about its contents. So, there's a lot more error-checking
 * in here than would normally be necessary.
 *
 * @param $event String: event name
 * @param $args Array: parameters passed to hook functions
 * @return Boolean
 */
function wfRunHooks($event, $args = array()) {

	global $wgHooks;

	// Return quickly in the most common case
	if ( !isset( $wgHooks[$event] ) ) {
		return true;
	}

	if (!is_array($wgHooks)) {
		throw new MWException("Global hooks array is not an array!\n");
		return false;
	}

	if (!is_array($wgHooks[$event])) {
		throw new MWException("Hooks array for event '$event' is not an array!\n");
		return false;
	}

	foreach ($wgHooks[$event] as $index => $hook) {

		$object = null;
		$method = null;
		$func = null;
		$data = null;
		$have_data = false;
		$closure = false;
		$badhookmsg = false;

		/* $hook can be: a function, an object, an array of $function and $data,
		 * an array of just a function, an array of object and method, or an
		 * array of object, method, and data.
		 */

		if ( is_array( $hook ) ) {
			if ( count( $hook ) < 1 ) {
				throw new MWException("Empty array in hooks for " . $event . "\n");
			} else if ( is_object( $hook[0] ) ) {
				$object = $wgHooks[$event][$index][0];
				if ( $object instanceof Closure ) {
					$closure = true;
					if ( count( $hook ) > 1 ) {
						$data = $hook[1];
						$have_data = true;
					}
				} else {
					if ( count( $hook ) < 2 ) {
						$method = "on" . $event;
					} else {
						$method = $hook[1];
						if ( count( $hook ) > 2 ) {
							$data = $hook[2];
							$have_data = true;
						}
					}
				}
			} else if ( is_string( $hook[0] ) ) {
				$func = $hook[0];
				if ( count( $hook ) > 1) {
					$data = $hook[1];
					$have_data = true;
				}
			} else {
				throw new MWException( "Unknown datatype in hooks for " . $event . "\n" );
			}
		} else if ( is_string( $hook ) ) { # functions look like strings, too
			$func = $hook;
		} else if ( is_object( $hook ) ) {
			$object = $wgHooks[$event][$index];
			if ( $object instanceof Closure ) {
				$closure = true;
			} else {
				$method = "on" . $event;
			}
		} else {
			throw new MWException( "Unknown datatype in hooks for " . $event . "\n" );
		}

		/* We put the first data element on, if needed. */

		if ( $have_data ) {
			$hook_args = array_merge(array($data), $args);
		} else {
			$hook_args = $args;
		}

		if ( $closure ) {
			$callback = $object;
			$func = "hook-$event-closure";
		} elseif ( isset( $object ) ) {
			$func = get_class( $object ) . '::' . $method;
			$callback = array( $object, $method );
		} elseif ( false !== ( $pos = strpos( $func, '::' ) ) ) {
			$callback = array( substr( $func, 0, $pos ), substr( $func, $pos + 2 ) );
		} else {
			$callback = $func;
		}

		// Run autoloader (workaround for call_user_func_array bug)
		is_callable( $callback );

		/* Call the hook. The documentation of call_user_func_array clearly
		 * states that FALSE is returned on failure. However this is not
		 * case always. In some version of PHP if the function signature
		 * does not match the call signature, PHP will issue an warning:
		 * Param y in x expected to be a reference, value given.
		 *
		 * In that case the call will also return null. The following code
		 * catches that warning and provides better error message. The
		 * function documentation also says that:
		 *     In other words, it does not depend on the function signature
		 *     whether the parameter is passed by a value or by a reference. 
		 * There is also PHP bug http://bugs.php.net/bug.php?id=47554 which
		 * is unsurprisingly marked as bogus. In short handling of failures
		 * with call_user_func_array is a failure, the documentation for that
		 * function is wrong and misleading and PHP developers don't see any
		 * problem here.
		 */
		$retval = null;
		set_error_handler( 'hookErrorHandler' );
		wfProfileIn( $func );
		try {
			$retval = call_user_func_array( $callback, $hook_args );
		} catch ( MWHookException $e ) {
			$badhookmsg = $e->getMessage();
		}
		wfProfileOut( $func );
		restore_error_handler();

		/* String return is an error; false return means stop processing. */
		if ( is_string( $retval ) ) {
			global $wgOut;
			$wgOut->showFatalError( $retval );
			return false;
		} elseif( $retval === null ) {
			if ( $closure ) {
				$prettyFunc = "$event closure";
			} elseif( is_array( $callback ) ) {
				if( is_object( $callback[0] ) ) {
					$prettyClass = get_class( $callback[0] );
				} else {
					$prettyClass = strval( $callback[0] );
				}
				$prettyFunc = $prettyClass . '::' . strval( $callback[1] );
			} else {
				$prettyFunc = strval( $callback );
			}
			if ( $badhookmsg ) {
				throw new MWException( "Detected bug in an extension! " .
				"Hook $prettyFunc has invalid call signature; " . $badhookmsg );
			} else {
				throw new MWException( "Detected bug in an extension! " .
					"Hook $prettyFunc failed to return a value; " .
					"should return true to continue hook processing or false to abort." );
			}
		} else if ( !$retval ) {
			return false;
		}
	}

	return true;
}

function hookErrorHandler( $errno, $errstr ) {
	if ( strpos( $errstr, 'expected to be a reference, value given' ) !== false ) {
		throw new MWHookException( $errstr );
	}
	return false;
}

class MWHookException extends MWException {}