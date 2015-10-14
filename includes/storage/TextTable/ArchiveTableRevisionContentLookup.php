<?php

namespace MediaWiki\Storage;

use LoadBalancer;
use MediaWiki\Storage\TextTable\ArchiveTableRevisionSlot;
use MediaWiki\Storage\TextTable\RevisionTableContentLookup;
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
 * based on the archive table. This supports only the "main" slot.
 */
class ArchiveTableRevisionContentLookup extends RevisionTableContentLookup {

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
			'ar_rev_id',
			'ar_text',
			'ar_comment',
			'ar_user',
			'ar_user_text',
			'ar_timestamp',
			'ar_minor_edit',
			'ar_flags',
			'ar_text_id',
			'ar_deleted',
			'ar_len',
			'ar_sha1',
		);

		if ( $this->contentHandlerUseDB ) {
			$fields[] = 'ar_content_format';
			$fields[] = 'ar_content_model';
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
	function getRevisionSlot( Title $title, $revisionId, $slot = 'main' ) {
		if ( $slot !== 'main' ) {
			throw new NoSuchSlotException( $title, $revisionId, $slot );
		}

		$dbr = $this->loadBalancer->getConnection( DB_SLAVE );

		$row = $dbr->selectRow( 'archive',
			$this->getFields(),
			array( 'ar_namespace' => $title->getNamespace(),
				'ar_title' => $title->getDBkey(),
				'ar_rev_id' => $revisionId ),
			__METHOD__ );

		$this->loadBalancer->reuseConnection( $dbr );

		if ( !$row ) {
			throw new RevisionNotFoundException( $title, $revisionId, $slot );
		}

		return new ArchiveTableRevisionSlot(
			$title,
			$row
		);
	}

}
