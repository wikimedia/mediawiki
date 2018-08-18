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
 * @since 1.32
 */
class StaticArrayWriter {

	/**
	 * @param string[] $data Array with string keys/values to export
	 * @param string $header
	 *
	 * @return string PHP code
	 */
	public function create( array $data, $header = 'Automatically generated' ) {
		$format = "\t%s => %s,\n";
		$code = "<?php\n"
			. "// " . implode( "\n// ", explode( "\n", $header ) ) . "\n"
			. "return [\n";
		foreach ( $data as $key => $value ) {
			$code .= sprintf(
				$format,
				var_export( $key, true ),
				var_export( $value, true )
			);
		}
		$code .= "];\n";
		return $code;
	}
}
