<?php
/**
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

use MediaWiki\MediaWikiServices;

/**
 * API module that functions as a shortcut to the wikitext preprocessor. Expands
 * any templates in a provided string, and returns the result of this expansion
 * to the caller.
 *
 * @ingroup API
 */
class ApiExpandTemplates extends ApiBase {

	public function execute() {
		// Cache may vary on the user because ParserOptions gets data from it
		$this->getMain()->setCacheMode( 'anon-public-user-private' );

		// Get parameters
		$params = $this->extractRequestParams();
		$this->requireMaxOneParameter( $params, 'prop', 'generatexml' );

		$title = $params['title'];
		if ( $title === null ) {
			$titleProvided = false;
			// A title is needed for parsing, so arbitrarily choose one
			$title = 'API';
		} else {
			$titleProvided = true;
		}

		if ( $params['prop'] === null ) {
			$this->addDeprecation(
				[ 'apiwarn-deprecation-missingparam', 'prop' ], 'action=expandtemplates&!prop'
			);
			$prop = [];
		} else {
			$prop = array_flip( $params['prop'] );
		}

		$titleObj = Title::newFromText( $title );
		if ( !$titleObj || $titleObj->isExternal() ) {
			$this->dieWithError( [ 'apierror-invalidtitle', wfEscapeWikiText( $params['title'] ) ] );
		}

		// Get title and revision ID for parser
		$revid = $params['revid'];
		if ( $revid !== null ) {
			$rev = MediaWikiServices::getInstance()->getRevisionStore()->getRevisionById( $revid );
			if ( !$rev ) {
				$this->dieWithError( [ 'apierror-nosuchrevid', $revid ] );
			}
			$pTitleObj = $titleObj;
			$titleObj = Title::newFromLinkTarget( $rev->getPageAsLinkTarget() );
			if ( $titleProvided ) {
				if ( !$titleObj->equals( $pTitleObj ) ) {
					$this->addWarning( [ 'apierror-revwrongpage', $rev->getId(),
						wfEscapeWikiText( $pTitleObj->getPrefixedText() ) ] );
				}
			}
		}

		$result = $this->getResult();

		// Parse text
		$options = ParserOptions::newFromContext( $this->getContext() );

		if ( $params['includecomments'] ) {
			$options->setRemoveComments( false );
		}

		$reset = null;
		$suppressCache = false;
		Hooks::run( 'ApiMakeParserOptions',
			[ $options, $titleObj, $params, $this, &$reset, &$suppressCache ] );

		$retval = [];

		$parser = MediaWikiServices::getInstance()->getParser();
		if ( isset( $prop['parsetree'] ) || $params['generatexml'] ) {
			$parser->startExternalParse( $titleObj, $options, Parser::OT_PREPROCESS );
			$dom = $parser->preprocessToDom( $params['text'] );
			// @phan-suppress-next-line PhanUndeclaredMethodInCallable
			if ( is_callable( [ $dom, 'saveXML' ] ) ) {
				// @phan-suppress-next-line PhanUndeclaredMethod
				$xml = $dom->saveXML();
			} else {
				// @phan-suppress-next-line PhanUndeclaredMethod
				$xml = $dom->__toString();
			}
			if ( isset( $prop['parsetree'] ) ) {
				unset( $prop['parsetree'] );
				$retval['parsetree'] = $xml;
			} else {
				// the old way
				$result->addValue( null, 'parsetree', $xml );
				$result->addValue( null, ApiResult::META_BC_SUBELEMENTS, [ 'parsetree' ] );
			}
		}

		// if they didn't want any output except (probably) the parse tree,
		// then don't bother actually fully expanding it
		if ( $prop || $params['prop'] === null ) {
			$parser->startExternalParse( $titleObj, $options, Parser::OT_PREPROCESS );
			$frame = $parser->getPreprocessor()->newFrame();
			$wikitext = $parser->preprocess( $params['text'], $titleObj, $options, $revid, $frame );
			if ( $params['prop'] === null ) {
				// the old way
				ApiResult::setContentValue( $retval, 'wikitext', $wikitext );
			} else {
				$p_output = $parser->getOutput();
				if ( isset( $prop['categories'] ) ) {
					$categories = $p_output->getCategories();
					if ( $categories ) {
						$categories_result = [];
						foreach ( $categories as $category => $sortkey ) {
							$entry = [];
							$entry['sortkey'] = $sortkey;
							ApiResult::setContentValue( $entry, 'category', (string)$category );
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
					// Deprecated since 1.32 (T188689)
					$retval['modulescripts'] = [];
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
					$this->addWarning( 'apiwarn-moduleswithoutvars' );
				}
			}
		}
		ApiResult::setSubelementsList( $retval, [ 'wikitext', 'parsetree' ] );
		$result->addValue( null, $this->getModuleName(), $retval );
	}

	public function getAllowedParams() {
		return [
			'title' => null,
			'text' => [
				ApiBase::PARAM_TYPE => 'text',
				ApiBase::PARAM_REQUIRED => true,
			],
			'revid' => [
				ApiBase::PARAM_TYPE => 'integer',
			],
			'prop' => [
				ApiBase::PARAM_TYPE => [
					'wikitext',
					'categories',
					'properties',
					'volatile',
					'ttl',
					'modules',
					'jsconfigvars',
					'encodedjsconfigvars',
					'parsetree',
				],
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'includecomments' => false,
			'generatexml' => [
				ApiBase::PARAM_TYPE => 'boolean',
				ApiBase::PARAM_DEPRECATED => true,
			],
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=expandtemplates&text={{Project:Sandbox}}'
				=> 'apihelp-expandtemplates-example-simple',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Parsing_wikitext#expandtemplates';
	}
}
