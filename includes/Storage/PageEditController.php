<?php

namespace MediaWiki\Storage;
use Content;
use DeferredUpdates;
use Hooks;
use InvalidArgumentException;
use TitleValue;
use User;

/**
 * Controller for performing a page edit
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class PageEditController {

	/**
	 * @var TitleValue
	 */
	private $title;

	public function __construct( TitleValue $title ) {
		$this->title = $title;
	}

	/**
	 * @see WikiPage::doEditContent
	 *
	 * @todo: separate methods and special handling for null edit, blank revision, redirect, revert/restore, first revision, etc.
	 * @todo: replace User
	 *
	 * @param Content[] $contentSlots
	 * @param string $summary
	 * @param int $flags
	 * @param bool $baseRevId
	 * @param User $user
	 *
	 * @throws \Exception
	 * @throws \FatalError
	 * @throws \MWException
	 */
	public function editPage( $contentSlots, $summary, $flags = 0, $baseRevId = false,
		User $user = null ) {

		//TODO: special handling for redirects!
		//TODO: permission checks? does that belong here? Or should that go to a higher app level controller?

		// Allow extensions to add (and replace or remove) slots.
		Hooks::run( 'RevisionContentSlots', array( $this->title, &$contentSlots, $flags, $user ) );

		//TODO: ********* do all the stuff WikiPage::doEditContent does **************

		//TODO: ********* Use a RevisionContentStore for storing the revision content ***********

		// Update the links tables and other secondary data for ALL content slots.
		foreach ( $contentSlots as $slot => $content ) {
			$recursive = $options['changed']; // bug 50785
			$updates = $content->getSecondaryDataUpdates( $this->title, $oldContentSlots[$slot], $recursive, $editInfo->output );

			foreach ( $updates as $update ) {
				DeferredUpdates::addUpdate( $update );
			}
		}

	}

}
