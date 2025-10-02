<?php
/**
 * @license GPL-2.0-or-later
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

	/** @inheritDoc */
	public function timing( $key, $time ) {
		return $this->factory->timing( $this->addPrefixToKey( $key ), $time );
	}

	/** @inheritDoc */
	public function gauge( $key, $value ) {
		return $this->factory->gauge( $this->addPrefixToKey( $key ), $value );
	}

	/** @inheritDoc */
	public function set( $key, $value ) {
		return $this->factory->set( $this->addPrefixToKey( $key ), $value );
	}

	/** @inheritDoc */
	public function increment( $key ) {
		return $this->factory->increment( $this->addPrefixToKey( $key ) );
	}

	/** @inheritDoc */
	public function decrement( $key ) {
		return $this->factory->decrement( $this->addPrefixToKey( $key ) );
	}

	/** @inheritDoc */
	public function updateCount( $key, $delta ) {
		return $this->factory->updateCount( $this->addPrefixToKey( $key ), $delta );
	}

	/** @inheritDoc */
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
