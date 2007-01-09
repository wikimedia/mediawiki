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
# Lacking documentation. An example is:
# php checkExtensioni18n.php /opt/mw/extensions/CentralAuth/CentralAuth.i18n.php wgCentralAuthMessages
#

require_once( dirname(__FILE__).'/../commandLine.inc' );
require_once( 'languages.inc' );
require_once( 'checkLanguage.inc' );

class extensionLanguages extends languages {
	private $mExt18nFilename, $mExtArrayName ;
	private $mExtArray;

	function __construct( $ext18nFilename, $extArrayName ) {
		$exif = false;
		$this->mExt18nFilename = $ext18nFilename;
		$this->mExtArrayName = $extArrayName;

		$this->mIgnoredMessages = array() ;
		$this->mOptionalMessages = array() ;

		if ( file_exists( $this->mExt18nFilename ) ) {
			require_once( $this->mExt18nFilename );
			$this->mExtArray = ${$this->mExtArrayName} ;
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


function usage() {
// Usage
print <<<END
Usage: php checkExtensioni18n.php <filename> <arrayname>


END;
die;
}

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
} else {
	usage();
}

$extLanguages = new extensionLanguages($filename, $arrayname);

// Stuff needed by the checkLanguage routine (globals)
$wgGeneralMessages = $extLanguages->getGeneralMessages();
$wgRequiredMessagesNumber = count( $wgGeneralMessages['required'] );
$wgDisplayLevel = 2;
$wgChecks = array( 'untranslated', 'obsolete', 'variables', 'empty', 'whitespace', 'xhtml', 'chars' );

foreach( $extLanguages->getLanguages() as $lang ) {
	if( $lang == 'en' ) {
		print "Skipped english language\n";
		continue;
	}
	checkLanguage( $extLanguages, $lang );
/*
	print "== $lang ==\n";
	print count($ext->getUntranslatedMessages( $lang ) ) . "\n";
	print count($ext->getEmptyMessages( $lang ) ) . "\n";
*/
}

?>
