<?php
/**
 * Copyright (C) 2012 Tamas Imrei <tamas.imrei@gmail.com> https://virtualtee.blogspot.com/
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
 * @ingroup Maintenance
 * @phan-file-suppress PhanInvalidCommentForDeclarationType False negative about about `@var`
 * @phan-file-suppress PhanUnextractableAnnotation False negative about about `@var`
 */

/**
 * Doxygen filter to show correct member variable types in documentation.
 *
 * Based on
 * <https://virtualtee.blogspot.co.uk/2012/03/tip-for-using-doxygen-for-php-code.html>
 *
 * It has been adapted for MediaWiki to resolve various bugs we experienced
 * from using Doxygen with our coding conventions:
 *
 * - We want to allow documenting class members on a single line by documenting
 *   them as `/** @var SomeType Description here.`, and in long-form as
 *   `/**\n * Description here.\n * @var SomeType`.
 *
 * - PHP does not support native type-hinting of class members. Instead, we document
 *   that using `@var` in the doc blocks above it. However, Doxygen only supports
 *   parsing this from executable code. We achieve this by having the below filter
 *   take the typehint from the doc block and insert it into the source code in
 *   front of `$myvar`, like `protected SomeType $myvar`. This result is technically
 *   invalid PHP code, but Doxygen understands it this way.
 *
 * @internal For use by maintenance/mwdoc-filter.php
 * @ingroup Maintenance
 */
class MWDoxygenFilter {
	/**
	 * @param string $source Original source code
	 * @return string Filtered source code
	 */
	public static function filter( $source ) {
		$tokens = token_get_all( $source );
		$buffer = null;
		$output = '';
		foreach ( $tokens as $token ) {
			if ( is_string( $token ) ) {
				if ( $buffer !== null && $token === ';' ) {
					// If we still have a buffer and the statement has ended,
					// flush it and move on.
					$output .= $buffer['raw'];
					$buffer = null;
				}
				$output .= $token;
				continue;
			}
			list( $id, $content ) = $token;
			switch ( $id ) {
				case T_DOC_COMMENT:
					// Escape slashes so that references to namespaces are not
					// wrongly interpreted as a Doxygen "\command".
					$content = addcslashes( $content, '\\' );
					// Look for instances of "@var SomeType".
					if ( preg_match( '#@var\s+\S+#s', $content ) ) {
						$buffer = [ 'raw' => $content, 'desc' => null, 'type' => null, 'name' => null ];
						$buffer['desc'] = preg_replace_callback(
							// Strip "@var SomeType" part, but remember the type and optional name
							'#@var\s+(\S+)(\s+)?(\S+)?#s',
							function ( $matches ) use ( &$buffer ) {
								$buffer['type'] = $matches[1];
								$buffer['name'] = $matches[3] ?? null;
								return ( $matches[2] ?? '' ) . ( $matches[3] ?? '' );
							},
							$content
						);
					} else {
						$output .= $content;
					}
					break;

				case T_VARIABLE:
					// Doxygen requires class members to be documented in one of two ways:
					//
					// 1. Fully qualified:
					//    /** @var SomeType $name Description here. */
					//
					//    These result in the creation of a new virtual node called $name
					//    with the specified type and description. The real code doesn't
					//    even need to exist in this case.
					//
					// 2. Contextual:
					//    /** Description here. */
					//    private SomeType? $name;
					//
					// In MediaWiki, we are mostly like #1 but without the name repeated:
					//    /** @var SomeType Description here. */
					//    private $name;
					//
					// These emit a warning in Doxygen because they are missing a variable name.
					// Convert these to the "Contextual" kind by stripping ""@var", injecting
					// type into the code, and leaving the description in-place.
					if ( $buffer !== null ) {
						if ( $buffer['name'] === $content ) {
							// Fully qualitied "@var" comment, leave as-is.
							$output .= $buffer['raw'];
							$output .= $content;
						} else {
							// MW-style "@var" comment. Keep only the description and transplant
							// the type into the code.
							$output .= $buffer['desc'];
							$output .= "{$buffer['type']} $content";
						}
						$buffer = null;
					} else {
						$output .= $content;
					}
					break;

				default:
					if ( $buffer !== null ) {
						$buffer['raw'] .= $content;
						$buffer['desc'] .= $content;
					} else {
						$output .= $content;
					}
					break;
			}
		}
		return $output;
	}
}
