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
use Wikimedia\Rdbms\IDatabase;

class RevisionList extends RevisionListBase {
	public function getType() {
		return 'revision';
	}

	/**
	 * @param IDatabase $db
	 * @return mixed
	 */
	public function doQuery( $db ) {
		$conds = [ 'rev_page' => $this->title->getArticleID() ];
		if ( $this->ids !== null ) {
			$conds['rev_id'] = array_map( 'intval', $this->ids );
		}
		$revQuery = MediaWikiServices::getInstance()
			->getRevisionStore()
			->getQueryInfo( [ 'page', 'user' ] );
		return $db->select(
			$revQuery['tables'],
			$revQuery['fields'],
			$conds,
			__METHOD__,
			[ 'ORDER BY' => 'rev_id DESC' ],
			$revQuery['joins']
		);
	}

	public function newItem( $row ) {
		return new RevisionItem( $this, $row );
	}
}
