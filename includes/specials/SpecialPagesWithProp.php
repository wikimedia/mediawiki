<?php
/**
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

namespace MediaWiki\Specials;

use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Skin\Skin;
use MediaWiki\SpecialPage\QueryPage;
use MediaWiki\Title\Title;
use stdClass;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Special:PagesWithProp to search the page_props table
 *
 * @ingroup SpecialPage
 * @since 1.21
 */
class SpecialPagesWithProp extends QueryPage {

	/**
	 * @var string|null
	 */
	private $propName = null;

	/**
	 * @var string[]|null
	 */
	private $existingPropNames = null;

	/**
	 * @var int|null
	 */
	private $ns;

	/**
	 * @var bool
	 */
	private $reverse = false;

	/**
	 * @var bool
	 */
	private $sortByValue = false;

	public function __construct( IConnectionProvider $dbProvider ) {
		parent::__construct( 'PagesWithProp' );
		$this->setDatabaseProvider( $dbProvider );
	}

	/** @inheritDoc */
	public function isCacheable() {
		return false;
	}

	/** @inheritDoc */
	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$this->getOutput()->addModuleStyles( 'mediawiki.special' );

		$request = $this->getRequest();
		$propname = $request->getVal( 'propname', $par );
		$this->ns = $request->getIntOrNull( 'namespace' );
		$this->reverse = $request->getBool( 'reverse' );
		$this->sortByValue = $request->getBool( 'sortbyvalue' );

		$propnames = $this->getExistingPropNames();

		$fields = [
			'propname' => [
				'type' => 'combobox',
				'name' => 'propname',
				'options' => $propnames,
				'default' => $propname,
				'label-message' => 'pageswithprop-prop',
				'required' => true,
			],
			'namespace' => [
				'type' => 'namespaceselect',
				'name' => 'namespace',
				'label-message' => 'namespace',
				'all' => '',
				'default' => $this->ns,
			],
			'reverse' => [
				'type' => 'check',
				'name' => 'reverse',
				'default' => $this->reverse,
				'label-message' => 'pageswithprop-reverse',
				'required' => false,
			],
			'sortbyvalue' => [
				'type' => 'check',
				'name' => 'sortbyvalue',
				'default' => $this->sortByValue,
				'label-message' => 'pageswithprop-sortbyvalue',
				'required' => false,
			]
		];

		$form = HTMLForm::factory( 'ooui', $fields, $this->getContext() )
			->setMethod( 'get' )
			->setTitle( $this->getPageTitle() ) // Remove subpage
			->setSubmitCallback( $this->onSubmit( ... ) )
			->setWrapperLegendMsg( 'pageswithprop-legend' )
			->addHeaderHtml( $this->msg( 'pageswithprop-text' )->parseAsBlock() )
			->setSubmitTextMsg( 'pageswithprop-submit' )
			->prepareForm();
		$form->displayForm( false );
		if ( $propname !== '' && $propname !== null ) {
			$form->trySubmit();
		}
	}

	/**
	 * @param array $data
	 * @param HTMLForm $form
	 */
	private function onSubmit( $data, $form ) {
		$this->propName = $data['propname'];
		parent::execute( $data['propname'] );
	}

	/**
	 * Return an array of subpages beginning with $search that this special page will accept.
	 *
	 * @param string $search Prefix to search for
	 * @param int $limit Maximum number of results to return
	 * @param int $offset Number of pages to skip
	 * @return string[] Matching subpages
	 */
	public function prefixSearchSubpages( $search, $limit, $offset ) {
		$subpages = array_keys( $this->queryExistingProps( $limit, $offset ) );
		// We've already limited and offsetted, set to N and 0 respectively.
		return self::prefixSearchArray( $search, count( $subpages ), $subpages, 0 );
	}

	/**
	 * Disable RSS/Atom feeds
	 * @return bool
	 */
	public function isSyndicated() {
		return false;
	}

	/**
	 * @inheritDoc
	 */
	protected function linkParameters() {
		$params = [
			'reverse' => $this->reverse,
			'sortbyvalue' => $this->sortByValue,
		];
		if ( $this->ns !== null ) {
			$params['namespace'] = $this->ns;
		}
		return $params;
	}

	/** @inheritDoc */
	public function getQueryInfo() {
		$query = [
			'tables' => [ 'page_props', 'page' ],
			'fields' => [
				'page_id' => 'pp_page',
				'page_namespace',
				'page_title',
				'page_len',
				'page_is_redirect',
				'page_latest',
				'pp_value',
			],
			'conds' => [
				'pp_propname' => $this->propName,
			],
			'join_conds' => [
				'page' => [ 'JOIN', 'page_id = pp_page' ]
			],
			'options' => []
		];

		if ( $this->ns !== null ) {
			$query['conds']['page_namespace'] = $this->ns;
		}

		return $query;
	}

	/** @inheritDoc */
	protected function getOrderFields() {
		$sort = [ 'page_id' ];
		if ( $this->sortByValue ) {
			array_unshift( $sort, 'pp_sortkey' );
		}
		return $sort;
	}

	/**
	 * @return bool
	 */
	public function sortDescending() {
		return !$this->reverse;
	}

	/**
	 * @param Skin $skin
	 * @param stdClass $result Result row
	 * @return string
	 */
	public function formatResult( $skin, $result ) {
		$title = Title::newFromRow( $result );
		$ret = $this->getLinkRenderer()->makeKnownLink( $title );
		if ( $result->pp_value !== '' ) {
			// Do not show very long or binary values on the special page
			$valueLength = strlen( $result->pp_value );
			$isBinary = str_contains( $result->pp_value, "\0" );
			$isTooLong = $valueLength > 1024;

			if ( $isBinary || $isTooLong ) {
				$message = $this
					->msg( $isBinary ? 'pageswithprop-prophidden-binary' : 'pageswithprop-prophidden-long' )
					->sizeParams( $valueLength );

				$propValue = Html::element( 'span', [ 'class' => 'prop-value-hidden' ], $message->text() );
			} else {
				$propValue = Html::element( 'span', [ 'class' => 'prop-value' ], $result->pp_value );
			}

			$ret .= $this->msg( 'colon-separator' )->escaped() . $propValue;
		}

		return $ret;
	}

	public function getExistingPropNames(): array {
		if ( $this->existingPropNames === null ) {
			$this->existingPropNames = $this->queryExistingProps();
		}
		return $this->existingPropNames;
	}

	protected function queryExistingProps( ?int $limit = null, int $offset = 0 ): array {
		$queryBuilder = $this->getDatabaseProvider()
			->getReplicaDatabase()
			->newSelectQueryBuilder()
			->select( 'pp_propname' )
			->distinct()
			->from( 'page_props' )
			->orderBy( 'pp_propname' );

		if ( $limit ) {
			$queryBuilder->limit( $limit );
		}
		if ( $offset ) {
			$queryBuilder->offset( $offset );
		}
		$res = $queryBuilder->caller( __METHOD__ )->fetchResultSet();

		$propnames = [];
		foreach ( $res as $row ) {
			$propnames[$row->pp_propname] = $row->pp_propname;
		}

		return $propnames;
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'pages';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialPagesWithProp::class, 'SpecialPagesWithProp' );
