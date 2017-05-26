<?php
use Liuggio\StatsdClient\Entity\StatsdData;
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;

/**
 * Mediawiki adaptation of Statsd data factory.
 */
interface MediawikiStatsdDataFactory extends StatsdDataFactoryInterface {
	/**
	 * Check whether this data factory has any data.
	 * @return boolean
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
