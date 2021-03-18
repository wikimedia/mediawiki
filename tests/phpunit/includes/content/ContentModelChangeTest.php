<?php

use MediaWiki\Page\PageIdentity;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Tests\Unit\MockServiceDependenciesTrait;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;

/**
 * TODO convert to a pure unit test
 *
 * @group Database
 *
 * @author DannyS712
 * @method ContentModelChange newServiceInstance(string $serviceClass, array $parameterOverrides)
 */
class ContentModelChangeTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;
	use MockServiceDependenciesTrait;

	protected function setUp() : void {
		parent::setUp();

		$this->tablesUsed = array_merge(
			$this->tablesUsed,
			[ 'change_tag', 'change_tag_def', 'logging' ]
		);

		$this->getExistingTestPage( 'ExistingPage' );
		$this->mergeMwGlobalArrayValue( 'wgContentHandlers', [
			'testing' => 'DummyContentHandlerForTesting',
		] );
	}

	private function newContentModelChange(
		Authority $performer,
		WikiPage $page,
		string $newModel
	) {
		return $this->getServiceContainer()
			->getContentModelChangeFactory()
			->newContentModelChange( $performer, $page, $newModel );
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
			$this->mockRegisteredAuthorityWithPermissions( [ 'editcontentmodel' ] ),
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
			$this->mockRegisteredAuthorityWithPermissions( [ 'editcontentmodel' ] ),
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
			static function ( $unused1, $unused2, Status $status ) use ( $customMessage ) {
				if ( $customMessage !== false ) {
					$status->fatal( $customMessage );
				}
				return false;
			}
		);

		$change = $this->newContentModelChange(
			$this->mockRegisteredAuthorityWithPermissions( [ 'editcontentmodel' ] ),
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
			static function ( $unused1, $unused2, &$ok ) {
				$ok = false;
				return false;
			}
		);

		$change = $this->newContentModelChange(
			$this->mockRegisteredAuthorityWithPermissions( [ 'editcontentmodel' ] ),
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
			$this->mockRegisteredAuthorityWithPermissions( [ 'editcontentmodel' ] ),
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
			$this->mockRegisteredAuthorityWithoutPermissions( [ 'applychangetags' ] ),
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
	 * @covers ContentModelChange::authorizeChange
	 * @covers ContentModelChange::probablyCanChange
	 */
	public function testCheckPermissions() {
		$wikipage = $this->getExistingTestPage( 'ExistingPage' );
		$title = $wikipage->getTitle();
		$currentContentModel = $title->getContentModel( Title::READ_LATEST );
		$newContentModel = 'text';

		$this->assertSame(
			'wikitext',
			$currentContentModel,
			'Sanity check: `ExistingPage` should be wikitext'
		);

		$performer = $this->mockRegisteredAuthority( function (
			string $permission,
			PageIdentity $page,
			PermissionStatus $status
		) use ( $currentContentModel, $newContentModel ) {
			$title = Title::castFromPageIdentity( $page );
			if ( $permission === 'editcontentmodel' && $title->hasContentModel( $currentContentModel ) ) {
				$status->fatal( 'no edit old content model' );
				return false;
			}
			if ( $permission === 'editcontentmodel' && $title->hasContentModel( $newContentModel ) ) {
				$status->fatal( 'no edit new content model' );
				return false;
			}
			if ( $permission === 'edit' && $title->hasContentModel( $currentContentModel ) ) {
				$status->fatal( 'no edit at all old content model' );
				return false;
			}
			if ( $permission === 'edit' && $title->hasContentModel( $newContentModel ) ) {
				$status->fatal( 'no edit at all new content model' );
				return false;
			}
			return true;
		} );

		$change = $this->newServiceInstance(
			ContentModelChange::class,
			[
				'performer' => $performer,
				'page' => $wikipage,
				'newModel' => $newContentModel
			]
		);

		foreach ( [ 'probablyCanChange', 'authorizeChange' ] as $method ) {
			$status = $change->$method();
			$this->assertArrayEquals(
				[
					[ 'no edit new content model' ],
					[ 'no edit old content model' ],
					[ 'no edit at all old content model' ],
					[ 'no edit at all new content model' ],
				],
				$status->toLegacyErrorArray()
			);
		}
	}

	/**
	 * @covers ContentModelChange::doContentModelChange
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
