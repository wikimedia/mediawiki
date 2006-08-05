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
		'linktrail',
		'loginend',
		'loginlanguagelinks',
		'markaspatrolledlink',
		'metadata-fields', // Objection, contains help
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
				$this->mList[] = $matches[1];
			}
		}
		sort($this->mList);
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
	 * Get all the messages of all the languages.
	 */
	public function getAllMessages() {
		foreach ( $this->mList as $code ) {
			$this->getMessages( $code );
		}
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
		foreach ( $this->mMessages['en'] as $key => $value ) {
			if ( !isset( $this->mMessages[$code][$key] ) && !in_array( $key, $this->mIgnoredMessages ) ) {
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
			if ( @$this->mMessages['en'][$key] == $value ) {
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
			if ( !isset( $this->mMessages['en'][$key] ) ) {
				$obsoleteMessages[$key] = $value;
			}
		}
		return $obsoleteMessages;
	}

	/**
	 * Output a messages list.
	 *
	 * @param $messages The messages list.
	 * @param $text The text to show before the list (optional).
	 */
	public function outputMessagesList( $messages, $text = '' ) {
		if ( count( $messages ) > 0 ) {
			if ( $text ) {
				echo "$text\n";
			}
			foreach ( array_keys( $messages ) as $key ) {
				echo "* $key\n";
			}
		}
	}
}

?>
