<?php
/**
 * @license GPL-2.0-or-later
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
	 * Flag for write() and writeClass(): Skip formatting the array according
	 * to our coding conventions (short array syntax, tab indentation) and use
	 * the output of var_export() as-is. This is much faster for large arrays,
	 * and meant for generated files that are not read by humans, such as caches.
	 *
	 * @since 1.48
	 */
	public const UNFORMATTED = 1;

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
	 * @since 1.48 Added $flags parameter
	 * @param array $data Array with keys/values to export
	 * @param string $header
	 * @param int $flags Bitfield of StaticArrayWriter::UNFORMATTED
	 * @return string PHP code
	 */
	public static function write( array $data, $header, int $flags = 0 ) {
		$code = "<?php\n"
			. "// " . implode( "\n// ", explode( "\n", $header ) ) . "\n"
			. "return " . self::encode( $data, "\t", $flags ) . ";\n";
		return $code;
	}

	/**
	 * Create an PHP class file with the array as a class constant.
	 *
	 * PHP classes can be autoloaded by name, which allows usage to be decoupled
	 * from the file path.
	 *
	 * @since 1.37
	 * @since 1.48 Added $flags parameter
	 * @param array $data
	 * @param array{header:string,namespace:string,class:string,const:string} $layout
	 * @param int $flags Bitfield of StaticArrayWriter::UNFORMATTED
	 * @return string PHP code
	 */
	public static function writeClass( array $data, array $layout, int $flags = 0 ) {
		$code = "<?php\n"
			. "// " . implode( "\n// ", explode( "\n", $layout['header'] ) ) . "\n"
			. "\n"
			. "namespace {$layout['namespace']};\n"
			. "\n"
			. "class {$layout['class']} {\n"
			. "\tpublic const {$layout['const']} = " . self::encode( $data, "\t\t", $flags ) . ";\n}\n";
		return $code;
	}

	/**
	 * @param array $data
	 * @param string $tabs Indentation level
	 * @param int $flags
	 * @return string PHP code
	 */
	private static function encode( array $data, string $tabs, int $flags ): string {
		return ( $flags & self::UNFORMATTED )
			? var_export( $data, true )
			: self::encodeArray( $data, $tabs );
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
