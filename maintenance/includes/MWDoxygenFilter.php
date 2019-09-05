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
 * Improved to resolve various bugs and better MediaWiki PHPDoc conventions:
 *
 * - Insert variable name after typehint instead of at end of line so that
 *   documentation text may follow after `@var Type`.
 * - Insert typehint into source code before $variable instead of inside the comment
 *   so that Doxygen interprets it.
 * - Strip the text after `@var` from the output to avoid Doxygen warnings about bogus
 *   symbols being documented but not declared or defined.
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
		$buffer = $bufferType = null;
		$output = '';
		foreach ( $tokens as $token ) {
			if ( is_string( $token ) ) {
				if ( $buffer !== null && $token === ';' ) {
					// If we still have a buffer and the statement has ended,
					// flush it and move on.
					$output .= $buffer;
					$buffer = $bufferType = null;
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
					// Look for instances of "@var Type" not followed by $name.
					if ( preg_match( '#@var\s+([^\s]+)\s+([^\$]+)#s', $content ) ) {
						$buffer = preg_replace_callback(
							// Strip the "@var Type" part and remember the type
							'#(@var\s+)([^\s]+)#s',
							function ( $matches ) use ( &$bufferType ) {
								$bufferType = $matches[2];
								return '';
							},
							$content
						);
					} else {
						$output .= $content;
					}
					break;

				case T_VARIABLE:
					if ( $buffer !== null ) {
						$output .= $buffer;
						$output .= "$bufferType $content";
						$buffer = $bufferType = null;
					} else {
						$output .= $content;
					}
					break;

				default:
					if ( $buffer !== null ) {
						$buffer .= $content;
					} else {
						$output .= $content;
					}
					break;
			}
		}
		return $output;
	}
}
