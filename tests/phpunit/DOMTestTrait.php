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

/**
 * Trait for DOM testing.
 *
 * @since 1.27
 */
trait DOMTestTrait {

	private function assertTagSimple( array $matcher, $actual, $message = '', $isHtml = true ) {
		$this->assertEmpty( array_diff(
			array_keys( $matcher ),
			array( 'tag', 'attributes', 'content' )
		) );
		$doc = new DOMDocument();
		if ( $isHtml ) {
			$doc->loadHTML( $actual );
		} else {
			$doc->loadXML( $actual );
		}
		$found = false;
		$elements = $doc->getElementsByTagName( $matcher['tag'] );
		foreach ( $elements as $node ) {
			$valid = true;
			if ( isset( $matcher['attributes'] ) ) {
				foreach ( $matcher['attributes'] as $name => $value ) {
					if ( $name === 'class' ) {
						$expected = preg_split( '/\s+/', $value, null, PREG_SPLIT_NO_EMPTY );
						$got = preg_split( '/\s+/', $node->getAttribute( $name ), null, PREG_SPLIT_NO_EMPTY );
						// make sure each class given is in the actual node
						if ( array_diff( $expected, $got ) ) {
							$valid = false;
							break;
						}
					} elseif ( $node->getAttribute( $name ) !== $value ) {
						$valid = false;
						break;
					}
				}
			}
			if ( isset( $matcher['content'] ) ) {
				if ( $matcher['content'] === '' ) {
					if ( $node->nodeValue !== '' ) {
						$valid = false;
					}
				} elseif ( strstr( $node->nodeValue, $matcher['content'] ) === false ) {
					$valid = false;
				}
			}
			if ( $valid ) {
				$found = true;
				break;
			}
		}
		$this->assertTrue( $found, $message );
	}

}
