<?php

namespace MediaWiki\Api;

use MediaWiki\Title\Title;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\LikeValue;

abstract class ApiQueryCategoryList extends ApiQueryGeneratorBase {

	protected function createQuery( IReadableDatabase $db, array $params ): void {
		$this->addTables( 'category' );
		$this->addFields( 'cat_title' );

		if ( $params['continue'] !== null ) {
			$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'string' ] );
			$op = $params['dir'] == 'descending' ? '<=' : '>=';
			$this->addWhere( $db->expr( 'cat_title', $op, $cont[0] ) );
		}

		$dir = ( $params['dir'] == 'descending' ? 'older' : 'newer' );
		$from = ( $params['from'] === null
			? null
			: $this->titlePartToKey( $params['from'], NS_CATEGORY ) );
		$to = ( $params['to'] === null
			? null
			: $this->titlePartToKey( $params['to'], NS_CATEGORY ) );
		$this->addWhereRange( 'cat_title', $dir, $from, $to );

		$min = $params['min'];
		$max = $params['max'];
		if ( $dir == 'newer' ) {
			$this->addWhereRange( 'cat_pages', 'newer', $min, $max );
		} else {
			$this->addWhereRange( 'cat_pages', 'older', $max, $min );
		}

		if ( isset( $params['prefix'] ) ) {
			$this->addWhere(
				$db->expr(
					'cat_title',
					IExpression::LIKE,
					new LikeValue( $this->titlePartToKey( $params['prefix'], NS_CATEGORY ), $db->anyString() )
				)
			);
		}

		$this->addOption( 'LIMIT', $params['limit'] + 1 );
		$sort = ( $params['dir'] == 'descending' ? ' DESC' : '' );
		$this->addOption( 'ORDER BY', 'cat_title' . $sort );

		$prop = array_fill_keys( $params['prop'], true );
		$this->addFieldsIf( [ 'cat_pages', 'cat_subcats', 'cat_files' ], isset( $prop['size'] ) );
		if ( isset( $prop['hidden'] ) ) {
			$this->addTables( [ 'page', 'page_props' ] );
			$this->addJoinConds( [
				'page' => [ 'LEFT JOIN', [
					'page_namespace' => NS_CATEGORY,
					'page_title=cat_title' ] ],
				'page_props' => [ 'LEFT JOIN', [
					'pp_page=page_id',
					'pp_propname' => 'hiddencat' ] ],
			] );
			$this->addFields( [ 'cat_hidden' => 'pp_propname' ] );
		}
	}

	protected function executeQuery( array $params, ?ApiPageSet $resultPageSet = null, array $options = [] ) {
		$res = $this->select( __METHOD__ );
		$pages = [];

		$result = $this->getResult();
		$count = 0;
		foreach ( $res as $row ) {
			if ( ++$count > $params['limit'] ) {
				// We've reached the one extra which shows that there are
				// additional cats to be had. Stop here...
				$this->setContinueEnumParameter( 'continue', $row->cat_title );
				break;
			}

			// Normalize titles
			$titleObj = Title::makeTitle( NS_CATEGORY, $row->cat_title );
			if ( $resultPageSet !== null ) {
				$pages[] = $titleObj;
			} else {
				$item = [];
				ApiResult::setContentValue( $item, 'category', $titleObj->getText() );
				$prop = array_fill_keys( $params['prop'], true );
				if ( isset( $prop['size'] ) ) {
					$item['size'] = (int)$row->cat_pages;
					$item['pages'] = $row->cat_pages - $row->cat_subcats - $row->cat_files;
					$item['files'] = (int)$row->cat_files;
					$item['subcats'] = (int)$row->cat_subcats;
				}
				if ( isset( $prop['hidden'] ) ) {
					$item['hidden'] = (bool)$row->cat_hidden;
				}
				if ( isset( $options['catNames'] ) ) {
					$catNameList = $options['catNames'];
					$item['catid'] = $catNameList[ $titleObj->getDBkey() ];
				}
				$fit = $result->addValue( [ 'query', $this->getModuleName() ], null, $item );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'continue', $row->cat_title );
					break;
				}
			}
		}

		if ( $resultPageSet === null ) {
			$result->addIndexedTagName( [ 'query', $this->getModuleName() ], 'c' );
		} else {
			$resultPageSet->populateFromTitles( $pages );
		}
	}
}
