<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\PermissionManager;

/**
 * TODO convert to a pure unit test
 *
 * @group Database
 *
 * @author DannyS712
 */
class ContentModelChangeTest extends MediaWikiIntegrationTestCase {

	protected function setUp() : void {
		parent::setUp();

		$this->tablesUsed = array_merge(
			$this->tablesUsed,
			[ 'change_tag', 'change_tag_def', 'logging' ]
		);

		$this->getExistingTestPage( 'ExistingPage' );

		$this->mergeMwGlobalArrayValue( 'wgGroupPermissions', [
			'editcontentmodel' => [ 'editcontentmodel' => true ]
		] );

		$this->setMwGlobals( [
			'wgRevokePermissions' => [
				'noeditcontentmodel' => [ 'editcontentmodel' => true ],
				'noapplychangetags' => [ 'applychangetags' => true ],
			],
			'wgExtraNamespaces' => [
				12312 => 'Dummy',
				12313 => 'Dummy_talk',
			],
			'wgNamespaceContentModels' => [
				12312 => 'testing',
			],
		] );
		$this->mergeMwGlobalArrayValue( 'wgContentHandlers', [
			'testing' => 'DummyContentHandlerForTesting',
		] );
	}

	private function newContentModelChange(
		User $user,
		WikiPage $page,
		string $newModel
	) {
		return MediaWikiServices::getInstance()
			->getContentModelChangeFactory()
			->newContentModelChange( $user, $page, $newModel );
	}

	/**
	 * Test that the content model needs to change
	 *
	 * @covers ContentModelChange::doContentModelChange
	 */
	public function testChangeNeeded() {
		$wikipage = $this->getExistingTestPage( 'ExistingPage' );
		$this->assertSame(
			'wikitext',
			$wikipage->getTitle()->getContentModel(),
			'Sanity check: `ExistingPage` should be wikitext'
		);

		$change = $this->newContentModelChange(
			$this->getTestUser( [ 'editcontentmodel' ] )->getUser(),
			$wikipage,
			'wikitext'
		);
		$status = $change->doContentModelChange(
			RequestContext::getMain(),
			__METHOD__ . ' comment',
			false
		);
		$this->assertEquals(
			Status::newFatal( 'apierror-nochanges' ),
			$status
		);
	}

	/**
	 * Test that the content needs to be valid for the requested model
	 *
	 * @covers ContentModelChange::doContentModelChange
	 */
	public function testInvalidContent() {
		$invalidJSON = 'Foo\nBar\nEaster egg\nT22281';
		$wikipage = $this->getExistingTestPage( 'PageWithTextThatIsNotValidJSON' );
		$wikipage->doEditContent(
			ContentHandler::makeContent( $invalidJSON, $wikipage->getTitle() ),
			'EditSummaryForThisTest',
			EDIT_UPDATE | EDIT_SUPPRESS_RC,
			false,
			$this->getTestSysop()->getUser()
		);
		$this->assertSame(
			'wikitext',
			$wikipage->getTitle()->getContentModel(),
			'Sanity check: `PageWithTextThatIsNotValidJSON` should be wikitext at first'
		);

		$change = $this->newContentModelChange(
			$this->getTestUser( [ 'editcontentmodel' ] )->getUser(),
			$wikipage,
			'json'
		);
		$status = $change->doContentModelChange(
			RequestContext::getMain(),
			__METHOD__ . ' comment',
			false
		);
		$this->assertSame(
			'invalid-content-data',
			$status->getErrors()[0]['message']
		);
	}

	/**
	 * Test the EditFilterMergedContent hook can be intercepted
	 *
	 * @covers ContentModelChange::doContentModelChange
	 *
	 * @dataProvider provideTestEditFilterMergedContent
	 * @param string|bool $customMessage Hook message, or false
	 * @param string $expectedMessage expected fatal
	 */
	public function testEditFilterMergedContent( $customMessage, $expectedMessage ) {
		$wikipage = $this->getExistingTestPage( 'ExistingPage' );
		$this->assertSame(
			'wikitext',
			$wikipage->getTitle()->getContentModel( Title::READ_LATEST ),
			'Sanity check: `ExistingPage` should be wikitext'
		);

		$this->setTemporaryHook( 'EditFilterMergedContent',
			function ( $unused1, $unused2, Status $status ) use ( $customMessage ) {
				if ( $customMessage !== false ) {
					$status->fatal( $customMessage );
				}
				return false;
			}
		);

		$change = $this->newContentModelChange(
			$this->getTestUser( [ 'editcontentmodel' ] )->getUser(),
			$wikipage,
			'text'
		);
		$status = $change->doContentModelChange(
			RequestContext::getMain(),
			__METHOD__ . ' comment',
			false
		);
		$this->assertEquals(
			Status::newFatal( $expectedMessage ),
			$status
		);
	}

	public function provideTestEditFilterMergedContent() {
		return [
			[ 'DannyS712 objects to this change!', 'DannyS712 objects to this change!' ],
			[ false, 'hookaborted' ]
		];
	}

	/**
	 * Test the ContentModelCanBeUsedOn hook can be intercepted
	 *
	 * @covers ContentModelChange::doContentModelChange
	 */
	public function testContentModelCanBeUsedOn() {
		$wikipage = $this->getExistingTestPage( 'ExistingPage' );
		$wikipage->doEditContent(
			ContentHandler::makeContent( 'Text', $wikipage->getTitle() ),
			'Ensure a revision exists',
			EDIT_UPDATE | EDIT_SUPPRESS_RC,
			false,
			$this->getTestSysop()->getUser()
		);
		$this->assertSame(
			'wikitext',
			$wikipage->getTitle()->getContentModel( Title::READ_LATEST ),
			'Sanity check: `ExistingPage` should be wikitext'
		);

		$this->setTemporaryHook( 'ContentModelCanBeUsedOn',
			function ( $unused1, $unused2, &$ok ) {
				$ok = false;
				return false;
			}
		);

		$change = $this->newContentModelChange(
			$this->getTestUser( [ 'editcontentmodel' ] )->getUser(),
			$wikipage,
			'text'
		);
		$status = $change->doContentModelChange(
			RequestContext::getMain(),
			__METHOD__ . ' comment',
			false
		);
		$this->assertEquals(
			Status::newFatal(
				'apierror-changecontentmodel-cannotbeused',
				'plain text',
				Message::plaintextParam( $wikipage->getTitle()->getPrefixedText() )
			),
			$status
		);
	}

	/**
	 * Test that content handler must support direct editing
	 *
	 * @covers ContentModelChange::doContentModelChange
	 */
	public function testNoDirectEditing() {
		$title = Title::newFromText( 'Dummy:NoDirectEditing' );
		$wikipage = WikiPage::factory( $title );

		$dummyContent = ContentHandler::getForModelID( 'testing' )->makeEmptyContent();
		$wikipage->doEditContent(
			$dummyContent,
			'EditSummaryForThisTest',
			EDIT_NEW | EDIT_SUPPRESS_RC,
			false,
			$this->getTestSysop()->getUser()
		);
		$this->assertSame(
			'testing',
			$title->getContentModel( Title::READ_LATEST ),
			'Dummy:NoDirectEditing should start with the `testing` content model'
		);

		$change = $this->newContentModelChange(
			$this->getTestUser( [ 'editcontentmodel' ] )->getUser(),
			$wikipage,
			'text'
		);
		$status = $change->doContentModelChange(
			RequestContext::getMain(),
			__METHOD__ . ' comment',
			false
		);
		$this->assertEquals(
			Status::newFatal(
				'apierror-changecontentmodel-nodirectediting',
				ContentHandler::getLocalizedName( 'testing' )
			),
			$status
		);
	}

	/**
	 * @covers ContentModelChange::setTags
	 */
	public function testCannotApplyTags() {
		ChangeTags::defineTag( 'edit content model tag' );

		$change = $this->newContentModelChange(
			$this->getTestUser( [ 'noapplychangetags' ] )->getUser(),
			$this->getExistingTestPage( 'ExistingPage' ),
			'text'
		);
		$status = $change->setTags( [ 'edit content model tag' ] );
		$this->assertEquals(
			Status::newFatal( 'tags-apply-no-permission' ),
			$status
		);
	}

	/**
	 * @covers ContentModelChange::checkPermissions
	 */
	public function testCheckPermissions() {
		$user = $this->getTestUser()->getUser();
		$wikipage = $this->getExistingTestPage( 'ExistingPage' );
		$title = $wikipage->getTitle();
		$currentContentModel = $title->getContentModel( Title::READ_LATEST );

		$this->assertSame(
			'wikitext',
			$currentContentModel,
			'Sanity check: `ExistingPage` should be wikitext'
		);

		$newContentModel = 'text';
		$mock = $this->getMockBuilder( PermissionManager::class )
			->disableOriginalConstructor()
			->setMethods( [ 'getPermissionErrors' ] )
			->getMock();
		$mock->expects( $this->exactly( 4 ) )
			->method( 'getPermissionErrors' )
			->withConsecutive(
				[
					$this->equalTo( 'editcontentmodel' ),
					$this->callback( function ( User $userInMock ) use ( $user ) {
						return $user->equals( $userInMock );
					} ),
					$this->callback( function ( Title $titleInMock ) use ( $title, $currentContentModel ) {
						return $title->equals( $titleInMock ) &&
							$titleInMock->hasContentModel( $currentContentModel );
					} )
				],
				[
					$this->equalTo( 'edit' ),
					$this->callback( function ( User $userInMock ) use ( $user ) {
						return $user->equals( $userInMock );
					} ),
					$this->callback( function ( Title $titleInMock ) use ( $title, $currentContentModel ) {
						return $title->equals( $titleInMock ) &&
							$titleInMock->hasContentModel( $currentContentModel );
					} )
				],
				[
					$this->equalTo( 'editcontentmodel' ),
					$this->callback( function ( User $userInMock ) use ( $user ) {
						return $user->equals( $userInMock );
					} ),
					$this->callback( function ( Title $titleInMock ) use ( $title, $newContentModel ) {
						return $title->equals( $titleInMock ) &&
							$titleInMock->hasContentModel( $newContentModel );
					} )
				],
				[
					$this->equalTo( 'edit' ),
					$this->callback( function ( User $userInMock ) use ( $user ) {
						return $user->equals( $userInMock );
					} ),
					$this->callback( function ( Title $titleInMock ) use ( $title, $newContentModel ) {
						return $title->equals( $titleInMock ) &&
							$titleInMock->hasContentModel( $newContentModel );
					} )
				]
			)
			->will(
				$this->onConsecutiveCalls(
					[ [ 'no edit content model' ] ],
					[ [ 'no editing at all' ] ],
					[ [ 'no edit content model' ] ],
					[ [ 'no editing', 'with new content model' ] ]
				)
			);

		$services = MediaWikiServices::getInstance();
		$change = new ContentModelChange(
			$services->getContentHandlerFactory(),
			$services->getHookContainer(),
			$mock,
			$services->getRevisionLookup(),
			$user,
			$wikipage,
			$newContentModel
		);

		$errors = $change->checkPermissions();
		$this->assertArrayEquals(
			[
				[ 'no edit content model' ],
				[ 'no editing at all' ],
				[ 'no editing', 'with new content model' ]
			],
			$errors
		);
	}

	/**
	 * @covers ContentModelChange::checkPermissions
	 */
	public function testCheckPermissionsThrottle() {
		$mock = $this->getMockBuilder( User::class )
			->setMethods( [ 'pingLimiter' ] )
			->getMock();
		$mock->expects( $this->once() )
			->method( 'pingLimiter' )
			->with( $this->equalTo( 'editcontentmodel' ) )
			->willReturn( true );

		$change = $this->newContentModelChange(
			$mock,
			$this->getNonexistingTestPage( 'NonExistingPage' ),
			'text'
		);

		$context = new RequestContext();
		$comment = 'comment';
		$bot = true;

		$this->expectException( ThrottledError::class );

		$change->doContentModelChange( $context, $comment, $bot );
	}

}
