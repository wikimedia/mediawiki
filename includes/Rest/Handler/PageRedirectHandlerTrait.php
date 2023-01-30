<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\RedirectStore;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\ResponseFactory;
use TitleFormatter;

/**
 * Important: this trait should be used for implementations of the Handler class
 *  * The use of a trait is a design decision based on the how Handler class currently
 *    deal with middlewares. It would be nice from a design perspective to have redirects
 *    as an actual middleware.
 * @see Handler
 */
trait PageRedirectHandlerTrait {
	/**
	 * @see Handler::getResponseFactory()
	 * @inheritDoc
	 */
	abstract public function getResponseFactory(): ResponseFactory;

	/**
	 * @see Handler::getRouteUrl()
	 * @inheritDoc
	 */
	abstract protected function getRouteUrl( $pathParams = [], $queryParams = [] ): string;

	/**
	 * @see Handler::getRequest()
	 * @inheritDoc
	 */
	abstract protected function getRequest(): RequestInterface;

	/**
	 * Check for Page Normalization Redirects and create a Permanent Redirect Response
	 * @param PageIdentity $page
	 * @param string|null $titleAsRequested
	 * @param TitleFormatter $titleFormatter
	 * @return Response|null
	 */
	private function createNormalizationRedirectResponseIfNeeded(
		PageIdentity $page,
		?string $titleAsRequested,
		TitleFormatter $titleFormatter
	): ?Response {
		if ( $titleAsRequested === null ) {
			return null;
		}

		$normalizedTitle = $titleFormatter->getPrefixedDBkey( $page );

		// Check for normalization redirects
		if ( $titleAsRequested !== $normalizedTitle ) {
			$redirectTargetUrl = $this->getNormalizationRedirectTargetUrl( $normalizedTitle );
			return $this->getResponseFactory()->createPermanentRedirect( $redirectTargetUrl );
		}

		return null;
	}

	/**
	 * Check for Page Wiki Redirects and create a Temporary Redirect Response
	 * @param PageIdentity $page
	 * @param RedirectStore $redirectStore
	 * @param TitleFormatter $titleFormatter
	 * @param bool|null $followWikiRedirects
	 * @return Response|null
	 */
	private function createWikiRedirectResponseIfNeeded(
		PageIdentity $page,
		RedirectStore $redirectStore,
		TitleFormatter $titleFormatter,
		?bool $followWikiRedirects = true
	): ?Response {
		$redirectTargetUrl = $this->getWikiRedirectTargetUrl( $page, $redirectStore, $titleFormatter );

		if ( $redirectTargetUrl === null ) {
			return null;
		}

		// Check if page is a redirect
		if ( $followWikiRedirects ) {
			return $this->getResponseFactory()->createTemporaryRedirect( $redirectTargetUrl );
		}

		return null;
	}

	/**
	 * @param PageIdentity $page
	 * @param RedirectStore $redirectStore
	 * @param TitleFormatter $titleFormatter
	 * @return string|null
	 */
	private function getWikiRedirectTargetUrl(
		PageIdentity $page, RedirectStore $redirectStore, TitleFormatter $titleFormatter
	): ?string {
		$redirectTarget = $redirectStore->getRedirectTarget( $page );

		if ( $redirectTarget === null ) {
			return null;
		}

		return $this->getRouteUrl( [
			"title" => $titleFormatter->getPrefixedDBkey( $redirectTarget ),
		], $this->getRequest()->getQueryParams() );
	}

	/**
	 * @param string $normalizedTitle
	 * @return string
	 */
	private function getNormalizationRedirectTargetUrl( string $normalizedTitle ): string {
		return $this->getRouteUrl( [
			"title" => $normalizedTitle
		], $this->getRequest()->getQueryParams() );
	}

	/**
	 * Use this function for endpoints that check for both normalizations and wiki redirects
	 * @param PageIdentity $page
	 * @param bool $followWikiRedirects
	 * @param string|null $titleAsRequested
	 * @param TitleFormatter $titleFormatter
	 * @param RedirectStore $redirectStore
	 * @return Response|null
	 */
	private function createRedirectResponseIfNeeded(
		PageIdentity $page,
		bool $followWikiRedirects,
		?string $titleAsRequested,
		TitleFormatter $titleFormatter,
		RedirectStore $redirectStore
	): ?Response {
		if ( $titleAsRequested === null ) {
			return null;
		}

		// That means param redirect=no is present in the request
		if ( !$followWikiRedirects ) {
			return null;
		}

		$normalizationRedirectResponse = $this->createNormalizationRedirectResponseIfNeeded(
			$page, $titleAsRequested, $titleFormatter
		);

		if ( $normalizationRedirectResponse !== null ) {
			return $normalizationRedirectResponse;
		}

		$wikiRedirectResponse = $this->createWikiRedirectResponseIfNeeded( $page, $redirectStore, $titleFormatter );

		if ( $wikiRedirectResponse !== null ) {
			return $wikiRedirectResponse;
		}

		return null;
	}
}
