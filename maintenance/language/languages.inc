<?php
/**
 * Handle messages in the language files.
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

/**
 * @ingroup MaintenanceLanguage
 */
class Languages {
	/** @var array List of languages */
	protected $mLanguages;

	/** @var array Raw list of the messages in each language */
	protected $mRawMessages;

	/** @var array Messages in each language (except for English), divided to groups */
	protected $mMessages;

	/** @var array Fallback language in each language */
	protected $mFallback;

	/** @var array General messages in English, divided to groups */
	protected $mGeneralMessages;

	/** @var array All the messages which should be exist only in the English file */
	protected $mIgnoredMessages;

	/** @var array All the messages which may be translated or not, depending on the language */
	protected $mOptionalMessages;

	/** @var array Namespace names */
	protected $mNamespaceNames;

	/** @var array Namespace aliases */
	protected $mNamespaceAliases;

	/** @var array Magic words */
	protected $mMagicWords;

	/** @var array Special page aliases */
	protected $mSpecialPageAliases;

	/**
	 * Load the list of languages: all the Messages*.php
	 * files in the languages directory.
	 */
	function __construct() {
		Hooks::run( 'LocalisationIgnoredOptionalMessages',
			array( &$this->mIgnoredMessages, &$this->mOptionalMessages ) );

		$this->mLanguages = array_keys( Language::fetchLanguageNames( null, 'mwfile' ) );
		sort( $this->mLanguages );
	}

	/**
	 * Get the language list.
	 *
	 * @return array The language list.
	 */
	public function getLanguages() {
		return $this->mLanguages;
	}

	/**
	 * Get the ignored messages list.
	 *
	 * @return array The ignored messages list.
	 */
	public function getIgnoredMessages() {
		return $this->mIgnoredMessages;
	}

	/**
	 * Get the optional messages list.
	 *
	 * @return array The  optional messages list.
	 */
	public function getOptionalMessages() {
		return $this->mOptionalMessages;
	}

	/**
	 * Load the language file.
	 *
	 * @param string $code The language code.
	 */
	protected function loadFile( $code ) {
		if ( isset( $this->mRawMessages[$code] ) &&
			isset( $this->mFallback[$code] ) &&
			isset( $this->mNamespaceNames[$code] ) &&
			isset( $this->mNamespaceAliases[$code] ) &&
			isset( $this->mMagicWords[$code] ) &&
			isset( $this->mSpecialPageAliases[$code] )
		) {
			return;
		}
		$this->mRawMessages[$code] = array();
		$this->mFallback[$code] = '';
		$this->mNamespaceNames[$code] = array();
		$this->mNamespaceAliases[$code] = array();
		$this->mMagicWords[$code] = array();
		$this->mSpecialPageAliases[$code] = array();

		$jsonfilename = Language::getJsonMessagesFileName( $code );
		if ( file_exists( $jsonfilename ) ) {
			$json = Language::getLocalisationCache()->readJSONFile( $jsonfilename );
			$this->mRawMessages[$code] = $json['messages'];
		}

		$filename = Language::getMessagesFileName( $code );
		if ( file_exists( $filename ) ) {
			require $filename;
			if ( isset( $fallback ) ) {
				$this->mFallback[$code] = $fallback;
			}
			if ( isset( $namespaceNames ) ) {
				$this->mNamespaceNames[$code] = $namespaceNames;
			}
			if ( isset( $namespaceAliases ) ) {
				$this->mNamespaceAliases[$code] = $namespaceAliases;
			}
			if ( isset( $magicWords ) ) {
				$this->mMagicWords[$code] = $magicWords;
			}
			if ( isset( $specialPageAliases ) ) {
				$this->mSpecialPageAliases[$code] = $specialPageAliases;
			}
		}
	}

	/**
	 * Load the messages for a specific language (which is not English) and divide them to
	 * groups:
	 * all - all the messages.
	 * required - messages which should be translated in order to get a complete translation.
	 * optional - messages which can be translated, the fallback translation is used if not
	 *   translated.
	 * obsolete - messages which should not be translated, either because they do not exist,
	 *   or they are ignored messages.
	 * translated - messages which are either required or optional, but translated from
	 *   English and needed.
	 *
	 * @param string $code The language code.
	 */
	private function loadMessages( $code ) {
		if ( isset( $this->mMessages[$code] ) ) {
			return;
		}
		$this->loadFile( $code );
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
			} elseif ( isset( $this->mGeneralMessages['optional'][$key] ) ) {
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
	 * required - messages which should be translated to other languages in order to get a
	 *   complete translation.
	 * optional - messages which can be translated to other languages, but it's not required
	 *   for a complete translation.
	 * ignored - messages which should not be translated to other languages.
	 * translatable - messages which are either required or optional, but can be translated
	 *   from English.
	 */
	private function loadGeneralMessages() {
		if ( isset( $this->mGeneralMessages ) ) {
			return;
		}
		$this->loadFile( 'en' );
		$this->mGeneralMessages['all'] = $this->mRawMessages['en'];
		$this->mGeneralMessages['required'] = array();
		$this->mGeneralMessages['optional'] = array();
		$this->mGeneralMessages['ignored'] = array();
		$this->mGeneralMessages['translatable'] = array();
		foreach ( $this->mGeneralMessages['all'] as $key => $value ) {
			if ( in_array( $key, $this->mIgnoredMessages ) ) {
				$this->mGeneralMessages['ignored'][$key] = $value;
			} elseif ( in_array( $key, $this->mOptionalMessages ) ) {
				$this->mGeneralMessages['optional'][$key] = $value;
				$this->mGeneralMessages['translatable'][$key] = $value;
			} else {
				$this->mGeneralMessages['required'][$key] = $value;
				$this->mGeneralMessages['translatable'][$key] = $value;
			}
		}
	}

	/**
	 * Get all the messages for a specific language (not English), without the
	 * fallback language messages, divided to groups:
	 * all - all the messages.
	 * required - messages which should be translated in order to get a complete translation.
	 * optional - messages which can be translated, the fallback translation is used if not
	 *   translated.
	 * obsolete - messages which should not be translated, either because they do not exist,
	 *   or they are ignored messages.
	 * translated - messages which are either required or optional, but translated from
	 *   English and needed.
	 *
	 * @param string $code The language code.
	 *
	 * @return string The messages in this language.
	 */
	public function getMessages( $code ) {
		$this->loadMessages( $code );

		return $this->mMessages[$code];
	}

	/**
	 * Get all the general English messages, divided to groups:
	 * all - all the messages.
	 * required - messages which should be translated to other languages in
	 *   order to get a complete translation.
	 * optional - messages which can be translated to other languages, but it's
	 *   not required for a complete translation.
	 * ignored - messages which should not be translated to other languages.
	 * translatable - messages which are either required or optional, but can be
	 *   translated from English.
	 *
	 * @return array The general English messages.
	 */
	public function getGeneralMessages() {
		$this->loadGeneralMessages();

		return $this->mGeneralMessages;
	}

	/**
	 * Get fallback language code for a specific language.
	 *
	 * @param string $code The language code.
	 *
	 * @return string Fallback code.
	 */
	public function getFallback( $code ) {
		$this->loadFile( $code );

		return $this->mFallback[$code];
	}

	/**
	 * Get namespace names for a specific language.
	 *
	 * @param string $code The language code.
	 *
	 * @return array Namespace names.
	 */
	public function getNamespaceNames( $code ) {
		$this->loadFile( $code );

		return $this->mNamespaceNames[$code];
	}

	/**
	 * Get namespace aliases for a specific language.
	 *
	 * @param string $code The language code.
	 *
	 * @return array Namespace aliases.
	 */
	public function getNamespaceAliases( $code ) {
		$this->loadFile( $code );

		return $this->mNamespaceAliases[$code];
	}

	/**
	 * Get magic words for a specific language.
	 *
	 * @param string $code The language code.
	 *
	 * @return array Magic words.
	 */
	public function getMagicWords( $code ) {
		$this->loadFile( $code );

		return $this->mMagicWords[$code];
	}

	/**
	 * Get special page aliases for a specific language.
	 *
	 * @param string $code The language code.
	 *
	 * @return array Special page aliases.
	 */
	public function getSpecialPageAliases( $code ) {
		$this->loadFile( $code );

		return $this->mSpecialPageAliases[$code];
	}

	/**
	 * Get the untranslated messages for a specific language.
	 *
	 * @param string $code The language code.
	 *
	 * @return array The untranslated messages for this language.
	 */
	public function getUntranslatedMessages( $code ) {
		$this->loadGeneralMessages();
		$this->loadMessages( $code );

		return array_diff_key( $this->mGeneralMessages['required'], $this->mMessages[$code]['required'] );
	}

	/**
	 * Get the duplicate messages for a specific language.
	 *
	 * @param string $code The language code.
	 *
	 * @return array The duplicate messages for this language.
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
	 * Get the obsolete messages for a specific language.
	 *
	 * @param string $code The language code.
	 *
	 * @return array The obsolete messages for this language.
	 */
	public function getObsoleteMessages( $code ) {
		$this->loadGeneralMessages();
		$this->loadMessages( $code );

		return $this->mMessages[$code]['obsolete'];
	}

	/**
	 * Get the messages whose variables do not match the original ones.
	 *
	 * @param string $code The language code.
	 *
	 * @return array The messages whose variables do not match the original ones.
	 */
	public function getMessagesWithMismatchVariables( $code ) {
		$this->loadGeneralMessages();
		$this->loadMessages( $code );
		$variables = array( '\$1', '\$2', '\$3', '\$4', '\$5', '\$6', '\$7', '\$8', '\$9' );
		$mismatchMessages = array();
		foreach ( $this->mMessages[$code]['translated'] as $key => $value ) {
			$missing = false;
			foreach ( $variables as $var ) {
				if ( preg_match( "/$var/sU", $this->mGeneralMessages['translatable'][$key] ) &&
					!preg_match( "/$var/sU", $value )
				) {
					$missing = true;
				}
				if ( !preg_match( "/$var/sU", $this->mGeneralMessages['translatable'][$key] ) &&
					preg_match( "/$var/sU", $value )
				) {
					$missing = true;
				}
			}
			if ( $missing ) {
				$mismatchMessages[$key] = $value;
			}
		}

		return $mismatchMessages;
	}

	/**
	 * Get the messages which do not use plural.
	 *
	 * @param string $code The language code.
	 *
	 * @return array The messages which do not use plural in this language.
	 */
	public function getMessagesWithoutPlural( $code ) {
		$this->loadGeneralMessages();
		$this->loadMessages( $code );
		$messagesWithoutPlural = array();
		foreach ( $this->mMessages[$code]['translated'] as $key => $value ) {
			if ( stripos( $this->mGeneralMessages['translatable'][$key], '{{plural:' ) !== false &&
				stripos( $value, '{{plural:' ) === false
			) {
				$messagesWithoutPlural[$key] = $value;
			}
		}

		return $messagesWithoutPlural;
	}

	/**
	 * Get the empty messages.
	 *
	 * @param string $code The language code.
	 *
	 * @return array The empty messages for this language.
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
	 * @param string $code The language code.
	 *
	 * @return array The messages with trailing whitespace in this language.
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
	 * @param string $code The language code.
	 *
	 * @return array The non-XHTML messages for this language.
	 */
	public function getNonXHTMLMessages( $code ) {
		$this->loadGeneralMessages();
		$this->loadMessages( $code );
		$wrongPhrases = array(
			'<hr *\\?>',
			'<br *\\?>',
			'<hr/>',
			'<br/>',
			'<hr>',
			'<br>',
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
	 * @param string $code The language code.
	 *
	 * @return array The messages which include wrong characters in this language.
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
			'[ZWSP]' => "\xE2\x80\x8B",
			'[NBSP]' => "\xC2\xA0",
			'[WJ]' => "\xE2\x81\xA0",
			'[BOM]' => "\xEF\xBB\xBF",
			'[FFFD]' => "\xEF\xBF\xBD",
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
	 * Get the messages which include dubious links.
	 *
	 * @param string $code The language code.
	 *
	 * @return array The messages which include dubious links in this language.
	 */
	public function getMessagesWithDubiousLinks( $code ) {
		$this->loadGeneralMessages();
		$this->loadMessages( $code );
		$tc = Title::legalChars() . '#%{}';
		$messages = array();
		foreach ( $this->mMessages[$code]['translated'] as $key => $value ) {
			$matches = array();
			preg_match_all( "/\[\[([{$tc}]+)(?:\\|(.+?))?]]/sDu", $value, $matches );
			$numMatches = count( $matches[0] );
			for ( $i = 0; $i < $numMatches; $i++ ) {
				if ( preg_match( "/.*project.*/isDu", $matches[1][$i] ) ) {
					$messages[$key][] = $matches[0][$i];
				}
			}

			if ( isset( $messages[$key] ) ) {
				$messages[$key] = implode( $messages[$key], ", " );
			}
		}

		return $messages;
	}

	/**
	 * Get the messages which include unbalanced brackets.
	 *
	 * @param string $code The language code.
	 *
	 * @return array The messages which include unbalanced brackets in this language.
	 */
	public function getMessagesWithUnbalanced( $code ) {
		$this->loadGeneralMessages();
		$this->loadMessages( $code );
		$messages = array();
		foreach ( $this->mMessages[$code]['translated'] as $key => $value ) {
			$a = $b = $c = $d = 0;
			foreach ( preg_split( '//', $value ) as $char ) {
				switch ( $char ) {
					case '[':
						$a++;
						break;
					case ']':
						$b++;
						break;
					case '{':
						$c++;
						break;
					case '}':
						$d++;
						break;
				}
			}

			if ( $a !== $b || $c !== $d ) {
				$messages[$key] = "$a, $b, $c, $d";
			}
		}

		return $messages;
	}

	/**
	 * Get the untranslated namespace names.
	 *
	 * @param string $code The language code.
	 *
	 * @return array The untranslated namespace names in this language.
	 */
	public function getUntranslatedNamespaces( $code ) {
		$this->loadFile( 'en' );
		$this->loadFile( $code );
		$namespacesDiff = array_diff_key( $this->mNamespaceNames['en'], $this->mNamespaceNames[$code] );
		if ( isset( $namespacesDiff[NS_MAIN] ) ) {
			unset( $namespacesDiff[NS_MAIN] );
		}

		return $namespacesDiff;
	}

	/**
	 * Get the project talk namespace names with no $1.
	 *
	 * @param string $code The language code.
	 *
	 * @return array The problematic project talk namespaces in this language.
	 */
	public function getProblematicProjectTalks( $code ) {
		$this->loadFile( $code );
		$namespaces = array();

		# Check default namespace name
		if ( isset( $this->mNamespaceNames[$code][NS_PROJECT_TALK] ) ) {
			$default = $this->mNamespaceNames[$code][NS_PROJECT_TALK];
			if ( strpos( $default, '$1' ) === false ) {
				$namespaces[$default] = 'default';
			}
		}

		# Check namespace aliases
		foreach ( $this->mNamespaceAliases[$code] as $key => $value ) {
			if ( $value == NS_PROJECT_TALK && strpos( $key, '$1' ) === false ) {
				$namespaces[$key] = '';
			}
		}

		return $namespaces;
	}

	/**
	 * Get the untranslated magic words.
	 *
	 * @param string $code The language code.
	 *
	 * @return array The untranslated magic words in this language.
	 */
	public function getUntranslatedMagicWords( $code ) {
		$this->loadFile( 'en' );
		$this->loadFile( $code );
		$magicWords = array();
		foreach ( $this->mMagicWords['en'] as $key => $value ) {
			if ( !isset( $this->mMagicWords[$code][$key] ) ) {
				$magicWords[$key] = $value[1];
			}
		}

		return $magicWords;
	}

	/**
	 * Get the obsolete magic words.
	 *
	 * @param string $code The language code.
	 *
	 * @return array The obsolete magic words in this language.
	 */
	public function getObsoleteMagicWords( $code ) {
		$this->loadFile( 'en' );
		$this->loadFile( $code );
		$magicWords = array();
		foreach ( $this->mMagicWords[$code] as $key => $value ) {
			if ( !isset( $this->mMagicWords['en'][$key] ) ) {
				$magicWords[$key] = $value[1];
			}
		}

		return $magicWords;
	}

	/**
	 * Get the magic words that override the original English magic word.
	 *
	 * @param string $code The language code.
	 *
	 * @return array The overriding magic words in this language.
	 */
	public function getOverridingMagicWords( $code ) {
		$this->loadFile( 'en' );
		$this->loadFile( $code );
		$magicWords = array();
		foreach ( $this->mMagicWords[$code] as $key => $local ) {
			if ( !isset( $this->mMagicWords['en'][$key] ) ) {
				# Unrecognized magic word
				continue;
			}
			$en = $this->mMagicWords['en'][$key];
			array_shift( $local );
			array_shift( $en );
			foreach ( $en as $word ) {
				if ( !in_array( $word, $local ) ) {
					$magicWords[$key] = $word;
					break;
				}
			}
		}

		return $magicWords;
	}

	/**
	 * Get the magic words which do not match the case-sensitivity of the original words.
	 *
	 * @param string $code The language code.
	 *
	 * @return array The magic words whose case does not match in this language.
	 */
	public function getCaseMismatchMagicWords( $code ) {
		$this->loadFile( 'en' );
		$this->loadFile( $code );
		$magicWords = array();
		foreach ( $this->mMagicWords[$code] as $key => $local ) {
			if ( !isset( $this->mMagicWords['en'][$key] ) ) {
				# Unrecognized magic word
				continue;
			}
			if ( $local[0] != $this->mMagicWords['en'][$key][0] ) {
				$magicWords[$key] = $local[0];
			}
		}

		return $magicWords;
	}

	/**
	 * Get the untranslated special page names.
	 *
	 * @param string $code The language code.
	 *
	 * @return array The untranslated special page names in this language.
	 */
	public function getUntraslatedSpecialPages( $code ) {
		$this->loadFile( 'en' );
		$this->loadFile( $code );
		$specialPageAliases = array();
		foreach ( $this->mSpecialPageAliases['en'] as $key => $value ) {
			if ( !isset( $this->mSpecialPageAliases[$code][$key] ) ) {
				$specialPageAliases[$key] = $value[0];
			}
		}

		return $specialPageAliases;
	}

	/**
	 * Get the obsolete special page names.
	 *
	 * @param string $code The language code.
	 *
	 * @return array The obsolete special page names in this language.
	 */
	public function getObsoleteSpecialPages( $code ) {
		$this->loadFile( 'en' );
		$this->loadFile( $code );
		$specialPageAliases = array();
		foreach ( $this->mSpecialPageAliases[$code] as $key => $value ) {
			if ( !isset( $this->mSpecialPageAliases['en'][$key] ) ) {
				$specialPageAliases[$key] = $value[0];
			}
		}

		return $specialPageAliases;
	}
}

class ExtensionLanguages extends Languages {
	/**
	 * @var MessageGroup
	 */
	private $mMessageGroup;

	/**
	 * Load the messages group.
	 * @param MessageGroup $group The messages group.
	 */
	function __construct( MessageGroup $group ) {
		$this->mMessageGroup = $group;

		$this->mIgnoredMessages = $this->mMessageGroup->getIgnored();
		$this->mOptionalMessages = $this->mMessageGroup->getOptional();
	}

	/**
	 * Get the extension name.
	 *
	 * @return string The extension name.
	 */
	public function name() {
		return $this->mMessageGroup->getLabel();
	}

	/**
	 * Load the language file.
	 *
	 * @param string $code The language code.
	 */
	protected function loadFile( $code ) {
		if ( !isset( $this->mRawMessages[$code] ) ) {
			$this->mRawMessages[$code] = $this->mMessageGroup->load( $code );
			if ( empty( $this->mRawMessages[$code] ) ) {
				$this->mRawMessages[$code] = array();
			}
		}
	}
}
