<?php
/**
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
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

use MediaWiki\MediaWikiServices;

/**
 * A query module to list all langlinks (links to corresponding foreign language pages).
 *
 * @ingroup API
 */
class ApiQueryLangLinks extends ApiQueryBase {

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'll' );
	}

	public function execute() {
		if ( $this->getPageSet()->getGoodTitleCount() == 0 ) {
			return;
		}

		$params = $this->extractRequestParams();
		$prop = array_flip( (array)$params['prop'] );

		if ( isset( $params['title'] ) && !isset( $params['lang'] ) ) {
			$this->dieWithError(
				[
					'apierror-invalidparammix-mustusewith',
					$this->encodeParamName( 'title' ),
					$this->encodeParamName( 'lang' ),
				],
				'invalidparammix'
			);
		}

		// Handle deprecated param
		$this->requireMaxOneParameter( $params, 'url', 'prop' );
		if ( $params['url'] ) {
			$prop = [ 'url' => 1 ];
		}

		$this->addFields( [
			'll_from',
			'll_lang',
			'll_title'
		] );

		$this->addTables( 'langlinks' );
		$this->addWhereFld( 'll_from', array_keys( $this->getPageSet()->getGoodTitles() ) );
		if ( $params['continue'] !== null ) {
			$cont = explode( '|', $params['continue'] );
			$this->dieContinueUsageIf( count( $cont ) != 2 );
			$op = $params['dir'] == 'descending' ? '<' : '>';
			$llfrom = (int)$cont[0];
			$lllang = $this->getDB()->addQuotes( $cont[1] );
			$this->addWhere(
				"ll_from $op $llfrom OR " .
				"(ll_from = $llfrom AND " .
				"ll_lang $op= $lllang)"
			);
		}

		// FIXME: (follow-up) To allow extensions to add to the language links, we need
		//       to load them all, add the extra links, then apply paging.
		//       Should not be terrible, it's not going to be more than a few hundred links.

		// Note that, since (ll_from, ll_lang) is a unique key, we don't need
		// to sort by ll_title to ensure deterministic ordering.
		$sort = ( $params['dir'] == 'descending' ? ' DESC' : '' );
		if ( isset( $params['lang'] ) ) {
			$this->addWhereFld( 'll_lang', $params['lang'] );
			if ( isset( $params['title'] ) ) {
				$this->addWhereFld( 'll_title', $params['title'] );
			}
			$this->addOption( 'ORDER BY', 'll_from' . $sort );
		} else {
			// Don't order by ll_from if it's constant in the WHERE clause
			if ( count( $this->getPageSet()->getGoodTitles() ) == 1 ) {
				$this->addOption( 'ORDER BY', 'll_lang' . $sort );
			} else {
				$this->addOption( 'ORDER BY', [
					'll_from' . $sort,
					'll_lang' . $sort
				] );
			}
		}

		$this->addOption( 'LIMIT', $params['limit'] + 1 );
		$res = $this->select( __METHOD__ );

		$count = 0;
		foreach ( $res as $row ) {
			if ( ++$count > $params['limit'] ) {
				// We've reached the one extra which shows that
				// there are additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'continue', "{$row->ll_from}|{$row->ll_lang}" );
				break;
			}
			$entry = [ 'lang' => $row->ll_lang ];
			if ( isset( $prop['url'] ) ) {
				$title = Title::newFromText( "{$row->ll_lang}:{$row->ll_title}" );
				if ( $title ) {
					$entry['url'] = wfExpandUrl( $title->getFullURL(), PROTO_CURRENT );
				}
			}
			$languageNameUtils = MediaWikiServices::getInstance()->getLanguageNameUtils();
			if ( isset( $prop['langname'] ) ) {
				$entry['langname'] = $languageNameUtils
					->getLanguageName( $row->ll_lang, $params['inlanguagecode'] );
			}
			if ( isset( $prop['autonym'] ) ) {
				$entry['autonym'] = $languageNameUtils->getLanguageName( $row->ll_lang );
			}
			ApiResult::setContentValue( $entry, 'title', $row->ll_title );
			$fit = $this->addPageSubItem( $row->ll_from, $entry );
			if ( !$fit ) {
				$this->setContinueEnumParameter( 'continue', "{$row->ll_from}|{$row->ll_lang}" );
				break;
			}
		}
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function getAllowedParams() {
		return [
			'prop' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => [
					'url',
					'langname',
					'autonym',
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'lang' => null,
			'title' => null,
			'dir' => [
				ApiBase::PARAM_DFLT => 'ascending',
				ApiBase::PARAM_TYPE => [
					'ascending',
					'descending'
				]
			],
			'inlanguagecode' => MediaWikiServices::getInstance()->getContentLanguage()->getCode(),
			'limit' => [
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'url' => [
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_DEPRECATED => true,
			],
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=query&prop=langlinks&titles=Main%20Page&redirects='
				=> 'apihelp-query+langlinks-example-simple',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Langlinks';
	}
}
