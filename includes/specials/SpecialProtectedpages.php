<?php
/**
 * Implements Special:Protectedpages
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
 * A special page that lists protected pages
 *
 * @ingroup SpecialPage
 */
class SpecialProtectedpages extends SpecialPage {
	protected $IdLevel = 'level';
	protected $IdType = 'type';

	public function __construct() {
		parent::__construct( 'Protectedpages' );
	}

	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$this->getOutput()->addModuleStyles( 'mediawiki.special' );

		$request = $this->getRequest();
		$type = $request->getVal( $this->IdType );
		$level = $request->getVal( $this->IdLevel );
		$sizetype = $request->getVal( 'sizetype' );
		$size = $request->getIntOrNull( 'size' );
		$ns = $request->getIntOrNull( 'namespace' );
		$indefOnly = $request->getBool( 'indefonly' ) ? 1 : 0;
		$cascadeOnly = $request->getBool( 'cascadeonly' ) ? 1 : 0;
		$noRedirect = $request->getBool( 'noredirect' ) ? 1 : 0;

		$pager = new ProtectedPagesPager(
			$this,
			[],
			$type,
			$level,
			$ns,
			$sizetype,
			$size,
			$indefOnly,
			$cascadeOnly,
			$noRedirect,
			$this->getLinkRenderer()
		);

		$this->getOutput()->addHTML( $this->showOptions(
			$ns,
			$type,
			$level,
			$sizetype,
			$size,
			$indefOnly,
			$cascadeOnly,
			$noRedirect
		) );

		if ( $pager->getNumRows() ) {
			$this->getOutput()->addParserOutputContent( $pager->getFullOutput() );
		} else {
			$this->getOutput()->addWikiMsg( 'protectedpagesempty' );
		}
	}

	/**
	 * @param int $namespace
	 * @param string $type Restriction type
	 * @param string $level Restriction level
	 * @param string $sizetype "min" or "max"
	 * @param int $size
	 * @param bool $indefOnly Only indefinite protection
	 * @param bool $cascadeOnly Only cascading protection
	 * @param bool $noRedirect Don't show redirects
	 * @return string Input form
	 */
	protected function showOptions( $namespace, $type = 'edit', $level, $sizetype,
		$size, $indefOnly, $cascadeOnly, $noRedirect
	) {
		$title = $this->getPageTitle();

		$formDescriptor = [
			'titlehidden' => [
				'class' => 'HTMLHiddenField',
				'name' => 'title',
				'value' => $title->getPrefixedDBkey(),
			],

			'namespace' => $this->getNamespaceMenu2( $namespace ),
			'typemenu' => $this->getTypeMenu2( $type ),
			'levelmenu' => $this->getLevelMenu2( $level ),

			'expirycheck' => $this->getExpiryCheck2( $indefOnly ),
			'cascadecheck' => $this->getCascadeCheck2( $cascadeOnly ),
			'redirectcheck' => $this->getRedirectCheck2( $noRedirect ),

			'sizelimit' => $this->getSizeLimit2( $sizetype, $size ),
		];
		$htmlForm = new HTMLForm( $formDescriptor, $this->getContext() );
		$htmlForm
			->setWrapperLegendMsg( 'protectedpages' )
			->setSubmitText( $this->msg( 'protectedpages-submit' )->text() );

		return Xml::openElement( 'form', [ 'method' => 'get', 'action' => wfScript() ] ) .
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', [], $this->msg( 'prozotectedpages' )->text() ) .
			Html::hidden( 'title', $title->getPrefixedDBkey() ) . "\n" .
			$this->getNamespaceMenu( $namespace ) . "\n" .
			$this->getTypeMenu( $type ) . "\n" .
			$this->getLevelMenu( $level ) . "\n" .
			"<br />\n" .
			$this->getExpiryCheck( $indefOnly ) . "\n" .
			$this->getCascadeCheck( $cascadeOnly ) . "\n" .
			$this->getRedirectCheck( $noRedirect ) . "\n" .
			"<br />\n" .
			$this->getSizeLimit( $sizetype, $size ) . "\n" .
			Xml::submitButton( $this->msg( 'protectedpages-submit' )->text() ) . "\n" .
			Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' ) .
			$htmlForm->prepareForm()->getHTML( true );
	}

	/**
	 * Prepare the namespace filter drop-down; standard namespace
	 * selector, sans the MediaWiki namespace
	 *
	 * @param string|null $namespace Pre-select namespace
	 * @return string
	 */
	protected function getNamespaceMenu( $namespace = null ) {
		return Html::rawElement( 'span', [ 'class' => 'mw-input-with-label' ],
			Html::namespaceSelector(
				[
					'selected' => $namespace,
					'all' => '',
					'label' => $this->msg( 'namespace' )->text()
				], [
					'name' => 'namespace',
					'id' => 'namespace',
					'class' => 'namespaceselector',
				]
			)
		);
	}

	/**
	 * Prepare the namespace filter drop-down; standard namespace
	 * selector, sans the MediaWiki namespace
	 *
	 * @param string|null $namespace Pre-select namespace
	 * @return TODO
	 */
	protected function getNamespaceMenu2( $namespace = null ) {
		return [
			'class' => 'HTMLSelectNamespace',
			'name' => 'namespace',
			'id' => 'namespace',
			'cssclass' => 'namespaceselector',
			'selected' => $namespace,
			'all' => '',
			'label' => $this->msg( 'namespace' )->text(),
		];
	}


	/**
	 * @param bool $indefOnly
	 * @return string Formatted HTML
	 */
	protected function getExpiryCheck( $indefOnly ) {
		return '<span class="mw-input-with-label">' . Xml::checkLabel(
			$this->msg( 'protectedpages-indef' )->text(),
			'indefonly',
			'indefonly',
			$indefOnly
		) . "</span>\n";
	}

	/**
	 * @param bool $indefOnly
	 * @return TODO
	 */
	protected function getExpiryCheck2( $indefOnly ) {
		return [
			'type' => 'check',
			'label' => $this->msg( 'protectedpages-indef' )->text(),
			'name' => 'indefonly',
			'id' => 'indefonly',
			'value' => $indefOnly
		];
	}

	/**
	 * @param bool $cascadeOnly
	 * @return string Formatted HTML
	 */
	protected function getCascadeCheck( $cascadeOnly ) {
		return '<span class="mw-input-with-label">' . Xml::checkLabel(
			$this->msg( 'protectedpages-cascade' )->text(),
			'cascadeonly',
			'cascadeonly',
			$cascadeOnly
		) . "</span>\n";
	}

	/**
	 * @param bool $cascadeOnly
	 * @return TODO
	 */
	protected function getCascadeCheck2( $cascadeOnly ) {
		return [
			'type' => 'check',
			'label' => $this->msg( 'protectedpages-cascade' )->text(),
			'name' => 'cascadeonly',
			'id' => 'cascadeonly',
			'value' => $cascadeOnly
		];
	}

	/**
	 * @param bool $noRedirect
	 * @return string Formatted HTML
	 */
	protected function getRedirectCheck( $noRedirect ) {
		return '<span class="mw-input-with-label">' . Xml::checkLabel(
			$this->msg( 'protectedpages-noredirect' )->text(),
			'noredirect',
			'noredirect',
			$noRedirect
		) . "</span>\n";
	}

	/**
	 * @param bool $noRedirect
	 * @return TODO
	 */
	protected function getRedirectCheck2( $noRedirect ) {
		return [
			'type' => 'check',
			'label' => $this->msg ( 'protectedpages-noredirect' )->text(),
			'name' => 'noredirect',
			'id' => 'noredirect',
			'value' => $noRedirect,
		];
	}

	/**
	 * @param string $sizetype "min" or "max"
	 * @param mixed $size
	 * @return string Formatted HTML
	 */
	protected function getSizeLimit( $sizetype, $size ) {
		$max = $sizetype === 'max';

		return '<span class="mw-input-with-label">' . Xml::radioLabel(
			$this->msg( 'minimum-size' )->text(),
			'sizetype',
			'min',
			'wpmin',
			!$max
		) .
			' ' .
			Xml::radioLabel(
				$this->msg( 'maximum-size' )->text(),
				'sizetype',
				'max',
				'wpmax',
				$max
			) .
			' ' .
			Xml::input( 'size', 9, $size, [ 'id' => 'wpsize' ] ) .
			' ' .
			Xml::label( $this->msg( 'pagesize' )->text(), 'wpsize' ) . "</span>\n";
	}

	/**
	 * @param string $sizetype "min" or "max"
	 * @param mixed $size
	 * @return TODO
	 */
	protected function getSizeLimit2( $sizetype, $size ) {
		$max = $sizetype === 'max';

		return [
			'type' => 'radio',
			'label' => $this->msg( 'minimum-size' )->text(),
			'name' => 'size',
			'options' => [
				'min' => 'wpmin',
				'max' => 'wpmax',
			],
		];
	}

	/**
	 * Creates the input label of the restriction type
	 * @param string $pr_type Protection type
	 * @return string Formatted HTML
	 */
	protected function getTypeMenu( $pr_type ) {
		$m = []; // Temporary array
		$options = [];

		// First pass to load the log names
		foreach ( Title::getFilteredRestrictionTypes( true ) as $type ) {
			// Messages: restriction-edit, restriction-move, restriction-create, restriction-upload
			$text = $this->msg( "restriction-$type" )->text();
			$m[$text] = $type;
		}

		// Third pass generates sorted XHTML content
		foreach ( $m as $text => $type ) {
			$selected = ( $type == $pr_type );
			$options[] = Xml::option( $text, $type, $selected ) . "\n";
		}

		return '<span class="mw-input-with-label">' .
			Xml::label( $this->msg( 'restriction-type' )->text(), $this->IdType ) . ' ' .
			Xml::tags( 'select',
				[ 'id' => $this->IdType, 'name' => $this->IdType ],
				implode( "\n", $options ) ) . "</span>";
	}

	/**
	 * Creates the input label of the restriction type
	 * @param string $pr_type Protection type
	 * @return TODO
	 */
	protected function getTypeMenu2( $pr_type ) {
		$m = []; // Temporary array
		$options = [];

		// First pass to load the log names
		foreach ( Title::getFilteredRestrictionTypes( true ) as $type ) {
			// Messages: restriction-edit, restriction-move, restriction-create, restriction-upload
			$text = $this->msg( "restriction-$type" )->text();
			$m[$text] = $type;
		}

		// Third pass generates sorted XHTML content
		foreach ( $m as $text => $type ) {
			$options[$text] = $type;
		}

		return [
			'type' => 'select',
			'options' => $options,
			'value' => $pr_type,
			'label' => $this->msg( 'restriction-type' )->text(),
			'name' => $this->IdType,
			'id' => $this->IdType,
		];
	}

	/**
	 * Creates the input label of the restriction level
	 * @param string $pr_level Protection level
	 * @return string Formatted HTML
	 */
	protected function getLevelMenu( $pr_level ) {
		// Temporary array
		$m = [ $this->msg( 'restriction-level-all' )->text() => 0 ];
		$options = [];

		// First pass to load the log names
		foreach ( $this->getConfig()->get( 'RestrictionLevels' ) as $type ) {
			// Messages used can be 'restriction-level-sysop' and 'restriction-level-autoconfirmed'
			if ( $type != '' && $type != '*' ) {
				$text = $this->msg( "restriction-level-$type" )->text();
				$m[$text] = $type;
			}
		}

		// Third pass generates sorted XHTML content
		foreach ( $m as $text => $type ) {
			$selected = ( $type == $pr_level );
			$options[] = Xml::option( $text, $type, $selected );
		}

		return '<span class="mw-input-with-label">' .
			Xml::label( $this->msg( 'restriction-level' )->text(), $this->IdLevel ) . ' ' .
			Xml::tags( 'select',
				[ 'id' => $this->IdLevel, 'name' => $this->IdLevel ],
				implode( "\n", $options ) ) . "</span>";
	}

	/**
	 * Creates the input label of the restriction level
	 * @param string $pr_level Protection level
	 * @return TODO
	 */
	protected function getLevelMenu2( $pr_level ) {
		// Temporary array
		$m = [ $this->msg( 'restriction-level-all' )->text() => 0 ];
		$options = [];

		// First pass to load the log names
		foreach ( $this->getConfig()->get( 'RestrictionLevels' ) as $type ) {
			// Messages used can be 'restriction-level-sysop' and 'restriction-level-autoconfirmed'
			if ( $type != '' && $type != '*' ) {
				$text = $this->msg( "restriction-level-$type" )->text();
				$m[$text] = $type;
			}
		}

		// Third pass generates sorted XHTML content
		foreach ( $m as $text => $type ) {
			$options[$text] = $type;
		}

		return [
			'type' => 'select',
			'options' => $options,
			'value' => $pr_level,
			'label' => $this->msg( 'restriction-level' )->text(),
			'name' => $this->IdLevel,
			'id' => $this->IdLevel
		];
	}

	protected function getGroupName() {
		return 'maintenance';
	}
}
