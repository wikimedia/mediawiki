<?php

/**
 * @group API
 * @group medium
 * @group Database
 */
class ApiPageSetTest extends ApiTestCase {
	public static function provideRedirectMergePolicy() {
		return [
			'By default nothing is merged' => [
				null,
				[]
			],

			'A simple merge policy adds the redirect data in' => [
				function( $current, $new ) {
					if ( !isset( $current['index'] ) || $new['index'] < $current['index'] ) {
						$current['index'] = $new['index'];
					}
					return $current;
				},
				[ 'index' => 1 ],
			],
		];
	}

	/**
	 * @dataProvider provideRedirectMergePolicy
	 */
	public function testRedirectMergePolicyWithArrayResult( $mergePolicy, $expect ) {
		list( $target, $pageSet ) = $this->createPageSetWithRedirect();
		$pageSet->setRedirectMergePolicy( $mergePolicy );
		$result = [
			$target->getArticleID() => []
		];
		$pageSet->populateGeneratorData( $result );
		$this->assertEquals( $expect, $result[$target->getArticleID()] );
	}

	/**
	 * @dataProvider provideRedirectMergePolicy
	 */
	public function testRedirectMergePolicyWithApiResult( $mergePolicy, $expect ) {
		list( $target, $pageSet ) = $this->createPageSetWithRedirect();
		$pageSet->setRedirectMergePolicy( $mergePolicy );
		$result = new ApiResult( false );
		$result->addValue( null, 'pages', [
			$target->getArticleID() => []
		] );
		$pageSet->populateGeneratorData( $result, [ 'pages' ] );
		$this->assertEquals(
			$expect,
			$result->getResultData( [ 'pages', $target->getArticleID() ] )
		);
	}

	protected function createPageSetWithRedirect() {
		$target = Title::makeTitle( NS_MAIN, 'UTRedirectTarget' );
		$sourceA = Title::makeTitle( NS_MAIN, 'UTRedirectSourceA' );
		$sourceB = Title::makeTitle( NS_MAIN, 'UTRedirectSourceB' );
		self::editPage( 'UTRedirectTarget', 'api page set test' );
		self::editPage( 'UTRedirectSourceA', '#REDIRECT [[UTRedirectTarget]]' );
		self::editPage( 'UTRedirectSourceB', '#REDIRECT [[UTRedirectTarget]]' );

		$request = new FauxRequest( [ 'redirects' => 1 ] );
		$context = new RequestContext();
		$context->setRequest( $request );
		$main = new ApiMain( $context );
		$pageSet = new ApiPageSet( $main );

		$pageSet->setGeneratorData( $sourceA, [ 'index' => 1 ] );
		$pageSet->setGeneratorData( $sourceB, [ 'index' => 3 ] );
		$pageSet->populateFromTitles( [ $sourceA, $sourceB ] );

		return [ $target, $pageSet ];
	}
}
