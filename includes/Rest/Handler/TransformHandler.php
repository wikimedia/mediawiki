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

use MediaWiki\Rest\Handler;
use MediaWiki\Rest\Handler\Helper\ParsoidFormatHelper;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\Response;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Handler for transforming content given in the request.
 * - /v1/transform/{from}/to/html
 * - /v1/transform/{from}/to/wikitext
 * - /v1/transform/{from}/to/html/{title}
 * - /v1/transform/{from}/to/wikitext/{title}
 * - /v1/transform/{from}/to/html/{title}/{revision}
 * - /v1/transform/{from}/to/wikitext/{title}/{revision}
 *
 * @see https://www.mediawiki.org/wiki/Parsoid/API#POST
 */
class TransformHandler extends ParsoidHandler {

	/** @inheritDoc */
	public function getParamSettings() {
		$params = [
			'title' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-transform-title' ),
			],
			'revision' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-transform-revision' ),
			],
		];

		if ( !isset( $this->getConfig()['from'] ) ) {
			$params['from'] = [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-transform-from' ),
			];
		}

		return $params;
	}

	/**
	 * @inheritDoc
	 */
	public function needsWriteAccess() {
		return false;
	}

	public function checkPreconditions() {
		// NOTE: disable all precondition checks.
		// If-(not)-Modified-Since is not supported by the /transform/ handler.
		// If-None-Match is not supported by the /transform/ handler.
		// If-Match for wt2html is handled in getRequestAttributes.
	}

	protected function getOpts( array $body, RequestInterface $request ): array {
		return array_merge(
			$body,
			[
				'format' => $this->getTargetFormat(),
				'from' => $this->getFromFormat(),
			]
		);
	}

	protected function &getRequestAttributes(): array {
		$attribs =& parent::getRequestAttributes();

		$request = $this->getRequest();

		// NOTE: If there is more than one ETag, this will break.
		//       We don't have a good way to test multiple ETag to see if one of them is a working stash key.
		$ifMatch = $request->getHeaderLine( 'If-Match' );

		if ( $ifMatch ) {
			$attribs['opts']['original']['etag'] = $ifMatch;
		}

		return $attribs;
	}

	private function getTargetFormat(): string {
		return $this->getConfig()['format'];
	}

	private function getFromFormat(): string {
		$request = $this->getRequest();
		return $this->getConfig()['from'] ?? $request->getPathParam( 'from' );
	}

	protected function generateResponseSpec( string $method ): array {
		// TODO: Consider if we prefer something like (for html and wikitext):
		//    text/html; charset=utf-8; profile="https://www.mediawiki.org/wiki/Specs/HTML/2.8.0"
		//    text/plain; charset=utf-8; profile="https://www.mediawiki.org/wiki/Specs/wikitext/1.0.0"
		//  Those would be more specific, but fragile when the profile version changes.
		switch ( $this->getTargetFormat() ) {
			case 'html':
				$spec = parent::generateResponseSpec( $method );
				$spec['200']['content']['text/html']['schema']['type'] = 'string';
				return $spec;

			case 'wikitext':
				$spec = parent::generateResponseSpec( $method );
				$spec['200']['content']['text/plain']['schema']['type'] = 'string';
				return $spec;

			case 'lint':
				$spec = parent::generateResponseSpec( $method );

				// TODO: define a schema for lint responses
				$spec['200']['content']['application/json']['schema']['type'] = 'array';
				return $spec;

			default:
				// Additional formats may be supported by subclasses, just do nothing.
				return parent::generateResponseSpec( $method );
		}
	}

	/**
	 * Transform content given in the request from or to wikitext.
	 *
	 * @return Response
	 * @throws HttpException
	 */
	public function execute(): Response {
		$request = $this->getRequest();
		$from = $this->getFromFormat();
		$format = $this->getTargetFormat();

		// XXX: Fallback to the default valid transforms in case the request is
		//      coming from a legacy client (restbase) that supports everything
		//      in the default valid transforms.
		$validTransformations = $this->getConfig()['transformations'] ?? ParsoidFormatHelper::VALID_TRANSFORM;

		if ( !isset( $validTransformations[$from] ) || !in_array( $format,
				$validTransformations[$from],
				true ) ) {
			throw new LocalizedHttpException( new MessageValue( "rest-invalid-transform", [ $from, $format ] ), 404 );
		}
		$attribs = &$this->getRequestAttributes();
		if ( !$this->acceptable( $attribs ) ) { // mutates $attribs
			throw new LocalizedHttpException( new MessageValue( "rest-unsupported-target-format" ), 406 );
		}
		if ( $from === ParsoidFormatHelper::FORMAT_WIKITEXT ) {
			// Accept wikitext as a string or object{body,headers}
			$wikitext = $attribs['opts']['wikitext'] ?? null;
			if ( is_array( $wikitext ) ) {
				$wikitext = $wikitext['body'];
				// We've been given a pagelanguage for this page.
				if ( isset( $attribs['opts']['wikitext']['headers']['content-language'] ) ) {
					$attribs['pagelanguage'] = $attribs['opts']['wikitext']['headers']['content-language'];
				}
			}
			// We've been given source for this page
			if ( $wikitext === null && isset( $attribs['opts']['original']['wikitext'] ) ) {
				$wikitext = $attribs['opts']['original']['wikitext']['body'];
				// We've been given a pagelanguage for this page.
				if ( isset( $attribs['opts']['original']['wikitext']['headers']['content-language'] ) ) {
					$attribs['pagelanguage'] = $attribs['opts']['original']['wikitext']['headers']['content-language'];
				}
			}
			// Abort if no wikitext or title.
			if ( $wikitext === null && empty( $attribs['pageName'] ) ) {
				throw new LocalizedHttpException( new MessageValue( "rest-transform-missing-title" ), 400 );
			}
			$pageConfig = $this->tryToCreatePageConfig( $attribs, $wikitext );

			return $this->wt2html( $pageConfig,
				$attribs,
				$wikitext );
		} elseif ( $format === ParsoidFormatHelper::FORMAT_WIKITEXT ) {
			$html = $attribs['opts']['html'] ?? null;
			// Accept html as a string or object{body,headers}
			if ( is_array( $html ) ) {
				$html = $html['body'];
			}
			if ( $html === null ) {
				throw new LocalizedHttpException( new MessageValue( "rest-transform-missing-html" ), 400 );
			}

			// TODO: use ETag from If-Match header, for compat!

			$page = $this->tryToCreatePageIdentity( $attribs );

			return $this->html2wt(
				$page,
				$attribs,
				$html
			);
		} else {
			return $this->pb2pb( $attribs );
		}
	}
}
