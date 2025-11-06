<?php

namespace MediaWiki\Tests\Unit\Actions;

use MediaWiki\Actions\ViewAction;
use MediaWiki\Context\RequestContext;
use MediaWiki\Page\Article;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Actions\ViewAction
 *
 * @group Actions
 *
 * @author Derick N. Alangi
 */
class ViewActionTest extends MediaWikiUnitTestCase {
	/**
	 * @return ViewAction
	 */
	private function makeViewActionClassFactory() {
		$page = $this->createMock( Article::class );
		$context = new RequestContext();
		$viewAction = new ViewAction( $page, $context );

		return $viewAction;
	}

	public function testGetName() {
		$viewAction = $this->makeViewActionClassFactory();
		$actual = $viewAction->getName();

		$this->assertSame( 'view', $actual );
	}

	public function testOnView() {
		$viewAction = $this->makeViewActionClassFactory();
		$actual = $viewAction->onView();

		$this->assertNull( $actual );
	}
}
