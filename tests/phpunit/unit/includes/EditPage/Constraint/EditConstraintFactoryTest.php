<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\Content;
use MediaWiki\Context\IContextSource;
use MediaWiki\EditPage\Constraint\EditConstraintFactory;
use MediaWiki\EditPage\Constraint\EditFilterMergedContentHookConstraint;
use MediaWiki\EditPage\Constraint\PageSizeConstraint;
use MediaWiki\EditPage\Constraint\ReadOnlyConstraint;
use MediaWiki\EditPage\Constraint\SimpleAntiSpamConstraint;
use MediaWiki\EditPage\Constraint\SpamRegexConstraint;
use MediaWiki\EditPage\SpamChecker;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Language\Language;
use MediaWiki\Logger\Spi;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\RateLimiter;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use Psr\Log\NullLogger;
use Wikimedia\Rdbms\ReadOnlyMode;

/**
 * Tests the EditConstraintFactory
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\EditConstraintFactory
 */
class EditConstraintFactoryTest extends MediaWikiUnitTestCase {

	public function testFactoryMethods() {
		$options = new ServiceOptions(
			EditConstraintFactory::CONSTRUCTOR_OPTIONS,
			[ MainConfigNames::MaxArticleSize => 10 ]
		);
		$loggerFactory = $this->createMock( Spi::class );
		$loggerFactory->method( 'getLogger' )
			->willReturn( new NullLogger() );

		$factory = new EditConstraintFactory(
			$options,
			$loggerFactory,
			$this->createMock( HookContainer::class ),
			$this->createMock( ReadOnlyMode::class ),
			$this->createMock( SpamChecker::class ),
			$this->createMock( RateLimiter::class ),
		);

		$user = $this->createMock( User::class );
		$title = $this->createMock( Title::class );
		$context = $this->createMock( IContextSource::class );
		$newContent = $this->createMock( Content::class );

		// Actual tests
		$this->assertInstanceOf(
			EditFilterMergedContentHookConstraint::class,
			$factory->newEditFilterMergedContentHookConstraint(
				$newContent,
				$context,
				'EditSummary',
				true, // $minorEdit
				$this->createMock( Language::class ),
				$this->createMock( User::class )
			)
		);
		$this->assertInstanceOf(
			PageSizeConstraint::class,
			$factory->newPageSizeConstraint( 123, PageSizeConstraint::BEFORE_MERGE )
		);
		$this->assertInstanceOf(
			ReadOnlyConstraint::class,
			$factory->newReadOnlyConstraint()
		);
		$this->assertInstanceOf(
			SimpleAntiSpamConstraint::class,
			$factory->newSimpleAntiSpamConstraint( '', $user, $title )
		);
		$this->assertInstanceOf(
			SpamRegexConstraint::class,
			$factory->newSpamRegexConstraint(
				'EditSummary',
				'SectionHeading',
				'Text',
				'RequestIP',
				$title
			)
		);
	}
}
