<?php

/**
 * Copyright (C) 2011-2020 Wikimedia Foundation and others.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

namespace MediaWiki\Rest\Handler;

use MediaWiki\Api\ApiOpenSearch;
use MediaWiki\Config\Config;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\StringStream;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Utils\UrlUtils;
use MediaWiki\Xml\Xml;
use Wikimedia\Http\HttpAcceptParser;
use Wikimedia\Message\MessageValue;

/**
 * Handler for generating an OpenSearch description document.
 * In a nutshell, this tells browsers how and where
 * to submit search queries to get a search results page back,
 * as well as how to get typeahead suggestions (see ApiOpenSearch).
 *
 * This class handles the following routes:
 * - /v1/search
 *
 * @see https://github.com/dewitt/opensearch
 * @see https://www.opensearch.org
 */
class OpenSearchDescriptionHandler extends Handler {

	private UrlUtils $urlUtils;

	/** @see MainConfigSchema::Favicon */
	private string $favicon;

	/** @see MainConfigSchema::OpenSearchTemplates */
	private array $templates;

	public function __construct( Config $config, UrlUtils $urlUtils ) {
		$this->favicon = $config->get( MainConfigNames::Favicon );
		$this->templates = $config->get( MainConfigNames::OpenSearchTemplates );
		$this->urlUtils = $urlUtils;
	}

	public function execute(): Response {
		$ctype = $this->getContentType();

		$response = $this->getResponseFactory()->create();
		$response->setHeader( 'Content-type', $ctype );

		// Set an Expires header so that CDN can cache it for a short time
		// Short enough so that the sysadmin barely notices when $wgSitename is changed
		$expiryTime = 600; # 10 minutes
		$response->setHeader( 'Expires', gmdate( 'D, d M Y H:i:s', time() + $expiryTime ) . ' GMT' );
		$response->setHeader( 'Cache-control', 'max-age=600' );

		$body = new StringStream();

		$body->write( '<?xml version="1.0"?>' );
		$body->write( Xml::openElement( 'OpenSearchDescription',
			[
				'xmlns' => 'http://a9.com/-/spec/opensearch/1.1/',
				'xmlns:moz' => 'http://www.mozilla.org/2006/browser/search/' ] ) );

		// The spec says the ShortName must be no longer than 16 characters,
		// but 16 is *realllly* short. In practice, browsers don't appear to care
		// when we give them a longer string, so we're no longer attempting to trim.
		//
		// Note: ShortName and the <link title=""> need to match; they are used as
		// a key for identifying if the search engine has been added already, *and*
		// as the display name presented to the end-user.
		//
		// Behavior seems about the same between Firefox and IE 7/8 here.
		// 'Description' doesn't appear to be used by either.
		$fullName = wfMessage( 'opensearch-desc' )->inContentLanguage()->text();
		$body->write( Xml::element( 'ShortName', null, $fullName ) );
		$body->write( Xml::element( 'Description', null, $fullName ) );

		// By default we'll use the site favicon.
		// Double-check if IE supports this properly?
		$body->write( Xml::element( 'Image',
			[
				'height' => 16,
				'width' => 16,
				'type' => 'image/x-icon'
			],
			(string)$this->urlUtils->expand( $this->favicon, PROTO_CURRENT )
		) );

		$urls = [];

		// General search template. Given an input term, this should bring up
		// search results or a specific found page.
		// At least Firefox and IE 7 support this.
		$searchPage = SpecialPage::getTitleFor( 'Search' );
		$urls[] = [
			'type' => 'text/html',
			'method' => 'get',
			'template' => $searchPage->getCanonicalURL( 'search={searchTerms}' ) ];

		// TODO: add v1/search/ endpoints?

		foreach ( $this->templates as $type => $template ) {
			if ( !$template ) {
				$template = ApiOpenSearch::getOpenSearchTemplate( $type );
			}

			if ( $template ) {
				$urls[] = [
					'type' => $type,
					'method' => 'get',
					'template' => $template,
				];
			}
		}

		// Allow hooks to override the suggestion URL settings in a more
		// general way than overriding the whole search engine...
		( new HookRunner( $this->getHookContainer() ) )->onOpenSearchUrls( $urls );

		foreach ( $urls as $attribs ) {
			$body->write( Xml::element( 'Url', $attribs ) );
		}

		// And for good measure, add a link to the straight search form.
		// This is a custom format extension for Firefox, which otherwise
		// sends you to the domain root if you hit "enter" with an empty
		// search box.
		$body->write( Xml::element( 'moz:SearchForm', null,
			$searchPage->getCanonicalURL() ) );

		$body->write( Xml::closeElement( 'OpenSearchDescription' ) );

		$response->setBody( $body );
		return $response;
	}

	/**
	 * Returns the content-type to use for the response.
	 * Will be either 'application/xml' or 'application/opensearchdescription+xml',
	 * depending on the client's preference.
	 *
	 * @return string
	 */
	private function getContentType(): string {
		$params = $this->getValidatedParams();
		if ( $params['ctype'] == 'application/xml' ) {
			// Makes testing tweaks about a billion times easier
			return 'application/xml';
		}

		$acceptHeader = $this->getRequest()->getHeader( 'accept' );

		if ( $acceptHeader ) {
			$parser = new HttpAcceptParser();
			$acceptableTypes = $parser->parseAccept( $acceptHeader[0] );

			foreach ( $acceptableTypes as $acc ) {
				if ( $acc['type'] === 'application/xml' ) {
					return 'application/xml';
				}
			}
		}

		return 'application/opensearchdescription+xml';
	}

	protected function generateResponseSpec( string $method ): array {
		$spec = parent::generateResponseSpec( $method );

		$spec['200']['content']['application/opensearchdescription+xml']['schema']['type'] = 'string';

		return $spec;
	}

	/** @inheritDoc */
	public function getParamSettings() {
		return [
			'ctype' => [
				self::PARAM_SOURCE => 'query',
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-opensearch-ctype' ),
			]
		];
	}

}
