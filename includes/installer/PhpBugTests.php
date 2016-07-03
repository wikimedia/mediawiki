<?php
/**
 * Classes for self-contained tests for known bugs in PHP.
 *
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
 * @defgroup PHPBugTests PHP known bugs tests
 */

/**
 * Test for PHP+libxml2 bug which breaks XML input subtly with certain versions.
 * Known fixed with PHP 5.2.9 + libxml2-2.7.3
 * @see http://bugs.php.net/bug.php?id=45996
 * @ingroup PHPBugTests
 */
class PhpXmlBugTester {
	private $parsedData = '';
	public $ok = false;

	public function __construct() {
		$charData = '<b>c</b>';
		$xml = '<a>' . htmlspecialchars( $charData ) . '</a>';

		$parser = xml_parser_create();
		xml_set_character_data_handler( $parser, [ $this, 'chardata' ] );
		$parsedOk = xml_parse( $parser, $xml, true );
		$this->ok = $parsedOk && ( $this->parsedData == $charData );
	}

	public function chardata( $parser, $data ) {
		$this->parsedData .= $data;
	}
}
