<?php

namespace MediaWiki\Tests\Integration\Specials;

use MediaWiki\Tests\Specials\SpecialPageTestBase;

/**
 * @covers \MediaWiki\Specials\SpecialCategories
 * @covers \MediaWiki\Specials\Pager\CategoryPager
 * @group Database
 */
class SpecialCategoriesTest extends SpecialPageTestBase {
	public function testExecuteForNoCategories(): void {
		[ $html ] = $this->executeSpecialPage();

		$this->verifyCategoriesListForm( $html );
		$this->assertStringContainsString( '(categoriespagetext: 0)', $html );
	}

	private function verifyCategoriesListForm( string $html ): void {
		$categoriesListForm = $this->assertSelectorMatchesOneElement( $html, '#mw-special-categories-list-form' );
		$this->assertStringContainsString( '(categories)', $categoriesListForm );
		$this->assertStringContainsString( '(categoriesfrom)', $categoriesListForm );
		$this->assertStringContainsString( '(categories-submit)', $categoriesListForm );
	}

	public function testExecuteWhenCategoriesExist(): void {
		$this->editPage( 'Category:Test', 'Test' );
		$this->editPage( 'Non-Category', '[[Category:Test]]' );
		$this->runJobs( [], [ 'type' => 'CategoryCountUpdateJob' ] );

		[ $html ] = $this->executeSpecialPage();

		$this->verifyCategoriesListForm( $html );
		$this->assertStringContainsString( '(categoriespagetext: 1)', $html );

		$categoriesListHtml = $this->assertSelectorMatchesOneElement(
			$html,
			'.mw-special-categories-list'
		);

		$categoryItem = $this->assertSelectorMatchesOneElement(
			$categoriesListHtml,
			'.mw-special-categories-list-item'
		);
		$this->assertStringContainsString( 'Category:Test', $categoryItem );
		$this->assertStringContainsString( 'nmembers: 1', $categoryItem );
	}

	/** @inheritDoc */
	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'Categories' );
	}
}
