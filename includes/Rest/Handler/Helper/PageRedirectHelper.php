<?php

namespace MediaWiki\Rest\Handler\Helper;

use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\RedirectStore;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\Router;
use TitleFormatter;

/**
 * Helper class for handling page redirects, for use with REST Handlers that provide access
 * to resources bound to MediaWiki pages.
 *
 * @since 1.41
 */
class PageRedirectHelper {

	private RedirectStore $redirectStore;
	private TitleFormatter $titleFormatter;
	private ResponseFactory $responseFactory;
	private Router $router;
	private string $path;
	private RequestInterface $request;

	private bool $followWikiRedirects = false;

	private string $titleParamName = 'title';

	public function __construct(
		RedirectStore $redirectStore,
		TitleFormatter $titleFormatter,
		ResponseFactory $responseFactory,
		Router $router,
		string $path,
		RequestInterface $request
	) {
		$this->redirectStore = $redirectStore;
		$this->titleFormatter = $titleFormatter;
		$this->responseFactory = $responseFactory;
		$this->router = $router;
		$this->path = $path;
		$this->request = $request;
	}

	/**
	 * Sets the name of the path parameter that represents the page title.
	 * @param string $titleParamName
	 */
	public function setTitleParamName( string $titleParamName ): void {
		$this->titleParamName = $titleParamName;
	}

	/**
	 * @param bool $followWikiRedirects
	 */
	public function setFollowWikiRedirects( bool $followWikiRedirects ): void {
		$this->followWikiRedirects = $followWikiRedirects;
	}

	/**
	 * Check for Page Normalization Redirects and create a Permanent Redirect Response
	 * @param PageIdentity $page
	 * @param ?string $titleAsRequested
	 * @return Response|null
	 */
	public function createNormalizationRedirectResponseIfNeeded(
		PageIdentity $page,
		?string $titleAsRequested
	): ?Response {
		if ( $titleAsRequested === null ) {
			return null;
		}

		$normalizedTitle = $this->titleFormatter->getPrefixedDBkey( $page );

		// Check for normalization redirects
		if ( $titleAsRequested !== $normalizedTitle ) {
			$redirectTargetUrl = $this->getTargetUrl( $normalizedTitle );
			return $this->responseFactory->createPermanentRedirect( $redirectTargetUrl );
		}

		return null;
	}

	/**
	 * Check for Page Wiki Redirects and create a Temporary Redirect Response
	 * @param PageIdentity $page
	 * @return Response|null
	 */
	public function createWikiRedirectResponseIfNeeded( PageIdentity $page ): ?Response {
		$redirectTargetUrl = $this->getWikiRedirectTargetUrl( $page );

		if ( $redirectTargetUrl === null ) {
			return null;
		}

		return $this->responseFactory->createTemporaryRedirect( $redirectTargetUrl );
	}

	/**
	 * @param PageIdentity $page
	 * @return string|null
	 */
	public function getWikiRedirectTargetUrl( PageIdentity $page ): ?string {
		$redirectTarget = $this->redirectStore->getRedirectTarget( $page );

		if ( $redirectTarget === null ) {
			return null;
		}

		return $this->getTargetUrl( $redirectTarget );
	}

	/**
	 * @param string|LinkTarget|PageReference $title
	 * @return string
	 */
	public function getTargetUrl( $title ): string {
		if ( !is_string( $title ) ) {
			$title = $this->titleFormatter->getPrefixedDBkey( $title );
		}

		return $this->router->getRouteUrl(
			$this->path,
			[ $this->titleParamName => $title ],
			$this->request->getQueryParams()
		);
	}

	/**
	 * Use this function for endpoints that check for both normalizations and wiki redirects
	 * @param PageIdentity $page
	 * @param string|null $titleAsRequested
	 * @return Response|null
	 */
	public function createRedirectResponseIfNeeded(
		PageIdentity $page,
		?string $titleAsRequested
	): ?Response {
		if ( $titleAsRequested !== null ) {
			$normalizationRedirectResponse = $this->createNormalizationRedirectResponseIfNeeded(
				$page, $titleAsRequested
			);

			if ( $normalizationRedirectResponse !== null ) {
				return $normalizationRedirectResponse;
			}
		}

		if ( $this->followWikiRedirects ) {
			$wikiRedirectResponse = $this->createWikiRedirectResponseIfNeeded( $page );

			if ( $wikiRedirectResponse !== null ) {
				return $wikiRedirectResponse;
			}
		}

		return null;
	}
}
