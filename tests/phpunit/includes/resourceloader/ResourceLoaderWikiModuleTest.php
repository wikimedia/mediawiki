<?php

class ResourceLoaderWikiModuleTest extends ResourceLoaderTestCase {

	/**
	 * @covers ResourceLoaderWikiModule::isKnownEmpty
	 * @dataProvider provideIsKnownEmpty
	 */
	public function testIsKnownEmpty( $titleInfo, $group, $expected ) {
		$module = $this->getMockBuilder( 'ResourceLoaderWikiModuleTestModule' )
			->setMethods( array( 'getTitleInfo', 'getGroup' ) )
			->getMock();
		$module->expects( $this->any() )
			->method( 'getTitleInfo' )
			->will( $this->returnValue( $titleInfo ) );
		$module->expects( $this->any() )
			->method( 'getGroup' )
			->will( $this->returnValue( $group ) );
		$context = $this->getMockBuilder( 'ResourceLoaderContext' )
			->disableOriginalConstructor()
			->getMock();
		$this->assertEquals( $expected, $module->isKnownEmpty( $context ) );
	}

	public static function provideIsKnownEmpty() {
		return array(
			// No valid pages
			array( array(), 'test1', true ),
			// 'site' module with a non-empty page
			array(
				array(
					'MediaWiki:Common.js' => array(
						'timestamp' => 123456789,
						'length' => 1234
					)
				), 'site', false,
			),
			// 'site' module with an empty page
			array(
				array(
					'MediaWiki:Monobook.js' => array(
						'timestamp' => 987654321,
						'length' => 0,
					),
				), 'site', false,
			),
			// 'user' module with a non-empty page
			array(
				array(
					'User:FooBar/common.js' => array(
						'timestamp' => 246813579,
						'length' => 25,
					),
				), 'user', false,
			),
			// 'user' module with an empty page
			array(
				array(
					'User:FooBar/monobook.js' => array(
						'timestamp' => 1357924680,
						'length' => 0,
					),
				), 'user', true,
			),
		);
	}
}
