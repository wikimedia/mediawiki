<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA
 *
 * @file
 * @ingroup Actions
 */

use MediaWiki\SpecialPage\SpecialPageFactory;

/**
 * An action that just passes the request to the relevant special page
 *
 * @ingroup Actions
 * @since 1.25
 */
class SpecialPageAction extends FormlessAction {
	/**
	 * @var array A mapping of action names to special page names.
	 */
	public static $actionToSpecialPageMapping = [
		'revisiondelete' => 'Revisiondelete',
		'editchangetags' => 'EditTags',
	];

	/** @var SpecialPageFactory */
	private $specialPageFactory;

	/** @var string Name of this action, must exist as a key in $actionToSpecialPageMapping */
	private $actionName;

	/**
	 * @param Article $article
	 * @param IContextSource $context
	 * @param SpecialPageFactory $specialPageFactory
	 * @param string $actionName
	 */
	public function __construct(
		Article $article,
		IContextSource $context,
		SpecialPageFactory $specialPageFactory,
		string $actionName
	) {
		parent::__construct( $article, $context );
		$this->specialPageFactory = $specialPageFactory;
		if ( !isset( self::$actionToSpecialPageMapping[$actionName] ) ) {
			throw new InvalidArgumentException(
				__CLASS__ . " does not support the action $actionName"
			);
		}
		$this->actionName = $actionName;
	}

	/**
	 * @inheritDoc
	 */
	public function getName() {
		return $this->actionName;
	}

	public function requiresUnblock() {
		return false;
	}

	public function getDescription() {
		return '';
	}

	public function onView() {
		return '';
	}

	public function show() {
		$special = $this->getSpecialPage();
		if ( !$special ) {
			throw new ErrorPageError(
				$this->msg( 'nosuchaction' ), $this->msg( 'nosuchactiontext' ) );
		}

		$special->setContext( $this->getContext() );
		$special->getContext()->setTitle( $special->getPageTitle() );
		$special->run( '' );
	}

	public function doesWrites() {
		$special = $this->getSpecialPage();

		return $special ? $special->doesWrites() : false;
	}

	/**
	 * @return SpecialPage|null
	 */
	protected function getSpecialPage() {
		// map actions to (allowed) special pages
		return $this->specialPageFactory->getPage(
			self::$actionToSpecialPageMapping[$this->actionName]
		);
	}
}
