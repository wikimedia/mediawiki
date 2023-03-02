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

use MediaWiki\Html\Html;
use MediaWiki\MainConfigNames;
use MediaWiki\Tidy\TidyDriverBase;
use MediaWiki\Title\Title;
use MediaWiki\User\UserOptionsLookup;

/**
 * A special page that expands submitted templates, parser functions,
 * and variables, allowing easier debugging of these.
 *
 * @ingroup SpecialPage
 */
class SpecialExpandTemplates extends SpecialPage {

	/** @var int Maximum size in bytes to include. 50 MB allows fixing those huge pages */
	private const MAX_INCLUDE_SIZE = 50000000;

	/** @var Parser */
	private $parser;

	/** @var UserOptionsLookup */
	private $userOptionsLookup;

	/** @var TidyDriverBase */
	private $tidy;

	/**
	 * @param Parser $parser
	 * @param UserOptionsLookup $userOptionsLookup
	 * @param TidyDriverBase $tidy
	 */
	public function __construct(
		Parser $parser,
		UserOptionsLookup $userOptionsLookup,
		TidyDriverBase $tidy
	) {
		parent::__construct( 'ExpandTemplates' );
		$this->parser = $parser;
		$this->userOptionsLookup = $userOptionsLookup;
		$this->tidy = $tidy;
	}

	/**
	 * Show the special page
	 * @param string|null $subpage
	 */
	public function execute( $subpage ) {
		$this->setHeaders();
		$this->addHelpLink( 'Help:ExpandTemplates' );

		$request = $this->getRequest();
		$input = $request->getText( 'wpInput' );

		if ( strlen( $input ) ) {
			$removeComments = $request->getBool( 'wpRemoveComments', false );
			$removeNowiki = $request->getBool( 'wpRemoveNowiki', false );
			$generateXML = $request->getBool( 'wpGenerateXml' );
			$generateRawHtml = $request->getBool( 'wpGenerateRawHtml' );

			$options = ParserOptions::newFromContext( $this->getContext() );
			$options->setRemoveComments( $removeComments );
			$options->setMaxIncludeSize( self::MAX_INCLUDE_SIZE );

			$titleStr = $request->getText( 'wpContextTitle' );
			$title = Title::newFromText( $titleStr );
			if ( !$title ) {
				$title = $this->getPageTitle();
				$options->setTargetLanguage( $this->getContentLanguage() );
			}

			if ( $generateXML ) {
				$this->parser->startExternalParse( $title, $options, Parser::OT_PREPROCESS );
				$dom = $this->parser->preprocessToDom( $input );

				if ( method_exists( $dom, 'saveXML' ) ) {
					// @phan-suppress-next-line PhanUndeclaredMethod
					$xml = $dom->saveXML();
				} else {
					// @phan-suppress-next-line PhanUndeclaredMethod
					$xml = $dom->__toString();
				}
			}

			$output = $this->parser->preprocess( $input, $title, $options );
			$this->makeForm();

			$out = $this->getOutput();
			if ( $generateXML && strlen( $output ) > 0 ) {
				// @phan-suppress-next-line PhanPossiblyUndeclaredVariable xml is set when used
				$out->addHTML( $this->makeOutput( $xml, 'expand_templates_xml_output' ) );
			}

			$tmp = $this->makeOutput( $output );

			if ( $removeNowiki ) {
				$tmp = preg_replace(
					[ '_&lt;nowiki&gt;_', '_&lt;/nowiki&gt;_', '_&lt;nowiki */&gt;_' ],
					'',
					$tmp
				);
			}

			$tmp = $this->tidy->tidy( $tmp );

			$out->addHTML( $tmp );

			$pout = $this->parser->parse( $output, $title, $options );
			$rawhtml = $pout->getText( [ 'enableSectionEditLinks' => false ] );
			if ( $generateRawHtml && strlen( $rawhtml ) > 0 ) {
				// @phan-suppress-next-line SecurityCheck-DoubleEscaped Wanted here to display the html
				$out->addHTML( $this->makeOutput( $rawhtml, 'expand_templates_html_output' ) );
			}

			$this->showHtmlPreview( $title, $pout, $out );
		} else {
			$this->makeForm();
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
		if ( !strlen( $values['Input'] ) ) {
			$status = Status::newFatal( 'expand_templates_input_missing' );
		}
		return $status;
	}

	/**
	 * Generate a form allowing users to enter information
	 */
	private function makeForm() {
		$fields = [
			'ContextTitle' => [
				'type' => 'text',
				'label' => $this->msg( 'expand_templates_title' )->plain(),
				'id' => 'contexttitle',
				'size' => 60,
				'autofocus' => true,
			],
			'Input' => [
				'type' => 'textarea',
				'label' => $this->msg( 'expand_templates_input' )->text(),
				'rows' => 10,
				'id' => 'input',
				'useeditfont' => true,
			],
			'RemoveComments' => [
				'type' => 'check',
				'label' => $this->msg( 'expand_templates_remove_comments' )->text(),
				'id' => 'removecomments',
				'default' => true,
			],
			'RemoveNowiki' => [
				'type' => 'check',
				'label' => $this->msg( 'expand_templates_remove_nowiki' )->text(),
				'id' => 'removenowiki',
			],
			'GenerateXml' => [
				'type' => 'check',
				'label' => $this->msg( 'expand_templates_generate_xml' )->text(),
				'id' => 'generate_xml',
			],
			'GenerateRawHtml' => [
				'type' => 'check',
				'label' => $this->msg( 'expand_templates_generate_rawhtml' )->text(),
				'id' => 'generate_rawhtml',
			],
		];

		$form = HTMLForm::factory( 'ooui', $fields, $this->getContext() );
		$form
			->setSubmitTextMsg( 'expand_templates_ok' )
			->setWrapperLegendMsg( 'expandtemplates' )
			->setHeaderHtml( $this->msg( 'expand_templates_intro' )->parse() )
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
				'class' => 'mw-editfont-' . $this->userOptionsLookup->getOption( $this->getUser(), 'editfont' )
			]
		);

		return $out;
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

		if ( $this->getConfig()->get( MainConfigNames::RawHtml ) ) {
			$request = $this->getRequest();
			$user = $this->getUser();

			// To prevent cross-site scripting attacks, don't show the preview if raw HTML is
			// allowed and a valid edit token is not provided (T73111). However, MediaWiki
			// does not currently provide logged-out users with CSRF protection; in that case,
			// do not show the preview unless anonymous editing is allowed.
			if ( $user->isAnon() && !$this->getAuthority()->isAllowed( 'edit' ) ) {
				$error = [ 'expand_templates_preview_fail_html_anon' ];
			} elseif ( !$user->matchEditToken( $request->getVal( 'wpEditToken' ), '', $request ) ) {
				$error = [ 'expand_templates_preview_fail_html' ];
			} else {
				$error = false;
			}

			if ( $error ) {
				$out->addHTML(
					Html::errorBox(
						$out->msg( $error )->parse(),
						'',
						'previewnote'
					)
				);
				return;
			}
		}

		$out->addHTML( Html::openElement( 'div', [
			'class' => 'mw-content-' . $lang->getDir(),
			'dir' => $lang->getDir(),
			'lang' => $lang->getHtmlCode(),
		] ) );
		$out->addParserOutputContent( $pout, [ 'enableSectionEditLinks' => false ] );
		$out->addHTML( Html::closeElement( 'div' ) );
		$out->setCategoryLinks( $pout->getCategories() );
	}

	protected function getGroupName() {
		return 'wiki';
	}
}
