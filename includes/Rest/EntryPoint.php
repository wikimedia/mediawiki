<?php

namespace MediaWiki\Rest;

use ExtensionRegistry;
use MediaWiki\MediaWikiServices;
use RequestContext;
use Title;

class EntryPoint {
	public static function main() {
		// URL safety checks
		global $wgRequest;
		if ( !$wgRequest->checkUrlExtension() ) {
			return;
		}

		// Set $wgTitle and the title in RequestContext, as in api.php
		global $wgTitle;
		$wgTitle = Title::makeTitle( NS_SPECIAL, 'Badtitle/rest.php' );
		RequestContext::getMain()->setTitle( $wgTitle );

		$services = MediaWikiServices::getInstance();

		$conf = $services->getMainConfig();
		$request = new RequestFromGlobals( [
			'cookiePrefix' => $conf->get( 'CookiePrefix' )
		] );

		global $IP;
		$router = new Router(
			[ "$IP/includes/Rest/coreRoutes.json" ],
			ExtensionRegistry::getInstance()->getAttribute( 'RestRoutes' ),
			$conf->get( 'RestPath' ),
			$services->getLocalServerObjectCache(),
			new ResponseFactory
		);

		$response = $router->execute( $request );

		$webResponse = $wgRequest->response();
		$webResponse->header(
			'HTTP/' . $response->getProtocolVersion() . ' ' .
			$response->getStatusCode() . ' ' .
			$response->getReasonPhrase() );

		foreach ( $response->getRawHeaderLines() as $line ) {
			$webResponse->header( $line );
		}

		foreach ( $response->getCookies() as $cookie ) {
			$webResponse->setCookie(
				$cookie['name'],
				$cookie['value'],
				$cookie['expiry'],
				$cookie['options'] );
		}

		$stream = $response->getBody();
		$stream->rewind();
		if ( $stream instanceof CopyableStreamInterface ) {
			$stream->copyToStream( fopen( 'php://output', 'w' ) );
		} else {
			while ( true ) {
				$buffer = $stream->read( 65536 );
				if ( $buffer === '' ) {
					break;
				}
				echo $buffer;
			}
		}
	}
}
