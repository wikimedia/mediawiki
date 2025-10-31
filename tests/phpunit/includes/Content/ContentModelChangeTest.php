<?php

namespace MediaWiki\Tests\Content;

use MediaWiki\Content\ContentHandler;
use MediaWiki\Content\ContentModelChange;
use MediaWiki\Context\RequestContext;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\WikiPage;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Permissions\RateLimiter;
use MediaWiki\Status\Status;
use MediaWiki\Tests\Mocks\Content\DummyContentHandlerForTesting;
use MediaWiki\Tests\Unit\MockServiceDependenciesTrait;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Title\Title;
use MediaWikiIntegrationTestCase;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * TODO convert to a pure unit test
 *
 * @group Database
 * @covers \MediaWiki\Content\ContentModelChange
 *
 * @author DannyS712
 * @method ContentModelChange newServiceInstance(string $serviceClass, array $parameterOverrides)
 */
class ContentModelChangeTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;
	use MockServiceDependenciesTrait;

	protected function setUp(): void {
		parent::setUp();

		$this->getExistingTestPage( 'ExistingPage' );
		$this->mergeMwGlobalArrayValue( 'wgContentHandlers', [
			'testing' => DummyContentHandlerForTesting::class,
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
	 */
	public function testChangeNeeded() {
		$wikipage = $this->getExistingTestPage( 'ExistingPage' );
		$this->assertSame(
			'wikitext',
			$wikipage->getTitle()->getContentModel(),
			'`ExistingPage` should be wikitext'
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
		$this->assertStatusError( 'apierror-nochanges', $status );
	}

	/**
	 * Test that the content needs to be valid for the requested model
	 */
	public function testInvalidContent() {
		$invalidJSON = 'Foo\nBar\nEaster egg\nT22281';
		$wikipage = $this->getExistingTestPage( 'PageWithTextThatIsNotValidJSON' );
		$wikipage->doUserEditContent(
			ContentHandler::makeContent( $invalidJSON, $wikipage->getTitle() ),
			$this->getTestSysop()->getUser(),
			'EditSummaryForThisTest',
			EDIT_UPDATE | EDIT_SUPPRESS_RC
		);
		$this->assertSame(
			'wikitext',
			$wikipage->getTitle()->getContentModel(),
			'`PageWithTextThatIsNotValidJSON` should be wikitext at first'
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
		$this->assertStatusError( 'invalid-json-data', $status );
	}

	/**
	 * Test the EditFilterMergedContent hook can be intercepted
	 *
	 * @dataProvider provideTestEditFilterMergedContent
	 * @param string|bool $customMessage Hook message, or false
	 * @param string $expectedMessage expected fatal
	 */
	public function testEditFilterMergedContent( $customMessage, $expectedMessage ) {
		$wikipage = $this->getExistingTestPage( 'ExistingPage' );
		$this->assertSame(
			'wikitext',
			$wikipage->getTitle()->getContentModel( IDBAccessObject::READ_LATEST ),
			'`ExistingPage` should be wikitext'
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
		$this->assertStatusError( $expectedMessage, $status );
	}

	public static function provideTestEditFilterMergedContent() {
		return [
			[ 'DannyS712 objects to this change!', 'DannyS712 objects to this change!' ],
			[ false, 'hookaborted' ]
		];
	}

	/**
	 * Test the ContentModelCanBeUsedOn hook can be intercepted
	 */
	public function testContentModelCanBeUsedOn() {
		$wikipage = $this->getExistingTestPage( 'ExistingPage' );
		$wikipage->doUserEditContent(
			ContentHandler::makeContent( 'Text', $wikipage->getTitle() ),
			$this->getTestSysop()->getUser(),
			'Ensure a revision exists',
			EDIT_UPDATE | EDIT_SUPPRESS_RC
		);
		$this->assertSame(
			'wikitext',
			$wikipage->getTitle()->getContentModel( IDBAccessObject::READ_LATEST ),
			'`ExistingPage` should be wikitext'
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
		$this->assertStatusError( 'apierror-changecontentmodel-cannotbeused', $status );
	}

	/**
	 * Test that content handler must support direct editing
	 */
	public function testNoDirectEditing() {
		$title = Title::newFromText( 'Dummy:NoDirectEditing' );
		$wikipage = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );

		$dummyContent = $this->getServiceContainer()
			->getContentHandlerFactory()
			->getContentHandler( 'testing' )
			->makeEmptyContent();

		$wikipage->doUserEditContent(
			$dummyContent,
			$this->getTestSysop()->getUser(),
			'EditSummaryForThisTest',
			EDIT_NEW | EDIT_SUPPRESS_RC
		);
		$this->assertSame(
			'testing',
			$title->getContentModel( IDBAccessObject::READ_LATEST ),
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
		$this->assertStatusError(
			'apierror-changecontentmodel-nodirectediting',
			$status
		);
	}

	public function testCannotApplyTags() {
		$this->getServiceContainer()->getChangeTagsStore()->defineTag( 'edit content model tag' );

		$change = $this->newContentModelChange(
			$this->mockRegisteredAuthorityWithoutPermissions( [ 'applychangetags' ] ),
			$this->getExistingTestPage( 'ExistingPage' ),
			'text'
		);
		$status = $change->setTags( [ 'edit content model tag' ] );
		$this->assertStatusError(
			'tags-apply-no-permission',
			$status
		);
	}

	public function testCheckPermissions() {
		$wikipage = $this->getExistingTestPage( 'ExistingPage' );
		$title = $wikipage->getTitle();
		$currentContentModel = $title->getContentModel( IDBAccessObject::READ_LATEST );
		$newContentModel = 'text';

		$this->assertSame(
			'wikitext',
			$currentContentModel,
			'`ExistingPage` should be wikitext'
		);

		$performer = $this->mockRegisteredAuthority( static function (
			string $permission,
			PageIdentity $page,
			PermissionStatus $status
		) use ( $currentContentModel, $newContentModel ) {
			$title = Title::newFromPageIdentity( $page );
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

		$wpFactory = $this->createMock( WikiPageFactory::class );
		$wpFactory->method( 'newFromTitle' )->willReturn( $wikipage );
		$change = $this->newServiceInstance(
			ContentModelChange::class,
			[
				'performer' => $performer,
				'page' => $wikipage,
				'newModel' => $newContentModel,
				'wikiPageFactory' => $wpFactory,
			]
		);

		foreach ( [ 'probablyCanChange', 'authorizeChange' ] as $method ) {
			$status = $change->$method();
			$this->assertArrayEquals(
				[
					'permissionserrors', // addded by MockAuthorityTrait
					'no edit new content model',
					'no edit old content model',
					'no edit at all old content model',
					'no edit at all new content model',
				],
				array_map( static fn ( $msg ) => $msg->getKey(), $status->getMessages() )
			);
		}
	}

	public function testCheckPermissionsThrottle() {
		$user = $this->getTestUser()->getUser();

		$limiter = $this->createNoOpMock( RateLimiter::class, [ 'limit', 'isLimitable' ] );
		$limiter->method( 'isLimitable' )->willReturn( true );
		$limiter->method( 'limit' )
			->willReturnCallback( function ( $user, $action, $incr ) {
				if ( $action === 'editcontentmodel' ) {
					$this->assertSame( 1, $incr );
					return true;
				}
				return false;
			} );

		$this->setService( 'RateLimiter', $limiter );

		$change = $this->newContentModelChange(
			$user,
			$this->getNonexistingTestPage( 'NonExistingPage' ),
			'text'
		);

		$status = $change->authorizeChange();
		$this->assertFalse( $status->isOK() );
		$this->assertTrue( $status->isRateLimitExceeded() );
	}

}
