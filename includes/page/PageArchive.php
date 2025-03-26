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

namespace MediaWiki\Page;

use MediaWiki\FileRepo\File\FileSelectQueryBuilder;
use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\LikeValue;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * Used to show archived pages and eventually restore them.
 */
class PageArchive {

	protected Title $title;

	public function __construct( Title $title ) {
		$this->title = $title;
	}

	/**
	 * List deleted pages recorded in the archive matching the
	 * given term, using search engine archive.
	 * Returns result wrapper with (ar_namespace, ar_title, count) fields.
	 *
	 * @param string $term Search term
	 * @return IResultWrapper|bool
	 */
	public static function listPagesBySearch( $term ) {
		$title = Title::newFromText( $term );
		if ( $title ) {
			$ns = $title->getNamespace();
			$termMain = $title->getText();
			$termDb = $title->getDBkey();
		} else {
			// Prolly won't work too good
			// @todo handle bare namespace names cleanly?
			$ns = 0;
			$termMain = $termDb = $term;
		}

		// Try search engine first
		$engine = MediaWikiServices::getInstance()->newSearchEngine();
		$engine->setLimitOffset( 100 );
		$engine->setNamespaces( [ $ns ] );
		$results = $engine->searchArchiveTitle( $termMain );
		if ( !$results->isOK() ) {
			$results = [];
		} else {
			$results = $results->getValue();
		}

		if ( !$results ) {
			// Fall back to regular prefix search
			return self::listPagesByPrefix( $term );
		}

		$dbr = MediaWikiServices::getInstance()->getConnectionProvider()->getReplicaDatabase();
		$condTitles = array_values( array_unique( array_map( static function ( Title $t ) {
			return $t->getDBkey();
		}, $results ) ) );
		$conds = [
			'ar_namespace' => $ns,
			$dbr->expr( 'ar_title', '=', $condTitles )
				->or( 'ar_title', IExpression::LIKE, new LikeValue( $termDb, $dbr->anyString() ) ),
		];

		return self::listPages( $dbr, $conds );
	}

	/**
	 * List deleted pages recorded in the archive table matching the
	 * given title prefix.
	 * Returns result wrapper with (ar_namespace, ar_title, count) fields.
	 *
	 * @param string $prefix Title prefix
	 * @return IResultWrapper|bool
	 */
	public static function listPagesByPrefix( $prefix ) {
		$dbr = MediaWikiServices::getInstance()->getConnectionProvider()->getReplicaDatabase();

		$title = Title::newFromText( $prefix );
		if ( $title ) {
			$ns = $title->getNamespace();
			$prefix = $title->getDBkey();
		} else {
			// Prolly won't work too good
			// @todo handle bare namespace names cleanly?
			$ns = 0;
		}

		$conds = [
			'ar_namespace' => $ns,
			$dbr->expr( 'ar_title', IExpression::LIKE, new LikeValue( $prefix, $dbr->anyString() ) ),
		];

		return self::listPages( $dbr, $conds );
	}

	/**
	 * @param IReadableDatabase $dbr
	 * @param string|array $condition
	 * @return IResultWrapper
	 */
	protected static function listPages( IReadableDatabase $dbr, $condition ) {
		return $dbr->newSelectQueryBuilder()
			->select( [ 'ar_namespace', 'ar_title', 'count' => 'COUNT(*)' ] )
			->from( 'archive' )
			->where( $condition )
			->groupBy( [ 'ar_namespace', 'ar_title' ] )
			->orderBy( [ 'ar_namespace', 'ar_title' ] )
			->limit( 100 )
			->caller( __METHOD__ )->fetchResultSet();
	}

	/**
	 * List the deleted file revisions for this page, if it's a file page.
	 * Returns a result wrapper with various filearchive fields, or null
	 * if not a file page.
	 *
	 * @return IResultWrapper|null
	 * @todo Does this belong in Image for fuller encapsulation?
	 */
	public function listFiles() {
		if ( $this->title->getNamespace() !== NS_FILE ) {
			return null;
		}

		$dbr = MediaWikiServices::getInstance()->getConnectionProvider()->getReplicaDatabase();
		$queryBuilder = FileSelectQueryBuilder::newForArchivedFile( $dbr );
		$queryBuilder->where( [ 'fa_name' => $this->title->getDBkey() ] )
			->orderBy( 'fa_timestamp', SelectQueryBuilder::SORT_DESC );
		return $queryBuilder->caller( __METHOD__ )->fetchResultSet();
	}

}

/** @deprecated class alias since 1.44 */
class_alias( PageArchive::class, 'PageArchive' );
