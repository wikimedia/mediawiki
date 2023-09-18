<?php

namespace MediaWiki\Skin;

use MediaWiki\Permissions\Authority;
use MediaWiki\Request\WebRequest;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;

class SkinComponentUtils {
	/**
	 * Adds a class to the existing class value, supporting it as a string
	 * or array.
	 *
	 * @param string|array $class to update.
	 * @param string $newClass to add.
	 * @return string|array classes.
	 * @internal
	 */
	public static function addClassToClassList( $class, string $newClass ) {
		if ( is_array( $class ) ) {
			$class[] = $newClass;
		} else {
			$class .= ' ' . $newClass;
			$class = trim( $class );
		}
		return $class;
	}

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
		# Due to T34276, if a user does not have read permissions,
		# $this->getTitle() will just give Special:Badtitle, which is
		# not especially useful as a returnto parameter. Use the title
		# from the request instead, if there was one.
		if ( $authority->isAllowed( 'read' ) ) {
			$page = $title;
		} else {
			$page = Title::newFromText( $request->getVal( 'title', '' ) );
		}
		$page = $request->getVal( 'returnto', $page );

		$query = [];
		if ( !$request->wasPosted() ) {
			$query = $request->getValues();
			unset( $query['title'] );
			unset( $query['returnto'] );
			unset( $query['returntoquery'] );
		}

		$thisquery = wfArrayToCgi( $query );

		$returnto = [];
		if ( strval( $page ) !== '' ) {
			$returnto['returnto'] = $page;
			$query = $request->getVal( 'returntoquery', $thisquery );
			$paramsArray = wfCgiToArray( $query );
			$query = wfArrayToCgi( $paramsArray );
			if ( $query != '' ) {
				$returnto['returntoquery'] = $query;
			}
		}

		return $returnto;
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
