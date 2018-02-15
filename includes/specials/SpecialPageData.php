<?php
/**
 * Special page to act as an endpoint for accessing raw page data.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

/**
 * Special page to act as an endpoint for accessing raw page data.
 * The web server should generally be configured to make this accessible via a canonical URL/URI,
 * such as <http://my.domain.org/data/main/Foo>.
 *
 * @class
 * @ingroup SpecialPage
 */
class SpecialPageData extends SpecialPage {

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
	 *
	 * @param PageDataRequestHandler $requestHandler
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

	public function isListed() {
		// Do not list this page in Special:SpecialPages
		return false;
	}

}
