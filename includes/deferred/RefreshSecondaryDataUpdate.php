<?php
/**
 * Updater for secondary data after a page edit.
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

/**
 * Update object handling the cleanup of secondary data after a page was edited.
 *
 * This makes makes it possible for DeferredUpdates to have retry logic using a single
 * refreshLinks job if any of the bundled updates fail.
 */
class RefreshSecondaryDataUpdate extends DataUpdate implements EnqueueableDataUpdate {
	/** @var WikiPage */
	private $page;
	/** @var DeferrableUpdate[] */
	private $updates;
	/** @var bool */
	private $recursive;
	/** @var string */
	private $cacheTimestamp;
	/** @var string Database domain ID */
	private $domain;

	/** @var Revision|null */
	private $revision;
	/** @var User|null */
	private $user;

	/**
	 * @param WikiPage $page Page we are updating
	 * @param DeferrableUpdate[] $updates Updates from DerivedPageDataUpdater::getSecondaryUpdates()
	 * @param array $options Options map (causeAction, causeAgent, recursive)
	 * @param string $cacheTime Result of ParserOutput::getCacheTime() for the source output
	 * @param string $domain The database domain ID of the wiki the update is for
	 */
	function __construct(
		WikiPage $page,
		array $updates,
		array $options,
		$cacheTime,
		$domain
	) {
		parent::__construct();

		$this->page = $page;
		$this->updates = $updates;
		$this->causeAction = $options['causeAction'] ?? 'unknown';
		$this->causeAgent = $options['causeAgent'] ?? 'unknown';
		$this->recursive = !empty( $options['recursive'] );
		$this->cacheTimestamp = $cacheTime;
		$this->domain = $domain;
	}

	public function doUpdate() {
		foreach ( $this->updates as $update ) {
			$update->doUpdate();
		}
	}

	/**
	 * Set the revision corresponding to this LinksUpdate
	 * @param Revision $revision
	 */
	public function setRevision( Revision $revision ) {
		$this->revision = $revision;
	}

	/**
	 * Set the User who triggered this LinksUpdate
	 * @param User $user
	 */
	public function setTriggeringUser( User $user ) {
		$this->user = $user;
	}

	public function getAsJobSpecification() {
		return [
			'wiki' => WikiMap::getWikiIdFromDomain( $this->domain ),
			'job'  => new JobSpecification(
				'refreshLinksPrioritized',
				[
					// Reuse the parser cache if it was saved
					'rootJobTimestamp' => $this->cacheTimestamp,
					'useRecursiveLinksUpdate' => $this->recursive,
					'triggeringUser' => $this->user
						? [
							'userId' => $this->user->getId(),
							'userName' => $this->user->getName()
						]
						: false,
					'triggeringRevisionId' => $this->revision ? $this->revision->getId() : false,
					'causeAction' => $this->getCauseAction(),
					'causeAgent' => $this->getCauseAgent()
				],
				[ 'removeDuplicates' => true ],
				$this->page->getTitle()
			)
		];
	}
}
