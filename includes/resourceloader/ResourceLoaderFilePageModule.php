<?php
/* 
 * ResourceLoader definition for MediaWiki:Filepage.css
 */
class ResourceLoaderFilePageModule extends ResourceLoaderWikiModule {
	protected function getPages( ResourceLoaderContext $context ) {
		return array(
			'MediaWiki:Filepage.css' => array( 'type' => 'style' ),
		);
	}
}
