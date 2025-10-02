<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Revision;

use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * Help and centralize querying archive table.
 *
 * @since 1.41
 */
class ArchiveSelectQueryBuilder extends SelectQueryBuilder {

	/**
	 * @internal use RevisionStore::newSelectQueryBuilder() instead.
	 * @param IReadableDatabase $db
	 */
	public function __construct( IReadableDatabase $db ) {
		parent::__construct( $db );

		$this->select( [
			'ar_id',
			'ar_page_id',
			'ar_namespace',
			'ar_title',
			'ar_rev_id',
			'ar_timestamp',
			'ar_minor_edit',
			'ar_deleted',
			'ar_len',
			'ar_parent_id',
			'ar_sha1',
			'ar_actor',
			'ar_user' => 'archive_actor.actor_user',
			'ar_user_text' => 'archive_actor.actor_name',
		] )
			->from( 'archive' )
			->join( 'actor', 'archive_actor', 'actor_id=ar_actor' );
	}

	/**
	 * Join the query with comment table and several fields to allow easier query.
	 *
	 * @return $this
	 */
	public function joinComment() {
		$this->fields( [
			'ar_comment_text' => 'comment_ar_comment.comment_text',
			'ar_comment_data' => 'comment_ar_comment.comment_data',
			'ar_comment_cid' => 'comment_ar_comment.comment_id',
		] );
		$this->join( 'comment', "comment_ar_comment", 'comment_ar_comment.comment_id = ar_comment_id' );
		return $this;
	}
}
