<?php
/**
 * Entry point implementation for all %Action API queries, handled by ApiMain
 * and ApiBase subclasses.
 *
 * @see /api.php The corresponding HTTP entry point.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup entrypoint
 * @ingroup API
 */

namespace MediaWiki\Api;

use LogicException;
use MediaWiki\Context\RequestContext;
use MediaWiki\EntryPointEnvironment;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MediaWikiEntryPoint;
use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;
use Throwable;

/**
 * Implementation of the API entry point, for web browser navigations, usually via an
 * Action or SpecialPage subclass.
 *
 * This is used by bots to fetch content and information about the wiki,
 * its pages, and its users. See <https://www.mediawiki.org/wiki/API> for more
 * information.
 *
 * @see /api.php The corresponding HTTP entry point.
 * @internal
 */
class ApiEntryPoint extends MediaWikiEntryPoint {

	public function __construct(
		RequestContext $context,
		EntryPointEnvironment $environment,
		MediaWikiServices $services
	) {
		parent::__construct(
			$context,
			$environment,
			$services
		);
	}

	/**
	 * Overwritten to narrow the return type to RequestContext
	 */
	protected function getContext(): RequestContext {
		/** @var RequestContext $context */
		$context = parent::getContext();

		// @phan-suppress-next-line PhanTypeMismatchReturnSuperType see $context in the constructor
		return $context;
	}

	/**
	 * Executes a request to the action API.
	 *
	 * It begins by constructing a new ApiMain using the parameter passed to it
	 * as an argument in the URL ('?action='). It then invokes "execute()" on the
	 * ApiMain object instance, which produces output in the format specified in
	 * the URL.
	 */
	protected function execute() {
		// phpcs:ignore MediaWiki.Usage.DeprecatedGlobalVariables.Deprecated$wgTitle
		global $wgTitle;

		$context = $this->getContext();
		$request = $this->getRequest();

		$services = $this->getServiceContainer();

		// PATH_INFO can be used for stupid things. We don't support it for api.php at
		// all, so error out if it's present. (T128209)
		$pathInfo = $this->environment->getServerInfo( 'PATH_INFO', '' );
		if ( $pathInfo != '' ) {
			$correctUrl = wfAppendQuery(
				wfScript( 'api' ),
				$request->getQueryValuesOnly()
			);
			$correctUrl = (string)$services->getUrlUtils()->expand(
				$correctUrl,
				PROTO_CANONICAL
			);
			$this->header(
				"Location: $correctUrl",
				true,
				301
			);
			$this->print(
				'This endpoint does not support "path info", i.e. extra text ' .
				'between "api.php" and the "?". Remove any such text and try again.'
			);
			$this->exit( 1 );
		}

		// Set a dummy $wgTitle, because $wgTitle == null breaks various things
		// In a perfect world this wouldn't be necessary
		$wgTitle = Title::makeTitle(
			NS_SPECIAL,
			'Badtitle/dummy title for API calls set in api.php'
		);

		// RequestContext will read from $wgTitle, but it will also whine about it.
		// In a perfect world this wouldn't be necessary either.
		$context->setTitle( $wgTitle );

		try {
			// Construct an ApiMain with the arguments passed via the URL. What we get back
			// is some form of an ApiMain, possibly even one that produces an error message,
			// but we don't care here, as that is handled by the constructor.
			$processor = new ApiMain(
				$context,
				true,
				false
			);

			// Last chance hook before executing the API
			( new HookRunner( $services->getHookContainer() ) )->onApiBeforeMain( $processor );
			if ( !$processor instanceof ApiMain ) {
				throw new LogicException(
					'ApiBeforeMain hook set $processor to a non-ApiMain class'
				);
			}
		} catch ( Throwable $e ) {
			// Crap. Try to report the exception in API format to be friendly to clients.
			ApiMain::handleApiBeforeMainException( $e );
			$processor = false;
		}

		// Process data & print results
		if ( $processor ) {
			$processor->execute();
		}
	}
}
