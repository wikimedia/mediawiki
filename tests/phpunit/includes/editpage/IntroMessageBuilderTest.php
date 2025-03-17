<?php

namespace MediaWiki\Tests\EditPage;

use MediaWiki\Config\HashConfig;
use MediaWiki\EditPage\IntroMessageBuilder;
use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\FileRepo;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\Title\Title;
use MediaWiki\User\TempUser\TempUserCreator;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentityValue;
use MediaWikiIntegrationTestCase;
use MockMessageLocalizer;

/**
 * @covers \MediaWiki\EditPage\IntroMessageBuilder
 * @group Database
 */
class IntroMessageBuilderTest extends MediaWikiIntegrationTestCase {

	/** @var IntroMessageBuilder */
	private $introMessageBuilder;

	protected function setUp(): void {
		$services = $this->getServiceContainer();

		$config = new HashConfig( [
			MainConfigNames::AllowUserCss => true,
			MainConfigNames::AllowUserJs => true,
		] );

		$repoGroup = $this->createMock( RepoGroup::class );
		$repoGroup
			->method( 'findFile' )
			->willReturnCallback( function ( ProperPageIdentity $title ) {
				if ( $title->getDBkey() === 'Shared.png' || $title->getDBkey() === 'Shared-with-desc.png' ) {
					$file = $this->createMock( File::class );
					$file->method( 'isLocal' )->willReturn( false );
					$file->method( 'getDescriptionUrl' )->willReturn( 'https://example.com/' );
					$repo = $this->createMock( FileRepo::class );
					$repo->method( 'getDisplayName' )->willReturn( '' );
					$file->method( 'getRepo' )->willReturn( $repo );
					return $file;
				}
				return null;
			} );

		$tempUserCreator = $this->createMock( TempUserCreator::class );
		$tempUserCreator
			->method( 'isAutoCreateAction' )
			->willReturn( false );

		$userFactory = $this->createMock( UserFactory::class );
		$userFactory
			->method( 'newFromName' )
			->willReturnCallback( static function ( $name ) {
				$user = new User;
				$user->load(); // from 'defaults'
				$user->mName = $name;
				$user->mId = in_array( $name, [ 'Alice', 'Bob' ] ) ? 1 : 0;
				$user->mDataLoaded = true;
				return $user;
			} );

		$this->introMessageBuilder = new IntroMessageBuilder(
			$config,
			$services->getLinkRenderer(),
			$services->getPermissionManager(),
			$services->getUserNameUtils(),
			$tempUserCreator,
			$userFactory,
			$services->getRestrictionStore(),
			$services->getDatabaseBlockStore(),
			$services->getReadOnlyMode(),
			$services->getSpecialPageFactory(),
			$repoGroup,
			$services->getNamespaceInfo(),
			$services->getSkinFactory(),
			$services->getConnectionProvider(),
			$services->getUrlUtils()
		);
	}

	public static function provideCases() {
		// title, oldid, user, editIntro, pages, expectedMessage, expectedWrap
		$errorClass = 'cdx-message--error';
		$warningClass = 'cdx-message--warning';
		yield 'Main namespace has no default message' =>
			[ 'Hello', null, 'Alice', null, [ 'Hello' => '' ],
				[], null ];

		yield 'Logged-out warning' =>
			[ 'Hello', null, null, null, [ 'Hello' => '' ],
				[ "anoneditwarning" ], $warningClass ];

		// Code and message editing
		yield 'User JavaScript requires alert as well as code-specific message' =>
			[ 'User:Bob/common.js', null, 'Alice', null, [ 'User:Bob/common.js' => '' ],
				[ "userjsdangerous", "editpage-code-message" ], $errorClass ];

		yield 'Inform users that their JS is public and suggest guidelines' =>
			[ 'User:Bob/common.js', null, 'Bob', null, [ 'User:Bob/common.js' => '' ],
				[ "userjsispublic", "userjsdangerous", "editpage-code-message", "userjsyoucanpreview" ], $errorClass ];

		yield 'MediaWiki: namespace JSON requires alert' =>
			[ 'MediaWiki:Map.json', null, 'Alice', null, [],
				[ "editinginterface", "newarticletext" ], $errorClass ];

		yield 'MediaWiki: namespace message requires alert' =>
			[ 'MediaWiki:Does-not-exist-asdfasdf', null, 'Alice', null, [],
				[ "editinginterface", "newarticletext" ], $errorClass ];

		yield 'Translateable MediaWiki: namespace message links to Translatewiki' =>
			[ 'MediaWiki:View', null, 'Alice', null, [],
				[ "editinginterface", "translateinterface", "newarticletext" ], $errorClass ];

		// Files
		yield 'Neither shared not local file exists' =>
			[ 'File:Missing.png', null, 'Alice', null, [],
				[ "newarticletext" ], "mw-newarticletext" ];

		yield 'Shared file exists, local description does not exist' =>
			[ 'File:Shared.png', null, 'Alice', null, [],
				[ "sharedupload-desc-create", "newarticletext" ], "mw-sharedupload-desc-create" ];

		yield 'Shared file exists, local description exists' =>
			[ 'File:Shared-with-desc.png', null, 'Alice', null, [ 'File:Shared-with-desc.png' => 'Test' ],
				[ "sharedupload-desc-edit" ], "mw-sharedupload-desc-edit" ];

		// Users
		yield 'User does not exist' =>
			[ 'User:Foo', null, 'Alice', null, [],
				[ "userpage-userdoesnotexist", "newarticletext" ], "mw-newarticletext" ];

		yield 'User exists' =>
			[ 'User:Bob', null, 'Alice', null, [ 'User:Bob' => '' ],
				[], null ];

		yield 'IP user exists, I guess' =>
			[ 'User:1.2.3.4', null, 'Alice', null, [ 'User:1.2.3.4' => '' ],
				[], null ];

		// Editintro
		yield 'Default edit intro for missing page' =>
			[ 'Does-not-exist-asdfasdf', null, 'Alice', null, [],
				[ "newarticletext" ], "mw-newarticletext" ];

		yield 'The "editintro" parameter replaces the default edit intro' =>
			[ 'Does-not-exist-asdfasdf', null, 'Alice', 'Template:Editintro', [ 'Template:Editintro' => '(editintro)' ],
				[ "editintro" ], null ];

		// So many more cases to add...
	}

	/**
	 * @dataProvider provideCases
	 */
	public function testGetIntroMessages( $title, $oldid, $user, $editIntro, $pages, $expectedMessages, $expectedWrap ) {
		foreach ( $pages as $page => $content ) {
			$this->editPage( $page, $content );
		}

		if ( $user ) {
			$userObj = UserIdentityValue::newRegistered( 1, $user );
		} else {
			$userObj = UserIdentityValue::newAnonymous( '1.2.3.4' );
		}

		$parameters = [
			// These messages are always included, skip them to simplify expected values
			[ 'editnotice-notext', 'editpage-head-copy-warn' ],
			new MockMessageLocalizer( 'qqx' ),
			Title::newFromText( $title )->toPageIdentity(),
			null,
			new UltimateAuthority( $userObj ),
			$editIntro,
			null,
			false
		];

		$result = $this->introMessageBuilder->getIntroMessages( IntroMessageBuilder::LESS_FRAMES, ...$parameters );
		$resultLessFrames = implode( '', $result );
		$result = $this->introMessageBuilder->getIntroMessages( IntroMessageBuilder::MORE_FRAMES, ...$parameters );
		$resultMoreFrames = implode( '', $result );

		// Find anything that looks like a message in the output
		preg_match_all( '/[(](.+?)[):]/', $resultLessFrames, $matches, PREG_PATTERN_ORDER );
		$this->assertEquals( $expectedMessages, $matches[1], 'Messages (less frames)' );
		if ( $expectedWrap !== null ) {
			$this->assertStringNotContainsString( $expectedWrap, $resultLessFrames, 'No frames (less frames)' );
		}

		preg_match_all( '/[(](.+?)[):]/', $resultMoreFrames, $matches, PREG_PATTERN_ORDER );
		$this->assertEquals( $expectedMessages, $matches[1], 'Messages (more frames)' );
		if ( $expectedWrap !== null ) {
			$this->assertStringContainsString( $expectedWrap, $resultMoreFrames, 'Frames (more frames)' );
		}

		if ( $expectedWrap == null ) {
			$this->assertEquals( $resultLessFrames, $resultMoreFrames, 'No frames' );
		}
	}

}
