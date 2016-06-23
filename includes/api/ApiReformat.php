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
 * @since 1.28
 */

/**
 * API action for conversion between two different formats of the same content model
 *
 * @ingroup API
 */
class ApiReformat extends ApiBase {

	/**
	 * Purges the cache of a page
	 */
	public function execute() {
		$params = $this->extractRequestParams();
		$contentHandler = ContentHandler::getForModelID( $params['contentmodel'] );
		$inputContentFormat = $this->cleanInputContentFormat(
			$params['inputcontentformat'],
			$contentHandler
		);
		$outputContentFormat = $this->cleanOutputContentFormat(
			$params['outputcontentformat'],
			$contentHandler
		);

		try {
			$content = $contentHandler->unserializeContent( $params['text'], $inputContentFormat );
		} catch ( MWContentSerializationException $ex ) {
			$this->dieUsage( $ex->getMessage(), 'parseerror' );
		}

		if ( !$content->isValid() ) {
			$this->dieUsage( 'The provided text is an invalid ' . $content->getModel() . ' content.', 'badcontent' );
		}

		$serialization = $contentHandler->serializeContent( $content, $outputContentFormat );
		$apiResult = $this->getResult();
		$apiResult->addValue( null, $this->getModuleName(), [
			'text' => $serialization,
			'contentmodel' => $contentHandler->getModelID(),
			'contentformat' => $outputContentFormat
		] );
	}

	private function cleanInputContentFormat( $format, ContentHandler $contentHandler ) {
		if ( $format !== null && !$contentHandler->isSupportedFormat( $format ) ) {
			$this->dieUsage(
				"Input format $format is not supported for content model " . $contentHandler->getModelID(),
				'badformat_inputcontentmodel'
			);
		}
		return $format;
	}

	private function cleanOutputContentFormat( $format, ContentHandler $contentHandler ) {
		if ( $format === null ) {
			$format = $contentHandler->getDefaultFormat();
		}
		if ( !$contentHandler->isSupportedFormat( $format ) ) {
			$this->dieUsage(
				"Output format $format is not supported for content model " . $contentHandler->getModelID(),
				'badformat_outputcontentmodel'
			);
		}
		return $format;
	}

	public function getAllowedParams() {
		return [
			'text' => [
				ApiBase::PARAM_TYPE => 'text',
				ApiBase::PARAM_REQUIRED => true
			],
			'contentmodel' => [
				ApiBase::PARAM_TYPE => ContentHandler::getContentModels(),
				ApiBase::PARAM_REQUIRED => true
			],
			'inputcontentformat' => [
				ApiBase::PARAM_TYPE => ContentHandler::getAllContentFormats()
			],
			'outputcontentformat' => [
				ApiBase::PARAM_TYPE => ContentHandler::getAllContentFormats()
			]
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=reformat&text={"a":"b","c":"d"}&contentmodel=structured' .
				'&inputcontentformat=application/json&outputcontentformat=text/x-yaml'
					=> 'apihelp-reformat-example-structured'
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Reformat';
	}
}
