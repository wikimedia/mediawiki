<?php

class WebInstallerLanguage extends WebInstallerPage {

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

}

