<?php
/**
 * Handle messages in the language files.
 *
 * @package MediaWiki
 * @subpackage Maintenance
 */

class languages {
	private $mLanguages; # List of languages
	private $mRawMessages; # Raw list of the messages in each language
	private $mMessages; # Messages in each language (except for English), divided to groups
	private $mGeneralMessages; # General messages in English, divided to groups
	private $mIgnoredMessages = array(
		'sidebar',
		'addsection',
		'anonnotice',
		'catseparator',
		'googlesearch',
		'exif-make-value',
		'exif-model-value',
		'exif-software-value',
		'history_copyright',
		'licenses',
		'loginend',
		'loginlanguagelinks',
		'markaspatrolledlink',
		'newarticletextanon',
		'noarticletextanon',
		'number_of_watching_users_RCview',
		'pubmedurl',
		'randompage-url',
		'recentchanges-url',
		'rfcurl',
		'shareddescriptionfollows',
		'signupend',
		'sitenotice',
		'sitesubtitle',
		'sitetitle',
		'talkpagetext',
		'trackback',
		'trackbackexcerpt',
		'widthheight',
	); # All the messages which should be exist only in the English file
	private $mOptionalMessages = array(
		'imgmultigotopost',
		'linkprefix',
		'allpages-summary',
		'booksources-summary',
		'ipblocklist-summary',
		'listusers-summary',
		'longpages-summary',
		'preferences-summary',
		'specialpages-summary',
		'whatlinkshere-summary',
	); # All the messages which may be translated or not, depending on the language
	private $mEXIFMessages = array(
		'exif-imagewidth',
		'exif-imagelength',
		'exif-bitspersample',
		'exif-compression',
		'exif-photometricinterpretation',
		'exif-orientation',
		'exif-samplesperpixel',
		'exif-planarconfiguration',
		'exif-ycbcrsubsampling',
		'exif-ycbcrpositioning',
		'exif-xresolution',
		'exif-yresolution',
		'exif-resolutionunit',
		'exif-stripoffsets',
		'exif-rowsperstrip',
		'exif-stripbytecounts',
		'exif-jpeginterchangeformat',
		'exif-jpeginterchangeformatlength',
		'exif-transferfunction',
		'exif-whitepoint',
		'exif-primarychromaticities',
		'exif-ycbcrcoefficients',
		'exif-referenceblackwhite',
		'exif-datetime',
		'exif-imagedescription',
		'exif-make',
		'exif-model',
		'exif-software',
		'exif-artist',
		'exif-copyright',
		'exif-exifversion',
		'exif-flashpixversion',
		'exif-colorspace',
		'exif-componentsconfiguration',
		'exif-compressedbitsperpixel',
		'exif-pixelydimension',
		'exif-pixelxdimension',
		'exif-makernote',
		'exif-usercomment',
		'exif-relatedsoundfile',
		'exif-datetimeoriginal',
		'exif-datetimedigitized',
		'exif-subsectime',
		'exif-subsectimeoriginal',
		'exif-subsectimedigitized',
		'exif-exposuretime',
		'exif-exposuretime-format',
		'exif-fnumber',
		'exif-fnumber-format',
		'exif-exposureprogram',
		'exif-spectralsensitivity',
		'exif-isospeedratings',
		'exif-oecf',
		'exif-shutterspeedvalue',
		'exif-aperturevalue',
		'exif-brightnessvalue',
		'exif-exposurebiasvalue',
		'exif-maxaperturevalue',
		'exif-subjectdistance',
		'exif-meteringmode',
		'exif-lightsource',
		'exif-flash',
		'exif-focallength',
		'exif-focallength-format',
		'exif-subjectarea',
		'exif-flashenergy',
		'exif-spatialfrequencyresponse',
		'exif-focalplanexresolution',
		'exif-focalplaneyresolution',
		'exif-focalplaneresolutionunit',
		'exif-subjectlocation',
		'exif-exposureindex',
		'exif-sensingmethod',
		'exif-filesource',
		'exif-scenetype',
		'exif-cfapattern',
		'exif-customrendered',
		'exif-exposuremode',
		'exif-whitebalance',
		'exif-digitalzoomratio',
		'exif-focallengthin35mmfilm',
		'exif-scenecapturetype',
		'exif-gaincontrol',
		'exif-contrast',
		'exif-saturation',
		'exif-sharpness',
		'exif-devicesettingdescription',
		'exif-subjectdistancerange',
		'exif-imageuniqueid',
		'exif-gpsversionid',
		'exif-gpslatituderef',
		'exif-gpslatitude',
		'exif-gpslongituderef',
		'exif-gpslongitude',
		'exif-gpsaltituderef',
		'exif-gpsaltitude',
		'exif-gpstimestamp',
		'exif-gpssatellites',
		'exif-gpsstatus',
		'exif-gpsmeasuremode',
		'exif-gpsdop',
		'exif-gpsspeedref',
		'exif-gpsspeed',
		'exif-gpstrackref',
		'exif-gpstrack',
		'exif-gpsimgdirectionref',
		'exif-gpsimgdirection',
		'exif-gpsmapdatum',
		'exif-gpsdestlatituderef',
		'exif-gpsdestlatitude',
		'exif-gpsdestlongituderef',
		'exif-gpsdestlongitude',
		'exif-gpsdestbearingref',
		'exif-gpsdestbearing',
		'exif-gpsdestdistanceref',
		'exif-gpsdestdistance',
		'exif-gpsprocessingmethod',
		'exif-gpsareainformation',
		'exif-gpsdatestamp',
		'exif-gpsdifferential',
		'exif-compression-1',
		'exif-compression-6',
		'exif-photometricinterpretation-2',
		'exif-photometricinterpretation-6',
		'exif-orientation-1',
		'exif-orientation-2',
		'exif-orientation-3',
		'exif-orientation-4',
		'exif-orientation-5',
		'exif-orientation-6',
		'exif-orientation-7',
		'exif-orientation-8',
		'exif-planarconfiguration-1',
		'exif-planarconfiguration-2',
		'exif-xyresolution-i',
		'exif-xyresolution-c',
		'exif-colorspace-1',
		'exif-colorspace-ffff.h',
		'exif-componentsconfiguration-0',
		'exif-componentsconfiguration-1',
		'exif-componentsconfiguration-2',
		'exif-componentsconfiguration-3',
		'exif-componentsconfiguration-4',
		'exif-componentsconfiguration-5',
		'exif-componentsconfiguration-6',
		'exif-exposureprogram-0',
		'exif-exposureprogram-1',
		'exif-exposureprogram-2',
		'exif-exposureprogram-3',
		'exif-exposureprogram-4',
		'exif-exposureprogram-5',
		'exif-exposureprogram-6',
		'exif-exposureprogram-7',
		'exif-exposureprogram-8',
		'exif-subjectdistance-value',
		'exif-meteringmode-0',
		'exif-meteringmode-1',
		'exif-meteringmode-2',
		'exif-meteringmode-3',
		'exif-meteringmode-4',
		'exif-meteringmode-5',
		'exif-meteringmode-6',
		'exif-meteringmode-255',
		'exif-lightsource-0',
		'exif-lightsource-1',
		'exif-lightsource-2',
		'exif-lightsource-3',
		'exif-lightsource-4',
		'exif-lightsource-9',
		'exif-lightsource-10',
		'exif-lightsource-11',
		'exif-lightsource-12',
		'exif-lightsource-13',
		'exif-lightsource-14',
		'exif-lightsource-15',
		'exif-lightsource-17',
		'exif-lightsource-18',
		'exif-lightsource-19',
		'exif-lightsource-20',
		'exif-lightsource-21',
		'exif-lightsource-22',
		'exif-lightsource-23',
		'exif-lightsource-24',
		'exif-lightsource-255',
		'exif-focalplaneresolutionunit-2',
		'exif-sensingmethod-1',
		'exif-sensingmethod-2',
		'exif-sensingmethod-3',
		'exif-sensingmethod-4',
		'exif-sensingmethod-5',
		'exif-sensingmethod-7',
		'exif-sensingmethod-8',
		'exif-filesource-3',
		'exif-scenetype-1',
		'exif-customrendered-0',
		'exif-customrendered-1',
		'exif-exposuremode-0',
		'exif-exposuremode-1',
		'exif-exposuremode-2',
		'exif-whitebalance-0',
		'exif-whitebalance-1',
		'exif-scenecapturetype-0',
		'exif-scenecapturetype-1',
		'exif-scenecapturetype-2',
		'exif-scenecapturetype-3',
		'exif-gaincontrol-0',
		'exif-gaincontrol-1',
		'exif-gaincontrol-2',
		'exif-gaincontrol-3',
		'exif-gaincontrol-4',
		'exif-contrast-0',
		'exif-contrast-1',
		'exif-contrast-2',
		'exif-saturation-0',
		'exif-saturation-1',
		'exif-saturation-2',
		'exif-sharpness-0',
		'exif-sharpness-1',
		'exif-sharpness-2',
		'exif-subjectdistancerange-0',
		'exif-subjectdistancerange-1',
		'exif-subjectdistancerange-2',
		'exif-subjectdistancerange-3',
		'exif-gpslatitude-n',
		'exif-gpslatitude-s',
		'exif-gpslongitude-e',
		'exif-gpslongitude-w',
		'exif-gpsstatus-a',
		'exif-gpsstatus-v',
		'exif-gpsmeasuremode-2',
		'exif-gpsmeasuremode-3',
		'exif-gpsspeed-k',
		'exif-gpsspeed-m',
		'exif-gpsspeed-n',
		'exif-gpsdirection-t',
		'exif-gpsdirection-m',
	); # All the EXIF messages, may be set as optional if defined as such

	/**
	 * Load the list of languages: all the Messages*.php
	 * files in the languages directory.
	 *
	 * @param $exif Treat the EXIF messages?
	 */
	function __construct( $exif = true ) {
		global $IP;
		$dir = opendir( "$IP/languages" );
		while ( $file = readdir( $dir ) ) {
			if ( preg_match( "/Messages([^.]*?)\.php$/", $file, $matches ) ) {
				$this->mLanguages[] = str_replace( '_', '-', strtolower( substr( $matches[1], 0, 1 ) ) . substr( $matches[1], 1 ) );
			}
		}
		sort( $this->mLanguages );
		if ( !$exif ) {
			$this->mOptionalMessages = array_merge( $this->mOptionalMessages, $this->mEXIFMessages );
		}
	}

	/**
	 * Get the language list.
	 *
	 * @return The language list.
	 */
	public function getLanguages() {
		return $this->mLanguages;
	}

	/**
	 * Load the raw messages for a specific langauge from the messages file.
	 *
	 * @param $code The langauge code.
	 */
	private function loadRawMessages( $code ) {
		if ( isset( $this->mRawMessages[$code] ) ) {
			return;
		}
		global $IP;
		$filename = Language::getFileName( "$IP/languages/Messages", $code, '.php' );
		if ( file_exists( $filename ) ) {
			require( $filename );
			if ( isset( $messages ) ) {
				$this->mRawMessages[$code] = $messages;
			} else {
				$this->mRawMessages[$code] = array();
			}
		} else {
			$this->mRawMessages[$code] = array();
		}
	}

	/**
	 * Load the messages for a specific language (which is not English) and divide them to groups:
	 * all - all the messages.
	 * required - messages which should be translated in order to get a complete translation.
	 * optional - messages which can be translated, the fallback translation is used if not translated.
	 * obsolete - messages which should not be translated, either because they are not exist, or they are ignored messages.
	 * translated - messages which are either required or optional, but translated from English and needed.
	 *
	 * @param $code The language code.
	 */
	private function loadMessages( $code ) {
		if ( isset( $this->mMessages[$code] ) ) {
			return;
		}
		$this->loadRawMessages( $code );
		$this->loadGeneralMessages();
		$this->mMessages[$code]['all'] = $this->mRawMessages[$code];
		$this->mMessages[$code]['required'] = array();
		$this->mMessages[$code]['optional'] = array();
		$this->mMessages[$code]['obsolete'] = array();
		$this->mMessages[$code]['translated'] = array();
		foreach ( $this->mMessages[$code]['all'] as $key => $value ) {
			if ( isset( $this->mGeneralMessages['required'][$key] ) ) {
				$this->mMessages[$code]['required'][$key] = $value;
				$this->mMessages[$code]['translated'][$key] = $value;
			} else if ( isset( $this->mGeneralMessages['optional'][$key] ) ) {
				$this->mMessages[$code]['optional'][$key] = $value;
				$this->mMessages[$code]['translated'][$key] = $value;
			} else {
				$this->mMessages[$code]['obsolete'][$key] = $value;
			}
		}
	}

	/**
	 * Load the messages for English and divide them to groups:
	 * all - all the messages.
	 * required - messages which should be translated to other languages in order to get a complete translation.
	 * optional - messages which can be translated to other languages, but it's not required for a complete translation.
	 * ignored - messages which should not be translated to other languages.
	 * translatable - messages which are either required or optional, but can be translated from English.
	 */
	private function loadGeneralMessages() {
		if ( isset( $this->mGeneralMessages ) ) {
			return;
		}
		$this->loadRawMessages( 'en' );
		$this->mGeneralMessages['all'] = $this->mRawMessages['en'];
		$this->mGeneralMessages['required'] = array();
		$this->mGeneralMessages['optional'] = array();
		$this->mGeneralMessages['ignored'] = array();
		$this->mGeneralMessages['translatable'] = array();
		foreach ( $this->mGeneralMessages['all'] as $key => $value ) {
			if ( in_array( $key, $this->mIgnoredMessages ) ) {
				$this->mGeneralMessages['ignored'][$key] = $value;
			} else if ( in_array( $key, $this->mOptionalMessages ) ) {
				$this->mGeneralMessages['optional'][$key] = $value;
				$this->mGeneralMessages['translatable'][$key] = $value;
			} else {
				$this->mGeneralMessages['required'][$key] = $value;
				$this->mGeneralMessages['translatable'][$key] = $value;
			}
		}
	}

	/**
	 * Get all the messages for a specific langauge (not English), without the
	 * fallback language messages, divided to groups:
	 * all - all the messages.
	 * required - messages which should be translated in order to get a complete translation.
	 * optional - messages which can be translated, the fallback translation is used if not translated.
	 * obsolete - messages which should not be translated, either because they are not exist, or they are ignored messages.
	 * translated - messages which are either required or optional, but translated from English and needed.
	 *
	 * @param $code The langauge code.
	 *
	 * @return The messages in this language.
	 */
	public function getMessages( $code ) {
		$this->loadMessages( $code );
		return $this->mMessages[$code];
	}

	/**
	 * Get all the general English messages, divided to groups:
	 * all - all the messages.
	 * required - messages which should be translated to other languages in order to get a complete translation.
	 * optional - messages which can be translated to other languages, but it's not required for a complete translation.
	 * ignored - messages which should not be translated to other languages.
	 * translatable - messages which are either required or optional, but can be translated from English.
	 *
	 * @return The general English messages.
	 */
	public function getGeneralMessages() {
		$this->loadGeneralMessages();
		return $this->mGeneralMessages;
	}

	/**
	 * Get the untranslated messages for a specific language.
	 *
	 * @param $code The langauge code.
	 *
	 * @return The untranslated messages for this language.
	 */
	public function getUntranslatedMessages( $code ) {
		$this->loadGeneralMessages();
		$this->loadMessages( $code );
		$requiredGeneralMessages = array_keys( $this->mGeneralMessages['required'] );
		$requiredMessages = array_keys( $this->mMessages[$code]['required'] );
		$untranslatedMessages = array();
		foreach ( array_diff( $requiredGeneralMessages, $requiredMessages ) as $key ) {
			$untranslatedMessages[$key] = $this->mGeneralMessages['required'][$key];
		}
		return $untranslatedMessages;
	}

	/**
	 * Get the duplicate messages for a specific language.
	 *
	 * @param $code The langauge code.
	 *
	 * @return The duplicate messages for this language.
	 */
	public function getDuplicateMessages( $code ) {
		$this->loadGeneralMessages();
		$this->loadMessages( $code );
		$duplicateMessages = array();
		foreach ( $this->mMessages[$code]['translated'] as $key => $value ) {
			if ( $this->mGeneralMessages['translatable'][$key] == $value ) {
				$duplicateMessages[$key] = $value;
			}
		}
		return $duplicateMessages;
	}

	/**
	 * Get the messages which do not use some variables.
	 *
	 * @param $code The langauge code.
	 *
	 * @return The messages which do not use some variables in this language.
	 */
	public function getMessagesWithoutVariables( $code ) {
		$this->loadGeneralMessages();
		$this->loadMessages( $code );
		$variables = array( '\$1', '\$2', '\$3', '\$4', '\$5', '\$6', '\$7', '\$8', '\$9' );
		$messagesWithoutVariables = array();
		foreach ( $this->mMessages[$code]['translated'] as $key => $value ) {
			$missing = false;
			foreach ( $variables as $var ) {
				if ( preg_match( "/$var/sU", $this->mGeneralMessages['translatable'][$key] ) &&
					!preg_match( "/$var/sU", $value ) ) {
					$missing = true;
				}
			}
			if ( $missing ) {
				$messagesWithoutVariables[$key] = $value;
			}
		}
		return $messagesWithoutVariables;
	}

	/**
	 * Get the empty messages.
	 *
	 * @param $code The langauge code.
	 *
	 * @return The empty messages for this language.
	 */
	public function getEmptyMessages( $code ) {
		$this->loadGeneralMessages();
		$this->loadMessages( $code );
		$emptyMessages = array();
		foreach ( $this->mMessages[$code]['translated'] as $key => $value ) {
			if ( $value === '' || $value === '-' ) {
				$emptyMessages[$key] = $value;
			}
		}
		return $emptyMessages;
	}

	/**
	 * Get the messages with trailing whitespace.
	 *
	 * @param $code The langauge code.
	 *
	 * @return The messages with trailing whitespace in this language.
	 */
	public function getMessagesWithWhitespace( $code ) {
		$this->loadGeneralMessages();
		$this->loadMessages( $code );
		$messagesWithWhitespace = array();
		foreach ( $this->mMessages[$code]['translated'] as $key => $value ) {
			if ( $this->mGeneralMessages['translatable'][$key] !== '' && $value !== rtrim( $value ) ) {
				$messagesWithWhitespace[$key] = $value;
			}
		}
		return $messagesWithWhitespace;
	}

	/**
	 * Get the non-XHTML messages.
	 *
	 * @param $code The langauge code.
	 *
	 * @return The non-XHTML messages for this language.
	 */
	public function getNonXHTMLMessages( $code ) {
		$this->loadGeneralMessages();
		$this->loadMessages( $code );
		$wrongPhrases = array(
			'<hr *\\?>',
			'<br *\\?>',
			'<hr/>',
			'<br/>',
		);
		$wrongPhrases = '~(' . implode( '|', $wrongPhrases ) . ')~sDu';
		$nonXHTMLMessages = array();
		foreach ( $this->mMessages[$code]['translated'] as $key => $value ) {
			if ( preg_match( $wrongPhrases, $value ) ) {
				$nonXHTMLMessages[$key] = $value;
			}
		}
		return $nonXHTMLMessages;
	}

	/**
	 * Get the messages which include wrong characters.
	 *
	 * @param $code The langauge code.
	 *
	 * @return The messages which include wrong characters in this language.
	 */
	public function getMessagesWithWrongChars( $code ) {
		$this->loadGeneralMessages();
		$this->loadMessages( $code );
		$wrongChars = array(
			'[LRM]' => "\xE2\x80\x8E",
			'[RLM]' => "\xE2\x80\x8F",
			'[LRE]' => "\xE2\x80\xAA",
			'[RLE]' => "\xE2\x80\xAB",
			'[POP]' => "\xE2\x80\xAC",
			'[LRO]' => "\xE2\x80\xAD",
			'[RLO]' => "\xE2\x80\xAB",
			'[ZWSP]'=> "\xE2\x80\x8B",
			'[NBSP]'=> "\xC2\xA0",
			'[WJ]'  => "\xE2\x81\xA0",
			'[BOM]' => "\xEF\xBB\xBF",
			'[FFFD]'=> "\xEF\xBF\xBD",
		);
		$wrongRegExp = '/(' . implode( '|', array_values( $wrongChars ) ) . ')/sDu';
		$wrongCharsMessages = array();
		foreach ( $this->mMessages[$code]['translated'] as $key => $value ) {
			if ( preg_match( $wrongRegExp, $value ) ) {
				foreach ( $wrongChars as $viewableChar => $hiddenChar ) {
					$value = str_replace( $hiddenChar, $viewableChar, $value );
				}
				$wrongCharsMessages[$key] = $value;
			}
		}
		return $wrongCharsMessages;
	}

	/**
	 * Output a messages list
	 *
	 * @param $messages The messages list
	 * @param $code The language code
	 * @param $text The text to show before the list (optional)
	 * @param $level The display level (optional)
	 * @param $links Show links (optional)
	 * @param $wikilang The langauge of the wiki to display the list in, for the links (optional)
	 */
	public function outputMessagesList( $messages, $code, $text = '', $level = 2, $links = false, $wikilang = null ) {
		if ( count( $messages ) == 0 ) {
			return;
		}
		if ( $text ) {
			echo "$text\n";
		}
		if ( $level == 1 ) {
			echo "[messages are hidden]\n";
		} else {
			foreach ( $messages as $key => $value ) {
				if ( $links ) {
					$displayKey = ucfirst( $key );
					if ( !isset( $wikilang ) ) {
						global $wgContLang;
						$wikilang = $wgContLang->getCode();
					}
					if ( $code == $wikilang ) {
						$displayKey = "[[MediaWiki:$displayKey|$key]]";
					} else {
						$displayKey = "[[MediaWiki:$displayKey/$code|$key]]";
					}
				} else {
					$displayKey = $key;
				}
				if ( $level == 2 ) {
					echo "* $displayKey\n";
				} else {
					echo "* $displayKey:		'$value'\n";
				}
			}
		}
	}
}

?>
