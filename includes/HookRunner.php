<?php
namespace MediaWiki\Hooks;

/**
 * Helper object for running the designated handler functions for a particular hook.
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
 * @file
 */
use Closure;
use Exception;
use FatalError;
use MWException;
use Wikimedia\Assert\Assert;

/**
 * Helper object for running the designated handler functions for a particular hook.
 *
 * @since 1.29
 */
class HookRunner {

	/**
	 * @var string the event name
	 */
	private $name;

	/**
	 * Array of events mapped to an array of callbacks to be run
	 * when that event is triggered.
	 */
	protected $handlers = [];

	/**
	 * HookRunner constructor.
	 *
	 * @param string $name name of the event handled by this hook runner
	 * @param array $handlers
	 */
	public function __construct( $name, array $handlers ) {
		Assert::parameterType( 'string', $name, '$name' );
		Assert::parameterElementType( 'callable|array|object', $handlers, '$handlers' );

		$this->name = $name;
		$this->handlers = $handlers;

		// XXX: we could define the deprecation version here in the constructor.
	}

	/**
	 * Calls the hook handler functions provided to the constructor.
	 *
	 * @param array $args Array of parameters passed to hook handler functions.
	 * @param string|null $deprecatedVersion Optionally, mark hook as deprecated with version number
	 * @return bool True if no handler aborted the hook
	 *
	 * @throws Exception
	 * @throws FatalError
	 * @throws MWException
	 */
	public function run( array $args = [], $deprecatedVersion = null ) {
		foreach ( $this->handlers as $hook ) {
			// Turn non-array values into an array. (Can't use casting because of objects.)
			if ( !is_array( $hook ) ) {
				$hook = [ $hook ];
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
				$func = "hook-{$this->name}-closure";
				$callback = array_shift( $hook );
			} elseif ( is_object( $hook[0] ) ) {
				$object = array_shift( $hook );
				$method = array_shift( $hook );

				// If no method was specified, default to on$event.
				if ( $method === null ) {
					$method = "on{$this->name}";
				}

				$func = get_class( $object ) . '::' . $method;
				$callback = [ $object, $method ];
			} elseif ( is_string( $hook[0] ) ) {
				$func = $callback = array_shift( $hook );
			} else {
				throw new MWException( 'Unknown datatype in hooks for ' . $this->name . "\n" );
			}

			// Run autoloader (workaround for call_user_func_array bug)
			// and throw error if not callable.
			if ( !is_callable( $callback ) ) {
				throw new MWException( 'Invalid callback ' . $func . ' in hooks for ' . $this->name . "\n" );
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

			// mark hook as deprecated, if deprecation version is specified
			if ( $deprecatedVersion !== null ) {
				wfDeprecated( "{$this->name} hook (used in $func)", $deprecatedVersion );
			}

			$retval = call_user_func_array( $callback, $hook_args );

			// Process the return value.
			if ( is_string( $retval ) ) {
				// String returned means error.
				throw new FatalError( $retval );
			} elseif ( $retval === false ) {
				// False was returned. Stop processing, but no error.
				return false;
			}
		}

		return true;
	}

}
