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

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Cache\LinkCache;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\Content\Content;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\Renderer\ContentRenderer;
use MediaWiki\Content\Transform\ContentTransformer;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\EditPage\EditPage;
use MediaWiki\Exception\MWContentSerializationException;
use MediaWiki\Json\FormatJson;
use MediaWiki\Language\RawMessage;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Message\Message;
use MediaWiki\Output\OutputPage;
use MediaWiki\Page\Article;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\WikiPage;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Parser\Parser;
use MediaWiki\Parser\ParserFactory;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\ParserOutputFlags;
use MediaWiki\Parser\ParserOutputLinkTypes;
use MediaWiki\PoolCounter\PoolCounterWorkViaCallback;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Skin\Skin;
use MediaWiki\Skin\SkinFactory;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\Title\TitleValue;
use MediaWiki\User\TempUser\TempUserCreator;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\Utils\UrlUtils;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\EnumDef;
use Wikimedia\Parsoid\Core\LinkTarget as ParsoidLinkTarget;

/**
 * @ingroup API
 */
class ApiParse extends ApiBase {

	/** @var string|false|null */
	private $section = null;

	/** @var Content|null */
	private $content = null;

	/** @var Content|null */
	private $pstContent = null;

	private bool $contentIsDeleted = false;
	private bool $contentIsSuppressed = false;

	private RevisionLookup $revisionLookup;
	private SkinFactory $skinFactory;
	private LanguageNameUtils $languageNameUtils;
	private LinkBatchFactory $linkBatchFactory;
	private LinkCache $linkCache;
	private IContentHandlerFactory $contentHandlerFactory;
	private ParserFactory $parserFactory;
	private WikiPageFactory $wikiPageFactory;
	private ContentTransformer $contentTransformer;
	private CommentFormatter $commentFormatter;
	private ContentRenderer $contentRenderer;
	private TempUserCreator $tempUserCreator;
	private UserFactory $userFactory;
	private UrlUtils $urlUtils;
	private TitleFormatter $titleFormatter;

	public function __construct(
		ApiMain $main,
		string $action,
		RevisionLookup $revisionLookup,
		SkinFactory $skinFactory,
		LanguageNameUtils $languageNameUtils,
		LinkBatchFactory $linkBatchFactory,
		LinkCache $linkCache,
		IContentHandlerFactory $contentHandlerFactory,
		ParserFactory $parserFactory,
		WikiPageFactory $wikiPageFactory,
		ContentRenderer $contentRenderer,
		ContentTransformer $contentTransformer,
		CommentFormatter $commentFormatter,
		TempUserCreator $tempUserCreator,
		UserFactory $userFactory,
		UrlUtils $urlUtils,
		TitleFormatter $titleFormatter
	) {
		parent::__construct( $main, $action );
		$this->revisionLookup = $revisionLookup;
		$this->skinFactory = $skinFactory;
		$this->languageNameUtils = $languageNameUtils;
		$this->linkBatchFactory = $linkBatchFactory;
		$this->linkCache = $linkCache;
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->parserFactory = $parserFactory;
		$this->wikiPageFactory = $wikiPageFactory;
		$this->contentRenderer = $contentRenderer;
		$this->contentTransformer = $contentTransformer;
		$this->commentFormatter = $commentFormatter;
		$this->tempUserCreator = $tempUserCreator;
		$this->userFactory = $userFactory;
		$this->urlUtils = $urlUtils;
		$this->titleFormatter = $titleFormatter;
	}

	private function getPoolKey(): string {
		$poolKey = WikiMap::getCurrentWikiDbDomain() . ':ApiParse:';
		if ( !$this->getUser()->isRegistered() ) {
			$poolKey .= 'a:' . $this->getUser()->getName();
		} else {
			$poolKey .= 'u:' . $this->getUser()->getId();
		}
		return $poolKey;
	}

	private function getContentParserOutput(
		Content $content,
		PageReference $page,
		?RevisionRecord $revision,
		ParserOptions $popts
	): ParserOutput {
		$worker = new PoolCounterWorkViaCallback( 'ApiParser', $this->getPoolKey(),
			[
				'doWork' => function () use ( $content, $page, $revision, $popts ) {
					return $this->contentRenderer->getParserOutput(
						$content, $page, $revision, $popts
					);
				},
				'error' => function () {
					$this->dieWithError( 'apierror-concurrency-limit' );
				},
			]
		);
		return $worker->execute();
	}

	private function getUserForPreview(): UserIdentity {
		$user = $this->getUser();
		if ( $this->tempUserCreator->shouldAutoCreate( $user, 'edit' ) ) {
			return $this->userFactory->newUnsavedTempUser(
				$this->tempUserCreator->getStashedName( $this->getRequest()->getSession() )
			);
		}
		return $user;
	}

	/** @return ParserOutput|false */
	private function getPageParserOutput(
		WikiPage $page,
		?int $revId,
		ParserOptions $popts,
		bool $suppressCache
	) {
		$worker = new PoolCounterWorkViaCallback( 'ApiParser', $this->getPoolKey(),
			[
				'doWork' => static function () use ( $page, $revId, $popts, $suppressCache ) {
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

		$prop = array_fill_keys( $params['prop'], true );

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
		// phpcs:ignore MediaWiki.Usage.DeprecatedGlobalVariables.Deprecated$wgTitle
		global $wgTitle;

		$format = null;
		$redirValues = null;

		$needContent = isset( $prop['wikitext'] ) ||
			isset( $prop['parsetree'] ) || $params['generatexml'];

		// Return result
		$result = $this->getResult();

		if ( $oldid !== null || $pageid !== null || $page !== null ) {
			if ( $this->section === 'new' ) {
				$this->dieWithError( 'apierror-invalidparammix-parse-new-section', 'invalidparammix' );
			}
			if ( $oldid !== null ) {
				// Don't use the parser cache
				$rev = $this->revisionLookup->getRevisionById( $oldid );
				if ( !$rev ) {
					$this->dieWithError( [ 'apierror-nosuchrevid', $oldid ] );
				}

				$this->checkTitleUserPermissions( $rev->getPage(), 'read' );

				if ( !$rev->userCan( RevisionRecord::DELETED_TEXT, $this->getAuthority() ) ) {
					$this->dieWithError(
						[ 'apierror-permissiondenied', $this->msg( 'action-deletedtext' ) ]
					);
				}

				$revLinkTarget = $rev->getPageAsLinkTarget();
				$titleObj = Title::newFromLinkTarget( $revLinkTarget );
				$wgTitle = $titleObj;
				$pageObj = $this->wikiPageFactory->newFromTitle( $titleObj );
				[ $popts, $reset, $suppressCache ] = $this->makeParserOptions( $pageObj, $params );
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

					foreach ( $pageSet->getRedirectTargets() as $redirectTarget ) {
						$pageParams = [ 'title' => $this->titleFormatter->getFullText( $redirectTarget ) ];
					}
				} elseif ( $pageid !== null ) {
					$pageParams = [ 'pageid' => $pageid ];
				} else { // $page
					$pageParams = [ 'title' => $page ];
				}

				$pageObj = $this->getTitleOrPageId( $pageParams, 'fromdb' );
				$titleObj = $pageObj->getTitle();
				if ( !$titleObj->exists() ) {
					$this->dieWithError( 'apierror-missingtitle' );
				}

				$this->checkTitleUserPermissions( $titleObj, 'read' );
				$wgTitle = $titleObj;

				if ( isset( $prop['revid'] ) ) {
					$oldid = $pageObj->getLatest();
				}

				[ $popts, $reset, $suppressCache ] = $this->makeParserOptions( $pageObj, $params );
				$p_result = $this->getParsedContent(
					$pageObj, $popts, $suppressCache, $pageid, null, $needContent
				);
			}
		} else { // Not $oldid, $pageid, $page. Hence based on $text
			$model = $params['contentmodel'];
			$format = $params['contentformat'];

			$titleObj = Title::newFromText( $title );
			if ( !$titleObj || $titleObj->isExternal() ) {
				$this->dieWithError( [ 'apierror-invalidtitle', wfEscapeWikiText( $title ) ] );
			}
			$revid = $params['revid'];
			$rev = null;
			if ( $revid !== null ) {
				$rev = $this->revisionLookup->getRevisionById( $revid );
				if ( !$rev ) {
					$this->dieWithError( [ 'apierror-nosuchrevid', $revid ] );
				}
				$pTitleObj = $titleObj;
				$titleObj = Title::newFromPageIdentity( $rev->getPage() );
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
				$pageObj = $this->wikiPageFactory->newFromTitle( $titleObj );
				[ $popts, $reset ] = $this->makeParserOptions( $pageObj, $params );
			} else {
				// Allow parsing wikitext in the context of special pages (T51477)
				$pageObj = null;
				$popts = ParserOptions::newFromContext( $this->getContext() );
				[ $popts, $reset ] = $this->tweakParserOptions( $popts, $titleObj, $params );
			}

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
			} elseif ( $model === null ) {
				$model = $titleObj->getContentModel();
			}

			$contentHandler = $this->contentHandlerFactory->getContentHandler( $model );
			// Not in the default format, check supported or not
			if ( $format && !$contentHandler->isSupportedFormat( $format ) ) {
				$this->dieWithError( [ 'apierror-badformat-generic', $format, $model ] );
			}

			try {
				$this->content = $contentHandler->unserializeContent( $text, $format );
			} catch ( MWContentSerializationException $ex ) {
				$this->dieWithException( $ex, [
					'wrap' => ApiMessage::create( 'apierror-contentserializationexception', 'parseerror' )
				] );
			}

			if ( $this->section !== false ) {
				if ( $this->section === 'new' ) {
					// Insert the section title above the content.
					if ( $params['sectiontitle'] !== null ) {
						$this->content = $this->content->addSectionHeader( $params['sectiontitle'] );
					}
				} else {
					$this->content = $this->getSectionContent( $this->content, $titleObj->getPrefixedText() );
				}
			}

			if ( $params['pst'] || $params['onlypst'] ) {
				$this->pstContent = $this->contentTransformer->preSaveTransform(
					$this->content,
					$titleObj,
					$this->getUserForPreview(),
					$popts
				);
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
				$p_result = $this->getContentParserOutput( $this->pstContent, $titleObj, $rev, $popts );
			} else {
				$p_result = $this->getContentParserOutput( $this->content, $titleObj, $rev, $popts );
			}
		}

		$result_array = [];

		$result_array['title'] = $titleObj->getPrefixedText();
		$result_array['pageid'] = $pageid ?: $titleObj->getArticleID();
		if ( $this->contentIsDeleted ) {
			$result_array['textdeleted'] = true;
		}
		if ( $this->contentIsSuppressed ) {
			$result_array['textsuppressed'] = true;
		}

		if ( isset( $params['useskin'] ) ) {
			$skin = $this->skinFactory->makeSkin( Skin::normalizeKey( $params['useskin'] ) );
		} else {
			$skin = null;
		}

		$outputPage = null;
		$context = null;
		if (
			$skin || isset( $prop['subtitle'] ) || isset( $prop['headhtml'] ) || isset( $prop['categorieshtml'] ) ||
			isset( $params['mobileformat'] )
		) {
			// Enabling the skin via 'useskin', 'subtitle', 'headhtml', or 'categorieshtml'
			// gets OutputPage and Skin involved, which (among others) applies
			// these hooks:
			// - Hook: LanguageLinks
			// - Hook: SkinSubPageSubtitle
			// - Hook: OutputPageParserOutput
			// - Hook: OutputPageRenderCategoryLink
			// - Hook: OutputPageBeforeHTML
			// HACK Adding the 'mobileformat' parameter *also* enables the skin, for compatibility with legacy
			// apps. This behavior should be considered deprecated so new users should not rely on this and
			// always use the "useskin" parameter to enable "skin mode".
			// Ideally this would be done with another hook so that MobileFrontend could enable skin mode, but
			// as this is just for a deprecated feature, we are hard-coding this param into core.
			$context = new DerivativeContext( $this->getContext() );
			$context->setTitle( $titleObj );

			if ( $pageObj ) {
				$context->setWikiPage( $pageObj );
			}
			// Some hooks only apply to pages when action=view, which this API
			// call is simulating.
			$context->setRequest( new FauxRequest( [ 'action' => 'view' ] ) );

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
			// Required for subtitle to appear
			$outputPage->setArticleFlag( true );

			if ( $this->content ) {
				$outputPage->addContentOverride( $titleObj, $this->content );
			}
			$context->setOutput( $outputPage );

			if ( $skin ) {
				// Based on OutputPage::output()
				$outputPage->loadSkinModules( $skin );
			}
		}

		if ( $oldid !== null ) {
			$result_array['revid'] = (int)$oldid;
		}

		if ( $params['redirects'] && $redirValues !== null ) {
			$result_array['redirects'] = $redirValues;
		}

		if ( isset( $prop['text'] ) ) {
			$skin = $context ? $context->getSkin() : null;
			$skinOptions = $skin ? $skin->getOptions() : [
				'toc' => true,
			];
			// TODO T371004 move runOutputPipeline out of $parserOutput
			// TODO T371022 it should be reasonably straightforward to move this to a clone, but it requires
			// careful checking of the clone and of what happens on the boundary of OutputPage. Leaving this as
			// "getText-equivalent" for now; will fix in a later, independent patch.
			$oldText = $p_result->getRawText();
			$result_array['text'] = $p_result->runOutputPipeline( $popts, [
				'allowClone' => false,
				'allowTOC' => !$params['disabletoc'],
				'injectTOC' => $skinOptions['toc'],
				'enableSectionEditLinks' => !$params['disableeditsection'],
				'wrapperDivClass' => $params['wrapoutputclass'],
				'deduplicateStyles' => !$params['disablestylededuplication'],
				'userLang' => $context ? $context->getLanguage() : null,
				'skin' => $skin,
				'includeDebugInfo' => !$params['disablepp'] && !$params['disablelimitreport']
			] )->getContentHolderText();
			$p_result->setRawText( $oldText );
			$result_array[ApiResult::META_BC_SUBELEMENTS][] = 'text';
			if ( $context ) {
				$this->getHookRunner()->onOutputPageBeforeHTML( $context->getOutput(), $result_array['text'] );
			}
		}

		if ( $outputPage ) {
			// This needs to happen after running the OutputTransform pipeline so that the metadata inserted by
			// the pipeline is also added to the OutputPage
			$outputPage->addParserOutputMetadata( $p_result );

			$this->getHookRunner()->onApiParseMakeOutputPage( $this, $outputPage );
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
				$langlinks = array_map(
					static fn ( $item ) => $item['link'],
					$p_result->getLinkList( ParserOutputLinkTypes::LANGUAGE )
				);
				// The deprecated 'effectivelanglinks' option pre-dates OutputPage
				// support via 'useskin'. If not already applied, then run just this
				// one hook of OutputPage::addParserOutputMetadata here.
				if ( $params['effectivelanglinks'] ) {
					# for compatibility with old hook, convert to string[]
					$compat = [];
					foreach ( $langlinks as $link ) {
						$s = $link->getInterwiki() . ':' . $link->getText();
						if ( $link->hasFragment() ) {
							$s .= '#' . $link->getFragment();
						}
						$compat[] = $s;
					}
					$langlinks = $compat;
					$linkFlags = [];
					$this->getHookRunner()->onLanguageLinks( $titleObj, $langlinks, $linkFlags );
				}
			}

			$result_array['langlinks'] = $this->formatLangLinks( $langlinks );
		}
		if ( isset( $prop['categories'] ) ) {
			$result_array['categories'] = $this->formatCategoryLinks( $p_result->getCategoryMap() );
		}
		if ( isset( $prop['categorieshtml'] ) ) {
			$result_array['categorieshtml'] = $outputPage->getSkin()->getCategories();
			$result_array[ApiResult::META_BC_SUBELEMENTS][] = 'categorieshtml';
		}
		if ( isset( $prop['links'] ) ) {

			$result_array['links'] = $this->formatLinks( $p_result->getLinkList( ParserOutputLinkTypes::LOCAL ) );
		}
		if ( isset( $prop['templates'] ) ) {
			$result_array['templates'] = $this->formatLinks(
				$p_result->getLinkList( ParserOutputLinkTypes::TEMPLATE )
			);
		}
		if ( isset( $prop['images'] ) ) {
			$result_array['images'] = array_map(
				static fn ( $item ) => $item['link']->getDBkey(),
				$p_result->getLinkList( ParserOutputLinkTypes::MEDIA )
			);
		}
		if ( isset( $prop['externallinks'] ) ) {
			$result_array['externallinks'] = array_keys( $p_result->getExternalLinks() );
		}
		if ( isset( $prop['sections'] ) ) {
			$result_array['sections'] = $p_result->getSections();
			$result_array['showtoc'] = $p_result->getOutputFlag( ParserOutputFlags::SHOW_TOC );
		}
		if ( isset( $prop['parsewarnings'] ) || isset( $prop['parsewarningshtml'] ) ) {
			$warnings = array_map(
				static fn ( $mv ) => Message::newFromSpecifier( $mv )
					->page( $titleObj )
					// Note that we use ContentLanguage here
					->inContentLanguage()
					->text(),
				$p_result->getWarningMsgs()
			);
			if ( $warnings === [] ) {
				// Backward compatibilty with cached ParserOutput from
				// MW <= 1.45 which didn't store the MessageValues (T343048)
				$warnings = $p_result->getWarnings();
			}
			if ( isset( $prop['parsewarnings'] ) ) {
				$result_array['parsewarnings'] = $warnings;
			}
			if ( isset( $prop['parsewarningshtml'] ) ) {
				$warningsHtml = array_map( static function ( $warning ) {
					return ( new RawMessage( '$1', [ $warning ] ) )->parse();
				}, $warnings );
				$result_array['parsewarningshtml'] = $warningsHtml;
			}
		}

		if ( isset( $prop['displaytitle'] ) ) {
			$result_array['displaytitle'] = $p_result->getDisplayTitle() !== false
				? $p_result->getDisplayTitle()
				: htmlspecialchars( $titleObj->getPrefixedText(), ENT_NOQUOTES );
		}

		if ( isset( $prop['subtitle'] ) ) {
			// Get the subtitle without its container element to support UI refreshing
			$result_array['subtitle'] = $context->getSkin()->prepareSubtitle( false );
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
			$showStrategyKeys = (bool)( $params['showstrategykeys'] );
			$jsconfigvars = $skin ? $outputPage->getJsConfigVars() : $p_result->getJsConfigVars( $showStrategyKeys );
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
				$result_array['indicators'] = $outputPage->getIndicators();
			} else {
				$result_array['indicators'] = $p_result->getIndicators();
			}
			ApiResult::setArrayType( $result_array['indicators'], 'BCkvp', 'name' );
		}

		if ( isset( $prop['iwlinks'] ) ) {
			$links = array_map(
				static fn ( $item ) => $item['link'],
				$p_result->getLinkList( ParserOutputLinkTypes::INTERWIKI )
			);
			$result_array['iwlinks'] = $this->formatIWLinks( $links );
		}

		if ( isset( $prop['wikitext'] ) ) {
			$result_array['wikitext'] = $this->content->serialize( $format );
			$result_array[ApiResult::META_BC_SUBELEMENTS][] = 'wikitext';
			// @phan-suppress-next-line PhanImpossibleTypeComparison
			if ( $this->pstContent !== null ) {
				$result_array['psttext'] = $this->pstContent->serialize( $format );
				$result_array[ApiResult::META_BC_SUBELEMENTS][] = 'psttext';
			}
		}
		if ( isset( $prop['properties'] ) ) {
			$result_array['properties'] = $p_result->getPageProperties();
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

			$parser = $this->parserFactory->getInstance();
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
			'parsewarnings' => 'pw',
			'parsewarningshtml' => 'pw',
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
	private function makeParserOptions( WikiPage $pageObj, array $params ) {
		$popts = $pageObj->makeParserOptions( $this->getContext() );
		$popts->setRenderReason( 'api-parse' );
		if ( $params['usearticle'] ) {
			# T349037: The ArticleParserOptions hook should be broadened to take
			# a WikiPage (aka $pageObj) instead of an Article.  But for now
			# fake the Article.
			$article = Article::newFromWikiPage( $pageObj, $this->getContext() );
			# Allow extensions to vary parser options used for article rendering,
			# in the same way Article does
			$this->getHookRunner()->onArticleParserOptions( $article, $popts );
		}
		return $this->tweakParserOptions( $popts, $pageObj->getTitle(), $params );
	}

	/**
	 * Tweaks a ParserOptions object
	 *
	 * @param ParserOptions $popts
	 * @param Title $title
	 * @param array $params
	 *
	 * @return array [ ParserOptions, ScopedCallback, bool $suppressCache ]
	 */
	private function tweakParserOptions( ParserOptions $popts, Title $title, array $params ) {
		$popts->setIsPreview( $params['preview'] || $params['sectionpreview'] );
		$popts->setIsSectionPreview( $params['sectionpreview'] );

		if ( $params['wrapoutputclass'] !== '' ) {
			$popts->setWrapOutputClass( $params['wrapoutputclass'] );
		}
		if ( $params['parsoid'] ) {
			$popts->setUseParsoid();
		}

		$reset = null;
		$suppressCache = false;
		$this->getHookRunner()->onApiMakeParserOptions( $popts, $title,
			$params, $this, $reset, $suppressCache );

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
					SlotRecord::MAIN, RevisionRecord::FOR_THIS_USER, $this->getAuthority()
				);
				if ( !$this->content ) {
					$this->dieWithError( [ 'apierror-missingcontent-revid', $revId ] );
				}
			} else {
				$this->content = $page->getContent( RevisionRecord::FOR_THIS_USER, $this->getAuthority() );
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
			return $this->getContentParserOutput(
				$this->content, $page->getTitle(),
				$rev,
				$popts
			);
		}

		if ( $isDeleted ) {
			// getParserOutput can't do revdeled revisions

			$pout = $this->getContentParserOutput(
				$this->content, $page->getTitle(),
				$rev,
				$popts
			);
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
		}

		// @phan-suppress-next-line PhanTypeMismatchReturnNullable T240141
		return $section;
	}

	/**
	 * This mimics the behavior of EditPage in formatting a summary
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
				$summary = $this->msg( 'newsectionsummary' )
					->rawParams( $this->parserFactory->getMainInstance()->stripSectionName( $summary ) )
					->inContentLanguage()->text();
			}
		}
		return $this->commentFormatter->format( $summary, $title, $this->section === 'new' );
	}

	/**
	 * @param string[]|ParsoidLinkTarget[] $links
	 * @return array
	 */
	private function formatLangLinks( $links ): array {
		$result = [];
		foreach ( $links as $link ) {
			$entry = [];
			if ( is_string( $link ) ) {
				[ $lang, $titleWithFrag ] = explode( ':', $link, 2 );
				[ $title, $frag ] = array_pad( explode( '#', $titleWithFrag, 2 ), 2, '' );
				$title = TitleValue::tryNew( NS_MAIN, $title, $frag, $lang );
			} else {
				$title = $link;
				$lang = $link->getInterwiki();
				$titleWithFrag = $link->getText();
				if ( $link->hasFragment() ) {
					$titleWithFrag .= '#' . $link->getFragment();
				}
			}
			$title = Title::castFromLinkTarget( $title );

			$entry['lang'] = $lang;
			if ( $title ) {
				$entry['url'] = (string)$this->urlUtils->expand( $title->getFullURL(), PROTO_CURRENT );
				// title validity implies language code validity
				// localised language name in 'uselang' language
				$entry['langname'] = $this->languageNameUtils->getLanguageName(
					$lang,
					$this->getLanguage()->getCode()
				);

				// native language name
				$entry['autonym'] = $this->languageNameUtils->getLanguageName( $lang );
			}
			ApiResult::setContentValue( $entry, 'title', $titleWithFrag );
			$result[] = $entry;
		}

		return $result;
	}

	private function formatCategoryLinks( array $links ): array {
		$result = [];

		if ( !$links ) {
			return $result;
		}

		// Fetch hiddencat property
		$lb = $this->linkBatchFactory->newLinkBatch();
		$lb->setArray( [ NS_CATEGORY => $links ] );
		$db = $this->getDB();
		$res = $db->newSelectQueryBuilder()
			->select( [ 'page_title', 'pp_propname' ] )
			->from( 'page' )
			->where( $lb->constructSet( 'page', $db ) )
			->leftJoin( 'page_props', null, [ 'pp_propname' => 'hiddencat', 'pp_page = page_id' ] )
			->caller( __METHOD__ )
			->fetchResultSet();
		$hiddencats = [];
		foreach ( $res as $row ) {
			$hiddencats[$row->page_title] = isset( $row->pp_propname );
		}

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
				$this->linkCache->addBadLinkObj( $title );
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

	/**
	 * @param list<array{link:ParsoidLinkTarget,pageid?:int}> $links
	 * @return array
	 */
	private function formatLinks( array $links ): array {
		$result = [];
		foreach ( $links as [ 'link' => $link, 'pageid' => $id ] ) {
			$entry = [];
			$entry['ns'] = $link->getNamespace();
			ApiResult::setContentValue( $entry, 'title', Title::newFromLinkTarget( $link )->getFullText() );
			$entry['exists'] = $id != 0;
			$result[] = $entry;
		}

		return $result;
	}

	private function formatIWLinks( array $iw ): array {
		$result = [];
		foreach ( $iw as $linkTarget ) {
			$entry = [];
			$entry['prefix'] = $linkTarget->getInterwiki();
			$title = Title::newFromLinkTarget( $linkTarget );
			if ( $title ) {
				$entry['url'] = (string)$this->urlUtils->expand( $title->getFullURL(), PROTO_CURRENT );

				ApiResult::setContentValue( $entry, 'title', $title->getFullText() );
			}
			$result[] = $entry;
		}

		return $result;
	}

	private function formatHeadItems( array $headItems ): array {
		$result = [];
		foreach ( $headItems as $tag => $content ) {
			$entry = [];
			$entry['tag'] = $tag;
			ApiResult::setContentValue( $entry, 'content', $content );
			$result[] = $entry;
		}

		return $result;
	}

	private function formatLimitReportData( array $limitReportData ): array {
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

	private function setIndexedTagNames( array &$array, array $mapping ) {
		foreach ( $mapping as $key => $name ) {
			if ( isset( $array[$key] ) ) {
				ApiResult::setIndexedTagName( $array[$key], $name );
			}
		}
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'title' => null,
			'text' => [
				ParamValidator::PARAM_TYPE => 'text',
			],
			'revid' => [
				ParamValidator::PARAM_TYPE => 'integer',
			],
			'summary' => null,
			'page' => null,
			'pageid' => [
				ParamValidator::PARAM_TYPE => 'integer',
			],
			'redirects' => false,
			'oldid' => [
				ParamValidator::PARAM_TYPE => 'integer',
			],
			'prop' => [
				ParamValidator::PARAM_DEFAULT => 'text|langlinks|categories|links|templates|' .
					'images|externallinks|sections|revid|displaytitle|iwlinks|' .
					'properties|parsewarnings',
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => [
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
					'subtitle',
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
					'parsewarningshtml',
					'headitems',
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [
					'parsetree' => [ 'apihelp-parse-paramvalue-prop-parsetree', CONTENT_MODEL_WIKITEXT ],
				],
				EnumDef::PARAM_DEPRECATED_VALUES => [
					'headitems' => 'apiwarn-deprecation-parse-headitems',
				],
			],
			'wrapoutputclass' => 'mw-parser-output',
			'usearticle' => false, // since 1.43
			'parsoid' => false, // since 1.41
			'pst' => false,
			'onlypst' => false,
			'effectivelanglinks' => [
				ParamValidator::PARAM_DEFAULT => false,
				ParamValidator::PARAM_DEPRECATED => true,
			],
			'section' => null,
			'sectiontitle' => [
				ParamValidator::PARAM_TYPE => 'string',
			],
			'disablepp' => [
				ParamValidator::PARAM_DEFAULT => false,
				ParamValidator::PARAM_DEPRECATED => true,
			],
			'disablelimitreport' => false,
			'disableeditsection' => false,
			'disablestylededuplication' => false,
			'showstrategykeys' => false,
			'generatexml' => [
				ParamValidator::PARAM_DEFAULT => false,
				ApiBase::PARAM_HELP_MSG => [
					'apihelp-parse-param-generatexml', CONTENT_MODEL_WIKITEXT
				],
				ParamValidator::PARAM_DEPRECATED => true,
			],
			'preview' => false,
			'sectionpreview' => false,
			'disabletoc' => false,
			'useskin' => [
				// T237856; We use all installed skins here to allow hidden (but usable) skins
				// to continue working correctly with some features such as Live Preview
				ParamValidator::PARAM_TYPE => array_keys( $this->skinFactory->getInstalledSkins() ),
			],
			'contentformat' => [
				ParamValidator::PARAM_TYPE => $this->contentHandlerFactory->getAllContentFormats(),
			],
			'contentmodel' => [
				ParamValidator::PARAM_TYPE => $this->contentHandlerFactory->getContentModels(),
			],
		];
	}

	/** @inheritDoc */
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

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Parsing_wikitext';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiParse::class, 'ApiParse' );
