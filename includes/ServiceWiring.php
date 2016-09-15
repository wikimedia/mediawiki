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

use MediaWiki\Interwiki\ClassicInterwikiLookup;
use MediaWiki\Linker\LinkRendererFactory;
use MediaWiki\MediaWikiServices;

return [
	'DBLoadBalancerFactory' => function( MediaWikiServices $services ) {
		$mainConfig = $services->getMainConfig();

		$lbConf = $mainConfig->get( 'LBFactoryConf' );
		if ( !isset( $lbConf['readOnlyReason'] ) ) {
			// TODO: replace the global wfConfiguredReadOnlyReason() with a service.
			$lbConf['readOnlyReason'] = wfConfiguredReadOnlyReason();
		}

		// Determine schema defaults. Currently Microsoft SQL Server uses $wgDBmwschema,
		// and everything else doesn't use a schema (e.g. null)
		// Although postgres and oracle support schemas, we don't use them (yet)
		// to maintain backwards compatibility
		$schema = ( $mainConfig->get( 'DBtype' ) === 'mssql' )
			? $mainConfig->get( 'DBmwschema' )
			: null;

		$class = LBFactoryMW::getLBFactoryClass( $lbConf );
		if ( $class === 'LBFactorySimple' ) {
			if ( is_array( $mainConfig->get( 'DBservers' ) ) ) {
				foreach ( $mainConfig->get( 'DBservers' ) as $i => $server ) {
					$lbConf['servers'][$i] = $server + [
						'schema' => $schema,
						'tablePrefix' => $mainConfig->get( 'DBprefix' ),
						'flags' => DBO_DEFAULT,
					];
				}
			} else {
				$flags = DBO_DEFAULT;
				$flags |= $mainConfig->get( 'DebugDumpSql' ) ? DBO_DEBUG : 0;
				$flags |= $mainConfig->get( 'DBssl' ) ? DBO_SSL : 0;
				$flags |= $mainConfig->get( 'DBcompress' ) ? DBO_COMPRESS : 0;
				$lbConf['servers'] = [
					[
						'host' => $mainConfig->get( 'DBserver' ),
						'user' => $mainConfig->get( 'DBuser' ),
						'password' => $mainConfig->get( 'DBpassword' ),
						'dbname' => $mainConfig->get( 'DBname' ),
						'schema' => $schema,
						'tablePrefix' => $mainConfig->get( 'DBprefix' ),
						'type' => $mainConfig->get( 'DBtype' ),
						'load' => 1,
						'flags' => $flags,
					]
				];
			}
			$lbConf['externalServers'] = $mainConfig->get( 'ExternalServers' );
		}

		return new $class( LBFactoryMW::applyDefaultConfig( $lbConf ) );
	},

	'DBLoadBalancer' => function( MediaWikiServices $services ) {
		// just return the default LB from the DBLoadBalancerFactory service
		return $services->getDBLoadBalancerFactory()->getMainLB();
	},

	'SiteStore' => function( MediaWikiServices $services ) {
		$rawSiteStore = new DBSiteStore( $services->getDBLoadBalancer() );

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

	'InterwikiLookup' => function( MediaWikiServices $services ) {
		global $wgContLang; // TODO: manage $wgContLang as a service
		$config = $services->getMainConfig();
		return new ClassicInterwikiLookup(
			$wgContLang,
			ObjectCache::getMainWANInstance(),
			$config->get( 'InterwikiExpiry' ),
			$config->get( 'InterwikiCache' ),
			$config->get( 'InterwikiScopes' ),
			$config->get( 'InterwikiFallbackSite' )
		);
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
		$factory = new SkinFactory();

		$names = $services->getMainConfig()->get( 'ValidSkinNames' );

		foreach ( $names as $name => $skin ) {
			$factory->register( $name, $skin, function () use ( $name, $skin ) {
				$class = "Skin$skin";
				return new $class( $name );
			} );
		}
		// Register a hidden "fallback" skin
		$factory->register( 'fallback', 'Fallback', function () {
			return new SkinFallback;
		} );
		// Register a hidden skin for api output
		$factory->register( 'apioutput', 'ApiOutput', function () {
			return new SkinApi;
		} );

		return $factory;
	},

	'WatchedItemStore' => function( MediaWikiServices $services ) {
		$store = new WatchedItemStore(
			$services->getDBLoadBalancer(),
			new HashBagOStuff( [ 'maxKeys' => 100 ] )
		);
		$store->setStatsdDataFactory( $services->getStatsdDataFactory() );
		return $store;
	},

	'WatchedItemQueryService' => function( MediaWikiServices $services ) {
		return new WatchedItemQueryService( $services->getDBLoadBalancer() );
	},

	'MediaHandlerFactory' => function( MediaWikiServices $services ) {
		return new MediaHandlerFactory(
			$services->getMainConfig()->get( 'MediaHandlers' )
		);
	},

	'LinkCache' => function( MediaWikiServices $services ) {
		return new LinkCache(
			$services->getTitleFormatter(),
			ObjectCache::getMainWANInstance()
		);
	},

	'LinkRendererFactory' => function( MediaWikiServices $services ) {
		return new LinkRendererFactory(
			$services->getTitleFormatter(),
			$services->getLinkCache()
		);
	},

	'LinkRenderer' => function( MediaWikiServices $services ) {
		global $wgUser;

		if ( defined( 'MW_NO_SESSION' ) ) {
			return $services->getLinkRendererFactory()->create();
		} else {
			return $services->getLinkRendererFactory()->createForUser( $wgUser );
		}
	},

	'GenderCache' => function( MediaWikiServices $services ) {
		return new GenderCache();
	},

	'_MediaWikiTitleCodec' => function( MediaWikiServices $services ) {
		global $wgContLang;

		return new MediaWikiTitleCodec(
			$wgContLang,
			$services->getGenderCache(),
			$services->getMainConfig()->get( 'LocalInterwikis' )
		);
	},

	'TitleFormatter' => function( MediaWikiServices $services ) {
		return $services->getService( '_MediaWikiTitleCodec' );
	},

	'TitleParser' => function( MediaWikiServices $services ) {
		return $services->getService( '_MediaWikiTitleCodec' );
	},

	'VirtualRESTServiceClient' => function( MediaWikiServices $services ) {
		$config = $services->getMainConfig()->get( 'VirtualRestConfig' );

		$vrsClient = new VirtualRESTServiceClient( new MultiHttpClient( [] ) );
		foreach ( $config['paths'] as $prefix => $serviceConfig ) {
			$class = $serviceConfig['class'];
			// Merge in the global defaults
			$constructArg = isset( $serviceConfig['options'] )
				? $serviceConfig['options']
				: [];
			$constructArg += $config['global'];
			// Make the VRS service available at the mount point
			$vrsClient->mount( $prefix, [ 'class' => $class, 'config' => $constructArg ] );
		}

		return $vrsClient;
	},

	///////////////////////////////////////////////////////////////////////////
	// NOTE: When adding a service here, don't forget to add a getter function
	// in the MediaWikiServices class. The convenience getter should just call
	// $this->getService( 'FooBarService' ).
	///////////////////////////////////////////////////////////////////////////

];
