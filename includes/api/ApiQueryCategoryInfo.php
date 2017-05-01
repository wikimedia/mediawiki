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
 * This query adds the "<categories>" subelement to all pages with the list of
 * categories the page is in.
 *
 * @ingroup API
 */
class ApiQueryCategoryInfo extends ApiQueryBase {

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'ci' );
	}

	public function execute() {
		$params = $this->extractRequestParams();
		$alltitles = $this->getPageSet()->getGoodAndMissingTitlesByNamespace();
		if ( empty( $alltitles[NS_CATEGORY] ) ) {
			return;
		}
		$categories = $alltitles[NS_CATEGORY];

		$titles = $this->getPageSet()->getGoodAndMissingTitles();
		$cattitles = [];
		foreach ( $categories as $c ) {
			/** @var $t Title */
			$t = $titles[$c];
			$cattitles[$c] = $t->getDBkey();
		}

		$this->addTables( [ 'category', 'page', 'page_props' ] );
		$this->addJoinConds( [
			'page' => [ 'LEFT JOIN', [
				'page_namespace' => NS_CATEGORY,
				'page_title=cat_title' ] ],
			'page_props' => [ 'LEFT JOIN', [
				'pp_page=page_id',
				'pp_propname' => 'hiddencat' ] ],
		] );

		$this->addFields( [
			'cat_title',
			'cat_pages',
			'cat_subcats',
			'cat_files',
			'cat_hidden' => 'pp_propname'
		] );
		$this->addWhere( [ 'cat_title' => $cattitles ] );

		if ( !is_null( $params['continue'] ) ) {
			$title = $this->getDB()->addQuotes( $params['continue'] );
			$this->addWhere( "cat_title >= $title" );
		}
		$this->addOption( 'ORDER BY', 'cat_title' );

		$res = $this->select( __METHOD__ );

		$catids = array_flip( $cattitles );
		foreach ( $res as $row ) {
			$vals = [];
			$vals['size'] = intval( $row->cat_pages );
			$vals['pages'] = $row->cat_pages - $row->cat_subcats - $row->cat_files;
			$vals['files'] = intval( $row->cat_files );
			$vals['subcats'] = intval( $row->cat_subcats );
			$vals['hidden'] = (bool)$row->cat_hidden;
			$fit = $this->addPageSubItems( $catids[$row->cat_title], $vals );
			if ( !$fit ) {
				$this->setContinueEnumParameter( 'continue', $row->cat_title );
				break;
			}
		}
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function getAllowedParams() {
		return [
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=query&prop=categoryinfo&titles=Category:Foo|Category:Bar'
				=> 'apihelp-query+categoryinfo-example-simple',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Categoryinfo';
	}
}
