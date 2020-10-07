<?php

/**
 * @group ResourceLoader
 * @covers VueComponentParser
 */
class VueComponentParserTest extends PHPUnit\Framework\TestCase {
	/**
	 * @dataProvider provideTestParse
	 */
	public function testParse( $html, $minify, $expectedResult, $message, $expectedException = '' ) {
		$parser = new VueComponentParser;
		if ( $expectedException !== '' ) {
			$this->expectExceptionMessage( $expectedException );
		}
		$actualResult = $parser->parse( $html, [ 'minifyTemplate' => $minify ] );
		$this->assertEquals( $expectedResult, $actualResult, $message );
	}

	public static function provideTestParse() {
		// @codingStandardsIgnoreStart Generic.Files.LineLength
		return [
			[
				'<template><p>{{foo}}</p></template><script>bar</script><style>baz</style>',
				false,
				[
					'script' => 'bar',
					'template' => '<p>{{foo}}</p>',
					'style' => 'baz',
					'styleLang' => 'css',
				],
				'Basic test'
			],
			[
				'<template><p>{{foo}}</p></template><style>baz</style>',
				false,
				null,
				'Missing <script> tag',
				'No <script> tag found',
			],
			[
				'<script>bar</script><style>baz</style>',
				false,
				null,
				'Missing <template> tag',
				'No <template> tag found',
			],
			[
				'<template><p>{{foo}}</p></template><script>bar</script>',
				false,
				[
					'script' => 'bar',
					'template' => '<p>{{foo}}</p>',
					'style' => null,
					'styleLang' => null,
				],
				'Missing <style> tag'
			],
			[
				'<template><p>{{foo}}</p></template><template><p>{{quux}}</p></template><script>bar</script>',
				false,
				null,
				'Two template tags',
				'More than one <template> tag found'
			],
			[
				'<template><p>{{foo}}</p></template><script>bar</script><script>quux</script>',
				false,
				null,
				'Two script tags',
				'More than one <script> tag found'
			],
			[
				'<template><p>{{foo}}</p></template><script>bar</script><style>baz</style><style>quux</style>',
				false,
				null,
				'Two style tags',
				'More than one <style> tag found'
			],
			[
				'<template></template><script>bar</script><style>baz</style>',
				false,
				null,
				'Empty <template> tag',
				'<template> tag may not be empty',
			],
			[
				'<template><p>{{foo}}</p><p></p></template><script>bar</script>',
				false,
				null,
				'Template with two root nodes',
				'<template> tag may not have multiple child tags',
			],
			[
				'<template><!-- Explanation --><p>{{foo}}</p></template><script>bar</script>',
				false,
				[
					'script' => 'bar',
					'template' => '<!-- Explanation --><p>{{foo}}</p>',
					'style' => null,
					'styleLang' => null,
				],
				'Template with comment outside: comment preserved when not minifiying',
			],
			[
				'<template><!-- Explanation --><p>{{foo}}</p></template><script>bar</script>',
				true,
				[
					'script' => 'bar',
					'template' => '<p>{{foo}}</p>',
					'style' => null,
					'styleLang' => null,
				],
				'Template with comment outside: comment removed when minifiying',
			],
			[
				'<template><p><!-- Explanation -->{{foo}}</p></template><script>bar</script>',
				false,
				[
					'script' => 'bar',
					'template' => '<p><!-- Explanation -->{{foo}}</p>',
					'style' => null,
					'styleLang' => null,
				],
				'Template with comment inside: comment preserved when not minifying',
			],
			[
				'<template><p><!-- Explanation -->{{foo}}</p></template><script>bar</script>',
				true,
				[
					'script' => 'bar',
					'template' => '<p>{{foo}}</p>',
					'style' => null,
					'styleLang' => null,
				],
				'Template with comment inside: comment removed when minifying',
			],
			[
				'<template>blah</template><script>bar</script>',
				false,
				null,
				'Template with text',
				'<template> tag may not contain text',
			],
			[
				"<template>\n\t<div>\t\t<div> {{foo}}\n{{bar}}  </div>\n\t</div>\n</template><script>blah</script>",
				false,
				[
					'script' => 'blah',
					'template' => "<div>\t\t<div> {{foo}}\n{{bar}}  </div>\n\t</div>",
					'style' => null,
					'styleLang' => null,
				],
				'Whitespace in <template> tag is preserved when not minifying',
			],
			[
				"<template>\n\t<div>\t\t<div> {{foo}}\n{{bar}}  </div>\n\t</div>\n</template><script>blah</script>",
				true,
				[
					'script' => 'blah',
					'template' => "<div><div>{{foo}} {{bar}}</div></div>",
					'style' => null,
					'styleLang' => null,
				],
				'Whitespace in <template> tag is stripped and collapsed when minifying',
			],
			[
				"<template>\n\t<div>\t\t<pre> {{foo}}\n{{bar}}  </pre>\n\t</div>\n</template><script>blah</script>",
				true,
				[
					'script' => 'blah',
					'template' => "<div><pre> {{foo}}\n{{bar}}  </pre></div>",
					'style' => null,
					'styleLang' => null,
				],
				'Whitespace stripping and collapsing skips <pre> tags',
			],
			[
				"<template>\n\t<div>\t\t<pre>\n {{foo}}\n{{bar}}  </pre>\n\t</div>\n</template><script>blah</script>",
				false,
				[
					'script' => 'blah',
					'template' => "<div>\t\t<pre> {{foo}}\n{{bar}}  </pre>\n\t</div>",
					'style' => null,
					'styleLang' => null,
				],
				'Single leading newline in <pre> is removed when not minifying',
			],
			[
				"<template>\n\t<div>\t\t<pre>\n {{foo}}\n{{bar}}  </pre>\n\t</div>\n</template><script>blah</script>",
				true,
				[
					'script' => 'blah',
					'template' => "<div><pre> {{foo}}\n{{bar}}  </pre></div>",
					'style' => null,
					'styleLang' => null,
				],
				'Single leading newline in <pre> is removed when minifying',
			],
			[
				"<template>\n\t<div>\t\t<pre>\n\n {{foo}}\n{{bar}}  </pre>\n\t</div>\n</template><script>blah</script>",
				false,
				[
					'script' => 'blah',
					'template' => "<div>\t\t<pre>\n\n {{foo}}\n{{bar}}  </pre>\n\t</div>",
					'style' => null,
					'styleLang' => null,
				],
				'Double leading newline in <pre> is preserved when not minifying',
			],
			[
				"<template>\n\t<div>\t\t<pre>\n\n {{foo}}\n{{bar}}  </pre>\n\t</div>\n</template><script>blah</script>",
				true,
				[
					'script' => 'blah',
					'template' => "<div><pre>\n\n {{foo}}\n{{bar}}  </pre></div>",
					'style' => null,
					'styleLang' => null,
				],
				'Double leading newline in <pre> is preserved when minifying',
			],
			[
				"<template><a>!!!</a></template><script>var x = '<a>!!!</a>';</script>",
				false,
				[
					'script' => "var x = '<a>!!!</a>';",
					'template' => "<a>!!!</a>",
					'style' => null,
					'styleLang' => null,
				],
				'Script tag with HTML string'
			],
			[
				'<template><p>{{foo}}</p></template><script>bar</script><style lang="less">baz</style>',
				false,
				[
					'script' => 'bar',
					'template' => '<p>{{foo}}</p>',
					'style' => 'baz',
					'styleLang' => 'less',
				],
				'Style tag with lang="less"',
			],
			[
				'<template><p>{{foo}}</p></template><script>bar</script><style lang="quux">baz</style>',
				false,
				null,
				'Style tag with invalid language',
				'<style lang="quux"> is invalid, lang must be "css" or "less"',
			],
			[
				'<template><p>{{foo}}</p></template><script>bar</script><style scoped>baz</style>',
				false,
				null,
				'Scoped style tag',
				'<style> may not have the scoped attribute',
			],
			[
				'<template><p>{{foo}}</p></template><script>bar</script><style lang="less" scoped>baz</style>',
				false,
				null,
				'Scoped style tag with lang="less"',
				'<style> may not have the scoped attribute',
			],
			[
				'<template><p>{{foo}}</p></template><script>bar</script><style module>baz</style>',
				false,
				null,
				'<style module> tag',
				'<style> may not have the module attribute',
			],
			[
				'<template functional><p>{{foo}}</p></template><script>bar</script><style>baz</style>',
				false,
				null,
				'<template functional> tag',
				'<template> may not have any attributes'
			],
			[
				'<template><p>{{foo}}</p></template><script foo>bar</script><style>baz</style>',
				false,
				null,
				'<script> tag with attribute',
				'<script> may not have any attributes'
			],
			[
				'<template><p :class="classObj">{{foo}}</p></template><script>bar</script>',
				false,
				[
					'script' => 'bar',
					'template' => '<p :class="classObj">{{foo}}</p>',
					'style' => null,
					'styleLang' => null,
				],
				'Attribute with : in the name'
			],
			[
				'<template><a @click="onClick">{{foo}}</a></template><script>bar</script>',
				false,
				[
					'script' => 'bar',
					'template' => '<a @click="onClick">{{foo}}</a>',
					'style' => null,
					'styleLang' => null,
				],
				'Attribute with @ in the name'
			],
			[
				'<template><div><template #header></template><template #footer="foo"></template></div></template><script>bar</script>',
				false,
				[
					'script' => 'bar',
					'template' => '<div><template #header=""></template><template #footer="foo"></template></div>',
					'style' => null,
					'styleLang' => null,
				],
				'Attribute with # in the name'
			],
			[
				'<template><p><mw-button /><mw-blah>foo</mw-blah></p></template><script>bar</script>',
				false,
				[
					'script' => 'bar',
					'template' => '<p><mw-button><mw-blah>foo</mw-blah></mw-button></p>',
					'style' => null,
					'styleLang' => null,
				],
				'Template with self-closing tag (broken)'
			],
		];
		// @codingStandardsIgnoreEnd Generic.Files.LineLength
	}
}
