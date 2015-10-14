<?php

namespace MediaWiki\Storage\TextTable;

use LoadBalancer;
use MediaWiki\Storage\NoSuchSlotException;
use MediaWiki\Storage\RevisionContentException;
use MediaWiki\Storage\RevisionContentLookup;
use MediaWiki\Storage\RevisionNotFoundException;
use MediaWiki\Storage\RevisionSlot;
use Title;

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
 * @since 1.27
 *
 * @file
 * @ingroup Storage
 *
 * @author Daniel Kinzler
 */

/**
 * Implementation of RevisionContentLookup that using the traditional storage schema
 * based on the revision and text tables. This supports only the "main" slot.
 */
class RevisionTableContentLookup implements RevisionContentLookup {

	/**
	 * @var LoadBalancer
	 */
	private $loadBalancer;

	/**
	 * @var bool
	 */
	private $contentHandlerUseDB;

	/**
	 * @param LoadBalancer $loadBalancer
	 * @param bool $contentHandlerUseDB
	 */
	public function __construct( LoadBalancer $loadBalancer, $contentHandlerUseDB = true ) {
		$this->loadBalancer = $loadBalancer;
		$this->contentHandlerUseDB = $contentHandlerUseDB;
	}

	/**
	 * Return the list of revision fields that should be selected to create
	 * a new revision.
	 * @return array
	 */
	private function getFields() {
		$fields = array(
			'rev_id',
			'rev_page',
			'rev_text_id',
			'rev_timestamp',
			'rev_comment',
			'rev_user_text',
			'rev_user',
			'rev_minor_edit',
			'rev_deleted',
			'rev_len',
			'rev_parent_id',
			'rev_sha1',
		);

		if ( $this->contentHandlerUseDB ) {
			$fields[] = 'rev_content_format';
			$fields[] = 'rev_content_model';
		}

		return $fields;
	}

	/**
	 * @param Title $title
	 * @param int $revisionId The revision ID (not 0)
	 * @param string $slot The slot name.
	 *
	 * @throws RevisionContentException
	 * @return RevisionSlot
	 */
	public function getRevisionSlot( Title $title, $revisionId, $slot = 'main' ) {
		if ( $slot !== 'main' ) {
			throw new NoSuchSlotException( $title, $revisionId, $slot );
		}

		$dbr = $this->loadBalancer->getConnection( DB_SLAVE );
		$row = $dbr->selectRow(
			'revision',
			$this->getFields(),
			array(
				'rev_id' => $revisionId,
			),
			__METHOD__
		);

		$this->loadBalancer->reuseConnection( $dbr );

		if ( !$row ) {
			throw new RevisionNotFoundException( $title, $revisionId, $slot );
		}

		if ( $row->rev_page !== $title->getArticleID() ) {
			throw new RevisionNotFoundException( $title, $revisionId, $slot );
		}

		return new TextTableRevisionSlot(
			$title,
			$row
		);
	}

	/**
	 * Lists the primary content slots associated with the given revision. Primary slots contain
	 * original, user supplied content. They are persistent and immutable.
	 *
	 * @param Title $title
	 * @param int $revisionId
	 *
	 * @throws RevisionContentException
	 * @return string[]
	 */
	function listPrimarySlots( Title $title, $revisionId ) {
		return array( 'main' );
	}

}
