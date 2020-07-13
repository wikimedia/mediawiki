<?php

use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;

/**
 * Describes a Statsd aware interface
 *
 * @stable to implement
 *
 * @since 1.27
 * @author Addshore
 */
interface StatsdAwareInterface {

	/**
	 * Sets a StatsdDataFactory instance on the object
	 *
	 * @param StatsdDataFactoryInterface $statsFactory
	 * @return null
	 */
	public function setStatsdDataFactory( StatsdDataFactoryInterface $statsFactory );

}
