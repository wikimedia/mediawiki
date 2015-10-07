<?php

/**
 * @group API
 * @group medium
 * @group Database
 */
class ApiPageSetTest extends ApiTestCase {
	public static function provideRedirectMergePolicy() {
		return array(
			'By default nothing is merged' => array(
				null,
				array()
			),

			'A simple merge policy adds the redirect data in' => array(
				function( $current, $new ) {
					return $current + $new;
				},
				array( 'index' => 1 ),
			),
		);
	}

	/**
	 * @dataProvider provideRedirectMergePolicy
	 */
	public function testRedirectMergePolicyWithArrayResult( $mergePolicy, $expect ) {
		list( $target, $pageSet ) = $this->createPageSetWithRedirect();
		$pageSet->setRedirectMergePolicy( $mergePolicy );
		$result = array(
			$target->getArticleID() => array()
		);
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
		$result->addValue( null, 'pages', array(
			$target->getArticleID() => array()
		) );
		$pageSet->populateGeneratorData( $result, array( 'pages' ) );
		$this->assertEquals(
			$expect,
			$result->getResultData( array( 'pages', $target->getArticleID() ) )
		);
	}

	protected function createPageSetWithRedirect() {
		$target = Title::makeTitle( NS_MAIN, 'UTRedirectTarget' );
		$source = Title::makeTitle( NS_MAIN, 'UTRedirectSource' );
		self::editPage( 'UTRedirectTarget', 'api page set test' );
		self::editPage( 'UTRedirectSource', '#REDIRECT [[UTRedirectTarget]]' );

		$request = new FauxRequest( array( 'redirects' => 1 ) );
		$context = new RequestContext();
		$context->setRequest( $request );
		$main = new ApiMain( $context );
		$pageSet = new ApiPageSet( $main );

		$pageSet->setGeneratorData( $source, array( 'index' => 1 ) );
		$pageSet->populateFromTitles( array( $source ) );

		return array( $target, $pageSet );
	}
}
