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

/**
 * A query module to list all wiki links on a given set of pages.
 *
 * @ingroup API
 */
class ApiQueryLinks extends ApiQueryGeneratorBase {

	private const LINKS = 'links';
	private const TEMPLATES = 'templates';

	private $table, $prefix, $titlesParam, $helpUrl;

	public function __construct( ApiQuery $query, $moduleName ) {
		switch ( $moduleName ) {
			case self::LINKS:
				$this->table = 'pagelinks';
				$this->prefix = 'pl';
				$this->titlesParam = 'titles';
				$this->helpUrl = 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Links';
				break;
			case self::TEMPLATES:
				$this->table = 'templatelinks';
				$this->prefix = 'tl';
				$this->titlesParam = 'templates';
				$this->helpUrl = 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Templates';
				break;
			default:
				ApiBase::dieDebug( __METHOD__, 'Unknown module name' );
		}

		parent::__construct( $query, $moduleName, $this->prefix );
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
	 * @param ApiPageSet|null $resultPageSet
	 */
	private function run( $resultPageSet = null ) {
		if ( $this->getPageSet()->getGoodTitleCount() == 0 ) {
			return; // nothing to do
		}

		$params = $this->extractRequestParams();

		$this->addFields( [
			'pl_from' => $this->prefix . '_from',
			'pl_namespace' => $this->prefix . '_namespace',
			'pl_title' => $this->prefix . '_title'
		] );

		$this->addTables( $this->table );
		$this->addWhereFld( $this->prefix . '_from', array_keys( $this->getPageSet()->getGoodTitles() ) );

		$multiNS = true;
		$multiTitle = true;
		if ( $params[$this->titlesParam] ) {
			// Filter the titles in PHP so our ORDER BY bug avoidance below works right.
			$filterNS = $params['namespace'] ? array_flip( $params['namespace'] ) : false;

			$lb = new LinkBatch;
			foreach ( $params[$this->titlesParam] as $t ) {
				$title = Title::newFromText( $t );
				if ( !$title ) {
					$this->addWarning( [ 'apiwarn-invalidtitle', wfEscapeWikiText( $t ) ] );
				} elseif ( !$filterNS || isset( $filterNS[$title->getNamespace()] ) ) {
					$lb->addObj( $title );
				}
			}
			$cond = $lb->constructSet( $this->prefix, $this->getDB() );
			if ( $cond ) {
				$this->addWhere( $cond );
				$multiNS = count( $lb->data ) !== 1;
				$multiTitle = count( array_merge( ...$lb->data ) ) !== 1;
			} else {
				// No titles so no results
				return;
			}
		} elseif ( $params['namespace'] ) {
			$this->addWhereFld( $this->prefix . '_namespace', $params['namespace'] );
			$multiNS = $params['namespace'] === null || count( $params['namespace'] ) !== 1;
		}

		if ( $params['continue'] !== null ) {
			$cont = explode( '|', $params['continue'] );
			$this->dieContinueUsageIf( count( $cont ) != 3 );
			$op = $params['dir'] == 'descending' ? '<' : '>';
			$plfrom = (int)$cont[0];
			$plns = (int)$cont[1];
			$pltitle = $this->getDB()->addQuotes( $cont[2] );
			$this->addWhere(
				"{$this->prefix}_from $op $plfrom OR " .
				"({$this->prefix}_from = $plfrom AND " .
				"({$this->prefix}_namespace $op $plns OR " .
				"({$this->prefix}_namespace = $plns AND " .
				"{$this->prefix}_title $op= $pltitle)))"
			);
		}

		$sort = ( $params['dir'] == 'descending' ? ' DESC' : '' );
		// Here's some MySQL craziness going on: if you use WHERE foo='bar'
		// and later ORDER BY foo MySQL doesn't notice the ORDER BY is pointless
		// but instead goes and filesorts, because the index for foo was used
		// already. To work around this, we drop constant fields in the WHERE
		// clause from the ORDER BY clause
		$order = [];
		if ( count( $this->getPageSet()->getGoodTitles() ) != 1 ) {
			$order[] = $this->prefix . '_from' . $sort;
		}
		if ( $multiNS ) {
			$order[] = $this->prefix . '_namespace' . $sort;
		}
		if ( $multiTitle ) {
			$order[] = $this->prefix . '_title' . $sort;
		}
		if ( $order ) {
			$this->addOption( 'ORDER BY', $order );
		}
		$this->addOption( 'LIMIT', $params['limit'] + 1 );

		$res = $this->select( __METHOD__ );

		if ( $resultPageSet === null ) {
			$this->executeGenderCacheFromResultWrapper( $res, __METHOD__, 'pl' );

			$count = 0;
			foreach ( $res as $row ) {
				if ( ++$count > $params['limit'] ) {
					// We've reached the one extra which shows that
					// there are additional pages to be had. Stop here...
					$this->setContinueEnumParameter( 'continue',
						"{$row->pl_from}|{$row->pl_namespace}|{$row->pl_title}" );
					break;
				}
				$vals = [];
				ApiQueryBase::addTitleInfo( $vals, Title::makeTitle( $row->pl_namespace, $row->pl_title ) );
				$fit = $this->addPageSubItem( $row->pl_from, $vals );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'continue',
						"{$row->pl_from}|{$row->pl_namespace}|{$row->pl_title}" );
					break;
				}
			}
		} else {
			$titles = [];
			$count = 0;
			foreach ( $res as $row ) {
				if ( ++$count > $params['limit'] ) {
					// We've reached the one extra which shows that
					// there are additional pages to be had. Stop here...
					$this->setContinueEnumParameter( 'continue',
						"{$row->pl_from}|{$row->pl_namespace}|{$row->pl_title}" );
					break;
				}
				$titles[] = Title::makeTitle( $row->pl_namespace, $row->pl_title );
			}
			$resultPageSet->populateFromTitles( $titles );
		}
	}

	public function getAllowedParams() {
		return [
			'namespace' => [
				ApiBase::PARAM_TYPE => 'namespace',
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_EXTRA_NAMESPACES => [ NS_MEDIA, NS_SPECIAL ],
			],
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
			$this->titlesParam => [
				ApiBase::PARAM_ISMULTI => true,
			],
			'dir' => [
				ApiBase::PARAM_DFLT => 'ascending',
				ApiBase::PARAM_TYPE => [
					'ascending',
					'descending'
				]
			],
		];
	}

	protected function getExamplesMessages() {
		$name = $this->getModuleName();
		$path = $this->getModulePath();

		return [
			"action=query&prop={$name}&titles=Main%20Page"
				=> "apihelp-{$path}-example-simple",
			"action=query&generator={$name}&titles=Main%20Page&prop=info"
				=> "apihelp-{$path}-example-generator",
			"action=query&prop={$name}&titles=Main%20Page&{$this->prefix}namespace=2|10"
				=> "apihelp-{$path}-example-namespaces",
		];
	}

	public function getHelpUrls() {
		return $this->helpUrl;
	}
}
