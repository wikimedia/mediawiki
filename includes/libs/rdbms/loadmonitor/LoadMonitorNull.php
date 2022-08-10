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
 */

namespace Wikimedia\Rdbms;

use BagOStuff;
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use Psr\Log\LoggerInterface;
use WANObjectCache;

/**
 * @ingroup Database
 */
class LoadMonitorNull implements ILoadMonitor {
	public function __construct(
		ILoadBalancer $lb, BagOStuff $sCache, WANObjectCache $wCache, array $options = []
	) {
	}

	public function setLogger( LoggerInterface $logger ) {
	}

	public function setStatsdDataFactory( StatsdDataFactoryInterface $statsFactory ) {
	}

	public function scaleLoads( array &$loads, $domain ) {
	}

	public function getLagTimes( array $serverIndexes, $domain ) {
		return array_fill_keys( $serverIndexes, 0 );
	}

	public function clearCaches() {
	}
}
