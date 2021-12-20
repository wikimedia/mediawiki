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

namespace MediaWiki\Preferences;

use Html;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Parser\ParserOutputFlags;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\User\UserIdentity;
use MessageLocalizer;
use MultiHttpClient;
use Parser;
use ParserOptions;
use ParsoidVirtualRESTService;
use SpecialPage;
use TitleFactory;
use VirtualRESTServiceClient;

/**
 * @since 1.35
 */
class SignatureValidator {

	/** @var array Made public for use in services */
	public const CONSTRUCTOR_OPTIONS = [
		'SignatureAllowedLintErrors',
		'VirtualRestConfig',
	];

	/** @var UserIdentity */
	private $user;
	/** @var MessageLocalizer|null */
	private $localizer;
	/** @var ParserOptions */
	private $popts;
	/** @var Parser */
	private $parser;
	/** @var ServiceOptions */
	private $serviceOptions;
	/** @var SpecialPageFactory */
	private $specialPageFactory;
	/** @var TitleFactory */
	private $titleFactory;

	/**
	 * @param ServiceOptions $options
	 * @param UserIdentity $user
	 * @param ?MessageLocalizer $localizer
	 * @param ParserOptions $popts
	 * @param Parser $parser
	 * @param SpecialPageFactory $specialPageFactory
	 * @param TitleFactory $titleFactory
	 */
	public function __construct(
		ServiceOptions $options,
		UserIdentity $user,
		?MessageLocalizer $localizer,
		ParserOptions $popts,
		Parser $parser,
		SpecialPageFactory $specialPageFactory,
		TitleFactory $titleFactory
	) {
		$this->user = $user;
		$this->localizer = $localizer;
		$this->popts = $popts;
		// Fetch the parser, will be used to create a new parser via getFreshParser() when needed
		$this->parser = $parser;
		// Configuration
		$this->serviceOptions = $options;
		$this->serviceOptions->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		// TODO SpecialPage::getTitleFor should also be available via SpecialPageFactory
		$this->specialPageFactory = $specialPageFactory;
		$this->titleFactory = $titleFactory;
	}

	/**
	 * @param string $signature Signature before PST
	 * @return string[]|bool If localizer is defined: List of errors, as HTML (empty array for no errors)
	 *   If localizer is not defined: True if there are errors, false if there are no errors
	 */
	public function validateSignature( string $signature ) {
		$pstSignature = $this->applyPreSaveTransform( $signature );
		if ( $pstSignature === false ) {
			// Return early because the rest of the validation uses wikitext parsing, which requires
			// the pre-save transform to be applied first, and we just found out that the result of the
			// pre-save transform would require *another* pre-save transform, which is crazy
			if ( $this->localizer ) {
				return [ $this->localizer->msg( 'badsigsubst' )->parse() ];
			}
			return true;
		}

		$pstWasApplied = false;
		if ( $pstSignature !== $signature ) {
			$pstWasApplied = true;
			$signature = $pstSignature;
		}

		$errors = $this->localizer ? [] : false;

		$lintErrors = $this->checkLintErrors( $signature );
		if ( $lintErrors ) {
			$allowedLintErrors = $this->serviceOptions->get( 'SignatureAllowedLintErrors' );
			$messages = '';

			foreach ( $lintErrors as $error ) {
				if ( $error['type'] === 'multiple-unclosed-formatting-tags' ) {
					// Always appears with 'missing-end-tag', we can ignore it to simplify the error message
					continue;
				}
				if ( in_array( $error['type'], $allowedLintErrors, true ) ) {
					continue;
				}
				if ( !$this->localizer ) {
					$errors = true;
					break;
				}

				$details = $this->getLintErrorDetails( $error );
				$location = $this->getLintErrorLocation( $error );
				// Messages used here:
				// * linterror-bogus-image-options
				// * linterror-deletable-table-tag
				// * linterror-html5-misnesting
				// * linterror-misc-tidy-replacement-issues
				// * linterror-misnested-tag
				// * linterror-missing-end-tag
				// * linterror-multi-colon-escape
				// * linterror-multiline-html-table-in-list
				// * linterror-multiple-unclosed-formatting-tags
				// * linterror-obsolete-tag
				// * linterror-pwrap-bug-workaround
				// * linterror-self-closed-tag
				// * linterror-stripped-tag
				// * linterror-tidy-font-bug
				// * linterror-tidy-whitespace-bug
				// * linterror-unclosed-quotes-in-heading
				$label = $this->localizer->msg( "linterror-{$error['type']}" )->parse();
				$docsLink = new \OOUI\ButtonWidget( [
					'href' =>
						"https://www.mediawiki.org/wiki/Special:MyLanguage/Help:Lint_errors/{$error['type']}",
					'target' => '_blank',
					'label' => $this->localizer->msg( 'prefs-signature-error-details' )->text(),
				] );

				// If pre-save transform was applied (i.e., the signature has 'subst:' syntax),
				// error locations will be incorrect, because Parsoid can't expand templates.
				// Don't display them.
				$encLocation = $pstWasApplied ? null : json_encode( $location );

				$messages .= Html::rawElement(
					'li',
					[ 'data-mw-lint-error-location' => $encLocation ],
					$label . $this->localizer->msg( 'colon-separator' )->escaped() .
						$details . ' ' . $docsLink
				);
			}

			if ( $messages && $this->localizer ) {
				$errors[] = $this->localizer->msg( 'badsightml' )->parse() .
					Html::rawElement( 'ol', [], $messages );
			}
		}

		if ( !$this->checkUserLinks( $signature ) ) {
			if ( $this->localizer ) {
				$userText = wfEscapeWikiText( $this->user->getName() );
				$linkWikitext = $this->localizer->msg( 'signature', $userText, $userText )->inContentLanguage()->text();
				$errors[] = $this->localizer->msg( 'badsiglinks', wfEscapeWikiText( $linkWikitext ) )->parse();
			} else {
				$errors = true;
			}
		}

		if ( !$this->checkLineBreaks( $signature ) ) {
			if ( $this->localizer ) {
				$errors[] = $this->localizer->msg( 'badsiglinebreak' )->parse();
			} else {
				$errors = true;
			}
		}

		return $errors;
	}

	/**
	 * @param string $signature Signature before PST
	 * @return string|bool Signature with PST applied, or false if applying PST yields wikitext that
	 *     would change if PST was applied again
	 */
	protected function applyPreSaveTransform( string $signature ) {
		// This may be called by the Parser when it's displaying a signature, so we need a new instance
		$parser = $this->parser->getFreshParser();

		$pstSignature = $parser->preSaveTransform(
			$signature,
			SpecialPage::getTitleFor( 'Preferences' ),
			$this->user,
			$this->popts
		);

		// The signature wikitext contains another '~~~~' or similar (T230652)
		if ( $parser->getOutput()->getOutputFlag( ParserOutputFlags::USER_SIGNATURE ) ) {
			return false;
		}

		// The signature wikitext contains '{{subst:...}}' markup that produces another subst (T230652)
		$pstPstSignature = $parser->preSaveTransform(
			$pstSignature,
			SpecialPage::getTitleFor( 'Preferences' ),
			$this->user,
			$this->popts
		);
		if ( $pstPstSignature !== $pstSignature ) {
			return false;
		}

		return $pstSignature;
	}

	/**
	 * @param string $signature Signature after PST
	 * @return array Array of error objects returned by Parsoid's lint API (empty array for no errors)
	 */
	protected function checkLintErrors( string $signature ): array {
		// Real check for mismatched HTML tags in the *output*.
		// This has to use Parsoid because PHP Parser doesn't produce this information,
		// it just fixes up the result quietly.

		// This request is not cached, but that's okay, because $signature is short (other code checks
		// the length against $wgMaxSigChars).

		$vrsConfig = $this->serviceOptions->get( 'VirtualRestConfig' );
		if ( isset( $vrsConfig['modules']['parsoid'] ) ) {
			$params = $vrsConfig['modules']['parsoid'];
			if ( isset( $vrsConfig['global'] ) ) {
				$params = array_merge( $vrsConfig['global'], $params );
			}
			$parsoidVrs = new ParsoidVirtualRESTService( $params );

			$vrsClient = new VirtualRESTServiceClient( new MultiHttpClient( [] ) );
			$vrsClient->mount( '/parsoid/', $parsoidVrs );

			$request = [
				'method' => 'POST',
				'url' => '/parsoid/local/v3/transform/wikitext/to/lint',
				'body' => [
					'wikitext' => $signature,
				],
				'headers' => [
					// Are both of these are really needed?
					'User-Agent' => 'MediaWiki/' . MW_VERSION,
					'Api-User-Agent' => 'MediaWiki/' . MW_VERSION,
				],
			];

			$response = $vrsClient->run( $request );
			if ( $response['code'] === 200 ) {
				$json = json_decode( $response['body'], true );
				// $json is an array of error objects
				if ( $json ) {
					return $json;
				}
			}
		}

		return [];
	}

	/**
	 * @param string $signature Signature after PST
	 * @return bool Whether signature contains required links
	 */
	protected function checkUserLinks( string $signature ): bool {
		// This may be called by the Parser when it's displaying a signature, so we need a new instance
		$parser = $this->parser->getFreshParser();

		// Check for required links. This one's easier to do with the PHP Parser.
		$pout = $parser->parse(
			$signature,
			SpecialPage::getTitleFor( 'Preferences' ),
			$this->popts
		);

		// Checking user or talk links is easy
		$links = $pout->getLinks();
		$username = $this->user->getName();
		if (
			isset( $links[ NS_USER ][ strtr( $username, ' ', '_' ) ] ) ||
			isset( $links[ NS_USER_TALK ][ strtr( $username, ' ', '_' ) ] )
		) {
			return true;
		}

		// Checking the contributions link is harder, because the special page name and the username in
		// the "subpage parameter" are not normalized for us.
		$splinks = $pout->getLinksSpecial();
		foreach ( $splinks as $dbkey => $unused ) {
			list( $name, $subpage ) = $this->specialPageFactory->resolveAlias( $dbkey );
			if ( $name === 'Contributions' && $subpage ) {
				$userTitle = $this->titleFactory->makeTitleSafe( NS_USER, $subpage );
				if ( $userTitle && $userTitle->getText() === $username ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * @param string $signature Signature after PST
	 * @return bool Whether signature contains no line breaks
	 */
	protected function checkLineBreaks( string $signature ): bool {
		return !preg_match( "/[\r\n]/", $signature );
	}

	// Adapted from the Linter extension
	private function getLintErrorLocation( array $lintError ): array {
		return array_slice( $lintError['dsr'], 0, 2 );
	}

	// Adapted from the Linter extension
	private function getLintErrorDetails( array $lintError ): string {
		[ 'type' => $type, 'params' => $params ] = $lintError;

		if ( $type === 'bogus-image-options' && isset( $params['items'] ) ) {
			$list = array_map( static function ( $in ) {
				return Html::element( 'code', [], $in );
			}, $params['items'] );
			return implode(
				$this->localizer->msg( 'comma-separator' )->escaped(),
				$list
			);
		} elseif ( $type === 'pwrap-bug-workaround' &&
			isset( $params['root'] ) &&
			isset( $params['child'] ) ) {
			return Html::element( 'code', [],
				$params['root'] . " > " . $params['child'] );
		} elseif ( $type === 'tidy-whitespace-bug' &&
			isset( $params['node'] ) &&
			isset( $params['sibling'] ) ) {
			return Html::element( 'code', [],
				$params['node'] . " + " . $params['sibling'] );
		} elseif ( $type === 'multi-colon-escape' &&
			isset( $params['href'] ) ) {
			return Html::element( 'code', [], $params['href'] );
		} elseif ( $type === 'multiline-html-table-in-list' ) {
			/* ancestor and name will be set */
			return Html::element( 'code', [],
				$params['ancestorName'] . " > " . $params['name'] );
		} elseif ( $type === 'misc-tidy-replacement-issues' ) {
			/* There will be a 'subtype' param to disambiguate */
			return Html::element( 'code', [], $params['subtype'] );
		} elseif ( $type === 'missing-end-tag' ) {
			return Html::element( 'code', [], '</' . $params['name'] . '>' );
		} elseif ( isset( $params['name'] ) ) {
			return Html::element( 'code', [], $params['name'] );
		}

		return '';
	}

}
