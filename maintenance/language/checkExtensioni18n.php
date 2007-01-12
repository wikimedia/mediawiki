<?php
/**
 * Copyright (C) 2007 Ashar Voultoiz <hashar@altern.org>
 *
 * Based on dumpBackup:
 * Copyright (C) 2005 Brion Vibber <brion@pobox.com>
 *
 * http://www.mediawiki.org
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
 * @package MediaWiki
 * @subpackage SpecialPage
 */

#
# Lacking documentation. Examples:
# php checkExtensioni18n.php /opt/mw/extensions/CentralAuth/CentralAuth.i18n.php wgCentralAuthMessages
# php checkExtensioni18n.php --extdir /opt/mw/extensions/
#
# BUGS: cant guess registered extensions :)
# TODO: let users set parameters to configure checklanguage.inc (it uses globals)

// Filename for the extension i18n files database:
define( 'EXT_I18N_DB', 'i18n.db' );

// Global parameters for checkLanguage.inc
$wgDisplayLevel = 2;
$wgChecks = array( 'untranslated', 'obsolete', 'variables', 'empty', 'whitespace', 'xhtml', 'chars' );


$optionsWithArgs = array( 'extdir' );


require_once( dirname(__FILE__).'/../commandLine.inc' );
require_once( 'languages.inc' );
require_once( 'checkLanguage.inc' );


class extensionLanguages extends languages {
	private $mExt18nFilename, $mExtArrayName ;
	private $mExtArray;

	function __construct( $ext18nFilename, $extArrayName ) {
		$this->mExt18nFilename = $ext18nFilename;
		$this->mExtArrayName = $extArrayName;

		$this->mIgnoredMessages = array() ;
		$this->mOptionalMessages = array() ;

		if ( file_exists( $this->mExt18nFilename ) ) {
			require_once( $this->mExt18nFilename );

			$foundarray = false ;

			if( isset( ${$this->mExtArrayName} ) )  {
				// File provided in the db file
				$foundarray = ${$this->mExtArrayName} ;
			} else {
				// Provided array could not be found we try to guess it.

				# Using the extension path ($m[1]) and filename ($m[2]):
				$m = array();
				preg_match( '%.*/(.*)/(.*).i18n\.php%', $this->mExt18nFilename, $m);
				$arPathCandidate = 'wg' . $m[1].'Messages';
				$arFileCandidate = 'wg' . $m[2].'Messages';
				$funcCandidate = "ef{$m[2]}Messages";

				// Try them:
				if( isset($$arPathCandidate) && is_array( $$arPathCandidate ) ) {
					print "warning> messages from guessed path array \$$arPathCandidate.\n";
					$foundarray = $$arPathCandidate;
				} elseif( isset($$arFileCandidate) && is_array( $$arFileCandidate ) ) {
					print "warning> messages from guessed file array \$$arFileCandidate.\n";
					$foundarray = $$arFileCandidate;
				} elseif( function_exists( $funcCandidate ) ) {
					print "warning> messages build from guessed function {$funcCandidate}().\n";
					$foundarray = $funcCandidate();
				}

				# We are unlucky, return empty stuff
				if(!$foundarray) {
					print "ERROR> failed to guess an array to use.\n";
					$this->mExtArray = null;
					$this->mLanguages = null;
					return;
				}
			}

			$this->mExtArray = $foundarray ;
			$this->mLanguages = array_keys( $this->mExtArray );
		} else {
			wfDie( "File $this->mExt18nFilename not found\n" );
		}
	}

	protected function loadRawMessages( $code ) {
		if ( isset( $this->mRawMessages[$code] ) ) {
			return;
		}
		if( isset( $this->mExtArray[$code] ) ) {
			$this->mRawMessages[$code] = $this->mExtArray[$code] ;
		} else {
			$this->mRawMessages[$code] = array();
		}
	}

	public function getLanguages() {
		return $this->mLanguages;
	}
}


function checkExtensionLanguage( $filename, $arrayname ) {
	global $wgGeneralMessages, $wgRequiredMessagesNumber;

	$extLanguages = new extensionLanguages($filename, $arrayname);

	// Stuff needed by the checkLanguage routine (globals)
	$wgGeneralMessages = $extLanguages->getGeneralMessages();
	$wgRequiredMessagesNumber = count( $wgGeneralMessages['required'] );

	$langs = $extLanguages->getLanguages();
	if( !$langs ) {
		print "ERROR> \$$arrayname array does not exist.\n";
		return false;
	}

	print "Found ". count($langs) . " languages : " . implode(' ', $langs) ."\n";
	foreach( $langs as $lang ) {
		if( $lang == 'en' ) {
			#print "Skipped english language\n";
			continue;
		}

		checkLanguage( $extLanguages, $lang );
	}
}

function checkExtensionRepository( $extdir, $db ) {
	$fh = fopen( $extdir. '/' . $db, 'r' );

	$line_number = 0;
	while( $line = fgets( $fh ) ) {
		$line_number++;

		// Ignore comments
		if( preg_match( '/^#/', $line ) ) {
			continue;
		}

		// Load data from i18n database
		$data = split( ' ', chop($line) );
		$i18n_file = @$data[0];
		$arrayname = @$data[1];

		print "------------------------------------------------------\n";
		print "Checking $i18n_file (\$$arrayname).\n";

		// Check data
		if( !file_exists( $extdir . '/' . $i18n_file ) ) {
			print "ERROR> $i18n_file not found ($db:$line_number).\n";
			continue;
		}
#		if( $arrayname == '' ) {
#			print "warning> no array name for $i18n_file ($db:$line_number).\n";
#		}

 		$i18n_file = $extdir . '/' . $i18n_file ;

		checkExtensionLanguage( $i18n_file, $arrayname );

		print "\n";
	}
}


function usage() {
// Usage
print <<<END
Usage:
    php checkExtensioni18n.php <filename> <arrayname>
    php checkExtensioni18n.php --extdir <exstention repository>


END;
die;
}

// Play with options and arguments
if( isset( $options['extdir'] ) ) {
	$extdb = $options['extdir'] . '/' . EXT_I18N_DB ;

	if( file_exists( $extdb ) ) {
		checkExtensionRepository( $options['extdir'], EXT_I18N_DB );
	} else {
		print "$extdb does not exist\n";
	}

} else {
	// Check arguments
	if ( isset( $argv[0] ) ) {

		if (file_exists( $argv[0] ) ) {
			$filename = $argv[0];
		} else {
			print "Unable to open file '{$argv[0]}'\n";
			usage();
		}

		if ( isset( $argv[1] ) ) {
			$arrayname = $argv[1];
		} else {
			print "You must give an array name to be checked\n";
			usage();
		}

		checkExtensionLanguage( $filename, $arrayname );
	} else {
		usage();
	}
}

?>
