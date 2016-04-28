<?php
/**
 * Default wiring for MediaWiki services.
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
 *
 * This file is loaded by MediaWiki\MediaWikiServices::getInstance() during the
 * bootstrapping of the dependency injection framework.
 *
 * This file returns an array that associates service name with instantiator functions
 * that create the default instances for the services used by MediaWiki core.
 * For every service that MediaWiki core requires, an instantiator must be defined in
 * this file.
 *
 * @note As of version 1.27, MediaWiki is only beginning to use dependency injection.
 * The services defined here do not yet fully represent all services used by core,
 * much of the code still relies on global state for this accessing services.
 *
 * @since 1.27
 *
 * @see docs/injection.txt for an overview of using dependency injection in the
 *      MediaWiki code base.
 */

use MediaWiki\MediaWikiServices;

return [
	'SiteStore' => function( MediaWikiServices $services ) {
		$loadBalancer = wfGetLB(); // TODO: use LB from MediaWikiServices
		$rawSiteStore = new DBSiteStore( $loadBalancer );

		// TODO: replace wfGetCache with a CacheFactory service.
		// TODO: replace wfIsHHVM with a capabilities service.
		$cache = wfGetCache( wfIsHHVM() ? CACHE_ACCEL : CACHE_ANYTHING );

		return new CachingSiteStore( $rawSiteStore, $cache );
	},

	'SiteLookup' => function( MediaWikiServices $services ) {
		// Use the default SiteStore as the SiteLookup implementation for now
		return $services->getSiteStore();
	},

	'ConfigFactory' => function( MediaWikiServices $services ) {
		// Use the bootstrap config to initialize the ConfigFactory.
		$registry = $services->getBootstrapConfig()->get( 'ConfigRegistry' );
		$factory = new ConfigFactory();

		foreach ( $registry as $name => $callback ) {
			$factory->register( $name, $callback );
		}
		return $factory;
	},

	'MainConfig' => function( MediaWikiServices $services ) {
		// Use the 'main' config from the ConfigFactory service.
		return $services->getConfigFactory()->makeConfig( 'main' );
	},

	'StatsdDataFactory' => function( MediaWikiServices $services ) {
		return new BufferingStatsdDataFactory(
			rtrim( $services->getMainConfig()->get( 'StatsdMetricPrefix' ), '.' )
		);
	},

	'EventRelayerGroup' => function( MediaWikiServices $services ) {
		return new EventRelayerGroup( $services->getMainConfig()->get( 'EventRelayerConfig' ) );
	},

	'SearchEngineFactory' => function( MediaWikiServices $services ) {
		return new SearchEngineFactory( $services->getSearchEngineConfig() );
	},

	'SearchEngineConfig' => function( MediaWikiServices $services ) {
		global $wgContLang;
		return new SearchEngineConfig( $services->getMainConfig(), $wgContLang );
	},

	'SkinFactory' => function( MediaWikiServices $services ) {
		return new SkinFactory();
	},

	///////////////////////////////////////////////////////////////////////////
	// NOTE: When adding a service here, don't forget to add a getter function
	// in the MediaWikiServices class. The convenience getter should just call
	// $this->getService( 'FooBarService' ).
	///////////////////////////////////////////////////////////////////////////

];
