<?php

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiParse
 */
class ApiParseTest extends ApiTestCase {

	protected static $pageId;
	protected static $revIds = [];

	public function addDBDataOnce() {
		$user = static::getTestSysop()->getUser();
		$title = Title::newFromText( __CLASS__ );
		$page = WikiPage::factory( $title );

		$status = $page->doEditContent(
			ContentHandler::makeContent( 'Test for revdel', $title, CONTENT_MODEL_WIKITEXT ),
			__METHOD__ . ' Test for revdel', 0, false, $user
		);
		if ( !$status->isOk() ) {
			$this->fail( "Failed to create $title: " . $status->getWikiText( false, false, 'en' ) );
		}
		self::$pageId = $status->value['revision']->getPage();
		self::$revIds['revdel'] = $status->value['revision']->getId();

		$status = $page->doEditContent(
			ContentHandler::makeContent( 'Test for oldid', $title, CONTENT_MODEL_WIKITEXT ),
			__METHOD__ . ' Test for oldid', 0, false, $user
		);
		if ( !$status->isOk() ) {
			$this->fail( "Failed to edit $title: " . $status->getWikiText( false, false, 'en' ) );
		}
		self::$revIds['oldid'] = $status->value['revision']->getId();

		$status = $page->doEditContent(
			ContentHandler::makeContent( 'Test for latest', $title, CONTENT_MODEL_WIKITEXT ),
			__METHOD__ . ' Test for latest', 0, false, $user
		);
		if ( !$status->isOk() ) {
			$this->fail( "Failed to edit $title: " . $status->getWikiText( false, false, 'en' ) );
		}
		self::$revIds['latest'] = $status->value['revision']->getId();

		RevisionDeleter::createList(
			'revision', RequestContext::getMain(), $title, [ self::$revIds['revdel'] ]
		)->setVisibility( [
			'value' => [
				Revision::DELETED_TEXT => 1,
			],
			'comment' => 'Test for revdel',
		] );

		Title::clearCaches(); // Otherwise it has the wrong latest revision for some reason
	}

	public function testParseByName() {
		$res = $this->doApiRequest( [
			'action' => 'parse',
			'page' => __CLASS__,
		] );
		$this->assertContains( 'Test for latest', $res[0]['parse']['text'] );

		$res = $this->doApiRequest( [
			'action' => 'parse',
			'page' => __CLASS__,
			'disablelimitreport' => 1,
		] );
		$this->assertContains( 'Test for latest', $res[0]['parse']['text'] );
	}

	public function testParseById() {
		$res = $this->doApiRequest( [
			'action' => 'parse',
			'pageid' => self::$pageId,
		] );
		$this->assertContains( 'Test for latest', $res[0]['parse']['text'] );
	}

	public function testParseByOldId() {
		$res = $this->doApiRequest( [
			'action' => 'parse',
			'oldid' => self::$revIds['oldid'],
		] );
		$this->assertContains( 'Test for oldid', $res[0]['parse']['text'] );
		$this->assertArrayNotHasKey( 'textdeleted', $res[0]['parse'] );
		$this->assertArrayNotHasKey( 'textsuppressed', $res[0]['parse'] );
	}

	public function testParseRevDel() {
		$user = static::getTestUser()->getUser();
		$sysop = static::getTestSysop()->getUser();

		try {
			$this->doApiRequest( [
				'action' => 'parse',
				'oldid' => self::$revIds['revdel'],
			], null, null, $user );
			$this->fail( "API did not return an error as expected" );
		} catch ( ApiUsageException $ex ) {
			$this->assertTrue( ApiTestCase::apiExceptionHasCode( $ex, 'permissiondenied' ),
				"API failed with error 'permissiondenied'" );
		}

		$res = $this->doApiRequest( [
			'action' => 'parse',
			'oldid' => self::$revIds['revdel'],
		], null, null, $sysop );
		$this->assertContains( 'Test for revdel', $res[0]['parse']['text'] );
		$this->assertArrayHasKey( 'textdeleted', $res[0]['parse'] );
		$this->assertArrayNotHasKey( 'textsuppressed', $res[0]['parse'] );
	}

	public function testParseNonexistentPage() {
		try {
			$this->doApiRequest( [
				'action' => 'parse',
				'page' => 'DoesNotExist',
			] );

			$this->fail( "API did not return an error when parsing a nonexistent page" );
		} catch ( ApiUsageException $ex ) {
			$this->assertTrue( ApiTestCase::apiExceptionHasCode( $ex, 'missingtitle' ),
				"Parse request for nonexistent page must give 'missingtitle' error: "
					. var_export( self::getErrorFormatter()->arrayFromStatus( $ex->getStatusValue() ), true )
			);
		}
	}
}
