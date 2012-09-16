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
	private $section, $text, $pstText = null;

	public function __construct( $main, $action ) {
		parent::__construct( $main, $action );
	}

	public function execute() {
		// The data is hot but user-dependent, like page views, so we set vary cookies
		$this->getMain()->setCacheMode( 'anon-public-user-private' );

		// Get parameters
		$params = $this->extractRequestParams();
		$text = $params['text'];
		$title = $params['title'];
		$page = $params['page'];
		$pageid = $params['pageid'];
		$oldid = $params['oldid'];

		if ( !is_null( $page ) && ( !is_null( $text ) || $title != 'API' ) ) {
			$this->dieUsage( 'The page parameter cannot be used together with the text and title parameters', 'params' );
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

		// Currently unnecessary, code to act as a safeguard against any change in current behaviour of uselang breaks
		$oldLang = null;
		if ( isset( $params['uselang'] ) && $params['uselang'] != $this->getContext()->getLanguage()->getCode() ) {
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
				$popts = $pageObj->makeParserOptions( $this->getContext() );
				$popts->enableLimitReport( !$params['disablepp'] );

				// If for some reason the "oldid" is actually the current revision, it may be cached
				if ( $titleObj->getLatestRevID() === intval( $oldid ) )  {
					// May get from/save to parser cache
					$p_result = $this->getParsedSectionOrText( $pageObj, $popts, $pageid,
						 isset( $prop['wikitext'] ) ) ;
				} else { // This is an old revision, so get the text differently
					$this->text = $rev->getText( Revision::FOR_THIS_USER, $this->getUser() );

					if ( $this->section !== false ) {
						$this->text = $this->getSectionText( $this->text, 'r' . $rev->getId() );
					}

					// Should we save old revision parses to the parser cache?
					$p_result = $wgParser->parse( $this->text, $titleObj, $popts );
				}
			} else { // Not $oldid, but $pageid or $page
				if ( $params['redirects'] ) {
					$reqParams = array(
						'action' => 'query',
						'redirects' => '',
					);
					if ( !is_null ( $pageid ) ) {
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
				$wgTitle = $titleObj;

				if ( isset( $prop['revid'] ) ) {
					$oldid = $pageObj->getLatest();
				}

				$popts = $pageObj->makeParserOptions( $this->getContext() );
				$popts->enableLimitReport( !$params['disablepp'] );

				// Potentially cached
				$p_result = $this->getParsedSectionOrText( $pageObj, $popts, $pageid,
					 isset( $prop['wikitext'] ) ) ;
			}
		} else { // Not $oldid, $pageid, $page. Hence based on $text

			if ( is_null( $text ) ) {
				$this->dieUsage( 'The text parameter should be passed with the title parameter. Should you be using the "page" parameter instead?', 'params' );
			}
			$this->text = $text;
			$titleObj = Title::newFromText( $title );
			if ( !$titleObj ) {
				$this->dieUsageMsg( array( 'invalidtitle', $title ) );
			}
			$wgTitle = $titleObj;
			$pageObj = WikiPage::factory( $titleObj );

			$popts = $pageObj->makeParserOptions( $this->getContext() );
			$popts->enableLimitReport( !$params['disablepp'] );

			if ( $this->section !== false ) {
				$this->text = $this->getSectionText( $this->text, $titleObj->getText() );
			}

			if ( $params['pst'] || $params['onlypst'] ) {
				$this->pstText = $wgParser->preSaveTransform( $this->text, $titleObj, $this->getUser(), $popts );
			}
			if ( $params['onlypst'] ) {
				// Build a result and bail out
				$result_array = array();
				$result_array['text'] = array();
				$result->setContent( $result_array['text'], $this->pstText );
				if ( isset( $prop['wikitext'] ) ) {
					$result_array['wikitext'] = array();
					$result->setContent( $result_array['wikitext'], $this->text );
				}
				$result->addValue( null, $this->getModuleName(), $result_array );
				return;
			}
			// Not cached (save or load)
			$p_result = $wgParser->parse( $params['pst'] ? $this->pstText : $this->text, $titleObj, $popts );
		}

		$result_array = array();

		$result_array['title'] = $titleObj->getPrefixedText();

		if ( !is_null( $oldid ) ) {
			$result_array['revid'] = intval( $oldid );
		}

		if ( $params['redirects'] && !is_null( $redirValues ) ) {
			$result_array['redirects'] = $redirValues;
		}

		if ( isset( $prop['text'] ) ) {
			$result_array['text'] = array();
			$result->setContent( $result_array['text'], $p_result->getText() );
		}

		if ( !is_null( $params['summary'] ) ) {
			$result_array['parsedsummary'] = array();
			$result->setContent( $result_array['parsedsummary'], Linker::formatComment( $params['summary'], $titleObj ) );
		}

		if ( isset( $prop['langlinks'] ) ) {
			$result_array['langlinks'] = $this->formatLangLinks( $p_result->getLanguageLinks() );
		}
		if ( isset( $prop['languageshtml'] ) ) {
			$languagesHtml = $this->languagesHtml( $p_result->getLanguageLinks() );
			$result_array['languageshtml'] = array();
			$result->setContent( $result_array['languageshtml'], $languagesHtml );
		}
		if ( isset( $prop['categories'] ) ) {
			$result_array['categories'] = $this->formatCategoryLinks( $p_result->getCategories() );
		}
		if ( isset( $prop['categorieshtml'] ) ) {
			$categoriesHtml = $this->categoriesHtml( $p_result->getCategories() );
			$result_array['categorieshtml'] = array();
			$result->setContent( $result_array['categorieshtml'], $categoriesHtml );
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
				$result->setContent( $result_array['headhtml'], $context->getOutput()->headElement( $context->getSkin() ) );
			}
		}

		if ( isset( $prop['iwlinks'] ) ) {
			$result_array['iwlinks'] = $this->formatIWLinks( $p_result->getInterwikiLinks() );
		}

		if ( isset( $prop['wikitext'] ) ) {
			$result_array['wikitext'] = array();
			$result->setContent( $result_array['wikitext'], $this->text );
			if ( !is_null( $this->pstText ) ) {
				$result_array['psttext'] = array();
				$result->setContent( $result_array['psttext'], $this->pstText );
			}
		}
		if ( isset( $prop['properties'] ) ) {
			$result_array['properties'] = $this->formatProperties( $p_result->getProperties() );
		}

		if ( $params['generatexml'] ) {
			$wgParser->startExternalParse( $titleObj, $popts, OT_PREPROCESS );
			$dom = $wgParser->preprocessToDom( $this->text );
			if ( is_callable( array( $dom, 'saveXML' ) ) ) {
				$xml = $dom->saveXML();
			} else {
				$xml = $dom->__toString();
			}
			$result_array['parsetree'] = array();
			$result->setContent( $result_array['parsetree'], $xml );
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
		);
		$this->setIndexedTagNames( $result_array, $result_mapping );
		$result->addValue( null, $this->getModuleName(), $result_array );

		if ( !is_null( $oldLang ) ) {
			$this->getContext()->setLanguage( $oldLang ); // Reset language to $oldLang
		}
	}

	/**
	 * @param $page WikiPage
	 * @param $popts ParserOptions
	 * @param $pageId Int
	 * @param $getWikitext Bool
	 * @return ParserOutput
	 */
	private function getParsedSectionOrText( $page, $popts, $pageId = null, $getWikitext = false ) {
		global $wgParser;

		if ( $this->section !== false ) {
			$this->text = $this->getSectionText( $page->getRawText(), !is_null( $pageId )
					? 'page id ' . $pageId : $page->getTitle()->getPrefixedText() );

			// Not cached (save or load)
			return $wgParser->parse( $this->text, $page->getTitle(), $popts );
		} else {
			// Try the parser cache first
			// getParserOutput will save to Parser cache if able
			$pout = $page->getParserOutput( $popts );
			if ( !$pout ) {
				$this->dieUsage( "There is no revision ID {$page->getLatest()}", 'missingrev' );
			}
			if ( $getWikitext ) {
				$this->text = $page->getRawText();
			}
			return $pout;
		}
	}

	private function getSectionText( $text, $what ) {
		global $wgParser;
		// Not cached (save or load)
		$text = $wgParser->getSection( $text, $this->section, false );
		if ( $text === false ) {
			$this->dieUsage( "There is no section {$this->section} in " . $what, 'nosuchsection' );
		}
		return $text;
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
			}
			$this->getResult()->setContent( $entry, $bits[1] );
			$result[] = $entry;
		}
		return $result;
	}

	private function formatCategoryLinks( $links ) {
		$result = array();
		foreach ( $links as $link => $sortkey ) {
			$entry = array();
			$entry['sortkey'] = $sortkey;
			$this->getResult()->setContent( $entry, $link );
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
	 * @deprecated since 1.18 No modern skin generates language links this way, please use language links
	 *                        data to generate your own HTML.
	 * @param $languages array
	 * @return string
	 */
	private function languagesHtml( $languages ) {
		wfDeprecated( __METHOD__, '1.18' );

		global $wgContLang, $wgHideInterlanguageLinks;

		if ( $wgHideInterlanguageLinks || count( $languages ) == 0 ) {
			return '';
		}

		$s = htmlspecialchars( wfMessage( 'otherlanguages' )->text() . wfMessage( 'colon-separator' )->text() );

		$langs = array();
		foreach ( $languages as $l ) {
			$nt = Title::newFromText( $l );
			$text = Language::fetchLanguageName( $nt->getInterwiki() );

			$langs[] = Html::element( 'a',
				array( 'href' => $nt->getFullURL(), 'title' => $nt->getText(), 'class' => "external" ),
				$text == '' ? $l : $text );
		}

		$s .= implode( wfMessage( 'pipe-separator' )->escaped(), $langs );

		if ( $wgContLang->isRTL() ) {
			$s = Html::rawElement( 'span', array( 'dir' => "LTR" ), $s );
		}

		return $s;
	}

	private function formatLinks( $links ) {
		$result = array();
		foreach ( $links as $ns => $nslinks ) {
			foreach ( $nslinks as $title => $id ) {
				$entry = array();
				$entry['ns'] = $ns;
				$this->getResult()->setContent( $entry, Title::makeTitle( $ns, $title )->getFullText() );
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

				$this->getResult()->setContent( $entry, $title->getFullText() );
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
			$this->getResult()->setContent( $entry, $content );
			$result[] = $entry;
		}
		return $result;
	}

	private function formatProperties( $properties ) {
		$result = array();
		foreach ( $properties as $name => $value ) {
			$entry = array();
			$entry['name'] = $name;
			$this->getResult()->setContent( $entry, $value );
			$result[] = $entry;
		}
		return $result;
	}

	private function formatCss( $css ) {
		$result = array();
		foreach ( $css as $file => $link ) {
			$entry = array();
			$entry['file'] = $file;
			$this->getResult()->setContent( $entry, $link );
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
			'title' => array(
				ApiBase::PARAM_DFLT => 'API',
			),
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
				ApiBase::PARAM_DFLT => 'text|langlinks|categories|links|templates|images|externallinks|sections|revid|displaytitle|iwlinks|properties',
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
				)
			),
			'pst' => false,
			'onlypst' => false,
			'uselang' => null,
			'section' => null,
			'disablepp' => false,
			'generatexml' => false,
		);
	}

	public function getParamDescription() {
		$p = $this->getModulePrefix();
		return array(
			'text' => 'Wikitext to parse',
			'summary' => 'Summary to parse',
			'redirects' => "If the {$p}page or the {$p}pageid parameter is set to a redirect, resolve it",
			'title' => 'Title of page the text belongs to',
			'page' => "Parse the content of this page. Cannot be used together with {$p}text and {$p}title",
			'pageid' => "Parse the content of this page. Overrides {$p}page",
			'oldid' => "Parse the content of this revision. Overrides {$p}page and {$p}pageid",
			'prop' => array(
				'Which pieces of information to get',
				' text           - Gives the parsed text of the wikitext',
				' langlinks      - Gives the language links in the parsed wikitext',
				' categories     - Gives the categories in the parsed wikitext',
				' categorieshtml - Gives the HTML version of the categories',
				' languageshtml  - Gives the HTML version of the language links',
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
			),
			'pst' => array(
				'Do a pre-save transform on the input before parsing it',
				'Ignored if page, pageid or oldid is used'
			),
			'onlypst' => array(
				'Do a pre-save transform (PST) on the input, but don\'t parse it',
				'Returns the same wikitext, after a PST has been applied. Ignored if page, pageid or oldid is used'
			),
			'uselang' => 'Which language to parse the request in',
			'section' => 'Only retrieve the content of this section number',
			'disablepp' => 'Disable the PP Report from the parser output',
			'generatexml' => 'Generate XML parse tree',
		);
	}

	public function getDescription() {
		return array(
			'Parses wikitext and returns parser output',
			'See the various prop-Modules of action=query to get information from the current version of a page',
		);
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'params', 'info' => 'The page parameter cannot be used together with the text and title parameters' ),
			array( 'code' => 'params', 'info' => 'The text parameter should be passed with the title parameter. Should you be using the "page" parameter instead?' ),
			array( 'code' => 'missingrev', 'info' => 'There is no revision ID oldid' ),
			array( 'code' => 'permissiondenied', 'info' => 'You don\'t have permission to view deleted revisions' ),
			array( 'code' => 'missingtitle', 'info' => 'The page you specified doesn\'t exist' ),
			array( 'code' => 'nosuchsection', 'info' => 'There is no section sectionnumber in page' ),
			array( 'nosuchpageid' ),
			array( 'invalidtitle', 'title' ),
		) );
	}

	public function getExamples() {
		return array(
			'api.php?action=parse&text={{Project:Sandbox}}'
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Parsing_wikitext#parse';
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
