<?php
/**
 *
 *
 * Created on May 13, 2007
 *
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

/**
 * A query module to list all langlinks (links to corresponding foreign language pages).
 *
 * @ingroup API
 */
class ApiQueryLangLinks extends ApiQueryBase {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'll' );
	}

	public function execute() {
		if ( $this->getPageSet()->getGoodTitleCount() == 0 ) {
			return;
		}

		$params = $this->extractRequestParams();

		if ( isset( $params['title'] ) && !isset( $params['lang'] ) ) {
			$this->dieUsageMsg( array( 'missingparam', 'lang' ) );
		}

		$fromPageIds = array_keys( $this->getPageSet()->getGoodTitles() );
		$dir = $params['dir'];
		$limit  = $params['limit'];

		$forLang = isset( $params['lang'] ) ? $params['lang'] : null;
		$forTitle = isset( $params['title'] ) ? $params['title'] : null;

		$continueFrom = null;
		$continueLang = null;

		if ( !is_null( $params['continue'] ) ) {
			$cont = explode( '|', $params['continue'] );
			$this->dieContinueUsageIf( count( $cont ) != 2 );

			$continueFrom = intval( $cont[0] );
			$continueLang = strval( $cont[1] );
		}

		$loader = new DBLangLinkLoader();

		if ( $params['effective'] ) {
			$loader = new HookedLangLinkLoader( $loader );
		}

		//TODO: catch & handle (some) errors
		$links = $loader->loadLanguageLinks(
			$fromPageIds,
			$dir,
			$limit +1, // +1 to detect continuation point
			$forLang,
			$forTitle,
			$continueFrom,
			$continueLang );

		$count = 0;
		foreach ( $links as $row ) {
			if ( ++$count > $params['limit'] ) {
				// We've reached the one extra which shows that
				// there are additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'continue', "{$row->ll_from}|{$row->ll_lang}" );
				break;
			}
			$entry = array( 'lang' => $row->ll_lang );
			if ( $params['url'] ) {
				$title = Title::newFromText( "{$row->ll_lang}:{$row->ll_title}" );
				if ( $title ) {
					$entry['url'] = wfExpandUrl( $title->getFullURL(), PROTO_CURRENT );
				}
			}
			if ( isset( $row->ll_flags ) ) {
				$entry['flags'] = implode( '|', (array)$row->ll_flags );
			}
			ApiResult::setContent( $entry, $row->ll_title );
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
		return array(
			'limit' => array(
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			),
			'continue' => null,
			'url' => false,
			'effective' => false,
			'lang' => null,
			'title' => null,
			'dir' => array(
				ApiBase::PARAM_DFLT => 'ascending',
				ApiBase::PARAM_TYPE => array(
					'ascending',
					'descending'
				)
			),
		);
	}

	public function getParamDescription() {
		return array(
			'limit' => 'How many langlinks to return',
			'continue' => 'When more results are available, use this to continue',
			'url' => 'Whether to get the full URL',
			'effective' => array(
				'Whether to determine effective language links,',
				'including all provided by extensions' ),
			'lang' => 'Language code',
			'title' => "Link to search for. Must be used with {$this->getModulePrefix()}lang",
			'dir' => 'The direction in which to list',
		);
	}

	public function getResultProperties() {
		return array(
			'' => array(
				'lang' => 'string',
				'flags' => 'string',
				'url' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				),
				'*' => 'string'
			)
		);
	}

	public function getDescription() {
		return 'Returns all interlanguage links from the given page(s)';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'missingparam', 'lang' ),
			array( 'code' => 'toomanylinks', 'info' => 'If too many links were found in "effective" mode.' ),
		) );
	}

	public function getExamples() {
		return array(
			'api.php?action=query&prop=langlinks&titles=Main%20Page&redirects=' => 'Get interlanguage links from the [[Main Page]]',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Properties#langlinks_.2F_ll';
	}
}
