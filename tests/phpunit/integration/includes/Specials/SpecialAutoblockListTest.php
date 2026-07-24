<?php

namespace MediaWiki\Tests\Integration\Specials;

use MediaWiki\Tests\Specials\SpecialPageTestBase;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \MediaWiki\Specials\SpecialAutoblockList
 * @covers \MediaWiki\Specials\Pager\BlockListPager
 * @group Database
 */
class SpecialAutoblockListTest extends SpecialPageTestBase {

	public function testExecuteForNoAutoblocks(): void {
		$this->clearHook( 'OtherAutoblockLogLink' );

		[ $html ] = $this->executeSpecialPage();

		$this->verifyAutoblockListForm( $html );
		$this->assertStringContainsString( '(autoblocklist-empty)', $html );
		$this->assertStringNotContainsString( 'mw-autoblocklist-otherblocks', $html );
	}

	private function verifyAutoblockListForm( string $html ): void {
		$autoblockListForm = $this->assertSelectorMatchesOneElement( $html, '#mw-autoblock-list-form' );
		$this->assertStringContainsString( '(autoblocklist-legend)', $autoblockListForm );
		$this->assertStringContainsString( '(table_pager_limit_label)', $autoblockListForm );
		$this->assertStringContainsString( '(autoblocklist-submit)', $autoblockListForm );
	}

	public function testExecuteForOneAutoblock(): void {
		ConvertibleTimestamp::setFakeTime( '20260504030201' );

		$this->setTemporaryHook(
			'OtherAutoblockLogLink',
			static function ( &$otherAutoblockLink ) {
				$otherAutoblockLink[] = 'Test autoblock other link';
			}
		);

		$performer = $this->getTestSysop()->getUser();
		$parentBlock = $this->getServiceContainer()->getDatabaseBlockStore()
			->insertBlockWithParams( [
				'targetUser' => $this->getTestUser()->getUser(),
				'by' => $performer,
				'enableAutoblock' => true,
			] );

		$autoblockId = $this->getServiceContainer()->getDatabaseBlockStore()
			->doAutoblock( $parentBlock, '1.2.3.4' );
		$this->assertNotFalse( $autoblockId );

		[ $html ] = $this->executeSpecialPage();

		$this->verifyAutoblockListForm( $html );
		$this->assertStringContainsString( '(autoblocklist-localblocks: 1)', $html );

		$autoblockBlockListHtml = $this->assertSelectorMatchesOneElement(
			$html,
			'.mw-blocklist'
		);

		$expectedHeaders = [
			'(blocklist-timestamp)',
			'(blocklist-target)',
			'(blocklist-expiry)',
			'(blocklist-by)',
			'(blocklist-params)',
			'(blocklist-reason)',
		];
		foreach ( $expectedHeaders as $header ) {
			$this->assertStringContainsString( $header, $autoblockBlockListHtml );
		}

		$timestampCell = $this->assertSelectorMatchesOneElement( $html, '.TablePager_col_bl_timestamp' );
		$this->assertStringContainsString( '03:02, 4 (may_long) 2026', $timestampCell );

		$targetCell = $this->assertSelectorMatchesOneElement( $html, '.TablePager_col_target' );
		$this->assertStringContainsString( "(autoblockid: $autoblockId)", $targetCell );

		$expiryCell = $this->assertSelectorMatchesOneElement( $html, '.TablePager_col_bl_expiry' );
		$this->assertStringContainsString( '03:02, 5 (may_long) 2026', $expiryCell );
		$this->assertStringContainsString( '(duration-days: 1)', $expiryCell );

		$performerCell = $this->assertSelectorMatchesOneElement( $html, '.TablePager_col_bl_by' );
		$this->assertStringContainsString( $performer->getName(), $performerCell );

		$paramsCell = $this->assertSelectorMatchesOneElement( $html, '.TablePager_col_params' );
		$this->assertStringContainsString( '(blocklist-editing-sitewide)', $paramsCell );

		$reasonCell = $this->assertSelectorMatchesOneElement( $html, '.TablePager_col_bl_reason' );
		$this->assertStringContainsString( 'Autoblocked because your IP address', $reasonCell );

		$this->assertStringContainsString( '(autoblocklist-otherblocks: 1)', $html );
		$otherAutoblocksHtml = $this->assertSelectorMatchesOneElement( $html, '.mw-autoblocklist-otherblocks' );
		$this->assertStringContainsString( 'Test autoblock other link', $otherAutoblocksHtml );
	}

	/** @inheritDoc */
	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'AutoblockList' );
	}
}
