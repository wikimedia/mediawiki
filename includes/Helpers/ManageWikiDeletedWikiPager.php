<?php

namespace Miraheze\ManageWiki\Helpers;

use Linker;
use MediaWiki\MediaWikiServices;
use SpecialPage;
use TablePager;

class ManageWikiDeletedWikiPager extends TablePager {
	public function __construct( $page ) {
		$config = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'managewiki' );
		$this->mDb = MediaWikiServices::getInstance()->getDBLoadBalancerFactory()
			->getMainLB( $config->get( 'CreateWikiDatabase' ) )
			->getMaintenanceConnectionRef( DB_REPLICA, [], $config->get( 'CreateWikiDatabase' ) );

		parent::__construct( $page->getContext(), $page->getLinkRenderer() );
	}

	public function getFieldNames() {
		static $headers = null;

		$headers = [
			'wiki_dbname' => 'managewiki-label-dbname',
			'wiki_creation' => 'managewiki-label-creationdate',
			'wiki_deleted_timestamp' => 'managewiki-label-deletiondate',
			'wiki_deleted' => 'managewiki-label-undeletewiki'
		];

		foreach ( $headers as &$msg ) {
			$msg = $this->msg( $msg )->text();
		}

		return $headers;
	}

	public function formatValue( $name, $value ) {
		$row = $this->mCurrentRow;

		switch ( $name ) {
			case 'wiki_dbname':
				$formatted = $row->wiki_dbname;
				break;
			case 'wiki_creation':
				$formatted = wfTimestamp( TS_RFC2822, (int)$row->wiki_creation );
				break;
			case 'wiki_deleted_timestamp':
				$formatted = wfTimestamp( TS_RFC2822, (int)$row->wiki_deleted_timestamp );
				break;
			case 'wiki_deleted':
				$formatted = Linker::makeExternalLink( SpecialPage::getTitleFor( 'ManageWiki' )->getFullURL() . '/core/' . $row->wiki_dbname, $this->msg( 'managewiki-label-goto' )->text() );
				break;
			default:
				$formatted = "Unable to format $name";
				break;
		}
		return $formatted;
	}

	public function getQueryInfo() {
		return [
			'tables' => [
				'cw_wikis'
			],
			'fields' => [
				'wiki_dbname',
				'wiki_creation',
				'wiki_deleted',
				'wiki_deleted_timestamp'
			],
			'conds' => [
				'wiki_deleted' => 1
			],
			'joins_conds' => [],
		];
	}

	public function getDefaultSort() {
		return 'wiki_dbname';
	}

	public function isFieldSortable( $name ) {
		return true;
	}
}
