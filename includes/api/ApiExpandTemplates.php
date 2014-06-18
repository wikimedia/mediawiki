<?php
/**
 *
 *
 * Created on Oct 05, 2007
 *
 * Copyright © 2007 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 *
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

/**
 * API module that functions as a shortcut to the wikitext preprocessor. Expands
 * any templates in a provided string, and returns the result of this expansion
 * to the caller.
 *
 * @ingroup API
 */
class ApiExpandTemplates extends ApiBase {

	public function execute() {
		// Cache may vary on $wgUser because ParserOptions gets data from it
		$this->getMain()->setCacheMode( 'anon-public-user-private' );

		// Get parameters
		$params = $this->extractRequestParams();
		$this->requireMaxOneParameter( $params, 'prop', 'generatexml' );

		if ( $params['prop'] === null ) {
			$this->setWarning( 'Because no values have been specified for the prop parameter, a legacy format has been used for the output.'
				 . ' This format is deprecated, and in the future, a default value will be set for the prop parameter, causing the new format to always be used.' );
			$prop = array();
		} else {
			$prop = array_flip( $params['prop'] );
		}

		// Create title for parser
		$title_obj = Title::newFromText( $params['title'] );
		if ( !$title_obj || $title_obj->isExternal() ) {
			$this->dieUsageMsg( array( 'invalidtitle', $params['title'] ) );
		}

		$result = $this->getResult();

		// Parse text
		global $wgParser;
		$options = ParserOptions::newFromContext( $this->getContext() );

		if ( $params['includecomments'] ) {
			$options->setRemoveComments( false );
		}

		$retval = array();

		if ( isset( $prop['parsetree'] ) || $params['generatexml'] ) {
			$wgParser->startExternalParse( $title_obj, $options, OT_PREPROCESS );
			$dom = $wgParser->preprocessToDom( $params['text'] );
			if ( is_callable( array( $dom, 'saveXML' ) ) ) {
				$xml = $dom->saveXML();
			} else {
				$xml = $dom->__toString();
			}
			if ( isset( $prop['parsetree'] ) ) {
				unset( $prop['parsetree'] );
				$retval['parsetree'] = $xml;
			} else {
				// the old way
				$xml_result = array();
				ApiResult::setContent( $xml_result, $xml );
				$result->addValue( null, 'parsetree', $xml_result );
			}
		}

		// if they didn't want any output except (probably) the parse tree,
		// then don't bother actually fully expanding it
		if ( $prop || $params['prop'] === null ) {
			$wgParser->startExternalParse( $title_obj, $options, OT_PREPROCESS );
			$frame = $wgParser->getPreprocessor()->newFrame();
			$wikitext = $wgParser->preprocess( $params['text'], $title_obj, $options, null, $frame );
			if ( $params['prop'] === null ) {
				// the old way
				ApiResult::setContent( $retval, $wikitext );
			} else {
				if ( isset( $prop['categories'] ) ) {
					$categories = $wgParser->getOutput()->getCategories();
					if ( !empty( $categories ) ) {
						$categories_result = array();
						foreach ( $categories as $category => $sortkey ) {
							$entry = array();
							$entry['sortkey'] = $sortkey;
							ApiResult::setContent( $entry, $category );
							$categories_result[] = $entry;
						}
						$result->setIndexedTagName( $categories_result, 'category' );
						$retval['categories'] = $categories_result;
					}
				}
				if ( isset( $prop['volatile'] ) && $frame->isVolatile() ) {
					$retval['volatile'] = '';
				}
				if ( isset( $prop['ttl'] ) && $frame->getTTL() !== null ) {
					$retval['ttl'] = $frame->getTTL();
				}
				if ( isset( $prop['wikitext'] ) ) {
					$retval['wikitext'] = $wikitext;
				}
			}
		}
		$result->setSubelements( $retval, array( 'wikitext', 'parsetree' ) );
		$result->addValue( null, $this->getModuleName(), $retval );
	}

	public function getAllowedParams() {
		return array(
			'title' => array(
				ApiBase::PARAM_DFLT => 'API',
			),
			'text' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true,
			),
			'prop' => array(
				ApiBase::PARAM_TYPE => array(
					'wikitext',
					'categories',
					'volatile',
					'ttl',
					'parsetree',
				),
				ApiBase::PARAM_ISMULTI => true,
			),
			'includecomments' => false,
			'generatexml' => array(
				ApiBase::PARAM_TYPE => 'boolean',
				ApiBase::PARAM_DEPRECATED => true,
			),
		);
	}

	public function getParamDescription() {
		return array(
			'text' => 'Wikitext to convert',
			'title' => 'Title of page',
			'prop' => array(
				'Which pieces of information to get',
				' wikitext   - The expanded wikitext',
				' categories - Any categories present in the input that are not represented in the wikitext output',
				' volatile   - Whether the output is volatile and should not be reused elsewhere within the page',
				' ttl        - The maximum time after which caches of the result should be invalidated',
				' parsetree  - The XML parse tree of the input',
				'Note that if no values are selected, the result will contain the wikitext,',
				'but the output will be in a deprecated format.',
			),
			'includecomments' => 'Whether to include HTML comments in the output',
			'generatexml' => 'Generate XML parse tree (replaced by prop=parsetree)',
		);
	}

	public function getResultProperties() {
		return array(
			'wikitext' => array(
				'wikitext' => 'string',
			),
			'categories' => array(
				'categories' => array(
					ApiBase::PROP_TYPE => 'array',
					ApiBase::PROP_NULLABLE => true,
				),
			),
			'volatile' => array(
				'volatile' => array(
					ApiBase::PROP_TYPE => 'boolean',
					ApiBase::PROP_NULLABLE => true,
				),
			),
			'ttl' => array(
				'ttl' => array(
					ApiBase::PROP_TYPE => 'integer',
					ApiBase::PROP_NULLABLE => true,
				),
			),
			'parsetree' => array(
				'parsetree' => 'string',
			),
		);
	}

	public function getDescription() {
		return 'Expands all templates in wikitext.';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'invalidtitle', 'title' ),
		) );
	}

	public function getExamples() {
		return array(
			'api.php?action=expandtemplates&text={{Project:Sandbox}}'
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Parsing_wikitext#expandtemplates';
	}
}
