<?php

/**
 * A tool for running hook functions.
 *
 * Copyright 2004, 2005 Evan Prodromou <evan@wikitravel.org>.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA
 *
 * @author Evan Prodromou <evan@wikitravel.org>
 * @see hooks.txt
 * @file
 */

/**
 * @since 1.18
 */
class MWHookException extends MWException {}

/**
 * Hooks class.
 *
 * Used to supersede $wgHooks, because globals are EVIL.
 *
 * @since 1.18
 */
class Hooks {

	protected static $handlers = array();

	/**
	 * Clears hooks registered via Hooks::register(). Does not touch $wgHooks.
	 * This is intended for use while testing and will fail if MW_PHPUNIT_TEST is not defined.
	 *
	 * @since 1.21
	 *
	 * @param $name String: the name of the hook to clear.
	 *
	 * @throws MWException if not in testing mode.
	 */
	public static function clear( $name ) {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new MWException( 'can not reset hooks in operation.' );
		}

		unset( self::$handlers[$name] );
	}


	/**
	 * Attach an event handler to a given hook
	 *
	 * @since 1.18
	 *
	 * @param $name String: name of hook
	 * @param $callback Mixed: callback function to attach
	 */
	public static function register( $name, $callback ) {
		if( !isset( self::$handlers[$name] ) ) {
			self::$handlers[$name] = array();
		}

		self::$handlers[$name][] = $callback;
	}

	/**
	 * Returns true if a hook has a function registered to it.
	 * The function may have been registered either via Hooks::register or in $wgHooks.
	 *
	 * @since 1.18
	 *
	 * @param $name String: name of hook
	 * @return Boolean: true if the hook has a function registered to it
	 */
	public static function isRegistered( $name ) {
		global $wgHooks;

		return !empty( $wgHooks[$name] ) || !empty( self::$handlers[$name] );
	}

	/**
	 * Returns an array of all the event functions attached to a hook
	 * This combines functions registered via Hooks::register and with $wgHooks.
	 *
	 * @since 1.18
	 *
	 * @throws MWException if $wgHooks is not an array for some reason
	 * @param string $name Name of the hook
	 * @return array
	 */
	public static function getHandlers( $name ) {
		global $wgHooks;

		// Return quickly in the most common case
		if ( !self::isRegistered( $name ) ) {
			return array();
		}

		if ( !is_array( $wgHooks ) ) {
			throw new MWException( "Global hooks array is not an array!\n" );
		}

		if ( !isset( self::$handlers[$name] ) ) {
			$hooks = $wgHooks[$name];
		} elseif ( !isset( $wgHooks[$name] ) ) {
			$hooks = self::$handlers[$name];
		} else {
			$hooks = array_merge( self::$handlers[$name], $wgHooks[$name] );
		}

		return $hooks;
	}

	/**
	 * Call hook functions defined in Hooks::register and $wgHooks.
	 *
	 * For a certain hook event, fetch the array of hook events and
	 * process them. Determine the proper callback for each hook and
	 * then call the actual hook using the appropriate arguments.
	 * Finally, process the return value and return/throw accordingly.
	 *
	 * @param string $event Event name
	 * @param array $args  Array of parameters passed to hook functions
	 * @return bool True if no handler aborted the hook
	 *
	 * @throw MWException if $wgHooks is not an array or if a hook has a bug
	 * @throw FatalError if a hook returns a string as an error
	 */
	public static function run( $event, array $args = array(), $ctx = null ) {
		if ( $ctx === null ) {
			$ctx = RequestContext::getMain();
		}

		foreach ( self::getHandlers( $event ) as $hook ) {
			// Turn non-array values into an array.
			if ( !is_array( $hook ) ) {
				$hook = array( $hook );
			}

			/**
			 * $hook can be: a function, an object, an array of $function and
			 * $data, an array of just a function, an array of object and
			 * method, or an array of object, method, and data.
			 */
			if ( count( $hook ) < 1 ) {
				throw new MWException( 'Empty array in hooks for ' . $event . "\n" );
			} elseif ( $hook[0] instanceof Closure ) {
				$func = "hook-$event-closure";
				$callback = array_shift( $hook );
			} elseif ( is_object( $hook[0] ) ) {
				$object = array_shift( $hook );
				$method = array_shift( $hook );

				// Set proper context on object.
				if ( $object instanceof ContextSource ) {
					$object->setContext( $ctx );
				}

				// If no method was specified, default to on$event.
				if ( $method === null ) {
					$method = "on$event";
				}

				$func = get_class( $object ) . '::' . $method;
				$callback = array( $object, $method );
			} elseif ( is_string( $hook[0] ) ) {
				$func = $callback = array_shift( $hook );
			} else {
				throw new MWException( 'Unknown datatype in hooks for ' . $event . "\n" );
			}

			// Put any hook data before the actual arguments.
			foreach ( $hook as &$arg ) {
				if ( $arg instanceof ContextSource ) {
					$arg->setContext( $ctx );
				}
			}
			$hook_args = array_merge( $hook, $args );

			// Run autoloader (workaround for call_user_func_array bug)
			is_callable( $callback );

			/*
			 * Call the hook. The documentation of call_user_func_array says
			 * false is returned on failure. However, if the function signature
			 * does not match the call signature, PHP will issue an warning and
			 * return null instead. The following code catches that warning and
			 * provides better error message.
			 */
			$retval = null;
			$badhookmsg = null;

			// Profile first in case the Profiler causes errors.
			wfProfileIn( $func );
			set_error_handler( 'Hooks::hookErrorHandler' );
			try {
				$retval = call_user_func_array( $callback, $hook_args );
			} catch ( MWHookException $e ) {
				$badhookmsg = $e->getMessage();
			}
			restore_error_handler();
			wfProfileOut( $func );

			// Process the return value.
			if ( is_string( $retval ) ) {
				// String returned means error.
				throw new FatalError( $retval );
			} elseif ( $badhookmsg !== null ) {
				// Exception was thrown from Hooks::hookErrorHandler.
				throw new MWException(
					'Detected bug in an extension! ' .
					"Hook $func has invalid call signature; " . $badhookmsg
				);
			} elseif ( $retval === null ) {
				// Null was returned. Error.
				throw new MWException(
					'Detected bug in an extension! ' .
					"Hook $func failed to return a value; " .
					'should return true to continue hook processing or false to abort.'
				);
			} elseif ( !$retval ) {
				// False was returned. Stop processing, but no error.
				return false;
			}
		}

		return true;
	}

	/**
	 * This REALLY should be protected... but it's public for compatibility
	 *
	 * @since 1.18
	 *
	 * @param $errno int Unused
	 * @param $errstr String: error message
	 * @throws MWHookException
	 * @return Boolean: false
	 */
	public static function hookErrorHandler( $errno, $errstr ) {
		if ( strpos( $errstr, 'expected to be a reference, value given' ) !== false ) {
			throw new MWHookException( $errstr );
		}
		return false;
	}
}
