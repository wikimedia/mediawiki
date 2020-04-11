<?php

/**
 * Tests for editing page content model via api
 *
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiChangeContentModel
 * @author DannyS712
 */
class ApiChangeContentModelTest extends ApiTestCase {

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

	/**
	 * Test title must exist
	 */
	public function testTitleMustExist() {
		$name = __METHOD__;

		$this->assertFalse(
			Title::newFromText( $name )->exists(),
			'Sanity check that title does not exist already'
		);

		$this->setExpectedApiException( 'apierror-changecontentmodel-missingtitle' );

		$this->doApiRequestWithToken( [
			'action' => 'changecontentmodel',
			'title' => $name,
			'model' => 'text'
		] );
	}

	/**
	 * Test user needs `editcontentmodel` rights
	 */
	public function testRightsNeeded() {
		$user = $this->getTestUser( [ 'noeditcontentmodel' ] )->getUser();

		$this->setExpectedApiException( [
			'apierror-permissiondenied',
			wfMessage( 'action-editcontentmodel' )
		] );

		$this->doApiRequestWithToken( [
			'action' => 'changecontentmodel',
			'title' => 'ExistingPage',
			'model' => 'text'
		], null, $user );
	}

	/**
	 * Test that the content model needs to change
	 */
	public function testChangeNeeded() {
		$this->assertSame(
			'wikitext',
			Title::newFromText( 'ExistingPage' )->getContentModel(),
			'Sanity check: `ExistingPage` should be wikitext'
		);

		$this->setExpectedApiException( 'apierror-nochanges' );

		$this->doApiRequestWithToken( [
			'action' => 'changecontentmodel',
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

		$this->setExpectedApiException( 'invalid-content-data' );
		$user = $this->getTestUser( [ 'editcontentmodel' ] )->getUser();
		$this->doApiRequestWithToken( [
			'action' => 'changecontentmodel',
			'title' => 'PageWithTextThatIsNotValidJSON',
			'model' => 'json'
		], null, $user );
	}

	/**
	 * Test the EditFilterMergedContent hook can be intercepted
	 *
	 * @dataProvider provideTestEditFilterMergedContent
	 * @param string|bool $customMessage Hook message, or false
	 * @param string $expectedMessage expected fatal
	 */
	public function testEditFilterMergedContent( $customMessage, $expectedMessage ) {
		$title = Title::newFromText( 'ExistingPage' );
		$user = $this->getTestUser( [ 'editcontentmodel' ] )->getUser();

		$this->assertSame(
			'wikitext',
			$title->getContentModel( Title::READ_LATEST ),
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

		$exception = new ApiUsageException(
			null,
			Status::newFatal( $expectedMessage )
		);
		$this->expectException( ApiUsageException::class );
		$this->expectExceptionMessage( $exception->getMessage() );

		$this->doApiRequestWithToken( [
			'action' => 'changecontentmodel',
			'title' => 'ExistingPage',
			'model' => 'text'
		], null, $user );
	}

	public function provideTestEditFilterMergedContent() {
		return [
			[ 'DannyS712 objects to this change!', 'DannyS712 objects to this change!' ],
			[ false, 'hookaborted' ]
		];
	}

	/**
	 * Test the ContentModelCanBeUsedOn hook can be intercepted
	 */
	public function testContentModelCanBeUsedOn() {
		$title = Title::newFromText( 'ExistingPage' );
		$user = $this->getTestUser( [ 'editcontentmodel' ] )->getUser();

		$this->assertSame(
			'wikitext',
			$title->getContentModel( Title::READ_LATEST ),
			'Sanity check: `ExistingPage` should be wikitext'
		);

		$this->setTemporaryHook( 'ContentModelCanBeUsedOn',
			function ( $unused1, $unused2, &$ok ) {
				$ok = false;
				return false;
			}
		);

		$this->setExpectedApiException( [
			'apierror-changecontentmodel-cannotbeused',
			wfMessage( 'content-model-text' ),
			'ExistingPage'
		] );

		$this->doApiRequestWithToken( [
			'action' => 'changecontentmodel',
			'title' => 'ExistingPage',
			'model' => 'text'
		], null, $user );
	}

	/**
	 * Test that content handler must support direct editing
	 */
	public function testNoDirectEditing() {
		$title = Title::newFromText( 'Dummy:NoDirectEditing' );

		$dummyContent = ContentHandler::getForModelID( 'testing' )->makeEmptyContent();
		WikiPage::factory( $title )->doEditContent(
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

		$this->setExpectedApiException( [
			'apierror-changecontentmodel-nodirectediting',
			ContentHandler::getLocalizedName( 'testing' )
		] );

		$user = $this->getTestUser( [ 'editcontentmodel' ] )->getUser();
		$this->doApiRequestWithToken( [
			'action' => 'changecontentmodel',
			'title' => 'Dummy:NoDirectEditing',
			'model' => 'wikitext'
		], null, $user );
	}

	public function testCannotApplyTags() {
		$user = $this->getTestUser( [ 'noapplychangetags' ] )->getUser();
		ChangeTags::defineTag( 'api edit content model tag' );
		$this->setExpectedApiException( 'tags-apply-no-permission' );

		$this->doApiRequestWithToken( [
			'action' => 'changecontentmodel',
			'title' => 'ExistingPage',
			'model' => 'text',
			'tags' => 'api edit content model tag',
		], null, $user );
	}

	/**
	 * Test that it works
	 */
	public function testEverythingWorks() {
		$title = Title::newFromText( 'ExistingPage' );
		$this->assertSame(
			'wikitext',
			$title->getContentModel( Title::READ_LATEST ),
			'Sanity check: `ExistingPage` should be wikitext'
		);

		$user = $this->getTestUser( [ 'editcontentmodel' ] )->getUser();
		ChangeTags::defineTag( 'api edit content model tag' );

		$data = $this->doApiRequestWithToken( [
			'action' => 'changecontentmodel',
			'title' => 'ExistingPage',
			'model' => 'text',
			'tags' => 'api edit content model tag',
		], null, $user );

		$this->assertSame(
			'text',
			$title->getContentModel( Title::READ_LATEST ),
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
			'title' => 'ExistingPage',
			'model' => 'wikitext',
			'tags' => 'api edit content model tag',
		], null, $user );

		$this->assertSame(
			'wikitext',
			$title->getContentModel( Title::READ_LATEST ),
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

		$dbw = wfGetDB( DB_MASTER );
		$this->assertSame(
			'4',
			$dbw->selectField(
				[ 'change_tag_def' ],
				'ctd_count',
				[ 'ctd_name' => 'api edit content model tag' ],
				__METHOD__
			),
			'There should be four uses of the `api edit content model tag` tag, '
				. 'two for the two revisions and two for the two log entries'
		);
	}
}
