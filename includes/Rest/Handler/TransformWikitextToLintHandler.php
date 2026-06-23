<?php

namespace MediaWiki\Rest\Handler;

use Wikimedia\Message\MessageValue;

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
class TransformWikitextToLintHandler extends TransformHandler {

	/** @inheritDoc */
	public function getParamSettings() {
		return [];
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
						'wikitext' => [
							'x-i18n-description' => 'rest-property-desc-transform-wikitext',
							'example' => '== Hello world ==',
							'type' => 'string',
						]
					]
				],
				[
					'type' => 'object',
					'properties' => [
						'wikitext' => [
							'x-i18n-description' => 'rest-property-desc-transform-wikitext-with-headers',
							'example' => [ 'body' => '== Hello world ==' ],
							'type' => 'object',
							'properties' => [
								'headers' => [
									'x-i18n-description' => 'rest-property-desc-transform-wikitext-headers',
									'example' => [ 'content-language' => 'en' ],
									'type' => 'object',
								],
								'body' => [
									'x-i18n-description' => 'rest-property-desc-transform-wikitext-body',
									'example' => '== Hello world ==',
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
		return new MessageValue( 'rest-requestbody-desc-transform-wikitext' );
	}

	public function getRequestBodyExample( string $mediaType ): ?array {
		return [ 'wikitext' => '== Hello world ==' ];
	}

	public function getResponseBodyExampleFileName( string $method ): ?string {
		return __DIR__ . '/Example/ContentLintErrors.json';
	}

	public function getResponseBodySchemaFileName( string $method ): ?string {
		return __DIR__ . '/Schema/ContentLintErrors.json';
	}
}
