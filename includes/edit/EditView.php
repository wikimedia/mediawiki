<?php
/**
 * Abstract user interface for page editing.
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

use \MediaWiki\MediaWikiServices;

/**
 * Abstract class for the page editing user interface
 *
 * This provides an edit box, a summary input / preview and an edit button.
 */
abstract class EditView {

	/**
	 * Constructor-set
	 */

	/** @var Title */
	protected $title;

	/** @var IContextSource */
	protected $context;

	/** @var OutputPage */
	protected $output;

	/** @var Title */
	protected $contextTitle;

	/** @var EditFormDataSource */
	protected $dataSource;

	/**
	 * Can be overriden by subclasses
	 */

	/** @var string */
	protected $action = 'submit';

	/**
	 * Set by Editor (from its own properties)
	 */

	/** @var bool */
	private $isConflict = false;

	/** @var string */
	private $missingSummary = false;

	/**
	 * @param Title $title
	 * @param IContextSource $context
	 * @param EditFormDataSource $dataSource
	 */
	final public function __construct( Title $title, IContextSource $context,
		EditFormDataSource $dataSource
	) {
		$this->title = $title;
		$this->context = $context;
		$this->output = $context->getOutput(); // shortcut
		$this->contextTitle = $context->getTitle(); // shortcut
		$this->dataSource = $dataSource;
	}

	final public function getTitle() {
		return $this->title;
	}

	final public function getContext() {
		return $this->context;
	}

	final public function getDataSource() {
		return $this->dataSource;
	}

	final public function getAction() {
		return $this->action;
	}

	/**
	 * set by controller
	 */

	final public function setIsConflict( $isConflict ) {
		$this->isConflict = $isConflict;
	}

	final public function setMissingSummary( $missingSummary ) {
		$this->missingSummary = $missingSummary;
	}

	/**
	 * get
	 */

	final public function isConflict() {
		return $this->isConflict;
	}

	final public function hasMissingSummary() {
		return $this->missingSummary;
	}

	protected function showTextbox( $text, $name, $customAttribs = [], $isUnicodeCompliant, $font ) {
		if ( $isUnicodeCompliant ) {
			$wikitext = $text;
		} else {
			$wikitext = EditUtilities::makeSafe( $text );
		}
		$wikitext = EditUtilities::addNewLineAtEnd( $wikitext );

		$attribs = $this->buildTextboxAttribs( $name, $customAttribs, $font );

		$this->output->addHTML( Html::textarea( $name, $wikitext, $attribs ) );
	}

	/**
	 * @param string $name
	 * @param mixed[] $customAttribs
	 * @param User $user
	 * @return mixed[]
	 * @since 1.29
	 */
	private function buildTextboxAttribs( $name, array $customAttribs, $font ) {
		$attribs = $customAttribs + [
				'accesskey' => ',',
				'id' => $name,
				'cols' => 80,
				'rows' => 25,
				// Avoid PHP notices when appending preferences
				// (appending allows customAttribs['style'] to still work).
				'style' => ''
			];

		// The following classes can be used here:
		// * mw-editfont-default
		// * mw-editfont-monospace
		// * mw-editfont-sans-serif
		// * mw-editfont-serif
		$class = 'mw-editfont-' . $font;

		if ( isset( $attribs['class'] ) ) {
			if ( is_string( $attribs['class'] ) ) {
				$attribs['class'] .= ' ' . $class;
			} elseif ( is_array( $attribs['class'] ) ) {
				$attribs['class'][] = $class;
			}
		} else {
			$attribs['class'] = $class;
		}

		$pageLang = $this->title->getPageLanguage();
		$attribs['lang'] = $pageLang->getHtmlCode();
		$attribs['dir'] = $pageLang->getDir();

		return $attribs;
	}

	/**
	 * @param bool $isSubjectPreview True if this is the section subject/title
	 *   up top, or false if this is the comment summary
	 *   down below the textarea
	 * @param string $summary The text of the summary to display
	 */
	protected function showSummaryInput( $isSubjectPreview, $summary = "" ) {
		# Add a class if 'missingSummary' is triggered to allow styling of the summary line
		$summaryClass = $this->missingSummary ? 'mw-summarymissed' : 'mw-summary';
		if ( $isSubjectPreview ) {
			if ( $this->dataSource->noSummary ) {
				return;
			}
		} else {
			if ( !$this->showSummaryField ) {
				return;
			}
		}
		$labelText = $this->context->msg( $isSubjectPreview ? 'subject' : 'summary' )->parse();
		list( $label, $input ) = EditUtilities::getSummaryInput(
			$summary,
			$labelText,
			[ 'class' => $summaryClass ],
			[],
			$this->missingSummary
		);
		$this->output->addHTML( "{$label} {$input}" );
	}

	/**
	 * @param bool $isSubjectPreview True if this is the section subject/title
	 *   up top, or false if this is the comment summary
	 *   down below the textarea
	 * @param string $summary The text of the summary to display
	 * @return string
	 */
	protected function getSummaryPreview( $isSubjectPreview, $summary = "" ) {
		// avoid spaces in preview, gets always trimmed on save
		$summary = trim( $summary );
		if ( !$summary || ( !$this->dataSource->preview && !$this->dataSource->diff ) ) {
			return "";
		}

		$parser = MediaWikiServices::getInstance()->getParser();

		if ( $isSubjectPreview ) {
			$summary = $this->context->msg( 'newsectionsummary' )
				->rawParams( $parser->stripSectionName( $summary ) )
				->inContentLanguage()->text();
		}

		$message = $isSubjectPreview ? 'subject-preview' : 'summary-preview';

		$summary = $this->context->msg( $message )->parse()
			. Linker::commentBlock( $summary, $this->title, $isSubjectPreview );
		return Xml::tags( 'div', [ 'class' => 'mw-summary-preview' ], $summary );
	}

	/**
	 * Returns the save button
	 *
	 * @param int $tabindex Current tabindex
	 *
	 * @return array
	 */
	protected function getEditButtons( &$tabindex ) {
		global $wgEditSubmitButtonLabelPublish;
		$buttons = [];

		$labelAsPublish = $wgEditSubmitButtonLabelPublish;

		// Can't use $this->isNew as that's also true if we're adding a new section to an extant page
		if ( $labelAsPublish ) {
			$buttonLabelKey = !$this->title->exists() ? 'publishpage' : 'publishchanges';
		} else {
			$buttonLabelKey = !$this->title->exists() ? 'savearticle' : 'savechanges';
		}
		$buttonLabel = $this->context->msg( $buttonLabelKey )->text();
		$attribs = [
			'id' => 'wpSave',
			'name' => 'wpSave',
			'tabindex' => ++$tabindex,
		] + Linker::tooltipAndAccesskeyAttribs( 'save' );
		$buttons['save'] = Html::submitButton( $buttonLabel, $attribs, [ 'mw-ui-progressive' ] );

		return $buttons;
	}

	/**
	 * Returns the URL to use in the form's action attribute.
	 * This is used by EditView subclasses when simply customizing the action
	 * variable in the constructor is not enough. This can be used when the
	 * EditView lives inside of a Special page rather than a custom page action.
	 *
	 * @param Title $title Title object for which is being edited (where we go to for &action= links)
	 * @return string
	 */
	protected function getActionURL( Title $title ) {
		return $title->getLocalURL( [ 'action' => $this->action ] );
	}
}
