<?php

/**
 * Copyright © 2014 Petr Bena (benapetr@gmail.com)
 *
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
