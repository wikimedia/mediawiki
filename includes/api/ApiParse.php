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

use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;

/**
 * @ingroup API
 */
class ApiParse extends ApiBase {

	/** @var string|false|null */
	private $section = null;

	/** @var Content */
	private $content = null;

	/** @var Content */
	private $pstContent = null;

	/** @var bool */
	private $contentIsDeleted = false, $contentIsSuppressed = false;

	private function getPoolKey(): string {
		$poolKey = WikiMap::getCurrentWikiDbDomain() . ':ApiParse:';
		if ( $this->getUser()->isAnon() ) {
			$poolKey .= 'a:' . $this->getUser()->getName();
		} else {
			$poolKey .= 'u:' . $this->getUser()->getId();
		}
		return $poolKey;
	}

	private function getContentParserOutput(
		Content $content,
		Title $title,
		$revId,
		ParserOptions $popts
	) {
		$worker = new PoolCounterWorkViaCallback( 'ApiParser', $this->getPoolKey(),
			[
				'doWork' => function () use ( $content, $title, $revId, $popts ) {
					return $content->getParserOutput( $title, $revId, $popts );
				},
				'error' => function () {
					$this->dieWithError( 'apierror-concurrency-limit' );
				},
			]
		);
		return $worker->execute();
	}

	private function getPageParserOutput(
		WikiPage $page,
		$revId,
		ParserOptions $popts,
		bool $suppressCache
	) {
		$worker = new PoolCounterWorkViaCallback( 'ApiParser', $this->getPoolKey(),
			[
				'doWork' => function () use ( $page, $revId, $popts, $suppressCache ) {
					return $page->getParserOutput( $popts, $revId, $suppressCache );
				},
				'error' => function () {
					$this->dieWithError( 'apierror-concurrency-limit' );
				},
			]
		);
		return $worker->execute();
	}

	public function execute() {
		// The data is hot but user-dependent, like page views, so we set vary cookies
		$this->getMain()->setCacheMode( 'anon-public-user-private' );

		// Get parameters
		$params = $this->extractRequestParams();

		// No easy way to say that text and title or revid are allowed together
		// while the rest aren't, so just do it in three calls.
		$this->requireMaxOneParameter( $params, 'page', 'pageid', 'oldid', 'text' );
		$this->requireMaxOneParameter( $params, 'page', 'pageid', 'oldid', 'title' );
		$this->requireMaxOneParameter( $params, 'page', 'pageid', 'oldid', 'revid' );

		$text = $params['text'];
		$title = $params['title'];
		if ( $title === null ) {
			$titleProvided = false;
			// A title is needed for parsing, so arbitrarily choose one
			$title = 'API';
		} else {
			$titleProvided = true;
		}

		$page = $params['page'];
		$pageid = $params['pageid'];
		$oldid = $params['oldid'];

		$model = $params['contentmodel'];
		$format = $params['contentformat'];

		$prop = array_flip( $params['prop'] );

		if ( isset( $params['section'] ) ) {
			$this->section = $params['section'];
			if ( !preg_match( '/^((T-)?\d+|new)$/', $this->section ) ) {
				$this->dieWithError( 'apierror-invalidsection' );
			}
		} else {
			$this->section = false;
		}

		// The parser needs $wgTitle to be set, apparently the
		// $title parameter in Parser::parse isn't enough *sigh*
		// TODO: Does this still need $wgTitle?
		global $wgTitle;

		$redirValues = null;

		$needContent = isset( $prop['wikitext'] ) ||
			isset( $prop['parsetree'] ) || $params['generatexml'];

		// Return result
		$result = $this->getResult();

		$revisionLookup = MediaWikiServices::getInstance()->getRevisionLookup();
		if ( $oldid !== null || $pageid !== null || $page !== null ) {
			if ( $this->section === 'new' ) {
				$this->dieWithError( 'apierror-invalidparammix-parse-new-section', 'invalidparammix' );
			}
			if ( $oldid !== null ) {
				// Don't use the parser cache
				$rev = $revisionLookup->getRevisionById( $oldid );
				if ( !$rev ) {
					$this->dieWithError( [ 'apierror-nosuchrevid', $oldid ] );
				}

				$revLinkTarget = $rev->getPageAsLinkTarget();
				$this->checkTitleUserPermissions( $revLinkTarget, 'read' );

				if ( !$rev->audienceCan(
					RevisionRecord::DELETED_TEXT,
					RevisionRecord::FOR_THIS_USER,
					$this->getUser()
				) ) {
					$this->dieWithError(
						[ 'apierror-permissiondenied', $this->msg( 'action-deletedtext' ) ]
					);
				}

				$titleObj = Title::newFromLinkTarget( $revLinkTarget );
				$wgTitle = $titleObj;
				$pageObj = WikiPage::factory( $titleObj );
				list( $popts, $reset, $suppressCache ) = $this->makeParserOptions( $pageObj, $params );
				$p_result = $this->getParsedContent(
					$pageObj, $popts, $suppressCache, $pageid, $rev, $needContent
				);
			} else { // Not $oldid, but $pageid or $page
				if ( $params['redirects'] ) {
					$reqParams = [
						'redirects' => '',
					];
					$pageParams = [];
					if ( $pageid !== null ) {
						$reqParams['pageids'] = $pageid;
						$pageParams['pageid'] = $pageid;
					} else { // $page
						$reqParams['titles'] = $page;
						$pageParams['title'] = $page;
					}
					$req = new FauxRequest( $reqParams );
					$main = new ApiMain( $req );
					$pageSet = new ApiPageSet( $main );
					$pageSet->execute();
					$redirValues = $pageSet->getRedirectTitlesAsResult( $this->getResult() );

					foreach ( $pageSet->getRedirectTitles() as $title ) {
						$pageParams = [ 'title' => $title->getFullText() ];
					}
				} elseif ( $pageid !== null ) {
					$pageParams = [ 'pageid' => $pageid ];
				} else { // $page
					$pageParams = [ 'title' => $page ];
				}

				$pageObj = $this->getTitleOrPageId( $pageParams, 'fromdb' );
				$titleObj = $pageObj->getTitle();
				if ( !$titleObj || !$titleObj->exists() ) {
					$this->dieWithError( 'apierror-missingtitle' );
				}

				$this->checkTitleUserPermissions( $titleObj, 'read' );
				$wgTitle = $titleObj;

				if ( isset( $prop['revid'] ) ) {
					$oldid = $pageObj->getLatest();
				}

				list( $popts, $reset, $suppressCache ) = $this->makeParserOptions( $pageObj, $params );
				$p_result = $this->getParsedContent(
					$pageObj, $popts, $suppressCache, $pageid, null, $needContent
				);
			}
		} else { // Not $oldid, $pageid, $page. Hence based on $text
			$titleObj = Title::newFromText( $title );
			if ( !$titleObj || $titleObj->isExternal() ) {
				$this->dieWithError( [ 'apierror-invalidtitle', wfEscapeWikiText( $title ) ] );
			}
			$revid = $params['revid'];
			if ( $revid !== null ) {
				$rev = $revisionLookup->getRevisionById( $revid );
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
				} else {
					// Consider the title derived from the revid as having
					// been provided.
					$titleProvided = true;
				}
			}
			$wgTitle = $titleObj;
			if ( $titleObj->canExist() ) {
				$pageObj = WikiPage::factory( $titleObj );
			} else {
				// Do like MediaWiki::initializeArticle()
				$article = Article::newFromTitle( $titleObj, $this->getContext() );
				$pageObj = $article->getPage();
			}

			list( $popts, $reset ) = $this->makeParserOptions( $pageObj, $params );
			$textProvided = $text !== null;

			if ( !$textProvided ) {
				if ( $titleProvided && ( $prop || $params['generatexml'] ) ) {
					if ( $revid !== null ) {
						$this->addWarning( 'apiwarn-parse-revidwithouttext' );
					} else {
						$this->addWarning( 'apiwarn-parse-titlewithouttext' );
					}
				}
				// Prevent warning from ContentHandler::makeContent()
				$text = '';
			}

			// If we are parsing text, do not use the content model of the default
			// API title, but default to wikitext to keep BC.
			if ( $textProvided && !$titleProvided && $model === null ) {
				$model = CONTENT_MODEL_WIKITEXT;
				$this->addWarning( [ 'apiwarn-parse-nocontentmodel', $model ] );
			}

			try {
				$this->content = ContentHandler::makeContent( $text, $titleObj, $model, $format );
			} catch ( MWContentSerializationException $ex ) {
				$this->dieWithException( $ex, [
					'wrap' => ApiMessage::create( 'apierror-contentserializationexception', 'parseerror' )
				] );
			}

			if ( $this->section !== false ) {
				if ( $this->section === 'new' ) {
					// Insert the section title above the content.
					if ( $params['sectiontitle'] !== null && $params['sectiontitle'] !== '' ) {
						$this->content = $this->content->addSectionHeader( $params['sectiontitle'] );
					}
				} else {
					$this->content = $this->getSectionContent( $this->content, $titleObj->getPrefixedText() );
				}
			}

			if ( $params['pst'] || $params['onlypst'] ) {
				$this->pstContent = $this->content->preSaveTransform( $titleObj, $this->getUser(), $popts );
			}
			if ( $params['onlypst'] ) {
				// Build a result and bail out
				$result_array = [];
				$result_array['text'] = $this->pstContent->serialize( $format );
				$result_array[ApiResult::META_BC_SUBELEMENTS][] = 'text';
				if ( isset( $prop['wikitext'] ) ) {
					$result_array['wikitext'] = $this->content->serialize( $format );
					$result_array[ApiResult::META_BC_SUBELEMENTS][] = 'wikitext';
				}
				if ( $params['summary'] !== null ||
					( $params['sectiontitle'] !== null && $this->section === 'new' )
				) {
					$result_array['parsedsummary'] = $this->formatSummary( $titleObj, $params );
					$result_array[ApiResult::META_BC_SUBELEMENTS][] = 'parsedsummary';
				}

				$result->addValue( null, $this->getModuleName(), $result_array );

				return;
			}

			// Not cached (save or load)
			if ( $params['pst'] ) {
				$p_result = $this->getContentParserOutput( $this->pstContent, $titleObj, $revid, $popts );
			} else {
				$p_result = $this->getContentParserOutput( $this->content, $titleObj, $revid, $popts );
			}
		}

		$result_array = [];

		$result_array['title'] = $titleObj->getPrefixedText();
		$result_array['pageid'] = $pageid ?: $pageObj->getId();
		if ( $this->contentIsDeleted ) {
			$result_array['textdeleted'] = true;
		}
		if ( $this->contentIsSuppressed ) {
			$result_array['textsuppressed'] = true;
		}

		if ( isset( $params['useskin'] ) ) {
			$factory = MediaWikiServices::getInstance()->getSkinFactory();
			$skin = $factory->makeSkin( Skin::normalizeKey( $params['useskin'] ) );
		} else {
			$skin = null;
		}

		$outputPage = null;
		$context = null;
		if ( $skin || isset( $prop['headhtml'] ) || isset( $prop['categorieshtml'] ) ) {
			// Enabling the skin via 'useskin', 'headhtml', or 'categorieshtml'
			// gets OutputPage and Skin involved, which (among others) applies
			// these hooks:
			// - ParserOutputHooks
			// - Hook: LanguageLinks
			// - Hook: OutputPageParserOutput
			// - Hook: OutputPageMakeCategoryLinks
			$context = new DerivativeContext( $this->getContext() );
			$context->setTitle( $titleObj );
			$context->setWikiPage( $pageObj );

			if ( $skin ) {
				// Use the skin specified by 'useskin'
				$context->setSkin( $skin );
				// Context clones the skin, refetch to stay in sync. (T166022)
				$skin = $context->getSkin();
			} else {
				// Make sure the context's skin refers to the context. Without this,
				// $outputPage->getSkin()->getOutput() !== $outputPage which
				// confuses some of the output.
				$context->setSkin( $context->getSkin() );
			}

			$outputPage = new OutputPage( $context );
			$outputPage->addParserOutputMetadata( $p_result );
			if ( $this->content ) {
				$outputPage->addContentOverride( $titleObj, $this->content );
			}
			$context->setOutput( $outputPage );

			if ( $skin ) {
				// Based on OutputPage::output()
				$outputPage->loadSkinModules( $skin );
			}

			$this->getHookRunner()->onApiParseMakeOutputPage( $this, $outputPage );
		}

		if ( $oldid !== null ) {
			$result_array['revid'] = (int)$oldid;
		}

		if ( $params['redirects'] && $redirValues !== null ) {
			$result_array['redirects'] = $redirValues;
		}

		if ( isset( $prop['text'] ) ) {
			$result_array['text'] = $p_result->getText( [
				'allowTOC' => !$params['disabletoc'],
				'enableSectionEditLinks' => !$params['disableeditsection'],
				'wrapperDivClass' => $params['wrapoutputclass'],
				'deduplicateStyles' => !$params['disablestylededuplication'],
				'skin' => $context ? $context->getSkin() : null,
			] );
			$result_array[ApiResult::META_BC_SUBELEMENTS][] = 'text';
		}

		if ( $params['summary'] !== null ||
			( $params['sectiontitle'] !== null && $this->section === 'new' )
		) {
			$result_array['parsedsummary'] = $this->formatSummary( $titleObj, $params );
			$result_array[ApiResult::META_BC_SUBELEMENTS][] = 'parsedsummary';
		}

		if ( isset( $prop['langlinks'] ) ) {
			if ( $skin ) {
				$langlinks = $outputPage->getLanguageLinks();
			} else {
				$langlinks = $p_result->getLanguageLinks();
				// The deprecated 'effectivelanglinks' option depredates OutputPage
				// support via 'useskin'. If not already applied, then run just this
				// one hook of OutputPage::addParserOutputMetadata here.
				if ( $params['effectivelanglinks'] ) {
					$linkFlags = [];
					$this->getHookRunner()->onLanguageLinks( $titleObj, $langlinks, $linkFlags );
				}
			}

			$result_array['langlinks'] = $this->formatLangLinks( $langlinks );
		}
		if ( isset( $prop['categories'] ) ) {
			$result_array['categories'] = $this->formatCategoryLinks( $p_result->getCategories() );
		}
		if ( isset( $prop['categorieshtml'] ) ) {
			$result_array['categorieshtml'] = $outputPage->getSkin()->getCategories();
			$result_array[ApiResult::META_BC_SUBELEMENTS][] = 'categorieshtml';
		}
		if ( isset( $prop['links'] ) ) {
			$result_array['links'] = $this->formatLinks( $p_result->getLinks() );
		}
		if ( isset( $prop['templates'] ) ) {
			$result_array['templates'] = $this->formatLinks( $p_result->getTemplates() );
		}
		if ( isset( $prop['images'] ) ) {
			$result_array['images'] = array_keys( $p_result->getImages() );
		}
		if ( isset( $prop['externallinks'] ) ) {
			$result_array['externallinks'] = array_keys( $p_result->getExternalLinks() );
		}
		if ( isset( $prop['sections'] ) ) {
			$result_array['sections'] = $p_result->getSections();
		}
		if ( isset( $prop['parsewarnings'] ) ) {
			$result_array['parsewarnings'] = $p_result->getWarnings();
		}

		if ( isset( $prop['displaytitle'] ) ) {
			$result_array['displaytitle'] = $p_result->getDisplayTitle() !== false
				? $p_result->getDisplayTitle() : $titleObj->getPrefixedText();
		}

		if ( isset( $prop['headitems'] ) ) {
			if ( $skin ) {
				$result_array['headitems'] = $this->formatHeadItems( $outputPage->getHeadItemsArray() );
			} else {
				$result_array['headitems'] = $this->formatHeadItems( $p_result->getHeadItems() );
			}
		}

		if ( isset( $prop['headhtml'] ) ) {
			$result_array['headhtml'] = $outputPage->headElement( $context->getSkin() );
			$result_array[ApiResult::META_BC_SUBELEMENTS][] = 'headhtml';
		}

		if ( isset( $prop['modules'] ) ) {
			if ( $skin ) {
				$result_array['modules'] = $outputPage->getModules();
				// Deprecated since 1.32 (T188689)
				$result_array['modulescripts'] = [];
				$result_array['modulestyles'] = $outputPage->getModuleStyles();
			} else {
				$result_array['modules'] = array_values( array_unique( $p_result->getModules() ) );
				// Deprecated since 1.32 (T188689)
				$result_array['modulescripts'] = [];
				$result_array['modulestyles'] = array_values( array_unique( $p_result->getModuleStyles() ) );
			}
		}

		if ( isset( $prop['jsconfigvars'] ) ) {
			$jsconfigvars = $skin ? $outputPage->getJsConfigVars() : $p_result->getJsConfigVars();
			$result_array['jsconfigvars'] = ApiResult::addMetadataToResultVars( $jsconfigvars );
		}

		if ( isset( $prop['encodedjsconfigvars'] ) ) {
			$jsconfigvars = $skin ? $outputPage->getJsConfigVars() : $p_result->getJsConfigVars();
			$result_array['encodedjsconfigvars'] = FormatJson::encode(
				$jsconfigvars,
				false,
				FormatJson::ALL_OK
			);
			$result_array[ApiResult::META_SUBELEMENTS][] = 'encodedjsconfigvars';
		}

		if ( isset( $prop['modules'] ) &&
			!isset( $prop['jsconfigvars'] ) && !isset( $prop['encodedjsconfigvars'] ) ) {
			$this->addWarning( 'apiwarn-moduleswithoutvars' );
		}

		if ( isset( $prop['indicators'] ) ) {
			if ( $skin ) {
				$result_array['indicators'] = (array)$outputPage->getIndicators();
			} else {
				$result_array['indicators'] = (array)$p_result->getIndicators();
			}
			ApiResult::setArrayType( $result_array['indicators'], 'BCkvp', 'name' );
		}

		if ( isset( $prop['iwlinks'] ) ) {
			$result_array['iwlinks'] = $this->formatIWLinks( $p_result->getInterwikiLinks() );
		}

		if ( isset( $prop['wikitext'] ) ) {
			$result_array['wikitext'] = $this->content->serialize( $format );
			$result_array[ApiResult::META_BC_SUBELEMENTS][] = 'wikitext';
			if ( $this->pstContent !== null ) {
				$result_array['psttext'] = $this->pstContent->serialize( $format );
				$result_array[ApiResult::META_BC_SUBELEMENTS][] = 'psttext';
			}
		}
		if ( isset( $prop['properties'] ) ) {
			$result_array['properties'] = (array)$p_result->getProperties();
			ApiResult::setArrayType( $result_array['properties'], 'BCkvp', 'name' );
		}

		if ( isset( $prop['limitreportdata'] ) ) {
			$result_array['limitreportdata'] =
				$this->formatLimitReportData( $p_result->getLimitReportData() );
		}
		if ( isset( $prop['limitreporthtml'] ) ) {
			$result_array['limitreporthtml'] = EditPage::getPreviewLimitReport( $p_result );
			$result_array[ApiResult::META_BC_SUBELEMENTS][] = 'limitreporthtml';
		}

		if ( isset( $prop['parsetree'] ) || $params['generatexml'] ) {
			if ( $this->content->getModel() != CONTENT_MODEL_WIKITEXT ) {
				$this->dieWithError( 'apierror-parsetree-notwikitext', 'notwikitext' );
			}

			$parser = MediaWikiServices::getInstance()->getParser();
			$parser->startExternalParse( $titleObj, $popts, Parser::OT_PREPROCESS );
			// @phan-suppress-next-line PhanUndeclaredMethod
			$xml = $parser->preprocessToDom( $this->content->getText() )->__toString();
			$result_array['parsetree'] = $xml;
			$result_array[ApiResult::META_BC_SUBELEMENTS][] = 'parsetree';
		}

		$result_mapping = [
			'redirects' => 'r',
			'langlinks' => 'll',
			'categories' => 'cl',
			'links' => 'pl',
			'templates' => 'tl',
			'images' => 'img',
			'externallinks' => 'el',
			'iwlinks' => 'iw',
			'sections' => 's',
			'headitems' => 'hi',
			'modules' => 'm',
			'indicators' => 'ind',
			'modulescripts' => 'm',
			'modulestyles' => 'm',
			'properties' => 'pp',
			'limitreportdata' => 'lr',
			'parsewarnings' => 'pw'
		];
		$this->setIndexedTagNames( $result_array, $result_mapping );
		$result->addValue( null, $this->getModuleName(), $result_array );
	}

	/**
	 * Constructs a ParserOptions object
	 *
	 * @param WikiPage $pageObj
	 * @param array $params
	 *
	 * @return array [ ParserOptions, ScopedCallback, bool $suppressCache ]
	 */
	protected function makeParserOptions( WikiPage $pageObj, array $params ) {
		$popts = $pageObj->makeParserOptions( $this->getContext() );
		$popts->enableLimitReport( !$params['disablepp'] && !$params['disablelimitreport'] );
		$popts->setIsPreview( $params['preview'] || $params['sectionpreview'] );
		$popts->setIsSectionPreview( $params['sectionpreview'] );

		if ( $params['wrapoutputclass'] !== '' ) {
			$popts->setWrapOutputClass( $params['wrapoutputclass'] );
		}

		$reset = null;
		$suppressCache = false;
		$this->getHookRunner()->onApiMakeParserOptions( $popts, $pageObj->getTitle(),
			$params, $this, $reset, $suppressCache );

		// Force cache suppression when $popts aren't cacheable.
		$suppressCache = $suppressCache || !$popts->isSafeToCache();

		return [ $popts, $reset, $suppressCache ];
	}

	/**
	 * @param WikiPage $page
	 * @param ParserOptions $popts
	 * @param bool $suppressCache
	 * @param int $pageId
	 * @param RevisionRecord|null $rev
	 * @param bool $getContent
	 * @return ParserOutput
	 */
	private function getParsedContent(
		WikiPage $page, $popts, $suppressCache, $pageId, $rev, $getContent
	) {
		$revId = $rev ? $rev->getId() : null;
		$isDeleted = $rev && $rev->isDeleted( RevisionRecord::DELETED_TEXT );

		if ( $getContent || $this->section !== false || $isDeleted ) {
			if ( $rev ) {
				$this->content = $rev->getContent(
					SlotRecord::MAIN, RevisionRecord::FOR_THIS_USER, $this->getUser()
				);
				if ( !$this->content ) {
					$this->dieWithError( [ 'apierror-missingcontent-revid', $revId ] );
				}
			} else {
				$this->content = $page->getContent( RevisionRecord::FOR_THIS_USER, $this->getUser() );
				if ( !$this->content ) {
					$this->dieWithError( [ 'apierror-missingcontent-pageid', $page->getId() ] );
				}
			}
			$this->contentIsDeleted = $isDeleted;
			$this->contentIsSuppressed = $rev &&
				$rev->isDeleted( RevisionRecord::DELETED_TEXT | RevisionRecord::DELETED_RESTRICTED );
		}

		if ( $this->section !== false ) {
			$this->content = $this->getSectionContent(
				$this->content,
				$pageId === null ? $page->getTitle()->getPrefixedText() : $this->msg( 'pageid', $pageId )
			);
			return $this->getContentParserOutput( $this->content, $page->getTitle(), $revId, $popts );
		}

		if ( $isDeleted ) {
			// getParserOutput can't do revdeled revisions

			$pout = $this->getContentParserOutput( $this->content, $page->getTitle(), $revId, $popts );
		} else {
			// getParserOutput will save to Parser cache if able
			$pout = $this->getPageParserOutput( $page, $revId, $popts, $suppressCache );
		}
		if ( !$pout ) {
			// @codeCoverageIgnoreStart
			$this->dieWithError( [ 'apierror-nosuchrevid', $revId ?: $page->getLatest() ] );
			// @codeCoverageIgnoreEnd
		}

		return $pout;
	}

	/**
	 * Extract the requested section from the given Content
	 *
	 * @param Content $content
	 * @param string|Message $what Identifies the content in error messages, e.g. page title.
	 * @return Content
	 */
	private function getSectionContent( Content $content, $what ) {
		// Not cached (save or load)
		$section = $content->getSection( $this->section );
		if ( $section === false ) {
			$this->dieWithError( [ 'apierror-nosuchsection-what', $this->section, $what ], 'nosuchsection' );
		}
		if ( $section === null ) {
			$this->dieWithError( [ 'apierror-sectionsnotsupported-what', $what ], 'nosuchsection' );
			$section = false;
		}

		return $section;
	}

	/**
	 * This mimicks the behavior of EditPage in formatting a summary
	 *
	 * @param Title $title of the page being parsed
	 * @param array $params The API parameters of the request
	 * @return string HTML
	 */
	private function formatSummary( $title, $params ) {
		$summary = $params['summary'] ?? '';
		$sectionTitle = $params['sectiontitle'] ?? '';

		if ( $this->section === 'new' && ( $sectionTitle === '' || $summary === '' ) ) {
			if ( $sectionTitle !== '' ) {
				$summary = $params['sectiontitle'];
			}
			if ( $summary !== '' ) {
				$summary = wfMessage( 'newsectionsummary' )
					->rawParams( MediaWikiServices::getInstance()->getParser()
						->stripSectionName( $summary ) )
					->inContentLanguage()->text();
			}
		}
		return Linker::formatComment( $summary, $title, $this->section === 'new' );
	}

	private function formatLangLinks( $links ) {
		$languageNameUtils = MediaWikiServices::getInstance()->getLanguageNameUtils();
		$result = [];
		foreach ( $links as $link ) {
			$entry = [];
			$bits = explode( ':', $link, 2 );
			$title = Title::newFromText( $link );

			$entry['lang'] = $bits[0];
			if ( $title ) {
				$entry['url'] = wfExpandUrl( $title->getFullURL(), PROTO_CURRENT );
				// localised language name in 'uselang' language
				$entry['langname'] = $languageNameUtils->getLanguageName(
					$title->getInterwiki(),
					$this->getLanguage()->getCode()
				);

				// native language name
				$entry['autonym'] = $languageNameUtils->getLanguageName( $title->getInterwiki() );
			}
			ApiResult::setContentValue( $entry, 'title', $bits[1] );
			$result[] = $entry;
		}

		return $result;
	}

	private function formatCategoryLinks( $links ) {
		$result = [];

		if ( !$links ) {
			return $result;
		}

		// Fetch hiddencat property
		$linkBatchFactory = MediaWikiServices::getInstance()->getLinkBatchFactory();
		$lb = $linkBatchFactory->newLinkBatch();
		$lb->setArray( [ NS_CATEGORY => $links ] );
		$db = $this->getDB();
		$res = $db->select( [ 'page', 'page_props' ],
			[ 'page_title', 'pp_propname' ],
			$lb->constructSet( 'page', $db ),
			__METHOD__,
			[],
			[ 'page_props' => [
				'LEFT JOIN', [ 'pp_propname' => 'hiddencat', 'pp_page = page_id' ]
			] ]
		);
		$hiddencats = [];
		foreach ( $res as $row ) {
			$hiddencats[$row->page_title] = isset( $row->pp_propname );
		}

		$linkCache = MediaWikiServices::getInstance()->getLinkCache();

		foreach ( $links as $link => $sortkey ) {
			$entry = [];
			$entry['sortkey'] = $sortkey;
			// array keys will cast numeric category names to ints, so cast back to string
			ApiResult::setContentValue( $entry, 'category', (string)$link );
			if ( !isset( $hiddencats[$link] ) ) {
				$entry['missing'] = true;

				// We already know the link doesn't exist in the database, so
				// tell LinkCache that before calling $title->isKnown().
				$title = Title::makeTitle( NS_CATEGORY, $link );
				$linkCache->addBadLinkObj( $title );
				if ( $title->isKnown() ) {
					$entry['known'] = true;
				}
			} elseif ( $hiddencats[$link] ) {
				$entry['hidden'] = true;
			}
			$result[] = $entry;
		}

		return $result;
	}

	private function formatLinks( $links ) {
		$result = [];
		foreach ( $links as $ns => $nslinks ) {
			foreach ( $nslinks as $title => $id ) {
				$entry = [];
				$entry['ns'] = $ns;
				ApiResult::setContentValue( $entry, 'title', Title::makeTitle( $ns, $title )->getFullText() );
				$entry['exists'] = $id != 0;
				$result[] = $entry;
			}
		}

		return $result;
	}

	private function formatIWLinks( $iw ) {
		$result = [];
		foreach ( $iw as $prefix => $titles ) {
			foreach ( array_keys( $titles ) as $title ) {
				$entry = [];
				$entry['prefix'] = $prefix;

				$title = Title::newFromText( "{$prefix}:{$title}" );
				if ( $title ) {
					$entry['url'] = wfExpandUrl( $title->getFullURL(), PROTO_CURRENT );
				}

				ApiResult::setContentValue( $entry, 'title', $title->getFullText() );
				$result[] = $entry;
			}
		}

		return $result;
	}

	private function formatHeadItems( $headItems ) {
		$result = [];
		foreach ( $headItems as $tag => $content ) {
			$entry = [];
			$entry['tag'] = $tag;
			ApiResult::setContentValue( $entry, 'content', $content );
			$result[] = $entry;
		}

		return $result;
	}

	private function formatLimitReportData( $limitReportData ) {
		$result = [];

		foreach ( $limitReportData as $name => $value ) {
			$entry = [];
			$entry['name'] = $name;
			if ( !is_array( $value ) ) {
				$value = [ $value ];
			}
			ApiResult::setIndexedTagNameRecursive( $value, 'param' );
			$entry = array_merge( $entry, $value );
			$result[] = $entry;
		}

		return $result;
	}

	private function setIndexedTagNames( &$array, $mapping ) {
		foreach ( $mapping as $key => $name ) {
			if ( isset( $array[$key] ) ) {
				ApiResult::setIndexedTagName( $array[$key], $name );
			}
		}
	}

	public function getAllowedParams() {
		$skinFactory = MediaWikiServices::getInstance()->getSkinFactory();

		return [
			'title' => null,
			'text' => [
				ApiBase::PARAM_TYPE => 'text',
			],
			'revid' => [
				ApiBase::PARAM_TYPE => 'integer',
			],
			'summary' => null,
			'page' => null,
			'pageid' => [
				ApiBase::PARAM_TYPE => 'integer',
			],
			'redirects' => false,
			'oldid' => [
				ApiBase::PARAM_TYPE => 'integer',
			],
			'prop' => [
				ApiBase::PARAM_DFLT => 'text|langlinks|categories|links|templates|' .
					'images|externallinks|sections|revid|displaytitle|iwlinks|' .
					'properties|parsewarnings',
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => [
					'text',
					'langlinks',
					'categories',
					'categorieshtml',
					'links',
					'templates',
					'images',
					'externallinks',
					'sections',
					'revid',
					'displaytitle',
					'headhtml',
					'modules',
					'jsconfigvars',
					'encodedjsconfigvars',
					'indicators',
					'iwlinks',
					'wikitext',
					'properties',
					'limitreportdata',
					'limitreporthtml',
					'parsetree',
					'parsewarnings',
					'headitems',
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [
					'parsetree' => [ 'apihelp-parse-paramvalue-prop-parsetree', CONTENT_MODEL_WIKITEXT ],
				],
				ApiBase::PARAM_DEPRECATED_VALUES => [
					'headitems' => 'apiwarn-deprecation-parse-headitems',
				],
			],
			'wrapoutputclass' => 'mw-parser-output',
			'pst' => false,
			'onlypst' => false,
			'effectivelanglinks' => [
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_DEPRECATED => true,
			],
			'section' => null,
			'sectiontitle' => [
				ApiBase::PARAM_TYPE => 'string',
			],
			'disablepp' => [
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_DEPRECATED => true,
			],
			'disablelimitreport' => false,
			'disableeditsection' => false,
			'disablestylededuplication' => false,
			'generatexml' => [
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_HELP_MSG => [
					'apihelp-parse-param-generatexml', CONTENT_MODEL_WIKITEXT
				],
				ApiBase::PARAM_DEPRECATED => true,
			],
			'preview' => false,
			'sectionpreview' => false,
			'disabletoc' => false,
			'useskin' => [
				ApiBase::PARAM_TYPE => array_keys( $skinFactory->getAllowedSkins() ),
			],
			'contentformat' => [
				ApiBase::PARAM_TYPE => $this->getContentHandlerFactory()->getAllContentFormats(),
			],
			'contentmodel' => [
				ApiBase::PARAM_TYPE => $this->getContentHandlerFactory()->getContentModels(),
			],
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=parse&page=Project:Sandbox'
				=> 'apihelp-parse-example-page',
			'action=parse&text={{Project:Sandbox}}&contentmodel=wikitext'
				=> 'apihelp-parse-example-text',
			'action=parse&text={{PAGENAME}}&title=Test'
				=> 'apihelp-parse-example-texttitle',
			'action=parse&summary=Some+[[link]]&prop='
				=> 'apihelp-parse-example-summary',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Parsing_wikitext#parse';
	}

	private function getContentHandlerFactory(): IContentHandlerFactory {
		return MediaWikiServices::getInstance()->getContentHandlerFactory();
	}
}
