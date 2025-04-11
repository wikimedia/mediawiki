<?php

namespace MediaWiki\Rest\Handler\Helper;

use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\RedirectStore;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\Router;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\Title\TitleValue;

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
	private LanguageConverterFactory $languageConverterFactory;
	private bool $followWikiRedirects = false;
	private string $titleParamName = 'title';
	private bool $useRelativeRedirects = true;

	public function __construct(
		RedirectStore $redirectStore,
		TitleFormatter $titleFormatter,
		ResponseFactory $responseFactory,
		Router $router,
		string $path,
		RequestInterface $request,
		LanguageConverterFactory $languageConverterFactory
	) {
		$this->redirectStore = $redirectStore;
		$this->titleFormatter = $titleFormatter;
		$this->responseFactory = $responseFactory;
		$this->router = $router;
		$this->path = $path;
		$this->request = $request;
		$this->languageConverterFactory = $languageConverterFactory;
	}

	public function setUseRelativeRedirects( bool $useRelativeRedirects ): void {
		$this->useRelativeRedirects = $useRelativeRedirects;
	}

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
			$redirectTargetUrl = $this->getTargetUrl( $normalizedTitle, false );
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

		if ( $redirectTarget->isSameLinkAs( TitleValue::newFromPage( $page ) ) ) {
			// This can happen if the current page is virtual file description
			// page backed by a remote file repo (T353688).
			return null;
		}

		return $this->getTargetUrl( $redirectTarget );
	}

	/**
	 * Check if a page with a variant title exists and create a Temporary Redirect Response
	 * if needed.
	 *
	 * @param PageIdentity $page
	 * @param string|null $titleAsRequested
	 *
	 * @return Response|null
	 */
	private function createVariantRedirectResponseIfNeeded(
		PageIdentity $page, ?string $titleAsRequested
	): ?Response {
		if ( $page->exists() ) {
			// If the page exists, there is no need to generate a redirect.
			return null;
		}

		$redirectTargetUrl = $this->getVariantRedirectTargetUrl(
			$page,
			$titleAsRequested
		);

		if ( $redirectTargetUrl === null ) {
			return null;
		}

		return $this->responseFactory->createTemporaryRedirect( $redirectTargetUrl );
	}

	/**
	 * @param PageIdentity $page
	 * @param string $titleAsRequested
	 *
	 * @return string|null
	 */
	private function getVariantRedirectTargetUrl(
		PageIdentity $page, string $titleAsRequested
	): ?string {
		$variantPage = null;
		if ( $this->hasVariants() ) {
			$variantPage = $this->findVariantPage( $titleAsRequested, $page );
		}

		if ( !$variantPage ) {
			return null;
		}

		return $this->getTargetUrl( $variantPage );
	}

	/**
	 * @param string|LinkTarget|PageReference $title
	 * @param bool $limitRedirects Whether to limit redirect chains (A=>B=>C, etc.) to one level.
	 *
	 * @return string The target to use in the Location header. Will be relative,
	 *         unless setUseRelativeRedirects( false ) was called.
	 */
	public function getTargetUrl( $title, bool $limitRedirects = true ): string {
		if ( !is_string( $title ) ) {
			$title = $this->titleFormatter->getPrefixedDBkey( $title );
		}

		$pathParams = [ $this->titleParamName => $title ];
		$queryParams = $this->request->getQueryParams();

		// Limit to one level of redirection, unless more are explicitly allowed. See T389588.
		if ( $limitRedirects ) {
			$queryParams['redirect'] = 'no';
		}

		if ( $this->useRelativeRedirects ) {
			return $this->router->getRoutePath(
				$this->path,
				$pathParams,
				$queryParams
			);
		} else {
			return $this->router->getRouteUrl(
				$this->path,
				$pathParams,
				$queryParams
			);
		}
	}

	/**
	 * Use this function for endpoints that check for both
	 * normalizations and wiki redirects.
	 *
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
			$variantRedirectResponse = $this->createVariantRedirectResponseIfNeeded( $page, $titleAsRequested );

			if ( $variantRedirectResponse !== null ) {
				return $variantRedirectResponse;
			}

			$wikiRedirectResponse = $this->createWikiRedirectResponseIfNeeded( $page );

			if ( $wikiRedirectResponse !== null ) {
				return $wikiRedirectResponse;
			}
		}

		return null;
	}

	private function hasVariants(): bool {
		return $this->languageConverterFactory->getLanguageConverter()->hasVariants();
	}

	/**
	 * @param string $titleAsRequested
	 * @param PageReference $page
	 *
	 * @return ?PageReference
	 */
	private function findVariantPage( string $titleAsRequested, PageReference $page ): ?PageReference {
		$originalPage = $page;
		$languageConverter = $this->languageConverterFactory->getLanguageConverter();
		// @phan-suppress-next-line PhanTypeMismatchArgumentSuperType
		$languageConverter->findVariantLink( $titleAsRequested, $page, true );

		if ( $page === $originalPage ) {
			// No variant link found, $page was not updated.
			return null;
		}

		return $page;
	}
}
