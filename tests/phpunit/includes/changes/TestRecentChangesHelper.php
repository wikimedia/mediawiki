<?php

/**
 * Helper for generating test recent changes entries.
 *
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class TestRecentChangesHelper {

	public function makeEditRecentChange( User $user, $titleText, $curid, $thisid, $lastid,
		$timestamp, $counter, $watchingUsers
	) {

		$attribs = array_merge(
			$this->getDefaultAttributes( $titleText, $timestamp ),
			array(
				'rc_user' => $user->getId(),
				'rc_user_text' => $user->getName(),
				'rc_this_oldid' => $thisid,
				'rc_last_oldid' => $lastid,
				'rc_cur_id' => $curid
			)
		);

		return $this->makeRecentChange( $attribs, $counter, $watchingUsers );
	}

	public function makeLogRecentChange( $logType, $logAction, User $user, $titleText, $timestamp, $counter,
		$watchingUsers
	) {
		$attribs = array_merge(
			$this->getDefaultAttributes( $titleText, $timestamp ),
			array(
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
				'rc_source' => 'mw.log'
			)
		);

		return $this->makeRecentChange( $attribs, $counter, $watchingUsers );
	}

	public function makeDeletedEditRecentChange( User $user, $titleText, $timestamp, $curid,
		$thisid, $lastid, $counter, $watchingUsers
	) {
		$attribs = array_merge(
			$this->getDefaultAttributes( $titleText, $timestamp ),
			array(
				'rc_user' => $user->getId(),
				'rc_user_text' => $user->getName(),
				'rc_deleted' => 5,
				'rc_cur_id' => $curid,
				'rc_this_oldid' => $thisid,
				'rc_last_oldid' => $lastid
			)
		);

		return $this->makeRecentChange( $attribs, $counter, $watchingUsers );
	}

	public function makeNewBotEditRecentChange( User $user, $titleText, $curid, $thisid, $lastid,
		$timestamp, $counter, $watchingUsers
	) {

		$attribs = array_merge(
			$this->getDefaultAttributes( $titleText, $timestamp ),
			array(
				'rc_user' => $user->getId(),
				'rc_user_text' => $user->getName(),
				'rc_this_oldid' => $thisid,
				'rc_last_oldid' => $lastid,
				'rc_cur_id' => $curid,
				'rc_type' => 1,
				'rc_bot' => 1,
				'rc_source' => 'mw.new'
			)
		);

		return $this->makeRecentChange( $attribs, $counter, $watchingUsers );
	}

	private function makeRecentChange( $attribs, $counter, $watchingUsers ) {
		$change = new RecentChange();
		$change->setAttribs( $attribs );
		$change->counter = $counter;
		$change->numberofWatchingusers = $watchingUsers;

		return $change;
	}

	private function getDefaultAttributes( $titleText, $timestamp ) {
		return array(
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
			'rc_minor' => 0,
			'rc_bot' => 0,
			'rc_type' => 0,
			'rc_patrolled' => 1,
			'rc_deleted' => 0,
			'rc_logid' => 0,
			'rc_log_type' => null,
			'rc_log_action' => '',
			'rc_params' => '',
			'rc_source' => 'mw.edit'
		);
	}

	public function getTestContext( User $user ) {
		$context = new RequestContext();
		$context->setLanguage( Language::factory( 'en' ) );

		$context->setUser( $user );

		$title = Title::newFromText( 'RecentChanges', NS_SPECIAL );
		$context->setTitle( $title );

		return $context;
	}
}
