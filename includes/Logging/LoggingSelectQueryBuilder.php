<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Logging;

use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * Help and centralize querying logging table.
 *
 * @since 1.41
 */
class LoggingSelectQueryBuilder extends SelectQueryBuilder {

	/**
	 * @internal use DatabaseLogEntry::newSelectQueryBuilder() instead.
	 * @param IReadableDatabase $db
	 */
	public function __construct( IReadableDatabase $db ) {
		parent::__construct( $db );
		$this->select( [
			'log_id', 'log_type', 'log_action', 'log_timestamp',
			'log_namespace', 'log_title', // unused log_page
			'log_params', 'log_deleted',
			'user_id',
			'user_name',
			'log_actor',
			'log_user' => 'logging_actor.actor_user',
			'log_user_text' => 'logging_actor.actor_name',
			'log_comment_text' => 'comment_log_comment.comment_text',
			'log_comment_data' => 'comment_log_comment.comment_data',
			'log_comment_cid' => 'comment_log_comment.comment_id',
		] )
			->from( 'logging' )
			->join( 'actor', 'logging_actor', 'actor_id=log_actor' )
			->leftJoin( 'user', null, 'user_id=logging_actor.actor_user' )
			->join(
				'comment',
				'comment_log_comment',
				'comment_log_comment.comment_id = log_comment_id'
			);
	}

}
