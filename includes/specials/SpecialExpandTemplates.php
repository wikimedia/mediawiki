<?php
/**
 * Implements Special:ExpandTemplates
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
 * @ingroup SpecialPage
 */

/**
 * A special page that expands submitted templates, parser functions,
 * and variables, allowing easier debugging of these.
 *
 * @ingroup SpecialPage
 */
class SpecialExpandTemplates extends SpecialPage {

	/** @var bool Whether or not to show the XML parse tree */
	protected $generateXML;

	/** @var bool Whether or not to show the raw HTML code */
	protected $generateRawHtml;

	/** @var bool Whether or not to remove comments in the expanded wikitext */
	protected $removeComments;

	/** @var bool Whether or not to remove <nowiki> tags in the expanded wikitext */
	protected $removeNowiki;

	/** @var int Maximum size in bytes to include. 50MB allows fixing those huge pages */
	const MAX_INCLUDE_SIZE = 50000000;

	function __construct() {
		parent::__construct( 'ExpandTemplates' );
	}

	/**
	 * Show the special page
	 * @param string|null $subpage
	 */
	function execute( $subpage ) {
		global $wgParser;

		$this->setHeaders();
		$this->addHelpLink( 'Help:ExpandTemplates' );

		$request = $this->getRequest();
		$titleStr = $request->getText( 'wpContextTitle' );
		$title = Title::newFromText( $titleStr );

		if ( !$title ) {
			$title = $this->getPageTitle();
		}
		$input = $request->getText( 'wpInput' );
		$this->generateXML = $request->getBool( 'wpGenerateXml' );
		$this->generateRawHtml = $request->getBool( 'wpGenerateRawHtml' );

		if ( strlen( $input ) ) {
			$this->removeComments = $request->getBool( 'wpRemoveComments', false );
			$this->removeNowiki = $request->getBool( 'wpRemoveNowiki', false );
			$options = ParserOptions::newFromContext( $this->getContext() );
			$options->setRemoveComments( $this->removeComments );
			$options->setTidy( true );
			$options->setMaxIncludeSize( self::MAX_INCLUDE_SIZE );

			if ( $this->generateXML ) {
				$wgParser->startExternalParse( $title, $options, Parser::OT_PREPROCESS );
				$dom = $wgParser->preprocessToDom( $input );

				if ( method_exists( $dom, 'saveXML' ) ) {
					$xml = $dom->saveXML();
				} else {
					$xml = $dom->__toString();
				}
			}

			$output = $wgParser->preprocess( $input, $title, $options );
		} else {
			$this->removeComments = $request->getBool( 'wpRemoveComments', true );
			$this->removeNowiki = $request->getBool( 'wpRemoveNowiki', false );
			$output = false;
		}

		$out = $this->getOutput();

		$this->makeForm( $titleStr, $input );

		if ( $output !== false ) {
			if ( $this->generateXML && strlen( $output ) > 0 ) {
				$out->addHTML( $this->makeOutput( $xml, 'expand_templates_xml_output' ) );
			}

			$tmp = $this->makeOutput( $output );

			if ( $this->removeNowiki ) {
				$tmp = preg_replace(
					[ '_&lt;nowiki&gt;_', '_&lt;/nowiki&gt;_', '_&lt;nowiki */&gt;_' ],
					'',
					$tmp
				);
			}

			$config = $this->getConfig();
			if ( $config->get( 'UseTidy' ) && $options->getTidy() ) {
				$tmp = MWTidy::tidy( $tmp );
			}

			$out->addHTML( $tmp );

			$pout = $this->generateHtml( $title, $output );
			$rawhtml = $pout->getText();
			if ( $this->generateRawHtml && strlen( $rawhtml ) > 0 ) {
				$out->addHTML( $this->makeOutput( $rawhtml, 'expand_templates_html_output' ) );
			}

			$this->showHtmlPreview( $title, $pout, $out );
		}
	}

	/**
	 * Callback for the HTMLForm used in self::makeForm.
	 * Checks, if the input was given, and if not, returns a fatal Status
	 * object with an error message.
	 *
	 * @param array $values The values submitted to the HTMLForm
	 * @return Status
	 */
	public function onSubmitInput( array $values ) {
		$status = Status::newGood();
		if ( !strlen( $values['input'] ) ) {
			$status = Status::newFatal( 'expand_templates_input_missing' );
		}
		return $status;
	}

	/**
	 * Generate a form allowing users to enter information
	 *
	 * @param string $title Value for context title field
	 * @param string $input Value for input textbox
	 * @return string
	 */
	private function makeForm( $title, $input ) {
		$fields = [
			'contexttitle' => [
				'type' => 'text',
				'label' => $this->msg( 'expand_templates_title' )->plain(),
				'name' => 'wpContextTitle',
				'id' => 'contexttitle',
				'size' => 60,
				'default' => $title,
				'autofocus' => true,
			],
			'input' => [
				'type' => 'textarea',
				'name' => 'wpInput',
				'label' => $this->msg( 'expand_templates_input' )->text(),
				'rows' => 10,
				'default' => $input,
				'id' => 'input',
				'useeditfont' => true,
			],
			'removecomments' => [
				'type' => 'check',
				'label' => $this->msg( 'expand_templates_remove_comments' )->text(),
				'name' => 'wpRemoveComments',
				'id' => 'removecomments',
				'default' => $this->removeComments,
			],
			'removenowiki' => [
				'type' => 'check',
				'label' => $this->msg( 'expand_templates_remove_nowiki' )->text(),
				'name' => 'wpRemoveNowiki',
				'id' => 'removenowiki',
				'default' => $this->removeNowiki,
			],
			'generate_xml' => [
				'type' => 'check',
				'label' => $this->msg( 'expand_templates_generate_xml' )->text(),
				'name' => 'wpGenerateXml',
				'id' => 'generate_xml',
				'default' => $this->generateXML,
			],
			'generate_rawhtml' => [
				'type' => 'check',
				'label' => $this->msg( 'expand_templates_generate_rawhtml' )->text(),
				'name' => 'wpGenerateRawHtml',
				'id' => 'generate_rawhtml',
				'default' => $this->generateRawHtml,
			],
		];

		$form = HTMLForm::factory( 'ooui', $fields, $this->getContext() );
		$form
			->setSubmitTextMsg( 'expand_templates_ok' )
			->setWrapperLegendMsg( 'expandtemplates' )
			->setHeaderText( $this->msg( 'expand_templates_intro' )->parse() )
			->setSubmitCallback( [ $this, 'onSubmitInput' ] )
			->showAlways();
	}

	/**
	 * Generate a nice little box with a heading for output
	 *
	 * @param string $output Wiki text output
	 * @param string $heading
	 * @return string
	 */
	private function makeOutput( $output, $heading = 'expand_templates_output' ) {
		$out = "<h2>" . $this->msg( $heading )->escaped() . "</h2>\n";
		$out .= Xml::textarea(
			'output',
			$output,
			10,
			10,
			[
				'id' => 'output',
				'readonly' => 'readonly',
				'class' => 'mw-editfont-' . $this->getUser()->getOption( 'editfont' )
			]
		);

		return $out;
	}

	/**
	 * Renders the supplied wikitext as html
	 *
	 * @param Title $title
	 * @param string $text
	 * @return ParserOutput
	 */
	private function generateHtml( Title $title, $text ) {
		global $wgParser;

		$popts = ParserOptions::newFromContext( $this->getContext() );
		$popts->setTargetLanguage( $title->getPageLanguage() );
		return $wgParser->parse( $text, $title, $popts );
	}

	/**
	 * Wraps the provided html code in a div and outputs it to the page
	 *
	 * @param Title $title
	 * @param ParserOutput $pout
	 * @param OutputPage $out
	 */
	private function showHtmlPreview( Title $title, ParserOutput $pout, OutputPage $out ) {
		$lang = $title->getPageViewLanguage();
		$out->addHTML( "<h2>" . $this->msg( 'expand_templates_preview' )->escaped() . "</h2>\n" );

		if ( $this->getConfig()->get( 'RawHtml' ) ) {
			$request = $this->getRequest();
			$user = $this->getUser();

			// To prevent cross-site scripting attacks, don't show the preview if raw HTML is
			// allowed and a valid edit token is not provided (T73111). However, MediaWiki
			// does not currently provide logged-out users with CSRF protection; in that case,
			// do not show the preview unless anonymous editing is allowed.
			if ( $user->isAnon() && !$user->isAllowed( 'edit' ) ) {
				$error = [ 'expand_templates_preview_fail_html_anon' ];
			} elseif ( !$user->matchEditToken( $request->getVal( 'wpEditToken' ), '', $request ) ) {
				$error = [ 'expand_templates_preview_fail_html' ];
			} else {
				$error = false;
			}

			if ( $error ) {
				$out->wrapWikiMsg( "<div class='previewnote'>\n$1\n</div>", $error );
				return;
			}
		}

		$out->addHTML( Html::openElement( 'div', [
			'class' => 'mw-content-' . $lang->getDir(),
			'dir' => $lang->getDir(),
			'lang' => $lang->getHtmlCode(),
		] ) );
		$out->addParserOutputContent( $pout );
		$out->addHTML( Html::closeElement( 'div' ) );
		$out->setCategoryLinks( $pout->getCategories() );
	}

	protected function getGroupName() {
		return 'wiki';
	}
}
