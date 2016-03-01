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
use PHPUnit_Framework_Error_Notice;

class AvroFormatterTest extends MediaWikiTestCase {

	protected function setUp() {
		if ( !class_exists( 'AvroStringIO' ) ) {
			$this->markTestSkipped( 'Avro is required for the AvroFormatterTest' );
		}
		parent::setUp();
	}

	public function testSchemaNotAvailable() {
		$formatter = new AvroFormatter( [] );
		$this->setExpectedException(
			'PHPUnit_Framework_Error_Notice',
			"The schema for channel 'marty' is not available"
		);
		$formatter->format( [ 'channel' => 'marty' ] );
	}

	public function testSchemaNotAvailableReturnValue() {
		$formatter = new AvroFormatter( [] );
		$noticeEnabled = PHPUnit_Framework_Error_Notice::$enabled;
		// disable conversion of notices
		PHPUnit_Framework_Error_Notice::$enabled = false;
		// have to keep the user notice from being output
		wfSuppressWarnings();
		$res = $formatter->format( [ 'channel' => 'marty' ] );
		wfRestoreWarnings();
		PHPUnit_Framework_Error_Notice::$enabled = $noticeEnabled;
		$this->assertNull( $res );
	}

	public function testDoesSomethingWhenSchemaAvailable() {
		$formatter = new AvroFormatter( [
			'string' => [
				'schema' => json_encode( [ 'type' => 'string' ] ),
				'revision' => 1010101,
			]
		] );
		$res = $formatter->format( [
			'channel' => 'string',
			'context' => 'better to be',
		] );
		$this->assertNotNull( $res );
		// basically just tell us if avro changes its string encoding, or if
		// we completely fail to generate a log message.
		$this->assertEquals( 'AAAAAAAAD2m1GGJldHRlciB0byBiZQ==', base64_encode( $res ) );
	}

	public function testReadsAvroProtocols() {
		$protocol = json_encode( [
			'protocol' => 'UnitTest',
			'namespace' => 'org.wikimedia.unittest',
			'types' => [
				[
					'type' => 'record',
					'namespae' => null,
					'name' => 'Foo',
					'fields' => [
						[ 'name' => 'bar', 'type' => 'string' ],
					],
				]
			],
			'messages' => [],
		] );
		$formatter = new AvroFormatter( [
			'Foo' => [
				'protocol' => $protocol,
				'schema' => 'org.wikimedia.unittest.Foo',
				'revision' => 1010101,
			]
		] );
		$res = $formatter->format( [
			'channel' => 'Foo',
			'context' => [ 'bar' => 'bigger than the other' ],
		] );
		// This doesn't on its own say much other than that avro has a consistent encoding,
		// but it would also tell us if we somehow broke avro and it returned something but
		// not what it was supposed to.
		$this->assertEquals( 'AAAAAAAAD2m1KmJpZ2dlciB0aGFuIHRoZSBvdGhlcg==', base64_encode( $res ) );
	}
}
