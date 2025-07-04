<?php
/**
 * API for MediaWiki 1.17+
 *
 * Copyright © 2010 Sam Reed
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

namespace MediaWiki\Api;

use MediaWiki\Title\Title;
use MediaWiki\Utils\UrlUtils;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * A query module to list all interwiki links on a page
 *
 * @ingroup API
 */
class ApiQueryIWLinks extends ApiQueryBase {

	private UrlUtils $urlUtils;

	public function __construct( ApiQuery $query, string $moduleName, UrlUtils $urlUtils ) {
		parent::__construct( $query, $moduleName, 'iw' );

		$this->urlUtils = $urlUtils;
	}

	public function execute() {
		$pages = $this->getPageSet()->getGoodPages();
		if ( $pages === [] ) {
			return;
		}

		$params = $this->extractRequestParams();
		$prop = array_fill_keys( (array)$params['prop'], true );

		if ( isset( $params['title'] ) && !isset( $params['prefix'] ) ) {
			$this->dieWithError(
				[
					'apierror-invalidparammix-mustusewith',
					$this->encodeParamName( 'title' ),
					$this->encodeParamName( 'prefix' ),
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
			'iwl_from',
			'iwl_prefix',
			'iwl_title'
		] );

		$this->addTables( 'iwlinks' );
		$this->addWhereFld( 'iwl_from', array_keys( $pages ) );

		if ( $params['continue'] !== null ) {
			$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'int', 'string', 'string' ] );
			$op = $params['dir'] == 'descending' ? '<=' : '>=';
			$db = $this->getDB();
			$this->addWhere( $db->buildComparison( $op, [
				'iwl_from' => $cont[0],
				'iwl_prefix' => $cont[1],
				'iwl_title' => $cont[2],
			] ) );
		}

		$sort = ( $params['dir'] == 'descending' ? ' DESC' : '' );
		if ( isset( $params['prefix'] ) ) {
			$this->addWhereFld( 'iwl_prefix', $params['prefix'] );
			if ( isset( $params['title'] ) ) {
				$this->addWhereFld( 'iwl_title', $params['title'] );
				$this->addOption( 'ORDER BY', 'iwl_from' . $sort );
			} else {
				$this->addOption( 'ORDER BY', [
					'iwl_from' . $sort,
					'iwl_title' . $sort
				] );
			}
		} else {
			// Don't order by iwl_from if it's constant in the WHERE clause
			if ( count( $pages ) === 1 ) {
				$this->addOption( 'ORDER BY', 'iwl_prefix' . $sort );
			} else {
				$this->addOption( 'ORDER BY', [
					'iwl_from' . $sort,
					'iwl_prefix' . $sort,
					'iwl_title' . $sort
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
				$this->setContinueEnumParameter(
					'continue',
					"{$row->iwl_from}|{$row->iwl_prefix}|{$row->iwl_title}"
				);
				break;
			}
			$entry = [ 'prefix' => $row->iwl_prefix ];

			if ( isset( $prop['url'] ) ) {
				$title = Title::newFromText( "{$row->iwl_prefix}:{$row->iwl_title}" );
				if ( $title ) {
					$entry['url'] = (string)$this->urlUtils->expand( $title->getFullURL(), PROTO_CURRENT );
				}
			}

			ApiResult::setContentValue( $entry, 'title', $row->iwl_title );
			$fit = $this->addPageSubItem( $row->iwl_from, $entry );
			if ( !$fit ) {
				$this->setContinueEnumParameter(
					'continue',
					"{$row->iwl_from}|{$row->iwl_prefix}|{$row->iwl_title}"
				);
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
			'prop' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => [
					'url',
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'prefix' => null,
			'title' => null,
			'dir' => [
				ParamValidator::PARAM_DEFAULT => 'ascending',
				ParamValidator::PARAM_TYPE => [
					'ascending',
					'descending'
				]
			],
			'limit' => [
				ParamValidator::PARAM_DEFAULT => 10,
				ParamValidator::PARAM_TYPE => 'limit',
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => ApiBase::LIMIT_BIG1,
				IntegerDef::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'url' => [
				ParamValidator::PARAM_DEFAULT => false,
				ParamValidator::PARAM_DEPRECATED => true,
			],
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		$title = Title::newMainPage()->getPrefixedText();
		$mp = rawurlencode( $title );

		return [
			"action=query&prop=iwlinks&titles={$mp}"
				=> 'apihelp-query+iwlinks-example-simple',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Iwlinks';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryIWLinks::class, 'ApiQueryIWLinks' );
