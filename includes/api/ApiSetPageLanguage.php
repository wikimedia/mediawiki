<?php
/**
 * Copyright © 2017 Justin Du "<justin.d128@gmail.com>"
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
 */

namespace MediaWiki\Api;

use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\MainConfigNames;
use MediaWiki\Specials\SpecialPageLanguage;
use MediaWiki\Title\Title;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * API module that facilitates changing the language of a page.
 * The API equivalent of SpecialPageLanguage.
 * Requires API write mode to be enabled.
 *
 * @ingroup API
 */
class ApiSetPageLanguage extends ApiBase {

	private IConnectionProvider $dbProvider;
	private LanguageNameUtils $languageNameUtils;

	public function __construct(
		ApiMain $mainModule,
		string $moduleName,
		IConnectionProvider $dbProvider,
		LanguageNameUtils $languageNameUtils
	) {
		parent::__construct( $mainModule, $moduleName );
		$this->dbProvider = $dbProvider;
		$this->languageNameUtils = $languageNameUtils;
	}

	// Check if change language feature is enabled
	protected function getExtendedDescription() {
		if ( !$this->getConfig()->get( MainConfigNames::PageLanguageUseDB ) ) {
			return 'apihelp-setpagelanguage-extended-description-disabled';
		}
		return parent::getExtendedDescription();
	}

	/**
	 * Extracts the title and language from the request parameters and invokes
	 * the static SpecialPageLanguage::changePageLanguage() function with these as arguments.
	 * If the language change succeeds, the title, old language, and new language
	 * of the article changed, as well as the performer of the language change
	 * are added to the result object.
	 */
	public function execute() {
		// Check if change language feature is enabled
		if ( !$this->getConfig()->get( MainConfigNames::PageLanguageUseDB ) ) {
			$this->dieWithError( 'apierror-pagelang-disabled' );
		}

		// Check if the user has permissions
		$this->checkUserRightsAny( 'pagelang' );

		$this->useTransactionalTimeLimit();

		$params = $this->extractRequestParams();

		$pageObj = $this->getTitleOrPageId( $params, 'fromdbmaster' );
		$titleObj = $pageObj->getTitle();
		$this->getErrorFormatter()->setContextTitle( $titleObj );
		if ( !$pageObj->exists() ) {
			$this->dieWithError( 'apierror-missingtitle' );
		}

		// Check that the user is allowed to edit the page
		$this->checkTitleUserPermissions( $titleObj, 'edit' );

		// If change tagging was requested, check that the user is allowed to tag,
		// and the tags are valid
		if ( $params['tags'] ) {
			$tagStatus = ChangeTags::canAddTagsAccompanyingChange( $params['tags'], $this->getAuthority() );
			if ( !$tagStatus->isOK() ) {
				$this->dieStatus( $tagStatus );
			}
		}

		$status = SpecialPageLanguage::changePageLanguage(
			$this,
			$titleObj,
			$params['lang'],
			$params['reason'] ?? '',
			$params['tags'] ?: [],
			$this->dbProvider->getPrimaryDatabase()
		);

		if ( !$status->isOK() ) {
			$this->dieStatus( $status );
		}

		$r = [
			'title' => $titleObj->getPrefixedText(),
			'oldlanguage' => $status->value->oldLanguage,
			'newlanguage' => $status->value->newLanguage,
			'logid' => $status->value->logId
		];
		$this->getResult()->addValue( null, $this->getModuleName(), $r );
	}

	/** @inheritDoc */
	public function mustBePosted() {
		return true;
	}

	/** @inheritDoc */
	public function isWriteMode() {
		return true;
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'title' => null,
			'pageid' => [
				ParamValidator::PARAM_TYPE => 'integer'
			],
			'lang' => [
				ParamValidator::PARAM_TYPE => array_merge(
					[ 'default' ],
					array_keys( $this->languageNameUtils->getLanguageNames(
						LanguageNameUtils::AUTONYMS,
						LanguageNameUtils::SUPPORTED
					) )
				),
				ParamValidator::PARAM_REQUIRED => true,
			],
			'reason' => null,
			'tags' => [
				ParamValidator::PARAM_TYPE => 'tags',
				ParamValidator::PARAM_ISMULTI => true,
			],
		];
	}

	/** @inheritDoc */
	public function needsToken() {
		return 'csrf';
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		$title = Title::newMainPage()->getPrefixedText();
		$mp = rawurlencode( $title );

		return [
			"action=setpagelanguage&title={$mp}&lang=eu&token=123ABC"
				=> 'apihelp-setpagelanguage-example-language',
			'action=setpagelanguage&pageid=123&lang=default&token=123ABC'
				=> 'apihelp-setpagelanguage-example-default',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:SetPageLanguage';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiSetPageLanguage::class, 'ApiSetPageLanguage' );
