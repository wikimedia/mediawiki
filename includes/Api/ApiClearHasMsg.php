<?php

/**
 * Copyright © 2014 Petr Bena (benapetr@gmail.com)
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\User\TalkPageNotificationManager;

/**
 * API module that clears the hasmsg flag for current user
 * @ingroup API
 */
class ApiClearHasMsg extends ApiBase {

	private TalkPageNotificationManager $talkPageNotificationManager;

	public function __construct(
		ApiMain $main,
		string $action,
		TalkPageNotificationManager $talkPageNotificationManager
	) {
		parent::__construct( $main, $action );
		$this->talkPageNotificationManager = $talkPageNotificationManager;
	}

	public function execute() {
		$this->talkPageNotificationManager->removeUserHasNewMessages( $this->getUser() );

		$this->getResult()->addValue( null, $this->getModuleName(), 'success' );
	}

	/** @inheritDoc */
	public function isWriteMode() {
		return true;
	}

	/** @inheritDoc */
	public function mustBePosted() {
		return true;
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=clearhasmsg'
				=> 'apihelp-clearhasmsg-example-1',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:ClearHasMsg';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiClearHasMsg::class, 'ApiClearHasMsg' );
