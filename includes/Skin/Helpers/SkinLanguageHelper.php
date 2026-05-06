<?php
/**
 * @license GPL-2.0-or-later
 */

namespace MediaWiki\Skin\Helpers;

use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Language\Language;
use MediaWiki\Language\LanguageCode;
use MediaWiki\Language\LanguageNameUtils;
use MediaWiki\MediaWikiServices;
use MediaWiki\Skin\Skin;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;

/**
 * Helper class for generating interlanguage link data.
 *
 * Extracted from Skin::getLanguages() to keep Skin focused on rendering.
 *
 * @internal for use inside Skin and SkinTemplate classes only
 * @unstable
 */
class SkinLanguageHelper {

	public function __construct(
		private readonly Skin $skin
	) {
	}

	/**
	 * Generates array of language links for the current page.
	 *
	 * @return array[]
	 */
	public function getData(): array {
		$services = MediaWikiServices::getInstance();
		$hookRunner = new HookRunner( $services->getHookContainer() );
		$userLang = $this->skin->getLanguage();
		$languageLinks = [];
		$langNameUtils = $services->getLanguageNameUtils();
		// Use a Language without locale-specific ucfirst overrides (T294695).
		// Turkish and Azerbaijani override ucfirst to map i→İ, which
		// incorrectly capitalizes autonyms like 'italiano' to 'İtaliano'.
		$defaultCaseLang = $services->getLanguageFactory()->getLanguage( 'en' );

		foreach ( $this->skin->getOutput()->getLanguageLinks() as $languageLinkText ) {
			$languageLink = $this->buildLanguageLink(
				$languageLinkText, $langNameUtils, $defaultCaseLang, $userLang
			);
			if ( $languageLink === null ) {
				continue;
			}
			$languageLinkFullTitle = $languageLink['fullTitle'];
			unset( $languageLink['fullTitle'] );
			$hookRunner->onSkinTemplateGetLanguageLink(
				$languageLink, $languageLinkFullTitle, $this->skin->getTitle(), $this->skin->getOutput()
			);
			$languageLinks[] = $languageLink;
		}

		return $languageLinks;
	}

	/**
	 * Build a single language link array from a language link text.
	 *
	 * @return array|null The language link data, or null if the link is invalid.
	 */
	private function buildLanguageLink(
		string $languageLinkText,
		LanguageNameUtils $langNameUtils,
		Language $defaultCaseLang,
		Language $userLang
	): ?array {
		[ $prefix, $title ] = explode( ':', $languageLinkText, 2 );
		$class = 'interlanguage-link interwiki-' . $prefix;

		[ $title, $frag ] = array_pad( explode( '#', $title, 2 ), 2, '' );
		$languageLinkTitle = TitleValue::tryNew( NS_MAIN, $title, $frag, $prefix );
		if ( $languageLinkTitle === null ) {
			return null;
		}
		$ilInterwikiCode = $this->skin->mapInterwikiToLanguage( $prefix );

		$ilLangName = $langNameUtils->getLanguageName( $ilInterwikiCode );

		if ( strval( $ilLangName ) === '' ) {
			$ilDisplayTextMsg = $this->skin->msg( "interlanguage-link-$ilInterwikiCode" );
			if ( !$ilDisplayTextMsg->isDisabled() ) {
				// Use custom MW message for the display text
				$ilLangName = $ilDisplayTextMsg->text();
			} else {
				// Last resort: fallback to the language link target
				$ilLangName = $languageLinkText;
			}
		} else {
			// Use the language autonym as display text
			$ilLangName = $defaultCaseLang->ucfirst( $ilLangName );
		}

		// CLDR extension or similar is required to localize the language name;
		// otherwise we'll end up with the autonym again.
		$ilLangLocalName =
			$langNameUtils->getLanguageName( $ilInterwikiCode, $userLang->getCode() );

		$languageLinkTitleText = $languageLinkTitle->getText();
		if ( $ilLangLocalName === '' ) {
			$ilFriendlySiteName =
				$this->skin->msg( "interlanguage-link-sitename-$ilInterwikiCode" );
			if ( !$ilFriendlySiteName->isDisabled() ) {
				if ( $languageLinkTitleText === '' ) {
					$ilTitle =
						$this->skin->msg( 'interlanguage-link-title-nonlangonly',
							$ilFriendlySiteName->text() )->text();
				} else {
					$ilTitle =
						$this->skin->msg( 'interlanguage-link-title-nonlang',
							$languageLinkTitleText, $ilFriendlySiteName->text() )->text();
				}
			} else {
				// we have nothing friendly to put in the title, so fall back to
				// displaying the interlanguage link itself in the title text
				// (similar to what is done in page content)
				$ilTitle = $languageLinkTitle->getInterwiki() . ":$languageLinkTitleText";
			}
		} elseif ( $languageLinkTitleText === '' ) {
			$ilTitle =
				$this->skin->msg( 'interlanguage-link-title-langonly', $ilLangLocalName )->text();
		} else {
			$ilTitle =
				$this->skin->msg( 'interlanguage-link-title', $languageLinkTitleText,
					$ilLangLocalName )->text();
		}

		$ilInterwikiCodeBCP47 = LanguageCode::bcp47( $ilInterwikiCode );
		// A TitleValue is sufficient above this point, but we need
		// a full Title for ::getFullURL() and the hook invocation
		$languageLinkFullTitle = Title::newFromLinkTarget( $languageLinkTitle );
		return [
			'href' => $languageLinkFullTitle->getFullURL(),
			'text' => $ilLangName,
			'title' => $ilTitle,
			'class' => $class,
			'link-class' => 'interlanguage-link-target',
			'lang' => $ilInterwikiCodeBCP47,
			'hreflang' => $ilInterwikiCodeBCP47,
			'data-title' => $languageLinkTitleText,
			'data-language-autonym' => $ilLangName,
			'data-language-local-name' => $ilLangLocalName,
			// Temporarily pass the full title for hook invocation;
			// removed by getData() before returning.
			'fullTitle' => $languageLinkFullTitle,
		];
	}
}
