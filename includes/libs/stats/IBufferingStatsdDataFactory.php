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
	 * Check whether this data factory has any data.
	 * @return bool
	 */
	public function hasData();

	/**
	 * Return data from the factory.
	 * @return StatsdData[]
	 */
	public function getData();

	/**
	 * Set collection enable status.
	 * @param bool $enabled Will collection be enabled?
	 * @return void
	 */
	public function setEnabled( $enabled );

}
