<?php

namespace MediaWiki\Tests\Integration\Specials;

use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\Specials\SpecialPageTestBase;

/**
 * @covers \MediaWiki\Specials\SpecialComparePages
 * @group Database
 */
class SpecialComparePagesTest extends SpecialPageTestBase {

	public function testExecuteForView(): void {
		[ $html ] = $this->executeSpecialPage();

		$this->verifyComparePagesForm( $html );
	}

	private function verifyComparePagesForm( string $html ): void {
		$formHtml = $this->assertSelectorMatchesOneElement( $html, '.mw-htmlform' );

		$this->assertStringContainsString( '(compare-page1)', $formHtml );
		$this->assertStringContainsString( '(compare-rev1)', $formHtml );
		$this->assertStringContainsString( '(compare-page2)', $formHtml );
		$this->assertStringContainsString( '(compare-rev2)', $formHtml );
		$this->assertStringContainsString( '(compare-submit)', $formHtml );
	}

	public function testExecuteForComparingOfSamePage(): void {
		$testPage = $this->getExistingTestPage()->getTitle();

		[ $html ] = $this->executeSpecialPage( '', new FauxRequest( [
			'page1' => $testPage->getPrefixedText(),
			'page2' => $testPage->getPrefixedText(),
		] ) );

		$this->verifyComparePagesForm( $html );

		$emptyDiffHtml = $this->assertSelectorMatchesOneElement( $html, '.mw-diff-empty' );
		$this->assertStringContainsString( 'diff-empty', $emptyDiffHtml );
	}

	public function testExecuteForComparingDifferentPages(): void {
		$firstEditStatus = $this->editPage( 'First page', 'Test edit 1' );
		$this->assertStatusGood( $firstEditStatus );
		$secondEditStatus = $this->editPage( 'Second page', 'Test edit 2' );
		$this->assertStatusGood( $secondEditStatus );
		$thirdEditStatus = $this->editPage( 'Second page', 'Test edit 3' );
		$this->assertStatusGood( $thirdEditStatus );

		[ $html ] = $this->executeSpecialPage( '', new FauxRequest( [
			'page1' => 'First page',
			'page2' => 'Second page',
			'rev2' => $secondEditStatus->getNewRevision()->getId(),
		] ) );

		$this->verifyComparePagesForm( $html );

		$diffHtml = $this->assertSelectorMatchesOneElement( $html, '.diff-type-table.diff' );

		// Check that the diff shown uses the latest revision from the first page and the specified revision
		// from the second page
		$leftSideDiffHtml = $this->assertSelectorMatchesOneElement( $diffHtml, '.diff-side-deleted.diff-otitle' );
		$this->assertStringContainsString(
			'?title=First_page&amp;oldid=1',
			$leftSideDiffHtml,
			'Wrong revision ID used for the left side in the diff'
		);

		$rightSideDiffHtml = $this->assertSelectorMatchesOneElement( $diffHtml, '.diff-side-added.diff-ntitle' );
		$this->assertStringContainsString(
			'?title=Second_page&amp;oldid=2',
			$rightSideDiffHtml,
			'Wrong revision ID used for the right side in the diff'
		);
	}

	public function testExecuteWhenRevisionIdDoesNotExist(): void {
		$firstEditStatus = $this->editPage( 'First page', 'Test edit 1' );
		$this->assertStatusGood( $firstEditStatus );
		$secondEditStatus = $this->editPage( 'Second page', 'Test edit 2' );
		$this->assertStatusGood( $secondEditStatus );

		[ $html ] = $this->executeSpecialPage( '', new FauxRequest( [
			'page1' => 'First page',
			'page2' => 'Second page',
			'rev2' => '1223443',
		] ) );

		$this->verifyComparePagesForm( $html );

		$this->assertStringContainsString( '(compare-revision-not-exists)', $html );
	}

	/** @inheritDoc */
	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'ComparePages' );
	}
}
