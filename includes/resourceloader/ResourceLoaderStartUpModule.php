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
	protected function getConfig( $context ) {

		$hash = $context->getHash();
		if ( isset( $this->configVars[$hash] ) ) {
			return $this->configVars[$hash];
		}

		global $wgLoadScript, $wgScript, $wgStylePath, $wgScriptExtension,
			$wgArticlePath, $wgScriptPath, $wgServer, $wgContLang,
			$wgVariantArticlePath, $wgActionPaths, $wgVersion,
			$wgEnableAPI, $wgEnableWriteAPI, $wgDBname,
			$wgSitename, $wgFileExtensions, $wgExtensionAssetsPath,
			$wgCookiePrefix, $wgResourceLoaderMaxQueryLength,
			$wgResourceLoaderStorageEnabled, $wgResourceLoaderStorageVersion,
			$wgSearchType;

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
			'wgSearchType' => $wgSearchType,
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
			'wgContentNamespaces' => MWNamespace::getContentNamespaces(),
			'wgSiteName' => $wgSitename,
			'wgFileExtensions' => array_values( array_unique( $wgFileExtensions ) ),
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
			'wgLegalTitleChars' => Title::convertByteClassToUnicodeClass( Title::legalChars() ),
			'wgResourceLoaderStorageVersion' => $wgResourceLoaderStorageVersion,
			'wgResourceLoaderStorageEnabled' => $wgResourceLoaderStorageEnabled,
		);

		wfRunHooks( 'ResourceLoaderGetConfigVars', array( &$vars ) );

		$this->configVars[$hash] = $vars;
		return $this->configVars[$hash];
	}

	/**
	 * Get registration code for all modules.
	 *
	 * @param ResourceLoaderContext $context object
	 * @return string JavaScript code for registering all modules with the client loader
	 */
	public static function getModuleRegistrations( ResourceLoaderContext $context ) {
		global $wgCacheEpoch;
		wfProfileIn( __METHOD__ );

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

			// getModifiedTime() is supposed to return a UNIX timestamp, but it doesn't always
			// seem to do that, and custom implementations might forget. Coerce it to TS_UNIX
			$moduleMtime = wfTimestamp( TS_UNIX, $module->getModifiedTime( $context ) );
			$mtime = max( $moduleMtime, wfTimestamp( TS_UNIX, $wgCacheEpoch ) );

			// FIXME: Convert to numbers, wfTimestamp always gives us stings, even for TS_UNIX

			$registryData[ $name ] = array(
				'version' => $mtime,
				'dependencies' => $module->getDependencies(),
				'group' => $module->getGroup(),
				'source' => $module->getSource(),
				'loader' => $module->getLoaderScript(),
			);
		}

		// Register sources
		$out .= ResourceLoader::makeLoaderSourcesScript( $resourceLoader->getSources() );

		// Concatenate module loader scripts and figure out the different call
		// signatures for mw.loader.register
		$registrations = array();
		foreach ( $registryData as $name => $data ) {
			if ( $data['loader'] !== false ) {
				$out .= ResourceLoader::makeCustomLoaderScript(
					$name,
					wfTimestamp( TS_ISO_8601_BASIC, $data['version'] ),
					$data['dependencies'],
					$data['group'],
					$data['source'],
					$data['loader']
				);
				continue;
			}

			if (
				!count( $data['dependencies'] ) &&
				$data['group'] === null &&
				$data['source'] === 'local'
			) {
				// Modules without dependencies, a group or a foreign source;
				// call mw.loader.register(name, timestamp)
				$registrations[] = array( $name, $data['version'] );
			} elseif ( $data['group'] === null && $data['source'] === 'local' ) {
				// Modules with dependencies but no group or foreign source;
				// call mw.loader.register(name, timestamp, dependencies)
				$registrations[] = array( $name, $data['version'], $data['dependencies'] );
			} elseif ( $data['source'] === 'local' ) {
				// Modules with a group but no foreign source;
				// call mw.loader.register(name, timestamp, dependencies, group)
				$registrations[] = array(
					$name,
					$data['version'],
					$data['dependencies'],
					$data['group']
				);
			} else {
				// Modules with a foreign source;
				// call mw.loader.register(name, timestamp, dependencies, group, source)
				$registrations[] = array(
					$name,
					$data['version'],
					$data['dependencies'],
					$data['group'],
					$data['source']
				);
			}
		}

		// Register modules
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
	 * Get the load URL of the startup modules.
	 *
	 * This is a helper for getScript(), but can also be called standalone, such
	 * as when generating an AppCache manifest.
	 *
	 * @param ResourceLoaderContext $context
	 * @return string
	 */
	public static function getStartupModulesUrl( ResourceLoaderContext $context ) {
		// The core modules:
		$moduleNames = array( 'jquery', 'mediawiki' );
		wfRunHooks( 'ResourceLoaderGetStartupModules', array( &$moduleNames ), '1.23' );

		// Get the latest version
		$loader = $context->getResourceLoader();
		$version = 0;
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
		global $IP, $wgLegacyJavaScriptGlobals;

		$out = file_get_contents( "$IP/resources/src/startup.js" );
		if ( $context->getOnly() === 'scripts' ) {

			// Startup function
			$configuration = $this->getConfig( $context );
			$registrations = self::getModuleRegistrations( $context );
			// Fix indentation
			$registrations = str_replace( "\n", "\n\t", trim( $registrations ) );
			$out .= "var startUp = function () {\n" .
				"\tmw.config = new " .
				Xml::encodeJsCall( 'mw.Map', array( $wgLegacyJavaScriptGlobals ) ) . "\n" .
				"\t$registrations\n" .
				"\t" . Xml::encodeJsCall( 'mw.config.set', array( $configuration ) ) .
				"};\n";

			// Conditional script injection
			$scriptTag = Html::linkedScript( self::getStartupModulesUrl( $context ) );
			$out .= "if ( isCompatible() ) {\n" .
				"\t" . Xml::encodeJsCall( 'document.write', array( $scriptTag ) ) .
				"}";
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
		global $IP, $wgCacheEpoch;

		$hash = $context->getHash();
		if ( isset( $this->modifiedTime[$hash] ) ) {
			return $this->modifiedTime[$hash];
		}

		// Call preloadModuleInfo() on ALL modules as we're about
		// to call getModifiedTime() on all of them
		$loader = $context->getResourceLoader();
		$loader->preloadModuleInfo( $loader->getModuleNames(), $context );

		$time = max(
			wfTimestamp( TS_UNIX, $wgCacheEpoch ),
			filemtime( "$IP/resources/src/startup.js" ),
			$this->getHashMtime( $context )
		);

		// ATTENTION!: Because of the line below, this is not going to cause
		// infinite recursion - think carefully before making changes to this
		// code!
		// Pre-populate modifiedTime with something because the the loop over
		// all modules below includes the the startup module (this module).
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
	 * @param $context ResourceLoaderContext
	 * @return string: Hash
	 */
	public function getModifiedHash( ResourceLoaderContext $context ) {
		global $wgLegacyJavaScriptGlobals;

		$data = array(
			'vars' => $this->getConfig( $context ),
			'wgLegacyJavaScriptGlobals' => $wgLegacyJavaScriptGlobals,
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
