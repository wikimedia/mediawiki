<?php
/**
 * Implements Special:Shortpages
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
 * @ingroup SpecialPage
 */

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Html\Html;
use MediaWiki\Linker\Linker;
use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * SpecialShortpages extends QueryPage. It is used to return the shortest
 * pages in the database.
 *
 * @ingroup SpecialPage
 */
class SpecialShortPages extends QueryPage {

	/** @var NamespaceInfo */
	private $namespaceInfo;

	/**
	 * @param NamespaceInfo $namespaceInfo
	 * @param IConnectionProvider $dbProvider
	 * @param LinkBatchFactory $linkBatchFactory
	 */
	public function __construct(
		NamespaceInfo $namespaceInfo,
		IConnectionProvider $dbProvider,
		LinkBatchFactory $linkBatchFactory
	) {
		parent::__construct( 'Shortpages' );
		$this->namespaceInfo = $namespaceInfo;
		$this->setDatabaseProvider( $dbProvider );
		$this->setLinkBatchFactory( $linkBatchFactory );
	}

	public function isSyndicated() {
		return false;
	}

	public function getQueryInfo() {
		$config = $this->getConfig();
		$tables = [ 'page' ];
		$conds = [
			'page_namespace' => array_diff(
				$this->namespaceInfo->getContentNamespaces(),
				$config->get( MainConfigNames::ShortPagesNamespaceExclusions )
			),
			'page_is_redirect' => 0
		];
		$joinConds = [];
		$options = [ 'USE INDEX' => [ 'page' => 'page_redirect_namespace_len' ] ];

		// Allow extensions to modify the query
		$this->getHookRunner()->onShortPagesQuery( $tables, $conds, $joinConds, $options );

		return [
			'tables' => $tables,
			'fields' => [
				'namespace' => 'page_namespace',
				'title' => 'page_title',
				'value' => 'page_len'
			],
			'conds' => $conds,
			'join_conds' => $joinConds,
			'options' => $options
		];
	}

	public function reallyDoQuery( $limit, $offset = false ) {
		$fname = static::class . '::reallyDoQuery';
		$dbr = $this->getRecacheDB();
		$query = $this->getQueryInfo();
		$order = $this->getOrderFields();
		$sqb = $dbr->newSelectQueryBuilder();

		if ( $this->sortDescending() ) {
			foreach ( $order as &$field ) {
				$field .= ' DESC';
			}
		}

		$conds = isset( $query['conds'] ) ? (array)$query['conds'] : [];
		$namespaces = $conds['page_namespace'];
		unset( $conds['page_namespace'] );

		$sqb
			->select( isset( $query['fields'] ) ? (array)$query['fields'] : [] )
			->tables( isset( $query['tables'] ) ? (array)$query['tables'] : [] )
			->where( $conds )
			->options( isset( $query['options'] ) ? (array)$query['options'] : [] )
			->joinConds( isset( $query['join_conds'] ) ? (array)$query['join_conds'] : [] );
		if ( $limit !== false ) {
			$sqb->limit( intval( $limit ) );
		}
		if ( $offset !== false ) {
			$sqb->offset( intval( $offset ) );
		}

		if ( count( $namespaces ) === 1 || !$dbr->unionSupportsOrderAndLimit() ) {
			$sqb->andWhere( [ 'page_namespace' => $namespaces ] );
			$sqb->orderBy( $order );
			$res = $sqb->caller( $fname )->fetchResultSet();
		} else {
			$uqb = $dbr->newUnionQueryBuilder();
			foreach ( $namespaces as $namespace ) {
				$nsSqb = clone $sqb;
				$nsSqb->orderBy( $order );
				$nsSqb->andWhere( [ 'page_namespace' => $namespace ] );
				$uqb->add( $nsSqb );
			}
			$res = $uqb->caller( $fname )->fetchResultSet();
		}

		return $res;
	}

	protected function getOrderFields() {
		return [ 'page_len' ];
	}

	/**
	 * @param IDatabase $db
	 * @param IResultWrapper $res
	 */
	public function preprocessResults( $db, $res ) {
		$this->executeLBFromResultWrapper( $res );
	}

	protected function sortDescending() {
		return false;
	}

	/**
	 * @param Skin $skin
	 * @param stdClass $result Result row
	 * @return string
	 */
	public function formatResult( $skin, $result ) {
		$dm = $this->getLanguage()->getDirMark();

		$title = Title::makeTitleSafe( $result->namespace, $result->title );
		if ( !$title ) {
			return Html::element( 'span', [ 'class' => 'mw-invalidtitle' ],
				Linker::getInvalidTitleDescription( $this->getContext(), $result->namespace, $result->title ) );
		}

		$linkRenderer = $this->getLinkRenderer();
		$hlink = $linkRenderer->makeKnownLink(
			$title,
			$this->msg( 'hist' )->text(),
			[],
			[ 'action' => 'history' ]
		);
		$hlinkInParentheses = $this->msg( 'parentheses' )->rawParams( $hlink )->escaped();

		if ( $this->isCached() ) {
			$plink = $linkRenderer->makeLink( $title );
			$exists = $title->exists();
		} else {
			$plink = $linkRenderer->makeKnownLink( $title );
			$exists = true;
		}

		$size = $this->msg( 'nbytes' )->numParams( $result->value )->escaped();

		return $exists
			? "{$hlinkInParentheses} {$dm}{$plink} {$dm}[{$size}]"
			: "<del>{$hlinkInParentheses} {$dm}{$plink} {$dm}[{$size}]</del>";
	}

	protected function getGroupName() {
		return 'maintenance';
	}
}
