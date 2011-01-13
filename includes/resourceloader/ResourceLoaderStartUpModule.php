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

class ResourceLoaderStartUpModule extends ResourceLoaderModule {
	
	/* Protected Members */

	protected $modifiedTime = array();

	/* Protected Methods */
	
	protected function getConfig( $context ) {
		global $wgLoadScript, $wgScript, $wgStylePath, $wgScriptExtension, 
			$wgArticlePath, $wgScriptPath, $wgServer, $wgContLang, 
			$wgVariantArticlePath, $wgActionPaths, $wgUseAjax, $wgVersion, 
			$wgEnableAPI, $wgEnableWriteAPI, $wgDBname, $wgEnableMWSuggest, 
			$wgSitename, $wgFileExtensions;

		// Pre-process information
		$separatorTransTable = $wgContLang->separatorTransformTable();
		$separatorTransTable = $separatorTransTable ? $separatorTransTable : array();
		$compactSeparatorTransTable = array(
			implode( "\t", array_keys( $separatorTransTable ) ),
			implode( "\t", $separatorTransTable ),
		);
		$digitTransTable = $wgContLang->digitTransformTable();
		$digitTransTable = $digitTransTable ? $digitTransTable : array();
		$compactDigitTransTable = array(
			implode( "\t", array_keys( $digitTransTable ) ),
			implode( "\t", $digitTransTable ),
		);
		$mainPage = Title::newMainPage();
		
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
			'wgActionPaths' => $wgActionPaths,
			'wgServer' => $wgServer,
			'wgUserLanguage' => $context->getLanguage(),
			'wgContentLanguage' => $wgContLang->getCode(),
			'wgVersion' => $wgVersion,
			'wgEnableAPI' => $wgEnableAPI,
			'wgEnableWriteAPI' => $wgEnableWriteAPI,
			'wgSeparatorTransformTable' => $compactSeparatorTransTable,
			'wgDigitTransformTable' => $compactDigitTransTable,
			'wgMainPageTitle' => $mainPage ? $mainPage->getPrefixedText() : null,
			'wgFormattedNamespaces' => $wgContLang->getFormattedNamespaces(),
			'wgNamespaceIds' => $wgContLang->getNamespaceIds(),
			'wgSiteName' => $wgSitename,
			'wgFileExtensions' => array_values( $wgFileExtensions ),
			'wgDBname' => $wgDBname,
		);
		if ( $wgContLang->hasVariants() ) {
			$vars['wgUserVariant'] = $wgContLang->getPreferredVariant();
		}
		if ( $wgUseAjax && $wgEnableMWSuggest ) {
			$vars['wgMWSuggestTemplate'] = SearchEngine::getMWSuggestTemplate();
		}
		
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
		foreach ( $resourceLoader->getModuleNames() as $name ) {
			$module = $resourceLoader->getModule( $name );
			// Support module loader scripts
			$loader = $module->getLoaderScript();
			if ( $loader !== false ) {
				$deps = $module->getDependencies();
				$group = $module->getGroup();
				$version = wfTimestamp( TS_ISO_8601_BASIC, 
					round( $module->getModifiedTime( $context ), -2 ) );
				$out .= ResourceLoader::makeCustomLoaderScript( $name, $version, $deps, $group, $loader );
			}
			// Automatically register module
			else {
				// getModifiedTime() is supposed to return a UNIX timestamp, but it doesn't always
				// seem to do that, and custom implementations might forget. Coerce it to TS_UNIX
				$moduleMtime = wfTimestamp( TS_UNIX, $module->getModifiedTime( $context ) );
				$mtime = max( $moduleMtime, wfTimestamp( TS_UNIX, $wgCacheEpoch ) );
				// Modules without dependencies or a group pass two arguments (name, timestamp) to 
				// mediaWiki.loader.register()
				if ( !count( $module->getDependencies() && $module->getGroup() === null ) ) {
					$registrations[] = array( $name, $mtime );
				}
				// Modules with dependencies but no group pass three arguments 
				// (name, timestamp, dependencies) to mediaWiki.loader.register()
				else if ( $module->getGroup() === null ) {
					$registrations[] = array(
						$name, $mtime,  $module->getDependencies() );
				}
				// Modules with dependencies pass four arguments (name, timestamp, dependencies, group) 
				// to mediaWiki.loader.register()
				else {
					$registrations[] = array(
						$name, $mtime,  $module->getDependencies(), $module->getGroup() );
				}
			}
		}
		$out .= ResourceLoader::makeLoaderRegisterScript( $registrations );
		
		wfProfileOut( __METHOD__ );
		return $out;
	}

	/* Methods */

	public function getScript( ResourceLoaderContext $context ) {
		global $IP, $wgLoadScript;

		$out = file_get_contents( "$IP/resources/startup.js" );
		if ( $context->getOnly() === 'scripts' ) {
			// Build load query for jquery and mediawiki modules
			$query = array(
				'modules' => implode( '|', array( 'jquery', 'mediawiki' ) ),
				'only' => 'scripts',
				'lang' => $context->getLanguage(),
				'skin' => $context->getSkin(),
				'debug' => $context->getDebug() ? 'true' : 'false',
				'version' => wfTimestamp( TS_ISO_8601_BASIC, round( max(
					$context->getResourceLoader()->getModule( 'jquery' )->getModifiedTime( $context ),
					$context->getResourceLoader()->getModule( 'mediawiki' )->getModifiedTime( $context )
				), -2 ) )
			);
			// Ensure uniform query order
			ksort( $query );
			
			// Startup function
			$configuration = $this->getConfig( $context );
			$registrations = self::getModuleRegistrations( $context );
			$out .= "var startUp = function() {\n" . 
				"\t$registrations\n" . 
				"\t" . Xml::encodeJsCall( 'mediaWiki.config.set', array( $configuration ) ) . 
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

	public function getFlip( $context ) {
		global $wgContLang;

		return $wgContLang->getDir() !== $context->getDirection();
	}
	
	/* Methods */
	
	public function getGroup() {
		return 'startup';
	}
}
