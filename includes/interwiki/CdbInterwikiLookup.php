<?php
namespace MediaWiki\Interwiki;

/**
 * Wikimedia InterwikiLookup implementation based on a precomputed CDB
 * database built by WikimediaMaintenance/dumpInterwiki.php -o db.cdb
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
 */

use \Cdb\Exception as CdbException;
use \Cdb\Reader as CdbReader;
use Language;

/**
 * This is just like HashInerwikiLookup but uses a cdb file
 * to read its data.
 * @deprecated use HashInerwikiLookup
 */
class CdbInterwikiLookup extends HashInterwikiLookup  {
	/**
	 * @var string
	 */
	private $cdbData;

	/**
	 * @var CdbReader|null
	 */
	private $cdbReader = null;

	/**
	 * Build a new CdbInterwikiLookup
	 * @param Language $language
	 * @param string $cdbData path to a CDB file
	 * @param int $interwikiScopes Specify number of domains to check for messages:
	 *    - 1: Just local wiki level
	 *    - 2: wiki and global levels
	 *    - 3: site level as well as wiki and global levels
	 * @param string $fallbackSite The code to assume for the local site,
	 * @return InterwikiLookup
	 */
	function __construct(
		Language $language,
		$cdbData,
		$interwikiScopes,
		$fallbackSite
	) {
		parent::__construct( $language, [], $interwikiScopes, $fallbackSite );
		$this->cdbData = $cdbData;
	}

	protected function getCacheValue( $key ) {
		try {
			if ( $this->cdbReader === null ) {
				$this->cdbReader = \Cdb\Reader::open( $this->cdbData );
			}

			return $this->cdbReader->get( $key );
		} catch ( CdbException $e ) {
			wfDebug( __METHOD__ . ": CdbException caught, error message was "
				. $e->getMessage() );
		}
		return false;
	}
}
