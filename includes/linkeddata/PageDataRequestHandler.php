<?php

/**
 * Request handler implementing a linked data interface for mediawiki pages.
 *
 * @license GPL-2.0+
 * @author Daniel Kinzler
 * @author Amir Sarabadanai
 */
class PageDataRequestHandler {

	/**
	 * @var string
	 */
	private $defaultFormat;

	/**
	 * @param string $defaultFormat The format as a file extension or MIME type.
	 */
	public function __construct( $defaultFormat ) {
		$this->defaultFormat = $defaultFormat;
	}

	/**
	 * Checks whether the request is complete, i.e. whether it contains all information needed
	 * to reply with entity data.
	 *
	 * This does not check whether the request is valid and will actually produce a successful
	 * response.
	 *
	 * @param string|null $doc Document name, e.g. Q5 or Q5.json or Q5:33.xml
	 * @param WebRequest $request
	 *
	 * @return bool
	 * @throws HttpError
	 */
	public function canHandleRequest( $doc, WebRequest $request ) {
		if ( $doc === '' || $doc === null ) {
			if ( $request->getText( 'title', '' ) === '' ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Main method for handling requests.
	 *
	 * @param string $doc Document name, e.g. Q5 or Q5.json or Q5:33.xml
	 * @param WebRequest $request The request parameters. Known parameters are:
	 *        - id: the entity ID
	 *        - format: the format
	 *        - oldid|revision: the revision ID
	 *        - action=purge: to purge cached data from (web) caches
	 * @param OutputPage $output
	 *
	 * @note: Instead of an output page, a WebResponse could be sufficient, but
	 *        redirect logic is currently implemented in OutputPage.
	 *
	 * @throws HttpError
	 */
	public function handleRequest( $title, WebRequest $request, OutputPage $output ) {
		// No matter what: The response is always public
		$output->getRequest()->response()->header( 'Access-Control-Allow-Origin: *' );

		$revision = 0;

		$title = $request->getText( 'title', $title );
		$revision = $request->getInt( 'oldid', $revision );
		$revision = $request->getInt( 'revision', $revision );
		//TODO: malformed revision IDs should trigger a code 400

		if ( $title === null || $title === '' ) {
			//TODO: different error message?
			throw new HttpError( 400, wfMessage( 'pagedata-bad-id', $title ) );
		}

		try {
			$title = Title::newFromText( $title );
		} catch ( MalformedTitleException $ex ) {
			throw new HttpError( 400, wfMessage( 'pagedata-bad-id', $title ) );
		}

		$this->httpContentNegotiation( $request, $output, $title, $revision );
	}

	/**
	 * Applies HTTP content negotiation.
	 * If the negotiation is successfull, this method will set the appropriate redirect
	 * in the OutputPage object and return. Otherwise, an HttpError is thrown.
	 *
	 * @param WebRequest $request
	 * @param OutputPage $output
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
			if ( $this->defaultFormat != null ) {
				$accept[$this->defaultFormat] = 1;
			}
		}

		$contentHandler = ContentHandler::getForTitle( $title );
		$mimeTypes = $contentHandler->getSupportedFormats();
		$mimeTypes[] = 'text/html';
		$negotiator = new HttpAcceptNegotiator( $mimeTypes );
		$format = $negotiator->getBestSupportedKey( $accept, null );

		if ( $format === null ) {
			$msg = wfMessage( 'pagedata-not-acceptable', implode( ', ', $mimeTypes ) );
			throw new HttpError( 406, $msg );
		}

		$url = $this->getDocUrl( $title, $format, $revision );
		$output->redirect( $url, 303 );
	}

	/**
	 * Returns a Title representing the given document.
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
