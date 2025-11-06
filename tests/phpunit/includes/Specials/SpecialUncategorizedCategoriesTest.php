<?php
namespace MediaWiki\Tests\Specials;

use MediaWiki\Context\RequestContext;
use MediaWiki\Language\RawMessage;
use MediaWiki\Specials\SpecialUncategorizedCategories;
use MediaWikiIntegrationTestCase;
use Wikimedia\Rdbms\Expression;

/**
 * Tests for Special:UncategorizedCategories
 *
 * @group Database
 */
class SpecialUncategorizedCategoriesTest extends MediaWikiIntegrationTestCase {
	/**
	 * @dataProvider provideTestGetQueryInfoData
	 * @covers \MediaWiki\Specials\SpecialUncategorizedCategories::getQueryInfo
	 */
	public function testGetQueryInfo( $msgContent, $expected ) {
		$msg = new RawMessage( $msgContent );
		$mockContext = $this->createMock( RequestContext::class );
		$mockContext->method( 'msg' )->willReturn( $msg );
		$services = $this->getServiceContainer();
		$special = new SpecialUncategorizedCategories(
			$services->getNamespaceInfo(),
			$services->getConnectionProvider(),
			$services->getLinkBatchFactory(),
			$services->getLanguageConverterFactory()
		);
		$special->setContext( $mockContext );
		$this->assertEquals( [
			'tables' => [
				0 => 'page',
				1 => 'categorylinks',
			],
			'fields' => [
				'namespace' => 'page_namespace',
				'title' => 'page_title',
			],
			'conds' => [
				'cl_from' => null,
				'page_namespace' => 14,
				'page_is_redirect' => 0,
			] + $expected,
			'join_conds' => [
				'categorylinks' => [
					0 => 'LEFT JOIN',
					1 => 'cl_from = page_id',
				],
			],
		], $special->getQueryInfo() );
	}

	public static function provideTestGetQueryInfoData() {
		return [
			[
				"* Stubs\n* Test\n* *\n* * test123",
				[ 0 => new Expression( 'page_title', '!=', [ 'Stubs', 'Test', '*', '*_test123' ] ) ]
			],
			[
				"Stubs\n* Test\n* *\n* * test123",
				[ 0 => new Expression( 'page_title', '!=', [ 'Test', '*', '*_test123' ] ) ],
			],
			[
				"* StubsTest\n* *\n* * test123",
				[ 0 => new Expression( 'page_title', '!=', [ 'StubsTest', '*', '*_test123' ] ) ],
			],
			[ "", [] ],
			[ "\n\n\n", [] ],
			[ "\n", [] ],
			[ "Test\n*Test2", [ 0 => new Expression( 'page_title', '!=', [ 'Test2' ] ) ] ],
			[ "Test", [] ],
			[ "*Test\nTest2", [ 0 => new Expression( 'page_title', '!=', [ 'Test' ] ) ] ],
			[ "Test\nTest2", [] ],
		];
	}
}
