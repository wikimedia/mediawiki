<?php

use MediaWiki\Context\RequestContext;
use MediaWiki\MediaWikiServices;
use MediaWiki\RecentChanges\RCCacheEntryFactory;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;

/**
 * Helper for generating test recent changes entries.
 *
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class TestRecentChangesHelper {

	public function makeEditRecentChange( UserIdentity $user, string $titleText, ?int $curid, ?int $thisid, ?int $lastid,
		string $timestamp, int $counter, int $watchingUsers
	): RecentChange {
		$attribs = array_merge(
			$this->getDefaultAttributes( $titleText, $timestamp ),
			[
				'rc_user' => $user->getId(),
				'rc_user_text' => $user->getName(),
				'rc_this_oldid' => $thisid,
				'rc_last_oldid' => $lastid,
				'rc_cur_id' => $curid
			]
		);

		return $this->makeRecentChange( $attribs, $counter, $watchingUsers );
	}

	public function makeLogRecentChange(
		string $logType, string $logAction, UserIdentity $user, string $titleText, string $timestamp, int $counter, int $watchingUsers,
		array $additionalAttribs = []
	): RecentChange {
		$attribs = array_merge(
			$this->getDefaultAttributes( $titleText, $timestamp ),
			[
				'rc_cur_id' => 0,
				'rc_user' => $user->getId(),
				'rc_user_text' => $user->getName(),
				'rc_this_oldid' => 0,
				'rc_last_oldid' => 0,
				'rc_old_len' => null,
				'rc_new_len' => null,
				'rc_type' => 3,
				'rc_logid' => 25,
				'rc_log_type' => $logType,
				'rc_log_action' => $logAction,
				'rc_source' => RecentChange::SRC_LOG
			],
			$additionalAttribs
		);

		return $this->makeRecentChange( $attribs, $counter, $watchingUsers );
	}

	public function makeDeletedEditRecentChange( UserIdentity $user, string $titleText, string $timestamp, ?int $curid,
		?int $thisid, ?int $lastid, int $counter, int $watchingUsers
	): RecentChange {
		$attribs = array_merge(
			$this->getDefaultAttributes( $titleText, $timestamp ),
			[
				'rc_user' => $user->getId(),
				'rc_user_text' => $user->getName(),
				'rc_deleted' => 5,
				'rc_cur_id' => $curid,
				'rc_this_oldid' => $thisid,
				'rc_last_oldid' => $lastid
			]
		);

		return $this->makeRecentChange( $attribs, $counter, $watchingUsers );
	}

	public function makeNewBotEditRecentChange( UserIdentity $user, string $titleText, ?int $curid, ?int $thisid, ?int $lastid,
		string $timestamp, int $counter, int $watchingUsers
	): RecentChange {
		$attribs = array_merge(
			$this->getDefaultAttributes( $titleText, $timestamp ),
			[
				'rc_user' => $user->getId(),
				'rc_user_text' => $user->getName(),
				'rc_this_oldid' => $thisid,
				'rc_last_oldid' => $lastid,
				'rc_cur_id' => $curid,
				'rc_type' => 1,
				'rc_bot' => 1,
				'rc_source' => RecentChange::SRC_NEW
			]
		);

		return $this->makeRecentChange( $attribs, $counter, $watchingUsers );
	}

	private function makeRecentChange( array $attribs, int $counter, int $watchingUsers ): RecentChange {
		$change = new RecentChange();
		$change->setAttribs( $attribs );
		$change->counter = $counter;
		$change->numberofWatchingusers = $watchingUsers;

		return $change;
	}

	public function getCacheEntry( RecentChange $recentChange ): RecentChange {
		$rcCacheFactory = new RCCacheEntryFactory(
			new RequestContext(),
			[ 'diff' => 'diff', 'cur' => 'cur', 'last' => 'last' ],
			MediaWikiServices::getInstance()->getLinkRenderer(),
			MediaWikiServices::getInstance()->getUserLinkRenderer()
		);
		return $rcCacheFactory->newFromRecentChange( $recentChange, false );
	}

	public function makeCategorizationRecentChange(
		UserIdentity $user, string $titleText, ?int $curid, ?int $thisid, ?int $lastid, string $timestamp
	): RecentChange {
		$attribs = array_merge(
			$this->getDefaultAttributes( $titleText, $timestamp ),
			[
				'rc_type' => RC_CATEGORIZE,
				'rc_source' => RecentChange::SRC_CATEGORIZE,
				'rc_user' => $user->getId(),
				'rc_user_text' => $user->getName(),
				'rc_this_oldid' => $thisid,
				'rc_last_oldid' => $lastid,
				'rc_cur_id' => $curid,
				'rc_comment' => '[[:Testpage]] added to category',
				'rc_comment_text' => '[[:Testpage]] added to category',
				'rc_comment_data' => null,
				'rc_old_len' => 0,
				'rc_new_len' => 0,
			]
		);

		return $this->makeRecentChange( $attribs, 0, 0 );
	}

	private function getDefaultAttributes( string $titleText, string $timestamp ): array {
		return [
			'rc_id' => 545,
			'rc_user' => 0,
			'rc_user_text' => '127.0.0.1',
			'rc_ip' => '127.0.0.1',
			'rc_title' => $titleText,
			'rc_namespace' => 0,
			'rc_timestamp' => $timestamp,
			'rc_old_len' => 212,
			'rc_new_len' => 188,
			'rc_comment' => '',
			'rc_comment_text' => '',
			'rc_comment_data' => null,
			'rc_minor' => 0,
			'rc_bot' => 0,
			'rc_type' => 0,
			'rc_patrolled' => 1,
			'rc_deleted' => 0,
			'rc_logid' => 0,
			'rc_log_type' => null,
			'rc_log_action' => '',
			'rc_params' => '',
			'rc_source' => RecentChange::SRC_EDIT
		];
	}

	public function getTestContext( User $user ): RequestContext {
		$context = new RequestContext();
		$context->setLanguage( 'en' );

		$context->setUser( $user );

		$title = Title::makeTitle( NS_SPECIAL, 'RecentChanges' );
		$context->setTitle( $title );

		return $context;
	}
}
