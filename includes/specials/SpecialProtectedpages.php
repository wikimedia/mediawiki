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
		$sizetype = $request->getVal( 'size-mode' );
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
		$formDescriptor = [
			'namespace' => $this->getNamespaceMenu( $namespace ),
			'typemenu' => $this->getTypeMenu( $type ),
			'levelmenu' => $this->getLevelMenu( $level ),

			'expirycheck' => $this->getExpiryCheck( $indefOnly ),
			'cascadecheck' => $this->getCascadeCheck( $cascadeOnly ),
			'redirectcheck' => $this->getRedirectCheck( $noRedirect ),

			'sizelimit' => $this->getSizeLimit( $sizetype, $size ),
		];
		$htmlForm = new HTMLForm( $formDescriptor, $this->getContext() );
		$htmlForm
			->setMethod( 'get' )
			->setWrapperLegendMsg( 'protectedpages' )
			->setSubmitText( $this->msg( 'protectedpages-submit' )->text() );

		return $htmlForm->prepareForm()->getHTML( false );
	}

	/**
	 * Prepare the namespace filter drop-down; standard namespace
	 * selector, sans the MediaWiki namespace
	 *
	 * @param string|null $namespace Pre-select namespace
	 * @return array
	 */
	protected function getNamespaceMenu( $namespace = null ) {
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
	 * @return array
	 */
	protected function getExpiryCheck( $indefOnly ) {
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
	 * @return array
	 */
	protected function getCascadeCheck( $cascadeOnly ) {
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
	 * @return array
	 */
	protected function getRedirectCheck( $noRedirect ) {
		return [
			'type' => 'check',
			'label' => $this->msg( 'protectedpages-noredirect' )->text(),
			'name' => 'noredirect',
			'id' => 'noredirect',
			'value' => $noRedirect,
		];
	}

	/**
	 * @param string $sizetype "min" or "max"
	 * @param mixed $size
	 * @return array
	 */
	protected function getSizeLimit( $sizetype, $size ) {
		$max = $sizetype === 'max';

		return [
			'class' => 'HTMLSizeFilterField',
			'name' => 'size',
		];
	}

	/**
	 * Creates the input label of the restriction type
	 * @param string $pr_type Protection type
	 * @return array
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
	 * @return array
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
