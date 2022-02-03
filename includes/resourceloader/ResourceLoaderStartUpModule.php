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
 * @author Trevor Parscal
 * @author Roan Kattouw
 */

use Wikimedia\RequestTimeout\TimeoutException;

/**
 * Module for ResourceLoader initialization.
 *
 * See also <https://www.mediawiki.org/wiki/ResourceLoader/Features#Startup_Module>
 *
 * The startup module, as being called only from ResourceLoaderClientHtml, has
 * the ability to vary based extra query parameters, in addition to those
 * from ResourceLoaderContext:
 *
 * - target: Only register modules in the client intended for this target.
 *   Default: "desktop".
 *   See also: OutputPage::setTarget(), ResourceLoaderModule::getTargets().
 *
 * - safemode: Only register modules that have ORIGIN_CORE as their origin.
 *   This effectively disables ORIGIN_USER modules. (T185303)
 *   See also: OutputPage::disallowUserJs()
 *
 * @ingroup ResourceLoader
 * @internal
 */
class ResourceLoaderStartUpModule extends ResourceLoaderModule {
	protected $targets = [ 'desktop', 'mobile' ];

	private $groupIds = [
		// These reserved numbers MUST start at 0 and not skip any. These are preset
		// for forward compatibility so that they can be safely referenced by mediawiki.js,
		// even when the code is cached and the order of registrations (and implicit
		// group ids) changes between versions of the software.
		self::GROUP_USER => 0,
		self::GROUP_PRIVATE => 1,
	];

	/**
	 * Recursively get all explicit and implicit dependencies for to the given module.
	 *
	 * @param array $registryData
	 * @param string $moduleName
	 * @param string[] $handled Internal parameter for recursion. (Optional)
	 * @return array
	 * @throws ResourceLoaderCircularDependencyError
	 */
	protected static function getImplicitDependencies(
		array $registryData,
		string $moduleName,
		array $handled = []
	): array {
		static $dependencyCache = [];

		// No modules will be added or changed server-side after this point,
		// so we can safely cache parts of the tree for re-use.
		if ( !isset( $dependencyCache[$moduleName] ) ) {
			if ( !isset( $registryData[$moduleName] ) ) {
				// Unknown module names are allowed here, this is only an optimisation.
				// Checks for illegal and unknown dependencies happen as PHPUnit structure tests,
				// and also client-side at run-time.
				$flat = [];
			} else {
				$data = $registryData[$moduleName];
				$flat = $data['dependencies'];

				// Prevent recursion
				$handled[] = $moduleName;
				foreach ( $data['dependencies'] as $dependency ) {
					if ( in_array( $dependency, $handled, true ) ) {
						// If we encounter a circular dependency, then stop the optimiser and leave the
						// original dependencies array unmodified. Circular dependencies are not
						// supported in ResourceLoader. Awareness of them exists here so that we can
						// optimise the registry when it isn't broken, and otherwise transport the
						// registry unchanged. The client will handle this further.
						throw new ResourceLoaderCircularDependencyError();
					} else {
						// Recursively add the dependencies of the dependencies
						$flat = array_merge(
							$flat,
							self::getImplicitDependencies( $registryData, $dependency, $handled )
						);
					}
				}
			}

			$dependencyCache[$moduleName] = $flat;
		}

		return $dependencyCache[$moduleName];
	}

	/**
	 * Optimize the dependency tree in $this->modules.
	 *
	 * The optimization basically works like this:
	 * 	Given we have module A with the dependencies B and C
	 * 		and module B with the dependency C.
	 * 	Now we don't have to tell the client to explicitly fetch module
	 * 		C as that's already included in module B.
	 *
	 * This way we can reasonably reduce the amount of module registration
	 * data send to the client.
	 *
	 * @param array[] &$registryData Modules keyed by name with properties:
	 *  - string 'version'
	 *  - array 'dependencies'
	 *  - string|null 'group'
	 *  - string 'source'
	 * @phan-param array<string,array{version:string,dependencies:array,group:?string,source:string}> &$registryData
	 */
	public static function compileUnresolvedDependencies( array &$registryData ): void {
		foreach ( $registryData as $name => &$data ) {
			$dependencies = $data['dependencies'];
			try {
				foreach ( $data['dependencies'] as $dependency ) {
					$implicitDependencies = self::getImplicitDependencies( $registryData, $dependency );
					$dependencies = array_diff( $dependencies, $implicitDependencies );
				}
			} catch ( ResourceLoaderCircularDependencyError $err ) {
				// Leave unchanged
				$dependencies = $data['dependencies'];
			}

			// Rebuild keys
			$data['dependencies'] = array_values( $dependencies );
		}
	}

	/**
	 * Get registration code for all modules.
	 *
	 * @param ResourceLoaderContext $context
	 * @return string JavaScript code for registering all modules with the client loader
	 */
	public function getModuleRegistrations( ResourceLoaderContext $context ): string {
		$resourceLoader = $context->getResourceLoader();
		// Future developers: Use WebRequest::getRawVal() instead getVal().
		// The getVal() method performs slow Language+UTF logic. (f303bb9360)
		$target = $context->getRequest()->getRawVal( 'target', 'desktop' );
		$safemode = $context->getRequest()->getRawVal( 'safemode' ) === '1';
		// Bypass target filter if this request is Special:JavaScriptTest.
		// To prevent misuse in production, this is only allowed if testing is enabled server-side.
		$byPassTargetFilter = $this->getConfig()->get( 'EnableJavaScriptTest' ) && $target === 'test';

		$out = '';
		$states = [];
		$registryData = [];
		$moduleNames = $resourceLoader->getModuleNames();

		// Preload with a batch so that the below calls to getVersionHash() for each module
		// don't require on-demand loading of more information.
		try {
			$resourceLoader->preloadModuleInfo( $moduleNames, $context );
		} catch ( TimeoutException $e ) {
			throw $e;
		} catch ( Exception $e ) {
			// Don't fail the request (T152266)
			// Also print the error in the main output
			$resourceLoader->outputErrorAndLog( $e,
				'Preloading module info from startup failed: {exception}',
				[ 'exception' => $e ]
			);
		}

		// Get registry data
		foreach ( $moduleNames as $name ) {
			$module = $resourceLoader->getModule( $name );
			$moduleTargets = $module->getTargets();
			if (
				( !$byPassTargetFilter && !in_array( $target, $moduleTargets ) )
				|| ( $safemode && $module->getOrigin() > ResourceLoaderModule::ORIGIN_CORE_INDIVIDUAL )
			) {
				continue;
			}

			if ( $module instanceof ResourceLoaderStartUpModule ) {
				// Don't register 'startup' to the client because loading it lazily or depending
				// on it doesn't make sense, because the startup module *is* the client.
				// Registering would be a waste of bandwidth and memory and risks somehow causing
				// it to load a second time.

				// ATTENTION: Because of the line below, this is not going to cause infinite recursion.
				// Think carefully before making changes to this code!
				// The below code is going to call ResourceLoaderModule::getVersionHash() for every module.
				// For StartUpModule (this module) the hash is computed based on the manifest content,
				// which is the very thing we are computing right here. As such, this must skip iterating
				// over 'startup' itself.
				continue;
			}

			// Optimization: Exclude modules in the `noscript` group. These are only ever used
			// directly by HTML without use of JavaScript (T291735).
			if ( $module->getGroup() === self::GROUP_NOSCRIPT ) {
				continue;
			}

			try {
				// The version should be formatted by ResourceLoader::makeHash and be of
				// length ResourceLoader::HASH_LENGTH (or empty string).
				// The getVersionHash method is final and is covered by tests, as is makeHash().
				$versionHash = $module->getVersionHash( $context );
			} catch ( TimeoutException $e ) {
				throw $e;
			} catch ( Exception $e ) {
				// Don't fail the request (T152266)
				// Also print the error in the main output
				$resourceLoader->outputErrorAndLog( $e,
					'Calculating version for "{module}" failed: {exception}',
					[
						'module' => $name,
						'exception' => $e,
					]
				);
				$versionHash = '';
				$states[$name] = 'error';
			}

			$skipFunction = $module->getSkipFunction();
			if ( $skipFunction !== null && !$context->getDebug() ) {
				$skipFunction = ResourceLoader::filter( 'minify-js', $skipFunction );
			}

			$registryData[$name] = [
				'version' => $versionHash,
				'dependencies' => $module->getDependencies( $context ),
				'es6' => $module->requiresES6(),
				'group' => $this->getGroupId( $module->getGroup() ),
				'source' => $module->getSource(),
				'skip' => $skipFunction,
			];
		}

		self::compileUnresolvedDependencies( $registryData );

		// Register sources
		$out .= ResourceLoader::makeLoaderSourcesScript( $context, $resourceLoader->getSources() );

		// Figure out the different call signatures for mw.loader.register
		$registrations = [];
		foreach ( $registryData as $name => $data ) {
			// Call mw.loader.register(name, version, dependencies, group, source, skip)
			$registrations[] = [
				$name,
				// HACK: signify ES6 with a ! added at the end of the version
				// This avoids having to add another register() parameter, and generating
				// a bunch of nulls for ES6-only modules
				$data['version'] . ( $data['es6'] ? '!' : '' ),
				$data['dependencies'],
				$data['group'],
				// Swap default (local) for null
				$data['source'] === 'local' ? null : $data['source'],
				$data['skip']
			];
		}

		// Register modules
		$out .= "\n" . ResourceLoader::makeLoaderRegisterScript( $context, $registrations );

		if ( $states ) {
			$out .= "\n" . ResourceLoader::makeLoaderStateScript( $context, $states );
		}

		return $out;
	}

	private function getGroupId( $groupName ): ?int {
		if ( $groupName === null ) {
			return null;
		}

		if ( !array_key_exists( $groupName, $this->groupIds ) ) {
			$this->groupIds[$groupName] = count( $this->groupIds );
		}

		return $this->groupIds[$groupName];
	}

	/**
	 * Base modules implicitly available to all modules.
	 *
	 * @return array
	 */
	private function getBaseModules(): array {
		return [ 'jquery', 'mediawiki.base' ];
	}

	/**
	 * Get the localStorage key for the entire module store. The key references
	 * $wgDBname to prevent clashes between wikis under the same web domain.
	 *
	 * @return string localStorage item key for JavaScript
	 */
	private function getStoreKey(): string {
		return 'MediaWikiModuleStore:' . $this->getConfig()->get( 'DBname' );
	}

	/**
	 * @see $wgResourceLoaderMaxQueryLength
	 * @return int
	 */
	private function getMaxQueryLength(): int {
		$len = $this->getConfig()->get( 'ResourceLoaderMaxQueryLength' );
		// - Ignore -1, which in MW 1.34 and earlier was used to mean "unlimited".
		// - Ignore invalid values, e.g. non-int or other negative values.
		if ( $len === false || $len < 0 ) {
			// Default
			$len = 2000;
		}
		return $len;
	}

	/**
	 * Get the key on which the JavaScript module cache (mw.loader.store) will vary.
	 *
	 * @param ResourceLoaderContext $context
	 * @return string String of concatenated vary conditions
	 */
	private function getStoreVary( ResourceLoaderContext $context ): string {
		return implode( ':', [
			$context->getSkin(),
			$this->getConfig()->get( 'ResourceLoaderStorageVersion' ),
			$context->getLanguage(),
		] );
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return string JavaScript code
	 */
	public function getScript( ResourceLoaderContext $context ): string {
		global $IP;
		$conf = $this->getConfig();

		if ( $context->getOnly() !== 'scripts' ) {
			return '/* Requires only=scripts */';
		}

		$startupCode = file_get_contents( "$IP/resources/src/startup/startup.js" );

		// The files read here MUST be kept in sync with maintenance/jsduck/eg-iframe.html.
		$mwLoaderCode = file_get_contents( "$IP/resources/src/startup/mediawiki.js" ) .
			file_get_contents( "$IP/resources/src/startup/mediawiki.loader.js" ) .
			file_get_contents( "$IP/resources/src/startup/mediawiki.requestIdleCallback.js" );
		if ( $conf->get( 'ResourceLoaderEnableJSProfiler' ) ) {
			$mwLoaderCode .= file_get_contents( "$IP/resources/src/startup/profiler.js" );
		}

		// Perform replacements for mediawiki.js
		$mwLoaderPairs = [
			// This should always be an object, even if the base vars are empty
			// (such as when using the default lang/skin).
			'$VARS.reqBase' => $context->encodeJson( (object)$context->getReqBase() ),
			'$VARS.baseModules' => $context->encodeJson( $this->getBaseModules() ),
			'$VARS.maxQueryLength' => $context->encodeJson(
				// In debug mode (except legacy debug mode), let the client fetch each module in
				// its own dedicated request (T85805).
				// This is effectively the equivalent of ResourceLoaderClientHtml::makeLoad,
				// which does this for stylesheets.
				( !$context->getDebug() || $context->getDebug() === $context::DEBUG_LEGACY ) ?
					$this->getMaxQueryLength() :
					0
			),
			// The client-side module cache can be disabled by site configuration.
			// It is also always disabled in debug mode.
			'$VARS.storeDisabled' => $context->encodeJson(
				!$conf->get( 'ResourceLoaderStorageEnabled' ) || $context->getDebug()
			),
			'$VARS.storeKey' => $context->encodeJson( $this->getStoreKey() ),
			'$VARS.storeVary' => $context->encodeJson( $this->getStoreVary( $context ) ),
			'$VARS.groupUser' => $context->encodeJson( $this->getGroupId( self::GROUP_USER ) ),
			'$VARS.groupPrivate' => $context->encodeJson( $this->getGroupId( self::GROUP_PRIVATE ) ),
			// Only expose private mw.loader.isES6ForTest in test mode.
			'$CODE.test( isES6Supported )' => $conf->get( 'EnableJavaScriptTest' ) ?
				'(mw.loader.isES6ForTest !== undefined ? mw.loader.isES6ForTest : isES6Supported)' :
				'isES6Supported',
			// Only expose private mw.redefineFallbacksForTest in test mode.
			'$CODE.maybeRedefineFallbacksForTest();' => $conf->get( 'EnableJavaScriptTest' ) ?
				'mw.redefineFallbacksForTest = defineFallbacks;' :
				'',
		];
		$profilerStubs = [
			'$CODE.profileExecuteStart();' => 'mw.loader.profiler.onExecuteStart( module );',
			'$CODE.profileExecuteEnd();' => 'mw.loader.profiler.onExecuteEnd( module );',
			'$CODE.profileScriptStart();' => 'mw.loader.profiler.onScriptStart( module );',
			'$CODE.profileScriptEnd();' => 'mw.loader.profiler.onScriptEnd( module );',
		];
		$debugStubs = [
			'$CODE.consoleLog();' => 'console.log.apply( console, arguments );',
		];
		// When profiling is enabled, insert the calls. When disabled (by default), insert nothing.
		$mwLoaderPairs += $conf->get( 'ResourceLoaderEnableJSProfiler' )
			? $profilerStubs
			: array_fill_keys( array_keys( $profilerStubs ), '' );
		$mwLoaderPairs += $context->getDebug()
			? $debugStubs
			: array_fill_keys( array_keys( $debugStubs ), '' );
		$mwLoaderCode = strtr( $mwLoaderCode, $mwLoaderPairs );

		// Perform string replacements for startup.js
		$pairs = [
			// Raw JavaScript code (not JSON)
			'$CODE.registrations();' => trim( $this->getModuleRegistrations( $context ) ),
			'$CODE.defineLoader();' => $mwLoaderCode,
		];
		$startupCode = strtr( $startupCode, $pairs );

		return $startupCode;
	}

	/**
	 * @return bool
	 */
	public function supportsURLLoading(): bool {
		return false;
	}

	/**
	 * @return bool
	 */
	public function enableModuleContentVersion(): bool {
		// Enabling this means that ResourceLoader::getVersionHash will simply call getScript()
		// and hash it to determine the version (as used by E-Tag HTTP response header).
		return true;
	}
}
