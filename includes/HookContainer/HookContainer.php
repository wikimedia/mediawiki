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
use InvalidArgumentException;
use LogicException;
use MWDebug;
use MWException;
use UnexpectedValueException;
use Wikimedia\Assert\Assert;
use Wikimedia\NonSerializable\NonSerializableTrait;
use Wikimedia\ObjectFactory\ObjectFactory;
use Wikimedia\ScopedCallback;
use Wikimedia\Services\SalvageableService;
use function array_filter;
use function array_keys;
use function array_merge;
use function array_shift;
use function array_unique;
use function is_array;
use function is_object;
use function is_string;
use function strpos;
use function strtr;

/**
 * HookContainer class.
 *
 * Main class for managing hooks
 *
 * @since 1.35
 */
class HookContainer implements SalvageableService {
	use NonSerializableTrait;

	public const NOOP = '*no-op*';

	/**
	 * Normalized hook handlers, as a 3D array:
	 * - the first level maps hook names to lists of handlers
	 * - the second is a list of handlers
	 * - each handler is an associative array with some well known keys, as returned by normalizeHandler()
	 * @var array<array>
	 */
	private $handlers = [];

	/** @var array<object> handler name and their handler objects */
	private $handlerObjects = [];

	/** @var HookRegistry */
	private $registry;

	/** @var ObjectFactory */
	private $objectFactory;

	/** @var int The next ID to be used by scopedRegister() */
	private $nextScopedRegisterId = 0;

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
		if ( $this->handlers || $this->handlerObjects ) {
			throw new MWException( 'salvage() must be called immediately after construction' );
		}
		$this->handlerObjects = $other->handlerObjects;
		$this->handlers = $other->handlers;
	}

	/**
	 * Call registered hook functions through either the legacy $wgHooks or extension.json
	 *
	 * For the given hook, fetch the array of handler objects and
	 * process them. Determine the proper callback for each hook and
	 * then call the actual hook using the appropriate arguments.
	 * Finally, process the return value and return/throw accordingly.
	 *
	 * @param string $hook Name of the hook
	 * @param array $args Arguments to pass to hook handler
	 * @param array $options options map:
	 *   - abortable: (bool) If false, handlers will not be allowed to abort the call sequence.
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
	public function run( string $hook, array $args = [], array $options = [] ): bool {
		$checkDeprecation = isset( $options['deprecatedVersion'] );

		$abortable = $options['abortable'] ?? true;
		foreach ( $this->getHandlers( $hook, $options ) as $handler ) {
			// The handler is "shadowed" by a scoped hook handler.
			if ( ( $handler['skip'] ?? 0 ) > 0 ) {
				continue;
			}

			if ( $checkDeprecation ) {
				$this->checkDeprecation( $hook, $handler['functionName'], $options );
			}

			// Compose callback arguments.
			if ( !empty( $handler['args'] ) ) {
				$callbackArgs = array_merge( $handler['args'], $args );
			} else {
				$callbackArgs = $args;
			}
			// Call the handler.
			$callback = $handler['callback'];
			$return = $callback( ...$callbackArgs );

			// Handler returned false, signal abort to caller
			if ( $return === false ) {
				if ( !$abortable ) {
					throw new UnexpectedValueException( "Handler {$handler['functionName']}" .
						" return false for unabortable $hook." );
				}

				return false;
			} elseif ( $return !== null && $return !== true ) {
				throw new UnexpectedValueException(
					"Hook handlers can only return null or a boolean. Got an unexpected value from " .
					"handler {$handler['functionName']} for $hook" );
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
	public function clear( string $hook ): void {
		if ( !defined( 'MW_PHPUNIT_TEST' ) && !defined( 'MW_PARSER_TEST' ) ) {
			throw new MWException( 'Cannot reset hooks in operation.' );
		}

		$this->handlers[$hook] = [];
	}

	/**
	 * Register hook and handler, allowing for easy removal.
	 * Intended for use in temporary registration e.g. testing
	 *
	 * @param string $hook Name of hook
	 * @param callable|string|array $handler Handler to attach
	 * @param bool $replace (optional) Set true to disable existing handlers for the hook while
	 *        the scoped handler is active.
	 * @return ScopedCallback
	 */
	public function scopedRegister( string $hook, $handler, bool $replace = false ): ScopedCallback {
		$handler = $this->normalizeHandler( $hook, $handler );
		if ( !$handler ) {
			throw new InvalidArgumentException( 'Bad hook handler!' );
		}

		$this->checkDeprecation( $hook, $handler['functionName'] );

		$id = 'TemporaryHook_' . $this->nextScopedRegisterId++;

		$this->getHandlers( $hook );

		$this->handlers[$hook][$id] = $handler;
		if ( $replace ) {
			$this->updateSkipCounter( $hook, 1, $id );
		}

		return new ScopedCallback( function () use ( $hook, $id, $replace ) {
			if ( $replace ) {
				$this->updateSkipCounter( $hook, -1, $id );
			}
			unset( $this->handlers[$hook][$id] );
		} );
	}

	/**
	 * Utility for scopedRegister() for updating the counter in the 'skip'
	 * field of each handler of the given hook, up to the given index.
	 *
	 * The idea is that handlers with a non-zero 'skip' count will be ignored.
	 * This allows a call to scopedRegister to temporarily disable all previously
	 * registered handlers, and automatically re-enable them when the temporary
	 * handler goes out of scope.
	 *
	 * @param string $hook
	 * @param int $n The amount by which to update the count (1 to disable handlers,
	 *        -1 to re-enable them)
	 * @param int|string $upTo The index at which to stop the update. This will be the
	 *        index of the temporary handler registered with scopedRegister.
	 *
	 * @return void
	 */
	private function updateSkipCounter( string $hook, $n, $upTo ) {
		foreach ( $this->handlers[$hook] as $i => $unused ) {
			if ( $i === $upTo ) {
				break;
			}

			$current = $this->handlers[$hook][$i]['skip'] ?? 0;
			$this->handlers[$hook][$i]['skip'] = $current + $n;
		}
	}

	/**
	 * Returns a callable array based on the handler specification provided.
	 * This will find the appropriate handler object to call a method on,
	 * instantiating it if it doesn't exist yet.
	 *
	 * @param string $hook The name of the hook the handler was registered for
	 * @param array $handler A hook handler specification as given in an extension.json file.
	 * @param array $options Options to apply. If the 'noServices' option is set and the
	 *              handler requires service injection, this method will throw an
	 *              UnexpectedValueException.
	 *
	 * @return array
	 */
	private function makeExtensionHandlerCallback( string $hook, array $handler, array $options = [] ): array {
		$spec = $handler['handler'];
		$name = $spec['name'];

		if (
			!empty( $options['noServices'] ) && (
				!empty( $spec['services'] ) ||
				!empty( $spec['optional_services'] )
			)
		) {
			throw new UnexpectedValueException(
				"The handler for the hook $hook registered in " .
				"{$handler['extensionPath']} has a service dependency, " .
				"but this hook does not allow it." );
		}

		if ( !isset( $this->handlerObjects[$name] ) ) {
			// @phan-suppress-next-line PhanTypeInvalidCallableArraySize
			$this->handlerObjects[$name] = $this->objectFactory->createObject( $spec );
		}

		$obj = $this->handlerObjects[$name];
		$method = $this->getHookMethodName( $hook );

		return [ $obj, $method ];
	}

	/**
	 * Normalize/clean up format of argument passed as hook handler
	 *
	 * @param string $hook Hook name
	 * @param string|array|callable $handler Executable handler function. See register() for supported structures.
	 * @param array $options
	 *
	 * @return array|false
	 *  - callback: (callable) Executable handler function
	 *  - functionName: (string) Handler name for passing to wfDeprecated() or Exceptions thrown
	 *  - args: (array) Extra handler function arguments (omitted when not needed)
	 */
	private function normalizeHandler( string $hook, $handler, array $options = [] ) {
		if ( is_object( $handler ) && !$handler instanceof Closure ) {
			$handler = [ $handler, $this->getHookMethodName( $hook ) ];
		}

		// Backwards compatibility with old-style callable that uses a qualified method name.
		if ( is_array( $handler ) && is_object( $handler[0] ?? false ) && is_string( $handler[1] ?? false ) ) {
			$ofs = strpos( $handler[1], '::' );

			if ( $ofs !== false ) {
				$handler[1] = substr( $handler[1], $ofs + 2 );
			}
		}

		// Backwards compatibility: support objects wrapped in an array but no method name.
		if ( is_array( $handler ) && is_object( $handler[0] ?? false ) && !isset( $handler[1] ) ) {
			if ( !$handler[0] instanceof Closure ) {
				$handler[1] = $this->getHookMethodName( $hook );
			}
		}

		// The empty callback is used to represent a no-op handler in some test cases.
		if ( $handler === [] || $handler === null || $handler === false || $handler === self::NOOP ) {
			return [
				'callback' => static function () {
					// no-op
				},
				'functionName' => self::NOOP,
			];
		}

		// Plain callback
		if ( is_callable( $handler ) ) {
			return [
				'callback' => $handler,
				'functionName' => self::callableToString( $handler ),
			];
		}

		// Not callable and not an array. Something is wrong.
		if ( !is_array( $handler ) ) {
			return false;
		}

		// Empty array or array filled with null/false/empty.
		if ( !array_filter( $handler ) ) {
			return false;
		}

		// ExtensionRegistry style handler
		if ( isset( $handler['handler'] ) ) {
			// Skip hooks that both acknowledge deprecation and are deprecated in core
			if ( $handler['deprecated'] ?? false ) {
				$deprecatedHooks = $this->registry->getDeprecatedHooks();
				$deprecated = $deprecatedHooks->isHookDeprecated( $hook );
				if ( $deprecated ) {
					return false;
				}
			}

			$callback = $this->makeExtensionHandlerCallback( $hook, $handler, $options );
			return [
				'callback' => $callback,
				'functionName' => self::callableToString( $callback ),
			];
		}

		// Not an indexed array, something is wrong.
		if ( !isset( $handler[0] ) ) {
			return false;
		}

		// Backwards compatibility: support for arrays of the form [ $object, $method, $data... ]
		if ( is_object( $handler[0] ) && is_string( $handler[1] ?? false ) && array_key_exists( 2, $handler ) ) {
			$obj = $handler[0];
			if ( !$obj instanceof Closure ) {
				// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset
				$method = $handler[1];
				$handler = array_merge(
					[ [ $obj, $method ] ],
					array_slice( $handler, 2 )
				);
			}
		}

		// The only option left is an array of the form [ $callable, $data... ]
		$callback = array_shift( $handler );

		// Backwards-compatibility for callbacks in the form [ [ $function ], $data ]
		if ( is_array( $callback ) && count( $callback ) === 1 && is_string( $callback[0] ?? null ) ) {
			$callback = $callback[0];
		}

		if ( !is_callable( $callback ) ) {
			return false;
		}

		return [
			'callback' => $callback,
			'functionName' => self::callableToString( $callback ),
			'args' => $handler,
		];
	}

	/**
	 * Return whether hook has any handlers registered to it.
	 * The function may have been registered via Hooks::register or in extension.json
	 *
	 * @param string $hook Name of hook
	 * @return bool Whether the hook has a handler registered to it
	 */
	public function isRegistered( string $hook ): bool {
		return !empty( $this->getHandlers( $hook ) );
	}

	/**
	 * Attach an event handler to a given hook.
	 *
	 * The handler should be given in one of the following forms:
	 *
	 * 1) A callable (string, array, or closure)
	 * 2) An extension hook handler spec in the form returned by
	 *    HookRegistry::getExtensionHooks
	 *
	 * Several other forms are supported for backwards compatibility, but
	 * should not be used when calling this method directly.
	 *
	 * @param string $hook Name of hook
	 * @param string|array|callable $handler handler
	 */
	public function register( string $hook, $handler ) {
		$normalized = $this->normalizeHandler( $hook, $handler );
		if ( !$normalized ) {
			throw new InvalidArgumentException( 'Bad hook handler!' );
		}

		$this->checkDeprecation( $hook, $normalized['functionName'] );

		$this->getHandlers( $hook );
		$this->handlers[$hook][] = $normalized;
	}

	/**
	 * Get handler callbacks.
	 *
	 * @internal For use by FauxHookHandlerArray. Delete when no longer needed.
	 * @param string $hook Name of hook
	 * @return callable[]
	 */
	public function getHandlerCallbacks( string $hook ): array {
		$handlers = $this->getHandlers( $hook );

		$callbacks = [];
		foreach ( $handlers as $h ) {
			if ( ( $h['skip'] ?? 0 ) > 0 ) {
				continue;
			}

			$callback = $h['callback'];

			if ( isset( $h['args'] ) ) {
				// Needs curry in order to pass extra arguments.
				// NOTE: This does not support reference parameters!
				$extraArgs = $h['args'];
				$callbacks[] = static function ( ...$hookArgs ) use ( $callback, $extraArgs ) {
					return $callback( ...$extraArgs, ...$hookArgs );
				};
			} else {
				$callbacks[] = $callback;
			}
		}

		return $callbacks;
	}

	/**
	 * Returns the names of all hooks that have at least one handler registered.
	 * @return string[]
	 */
	public function getHookNames(): array {
		$names = array_merge(
			array_keys( array_filter( $this->handlers ) ),
			array_keys( array_filter( $this->registry->getGlobalHooks() ) ),
			array_keys( array_filter( $this->registry->getExtensionHooks() ) )
		);

		return array_unique( $names );
	}

	/**
	 * Return the array of handlers for the given hook.
	 *
	 * @param string $hook Name of the hook
	 * @param array $options Handler options, which may include:
	 *   - noServices: Do not allow hook handlers with service dependencies
	 * @return array[] A list of handler entries
	 */
	private function getHandlers( string $hook, array $options = [] ): array {
		if ( !isset( $this->handlers[$hook] ) ) {
			$handlers = [];
			$registeredHooks = $this->registry->getExtensionHooks();
			$configuredHooks = $this->registry->getGlobalHooks();

			$rawHandlers = array_merge(
				$configuredHooks[ $hook ] ?? [],
				$registeredHooks[ $hook ] ?? []
			);

			foreach ( $rawHandlers as $raw ) {
				$handler = $this->normalizeHandler( $hook, $raw, $options );
				if ( !$handler ) {
					// XXX: log this?!
					// NOTE: also happens for deprecated hooks, which is fine!
					continue;
				}

				$handlers[] = $handler;
			}

			$this->handlers[ $hook ] = $handlers;
		}

		return $this->handlers[ $hook ];
	}

	/**
	 * Return the array of strings that describe the handler registered with the given hook.
	 *
	 * @internal Only public for use by ApiQuerySiteInfo.php and SpecialVersion.php
	 * @param string $hook Name of the hook
	 * @return string[] A list of handler descriptions
	 */
	public function getHandlerDescriptions( string $hook ): array {
		$descriptions = [];
		$registeredHooks = $this->registry->getExtensionHooks();
		$configuredHooks = $this->registry->getGlobalHooks();

		$rawHandlers = array_merge(
			$configuredHooks[ $hook ] ?? [],
			$registeredHooks[ $hook ] ?? [],
			$this->handlers[ $hook ] ?? []
		);

		foreach ( $rawHandlers as $raw ) {
			$descr = $this->describeHandler( $hook, $raw );

			if ( $descr ) {
				$descriptions[] = $descr;
			}
		}

		return $descriptions;
	}

	/**
	 * Returns a human-readable description of the given handler.
	 *
	 * @param string $hook
	 * @param string|array|callable $handler
	 *
	 * @return ?string
	 */
	private function describeHandler( string $hook, $handler ): ?string {
		if ( is_array( $handler ) ) {
			// already normalized
			if ( isset( $handler['functionName'] ) ) {
				return $handler['functionName'];
			}

			if ( isset( $handler['callback'] ) ) {
				return self::callableToString( $handler['callback'] );
			}

			if ( isset( $handler['handler']['class'] ) ) {
				// New style hook. Avoid instantiating the handler object
				$method = $this->getHookMethodName( $hook );
				return $handler['handler']['class'] . '::' . $method;
			}
		}

		$handler = $this->normalizeHandler( $hook, $handler );
		return $handler ? $handler['functionName'] : null;
	}

	/**
	 * For each hook handler of each hook, this will log a deprecation if:
	 * 1. the hook is marked deprecated and
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

	/**
	 * Will trigger a deprecation warning if the given hook is deprecated and the deprecation
	 * is not marked as silent.
	 *
	 * @param string $hook The name of the hook.
	 * @param string $functionName The human-readable description of the handler
	 * @param array|null $deprecationInfo Deprecation info if the caller already knows it.
	 *        If not given, it will be looked up from the hook registry.
	 *
	 * @return void
	 */
	private function checkDeprecation( string $hook, string $functionName, array $deprecationInfo = null ): void {
		if ( !$deprecationInfo ) {
			$deprecatedHooks = $this->registry->getDeprecatedHooks();
			$deprecationInfo = $deprecatedHooks->getDeprecationInfo( $hook );
		}

		if ( $deprecationInfo && empty( $deprecationInfo['silent'] ) ) {
			wfDeprecated(
				"$hook hook (used in " . $functionName . ")",
				$deprecationInfo['deprecatedVersion'] ?? false,
				$deprecationInfo['component'] ?? false
			);
		}
	}

	/**
	 * Returns a human-readable representation of the given callable.
	 *
	 * @param callable $callable
	 *
	 * @return string
	 */
	private static function callableToString( $callable ): string {
		if ( is_string( $callable ) ) {
			return $callable;
		}

		if ( $callable instanceof Closure ) {
			return '*closure*';
		}

		if ( is_array( $callable ) ) {
			[ $on, $func ] = $callable;

			if ( is_object( $on ) ) {
				$on = get_class( $on );
			}

			return "$on::$func";
		}

		throw new LogicException( 'Unexpected kind of callable' );
	}

	/**
	 * Returns the default handler method name for the given hook.
	 *
	 * @param string $hook
	 *
	 * @return string
	 */
	private function getHookMethodName( string $hook ): string {
		$hook = strtr( $hook, ':\\-', '___' );
		return "on$hook";
	}
}
