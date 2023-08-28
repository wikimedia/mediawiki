<?php
/**
 * Holders of revision list for a single page
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

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\SelectQueryBuilder;

class RevisionList extends RevisionListBase {
	/** @inheritDoc */
	public function getType() {
		return 'revision';
	}

	/** @inheritDoc */
	public function doQuery( $db ) {
		$queryBuilder = MediaWikiServices::getInstance()->getRevisionStore()->newSelectQueryBuilder( $db )
			->joinComment()
			->joinPage()
			->joinUser()
			->where( [ 'rev_page' => $this->page->getId() ] )
			->orderBy( 'rev_id', SelectQueryBuilder::SORT_DESC );
		if ( $this->ids !== null ) {
			$queryBuilder->andWhere( [ 'rev_id' => array_map( 'intval', $this->ids ) ] );
		}
		return $queryBuilder->caller( __METHOD__ )->fetchResultSet();
	}

	/** @inheritDoc */
	public function newItem( $row ) {
		return new RevisionItem( $this, $row );
	}
}
