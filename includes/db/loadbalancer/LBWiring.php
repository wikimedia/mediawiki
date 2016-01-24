<?php
/**
 * Wiring for MediaWiki LBFactory implementations.
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
 * This file is loaded when getDBLoadBalancerFactoryContainer is called on MediaWikiServices,
 * as specified by the wiring for 'DBLoadBalancerFactoryContainer' in ServiceWiring.php.
 *
 * This file returns an array that associates service name with constructor callbacks
 * that create LBFactory instances.
 *
 * @since 1.27
 *
 * @see docs/injection.txt for an overview of using dependency injection in the
 *      MediaWiki code base.
 */

use MediaWiki\MediaWikiServices;
use MediaWiki\Services\ServiceContainer;

return array(

	'LBFactorySimple' => function( ServiceContainer $container, MediaWikiServices $services ) {
		$config = $services->getMainConfig();

		$factory = new LBFactorySimple(
			LBFactorySimple::buildServerSpecsFromConfig( $config ),
			$config->get( 'ExternalServers' ),
			$services->getObjectCacheManager()->getLocalServerInstance(),
			$services->getChronologyProtector(),
			$services->getProfiler()->getTransactionProfiler(),
			$services->getLoggerFactory()->getLogger( 'DBTransaction' ),
			$services->getStatsDataFactory()
		);

		// TODO: replace the global wfConfiguredReadOnlyReason() with a service.
		$readOnlyReason = wfConfiguredReadOnlyReason();
		$factory->setReadOnlyReason( $readOnlyReason );

		return $factory;
	},

	'LBFactoryMulti' => function( ServiceContainer $container, MediaWikiServices $services ) {
		$config = $services->getMainConfig();
		$lbConf = $config->get( 'LBFactoryConf' );

		$prefix = $config->get( 'DBprefix' );
		$name = $config->get( 'DBname' );

		$wikiID = ( $prefix === '' ) ? $name : "$name-$prefix";

		$factory = new LBFactoryMulti(
			$wikiID,
			$lbConf, //TODO: use a Config object!
			$services->getObjectCacheManager()->getLocalServerInstance(),
			$services->getChronologyProtector(),
			$services->getProfiler()->getTransactionProfiler(),
			$services->getLoggerFactory()->getLogger( 'DBTransaction' ),
			$services->getStatsDataFactory()
		);

		// TODO: replace the global wfConfiguredReadOnlyReason() with a service.
		$readOnlyReason = isset( $lbConf['readOnlyReason'] )
			? $lbConf['readOnlyReason']
			: wfConfiguredReadOnlyReason();

		$factory->setReadOnlyReason( $readOnlyReason );
		return $factory;
	},

);
