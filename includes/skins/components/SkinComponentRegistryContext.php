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
 */

namespace MediaWiki\Skin;

use Language;
use MediaWiki\Config\Config;
use MediaWiki\Context\IContextSource;
use MediaWiki\HookContainer\ProtectedHookAccessorTrait;
use MediaWiki\Output\OutputPage;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MessageLocalizer;
use Skin;
use WikiPage;

/**
 * @internal for use inside Skin and SkinTemplate classes only
 * @unstable
 */
class SkinComponentRegistryContext implements ComponentRegistryContext {
	use ProtectedHookAccessorTrait;

	/** @var Skin */
	private $skin;

	/** @var MessageLocalizer|null */
	private $localizer = null;

	/**
	 * @param Skin $skin
	 */
	public function __construct( Skin $skin ) {
		$this->skin = $skin;
	}

	/**
	 * @return IContextSource
	 */
	public function getContextSource(): IContextSource {
		return $this->skin->getContext();
	}

	/**
	 * @inheritDoc
	 */
	public function getConfig(): Config {
		return $this->skin->getConfig();
	}

	/**
	 * @inheritDoc
	 */
	public function getTitle(): Title {
		return $this->skin->getTitle() ?? Title::makeTitle( NS_MAIN, 'Foo' );
	}

	/**
	 * @return Title|null the "relevant" title - see Skin::getRelevantTitle
	 */
	public function getRelevantTitle() {
		return $this->skin->getRelevantTitle() ?? $this->getTitle();
	}

	/**
	 * @return OutputPage
	 */
	public function getOutput(): OutputPage {
		return $this->skin->getOutput();
	}

	/**
	 * @return User
	 */
	public function getUser() {
		return $this->skin->getUser();
	}

	/**
	 * @return Language $language
	 */
	public function getLanguage() {
		return $this->skin->getLanguage();
	}

	/**
	 * @return MessageLocalizer
	 */
	public function getMessageLocalizer(): MessageLocalizer {
		if ( $this->localizer === null ) {
			// Cannot call getContext in constructor,
			// because Skin::class does not have a context yet.
			// But it is valid to call it now
			$this->localizer = $this->skin->getContext();
		}

		return $this->localizer;
	}

	/**
	 * @return WikiPage
	 */
	public function getWikiPage() {
		return $this->skin->getWikiPage();
	}

	/**
	 * Run a hook
	 *
	 * @param string $methodName
	 * @param array $args
	 */
	public function runHook( string $methodName, array $args ) {
		$hookRunner = $this->getHookRunner();
		$hook = [ $hookRunner, $methodName ];
		switch ( $methodName ) {
			case 'onSkinAddFooterLinks':
				call_user_func_array(
					$hook,
					array_merge( [ $this->skin ], $args )
				);
				break;
			default:
				call_user_func_array( $hook, $args );
				break;
		}
	}

	/**
	 * Temporarily allows access to Skin method.
	 * It exists to support skins overriding the method in MediaWiki 1.40
	 * (overriding the method is now deprecated)
	 * It can be removed in 1.41
	 *
	 * @unstable
	 * @internal
	 * @return array
	 */
	public function getFooterIcons() {
		return $this->skin->getFooterIcons();
	}

	/**
	 * Renders a $wgFooterIcons icon according to the method's arguments
	 * It exists to support skins overriding the method in MediaWiki 1.40
	 * (overriding the method is now deprecated)
	 * It can be removed in 1.41
	 *
	 * @unstable
	 * @param array $icon The icon to build the html for, see $wgFooterIcons
	 *   for the format of this array.
	 * @param bool|string $withImage Whether to use the icon's image or output
	 *   a text-only footericon.
	 * @internal
	 * @return string HTML
	 */
	public function makeFooterIcon( $icon, $withImage = 'withImage' ) {
		return $this->skin->makeFooterIcon( $icon, $withImage );
	}
}
