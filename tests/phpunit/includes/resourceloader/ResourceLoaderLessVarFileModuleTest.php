<?php

/**
 * @group ResourceLoader
 */
class ResourceLoaderLessVarFileModuleTest extends ResourceLoaderTestCase {

	public static function providerWrapAndEscapeMessage() {
		return [
			[
				"Foo", '"Foo"',
			],
			[
				"Foo bananas", '"Foo bananas"',
			],
			[
				"Who's that test? Who's that test? It's Jess!",
				'"Who\'s that test? Who\'s that test? It\'s Jess!"',
			],
			[
				'Hello "he" said',
				'"Hello \"he\" said"',
			],
			[
				'boo";-o-link:javascript:alert(1);color:red;content:"',
				'"boo\";-o-link:javascript:alert(1);color:red;content:\""',
			]
		];
	}
	/**
	 * @dataProvider providerWrapAndEscapeMessage
	 * @covers ResourceLoaderLessVarFileModule::wrapAndEscapeMessage
	 */
	public function testEscapeMessage( $msg, $expected ) {
		$module = new ResourceLoaderLessVarFileModule();
		$this->assertEquals( $module::wrapAndEscapeMessage( $msg ), $expected );
	}
}
