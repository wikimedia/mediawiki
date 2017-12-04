<?php
/**
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

use Wikimedia\Http\HttpAcceptParser;
use Wikimedia\Http\HttpAcceptNegotiator;

/**
 * Request handler implementing a data interface for mediawiki pages.
 *
 * @author Daniel Kinzler
 * @author Amir Sarabadanai
 */
class PageDataRequestHandler {

	/**
	 * Checks whether the request is complete, i.e. whether it contains all information needed
	 * to reply with page data.
	 *
	 * This does not check whether the request is valid and will actually produce a successful
	 * response.
	 *
	 * @param string|null $subPage
	 * @param WebRequest $request
	 *
	 * @return bool
	 * @throws HttpError
	 */
	public function canHandleRequest( $subPage, WebRequest $request ) {
		if ( $subPage === '' || $subPage === null ) {
			if ( $request->getText( 'target', '' ) === '' ) {
				return false;
			} else {
				return true;
			}
		}

		$parts = explode( '/', $subPage, 2 );
		if ( $parts !== 2 ) {
			$slot = $parts[0];
			if ( $slot === 'main' || $slot === '' ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Main method for handling requests.
	 *
	 * @param string $subPage
	 * @param WebRequest $request The request parameters. Known parameters are:
	 *        - title: the page title
	 *        - format: the format
	 *        - oldid|revision: the revision ID
	 * @param OutputPage $output
	 *
	 * @note: Instead of an output page, a WebResponse could be sufficient, but
	 *        redirect logic is currently implemented in OutputPage.
	 *
	 * @throws HttpError
	 */
	public function handleRequest( $subPage, WebRequest $request, OutputPage $output ) {
		// No matter what: The response is always public
		$output->getRequest()->response()->header( 'Access-Control-Allow-Origin: *' );

		if ( !$this->canHandleRequest( $subPage, $request ) ) {
			throw new HttpError( 400, wfMessage( 'pagedata-bad-title', $subPage ) );
		}

		$revision = 0;

		$parts = explode( '/', $subPage, 2 );
		if ( $subPage !== '' ) {
			$title = $parts[1];
		} else {
			$title = $request->getText( 'target', '' );
		}

		$revision = $request->getInt( 'oldid', $revision );
		$revision = $request->getInt( 'revision', $revision );

		if ( $title === null || $title === '' ) {
			//TODO: different error message?
			throw new HttpError( 400, wfMessage( 'pagedata-bad-title', $title ) );
		}

		try {
			$title = Title::newFromTextThrow( $title );
		} catch ( MalformedTitleException $ex ) {
			throw new HttpError( 400, wfMessage( 'pagedata-bad-title', $title ) );
		}

		$this->httpContentNegotiation( $request, $output, $title, $revision );
	}

	/**
	 * Applies HTTP content negotiation.
	 * If the negotiation is successful, this method will set the appropriate redirect
	 * in the OutputPage object and return. Otherwise, an HttpError is thrown.
	 *
	 * @param WebRequest $request
	 * @param OutputPage $output
	 * @param Title $title
	 * @param int $revision The desired revision
	 *
	 * @throws HttpError
	 */
	public function httpContentNegotiation(
		WebRequest $request,
		OutputPage $output,
		Title $title,
		$revision = 0
	) {
		$contentHandler = ContentHandler::getForTitle( $title );
		$mimeTypes = $contentHandler->getSupportedFormats();

		$headers = $request->getAllHeaders();
		if ( isset( $headers['ACCEPT'] ) ) {
			$parser = new HttpAcceptParser();
			$accept = $parser->parseWeights( $headers['ACCEPT'] );
		} else {
			// anything goes
			$accept = [
				'*' => 0.1 // just to make extra sure
			];
			// prefer the default
			$accept[$mimeTypes[0]] = 1;
		}

		$negotiator = new HttpAcceptNegotiator( $mimeTypes );
		$format = $negotiator->getBestSupportedKey( $accept, null );

		if ( $format === null ) {
			$format = isset( $accept['text/html'] ) ? 'text/html' : null;
		}

		if ( $format === null ) {
			$msg = wfMessage( 'pagedata-not-acceptable', implode( ', ', $mimeTypes ) );
			throw new HttpError( 406, $msg );
		}

		$url = $this->getDocUrl( $title, $format, $revision );
		$output->redirect( $url, 303 );
	}

	/**
	 * Returns a url representing the given title.
	 *
	 * @param Title $title
	 * @param string|null $format The (normalized) format name, or ''
	 * @param int $revision
	 * @return string
	 */
	private function getDocUrl( Title $title, $format = '', $revision = 0 ) {
		$params = [];

		if ( $revision > 0 ) {
			$params['oldid'] = $revision;
		}

		if ( $format === 'text/html' ) {
			return $title->getFullURL( $params );
		}

		$params[ 'action' ] = 'raw';

		return $title->getFullURL( $params );
	}

}
