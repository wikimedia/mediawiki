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

namespace MediaWiki\Revision;

use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * Help and centralize querying revision table.
 *
 * @since 1.41
 */
class RevisionSelectQueryBuilder extends SelectQueryBuilder {

	/**
	 * @internal use RevisionStore::newSelectQueryBuilder() instead.
	 * @param IReadableDatabase $db
	 */
	public function __construct( IReadableDatabase $db ) {
		parent::__construct( $db );

		$this->select( [
			'rev_id',
			'rev_page',
			'rev_timestamp',
			'rev_minor_edit',
			'rev_deleted',
			'rev_len',
			'rev_parent_id',
			'rev_sha1',
			'rev_user' => 'actor_rev_user.actor_user',
			'rev_user_text' => 'actor_rev_user.actor_name',
			'rev_actor' => 'rev_actor'
		] )
			->from( 'revision' )
			->join( 'actor', 'actor_rev_user', 'actor_rev_user.actor_id = rev_actor' );
	}

	/**
	 * Join the query with user table and add user_name field.
	 *
	 * @return $this
	 */
	public function joinUser() {
		$this->field( 'user_name' )
			->leftJoin(
				'user',
				null,
				[ $this->db->expr( 'actor_rev_user.actor_user', '!=', 0 ), "user_id = actor_rev_user.actor_user" ]
			);

		return $this;
	}

	/**
	 * Join the query with page table and several fields to allow easier query.
	 *
	 * @return $this
	 */
	public function joinPage() {
		$this->fields( [
			'page_namespace',
			'page_title',
			'page_id',
			'page_latest',
			'page_is_redirect',
			'page_len',
		] )
		->join( 'page', null, 'page_id = rev_page' );

		return $this;
	}

	/**
	 * Join the query with comment table and several fields to allow easier query.
	 *
	 * @return $this
	 */
	public function joinComment() {
		$this->fields( [
			'rev_comment_text' => 'comment_rev_comment.comment_text',
			'rev_comment_data' => 'comment_rev_comment.comment_data',
			'rev_comment_cid' => 'comment_rev_comment.comment_id',
		] );
		$this->join( 'comment', "comment_rev_comment", 'comment_rev_comment.comment_id = rev_comment_id' );
		return $this;
	}
}
