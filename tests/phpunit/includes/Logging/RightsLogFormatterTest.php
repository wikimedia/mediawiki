<?php
namespace MediaWiki\Tests\Logging;

use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LBFactory;

/**
 * @covers \MediaWiki\Logging\RightsLogFormatter
 */
class RightsLogFormatterTest extends LogFormatterTestCase {

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
	public static function provideRightsLogDatabaseRows() {
		return [
			// Current format
			[
				[
					'type' => 'rights',
					'action' => 'rights',
					'comment' => 'rights comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'User',
					'params' => [
						'4::oldgroups' => [],
						'5::newgroups' => [ 'sysop', 'bureaucrat' ],
						'oldmetadata' => [],
						'newmetadata' => [
							[ 'expiry' => null ],
							[ 'expiry' => '20160101123456' ]
						],
					],
				],
				[
					'text' => 'Sysop changed group membership for User: granted bureaucrat '
						. '(temporary, until 12:34, 1 January 2016) and administrator',
					'api' => [
						'oldgroups' => [],
						'newgroups' => [ 'sysop', 'bureaucrat' ],
						'oldmetadata' => [],
						'newmetadata' => [
							[ 'group' => 'sysop', 'expiry' => 'infinity' ],
							[ 'group' => 'bureaucrat', 'expiry' => '2016-01-01T12:34:56Z' ],
						],
					],
				],
			],
			[
				[
					'type' => 'rights',
					'action' => 'rights',
					'comment' => 'rights comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'User',
					'params' => [
						'4::oldgroups' => [ 'bot', 'sysop', 'interface-admin' ],
						'5::newgroups' => [ 'sysop', 'bureaucrat', 'interface-admin' ],
						'oldmetadata' => [
							[ 'expiry' => null ],
							[ 'expiry' => '20160203123456' ],
							[ 'expiry' => null ],
						],
						'newmetadata' => [
							[ 'expiry' => null ],
							[ 'expiry' => '20160101123456' ],
							[ 'expiry' => null ],
						],
					],
				],
				[
					'text' => 'Sysop changed group membership for User: granted bureaucrat '
						. '(temporary, until 12:34, 1 January 2016); revoked bot; changed '
						. 'expiration of administrator (permanent, was: until 12:34, '
						. '3 February 2016); kept interface administrator unchanged',
					'api' => [
						'oldgroups' => [ 'bot', 'sysop', 'interface-admin' ],
						'newgroups' => [ 'sysop', 'bureaucrat', 'interface-admin' ],
						'oldmetadata' => [
							[ 'group' => 'bot', 'expiry' => 'infinity' ],
							[ 'group' => 'sysop', 'expiry' => '2016-02-03T12:34:56Z' ],
							[ 'group' => 'interface-admin', 'expiry' => 'infinity' ],
						],
						'newmetadata' => [
							[ 'group' => 'sysop', 'expiry' => 'infinity' ],
							[ 'group' => 'bureaucrat', 'expiry' => '2016-01-01T12:34:56Z' ],
							[ 'group' => 'interface-admin', 'expiry' => 'infinity' ],
						],
					],
				],
			],
			[
				[
					'type' => 'rights',
					'action' => 'rights',
					'comment' => 'rights comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'User',
					'params' => [
						'4::oldgroups' => [ 'bot', 'sysop', 'bureaucrat', 'interface-admin' ],
						'5::newgroups' => [ 'bot', 'sysop', 'bureaucrat', 'interface-admin' ],
						'oldmetadata' => [
							[ 'expiry' => null ],
							[ 'expiry' => '20160203123456' ],
							[ 'expiry' => '20160304123456' ],
							[ 'expiry' => '20160506123456' ],
						],
						'newmetadata' => [
							[ 'expiry' => '20160102123456' ],
							[ 'expiry' => null ],
							[ 'expiry' => '20160405123456' ],
							[ 'expiry' => '20160506123456' ],
						],
					],
				],
				[
					'text' => 'Sysop changed group membership for User: changed expiration of '
						. 'bot (until 12:34, 2 January 2016, was: permanent), administrator '
						. '(permanent, was: until 12:34, 3 February 2016) and bureaucrat (until '
						. '12:34, 5 April 2016, was: 12:34, 4 March 2016); kept interface '
						. 'administrator (temporary, until 12:34, 6 May 2016) unchanged',
					'api' => [
						'oldgroups' => [ 'bot', 'sysop', 'bureaucrat', 'interface-admin' ],
						'newgroups' => [ 'bot', 'sysop', 'bureaucrat', 'interface-admin' ],
						'oldmetadata' => [
							[ 'group' => 'bot', 'expiry' => 'infinity' ],
							[ 'group' => 'sysop', 'expiry' => '2016-02-03T12:34:56Z' ],
							[ 'group' => 'bureaucrat', 'expiry' => '2016-03-04T12:34:56Z' ],
							[ 'group' => 'interface-admin', 'expiry' => '2016-05-06T12:34:56Z' ],
						],
						'newmetadata' => [
							[ 'group' => 'bot', 'expiry' => '2016-01-02T12:34:56Z' ],
							[ 'group' => 'sysop', 'expiry' => 'infinity' ],
							[ 'group' => 'bureaucrat', 'expiry' => '2016-04-05T12:34:56Z' ],
							[ 'group' => 'interface-admin', 'expiry' => '2016-05-06T12:34:56Z' ],
						],
					],
				],
			],

			// Previous format (oldgroups and newgroups as arrays, no metadata)
			[
				[
					'type' => 'rights',
					'action' => 'rights',
					'comment' => 'rights comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'User',
					'params' => [
						'4::oldgroups' => [],
						'5::newgroups' => [ 'sysop', 'bureaucrat' ],
					],
				],
				[
					'text' => 'Sysop changed group membership for User: granted '
						. 'administrator and bureaucrat',
					'api' => [
						'oldgroups' => [],
						'newgroups' => [ 'sysop', 'bureaucrat' ],
						'oldmetadata' => [],
						'newmetadata' => [
							[ 'group' => 'sysop', 'expiry' => 'infinity' ],
							[ 'group' => 'bureaucrat', 'expiry' => 'infinity' ],
						],
					],
				],
			],

			// Legacy format (oldgroups and newgroups as numeric-keyed strings)
			[
				[
					'type' => 'rights',
					'action' => 'rights',
					'comment' => 'rights comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'User',
					'params' => [
						'',
						'sysop, bureaucrat',
					],
				],
				[
					'legacy' => true,
					'text' => 'Sysop changed group membership for User: granted '
						. 'administrator and bureaucrat',
					'api' => [
						'oldgroups' => [],
						'newgroups' => [ 'sysop', 'bureaucrat' ],
						'oldmetadata' => [],
						'newmetadata' => [
							[ 'group' => 'sysop', 'expiry' => 'infinity' ],
							[ 'group' => 'bureaucrat', 'expiry' => 'infinity' ],
						],
					],
				],
			],

			// Really old entry
			[
				[
					'type' => 'rights',
					'action' => 'rights',
					'comment' => 'rights comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'User',
					'params' => [],
				],
				[
					'legacy' => true,
					'text' => 'Sysop changed group membership for User',
					'api' => [],
				],
			],
		];
	}

	/**
	 * @dataProvider provideRightsLogDatabaseRows
	 */
	public function testRightsLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}

	/**
	 * Provide different rows from the logging table to test
	 * for backward compatibility.
	 * Do not change the existing data, just add a new database row
	 */
	public static function provideAutopromoteLogDatabaseRows() {
		return [
			// Current format
			[
				[
					'type' => 'rights',
					'action' => 'autopromote',
					'comment' => 'rights comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'Sysop',
					'params' => [
						'4::oldgroups' => [ 'sysop' ],
						'5::newgroups' => [ 'sysop', 'bureaucrat' ],
					],
				],
				[
					'text' => 'Sysop automatically changed their group membership: '
						. 'got bureaucrat; kept administrator unchanged',
					'api' => [
						'oldgroups' => [ 'sysop' ],
						'newgroups' => [ 'sysop', 'bureaucrat' ],
						'oldmetadata' => [
							[ 'group' => 'sysop', 'expiry' => 'infinity' ],
						],
						'newmetadata' => [
							[ 'group' => 'sysop', 'expiry' => 'infinity' ],
							[ 'group' => 'bureaucrat', 'expiry' => 'infinity' ],
						],
					],
				],
			],
			[
				// At the moment it's impossible to lose a group through autopromotion,
				// but it's checked for completeness
				[
					'type' => 'rights',
					'action' => 'autopromote',
					'comment' => 'rights comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'User',
					'params' => [
						'4::oldgroups' => [ 'bot', 'sysop', 'interface-admin' ],
						'5::newgroups' => [ 'sysop', 'bureaucrat', 'interface-admin' ],
						'oldmetadata' => [
							[ 'expiry' => null ],
							[ 'expiry' => '20160203123456' ],
							[ 'expiry' => null ],
						],
						'newmetadata' => [
							[ 'expiry' => null ],
							[ 'expiry' => '20160101123456' ],
							[ 'expiry' => null ],
						],
					],
				],
				[
					'text' => 'Sysop automatically changed their group membership: got bureaucrat '
						. '(temporary, until 12:34, 1 January 2016); lost bot; changed '
						. 'expiration of administrator (permanent, was: until 12:34, '
						. '3 February 2016); kept interface administrator unchanged',
					'api' => [
						'oldgroups' => [ 'bot', 'sysop', 'interface-admin' ],
						'newgroups' => [ 'sysop', 'bureaucrat', 'interface-admin' ],
						'oldmetadata' => [
							[ 'group' => 'bot', 'expiry' => 'infinity' ],
							[ 'group' => 'sysop', 'expiry' => '2016-02-03T12:34:56Z' ],
							[ 'group' => 'interface-admin', 'expiry' => 'infinity' ],
						],
						'newmetadata' => [
							[ 'group' => 'sysop', 'expiry' => 'infinity' ],
							[ 'group' => 'bureaucrat', 'expiry' => '2016-01-01T12:34:56Z' ],
							[ 'group' => 'interface-admin', 'expiry' => 'infinity' ],
						],
					],
				],
			],

			// Legacy format
			[
				[
					'type' => 'rights',
					'action' => 'autopromote',
					'comment' => 'rights comment',
					'user' => 0,
					'user_text' => 'Sysop',
					'namespace' => NS_USER,
					'title' => 'Sysop',
					'params' => [
						'sysop',
						'sysop, bureaucrat',
					],
				],
				[
					'legacy' => true,
					'text' => 'Sysop automatically changed their group membership: '
						. 'got bureaucrat; kept administrator unchanged',
					'api' => [
						'oldgroups' => [ 'sysop' ],
						'newgroups' => [ 'sysop', 'bureaucrat' ],
						'oldmetadata' => [
							[ 'group' => 'sysop', 'expiry' => 'infinity' ],
						],
						'newmetadata' => [
							[ 'group' => 'sysop', 'expiry' => 'infinity' ],
							[ 'group' => 'bureaucrat', 'expiry' => 'infinity' ],
						],
					],
				],
			],
		];
	}

	/**
	 * @dataProvider provideAutopromoteLogDatabaseRows
	 */
	public function testAutopromoteLogDatabaseRows( $row, $extra ) {
		$this->doTestLogFormatter( $row, $extra );
	}
}
