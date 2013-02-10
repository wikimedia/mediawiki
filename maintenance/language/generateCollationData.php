<?php
/**
 * Maintenance script to generate first letter data files for Collation.php.
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
 * @ingroup MaintenanceLanguage
 */

require_once( __DIR__ .'/../Maintenance.php' );

/**
 * Generate first letter data files for Collation.php
 *
 * @ingroup MaintenanceLanguage
 */
class GenerateCollationData extends Maintenance {
	/** The directory with source data files in it */
	public $dataDir;

	/** The primary weights, indexed by codepoint */
	public $weights;

	/**
	 * A hashtable keyed by codepoint, where presence indicates that a character
	 * has a decomposition mapping. This makes it non-preferred for group header
	 * selection.
	 */
	public $mappedChars;

	public $debugOutFile;

	/**
	 * Important tertiary weights from UTS #10 section 7.2
	 */
	const NORMAL_UPPERCASE = 0x08;
	const NORMAL_HIRAGANA = 0X0E;

	public function __construct() {
		parent::__construct();
		$this->addOption( 'data-dir', 'A directory on the local filesystem ' .
			'containing allkeys.txt and ucd.all.grouped.xml from unicode.org',
			false, true );
		$this->addOption( 'debug-output', 'Filename for sending debug output to',
			false, true );
	}

	public function execute() {
		$this->dataDir = $this->getOption( 'data-dir', '.' );

		$allkeysPresent = file_exists( "{$this->dataDir}/allkeys.txt" );
		$ucdallPresent = file_exists( "{$this->dataDir}/ucd.all.grouped.xml" );

		// As of January 2013, these links work for all versions of Unicode
		// between 5.1 and 6.2, inclusive.
		$allkeysURL = "http://www.unicode.org/Public/UCA/<Unicode version>/allkeys.txt";
		$ucdallURL = "http://www.unicode.org/Public/<Unicode version>/ucdxml/ucd.all.grouped.zip";

		if ( !$allkeysPresent || !$ucdallPresent ) {
			$icuVersion = IcuCollation::getICUVersion();
			$unicodeVersion = IcuCollation::getUnicodeVersionForICU();

			$error = "";

			if ( !$allkeysPresent ) {
				$error .= "Unable to find allkeys.txt. "
					. "Download it and specify its location with --data-dir=<DIR>. "
					. "\n\n";
			}
			if ( !$ucdallPresent ) {
				$error .= "Unable to find ucd.all.grouped.xml. "
					. "Download it, unzip, and specify its location with --data-dir=<DIR>. "
					. "\n\n";
			}

			$versionKnown = false;
			if ( !$icuVersion ) {
				// Unknown version - either very old intl,
				// or PHP < 5.3.7 which does not expose this information
				$error .= "As MediaWiki could not determine the version of ICU library used by your PHP's "
					. "intl extension it can't suggest which file version to download. "
					. "This can be caused by running a very old version of intl or PHP < 5.3.7. "
					. "If you are sure everything is all right, find out the ICU version "
					. "by running phpinfo(), check what is the Unicode version it is using "
					. "at http://site.icu-project.org/download, then try finding appropriate data file(s) at:";
			} elseif ( version_compare( $icuVersion, "4.0", "<" ) ) {
				// Extra old version
				$error .= "You are using outdated version of ICU ($icuVersion), intended for "
					. ( $unicodeVersion ? "Unicode $unicodeVersion" : "an unknown version of Unicode" )
					. "; this file might not be avalaible for it, and it's not supported by MediaWiki. "
					." You are on your own; consider upgrading PHP's intl extension or try "
					. "one of the files available at:";
			} elseif ( version_compare( $icuVersion, "51.0", ">=" ) ) {
				// Extra recent version
				$error .= "You are using ICU $icuVersion, released after this script was last updated. "
					. "Check what is the Unicode version it is using at http://site.icu-project.org/download . "
					. "It can't be guaranteed everything will work, but appropriate file(s) should "
					. "be available at:";
			} else {
				// ICU 4.0 to 50.x
				$versionKnown = true;
				$error .= "You are using ICU $icuVersion, intended for "
					. ( $unicodeVersion ? "Unicode $unicodeVersion" : "an unknown version of Unicode" )
					. ". Appropriate file(s) should be available at:";
			}
			$error .= "\n";

			if ( $versionKnown && $unicodeVersion ) {
				$allkeysURL = str_replace( "<Unicode version>", "$unicodeVersion.0", $allkeysURL );
				$ucdallURL = str_replace( "<Unicode version>", "$unicodeVersion.0", $ucdallURL );
			}

			if ( !$allkeysPresent ) {
				$error .= "* $allkeysURL\n";
			}
			if ( !$ucdallPresent ) {
				$error .= "* $ucdallURL\n";
			}

			$this->error( $error );
			exit( 1 );
		}

		$debugOutFileName = $this->getOption( 'debug-output' );
		if ( $debugOutFileName ) {
			$this->debugOutFile = fopen( $debugOutFileName, 'w' );
			if ( !$this->debugOutFile ) {
				$this->error( "Unable to open debug output file for writing" );
				exit( 1 );
			}
		}
		$this->loadUcd();
		$this->generateFirstChars();
	}

	function loadUcd() {
		$uxr = new UcdXmlReader( "{$this->dataDir}/ucd.all.grouped.xml" );
		$uxr->readChars( array( $this, 'charCallback' ) );
	}

	function charCallback( $data ) {
		// Skip non-printable characters,
		// but do not skip a normal space (U+0020) since
		// people like to use that as a fake no header symbol.
		$category = substr( $data['gc'], 0, 1 );
		if ( strpos( 'LNPS', $category ) === false
			&& $data['cp'] !== '0020' ) {
			return;
		}
		$cp = hexdec( $data['cp'] );

		// Skip the CJK ideograph blocks, as an optimisation measure.
		// UCA doesn't sort them properly anyway, without tailoring.
		if ( IcuCollation::isCjk( $cp ) ) {
			return;
		}

		// Skip the composed Hangul syllables, we will use the bare Jamo
		// as first letters
		if ( $data['block'] == 'Hangul Syllables' ) {
			return;
		}

		// Calculate implicit weight per UTS #10 v6.0.0, sec 7.1.3
		if ( $data['UIdeo'] === 'Y' ) {
			if ( $data['block'] == 'CJK Unified Ideographs'
				|| $data['block'] == 'CJK Compatibility Ideographs' )
			{
				$base = 0xFB40;
			} else {
				$base = 0xFB80;
			}
		} else {
			$base = 0xFBC0;
		}
		$a = $base + ( $cp >> 15 );
		$b = ( $cp & 0x7fff ) | 0x8000;

		$this->weights[$cp] = sprintf( ".%04X.%04X", $a, $b );

		if ( $data['dm'] !== '#' ) {
			$this->mappedChars[$cp] = true;
		}

		if ( $cp % 4096 == 0 ) {
			print "{$data['cp']}\n";
		}
	}

	function generateFirstChars() {
		$file = fopen( "{$this->dataDir}/allkeys.txt", 'r' );
		if ( !$file ) {
			$this->error( "Unable to open allkeys.txt" );
			exit( 1 );
		}
		global $IP;
		$outFile = fopen( "$IP/serialized/first-letters-root.ser", 'w' );
		if ( !$outFile ) {
			$this->error( "Unable to open output file first-letters-root.ser" );
			exit( 1 );
		}

		$goodTertiaryChars = array();

		// For each character with an entry in allkeys.txt, overwrite the implicit
		// entry in $this->weights that came from the UCD.
		// Also gather a list of tertiary weights, for use in selecting the group header
		while ( false !== ( $line = fgets( $file ) ) ) {
			// We're only interested in single-character weights, pick them out with a regex
			$line = trim( $line );
			if ( !preg_match( '/^([0-9A-F]+)\s*;\s*([^#]*)/', $line, $m ) ) {
				continue;
			}

			$cp = hexdec( $m[1] );
			$allWeights = trim( $m[2] );
			$primary = '';
			$tertiary = '';

			if ( !isset( $this->weights[$cp] ) ) {
				// Non-printable, ignore
				continue;
			}
			foreach ( StringUtils::explode( '[', $allWeights ) as $weightStr ) {
				preg_match_all( '/[*.]([0-9A-F]+)/', $weightStr, $m );
				if ( !empty( $m[1] ) ) {
					if ( $m[1][0] !== '0000' ) {
						$primary .= '.' . $m[1][0];
					}
					if ( $m[1][2] !== '0000' ) {
						$tertiary .= '.' . $m[1][2];
					}
				}
			}
			$this->weights[$cp] = $primary;
			if ( $tertiary === '.0008'
				|| $tertiary === '.000E' )
			{
				$goodTertiaryChars[$cp] = true;
			}
		}
		fclose( $file );

		// Identify groups of characters with the same primary weight
		$this->groups = array();
		asort( $this->weights, SORT_STRING );
		$prevWeight = reset( $this->weights );
		$group = array();
		foreach ( $this->weights as $cp => $weight ) {
			if ( $weight !== $prevWeight ) {
				$this->groups[$prevWeight] = $group;
				$prevWeight = $weight;
				if ( isset( $this->groups[$weight] ) ) {
					$group = $this->groups[$weight];
				} else {
					$group = array();
				}
			}
			$group[] = $cp;
		}
		if ( $group ) {
			$this->groups[$prevWeight] = $group;
		}

		// If one character has a given primary weight sequence, and a second
		// character has a longer primary weight sequence with an initial
		// portion equal to the first character, then remove the second
		// character. This avoids having characters like U+A732 (double A)
		// polluting the basic latin sort area.

		foreach ( $this->groups as $weight => $group ) {
			if ( preg_match( '/(\.[0-9A-F]*)\./', $weight, $m ) ) {
				if ( isset( $this->groups[$m[1]] ) ) {
					unset( $this->groups[$weight] );
				}
			}
		}

		ksort( $this->groups, SORT_STRING );

		// Identify the header character in each group
		$headerChars = array();
		$prevChar = "\000";
		$tertiaryCollator = new Collator( 'root' );
		$primaryCollator = new Collator( 'root' );
		$primaryCollator->setStrength( Collator::PRIMARY );
		$numOutOfOrder = 0;
		foreach ( $this->groups as $weight => $group ) {
			$uncomposedChars = array();
			$goodChars = array();
			foreach ( $group as $cp ) {
				if ( isset( $goodTertiaryChars[$cp] ) ) {
					$goodChars[] = $cp;
				}
				if ( !isset( $this->mappedChars[$cp] ) ) {
					$uncomposedChars[] = $cp;
				}
			}
			$x = array_intersect( $goodChars, $uncomposedChars );
			if ( !$x ) {
				$x = $uncomposedChars;
				if ( !$x ) {
					$x = $group;
				}
			}

			// Use ICU to pick the lowest sorting character in the selection
			$tertiaryCollator->sort( $x );
			$cp = $x[0];

			$char = codepointToUtf8( $cp );
			$headerChars[] = $char;
			if ( $primaryCollator->compare( $char, $prevChar ) <= 0 ) {
				$numOutOfOrder ++;
				/*
				printf( "Out of order: U+%05X > U+%05X\n",
					utf8ToCodepoint( $prevChar ),
					utf8ToCodepoint( $char ) );
				 */
			}
			$prevChar = $char;

			if ( $this->debugOutFile ) {
				fwrite( $this->debugOutFile, sprintf( "%05X %s %s (%s)\n", $cp, $weight, $char,
					implode( ' ', array_map( 'codepointToUtf8', $group ) ) ) );
			}
		}

		print "Out of order: $numOutOfOrder / " . count( $headerChars ) . "\n";

		fwrite( $outFile, serialize( $headerChars ) );
	}
}

class UcdXmlReader {
	public $fileName;
	public $callback;
	public $groupAttrs;
	public $xml;
	public $blocks = array();
	public $currentBlock;

	function __construct( $fileName ) {
		$this->fileName = $fileName;
	}

	public function readChars( $callback ) {
		$this->getBlocks();
		$this->currentBlock = reset( $this->blocks );
		$xml = $this->open();
		$this->callback = $callback;

		while ( $xml->name !== 'repertoire' && $xml->next() );

		while ( $xml->read() ) {
			if ( $xml->nodeType == XMLReader::ELEMENT ) {
				if ( $xml->name === 'group' ) {
					$this->groupAttrs = $this->readAttributes();
				} elseif ( $xml->name === 'char' ) {
					$this->handleChar();
				}
			} elseif ( $xml->nodeType === XMLReader::END_ELEMENT ) {
				if ( $xml->name === 'group' ) {
					$this->groupAttrs = array();
				}
			}
		}
		$xml->close();
	}

	protected function open() {
		$this->xml = new XMLReader;
		$this->xml->open( $this->fileName );
		if ( !$this->xml ) {
			throw new MWException( __METHOD__.": unable to open {$this->fileName}" );
		}
		while ( $this->xml->name !== 'ucd' && $this->xml->read() );
		$this->xml->read();
		return $this->xml;
	}

	/**
	 * Read the attributes of the current element node and return them
	 * as an array
	 * @return array
	 */
	protected function readAttributes() {
		$attrs = array();
		while ( $this->xml->moveToNextAttribute() ) {
			$attrs[$this->xml->name] = $this->xml->value;
		}
		return $attrs;
	}

	protected function handleChar() {
		$attrs = $this->readAttributes() + $this->groupAttrs;
		if ( isset( $attrs['cp'] ) ) {
			$first = $last = hexdec( $attrs['cp'] );
		} else {
			$first = hexdec( $attrs['first-cp'] );
			$last = hexdec( $attrs['last-cp'] );
			unset( $attrs['first-cp'] );
			unset( $attrs['last-cp'] );
		}

		for ( $cp = $first; $cp <= $last; $cp++ ) {
			$hexCp = sprintf( "%04X", $cp );
			foreach ( array( 'na', 'na1' ) as $nameProp ) {
				if ( isset( $attrs[$nameProp] ) ) {
					$attrs[$nameProp] = str_replace( '#', $hexCp, $attrs[$nameProp] );
				}
			}

			while ( $this->currentBlock ) {
				if ( $cp < $this->currentBlock[0] ) {
					break;
				} elseif ( $cp <= $this->currentBlock[1] ) {
					$attrs['block'] = key( $this->blocks );
					break;
				} else {
					$this->currentBlock = next( $this->blocks );
				}
			}

			$attrs['cp'] = $hexCp;
			call_user_func( $this->callback, $attrs );
		}
	}

	public function getBlocks() {
		if ( $this->blocks ) {
			return $this->blocks;
		}

		$xml = $this->open();
		while ( $xml->name !== 'blocks' && $xml->read() );

		while ( $xml->read() ) {
			if ( $xml->nodeType == XMLReader::ELEMENT ) {
				if ( $xml->name === 'block' ) {
					$attrs = $this->readAttributes();
					$first = hexdec( $attrs['first-cp'] );
					$last = hexdec( $attrs['last-cp'] );
					$this->blocks[$attrs['name']] = array( $first, $last );
				}
			}
		}
		$xml->close();
		return $this->blocks;
	}

}

$maintClass = 'GenerateCollationData';
require_once( RUN_MAINTENANCE_IF_MAIN );
