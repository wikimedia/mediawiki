<?php
/**
 *
 *
 * Created on Oct 22, 2006
 *
 * Copyright © 2006 Yuri Astrakhan <Firstname><Lastname>@gmail.com
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
 */

/**
 * API WDDX output formatter
 * @ingroup API
 */
class ApiFormatWddx extends ApiFormatBase {

	public function __construct( $main, $format ) {
		parent::__construct( $main, $format );
	}

	public function getMimeType() {
		return 'text/xml';
	}

	public function execute() {
		// Some versions of PHP have a broken wddx_serialize_value, see
		// PHP bug 45314. Test encoding an affected character (U+00A0)
		// to avoid this.
		$expected = "<wddxPacket version='1.0'><header/><data><string>\xc2\xa0</string></data></wddxPacket>";
		if ( function_exists( 'wddx_serialize_value' )
				&& !$this->getIsHtml()
				&& wddx_serialize_value( "\xc2\xa0" ) == $expected ) {
			$this->printText( wddx_serialize_value( $this->getResultData() ) );
		} else {
			// Don't do newlines and indentation if we weren't asked
			// for pretty output
			$nl = ( $this->getIsHtml() ? '' : "\n" );
			$indstr = ' ';
			$this->printText( "<?xml version=\"1.0\"?>$nl" );
			$this->printText( "<wddxPacket version=\"1.0\">$nl" );
			$this->printText( "$indstr<header/>$nl" );
			$this->printText( "$indstr<data>$nl" );
			$this->slowWddxPrinter( $this->getResultData(), 4 );
			$this->printText( "$indstr</data>$nl" );
			$this->printText( "</wddxPacket>$nl" );
		}
	}

	/**
	 * Recursively go through the object and output its data in WDDX format.
	 * @param $elemValue
	 * @param $indent int
	 */
	function slowWddxPrinter( $elemValue, $indent = 0 ) {
		$indstr = ( $this->getIsHtml() ? '' : str_repeat( ' ', $indent ) );
		$indstr2 = ( $this->getIsHtml() ? '' : str_repeat( ' ', $indent + 2 ) );
		$nl = ( $this->getIsHtml() ? '' : "\n" );
		switch ( gettype( $elemValue ) ) {
			case 'array':
				// Check whether we've got an associative array (<struct>)
				// or a regular array (<array>)
				$cnt = count( $elemValue );
				if ( $cnt == 0 || array_keys( $elemValue ) === range( 0, $cnt - 1 ) ) {
					// Regular array
					$this->printText( $indstr . Xml::element( 'array', array(
						'length' => $cnt ), null ) . $nl );
					foreach ( $elemValue as $subElemValue ) {
						$this->slowWddxPrinter( $subElemValue, $indent + 2 );
					}
					$this->printText( "$indstr</array>$nl" );
				} else {
					// Associative array (<struct>)
					$this->printText( "$indstr<struct>$nl" );
					foreach ( $elemValue as $subElemName => $subElemValue ) {
						$this->printText( $indstr2 . Xml::element( 'var', array(
							'name' => $subElemName
						), null ) . $nl );
						$this->slowWddxPrinter( $subElemValue, $indent + 4 );
						$this->printText( "$indstr2</var>$nl" );
					}
					$this->printText( "$indstr</struct>$nl" );
				}
				break;
			case 'integer':
			case 'double':
				$this->printText( $indstr . Xml::element( 'number', null, $elemValue ) . $nl );
				break;
			case 'string':
				$this->printText( $indstr . Xml::element( 'string', null, $elemValue ) . $nl );
				break;
			default:
				ApiBase::dieDebug( __METHOD__, 'Unknown type ' . gettype( $elemValue ) );
		}
	}

	public function getDescription() {
		return 'Output data in WDDX format' . parent::getDescription();
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
