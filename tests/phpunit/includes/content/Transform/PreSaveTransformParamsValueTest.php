<?php
/**
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
 * @since 1.42
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
