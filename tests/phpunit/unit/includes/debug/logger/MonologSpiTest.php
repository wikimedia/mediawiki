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

use Wikimedia\TestingAccessWrapper;

class MonologSpiTest extends \MediaWikiUnitTestCase {

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

	/**
	 * @covers MediaWiki\Logger\MonologSpi::__construct
	 * @covers MediaWiki\Logger\MonologSpi::reset
	 * @covers MediaWiki\Logger\MonologSpi::getLogger
	 * @covers MediaWiki\Logger\MonologSpi::createLogger
	 * @covers MediaWiki\Logger\MonologSpi::getProcessor
	 * @covers MediaWiki\Logger\MonologSpi::getHandler
	 * @covers MediaWiki\Logger\MonologSpi::getFormatter
	 */
	public function testDefaultChannel() {
		$this->markTestSkipped( 'Broken on monolog 2.0' );
		$base = [
			'loggers' => [
				'@default' => [
					'processors' => [ 'myprocessor' ],
					'handlers' => [ 'myhandler' ],
				],
			],
			'processors' => [
				'myprocessor' => [
					'class' => Monolog\WikiProcessor::class,
				],
			],
			'handlers' => [
				'myhandler' => [
					'class' => \Monolog\Handler\NullHandler::class,
					'buffer' => true,
					'formatter' => 'myformatter',
				],
			],
			'formatters' => [
				'myformatter' => [
					'class' => \Monolog\Formatter\LineFormatter::class,
				],
			],
		];
		$monologSpi = new MonologSpi( $base );
		$logger = $monologSpi->getLogger( 'mychannel' );
		$wrapperMonologSpi = TestingAccessWrapper::newFromObject( $monologSpi );
		$this->assertInstanceOf( \Psr\Log\LoggerInterface::class, $logger );
		$this->assertInstanceOf( \Monolog\Logger::class, $logger );
		$this->assertCount( 1, $wrapperMonologSpi->singletons['loggers'] );
		$this->assertArrayHasKey( 'mychannel', $wrapperMonologSpi->singletons['loggers'] );
		$actualProcessors = $logger->getProcessors();
		$this->assertArrayHasKey( 0, $actualProcessors );
		$this->assertInstanceOf( Monolog\WikiProcessor::class, $actualProcessors[0] );
		$this->assertCount( 1, $wrapperMonologSpi->singletons['processors'] );
		$this->assertArrayHasKey( 'myprocessor', $wrapperMonologSpi->singletons['processors'] );
		$actualHandlers = $logger->getHandlers();
		$this->assertArrayHasKey( 0, $actualHandlers );
		$firstActualHandler = $actualHandlers[0];
		$this->assertInstanceOf( \Monolog\Handler\BufferHandler::class, $firstActualHandler );
		$this->assertCount( 1, $wrapperMonologSpi->singletons['handlers'] );
		$this->assertArrayHasKey( 'myhandler', $wrapperMonologSpi->singletons['handlers'] );
		$actualFormatter = $firstActualHandler->getFormatter();
		$this->assertInstanceOf( \Monolog\Formatter\LineFormatter::class, $actualFormatter );
		$this->assertCount( 1, $wrapperMonologSpi->singletons['formatters'] );
		$this->assertArrayHasKey( 'myformatter', $wrapperMonologSpi->singletons['formatters'] );
	}

	/**
	 * @covers MediaWiki\Logger\MonologSpi::createLogger
	 */
	public function testEmptyChannel() {
		$base = [
			'loggers' => [
				'@default' => [
					'handlers' => [ 'myhandler' ],
				],
				'emptychannel' => [],
			],
			'handlers' => [
				'myhandler' => [
					'class' => \Monolog\Handler\NullHandler::class,
					'buffer' => true,
					'formatter' => 'myformatter',
				],
			],
			'formatters' => [
				'myformatter' => [
					'class' => \Monolog\Formatter\LineFormatter::class,
				],
			],
		];
		$monologSpi = new MonologSpi( $base );
		$logger = $monologSpi->getLogger( 'emptychannel' );
		$wrapperMonologSpi = TestingAccessWrapper::newFromObject( $monologSpi );
		$this->assertInstanceOf( \Psr\Log\LoggerInterface::class, $logger );
		$this->assertInstanceOf( \Monolog\Logger::class, $logger );
		$this->assertCount( 1, $wrapperMonologSpi->singletons['loggers'] );
		$this->assertArrayHasKey( 'emptychannel', $wrapperMonologSpi->singletons['loggers'] );
		$actualHandlers = $logger->getHandlers();
		$this->assertCount( 1, $actualHandlers );
		$this->assertArrayHasKey( 0, $actualHandlers );
		$firstActualHandler = $actualHandlers[0];
		$this->assertInstanceOf( \Monolog\Handler\NullHandler::class, $firstActualHandler );
		$this->assertCount( 0, $wrapperMonologSpi->singletons['handlers'] );
		$this->assertCount( 0, $wrapperMonologSpi->singletons['formatters'] );
		$this->assertCount( 0, $wrapperMonologSpi->singletons['processors'] );
	}

}
