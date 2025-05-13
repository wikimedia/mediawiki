<?php
namespace MediaWiki\Tests\Linker;

use MediaWiki\Config\SiteConfiguration;
use MediaWiki\Context\IContextSource;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\Linker\UserLinkRenderer;
use MediaWiki\MainConfigNames;
use MediaWiki\Output\OutputPage;
use MediaWiki\Tests\Site\TestSites;
use MediaWiki\Tests\Unit\FakeQqxMessageLocalizer;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\TempUser\TempUserDetailsLookup;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityLookup;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\WikiMap\WikiMap;
use MediaWikiLangTestCase;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * @covers \MediaWiki\Linker\UserLinkRenderer
 * @group Database
 */
class UserLinkRendererTest extends MediaWikiLangTestCase {
	private const EXPIRED_TEMP_USER_NAME = '~2023-1';

	use TempUserTestTrait;

	private OutputPage $outputPage;
	private IContextSource $context;
	private TempUserDetailsLookup $tempUserDetailsLookup;
	private UserIdentityLookup $userIdentityLookup;
	private UserLinkRenderer $userLinkRenderer;

	protected function setUp(): void {
		parent::setUp();

		$conf = new SiteConfiguration();
		$conf->suffixes = [ 'wiki' ];
		$conf->settings = [
			'wgServer' => [
				WikiMap::getCurrentWikiId() => 'http://local.wiki.org',
				'externalwiki' => '//external.wiki.org',
			],
			'wgArticlePath' => [
				WikiMap::getCurrentWikiId() => '/w/$1',
				'externalwiki' => '/wiki/$1',
			],
		];

		$this->setMwGlobals( 'wgConf', $conf );
		$this->overrideConfigValues( [
			MainConfigNames::LocalDatabases => [
				WikiMap::getCurrentWikiId(),
				'externalwiki'
			],
		] );

		TestSites::insertIntoDb();

		$this->enableAutoCreateTempUser();

		$messageLocalizer = new FakeQqxMessageLocalizer();

		$this->outputPage = $this->createMock( OutputPage::class );
		$this->context = $this->createMock( IContextSource::class );
		$this->context->method( 'getOutput' )
			->willReturn( $this->outputPage );
		$this->context->method( 'msg' )
			->willReturnCallback( [ $messageLocalizer, 'msg' ] );

		$this->tempUserDetailsLookup = $this->createMock( TempUserDetailsLookup::class );
		$this->userIdentityLookup = $this->createMock( UserIdentityLookup::class );

		$this->userLinkRenderer = new UserLinkRenderer(
			$this->getServiceContainer()->getHookContainer(),
			$this->getServiceContainer()->getTempUserConfig(),
			$this->getServiceContainer()->getSpecialPageFactory(),
			$this->getServiceContainer()->getLinkRenderer(),
			$this->tempUserDetailsLookup,
			$this->userIdentityLookup
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
		?bool $isTemp,
		?string $altUserName = null,
		array $attributes = []
	): void {
		$this->outputPage->expects( $this->once() )
			->method( 'addModuleStyles' )
			->with( [
				'mediawiki.interface.helpers.styles',
				'mediawiki.interface.helpers.linker.styles'
			] );

		// For temp users, UserLinkRenderer checks if they belong to the local
		// wiki before trying to render the actual link.
		if ( $isTemp ) {
			if ( $user->getWikiId() === WikiAwareEntity::LOCAL ) {
				$this->userIdentityLookup
					->expects( $this->never() )
					->method( 'getUserIdentityByName' )
					->with( $user->getName() );
				$this->tempUserDetailsLookup
					->method( 'isExpired' )
					->with( $user )
					->willReturn(
						$user->getName() === self::EXPIRED_TEMP_USER_NAME
					);
			} else {
				$localUserMock = $this->createMock( UserIdentity::class );

				$this->userIdentityLookup
					->expects( $this->once() )
					->method( 'getUserIdentityByName' )
					->with( $user->getName() )
					->willReturn( $localUserMock );
				$this->tempUserDetailsLookup
					->expects( $isTemp ? $this->once() : $this->never() )
					->method( 'isExpired' )
					->with( $localUserMock )
					->willReturn(
						$user->getName() === self::EXPIRED_TEMP_USER_NAME
					);
			}
		}

		$actual = $this->userLinkRenderer->userLink(
			$user,
			$this->context,
			$altUserName,
			$attributes
		);

		$this->assertSame( $expected, $actual );
	}

	public static function provideCasesForUserLink(): iterable {
		return [
			# Empty name (T222529)
			'Empty username, userid 0' => [
				'expected' => '<span class="mw-userlink mw-anonuserlink"><bdi></bdi></span>',
				'userIdentity' => new UserIdentityValue( 0, '' ),
				'isTemp' => false,
			],
			'Empty username, userid > 0' => [
				'expected' => '<span class="mw-userlink"><bdi></bdi></span>',
				'userIdentity' => new UserIdentityValue( 73, '' ),
				'isTemp' => false,
			],

			# Anonymous users
			'User with user ID 0' => [
				'expected' => '<a href="/wiki/Special:Contributions/JohnDoe" '
				. 'class="mw-userlink mw-anonuserlink" '
				. 'title="Special:Contributions/JohnDoe"><bdi>JohnDoe</bdi></a>',
				'userIdentity' => new UserIdentityValue( 0, 'JohnDoe' ),
				'isTemp' => false,
			],
			'Anonymous with pretty IPv6' => [
				'expected' => '<a href="/wiki/Special:Contributions/::1" '
				. 'class="mw-userlink mw-anonuserlink" '
				. 'title="Special:Contributions/::1"><bdi>::1</bdi></a>',
				'userIdentity' => new UserIdentityValue( 0, '::1' ),
				'isTemp' => false,
			],
			'Anonymous with almost pretty IPv6' => [
				'expected' => '<a href="/wiki/Special:Contributions/0:0:0:0:0:0:0:1" '
				. 'class="mw-userlink mw-anonuserlink" '
				. 'title="Special:Contributions/0:0:0:0:0:0:0:1"><bdi>::1</bdi></a>',
				'userIdentity' => new UserIdentityValue( 0, '0:0:0:0:0:0:0:1' ),
				'isTemp' => false,
			],
			'Anonymous with full IPv6' => [
				'expected' => '<a href="/wiki/Special:Contributions/0000:0000:0000:0000:0000:0000:0000:0001" '
				. 'class="mw-userlink mw-anonuserlink" '
				. 'title="Special:Contributions/0000:0000:0000:0000:0000:0000:0000:0001"><bdi>::1</bdi></a>',
				'userIdentity' => new UserIdentityValue( 0, '0000:0000:0000:0000:0000:0000:0000:0001' ),
				'isTemp' => false,
			],
			'Anonymous with pretty IPv6 and an alternative username' => [
				'expected' => '<a href="/wiki/Special:Contributions/::1" '
				. 'class="mw-userlink mw-anonuserlink" '
				. 'title="Special:Contributions/::1"><bdi>AlternativeUsername</bdi></a>',
				'userIdentity' => new UserIdentityValue( 0, '::1' ),
				'isTemp' => false,
				'altUserName' => 'AlternativeUsername',
			],

			# IPV4
			'Anonymous with IPv4' => [
				'expected' => '<a href="/wiki/Special:Contributions/127.0.0.1" '
				. 'class="mw-userlink mw-anonuserlink" '
				. 'title="Special:Contributions/127.0.0.1"><bdi>127.0.0.1</bdi></a>',
				'userIdentity' => new UserIdentityValue( 0, '127.0.0.1' ),
				'isTemp' => false,
			],
			'Anonymous with IPv4 and an alternative username' => [
				'expected' => '<a href="/wiki/Special:Contributions/127.0.0.1" '
				. 'class="mw-userlink mw-anonuserlink" '
				. 'title="Special:Contributions/127.0.0.1"><bdi>AlternativeUsername</bdi></a>',
				'userIdentity' => new UserIdentityValue( 0, '127.0.0.1' ),
				'isTemp' => false,
				'altUserName' => 'AlternativeUsername',
			],

			# IP ranges
			'Anonymous with IPv4 range' => [
				'expected' => '<a href="/wiki/Special:Contributions/1.2.3.4/31" '
				. 'class="mw-userlink mw-anonuserlink" '
				. 'title="Special:Contributions/1.2.3.4/31"><bdi>1.2.3.4/31</bdi></a>',
				'userIdentity' => new UserIdentityValue( 0, '1.2.3.4/31' ),
				'isTemp' => false,
			],
			'Anonymous with IPv6 range' => [
				'expected' => '<a href="/wiki/Special:Contributions/2001:db8::1/43" '
				. 'class="mw-userlink mw-anonuserlink" '
				. 'title="Special:Contributions/2001:db8::1/43"><bdi>2001:db8::1/43</bdi></a>',
				'userIdentity' => new UserIdentityValue( 0, '2001:db8::1/43' ),
				'isTemp' => false,
			],

			# External (imported) user, unknown prefix
			'User from acme wiki' => [
				'expected' => '<span class="mw-userlink mw-extuserlink mw-anonuserlink"><bdi>acme&gt;Alice</bdi></span>',
				'userIdentity' => new UserIdentityValue( 0, "acme>Alice" ),
				'isTemp' => false,
			],

			# Corrupt user names
			'User name with line break' => [
				'expected' => "<span class=\"mw-userlink mw-anonuserlink\"><bdi>Foo\nBar</bdi></span>",
				'userIdentity' => new UserIdentityValue( 0, "Foo\nBar" ),
				'isTemp' => false,
			],
			'User name with trailing underscore' => [
				'expected' => '<span class="mw-userlink mw-anonuserlink"><bdi>Barf_</bdi></span>',
				'userIdentity' => new UserIdentityValue( 0, "Barf_" ),
				'isTemp' => false,
			],
			'Lower case user name' => [
				'expected' => '<span class="mw-userlink mw-anonuserlink"><bdi>abcd</bdi></span>',
				'userIdentity' => new UserIdentityValue( 0, "abcd" ),
				'isTemp' => false,
			],
			'User name with slash' => [
				'expected' => '<span class="mw-userlink mw-anonuserlink"><bdi>For/Bar</bdi></span>',
				'userIdentity' => new UserIdentityValue( 0, "For/Bar" ),
				'isTemp' => false,
			],
			'User name with hash' => [
				'expected' => '<span class="mw-userlink mw-anonuserlink"><bdi>For#Bar</bdi></span>',
				'userIdentity' => new UserIdentityValue( 0, "For#Bar" ),
				'isTemp' => false,
			],

			# Temporary accounts
			'Temporary user' => [
				'expected' => '<a href="/wiki/Special:Contributions/~2025-1" '
				. 'class="mw-userlink mw-tempuserlink" '
				. 'title="Special:Contributions/~2025-1" '
				. 'data-mw-target="~2025-1"><bdi>~2025-1</bdi></a>',
				'userIdentity' => new UserIdentityValue( 2, '~2025-1' ),
				'isTemp' => true,
			],
			'Temporary user link with custom class' => [
				'expected' => '<a href="/wiki/Special:Contributions/~2025-1" '
				. 'class="mw-userlink mw-tempuserlink custom-class" '
				. 'title="Special:Contributions/~2025-1" '
				. 'data-mw-target="~2025-1"><bdi>~2025-1</bdi></a>',
				'userIdentity' => new UserIdentityValue( 2, '~2025-1' ),
				'isTemp' => true,
				'altUsername' => null,
				[ 'class' => 'custom-class' ]
			],
			'Expired temporary user link' => [
				'expected' => '<a href="/wiki/Special:Contributions/~2023-1" '
				. 'class="mw-userlink mw-tempuserlink mw-tempuserlink-expired" '
				. 'data-mw-target="~2023-1" '
				. 'aria-description="(tempuser-expired-link-tooltip)">'
				. '<bdi>~2023-1</bdi>'
				. '<span role="presentation" class="cdx-tooltip '
				. 'mw-tempuserlink-expired--tooltip">'
				. '(tempuser-expired-link-tooltip)</span></a>',
				'userIdentity' => new UserIdentityValue( 2, self::EXPIRED_TEMP_USER_NAME ),
				'isTemp' => true,
			],

			# Named users
			'Named user with existing user page' => [
				'expected' => '<a href="/wiki/User:UserLinkRendererTestUser" '
				. 'class="mw-userlink" '
				. 'title="User:UserLinkRendererTestUser"><bdi>UserLinkRendererTestUser</bdi></a>',
				'userIdentity' => new UserIdentityValue( 3, 'UserLinkRendererTestUser' ),
				'isTemp' => false,
			],
			'Named user with nonexistent user page' => [
				'expected' => '<a href="/index.php?title=User:UserLinkRendererTestUserNoPage&amp;action=edit&amp;redlink=1" '
				. 'class="new mw-userlink" '
				. 'title="User:UserLinkRendererTestUserNoPage (page does not exist)">'
				. '<bdi>UserLinkRendererTestUserNoPage</bdi></a>',
				'userIdentity' => new UserIdentityValue( 4, 'UserLinkRendererTestUserNoPage' ),
				'isTemp' => false,
			],

			# External users
			'User from an external wiki' => [
				'expected' => '<a class="mw-userlink external" rel="nofollow"'
				. ' href="//external.wiki.org/wiki/User:Jane_Doe">'
				. '<bdi>Jane Doe</bdi></a>',
				'userIdentity' => new UserIdentityValue(
					123,
					'Jane Doe',
					'externalwiki'
				),
				'isTemp' => false,
			],
			'Temp user from an external wiki' => [
				'expected' => '<a '
				. 'data-mw-target="~2025-1" '
				. 'class="mw-userlink mw-tempuserlink external" '
				. 'rel="nofollow" href="//external.wiki.org/wiki/User:~2025-1">'
				. '<bdi>~2025-1</bdi></a>',
				'userIdentity' => new UserIdentityValue(
					123,
					'~2025-1',
					'externalwiki'
				),
				'isTemp' => true,
			],
			'Expired temporary account from an external wiki' => [
				'expected' => sprintf(
					'<a data-mw-target="%1$s" '
					. 'aria-description="(tempuser-expired-link-tooltip)" '
					. 'class="mw-userlink mw-tempuserlink '
					. 'mw-tempuserlink-expired external" rel="nofollow" '
					. 'href="//external.wiki.org/wiki/User:%1$s">'
					. '<bdi>%1$s</bdi>'
					. '<span role="presentation" '
					. 'class="cdx-tooltip mw-tempuserlink-expired--tooltip">'
					. '(tempuser-expired-link-tooltip)</span></a>',
					self::EXPIRED_TEMP_USER_NAME
				),
				'userIdentity' => new UserIdentityValue(
					123,
					self::EXPIRED_TEMP_USER_NAME,
					'externalwiki'
				),
				'isTemp' => true,
			]
		];
	}

	/**
	 * @dataProvider provideCacheParams
	 *
	 * @param bool $isTemp `true` if the provided user is a temp account, `false` otherwise.
	 * @param bool $shouldCache `true` if the user link should be cached, `false` otherwise.
	 * @param bool $shouldMatch `false` if the extra params change the link HTML, `true` otherwise.
	 * @param UserIdentity $otherUser User to use in the second call to userLink().
	 * @param string|null $altUserName Alternative username to use in the second call to userLink().
	 * @param string[] $attributes Attributes to use in the second call to userLink().
	 */
	public function testUserLinkShouldCacheByUserNameAndParams(
		bool $isTemp,
		bool $shouldCache,
		bool $shouldMatch,
		UserIdentity $otherUser,
		?string $altUserName = null,
		array $attributes = []
	): void {
		$user = new UserIdentityValue( 1, 'TestUser' );

		$this->outputPage->expects( $this->exactly( 2 ) )
			->method( 'addModuleStyles' )
			->with( [
				'mediawiki.interface.helpers.styles',
				'mediawiki.interface.helpers.linker.styles'
			] );

		$users = [
			[ $user->getName(), IDBAccessObject::READ_NORMAL, $user ],
			[
				$otherUser->getName(),
				IDBAccessObject::READ_NORMAL,
				$otherUser
			]
		];

		$this->userIdentityLookup
			->expects(
				$this->exactly(
				$isTemp ? ( $shouldCache ? 1 : 2 ) : 0
				)
			)
			->method( 'getUserIdentityByName' )
			->willReturnMap( $users );

		$firstCall = $this->userLinkRenderer->userLink(
			$user,
			$this->context
		);
		$otherCall = $this->userLinkRenderer->userLink(
			$otherUser,
			$this->context,
			$altUserName,
			$attributes
		);

		if ( $shouldMatch ) {
			$this->assertSame( $firstCall, $otherCall );
		} else {
			$this->assertNotEquals( $firstCall, $otherCall );
		}
	}

	public static function provideCacheParams(): iterable {
		yield 'same user and params' => [
			'isTemp' => false,
			'shouldCache' => true,
			'shouldMatch' => true,
			'otherUser' => new UserIdentityValue( 1, 'TestUser' )
		];
		yield 'same user but different link text' => [
			'isTemp' => false,
			'shouldCache' => false,
			'shouldMatch' => false,
			'otherUser' => new UserIdentityValue( 1, 'TestUser' ),
			'altUserName' => 'foo'
		];
		yield 'same user but different attributes' => [
			'isTemp' => false,
			'shouldCache' => false,
			'shouldMatch' => false,
			'otherUser' => new UserIdentityValue( 1, 'TestUser' ),
			'altUserName' => null,
			'attributes' => [ 'class' => 'foo' ]
		];
		yield 'different user' => [
			'isTemp' => false,
			'shouldCache' => false,
			'shouldMatch' => false,
			'otherUser' => new UserIdentityValue( 2, 'OtherUser' )
		];
	}

	public function testUserLinkHook() {
		$this->setTemporaryHook( 'UserLinkRendererUserLinkPostRender', static function (
			UserIdentity $targetUser, IContextSource $context, &$html, &$prefix, &$postfix
		) {
			$prefix .= '<span>foo</span>';
			$postfix .= '<span>bar</span>';
			$html .= '<span>test</span>';
		} );
		$outputPageMock = $this->createMock( OutputPage::class );
		$contextMock = $this->createMock( IContextSource::class );
		$contextMock->method( 'getOutput' )
			->willReturn( $outputPageMock );
		$usernameHtml = $this->getServiceContainer()->getUserLinkRenderer()->userLink(
			$this->getTestUser()->getUser(),
			$contextMock
		);
		$this->assertStringContainsString( '<span>foo</span>', $usernameHtml );
		$this->assertStringContainsString( '<span>bar</span>', $usernameHtml );
		$this->assertStringContainsString( '<span>test</span>', $usernameHtml );
	}
}
