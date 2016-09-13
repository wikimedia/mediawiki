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
 * @license GPL-2.0+
 */
namespace MediaWiki\Linker;

use ObjectFactory;

class BatchLinkExistenceLookup {

	/** Keep in sync with LinkTargetExistenceLookup */
	const HANDLE_ONLY = 1;
	const HANDLE = 2;
	const SKIP = 3;
	const EXISTS = 4;
	const BROKEN = 5;

	/**
	 * (string)$name => $lookup
	 *
	 * @var LinkTargetExistenceLookup[]
	 */
	private $lookups = [];

	/**
	 * (int)$ns => $lookup
	 *
	 * @var LinkTargetExistenceLookup[]
	 */
	private $nsLookups = [];

	public function __construct( array $nsRegistry = [], array $registry = [] ) {
		$nsRegistry = [
			NS_SPECIAL => [ 'class' => SpecialPageExistenceLookup::class ],
			// NS_MEDIA is handled below because it is special
			NS_FILE => [ 'class' => FileExistenceLookup::class ],
			NS_MEDIAWIKI => [ 'class' => MessageExistenceLookup::class ],
		];

		$registry += [
			'TitleIsAlwaysKnown' => [ 'class' => TitleIsAlwaysKnownLookup::class, 'priority' => 10 ],
			'interwiki' => [ 'class' => InterwikiExistenceLookup::class, 'priority' => 20 ],
			'local' => [ 'class' => LocalExistenceLookup::class, 'priority' => 30 ],
		];

		// Sorty registry by priority
		usort( $registry, function ( array $params1, array $params2 ) {
			if ( $params1['priority'] == $params2['priority'] ) {
				return 0;
			}

			return $params1['priority'] < $params2['priority']
				? -1 : 1;
		} );

		foreach ( $nsRegistry as $ns => $params ) {
			$this->nsLookups[$ns] = ObjectFactory::getObjectFromSpec( $params );
		}
		// Support NS_MEDIA as a NS_FILE alias. Use the same object so
		// it can utilize the same internal cache
		$this->nsLookups[NS_MEDIA] = $this->nsLookups[NS_FILE];

		foreach ( $registry as $name => $params ) {
			$this->lookups[$name] = ObjectFactory::getObjectFromSpec( $params );
		}
	}

	/**
	 * @param LinkTarget $linkTarget
	 * @return int
	 */
	public function add( LinkTarget $linkTarget ) {
		$ns = $linkTarget->getNamespace();
		if ( isset( $this->nsLookups[$ns] ) ) {
			$handling = $this->nsLookups[$ns]->add( $linkTarget );
			if ( $handling == self::EXISTS || $handling == self::BROKEN
				|| $handling == self::HANDLE_ONLY
			) {
				return $handling;
			}
		}
		foreach ( $this->lookups as $lookup ) {
			$handling = $lookup->add( $linkTarget );
			if ( $handling == self::EXISTS || $handling == self::BROKEN
				|| $handling == self::HANDLE_ONLY
			) {
				return $handling;
			}
		}

		return self::SKIP;
	}

	/**
	 * Batch lookup
	 */
	public function lookup() {
		foreach ( $this->nsLookups as $lookup ) {
			$lookup->lookup();
		}
		foreach ( $this->lookups as $lookup ) {
			$lookup->lookup();
		}
	}

	/**
	 * @param LinkTarget $linkTarget
	 * @return bool
	 */
	public function exists( LinkTarget $linkTarget ) {
		$ns = $linkTarget->getNamespace();
		if ( isset( $this->nsLookups[$ns] ) ) {
			$exists = $this->nsLookups[$ns]->exists( $linkTarget );
			if ( $exists == self::EXISTS ) {
				return true;
			} elseif ( $exists == self::BROKEN ) {
				return false;
			}
		}
		foreach ( $this->lookups as $lookup ) {
			$exists = $lookup->exists( $linkTarget );
			if ( $exists == self::EXISTS ) {
				return true;
			} elseif ( $exists == self::BROKEN ) {
				return false;
			}
		}

		// Nothing said it exists
		return false;
	}
}
