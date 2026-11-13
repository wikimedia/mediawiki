<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Page\WikiPage;
use MediaWiki\Permissions\Authority;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\ResponseInterface;
use MediaWikiTestCaseTrait;

/**
 * This trait is used in PageLintLHandlerTest.php & RevisionLintHandlerTest.php to construct requests.
 *
 * @extends \PHPUnit\Framework\TestCase
 */
trait LintHandlerTestTrait {

	use MediaWikiTestCaseTrait;
	use HandlerTestTrait;

	/**
	 * @param WikiPage $page
	 * @param array $queryParams
	 * @param array $config
	 * @return ResponseInterface
	 */
	private function executePageLintRequest(
		WikiPage $page,
		array $queryParams = [],
		array $config = [],
		?Authority $authority = null
	): ResponseInterface {
		$handler = $this->newHandler();
		$request = new RequestData( [
			'pathParams' => [ 'title' => $page->getTitle()->getPrefixedDBkey() ],
			'queryParams' => $queryParams,
		] );

		return $this->executeHandler(
			$handler,
			$request,
			$config,
			[],
			[],
			[],
			$authority
		);
	}

	/**
	 * @param int $revId
	 * @param array $queryParams
	 * @param array $config
	 * @return ResponseInterface
	 */
	private function executeRevisionLintRequest(
		int $revId,
		array $queryParams = [],
		array $config = [],
		?Authority $authority = null
	): ResponseInterface {
		$handler = $this->newHandler();
		$request = new RequestData( [
			'pathParams' => [ 'id' => $revId ],
			'queryParams' => $queryParams,
		] );

		return $this->executeHandler(
			$handler,
			$request,
			$config,
			[],
			[],
			[],
			$authority
		);
	}
}
