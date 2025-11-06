<?php
namespace MediaWiki\Tests\Unit\Installer;

use MediaWiki\Installer\InstallDocFormatter;
use MediaWikiUnitTestCase;

class InstallDocFormatterTest extends MediaWikiUnitTestCase {
	/**
	 * @covers \MediaWiki\Installer\InstallDocFormatter
	 * @dataProvider provideDocFormattingTests
	 */
	public function testFormat( $expected, $unformattedText, $message = '' ) {
		$this->assertEquals(
			$expected,
			InstallDocFormatter::format( $unformattedText ),
			$message
		);
	}

	/**
	 * Provider for testFormat()
	 */
	public static function provideDocFormattingTests() {
		# Format: (expected string, unformattedText string, optional message)
		return [
			# Escape some wikitext
			[ 'Install &lt;tag>', 'Install <tag>', 'Escaping <' ],
			[ 'Install &#123;&#123;template}}', 'Install {{template}}', 'Escaping [[' ],
			[ 'Install &#91;&#91;page]]', 'Install [[page]]', 'Escaping {{' ],
			[ 'Install &#95;&#95;TOC&#95;&#95;', 'Install __TOC__', 'Escaping __' ],
			[ 'Install ', "Install \r", 'Removing \r' ],

			# Transform \t{1,2} into :{1,2}
			[ ':One indentation', "\tOne indentation", 'Replacing a single \t' ],
			[ '::Two indentations', "\t\tTwo indentations", 'Replacing 2 x \t' ],

			# Transform 'T123' links
			[
				'<span class="config-plainlink">[https://phabricator.wikimedia.org/T123 T123]</span>',
				'T123', 'Testing T123 links' ],
			[
				'bug <span class="config-plainlink">[https://phabricator.wikimedia.org/T123 T123]</span>',
				'bug T123', 'Testing bug T123 links' ],
			[
				'(<span class="config-plainlink">[https://phabricator.wikimedia.org/T987654 T987654]</span>)',
				'(T987654)', 'Testing (T987654) links' ],

			# "Tabc" shouldn't work
			[ 'Tfoobar', 'Tfoobar', "Don't match T followed by non-digits" ],
			[ 'T!!fakefake!!', 'T!!fakefake!!', "Don't match T followed by non-digits" ],

			# Transform 'bug 123' links
			[
				'<span class="config-plainlink">[https://bugzilla.wikimedia.org/123 bug 123]</span>',
				'bug 123', 'Testing bug 123 links' ],
			[
				'(<span class="config-plainlink">[https://bugzilla.wikimedia.org/987654 bug 987654]</span>)',
				'(bug 987654)', 'Testing (bug 987654) links' ],

			# "bug abc" shouldn't work
			[ 'bug foobar', 'bug foobar', "Don't match bug followed by non-digits" ],
			[ 'bug !!fakefake!!', 'bug !!fakefake!!', "Don't match bug followed by non-digits" ],

			# Transform '$wgFooBar' links
			[
				'<span class="config-plainlink">'
					. '[https://www.mediawiki.org/wiki/Manual:$wgFooBar $wgFooBar]</span>',
				'$wgFooBar', 'Testing basic $wgFooBar' ],
			[
				'<span class="config-plainlink">'
					. '[https://www.mediawiki.org/wiki/Manual:$wgFooBar45 $wgFooBar45]</span>',
				'$wgFooBar45', 'Testing $wgFooBar45 (with numbers)' ],
			[
				'<span class="config-plainlink">'
					. '[https://www.mediawiki.org/wiki/Manual:$wgFoo_Bar $wgFoo_Bar]</span>',
				'$wgFoo_Bar', 'Testing $wgFoo_Bar (with underscore)' ],

			# Icky variables that shouldn't link
			[
				'$myAwesomeVariable',
				'$myAwesomeVariable',
				'Testing $myAwesomeVariable (not starting with $wg)'
			],
			[ '$()not!a&Var', '$()not!a&Var', 'Testing $()not!a&Var (obviously not a variable)' ],
		];
	}
}
