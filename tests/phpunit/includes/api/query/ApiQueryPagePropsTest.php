<?php

namespace MediaWiki\Tests\Api\Query;

use MediaWiki\Tests\Api\ApiTestCase;
use WikiPage;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiQueryPageProps
 */
class ApiQueryPagePropsTest extends ApiTestCase {

	/**
	 * Sets a page property directly, without relying on parser behaviour.
	 */
	private function setPageProperty( WikiPage $page, string $name, string $value ): void {
		$this->getDb()->newReplaceQueryBuilder()
			->replaceInto( 'page_props' )
			->uniqueIndexFields( [ 'pp_page', 'pp_propname' ] )
			->row( [
				'pp_page' => $page->getId(),
				'pp_propname' => $name,
				'pp_value' => $value,
			] )
			->caller( __METHOD__ )
			->execute();
	}

	public function testPagePropsAreReturnedForReadablePages() {
		$page = $this->getExistingTestPage( 'ApiQueryPagePropsTest readable' );
		$this->setPageProperty( $page, 'description', 'A description of a readable page' );

		$result = $this->doApiRequest( [
			'action' => 'query',
			'pageids' => $page->getId(),
			'prop' => 'pageprops',
		] );

		$this->assertSame(
			'A description of a readable page',
			$result[0]['query']['pages'][$page->getId()]['pageprops']['description']
		);
	}

	public function testPagePropsAreNotReturnedForUnreadablePages() {
		$page = $this->getExistingTestPage( 'ApiQueryPagePropsTest restricted' );
		$this->setPageProperty( $page, 'description', 'A description of a restricted page' );

		// Make only this page unreadable, as extensions such as Lockdown do.
		$this->setTemporaryHook( 'getUserPermissionsErrors',
			static function ( $title, $user, $action, &$result ) use ( $page ) {
				if ( $action === 'read' && $title->equals( $page->getTitle() ) ) {
					$result = [ 'badaccess-group0' ];
					return false;
				}
				return true;
			}
		);

		$result = $this->doApiRequest( [
			'action' => 'query',
			'pageids' => $page->getId(),
			'prop' => 'pageprops',
		] );

		$this->assertArrayNotHasKey(
			'pageprops',
			$result[0]['query']['pages'][$page->getId()],
			'Page properties must not be exposed for pages the user cannot read'
		);
	}
}
