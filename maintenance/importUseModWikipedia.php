<?php

/**
 * A script to read a dump of the English Wikipedia from the UseModWiki period, and to
 * generate an XML dump in MediaWiki format.
 *
 * Some relevant code was ported from UseModWiki 0.92.
 *
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );
require_once( dirname( __FILE__ ) .'/../includes/normal/UtfNormalUtil.php' );


class ImportUseModWikipedia extends Maintenance {
	var $encodeMap, $decodeMap;

	var $deepRenames = array(
		'JimboWales' => 983862286,
		'TexaS' => 983918410,
		'HistoryOfUnitedStatesTalk' => 984795423,
		'MetallicA' => 985128533,
		'PythagoreanTheorem' => 985225545,
		'TheCanonofScripture' => 985368223,
		'TaoTehChing' => 985368222,
		//'TheMostRemarkableFormulaInTheWorld' => 985368221,
		'TheRecorder' => 985368220,
		'GladstoneOregon' => 985368219,
		#'UnitedStatesConstitution/AmendmentTwo' => 
	);

	var $replacements = array();

	var $renameTextLinksOps = array(
		983846265 => array(
			'TestIgnore' => 'IgnoreTest',
		),
		983848080 => array(
			'UnitedLocomotiveWorks' => 'Atlas Shrugged/United Locomotive Works'
		),
		983856376 => array(
			'WikiPedia' => 'Wikipedia',
		),
		983896152 => array(
			'John_F_Kennedy' => 'John_F._Kennedy',
		),
		983905871 => array(
			'LarrySanger' => 'Larry_Sanger'
		),
		984697068 => array(
			'UnitedStates' => 'United States',
		),
		984792748 => array(
			'LibertarianisM' => 'Libertarianism'
		),
		985327832 => array(
			'AnarchisM' => 'Anarchism',
		),
		985290063 => array(
			'HistoryOfUnitedStatesDiscussion' => 'History_Of_United_States_Discussion'
		),
		985290091 => array(
			'BritishEmpire' => 'British Empire'
		),
		/*
		985468958 => array(
			'ScienceFiction' => 'Science fiction',
		),*/
	);

	/**
	 * Hack for observed substitution issues
	 */
	var $skipSelfSubstitution = array(
		'Pythagorean_Theorem',
		'The_Most_Remarkable_Formula_In_The_World',
		'Wine',
	);

	var $unixLineEndingsOps = array(
		987743732 => 'Wikipedia_FAQ'
	);

	var $replacementsDone = array();

	var $moveLog = array();
	var $moveDests = array();
	var $revId;

	var $rc = array();
	var $textCache = array();
	var $blacklist = array();

	var $FS, $FS1, $FS2, $FS3;
	var $FreeLinkPattern, $UrlPattern, $LinkPattern, $InterLinkPattern;

	var $cp1252Table = <<<EOT
0x00	0x0000
0x01	0x0001
0x02	0x0002
0x03	0x0003
0x04	0x0004
0x05	0x0005
0x06	0x0006
0x07	0x0007
0x08	0x0008
0x09	0x0009
0x0a	0x000a
0x0b	0x000b
0x0c	0x000c
0x0d	0x000d
0x0e	0x000e
0x0f	0x000f
0x10	0x0010
0x11	0x0011
0x12	0x0012
0x13	0x0013
0x14	0x0014
0x15	0x0015
0x16	0x0016
0x17	0x0017
0x18	0x0018
0x19	0x0019
0x1a	0x001a
0x1b	0x001b
0x1c	0x001c
0x1d	0x001d
0x1e	0x001e
0x1f	0x001f
0x20	0x0020
0x21	0x0021
0x22	0x0022
0x23	0x0023
0x24	0x0024
0x25	0x0025
0x26	0x0026
0x27	0x0027
0x28	0x0028
0x29	0x0029
0x2a	0x002a
0x2b	0x002b
0x2c	0x002c
0x2d	0x002d
0x2e	0x002e
0x2f	0x002f
0x30	0x0030
0x31	0x0031
0x32	0x0032
0x33	0x0033
0x34	0x0034
0x35	0x0035
0x36	0x0036
0x37	0x0037
0x38	0x0038
0x39	0x0039
0x3a	0x003a
0x3b	0x003b
0x3c	0x003c
0x3d	0x003d
0x3e	0x003e
0x3f	0x003f
0x40	0x0040
0x41	0x0041
0x42	0x0042
0x43	0x0043
0x44	0x0044
0x45	0x0045
0x46	0x0046
0x47	0x0047
0x48	0x0048
0x49	0x0049
0x4a	0x004a
0x4b	0x004b
0x4c	0x004c
0x4d	0x004d
0x4e	0x004e
0x4f	0x004f
0x50	0x0050
0x51	0x0051
0x52	0x0052
0x53	0x0053
0x54	0x0054
0x55	0x0055
0x56	0x0056
0x57	0x0057
0x58	0x0058
0x59	0x0059
0x5a	0x005a
0x5b	0x005b
0x5c	0x005c
0x5d	0x005d
0x5e	0x005e
0x5f	0x005f
0x60	0x0060
0x61	0x0061
0x62	0x0062
0x63	0x0063
0x64	0x0064
0x65	0x0065
0x66	0x0066
0x67	0x0067
0x68	0x0068
0x69	0x0069
0x6a	0x006a
0x6b	0x006b
0x6c	0x006c
0x6d	0x006d
0x6e	0x006e
0x6f	0x006f
0x70	0x0070
0x71	0x0071
0x72	0x0072
0x73	0x0073
0x74	0x0074
0x75	0x0075
0x76	0x0076
0x77	0x0077
0x78	0x0078
0x79	0x0079
0x7a	0x007a
0x7b	0x007b
0x7c	0x007c
0x7d	0x007d
0x7e	0x007e
0x7f	0x007f
0x80	0x20ac
0x81	0x0081
0x82	0x201a
0x83	0x0192
0x84	0x201e
0x85	0x2026
0x86	0x2020
0x87	0x2021
0x88	0x02c6
0x89	0x2030
0x8a	0x0160
0x8b	0x2039
0x8c	0x0152
0x8d	0x008d
0x8e	0x017d
0x8f	0x008f
0x90	0x0090
0x91	0x2018
0x92	0x2019
0x93	0x201c
0x94	0x201d
0x95	0x2022
0x96	0x2013
0x97	0x2014
0x98	0x02dc
0x99	0x2122
0x9a	0x0161
0x9b	0x203a
0x9c	0x0153
0x9d	0x009d
0x9e	0x017e
0x9f	0x0178
0xa0	0x00a0
0xa1	0x00a1
0xa2	0x00a2
0xa3	0x00a3
0xa4	0x00a4
0xa5	0x00a5
0xa6	0x00a6
0xa7	0x00a7
0xa8	0x00a8
0xa9	0x00a9
0xaa	0x00aa
0xab	0x00ab
0xac	0x00ac
0xad	0x00ad
0xae	0x00ae
0xaf	0x00af
0xb0	0x00b0
0xb1	0x00b1
0xb2	0x00b2
0xb3	0x00b3
0xb4	0x00b4
0xb5	0x00b5
0xb6	0x00b6
0xb7	0x00b7
0xb8	0x00b8
0xb9	0x00b9
0xba	0x00ba
0xbb	0x00bb
0xbc	0x00bc
0xbd	0x00bd
0xbe	0x00be
0xbf	0x00bf
0xc0	0x00c0
0xc1	0x00c1
0xc2	0x00c2
0xc3	0x00c3
0xc4	0x00c4
0xc5	0x00c5
0xc6	0x00c6
0xc7	0x00c7
0xc8	0x00c8
0xc9	0x00c9
0xca	0x00ca
0xcb	0x00cb
0xcc	0x00cc
0xcd	0x00cd
0xce	0x00ce
0xcf	0x00cf
0xd0	0x00d0
0xd1	0x00d1
0xd2	0x00d2
0xd3	0x00d3
0xd4	0x00d4
0xd5	0x00d5
0xd6	0x00d6
0xd7	0x00d7
0xd8	0x00d8
0xd9	0x00d9
0xda	0x00da
0xdb	0x00db
0xdc	0x00dc
0xdd	0x00dd
0xde	0x00de
0xdf	0x00df
0xe0	0x00e0
0xe1	0x00e1
0xe2	0x00e2
0xe3	0x00e3
0xe4	0x00e4
0xe5	0x00e5
0xe6	0x00e6
0xe7	0x00e7
0xe8	0x00e8
0xe9	0x00e9
0xea	0x00ea
0xeb	0x00eb
0xec	0x00ec
0xed	0x00ed
0xee	0x00ee
0xef	0x00ef
0xf0	0x00f0
0xf1	0x00f1
0xf2	0x00f2
0xf3	0x00f3
0xf4	0x00f4
0xf5	0x00f5
0xf6	0x00f6
0xf7	0x00f7
0xf8	0x00f8
0xf9	0x00f9
0xfa	0x00fa
0xfb	0x00fb
0xfc	0x00fc
0xfd	0x00fd
0xfe	0x00fe
0xff	0x00ff
EOT;
	public function __construct() {
		parent::__construct();
		$this->addOption( 'datadir', 'the value of $DataDir from wiki.cgi', true, true );
		$this->addOption( 'outfile', 'the name of the output XML file', true, true );
		$this->initLinkPatterns();

		$this->encodeMap = $this->decodeMap = array();
		foreach ( explode( "\n", $this->cp1252Table ) as $line ) {
			list( $source, $dest ) = explode( "\t", $line );
			$sourceChar = chr( base_convert( substr( $source, 2 ), 16, 10 ) );
			$destChar = codepointToUtf8( base_convert( substr( $dest, 2 ), 16, 10 ) );
			$this->encodeMap[$sourceChar] = $destChar;
			$this->decodeMap[$destChar] = $sourceChar;
		}
	}

	function initLinkPatterns() {
		# Field separators are used in the URL-style patterns below.
		$this->FS  = "\xb3";      # The FS character is a superscript "3"
		$this->FS1 = $this->FS . "1";   # The FS values are used to separate fields
		$this->FS2 = $this->FS . "2";   # in stored hashtables and other data structures.
		$this->FS3 = $this->FS . "3";   # The FS character is not allowed in user data.

		$UpperLetter = "[A-Z";
		$LowerLetter = "[a-z";
		$AnyLetter   = "[A-Za-z";
		$AnyLetter .= "_0-9";
		$UpperLetter .= "]"; $LowerLetter .= "]"; $AnyLetter .= "]";

		# Main link pattern: lowercase between uppercase, then anything
		$LpA = $UpperLetter . "+" . $LowerLetter . "+" . $UpperLetter
			. $AnyLetter . "*";
		# Optional subpage link pattern: uppercase, lowercase, then anything
		$LpB = $UpperLetter . "+" . $LowerLetter . "+" . $AnyLetter . "*";

		# Loose pattern: If subpage is used, subpage may be simple name
		$this->LinkPattern = "((?:(?:$LpA)?\\/$LpB)|$LpA)";
		$QDelim = '(?:"")?';     # Optional quote delimiter (not in output)
		$this->LinkPattern .= $QDelim;

		# Inter-site convention: sites must start with uppercase letter
		# (Uppercase letter avoids confusion with URLs)
		$InterSitePattern = $UpperLetter . $AnyLetter . "+";
		$this->InterLinkPattern = "((?:$InterSitePattern:[^\\]\\s\"<>{$this->FS}]+)$QDelim)";

		$AnyLetter = "[-,. _0-9A-Za-z]";
		$this->FreeLinkPattern = "($AnyLetter+)";
		$this->FreeLinkPattern = "((?:(?:$AnyLetter+)?\\/)?$AnyLetter+)";
		$this->FreeLinkPattern .= $QDelim;

		# Url-style links are delimited by one of:
		#   1.  Whitespace                           (kept in output)
		#   2.  Left or right angle-bracket (< or >) (kept in output)
		#   3.  Right square-bracket (])             (kept in output)
		#   4.  A single double-quote (")            (kept in output)
		#   5.  A $FS (field separator) character    (kept in output)
		#   6.  A double double-quote ("")           (removed from output)

		$UrlProtocols = "http|https|ftp|afs|news|nntp|mid|cid|mailto|wais|"
			. "prospero|telnet|gopher";
		$UrlProtocols .= '|file';
		$this->UrlPattern = "((?:(?:$UrlProtocols):[^\\]\\s\"<>{$this->FS}]+)$QDelim)";
		$ImageExtensions = "(gif|jpg|png|bmp|jpeg)";
		$RFCPattern = "RFC\\s?(\\d+)";
		$ISBNPattern = "ISBN:?([0-9- xX]{10,})";
	}

	function execute() {
		$this->articleFileName = '/tmp/importUseMod.' . mt_rand( 0, 0x7ffffff ) . '.tmp';
		$this->patchFileName = '/tmp/importUseMod.' . mt_rand( 0, 0x7ffffff ) . '.tmp';
		$this->dataDir = $this->getOption( 'datadir' );
		$this->outFile = fopen( $this->getOption( 'outfile' ), 'w' );
		if ( !$this->outFile ) {
			echo "Unable to open output file\n";
			return 1;
		}
		$this->writeXmlHeader();
		$this->readRclog();
		$this->writeMoveLog();
		$this->writeRevisions();
		$this->reconcileCurrentRevs();
		$this->writeXmlFooter();
		unlink( $this->articleFileName );
		unlink( $this->patchFileName );
		return 0;
	}

	function writeXmlHeader() {
		fwrite( $this->outFile, <<<EOT
<mediawiki xmlns="http://www.mediawiki.org/xml/export-0.3/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.mediawiki.org/xml/export-0.3/ http://www.mediawiki.org/xml/export-0.3.xsd" version="0.3" xml:lang="en">
  <siteinfo>
    <sitename>Wikipedia</sitename>
    <base>http://www.wikipedia.com/</base>
    <generator>MediaWiki 1.18alpha importUseModWikipedia.php</generator>
    <case>case-sensitive</case>
    <namespaces>
      <namespace key="0" />
    </namespaces>
  </siteinfo>

EOT
		);
	}

	function writeXmlFooter() {
		fwrite( $this->outFile, "</mediawiki>\n" );
	}

	function readRclog() {
		$rcFile = fopen( "{$this->dataDir}/rclog", 'r' );
		while ( $line = fgets( $rcFile ) ) {
			$bits = explode( $this->FS3, $line );
			if ( count( $bits ) !== 7 ) {
				echo "Error reading rclog\n";
				return;
			}
			$params = array(
				'timestamp' => $bits[0],
				'rctitle' => $bits[1],
				'summary' => $bits[2],
				'minor' => $bits[3],
				'host' => $bits[4],
				'kind' => $bits[5],
				'extra' => array()
			);
			$extraList = explode( $this->FS2, $bits[6] );

			for ( $i = 0; $i < count( $extraList ); $i += 2 ) {
				$params['extra'][$extraList[$i]] = $extraList[$i + 1];
			}
			$this->rc[$params['timestamp']][] = $params;
		}
	}

	function writeMoveLog() {
		$this->moveLog = array();
		$deepRenames = $this->deepRenames;
		echo "Calculating move log...\n";
		$this->processDiffFile( array( $this, 'moveLogCallback' ) );

		// We have the timestamp intervals, now make a guess at the actual timestamp
		foreach ( $this->moveLog as $newTitle => $params ) {
			// Is there a time specified?
			$drTime = false;
			if ( isset( $deepRenames[$params['old']] ) ) {
				$drTime = $deepRenames[$params['old']];
				if ( $drTime !== '?' ) {
					if ( ( !isset( $params['endTime'] ) || $drTime < $params['endTime'] )
						&& $drTime > $params['startTime'] ) 
					{
						$this->moveLog[$newTitle]['timestamp'] = $drTime;
						$this->moveLog[$newTitle]['deep'] = true;

						echo "{$params['old']} -> $newTitle at $drTime\n";
						unset( $deepRenames[$params['old']] );
						continue;
					} else {
						echo "WARNING: deep rename time invalid: {$params['old']}\n";
						unset( $deepRenames[$params['old']] );
					}
				}
			}

			// Guess that it is one second after the last edit to the page before it was moved
			$this->moveLog[$newTitle]['timestamp'] = $params['startTime'] + 1;
			if ( $drTime === '?' ) {
				$this->moveLog[$newTitle]['deep'] = true;
				unset( $deepRenames[$params['old']] );
			}
			if ( isset( $params['endTime'] ) ) {
				$this->printLatin1( "{$params['old']} -> $newTitle between " . 
					"{$params['startTime']} and {$params['endTime']}\n" );
			} else {
				$this->printLatin1( "{$params['old']} -> $newTitle after " . 
					"{$params['startTime']}\n" );
			}
		}

		// Write the move log to the XML file
		$id = 1;
		foreach ( $this->moveLog as $newTitle => $params ) {
			$out = "<logitem>\n" . 
				$this->element( 'id', $id++ ) .
				$this->element( 'timestamp', wfTimestamp( TS_ISO_8601, $params['timestamp'] ) ) .
				"<contributor>\n" .
				$this->element( 'username', 'UseModWiki admin' ) .
				"</contributor>" .
				$this->element( 'type', 'move' ) .
				$this->element( 'action', 'move' ) .
				$this->element( 'logtitle', $params['old'] ) .
				"<params xml:space=\"preserve\">" .
				htmlspecialchars( $this->encode( "{$newTitle}\n1" ) ) .
				"</params>\n" .
				"</logitem>\n";
			fwrite( $this->outFile, $out );
		}

		// Check for remaining deep rename entries
		if ( $deepRenames ) {
			echo "WARNING: the following entries in \$this->deepRenames are " .
				"invalid, since no such move exists:\n" .
				implode( "\n", array_keys( $deepRenames ) ) .
				"\n\n";
		}

	}

	function element( $name, $value ) {
		return "<$name>" . htmlspecialchars( $this->encode( $value ) ) . "</$name>\n";
	}

	function moveLogCallback( $entry ) {
		$rctitle = $entry['rctitle'];
		$title = $entry['title'];
		$this->moveDests[$rctitle] = $title;

		if ( $rctitle === $title ) {
			if ( isset( $this->moveLog[$rctitle] )
				&& !isset( $this->moveLog[$rctitle]['endTime'] ) )
			{
				// This is the latest time that the page could have been moved
				$this->moveLog[$rctitle]['endTime'] = $entry['timestamp'];
			}
		} else {
			if ( !isset( $this->moveLog[$rctitle] ) ) {
				// Initialise the move log entry
				$this->moveLog[$rctitle] = array(
					'old' => $title
				);
			}
			// Update the earliest time the page could have been moved
			$this->moveLog[$rctitle]['startTime'] = $entry['timestamp'];
		}
	}

	function writeRevisions() {
		$this->numGoodRevs = 0;
		$this->revId = 1;
		$this->processDiffFile( array( $this, 'revisionCallback' ) );
		echo "\n\nImported {$this->numGoodRevs} out of {$this->numRevs}\n";
	}

	function revisionCallback( $params ) {
		$origTitle = $params['title'];
		$title = $params['rctitle'];
		$editTime = $params['timestamp'];

		if ( isset( $this->blacklist[$title] ) ) {
			return;
		}
		$this->doPendingOps( $editTime );

		$origText = $this->getText( $title );
		$text = $this->patch( $origText, $params['diff'] );
		if ( $text === false ) {
			echo "$editTime $title attempting resolution...\n";
			$linkSubstitutes = $this->resolveFailedDiff( $origText, $params['diff'] );
			if ( !$linkSubstitutes ) {
				$this->printLatin1( "$editTime $title DIFF FAILED\n" );
				$this->blacklist[$title] = true;
				return;
			}
			$this->printLatin1( "$editTime $title requires substitutions:\n" );
			$time = $editTime - 1;
			foreach ( $linkSubstitutes as $old => $new ) {
				$this->printLatin1( "SUBSTITUTE $old -> $new\n" );
				$this->renameTextLinks( $old, $new, $time-- );
			}
			$origText = $this->getText( $title );
			$text = $this->patch( $origText, $params['diff'] );
			if ( $text === false ) {
				$this->printLatin1( "$editTime $title STILL FAILS!\n" );
				$this->blacklist[$title] = true;
				return;
			}

			echo "\n";
		}

		$params['text'] = $text;
		$this->saveRevision( $params );
		$this->numGoodRevs++;
		#$this->printLatin1( "$editTime $title\n" );
	}

	function doPendingOps( $editTime ) {
		foreach ( $this->moveLog as $newTitle => $entry ) {
			if ( $entry['timestamp'] <= $editTime ) {
				unset( $this->moveLog[$newTitle] );
				if ( isset( $entry['deep'] ) ) {
					$this->renameTextLinks( $entry['old'], $newTitle, $entry['timestamp'] );
				}
			}
		}

		foreach ( $this->renameTextLinksOps as $renameTime => $replacements ) {
			if ( $editTime >= $renameTime ) {
				foreach ( $replacements as $old => $new ) {
					$this->printLatin1( "SUBSTITUTE $old -> $new\n" );
					$this->renameTextLinks( $old, $new, $renameTime );
				}
				unset( $this->renameTextLinksOps[$renameTime] );
			}
		}

		foreach ( $this->unixLineEndingsOps as $fixTime => $title ) {
			if ( $editTime >= $fixTime ) {
				$this->printLatin1( "$fixTime $title FIXING LINE ENDINGS\n" );
				$text = $this->getText( $title );
				$text = str_replace( "\r", '', $text );
				$this->saveRevision( array(
					'rctitle' => $title,
					'timestamp' => $fixTime,
					'extra' => array( 'name' => 'UseModWiki admin' ),
					'text' => $text,
					'summary' => 'Fixing line endings',
				) );
				unset( $this->unixLineEndingsOps[$fixTime] );
			}
		}
	}

	function patch( $source, $diff ) {
		file_put_contents( $this->articleFileName, $source );
		file_put_contents( $this->patchFileName, $diff );
		$error = wfShellExec( 
			wfEscapeShellArg(
				'patch',
				'-n',
				'-r', '-',
				'--no-backup-if-mismatch',
				'--binary',
				$this->articleFileName,
				$this->patchFileName
			) . ' 2>&1',
			$status
		);
		$text = file_get_contents( $this->articleFileName );
		if ( $status || $text === false ) {
			return false;
		} else {
			return $text;
		}
	}

	function resolveFailedDiff( $origText, $diff ) {
		$context = array();
		$rxRange = '\d+(?:,(\d+))?';
		$diffLines = explode( "\n", $diff );
		for ( $i = 0; $i < count( $diffLines ); $i++ ) {
			$diffLine = $diffLines[$i];
			if ( !preg_match( '/^(\d+)(?:,\d+)?[acd]\d+(?:,\d+)?$/', $diffLine, $m ) ) {
				continue;
			}

			$sourceIndex = intval( $m[1] );
			$i++;
			while ( $i < count( $diffLines ) && substr( $diffLines[$i], 0, 1 ) === '<' ) {
				$context[$sourceIndex - 1] = substr( $diffLines[$i], 2 );
				$sourceIndex++;
				$i++;
			}
			$i--;
		}

		$changedLinks = array();
		$origLines = explode( "\n", $origText );
		foreach ( $context as $i => $contextLine ) {
			$origLine = isset( $origLines[$i] ) ? $origLines[$i] : '';
			if ( $contextLine === $origLine ) {
				continue;
			}
			$newChanges = $this->resolveTextChange( $origLine, $contextLine );
			if ( is_array( $newChanges ) ) {
				$changedLinks += $newChanges;
			} else {
				echo "Resolution failure on line " . ( $i + 1 ) . "\n";
				$this->printLatin1( $newChanges );
			}
		}

		return $changedLinks;
	}

	function resolveTextChange( $source, $dest ) {
		$changedLinks = array();
		$sourceLinks = $this->getLinkList( $source );
		$destLinks = $this->getLinkList( $dest );
		$newLinks = array_diff( $destLinks, $sourceLinks );
		$removedLinks = array_diff( $sourceLinks, $destLinks );

		// Match up the removed links with the new links
		foreach ( $newLinks as $j => $newLink ) {
			$minDistance = 100000000;
			$bestRemovedLink = false;
			foreach ( $removedLinks as $k => $removedLink ) {
				$editDistance = levenshtein( $newLink, $removedLink );
				if ( $editDistance < $minDistance ) {
					$minDistance = $editDistance;
					$bestRemovedLink = $removedLink;
				}
			}
			if ( $bestRemovedLink !== false ) {
				$changedLinks[$bestRemovedLink] = $newLink;
				$newLinks = array_diff( $newLinks, array( $newLink ) );
				$removedLinks = array_diff( $removedLinks, array( $bestRemovedLink ) );
			}
		}

		$proposal = $source;
		foreach ( $changedLinks as $removedLink => $newLink ) {
			$proposal = $this->substituteTextLinks( $removedLink, $newLink, $proposal );
		}
		if ( $proposal !== $dest ) {
			// Resolution failed
			$msg = "Source line: $source\n" .
				"Source links: " . implode( ', ', $sourceLinks ) . "\n" .
				"Context line: $dest\n" .
				"Context links: " . implode( ', ', $destLinks ) . "\n" .
				"Proposal: $proposal\n";
			return $msg;
		}
		return $changedLinks;
	}

	function processDiffFile( $callback ) {
		$diffFile = fopen( "{$this->dataDir}/diff_log", 'r' );

		$delimiter = "------\n";
		file_put_contents( $this->articleFileName, "Describe the new page here.\n" );

		$line = fgets( $diffFile );
		$lineNum = 1;
		if ( $line !== $delimiter ) {
			echo "Invalid diff file\n";
			return false;
		}
		$lastReportLine = 0;
		$this->numRevs = 0;

		while ( true ) {
			$line = fgets( $diffFile );
			$lineNum++;
			if ( $line === false ) {
				break;
			}
			if ( $lineNum > $lastReportLine + 1000 ) {
				$lastReportLine = $lineNum;
				fwrite( STDERR, "$lineNum       \r" );
				fflush( STDERR );
			}
			$line = trim( $line );
			if ( !preg_match( '/^([^|]+)\|(\d+)$/', $line, $matches ) ) {
				echo "Invalid header on line $lineNum\n";
				return true;
			}
			list( , $title, $editTime ) = $matches;

			$diff = '';
			$diffStartLine = $lineNum;
			while ( true ) {
				$line = fgets( $diffFile );
				$lineNum++;
				if ( $line === $delimiter ) {
					break;
				}
				if ( $line === false ) {
					break 2;
				}
				$diff .= $line;
			}

			$this->numRevs++;

			if ( !isset( $this->rc[$editTime] ) ) {
				$this->printLatin1( "$editTime $title DELETED, skipping\n" );
				continue;
			}

			if ( count( $this->rc[$editTime] ) == 1 ) {
				$params = $this->rc[$editTime][0];
			} else {
				$params = false;
				$candidates = '';
				foreach ( $this->rc[$editTime] as $rc ) {
					if ( $rc['rctitle'] === $title ) {
						$params = $rc;
						break;
					}
					if ( $candidates === '' ) {
						$candidates = $rc['rctitle'];
					} else {
						$candidates .= ', ' . $rc['rctitle'];
					}
				}
				if ( !$params ) {
					$this->printLatin1( "$editTime $title ERROR cannot resolve rclog\n" );
					$this->printLatin1( "$editTime $title CANDIDATES: $candidates\n" );
					continue;
				}
			}
			$params['diff'] = $diff;
			$params['title'] = $title;
			$params['diffStartLine'] = $diffStartLine;
			call_user_func( $callback, $params );
		}
		echo "\n";

		if ( !feof( $diffFile ) ) {
			echo "Stopped at line $lineNum\n";
		}
		return true;
	}

	function reconcileCurrentRevs() {
		foreach ( $this->textCache as $title => $text ) {
			$fileName = "{$this->dataDir}/page/";
			if ( preg_match( '/^[A-Z]/', $title, $m ) ) {
				$fileName .= $m[0];
			} else {
				$fileName .= 'other';
			}
			$fileName .= "/$title.db";

			if ( !file_exists( $fileName ) ) {
				$this->printLatin1( "ERROR: Cannot find page file for {$title}\n" );
				continue;
			}

			$fileContents = file_get_contents( $fileName );
			$page = $this->unserializeUseMod( $fileContents, $this->FS1 );
			$section = $this->unserializeUseMod( $page['text_default'], $this->FS2 );
			$data = $this->unserializeUseMod( $section['data'], $this->FS3 );
			$pageText = $data['text'];
			if ( $text !== $pageText ) {
				$substs = $this->resolveTextChange( $text, $pageText );
				if ( is_array( $substs ) ) {
					foreach ( $substs as $source => $dest ) {
						if ( isset( $this->moveLog[$dest] ) ) {
							$this->printLatin1( "ERROR: need deep rename: $source\n" );
						} else {
							$this->printLatin1( "ERROR: need substitute: $source -> $dest\n" );
						}
					}
				} else {
					$this->printLatin1( "ERROR: unresolved diff in $title:\n" );
					wfSuppressWarnings();
					$diff = xdiff_string_diff( $text, $pageText ) . '';
					wfRestoreWarnings();
					$this->printLatin1( "$diff\n" );
				}
			}
		}
	}

	function makeTitle( $titleText ) {
		return Title::newFromText( $this->encode( $titleText ) );
	}

	function getText( $titleText ) {
		if ( !isset( $this->textCache[$titleText] ) ) {
			return "Describe the new page here.\n";
		} else {
			return $this->textCache[$titleText];
		}
	}

	function saveRevision( $params ) {
		$this->textCache[$params['rctitle']] = $params['text'];

		$out = "<page>\n" .
			$this->element( 'title', $params['rctitle'] ) . 
			"<revision>\n" .
			$this->element( 'id', $this->revId ++ ) .
			$this->element( 'timestamp', wfTimestamp( TS_ISO_8601, $params['timestamp'] ) ) .
			"<contributor>\n";
		if ( isset( $params['extra']['name'] ) ) {
			$out .= $this->element( 'username', $params['extra']['name'] );
		}
		if ( isset( $params['extra']['id'] ) ) {
			$out .= $this->element( 'id', $params['extra']['id'] );
		}
		if ( isset( $params['host'] ) ) {
			$out .= $this->element( 'ip', $params['host'] );
		}
		$out .=
			"</contributor>\n" .
			$this->element( 'comment', $params['summary'] ) .
			"<text xml:space=\"preserve\">" . 
			htmlspecialchars( $this->encode( $params['text'] ) ) .
			"</text>\n" .
			"</revision>\n" . 
			"</page>\n";
		fwrite( $this->outFile, $out );
	}

	function renameTextLinks( $old, $new, $timestamp ) {
		$newWithUnderscores = $new;
		$old = str_replace( '_', ' ', $old );
		$new = str_replace( '_', ' ', $new );

		foreach ( $this->textCache as $title => $oldText ) {
			if ( $newWithUnderscores === $title
				&& in_array( $title, $this->skipSelfSubstitution ) ) 
			{
				// Hack to make Pythagorean_Theorem etc. work
				continue;
			}

			$newText = $this->substituteTextLinks( $old, $new, $oldText );
			if ( $oldText !== $newText ) {
				$this->saveRevision( array(
					'rctitle' => $title,
					'timestamp' => $timestamp,
					'text' => $newText,
					'extra' => array( 'name' => 'Page move link fixup script' ),
					'summary' => '',
					'minor' => true
				) );
			}
		}
	}

	function substituteTextLinks( $old, $new, $text ) {
		$this->saveUrl = array();
		$this->old = $old;
		$this->new = $new;

		$text = str_replace( $this->FS, '', $text ); # Remove separators (paranoia)
		$text = preg_replace_callback( '/(<pre>(.*?)<\/pre>)/is', 
			array( $this, 'storeRaw' ), $text );
		$text = preg_replace_callback( '/(<code>(.*?)<\/code>)/is', 
			array( $this, 'storeRaw' ), $text );
		$text = preg_replace_callback( '/(<nowiki>(.*?)<\/nowiki>)/s', 
			array( $this, 'storeRaw' ), $text );

		$text = preg_replace_callback( "/\[\[{$this->FreeLinkPattern}\|([^\]]+)\]\]/",
			array( $this, 'subFreeLink' ), $text );
		$text = preg_replace_callback( "/\[\[{$this->FreeLinkPattern}\]\]/",
			array( $this, 'subFreeLink' ), $text );
		$text = preg_replace_callback( "/(\[{$this->UrlPattern}\s+([^\]]+?)\])/", 
			array( $this, 'storeRaw' ), $text );
		$text = preg_replace_callback( "/(\[{$this->InterLinkPattern}\s+([^\]]+?)\])/", 
			array( $this, 'storeRaw' ), $text );
		$text = preg_replace_callback( "/(\[?{$this->UrlPattern}\]?)/", 
			array( $this, 'storeRaw' ), $text );
		$text = preg_replace_callback( "/(\[?{$this->InterLinkPattern}\]?)/",
			array( $this, 'storeRaw' ), $text );
		$text = preg_replace_callback( "/{$this->LinkPattern}/", 
			array( $this, 'subWikiLink' ), $text );

		$text = preg_replace_callback( "/{$this->FS}(\d+){$this->FS}/", 
			array( $this, 'restoreRaw' ), $text );   # Restore saved text
		return $text;
	}

	function getLinkList( $text ) {
		$this->saveUrl = array();
		$this->linkList = array();

		$text = str_replace( $this->FS, '', $text ); # Remove separators (paranoia)
		$text = preg_replace_callback( '/(<pre>(.*?)<\/pre>)/is', 
			array( $this, 'storeRaw' ), $text );
		$text = preg_replace_callback( '/(<code>(.*?)<\/code>)/is', 
			array( $this, 'storeRaw' ), $text );
		$text = preg_replace_callback( '/(<nowiki>(.*?)<\/nowiki>)/s', 
			array( $this, 'storeRaw' ), $text );

		$text = preg_replace_callback( "/\[\[{$this->FreeLinkPattern}\|([^\]]+)\]\]/",
			array( $this, 'storeLink' ), $text );
		$text = preg_replace_callback( "/\[\[{$this->FreeLinkPattern}\]\]/",
			array( $this, 'storeLink' ), $text );
		$text = preg_replace_callback( "/(\[{$this->UrlPattern}\s+([^\]]+?)\])/", 
			array( $this, 'storeRaw' ), $text );
		$text = preg_replace_callback( "/(\[{$this->InterLinkPattern}\s+([^\]]+?)\])/", 
			array( $this, 'storeRaw' ), $text );
		$text = preg_replace_callback( "/(\[?{$this->UrlPattern}\]?)/", 
			array( $this, 'storeRaw' ), $text );
		$text = preg_replace_callback( "/(\[?{$this->InterLinkPattern}\]?)/",
			array( $this, 'storeRaw' ), $text );
		$text = preg_replace_callback( "/{$this->LinkPattern}/", 
			array( $this, 'storeLink' ), $text );

		return $this->linkList;
	}

	function storeRaw( $m ) {
		$this->saveUrl[] = $m[1];
		return $this->FS . (count( $this->saveUrl ) - 1) . $this->FS;
	}

	function subFreeLink( $m ) {
		$link = $m[1];
		if ( isset( $m[2] ) ) {
			$name = $m[2];
		} else {
			$name = '';
		}
		$oldlink = $link;
		$link = preg_replace( '/^\s+/', '', $link );
		$link = preg_replace( '/\s+$/', '', $link );
		if ( $link == $this->old ) {
			$link = $this->new;
		} else {
			$link = $oldlink;  # Preserve spaces if no match
		}
		$link = "[[$link";
		if ( $name !== "" ) {
			$link .= "|$name";
		}
		$link .= "]]";
		return $this->storeRaw( array( 1 => $link ) );
	}

	function subWikiLink( $m ) {
		$link = $m[1];
		if ( $link == $this->old ) {
			$link = $this->new;
			if ( !preg_match( "/^{$this->LinkPattern}$/", $this->new ) ) {
				$link = "[[$link]]";
			}
		}
		return $this->storeRaw( array( 1 => $link ) );
	}

	function restoreRaw( $m ) {
		return $this->saveUrl[$m[1]];
	}

	function storeLink( $m ) {
		$this->linkList[] = $m[1];
		return $this->storeRaw( $m );
	}

	function encode( $s ) {
		return strtr( $s, $this->encodeMap );
	}

	function decode( $s ) {
		return strtr( $s, $this->decodeMap );
	}

	function printLatin1( $s ) {
		echo $this->encode( $s );
	}

	function unserializeUseMod( $s, $sep ) {
		$parts = explode( $sep, $s );
		$result = array();
		for ( $i = 0; $i < count( $parts ); $i += 2 ) {
			$result[$parts[$i]] = $parts[$i+1];
		}
		return $result;
	}
}

$maintClass = 'ImportUseModWikipedia';
require_once( DO_MAINTENANCE );
