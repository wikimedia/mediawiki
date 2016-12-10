<?php

namespace MediaWiki\Cdn;

use Hooks;
use OutputPage;
use Title;
use WebRequest;
use Wikimedia\Assert\Assert;

/**
 * ActiveCdnController
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class CacheHeaderCdnController implements CdnController {

	/**
	 * @var int
	 */
	private $maxAge;

	/**
	 * CacheHeaderCdnController constructor.
	 *
	 * @param int $maxAge fronm the SquidMaxAge setting
	 */
	public function __construct( $maxAge ) {
		Assert::parameterType( 'int', $maxAge, '$maxAge' );
		$this->maxAge = $maxAge;
	}

	/**
	 * @param string[] $resourceUris
	 *
	 * @throws \Exception if supportsBuckets() returns false.
	 * @return void
	 */
	public function purgeURLs( array $resourceUris ) {
		// noop
	}

	/**
	 * @param Title $title
	 *
	 * @return void
	 */
	public function purgeDependentResources( Title $title ) {
		$urls = $this->getDependentResources( $title );
		$this->purgeURLs( $urls );
	}

	/**
	 * @param Title $requestTitle
	 * @param WebRequest $request
	 * @param OutputPage $output
	 */
	public function applyCacheControl( Title $requestTitle, WebRequest $request, OutputPage $output ) {
		$urls = $this->getDependentResources( $requestTitle );

		if ( in_array(
			// Use PROTO_INTERNAL because that's what getCdnUrls() uses
				wfExpandUrl( $request->getRequestURL(), PROTO_INTERNAL ),
				$urls
			)
		) {
			$output->setCdnMaxage( $this->maxAge );
		}
	}

	/**
	 * @param Title $title
	 *
	 * @return string[]
	 */
	public function getDependentResources( Title $title ) {
		$urls = $this->getPageUrls( $title );

		Hooks::run( 'TitleSquidURLs', [ $title, &$urls, $this->isUsingBuckets() ] );
		return $urls;
	}

	protected function isUsingBuckets() {
		return false;
	}

	protected function getPageUrls( Title $title ) {
		$urls = [
			$title->getInternalURL(),
			$title->getInternalURL( 'action=history' )
		];

		$pageLang = $title->getPageLanguage();
		if ( $pageLang->hasVariants() ) {
			$variants = $pageLang->getVariants();
			foreach ( $variants as $vCode ) {
				$urls[] = $title->getInternalURL( $vCode );
			}
		}

		// If we are looking at a css/js user subpage, purge the action=raw.
		if ( $title->isJsSubpage() ) {
			$urls[] = $title->getInternalURL( 'action=raw&ctype=text/javascript' );
		} elseif ( $title->isCssSubpage() ) {
			$urls[] = $title->getInternalURL( 'action=raw&ctype=text/css' );
		}
	}
}
