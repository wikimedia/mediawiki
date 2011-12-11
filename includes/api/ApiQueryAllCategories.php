<?php
/**
 *
 *
 * Created on December 12, 2007
 *
 * Copyright © 2007 Roan Kattouw <Firstname>.<Lastname>@gmail.com
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
 * Query module to enumerate all categories, even the ones that don't have
 * category pages.
 *
 * @ingroup API
 */
class ApiQueryAllCategories extends ApiQueryGeneratorBase {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'ac' );
	}

	public function execute() {
		$this->run();
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	/**
	 * @param $resultPageSet ApiPageSet
	 */
	private function run( $resultPageSet = null ) {
		$db = $this->getDB();
		$params = $this->extractRequestParams();

		$this->addTables( 'category' );
		$this->addFields( 'cat_title' );

		$dir = ( $params['dir'] == 'descending' ? 'older' : 'newer' );
		$from = ( is_null( $params['from'] ) ? null : $this->titlePartToKey( $params['from'] ) );
		$to = ( is_null( $params['to'] ) ? null : $this->titlePartToKey( $params['to'] ) );
		$this->addWhereRange( 'cat_title', $dir, $from, $to );

		$min = $params['min'];
		$max = $params['max'];
		$this->addWhereRange( 'cat_pages', $dir, $min, $max );

		if ( isset( $params['prefix'] ) ) {
			$this->addWhere( 'cat_title' . $db->buildLike( $this->titlePartToKey( $params['prefix'] ), $db->anyString() ) );
		}

		$this->addOption( 'LIMIT', $params['limit'] + 1 );
		$this->addOption( 'ORDER BY', 'cat_title' . ( $params['dir'] == 'descending' ? ' DESC' : '' ) );

		$prop = array_flip( $params['prop'] );
		$this->addFieldsIf( array( 'cat_pages', 'cat_subcats', 'cat_files' ), isset( $prop['size'] ) );
		if ( isset( $prop['hidden'] ) ) {
			$this->addTables( array( 'page', 'page_props' ) );
			$this->addJoinConds( array(
				'page' => array( 'LEFT JOIN', array(
					'page_namespace' => NS_CATEGORY,
					'page_title=cat_title' ) ),
				'page_props' => array( 'LEFT JOIN', array(
					'pp_page=page_id',
					'pp_propname' => 'hiddencat' ) ),
			) );
			$this->addFields( 'pp_propname AS cat_hidden' );
		}

		$res = $this->select( __METHOD__ );

		$pages = array();

		$result = $this->getResult();
		$count = 0;
		foreach ( $res as $row ) {
			if ( ++ $count > $params['limit'] ) {
				// We've reached the one extra which shows that there are additional cats to be had. Stop here...
				// TODO: Security issue - if the user has no right to view next title, it will still be shown
				$this->setContinueEnumParameter( 'from', $this->keyToTitle( $row->cat_title ) );
				break;
			}

			// Normalize titles
			$titleObj = Title::makeTitle( NS_CATEGORY, $row->cat_title );
			if ( !is_null( $resultPageSet ) ) {
				$pages[] = $titleObj->getPrefixedText();
			} else {
				$item = array();
				$result->setContent( $item, $titleObj->getText() );
				if ( isset( $prop['size'] ) ) {
					$item['size'] = intval( $row->cat_pages );
					$item['pages'] = $row->cat_pages - $row->cat_subcats - $row->cat_files;
					$item['files'] = intval( $row->cat_files );
					$item['subcats'] = intval( $row->cat_subcats );
				}
				if ( isset( $prop['hidden'] ) && $row->cat_hidden ) {
					$item['hidden'] = '';
				}
				$fit = $result->addValue( array( 'query', $this->getModuleName() ), null, $item );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'from', $this->keyToTitle( $row->cat_title ) );
					break;
				}
			}
		}

		if ( is_null( $resultPageSet ) ) {
			$result->setIndexedTagName_internal( array( 'query', $this->getModuleName() ), 'c' );
		} else {
			$resultPageSet->populateFromTitles( $pages );
		}
	}

	public function getAllowedParams() {
		return array(
			'from' => null,
			'to' => null,
			'prefix' => null,
			'dir' => array(
				ApiBase::PARAM_DFLT => 'ascending',
				ApiBase::PARAM_TYPE => array(
					'ascending',
					'descending'
				),
			),
			'min' => array(
				ApiBase::PARAM_DFLT => null,
				ApiBase::PARAM_TYPE => 'integer'
			),
			'max' => array(
				ApiBase::PARAM_DFLT => null,
				ApiBase::PARAM_TYPE => 'integer'
			),
			'limit' => array(
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			),
			'prop' => array(
				ApiBase::PARAM_TYPE => array( 'size', 'hidden' ),
				ApiBase::PARAM_DFLT => '',
				ApiBase::PARAM_ISMULTI => true
			),
		);
	}

	public function getParamDescription() {
		return array(
			'from' => 'The category to start enumerating from',
			'to' => 'The category to stop enumerating at',
			'prefix' => 'Search for all category titles that begin with this value',
			'dir' => 'Direction to sort in',
			'min' => 'Minimum number of category members',
			'max' => 'Maximum number of category members',
			'limit' => 'How many categories to return',
			'prop' => array(
				'Which properties to get',
				' size    - Adds number of pages in the category',
				' hidden  - Tags categories that are hidden with __HIDDENCAT__',
			),
		);
	}

	public function getDescription() {
		return 'Enumerate all categories';
	}

	public function getExamples() {
		return array(
			'api.php?action=query&list=allcategories&acprop=size',
			'api.php?action=query&generator=allcategories&gacprefix=List&prop=info',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Allcategories';
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
