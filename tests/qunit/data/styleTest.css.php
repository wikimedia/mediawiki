<?php
/**
 * Dynamically create a simple stylesheet for unit tests in MediaWiki.
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
 * @package MediaWiki
 * @author Timo Tijhof
 * @since 1.20
 */

// This file doesn't run as part of MediaWiki
// phpcs:disable MediaWiki.Usage.SuperGlobalsUsage.SuperGlobals

header( 'Content-Type: text/css; charset=utf-8' );

/**
 * Allows characters in ranges [a-z], [A-Z] and [0-9],
 * in addition to a dot ("."), dash ("-"), space (" ") and hash ("#").
 * @since 1.20
 *
 * @param string $val
 * @return string Value with any illegal characters removed.
 */
function cssfilter( $val ) {
	return preg_replace( '/[^A-Za-z0-9\.\- #]/', '', $val );
}

// Do basic sanitization
$params = array_map( 'cssfilter', $_GET );

// Defaults
$selector = $params['selector'] ?? '.mw-test-example';
$property = $params['prop'] ?? 'float';
$value = $params['val'] ?? 'right';
$wait = isset( $params['wait'] ) ? (int)$params['wait'] : 0; // seconds

sleep( $wait );

$css = "
/**
 * Generated " . gmdate( 'r' ) . ".
 * Waited {$wait}s.
 */

$selector {
	$property: $value;
}
";

echo trim( $css ) . "\n";
