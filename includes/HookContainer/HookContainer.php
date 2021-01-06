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
 * @ingroup Hooks
 * @defgroup Hooks Hooks
 * Hooks allow custom code to be executed when an event occurs; this module
 * includes all hooks provided by MediaWiki Core; for more information, see
 * https://www.mediawiki.org/wiki/Manual:Hooks.
 */

namespace MediaWiki\HookContainer;

use Closure;
use MWDebug;
use MWException;
use UnexpectedValueException;
use Wikimedia\Assert\Assert;
use Wikimedia\ObjectFactory;
use Wikimedia\ScopedCallback;
use Wikimedia\Services\SalvageableService;

/**
 * HookContainer class.
 *
 * Main class for managing hooks
 *
 * @since 1.35
 */
class HookContainer implements SalvageableService {

	/** @var array Hooks and their callbacks registered through $this->register() */
	private $legacyRegisteredHandlers = [];

	/** @var array handler name and their handler objects */
	private $handlersByName = [];

	/** @var HookRegistry */
	private $registry;

	/** @var ObjectFactory */
	private $objectFactory;

	/** @var int The next ID to be used by scopedRegister() */
	private $nextScopedRegisterId = 0;

	/** @var array existing hook names and their handlers to restore between tests */
	private $originalHooks;

	/**
	 * @param HookRegistry $hookRegistry
	 * @param ObjectFactory $objectFactory
	 */
	public function __construct(
		HookRegistry $hookRegistry,
		ObjectFactory $objectFactory
	) {
		$this->registry = $hookRegistry;
		$this->objectFactory = $objectFactory;
	}

	/**
	 * Salvage the state of HookContainer by retaining existing handler objects
	 * and hooks registered via HookContainer::register(). Necessary in the event
	 * that MediaWikiServices::resetGlobalInstance() is called after hooks have already
	 * been registered.
	 *
	 * @param HookContainer|SalvageableService $other The object to salvage state from. $other be
	 * of type HookContainer
	 * @throws MWException
	 */
	public function salvage( SalvageableService $other ) {
		Assert::parameterType( self::class, $other, '$other' );
		if ( $this->legacyRegisteredHandlers || $this->handlersByName ) {
			throw new MWException( 'salvage() must be called immediately after construction' );
		}
		$this->handlersByName = $other->handlersByName;
		$this->legacyRegisteredHandlers = $other->legacyRegisteredHandlers;
	}

	/**
	 * Call registered hook functions through either the legacy $wgHooks or extension.json
	 *
	 * For the given hook, fetch the array of handler objects and
	 * process them. Determine the proper callback for each hook and
	 * then call the actual hook using the appropriate arguments.
	 * Finally, process the return value and return/throw accordingly.
	 *
	 * For hooks that are not abortable through a handler's return value,
	 * use runWithoutAbort() instead.
	 *
	 * @param string $hook Name of the hook
	 * @param array $args Arguments to pass to hook handler
	 * @param array $options options map:
	 *   - abortable: (bool) If false, handlers will not be allowed to abort the call sequenece.
	 *     An exception will be raised if a handler returns anything other than true or null.
	 *   - deprecatedVersion: (string) Version of MediaWiki this hook was deprecated in. For supporting
	 *     Hooks::run() legacy $deprecatedVersion parameter. New core code should add deprecated
	 *     hooks to the DeprecatedHooks::$deprecatedHooks array literal. New extension code should
	 *     use the DeprecatedHooks attribute.
	 *   - silent: (bool) If true, do not raise a deprecation warning
	 *   - noServices: (bool) If true, do not allow hook handlers with service dependencies
	 * @return bool True if no handler aborted the hook
	 * @throws UnexpectedValueException if handlers return an invalid value
	 */
	public function run( string $hook, array $args = [], array $options = [] ) : bool {
		$legacyHandlers = $this->getLegacyHandlers( $hook );
		$options = array_merge(
			$this->registry->getDeprecatedHooks()->getDeprecationInfo( $hook ) ?? [],
			$options
		);
		// Equivalent of legacy Hooks::runWithoutAbort()
		$notAbortable = ( isset( $options['abortable'] ) && $options['abortable'] === false );
		foreach ( $legacyHandlers as $handler ) {
			$normalizedHandler = $this->normalizeHandler( $handler, $hook );
			if ( $normalizedHandler ) {
				$functionName = $normalizedHandler['functionName'];
				$return = $this->callLegacyHook( $hook, $normalizedHandler, $args, $options );
				if ( $notAbortable && $return !== null && $return !== true ) {
					throw new UnexpectedValueException( "Invalid return from $functionName" .
						" for unabortable $hook." );
				}
				if ( $return === false ) {
					return false;
				}
				if ( is_string( $return ) ) {
					wfDeprecatedMsg(
						"Returning a string from a hook handler is deprecated since MediaWiki 1.35 ' .
						 '(done by $functionName for $hook)",
						'1.35', false, false
					);
					throw new UnexpectedValueException( $return );
				}
			}
		}

		$handlers = $this->getHandlers( $hook, $options );
		$funcName = 'on' . strtr( ucfirst( $hook ), ':-', '__' );

		foreach ( $handlers as $handler ) {
			$return = $handler->$funcName( ...$args );
			if ( $notAbortable && $return !== null && $return !== true ) {
				throw new UnexpectedValueException(
					"Invalid return from " . $funcName . " for unabortable $hook."
				);
			}
			if ( $return === false ) {
				return false;
			}
			if ( $return !== null && !is_bool( $return ) ) {
				throw new UnexpectedValueException( "Invalid return from " . $funcName . " for $hook." );
			}
		}
		return true;
	}

	/**
	 * Clear hooks registered via Hooks::register().
	 * This is intended for use while testing and will fail if MW_PHPUNIT_TEST
	 * and MW_PARSER_TEST are not defined.
	 *
	 * @param string $hook Name of hook to clear
	 *
	 * @internal For use by Hooks.php
	 * @throws MWException If not in testing mode.
	 * @codeCoverageIgnore
	 */
	public function clear( string $hook ) : void {
		if ( !defined( 'MW_PHPUNIT_TEST' ) && !defined( 'MW_PARSER_TEST' ) ) {
			throw new MWException( 'Cannot reset hooks in operation.' );
		}
		unset( $this->legacyRegisteredHandlers[$hook] ); // dynamically registered legacy handlers
	}

	/**
	 * Register hook and handler, allowing for easy removal.
	 * Intended for use in temporary registration e.g. testing
	 *
	 * @param string $hook Name of hook
	 * @param callable|string|array $callback Handler object to attach
	 * @param bool $replace (optional) By default adds callback to handler array.
	 *        Set true to remove all existing callbacks for the hook.
	 * @return ScopedCallback
	 */
	public function scopedRegister( string $hook, $callback, bool $replace = false ) : ScopedCallback {
		if ( $replace ) {
			// Stash any previously registered hooks
			if ( !isset( $this->originalHooks[$hook] ) &&
				isset( $this->legacyRegisteredHandlers[$hook] )
			) {
				$this->originalHooks[$hook] = $this->legacyRegisteredHandlers[$hook];
			}
			$this->legacyRegisteredHandlers[$hook] = [ $callback ];
			return new ScopedCallback( function () use ( $hook ) {
				unset( $this->legacyRegisteredHandlers[$hook] );
			} );
		}
		$id = $this->nextScopedRegisterId++;
		$this->legacyRegisteredHandlers[$hook][$id] = $callback;
		return new ScopedCallback( function () use ( $hook, $id ) {
			unset( $this->legacyRegisteredHandlers[$hook][$id] );
		} );
	}

	/**
	 * Return hooks that were set before being potentially overridden by scopedRegister().
	 * For use in restoring registered hook handlers between tests.
	 *
	 * @return array Associative array mapping hook names to array of handlers
	 * @throws MWException
	 */
	public function getOriginalHooksForTest() {
		if ( !defined( 'MW_PHPUNIT_TEST' ) && !defined( 'MW_PARSER_TEST' ) ) {
			throw new MWException( 'Cannot get original hooks outside when not in test mode' );
		}
		return $this->originalHooks ?? [];
	}

	/**
	 * Normalize/clean up format of argument passed as hook handler
	 *
	 * @param array|callable $handler Executable handler function
	 * @param string $hook Hook name
	 * @return array|false
	 *  - handler: (callable) Executable handler function
	 *  - functionName: (string) Handler name for passing to wfDeprecated() or Exceptions thrown
	 *  - args: (array) handler function arguments
	 */
	private function normalizeHandler( $handler, string $hook ) {
		$normalizedHandler = $handler;
		if ( !is_array( $handler ) ) {
			$normalizedHandler = [ $normalizedHandler ];
		}

		// Empty array or array filled with null/false/empty.
		if ( !array_filter( $normalizedHandler ) ) {
			return false;
		}

		if ( is_array( $normalizedHandler[0] ) ) {
			// First element is an array, meaning the developer intended
			// the first element to be a callback. Merge it in so that
			// processing can be uniform.
			$normalizedHandler = array_merge( $normalizedHandler[0], array_slice( $normalizedHandler, 1 ) );
		}

		$firstArg = $normalizedHandler[0];

		// Extract function name, handler object, and any arguments for handler object
		if ( $firstArg instanceof Closure ) {
			$functionName = "hook-$hook-closure";
			$callback = array_shift( $normalizedHandler );
		} elseif ( is_object( $firstArg ) ) {
			$object = array_shift( $normalizedHandler );
			$functionName = array_shift( $normalizedHandler );

			// If no method was specified, default to on$event
			if ( $functionName === null ) {
				$functionName = "on$hook";
			} else {
				$colonPos = strpos( $functionName, '::' );
				if ( $colonPos !== false ) {
					// Some extensions use [ $object, 'Class::func' ] which
					// worked with call_user_func_array() but doesn't work now
					// that we use a plain varadic call
					$functionName = substr( $functionName, $colonPos + 2 );
				}
			}

			$callback = [ $object, $functionName ];
		} elseif ( is_string( $firstArg ) ) {
			$functionName = $callback = array_shift( $normalizedHandler );
		} else {
			throw new UnexpectedValueException( 'Unknown datatype in hooks for ' . $hook );
		}
		return [
			'callback' => $callback,
			'args' => $normalizedHandler,
			'functionName' => $functionName,
		];
	}

	/**
	 * Run legacy hooks
	 * Hook can be: a function, an object, an array of $function and
	 * $data, an array of just a function, an array of object and
	 * method, or an array of object, method, and data
	 * (See hooks.txt for more details)
	 *
	 * @param string $hook
	 * @param array|callable $handler The name of the hooks handler function
	 * @param array $args Arguments for hook handler function
	 * @param array $options
	 * @return null|string|bool
	 */
	private function callLegacyHook( string $hook, $handler, array $args, array $options ) {
		$callback = $handler['callback'];
		$hookArgs = array_merge( $handler['args'], $args );
		if ( isset( $options['deprecatedVersion'] ) && empty( $options['silent'] ) ) {
			wfDeprecated(
				"$hook hook (used in " . $handler['functionName'] . ")",
				$options['deprecatedVersion'] ?? false,
				$options['component'] ?? false
			);
		}
		// Call the hooks
		return $callback( ...$hookArgs );
	}

	/**
	 * Return whether hook has any handlers registered to it.
	 * The function may have been registered via Hooks::register or in extension.json
	 *
	 * @param string $hook Name of hook
	 * @return bool Whether the hook has a handler registered to it
	 */
	public function isRegistered( string $hook ) : bool {
		$legacyRegisteredHook = !empty( $this->registry->getGlobalHooks()[$hook] ) ||
			!empty( $this->legacyRegisteredHandlers[$hook] );
		$registeredHooks = $this->registry->getExtensionHooks();
		return !empty( $registeredHooks[$hook] ) || $legacyRegisteredHook;
	}

	/**
	 * Attach an event handler to a given hook.
	 *
	 * @param string $hook Name of hook
	 * @param callable|string|array $callback handler object to attach
	 */
	public function register( string $hook, $callback ) {
		$deprecatedHooks = $this->registry->getDeprecatedHooks();
		$deprecated = $deprecatedHooks->isHookDeprecated( $hook );
		if ( $deprecated ) {
			$info = $deprecatedHooks->getDeprecationInfo( $hook );
			if ( empty( $info['silent'] ) ) {
				$deprecatedVersion = $info['deprecatedVersion'] ?? false;
				$component = $info['component'] ?? false;
				wfDeprecated(
					"$hook hook", $deprecatedVersion, $component
				);
			}
		}
		$this->legacyRegisteredHandlers[$hook][] = $callback;
	}

	/**
	 * Get all handlers for legacy hooks system
	 *
	 * @internal For use by Hooks.php
	 * @param string $hook Name of hook
	 * @return array function names
	 */
	public function getLegacyHandlers( string $hook ) : array {
		$handlers = array_merge(
			$this->legacyRegisteredHandlers[$hook] ?? [],
			$this->registry->getGlobalHooks()[$hook] ?? []
		);
		return $handlers;
	}

	/**
	 * Return array of handler objects registered with given hook in the new system
	 * @internal For use by Hooks.php
	 * @param string $hook Name of the hook
	 * @param array $options Handler options, which may include:
	 *   - noServices: Do not allow hook handlers with service dependencies
	 * @return array non-deprecated handler objects
	 */
	public function getHandlers( string $hook, array $options = [] ) : array {
		$handlers = [];
		$deprecatedHooks = $this->registry->getDeprecatedHooks();
		$registeredHooks = $this->registry->getExtensionHooks();
		if ( isset( $registeredHooks[$hook] ) ) {
			foreach ( $registeredHooks[$hook] as $hookReference ) {
				// Non-legacy hooks have handler attributes
				$handlerSpec = $hookReference['handler'];
				// Skip hooks that both acknowledge deprecation and are deprecated in core
				$flaggedDeprecated = !empty( $hookReference['deprecated'] );
				$deprecated = $deprecatedHooks->isHookDeprecated( $hook );
				if ( $deprecated && $flaggedDeprecated ) {
					continue;
				}
				$handlerName = $handlerSpec['name'];
				if ( !empty( $options['noServices'] ) && isset( $handlerSpec['services'] ) ) {
					throw new UnexpectedValueException(
						"The handler for the hook $hook registered in " .
						"{$hookReference['extensionPath']} has a service dependency, " .
						"but this hook does not allow it." );
				}
				if ( !isset( $this->handlersByName[$handlerName] ) ) {
					$this->handlersByName[$handlerName] =
						$this->objectFactory->createObject( $handlerSpec );
				}
				$handlers[] = $this->handlersByName[$handlerName];
			}
		}
		return $handlers;
	}

	/**
	 * Will log a deprecation warning if:
	 * 1. the hook is marked deprecated
	 * 2. the "silent" flag is absent or false, and
	 * 3. an extension registers a handler in the new way but does not acknowledge deprecation
	 */
	public function emitDeprecationWarnings() {
		$deprecatedHooks = $this->registry->getDeprecatedHooks();
		$registeredHooks = $this->registry->getExtensionHooks();
		foreach ( $registeredHooks as $name => $handlers ) {
			if ( $deprecatedHooks->isHookDeprecated( $name ) ) {
				$deprecationInfo = $deprecatedHooks->getDeprecationInfo( $name );
				if ( !empty( $deprecationInfo['silent'] ) ) {
					continue;
				}
				$version = $deprecationInfo['deprecatedVersion'] ?? '';
				$component = $deprecationInfo['component'] ?? 'MediaWiki';
				foreach ( $handlers as $handler ) {
					if ( !isset( $handler['deprecated'] ) || !$handler['deprecated'] ) {
						MWDebug::sendRawDeprecated(
							"Hook $name was deprecated in $component $version " .
							"but is registered in " . $handler['extensionPath']
						);
					}
				}
			}
		}
	}
}
