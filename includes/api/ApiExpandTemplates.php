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

namespace MediaWiki\Api;

use MediaWiki\Json\FormatJson;
use MediaWiki\Parser\Parser;
use MediaWiki\Parser\ParserFactory;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Title\Title;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * API module that functions as a shortcut to the wikitext preprocessor. Expands
 * any templates in a provided string, and returns the result of this expansion
 * to the caller.
 *
 * @ingroup API
 */
class ApiExpandTemplates extends ApiBase {
	private RevisionStore $revisionStore;
	private ParserFactory $parserFactory;

	public function __construct(
		ApiMain $main,
		string $action,
		RevisionStore $revisionStore,
		ParserFactory $parserFactory
	) {
		parent::__construct( $main, $action );
		$this->revisionStore = $revisionStore;
		$this->parserFactory = $parserFactory;
	}

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
			$prop = array_fill_keys( $params['prop'], true );
		}

		$titleObj = Title::newFromText( $title );
		if ( !$titleObj || $titleObj->isExternal() ) {
			$this->dieWithError( [ 'apierror-invalidtitle', wfEscapeWikiText( $params['title'] ) ] );
		}

		// Get title and revision ID for parser
		$revid = $params['revid'];
		if ( $revid !== null ) {
			$rev = $this->revisionStore->getRevisionById( $revid );
			if ( !$rev ) {
				$this->dieWithError( [ 'apierror-nosuchrevid', $revid ] );
			}
			$pTitleObj = $titleObj;
			$titleObj = Title::newFromPageIdentity( $rev->getPage() );
			if ( $titleProvided && !$titleObj->equals( $pTitleObj ) ) {
				$this->addWarning( [ 'apierror-revwrongpage', $rev->getId(),
					wfEscapeWikiText( $pTitleObj->getPrefixedText() ) ] );
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
		$this->getHookRunner()->onApiMakeParserOptions(
			$options, $titleObj, $params, $this, $reset, $suppressCache );

		$retval = [];

		if ( isset( $prop['parsetree'] ) || $params['generatexml'] ) {
			$parser = $this->parserFactory->getInstance();
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
			$parser = $this->parserFactory->getInstance();
			$parser->startExternalParse( $titleObj, $options, Parser::OT_PREPROCESS );
			$frame = $parser->getPreprocessor()->newFrame();
			$wikitext = $parser->preprocess( $params['text'], $titleObj, $options, $revid, $frame );
			if ( $params['prop'] === null ) {
				// the old way
				ApiResult::setContentValue( $retval, 'wikitext', $wikitext );
			} else {
				$p_output = $parser->getOutput();
				if ( isset( $prop['categories'] ) ) {
					$categories = $p_output->getCategoryNames();
					if ( $categories ) {
						$defaultSortKey = $p_output->getPageProperty( 'defaultsort' ) ?? '';
						$categories_result = [];
						foreach ( $categories as $category ) {
							$entry = [
								// Note that ::getCategorySortKey() returns
								// the empty string '' to mean
								// "use the default sort key"
								'sortkey' => $p_output->getCategorySortKey( $category ) ?: $defaultSortKey,
							];
							ApiResult::setContentValue( $entry, 'category', $category );
							$categories_result[] = $entry;
						}
						ApiResult::setIndexedTagName( $categories_result, 'category' );
						$retval['categories'] = $categories_result;
					}
				}
				if ( isset( $prop['properties'] ) ) {
					$properties = $p_output->getPageProperties();
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
					$showStrategyKeys = (bool)( $params['showstrategykeys'] );
					$retval['jsconfigvars'] =
						ApiResult::addMetadataToResultVars( $p_output->getJsConfigVars( $showStrategyKeys ) );
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

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'title' => null,
			'text' => [
				ParamValidator::PARAM_TYPE => 'text',
				ParamValidator::PARAM_REQUIRED => true,
			],
			'revid' => [
				ParamValidator::PARAM_TYPE => 'integer',
			],
			'prop' => [
				ParamValidator::PARAM_TYPE => [
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
				ParamValidator::PARAM_ISMULTI => true,
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'includecomments' => false,
			'showstrategykeys' => false,
			'generatexml' => [
				ParamValidator::PARAM_TYPE => 'boolean',
				ParamValidator::PARAM_DEPRECATED => true,
			],
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=expandtemplates&text={{Project:Sandbox}}'
				=> 'apihelp-expandtemplates-example-simple',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Expandtemplates';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiExpandTemplates::class, 'ApiExpandTemplates' );
