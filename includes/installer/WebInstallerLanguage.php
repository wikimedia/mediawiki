<?php
/**
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
 * @ingroup Deployment
 */

class WebInstallerLanguage extends WebInstallerPage {

	/**
	 * Language specific collations supported by MediaWiki
	 */
	protected $supportedLangCollations = [
		'af', 'ast', 'az', 'be', 'be-tarask', 'bg', 'br', 'bs', 'ca',
		'co', 'cs', 'cy', 'da', 'de', 'dsb', 'el', 'en', 'eo', 'es', 'et', 'eu', 'fa', 'fi',
		'fo', 'fr', 'fur', 'fy', 'ga', 'gd', 'gl', 'hr', 'hsb', 'hu', 'is', 'it', 'kk', 'kl',
		'ku', 'ky', 'la', 'lb', 'lt', 'lv', 'mk', 'mo', 'mt', 'nl', 'no', 'oc', 'pl', 'pt',
		'rm', 'ro', 'ru', 'rup', 'sco', 'sk', 'sl', 'smn', 'sq', 'sr', 'sv', 'ta', 'tk', 'tl',
		'tr', 'tt', 'uk', 'uz', 'vi'
	];

	/**
	 * @return string|null
	 */
	public function execute() {
		global $wgLang;
		$r = $this->parent->request;
		$userLang = $r->getVal( 'uselang' );
		$contLang = $r->getVal( 'ContLang' );

		$languages = Language::fetchLanguageNames();
		$lifetime = intval( ini_get( 'session.gc_maxlifetime' ) );
		if ( !$lifetime ) {
			$lifetime = 1440; // PHP default
		}

		if ( $r->wasPosted() ) {
			# Do session test
			if ( $this->parent->getSession( 'test' ) === null ) {
				$requestTime = $r->getVal( 'LanguageRequestTime' );
				if ( !$requestTime ) {
					// The most likely explanation is that the user was knocked back
					// from another page on POST due to session expiry
					$msg = 'config-session-expired';
				} elseif ( time() - $requestTime > $lifetime ) {
					$msg = 'config-session-expired';
				} else {
					$msg = 'config-no-session';
				}
				$this->parent->showError( $msg, $wgLang->formatTimePeriod( $lifetime ) );
			} else {
				if ( isset( $languages[$userLang] ) ) {
					$this->setVar( '_UserLang', $userLang );
				}
				if ( isset( $languages[$contLang] ) ) {
					$this->setVar( 'wgLanguageCode', $contLang );
					$this->setVar( 'wgCategoryCollation',
						$this->getCollation( $languages[$contLang] ) );
				}

				return 'continue';
			}
		} elseif ( $this->parent->showSessionWarning ) {
			# The user was knocked back from another page to the start
			# This probably indicates a session expiry
			$this->parent->showError( 'config-session-expired',
				$wgLang->formatTimePeriod( $lifetime ) );
		}

		$this->parent->setSession( 'test', true );

		if ( !isset( $languages[$userLang] ) ) {
			$userLang = $this->getVar( '_UserLang', 'en' );
		}
		if ( !isset( $languages[$contLang] ) ) {
			$contLang = $this->getVar( 'wgLanguageCode', 'en' );
		}
		$this->startForm();
		$s = Html::hidden( 'LanguageRequestTime', time() ) .
			$this->getLanguageSelector( 'uselang', 'config-your-language', $userLang,
				$this->parent->getHelpBox( 'config-your-language-help' ) ) .
			$this->getLanguageSelector( 'ContLang', 'config-wiki-language', $contLang,
				$this->parent->getHelpBox( 'config-wiki-language-help' ) );
		$this->addHTML( $s );
		$this->endForm( 'continue', false );

		return null;
	}

	/**
	 * Get a "<select>" for selecting languages.
	 *
	 * @param string $name
	 * @param string $label
	 * @param string $selectedCode
	 * @param string $helpHtml
	 *
	 * @return string
	 */
	public function getLanguageSelector( $name, $label, $selectedCode, $helpHtml = '' ) {
		global $wgDummyLanguageCodes;

		$output = $helpHtml;

		$select = new XmlSelect( $name, $name, $selectedCode );
		$select->setAttribute( 'tabindex', $this->parent->nextTabIndex() );

		$languages = Language::fetchLanguageNames();
		ksort( $languages );
		foreach ( $languages as $code => $lang ) {
			if ( isset( $wgDummyLanguageCodes[$code] ) ) {
				continue;
			}
			$select->addOption( "$code - $lang", $code );
		}

		$output .= $select->getHTML();
		return $this->parent->label( $label, $name, $output );
	}

	/**
	 * Get language appropriate collation value (T47611)
	 *
	 * @return string Collation value
	 */
	public function getCollation( $wikiLang ) {
		if ( extension_loaded( 'intl' ) ) {
			if ( in_array( $wikiLang, $this->supportedLangCollations ) ) {
				return 'uca-' . $wikiLang . '-u-kn';
			} else {
				return 'uca-default-u-kn';
			}
		}
		return 'numeric';

	}

}
