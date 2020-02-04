<?php

/**
 * @group ResourceLoader
 * @covers VueComponentParser
 */
class VueComponentParserTest extends PHPUnit\Framework\TestCase {
	/**
	 * @dataProvider provideTestParse
	 */
	public function testParse( $html, $expectedResult, $message, $expectedException = '' ) {
		$parser = new VueComponentParser;
		if ( $expectedException !== '' ) {
			$this->expectExceptionMessage( $expectedException );
		}
		$actualResult = $parser->parse( $html );
		$this->assertEquals( $expectedResult, $actualResult, $message );
	}

	public static function provideTestParse() {
		// @codingStandardsIgnoreStart Generic.Files.LineLength
		return [
			[
				'<template><p>{{foo}}</p></template><script>bar</script><style>baz</style>',
				[
					'script' => 'bar',
					'template' => '<p>{{foo}}</p>',
					'rawTemplate' => '<p>{{foo}}</p>',
					'style' => 'baz',
					'styleLang' => 'css',
				],
				'Basic test'
			],
			[
				'<template><p>{{foo}}</p></template><style>baz</style>',
				null,
				'Missing <script> tag',
				'No <script> tag found',
			],
			[
				'<script>bar</script><style>baz</style>',
				null,
				'Missing <template> tag',
				'No <template> tag found',
			],
			[
				'<template><p>{{foo}}</p></template><script>bar</script>',
				[
					'script' => 'bar',
					'template' => '<p>{{foo}}</p>',
					'rawTemplate' => '<p>{{foo}}</p>',
					'style' => null,
					'styleLang' => null,
				],
				'Missing <style> tag'
			],
			[
				'<template><p>{{foo}}</p></template><template><p>{{quux}}</p></template><script>bar</script>',
				null,
				'Two template tags',
				'More than one <template> tag found'
			],
			[
				'<template><p>{{foo}}</p></template><script>bar</script><script>quux</script>',
				null,
				'Two script tags',
				'More than one <script> tag found'
			],
			[
				'<template><p>{{foo}}</p></template><script>bar</script><style>baz</style><style>quux</style>',
				null,
				'Two style tags',
				'More than one <style> tag found'
			],
			[
				'<template></template><script>bar</script><style>baz</style>',
				null,
				'Empty <template> tag',
				'<template> tag may not be empty',
			],
			[
				'<template><p>{{foo}}</p><p></p></template><script>bar</script>',
				null,
				'Template with two root nodes',
				'<template> tag may not have multiple child tags',
			],
			[
				'<template><!-- Explanation --><p>{{foo}}</p></template><script>bar</script>',
				[
					'script' => 'bar',
					'template' => '<p>{{foo}}</p>',
					'rawTemplate' => '<p>{{foo}}</p>',
					'style' => null,
					'styleLang' => null,
				],
				'Template with comment outside',
			],
			[
				'<template><p><!-- Explanation -->{{foo}}</p></template><script>bar</script>',
				[
					'script' => 'bar',
					'template' => '<p>{{foo}}</p>',
					'rawTemplate' => '<p><!-- Explanation -->{{foo}}</p>',
					'style' => null,
					'styleLang' => null,
				],
				'Template with comment inside',
			],
			[
				'<template>blah</template><script>bar</script>',
				null,
				'Template with text',
				'<template> tag may not contain text',
			],
			[
				"<template>\n\t<div>\t\t<div> {{foo}}\n{{bar}}  </div>\n\t</div>\n</template><script>blah</script>",
				[
					'script' => 'blah',
					'template' => "<div><div>{{foo}}\n{{bar}}</div></div>",
					'rawTemplate' => "<div>\t\t<div> {{foo}}\n{{bar}}  </div>\n\t</div>",
					'style' => null,
					'styleLang' => null,
				],
				'Whitespace in <template> tag is stripped',
			],
			[
				"<template>\n\t<div>\t\t<pre> {{foo}}\n{{bar}}  </pre>\n\t</div>\n</template><script>blah</script>",
				[
					'script' => 'blah',
					'template' => "<div><pre> {{foo}}\n{{bar}}  </pre></div>",
					'rawTemplate' => "<div>\t\t<pre> {{foo}}\n{{bar}}  </pre>\n\t</div>",
					'style' => null,
					'styleLang' => null,
				],
				'Whitespace stripping skips <pre> tags',
			],
			[
				'<template><p>{{foo}}</p></template><script>bar</script><style lang="less">baz</style>',
				[
					'script' => 'bar',
					'template' => '<p>{{foo}}</p>',
					'rawTemplate' => '<p>{{foo}}</p>',
					'style' => 'baz',
					'styleLang' => 'less',
				],
				'Style tag with lang="less"',
			],
			[
				'<template><p>{{foo}}</p></template><script>bar</script><style lang="quux">baz</style>',
				null,
				'Style tag with invalid language',
				'<style lang="quux"> is invalid, lang must be "css" or "less"',
			],
			[
				'<template><p>{{foo}}</p></template><script>bar</script><style scoped>baz</style>',
				null,
				'Scoped style tag',
				'<style> may not have the scoped attribute',
			],
			[
				'<template><p>{{foo}}</p></template><script>bar</script><style lang="less" scoped>baz</style>',
				null,
				'Scoped style tag with lang="less"',
				'<style> may not have the scoped attribute',
			],
			[
				'<template><p>{{foo}}</p></template><script>bar</script><style module>baz</style>',
				null,
				'<style module> tag',
				'<style> may not have the module attribute',
			],
			[
				'<template functional><p>{{foo}}</p></template><script>bar</script><style>baz</style>',
				null,
				'<template functional> tag',
				'<template> may not have any attributes'
			],
			[
				'<template><p>{{foo}}</p></template><script foo>bar</script><style>baz</style>',
				null,
				'<script> tag with attribute',
				'<script> may not have any attributes'
			],
			[
				'<template><p @click="onClick">{{foo}}</p></template><script>bar</script>',
				null,
				'@click attribute',
				"HTML parse errors:\nerror parsing attribute name\n on line 1"
			]
		];
		// @codingStandardsIgnoreEnd Generic.Files.LineLength
	}
}
