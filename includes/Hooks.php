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

	/**
	 * Array of events mapped to an array of callbacks to be run
	 * when that event is triggered.
	 */
	protected static $handlers = array();

	/**
	 * Attach an event handler to a given hook.
	 *
	 * @param string $name Name of hook
	 * @param mixed $callback Callback function to attach
	 *
	 * @since 1.18
	 */
	public static function register( $name, $callback ) {
		if ( !isset( self::$handlers[$name] ) ) {
			self::$handlers[$name] = array();
		}

		self::$handlers[$name][] = $callback;
	}

	/**
	 * Clears hooks registered via Hooks::register(). Does not touch $wgHooks.
	 * This is intended for use while testing and will fail if MW_PHPUNIT_TEST is not defined.
	 *
	 * @param string $name the name of the hook to clear.
	 *
	 * @since 1.21
	 * @throws MWException if not in testing mode.
	 */
	public static function clear( $name ) {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new MWException( 'Cannot reset hooks in operation.' );
		}

		unset( self::$handlers[$name] );
	}

	/**
	 * Returns true if a hook has a function registered to it.
	 * The function may have been registered either via Hooks::register or in $wgHooks.
	 *
	 * @since 1.18
	 *
	 * @param string $name Name of hook
	 * @return bool True if the hook has a function registered to it
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
	 * @param string $name Name of the hook
	 * @return array
	 */
	public static function getHandlers( $name ) {
		global $wgHooks;

		if ( !self::isRegistered( $name ) ) {
			return array();
		} elseif ( !isset( self::$handlers[$name] ) ) {
			return $wgHooks[$name];
		} elseif ( !isset( $wgHooks[$name] ) ) {
			return self::$handlers[$name];
		} else {
			return array_merge( self::$handlers[$name], $wgHooks[$name] );
		}
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
	 * @param string|null $deprecatedVersion Optionally, mark hook as deprecated with version number
	 * @return bool True if no handler aborted the hook
	 *
	 * @since 1.22 A hook function is not required to return a value for
	 *   processing to continue. Not returning a value (or explicitly
	 *   returning null) is equivalent to returning true.
	 * @throws MWException
	 * @throws FatalError
	 */
	public static function run( $event, array $args = array(), $deprecatedVersion = null ) {
		wfProfileIn( 'hook: ' . $event );
		foreach ( self::getHandlers( $event ) as $hook ) {
			// Turn non-array values into an array. (Can't use casting because of objects.)
			if ( !is_array( $hook ) ) {
				$hook = array( $hook );
			}

			if ( !array_filter( $hook ) ) {
				// Either array is empty or it's an array filled with null/false/empty.
				continue;
			} elseif ( is_array( $hook[0] ) ) {
				// First element is an array, meaning the developer intended
				// the first element to be a callback. Merge it in so that
				// processing can be uniform.
				$hook = array_merge( $hook[0], array_slice( $hook, 1 ) );
			}

			/**
			 * $hook can be: a function, an object, an array of $function and
			 * $data, an array of just a function, an array of object and
			 * method, or an array of object, method, and data.
			 */
			if ( $hook[0] instanceof Closure ) {
				$func = "hook-$event-closure";
				$callback = array_shift( $hook );
			} elseif ( is_object( $hook[0] ) ) {
				$object = array_shift( $hook );
				$method = array_shift( $hook );

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

			// Run autoloader (workaround for call_user_func_array bug)
			// and throw error if not callable.
			if ( !is_callable( $callback ) ) {
				throw new MWException( 'Invalid callback in hooks for ' . $event . "\n" );
			}

			/*
			 * Call the hook. The documentation of call_user_func_array says
			 * false is returned on failure. However, if the function signature
			 * does not match the call signature, PHP will issue an warning and
			 * return null instead. The following code catches that warning and
			 * provides better error message.
			 */
			$retval = null;
			$badhookmsg = null;
			$hook_args = array_merge( $hook, $args );

			// Profile first in case the Profiler causes errors.
			wfProfileIn( $func );

			// mark hook as deprecated, if deprecation version is specified
			if ( $deprecatedVersion !== null ) {
				wfDeprecated( "$event hook (used in $func)", $deprecatedVersion );
			}

			$retval = call_user_func_array( $callback, $hook_args );
			wfProfileOut( $func );

			// Process the return value.
			if ( is_string( $retval ) ) {
				// String returned means error.
				throw new FatalError( $retval );
			} elseif ( $retval === false ) {
				wfProfileOut( 'hook: ' . $event );
				// False was returned. Stop processing, but no error.
				return false;
			}
		}

		wfProfileOut( 'hook: ' . $event );
		return true;
	}
}
