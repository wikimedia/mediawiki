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
 * This file returns an array that associates service name with constructor callbacks
 * that instantiate the default instances for the services used by MediaWiki
 * core. For every service that MediaWiki core requires, a constructor callback
 * creating a default instance must be defined in this file.
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
use MediaWiki\Services\ServicePool;

return array(
	'DBLoadBalancerFactory' => function( MediaWikiServices $services ) {
		// NOTE: Defining the LBFactory class via LBFactoryConf is supported for
		// backwards compatibility. The preferred way would be to register a
		// callback for DBLoadBalancerFactory that constructs the desired LBFactory
		// directly.
		$config = $services->getMainConfig()->get( 'LBFactoryConf' );

		$class = LBFactory::getLBFactoryClass( $config );
		if ( !isset( $config['readOnlyReason'] ) ) {
			// TODO: replace the global wfConfiguredReadOnlyReason() with a service.
			$config['readOnlyReason'] = wfConfiguredReadOnlyReason();
		}

		return new $class( $config );
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

	'ObjectCacheManager' => function( MediaWikiServices $services ) {
		$config = $services->getMainConfig();
		$keyPrefix = $config->get( 'CachePrefix' );
		$objectCacheSpecs = $config->get( 'ObjectCaches' );
		$wanObjectCacheSpecs = $config->get( 'WANObjectCaches' );

		if ( !is_string( $keyPrefix ) || $keyPrefix === '' ) {
			$keyPrefix = wfWikiID(); // TODO: use a service!
		}

		$manager = new ObjectCacheManager(
			$keyPrefix,
			$objectCacheSpecs,
			$wanObjectCacheSpecs,
			$services->getLoggerFactory()
		);

		$manager->setMainStash( $config->get( 'MainStash' ) );
		$manager->setMainWANCache( $config->get( 'MainWANCache' ) );
		$manager->setLocalClusterCache( $config->get( 'MainCacheType' ) );

		$manager->setAnythingCandidates( array(
			$config->get( 'MainCacheType' ),
			$config->get( 'MessageCacheType' ),
			$config->get( 'ParserCacheType' ),
		) );

		return $manager;
	},

	'Profiler' =>  function( MediaWikiServices $services ) {
		$config = $services->getMainConfig();
		$limit = $config->has( 'ProfilerLimit' ) ? $config->get( 'ProfilerLimit' ) : null;

		$params = array(
			'class'     => 'ProfilerStub',
			'sampling'  => 1,
			'threshold' => $limit,
			'output'    => array(),
		);

		if ( $config->has( 'Profiler' ) ) {
			$spec = $config->get( 'Profiler' );

			if ( is_array( $spec ) ) {
				$params = array_merge( $params, $spec );
			}
		}

		$inSample = mt_rand( 0, $params['sampling'] - 1 ) === 0;
		if ( PHP_SAPI === 'cli' || !$inSample ) {
			$params['class'] = 'ProfilerStub';
		}

		if ( !is_array( $params['output'] ) ) {
			$params['output'] = array( $params['output'] );
		}

		// TODO: use a ServiceContainer for managing profiler implementations!
		return new $params['class']( $params );
	},

	'LoggerFactory' =>  function( MediaWikiServices $services ) {
		$spiSpec = $services->getInstance()->getMainConfig()->get( 'MWLoggerDefaultSpi' );

		$provider = ObjectFactory::getObjectFromSpec( $spiSpec );
		return $provider;
	},

	'FileBackendGroup' => function( MediaWikiServices $services ) {
		$config = $services->getMainConfig();

		return new FileBackendGroup(
			$config->get( 'LocalFileRepo' ),
			$config->get( 'ForeignFileRepos' ),
			$config->get( 'FileBackends' ),
			wfConfiguredReadOnlyReason()
		);
	},

	'RedisConnectionPoolPool' => function( MediaWikiServices $services ) {
		// NOTE: this is a ServicePool (per wiki id) of connection pool services (per redis options).
		return new ServicePool(
			function( $options ) {
				return new RedisConnectionPool( $options );
			},
			function( $params ) {
				$options = $params[0];
				ksort( $options ); // normalize to avoid pool fragmentation
				$id = sha1( serialize( $options ) );
				return $id;
			},
			function ( $params ) use ( $services ) {
				$options = &$params[0];

				if ( !isset( $options['connectTimeout'] ) ) {
					$options['connectTimeout'] = 1;
				}
				if ( !isset( $options['readTimeout'] ) ) {
					$options['readTimeout'] = 1;
				}
				if ( !isset( $options['persistent'] ) ) {
					$options['persistent'] = false;
				}
				if ( !isset( $options['password'] ) ) {
					$options['password'] = null;
				}
				if ( !isset( $options['logger'] ) ) {
					$options['logger'] = $services->getLoggerFactory()->getLogger( 'redis' );
				}

				return $params;
			}
		);
	},

	'JobQueueGroupPool' => function( MediaWikiServices $services ) {
		return new ServicePool(
			function( $wiki ) use ( $services ) {
				return new JobQueueGroup( $wiki );
			},
			function( $params ) {
				return strval( $params[0] );
			},
			function( $params ) {
				$params[0] = ( $params[0] === false ) ? wfWikiID() : $params[0];
				return $params;
			}
		);
	},

	'LockManagerGroupPool' => function( MediaWikiServices $services ) {
		return new ServicePool(
			function( $domain ) use ( $services ) {
				$domain = ( $domain === false ) ? wfWikiID() : $domain;

				$lockManagerGroup = new LockManagerGroup( $domain );

				$managers = $services->getMainConfig()->get( 'LockManagers' );
				$lockManagerGroup->register( $managers );

				return $lockManagerGroup;
			},
			function( $params ) {
				return strval( $params[0] );
			},
			function( $params ) {
				$params[0] = ( $params[0] === false ) ? wfWikiID() : $params[0];
				return $params;
			}
		);
	},

	///////////////////////////////////////////////////////////////////////////
	// NOTE: When adding a service here, don't forget to add a getter function
	// in the MediaWikiServices class. The convenience getter should just call
	// $this->getService( 'FooBarService' ).
	///////////////////////////////////////////////////////////////////////////

);
