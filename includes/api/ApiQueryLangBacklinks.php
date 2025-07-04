<?php
/**
 * API for MediaWiki 1.17+
 *
 * Copyright © 2011 Sam Reed
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
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * This gives links pointing to the given interwiki
 * @ingroup API
 */
class ApiQueryLangBacklinks extends ApiQueryGeneratorBase {

	public function __construct( ApiQuery $query, string $moduleName ) {
		parent::__construct( $query, $moduleName, 'lbl' );
	}

	public function execute() {
		$this->run();
	}

	/** @inheritDoc */
	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	/**
	 * @param ApiPageSet|null $resultPageSet
	 * @return void
	 */
	public function run( $resultPageSet = null ) {
		$params = $this->extractRequestParams();

		if ( isset( $params['title'] ) && !isset( $params['lang'] ) ) {
			$this->dieWithError(
				[
					'apierror-invalidparammix-mustusewith',
					$this->encodeParamName( 'title' ),
					$this->encodeParamName( 'lang' )
				],
				'nolang'
			);
		}

		if ( $params['continue'] !== null ) {
			$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'string', 'string', 'int' ] );
			$db = $this->getDB();
			$op = $params['dir'] == 'descending' ? '<=' : '>=';
			$this->addWhere( $db->buildComparison( $op, [
				'll_lang' => $cont[0],
				'll_title' => $cont[1],
				'll_from' => $cont[2],
			] ) );
		}

		$prop = array_fill_keys( $params['prop'], true );
		$lllang = isset( $prop['lllang'] );
		$lltitle = isset( $prop['lltitle'] );

		$this->addTables( [ 'langlinks', 'page' ] );
		$this->addWhere( 'll_from = page_id' );

		$this->addFields( [ 'page_id', 'page_title', 'page_namespace', 'page_is_redirect',
			'll_from', 'll_lang', 'll_title' ] );

		$sort = ( $params['dir'] == 'descending' ? ' DESC' : '' );
		if ( isset( $params['lang'] ) ) {
			$this->addWhereFld( 'll_lang', $params['lang'] );
			if ( isset( $params['title'] ) ) {
				$this->addWhereFld( 'll_title', $params['title'] );
				$this->addOption( 'ORDER BY', 'll_from' . $sort );
			} else {
				$this->addOption( 'ORDER BY', [
					'll_title' . $sort,
					'll_from' . $sort
				] );
			}
		} else {
			$this->addOption( 'ORDER BY', [
				'll_lang' . $sort,
				'll_title' . $sort,
				'll_from' . $sort
			] );
		}

		$this->addOption( 'LIMIT', $params['limit'] + 1 );

		$res = $this->select( __METHOD__ );

		$pages = [];

		$count = 0;
		$result = $this->getResult();

		if ( $resultPageSet === null ) {
			$this->executeGenderCacheFromResultWrapper( $res, __METHOD__ );
		}

		foreach ( $res as $row ) {
			if ( ++$count > $params['limit'] ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here... Continue string
				// preserved in case the redirect query doesn't pass the limit.
				$this->setContinueEnumParameter(
					'continue',
					"{$row->ll_lang}|{$row->ll_title}|{$row->ll_from}"
				);
				break;
			}

			if ( $resultPageSet !== null ) {
				$pages[] = Title::newFromRow( $row );
			} else {
				$entry = [ 'pageid' => (int)$row->page_id ];

				$title = Title::makeTitle( $row->page_namespace, $row->page_title );
				ApiQueryBase::addTitleInfo( $entry, $title );

				if ( $row->page_is_redirect ) {
					$entry['redirect'] = true;
				}

				if ( $lllang ) {
					$entry['lllang'] = $row->ll_lang;
				}

				if ( $lltitle ) {
					$entry['lltitle'] = $row->ll_title;
				}

				$fit = $result->addValue( [ 'query', $this->getModuleName() ], null, $entry );
				if ( !$fit ) {
					$this->setContinueEnumParameter(
						'continue',
						"{$row->ll_lang}|{$row->ll_title}|{$row->ll_from}"
					);
					break;
				}
			}
		}

		if ( $resultPageSet === null ) {
			$result->addIndexedTagName( [ 'query', $this->getModuleName() ], 'll' );
		} else {
			$resultPageSet->populateFromTitles( $pages );
		}
	}

	/** @inheritDoc */
	public function getCacheMode( $params ) {
		return 'public';
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'lang' => null,
			'title' => null,
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'limit' => [
				ParamValidator::PARAM_DEFAULT => 10,
				ParamValidator::PARAM_TYPE => 'limit',
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => ApiBase::LIMIT_BIG1,
				IntegerDef::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'prop' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_DEFAULT => '',
				ParamValidator::PARAM_TYPE => [
					'lllang',
					'lltitle',
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'dir' => [
				ParamValidator::PARAM_DEFAULT => 'ascending',
				ParamValidator::PARAM_TYPE => [
					'ascending',
					'descending'
				]
			],
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=query&list=langbacklinks&lbltitle=Test&lbllang=fr'
				=> 'apihelp-query+langbacklinks-example-simple',
			'action=query&generator=langbacklinks&glbltitle=Test&glbllang=fr&prop=info'
				=> 'apihelp-query+langbacklinks-example-generator',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Langbacklinks';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryLangBacklinks::class, 'ApiQueryLangBacklinks' );
