<?php
/**
 * External storage in some particular medium.
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
 * @ingroup ExternalStorage
 */

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Key/value blob storage for a particular storage medium type (e.g. RDBMs, files)
 *
 * There can be multiple "locations" for a storage medium type (e.g. DB clusters, filesystems).
 * Blobs are stored under URLs of the form "<protocol>://<location>/<path>". Each type of storage
 * medium has an associated protocol.
 *
 * @ingroup ExternalStorage
 * @since 1.21
 */
abstract class ExternalStoreMedium implements LoggerAwareInterface {
	/** @var array Usage context options for this instance */
	protected $params = [];
	/** @var string Default database domain to store content under */
	protected $dbDomain;
	/** @var bool Whether this was factoried with an explicit DB domain */
	protected $isDbDomainExplicit;

	/** @var LoggerInterface */
	protected $logger;

	/**
	 * @param array $params Usage context options for this instance:
	 *   - domain: the DB domain ID of the wiki the content is for [required]
	 *   - logger: LoggerInterface instance [optional]
	 *   - isDomainImplicit: whether this was factoried without an explicit DB domain [optional]
	 */
	public function __construct( array $params ) {
		$this->params = $params;
		if ( isset( $params['domain'] ) ) {
			$this->dbDomain = $params['domain'];
			$this->isDbDomainExplicit = empty( $params['isDomainImplicit'] );
		} else {
			throw new InvalidArgumentException( 'Missing DB "domain" parameter.' );
		}

		$this->logger = $params['logger'] ?? new NullLogger();
	}

	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * Fetch data from given external store URL
	 *
	 * @param string $url An external store URL
	 * @return string|bool The text stored or false on error
	 * @throws MWException
	 */
	abstract public function fetchFromURL( $url );

	/**
	 * Fetch data from given external store URLs.
	 *
	 * @param array $urls A list of external store URLs
	 * @return string[] Map of (url => text) for the URLs where data was actually found
	 */
	public function batchFetchFromURLs( array $urls ) {
		$retval = [];
		foreach ( $urls as $url ) {
			$data = $this->fetchFromURL( $url );
			// Dont return when false to allow for simpler implementations
			if ( $data !== false ) {
				$retval[$url] = $data;
			}
		}

		return $retval;
	}

	/**
	 * Insert a data item into a given location
	 *
	 * @param string $location The location name
	 * @param string $data The data item
	 * @return string|bool The URL of the stored data item, or false on error
	 * @throws MWException
	 */
	abstract public function store( $location, $data );

	/**
	 * Check if a given location is read-only
	 *
	 * @param string $location The location name
	 * @return bool Whether this location is read-only
	 * @since 1.31
	 */
	public function isReadOnly( $location ) {
		return false;
	}
}
