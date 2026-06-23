<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Rest\Handler;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Handler for transforming content given in the request.
 *
 * This class primarily deals with parameter validation and OpenAPI spec generation.
 * Everything else is handled by the base class.
 *
 * TODO: reconsider the ParsoidHandler => TransformHandler hierarchy. Consider consolidating
 *     classes and moving more validation into functions like getParamSettings(),
 *     getBodyParamSettings(), etc.
 *
 * @unstable Pending consolidation of the Parsoid extension with core code.
 */
class TransformHtmlToWikitextTitleHandler extends TransformHandler {

	/** @inheritDoc */
	public function getParamSettings() {
		return [
			'title' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-html-to-wikitext-title' ),
				Handler::PARAM_EXAMPLE => 'Main_Page',
			],
		];
	}

	/**
	 * The parent TransformHandler class accepts a variety of body parameters in different
	 * situations, some of which can accept values in multiple types and therefore cannot
	 * be described in getBodyParamSettings(). See TransformHandler::execute(), and notice
	 * that both "wikitext" and "html" can be either a string or an object.
	 *
	 * We therefore define a request body schema directly rather than the more common approach of
	 * generating it from definitions in getBodyParamSettings().
	 */
	protected function getRequestBodySchema( string $mediaType ): array {
		return [
			'oneOf' => [
				[
					'type' => 'object',
					'properties' => [
						'html' => [
							'x-i18n-description' => 'rest-property-desc-transform-html',
							'example' => '<h2>Hello world</h2>',
							'type' => 'string',
						]
					]
				],
				[
					'type' => 'object',
					'properties' => [
						'html' => [
							'x-i18n-description' => 'rest-property-desc-transform-html-with-headers',
							'example' => [ 'body' => '<h2>Hello world</h2>' ],
							'type' => 'object',
							'properties' => [
								'headers' => [
									'x-i18n-description' => 'rest-property-desc-transform-html-headers',
									'example' => [ 'content-language' => 'en' ],
									'type' => 'object',
								],
								'body' => [
									'x-i18n-description' => 'rest-property-desc-transform-html-body',
									'example' => '<h2>Hello world</h2>',
									'type' => 'string',
								]
							]
						]
					]
				]
			],
		];
	}

	public function getRequestBodyDescription(): MessageValue|string|null {
		return new MessageValue( 'rest-requestbody-desc-transform-html' );
	}

	public function getRequestBodyExample( string $mediaType ): ?array {
		return [ 'html' => '<h2>Hello world</h2>' ];
	}
}
