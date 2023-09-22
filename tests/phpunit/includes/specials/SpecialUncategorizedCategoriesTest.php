<?php

use MediaWiki\Language\RawMessage;
use MediaWiki\Specials\SpecialUncategorizedCategories;

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
			$services->getDBLoadBalancerFactory(),
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
				[ 0 => "page_title not in ( 'Stubs','Test','*','*_test123' )" ]
			],
			[
				"Stubs\n* Test\n* *\n* * test123",
				[ 0 => "page_title not in ( 'Test','*','*_test123' )" ]
			],
			[
				"* StubsTest\n* *\n* * test123",
				[ 0 => "page_title not in ( 'StubsTest','*','*_test123' )" ]
			],
			[ "", [] ],
			[ "\n\n\n", [] ],
			[ "\n", [] ],
			[ "Test\n*Test2", [ 0 => "page_title not in ( 'Test2' )" ] ],
			[ "Test", [] ],
			[ "*Test\nTest2", [ 0 => "page_title not in ( 'Test' )" ] ],
			[ "Test\nTest2", [] ],
		];
	}
}
