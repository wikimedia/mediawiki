<?php
/**
 * Module for ResourceLoader initialization.
 *
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

class ResourceLoaderStartUpModule extends ResourceLoaderModule {

	// Cache for getConfigSettings() as it's called by multiple methods
	protected $configVars = [];
	protected $targets = [ 'desktop', 'mobile' ];

	/**
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	protected function getConfigSettings( $context ) {

		$hash = $context->getHash();
		if ( isset( $this->configVars[$hash] ) ) {
			return $this->configVars[$hash];
		}

		global $wgContLang;
		$conf = $this->getConfig();

		// We can't use Title::newMainPage() if 'mainpage' is in
		// $wgForceUIMsgAsContentMsg because that will try to use the session
		// user's language and we have no session user. This does the
		// equivalent but falling back to our ResourceLoaderContext language
		// instead.
		$mainPage = Title::newFromText( $context->msg( 'mainpage' )->inContentLanguage()->text() );
		if ( !$mainPage ) {
			$mainPage = Title::newFromText( 'Main Page' );
		}

		/**
		 * Namespace related preparation
		 * - wgNamespaceIds: Key-value pairs of all localized, canonical and aliases for namespaces.
		 * - wgCaseSensitiveNamespaces: Array of namespaces that are case-sensitive.
		 */
		$namespaceIds = $wgContLang->getNamespaceIds();
		$caseSensitiveNamespaces = [];
		foreach ( MWNamespace::getCanonicalNamespaces() as $index => $name ) {
			$namespaceIds[$wgContLang->lc( $name )] = $index;
			if ( !MWNamespace::isCapitalized( $index ) ) {
				$caseSensitiveNamespaces[] = $index;
			}
		}

		$illegalFileChars = $conf->get( 'IllegalFileChars' );

		// Build list of variables
		$vars = [
			'wgLoadScript' => wfScript( 'load' ),
			'debug' => $context->getDebug(),
			'skin' => $context->getSkin(),
			'stylepath' => $conf->get( 'StylePath' ),
			'wgUrlProtocols' => wfUrlProtocols(),
			'wgArticlePath' => $conf->get( 'ArticlePath' ),
			'wgScriptPath' => $conf->get( 'ScriptPath' ),
			'wgScriptExtension' => '.php',
			'wgScript' => wfScript(),
			'wgSearchType' => $conf->get( 'SearchType' ),
			'wgVariantArticlePath' => $conf->get( 'VariantArticlePath' ),
			// Force object to avoid "empty" associative array from
			// becoming [] instead of {} in JS (T36604)
			'wgActionPaths' => (object)$conf->get( 'ActionPaths' ),
			'wgServer' => $conf->get( 'Server' ),
			'wgServerName' => $conf->get( 'ServerName' ),
			'wgUserLanguage' => $context->getLanguage(),
			'wgContentLanguage' => $wgContLang->getCode(),
			'wgTranslateNumerals' => $conf->get( 'TranslateNumerals' ),
			'wgVersion' => $conf->get( 'Version' ),
			'wgEnableAPI' => $conf->get( 'EnableAPI' ),
			'wgEnableWriteAPI' => $conf->get( 'EnableWriteAPI' ),
			'wgMainPageTitle' => $mainPage->getPrefixedText(),
			'wgFormattedNamespaces' => $wgContLang->getFormattedNamespaces(),
			'wgNamespaceIds' => $namespaceIds,
			'wgContentNamespaces' => MWNamespace::getContentNamespaces(),
			'wgSiteName' => $conf->get( 'Sitename' ),
			'wgDBname' => $conf->get( 'DBname' ),
			'wgExtraSignatureNamespaces' => $conf->get( 'ExtraSignatureNamespaces' ),
			'wgAvailableSkins' => Skin::getSkinNames(),
			'wgExtensionAssetsPath' => $conf->get( 'ExtensionAssetsPath' ),
			// MediaWiki sets cookies to have this prefix by default
			'wgCookiePrefix' => $conf->get( 'CookiePrefix' ),
			'wgCookieDomain' => $conf->get( 'CookieDomain' ),
			'wgCookiePath' => $conf->get( 'CookiePath' ),
			'wgCookieExpiration' => $conf->get( 'CookieExpiration' ),
			'wgResourceLoaderMaxQueryLength' => $conf->get( 'ResourceLoaderMaxQueryLength' ),
			'wgCaseSensitiveNamespaces' => $caseSensitiveNamespaces,
			'wgLegalTitleChars' => Title::convertByteClassToUnicodeClass( Title::legalChars() ),
			'wgIllegalFileChars' => Title::convertByteClassToUnicodeClass( $illegalFileChars ),
			'wgResourceLoaderStorageVersion' => $conf->get( 'ResourceLoaderStorageVersion' ),
			'wgResourceLoaderStorageEnabled' => $conf->get( 'ResourceLoaderStorageEnabled' ),
			'wgForeignUploadTargets' => $conf->get( 'ForeignUploadTargets' ),
			'wgEnableUploads' => $conf->get( 'EnableUploads' ),
		];

		Hooks::run( 'ResourceLoaderGetConfigVars', [ &$vars ] );

		$this->configVars[$hash] = $vars;
		return $this->configVars[$hash];
	}

	/**
	 * Recursively get all explicit and implicit dependencies for to the given module.
	 *
	 * @param array $registryData
	 * @param string $moduleName
	 * @return array
	 */
	protected static function getImplicitDependencies( array $registryData, $moduleName ) {
		static $dependencyCache = [];

		// The list of implicit dependencies won't be altered, so we can
		// cache them without having to worry.
		if ( !isset( $dependencyCache[$moduleName] ) ) {

			if ( !isset( $registryData[$moduleName] ) ) {
				// Dependencies may not exist
				$dependencyCache[$moduleName] = [];
			} else {
				$data = $registryData[$moduleName];
				$dependencyCache[$moduleName] = $data['dependencies'];

				foreach ( $data['dependencies'] as $dependency ) {
					// Recursively get the dependencies of the dependencies
					$dependencyCache[$moduleName] = array_merge(
						$dependencyCache[$moduleName],
						self::getImplicitDependencies( $registryData, $dependency )
					);
				}
			}
		}

		return $dependencyCache[$moduleName];
	}

	/**
	 * Optimize the dependency tree in $this->modules.
	 *
	 * The optimization basically works like this:
	 *	Given we have module A with the dependencies B and C
	 *		and module B with the dependency C.
	 *	Now we don't have to tell the client to explicitly fetch module
	 *		C as that's already included in module B.
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
			foreach ( $data['dependencies'] as $dependency ) {
				$implicitDependencies = self::getImplicitDependencies( $registryData, $dependency );
				$dependencies = array_diff( $dependencies, $implicitDependencies );
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
		$target = $context->getRequest()->getVal( 'target', 'desktop' );
		// Bypass target filter if this request is Special:JavaScriptTest.
		// To prevent misuse in production, this is only allowed if testing is enabled server-side.
		$byPassTargetFilter = $this->getConfig()->get( 'EnableJavaScriptTest' ) && $target === 'test';

		$out = '';
		$states = [];
		$registryData = [];

		// Get registry data
		foreach ( $resourceLoader->getModuleNames() as $name ) {
			$module = $resourceLoader->getModule( $name );
			$moduleTargets = $module->getTargets();
			if ( !$byPassTargetFilter && !in_array( $target, $moduleTargets ) ) {
				continue;
			}

			if ( $module->isRaw() ) {
				// Don't register "raw" modules (like 'jquery' and 'mediawiki') client-side because
				// depending on them is illegal anyway and would only lead to them being reloaded
				// causing any state to be lost (like jQuery plugins, mw.config etc.)
				continue;
			}

			try {
				$versionHash = $module->getVersionHash( $context );
			} catch ( Exception $e ) {
				// See also T152266 and ResourceLoader::getCombinedVersion()
				MWExceptionHandler::logException( $e );
				$context->getLogger()->warning(
					'Calculating version for "{module}" failed: {exception}',
					[
						'module' => $name,
						'exception' => $e,
					]
				);
				$versionHash = '';
				$states[$name] = 'error';
			}

			if ( $versionHash !== '' && strlen( $versionHash ) !== 7 ) {
				$context->getLogger()->warning(
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
			if ( $skipFunction !== null && !ResourceLoader::inDebugMode() ) {
				$skipFunction = ResourceLoader::filter( 'minify-js', $skipFunction );
			}

			$registryData[$name] = [
				'version' => $versionHash,
				'dependencies' => $module->getDependencies( $context ),
				'group' => $module->getGroup(),
				'source' => $module->getSource(),
				'skip' => $skipFunction,
			];
		}

		self::compileUnresolvedDependencies( $registryData );

		// Register sources
		$out .= ResourceLoader::makeLoaderSourcesScript( $resourceLoader->getSources() );

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
		$out .= "\n" . ResourceLoader::makeLoaderRegisterScript( $registrations );

		if ( $states ) {
			$out .= "\n" . ResourceLoader::makeLoaderStateScript( $states );
		}

		return $out;
	}

	/**
	 * @return bool
	 */
	public function isRaw() {
		return true;
	}

	/**
	 * Base modules required for the base environment of ResourceLoader
	 *
	 * @return array
	 */
	public static function getStartupModules() {
		return [ 'jquery', 'mediawiki' ];
	}

	public static function getLegacyModules() {
		global $wgIncludeLegacyJavaScript;

		$legacyModules = [];
		if ( $wgIncludeLegacyJavaScript ) {
			$legacyModules[] = 'mediawiki.legacy.wikibits';
		}

		return $legacyModules;
	}

	/**
	 * Get the load URL of the startup modules.
	 *
	 * This is a helper for getScript(), but can also be called standalone, such
	 * as when generating an AppCache manifest.
	 *
	 * @param ResourceLoaderContext $context
	 * @return string
	 */
	public static function getStartupModulesUrl( ResourceLoaderContext $context ) {
		$rl = $context->getResourceLoader();
		$derivative = new DerivativeResourceLoaderContext( $context );
		$derivative->setModules( array_merge(
			self::getStartupModules(),
			self::getLegacyModules()
		) );
		$derivative->setOnly( 'scripts' );
		// Must setModules() before makeVersionQuery()
		$derivative->setVersion( $rl->makeVersionQuery( $derivative ) );

		return $rl->createLoaderURL( 'local', $derivative );
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return string JavaScript code
	 */
	public function getScript( ResourceLoaderContext $context ) {
		global $IP;
		if ( $context->getOnly() !== 'scripts' ) {
			return '/* Requires only=script */';
		}

		$out = file_get_contents( "$IP/resources/src/startup.js" );

		$pairs = array_map( function ( $value ) {
			$value = FormatJson::encode( $value, ResourceLoader::inDebugMode(), FormatJson::ALL_OK );
			// Fix indentation
			$value = str_replace( "\n", "\n\t", $value );
			return $value;
		}, [
			'$VARS.wgLegacyJavaScriptGlobals' => $this->getConfig()->get( 'LegacyJavaScriptGlobals' ),
			'$VARS.configuration' => $this->getConfigSettings( $context ),
			'$VARS.baseModulesUri' => self::getStartupModulesUrl( $context ),
		] );
		$pairs['$CODE.registrations()'] = str_replace(
			"\n",
			"\n\t",
			trim( $this->getModuleRegistrations( $context ) )
		);

		return strtr( $out, $pairs );
	}

	/**
	 * @return bool
	 */
	public function supportsURLLoading() {
		return false;
	}

	/**
	 * Get the definition summary for this module.
	 *
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	public function getDefinitionSummary( ResourceLoaderContext $context ) {
		global $IP;
		$summary = parent::getDefinitionSummary( $context );
		$summary[] = [
			// Detect changes to variables exposed in mw.config (T30899).
			'vars' => $this->getConfigSettings( $context ),
			// Changes how getScript() creates mw.Map for mw.config
			'wgLegacyJavaScriptGlobals' => $this->getConfig()->get( 'LegacyJavaScriptGlobals' ),
			// Detect changes to the module registrations
			'moduleHashes' => $this->getAllModuleHashes( $context ),

			'fileMtimes' => [
				filemtime( "$IP/resources/src/startup.js" ),
			],
		];
		return $summary;
	}

	/**
	 * Helper method for getDefinitionSummary().
	 *
	 * @param ResourceLoaderContext $context
	 * @return string SHA-1
	 */
	protected function getAllModuleHashes( ResourceLoaderContext $context ) {
		$rl = $context->getResourceLoader();
		// Preload for getCombinedVersion()
		$rl->preloadModuleInfo( $rl->getModuleNames(), $context );

		// ATTENTION: Because of the line below, this is not going to cause infinite recursion.
		// Think carefully before making changes to this code!
		// Pre-populate versionHash with something because the loop over all modules below includes
		// the startup module (this module).
		// See ResourceLoaderModule::getVersionHash() for usage of this cache.
		$this->versionHash[$context->getHash()] = null;

		return $rl->getCombinedVersion( $context, $rl->getModuleNames() );
	}

	/**
	 * @return string
	 */
	public function getGroup() {
		return 'startup';
	}
}
