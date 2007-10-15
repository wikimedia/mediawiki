<?php
/**
 * Check a language file.
 *
 * @addtogroup Maintenance
 */

require_once( dirname(__FILE__).'/../commandLine.inc' );
require_once( 'languages.inc' );

$cli = new CheckLanguageCLI( $options );
$cli->execute();

class CheckLanguageCLI {
	private $code  = null;
	private $level = 2;
	private $doLinks = false;
	private $wikiCode = 'en';
	private $includeExif = false;
	private $checkAll = false;
	private $output = 'plain';
	private $checks = array();

	private $defaultChecks = array(
		'untranslated', 'obsolete', 'variables', 'empty', 'plural',
		'whitespace', 'xhtml', 'chars', 'links', 'unbalanced'
	);

	private $L = null;

	/**
	 * GLOBALS: $wgLanguageCode;
	 */
	public function __construct( Array $options ) {

		if ( isset( $options['help'] ) ) {
			echo $this->help();
			exit();
		}

		if ( isset($options['lang']) ) {
			$this->code = $options['lang'];
		} else {
			global $wgLanguageCode;
			$this->code = $wgLanguageCode;
		}

		if ( isset($options['level']) ) {
			$this->level = $options['level'];
		}

		$this->doLinks = isset($options['links']);
		$this->includeExif = !isset($options['noexif']);
		$this->checkAll = isset($options['all']);

		if ( isset($options['wikilang']) ) {
			$this->wikiCode = $options['wikilang'];
		}

		if ( isset( $options['whitelist'] ) ) {
			$this->checks = explode( ',', $options['whitelist'] );
		} elseif ( isset( $options['blacklist'] ) ) {
			$this->checks = array_diff(
				$this->defaultChecks,
				explode( ',', $options['blacklist'] )
			);
		} else {
			$this->checks = $this->defaultChecks;
		}

		if ( isset($options['output']) ) {
			$this->output = $options['output'];
		}

		# Some additional checks not enabled by default
		if ( isset( $options['duplicate'] ) ) {
			$this->checks[] = 'duplicate';
		}

		$this->L = new languages( $this->includeExif );
	}

	protected function getChecks() {
		$checks = array();
		$checks['untranslated'] = 'getUntranslatedMessages';
		$checks['duplicate'] = 'getDuplicateMessages';
		$checks['obsolete'] = 'getObsoleteMessages';
		$checks['variables'] = 'getMessagesWithoutVariables';
		$checks['plural'] = 'getMessagesWithoutPlural';
		$checks['empty'] = 'getEmptyMessages';
		$checks['whitespace'] = 'getMessagesWithWhitespace';
		$checks['xhtml'] = 'getNonXHTMLMessages';
		$checks['chars'] = 'getMessagesWithWrongChars';
		$checks['links'] = 'getMessagesWithDubiousLinks';
		$checks['unbalanced'] = 'getMessagesWithUnbalanced';
		return $checks;
	}

	protected function getDescriptions() {
		$descriptions = array();
		$descriptions['untranslated'] = '$1 message(s) of $2 are not translated to $3, but exist in en:';
		$descriptions['duplicate'] = '$1 message(s) of $2 are translated the same in en and $3:';
		$descriptions['obsolete'] = '$1 message(s) of $2 do not exist in en or are in the ignore list, but are in $3';
		$descriptions['variables'] = '$1 message(s) of $2 in $3 don\'t use some variables that en uses:';
		$descriptions['plural'] = '$1 message(s) of $2 in $3 don\'t use {{plural}} while en uses:';
		$descriptions['empty'] = '$1 message(s) of $2 in $3 are empty or -:';
		$descriptions['whitespace'] = '$1 message(s) of $2 in $3 have trailing whitespace:';
		$descriptions['xhtml'] = '$1 message(s) of $2 in $3 contain illegal XHTML:';
		$descriptions['chars'] = '$1 message(s) of $2 in $3 include hidden chars which should not be used in the messages:';
		$descriptions['links'] = '$1 message(s) of $2 in $3 have problematic link(s):';
		$descriptions['unbalanced'] = '$1 message(s) of $2 in $3 have unbalanced {[]}:';
		return $descriptions;
	}

	protected function help() {
		return <<<ENDS
Run this script to check a specific language file, or all of them.
Command line settings are in form --parameter[=value].
Parameters:
	* lang: Language code (default: the installation default language).
	* all: Check all customized languages
	* help: Show this help.
	* level: Show the following level (default: 2).
	* links: Link the message values (default off).
	* wikilang: For the links, what is the content language of the wiki to display the output in (default en).
	* whitelist: Do only the following checks (form: code,code).
	* blacklist: Don't do the following checks (form: code,code).
	* duplicate: Additionally check for messages which are translated the same to English (default off).
	* noexif: Don't check for EXIF messages (a bit hard and boring to translate), if you know that they are currently not translated and want to focus on other problems (default off).
Check codes (ideally, all of them should result 0; all the checks are executed by default (except duplicate and language specific check blacklists in checkLanguage.inc):
	* untranslated: Messages which are required to translate, but are not translated.
	* duplicate: Messages which translation equal to fallback
	* obsolete: Messages which are untranslatable, but translated.
	* variables: Messages without variables which should be used.
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

	private $results = array();

	public function execute() {
		$this->doChecks();
		if ( $this->level > 0 ) {
			switch ($this->output) {
				case 'plain':
					$this->outputText();
					break;
				case 'wiki':
					$this->outputWiki();
					break;
				default:
					throw new MWException( "Invalid output type $this->output");
			}
		}
	}

	protected function doChecks() {
		$ignoredCodes = array( 'en', 'enRTL' );

		$this->results = array();
		# Check the language
		if ( $this->checkAll ) {
			foreach ( $this->L->getLanguages() as $language ) {
				if ( !in_array($language, $ignoredCodes) ) {
					$this->results[$language] = $this->checkLanguage( $language );
				}
			}
		} else {
			if ( in_array($this->code, $ignoredCodes) ) {
				throw new MWException("Cannot check code $this->code.");
			} else {
				$this->results[$this->code] = $this->checkLanguage( $this->code );
			}
		}
	}

	protected function getCheckBlacklist() {
		static $checkBlacklist = null;
		if ( $checkBlacklist === null ) {
			$checkBlacklist = array();
			require( dirname(__FILE__) . '/checkLanguage.inc' );
		}
		return $checkBlacklist;
	}

	protected function checkLanguage( $code ) {
		# Syntax check only
		if ( $this->level === 0 ) {
			$this->L->getMessages( $code );
			return;
		}

		$results = array();
		$checkFunctions = $this->getChecks();
		$checkBlacklist = $this->getCheckBlacklist();
		foreach ( $this->checks as $check ) {
			if ( isset($checkBlacklist[$code]) &&
				in_array($check, $checkBlacklist[$code]) ) {
				$result[$check] = array();
				continue;
			}

			$callback = array( $this->L, $checkFunctions[$check] );
			if ( !is_callable($callback ) ) {
				throw new MWException( "Unkown check $check." );
			}
			$results[$check] = call_user_func( $callback , $code );
		}

		return $results;
	}

	protected function outputText( ) {
		foreach ( $this->results as $code => $results ) {
			$translated = $this->L->getMessages( $code );
			$translated = count( $translated['translated'] );
			foreach ( $results as $check => $messages ) {
				$count = count( $messages );
				if ( $count ) {
					$search = array( '$1', '$2', '$3' );
					$replace = array( $count, $translated, $code );
					$descriptions = $this->getDescriptions();
					echo "\n" . str_replace( $search, $replace, $descriptions[$check] ) . "\n";
					if ( $this->level == 1 ) {
						echo "[messages are hidden]\n";
					} else {
						foreach ( $messages as $key => $value ) {
							if ( $this->doLinks ) {
								$displayKey = ucfirst( $key );
								if ( $code == $this->wikiCode ) {
									$displayKey = "[[MediaWiki:$displayKey|$key]]";
								} else {
									$displayKey = "[[MediaWiki:$displayKey/$code|$key]]";
								}
							} else {
								$displayKey = $key;
							}
							if ( $this->level == 2 ) {
								echo "* $displayKey\n";
							} else {
								echo "* $displayKey:		'$value'\n";
							}
						}
					}
				}
			}
		}	
	}

	/**
	 * Globals: $wgContLang, $IP
	 */
	function outputWiki() {
		global $wgContLang, $IP;
		$detailText = '';
		$rows[] = '! Language !! Code !! Total !! ' . implode( ' !! ', $this->checks );
		foreach ( $this->results as $code => $results ) {
			$detailTextForLang = "==$code==\n";
			$numbers = array();
			$problems = 0;
			$detailTextForLangChecks = array();
			foreach ( $results as $check => $messages ) {
				$count = count( $messages );
				if ( $count ) {
					$problems += $count;
					$messageDetails = array();
					foreach ( $messages as $key => $details ) {
						$messageDetails[] = $key;
					}
					$detailTextForLangChecks[] = "===$code-$check===\n* " . implode( ', ', $messageDetails );
					$numbers[] = "'''[[#$code-$check|$count]]'''";
				} else {
					$numbers[] = $count;
				}

			}

			if ( count( $detailTextForLangChecks ) ) {
				$detailText .= $detailTextForLang . implode( "\n", $detailTextForLangChecks ) . "\n";
			}

			$language = $wgContLang->getLanguageName( $code );
			$rows[] = "| $language || $code || $problems || " . implode( ' || ', $numbers );
		}

		$tableRows = implode( "\n|-\n", $rows );

		$version = SpecialVersion::getVersion( $IP );
		echo <<<EOL
'''Check results are for:''' <code>$version</code>


{| class="sortable wikitable" border="2" cellpadding="4" cellspacing="0" style="background-color: #F9F9F9; border: 1px #AAAAAA solid; border-collapse: collapse; clear:both;"
$tableRows
|}

$detailText

EOL;
	}

}
