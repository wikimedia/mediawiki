<?php
/**
 * Tests for Special:UncategorizedCategories
 */
class SpecialUncategorizedCategoriesTest extends MediaWikiTestCase {
	/**
	 * @dataProvider provideTestGetQueryInfoData
	 * @covers SpecialUncategorizedCategories::getQueryInfo
	 */
	public function testGetQueryInfo( $msgContent, $expected ) {
		$msg = new RawMessage( $msgContent );
		$mockContext = $this->getMockBuilder( RequestContext::class )->getMock();
		$mockContext->method( 'msg' )->willReturn( $msg );
		$special = new SpecialUncategorizedCategories();
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
				0 => 'cl_from IS NULL',
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

	public function provideTestGetQueryInfoData() {
		return [
			[
				"* Stubs\n* Test\n* *\n* * test123",
				[ 1 => "page_title not in ( 'Stubs','Test','*','*_test123' )" ]
			],
			[
				"Stubs\n* Test\n* *\n* * test123",
				[ 1 => "page_title not in ( 'Test','*','*_test123' )" ]
			],
			[
				"* StubsTest\n* *\n* * test123",
				[ 1 => "page_title not in ( 'StubsTest','*','*_test123' )" ]
			],
			[ "", [] ],
			[ "\n\n\n", [] ],
			[ "\n", [] ],
			[ "Test\n*Test2", [ 1 => "page_title not in ( 'Test2' )" ] ],
			[ "Test", [] ],
			[ "*Test\nTest2", [ 1 => "page_title not in ( 'Test' )" ] ],
			[ "Test\nTest2", [] ],
		];
	}
}
