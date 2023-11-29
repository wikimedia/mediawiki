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
