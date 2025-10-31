<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Content\Transform;

use MediaWiki\Content\Transform\PreSaveTransformParamsValue;
use MediaWiki\Page\PageReference;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Content\Transform\PreSaveTransformParamsValue
 * @group Database
 */
class PreSaveTransformParamsValueTest extends MediaWikiIntegrationTestCase {

	protected PageReference $page;
	protected UserIdentity $user;
	protected ParserOptions $parserOptions;

	protected function setUp(): void {
		parent::setUp();
		$this->page = $this->createMock( PageReference::class );
		$this->user = $this->createMock( UserIdentity::class );
		$this->parserOptions = $this->createMock( ParserOptions::class );
	}

	public function testConstruct() {
		$params = new PreSaveTransformParamsValue( $this->page, $this->user, $this->parserOptions );
		$this->assertSame( $this->page, $params->getPage() );
		$this->assertSame( $this->user, $params->getUser() );
		$this->assertSame( $this->parserOptions, $params->getParserOptions() );
	}

	public function testGetPage() {
		$title = Title::newFromText( 'TestPage' );
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$params = new PreSaveTransformParamsValue( $page, $this->user, $this->parserOptions );
		$this->assertSame( $page, $params->getPage() );
	}

	public function testGetUser() {
		$user = $this->getTestUser();
		$params = new PreSaveTransformParamsValue( $this->page, $user->getUserIdentity(), $this->parserOptions );
		$expectedUser = $user->getUserIdentity();
		$actualUser = $params->getUser();
		$this->assertEquals( $expectedUser->getId(), $actualUser->getId(), 'User IDs do not match' );
		$this->assertEquals( $expectedUser->getName(), $actualUser->getName(), 'User names do not match' );
		$this->assertEquals( $expectedUser->getWikiId(), $actualUser->getWikiId(), 'Wiki IDs do not match' );
	}

	/**
	 * @dataProvider provideParserOptions
	 */
	public function testGetParserOptions( $options ) {
		$title = Title::newFromText( 'TestPage' );
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$params = new PreSaveTransformParamsValue( $page, $this->user, $options );
		$this->assertSame( $options, $params->getParserOptions() );
	}

	public static function provideParserOptions(): array {
		$user = new User();
		$options = ParserOptions::newFromUser( $user );
		return [
			[ $options ],
			[ ParserOptions::newFromUser( $user ) ],
		];
	}
}
