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

// not available in the version of phpunit mw uses, so copied into repo
require_once __DIR__ . '/../../../ConsecutiveParametersMatcher.php';

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
		return array(
			array( array(), 'monolog_foo' ),
			array( array( 'alias' => array( 'foo' => 'bar' ) ), 'bar' )
		);
	}

	/**
	 * @dataProvider topicNamingProvider
	 */
	public function testTopicNaming( $options, $expect ) {
		$produce = $this->getMockBuilder( 'Kafka\Produce' )
			->disableOriginalConstructor()
			->getMock();
		$produce->expects($this->any())
			->method('getAvailablePartitions')
			->will($this->returnValue( array( 'A' ) ) );
		$produce->expects($this->once())
			->method( 'setMessages' )
			->with( $expect, $this->anything(), $this->anything() );

		$handler = new KafkaHandler( $produce, $options );
		$handler->handle( array(
			'channel' => 'foo',
			'level' => Logger::EMERGENCY,
			'extra' => array(),
		) );
	}

	public function swallowsExceptionsWhenRequested() {
		return array(
			// defaults to false
			array( array(), true ),
			// also try false explicitly
			array( array( 'swallowExceptions' => false ), true ),
			// turn it on
			array( array( 'swallowExceptions' => true ), false ),
		);
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

		if ( $expectException ) {
			$this->setExpectedException( 'Kafka\Exception' );
		}

		$handler = new KafkaHandler( $produce, $options );
		$handler->handle( array(
			'channel' => 'foo',
			'level' => Logger::EMERGENCY,
			'extra' => array(),
		) );

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
			->will( $this->returnValue( array( 'A' ) ) );
		$produce->expects( $this->any() )
			->method( 'send' )
			->will( $this->throwException( new \Kafka\Exception ) );

		if ( $expectException ) {
			$this->setExpectedException( 'Kafka\Exception' );
		}

		$handler = new KafkaHandler( $produce, $options );
		$handler->handle( array(
			'channel' => 'foo',
			'level' => Logger::EMERGENCY,
			'extra' => array(),
		) );

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
			->will( $this->returnValue( array( 'A' ) ) );
		$mockMethod = $produce->expects( $this->exactly( 2 ) )
			->method( 'setMessages' );
		// evil hax
		\TestingAccessWrapper::newFromObject( $mockMethod )->matcher->parametersMatcher =
			new \PHPUnit_Framework_MockObject_Matcher_ConsecutiveParameters( array(
				array( $this->anything(), $this->anything(), array( 'words' ) ),
				array( $this->anything(), $this->anything(), array( 'lines' ) )
			) );

		$formatter = $this->getMock( 'Monolog\Formatter\FormatterInterface' );
		$formatter->expects( $this->any() )
			->method( 'format' )
			->will( $this->onConsecutiveCalls( 'words', null, 'lines' ) );

		$handler = new KafkaHandler( $produce, array() );
		$handler->setFormatter( $formatter );
		for ( $i = 0; $i < 3; ++$i ) {
			$handler->handle( array(
				'channel' => 'foo',
				'level' => Logger::EMERGENCY,
				'extra' => array(),
			) );
		}
	}


	public function testBatchHandlesNullFormatterResult() {
		$produce = $this->getMockBuilder( 'Kafka\Produce' )
			->disableOriginalConstructor()
			->getMock();
		$produce->expects( $this->any() )
			->method( 'getAvailablePartitions' )
			->will( $this->returnValue( array( 'A' ) ) );
		$produce->expects( $this->once() )
			->method( 'setMessages' )
			->with( $this->anything(), $this->anything(), array( 'words', 'lines' ) );

		$formatter = $this->getMock( 'Monolog\Formatter\FormatterInterface' );
		$formatter->expects( $this->any() )
			->method( 'format' )
			->will( $this->onConsecutiveCalls( 'words', null, 'lines' ) );

		$handler = new KafkaHandler( $produce, array() );
		$handler->setFormatter( $formatter );
		$handler->handleBatch( array(
			array(
				'channel' => 'foo',
				'level' => Logger::EMERGENCY,
				'extra' => array(),
			),
			array(
				'channel' => 'foo',
				'level' => Logger::EMERGENCY,
				'extra' => array(),
			),
			array(
				'channel' => 'foo',
				'level' => Logger::EMERGENCY,
				'extra' => array(),
			),
		) );
	}
}
