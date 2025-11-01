<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Logger\Monolog;

use AssertionError;
use InvalidArgumentException;
use LengthException;
use LogicException;
use MediaWiki\Logger\Monolog\LineFormatter;
use Wikimedia\TestingAccessWrapper;

class LineFormatterTest extends \MediaWikiUnitTestCase {

	protected function setUp(): void {
		parent::setUp();
		if ( !class_exists( \Monolog\Formatter\LineFormatter::class ) ) {
			$this->markTestSkipped( 'This test requires monolog to be installed' );
		}
	}

	/**
	 * @covers \MediaWiki\Logger\Monolog\LineFormatter::normalizeException
	 */
	public function testNormalizeExceptionNoTrace() {
		$fixture = new LineFormatter();
		$fixture->includeStacktraces( false );
		$fixture = TestingAccessWrapper::newFromObject( $fixture );
		$boom = new InvalidArgumentException( 'boom', 0,
			new LengthException( 'too long', 0,
				new LogicException( 'Spock wuz here' )
			)
		);
		$out = $fixture->normalizeException( $boom );
		$this->assertStringContainsString( "\n[Exception InvalidArgumentException]", $out );
		$this->assertStringContainsString( "\nCaused by: [Exception LengthException]", $out );
		$this->assertStringContainsString( "\nCaused by: [Exception LogicException]", $out );
		$this->assertStringNotContainsString( "\n  #0", $out );
	}

	/**
	 * @covers \MediaWiki\Logger\Monolog\LineFormatter::normalizeException
	 */
	public function testNormalizeExceptionTrace() {
		$fixture = new LineFormatter();
		$fixture->includeStacktraces( true );
		$fixture = TestingAccessWrapper::newFromObject( $fixture );
		$boom = new InvalidArgumentException( 'boom', 0,
			new LengthException( 'too long', 0,
				new LogicException( 'Spock wuz here' )
			)
		);
		$out = $fixture->normalizeException( $boom );
		$this->assertStringContainsString( "\n[Exception InvalidArgumentException]", $out );
		$this->assertStringContainsString( "\nCaused by: [Exception LengthException]", $out );
		$this->assertStringContainsString( "\nCaused by: [Exception LogicException]", $out );
		$this->assertStringContainsString( "\n  #0", $out );
	}

	/**
	 * @covers \MediaWiki\Logger\Monolog\LineFormatter::normalizeException
	 */
	public function testNormalizeExceptionErrorNoTrace() {
		if ( !class_exists( AssertionError::class ) ) {
			$this->markTestSkipped( 'AssertionError class does not exist' );
		}

		$fixture = new LineFormatter();
		$fixture->includeStacktraces( false );
		$fixture = TestingAccessWrapper::newFromObject( $fixture );
		$boom = new InvalidArgumentException( 'boom', 0,
			new LengthException( 'too long', 0,
				new AssertionError( 'Spock wuz here' )
			)
		);
		$out = $fixture->normalizeException( $boom );
		$this->assertStringContainsString( "\n[Exception InvalidArgumentException]", $out );
		$this->assertStringContainsString( "\nCaused by: [Exception LengthException]", $out );
		$this->assertStringContainsString( "\nCaused by: [Error AssertionError]", $out );
		$this->assertStringNotContainsString( "\n  #0", $out );
	}

	/**
	 * @covers \MediaWiki\Logger\Monolog\LineFormatter::normalizeException
	 */
	public function testNormalizeExceptionErrorTrace() {
		if ( !class_exists( AssertionError::class ) ) {
			$this->markTestSkipped( 'AssertionError class does not exist' );
		}

		$fixture = new LineFormatter();
		$fixture->includeStacktraces( true );
		$fixture = TestingAccessWrapper::newFromObject( $fixture );
		$boom = new InvalidArgumentException( 'boom', 0,
			new LengthException( 'too long', 0,
				new AssertionError( 'Spock wuz here' )
			)
		);
		$out = $fixture->normalizeException( $boom );
		$this->assertStringContainsString( "\n[Exception InvalidArgumentException]", $out );
		$this->assertStringContainsString( "\nCaused by: [Exception LengthException]", $out );
		$this->assertStringContainsString( "\nCaused by: [Error AssertionError]", $out );
		$this->assertStringContainsString( "\n  #0", $out );
	}
}
