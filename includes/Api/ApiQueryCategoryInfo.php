<?php
/**
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\Title\Title;

/**
 * This query adds the "<categories>" subelement to all pages with the list of
 * categories the page is in.
 *
 * @ingroup API
 */
class ApiQueryCategoryInfo extends ApiQueryBase {

	public function __construct( ApiQuery $query, string $moduleName ) {
		parent::__construct( $query, $moduleName, 'ci' );
	}

	public function execute() {
		$params = $this->extractRequestParams();
		$alltitles = $this->getPageSet()->getGoodAndMissingTitlesByNamespace();
		if ( empty( $alltitles[NS_CATEGORY] ) ) {
			return;
		}
		$categories = $alltitles[NS_CATEGORY];

		$titles = $this->getPageSet()->getGoodAndMissingPages();
		$cattitles = [];
		foreach ( $categories as $c ) {
			/** @var Title $t */
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

		if ( $params['continue'] !== null ) {
			$this->addWhere( $this->getDB()->expr( 'cat_title', '>=', $params['continue'] ) );
		}
		$this->addOption( 'ORDER BY', 'cat_title' );

		$res = $this->select( __METHOD__ );

		$catids = array_flip( $cattitles );
		foreach ( $res as $row ) {
			$vals = [];
			$vals['size'] = (int)$row->cat_pages;
			$vals['pages'] = $row->cat_pages - $row->cat_subcats - $row->cat_files;
			$vals['files'] = (int)$row->cat_files;
			$vals['subcats'] = (int)$row->cat_subcats;
			$vals['hidden'] = (bool)$row->cat_hidden;
			$fit = $this->addPageSubItems( $catids[$row->cat_title], $vals );
			if ( !$fit ) {
				$this->setContinueEnumParameter( 'continue', $row->cat_title );
				break;
			}
		}
	}

	/** @inheritDoc */
	public function getCacheMode( $params ) {
		return 'public';
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=query&prop=categoryinfo&titles=Category:Foo|Category:Bar'
				=> 'apihelp-query+categoryinfo-example-simple',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Categoryinfo';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryCategoryInfo::class, 'ApiQueryCategoryInfo' );
