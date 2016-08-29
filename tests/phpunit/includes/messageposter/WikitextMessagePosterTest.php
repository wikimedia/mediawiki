<?php

/**
 * WikitextMessagePosterTest
 *
 * @copyright GPL http://www.gnu.org/copyleft/gpl.html
 * @author Matthew Flaschen
 * @ingroup MessagePoster
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
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

use MediaWiki\MessagePoster\IMessagePoster;
use MediaWiki\MessagePoster\WikitextMessagePoster;

/**
 * Tests for WikitextMessagePoster
 *
 * @group Database
 *
 * @covers MediaWiki\MessagePoster\WikitextMessagePoster
 */
class WikitextMessagePosterTest extends MediaWikiTestCase {
	protected $talkPageTitle;

	protected $messagePoster;

	protected $sysop;

	protected function setUp() {
		parent::setUp();

		$this->tablesUsed = array_merge(
			$this->tablesUsed,
			[ 'ipblocks', 'logging', 'page', 'protected_titles', 'revision', 'text', 'user_groups' ]
		);

		Title::clearCaches();

		$this->setMwGlobals( 'wgNamespaceContentModels', [] );
		$this->talkPageTitle = Title::newFromText( 'Talk:WikitextMessagePoster' );

		$this->messagePoster = new WikitextMessagePoster();

		$this->sysop = $this->getTestSysop()->getUser();
	}

	public function testSuccessfulPost() {
		$normalUser = $this->getTestUser()->getUser();
		$this->messagePoster->postTopic( $this->talkPageTitle, $normalUser, 'Subject', 'Body text' );

		$revision = Revision::newFromTitle( $this->talkPageTitle, 0, Revision::READ_LATEST );
		$actualContent = $revision->getContent( Revision::RAW );
		$actualText = $actualContent->getNativeData();

		$this->assertEquals(
			$normalUser->getId(),
			$revision->getUser( Revision::RAW ),
			'Revision is attributed to correct user'
		);

		$rc = $revision->getRecentChange();
		$this->assertSame(
			'0',
			$rc->getAttribute( 'rc_bot' ),
			'Non-bot user with bot parameter false should not set bot flag'
		);

		$this->assertStringStartsWith(
			"== Subject ==\n\nBody text",
			$actualText,
			'Page is created with correct section heading and body text'
		);
	}

	/**
	 * @expectedException MWException
	 * @expectedExceptionMessage You have been blocked from editing
	 */
	public function testBlockedUserPost() {
		$blockedUser = $this->getMutableTestUser()->getUser();
		$block = new Block();
		$block->setTarget( $blockedUser );
		$block->setBlocker( $this->sysop );
		$block->mReason = 'Test';
		$block->mExpiry = 'infinite';
		$block->prevents( 'editownusertalk', false );
		$block->insert();

		$this->messagePoster->postTopic( $this->talkPageTitle, $blockedUser, 'Subject', 'Body text' );
	}

	/**
	 * @expectedException MWException
	 * @expectedExceptionMessage The "editprotected" right is required to edit this page
	 */
	public function testProtectedPagePost() {
		$normalUser = $this->getTestUser()->getUser();
		$talkPage = WikiPage::factory( $this->talkPageTitle );
		$cascade = true;
		$talkPage->doUpdateRestrictions(
			[ 'create' => 'sysop' ],
			[ 'infinity' ],
			$cascade,
			'Test',
			$this->sysop
		);

		$this->messagePoster->postTopic( $this->talkPageTitle, $normalUser, 'Subject', 'Body text' );
	}

	public function testBotPost() {
		$botUser = $this->getMutableTestUser()->getUser();
		$botUser->addGroup( 'bot' );

		$this->messagePoster->postTopic(
			$this->talkPageTitle,
			$botUser,
			'Posted with bot flag true',
			'Body text',
			IMessagePoster::BOT_EDIT
		);
		$firstRevision = Revision::newFromTitle( $this->talkPageTitle, 0, Revision::READ_LATEST );
		$firstRc = $firstRevision->getRecentChange();
		$this->assertSame(
			'1',
			$firstRc->getAttribute( 'rc_bot' ),
			'Bot user with bot parameter true should set bot flag'
		);

		$this->messagePoster->postTopic(
			$this->talkPageTitle,
			$botUser,
			'Posted with bot flag false',
			'Body text',
			0
		);
		$secondRevision = Revision::newFromTitle( $this->talkPageTitle, 0, Revision::READ_LATEST );
		$secondRc = $secondRevision->getRecentChange();
		$this->assertSame(
			'0',
			$secondRc->getAttribute( 'rc_bot' ),
			'Bot user with bot parameter false should not set bot flag'
		);
	}

	// @codingStandardsIgnoreStart Ignore Generic.Files.LineLength.TooLong
	const HOOK_FAILURE_MESSAGE =
		'The modification you tried to make was aborted by an extension hook: {"errorDetails":"It didn\'t quite work","result":"Failure"}';
	// @codingStandardsIgnoreEnd

	/**
	 * @expectedException MWException
	 * @expectedExceptionMessage WikitextMessagePosterTest::HOOK_FAILURE_MESSAGE
	 */
	public function testHookFailure() {
		$this->setTemporaryHook(
			'EditFilterMergedContent',
			function ( $context, $content, $status, $summary, $user, $minoredit ) {
				$status->apiHookResult = [
					'errorDetails' => 'It didn\'t quite work'
				];

				return false;
			}
		);

		$this->messagePoster->postTopic(
			$this->talkPageTitle,
			$this->sysop,
			'Test hook abort',
			'Body text'
		);

	}
}
