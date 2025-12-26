<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\LinkedData;

use MediaWiki\Exception\HttpError;
use MediaWiki\MediaWikiServices;
use MediaWiki\Output\OutputPage;
use MediaWiki\Request\WebRequest;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Title\MalformedTitleException;
use MediaWiki\Title\Title;
use Wikimedia\Http\HttpAcceptNegotiator;
use Wikimedia\Http\HttpAcceptParser;

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
	 */
	public function canHandleRequest( $subPage, WebRequest $request ) {
		if ( $subPage === '' || $subPage === null ) {
			return $request->getText( 'target' ) !== '';
		}

		$parts = explode( '/', $subPage, 2 );
		$slot = $parts[0];
		$title = $parts[1] ?? '';
		return ( $slot === SlotRecord::MAIN || $slot === '' ) && $title !== '';
	}

	/**
	 * Main method for handling requests.
	 *
	 * @param string|null $subPage
	 * @param WebRequest $request The request parameters. Known parameters are:
	 *   - title: the page title
	 *   - format: the format
	 *   - oldid|revision: the revision ID
	 * @param OutputPage $output
	 *
	 * @note Instead of an output page, a WebResponse could be sufficient, but
	 *   redirect logic is currently implemented in OutputPage.
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

		if ( $subPage !== '' ) {
			$parts = explode( '/', $subPage, 2 );
			$titleText = $parts[1] ?? '';
		} else {
			$titleText = $request->getText( 'target' );
		}

		$revision = $request->getInt( 'oldid', $revision );
		$revision = $request->getInt( 'revision', $revision );

		if ( $titleText === null || $titleText === '' ) {
			// TODO: different error message?
			throw new HttpError( 400, wfMessage( 'pagedata-bad-title', (string)$titleText ) );
		}

		try {
			$title = MediaWikiServices::getInstance()->getTitleFactory()->newFromTextThrow( $titleText );
		} catch ( MalformedTitleException ) {
			throw new HttpError( 400, wfMessage( 'pagedata-bad-title', $titleText ) );
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
		$mimeTypes = MediaWikiServices::getInstance()
			->getContentHandlerFactory()
			->getContentHandler( $title->getContentModel() )
			->getSupportedFormats();

		$acceptHeader = $request->getHeader( 'Accept' );
		if ( $acceptHeader !== false ) {
			$parser = new HttpAcceptParser();
			$accept = $parser->parseWeights( $acceptHeader );
		} else {
			// anything goes
			$accept = [
				'*' => 0.1 // just to make extra sure
			];
			// prefer the default
			$accept[$mimeTypes[0]] = 1;
		}

		$negotiator = new HttpAcceptNegotiator( $mimeTypes );
		$format = $negotiator->getBestSupportedKey( $accept );

		if ( $format === null ) {
			$format = isset( $accept['text/html'] ) ? 'text/html' : null;
		}

		if ( $format === null ) {
			throw new HttpError( 406, wfMessage( 'pagedata-not-acceptable', implode( ', ', $mimeTypes ) ) );
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

/** @deprecated class alias since 1.42 */
class_alias( PageDataRequestHandler::class, 'PageDataRequestHandler' );
