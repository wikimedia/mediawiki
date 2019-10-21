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

use MediaWiki\MediaWikiServices;

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
		// for forward compatiblity so that they can be safely referenced by mediawiki.js,
		// even when the code is cached and the order of registrations (and implicit
		// group ids) changes between versions of the software.
		'user' => 0,
		'private' => 1,
	];

	/**
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	private function getConfigSettings( ResourceLoaderContext $context ) {
		$conf = $this->getConfig();

		/**
		 * Namespace related preparation
		 * - wgNamespaceIds: Key-value pairs of all localized, canonical and aliases for namespaces.
		 * - wgCaseSensitiveNamespaces: Array of namespaces that are case-sensitive.
		 */
		$contLang = MediaWikiServices::getInstance()->getContentLanguage();
		$namespaceIds = $contLang->getNamespaceIds();
		$caseSensitiveNamespaces = [];
		$nsInfo = MediaWikiServices::getInstance()->getNamespaceInfo();
		foreach ( $nsInfo->getCanonicalNamespaces() as $index => $name ) {
			$namespaceIds[$contLang->lc( $name )] = $index;
			if ( !$nsInfo->isCapitalized( $index ) ) {
				$caseSensitiveNamespaces[] = $index;
			}
		}

		$illegalFileChars = $conf->get( 'IllegalFileChars' );

		// Build list of variables
		$skin = $context->getSkin();
		$vars = [
			'debug' => $context->getDebug(),
			'skin' => $skin,
			'stylepath' => $conf->get( 'StylePath' ),
			'wgUrlProtocols' => wfUrlProtocols(),
			'wgArticlePath' => $conf->get( 'ArticlePath' ),
			'wgScriptPath' => $conf->get( 'ScriptPath' ),
			'wgScript' => $conf->get( 'Script' ),
			'wgSearchType' => $conf->get( 'SearchType' ),
			'wgVariantArticlePath' => $conf->get( 'VariantArticlePath' ),
			// Force object to avoid "empty" associative array from
			// becoming [] instead of {} in JS (T36604)
			'wgActionPaths' => (object)$conf->get( 'ActionPaths' ),
			'wgServer' => $conf->get( 'Server' ),
			'wgServerName' => $conf->get( 'ServerName' ),
			'wgUserLanguage' => $context->getLanguage(),
			'wgContentLanguage' => $contLang->getCode(),
			'wgTranslateNumerals' => $conf->get( 'TranslateNumerals' ),
			'wgVersion' => $conf->get( 'Version' ),
			'wgEnableAPI' => true, // Deprecated since MW 1.32
			'wgEnableWriteAPI' => true, // Deprecated since MW 1.32
			'wgFormattedNamespaces' => $contLang->getFormattedNamespaces(),
			'wgNamespaceIds' => $namespaceIds,
			'wgContentNamespaces' => $nsInfo->getContentNamespaces(),
			'wgSiteName' => $conf->get( 'Sitename' ),
			'wgDBname' => $conf->get( 'DBname' ),
			'wgWikiID' => WikiMap::getWikiIdFromDbDomain( WikiMap::getCurrentWikiDbDomain() ),
			'wgExtraSignatureNamespaces' => $conf->get( 'ExtraSignatureNamespaces' ),
			'wgExtensionAssetsPath' => $conf->get( 'ExtensionAssetsPath' ),
			// MediaWiki sets cookies to have this prefix by default
			'wgCookiePrefix' => $conf->get( 'CookiePrefix' ),
			'wgCookieDomain' => $conf->get( 'CookieDomain' ),
			'wgCookiePath' => $conf->get( 'CookiePath' ),
			'wgCookieExpiration' => $conf->get( 'CookieExpiration' ),
			'wgCaseSensitiveNamespaces' => $caseSensitiveNamespaces,
			'wgLegalTitleChars' => Title::convertByteClassToUnicodeClass( Title::legalChars() ),
			'wgIllegalFileChars' => Title::convertByteClassToUnicodeClass( $illegalFileChars ),
			'wgForeignUploadTargets' => $conf->get( 'ForeignUploadTargets' ),
			'wgEnableUploads' => $conf->get( 'EnableUploads' ),
			'wgCommentByteLimit' => null,
			'wgCommentCodePointLimit' => CommentStore::COMMENT_CHARACTER_LIMIT,
		];

		Hooks::run( 'ResourceLoaderGetConfigVars', [ &$vars, $skin, $conf ] );

		return $vars;
	}

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
		$moduleName,
		array $handled = []
	) {
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
	 * @param array &$registryData Modules keyed by name with properties:
	 *  - string 'version'
	 *  - array 'dependencies'
	 *  - string|null 'group'
	 *  - string 'source'
	 */
	public static function compileUnresolvedDependencies( array &$registryData ) {
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
	public function getModuleRegistrations( ResourceLoaderContext $context ) {
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

			try {
				$versionHash = $module->getVersionHash( $context );
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

			if ( $versionHash !== '' && strlen( $versionHash ) !== ResourceLoader::HASH_LENGTH ) {
				$e = new RuntimeException( "Badly formatted module version hash" );
				$resourceLoader->outputErrorAndLog( $e,
						"Module '{module}' produced an invalid version hash: '{version}'.",
					[
						'module' => $name,
						'version' => $versionHash,
					]
				);
				// Module implementation either broken or deviated from ResourceLoader::makeHash
				// Asserted by tests/phpunit/structure/ResourcesTest.
				$versionHash = ResourceLoader::makeHash( $versionHash );
			}

			$skipFunction = $module->getSkipFunction();
			if ( $skipFunction !== null && !$context->getDebug() ) {
				$skipFunction = ResourceLoader::filter( 'minify-js', $skipFunction );
			}

			$registryData[$name] = [
				'version' => $versionHash,
				'dependencies' => $module->getDependencies( $context ),
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
				$data['version'],
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

	private function getGroupId( $groupName ) {
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
	private function getBaseModules() {
		$baseModules = [ 'jquery', 'mediawiki.base' ];
		return $baseModules;
	}

	/**
	 * Get the localStorage key for the entire module store. The key references
	 * $wgDBname to prevent clashes between wikis under the same web domain.
	 *
	 * @return string localStorage item key for JavaScript
	 */
	private function getStoreKey() {
		return 'MediaWikiModuleStore:' . $this->getConfig()->get( 'DBname' );
	}

	/**
	 * Get the key on which the JavaScript module cache (mw.loader.store) will vary.
	 *
	 * @param ResourceLoaderContext $context
	 * @return string String of concatenated vary conditions
	 */
	private function getStoreVary( ResourceLoaderContext $context ) {
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
	public function getScript( ResourceLoaderContext $context ) {
		global $IP;
		$conf = $this->getConfig();

		if ( $context->getOnly() !== 'scripts' ) {
			return '/* Requires only=script */';
		}

		$startupCode = file_get_contents( "$IP/resources/src/startup/startup.js" );

		// The files read here MUST be kept in sync with maintenance/jsduck/eg-iframe.html,
		// and MUST be considered by 'fileHashes' in StartUpModule::getDefinitionSummary().
		$mwLoaderCode = file_get_contents( "$IP/resources/src/startup/mediawiki.js" ) .
			file_get_contents( "$IP/resources/src/startup/mediawiki.requestIdleCallback.js" );
		if ( $context->getDebug() ) {
			$mwLoaderCode .= file_get_contents( "$IP/resources/src/startup/mediawiki.log.js" );
		}
		if ( $conf->get( 'ResourceLoaderEnableJSProfiler' ) ) {
			$mwLoaderCode .= file_get_contents( "$IP/resources/src/startup/profiler.js" );
		}

		// Perform replacements for mediawiki.js
		$mwLoaderPairs = [
			'$VARS.reqBase' => $context->encodeJson( $context->getReqBase() ),
			'$VARS.baseModules' => $context->encodeJson( $this->getBaseModules() ),
			'$VARS.maxQueryLength' => $context->encodeJson(
				$conf->get( 'ResourceLoaderMaxQueryLength' )
			),
			// The client-side module cache can be disabled by site configuration.
			// It is also always disabled in debug mode.
			'$VARS.storeEnabled' => $context->encodeJson(
				$conf->get( 'ResourceLoaderStorageEnabled' ) && !$context->getDebug()
			),
			'$VARS.wgLegacyJavaScriptGlobals' => $context->encodeJson(
				$conf->get( 'LegacyJavaScriptGlobals' )
			),
			'$VARS.storeKey' => $context->encodeJson( $this->getStoreKey() ),
			'$VARS.storeVary' => $context->encodeJson( $this->getStoreVary( $context ) ),
			'$VARS.groupUser' => $context->encodeJson( $this->getGroupId( 'user' ) ),
			'$VARS.groupPrivate' => $context->encodeJson( $this->getGroupId( 'private' ) ),
		];
		$profilerStubs = [
			'$CODE.profileExecuteStart();' => 'mw.loader.profiler.onExecuteStart( module );',
			'$CODE.profileExecuteEnd();' => 'mw.loader.profiler.onExecuteEnd( module );',
			'$CODE.profileScriptStart();' => 'mw.loader.profiler.onScriptStart( module );',
			'$CODE.profileScriptEnd();' => 'mw.loader.profiler.onScriptEnd( module );',
		];
		if ( $conf->get( 'ResourceLoaderEnableJSProfiler' ) ) {
			// When profiling is enabled, insert the calls.
			$mwLoaderPairs += $profilerStubs;
		} else {
			// When disabled (by default), insert nothing.
			$mwLoaderPairs += array_fill_keys( array_keys( $profilerStubs ), '' );
		}
		$mwLoaderCode = strtr( $mwLoaderCode, $mwLoaderPairs );

		// Perform string replacements for startup.js
		$pairs = [
			'$VARS.configuration' => $context->encodeJson(
				$this->getConfigSettings( $context )
			),
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
	public function supportsURLLoading() {
		return false;
	}

	/**
	 * @return bool
	 */
	public function enableModuleContentVersion() {
		// Enabling this means that ResourceLoader::getVersionHash will simply call getScript()
		// and hash it to determine the version (as used by E-Tag HTTP response header).
		return true;
	}
}
