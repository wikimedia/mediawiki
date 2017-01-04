<?php
/**
 *
 *
 * Created on January 1, 2017
 *
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

/**
 * API module that facilitates changing the language of a page. The API equivalent of SpecialPageLanguage.
 * Requires API write mode to be enabled.
 *
 * @ingroup API
 */
class ApiSetPageLanguage extends ApiBase {
	/**
	 * Extracts the title and language from the request parameters and invokes
	 * the static SpecialPageLanguage::changePageLanguage() function with these as arguments.
	 * If the language change succeeds, the details of the article changed
	 * and the reason for the language change are added to the result object.
	 */
	public function execute() {
		// Check if change language feature is enabled
		global $wgPageLanguageuseDB;
		if ( !$wgPageLanguageuseDB ) {
			$this->dieWithError( 'apierror-pagelang-disabled' );
		}

		// Check if the user has permissions
		$this->checkUserRightsAny( 'pagelang' );

		$this->useTransactionalTimeLimit();

		$params = $this->extractRequestParams();

		$pageObj = $this->getTitleOrPageId( $params, 'fromdbmaster' );
		if ( !$pageObj->exists() ) {
			$this->dieWithError( 'apierror-missingtitle' );
		}

		$titleObj = $pageObj->getTitle();
		$reason = $params['reason'];
		$user = $this->getUser();

		// Check that the user is allowed to edit the page
		$this->checkTitleUserPermissions( $titleObj, 'edit' );

		// If change tagging was requested, check that the user is allowed to tag,
		// and the tags are valid
		if ( count( $params['tags'] ) ) {
			$tagStatus = ChangeTags::canAddTagsAccompanyingChange( $params['tags'], $user );
			if ( !$tagStatus->isOK() ) {
				$this->dieStatus( $tagStatus );
			}
		}

		$status = SpecialPageLanguage::changePageLanguage(
			$this->getContext(),
			$titleObj,
			$params['lang'],
			$params['tags']
		);

		if ( !$status->isGood() ) {
			$this->dieStatus( $status );
		}

		$r = [
			'title' => $titleObj->getPrefixedText(),
			'performer' => $status->value['performer'],
			'oldLanguage' => $status->value['oldLanguage'],
			'newLanguage' => $status->value['newLanguage'],
			'logId' => $status->value['logId'],
			'reason' => $params['reason']
		];
		$this->getResult()->addValue( null, $this->getModuleName(), $r );
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return [
			'title' => null,
			'pageid' => [
				ApiBase::PARAM_TYPE => 'integer'
			],
			'reason' => null,
			'tags' => [
				ApiBase::PARAM_TYPE => 'tags',
				ApiBase::PARAM_ISMULTI => true,
			],
			'lang' => [
				ApiBase::PARAM_TYPE => array_merge(
					array_keys( Language::fetchLanguageNames( null, 'mwfile' ) ),
					'default'
				)
			],
		];
	}

	public function needsToken() {
		return 'csrf';
	}

	protected function getExamplesMessages() {
		return [
			'action=delete&title=Main%20Page&token=123ABC'
				=> 'apihelp-delete-example-simple',
			'action=delete&title=Main%20Page&token=123ABC&reason=Preparing%20for%20move'
				=> 'apihelp-delete-example-reason',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:ChangePageLanguage';
	}
}
