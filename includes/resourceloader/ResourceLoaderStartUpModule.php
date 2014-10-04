<?php
/**
 * Module for resource loader initialization.
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

	/* Protected Members */

	protected $modifiedTime = array();
	protected $configVars = array();
	protected $targets = array( 'desktop', 'mobile' );

	/* Protected Methods */

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

		$mainPage = Title::newMainPage();

		/**
		 * Namespace related preparation
		 * - wgNamespaceIds: Key-value pairs of all localized, canonical and aliases for namespaces.
		 * - wgCaseSensitiveNamespaces: Array of namespaces that are case-sensitive.
		 */
		$namespaceIds = $wgContLang->getNamespaceIds();
		$caseSensitiveNamespaces = array();
		foreach ( MWNamespace::getCanonicalNamespaces() as $index => $name ) {
			$namespaceIds[$wgContLang->lc( $name )] = $index;
			if ( !MWNamespace::isCapitalized( $index ) ) {
				$caseSensitiveNamespaces[] = $index;
			}
		}

		$conf = $this->getConfig();
		// Build list of variables
		$vars = array(
			'wgLoadScript' => wfScript( 'load' ),
			'debug' => $context->getDebug(),
			'skin' => $context->getSkin(),
			'stylepath' => $conf->get( 'StylePath' ),
			'wgUrlProtocols' => wfUrlProtocols(),
			'wgArticlePath' => $conf->get( 'ArticlePath' ),
			'wgScriptPath' => $conf->get( 'ScriptPath' ),
			'wgScriptExtension' => $conf->get( 'ScriptExtension' ),
			'wgScript' => wfScript(),
			'wgSearchType' => $conf->get( 'SearchType' ),
			'wgVariantArticlePath' => $conf->get( 'VariantArticlePath' ),
			// Force object to avoid "empty" associative array from
			// becoming [] instead of {} in JS (bug 34604)
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
			'wgResourceLoaderStorageVersion' => $conf->get( 'ResourceLoaderStorageVersion' ),
			'wgResourceLoaderStorageEnabled' => $conf->get( 'ResourceLoaderStorageEnabled' ),
		);

		Hooks::run( 'ResourceLoaderGetConfigVars', array( &$vars ) );

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
		static $dependencyCache = array();

		// The list of implicit dependencies won't be altered, so we can
		// cache them without having to worry.
		if ( !isset( $dependencyCache[$moduleName] ) ) {

			if ( !isset( $registryData[$moduleName] ) ) {
				// Dependencies may not exist
				$dependencyCache[$moduleName] = array();
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
	 *  - number 'version'
	 *  - array 'dependencies'
	 *  - string|null 'group'
	 *  - string 'source'
	 *  - string|false 'loader'
	 */
	public static function compileUnresolvedDependencies( array &$registryData ) {
		foreach ( $registryData as $name => &$data ) {
			if ( $data['loader'] !== false ) {
				continue;
			}
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

		$out = '';
		$registryData = array();

		// Get registry data
		foreach ( $resourceLoader->getModuleNames() as $name ) {
			$module = $resourceLoader->getModule( $name );
			$moduleTargets = $module->getTargets();
			if ( !in_array( $target, $moduleTargets ) ) {
				continue;
			}

			if ( $module->isRaw() ) {
				// Don't register "raw" modules (like 'jquery' and 'mediawiki') client-side because
				// depending on them is illegal anyway and would only lead to them being reloaded
				// causing any state to be lost (like jQuery plugins, mw.config etc.)
				continue;
			}

			// Coerce module timestamp to UNIX timestamp.
			// getModifiedTime() is supposed to return a UNIX timestamp, but custom implementations
			// might forget. TODO: Maybe emit warning?
			$moduleMtime = wfTimestamp( TS_UNIX, $module->getModifiedTime( $context ) );

			$skipFunction = $module->getSkipFunction();
			if ( $skipFunction !== null && !ResourceLoader::inDebugMode() ) {
				$skipFunction = $resourceLoader->filter( 'minify-js',
					$skipFunction,
					// There will potentially be lots of these little string in the registrations
					// manifest, we don't want to blow up the startup module with
					// "/* cache key: ... */" all over it in non-debug mode.
					/* cacheReport = */ false
				);
			}

			$mtime = max(
				$moduleMtime,
				wfTimestamp( TS_UNIX, $this->getConfig()->get( 'CacheEpoch' ) )
			);

			$registryData[$name] = array(
				// Convert to numbers as wfTimestamp always returns a string, even for TS_UNIX
				'version' => (int) $mtime,
				'dependencies' => $module->getDependencies(),
				'group' => $module->getGroup(),
				'source' => $module->getSource(),
				'loader' => $module->getLoaderScript(),
				'skip' => $skipFunction,
			);
		}

		self::compileUnresolvedDependencies( $registryData );

		// Register sources
		$out .= ResourceLoader::makeLoaderSourcesScript( $resourceLoader->getSources() );

		// Concatenate module loader scripts and figure out the different call
		// signatures for mw.loader.register
		$registrations = array();
		foreach ( $registryData as $name => $data ) {
			if ( $data['loader'] !== false ) {
				$out .= ResourceLoader::makeCustomLoaderScript(
					$name,
					$data['version'],
					$data['dependencies'],
					$data['group'],
					$data['source'],
					$data['loader']
				);
				continue;
			}

			// Call mw.loader.register(name, timestamp, dependencies, group, source, skip)
			$registrations[] = array(
				$name,
				$data['version'],
				$data['dependencies'],
				$data['group'],
				// Swap default (local) for null
				$data['source'] === 'local' ? null : $data['source'],
				$data['skip']
			);
		}

		// Register modules
		$out .= ResourceLoader::makeLoaderRegisterScript( $registrations );

		return $out;
	}

	/* Methods */

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
		return array( 'jquery', 'mediawiki' );
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
		$moduleNames = self::getStartupModules();

		// Get the latest version
		$loader = $context->getResourceLoader();
		$version = 1;
		foreach ( $moduleNames as $moduleName ) {
			$version = max( $version,
				$loader->getModule( $moduleName )->getModifiedTime( $context )
			);
		}

		$query = array(
			'modules' => ResourceLoader::makePackedModulesString( $moduleNames ),
			'only' => 'scripts',
			'lang' => $context->getLanguage(),
			'skin' => $context->getSkin(),
			'debug' => $context->getDebug() ? 'true' : 'false',
			'version' => wfTimestamp( TS_ISO_8601_BASIC, $version )
		);
		// Ensure uniform query order
		ksort( $query );
		return wfAppendQuery( wfScript( 'load' ), $query );
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return string
	 */
	public function getScript( ResourceLoaderContext $context ) {
		global $IP;

		$out = file_get_contents( "$IP/resources/src/startup.js" );
		if ( $context->getOnly() === 'scripts' ) {

			// Startup function
			$configuration = $this->getConfigSettings( $context );
			$registrations = $this->getModuleRegistrations( $context );
			// Fix indentation
			$registrations = str_replace( "\n", "\n\t", trim( $registrations ) );
			$mwMapJsCall = Xml::encodeJsCall(
				'mw.Map',
				array( $this->getConfig()->get( 'LegacyJavaScriptGlobals' ) )
			);
			$mwConfigSetJsCall = Xml::encodeJsCall(
				'mw.config.set',
				array( $configuration ),
				ResourceLoader::inDebugMode()
			);

			$out .= "var startUp = function () {\n" .
				"\tmw.config = new " .
				$mwMapJsCall . "\n" .
				"\t$registrations\n" .
				"\t" . $mwConfigSetJsCall .
				"};\n";

			// Conditional script injection
			$scriptTag = Html::linkedScript( self::getStartupModulesUrl( $context ) );
			$out .= "if ( isCompatible() ) {\n" .
				"\t" . Xml::encodeJsCall( 'document.write', array( $scriptTag ) ) .
				"\n}";
		}

		return $out;
	}

	/**
	 * @return bool
	 */
	public function supportsURLLoading() {
		return false;
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return array|mixed
	 */
	public function getModifiedTime( ResourceLoaderContext $context ) {
		global $IP;

		$hash = $context->getHash();
		if ( isset( $this->modifiedTime[$hash] ) ) {
			return $this->modifiedTime[$hash];
		}

		// Call preloadModuleInfo() on ALL modules as we're about
		// to call getModifiedTime() on all of them
		$loader = $context->getResourceLoader();
		$loader->preloadModuleInfo( $loader->getModuleNames(), $context );

		$time = max(
			wfTimestamp( TS_UNIX, $this->getConfig()->get( 'CacheEpoch' ) ),
			filemtime( "$IP/resources/src/startup.js" ),
			$this->getHashMtime( $context )
		);

		// ATTENTION!: Because of the line below, this is not going to cause
		// infinite recursion - think carefully before making changes to this
		// code!
		// Pre-populate modifiedTime with something because the loop over
		// all modules below includes the startup module (this module).
		$this->modifiedTime[$hash] = 1;

		foreach ( $loader->getModuleNames() as $name ) {
			$module = $loader->getModule( $name );
			$time = max( $time, $module->getModifiedTime( $context ) );
		}

		$this->modifiedTime[$hash] = $time;
		return $this->modifiedTime[$hash];
	}

	/**
	 * Hash of all dynamic data embedded in getScript().
	 *
	 * Detect changes to mw.config settings embedded in #getScript (bug 28899).
	 *
	 * @param ResourceLoaderContext $context
	 * @return string Hash
	 */
	public function getModifiedHash( ResourceLoaderContext $context ) {
		$data = array(
			'vars' => $this->getConfigSettings( $context ),
			'wgLegacyJavaScriptGlobals' => $this->getConfig()->get( 'LegacyJavaScriptGlobals' ),
		);

		return md5( serialize( $data ) );
	}

	/**
	 * @return string
	 */
	public function getGroup() {
		return 'startup';
	}
}
