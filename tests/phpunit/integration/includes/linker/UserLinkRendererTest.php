<?php
namespace MediaWiki\Tests\Linker;

use MediaWiki\Linker\UserLinkRenderer;
use MediaWiki\Tests\Unit\FakeQqxMessageLocalizer;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWikiLangTestCase;

/**
 * @covers \MediaWiki\Linker\UserLinkRenderer
 * @group Database
 */
class UserLinkRendererTest extends MediaWikiLangTestCase {
	use TempUserTestTrait;

	private UserLinkRenderer $userLinkRenderer;

	protected function setUp(): void {
		parent::setUp();
		$this->enableAutoCreateTempUser();

		$this->userLinkRenderer = new UserLinkRenderer(
			$this->getServiceContainer()->getTempUserConfig(),
			$this->getServiceContainer()->getSpecialPageFactory(),
			$this->getServiceContainer()->getLinkRenderer()
		);
	}

	public function addDBDataOnce(): void {
		$this->getExistingTestPage(
			Title::makeTitle( NS_USER, 'UserLinkRendererTestUser' )
		);
	}

	/**
	 * @dataProvider provideCasesForUserLink
	 */
	public function testUserLink(
		string $expected,
		UserIdentity $user,
		?string $altUserName = null,
		array $attributes = []
	): void {
		$actual = $this->userLinkRenderer->userLink(
			$user,
			new FakeQqxMessageLocalizer(),
			$altUserName,
			$attributes
		);

		$this->assertSame( $expected, $actual );
	}

	public static function provideCasesForUserLink(): iterable {
		# Format:
		# - expected
		# - userid
		# - username
		# - optional altUserName
		# - optional message
		return [
			# Empty name (T222529)
			'Empty username, userid 0' => [
				'<span class="mw-userlink mw-anonuserlink"><bdi></bdi></span>',
				new UserIdentityValue( 0, '' )
			],
			'Empty username, userid > 0' => [
				'<span class="mw-userlink"><bdi></bdi></span>',
				new UserIdentityValue( 73, '' )
			],

			// Anonymous users
			'User with user ID 0' => [
				'<a href="/wiki/Special:Contributions/JohnDoe" '
				. 'class="mw-userlink mw-anonuserlink" '
				. 'title="Special:Contributions/JohnDoe"><bdi>JohnDoe</bdi></a>',
				new UserIdentityValue( 0, 'JohnDoe' ),
			],
			'Anonymous with pretty IPv6' => [
				'<a href="/wiki/Special:Contributions/::1" '
				. 'class="mw-userlink mw-anonuserlink" '
				. 'title="Special:Contributions/::1"><bdi>::1</bdi></a>',
				new UserIdentityValue( 0, '::1' ),
			],
			'Anonymous with almost pretty IPv6' => [
				'<a href="/wiki/Special:Contributions/0:0:0:0:0:0:0:1" '
				. 'class="mw-userlink mw-anonuserlink" '
				. 'title="Special:Contributions/0:0:0:0:0:0:0:1"><bdi>::1</bdi></a>',
				new UserIdentityValue( 0, '0:0:0:0:0:0:0:1' ),
			],
			'Anonymous with full IPv6' => [
				'<a href="/wiki/Special:Contributions/0000:0000:0000:0000:0000:0000:0000:0001" '
				. 'class="mw-userlink mw-anonuserlink" '
				. 'title="Special:Contributions/0000:0000:0000:0000:0000:0000:0000:0001"><bdi>::1</bdi></a>',
				new UserIdentityValue( 0, '0000:0000:0000:0000:0000:0000:0000:0001' ),
			],
			'Anonymous with pretty IPv6 and an alternative username' => [
				'<a href="/wiki/Special:Contributions/::1" '
				. 'class="mw-userlink mw-anonuserlink" '
				. 'title="Special:Contributions/::1"><bdi>AlternativeUsername</bdi></a>',
				new UserIdentityValue( 0, '::1' ), 'AlternativeUsername',
			],

			# IPV4
			'Anonymous with IPv4' => [
				'<a href="/wiki/Special:Contributions/127.0.0.1" '
				. 'class="mw-userlink mw-anonuserlink" '
				. 'title="Special:Contributions/127.0.0.1"><bdi>127.0.0.1</bdi></a>',
				new UserIdentityValue( 0, '127.0.0.1' ),
			],
			'Anonymous with IPv4 and an alternative username' => [
				'<a href="/wiki/Special:Contributions/127.0.0.1" '
				. 'class="mw-userlink mw-anonuserlink" '
				. 'title="Special:Contributions/127.0.0.1"><bdi>AlternativeUsername</bdi></a>',
				new UserIdentityValue( 0, '127.0.0.1' ), 'AlternativeUsername',
			],

			# IP ranges
			'Anonymous with IPv4 range' => [
				'<a href="/wiki/Special:Contributions/1.2.3.4/31" '
				. 'class="mw-userlink mw-anonuserlink" '
				. 'title="Special:Contributions/1.2.3.4/31"><bdi>1.2.3.4/31</bdi></a>',
				new UserIdentityValue( 0, '1.2.3.4/31' ),
			],
			'Anonymous with IPv6 range' => [
				'<a href="/wiki/Special:Contributions/2001:db8::1/43" '
				. 'class="mw-userlink mw-anonuserlink" '
				. 'title="Special:Contributions/2001:db8::1/43"><bdi>2001:db8::1/43</bdi></a>',
				new UserIdentityValue( 0, '2001:db8::1/43' ),
			],

			# External (imported) user, unknown prefix
			'User from acme wiki' => [
				'<span class="mw-userlink mw-extuserlink mw-anonuserlink"><bdi>acme&gt;Alice</bdi></span>',
				new UserIdentityValue( 0, "acme>Alice" ),
			],

			# Corrupt user names
			'User name with line break' => [
				"<span class=\"mw-userlink mw-anonuserlink\"><bdi>Foo\nBar</bdi></span>",
				new UserIdentityValue( 0, "Foo\nBar" ),
			],
			'User name with trailing underscore' => [
				'<span class="mw-userlink mw-anonuserlink"><bdi>Barf_</bdi></span>',
				new UserIdentityValue( 0, "Barf_" ),
			],
			'Lower case user name' => [
				'<span class="mw-userlink mw-anonuserlink"><bdi>abcd</bdi></span>',
				new UserIdentityValue( 0, "abcd" ),
			],
			'User name with slash' => [
				'<span class="mw-userlink mw-anonuserlink"><bdi>For/Bar</bdi></span>',
				new UserIdentityValue( 0, "For/Bar" ),
			],
			'User name with hash' => [
				'<span class="mw-userlink mw-anonuserlink"><bdi>For#Bar</bdi></span>',
				new UserIdentityValue( 0, "For#Bar" ),
			],

			// Temporary accounts
			'Temporary user' => [
				'<a href="/wiki/Special:Contributions/~2025-1" '
				. 'class="mw-userlink mw-tempuserlink" '
				. 'title="Special:Contributions/~2025-1"><bdi>~2025-1</bdi></a>',
				new UserIdentityValue( 2, '~2025-1' )
			],

			'Temporary user link with custom class' => [
				'<a href="/wiki/Special:Contributions/~2025-1" '
				. 'class="mw-userlink mw-tempuserlink custom-class" '
				. 'title="Special:Contributions/~2025-1"><bdi>~2025-1</bdi></a>',
				new UserIdentityValue( 2, '~2025-1' ),
				null,
				[ 'class' => 'custom-class' ]
			],

			// Named users
			'Named user with existing user page' => [
				'<a href="/wiki/User:UserLinkRendererTestUser" '
				. 'class="mw-userlink" '
				. 'title="User:UserLinkRendererTestUser"><bdi>UserLinkRendererTestUser</bdi></a>',
				new UserIdentityValue( 3, 'UserLinkRendererTestUser' )
			],

			'Named user with nonexistent user page' => [
				'<a href="/index.php?title=User:UserLinkRendererTestUserNoPage&amp;action=edit&amp;redlink=1" '
				. 'class="new mw-userlink" '
				. 'title="User:UserLinkRendererTestUserNoPage (page does not exist)">'
				. '<bdi>UserLinkRendererTestUserNoPage</bdi></a>',
				new UserIdentityValue( 4, 'UserLinkRendererTestUserNoPage' )
			],
		];
	}

	/**
	 * @dataProvider provideCacheParams
	 *
	 * @param bool $shouldCache `true` if the user link should be cached, `false` otherwise
	 * @param UserIdentity $otherUser User to use in the second call to userLink().
	 * @param string|null $altUserName Alternative user name to use in the second call to userLink().
	 * @param string[] $attributes Attributes to use in the second call to userLink().
	 */
	public function testUserLinkShouldCacheByUserNameAndParams(
		bool $shouldCache,
		UserIdentity $otherUser,
		?string $altUserName = null,
		array $attributes = []
	): void {
		$user = new UserIdentityValue( 1, 'TestUser' );

		$firstCall = $this->userLinkRenderer->userLink(
			$user,
			new FakeQqxMessageLocalizer()
		);
		$otherCall = $this->userLinkRenderer->userLink(
			$otherUser,
			new FakeQqxMessageLocalizer(),
			$altUserName,
			$attributes
		);

		if ( $shouldCache ) {
			$this->assertSame( $firstCall, $otherCall );
		} else {
			$this->assertNotEquals( $firstCall, $otherCall );
		}
	}

	public static function provideCacheParams(): iterable {
		yield 'same user and params' => [
			true,
			new UserIdentityValue( 1, 'TestUser' )
		];

		yield 'same user but different link text' => [
			false,
			new UserIdentityValue( 1, 'TestUser' ),
			'foo'
		];

		yield 'same user but different attributes' => [
			false,
			new UserIdentityValue( 1, 'TestUser' ),
			null,
			[ 'class' => 'foo' ]
		];

		yield 'different user' => [
			false,
			new UserIdentityValue( 2, 'OtherUser' )
		];
	}
}
