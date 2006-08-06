<?php
/**
 * Handle messages in the language files.
 *
 * @package MediaWiki
 * @subpackage Maintenance
 */

class languages {
	private $mList = array();
	private $mMessages = array();
	private $mTranslatableMessages = array();
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
		'linkprefix',
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
	);

	/**
	 * Load the list of languages: all the Messages*.php
	 * files in the languages directory.
	 */
	function __construct() {
		global $IP;
		$dir = opendir("$IP/languages");
		while ( $file = readdir( $dir ) ) {
			if ( preg_match( "/Messages([^.]*?)\.php$/", $file, $matches ) ) {
				$this->mList[] = str_replace( '_', '-', strtolower( substr( $matches[1], 0, 1 ) ) . substr( $matches[1], 1 ) );
			}
		}
		sort( $this->mList );
	}

	/**
	 * Get the language list.
	 *
	 * @return the language list
	 */
	public function getList() {
		return $this->mList;
	}

	/**
	 * Load the messages for a specific langauge from the messages file.
	 *
	 * @param $code The langauge code.
	 */
	private function loadMessages( $code ) {
		if ( !isset( $this->mMessages[$code] ) ) {
			global $IP;
			$filename = Language::getFileName( "$IP/languages/Messages", $code, '.php' );
			if ( file_exists( $filename ) ) {
				require( $filename );
				if ( isset( $messages ) ) {
					$this->mMessages[$code] = $messages;
					if ( $code == 'en' ) {
						$this->mTranslatableMessages = $this->mMessages['en'];
						foreach ( array_keys( $this->mTranslatableMessages ) as $key ) {
							if ( in_array( $key, $this->mIgnoredMessages ) ) {
								unset( $this->mTranslatableMessages[$key] );
							}
						}
					}
				} else {
					$this->mMessages[$code] = array();
				}
			} else {
				$this->mMessages[$code] = array();
			}
		}
	}

	/**
	 * Get all the messages for a specific langauge, without the fallback
	 * language messages.
	 *
	 * @param $code The langauge code.
	 *
	 * @return The messages in this language.
	 */
	public function getMessagesFor( $code ) {
		$this->loadMessages( $code );
		return $this->mMessages[$code];
	}

	/**
	 * Get all the messages which are translatable - not ignored messages.
	 *
	 * @param $code The langauge code.
	 *
	 * @return The messages in this language.
	 */
	public function getTranslatableMessages() {
		$this->loadMessages( 'en' );
		return $this->mTranslatableMessages;
	}

	/**
	 * Get the translated messages for a specific language.
	 *
	 * @param $code The langauge code.
	 *
	 * @return The translated messages for this language.
	 */
	public function getTranslatedMessages( $code ) {
		$this->loadMessages( 'en' );
		$this->loadMessages( $code );
		$translatedMessages = array();
		foreach ( $this->mTranslatableMessages as $key => $value ) {
			if ( isset( $this->mMessages[$code][$key] ) ) {
				$translatedMessages[$key] = $value;
			}
		}
		return $translatedMessages;
	}

	/**
	 * Get the untranslated messages for a specific language.
	 *
	 * @param $code The langauge code.
	 *
	 * @return The untranslated messages for this language.
	 */
	public function getUntranslatedMessages( $code ) {
		$this->loadMessages( 'en' );
		$this->loadMessages( $code );
		$untranslatedMessages = array();
		foreach ( $this->mTranslatableMessages as $key => $value ) {
			if ( !isset( $this->mMessages[$code][$key] ) ) {
				$untranslatedMessages[$key] = $value;
			}
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
		$this->loadMessages( 'en' );
		$this->loadMessages( $code );
		$duplicateMessages = array();
		foreach ( $this->mMessages[$code] as $key => $value ) {
			if ( @$this->mTranslatableMessages[$key] == $value ) {
				$duplicateMessages[$key] = $value;
			}
		}
		return $duplicateMessages;
	}

	/**
	 * Get the obsolete messages for a specific language.
	 *
	 * @param $code The langauge code.
	 *
	 * @return The obsolete messages for this language.
	 */
	public function getObsoleteMessages( $code ) {
		$this->loadMessages( 'en' );
		$this->loadMessages( $code );
		$obsoleteMessages = array();
		foreach ( $this->mMessages[$code] as $key => $value ) {
			if ( !isset( $this->mTranslatableMessages[$key] ) ) {
				$obsoleteMessages[$key] = $value;
			}
		}
		return $obsoleteMessages;
	}

	/**
	 * Get the messages which do not use some variables.
	 *
	 * @param $code The langauge code.
	 *
	 * @return The messages which do not use some variables in this language.
	 */
	public function getMessagesWithoutVariables( $code ) {
		$this->loadMessages( 'en' );
		$this->loadMessages( $code );
		$variables = array( '\$1', '\$2', '\$3', '\$4', '\$5', '\$6', '\$7', '\$8', '\$9' );
		$messagesWithoutVariables = array();
		foreach ( $this->mMessages[$code] as $key => $value ) {
			if ( isset( $this->mTranslatableMessages[$key] ) ) {
				$missing = array();
				foreach ( $variables as $var ) {
					if ( preg_match( "/$var/sU", $this->mTranslatableMessages[$key] ) &&
						!preg_match( "/$var/sU", $value ) ) {
						$missing[] = str_replace( '\$', '$', $var );
					}
				}
				if ( count( $missing ) > 0 ) {
					$messagesWithoutVariables[$key] = implode( ', ', $missing );
				}
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
		$this->loadMessages( 'en' );
		$this->loadMessages( $code );
		$emptyMessages = array();
		foreach ( $this->mMessages[$code] as $key => $value ) {
			if ( isset( $this->mTranslatableMessages[$key] ) &&
				( $this->mMessages[$code][$key] === '' || $this->mMessages[$code][$key] === '-' ) ) {
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
		$this->loadMessages( 'en' );
		$this->loadMessages( $code );
		$messagesWithWhitespace = array();
		foreach ( $this->mMessages[$code] as $key => $value ) {
			if ( isset( $this->mTranslatableMessages[$key] ) && $this->mTranslatableMessages[$key] !== '' &&
				$value !== rtrim( $value ) ) {
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
		$this->loadMessages( 'en' );
		$this->loadMessages( $code );
		$wrongPhrases = array(
			'<hr *\\?>',
			'<br *\\?>',
			'<hr/>',
			'<br/>',
		);
		$wrongPhrases = '~(' . implode( '|', $wrongPhrases ) . ')~sDu';
		$nonXHTMLMessages = array();
		foreach ( $this->mMessages[$code] as $key => $value ) {
			if ( isset( $this->mTranslatableMessages[$key] ) && preg_match( $wrongPhrases, $value ) ) {
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
		$this->loadMessages( 'en' );
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
		$nonXHTMLMessages = array();
		foreach ( $this->mMessages[$code] as $key => $value ) {
			if ( isset( $this->mTranslatableMessages[$key] ) && preg_match( $wrongRegExp, $value ) ) {
				foreach ( $wrongChars as $viewableChar => $hiddenChar ) {
					$value = str_replace( $hiddenChar, $viewableChar, $value );
				}
				$nonXHTMLMessages[$key] = $value;
			}
		}
		return $nonXHTMLMessages;
	}

	/**
	 * Output a messages list.
	 *
	 * @param $messages The messages list.
	 * @param $text The text to show before the list (optional).
	 * @param $hideMessages Hide the real messages if specified.
	 */
	public function outputMessagesList( $messages, $text = '', $hideMessages = false ) {
		if ( count( $messages ) > 0 ) {
			if ( $text ) {
				echo "$text\n";
			}
			if ( $hideMessages ) {
				echo "[messages are hidden]\n";
			} else {
				foreach ( $messages as $key => $value ) {
					echo "* '$key':		$value\n";
				}
			}
		}
	}
}

?>
