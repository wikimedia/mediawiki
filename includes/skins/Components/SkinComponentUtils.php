<?php

namespace MediaWiki\Skin;

use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Authority;
use MediaWiki\Request\WebRequest;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;

class SkinComponentUtils {

	/**
	 * Builds query params for the page to return to, used when building links
	 * @unstable
	 *
	 * @param Title $title
	 * @param WebRequest $request
	 * @param Authority $authority
	 * @return string[]
	 */
	public static function getReturnToParam( $title, $request, $authority ) {
		// T379295/T381216: Preserve authentication query params so they don't get lost
		// during switching between Login/Logout or CreateAccount pages where we need them.
		// See AuthManagerSpecialPage/LoginSignupSpecialPage::getPreservedParams().
		// This special case also avoids "nesting" returnto values on these pages.
		if (
			$title->isSpecial( 'Userlogin' )
			|| $title->isSpecial( 'CreateAccount' )
			|| $title->isSpecial( 'Userlogout' )
		) {
			$params = [
				'uselang' => $request->getVal( 'uselang' ),
				'variant' => $request->getVal( 'variant' ),
				'display' => $request->getVal( 'display' ),
				'returnto' => $request->getVal( 'returnto' ),
				'returntoquery' => $request->getVal( 'returntoquery' ),
				'returntoanchor' => $request->getVal( 'returntoanchor' ),
			];
			( new HookRunner( MediaWikiServices::getInstance()->getHookContainer() ) )
				->onAuthPreserveQueryParams( $params, [ 'request' => $request, 'reset' => true ] );
			return array_filter( $params, static fn ( $val ) => $val !== null );
		}

		# Due to T34276, if a user does not have read permissions,
		# $this->getTitle() will just give Special:Badtitle, which is
		# not especially useful as a returnto parameter. Use the title
		# from the request instead, if there was one.
		if ( $authority->isAllowed( 'read' ) ) {
			$page = $title;
		} else {
			$page = Title::newFromText( $request->getVal( 'title', '' ) );
		}

		$query = [];
		if ( !$request->wasPosted() ) {
			$query = $request->getQueryValues();
			unset( $query['title'] );
		}

		$params = [];
		if ( $page ) {
			$params['returnto'] = $page->getPrefixedText();
			if ( $query ) {
				$params['returntoquery'] = wfArrayToCgi( $query );
			}
		}

		return $params;
	}

	/**
	 * Make a URL for a Special Page using the given query and protocol.
	 *
	 * If $proto is set to null, make a local URL. Otherwise, make a full
	 * URL with the protocol specified.
	 *
	 * @param string $name Name of the Special page
	 * @param string|array $urlaction Query to append
	 * @param string|null $proto Protocol to use or null for a local URL
	 * @return string
	 */
	public static function makeSpecialUrl( $name, $urlaction = '', $proto = null ) {
		$title = SpecialPage::getSafeTitleFor( $name );
		if ( $proto === null ) {
			return $title->getLocalURL( $urlaction );
		} else {
			return $title->getFullURL( $urlaction, false, $proto );
		}
	}

	/**
	 * @param string $name
	 * @param string|bool $subpage false for no subpage
	 * @param string|array $urlaction
	 * @return string
	 */
	public static function makeSpecialUrlSubpage( $name, $subpage, $urlaction = '' ) {
		$title = SpecialPage::getSafeTitleFor( $name, $subpage );
		return $title->getLocalURL( $urlaction );
	}
}
