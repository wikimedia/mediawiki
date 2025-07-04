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
 * @ingroup RevisionDelete
 */

use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Context\IContextSource;
use MediaWiki\Logging\LogFormatterFactory;
use MediaWiki\Logging\LogPage;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\SpecialPage\SpecialPage;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * List for logging table items
 */
class RevDelLogList extends RevDelList {

	protected const SUPPRESS_BIT = LogPage::DELETED_RESTRICTED;

	/** @var CommentStore */
	private $commentStore;
	private LogFormatterFactory $logFormatterFactory;

	/**
	 * @internal Use RevisionDeleter
	 * @param IContextSource $context
	 * @param PageIdentity $page
	 * @param array $ids
	 * @param LBFactory $lbFactory
	 * @param CommentStore $commentStore
	 * @param LogFormatterFactory $logFormatterFactory
	 */
	public function __construct(
		IContextSource $context,
		PageIdentity $page,
		array $ids,
		LBFactory $lbFactory,
		CommentStore $commentStore,
		LogFormatterFactory $logFormatterFactory
	) {
		parent::__construct( $context, $page, $ids, $lbFactory );
		$this->commentStore = $commentStore;
		$this->logFormatterFactory = $logFormatterFactory;
	}

	/** @inheritDoc */
	public function getType() {
		return 'logging';
	}

	/** @inheritDoc */
	public static function getRelationType() {
		return 'log_id';
	}

	/** @inheritDoc */
	public static function getRestriction() {
		return 'deletelogentry';
	}

	/** @inheritDoc */
	public static function getRevdelConstant() {
		return LogPage::DELETED_ACTION;
	}

	/** @inheritDoc */
	public static function suggestTarget( $target, array $ids ) {
		$dbr = MediaWikiServices::getInstance()->getConnectionProvider()->getReplicaDatabase();
		$result = $dbr->newSelectQueryBuilder()
			->select( 'log_type' )
			->distinct()
			->from( 'logging' )
			->where( [ 'log_id' => $ids ] )
			->caller( __METHOD__ )->fetchResultSet();
		if ( $result->numRows() == 1 ) {
			// If there's only one type, the target can be set to include it.
			return SpecialPage::getTitleFor( 'Log', $result->current()->log_type );
		}

		return SpecialPage::getTitleFor( 'Log' );
	}

	/**
	 * @param \Wikimedia\Rdbms\IReadableDatabase $db
	 * @return IResultWrapper
	 */
	public function doQuery( $db ) {
		$ids = array_map( 'intval', $this->ids );
		$queryBuilder = $db->newSelectQueryBuilder()
			->select( [
				'log_id',
				'log_type',
				'log_action',
				'log_timestamp',
				'log_actor',
				'log_namespace',
				'log_title',
				'log_page',
				'log_params',
				'log_deleted',
				'log_user' => 'actor_user',
				'log_user_text' => 'actor_name',
				'log_comment_text' => 'comment_log_comment.comment_text',
				'log_comment_data' => 'comment_log_comment.comment_data',
				'log_comment_cid' => 'comment_log_comment.comment_id'
			] )
			->from( 'logging' )
			->join( 'actor', null, 'actor_id=log_actor' )
			->join( 'comment', 'comment_log_comment', 'comment_log_comment.comment_id = log_comment_id' )
			->where( [ 'log_id' => $ids ] )
			->orderBy( [ 'log_timestamp', 'log_id' ], SelectQueryBuilder::SORT_DESC );

		MediaWikiServices::getInstance()->getChangeTagsStore()->modifyDisplayQueryBuilder( $queryBuilder, 'logging' );

		return $queryBuilder->caller( __METHOD__ )->fetchResultSet();
	}

	/** @inheritDoc */
	public function newItem( $row ) {
		return new RevDelLogItem(
			$this,
			$row,
			$this->commentStore,
			MediaWikiServices::getInstance()->getConnectionProvider(),
			$this->logFormatterFactory
		);
	}

	/** @inheritDoc */
	public function getLogAction() {
		return 'event';
	}

	/** @inheritDoc */
	public function getLogParams( $params ) {
		return [
			'4::ids' => $params['ids'],
			'5::ofield' => $params['oldBits'],
			'6::nfield' => $params['newBits'],
		];
	}
}
