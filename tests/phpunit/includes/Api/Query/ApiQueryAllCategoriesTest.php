<?php

namespace MediaWiki\Tests\Api\Query;

use MediaWiki\Api\ApiListRestHelper;
use MediaWiki\Api\ApiMain;
use MediaWiki\Api\ApiQuery;
use MediaWiki\Api\ApiQueryAllCategories;
use MediaWiki\Api\ApiRestHelperContext;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\Api\ApiTestCase;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiQueryAllCategories
 */
class ApiQueryAllCategoriesTest extends ApiTestCase {

	private function newAllCategoriesModule(): ApiQueryAllCategories {
		$main = new ApiMain( new FauxRequest( [] ) );
		/** @var ApiQuery $query */
		$query = $main->getModuleManager()->getModule( 'query' );
		return $query->getModuleManager()->getModule( 'allcategories' );
	}

	/**
	 * Runs list=allcategories with the module in REST mode, as the REST framework does.
	 *
	 * @param array $params Parameters of the module, with the "ac" prefix
	 * @return array[] The items of the result
	 */
	private function executeInRestMode( array $params ): array {
		$main = new ApiMain( new FauxRequest( [ 'action' => 'query', 'list' => 'allcategories' ] + $params ) );
		/** @var ApiQuery $query */
		$query = $main->getModuleManager()->getModule( 'query' );
		// The module manager caches instances, so the query executes this one.
		$query->getModuleManager()->getModule( 'allcategories' )
			->initRestMode( new ApiRestHelperContext( [], [] ) );

		$main->execute();

		return $main->getResult()->getResultData( [ 'query', 'allcategories' ], [ 'Strip' => 'all' ] );
	}

	public function testInitRestMode_returnsListHelperWithCategoryItemSchema() {
		$helper = $this->newAllCategoriesModule()->initRestMode( new ApiRestHelperContext( [], [] ) );

		$this->assertInstanceOf( ApiListRestHelper::class, $helper );
		// The essential assertion: the item schema describes categories.
		$this->assertArrayHasKey( 'subcats', $helper->getResponseItemSchema()['properties'] );
	}

	public function testGetAllowedParams_actionMode() {
		$params = $this->newAllCategoriesModule()->getFinalParams();

		$this->assertArrayNotHasKey( 'name', $params );
		$this->assertSame( '', $params['prop'][ParamValidator::PARAM_DEFAULT] );
	}

	public function testGetAllowedParams_restMode() {
		$module = $this->newAllCategoriesModule();
		$module->initRestMode( new ApiRestHelperContext( [], [] ) );

		$params = $module->getFinalParams();

		// REST addresses a single category by name.
		$this->assertArrayHasKey( 'name', $params );
		$this->assertArrayNotHasKey( ParamValidator::PARAM_ISMULTI, $params['name'] );
		// Without the props, a category would have nothing but its name.
		$this->assertSame( 'size|hidden', $params['prop'][ParamValidator::PARAM_DEFAULT] );
	}

	public function testExecute_restModeNameFindsTheCategory() {
		$this->editPage( 'ApiAllCatRestPage1', '[[Category:ApiAllCatRest A]]' );
		$this->editPage( 'ApiAllCatRestPage2', '[[Category:ApiAllCatRest A]]' );
		// A category whose name starts with the name, which the range must not reach.
		$this->editPage( 'ApiAllCatRestPage3', '[[Category:ApiAllCatRest AB]]' );
		// The category table is updated by a job.
		$this->runJobs();

		// The name has no namespace prefix, and underscores stand for spaces.
		$items = $this->executeInRestMode( [ 'acname' => 'ApiAllCatRest_A' ] );

		$this->assertCount( 1, $items );
		$this->assertSame( 'ApiAllCatRest A', $items[0]['category'] );
		$this->assertSame( 2, $items[0]['size'] );
		$this->assertFalse( $items[0]['hidden'] );
	}

	public function testExecute_restModeNameOfUnknownCategoryHasNoItems() {
		$items = $this->executeInRestMode( [ 'acname' => 'ApiAllCatRest no such category' ] );

		$this->assertSame( [], $items );
	}
}
