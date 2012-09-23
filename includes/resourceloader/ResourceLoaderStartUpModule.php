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

	/* Protected Methods */

	/**
	 * @param $context ResourceLoaderContext
	 * @return array
	 */
	protected function getConfig( $context ) {
		global $wgLoadScript, $wgScript, $wgStylePath, $wgScriptExtension,
			$wgArticlePath, $wgScriptPath, $wgServer, $wgContLang,
			$wgVariantArticlePath, $wgActionPaths, $wgVersion,
			$wgEnableAPI, $wgEnableWriteAPI, $wgDBname,
			$wgSitename, $wgFileExtensions, $wgExtensionAssetsPath,
			$wgCookiePrefix, $wgResourceLoaderMaxQueryLength;

		$mainPage = Title::newMainPage();

		/**
		 * Namespace related preparation
		 * - wgNamespaceIds: Key-value pairs of all localized, canonical and aliases for namespaces.
		 * - wgCaseSensitiveNamespaces: Array of namespaces that are case-sensitive.
		 */
		$namespaceIds = $wgContLang->getNamespaceIds();
		$caseSensitiveNamespaces = array();
		foreach( MWNamespace::getCanonicalNamespaces() as $index => $name ) {
			$namespaceIds[$wgContLang->lc( $name )] = $index;
			if ( !MWNamespace::isCapitalized( $index ) ) {
				$caseSensitiveNamespaces[] = $index;
			}
		}

		// Build list of variables
		$vars = array(
			'wgLoadScript' => $wgLoadScript,
			'debug' => $context->getDebug(),
			'skin' => $context->getSkin(),
			'stylepath' => $wgStylePath,
			'wgUrlProtocols' => wfUrlProtocols(),
			'wgArticlePath' => $wgArticlePath,
			'wgScriptPath' => $wgScriptPath,
			'wgScriptExtension' => $wgScriptExtension,
			'wgScript' => $wgScript,
			'wgVariantArticlePath' => $wgVariantArticlePath,
			// Force object to avoid "empty" associative array from
			// becoming [] instead of {} in JS (bug 34604)
			'wgActionPaths' => (object)$wgActionPaths,
			'wgServer' => $wgServer,
			'wgUserLanguage' => $context->getLanguage(),
			'wgContentLanguage' => $wgContLang->getCode(),
			'wgVersion' => $wgVersion,
			'wgEnableAPI' => $wgEnableAPI,
			'wgEnableWriteAPI' => $wgEnableWriteAPI,
			'wgMainPageTitle' => $mainPage->getPrefixedText(),
			'wgFormattedNamespaces' => $wgContLang->getFormattedNamespaces(),
			'wgNamespaceIds' => $namespaceIds,
			'wgSiteName' => $wgSitename,
			'wgFileExtensions' => array_values( $wgFileExtensions ),
			'wgDBname' => $wgDBname,
			// This sucks, it is only needed on Special:Upload, but I could
			// not find a way to add vars only for a certain module
			'wgFileCanRotate' => BitmapHandler::canRotate(),
			'wgAvailableSkins' => Skin::getSkinNames(),
			'wgExtensionAssetsPath' => $wgExtensionAssetsPath,
			// MediaWiki sets cookies to have this prefix by default
			'wgCookiePrefix' => $wgCookiePrefix,
			'wgResourceLoaderMaxQueryLength' => $wgResourceLoaderMaxQueryLength,
			'wgCaseSensitiveNamespaces' => $caseSensitiveNamespaces,
		);

		wfRunHooks( 'ResourceLoaderGetConfigVars', array( &$vars ) );

		return $vars;
	}

	/**
	 * Gets registration code for all modules
	 *
	 * @param $context ResourceLoaderContext object
	 * @return String: JavaScript code for registering all modules with the client loader
	 */
	public static function getModuleRegistrations( ResourceLoaderContext $context ) {
		global $wgCacheEpoch;
		wfProfileIn( __METHOD__ );

		$out = '';
		$registrations = array();
		$resourceLoader = $context->getResourceLoader();

		// Register sources
		$out .= ResourceLoader::makeLoaderSourcesScript( $resourceLoader->getSources() );

		// Register modules
		foreach ( $resourceLoader->getModuleNames() as $name ) {
			$module = $resourceLoader->getModule( $name );
			$deps = $module->getDependencies();
			$group = $module->getGroup();
			$source = $module->getSource();
			// Support module loader scripts
			$loader = $module->getLoaderScript();
			if ( $loader !== false ) {
				$version = wfTimestamp( TS_ISO_8601_BASIC,
					$module->getModifiedTime( $context ) );
				$out .= ResourceLoader::makeCustomLoaderScript( $name, $version, $deps, $group, $source, $loader );
			}
			// Automatically register module
			else {
				// getModifiedTime() is supposed to return a UNIX timestamp, but it doesn't always
				// seem to do that, and custom implementations might forget. Coerce it to TS_UNIX
				$moduleMtime = wfTimestamp( TS_UNIX, $module->getModifiedTime( $context ) );
				$mtime = max( $moduleMtime, wfTimestamp( TS_UNIX, $wgCacheEpoch ) );
				// Modules without dependencies, a group or a foreign source pass two arguments (name, timestamp) to
				// mw.loader.register()
				if ( !count( $deps ) && $group === null && $source === 'local' ) {
					$registrations[] = array( $name, $mtime );
				}
				// Modules with dependencies but no group or foreign source pass three arguments
				// (name, timestamp, dependencies) to mw.loader.register()
				elseif ( $group === null && $source === 'local' ) {
					$registrations[] = array( $name, $mtime, $deps );
				}
				// Modules with a group but no foreign source pass four arguments (name, timestamp, dependencies, group)
				// to mw.loader.register()
				elseif ( $source === 'local' ) {
					$registrations[] = array( $name, $mtime, $deps, $group );
				}
				// Modules with a foreign source pass five arguments (name, timestamp, dependencies, group, source)
				// to mw.loader.register()
				else {
					$registrations[] = array( $name, $mtime, $deps, $group, $source );
				}
			}
		}
		$out .= ResourceLoader::makeLoaderRegisterScript( $registrations );

		wfProfileOut( __METHOD__ );
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
	 * @param $context ResourceLoaderContext
	 * @return string
	 */
	public function getScript( ResourceLoaderContext $context ) {
		global $IP, $wgLoadScript, $wgLegacyJavaScriptGlobals;

		$out = file_get_contents( "$IP/resources/startup.js" );
		if ( $context->getOnly() === 'scripts' ) {

			// The core modules:
			$moduleNames = array( 'jquery', 'mediawiki' );
			wfRunHooks( 'ResourceLoaderGetStartupModules', array( &$moduleNames ) );

			// Get the latest version
			$loader = $context->getResourceLoader();
			$version = 0;
			foreach ( $moduleNames as $moduleName ) {
				$version = max( $version,
					$loader->getModule( $moduleName )->getModifiedTime( $context )
				);
			}
			// Build load query for StartupModules
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

			// Startup function
			$configuration = $this->getConfig( $context );
			$registrations = self::getModuleRegistrations( $context );
			$registrations = str_replace( "\n", "\n\t", trim( $registrations ) ); // fix indentation
			$out .= "var startUp = function() {\n" .
				"\tmw.config = new " . Xml::encodeJsCall( 'mw.Map', array( $wgLegacyJavaScriptGlobals ) ) . "\n" .
				"\t$registrations\n" .
				"\t" . Xml::encodeJsCall( 'mw.config.set', array( $configuration ) ) .
				"};\n";

			// Conditional script injection
			$scriptTag = Html::linkedScript( $wgLoadScript . '?' . wfArrayToCGI( $query ) );
			$out .= "if ( isCompatible() ) {\n" .
				"\t" . Xml::encodeJsCall( 'document.write', array( $scriptTag ) ) .
				"}\n" .
				"delete isCompatible;";
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
	 * @param $context ResourceLoaderContext
	 * @return array|mixed
	 */
	public function getModifiedTime( ResourceLoaderContext $context ) {
		global $IP, $wgCacheEpoch;

		$hash = $context->getHash();
		if ( isset( $this->modifiedTime[$hash] ) ) {
			return $this->modifiedTime[$hash];
		}

		// Call preloadModuleInfo() on ALL modules as we're about
		// to call getModifiedTime() on all of them
		$loader = $context->getResourceLoader();
		$loader->preloadModuleInfo( $loader->getModuleNames(), $context );

		$this->modifiedTime[$hash] = filemtime( "$IP/resources/startup.js" );
		// ATTENTION!: Because of the line above, this is not going to cause
		// infinite recursion - think carefully before making changes to this
		// code!
		$time = wfTimestamp( TS_UNIX, $wgCacheEpoch );
		foreach ( $loader->getModuleNames() as $name ) {
			$module = $loader->getModule( $name );
			$time = max( $time, $module->getModifiedTime( $context ) );
		}
		return $this->modifiedTime[$hash] = $time;
	}

	/* Methods */

	/**
	 * @return string
	 */
	public function getGroup() {
		return 'startup';
	}
}
