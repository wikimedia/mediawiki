<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Deferred\LinksUpdate\CategoryLinksTable;
use MediaWiki\SpecialPage\ImageQueryPage;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * List of file pages which haven't been categorised
 *
 * @todo FIXME: Use an instance of UncategorizedPagesPage or something
 *
 * @ingroup SpecialPage
 * @author Rob Church <robchur@gmail.com>
 */
class SpecialUncategorizedImages extends ImageQueryPage {

	public function __construct( IConnectionProvider $dbProvider ) {
		parent::__construct( 'Uncategorizedimages' );
		$this->setDatabaseProvider( $dbProvider );
	}

	/** @inheritDoc */
	protected function sortDescending() {
		return false;
	}

	/** @inheritDoc */
	public function isExpensive() {
		return true;
	}

	/** @inheritDoc */
	public function isSyndicated() {
		return false;
	}

	/** @inheritDoc */
	protected function getOrderFields() {
		return [ 'title' ];
	}

	/** @inheritDoc */
	public function execute( $par ) {
		$this->addHelpLink( 'Help:Categories' );
		parent::execute( $par );
	}

	/** @inheritDoc */
	public function getQueryInfo() {
		return [
			'tables' => [ 'page', 'categorylinks' ],
			'fields' => [
				'namespace' => 'page_namespace',
				'title' => 'page_title',
			],
			'conds' => [
				'cl_from' => null,
				'page_namespace' => NS_FILE,
				'page_is_redirect' => 0,
			],
			'join_conds' => [
				'categorylinks' => [
					'LEFT JOIN',
					'cl_from=page_id',
				],
			],
		];
	}

	/** @inheritDoc */
	protected function getRecacheDB() {
		return $this->getDatabaseProvider()->getReplicaDatabase(
			CategoryLinksTable::VIRTUAL_DOMAIN,
			'vslow'
		);
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'maintenance';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialUncategorizedImages::class, 'SpecialUncategorizedImages' );
