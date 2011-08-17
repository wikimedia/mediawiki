<?php
/**
 * API for MediaWiki 1.17+
 *
 * Created on May 14, 2011
 *
 * Copyright © 2011 Sam Reed
 * Copyright © 2006 Yuri Astrakhan <Firstname><Lastname>@gmail.com
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

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once( "ApiQueryBase.php" );
}

/**
 * This gives links pointing to the given interwiki
 * @ingroup API
 */
class ApiQueryLangBacklinks extends ApiQueryGeneratorBase {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'lbl' );
	}

	public function execute() {
		$this->run();
	}

	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	/**
	 * @param $resultPageSet ApiPageSet
	 * @return void
	 */
	public function run( $resultPageSet = null ) {
		$params = $this->extractRequestParams();

		if ( isset( $params['title'] ) && !isset( $params['lang'] ) ) {
			$this->dieUsageMsg( array( 'missingparam', 'lang' ) );
		}

		if ( !is_null( $params['continue'] ) ) {
			$cont = explode( '|', $params['continue'] );
			if ( count( $cont ) != 3 ) {
				$this->dieUsage( 'Invalid continue param. You should pass the ' .
					'original value returned by the previous query', '_badcontinue' );
			}

			$prefix = $this->getDB()->strencode( $cont[0] );
			$title = $this->getDB()->strencode( $this->titleToKey( $cont[1] ) );
			$from = intval( $cont[2] );
			$this->addWhere(
				"ll_lang > '$prefix' OR " .
				"(ll_lang = '$prefix' AND " .
				"(ll_title > '$title' OR " .
				"(ll_title = '$title' AND " .
				"ll_from >= $from)))"
			);
		}

		$prop = array_flip( $params['prop'] );
		$lllang = isset( $prop['lllang'] );
		$lltitle = isset( $prop['lltitle'] );

		$this->addTables( array( 'langlinks', 'page' ) );
		$this->addWhere( 'll_from = page_id' );

		$this->addFields( array( 'page_id', 'page_title', 'page_namespace', 'page_is_redirect',
			'll_from', 'll_lang', 'll_title' ) );

		if ( isset( $params['lang'] ) ) {
			$this->addWhereFld( 'll_lang', $params['lang'] );
			if ( isset( $params['title'] ) ) {
				$this->addWhereFld( 'll_title', $params['title'] );
				$this->addOption( 'ORDER BY', 'll_from' );
			} else {
				$this->addOption( 'ORDER BY', 'll_title, ll_from' );
			}
		} else {
			$this->addOption( 'ORDER BY', 'll_lang, ll_title, ll_from' );
		}

		$this->addOption( 'LIMIT', $params['limit'] + 1 );

		$res = $this->select( __METHOD__ );

		$pages = array();

		$count = 0;
		$result = $this->getResult();
		foreach ( $res as $row ) {
			if ( ++ $count > $params['limit'] ) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				// Continue string preserved in case the redirect query doesn't pass the limit
				$this->setContinueEnumParameter( 'continue', "{$row->ll_lang}|{$row->ll_title}|{$row->ll_from}" );
				break;
			}

			if ( !is_null( $resultPageSet ) ) {
				$pages[] = Title::newFromRow( $row );
			} else {
				$entry = array( 'pageid' => $row->page_id );

				$title = Title::makeTitle( $row->page_namespace, $row->page_title );
				ApiQueryBase::addTitleInfo( $entry, $title );

				if ( $row->page_is_redirect ) {
					$entry['redirect'] = '';
				}

				if ( $lllang ) {
					$entry['lllang'] = $row->ll_lang;
				}

				if ( $lltitle ) {
					$entry['lltitle'] = $row->ll_title;
				}

				$fit = $result->addValue( array( 'query', $this->getModuleName() ), null, $entry );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'continue', "{$row->ll_lang}|{$row->ll_title}|{$row->ll_from}" );
					break;
				}
			}
		}

		if ( is_null( $resultPageSet ) ) {
			$result->setIndexedTagName_internal( array( 'query', $this->getModuleName() ), 'll' );
		} else {
			$resultPageSet->populateFromTitles( $pages );
		}
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function getAllowedParams() {
		return array(
			'lang' => null,
			'title' => null,
			'continue' => null,
			'limit' => array(
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			),
			'prop' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => '',
				ApiBase::PARAM_TYPE => array(
					'lllang',
					'lltitle',
				),
			),
		);
	}

	public function getParamDescription() {
		return array(
			'lang' => 'Language for the language link',
			'title' => "Language link to search for. Must be used with {$this->getModulePrefix()}lang",
			'continue' => 'When more results are available, use this to continue',
			'prop' => array(
				'Which properties to get',
				' lllang         - Adds the language code of the language link',
				' lltitle        - Adds the title of the language ink',
			),
			'limit' => 'How many total pages to return',
		);
	}

	public function getDescription() {
		return array( 'Find all pages that link to the given language link.',
			'Can be used to find all links with a language code, or',
			'all links to a title (with a given language).',
			'Using neither parameter is effectively "All Language Links"',
		);
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'missingparam', 'lang' ),
			array( 'code' => '_badcontinue', 'info' => 'Invalid continue param. You should pass the original value returned by the previous query' ),
		) );
	}

	public function getExamples() {
		return array(
			'api.php?action=query&list=langbacklinks&lbltitle=Test&lbllang=fr',
			'api.php?action=query&generator=langbacklinks&glbltitle=Test&lbllang=fr&prop=info'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
