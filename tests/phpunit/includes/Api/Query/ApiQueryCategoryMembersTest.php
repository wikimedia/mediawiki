<?php

namespace MediaWiki\Tests\Api\Query;

use MediaWiki\Api\ApiListRestHelper;
use MediaWiki\Api\ApiMain;
use MediaWiki\Api\ApiQuery;
use MediaWiki\Api\ApiQueryCategoryMembers;
use MediaWiki\Api\ApiRestHelperContext;
use MediaWiki\Api\ApiUsageException;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\Api\ApiTestCase;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiQueryCategoryMembers
 */
class ApiQueryCategoryMembersTest extends ApiTestCase {

	private function newCategoryMembersModule(): ApiQueryCategoryMembers {
		$main = new ApiMain( new FauxRequest( [] ) );
		/** @var ApiQuery $query */
		$query = $main->getModuleManager()->getModule( 'query' );
		return $query->getModuleManager()->getModule( 'categorymembers' );
	}

	/**
	 * Runs list=categorymembers with the module in REST mode, as the REST framework does.
	 *
	 * @param array $params Parameters of the module, with the "cm" prefix
	 * @return array[] The items of the result
	 */
	private function executeInRestMode( array $params ): array {
		$main = new ApiMain( new FauxRequest( [ 'action' => 'query', 'list' => 'categorymembers' ] + $params ) );
		/** @var ApiQuery $query */
		$query = $main->getModuleManager()->getModule( 'query' );
		// The module manager caches instances, so the query executes this one.
		$query->getModuleManager()->getModule( 'categorymembers' )
			->initRestMode( new ApiRestHelperContext( [], [] ) );

		$main->execute();

		return $main->getResult()->getResultData( [ 'query', 'categorymembers' ], [ 'Strip' => 'all' ] );
	}

	public function testInitRestMode_returnsListHelperWithCategoryMemberItemSchema() {
		$helper = $this->newCategoryMembersModule()->initRestMode( new ApiRestHelperContext( [], [] ) );

		$this->assertInstanceOf( ApiListRestHelper::class, $helper );
		// The essential assertion: the item schema describes category members.
		$this->assertArrayHasKey( 'sortkeyprefix', $helper->getResponseItemSchema()['properties'] );
	}

	public function testGetAllowedParams_actionMode() {
		$params = $this->newCategoryMembersModule()->getFinalParams();

		$this->assertArrayNotHasKey( 'name', $params );
	}

	public function testGetAllowedParams_restMode() {
		$module = $this->newCategoryMembersModule();
		$module->initRestMode( new ApiRestHelperContext( [], [] ) );

		$params = $module->getFinalParams();

		// REST names the category by its name, the action API by its title or page ID.
		$this->assertArrayHasKey( 'name', $params );
		$this->assertArrayHasKey( 'title', $params );
	}

	public function testExecute_restModeNameListsTheMembers() {
		$this->editPage( 'ApiCatMembersRestPage1', '[[Category:ApiCatMembersRest A]]' );
		$this->editPage( 'ApiCatMembersRestPage2', '[[Category:ApiCatMembersRest A]]' );
		$this->editPage( 'ApiCatMembersRestPage3', '[[Category:ApiCatMembersRest B]]' );

		// The name has no namespace prefix, and underscores stand for spaces.
		$items = $this->executeInRestMode( [ 'cmname' => 'ApiCatMembersRest_A' ] );

		$this->assertSame(
			[ 'ApiCatMembersRestPage1', 'ApiCatMembersRestPage2' ],
			array_column( $items, 'title' )
		);
	}

	public function testExecute_restModeInvalidNameIsRejected() {
		$this->expectException( ApiUsageException::class );
		$this->executeInRestMode( [ 'cmname' => 'Bad<name>' ] );
	}
}
