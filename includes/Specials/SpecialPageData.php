<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Exception\HttpError;
use MediaWiki\LinkedData\PageDataRequestHandler;
use MediaWiki\SpecialPage\UnlistedSpecialPage;

/**
 * Special page to act as an endpoint for accessing raw page data.
 *
 * The web server should generally be configured to make this accessible via a canonical URL/URI,
 * such as <http://my.domain.org/data/main/Foo>.
 *
 * @ingroup SpecialPage
 */
class SpecialPageData extends UnlistedSpecialPage {

	/**
	 * @var PageDataRequestHandler|null
	 */
	private $requestHandler = null;

	public function __construct() {
		parent::__construct( 'PageData' );
	}

	/**
	 * Sets the request handler to be used by the special page.
	 * May be used when a particular instance of PageDataRequestHandler is already
	 * known, e.g. during testing.
	 *
	 * If no request handler is set using this method, a default handler is created
	 * on demand by initDependencies().
	 */
	public function setRequestHandler( PageDataRequestHandler $requestHandler ) {
		$this->requestHandler = $requestHandler;
	}

	/**
	 * Initialize any un-initialized members from global context.
	 * In particular, this initializes $this->requestHandler
	 */
	protected function initDependencies() {
		if ( $this->requestHandler === null ) {
			$this->requestHandler = $this->newDefaultRequestHandler();
		}
	}

	/**
	 * Creates a PageDataRequestHandler based on global defaults.
	 *
	 * @return PageDataRequestHandler
	 */
	private function newDefaultRequestHandler() {
		return new PageDataRequestHandler();
	}

	/**
	 * @see SpecialWikibasePage::execute
	 *
	 * @param string|null $subPage
	 *
	 * @throws HttpError
	 */
	public function execute( $subPage ) {
		$this->initDependencies();

		// If there is no title, show an HTML form
		// TODO: Don't do this if HTML is not acceptable according to HTTP headers.
		if ( !$this->requestHandler->canHandleRequest( $subPage, $this->getRequest() ) ) {
			$this->showForm();
			return;
		}

		$this->requestHandler->handleRequest( $subPage, $this->getRequest(), $this->getOutput() );
	}

	/**
	 * Shows an informative page to the user; Called when there is no page to output.
	 */
	public function showForm() {
		$this->getOutput()->showErrorPage( 'pagedata-title', 'pagedata-text' );
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialPageData::class, 'SpecialPageData' );
