<?php
/**
 *
 *
 * Created on Oct 22, 2006
 *
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
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
 * @deprecated since 1.24
 * @ingroup API
 */
class ApiFormatWddx extends ApiFormatBase {

	public function getMimeType() {
		return 'text/xml';
	}

	public function execute() {
		$this->markDeprecated();

		$data = $this->getResult()->getResultData( null, array(
			'BC' => array(),
			'Types' => array( 'AssocAsObject' => true ),
			'Strip' => 'all',
		) );

		if ( !$this->getIsHtml() && !static::useSlowPrinter() ) {
			$txt = wddx_serialize_value( $data );
			$txt = str_replace(
				'<struct><var name=\'php_class_name\'><string>stdClass</string></var>',
				'<struct>',
				$txt
			);
			$this->printText( $txt );
		} else {
			// Don't do newlines and indentation if we weren't asked
			// for pretty output
			$nl = ( $this->getIsHtml() ? "\n" : '' );
			$indstr = ( $this->getIsHtml() ? ' ' : '' );
			$this->printText( "<?xml version=\"1.0\"?>$nl" );
			$this->printText( "<wddxPacket version=\"1.0\">$nl" );
			$this->printText( "$indstr<header />$nl" );
			$this->printText( "$indstr<data>$nl" );
			$this->slowWddxPrinter( $data, 4 );
			$this->printText( "$indstr</data>$nl" );
			$this->printText( "</wddxPacket>$nl" );
		}
	}

	public static function useSlowPrinter() {
		if ( !function_exists( 'wddx_serialize_value' ) ) {
			return true;
		}

		// Some versions of PHP have a broken wddx_serialize_value, see
		// PHP bug 45314. Test encoding an affected character (U+00A0)
		// to avoid this.
		$expected =
			"<wddxPacket version='1.0'><header/><data><string>\xc2\xa0</string></data></wddxPacket>";
		if ( wddx_serialize_value( "\xc2\xa0" ) !== $expected ) {
			return true;
		}

		// Some versions of HHVM don't correctly encode ampersands.
		$expected =
			"<wddxPacket version='1.0'><header/><data><string>&amp;</string></data></wddxPacket>";
		if ( wddx_serialize_value( '&' ) !== $expected ) {
			return true;
		}

		// Some versions of HHVM don't correctly encode empty arrays as subvalues.
		$expected =
			"<wddxPacket version='1.0'><header/><data><array length='1'><array length='0'></array></array></data></wddxPacket>";
		if ( wddx_serialize_value( array( array() ) ) !== $expected ) {
			return true;
		}

		// Some versions of HHVM don't correctly encode associative arrays with numeric keys.
		$expected =
			"<wddxPacket version='1.0'><header/><data><struct><var name='2'><number>1</number></var></struct></data></wddxPacket>";
		if ( wddx_serialize_value( array( 2 => 1 ) ) !== $expected ) {
			return true;
		}

		return false;
	}

	/**
	 * Recursively go through the object and output its data in WDDX format.
	 * @param mixed $elemValue
	 * @param int $indent
	 */
	function slowWddxPrinter( $elemValue, $indent = 0 ) {
		$indstr = ( $this->getIsHtml() ? str_repeat( ' ', $indent ) : '' );
		$indstr2 = ( $this->getIsHtml() ? str_repeat( ' ', $indent + 2 ) : '' );
		$nl = ( $this->getIsHtml() ? "\n" : '' );

		if ( is_array( $elemValue ) ) {
			$cnt = count( $elemValue );
			if ( $cnt != 0 && array_keys( $elemValue ) !== range( 0, $cnt - 1 ) ) {
				$elemValue = (object)$elemValue;
			}
		}

		if ( is_array( $elemValue ) ) {
			// Regular array
			$this->printText( $indstr . Xml::element( 'array', array(
				'length' => count( $elemValue ) ), null ) . $nl );
			foreach ( $elemValue as $subElemValue ) {
				$this->slowWddxPrinter( $subElemValue, $indent + 2 );
			}
			$this->printText( "$indstr</array>$nl" );
		} elseif ( is_object( $elemValue ) ) {
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
		} elseif ( is_int( $elemValue ) || is_float( $elemValue ) ) {
			$this->printText( $indstr . Xml::element( 'number', null, $elemValue ) . $nl );
		} elseif ( is_string( $elemValue ) ) {
			$this->printText( $indstr . Xml::element( 'string', null, $elemValue, false ) . $nl );
		} elseif ( is_bool( $elemValue ) ) {
			$this->printText( $indstr . Xml::element( 'boolean',
				array( 'value' => $elemValue ? 'true' : 'false' ) ) . $nl
			);
		} elseif ( $elemValue === null ) {
			$this->printText( $indstr . Xml::element( 'null', array() ) . $nl );
		} else {
			ApiBase::dieDebug( __METHOD__, 'Unknown type ' . gettype( $elemValue ) );
		}
	}

	public function isDeprecated() {
		return true;
	}
}
