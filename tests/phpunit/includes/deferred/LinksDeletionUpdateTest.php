<?php

use MediaWiki\Deferred\LinksUpdate\LinksDeletionUpdate;
use MediaWiki\Deferred\LinksUpdate\LinksUpdate;

/**
 * @covers \MediaWiki\Deferred\LinksUpdate\LinksDeletionUpdate
 * @covers \MediaWiki\Deferred\LinksUpdate\LinksUpdate
 * @covers \MediaWiki\Deferred\LinksUpdate\CategoryLinksTable
 * @covers \MediaWiki\Deferred\LinksUpdate\ExternalLinksTable
 * @covers \MediaWiki\Deferred\LinksUpdate\GenericPageLinksTable
 * @covers \MediaWiki\Deferred\LinksUpdate\ImageLinksTable
 * @covers \MediaWiki\Deferred\LinksUpdate\InterwikiLinksTable
 * @covers \MediaWiki\Deferred\LinksUpdate\LangLinksTable
 * @covers \MediaWiki\Deferred\LinksUpdate\LinksTable
 * @covers \MediaWiki\Deferred\LinksUpdate\LinksTableGroup
 * @covers \MediaWiki\Deferred\LinksUpdate\PageLinksTable
 * @covers \MediaWiki\Deferred\LinksUpdate\PagePropsTable
 * @covers \MediaWiki\Deferred\LinksUpdate\TemplateLinksTable
 * @covers \MediaWiki\Deferred\LinksUpdate\TitleLinksTable
 *
 * @group LinksUpdate
 * @group Database
 * ^--- make sure temporary tables are used.
 */
class LinksDeletionUpdateTest extends MediaWikiLangTestCase {
	public function testUpdate() {
		$this->tablesUsed = array_merge( $this->tablesUsed,
			[
				'interwiki',
				'page_props',
				'pagelinks',
				'categorylinks',
				'langlinks',
				'externallinks',
				'imagelinks',
				'templatelinks',
				'iwlinks',
				'recentchanges',
			]
		);

		$res = $this->insertPage( 'Source' );
		$id = $res['id'];
		$title = $res['title'];
		$wikiPage = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );

		$po = new ParserOutput();
		$po->addCategory( 'Cat', 'cat' );
		$po->addExternalLink( 'https://en.wikipedia.org/' );
		$po->addImage( 'Wiki.png' );
		$po->addLink( new TitleValue( 0, 'foo', '', 'iwprefix' ) );
		$po->addLanguageLink( 'fr:Francais' );
		$po->addLink( new TitleValue( 0, 'Target' ) );
		$po->setPageProperty( 'int', 1 );
		$po->addTemplate( new TitleValue( NS_TEMPLATE, '!' ), 1, 1 );

		$linksUpdate = new LinksUpdate( $title, $po, false );
		$linksUpdate->setTransactionTicket(
			$this->getServiceContainer()->getDBLoadBalancerFactory()->getEmptyTransactionTicket( __METHOD__ )
		);
		$linksUpdate->doUpdate();

		$tables = [
			'categorylinks' => 'cl_from',
			'externallinks' => 'el_from',
			'imagelinks' => 'il_from',
			'iwlinks' => 'iwl_from',
			'langlinks' => 'll_from',
			'pagelinks' => 'pl_from',
			'page_props' => 'pp_page',
			'templatelinks' => 'tl_from',
		];
		foreach ( $tables as $table => $fromField ) {
			$res = $this->db->select( $table, [ 1 ], [ $fromField => $id ], __METHOD__ );
			$this->assertSame( 1, $res->numRows(), "Number of rows in table $table" );
		}

		$linksDeletionUpdate = new LinksDeletionUpdate( $wikiPage, $id );
		$linksDeletionUpdate->setTransactionTicket(
			$this->getServiceContainer()->getDBLoadBalancerFactory()->getEmptyTransactionTicket( __METHOD__ )
		);
		$linksDeletionUpdate->doUpdate();

		foreach ( $tables as $table => $fromField ) {
			$res = $this->db->select( $table, [ 1 ], [ $fromField => $id ], __METHOD__ );
			$this->assertSame( 0, $res->numRows(), "Number of rows in table $table" );
		}
	}
}
