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

namespace MediaWiki\Logger\Monolog;

use MediaWikiTestCase;
use Monolog\Logger;

class KafkaHandlerTest extends MediaWikiTestCase {

	protected function setUp() {
		if ( !class_exists( 'Monolog\Handler\AbstractProcessingHandler' )
			|| !class_exists( 'Kafka\Produce' )
		) {
			$this->markTestSkipped( 'Monolog and Kafka are required for the KafkaHandlerTest' );
		}

		parent::setUp();
	}

	public function topicNamingProvider() {
		return [
			[ [], 'monolog_foo' ],
			[ [ 'alias' => [ 'foo' => 'bar' ] ], 'bar' ]
		];
	}

	/**
	 * @dataProvider topicNamingProvider
	 */
	public function testTopicNaming( $options, $expect ) {
		$produce = $this->getMockBuilder( 'Kafka\Produce' )
			->disableOriginalConstructor()
			->getMock();
		$produce->expects( $this->any() )
			->method( 'getAvailablePartitions' )
			->will( $this->returnValue( [ 'A' ] ) );
		$produce->expects( $this->once() )
			->method( 'setMessages' )
			->with( $expect, $this->anything(), $this->anything() );
		$produce->expects( $this->any() )
			->method( 'send' )
			->will( $this->returnValue( true ) );

		$handler = new KafkaHandler( $produce, $options );
		$handler->handle( [
			'channel' => 'foo',
			'level' => Logger::EMERGENCY,
			'extra' => [],
			'context' => [],
		] );
	}

	public function swallowsExceptionsWhenRequested() {
		return [
			// defaults to false
			[ [], true ],
			// also try false explicitly
			[ [ 'swallowExceptions' => false ], true ],
			// turn it on
			[ [ 'swallowExceptions' => true ], false ],
		];
	}

	/**
	 * @dataProvider swallowsExceptionsWhenRequested
	 */
	public function testGetAvailablePartitionsException( $options, $expectException ) {
		$produce = $this->getMockBuilder( 'Kafka\Produce' )
			->disableOriginalConstructor()
			->getMock();
		$produce->expects( $this->any() )
			->method( 'getAvailablePartitions' )
			->will( $this->throwException( new \Kafka\Exception ) );
		$produce->expects( $this->any() )
			->method( 'send' )
			->will( $this->returnValue( true ) );

		if ( $expectException ) {
			$this->setExpectedException( 'Kafka\Exception' );
		}

		$handler = new KafkaHandler( $produce, $options );
		$handler->handle( [
			'channel' => 'foo',
			'level' => Logger::EMERGENCY,
			'extra' => [],
			'context' => [],
		] );

		if ( !$expectException ) {
			$this->assertTrue( true, 'no exception was thrown' );
		}
	}

	/**
	 * @dataProvider swallowsExceptionsWhenRequested
	 */
	public function testSendException( $options, $expectException ) {
		$produce = $this->getMockBuilder( 'Kafka\Produce' )
			->disableOriginalConstructor()
			->getMock();
		$produce->expects( $this->any() )
			->method( 'getAvailablePartitions' )
			->will( $this->returnValue( [ 'A' ] ) );
		$produce->expects( $this->any() )
			->method( 'send' )
			->will( $this->throwException( new \Kafka\Exception ) );

		if ( $expectException ) {
			$this->setExpectedException( 'Kafka\Exception' );
		}

		$handler = new KafkaHandler( $produce, $options );
		$handler->handle( [
			'channel' => 'foo',
			'level' => Logger::EMERGENCY,
			'extra' => [],
			'context' => [],
		] );

		if ( !$expectException ) {
			$this->assertTrue( true, 'no exception was thrown' );
		}
	}

	public function testHandlesNullFormatterResult() {
		$produce = $this->getMockBuilder( 'Kafka\Produce' )
			->disableOriginalConstructor()
			->getMock();
		$produce->expects( $this->any() )
			->method( 'getAvailablePartitions' )
			->will( $this->returnValue( [ 'A' ] ) );
		$mockMethod = $produce->expects( $this->exactly( 2 ) )
			->method( 'setMessages' );
		$produce->expects( $this->any() )
			->method( 'send' )
			->will( $this->returnValue( true ) );
		// evil hax
		\TestingAccessWrapper::newFromObject( $mockMethod )->matcher->parametersMatcher =
			new \PHPUnit_Framework_MockObject_Matcher_ConsecutiveParameters( [
				[ $this->anything(), $this->anything(), [ 'words' ] ],
				[ $this->anything(), $this->anything(), [ 'lines' ] ]
			] );

		$formatter = $this->getMock( 'Monolog\Formatter\FormatterInterface' );
		$formatter->expects( $this->any() )
			->method( 'format' )
			->will( $this->onConsecutiveCalls( 'words', null, 'lines' ) );

		$handler = new KafkaHandler( $produce, [] );
		$handler->setFormatter( $formatter );
		for ( $i = 0; $i < 3; ++$i ) {
			$handler->handle( [
				'channel' => 'foo',
				'level' => Logger::EMERGENCY,
				'extra' => [],
				'context' => [],
			] );
		}
	}

	public function testBatchHandlesNullFormatterResult() {
		$produce = $this->getMockBuilder( 'Kafka\Produce' )
			->disableOriginalConstructor()
			->getMock();
		$produce->expects( $this->any() )
			->method( 'getAvailablePartitions' )
			->will( $this->returnValue( [ 'A' ] ) );
		$produce->expects( $this->once() )
			->method( 'setMessages' )
			->with( $this->anything(), $this->anything(), [ 'words', 'lines' ] );
		$produce->expects( $this->any() )
			->method( 'send' )
			->will( $this->returnValue( true ) );

		$formatter = $this->getMock( 'Monolog\Formatter\FormatterInterface' );
		$formatter->expects( $this->any() )
			->method( 'format' )
			->will( $this->onConsecutiveCalls( 'words', null, 'lines' ) );

		$handler = new KafkaHandler( $produce, [] );
		$handler->setFormatter( $formatter );
		$handler->handleBatch( [
			[
				'channel' => 'foo',
				'level' => Logger::EMERGENCY,
				'extra' => [],
				'context' => [],
			],
			[
				'channel' => 'foo',
				'level' => Logger::EMERGENCY,
				'extra' => [],
				'context' => [],
			],
			[
				'channel' => 'foo',
				'level' => Logger::EMERGENCY,
				'extra' => [],
				'context' => [],
			],
		] );
	}
}
