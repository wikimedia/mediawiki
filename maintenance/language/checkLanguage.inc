<?php
/**
 * Helper class for checkLanguage.php script.
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
class CheckLanguageCLI {
	protected $code = null;
	protected $level = 2;
	protected $doLinks = false;
	protected $linksPrefix = '';
	protected $wikiCode = 'en';
	protected $checkAll = false;
	protected $output = 'plain';
	protected $checks = array();
	protected $L = null;

	protected $results = array();

	private $includeExif = false;

	/**
	 * Constructor.
	 * @param $options array Options for script.
	 */
	public function __construct( array $options ) {
		if ( isset( $options['help'] ) ) {
			echo $this->help();
			exit( 1 );
		}

		if ( isset( $options['lang'] ) ) {
			$this->code = $options['lang'];
		} else {
			global $wgLanguageCode;
			$this->code = $wgLanguageCode;
		}

		if ( isset( $options['level'] ) ) {
			$this->level = $options['level'];
		}

		$this->doLinks = isset( $options['links'] );
		$this->includeExif = !isset( $options['noexif'] );
		$this->checkAll = isset( $options['all'] );

		if ( isset( $options['prefix'] ) ) {
			$this->linksPrefix = $options['prefix'];
		}

		if ( isset( $options['wikilang'] ) ) {
			$this->wikiCode = $options['wikilang'];
		}

		if ( isset( $options['whitelist'] ) ) {
			$this->checks = explode( ',', $options['whitelist'] );
		} elseif ( isset( $options['blacklist'] ) ) {
			$this->checks = array_diff(
				isset( $options['easy'] ) ? $this->easyChecks() : $this->defaultChecks(),
				explode( ',', $options['blacklist'] )
			);
		} elseif ( isset( $options['easy'] ) ) {
			$this->checks = $this->easyChecks();
		} else {
			$this->checks = $this->defaultChecks();
		}

		if ( isset( $options['output'] ) ) {
			$this->output = $options['output'];
		}

		$this->L = new languages( $this->includeExif );
	}

	/**
	 * Get the default checks.
	 * @return array A list of the default checks.
	 */
	protected function defaultChecks() {
		return array(
			'untranslated', 'duplicate', 'obsolete', 'variables', 'empty', 'plural',
			'whitespace', 'xhtml', 'chars', 'links', 'unbalanced', 'namespace',
			'projecttalk', 'magic', 'magic-old', 'magic-over', 'magic-case',
			'special', 'special-old',
		);
	}

	/**
	 * Get the checks which check other things than messages.
	 * @return array A list of the non-message checks.
	 */
	protected function nonMessageChecks() {
		return array(
			'namespace', 'projecttalk', 'magic', 'magic-old', 'magic-over',
			'magic-case', 'special', 'special-old',
		);
	}

	/**
	 * Get the checks that can easily be treated by non-speakers of the language.
	 * @return Array A list of the easy checks.
	 */
	protected function easyChecks() {
		return array(
			'duplicate', 'obsolete', 'empty', 'whitespace', 'xhtml', 'chars', 'magic-old',
			'magic-over', 'magic-case', 'special-old',
		);
	}

	/**
	 * Get all checks.
	 * @return array An array of all check names mapped to their function names.
	 */
	protected function getChecks() {
		return array(
			'untranslated' => 'getUntranslatedMessages',
			'duplicate' => 'getDuplicateMessages',
			'obsolete' => 'getObsoleteMessages',
			'variables' => 'getMessagesWithMismatchVariables',
			'plural' => 'getMessagesWithoutPlural',
			'empty' => 'getEmptyMessages',
			'whitespace' => 'getMessagesWithWhitespace',
			'xhtml' => 'getNonXHTMLMessages',
			'chars' => 'getMessagesWithWrongChars',
			'links' => 'getMessagesWithDubiousLinks',
			'unbalanced' => 'getMessagesWithUnbalanced',
			'namespace' => 'getUntranslatedNamespaces',
			'projecttalk' => 'getProblematicProjectTalks',
			'magic' => 'getUntranslatedMagicWords',
			'magic-old' => 'getObsoleteMagicWords',
			'magic-over' => 'getOverridingMagicWords',
			'magic-case' => 'getCaseMismatchMagicWords',
			'special' => 'getUntraslatedSpecialPages',
			'special-old' => 'getObsoleteSpecialPages',
		);
	}

	/**
	 * Get total count for each check non-messages check.
	 * @return array An array of all check names mapped to a two-element array:
	 * function name to get the total count and language code or null
	 * for checked code.
	 */
	protected function getTotalCount() {
		return array(
			'namespace' => array( 'getNamespaceNames', 'en' ),
			'projecttalk' => null,
			'magic' => array( 'getMagicWords', 'en' ),
			'magic-old' => array( 'getMagicWords', null ),
			'magic-over' => array( 'getMagicWords', null ),
			'magic-case' => array( 'getMagicWords', null ),
			'special' => array( 'getSpecialPageAliases', 'en' ),
			'special-old' => array( 'getSpecialPageAliases', null ),
		);
	}

	/**
	 * Get all check descriptions.
	 * @return array An array of all check names mapped to their descriptions.
	 */
	protected function getDescriptions() {
		return array(
			'untranslated' => '$1 message(s) of $2 are not translated to $3, but exist in en:',
			'duplicate' => '$1 message(s) of $2 are translated the same in en and $3:',
			'obsolete' => '$1 message(s) of $2 do not exist in en or are in the ignore list, but exist in $3:',
			'variables' => '$1 message(s) of $2 in $3 don\'t match the variables used in en:',
			'plural' => '$1 message(s) of $2 in $3 don\'t use {{plural}} while en uses:',
			'empty' => '$1 message(s) of $2 in $3 are empty or -:',
			'whitespace' => '$1 message(s) of $2 in $3 have trailing whitespace:',
			'xhtml' => '$1 message(s) of $2 in $3 contain illegal XHTML:',
			'chars' => '$1 message(s) of $2 in $3 include hidden chars which should not be used in the messages:',
			'links' => '$1 message(s) of $2 in $3 have problematic link(s):',
			'unbalanced' => '$1 message(s) of $2 in $3 have unbalanced {[]}:',
			'namespace' => '$1 namespace name(s) of $2 are not translated to $3, but exist in en:',
			'projecttalk' => '$1 namespace name(s) and alias(es) in $3 are project talk namespaces without the parameter:',
			'magic' => '$1 magic word(s) of $2 are not translated to $3, but exist in en:',
			'magic-old' => '$1 magic word(s) of $2 do not exist in en, but exist in $3:',
			'magic-over' => '$1 magic word(s) of $2 in $3 do not contain the original en word(s):',
			'magic-case' => '$1 magic word(s) of $2 in $3 change the case-sensitivity of the original en word:',
			'special' => '$1 special page alias(es) of $2 are not translated to $3, but exist in en:',
			'special-old' => '$1 special page alias(es) of $2 do not exist in en, but exist in $3:',
		);
	}

	/**
	 * Get help.
	 * @return string The help string.
	 */
	protected function help() {
		return <<<ENDS
Run this script to check a specific language file, or all of them.
Command line settings are in form --parameter[=value].
Parameters:
	--help: Show this help.
	--lang: Language code (default: the installation default language).
	--all: Check all customized languages.
	--level: Show the following display level (default: 2):
		* 0: Skip the checks (useful for checking syntax).
		* 1: Show only the stub headers and number of wrong messages, without list of messages.
		* 2: Show only the headers and the message keys, without the message values.
		* 3: Show both the headers and the complete messages, with both keys and values.
	--links: Link the message values (default off).
	--prefix: prefix to add to links.
	--wikilang: For the links, what is the content language of the wiki to display the output in (default en).
	--noexif: Do not check for Exif messages (a bit hard and boring to translate), if you know
		that they are currently not translated and want to focus on other problems (default off).
	--whitelist: Do only the following checks (form: code,code).
	--blacklist: Do not do the following checks (form: code,code).
	--easy: Do only the easy checks, which can be treated by non-speakers of the language.

Check codes (ideally, all of them should result 0; all the checks are executed by default (except language-specific check blacklists in checkLanguage.inc):
	* untranslated: Messages which are required to translate, but are not translated.
	* duplicate: Messages which translation equal to fallback
	* obsolete: Messages which are untranslatable or do not exist, but are translated.
	* variables: Messages without variables which should be used, or with variables which should not be used.
	* empty: Empty messages and messages that contain only -.
	* whitespace: Messages which have trailing whitespace.
	* xhtml: Messages which are not well-formed XHTML (checks only few common errors).
	* chars: Messages with hidden characters.
	* links: Messages which contains broken links to pages (does not find all).
	* unbalanced: Messages which contains unequal numbers of opening {[ and closing ]}.
	* namespace: Namespace names that were not translated.
	* projecttalk: Namespace names and aliases where the project talk does not contain $1.
	* magic: Magic words that were not translated.
	* magic-old: Magic words which do not exist.
	* magic-over: Magic words that override the original English word.
	* magic-case: Magic words whose translation changes the case-sensitivity of the original English word.
	* special: Special page names that were not translated.
	* special-old: Special page names which do not exist.

ENDS;
	}

	/**
	 * Execute the script.
	 */
	public function execute() {
		$this->doChecks();
		if ( $this->level > 0 ) {
			switch ( $this->output ) {
				case 'plain':
					$this->outputText();
					break;
				case 'wiki':
					$this->outputWiki();
					break;
				default:
					throw new MWException( "Invalid output type $this->output" );
			}
		}
	}

	/**
	 * Execute the checks.
	 */
	protected function doChecks() {
		$ignoredCodes = array( 'en', 'enRTL' );

		$this->results = array();
		# Check the language
		if ( $this->checkAll ) {
			foreach ( $this->L->getLanguages() as $language ) {
				if ( !in_array( $language, $ignoredCodes ) ) {
					$this->results[$language] = $this->checkLanguage( $language );
				}
			}
		} else {
			if ( in_array( $this->code, $ignoredCodes ) ) {
				throw new MWException( "Cannot check code $this->code." );
			} else {
				$this->results[$this->code] = $this->checkLanguage( $this->code );
			}
		}
	}

	/**
	 * Get the check blacklist.
	 * @return array The list of checks which should not be executed.
	 */
	protected function getCheckBlacklist() {
		global $checkBlacklist;

		return $checkBlacklist;
	}

	/**
	 * Check a language.
	 * @param $code string The language code.
	 * @throws MWException
	 * @return array The results.
	 */
	protected function checkLanguage( $code ) {
		# Syntax check only
		$results = array();
		if ( $this->level === 0 ) {
			$this->L->getMessages( $code );

			return $results;
		}

		$checkFunctions = $this->getChecks();
		$checkBlacklist = $this->getCheckBlacklist();
		foreach ( $this->checks as $check ) {
			if ( isset( $checkBlacklist[$code] ) &&
				in_array( $check, $checkBlacklist[$code] )
			) {
				$results[$check] = array();
				continue;
			}

			$callback = array( $this->L, $checkFunctions[$check] );
			if ( !is_callable( $callback ) ) {
				throw new MWException( "Unkown check $check." );
			}
			$results[$check] = call_user_func( $callback, $code );
		}

		return $results;
	}

	/**
	 * Format a message key.
	 * @param $key string The message key.
	 * @param $code string The language code.
	 * @return string The formatted message key.
	 */
	protected function formatKey( $key, $code ) {
		if ( $this->doLinks ) {
			$displayKey = ucfirst( $key );
			if ( $code == $this->wikiCode ) {
				return "[[{$this->linksPrefix}MediaWiki:$displayKey|$key]]";
			} else {
				return "[[{$this->linksPrefix}MediaWiki:$displayKey/$code|$key]]";
			}
		} else {
			return $key;
		}
	}

	/**
	 * Output the checks results as plain text.
	 */
	protected function outputText() {
		foreach ( $this->results as $code => $results ) {
			$translated = $this->L->getMessages( $code );
			$translated = count( $translated['translated'] );
			foreach ( $results as $check => $messages ) {
				$count = count( $messages );
				if ( $count ) {
					if ( $check == 'untranslated' ) {
						$translatable = $this->L->getGeneralMessages();
						$total = count( $translatable['translatable'] );
					} elseif ( in_array( $check, $this->nonMessageChecks() ) ) {
						$totalCount = $this->getTotalCount();
						$totalCount = $totalCount[$check];
						$callback = array( $this->L, $totalCount[0] );
						$callCode = $totalCount[1] ? $totalCount[1] : $code;
						$total = count( call_user_func( $callback, $callCode ) );
					} else {
						$total = $translated;
					}
					$search = array( '$1', '$2', '$3' );
					$replace = array( $count, $total, $code );
					$descriptions = $this->getDescriptions();
					echo "\n" . str_replace( $search, $replace, $descriptions[$check] ) . "\n";
					if ( $this->level == 1 ) {
						echo "[messages are hidden]\n";
					} else {
						foreach ( $messages as $key => $value ) {
							if ( !in_array( $check, $this->nonMessageChecks() ) ) {
								$key = $this->formatKey( $key, $code );
							}
							if ( $this->level == 2 || empty( $value ) ) {
								echo "* $key\n";
							} else {
								echo "* $key:		'$value'\n";
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Output the checks results as wiki text.
	 */
	function outputWiki() {
		$detailText = '';
		$rows[] = '! Language !! Code !! Total !! ' . implode( ' !! ', array_diff( $this->checks, $this->nonMessageChecks() ) );
		foreach ( $this->results as $code => $results ) {
			$detailTextForLang = "==$code==\n";
			$numbers = array();
			$problems = 0;
			$detailTextForLangChecks = array();
			foreach ( $results as $check => $messages ) {
				if ( in_array( $check, $this->nonMessageChecks() ) ) {
					continue;
				}
				$count = count( $messages );
				if ( $count ) {
					$problems += $count;
					$messageDetails = array();
					foreach ( $messages as $key => $details ) {
						$displayKey = $this->formatKey( $key, $code );
						$messageDetails[] = $displayKey;
					}
					$detailTextForLangChecks[] = "=== $code-$check ===\n* " . implode( ', ', $messageDetails );
					$numbers[] = "'''[[#$code-$check|$count]]'''";
				} else {
					$numbers[] = $count;
				}
			}

			if ( count( $detailTextForLangChecks ) ) {
				$detailText .= $detailTextForLang . implode( "\n", $detailTextForLangChecks ) . "\n";
			}

			if ( !$problems ) {
				# Don't list languages without problems
				continue;
			}
			$language = Language::fetchLanguageName( $code );
			$rows[] = "| $language || $code || $problems || " . implode( ' || ', $numbers );
		}

		$tableRows = implode( "\n|-\n", $rows );

		$version = SpecialVersion::getVersion( 'nodb' );
		echo <<<EOL
'''Check results are for:''' <code>$version</code>


{| class="sortable wikitable" border="2" cellpadding="4" cellspacing="0" style="background-color: #F9F9F9; border: 1px #AAAAAA solid; border-collapse: collapse; clear: both;"
$tableRows
|}

$detailText

EOL;
	}

	/**
	 * Check if there are any results for the checks, in any language.
	 * @return bool True if there are any results, false if not.
	 */
	protected function isEmpty() {
		foreach ( $this->results as $results ) {
			foreach ( $results as $messages ) {
				if ( !empty( $messages ) ) {
					return false;
				}
			}
		}

		return true;
	}
}

/**
 * @ingroup MaintenanceLanguage
 */
class CheckExtensionsCLI extends CheckLanguageCLI {
	private $extensions;

	/**
	 * Constructor.
	 * @param $options array Options for script.
	 * @param $extension string The extension name (or names).
	 */
	public function __construct( array $options, $extension ) {
		if ( isset( $options['help'] ) ) {
			echo $this->help();
			exit( 1 );
		}

		if ( isset( $options['lang'] ) ) {
			$this->code = $options['lang'];
		} else {
			global $wgLanguageCode;
			$this->code = $wgLanguageCode;
		}

		if ( isset( $options['level'] ) ) {
			$this->level = $options['level'];
		}

		$this->doLinks = isset( $options['links'] );

		if ( isset( $options['wikilang'] ) ) {
			$this->wikiCode = $options['wikilang'];
		}

		if ( isset( $options['whitelist'] ) ) {
			$this->checks = explode( ',', $options['whitelist'] );
		} elseif ( isset( $options['blacklist'] ) ) {
			$this->checks = array_diff(
				isset( $options['easy'] ) ? $this->easyChecks() : $this->defaultChecks(),
				explode( ',', $options['blacklist'] )
			);
		} elseif ( isset( $options['easy'] ) ) {
			$this->checks = $this->easyChecks();
		} else {
			$this->checks = $this->defaultChecks();
		}

		if ( isset( $options['output'] ) ) {
			$this->output = $options['output'];
		}

		# Some additional checks not enabled by default
		if ( isset( $options['duplicate'] ) ) {
			$this->checks[] = 'duplicate';
		}

		$this->extensions = array();
		$extensions = new PremadeMediawikiExtensionGroups();
		$extensions->addAll();
		if ( $extension == 'all' ) {
			foreach ( MessageGroups::singleton()->getGroups() as $group ) {
				if ( strpos( $group->getId(), 'ext-' ) === 0 && !$group->isMeta() ) {
					$this->extensions[] = new extensionLanguages( $group );
				}
			}
		} elseif ( $extension == 'wikimedia' ) {
			$wikimedia = MessageGroups::getGroup( 'ext-0-wikimedia' );
			foreach ( $wikimedia->wmfextensions() as $extension ) {
				$group = MessageGroups::getGroup( $extension );
				$this->extensions[] = new extensionLanguages( $group );
			}
		} elseif ( $extension == 'flaggedrevs' ) {
			foreach ( MessageGroups::singleton()->getGroups() as $group ) {
				if ( strpos( $group->getId(), 'ext-flaggedrevs-' ) === 0 && !$group->isMeta() ) {
					$this->extensions[] = new extensionLanguages( $group );
				}
			}
		} else {
			$extensions = explode( ',', $extension );
			foreach ( $extensions as $extension ) {
				$group = MessageGroups::getGroup( 'ext-' . $extension );
				if ( $group ) {
					$extension = new extensionLanguages( $group );
					$this->extensions[] = $extension;
				} else {
					print "No such extension $extension.\n";
				}
			}
		}
	}

	/**
	 * Get the default checks.
	 * @return array A list of the default checks.
	 */
	protected function defaultChecks() {
		return array(
			'untranslated', 'duplicate', 'obsolete', 'variables', 'empty', 'plural',
			'whitespace', 'xhtml', 'chars', 'links', 'unbalanced',
		);
	}

	/**
	 * Get the checks which check other things than messages.
	 * @return array A list of the non-message checks.
	 */
	protected function nonMessageChecks() {
		return array();
	}

	/**
	 * Get the checks that can easily be treated by non-speakers of the language.
	 * @return arrayA list of the easy checks.
	 */
	protected function easyChecks() {
		return array(
			'duplicate', 'obsolete', 'empty', 'whitespace', 'xhtml', 'chars',
		);
	}

	/**
	 * Get help.
	 * @return string The help string.
	 */
	protected function help() {
		return <<<ENDS
Run this script to check the status of a specific language in extensions, or all of them.
Command line settings are in form --parameter[=value], except for the first one.
Parameters:
	* First parameter (mandatory): Extension name, multiple extension names (separated by commas), "all" for all the extensions, "wikimedia" for extensions used by Wikimedia or "flaggedrevs" for all FLaggedRevs extension messages.
	* lang: Language code (default: the installation default language).
	* help: Show this help.
	* level: Show the following display level (default: 2).
	* links: Link the message values (default off).
	* wikilang: For the links, what is the content language of the wiki to display the output in (default en).
	* whitelist: Do only the following checks (form: code,code).
	* blacklist: Do not perform the following checks (form: code,code).
	* easy: Do only the easy checks, which can be treated by non-speakers of the language.
Check codes (ideally, all of them should result 0; all the checks are executed by default (except language-specific check blacklists in checkLanguage.inc):
	* untranslated: Messages which are required to translate, but are not translated.
	* duplicate: Messages which translation equal to fallback
	* obsolete: Messages which are untranslatable, but translated.
	* variables: Messages without variables which should be used, or with variables which should not be used.
	* empty: Empty messages.
	* whitespace: Messages which have trailing whitespace.
	* xhtml: Messages which are not well-formed XHTML (checks only few common errors).
	* chars: Messages with hidden characters.
	* links: Messages which contains broken links to pages (does not find all).
	* unbalanced: Messages which contains unequal numbers of opening {[ and closing ]}.
Display levels (default: 2):
	* 0: Skip the checks (useful for checking syntax).
	* 1: Show only the stub headers and number of wrong messages, without list of messages.
	* 2: Show only the headers and the message keys, without the message values.
	* 3: Show both the headers and the complete messages, with both keys and values.

ENDS;
	}

	/**
	 * Execute the script.
	 */
	public function execute() {
		$this->doChecks();
	}

	/**
	 * Check a language and show the results.
	 * @param $code string The language code.
	 * @throws MWException
	 */
	protected function checkLanguage( $code ) {
		foreach ( $this->extensions as $extension ) {
			$this->L = $extension;
			$this->results = array();
			$this->results[$code] = parent::checkLanguage( $code );

			if ( !$this->isEmpty() ) {
				echo $extension->name() . ":\n";

				if ( $this->level > 0 ) {
					switch ( $this->output ) {
						case 'plain':
							$this->outputText();
							break;
						case 'wiki':
							$this->outputWiki();
							break;
						default:
							throw new MWException( "Invalid output type $this->output" );
					}
				}

				echo "\n";
			}
		}
	}
}

# Blacklist some checks for some languages
$checkBlacklist = array(
#'code'        => array( 'check1', 'check2' ... )
	'az' => array( 'plural' ),
	'bo' => array( 'plural' ),
	'cdo' => array( 'plural' ),
	'dz' => array( 'plural' ),
	'id' => array( 'plural' ),
	'fa' => array( 'plural' ),
	'gan' => array( 'plural' ),
	'gan-hans' => array( 'plural' ),
	'gan-hant' => array( 'plural' ),
	'gn' => array( 'plural' ),
	'hak' => array( 'plural' ),
	'hu' => array( 'plural' ),
	'ja' => array( 'plural' ), // Does not use plural
	'jv' => array( 'plural' ),
	'ka' => array( 'plural' ),
	'kk-arab' => array( 'plural' ),
	'kk-cyrl' => array( 'plural' ),
	'kk-latn' => array( 'plural' ),
	'km' => array( 'plural' ),
	'kn' => array( 'plural' ),
	'ko' => array( 'plural' ),
	'lzh' => array( 'plural' ),
	'mn' => array( 'plural' ),
	'ms' => array( 'plural' ),
	'my' => array( 'plural', 'chars' ), // Uses a lot zwnj
	'sah' => array( 'plural' ),
	'sq' => array( 'plural' ),
	'tet' => array( 'plural' ),
	'th' => array( 'plural' ),
	'to' => array( 'plural' ),
	'tr' => array( 'plural' ),
	'vi' => array( 'plural' ),
	'wuu' => array( 'plural' ),
	'xmf' => array( 'plural' ),
	'yo' => array( 'plural' ),
	'yue' => array( 'plural' ),
	'zh' => array( 'plural' ),
	'zh-classical' => array( 'plural' ),
	'zh-cn' => array( 'plural' ),
	'zh-hans' => array( 'plural' ),
	'zh-hant' => array( 'plural' ),
	'zh-hk' => array( 'plural' ),
	'zh-sg' => array( 'plural' ),
	'zh-tw' => array( 'plural' ),
	'zh-yue' => array( 'plural' ),
);
