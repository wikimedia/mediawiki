<?php
if ( ! defined( 'MEDIAWIKI' ) )
	die( -1 );
/**
 * PHP <4.2.0 doesn't build ctype_ functions by default and Gentoo doesn't
 * build it by default, and that probably applies for some other distributions
 *
 * These functions should be fully compatable with their PHP equivalents
 *
 * @package MediaWiki
 * @subpackage Compatability
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @copyright Copyright © 2006, Ævar Arnfjörð Bjarmason
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 *
 * @link http://www.php.net/manual/en/ref.ctype.php PHP Character Type Functions
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

/**#@+
 * Takes the same input and returns the same values as the equvalent PHP
 * function
 *
 * @param mixed $in
 * @return bool
 */
function ctype_alnum() {
	$fname = 'ctype_alnum';

	$args = func_get_args();
	list( $in, $ret ) = wf_ctype_parse_args( $fname, $args );

	if ( ! is_null( $ret ) )
		return $ret;
	else
		return (bool)preg_match( '~^[[:alnum:]]+$~i', $in );
}

function ctype_alpha() {
	$fname = 'ctype_alpha';

	$args = func_get_args();
	list( $in, $ret ) = wf_ctype_parse_args( $fname, $args );

	if ( ! is_null( $ret ) )
		return $ret;
	else
		return (bool)preg_match( '~^[[:alpha:]]+$~i', $in );
}

function ctype_cntrl() {
	$fname = 'ctype_cntrl';

	$args = func_get_args();
	list( $in, $ret ) = wf_ctype_parse_args( $fname, $args );

	if ( ! is_null( $ret ) )
		return $ret;
	else
		return (bool)preg_match( '~^[[:cntrl:]]+$~', $in );
}

function ctype_digit() {
	$fname = 'ctype_digit';

	$args = func_get_args();
	list( $in, $ret ) = wf_ctype_parse_args( $fname, $args );

	if ( ! is_null( $ret ) )
		return $ret;
	else
		return (bool)preg_match( '~^[[:digit:]]+$~', $in );
}

function ctype_graph() {
	$fname = 'ctype_graph';

	$args = func_get_args();
	list( $in, $ret ) = wf_ctype_parse_args( $fname, $args );

	if ( ! is_null( $ret ) )
		return $ret;
	else
		return (bool)preg_match( '~^[[:graph:]]+$~', $in );
}

function ctype_lower() {
	$fname = 'ctype_lower';

	$args = func_get_args();
	list( $in, $ret ) = wf_ctype_parse_args( $fname, $args );

	if ( ! is_null( $ret ) )
		return $ret;
	else
		return (bool)preg_match( '~^[[:lower:]]+$~', $in );
}

function ctype_print() {
	$fname = 'ctype_print';

	$args = func_get_args();
	list( $in, $ret ) = wf_ctype_parse_args( $fname, $args );

	if ( ! is_null( $ret ) )
		return $ret;
	else
		return (bool)preg_match( '~^[[:print:]]+$~', $in );
}

function ctype_punct() {
	$fname = 'ctype_punct';

	$args = func_get_args();
	list( $in, $ret ) = wf_ctype_parse_args( $fname, $args );

	if ( ! is_null( $ret ) )
		return $ret;
	else
		return (bool)preg_match( '~^[[:punct:]]+$~', $in );
}

function ctype_space() {
	$fname = 'ctype_space';

	$args = func_get_args();
	list( $in, $ret ) = wf_ctype_parse_args( $fname, $args );

	if ( ! is_null( $ret ) )
		return $ret;
	else
		return (bool)preg_match( '~^[[:space:]]+$~', $in );

}


function ctype_upper() {
	$fname = 'ctype_upper';

	$args = func_get_args();
	list( $in, $ret ) = wf_ctype_parse_args( $fname, $args );

	if ( ! is_null( $ret ) )
		return $ret;
	else
		return (bool)preg_match( '~^[[:upper:]]+$~', $in );

}

function ctype_xdigit() {
	$fname = 'ctype_xdigit';

	$args = func_get_args();
	list( $in, $ret ) = wf_ctype_parse_args( $fname, $args );

	if ( ! is_null( $ret ) )
		return $ret;
	else
		return (bool)preg_match( '~^[[:xdigit:]]+$~i', $in );
}

/**#@-*/

/**
 * PHP does some munging on ctype_*() like converting  -128 <= x <= -1 to x +=
 * 256, 0 <= x <= 255 to chr(x) etc, it'll return true if x < -128 and false if
 * x >= 256, true if the input is an empty string and false if it's of any
 * other datatype than int or string, this function duplicates that behaviour.
 *
 * @param string $fname The name of the caller function
 * @param array $args The return of the callers func_get_args()
 * @return array An array with two items, the first is the munged input the
 *               calling function is supposed to use and a return value it
 *               should return if it's not null, $in will be null if $ret is
 *               not null since there's no point in setting it in that case
 */
function wf_ctype_parse_args( $fname, $args ) {
	$ret = null;

	$cnt = count( $args );

	if ( $cnt !== 1 )
		trigger_error( "$fname() expects exactly 1 parameter $cnt given", E_USER_WARNING );

	$in = array_pop( $args );

	if ( is_int( $in ) ) {
		if ( $in >= 256 )
			return array( null, false );
		else if ( $in >= 0 )
			$in = chr( $in );
		else if ( $in >= -128 )
			$in = ord( $in + 256 );
		else if ( $in < -128 )
			return array( null, true );
	}

	if ( is_string( $in ) )
		if ( $in === '' )
			return array( null, true );
		else
			return array( $in, null );
	else
		// Like PHP
		return array( null, false );
}
?>
