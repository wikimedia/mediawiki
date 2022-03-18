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
			. "return [\n";
		foreach ( $data as $key => $value ) {
			$code .= self::encodePair( $key, $value, 1 );
		}
		$code .= "];\n";
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
			. "\tpublic const {$layout['const']} = [\n";
		foreach ( $data as $key => $value ) {
			$code .= self::encodePair( $key, $value, 2 );
		}
		$code .= "\t];\n}\n";
		return $code;
	}

	/**
	 * Recursively turn one k/v pair into properly-indented PHP
	 *
	 * @since 1.38
	 *
	 * @param string|int $key
	 * @param mixed $value
	 * @param int $indent Indentation level
	 * @return string PHP code
	 */
	private static function encodePair( $key, $value, $indent = 0 ) {
		$tabs = str_repeat( "\t", $indent );
		$line = $tabs . var_export( $key, true ) . ' => ';
		$line .= self::encodeValue( $value, $indent );

		$line .= ",\n";
		return $line;
	}

	/**
	 * Recursively turn one value into properly-indented PHP
	 *
	 * @since 1.38
	 *
	 * @param mixed $value
	 * @param int $indent Indentation level
	 * @return string PHP code
	 */
	public static function encodeValue( $value, $indent = 0 ) {
		if ( is_array( $value ) ) {
			$tabs = str_repeat( "\t", $indent );
			$line = "[\n";
			foreach ( $value as $subkey => $subvalue ) {
				$line .= self::encodePair( $subkey, $subvalue, $indent + 1 );
			}
			$line .= "$tabs]";
			return $line;
		} else {
			$exportedValue = var_export( $value, true );
			if ( $exportedValue === 'NULL' ) {
				// var_export() exports nulls as uppercase NULL which
				// violates our own coding standards.
				$exportedValue = 'null';
			}
			return $exportedValue;
		}
	}
}
