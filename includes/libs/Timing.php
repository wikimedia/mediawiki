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
 * An interface to help developers measure the performance of their applications.
 * This interface closely matches the W3C's User Timing specification.
 * The key differences are:
 *
 * - The reference point for all measurements which do not explicitly specify
 *   a start time is $_SERVER['REQUEST_TIME_FLOAT'], not navigationStart.
 * - Successive calls to mark() and measure() with the same entry name cause
 *   the previous entry to be overwritten. This ensures that there is a 1:1
 *   mapping between names and entries.
 * - Because there is a 1:1 mapping, instead of getEntriesByName(), we have
 *   getEntryByName().
 *
 * The in-line documentation incorporates content from the User Timing Specification
 * https://www.w3.org/TR/user-timing/
 * Copyright © 2013 World Wide Web Consortium, (MIT, ERCIM, Keio, Beihang).
 * https://www.w3.org/Consortium/Legal/2015/doc-license
 *
 * @since 1.27
 */
class Timing implements LoggerAwareInterface {

	/** @var array[] */
	private $entries = [];

	/** @var LoggerInterface */
	protected $logger;

	public function __construct( array $params = [] ) {
		$this->clearMarks();
		$this->setLogger( isset( $params['logger'] ) ? $params['logger'] : new NullLogger() );
	}

	/**
	 * Sets a logger instance on the object.
	 *
	 * @param LoggerInterface $logger
	 * @return null
	 */
	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * Store a timestamp with the associated name (a "mark")
	 *
	 * @param string $markName The name associated with the timestamp.
	 *  If there already exists an entry by that name, it is overwritten.
	 * @return array The mark that has been created.
	 */
	public function mark( $markName ) {
		$this->entries[$markName] = [
			'name'      => $markName,
			'entryType' => 'mark',
			'startTime' => microtime( true ),
			'duration'  => 0,
		];
		return $this->entries[$markName];
	}

	/**
	 * @param string $markName The name of the mark that should
	 *  be cleared. If not specified, all marks will be cleared.
	 */
	public function clearMarks( $markName = null ) {
		if ( $markName !== null ) {
			unset( $this->entries[$markName] );
		} else {
			$this->entries = [
				'requestStart' => [
					'name'      => 'requestStart',
					'entryType' => 'mark',
					'startTime' => $_SERVER['REQUEST_TIME_FLOAT'],
					'duration'  => 0,
				],
			];
		}
	}

	/**
	 * This method stores the duration between two marks along with
	 * the associated name (a "measure").
	 *
	 * If neither the startMark nor the endMark argument is specified,
	 * measure() will store the duration from $_SERVER['REQUEST_TIME_FLOAT'] to
	 * the current time.
	 * If the startMark argument is specified, but the endMark argument is not
	 * specified, measure() will store the duration from the most recent
	 * occurrence of the start mark to the current time.
	 * If both the startMark and endMark arguments are specified, measure()
	 * will store the duration from the most recent occurrence of the start
	 * mark to the most recent occurrence of the end mark.
	 *
	 * @param string $measureName
	 * @param string $startMark
	 * @param string $endMark
	 * @return array|bool The measure that has been created, or false if either
	 *  the start mark or the end mark do not exist.
	 */
	public function measure( $measureName, $startMark = 'requestStart', $endMark = null ) {
		$start = $this->getEntryByName( $startMark );
		if ( $start === null ) {
			$this->logger->error( __METHOD__ . ": The mark '$startMark' does not exist" );
			return false;
		}
		$startTime = $start['startTime'];

		if ( $endMark ) {
			$end = $this->getEntryByName( $endMark );
			if ( $end === null ) {
				$this->logger->error( __METHOD__ . ": The mark '$endMark' does not exist" );
				return false;
			}
			$endTime = $end['startTime'];
		} else {
			$endTime = microtime( true );
		}

		$this->entries[$measureName] = [
			'name'      => $measureName,
			'entryType' => 'measure',
			'startTime' => $startTime,
			'duration'  => $endTime - $startTime,
		];

		return $this->entries[$measureName];
	}

	/**
	 * Sort entries in chronological order with respect to startTime.
	 */
	private function sortEntries() {
		uasort( $this->entries, function ( $a, $b ) {
			return 10000 * ( $a['startTime'] - $b['startTime'] );
		} );
	}

	/**
	 * @return array[] All entries in chronological order.
	 */
	public function getEntries() {
		$this->sortEntries();
		return $this->entries;
	}

	/**
	 * @param string $entryType
	 * @return array[] Entries (in chronological order) that have the same value
	 *  for the entryType attribute as the $entryType parameter.
	 */
	public function getEntriesByType( $entryType ) {
		$this->sortEntries();
		$entries = [];
		foreach ( $this->entries as $entry ) {
			if ( $entry['entryType'] === $entryType ) {
				$entries[] = $entry;
			}
		}
		return $entries;
	}

	/**
	 * @param string $name
	 * @return array|null Entry named $name or null if it does not exist.
	 */
	public function getEntryByName( $name ) {
		return isset( $this->entries[$name] ) ? $this->entries[$name] : null;
	}
}
