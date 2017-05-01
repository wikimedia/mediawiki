<?php
/**
 * Created on Dec 01, 2007
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
 * @ingroup API
 */
class ApiParse extends ApiBase {

	/** @var string $section */
	private $section = null;

	/** @var Content $content */
	private $content = null;

	/** @var Content $pstContent */
	private $pstContent = null;

	public function execute() {
		// The data is hot but user-dependent, like page views, so we set vary cookies
		$this->getMain()->setCacheMode( 'anon-public-user-private' );

		// Get parameters
		$params = $this->extractRequestParams();

		// No easy way to say that text & title are allowed together while the
		// rest aren't, so just do it in two calls.
		$this->requireMaxOneParameter( $params, 'page', 'pageid', 'oldid', 'text' );
		$this->requireMaxOneParameter( $params, 'page', 'pageid', 'oldid', 'title' );

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
		global $wgParser, $wgTitle;

		$redirValues = null;

		// Return result
		$result = $this->getResult();

		if ( !is_null( $oldid ) || !is_null( $pageid ) || !is_null( $page ) ) {
			if ( $this->section === 'new' ) {
				$this->dieWithError( 'apierror-invalidparammix-parse-new-section', 'invalidparammix' );
			}
			if ( !is_null( $oldid ) ) {
				// Don't use the parser cache
				$rev = Revision::newFromId( $oldid );
				if ( !$rev ) {
					$this->dieWithError( [ 'apierror-nosuchrevid', $oldid ] );
				}

				$this->checkTitleUserPermissions( $rev->getTitle(), 'read' );
				if ( !$rev->userCan( Revision::DELETED_TEXT, $this->getUser() ) ) {
					$this->dieWithError(
						[ 'apierror-permissiondenied', $this->msg( 'action-deletedtext' ) ]
					);
				}

				$titleObj = $rev->getTitle();
				$wgTitle = $titleObj;
				$pageObj = WikiPage::factory( $titleObj );
				list( $popts, $reset, $suppressCache ) = $this->makeParserOptions( $pageObj, $params );

				// If for some reason the "oldid" is actually the current revision, it may be cached
				// Deliberately comparing $pageObj->getLatest() with $rev->getId(), rather than
				// checking $rev->isCurrent(), because $pageObj is what actually ends up being used,
				// and if its ->getLatest() is outdated, $rev->isCurrent() won't tell us that.
				if ( !$suppressCache && $rev->getId() == $pageObj->getLatest() ) {
					// May get from/save to parser cache
					$p_result = $this->getParsedContent( $pageObj, $popts,
						$pageid, isset( $prop['wikitext'] ) );
				} else { // This is an old revision, so get the text differently
					$this->content = $rev->getContent( Revision::FOR_THIS_USER, $this->getUser() );

					if ( $this->section !== false ) {
						$this->content = $this->getSectionContent(
							$this->content, $this->msg( 'revid', $rev->getId() )
						);
					}

					// Should we save old revision parses to the parser cache?
					$p_result = $this->content->getParserOutput( $titleObj, $rev->getId(), $popts );
				}
			} else { // Not $oldid, but $pageid or $page
				if ( $params['redirects'] ) {
					$reqParams = [
						'redirects' => '',
					];
					if ( !is_null( $pageid ) ) {
						$reqParams['pageids'] = $pageid;
					} else { // $page
						$reqParams['titles'] = $page;
					}
					$req = new FauxRequest( $reqParams );
					$main = new ApiMain( $req );
					$pageSet = new ApiPageSet( $main );
					$pageSet->execute();
					$redirValues = $pageSet->getRedirectTitlesAsResult( $this->getResult() );

					$to = $page;
					foreach ( $pageSet->getRedirectTitles() as $title ) {
						$to = $title->getFullText();
					}
					$pageParams = [ 'title' => $to ];
				} elseif ( !is_null( $pageid ) ) {
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

				// Don't pollute the parser cache when setting options that aren't
				// in ParserOptions::optionsHash()
				/// @todo: This should be handled closer to the actual cache instead of here, see T110269
				$suppressCache = $suppressCache ||
					$params['disablepp'] ||
					$params['disablelimitreport'] ||
					$params['preview'] ||
					$params['sectionpreview'] ||
					$params['disabletidy'];

				if ( $suppressCache ) {
					$this->content = $this->getContent( $pageObj, $pageid );
					$p_result = $this->content->getParserOutput( $titleObj, null, $popts );
				} else {
					// Potentially cached
					$p_result = $this->getParsedContent( $pageObj, $popts, $pageid,
						isset( $prop['wikitext'] ) );
				}
			}
		} else { // Not $oldid, $pageid, $page. Hence based on $text
			$titleObj = Title::newFromText( $title );
			if ( !$titleObj || $titleObj->isExternal() ) {
				$this->dieWithError( [ 'apierror-invalidtitle', wfEscapeWikiText( $title ) ] );
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
			$textProvided = !is_null( $text );

			if ( !$textProvided ) {
				if ( $titleProvided && ( $prop || $params['generatexml'] ) ) {
					$this->addWarning( 'apiwarn-parse-titlewithouttext' );
				}
				// Prevent warning from ContentHandler::makeContent()
				$text = '';
			}

			// If we are parsing text, do not use the content model of the default
			// API title, but default to wikitext to keep BC.
			if ( $textProvided && !$titleProvided && is_null( $model ) ) {
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
					if ( !is_null( $params['sectiontitle'] ) && $params['sectiontitle'] !== '' ) {
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
				if ( !is_null( $params['summary'] ) ||
					( !is_null( $params['sectiontitle'] ) && $this->section === 'new' )
				) {
					$result_array['parsedsummary'] = $this->formatSummary( $titleObj, $params );
					$result_array[ApiResult::META_BC_SUBELEMENTS][] = 'parsedsummary';
				}

				$result->addValue( null, $this->getModuleName(), $result_array );

				return;
			}

			// Not cached (save or load)
			if ( $params['pst'] ) {
				$p_result = $this->pstContent->getParserOutput( $titleObj, null, $popts );
			} else {
				$p_result = $this->content->getParserOutput( $titleObj, null, $popts );
			}
		}

		$result_array = [];

		$result_array['title'] = $titleObj->getPrefixedText();
		$result_array['pageid'] = $pageid ?: $pageObj->getId();

		if ( !is_null( $oldid ) ) {
			$result_array['revid'] = intval( $oldid );
		}

		if ( $params['redirects'] && !is_null( $redirValues ) ) {
			$result_array['redirects'] = $redirValues;
		}

		if ( $params['disabletoc'] ) {
			$p_result->setTOCEnabled( false );
		}

		if ( isset( $prop['text'] ) ) {
			$result_array['text'] = $p_result->getText();
			$result_array[ApiResult::META_BC_SUBELEMENTS][] = 'text';
		}

		if ( !is_null( $params['summary'] ) ||
			( !is_null( $params['sectiontitle'] ) && $this->section === 'new' )
		) {
			$result_array['parsedsummary'] = $this->formatSummary( $titleObj, $params );
			$result_array[ApiResult::META_BC_SUBELEMENTS][] = 'parsedsummary';
		}

		if ( isset( $prop['langlinks'] ) ) {
			$langlinks = $p_result->getLanguageLinks();

			if ( $params['effectivelanglinks'] ) {
				// Link flags are ignored for now, but may in the future be
				// included in the result.
				$linkFlags = [];
				Hooks::run( 'LanguageLinks', [ $titleObj, &$langlinks, &$linkFlags ] );
			}
		} else {
			$langlinks = false;
		}

		if ( isset( $prop['langlinks'] ) ) {
			$result_array['langlinks'] = $this->formatLangLinks( $langlinks );
		}
		if ( isset( $prop['categories'] ) ) {
			$result_array['categories'] = $this->formatCategoryLinks( $p_result->getCategories() );
		}
		if ( isset( $prop['categorieshtml'] ) ) {
			$result_array['categorieshtml'] = $this->categoriesHtml( $p_result->getCategories() );
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
			$result_array['displaytitle'] = $p_result->getDisplayTitle() ?:
				$titleObj->getPrefixedText();
		}

		if ( isset( $prop['headitems'] ) ) {
			$result_array['headitems'] = $this->formatHeadItems( $p_result->getHeadItems() );
			$this->addDeprecation( 'apiwarn-deprecation-parse-headitems', 'action=parse&prop=headitems' );
		}

		if ( isset( $prop['headhtml'] ) ) {
			$context = new DerivativeContext( $this->getContext() );
			$context->setTitle( $titleObj );
			$context->setWikiPage( $pageObj );

			// We need an OutputPage tied to $context, not to the
			// RequestContext at the root of the stack.
			$output = new OutputPage( $context );
			$output->addParserOutputMetadata( $p_result );

			$result_array['headhtml'] = $output->headElement( $context->getSkin() );
			$result_array[ApiResult::META_BC_SUBELEMENTS][] = 'headhtml';
		}

		if ( isset( $prop['modules'] ) ) {
			$result_array['modules'] = array_values( array_unique( $p_result->getModules() ) );
			$result_array['modulescripts'] = array_values( array_unique( $p_result->getModuleScripts() ) );
			$result_array['modulestyles'] = array_values( array_unique( $p_result->getModuleStyles() ) );
		}

		if ( isset( $prop['jsconfigvars'] ) ) {
			$result_array['jsconfigvars'] =
				ApiResult::addMetadataToResultVars( $p_result->getJsConfigVars() );
		}

		if ( isset( $prop['encodedjsconfigvars'] ) ) {
			$result_array['encodedjsconfigvars'] = FormatJson::encode(
				$p_result->getJsConfigVars(), false, FormatJson::ALL_OK
			);
			$result_array[ApiResult::META_SUBELEMENTS][] = 'encodedjsconfigvars';
		}

		if ( isset( $prop['modules'] ) &&
			!isset( $prop['jsconfigvars'] ) && !isset( $prop['encodedjsconfigvars'] ) ) {
			$this->addWarning( 'apiwarn-moduleswithoutvars' );
		}

		if ( isset( $prop['indicators'] ) ) {
			$result_array['indicators'] = (array)$p_result->getIndicators();
			ApiResult::setArrayType( $result_array['indicators'], 'BCkvp', 'name' );
		}

		if ( isset( $prop['iwlinks'] ) ) {
			$result_array['iwlinks'] = $this->formatIWLinks( $p_result->getInterwikiLinks() );
		}

		if ( isset( $prop['wikitext'] ) ) {
			$result_array['wikitext'] = $this->content->serialize( $format );
			$result_array[ApiResult::META_BC_SUBELEMENTS][] = 'wikitext';
			if ( !is_null( $this->pstContent ) ) {
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

			$wgParser->startExternalParse( $titleObj, $popts, Parser::OT_PREPROCESS );
			$dom = $wgParser->preprocessToDom( $this->content->getNativeData() );
			if ( is_callable( [ $dom, 'saveXML' ] ) ) {
				$xml = $dom->saveXML();
			} else {
				$xml = $dom->__toString();
			}
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
		$popts->setEditSection( !$params['disableeditsection'] );
		if ( $params['disabletidy'] ) {
			$popts->setTidy( false );
		}

		$reset = null;
		$suppressCache = false;
		Hooks::run( 'ApiMakeParserOptions',
			[ $popts, $pageObj->getTitle(), $params, $this, &$reset, &$suppressCache ] );

		return [ $popts, $reset, $suppressCache ];
	}

	/**
	 * @param WikiPage $page
	 * @param ParserOptions $popts
	 * @param int $pageId
	 * @param bool $getWikitext
	 * @return ParserOutput
	 */
	private function getParsedContent( WikiPage $page, $popts, $pageId = null, $getWikitext = false ) {
		$this->content = $this->getContent( $page, $pageId );

		if ( $this->section !== false && $this->content !== null ) {
			// Not cached (save or load)
			return $this->content->getParserOutput( $page->getTitle(), null, $popts );
		}

		// Try the parser cache first
		// getParserOutput will save to Parser cache if able
		$pout = $page->getParserOutput( $popts );
		if ( !$pout ) {
			$this->dieWithError( [ 'apierror-nosuchrevid', $page->getLatest() ] );
		}
		if ( $getWikitext ) {
			$this->content = $page->getContent( Revision::RAW );
		}

		return $pout;
	}

	/**
	 * Get the content for the given page and the requested section.
	 *
	 * @param WikiPage $page
	 * @param int $pageId
	 * @return Content
	 */
	private function getContent( WikiPage $page, $pageId = null ) {
		$content = $page->getContent( Revision::RAW ); // XXX: really raw?

		if ( $this->section !== false && $content !== null ) {
			$content = $this->getSectionContent(
				$content,
				!is_null( $pageId )
					? $this->msg( 'pageid', $pageId )
					: $page->getTitle()->getPrefixedText()
			);
		}
		return $content;
	}

	/**
	 * Extract the requested section from the given Content
	 *
	 * @param Content $content
	 * @param string|Message $what Identifies the content in error messages, e.g. page title.
	 * @return Content|bool
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
	 * @param Array $params the API parameters of the request
	 * @return Content|bool
	 */
	private function formatSummary( $title, $params ) {
		global $wgParser;
		$summary = !is_null( $params['summary'] ) ? $params['summary'] : '';
		$sectionTitle = !is_null( $params['sectiontitle'] ) ? $params['sectiontitle'] : '';

		if ( $this->section === 'new' && ( $sectionTitle === '' || $summary === '' ) ) {
			if ( $sectionTitle !== '' ) {
				$summary = $params['sectiontitle'];
			}
			if ( $summary !== '' ) {
				$summary = wfMessage( 'newsectionsummary' )
					->rawParams( $wgParser->stripSectionName( $summary ) )
						->inContentLanguage()->text();
			}
		}
		return Linker::formatComment( $summary, $title, $this->section === 'new' );
	}

	private function formatLangLinks( $links ) {
		$result = [];
		foreach ( $links as $link ) {
			$entry = [];
			$bits = explode( ':', $link, 2 );
			$title = Title::newFromText( $link );

			$entry['lang'] = $bits[0];
			if ( $title ) {
				$entry['url'] = wfExpandUrl( $title->getFullURL(), PROTO_CURRENT );
				// localised language name in 'uselang' language
				$entry['langname'] = Language::fetchLanguageName(
					$title->getInterwiki(),
					$this->getLanguage()->getCode()
				);

				// native language name
				$entry['autonym'] = Language::fetchLanguageName( $title->getInterwiki() );
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
		$lb = new LinkBatch;
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

		$linkCache = LinkCache::singleton();

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

	private function categoriesHtml( $categories ) {
		$context = $this->getContext();
		$context->getOutput()->addCategoryLinks( $categories );

		return $context->getSkin()->getCategories();
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
		return [
			'title' => null,
			'text' => [
				ApiBase::PARAM_TYPE => 'text',
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
					'headitems',
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
					'parsewarnings'
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [
					'parsetree' => [ 'apihelp-parse-paramvalue-prop-parsetree', CONTENT_MODEL_WIKITEXT ],
				],
			],
			'pst' => false,
			'onlypst' => false,
			'effectivelanglinks' => false,
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
			'disabletidy' => false,
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
			'contentformat' => [
				ApiBase::PARAM_TYPE => ContentHandler::getAllContentFormats(),
			],
			'contentmodel' => [
				ApiBase::PARAM_TYPE => ContentHandler::getContentModels(),
			]
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
}
