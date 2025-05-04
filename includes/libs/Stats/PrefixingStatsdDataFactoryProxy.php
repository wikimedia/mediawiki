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

namespace Wikimedia\Stats;

use Liuggio\StatsdClient\Entity\StatsdDataInterface;
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;

/**
 * Proxy to prefix metric keys sent to a StatsdDataFactoryInterface
 *
 * @deprecated since 1.44 Use StatsFactory with `setLabel()` instead
 *
 * For example:
 *
 * ```
 * $statsFactory
 *      ->getCounter( 'example_total' )
 *      ->setLabel( 'wiki', WikiMap::getCurrentWikiId() )
 * ```
 *
 * @since 1.32
 */
class PrefixingStatsdDataFactoryProxy implements StatsdDataFactoryInterface {

	/**
	 * @var string
	 */
	private $prefix;

	/**
	 * @var StatsdDataFactoryInterface
	 */
	private $factory;

	/**
	 * @param StatsdDataFactoryInterface $factory
	 * @param string $prefix
	 */
	public function __construct(
		StatsdDataFactoryInterface $factory,
		$prefix
	) {
		$this->factory = $factory;
		$this->prefix = rtrim( $prefix, '.' );
	}

	/**
	 * @param string $key
	 * @return string
	 */
	private function addPrefixToKey( $key ) {
		return $this->prefix . '.' . $key;
	}

	public function timing( $key, $time ) {
		return $this->factory->timing( $this->addPrefixToKey( $key ), $time );
	}

	public function gauge( $key, $value ) {
		return $this->factory->gauge( $this->addPrefixToKey( $key ), $value );
	}

	public function set( $key, $value ) {
		return $this->factory->set( $this->addPrefixToKey( $key ), $value );
	}

	public function increment( $key ) {
		return $this->factory->increment( $this->addPrefixToKey( $key ) );
	}

	public function decrement( $key ) {
		return $this->factory->decrement( $this->addPrefixToKey( $key ) );
	}

	public function updateCount( $key, $delta ) {
		return $this->factory->updateCount( $this->addPrefixToKey( $key ), $delta );
	}

	public function produceStatsdData(
		$key,
		$value = 1,
		$metric = StatsdDataInterface::STATSD_METRIC_COUNT
	) {
		return $this->factory->produceStatsdData(
			$this->addPrefixToKey( $key ),
			$value,
			$metric
		);
	}
}

/** @deprecated class alias since 1.43 */
class_alias( PrefixingStatsdDataFactoryProxy::class, 'PrefixingStatsdDataFactoryProxy' );
