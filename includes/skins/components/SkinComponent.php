<?php
/**
 * @license GPL-2.0-or-later
 */

namespace MediaWiki\Skin;

/**
 * @internal for use inside Skin and SkinTemplate classes only
 * @unstable
 */
interface SkinComponent {
	/**
	 * This returns all the data that is needed to the component.
	 * Returned array must be serialized. This will be passed directly
	 * to a template (usually Mustache) for rendering.
	 *
	 * @return array Data related to component required to render.
	 */
	public function getTemplateData(): array;
}
