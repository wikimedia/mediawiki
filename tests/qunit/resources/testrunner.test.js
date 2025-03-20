QUnit.module( 'testrunner', () => {
	QUnit.test( 'assert.htmlEqual', ( assert ) => {
		assert.htmlEqual(
			'<div><p class="some classes" data-length="10">Child paragraph with <a href="http://example.com">A link</a></p>Regular text<span>A span</span></div>',
			'<div><p data-length=\'10\'  class=\'some classes\'>Child paragraph with <a href=\'http://example.com\' >A link</a></p>Regular text<span>A span</span></div>',
			'Attribute order, spacing and quotation marks (equal)'
		);

		assert.notHtmlEqual(
			'<div><p class="some classes" data-length="10">Child paragraph with <a href="http://example.com">A link</a></p>Regular text<span>A span</span></div>',
			'<div><p data-length=\'10\'  class=\'some more classes\'>Child paragraph with <a href=\'http://example.com\' >A link</a></p>Regular text<span>A span</span></div>',
			'Attribute order, spacing and quotation marks (not equal)'
		);

		assert.htmlEqual(
			'<label for="firstname" accesskey="f" class="important">First</label><input id="firstname" /><label for="lastname" accesskey="l" class="minor">Last</label><input id="lastname" />',
			'<label for="firstname" accesskey="f" class="important">First</label><input id="firstname" /><label for="lastname" accesskey="l" class="minor">Last</label><input id="lastname" />',
			'Multiple root nodes (equal)'
		);

		assert.notHtmlEqual(
			'<label for="firstname" accesskey="f" class="important">First</label><input id="firstname" /><label for="lastname" accesskey="l" class="minor">Last</label><input id="lastname" />',
			'<label for="firstname" accesskey="f" class="important">First</label><input id="firstname" /><label for="lastname" accesskey="l" class="important" >Last</label><input id="lastname" />',
			'Multiple root nodes (not equal, last label node is different)'
		);

		assert.htmlEqual(
			'fo&quot;o<br/>b&gt;ar',
			'fo"o<br/>b>ar',
			'Extra escaping is equal'
		);
		assert.notHtmlEqual(
			'foo&lt;br/&gt;bar',
			'foo<br/>bar',
			'Text escaping (not equal)'
		);

		assert.htmlEqual(
			'foo<a href="http://example.com">example</a>bar',
			'foo<a href="http://example.com">example</a>bar',
			'Outer text nodes are compared (equal)'
		);

		assert.notHtmlEqual(
			'foo<a href="http://example.com">example</a>bar',
			'foo<a href="http://example.com">example</a>quux',
			'Outer text nodes are compared (last text node different)'
		);
	} );
} );
