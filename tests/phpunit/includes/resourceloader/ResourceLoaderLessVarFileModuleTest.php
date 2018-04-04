<?php

/**
 * @group ResourceLoader
 */
class ResourceLoaderLessVarFileModuleTest extends ResourceLoaderTestCase {

	public static function providerWrapMessage() {
		return [
			[
				"Foo", "'Foo'",
			],
			[
				"Foo bananas", "'Foo bananas'",
			],
			[
				"Who's that test? Who's that test? It's Jess!",
				"'Who\'s that test? Who\'s that test? It\'s Jess!'",
			]
		];
	}
	/**
	 * @dataProvider providerWrapMessage
	 * @covers ResourceLoaderLessVarFileModule::escapeMessage
	 */
	public function testEscapeMessage( $msg, $expected ) {
		$module = new ResourceLoaderLessVarFileModule();
		$this->assertEquals( $module::wrapMessage( $msg ), $expected );
	}
}
