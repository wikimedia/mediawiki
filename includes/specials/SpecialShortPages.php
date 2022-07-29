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
use MediaWiki\MainConfigNames;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
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
	 * @param ILoadBalancer $loadBalancer
	 * @param LinkBatchFactory $linkBatchFactory
	 */
	public function __construct(
		NamespaceInfo $namespaceInfo,
		ILoadBalancer $loadBalancer,
		LinkBatchFactory $linkBatchFactory
	) {
		parent::__construct( 'Shortpages' );
		$this->namespaceInfo = $namespaceInfo;
		$this->setDBLoadBalancer( $loadBalancer );
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

		if ( $this->sortDescending() ) {
			foreach ( $order as &$field ) {
				$field .= ' DESC';
			}
		}

		$tables = isset( $query['tables'] ) ? (array)$query['tables'] : [];
		$fields = isset( $query['fields'] ) ? (array)$query['fields'] : [];
		$conds = isset( $query['conds'] ) ? (array)$query['conds'] : [];
		$options = isset( $query['options'] ) ? (array)$query['options'] : [];
		$join_conds = isset( $query['join_conds'] ) ? (array)$query['join_conds'] : [];

		if ( $limit !== false ) {
			$options['LIMIT'] = intval( $limit );
		}

		if ( $offset !== false ) {
			$options['OFFSET'] = intval( $offset );
		}

		$namespaces = $conds['page_namespace'];
		if ( count( $namespaces ) === 1 ) {
			$options['ORDER BY'] = $order;
			$res = $dbr->select( $tables, $fields, $conds, $fname,
				$options, $join_conds
			);
		} else {
			unset( $conds['page_namespace'] );
			$options['INNER ORDER BY'] = $order;
			$options['ORDER BY'] = [ 'value' . ( $this->sortDescending() ? ' DESC' : '' ) ];
			$sql = $dbr->unionConditionPermutations(
				$tables,
				$fields,
				[ 'page_namespace' => $namespaces ],
				$conds,
				$fname,
				$options,
				$join_conds
			);
			$res = $dbr->query( $sql, $fname );
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
