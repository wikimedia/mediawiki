<?php
/**
 * @license GPL-2.0-or-later
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
		wfDeprecated( __METHOD__, '1.45' );
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
