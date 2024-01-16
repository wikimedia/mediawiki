<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\User\User;

/**
 * Helper class to reduce typing in manual debugging tools like shell.php.
 * @internal must not be used in code, anywhere
 */
class MW {

	public static function srv(): MediaWikiServices {
		return MediaWikiServices::getInstance();
	}

	public static function wan(): WANObjectCache {
		return self::srv()->getMainWANObjectCache();
	}

	public static function user( string $username ): User {
		$user = self::srv()->getUserFactory()->newFromName( $username );
		if ( !$user ) {
			throw new DomainException( "Invalid username: $username" );
		}
		// preload so dumping the object is more informative
		$user->load();
		return $user;
	}

	public static function title( string $title ): Title {
		$title = self::srv()->getTitleFactory()->newFromTextThrow( $title );
		// preload so dumping the object is more informative
		$title->getArticleID();
		return $title;
	}

	public static function file( string $filename ): File {
		$file = self::srv()->getRepoGroup()->findFile( $filename );
		$file->load();
		return $file;
	}

	public static function page( string $title ): WikiPage {
		$page = self::srv()->getWikiPageFactory()->newFromTitle( self::title( $title ) );
		$page->loadPageData();
		return $page;
	}

	public static function rev( int $id ): ?RevisionRecord {
		return self::srv()->getRevisionStore()->getRevisionById( $id );
	}

}
