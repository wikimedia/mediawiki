<?php
/**
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

namespace MediaWiki\Specials;

use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Output\OutputPage;
use MediaWiki\Parser\Parser;
use MediaWiki\Parser\ParserFactory;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\Tidy\TidyDriverBase;
use MediaWiki\Title\Title;
use MediaWiki\User\Options\UserOptionsLookup;

/**
 * A special page to enter wikitext and expands its templates, parser functions,
 * and variables, allowing easier debugging of these.
 *
 * @ingroup SpecialPage
 * @ingroup Parser
 */
class SpecialExpandTemplates extends SpecialPage {

	/** @var int Maximum size in bytes to include. 50 MB allows fixing those huge pages */
	private const MAX_INCLUDE_SIZE = 50_000_000;

	private ParserFactory $parserFactory;
	private UserOptionsLookup $userOptionsLookup;
	private TidyDriverBase $tidy;

	public function __construct(
		ParserFactory $parserFactory,
		UserOptionsLookup $userOptionsLookup,
		TidyDriverBase $tidy
	) {
		parent::__construct( 'ExpandTemplates' );
		$this->parserFactory = $parserFactory;
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

		if ( $input !== '' ) {
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

			$parser = $this->parserFactory->getInstance();
			if ( $generateXML ) {
				$parser->startExternalParse( $title, $options, Parser::OT_PREPROCESS );
				$dom = $parser->preprocessToDom( $input );

				if ( method_exists( $dom, 'saveXML' ) ) {
					// @phan-suppress-next-line PhanUndeclaredMethod
					$xml = $dom->saveXML();
				} else {
					// @phan-suppress-next-line PhanUndeclaredMethod
					$xml = $dom->__toString();
				}
			}

			$output = $parser->preprocess( $input, $title, $options );
			$this->makeForm();

			$out = $this->getOutput();
			if ( $generateXML ) {
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

			$pout = $parser->parse( $output, $title, $options );
			// TODO T371008 consider if using the Content framework makes sense instead of creating the pipeline
			$rawhtml = MediaWikiServices::getInstance()->getDefaultOutputPipeline()
				->run( $pout, $options, [ 'enableSectionEditLinks' => false ] )->getContentHolderText();
			if ( $generateRawHtml && $rawhtml !== '' ) {
				$out->addHTML( $this->makeOutput( $rawhtml, 'expand_templates_html_output' ) );
			}

			$this->showHtmlPreview( $title, $pout, $options, $out );
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
	private function onSubmitInput( array $values ) {
		$status = Status::newGood();
		if ( $values['Input'] === '' ) {
			$status = Status::newFatal( 'expand_templates_input_missing' );
		}
		return $status;
	}

	/**
	 * Generate a form allowing users to enter information
	 */
	private function makeForm() {
		$fields = [
			'Input' => [
				'type' => 'textarea',
				'label' => $this->msg( 'expand_templates_input' )->text(),
				'rows' => 10,
				'id' => 'input',
				'useeditfont' => true,
				'required' => true,
				'autofocus' => true,
			],
			'ContextTitle' => [
				'type' => 'text',
				'label' => $this->msg( 'expand_templates_title' )->plain(),
				'id' => 'contexttitle',
				'size' => 60,
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
			->setSubmitCallback( $this->onSubmitInput( ... ) )
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
		$out .= Html::textarea(
			'output',
			$output,
			[
				// #output is used by CodeMirror (T384148)
				'id' => $heading === 'expand_templates_output' ? 'output' : $heading,
				'cols' => 10,
				'rows' => 10,
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
	 * @param ParserOptions $popts
	 * @param OutputPage $out
	 */
	private function showHtmlPreview( Title $title, ParserOutput $pout, ParserOptions $popts, OutputPage $out ) {
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

		$out->addParserOutputContent( $pout, $popts, [ 'enableSectionEditLinks' => false ] );
		$out->addCategoryLinks( $pout->getCategoryMap() );
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'wiki';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialExpandTemplates::class, 'SpecialExpandTemplates' );
