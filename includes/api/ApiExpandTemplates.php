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
			$this->logFeatureUsage( 'action=expandtemplates&!prop' );
			$this->setWarning( 'Because no values have been specified for the prop parameter, a ' .
				'legacy format has been used for the output. This format is deprecated, and in ' .
				'the future, a default value will be set for the prop parameter, causing the new' .
				'format to always be used.' );
			$prop = array();
		} else {
			$prop = array_flip( $params['prop'] );
		}

		// Get title and revision ID for parser
		$revid = $params['revid'];
		if ( $revid !== null ) {
			$rev = Revision::newFromId( $revid );
			if ( !$rev ) {
				$this->dieUsage( "There is no revision ID $revid", 'missingrev' );
			}
			$title_obj = $rev->getTitle();
		} else {
			$title_obj = Title::newFromText( $params['title'] );
			if ( !$title_obj || $title_obj->isExternal() ) {
				$this->dieUsageMsg( array( 'invalidtitle', $params['title'] ) );
			}
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
			if ( !isset( $prop['parsetree'] ) ) {
				$this->logFeatureUsage( 'action=expandtemplates&generatexml' );
			}

			$wgParser->startExternalParse( $title_obj, $options, Parser::OT_PREPROCESS );
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
				$result->addValue( null, 'parsetree', $xml );
				$result->addValue( null, ApiResult::META_BC_SUBELEMENTS, array( 'parsetree' ) );
			}
		}

		// if they didn't want any output except (probably) the parse tree,
		// then don't bother actually fully expanding it
		if ( $prop || $params['prop'] === null ) {
			$wgParser->startExternalParse( $title_obj, $options, Parser::OT_PREPROCESS );
			$frame = $wgParser->getPreprocessor()->newFrame();
			$wikitext = $wgParser->preprocess( $params['text'], $title_obj, $options, $revid, $frame );
			if ( $params['prop'] === null ) {
				// the old way
				ApiResult::setContentValue( $retval, 'wikitext', $wikitext );
			} else {
				$p_output = $wgParser->getOutput();
				if ( isset( $prop['categories'] ) ) {
					$categories = $p_output->getCategories();
					if ( $categories ) {
						$categories_result = array();
						foreach ( $categories as $category => $sortkey ) {
							$entry = array();
							$entry['sortkey'] = $sortkey;
							ApiResult::setContentValue( $entry, 'category', $category );
							$categories_result[] = $entry;
						}
						ApiResult::setIndexedTagName( $categories_result, 'category' );
						$retval['categories'] = $categories_result;
					}
				}
				if ( isset( $prop['properties'] ) ) {
					$properties = $p_output->getProperties();
					if ( $properties ) {
						ApiResult::setArrayType( $properties, 'BCkvp', 'name' );
						ApiResult::setIndexedTagName( $properties, 'property' );
						$retval['properties'] = $properties;
					}
				}
				if ( isset( $prop['volatile'] ) ) {
					$retval['volatile'] = $frame->isVolatile();
				}
				if ( isset( $prop['ttl'] ) && $frame->getTTL() !== null ) {
					$retval['ttl'] = $frame->getTTL();
				}
				if ( isset( $prop['wikitext'] ) ) {
					$retval['wikitext'] = $wikitext;
				}
				if ( isset( $prop['modules'] ) ) {
					$retval['modules'] = array_values( array_unique( $p_output->getModules() ) );
					$retval['modulescripts'] = array_values( array_unique( $p_output->getModuleScripts() ) );
					$retval['modulestyles'] = array_values( array_unique( $p_output->getModuleStyles() ) );
				}
				if ( isset( $prop['jsconfigvars'] ) ) {
					$retval['jsconfigvars'] =
						ApiResult::addMetadataToResultVars( $p_output->getJsConfigVars() );
				}
				if ( isset( $prop['encodedjsconfigvars'] ) ) {
					$retval['encodedjsconfigvars'] = FormatJson::encode(
						$p_output->getJsConfigVars(), false, FormatJson::ALL_OK
					);
					$retval[ApiResult::META_SUBELEMENTS][] = 'encodedjsconfigvars';
				}
				if ( isset( $prop['modules'] ) &&
					!isset( $prop['jsconfigvars'] ) && !isset( $prop['encodedjsconfigvars'] ) ) {
					$this->setWarning( "Property 'modules' was set but not 'jsconfigvars' " .
						"or 'encodedjsconfigvars'. Configuration variables are necessary " .
						"for proper module usage." );
				}
			}
		}
		ApiResult::setSubelementsList( $retval, array( 'wikitext', 'parsetree' ) );
		$result->addValue( null, $this->getModuleName(), $retval );
	}

	public function getAllowedParams() {
		return array(
			'title' => array(
				ApiBase::PARAM_DFLT => 'API',
			),
			'text' => array(
				ApiBase::PARAM_TYPE => 'text',
				ApiBase::PARAM_REQUIRED => true,
			),
			'revid' => array(
				ApiBase::PARAM_TYPE => 'integer',
			),
			'prop' => array(
				ApiBase::PARAM_TYPE => array(
					'wikitext',
					'categories',
					'properties',
					'volatile',
					'ttl',
					'modules',
					'jsconfigvars',
					'encodedjsconfigvars',
					'parsetree',
				),
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_HELP_MSG_PER_VALUE => array(),
			),
			'includecomments' => false,
			'generatexml' => array(
				ApiBase::PARAM_TYPE => 'boolean',
				ApiBase::PARAM_DEPRECATED => true,
			),
		);
	}

	protected function getExamplesMessages() {
		return array(
			'action=expandtemplates&text={{Project:Sandbox}}'
				=> 'apihelp-expandtemplates-example-simple',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Parsing_wikitext#expandtemplates';
	}
}
