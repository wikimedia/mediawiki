<?php

class LinkerTest extends MediaWikiLangTestCase {

	/**
	 * @dataProvider provideCasesForUserLink
	 * @covers Linker::userLink
	 */
	public function testUserLink( $expected, $userId, $userName, $altUserName = false, $msg = '' ) {
		$this->setMwGlobals( array(
			'wgArticlePath' => '/wiki/$1',
			'wgWellFormedXml' => true,
		) );

		$this->assertEquals( $expected,
			Linker::userLink( $userId, $userName, $altUserName, $msg )
		);
	}

	public static function provideCasesForUserLink() {
		# Format:
		# - expected
		# - userid
		# - username
		# - optional altUserName
		# - optional message
		return array(

			### ANONYMOUS USER ########################################
			array(
				'<a href="/wiki/Special:Contributions/JohnDoe" title="Special:Contributions/JohnDoe" class="mw-userlink">JohnDoe</a>',
				0, 'JohnDoe', false,
			),
			array(
				'<a href="/wiki/Special:Contributions/::1" title="Special:Contributions/::1" class="mw-userlink">::1</a>',
				0, '::1', false,
				'Anonymous with pretty IPv6'
			),
			array(
				'<a href="/wiki/Special:Contributions/0:0:0:0:0:0:0:1" title="Special:Contributions/0:0:0:0:0:0:0:1" class="mw-userlink">::1</a>',
				0, '0:0:0:0:0:0:0:1', false,
				'Anonymous with almost pretty IPv6'
			),
			array(
				'<a href="/wiki/Special:Contributions/0000:0000:0000:0000:0000:0000:0000:0001" title="Special:Contributions/0000:0000:0000:0000:0000:0000:0000:0001" class="mw-userlink">::1</a>',
				0, '0000:0000:0000:0000:0000:0000:0000:0001', false,
				'Anonymous with full IPv6'
			),
			array(
				'<a href="/wiki/Special:Contributions/::1" title="Special:Contributions/::1" class="mw-userlink">AlternativeUsername</a>',
				0, '::1', 'AlternativeUsername',
				'Anonymous with pretty IPv6 and an alternative username'
			),

			# IPV4
			array(
				'<a href="/wiki/Special:Contributions/127.0.0.1" title="Special:Contributions/127.0.0.1" class="mw-userlink">127.0.0.1</a>',
				0, '127.0.0.1', false,
				'Anonymous with IPv4'
			),
			array(
				'<a href="/wiki/Special:Contributions/127.0.0.1" title="Special:Contributions/127.0.0.1" class="mw-userlink">AlternativeUsername</a>',
				0, '127.0.0.1', 'AlternativeUsername',
				'Anonymous with IPv4 and an alternative username'
			),

			### Regular user ##########################################
			# TODO!
		);
	}
}
