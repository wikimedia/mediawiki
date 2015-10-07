<?php

/**
 * @group API
 * @group medium
 * @group Database
 */
class ApiPageSetTest extends ApiTestCase {
	public static function provideMergePolicy() {
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
	 * @dataProvider provideMergePolicy
	 */
	public function testSomething( $mergePolicy, $expect ) {
		$source = Title::makeTitle( NS_MAIN, 'UTRedirectSource' );
		$target = Title::makeTitle( NS_MAIN, 'UTRedirectTarget' );
		self::editPage( 'UTRedirectTarget', 'api page set test' );
		self::editPage( 'UTRedirectSource', '#REDIRECT [[UTRedirectTarget]]' );

		$request = new FauxRequest( array( 'redirects' => 1 ) );
		$context = new RequestContext();
		$context->setRequest( $request );
		$main = new ApiMain( $context );
		$base = $this->getMockBuilder( 'ApiBase' )
			->disableOriginalConstructor()
			->getMock();
		$base->expects( $this->any() )
			->method( 'getMain' )
			->will( $this->returnValue( $main ) );
		$pageSet = new ApiPageSet( $base );

		$pageSet->setRedirectMergePolicy( $mergePolicy );
		$pageSet->setGeneratorData( $source, array( 'index' => 1 ) );
		$pageSet->populateFromTitles( array( $source ) );

		$result = array(
			$target->getArticleID() => array()
		);
		$pageSet->populateGeneratorData( $result );
		$this->assertEquals( $expect, $result[$target->getArticleID()] );
	}
}
