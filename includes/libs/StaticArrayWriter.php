<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 */

namespace Wikimedia;

/**
 * Format a static PHP array to be written to a file
 *
 * @newable
 * @since 1.32
 */
class StaticArrayWriter {
	/**
	 * @param array $data Array with keys/values to export
	 * @param string $header
	 * @return string PHP code
	 */
	public function create( array $data, $header = 'Automatically generated' ) {
		return self::write( $data, $header );
	}

	/**
	 * Create a PHP file that returns the array.
	 *
	 * @since 1.35
	 * @param array $data Array with keys/values to export
	 * @param string $header
	 * @return string PHP code
	 */
	public static function write( array $data, $header ) {
		$code = "<?php\n"
			. "// " . implode( "\n// ", explode( "\n", $header ) ) . "\n"
			. "return " . self::encodeArray( $data ) . ";\n";
		return $code;
	}

	/**
	 * Create an PHP class file with the array as a class constant.
	 *
	 * PHP classes can be autoloaded by name, which allows usage to be decoupled
	 * from the file path.
	 *
	 * @since 1.37
	 * @param array $data
	 * @param array{header:string,namespace:string,class:string,const:string} $layout
	 * @return string PHP code
	 */
	public static function writeClass( array $data, array $layout ) {
		$code = "<?php\n"
			. "// " . implode( "\n// ", explode( "\n", $layout['header'] ) ) . "\n"
			. "\n"
			. "namespace {$layout['namespace']};\n"
			. "\n"
			. "class {$layout['class']} {\n"
			. "\tpublic const {$layout['const']} = " . self::encodeArray( $data, "\t\t" ) . ";\n}\n";
		return $code;
	}

	/**
	 * Recursively turn an array into properly-indented PHP
	 *
	 * @param array $array
	 * @param string $tabs Indentation level
	 * @return string PHP code
	 */
	private static function encodeArray( array $array, string $tabs = "\t" ): string {
		$code = "[\n";
		if ( array_is_list( $array ) ) {
			foreach ( $array as $value ) {
				$code .= $tabs . self::encodeValue( $value, $tabs ) . ",\n";
			}
		} else {
			foreach ( $array as $key => $value ) {
				$code .= $tabs . var_export( $key, true ) . ' => ' .
					self::encodeValue( $value, $tabs ) . ",\n";
			}
		}
		return $code . substr( $tabs, 0, -1 ) . ']';
	}

	/**
	 * Recursively turn one value into properly-indented PHP
	 *
	 * @param mixed $value
	 * @param string $tabs Indentation level
	 * @return string PHP code
	 */
	private static function encodeValue( $value, string $tabs ): string {
		if ( is_array( $value ) ) {
			return self::encodeArray( $value, $tabs . "\t" );
		}

		// var_export() exports nulls as uppercase NULL which
		// violates our own coding standards.
		return $value === null ? 'null' : var_export( $value, true );
	}
}
