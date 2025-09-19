<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Block\BlockErrorFormatter;
use MediaWiki\Context\IContextSource;
use MediaWiki\Page\WikiPage;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\UserAuthority;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\User\User;

/**
 * This trait is used in PageLintLHandlerTest.php & RevisionLintHandlerTest.php to construct requests.
 */
trait LintHandlerTestTrait {

	private function getAuthority(): Authority {
		$services = $this->getServiceContainer();
		return new UserAuthority(
		// We need a newly created user because we want IP and newbie to apply.
			new User(),
			new FauxRequest(),
			$this->createMock( IContextSource::class ),
			$services->getPermissionManager(),
			$services->getRateLimiter(),
			$this->createMock( BlockErrorFormatter::class )
		);
	}

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
