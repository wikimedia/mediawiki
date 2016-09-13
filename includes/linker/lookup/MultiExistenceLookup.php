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

class MultiExistenceLookup implements LinkTargetExistenceLookup {

	/**
	 * (string)$name => $lookup
	 *
	 * @var LinkTargetExistenceLookup[]
	 */
	private $lookups = [];

	public function __construct( array $registry = [] ) {
		$registry += [
			'TitleIsAlwaysKnown' => [ 'class' => TitleIsAlwaysKnownLookup::class, 'priority' => 10 ],
			'interwiki' => [ 'class' => InterwikiExistenceLookup::class, 'priority' => 20 ],
			'special' => [ 'class' => SpecialPageExistenceLookup::class, 'priority' => 30 ],
			'message' => [ 'class' => MessageExistenceLookup::class, 'priority' => 40 ],
			'local' => [ 'class' => LocalExistenceLookup::class, 'priority' => 50 ],
			'file' => [ 'class' => FileExistenceLookup::class, 'priority' => 60 ],
		];

		// Sorty registry by priority
		usort( $registry, function ( array $params1, array $params2 ) {
			if ( $params1['priority'] == $params2['priority'] ) {
				return 0;
			}

			return $params1['priority'] < $params2['priority']
				? -1 : 1;
		} );

		foreach ( $registry as $name => $params ) {
			$this->lookups[$name] = ObjectFactory::getObjectFromSpec( $params );
		}
	}

	public function shouldHandle( LinkTarget $linkTarget ) {
		$handle = false;
		foreach ( $this->lookups as $lookup ) {
			$should = $lookup->shouldHandle( $linkTarget );
			if ( $should == self::HANDLE_ONLY ) {
				return $should;
			} elseif ( $should == self::HANDLE ) {
				$handle = true;
			}
		}

		return $handle ? self::HANDLE : self::SKIP;
	}

	/**
	 * @param LinkTarget $linkTarget
	 */
	public function add( LinkTarget $linkTarget ) {
		foreach ( $this->lookups as $lookup ) {
			$should = $lookup->shouldHandle( $linkTarget );
			if ( $should == self::HANDLE || $should == self::HANDLE_ONLY ) {
				$lookup->add( $linkTarget );
			}
			if ( $should == self::HANDLE_ONLY ) {
				return;
			}
		}

		// TODO: throw exception if nothing handles it?
	}

	/**
	 * Batch lookup
	 */
	public function lookup() {
		foreach ( $this->lookups as $lookup ) {
			$lookup->lookup();
		}
	}

	/**
	 * @param LinkTarget $linkTarget
	 * @return bool
	 */
	public function exists( LinkTarget $linkTarget ) {
		foreach ( $this->lookups as $lookup ) {
			$should = $lookup->shouldHandle( $linkTarget );
			if ( $should == self::HANDLE || $should == self::HANDLE_ONLY ) {
				$exists = $lookup->exists( $linkTarget );
				if ( $exists || $should == self::HANDLE_ONLY ) {
					return $exists;
				}
			}
		}

		return false;
	}
}
