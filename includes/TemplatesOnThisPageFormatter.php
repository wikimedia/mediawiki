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

use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Linker\LinkTarget;

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
	 * @param IContextSource $context
	 * @param LinkRenderer $linkRenderer
	 */
	public function __construct( IContextSource $context, LinkRenderer $linkRenderer ) {
		$this->context = $context;
		$this->linkRenderer = $linkRenderer;
	}

	/**
	 * Make an HTML list of templates, and then add a "More..." link at
	 * the bottom. If $more is null, do not add a "More..." link. If $more
	 * is a LinkTarget, make a link to that title and use it. If $more is a string,
	 * directly paste it in as the link (escaping needs to be done manually).
	 *
	 * @param LinkTarget[] $templates
	 * @param string|bool $type 'preview' if a preview, 'section' if a section edit, false if neither
	 * @param LinkTarget|string|null $more An escaped link for "More..." of the templates
	 * @return string HTML output
	 */
	public function format( array $templates, $type = false, $more = null ) {
		if ( !$templates ) {
			// No templates
			return '';
		}

		# Do a batch existence check
		$batch = new LinkBatch;
		foreach ( $templates as $title ) {
			$batch->addObj( $title );
		}
		$batch->execute();

		# Construct the HTML
		$outText = '<div class="mw-templatesUsedExplanation">';
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
		$outText .= "</div><ul>\n";

		usort( $templates, 'Title::compare' );
		foreach ( $templates as $template ) {
			$outText .= $this->formatTemplate( $template );
		}

		if ( $more instanceof LinkTarget ) {
			$outText .= Html::rawElement( 'li', [], $this->linkRenderer->makeLink(
				$more, $this->context->msg( 'moredotdotdot' )->text() ) );
		} elseif ( $more ) {
			// Documented as should already be escaped
			$outText .= Html::rawElement( 'li', [], $more );
		}

		$outText .= '</ul>';
		return $outText;
	}

	/**
	 * Builds an <li> item for an individual template
	 *
	 * @param LinkTarget $target
	 * @return string
	 */
	private function formatTemplate( LinkTarget $target ) {
		// TODO Would be nice if we didn't have to use Title here
		$titleObj = Title::newFromLinkTarget( $target );
		$protected = $this->getRestrictionsText( $titleObj->getRestrictions( 'edit' ) );
		$editLink = $this->buildEditLink( $titleObj );
		return '<li>' . $this->linkRenderer->makeLink( $target )
			. $this->context->msg( 'word-separator' )->escaped()
			. $this->context->msg( 'parentheses' )->rawParams( $editLink )->escaped()
			. $this->context->msg( 'word-separator' )->escaped()
			. $protected . '</li>';
	}

	/**
	 * If the page is protected, get the relevant text
	 * for those restrictions
	 *
	 * @param array $restrictions
	 * @return string
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
	 * @param Title $titleObj
	 * @return string
	 */
	private function buildEditLink( Title $titleObj ) {
		if ( $titleObj->quickUserCan( 'edit', $this->context->getUser() ) ) {
			$linkMsg = 'editlink';
		} else {
			$linkMsg = 'viewsourcelink';
		}

		return $this->linkRenderer->makeLink(
			$titleObj,
			$this->context->msg( $linkMsg )->text(),
			[],
			[ 'action' => 'edit' ]
		);
	}

}
