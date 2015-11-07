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

class LogstashFormatterTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var LogstashFormatter
	 */
	protected $fixture;

	public function setUp() {
		parent::setUp();
		$this->fixture = new LogstashFormatter( 'test' );
	}

	/**
	 * @param array $given
	 * @param string $expect
	 * @requires function mb_detect_encoding
	 * @dataProvider providesIconv
	 */
	public function testIconv( $given, $expect ) {
		$this->assertContains( $expect, $this->fixture->format( $given ) );
	}

	public function providesIconv() {
		return array(
			'ACSII' => array(
				array( 'message' => 'foo bar baz' ),
				'foo bar baz',
			),
			'ISO-8859-1' => array(
				unpack( 'a*message', "\x4f\x72\x61\x6e\x67\x65\x45\x73\x70\x61\xf1\x61" ),
				'OrangeEspaña',
			),
			'UTF-8' => array(
				array( 'message' => 'OrangeEspaña' ),
				'OrangeEspaña',
			),
		);
	}

	/**
	 * @param array $given
	 * @param string $expect
	 * @dataProvider providesHtmlentites
	 */
	public function testHtmlentites( $given, $expect ) {
		$this->fixture->useIconv( false );
		$this->assertContains( $expect, $this->fixture->format( $given ) );
	}

	public function providesHtmlentites() {
		return array(
			'ACSII' => array(
				array( 'message' => 'foo bar baz' ),
				'foo bar baz',
			),
			'ISO-8859-1' => array(
				unpack( 'a*message', "\x4f\x72\x61\x6e\x67\x65\x45\x73\x70\x61\xf1\x61" ),
				'OrangeEspaa',
			),
			'UTF-8' => array(
				array( 'message' => 'OrangeEspaña' ),
				'OrangeEspaña',
			),
		);
	}
}
