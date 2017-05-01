<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Logger;

use MediaWikiTestCase;
use TestingAccessWrapper;

class MonologSpiTest extends MediaWikiTestCase {

	/**
	 * @covers MediaWiki\Logger\MonologSpi::mergeConfig
	 */
	public function testMergeConfig() {
		$base = [
			'loggers' => [
				'@default' => [
					'processors' => [ 'constructor' ],
					'handlers' => [ 'constructor' ],
				],
			],
			'processors' => [
				'constructor' => [
					'class' => 'constructor',
				],
			],
			'handlers' => [
				'constructor' => [
					'class' => 'constructor',
					'formatter' => 'constructor',
				],
			],
			'formatters' => [
				'constructor' => [
					'class' => 'constructor',
				],
			],
		];

		$fixture = new MonologSpi( $base );
		$this->assertSame(
			$base,
			TestingAccessWrapper::newFromObject( $fixture )->config
		);

		$fixture->mergeConfig( [
			'loggers' => [
				'merged' => [
					'processors' => [ 'merged' ],
					'handlers' => [ 'merged' ],
				],
			],
			'processors' => [
				'merged' => [
					'class' => 'merged',
				],
			],
			'magic' => [
				'idkfa' => [ 'xyzzy' ],
			],
			'handlers' => [
				'merged' => [
					'class' => 'merged',
					'formatter' => 'merged',
				],
			],
			'formatters' => [
				'merged' => [
					'class' => 'merged',
				],
			],
		] );
		$this->assertSame(
			[
				'loggers' => [
					'@default' => [
						'processors' => [ 'constructor' ],
						'handlers' => [ 'constructor' ],
					],
					'merged' => [
						'processors' => [ 'merged' ],
						'handlers' => [ 'merged' ],
					],
				],
				'processors' => [
					'constructor' => [
						'class' => 'constructor',
					],
					'merged' => [
						'class' => 'merged',
					],
				],
				'handlers' => [
					'constructor' => [
						'class' => 'constructor',
						'formatter' => 'constructor',
					],
					'merged' => [
						'class' => 'merged',
						'formatter' => 'merged',
					],
				],
				'formatters' => [
					'constructor' => [
						'class' => 'constructor',
					],
					'merged' => [
						'class' => 'merged',
					],
				],
				'magic' => [
					'idkfa' => [ 'xyzzy' ],
				],
			],
			TestingAccessWrapper::newFromObject( $fixture )->config
		);
	}

}
