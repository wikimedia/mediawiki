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

	/** @var String $section */
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

		if ( !is_null( $page ) && ( !is_null( $text ) || $titleProvided ) ) {
			$this->dieUsage(
				'The page parameter cannot be used together with the text and title parameters',
				'params'
			);
		}

		$prop = array_flip( $params['prop'] );

		if ( isset( $params['section'] ) ) {
			$this->section = $params['section'];
		} else {
			$this->section = false;
		}

		// The parser needs $wgTitle to be set, apparently the
		// $title parameter in Parser::parse isn't enough *sigh*
		// TODO: Does this still need $wgTitle?
		global $wgParser, $wgTitle;

		// Currently unnecessary, code to act as a safeguard against any change
		// in current behavior of uselang
		$oldLang = null;
		if ( isset( $params['uselang'] )
			&& $params['uselang'] != $this->getContext()->getLanguage()->getCode()
		) {
			$oldLang = $this->getContext()->getLanguage(); // Backup language
			$this->getContext()->setLanguage( Language::factory( $params['uselang'] ) );
		}

		$redirValues = null;

		// Return result
		$result = $this->getResult();

		if ( !is_null( $oldid ) || !is_null( $pageid ) || !is_null( $page ) ) {
			if ( !is_null( $oldid ) ) {
				// Don't use the parser cache
				$rev = Revision::newFromID( $oldid );
				if ( !$rev ) {
					$this->dieUsage( "There is no revision ID $oldid", 'missingrev' );
				}
				if ( !$rev->userCan( Revision::DELETED_TEXT, $this->getUser() ) ) {
					$this->dieUsage( "You don't have permission to view deleted revisions", 'permissiondenied' );
				}

				$titleObj = $rev->getTitle();
				$wgTitle = $titleObj;
				$pageObj = WikiPage::factory( $titleObj );
				$popts = $this->makeParserOptions( $pageObj, $params );

				// If for some reason the "oldid" is actually the current revision, it may be cached
				if ( $rev->isCurrent() ) {
					// May get from/save to parser cache
					$p_result = $this->getParsedContent( $pageObj, $popts,
						$pageid, isset( $prop['wikitext'] ) );
				} else { // This is an old revision, so get the text differently
					$this->content = $rev->getContent( Revision::FOR_THIS_USER, $this->getUser() );

					if ( $this->section !== false ) {
						$this->content = $this->getSectionContent( $this->content, 'r' . $rev->getId() );
					}

					// Should we save old revision parses to the parser cache?
					$p_result = $this->content->getParserOutput( $titleObj, $rev->getId(), $popts );
				}
			} else { // Not $oldid, but $pageid or $page
				if ( $params['redirects'] ) {
					$reqParams = array(
						'action' => 'query',
						'redirects' => '',
					);
					if ( !is_null( $pageid ) ) {
						$reqParams['pageids'] = $pageid;
					} else { // $page
						$reqParams['titles'] = $page;
					}
					$req = new FauxRequest( $reqParams );
					$main = new ApiMain( $req );
					$main->execute();
					$data = $main->getResultData();
					$redirValues = isset( $data['query']['redirects'] )
						? $data['query']['redirects']
						: array();
					$to = $page;
					foreach ( (array)$redirValues as $r ) {
						$to = $r['to'];
					}
					$pageParams = array( 'title' => $to );
				} elseif ( !is_null( $pageid ) ) {
					$pageParams = array( 'pageid' => $pageid );
				} else { // $page
					$pageParams = array( 'title' => $page );
				}

				$pageObj = $this->getTitleOrPageId( $pageParams, 'fromdb' );
				$titleObj = $pageObj->getTitle();
				if ( !$titleObj || !$titleObj->exists() ) {
					$this->dieUsage( "The page you specified doesn't exist", 'missingtitle' );
				}
				$wgTitle = $titleObj;

				if ( isset( $prop['revid'] ) ) {
					$oldid = $pageObj->getLatest();
				}

				$popts = $this->makeParserOptions( $pageObj, $params );

				// Potentially cached
				$p_result = $this->getParsedContent( $pageObj, $popts, $pageid,
					isset( $prop['wikitext'] ) );
			}
		} else { // Not $oldid, $pageid, $page. Hence based on $text
			$titleObj = Title::newFromText( $title );
			if ( !$titleObj || $titleObj->isExternal() ) {
				$this->dieUsageMsg( array( 'invalidtitle', $title ) );
			}
			$wgTitle = $titleObj;
			if ( $titleObj->canExist() ) {
				$pageObj = WikiPage::factory( $titleObj );
			} else {
				// Do like MediaWiki::initializeArticle()
				$article = Article::newFromTitle( $titleObj, $this->getContext() );
				$pageObj = $article->getPage();
			}

			$popts = $this->makeParserOptions( $pageObj, $params );
			$textProvided = !is_null( $text );

			if ( !$textProvided ) {
				if ( $titleProvided && ( $prop || $params['generatexml'] ) ) {
					$this->setWarning(
						"'title' used without 'text', and parsed page properties were requested " .
						"(did you mean to use 'page' instead of 'title'?)"
					);
				}
				// Prevent warning from ContentHandler::makeContent()
				$text = '';
			}

			// If we are parsing text, do not use the content model of the default
			// API title, but default to wikitext to keep BC.
			if ( $textProvided && !$titleProvided && is_null( $model ) ) {
				$model = CONTENT_MODEL_WIKITEXT;
				$this->setWarning( "No 'title' or 'contentmodel' was given, assuming $model." );
			}

			try {
				$this->content = ContentHandler::makeContent( $text, $titleObj, $model, $format );
			} catch ( MWContentSerializationException $ex ) {
				$this->dieUsage( $ex->getMessage(), 'parseerror' );
			}

			if ( $this->section !== false ) {
				$this->content = $this->getSectionContent( $this->content, $titleObj->getText() );
			}

			if ( $params['pst'] || $params['onlypst'] ) {
				$this->pstContent = $this->content->preSaveTransform( $titleObj, $this->getUser(), $popts );
			}
			if ( $params['onlypst'] ) {
				// Build a result and bail out
				$result_array = array();
				$result_array['text'] = array();
				ApiResult::setContent( $result_array['text'], $this->pstContent->serialize( $format ) );
				if ( isset( $prop['wikitext'] ) ) {
					$result_array['wikitext'] = array();
					ApiResult::setContent( $result_array['wikitext'], $this->content->serialize( $format ) );
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

		$result_array = array();

		$result_array['title'] = $titleObj->getPrefixedText();

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
			$result_array['text'] = array();
			ApiResult::setContent( $result_array['text'], $p_result->getText() );
		}

		if ( !is_null( $params['summary'] ) ) {
			$result_array['parsedsummary'] = array();
			ApiResult::setContent(
				$result_array['parsedsummary'],
				Linker::formatComment( $params['summary'], $titleObj )
			);
		}

		if ( isset( $prop['langlinks'] ) || isset( $prop['languageshtml'] ) ) {
			$langlinks = $p_result->getLanguageLinks();

			if ( $params['effectivelanglinks'] ) {
				// Link flags are ignored for now, but may in the future be
				// included in the result.
				$linkFlags = array();
				wfRunHooks( 'LanguageLinks', array( $titleObj, &$langlinks, &$linkFlags ) );
			}
		} else {
			$langlinks = false;
		}

		if ( isset( $prop['langlinks'] ) ) {
			$result_array['langlinks'] = $this->formatLangLinks( $langlinks );
		}
		if ( isset( $prop['languageshtml'] ) ) {
			$languagesHtml = $this->languagesHtml( $langlinks );

			$result_array['languageshtml'] = array();
			ApiResult::setContent( $result_array['languageshtml'], $languagesHtml );
		}
		if ( isset( $prop['categories'] ) ) {
			$result_array['categories'] = $this->formatCategoryLinks( $p_result->getCategories() );
		}
		if ( isset( $prop['categorieshtml'] ) ) {
			$categoriesHtml = $this->categoriesHtml( $p_result->getCategories() );
			$result_array['categorieshtml'] = array();
			ApiResult::setContent( $result_array['categorieshtml'], $categoriesHtml );
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

		if ( isset( $prop['displaytitle'] ) ) {
			$result_array['displaytitle'] = $p_result->getDisplayTitle() ?
				$p_result->getDisplayTitle() :
				$titleObj->getPrefixedText();
		}

		if ( isset( $prop['headitems'] ) || isset( $prop['headhtml'] ) ) {
			$context = $this->getContext();
			$context->setTitle( $titleObj );
			$context->getOutput()->addParserOutputNoText( $p_result );

			if ( isset( $prop['headitems'] ) ) {
				$headItems = $this->formatHeadItems( $p_result->getHeadItems() );

				$css = $this->formatCss( $context->getOutput()->buildCssLinksArray() );

				$scripts = array( $context->getOutput()->getHeadScripts() );

				$result_array['headitems'] = array_merge( $headItems, $css, $scripts );
			}

			if ( isset( $prop['headhtml'] ) ) {
				$result_array['headhtml'] = array();
				ApiResult::setContent(
					$result_array['headhtml'],
					$context->getOutput()->headElement( $context->getSkin() )
				);
			}
		}

		if ( isset( $prop['iwlinks'] ) ) {
			$result_array['iwlinks'] = $this->formatIWLinks( $p_result->getInterwikiLinks() );
		}

		if ( isset( $prop['wikitext'] ) ) {
			$result_array['wikitext'] = array();
			ApiResult::setContent( $result_array['wikitext'], $this->content->serialize( $format ) );
			if ( !is_null( $this->pstContent ) ) {
				$result_array['psttext'] = array();
				ApiResult::setContent( $result_array['psttext'], $this->pstContent->serialize( $format ) );
			}
		}
		if ( isset( $prop['properties'] ) ) {
			$result_array['properties'] = $this->formatProperties( $p_result->getProperties() );
		}

		if ( isset( $prop['limitreportdata'] ) ) {
			$result_array['limitreportdata'] = $this->formatLimitReportData( $p_result->getLimitReportData() );
		}
		if ( isset( $prop['limitreporthtml'] ) ) {
			$limitreportHtml = EditPage::getPreviewLimitReport( $p_result );
			$result_array['limitreporthtml'] = array();
			ApiResult::setContent( $result_array['limitreporthtml'], $limitreportHtml );
		}

		if ( $params['generatexml'] ) {
			if ( $this->content->getModel() != CONTENT_MODEL_WIKITEXT ) {
				$this->dieUsage( "generatexml is only supported for wikitext content", "notwikitext" );
			}

			$wgParser->startExternalParse( $titleObj, $popts, OT_PREPROCESS );
			$dom = $wgParser->preprocessToDom( $this->content->getNativeData() );
			if ( is_callable( array( $dom, 'saveXML' ) ) ) {
				$xml = $dom->saveXML();
			} else {
				$xml = $dom->__toString();
			}
			$result_array['parsetree'] = array();
			ApiResult::setContent( $result_array['parsetree'], $xml );
		}

		$result_mapping = array(
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
			'properties' => 'pp',
			'limitreportdata' => 'lr',
		);
		$this->setIndexedTagNames( $result_array, $result_mapping );
		$result->addValue( null, $this->getModuleName(), $result_array );

		if ( !is_null( $oldLang ) ) {
			$this->getContext()->setLanguage( $oldLang ); // Reset language to $oldLang
		}
	}

	/**
	 * Constructs a ParserOptions object
	 *
	 * @param WikiPage $pageObj
	 * @param array $params
	 *
	 * @return ParserOptions
	 */
	protected function makeParserOptions( WikiPage $pageObj, array $params ) {
		wfProfileIn( __METHOD__ );

		$popts = $pageObj->makeParserOptions( $this->getContext() );
		$popts->enableLimitReport( !$params['disablepp'] );
		$popts->setIsPreview( $params['preview'] || $params['sectionpreview'] );
		$popts->setIsSectionPreview( $params['sectionpreview'] );

		wfProfileOut( __METHOD__ );

		return $popts;
	}

	/**
	 * @param $page WikiPage
	 * @param $popts ParserOptions
	 * @param $pageId Int
	 * @param $getWikitext Bool
	 * @return ParserOutput
	 */
	private function getParsedContent( WikiPage $page, $popts, $pageId = null, $getWikitext = false ) {
		$this->content = $page->getContent( Revision::RAW ); //XXX: really raw?

		if ( $this->section !== false && $this->content !== null ) {
			$this->content = $this->getSectionContent(
				$this->content,
				!is_null( $pageId ) ? 'page id ' . $pageId : $page->getTitle()->getText()
			);

			// Not cached (save or load)
			return $this->content->getParserOutput( $page->getTitle(), null, $popts );
		}

		// Try the parser cache first
		// getParserOutput will save to Parser cache if able
		$pout = $page->getParserOutput( $popts );
		if ( !$pout ) {
			$this->dieUsage( "There is no revision ID {$page->getLatest()}", 'missingrev' );
		}
		if ( $getWikitext ) {
			$this->content = $page->getContent( Revision::RAW );
		}

		return $pout;
	}

	private function getSectionContent( Content $content, $what ) {
		// Not cached (save or load)
		$section = $content->getSection( $this->section );
		if ( $section === false ) {
			$this->dieUsage( "There is no section {$this->section} in " . $what, 'nosuchsection' );
		}
		if ( $section === null ) {
			$this->dieUsage( "Sections are not supported by " . $what, 'nosuchsection' );
			$section = false;
		}

		return $section;
	}

	private function formatLangLinks( $links ) {
		$result = array();
		foreach ( $links as $link ) {
			$entry = array();
			$bits = explode( ':', $link, 2 );
			$title = Title::newFromText( $link );

			$entry['lang'] = $bits[0];
			if ( $title ) {
				$entry['url'] = wfExpandUrl( $title->getFullURL(), PROTO_CURRENT );
				// localised language name in user language (maybe set by uselang=)
				$entry['langname'] = Language::fetchLanguageName( $title->getInterwiki(), $this->getLanguage()->getCode() );
				// native language name
				$entry['autonym'] = Language::fetchLanguageName( $title->getInterwiki() );
			}
			ApiResult::setContent( $entry, $bits[1] );
			$result[] = $entry;
		}

		return $result;
	}

	private function formatCategoryLinks( $links ) {
		$result = array();

		if ( !$links ) {
			return $result;
		}

		// Fetch hiddencat property
		$lb = new LinkBatch;
		$lb->setArray( array( NS_CATEGORY => $links ) );
		$db = $this->getDB();
		$res = $db->select( array( 'page', 'page_props' ),
			array( 'page_title', 'pp_propname' ),
			$lb->constructSet( 'page', $db ),
			__METHOD__,
			array(),
			array( 'page_props' => array(
				'LEFT JOIN', array( 'pp_propname' => 'hiddencat', 'pp_page = page_id' )
			) )
		);
		$hiddencats = array();
		foreach ( $res as $row ) {
			$hiddencats[$row->page_title] = isset( $row->pp_propname );
		}

		foreach ( $links as $link => $sortkey ) {
			$entry = array();
			$entry['sortkey'] = $sortkey;
			ApiResult::setContent( $entry, $link );
			if ( !isset( $hiddencats[$link] ) ) {
				$entry['missing'] = '';
			} elseif ( $hiddencats[$link] ) {
				$entry['hidden'] = '';
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

	/**
	 * @deprecated since 1.18 No modern skin generates language links this way,
	 * please use language links data to generate your own HTML.
	 * @param $languages array
	 * @return string
	 */
	private function languagesHtml( $languages ) {
		wfDeprecated( __METHOD__, '1.18' );
		$this->setWarning( '"action=parse&prop=languageshtml" is deprecated ' .
			'and will be removed in MediaWiki 1.24. Use "prop=langlinks" ' .
			'to generate your own HTML.' );

		global $wgContLang, $wgHideInterlanguageLinks;

		if ( $wgHideInterlanguageLinks || count( $languages ) == 0 ) {
			return '';
		}

		$s = htmlspecialchars( wfMessage( 'otherlanguages' )->text() .
			wfMessage( 'colon-separator' )->text() );

		$langs = array();
		foreach ( $languages as $l ) {
			$nt = Title::newFromText( $l );
			$text = Language::fetchLanguageName( $nt->getInterwiki() );

			$langs[] = Html::element( 'a',
				array( 'href' => $nt->getFullURL(), 'title' => $nt->getText(), 'class' => 'external' ),
				$text == '' ? $l : $text );
		}

		$s .= implode( wfMessage( 'pipe-separator' )->escaped(), $langs );

		if ( $wgContLang->isRTL() ) {
			$s = Html::rawElement( 'span', array( 'dir' => 'LTR' ), $s );
		}

		return $s;
	}

	private function formatLinks( $links ) {
		$result = array();
		foreach ( $links as $ns => $nslinks ) {
			foreach ( $nslinks as $title => $id ) {
				$entry = array();
				$entry['ns'] = $ns;
				ApiResult::setContent( $entry, Title::makeTitle( $ns, $title )->getFullText() );
				if ( $id != 0 ) {
					$entry['exists'] = '';
				}
				$result[] = $entry;
			}
		}

		return $result;
	}

	private function formatIWLinks( $iw ) {
		$result = array();
		foreach ( $iw as $prefix => $titles ) {
			foreach ( array_keys( $titles ) as $title ) {
				$entry = array();
				$entry['prefix'] = $prefix;

				$title = Title::newFromText( "{$prefix}:{$title}" );
				if ( $title ) {
					$entry['url'] = wfExpandUrl( $title->getFullURL(), PROTO_CURRENT );
				}

				ApiResult::setContent( $entry, $title->getFullText() );
				$result[] = $entry;
			}
		}

		return $result;
	}

	private function formatHeadItems( $headItems ) {
		$result = array();
		foreach ( $headItems as $tag => $content ) {
			$entry = array();
			$entry['tag'] = $tag;
			ApiResult::setContent( $entry, $content );
			$result[] = $entry;
		}

		return $result;
	}

	private function formatProperties( $properties ) {
		$result = array();
		foreach ( $properties as $name => $value ) {
			$entry = array();
			$entry['name'] = $name;
			ApiResult::setContent( $entry, $value );
			$result[] = $entry;
		}

		return $result;
	}

	private function formatCss( $css ) {
		$result = array();
		foreach ( $css as $file => $link ) {
			$entry = array();
			$entry['file'] = $file;
			ApiResult::setContent( $entry, $link );
			$result[] = $entry;
		}

		return $result;
	}

	private function formatLimitReportData( $limitReportData ) {
		$result = array();
		$apiResult = $this->getResult();

		foreach ( $limitReportData as $name => $value ) {
			$entry = array();
			$entry['name'] = $name;
			if ( !is_array( $value ) ) {
				$value = array( $value );
			}
			$apiResult->setIndexedTagName( $value, 'param' );
			$apiResult->setIndexedTagName_recursive( $value, 'param' );
			$entry = array_merge( $entry, $value );
			$result[] = $entry;
		}

		return $result;
	}

	private function setIndexedTagNames( &$array, $mapping ) {
		foreach ( $mapping as $key => $name ) {
			if ( isset( $array[$key] ) ) {
				$this->getResult()->setIndexedTagName( $array[$key], $name );
			}
		}
	}

	public function getAllowedParams() {
		return array(
			'title' => null,
			'text' => null,
			'summary' => null,
			'page' => null,
			'pageid' => array(
				ApiBase::PARAM_TYPE => 'integer',
			),
			'redirects' => false,
			'oldid' => array(
				ApiBase::PARAM_TYPE => 'integer',
			),
			'prop' => array(
				ApiBase::PARAM_DFLT => 'text|langlinks|categories|links|templates|' .
					'images|externallinks|sections|revid|displaytitle|iwlinks|properties',
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => array(
					'text',
					'langlinks',
					'languageshtml',
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
					'iwlinks',
					'wikitext',
					'properties',
					'limitreportdata',
					'limitreporthtml',
				)
			),
			'pst' => false,
			'onlypst' => false,
			'effectivelanglinks' => false,
			'uselang' => null,
			'section' => null,
			'disablepp' => false,
			'generatexml' => false,
			'preview' => false,
			'sectionpreview' => false,
			'disabletoc' => false,
			'contentformat' => array(
				ApiBase::PARAM_TYPE => ContentHandler::getAllContentFormats(),
			),
			'contentmodel' => array(
				ApiBase::PARAM_TYPE => ContentHandler::getContentModels(),
			)
		);
	}

	public function getParamDescription() {
		$p = $this->getModulePrefix();
		$wikitext = CONTENT_MODEL_WIKITEXT;

		return array(
			'text' => "Text to parse. Use {$p}title or {$p}contentmodel to control the content model",
			'summary' => 'Summary to parse',
			'redirects' => "If the {$p}page or the {$p}pageid parameter is set to a redirect, resolve it",
			'title' => "Title of page the text belongs to. " .
				"If omitted, {$p}contentmodel must be specified, and \"API\" will be used as the title",
			'page' => "Parse the content of this page. Cannot be used together with {$p}text and {$p}title",
			'pageid' => "Parse the content of this page. Overrides {$p}page",
			'oldid' => "Parse the content of this revision. Overrides {$p}page and {$p}pageid",
			'prop' => array(
				'Which pieces of information to get',
				' text           - Gives the parsed text of the wikitext',
				' langlinks      - Gives the language links in the parsed wikitext',
				' categories     - Gives the categories in the parsed wikitext',
				' categorieshtml - Gives the HTML version of the categories',
				' languageshtml  - DEPRECATED. Will be removed in MediaWiki 1.24.',
				'                  Gives the HTML version of the language links',
				' links          - Gives the internal links in the parsed wikitext',
				' templates      - Gives the templates in the parsed wikitext',
				' images         - Gives the images in the parsed wikitext',
				' externallinks  - Gives the external links in the parsed wikitext',
				' sections       - Gives the sections in the parsed wikitext',
				' revid          - Adds the revision ID of the parsed page',
				' displaytitle   - Adds the title of the parsed wikitext',
				' headitems      - Gives items to put in the <head> of the page',
				' headhtml       - Gives parsed <head> of the page',
				' iwlinks        - Gives interwiki links in the parsed wikitext',
				' wikitext       - Gives the original wikitext that was parsed',
				' properties     - Gives various properties defined in the parsed wikitext',
				' limitreportdata - Gives the limit report in a structured way.',
				"                   Gives no data, when {$p}disablepp is set.",
				' limitreporthtml - Gives the HTML version of the limit report.',
				"                   Gives no data, when {$p}disablepp is set.",
			),
			'effectivelanglinks' => array(
				'Includes language links supplied by extensions',
				'(for use with prop=langlinks|languageshtml)',
			),
			'pst' => array(
				'Do a pre-save transform on the input before parsing it',
				"Only valid when used with {$p}text",
			),
			'onlypst' => array(
				'Do a pre-save transform (PST) on the input, but don\'t parse it',
				'Returns the same wikitext, after a PST has been applied.',
				"Only valid when used with {$p}text",
			),
			'uselang' => 'Which language to parse the request in',
			'section' => 'Only retrieve the content of this section number',
			'disablepp' => 'Disable the PP Report from the parser output',
			'generatexml' => "Generate XML parse tree (requires contentmodel=$wikitext)",
			'preview' => 'Parse in preview mode',
			'sectionpreview' => 'Parse in section preview mode (enables preview mode too)',
			'disabletoc' => 'Disable table of contents in output',
			'contentformat' => array(
				'Content serialization format used for the input text',
				"Only valid when used with {$p}text",
			),
			'contentmodel' => array(
				"Content model of the input text. If omitted, ${p}title must be specified, " .
					"and default will be the model of the specified ${p}title",
				"Only valid when used with {$p}text",
			),
		);
	}

	public function getDescription() {
		$p = $this->getModulePrefix();

		return array(
			'Parses content and returns parser output.',
			'See the various prop-Modules of action=query to get information from the current' .
				'version of a page.',
			'There are several ways to specify the text to parse:',
			"1) Specify a page or revision, using {$p}page, {$p}pageid, or {$p}oldid.",
			"2) Specify content explicitly, using {$p}text, {$p}title, and {$p}contentmodel.",
			"3) Specify only a summary to parse. {$p}prop should be given an empty value.",
		);
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array(
				'code' => 'params',
				'info' => 'The page parameter cannot be used together with the text and title parameters'
			),
			array( 'code' => 'missingrev', 'info' => 'There is no revision ID oldid' ),
			array(
				'code' => 'permissiondenied',
				'info' => 'You don\'t have permission to view deleted revisions'
			),
			array( 'code' => 'missingtitle', 'info' => 'The page you specified doesn\'t exist' ),
			array( 'code' => 'nosuchsection', 'info' => 'There is no section sectionnumber in page' ),
			array( 'nosuchpageid' ),
			array( 'invalidtitle', 'title' ),
			array( 'code' => 'parseerror', 'info' => 'Failed to parse the given text.' ),
			array(
				'code' => 'notwikitext',
				'info' => 'The requested operation is only supported on wikitext content.'
			),
		) );
	}

	public function getExamples() {
		return array(
			'api.php?action=parse&page=Project:Sandbox' => 'Parse a page',
			'api.php?action=parse&text={{Project:Sandbox}}&contentmodel=wikitext' => 'Parse wikitext',
			'api.php?action=parse&text={{PAGENAME}}&title=Test'
				=> 'Parse wikitext, specifying the page title',
			'api.php?action=parse&summary=Some+[[link]]&prop=' => 'Parse a summary',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Parsing_wikitext#parse';
	}
}
