<?php

/**
 * @covers DnsSrvDiscoverer
 */
class DnsSrvDiscovererTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	/**
	 * @dataProvider provideRecords
	 */
	public function testPickServer( $params, $expected ) {
		$discoverer = new DnsSrvDiscoverer( 'etcd-tcp.example.net' );
		$record = $discoverer->pickServer( $params );

		$this->assertEquals( $expected, $record );
	}

	public static function provideRecords() {
		return [
			[
				[ // record list
					[
						'target' => 'conf03.example.net',
						'port' => 'SRV',
						'pri' => 0,
						'weight' => 1,
					],
					[
						'target' => 'conf02.example.net',
						'port' => 'SRV',
						'pri' => 1,
						'weight' => 1,
					],
					[
						'target' => 'conf01.example.net',
						'port' => 'SRV',
						'pri' => 2,
						'weight' => 1,
					],
				], // selected record
				[
					'target' => 'conf03.example.net',
					'port' => 'SRV',
					'pri' => 0,
					'weight' => 1,
				]
			],
			[
				[ // record list
					[
						'target' => 'conf03or2.example.net',
						'port' => 'SRV',
						'pri' => 0,
						'weight' => 1,
					],
					[
						'target' => 'conf03or2.example.net',
						'port' => 'SRV',
						'pri' => 0,
						'weight' => 1,
					],
					[
						'target' => 'conf01.example.net',
						'port' => 'SRV',
						'pri' => 2,
						'weight' => 1,
					],
					[
						'target' => 'conf04.example.net',
						'port' => 'SRV',
						'pri' => 2,
						'weight' => 1,
					],
					[
						'target' => 'conf05.example.net',
						'port' => 'SRV',
						'pri' => 3,
						'weight' => 1,
					],
				], // selected record
				[
					'target' => 'conf03or2.example.net',
					'port' => 'SRV',
					'pri' => 0,
					'weight' => 1,
				]
			],
		];
	}

	public function testRemoveServer() {
		$dsd = new DnsSrvDiscoverer( 'localhost' );

		$servers = [
			[
				'target' => 'conf01.example.net',
				'port' => 35,
				'pri' => 2,
				'weight' => 1,
			],
			[
				'target' => 'conf04.example.net',
				'port' => 74,
				'pri' => 2,
				'weight' => 1,
			],
			[
				'target' => 'conf05.example.net',
				'port' => 77,
				'pri' => 3,
				'weight' => 1,
			],
		];
		$server = $servers[1];

		$expected = [
			[
				'target' => 'conf01.example.net',
				'port' => 35,
				'pri' => 2,
				'weight' => 1,
			],
			[
				'target' => 'conf05.example.net',
				'port' => 77,
				'pri' => 3,
				'weight' => 1,
			],
		];

		$this->assertEquals(
			$expected,
			$dsd->removeServer( $server, $servers ),
			"Correct server removed"
		);
		$this->assertEquals(
			$expected,
			$dsd->removeServer( $server, $servers ),
			"Nothing to remove"
		);
	}
}
