<?php
/**
* Unicode normalization routines: Unicode tables generator
*
* Copyright ( C) 2004 Ludovic ARNAUD <ludovic.arnaud@gmail.com>
* 
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or 
* ( at your option) any later version.
* 
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
* 
* You should have received a copy of the GNU General Public License along
* with this program; if not, write to the Free Software Foundation, Inc.,
* 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
* http://www.gnu.org/copyleft/gpl.html
*
* @author Ludovic ARNAUD <ludovic.arnaud@gmail.com>
* @license http://www.gnu.org/licenses/gpl.txt
* @package UtfNormal
* @access private
*/

if( php_sapi_name() != 'cli' ) {
	die( "This program must be run from the command line.\n" );
}

require_once( 'UtfNormal.php' );
$file_contents = array();

/**
* Generate some Hangul/Jamo stuff
*/
echo "\nGenerating Hangul and Jamo tables\n";
for ( $i = 0; $i < UNICODE_HANGUL_LCOUNT; ++$i ) {
	$utf_char = cp_to_utf( UNICODE_HANGUL_LBASE + $i );
//	$file_contents['UtfNormalData.inc']['utfJamoIndex'][$utf_char] = $i;
	$file_contents['UtfNormalData.inc']['utfJamoIndex'][$utf_char] = $i * UNICODE_HANGUL_VCOUNT * UNICODE_HANGUL_TCOUNT + UNICODE_HANGUL_SBASE;
	$file_contents['UtfNormalData.inc']['utfJamoType'][$utf_char] = UNICODE_JAMO_L;
}

for ( $i = 0; $i < UNICODE_HANGUL_VCOUNT; ++$i ) {
	$utf_char = cp_to_utf( UNICODE_HANGUL_VBASE + $i );
//	$file_contents['UtfNormalData.inc']['utfJamoIndex'][$utf_char] = $i;
	$file_contents['UtfNormalData.inc']['utfJamoIndex'][$utf_char] = $i * UNICODE_HANGUL_TCOUNT;
	$file_contents['UtfNormalData.inc']['utfJamoType'][$utf_char] = UNICODE_JAMO_V;
}

for ( $i = 0; $i < UNICODE_HANGUL_TCOUNT; ++$i ) {
	$utf_char = cp_to_utf( UNICODE_HANGUL_TBASE + $i );
	$file_contents['UtfNormalData.inc']['utfJamoIndex'][$utf_char] = $i;
	$file_contents['UtfNormalData.inc']['utfJamoType'][$utf_char] = UNICODE_JAMO_T;
}

/**
* Load the CompositionExclusions table
*/
echo "Loading CompositionExclusion\n";
if( !$fp = fopen( 'CompositionExclusions.txt', 'rt' ) ) {
	print "\nCan't open UnicodeData.txt for reading.\n";
	print "If necessary, fetch this file from the internet:\n";
	print "http://www.unicode.org/Public/UNIDATA/CompositionExclusions.txt\n";
	exit( -1 );
}

$exclude = array();
while ( !feof( $fp ) ) {
	$line = fgets( $fp, 1024 );

	if( !ctype_xdigit( $line[0] ) ) {
		continue;
	}

	$cp = strtok( $line, ' ' );

	if( $pos = strpos( $cp, '..' ) ) {
		$start = hexdec( substr( $cp, 0, $pos ) );
		$end = hexdec( substr( $cp, $pos + 2 ) );

		for ( $i = $start; $i < $end; ++$i )
		{
			$exclude[$i] = 1;
		}
	} else {
		$exclude[hexdec( $cp )] = 1;
	}
}
fclose( $fp );

/**
* Load QuickCheck tables
*/
echo "Generating QuickCheck tables\n";
if( !$fp = fopen( 'DerivedNormalizationProps.txt', 'rt' ) ) {
	print "\nCan't open UnicodeData.txt for reading.\n";
	print "If necessary, fetch this file from the internet:\n";
	print "http://www.unicode.org/Public/UNIDATA/DerivedNormalizationProps.txt\n";
	exit( -1 );
}

while ( !feof( $fp ) ) {
	$line = fgets( $fp, 1024 );

	if( !ctype_xdigit( $line[0] ) ) {
		continue;
	}

	$p = array_map( 'trim', explode( ';', strtok( $line, '#' ) ) );

	/**
	* Capture only NFC_QC, NFKC_QC
	*/
	if( !preg_match( '#^NFK?C_QC$#', $p[1] ) ) {
		continue;
	}

	if( $pos = strpos( $p[0], '..' ) ) {
		$start = hexdec( substr( $p[0], 0, $pos ) );
		$end = hexdec( substr( $p[0], $pos + 2 ) );
	} else {
		$start = $end = hexdec( $p[0] );
	}

	if( $start >= UTF8_HANGUL_FIRST && $end <= UTF8_HANGUL_LAST ) {
		/**
		* We do not store Hangul syllables in the array
		*/
		continue;
	}

	if( $p[2] == 'M' ) {
		$val = UNICODE_QC_MAYBE;
	} else {
		$val = UNICODE_QC_NO;
	}

	if( $p[1] == 'NFKC_QC' ) {
		$file = 'UtfNormalDataK.inc';
	} else {
		$file = 'UtfNormalData.inc';
	}

	for ( $i = $start; $i <= $end; ++$i ) {
		$file_contents[$file]['utfCheck' . substr( $p[1], 0, -3 )][cp_to_utf( $i )] = $val;
	}
}
fclose( $fp );

/**
* Do mappings
*/
echo "Loading Unicode decomposition mappings\n";
if( !$fp = fopen( 'UnicodeData.txt', 'rt' ) ) {
	print "\nCan't open UnicodeData.txt for reading.\n";
	print "If necessary, fetch this file from the internet:\n";
	print "http://www.unicode.org/Public/UNIDATA/UnicodeData.txt\n";
	exit( -1 );
}

$map = array();
while ( !feof( $fp ) ) {
	$p = explode( ';', fgets( $fp, 1024 ) );
	$cp = hexdec( $p[0] );

	if( !empty( $p[3] ) ) {
		/**
		* Store combining class > 0
		*/
		$file_contents['UtfNormalData.inc']['utfCombiningClass'][cp_to_utf( $cp )] = ( int ) $p[3];
	}

	if( !isset( $p[5] ) || !preg_match_all( '#[0-9A-F]+#', strip_tags( $p[5] ), $m ) ) {
		continue;
	}

	if( strpos( $p[5], '>' ) ) {
		$map['NFKD'][$cp] = implode( ' ', array_map( 'hexdec', $m[0] ) );
	} else {
		$map['NFD'][$cp] = $map['NFKD'][$cp] = implode( ' ', array_map( 'hexdec', $m[0] ) );
	}
}
fclose( $fp );

/**
* Build the canonical composition table
*/
echo "Generating the Canonical Composition table\n";
foreach ( $map['NFD'] as $cp => $decomp_seq ) {
	if( !strpos( $decomp_seq, ' ' ) || isset( $exclude[$cp] ) ) {
		/**
		* Singletons are excluded from canonical composition
		*/
		continue;
	}

	$utf_seq = implode( '', array_map( 'cp_to_utf', explode( ' ', $decomp_seq ) ) );

	if( !isset( $file_contents['UtfNormalData.inc']['utfCanonicalComp'][$utf_seq] ) ) {
		$file_contents['UtfNormalData.inc']['utfCanonicalComp'][$utf_seq] = cp_to_utf( $cp );
	}
}

/**
* Decompose the NF[K]D mappings recursively and prepare the file contents
*/
echo "Generating the Canonical and Compatibility Decomposition tables\n\n";
foreach ( $map as $type => $decomp_map ) {
	foreach ( $decomp_map as $cp => $decomp_seq ) {
		$decomp_map[$cp] = decompose( $decomp_map, $decomp_seq );
	}
	unset( $decomp_seq );

	if( $type == 'NFKD' ) {
		$file = 'UtfNormalDataK.inc';
		$var = 'utfCompatibilityDecomp';
	} else {
		$file = 'UtfNormalData.inc';
		$var = 'utfCanonicalDecomp';
	}

	/**
	* Generate the corresponding file
	*/
	foreach ( $decomp_map as $cp => $decomp_seq ) {
		$file_contents[$file][$var][cp_to_utf( $cp )] = implode( '', array_map( 'cp_to_utf', explode( ' ', $decomp_seq ) ) );
	}
}

/**
* Generate and/or alter the files
*/
foreach ( $file_contents as $file => $contents ) {
	$php = '';
	foreach ( $contents as $var => $val ) {
		$php .= '$GLOBALS[' . my_var_export( $var ) . ']=' . my_var_export( $val ) . ";\n";
	}

	/**
	* Generate a new file ( overwrite if applicable
	*/
	echo "Generating $file\n";

	if( !$fp = fopen( $file, 'wb' ) ) {
		trigger_error( 'Cannot open ' . $file . ' for write' );
	}

	fwrite( $fp, '<?php
/**
* This file was automatically generated -- do not edit!
* Run UtfNormalGenerate.php to create this file again ( make clean && make )
* @package MediaWiki
*/
' . $php . '?' . '>' );
	fclose( $fp );
}

die( "All done!\n" );


////////////////////////////////////////////////////////////////////////////////
//                             Internal functions                             //
////////////////////////////////////////////////////////////////////////////////

/**
* Decompose a sequence recusively
*
* @param	array	$decomp_map	Decomposition mapping, passed by reference
* @param	string	$decomp_seq	Decomposition sequence as decimal codepoints separated with a space
* @return	string				Decomposition sequence, fully decomposed
*/
function decompose( &$decomp_map, $decomp_seq ) {
	$ret = array();
	foreach ( explode( ' ', $decomp_seq ) as $cp ) {
		if( isset( $decomp_map[$cp] ) )
		{
			$ret[] = decompose( $decomp_map, $decomp_map[$cp] );
		} else
		{
			$ret[] = $cp;
		}
	}

	return implode( ' ', $ret );
}

/**
* Convert a codepoint to a UTF char
*
* @param	integer	$cp	Unicode codepoint
* @return	string		UTF string
*/
function cp_to_utf( $cp ) {
	if( $cp > 0xFFFF ) {
		return chr( 0xF0 | ( $cp >> 18 ) ) . chr( 0x80 | ( ( $cp >> 12 ) & 0x3F ) ) . chr( 0x80 | ( ( $cp >> 6 ) & 0x3F ) ) . chr( 0x80 | ( $cp & 0x3F ) );
	} elseif( $cp > 0x7FF ) {
		return chr( 0xE0 | ( $cp >> 12 ) ) . chr( 0x80 | ( ( $cp >> 6 ) & 0x3F ) ) . chr( 0x80 | ( $cp & 0x3F ) );
	} elseif( $cp > 0x7F ) {
		return chr( 0xC0 | ( $cp >> 6 ) ) . chr( 0x80 | ( $cp & 0x3F ) );
	} else {
		return chr( $cp );
	}
}

/**
* Return a parsable string representation of a variable
*
* This is function is limited to array/strings/integers
*
* @param	mixed	$var		Variable
* @return	string				PHP code representing the variable
*/
function my_var_export( $var ) {
	if( is_array( $var ) ) {
		$lines = array();

		foreach ( $var as $k => $v )
		{
			$lines[] = my_var_export( $k ) . '=>' . my_var_export( $v );
		}

		return 'array(' . implode( ',', $lines ) . ')';
	} elseif( is_string( $var ) ) {
		return "'" . str_replace( array( '\\', "'" ), array( '\\\\', "\\'" ), $var ) . "'";
	} else {
		return $var;
	}
}

?>
