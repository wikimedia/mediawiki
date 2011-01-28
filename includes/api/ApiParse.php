<?php
/**
 *
 *
 * Created on Dec 01, 2007
 *
 * Copyright © 2007 Yuri Astrakhan <Firstname><Lastname>@gmail.com
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

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once( "ApiBase.php" );
}

/**
 * @ingroup API
 */
class ApiParse extends ApiBase {

	private $section;

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
		global $wgParser, $wgUser, $wgTitle, $wgLang;

		// Currently unnecessary, code to act as a safeguard against any change in current behaviour of uselang breaks
		$oldLang = null;
		if ( isset( $params['uselang'] ) && $params['uselang'] != $wgLang->getCode() ) {
			$oldLang = $wgLang; // Backup wgLang
			$wgLang = Language::factory( $params['uselang'] );
		}

		$popts = new ParserOptions();
		$popts->setTidy( true );
		$popts->enableLimitReport( !$params['disablepp'] );

		$redirValues = null;

		if ( !is_null( $oldid ) || !is_null( $pageid ) || !is_null( $page ) ) {

			if ( !is_null( $oldid ) ) {
				// Don't use the parser cache
				$rev = Revision::newFromID( $oldid );
				if ( !$rev ) {
					$this->dieUsage( "There is no revision ID $oldid", 'missingrev' );
				}
				if ( !$rev->userCan( Revision::DELETED_TEXT ) ) {
					$this->dieUsage( "You don't have permission to view deleted revisions", 'permissiondenied' );
				}

				$titleObj = $rev->getTitle();

				$wgTitle = $titleObj;

				// If for some reason the "oldid" is actually the current revision, it may be cached
				if ( $titleObj->getLatestRevID() === intval( $oldid ) )  {
					$articleObj = new Article( $titleObj, 0 );

					$p_result = $this->getParsedSectionOrText( $articleObj, $titleObj, $popts, $pageid ) ;

				} else { // This is an old revision, so get the text differently
					$text = $rev->getText( Revision::FOR_THIS_USER );

					$wgTitle = $titleObj;

					if ( $this->section !== false ) {
						$text = $this->getSectionText( $text, 'r' . $rev->getId() );
					}

					$p_result = $wgParser->parse( $text, $titleObj, $popts );
				}

			} else { // Not $oldid

				if ( !is_null ( $pageid ) ) {
					$titleObj = Title::newFromID( $pageid );

					if ( !$titleObj ) {
						$this->dieUsageMsg( array( 'nosuchpageid', $pageid ) );
					}
				} else { // $page

					if ( $params['redirects'] ) {
						$req = new FauxRequest( array(
							'action' => 'query',
							'redirects' => '',
							'titles' => $page
						) );
						$main = new ApiMain( $req );
						$main->execute();
						$data = $main->getResultData();
						$redirValues = @$data['query']['redirects'];
						$to = $page;
						foreach ( (array)$redirValues as $r ) {
							$to = $r['to'];
						}
					} else {
						$to = $page;
					}
					$titleObj = Title::newFromText( $to );
					if ( !$titleObj || !$titleObj->exists() ) {
						$this->dieUsage( "The page you specified doesn't exist", 'missingtitle' );
					}
				}
				$wgTitle = $titleObj;

				$articleObj = new Article( $titleObj, 0 );
				if ( isset( $prop['revid'] ) ) {
					$oldid = $articleObj->getRevIdFetched();
				}

				$p_result = $this->getParsedSectionOrText( $articleObj, $titleObj, $popts, $pageid ) ;
			}

		} else { // Not $oldid, $pageid, $page. Hence based on $text

			$titleObj = Title::newFromText( $title );
			if ( !$titleObj ) {
				$titleObj = Title::newFromText( 'API' );
			}
			$wgTitle = $titleObj;

			if ( $this->section !== false ) {
				$text = $this->getSectionText( $text, $titleObj->getText() );
			}

			if ( $params['pst'] || $params['onlypst'] ) {
				$text = $wgParser->preSaveTransform( $text, $titleObj, $wgUser, $popts );
			}
			if ( $params['onlypst'] ) {
				// Build a result and bail out
				$result_array['text'] = array();
				$this->getResult()->setContent( $result_array['text'], $text );
				$this->getResult()->addValue( null, $this->getModuleName(), $result_array );
				return;
			}
			$p_result = $wgParser->parse( $text, $titleObj, $popts );
		}

		// Return result
		$result = $this->getResult();
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
			$result->setContent( $result_array['parsedsummary'], $wgUser->getSkin()->formatComment( $params['summary'], $titleObj ) );
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
			$out = new OutputPage;
			$out->addParserOutputNoText( $p_result );
			$userSkin = $wgUser->getSkin();
		}

		if ( isset( $prop['headitems'] ) ) {
			$headItems = $this->formatHeadItems( $p_result->getHeadItems() );

			$userSkin->setupUserCss( $out );
			$css = $this->formatCss( $out->buildCssLinksArray() );

			$scripts = array( $out->getHeadScripts( $userSkin ) );

			$result_array['headitems'] = array_merge( $headItems, $css, $scripts );
		}

		if ( isset( $prop['headhtml'] ) ) {
			$result_array['headhtml'] = array();
			$result->setContent( $result_array['headhtml'], $out->headElement( $userSkin ) );
		}

		if ( isset( $prop['iwlinks'] ) ) {
			$result_array['iwlinks'] = $this->formatIWLinks( $p_result->getInterwikiLinks() );
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
		);
		$this->setIndexedTagNames( $result_array, $result_mapping );
		$result->addValue( null, $this->getModuleName(), $result_array );

		if ( !is_null( $oldLang ) ) {
			$wgLang = $oldLang; // Reset $wgLang to $oldLang
		}
	}

	/**
	 * @param  $articleObj Article
	 * @param  $titleObj Title
	 * @param  $popts ParserOptions
	 * @param  $pageId Int
	 * @return ParserOutput
	 */
	private function getParsedSectionOrText( $articleObj, $titleObj, $popts, $pageId = null ) {
		if ( $this->section !== false ) {
			global $wgParser;

			$text = $this->getSectionText( $articleObj->getRawText(), !is_null ( $pageId )
					? 'page id ' . $pageId : $titleObj->getText() );

			return $wgParser->parse( $text, $titleObj, $popts );
		} else {
			// Try the parser cache first
			return $articleObj->getParserOutput();
		}
	}

	private function getSectionText( $text, $what ) {
		global $wgParser;
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
				$entry['url'] = $title->getFullURL();
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
		global $wgOut, $wgUser;
		$wgOut->addCategoryLinks( $categories );
		$sk = $wgUser->getSkin();
		return $sk->getCategories();
	}

	private function languagesHtml( $languages ) {
		global $wgOut, $wgUser;
		$wgOut->setLanguageLinks( $languages );
		$sk = $wgUser->getSkin();
		return $sk->otherLanguages();
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
					$entry['url'] = $title->getFullURL();
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
			'pageid' => null,
			'redirects' => false,
			'oldid' => null,
			'prop' => array(
				ApiBase::PARAM_DFLT => 'text|langlinks|categories|links|templates|images|externallinks|sections|revid|displaytitle',
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
				)
			),
			'pst' => false,
			'onlypst' => false,
			'uselang' => null,
			'section' => null,
			'disablepp' => false,
		);
	}

	public function getParamDescription() {
		$p = $this->getModulePrefix();
		return array(
			'text' => 'Wikitext to parse',
			'summary' => 'Summary to parse',
			'redirects' => "If the {$p}page parameter is set to a redirect, resolve it",
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
				'NOTE: Section tree is only generated if there are more than 4 sections, or if the __TOC__ keyword is present'
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
		);
	}

	public function getDescription() {
		return 'This module parses wikitext and returns parser output';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'params', 'info' => 'The page parameter cannot be used together with the text and title parameters' ),
			array( 'code' => 'missingrev', 'info' => 'There is no revision ID oldid' ),
			array( 'code' => 'permissiondenied', 'info' => 'You don\'t have permission to view deleted revisions' ),
			array( 'code' => 'missingtitle', 'info' => 'The page you specified doesn\'t exist' ),
			array( 'code' => 'nosuchsection', 'info' => 'There is no section sectionnumber in page' ),
			array( 'nosuchpageid' ),
		) );
	}

	protected function getExamples() {
		return array(
			'api.php?action=parse&text={{Project:Sandbox}}'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
