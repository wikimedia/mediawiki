<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\Api\ApiUsageException;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\RateLimiter;
use MediaWiki\Status\Status;
use MediaWiki\Tests\Mocks\Content\DummyContentHandlerForTesting;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * Tests for editing page content model via api
 *
 * @group API
 * @group Database
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiChangeContentModel
 * @author DannyS712
 */
class ApiChangeContentModelTest extends ApiTestCase {
	use MockAuthorityTrait;
	use TempUserTestTrait;

	protected function setUp(): void {
		parent::setUp();

		$this->getExistingTestPage( 'ExistingPage' );

		$this->overrideConfigValues( [
			MainConfigNames::ExtraNamespaces => [
				12312 => 'Dummy',
				12313 => 'Dummy_talk',
			],
			MainConfigNames::NamespaceContentModels => [
				12312 => 'testing',
			],
		] );
		$this->mergeMwGlobalArrayValue( 'wgContentHandlers', [
			'testing' => DummyContentHandlerForTesting::class,
		] );
	}

	public function testTitleMustExist() {
		$title = Title::makeTitle( NS_MAIN, 'ApiChangeContentModelTest::TestTitleMustExist' );

		$this->assertFalse(
			$title->exists(),
			'Check that title does not exist already'
		);

		$this->expectApiErrorCode( 'changecontentmodel-missingtitle' );

		$this->doApiRequestWithToken( [
			'action' => 'changecontentmodel',
			'title' => $title->getPrefixedText(),
			'model' => 'text'
		] );
	}

	/**
	 * Test user needs `editcontentmodel` rights
	 */
	public function testRightsNeeded() {
		$this->expectApiErrorCode( 'permissiondenied' );

		$this->doApiRequestWithToken( [
				'action' => 'changecontentmodel',
				'summary' => __METHOD__,
				'title' => 'ExistingPage',
				'model' => 'text'
			],
			null,
			$this->mockAnonAuthorityWithoutPermissions( [ 'editcontentmodel' ] ) );
	}

	/**
	 * Test that the `editcontentmodel` rate limit is enforced
	 */
	public function testRateLimitApplies() {
		$limiter = $this->createNoOpMock(
			RateLimiter::class,
			[ 'limit', 'isLimitable', ]
		);
		$limiter->method( 'limit' )
			->willReturnCallback( function ( $user, $action, $incr ) {
				if ( $action === 'editcontentmodel' ) {
					$this->assertSame( 1, $incr );
					return true;
				}
				return false;
			} );
		$limiter->method( 'isLimitable' )
			->willReturn( true );

		$this->setService( 'RateLimiter', $limiter );

		$this->setExpectedApiException( [
			'apierror-ratelimited',
			wfMessage( 'action-ratelimited' )
		] );

		$this->doApiRequestWithToken( [
			'action' => 'changecontentmodel',
			'title' => 'ExistingPage',
			'summary' => 'test',
			'model' => 'text'
		] );
	}

	/**
	 * Test that the content model needs to change
	 */
	public function testChangeNeeded() {
		$this->assertSame(
			'wikitext',
			Title::makeTitle( NS_MAIN, 'ExistingPage' )->getContentModel(),
			'`ExistingPage` should be wikitext'
		);

		$this->expectApiErrorCode( 'nochanges' );

		$this->doApiRequestWithToken( [
			'action' => 'changecontentmodel',
			'summary' => __METHOD__,
			'title' => 'ExistingPage',
			'model' => 'wikitext'
		] );
	}

	/**
	 * Test that the content needs to be valid for the requested model
	 */
	public function testInvalidContent() {
		$wikipage = $this->getExistingTestPage( 'PageWithTextThatIsNotValidJSON' );
		$invalidJSON = 'Foo\nBar\nEaster egg\nT22281';
		$wikipage->doUserEditContent(
			$wikipage->getContentHandler()->unserializeContent( $invalidJSON ),
			$this->getTestSysop()->getAuthority(),
			'EditSummaryForThisTest',
			EDIT_UPDATE | EDIT_SUPPRESS_RC
		);
		$this->assertSame(
			'wikitext',
			$wikipage->getTitle()->getContentModel(),
			'`PageWithTextThatIsNotValidJSON` should be wikitext at first'
		);

		$this->expectApiErrorCode( 'invalid-json-data' );
		$this->doApiRequestWithToken( [
				'action' => 'changecontentmodel',
				'summary' => __METHOD__,
				'title' => 'PageWithTextThatIsNotValidJSON',
				'model' => 'json'
			],
			null,
			$this->mockAnonAuthorityWithPermissions( [ 'edit', 'editcontentmodel' ] )
		);
	}

	/**
	 * Test the EditFilterMergedContent hook can be intercepted
	 *
	 * @dataProvider provideTestEditFilterMergedContent
	 * @param string|bool $customMessage Hook message, or false
	 * @param string $expectedMessage expected fatal
	 */
	public function testEditFilterMergedContent( $customMessage, $expectedMessage ) {
		$title = Title::makeTitle( NS_MAIN, 'ExistingPage' );

		$this->assertSame(
			'wikitext',
			$title->getContentModel( IDBAccessObject::READ_LATEST ),
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

		$exception = new ApiUsageException(
			null,
			Status::newFatal( $expectedMessage )
		);
		$this->expectException( ApiUsageException::class );
		$this->expectExceptionMessage( $exception->getMessage() );

		$this->doApiRequestWithToken( [
				'action' => 'changecontentmodel',
				'summary' => __METHOD__,
				'title' => 'ExistingPage',
				'model' => 'text'
			],
			null,
			$this->mockAnonAuthorityWithPermissions( [ 'edit', 'editcontentmodel' ] )
		);
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
		$title = Title::makeTitle( NS_MAIN, 'ExistingPage' );

		$this->assertSame(
			'wikitext',
			$title->getContentModel( IDBAccessObject::READ_LATEST ),
			'`ExistingPage` should be wikitext'
		);

		$this->setTemporaryHook( 'ContentModelCanBeUsedOn',
			static function ( $unused1, $unused2, &$ok ) {
				$ok = false;
				return false;
			}
		);

		$this->expectApiErrorCode( 'changecontentmodel-cannotbeused' );

		$this->doApiRequestWithToken( [
				'action' => 'changecontentmodel',
				'summary' => __METHOD__,
				'title' => 'ExistingPage',
				'model' => 'text'
			],
			null,
			$this->mockAnonAuthorityWithPermissions( [ 'edit', 'editcontentmodel' ] )
		);
	}

	/**
	 * Test that content handler must support direct editing
	 */
	public function testNoDirectEditing() {
		$title = Title::newFromText( 'Dummy:NoDirectEditing' );

		$dummyContent = $this->getServiceContainer()
			->getContentHandlerFactory()
			->getContentHandler( 'testing' )->makeEmptyContent();
		$this->editPage(
			$title,
			$dummyContent,
			'EditSummaryForThisTest',
			NS_MAIN,
			$this->getTestSysop()->getAuthority()
		);
		$this->assertSame(
			'testing',
			$title->getContentModel( IDBAccessObject::READ_LATEST ),
			'Dummy:NoDirectEditing should start with the `testing` content model'
		);

		$this->expectApiErrorCode( 'changecontentmodel-nodirectediting' );

		$this->doApiRequestWithToken( [
				'action' => 'changecontentmodel',
				'summary' => __METHOD__,
				'title' => 'Dummy:NoDirectEditing',
				'model' => 'wikitext'
			],
			null,
			$this->mockAnonAuthorityWithPermissions( [ 'edit', 'editcontentmodel' ] )
		);
	}

	public function testCannotApplyTags() {
		$this->getServiceContainer()->getChangeTagsStore()->defineTag( 'api edit content model tag' );
		$this->expectApiErrorCode( 'tags-apply-no-permission' );

		$this->doApiRequestWithToken( [
				'action' => 'changecontentmodel',
				'summary' => __METHOD__,
				'title' => 'ExistingPage',
				'model' => 'text',
				'tags' => 'api edit content model tag',
			],
			null,
			$this->mockAnonAuthorityWithoutPermissions( [ 'applychangetags' ] ) );
	}

	/**
	 * Test that it works
	 */
	public function testEverythingWorks() {
		$this->disableAutoCreateTempUser();
		$title = Title::makeTitle( NS_MAIN, 'ExistingPage' );
		$performer = $this->mockAnonAuthorityWithPermissions(
			[ 'edit', 'editcontentmodel', 'applychangetags' ]
		);
		$this->assertSame(
			'wikitext',
			$title->getContentModel( IDBAccessObject::READ_LATEST ),
			'`ExistingPage` should be wikitext'
		);

		$this->getServiceContainer()->getChangeTagsStore()->defineTag( 'api edit content model tag' );

		$data = $this->doApiRequestWithToken( [
			'action' => 'changecontentmodel',
			'summary' => __METHOD__,
			'title' => 'ExistingPage',
			'model' => 'text',
			'tags' => 'api edit content model tag',
		], null, $performer );

		$this->assertSame(
			'text',
			$title->getContentModel( IDBAccessObject::READ_LATEST ),
			'API can successfully change the content model'
		);

		$data = $data[0]['changecontentmodel'];
		$this->assertSame( 'Success', $data['result'], 'API reports successful change' );
		$firstLogId = (int)$data['logid'];
		$firstRevId = (int)$data['revid'];
		$this->assertGreaterThan( 0, $firstLogId, 'Plausible log id generated' );
		$this->assertGreaterThan( 0, $firstRevId, 'Plausible rev id generated' );

		$data = $this->doApiRequestWithToken( [
			'action' => 'changecontentmodel',
			// no 'summary', should be optional
			'title' => 'ExistingPage',
			'model' => 'wikitext',
			'tags' => 'api edit content model tag',
		], null, $performer );

		$this->assertSame(
			'wikitext',
			$title->getContentModel( IDBAccessObject::READ_LATEST ),
			'API can also change the content model back'
		);

		$data = $data[0]['changecontentmodel'];
		$this->assertSame( 'Success', $data['result'], 'API reports successful change back' );
		$this->assertGreaterThan(
			$firstLogId,
			(int)$data['logid'],
			'Second log entry should come after the first'
		);
		$this->assertGreaterThan(
			$firstRevId,
			(int)$data['revid'],
			'Second revision should come after the first'
		);

		$this->assertSame(
			'4',
			$this->getDb()->newSelectQueryBuilder()
				->select( 'ctd_count' )
				->from( 'change_tag_def' )
				->where( [ 'ctd_name' => 'api edit content model tag' ] )
				->caller( __METHOD__ )->fetchField(),
			'There should be four uses of the `api edit content model tag` tag, '
				. 'two for the two revisions and two for the two log entries'
		);
	}
}
