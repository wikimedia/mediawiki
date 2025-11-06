<?php
namespace MediaWiki\Tests\Logging;

use MediaWiki\Cache\LinkCache;
use MediaWiki\Context\RequestContext;
use MediaWiki\Linker\LinkRendererFactory;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use MediaWiki\User\UserIdentityValue;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LBFactory;

/**
 * @covers \MediaWiki\Logging\ProtectLogFormatter
 */
class ProtectLogFormatterTest extends LogFormatterTestCase {

	use MockAuthorityTrait;

	protected function setUp(): void {
		parent::setUp();

		$db = $this->createNoOpMock( IDatabase::class, [ 'getInfinity' ] );
		$db->method( 'getInfinity' )->willReturn( 'infinity' );
		$lbFactory = $this->createMock( LBFactory::class );
		$lbFactory->method( 'getReplicaDatabase' )->willReturn( $db );
		$this->setService( 'DBLoadBalancerFactory', $lbFactory );
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideProtectLogDatabaseRows() {
		return [
			// Current format
			[
				[
					'type' => 'protect',
					'action' => 'protect',
					'comment' => 'protect comment',
					'namespace' => NS_MAIN,
					'title' => 'ProtectPage',
					'params' => [
						'4::description' => '[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'5:bool:cascade' => false,
						'details' => [
							[
								'type' => 'edit',
								'level' => 'sysop',
								'expiry' => 'infinity',
								'cascade' => false,
							],
							[
								'type' => 'move',
								'level' => 'sysop',
								'expiry' => 'infinity',
								'cascade' => false,
							],
						],
					],
				],
				[
					'text' => 'User protected ProtectPage [Edit=Allow only administrators] ' .
						'(indefinite) [Move=Allow only administrators] (indefinite)',
					'api' => [
						'description' => '[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'cascade' => false,
						'details' => [
							[
								'type' => 'edit',
								'level' => 'sysop',
								'expiry' => 'infinite',
								'cascade' => false,
							],
							[
								'type' => 'move',
								'level' => 'sysop',
								'expiry' => 'infinite',
								'cascade' => false,
							],
						],
					],
				],
			],

			// Current format with cascade
			[
				[
					'type' => 'protect',
					'action' => 'protect',
					'comment' => 'protect comment',
					'namespace' => NS_MAIN,
					'title' => 'ProtectPage',
					'params' => [
						'4::description' => '[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'5:bool:cascade' => true,
						'details' => [
							[
								'type' => 'edit',
								'level' => 'sysop',
								'expiry' => 'infinity',
								'cascade' => true,
							],
							[
								'type' => 'move',
								'level' => 'sysop',
								'expiry' => 'infinity',
								'cascade' => false,
							],
						],
					],
				],
				[
					'text' => 'User protected ProtectPage [Edit=Allow only administrators] ' .
						'(indefinite) [Move=Allow only administrators] (indefinite) [cascading]',
					'api' => [
						'description' => '[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'cascade' => true,
						'details' => [
							[
								'type' => 'edit',
								'level' => 'sysop',
								'expiry' => 'infinite',
								'cascade' => true,
							],
							[
								'type' => 'move',
								'level' => 'sysop',
								'expiry' => 'infinite',
								'cascade' => false,
							],
						],
					],
				],
			],

			// Legacy format
			[
				[
					'type' => 'protect',
					'action' => 'protect',
					'comment' => 'protect comment',
					'namespace' => NS_MAIN,
					'title' => 'ProtectPage',
					'params' => [
						'[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'',
					],
				],
				[
					'legacy' => true,
					'text' => 'User protected ProtectPage [edit=sysop] (indefinite)[move=sysop] (indefinite)',
					'api' => [
						'description' => '[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'cascade' => false,
					],
				],
			],

			// Legacy format with cascade
			[
				[
					'type' => 'protect',
					'action' => 'protect',
					'comment' => 'protect comment',
					'namespace' => NS_MAIN,
					'title' => 'ProtectPage',
					'params' => [
						'[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'cascade',
					],
				],
				[
					'legacy' => true,
					'text' => 'User protected ProtectPage [edit=sysop] ' .
						'(indefinite)[move=sysop] (indefinite) [cascading]',
					'api' => [
						'description' => '[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'cascade' => true,
					],
				],
			],
		];
	}

	/**
	 * @dataProvider provideProtectLogDatabaseRows
	 */
	public function testProtectLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideModifyLogDatabaseRows() {
		return [
			// Current format
			[
				[
					'type' => 'protect',
					'action' => 'modify',
					'comment' => 'protect comment',
					'namespace' => NS_MAIN,
					'title' => 'ProtectPage',
					'params' => [
						'4::description' => '[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'5:bool:cascade' => false,
						'details' => [
							[
								'type' => 'edit',
								'level' => 'sysop',
								'expiry' => 'infinity',
								'cascade' => false,
							],
							[
								'type' => 'move',
								'level' => 'sysop',
								'expiry' => 'infinity',
								'cascade' => false,
							],
						],
					],
				],
				[
					'text' => 'User changed protection settings for ProtectPage ' .
						'[Edit=Allow only administrators] ' .
						'(indefinite) [Move=Allow only administrators] (indefinite)',
					'api' => [
						'description' => '[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'cascade' => false,
						'details' => [
							[
								'type' => 'edit',
								'level' => 'sysop',
								'expiry' => 'infinite',
								'cascade' => false,
							],
							[
								'type' => 'move',
								'level' => 'sysop',
								'expiry' => 'infinite',
								'cascade' => false,
							],
						],
					],
				],
			],

			// Current format with cascade
			[
				[
					'type' => 'protect',
					'action' => 'modify',
					'comment' => 'protect comment',
					'namespace' => NS_MAIN,
					'title' => 'ProtectPage',
					'params' => [
						'4::description' => '[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'5:bool:cascade' => true,
						'details' => [
							[
								'type' => 'edit',
								'level' => 'sysop',
								'expiry' => 'infinity',
								'cascade' => true,
							],
							[
								'type' => 'move',
								'level' => 'sysop',
								'expiry' => 'infinity',
								'cascade' => false,
							],
						],
					],
				],
				[
					'text' => 'User changed protection settings for ProtectPage ' .
						'[Edit=Allow only administrators] (indefinite) ' .
						'[Move=Allow only administrators] (indefinite) [cascading]',
					'api' => [
						'description' => '[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'cascade' => true,
						'details' => [
							[
								'type' => 'edit',
								'level' => 'sysop',
								'expiry' => 'infinite',
								'cascade' => true,
							],
							[
								'type' => 'move',
								'level' => 'sysop',
								'expiry' => 'infinite',
								'cascade' => false,
							],
						],
					],
				],
			],

			// Legacy format
			[
				[
					'type' => 'protect',
					'action' => 'modify',
					'comment' => 'protect comment',
					'namespace' => NS_MAIN,
					'title' => 'ProtectPage',
					'params' => [
						'[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'',
					],
				],
				[
					'legacy' => true,
					'text' => 'User changed protection settings for ProtectPage ' .
						'[edit=sysop] (indefinite)[move=sysop] (indefinite)',
					'api' => [
						'description' => '[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'cascade' => false,
					],
				],
			],

			// Legacy format with cascade
			[
				[
					'type' => 'protect',
					'action' => 'modify',
					'comment' => 'protect comment',
					'namespace' => NS_MAIN,
					'title' => 'ProtectPage',
					'params' => [
						'[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'cascade',
					],
				],
				[
					'legacy' => true,
					'text' => 'User changed protection settings for ProtectPage ' .
						'[edit=sysop] (indefinite)[move=sysop] (indefinite) [cascading]',
					'api' => [
						'description' => '[edit=sysop] (indefinite)[move=sysop] (indefinite)',
						'cascade' => true,
					],
				],
			],
		];
	}

	/**
	 * @dataProvider provideModifyLogDatabaseRows
	 */
	public function testModifyLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideUnprotectLogDatabaseRows() {
		return [
			// Current format
			[
				[
					'type' => 'protect',
					'action' => 'unprotect',
					'comment' => 'unprotect comment',
					'namespace' => NS_MAIN,
					'title' => 'ProtectPage',
					'params' => [],
				],
				[
					'text' => 'User removed protection from ProtectPage',
					'api' => [],
				],
			],
		];
	}

	/**
	 * @dataProvider provideUnprotectLogDatabaseRows
	 */
	public function testUnprotectLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideMoveProtLogDatabaseRows() {
		return [
			// Current format
			[
				[
					'type' => 'protect',
					'action' => 'move_prot',
					'comment' => 'Move comment',
					'namespace' => NS_MAIN,
					'title' => 'NewPage',
					'params' => [
						'4::oldtitle' => 'OldPage',
					],
				],
				[
					'text' => 'User moved protection settings from OldPage to NewPage',
					'api' => [
						'oldtitle_ns' => 0,
						'oldtitle_title' => 'OldPage',
					],
				],
			],

			// Legacy format
			[
				[
					'type' => 'protect',
					'action' => 'move_prot',
					'comment' => 'Move comment',
					'namespace' => NS_MAIN,
					'title' => 'NewPage',
					'params' => [
						'OldPage',
					],
				],
				[
					'legacy' => true,
					'text' => 'User moved protection settings from OldPage to NewPage',
					'api' => [
						'oldtitle_ns' => 0,
						'oldtitle_title' => 'OldPage',
					],
				],
			],
		];
	}

	/**
	 * @dataProvider provideMoveProtLogDatabaseRows
	 */
	public function testMoveProtLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}

	public static function provideGetActionLinks() {
		yield [
			[ 'protect' ],
			true
		];
		yield [
			[],
			false
		];
	}

	/**
	 * @param string[] $permissions
	 * @param bool $shouldMatch
	 * @dataProvider provideGetActionLinks
	 * @covers \MediaWiki\Logging\ProtectLogFormatter::getActionLinks
	 */
	public function testGetActionLinks( array $permissions, $shouldMatch ) {
		RequestContext::resetMain();
		$user = $this->mockUserAuthorityWithPermissions( new UserIdentityValue( 42, __METHOD__ ), $permissions );
		$row = $this->expandDatabaseRow( [
			'type' => 'protect',
			'action' => 'unprotect',
			'comment' => 'unprotect comment',
			'namespace' => NS_MAIN,
			'title' => 'ProtectPage',
			'params' => [],
		], false );
		$context = new RequestContext();
		$context->setAuthority( $user );
		$context->setLanguage( 'en' );
		$formatter = $this->getServiceContainer()->getLogFormatterFactory()->newFromRow( $row );
		$formatter->setContext( $context );
		$titleFactory = $this->createMock( TitleFactory::class );
		$titleFactory->method( 'makeTitle' )->willReturnCallback( static function ( ...$params ) {
			$ret = Title::makeTitle( ...$params );
			$ret->resetArticleID( 0 );
			return $ret;
		} );
		$this->setService( 'TitleFactory', $titleFactory );
		$formatter->setLinkRenderer( ( new LinkRendererFactory(
			$this->getServiceContainer()->getTitleFormatter(),
			$this->createMock( LinkCache::class ),
			$this->getServiceContainer()->getSpecialPageFactory(),
			$this->getServiceContainer()->getHookContainer()
		) )->create() );
		if ( $shouldMatch ) {
			$this->assertStringMatchesFormat(
				'%Aaction=protect%A', $formatter->getActionLinks() );
		} else {
			$this->assertStringNotMatchesFormat(
				'%Aaction=protect%A', $formatter->getActionLinks() );
		}
	}
}
