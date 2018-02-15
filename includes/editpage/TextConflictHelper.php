<?php
/**
 * Helper for displaying edit conflicts to users
 *
 * Copyright (C) 2017 Kunal Mehta <legoktm@member.fsf.org>
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

namespace MediaWiki\EditPage;

use Content;
use ContentHandler;
use Html;
use IBufferingStatsdDataFactory;
use OutputPage;
use Title;

/**
 * Helper for displaying edit conflicts in text content
 * models to users
 *
 * @since 1.31
 */
class TextConflictHelper {

	/**
	 * @var Title
	 */
	protected $title;

	/**
	 * @var null|string
	 */
	public $contentModel = null;

	/**
	 * @var null|string
	 */
	public $contentFormat = null;

	/**
	 * @var OutputPage
	 */
	protected $out;

	/**
	 * @var IBufferingStatsdDataFactory
	 */
	protected $stats;

	/**
	 * @var string Message key for submit button's label
	 */
	protected $submitLabel;

	/**
	 * @var string
	 */
	protected $yourtext = '';

	/**
	 * @var string
	 */
	protected $storedversion = '';

	/**
	 * @param Title $title
	 * @param OutputPage $out
	 * @param IBufferingStatsdDataFactory $stats
	 * @param string $submitLabel
	 */
	public function __construct( Title $title, OutputPage $out, IBufferingStatsdDataFactory $stats,
		$submitLabel
	) {
		$this->title = $title;
		$this->out = $out;
		$this->stats = $stats;
		$this->submitLabel = $submitLabel;
		$this->contentModel = $title->getContentModel();
		$this->contentFormat = ContentHandler::getForModelID( $this->contentModel )->getDefaultFormat();
	}

	/**
	 * @param string $yourtext
	 * @param string $storedversion
	 */
	public function setTextboxes( $yourtext, $storedversion ) {
		$this->yourtext = $yourtext;
		$this->storedversion = $storedversion;
	}

	/**
	 * @param string $contentModel
	 */
	public function setContentModel( $contentModel ) {
		$this->contentModel = $contentModel;
	}

	/**
	 * @param string $contentFormat
	 */
	public function setContentFormat( $contentFormat ) {
		$this->contentFormat = $contentFormat;
	}

	/**
	 * Record a user encountering an edit conflict
	 */
	public function incrementConflictStats() {
		$this->stats->increment( 'edit.failures.conflict' );
		// Only include 'standard' namespaces to avoid creating unknown numbers of statsd metrics
		if (
			$this->title->getNamespace() >= NS_MAIN &&
			$this->title->getNamespace() <= NS_CATEGORY_TALK
		) {
			$this->stats->increment(
				'edit.failures.conflict.byNamespaceId.' . $this->title->getNamespace()
			);
		}
	}

	/**
	 * Record when a user has resolved an edit conflict
	 */
	public function incrementResolvedStats() {
		$this->stats->increment( 'edit.failures.conflict.resolved' );
		// Only include 'standard' namespaces to avoid creating unknown numbers of statsd metrics
		if (
			$this->title->getNamespace() >= NS_MAIN &&
			$this->title->getNamespace() <= NS_CATEGORY_TALK
		) {
			$this->stats->increment(
				'edit.failures.conflict.resolved.byNamespaceId.' . $this->title->getNamespace()
			);
		}
	}

	/**
	 * @return string HTML
	 */
	public function getExplainHeader() {
		return Html::rawElement(
			'div',
			[ 'class' => 'mw-explainconflict' ],
			$this->out->msg( 'explainconflict', $this->out->msg( $this->submitLabel )->text() )->parse()
		);
	}

	/**
	 * HTML to build the textbox1 on edit conflicts
	 *
	 * @param mixed[]|null $customAttribs
	 * @return string HTML
	 */
	public function getEditConflictMainTextBox( $customAttribs = [] ) {
		$builder = new TextboxBuilder();
		$classes = $builder->getTextboxProtectionCSSClasses( $this->title );

		$attribs = [ 'tabindex' => 1 ];
		$attribs += $customAttribs;

		$attribs = $builder->mergeClassesIntoAttributes( $classes, $attribs );

		$attribs = $builder->buildTextboxAttribs(
			'wpTextbox1',
			$attribs,
			$this->out->getUser(),
			$this->title
		);

		$this->out->addHTML(
			Html::textarea( 'wpTextbox1', $builder->addNewLineAtEnd( $this->storedversion ), $attribs )
		);
	}

	/**
	 * Content to go in the edit form before textbox1
	 *
	 * @see EditPage::$editFormTextBeforeContent
	 * @return string HTML
	 */
	public function getEditFormHtmlBeforeContent() {
		return '';
	}

	/**
	 * Content to go in the edit form after textbox1
	 *
	 * @see EditPage::$editFormTextAfterContent
	 * @return string HTML
	 */
	public function getEditFormHtmlAfterContent() {
		return '';
	}

	/**
	 * Content to go in the edit form after the footers
	 * (templates on this page, hidden categories, limit report)
	 */
	public function showEditFormTextAfterFooters() {
		$this->out->wrapWikiMsg( '<h2>$1</h2>', "yourdiff" );

		$yourContent = $this->toEditContent( $this->yourtext );
		$storedContent = $this->toEditContent( $this->storedversion );
		$handler = ContentHandler::getForModelID( $this->contentModel );
		$diffEngine = $handler->createDifferenceEngine( $this->out );

		$diffEngine->setContent( $yourContent, $storedContent );
		$diffEngine->showDiff(
			$this->out->msg( 'yourtext' )->parse(),
			$this->out->msg( 'storedversion' )->text()
		);

		$this->out->wrapWikiMsg( '<h2>$1</h2>', "yourtext" );

		$builder = new TextboxBuilder();
		$attribs = $builder->buildTextboxAttribs(
			'wpTextbox2',
			[ 'tabindex' => 6, 'readonly' ],
			$this->out->getUser(),
			$this->title
		);

		$this->out->addHTML(
			Html::textarea( 'wpTextbox2', $builder->addNewLineAtEnd( $this->yourtext ), $attribs )
		);
	}

	/**
	 * @param string $text
	 * @return Content
	 */
	public function toEditContent( $text ) {
		return ContentHandler::makeContent(
			$text,
			$this->title,
			$this->contentModel,
			$this->contentFormat
		);
	}
}
