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
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Base class for reliable event relays
 *
 * @stable to extend
 */
abstract class EventRelayer implements LoggerAwareInterface {
	/** @var LoggerInterface */
	protected $logger;

	/**
	 * @stable to call
	 *
	 * @param array $params
	 */
	public function __construct( array $params ) {
		$this->logger = new NullLogger();
	}

	/**
	 * @param string $channel
	 * @param array $event Event data map
	 * @return bool Success
	 */
	final public function notify( $channel, $event ) {
		return $this->doNotify( $channel, [ $event ] );
	}

	/**
	 * @param string $channel
	 * @param array $events List of event data maps
	 * @return bool Success
	 */
	final public function notifyMulti( $channel, $events ) {
		return $this->doNotify( $channel, $events );
	}

	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * @param string $channel
	 * @param array $events List of event data maps
	 * @return bool Success
	 */
	abstract protected function doNotify( $channel, array $events );
}
