<?php
/**
 * Formats credits for articles
 *
 * Copyright 2004, Evan Prodromou <evan@wikitravel.org>.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Actions
 * @author <evan@wikitravel.org>
 */

namespace MediaWiki\Actions;

use MediaWiki\Context\IContextSource;
use MediaWiki\Html\Html;
use MediaWiki\Linker\Linker;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\Article;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserRigorOptions;

/**
 * @ingroup Actions
 */
class CreditsAction extends FormlessAction {

	private LinkRenderer $linkRenderer;
	private UserFactory $userFactory;

	/**
	 * @param Article $article
	 * @param IContextSource $context
	 * @param LinkRenderer $linkRenderer
	 * @param UserFactory $userFactory
	 */
	public function __construct(
		Article $article,
		IContextSource $context,
		LinkRenderer $linkRenderer,
		UserFactory $userFactory
	) {
		parent::__construct( $article, $context );
		$this->linkRenderer = $linkRenderer;
		$this->userFactory = $userFactory;
	}

	/** @inheritDoc */
	public function getName() {
		return 'credits';
	}

	/** @inheritDoc */
	protected function getDescription() {
		return $this->msg( 'creditspage' )->escaped();
	}

	/**
	 * This is largely cadged from PageHistory::history
	 *
	 * @return string HTML
	 */
	public function onView() {
		$this->getOutput()->addModuleStyles( [
			'mediawiki.action.styles',
		] );

		if ( $this->getWikiPage()->getId() === 0 ) {
			$s = $this->msg( 'nocredits' )->parse();
		} else {
			$s = $this->getCredits( -1 );
		}

		return Html::rawElement( 'div', [ 'id' => 'mw-credits' ], $s );
	}

	/**
	 * Get a list of contributors
	 *
	 * @param int $cnt Maximum list of contributors to show
	 * @param bool $showIfMax Whether to contributors if there more than $cnt
	 * @return string Html
	 */
	public function getCredits( $cnt, $showIfMax = true ) {
		$s = '';

		if ( $cnt !== 0 ) {
			$s = $this->getAuthor();
			if ( $cnt > 1 || $cnt < 0 ) {
				$s .= ' ' . $this->getContributors( $cnt - 1, $showIfMax );
			}
		}

		return $s;
	}

	/**
	 * Get the last author with the last modification time
	 *
	 * @return string HTML
	 */
	private function getAuthor() {
		$page = $this->getWikiPage();
		$user = $this->userFactory->newFromName( $page->getUserText(), UserRigorOptions::RIGOR_NONE );

		$timestamp = $page->getTimestamp();
		if ( $timestamp ) {
			$lang = $this->getLanguage();
			$d = $lang->date( $page->getTimestamp(), true );
			$t = $lang->time( $page->getTimestamp(), true );
		} else {
			$d = '';
			$t = '';
		}

		return $this->msg( 'lastmodifiedatby', $d, $t )->rawParams(
			// @phan-suppress-next-line PhanTypeMismatchArgumentNullable RIGOR_NONE never returns null
			$this->userLink( $user ) )->params( $user->getName() )->escaped();
	}

	/**
	 * Whether we can display the user's real name (not a hidden pref)
	 *
	 * @since 1.24
	 * @return bool
	 */
	protected function canShowRealUserName() {
		$hiddenPrefs = $this->context->getConfig()->get( MainConfigNames::HiddenPrefs );
		return !in_array( 'realname', $hiddenPrefs );
	}

	/**
	 * Get a list of contributors of $article
	 * @param int $cnt Maximum list of contributors to show
	 * @param bool $showIfMax Whether to contributors if there more than $cnt
	 * @return string Html
	 */
	protected function getContributors( $cnt, $showIfMax ) {
		$contributors = $this->getWikiPage()->getContributors();

		$others_link = false;

		# Hmm... too many to fit!
		if ( $cnt > 0 && $contributors->count() > $cnt ) {
			$others_link = $this->othersLink();
			if ( !$showIfMax ) {
				return $this->msg( 'othercontribs' )->rawParams(
					$others_link )->params( $contributors->count() )->escaped();
			}
		}

		$real_names = [];
		$user_names = [];
		$anon_ips = [];

		# Sift for real versus user names
		/** @var User $user */
		foreach ( $contributors as $user ) {
			$cnt--;
			if ( $user->isRegistered() ) {
				$link = $this->link( $user );
				if ( $this->canShowRealUserName() && $user->getRealName() ) {
					$real_names[] = $link;
				} else {
					$user_names[] = $link;
				}
			} else {
				$anon_ips[] = $this->link( $user );
			}

			if ( $cnt === 0 ) {
				break;
			}
		}

		$lang = $this->getLanguage();

		if ( count( $real_names ) ) {
			$real = $lang->listToText( $real_names );
		} else {
			$real = false;
		}

		# "ThisSite user(s) A, B and C"
		if ( count( $user_names ) ) {
			$user = $this->msg( 'siteusers' )->rawParams( $lang->listToText( $user_names ) )->params(
				count( $user_names ) )->escaped();
		} else {
			$user = false;
		}

		if ( count( $anon_ips ) ) {
			$anon = $this->msg( 'anonusers' )->rawParams( $lang->listToText( $anon_ips ) )->params(
				count( $anon_ips ) )->escaped();
		} else {
			$anon = false;
		}

		# This is the big list, all mooshed together. We sift for blank strings
		$fullList = [];
		foreach ( [ $real, $user, $anon, $others_link ] as $s ) {
			if ( $s !== false ) {
				$fullList[] = $s;
			}
		}

		$count = count( $fullList );

		# "Based on work by ..."
		return $count
			? $this->msg( 'othercontribs' )->rawParams(
				$lang->listToText( $fullList ) )->params( $count )->escaped()
			: '';
	}

	/**
	 * Get a link to $user's user page
	 * @param User $user
	 * @return string Html
	 */
	protected function link( User $user ) {
		if ( $this->canShowRealUserName() && !$user->isAnon() ) {
			$real = $user->getRealName();
			if ( $real === '' ) {
				$real = $user->getName();
			}
		} else {
			$real = $user->getName();
		}

		return Linker::userLink( $user->getId(), $user->getName(), $real );
	}

	/**
	 * Get a link to $user's user page
	 * @param User $user
	 * @return string Html
	 */
	protected function userLink( User $user ) {
		$link = $this->link( $user );
		if ( $user->isAnon() ) {
			return $this->msg( 'anonuser' )->rawParams( $link )->parse();
		} elseif ( $this->canShowRealUserName() && $user->getRealName() ) {
			return $link;
		} else {
			return $this->msg( 'siteuser' )->rawParams( $link )->params( $user->getName() )->escaped();
		}
	}

	/**
	 * Get a link to action=credits of $article page
	 * @return string HTML link
	 */
	protected function othersLink() {
		return $this->linkRenderer->makeKnownLink(
			$this->getTitle(),
			$this->msg( 'others' )->text(),
			[],
			[ 'action' => 'credits' ]
		);
	}
}

/** @deprecated class alias since 1.44 */
class_alias( CreditsAction::class, 'CreditsAction' );
