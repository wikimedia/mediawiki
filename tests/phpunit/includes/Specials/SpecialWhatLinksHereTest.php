<?php

namespace MediaWiki\Tests\Specials;

use MediaWiki\Request\FauxRequest;

/**
 * @group Database
 * @covers \MediaWiki\Specials\SpecialWhatLinksHere
 */
class SpecialWhatLinksHereTest extends SpecialPageTestBase {

	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'WhatLinksHere' );
	}

	public function addDBDataOnce() {
		$this->insertPage( 'Target page', 'target page content' );
		$this->insertPage( 'Template:Template that links to Target page', '[[Target page]]' );
		$this->insertPage( 'Direct link to Target page', '[[Target page]]' );
		$this->insertPage(
			'Transcludes the template that links to Target page',
			'{{Template that links to Target page}}'
		);
		$this->insertPage( 'Transcludes Target page', '{{:Target page}}' );
		$this->insertPage( 'Redirect to Target page', '#REDIRECT [[Target page]]' );

		$this->insertPage( 'Filtered target page' );
		for ( $i = 1; $i <= 7; $i++ ) {
			$this->insertPage( "Filtered backlink $i", '[[Filtered target page]]' );
		}
		for ( $i = 1; $i <= 3; $i++ ) {
			$this->insertPage( "Visible backlink $i", '[[Filtered target page]]' );
		}
	}

	public function testShowsExpectedLinkTypes() {
		[ $html ] = $this->executeSpecialPage(
			'Target page',
			new FauxRequest( [
				'target' => 'Target page',
				'limit' => 50,
			] ),
			'en'
		);
		$text = html_entity_decode( preg_replace( '/\s+/', ' ', strip_tags( $html ) ) );

		$this->assertStringContainsString(
			'Transcludes Target page (transclusion)',
			$text
		);

		$this->assertStringContainsString(
			'Redirect to Target page (redirect page)',
			$text
		);
	}

	public function testBacklinkQueryHookFiltersBeforePagination(): void {
		$firstVisiblePageId = $this->getServiceContainer()
			->getTitleFactory()
			->newFromText( 'Visible backlink 1' )
			->getId();

		$this->setTemporaryHook(
			'SpecialWhatLinksHereBacklinkQuery',
			static function ( $data, $queryBuilder ) use ( $firstVisiblePageId ) {
				if ( $data->table === 'pagelinks' ) {
					$queryBuilder->andWhere(
						$data->db->expr( $data->sourcePageIdField, '>=', $firstVisiblePageId )
					);
				}
			}
		);

		[ $html ] = $this->executeSpecialPage(
			'Filtered target page',
			new FauxRequest( [
				'target' => 'Filtered target page',
				'limit' => 2,
			] ),
			'en'
		);
		$text = html_entity_decode( preg_replace( '/\s+/', ' ', strip_tags( $html ) ) );

		$this->assertStringContainsString( 'Visible backlink 1', $text );
		$this->assertStringContainsString( 'Visible backlink 2', $text );
		$this->assertStringNotContainsString( 'Visible backlink 3', $text );
		$this->assertStringNotContainsString( 'Filtered backlink', $text );
	}
}
