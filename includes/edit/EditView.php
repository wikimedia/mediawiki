<?php
/**
 * User interface elements for page editing.
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
 * Controlled by the Editor class
 *
 * EditView cares about two distinct titles:
 *  - $this->contextTitle is the page that forms submit to, links point to, redirects go to, etc.
 *  - $this->title is the page in the database that is actually being edited.
 * These are usually the same, but they are now allowed to be different.
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

	/** @var Edit */
	protected $edit;

	/** @var Title */
	protected $contextTitle;

	/** @var string */
	protected $action;

	/**
	 * Set by Editor (from edit status)
	 */

	/** @var bool */
	private $isConflict = false;

	/** @var string */
	private $missingSummary = false;

	/**
	 * @param IContextSource $context
	 * @param Edit $edit
	 * @param Title $contextTitle
	 * @param string $action
	 */
	final public function __construct( IContextSource $context, Edit $edit,
		Title $contextTitle, $action
	) {
		$this->context = $context;
		$this->title = $this->context->getTitle(); // shortcut
		$this->output = $context->getOutput(); // shortcut
		$this->edit = $edit;
		$this->contextTitle = $contextTitle;
		$this->action = $action;
	}

	final public function getContext() {
		return $this->context;
	}

	final public function getContextTitle() {
		return $this->contextTitle;
	}

	final public function getEdit() {
		return $this->edit;
	}

	final public function setIsConflict( $isConflict ) {
		$this->isConflict = $isConflict;
	}

	final public function setMissingSummary( $missingSummary ) {
		$this->missingSummary = $missingSummary;
	}

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
	 * @param bool $noSummary If $isSubjectPreview is true, whether to hide the section subject
	 */
	protected function showSummaryInput( $isSubjectPreview, $summary = "", $noSummary = false ) {
		# Add a class if 'missingSummary' is triggered to allow styling of the summary line
		$summaryClass = $this->missingSummary ? 'mw-summarymissed' : 'mw-summary';
		if ( $isSubjectPreview ) {
			if ( $noSummary ) {
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
