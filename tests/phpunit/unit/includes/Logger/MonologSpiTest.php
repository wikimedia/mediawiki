<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Logger;

use MediaWiki\Logger\Monolog\WikiProcessor;
use MediaWiki\Logger\MonologSpi;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Logger\MonologSpi
 */
class MonologSpiTest extends \MediaWikiUnitTestCase {

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

	public function testDefaultChannel() {
		$base = [
			'loggers' => [
				'@default' => [
					'processors' => [ 'myprocessor' ],
					'handlers' => [ 'myhandler' ],
				],
			],
			'processors' => [
				'myprocessor' => [
					'class' => WikiProcessor::class,
				],
			],
			'handlers' => [
				'myhandler' => [
					'class' => \Monolog\Handler\NullHandler::class,
				],
			],
			'formatters' => [
			],
		];
		$monologSpi = new MonologSpi( $base );
		$logger = $monologSpi->getLogger( 'mychannel' );
		$wrapperMonologSpi = TestingAccessWrapper::newFromObject( $monologSpi );
		$this->assertInstanceOf( \Psr\Log\LoggerInterface::class, $logger );
		$this->assertCount( 1, $wrapperMonologSpi->singletons['loggers'] );
		$this->assertArrayHasKey( 'mychannel', $wrapperMonologSpi->singletons['loggers'] );

		$actualProcessors = $logger->getProcessors();
		$this->assertArrayHasKey( 0, $actualProcessors );
		$this->assertInstanceOf( WikiProcessor::class, $actualProcessors[0] );
		$this->assertCount( 1, $wrapperMonologSpi->singletons['processors'] );
		$this->assertArrayHasKey( 'myprocessor', $wrapperMonologSpi->singletons['processors'] );

		$actualHandlers = $logger->getHandlers();
		$this->assertArrayHasKey( 0, $actualHandlers );
		$firstActualHandler = $actualHandlers[0];
		$this->assertInstanceOf( \Monolog\Handler\NullHandler::class, $firstActualHandler );
		$this->assertCount( 1, $wrapperMonologSpi->singletons['handlers'] );
		$this->assertArrayHasKey( 'myhandler', $wrapperMonologSpi->singletons['handlers'] );

		$this->assertCount( 0, $wrapperMonologSpi->singletons['formatters'] );
	}

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
		$this->assertCount( 1, $wrapperMonologSpi->singletons['loggers'] );
		$this->assertArrayHasKey( 'emptychannel', $wrapperMonologSpi->singletons['loggers'] );
		$actualHandlers = $logger->getHandlers();
		$this->assertCount( 0, $actualHandlers );
		$this->assertCount( 0, $wrapperMonologSpi->singletons['handlers'] );
		$this->assertCount( 0, $wrapperMonologSpi->singletons['formatters'] );
		$this->assertCount( 0, $wrapperMonologSpi->singletons['processors'] );
	}

}
