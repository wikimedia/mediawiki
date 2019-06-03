<?php

use Liuggio\StatsdClient\Entity\StatsdData;
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;

/**
 * MediaWiki adaptation of StatsdDataFactory that provides buffering functionality.
 *
 * @see BufferingStatsdDataFactory
 */
interface IBufferingStatsdDataFactory extends StatsdDataFactoryInterface {
	/**
	 * Check whether this data factory has any buffered data.
	 * @return bool
	 */
	public function hasData();

	/**
	 * Return the buffered data from the factory.
	 * @return StatsdData[]
	 */
	public function getData();

	/**
	 * Clear all buffered data from the factory
	 * @since 1.31
	 */
	public function clearData();

	/**
	 * Return the number of buffered statsd data entries
	 * @return int
	 * @since 1.31
	 */
	public function getDataCount();

	/**
	 * Set collection enable status.
	 * @param bool $enabled Will collection be enabled?
	 * @return void
	 */
	public function setEnabled( $enabled );
}
