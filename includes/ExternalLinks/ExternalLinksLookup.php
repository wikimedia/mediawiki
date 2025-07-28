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

namespace MediaWiki\ExternalLinks;

use MediaWiki\Deferred\LinksUpdate\ExternalLinksTable;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * Functions for looking up externallinks table
 */
class ExternalLinksLookup {

	/**
	 * Return an array of external links for a given page id
	 *
	 * @stable to call
	 * @param int $pagId
	 * @param IReadableDatabase $dbr
	 * @param string $fname
	 * @return string[] array of external links
	 * @deprecated since 1.45 use ::getExtLinksForPage() instead
	 */
	public static function getExternalLinksForPage( int $pagId, IReadableDatabase $dbr, $fname ) {
		$links = [];
		$res = $dbr->newSelectQueryBuilder()
			->select( [ 'el_to_domain_index', 'el_to_path' ] )
			->from( 'externallinks' )
			->where( [ 'el_from' => $pagId ] )
			->caller( $fname )->fetchResultSet();
		foreach ( $res as $row ) {
			$links[] = LinkFilter::reverseIndexes( $row->el_to_domain_index ) . $row->el_to_path;
		}
		return $links;
	}

	/**
	 * Return an array of external links for a given page id
	 *
	 * @stable to call
	 * @param int $pagId
	 * @param IConnectionProvider $dbProvider
	 * @param string $fname
	 * @return string[] array of external links
	 */
	public static function getExtLinksForPage( int $pagId, IConnectionProvider $dbProvider, $fname ) {
		$links = [];
		$res = $dbProvider->getReplicaDatabase( ExternalLinksTable::VIRTUAL_DOMAIN )->newSelectQueryBuilder()
			->select( [ 'el_to_domain_index', 'el_to_path' ] )
			->from( 'externallinks' )
			->where( [ 'el_from' => $pagId ] )
			->caller( $fname )->fetchResultSet();
		foreach ( $res as $row ) {
			$links[] = LinkFilter::reverseIndexes( $row->el_to_domain_index ) . $row->el_to_path;
		}
		return $links;
	}
}
