<?php

require_once( dirname( __FILE__ ) . '/../Maintenance.php' );

require_once( dirname( __FILE__ ) . '/../../includes/normal/UtfNormalUtil.php' );

/**
 * Generates normalizer data files for Arabic and Malayalam.
 * For NFC see includes/normal.
 */
class GenerateNormalizerData extends Maintenance {
	var $dataFile;

	public function __construct() {
		parent::__construct();
		$this->addOption( 'unicode-data-file', 'The local location of the data file ' .
			'from http://unicode.org/Public/UNIDATA/UnicodeData.txt', false, true );
	}

	public function execute() {
		if ( !$this->hasOption( 'unicode-data-file' ) ) {
			$this->dataFile = 'UnicodeData.txt';
			if ( !file_exists( $this->dataFile ) ) {
				$this->error( "Unable to find UnicodeData.txt. Please specify its location with --unicode-data-file=<FILE>" );
				exit( 1 );
			}
		} else {
			$this->dataFile = $this->getOption( 'unicode-data-file' );
			if ( !file_exists( $this->dataFile ) ) {
				$this->error( 'Unable to find the specified data file.' );
				exit( 1 );
			}
		}

		$this->generateArabic();
		$this->generateMalayalam();
	}

	function generateArabic() {
		$file = fopen( $this->dataFile, 'r' );
		if ( !$file ) {
			$this->error( 'Unable to open the data file.' );
			exit( 1 );
		}

		// For the file format, see http://www.unicode.org/reports/tr44/
		$fieldNames = array(
			'Code',
			'Name',
			'General_Category',
			'Canonical_Combining_Class',
			'Bidi_Class',
			'Decomposition_Type_Mapping',
			'Numeric_Type_Value',
			'Bidi_Mirrored',
			'Unicode_1_Name',
			'ISO_Comment',
			'Simple_Uppercase_Mapping',
			'Simple_Lowercase_Mapping',
			'Simple_Titlecase_Mapping'
		);

		$pairs = array();

		$lineNum = 0;
		while ( false !== ( $line = fgets( $file ) ) ) {
			++$lineNum;

			# Strip comments
			$line = trim( substr( $line, 0, strcspn( $line, '#' ) ) );
			if ( $line === '' ) {
				continue;
			}

			# Split fields
			$numberedData = explode( ';', $line );
			$data = array();
			foreach ( $fieldNames as $number => $name ) {
				$data[$name] = $numberedData[$number];
			}

			$code = base_convert( $data['Code'], 16, 10 );
			if ( ( $code >= 0xFB50 && $code <= 0xFDFF ) # Arabic presentation forms A
				|| ( $code >= 0xFE70 && $code <= 0xFEFF ) ) # Arabic presentation forms B
			{
				if ( $data['Decomposition_Type_Mapping'] === '' ) {
					// No decomposition
					continue;
				}
				if ( !preg_match( '/^ *(<\w*>) +([0-9A-F ]*)$/',
					$data['Decomposition_Type_Mapping'], $m ) )
				{
					$this->error( "Can't parse Decomposition_Type/Mapping on line $lineNum" );
					$this->error( $line );
					continue;
				}

				$source = hexSequenceToUtf8( $data['Code'] );
				$dest = hexSequenceToUtf8( $m[2] );
				$pairs[$source] = $dest;
			}
		}

		global $IP;
		file_put_contents( "$IP/serialized/normalize-ar.ser", serialize( $pairs ) );
		echo "ar: " . count( $pairs ) . " pairs written.\n";
	}

	function generateMalayalam() {
		$hexPairs = array(
			# From http://unicode.org/versions/Unicode5.1.0/#Malayalam_Chillu_Characters
			'0D23 0D4D 200D' => '0D7A',
			'0D28 0D4D 200D' => '0D7B',
			'0D30 0D4D 200D' => '0D7C',
			'0D32 0D4D 200D' => '0D7D',
			'0D33 0D4D 200D' => '0D7E',

			# From http://permalink.gmane.org/gmane.science.linguistics.wikipedia.technical/46413
			'0D15 0D4D 200D' => '0D7F',
		);

		$pairs = array();
		foreach ( $hexPairs as $hexSource => $hexDest ) {
			$source = hexSequenceToUtf8( $hexSource );
			$dest = hexSequenceToUtf8( $hexDest );
			$pairs[$source] = $dest;
		}

		global $IP;
		file_put_contents( "$IP/serialized/normalize-ml.ser", serialize( $pairs ) );
		echo "ml: " . count( $pairs ) . " pairs written.\n";
	}
}

$maintClass = 'GenerateNormalizerData';
require_once( DO_MAINTENANCE );

