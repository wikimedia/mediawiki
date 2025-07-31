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
class TransformWikitextToLintTitleHandler extends TransformHandler {

	/** @inheritDoc */
	public function getParamSettings() {
		return [
			'title' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-transform-title' ),
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
						'wikitext' => [
							'type' => 'string',
						]
					]
				],
				[
					'type' => 'object',
					'properties' => [
						'wikitext' => [
							'type' => 'object',
							'properties' => [
								'headers' => [
									'type' => 'object',
								],
								'body' => [
									'type' => 'string',
								]
							]
						]
					]
				]
			],
		];
	}
}
