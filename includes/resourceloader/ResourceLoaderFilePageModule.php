<?php
/**
 * ResourceLoader definition for MediaWiki:Filepage.css
 */
class ResourceLoaderFilePageModule extends ResourceLoaderWikiModule {

	/**
	 * @param $context ResourceLoaderContext
	 * @return array
	 */
	protected function getPages( ResourceLoaderContext $context ) {
		return array(
			'MediaWiki:Filepage.css' => array( 'type' => 'style' ),
		);
	}
}
