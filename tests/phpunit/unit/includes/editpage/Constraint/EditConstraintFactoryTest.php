<?php
/**
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

use MediaWiki\Config\ServiceOptions;
use MediaWiki\EditPage\Constraint\EditConstraintFactory;
use MediaWiki\EditPage\Constraint\EditFilterMergedContentHookConstraint;
use MediaWiki\EditPage\Constraint\PageSizeConstraint;
use MediaWiki\EditPage\Constraint\ReadOnlyConstraint;
use MediaWiki\EditPage\Constraint\SimpleAntiSpamConstraint;
use MediaWiki\EditPage\Constraint\SpamRegexConstraint;
use MediaWiki\EditPage\Constraint\UserBlockConstraint;
use MediaWiki\EditPage\SpamChecker;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Logger\Spi;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\PermissionManager;
use Psr\Log\NullLogger;

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
			$this->createMock( PermissionManager::class ),
			$this->createMock( HookContainer::class ),
			$this->createMock( ReadOnlyMode::class ),
			$this->createMock( SpamChecker::class )
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
		$this->assertInstanceOf(
			UserBlockConstraint::class,
			$factory->newUserBlockConstraint( $title, $user )
		);
	}
}
