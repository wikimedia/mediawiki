<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Context\IContextSource;
use MediaWiki\Html\Html;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageReference;
use MediaWiki\Permissions\RestrictionStore;
use MediaWiki\Title\Title;

/**
 * Handles formatting for the "templates used on this page"
 * lists. Formerly known as Linker::formatTemplates()
 *
 * @since 1.28
 */
class TemplatesOnThisPageFormatter {

	/**
	 * @var IContextSource
	 */
	private $context;

	/**
	 * @var LinkRenderer
	 */
	private $linkRenderer;

	/**
	 * @var LinkBatchFactory
	 */
	private $linkBatchFactory;

	/**
	 * @var RestrictionStore
	 */
	private $restrictionStore;

	/**
	 * @param IContextSource $context
	 * @param LinkRenderer $linkRenderer
	 * @param LinkBatchFactory $linkBatchFactory
	 * @param RestrictionStore $restrictionStore
	 */
	public function __construct(
		IContextSource $context,
		LinkRenderer $linkRenderer,
		LinkBatchFactory $linkBatchFactory,
		RestrictionStore $restrictionStore
	) {
		$this->context = $context;
		$this->linkRenderer = $linkRenderer;
		$this->linkBatchFactory = $linkBatchFactory;
		$this->restrictionStore = $restrictionStore;
	}

	/**
	 * Make an HTML list of templates, and then add a "More..." link at
	 * the bottom. If $more is null, do not add a "More..." link. If $more
	 * is a PageReference, make a link to that page and use it. If $more is a string,
	 * directly paste it in as the link (escaping needs to be done manually).
	 *
	 * @param PageIdentity[] $templates
	 * @param string|false $type 'preview' if a preview, 'section' if a section edit, false if neither
	 * @param PageReference|string|null $more An escaped link for "More..." of the templates
	 * @return string HTML output
	 */
	public function format( array $templates, $type = false, $more = null ) {
		if ( !$templates ) {
			// No templates
			return '';
		}

		# Do a batch existence check
		$batch = $this->linkBatchFactory->newLinkBatch( $templates );
		$batch->setCaller( __METHOD__ );
		$batch->execute();

		# Construct the HTML
		$outText = Html::openElement( 'div', [ 'class' => 'mw-templatesUsedExplanation' ] );
		$count = count( $templates );
		if ( $type === 'preview' ) {
			$outText .= $this->context->msg( 'templatesusedpreview' )->numParams( $count )
				->parseAsBlock();
		} elseif ( $type === 'section' ) {
			$outText .= $this->context->msg( 'templatesusedsection' )->numParams( $count )
				->parseAsBlock();
		} else {
			$outText .= $this->context->msg( 'templatesused' )->numParams( $count )
				->parseAsBlock();
		}
		$outText .= Html::closeElement( 'div' ) . Html::openElement( 'ul' ) . "\n";

		usort( $templates, [ Title::class, 'compare' ] );
		foreach ( $templates as $template ) {
			$outText .= $this->formatTemplate( $template );
		}

		if ( $more instanceof PageReference ) {
			$outText .= Html::rawElement( 'li', [],
				$this->linkRenderer->makeLink(
					$more,
					$this->context->msg( 'moredotdotdot' )->text()
				)
			);
		} elseif ( $more ) {
			// Documented as should already be escaped
			$outText .= Html::rawElement( 'li', [], $more );
		}

		$outText .= Html::closeElement( 'ul' );
		return $outText;
	}

	/**
	 * Builds a list item for an individual template
	 *
	 * The output of this is repeated for live-preview in resources/src/mediawiki.page.preview.js
	 *
	 * @param PageIdentity $target
	 * @return string
	 */
	private function formatTemplate( PageIdentity $target ) {
		if ( !$target->canExist() ) {
			return Html::rawElement( 'li', [], $this->linkRenderer->makeLink( $target ) );
		}

		$protected = $this->getRestrictionsText(
			$this->restrictionStore->getRestrictions( $target, 'edit' )
		);
		$editLink = $this->buildEditLink( $target );
		return Html::rawElement( 'li', [], $this->linkRenderer->makeLink( $target )
			. $this->context->msg( 'word-separator' )->escaped()
			. $this->context->msg( 'parentheses' )->rawParams( $editLink )->escaped()
			. $this->context->msg( 'word-separator' )->escaped()
			. $protected
		);
	}

	/**
	 * If the page is protected, get the relevant text
	 * for those restrictions
	 *
	 * @param array $restrictions
	 * @return string HTML
	 */
	private function getRestrictionsText( array $restrictions ) {
		$protected = '';
		if ( !$restrictions ) {
			return $protected;
		}

		// Check backwards-compatible messages
		$msg = null;
		if ( $restrictions === [ 'sysop' ] ) {
			$msg = $this->context->msg( 'template-protected' );
		} elseif ( $restrictions === [ 'autoconfirmed' ] ) {
			$msg = $this->context->msg( 'template-semiprotected' );
		}
		if ( $msg && !$msg->isDisabled() ) {
			$protected = $msg->parse();
		} else {
			// Construct the message from restriction-level-*
			// e.g. restriction-level-sysop, restriction-level-autoconfirmed
			$msgs = [];
			foreach ( $restrictions as $r ) {
				$msgs[] = $this->context->msg( "restriction-level-$r" )->parse();
			}
			$protected = $this->context->msg( 'parentheses' )
				->rawParams( $this->context->getLanguage()->commaList( $msgs ) )->escaped();
		}

		return $protected;
	}

	/**
	 * Return a link to the edit page, with the text
	 * saying "view source" if the user can't edit the page
	 *
	 * @param PageIdentity $page
	 * @return string HTML
	 */
	private function buildEditLink( PageIdentity $page ) {
		if ( $this->context->getAuthority()->probablyCan( 'edit', $page ) ) {
			$linkMsg = 'editlink';
		} else {
			$linkMsg = 'viewsourcelink';
		}

		return $this->linkRenderer->makeLink(
			$page,
			$this->context->msg( $linkMsg )->text(),
			[],
			[ 'action' => 'edit' ]
		);
	}

}
