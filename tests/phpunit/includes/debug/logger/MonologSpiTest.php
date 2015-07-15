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
	 * @covers MonologSpi::mergeConfig
	 */
	public function testMergeConfig() {
		$base = array(
			'loggers' => array(
				'@default' => array(
					'processors' => array( 'constructor' ),
					'handlers' => array( 'constructor' ),
				),
			),
			'processors' => array(
				'constructor' => array(
					'class' => 'constructor',
				),
			),
			'handlers' => array(
				'constructor' => array(
					'class' => 'constructor',
					'formatter' => 'constructor',
				),
			),
			'formatters' => array(
				'constructor' => array(
					'class' => 'constructor',
				),
			),
		);

		$fixture = new MonologSpi( $base );
		$this->assertSame(
			$base,
			TestingAccessWrapper::newFromObject( $fixture )->config
		);

		$fixture->mergeConfig( array(
			'loggers' => array(
				'merged' => array(
					'processors' => array( 'merged' ),
					'handlers' => array( 'merged' ),
				),
			),
			'processors' => array(
				'merged' => array(
					'class' => 'merged',
				),
			),
			'magic' => array(
				'idkfa' => array( 'xyzzy' ),
			),
			'handlers' => array(
				'merged' => array(
					'class' => 'merged',
					'formatter' => 'merged',
				),
			),
			'formatters' => array(
				'merged' => array(
					'class' => 'merged',
				),
			),
		) );
		$this->assertSame(
			array(
				'loggers' => array(
					'@default' => array(
						'processors' => array( 'constructor' ),
						'handlers' => array( 'constructor' ),
					),
					'merged' => array(
						'processors' => array( 'merged' ),
						'handlers' => array( 'merged' ),
					),
				),
				'processors' => array(
					'constructor' => array(
						'class' => 'constructor',
					),
					'merged' => array(
						'class' => 'merged',
					),
				),
				'handlers' => array(
					'constructor' => array(
						'class' => 'constructor',
						'formatter' => 'constructor',
					),
					'merged' => array(
						'class' => 'merged',
						'formatter' => 'merged',
					),
				),
				'formatters' => array(
					'constructor' => array(
						'class' => 'constructor',
					),
					'merged' => array(
						'class' => 'merged',
					),
				),
				'magic' => array(
					'idkfa' => array( 'xyzzy' ),
				),
			),
			TestingAccessWrapper::newFromObject( $fixture )->config
		);
	}

}
