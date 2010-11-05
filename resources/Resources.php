<?php

return array(

	/* Special resources who have their own classes */
	
	'site' => new ResourceLoaderSiteModule,
	'startup' => new ResourceLoaderStartUpModule,
	'user' => new ResourceLoaderUserModule,
	'user.options' => new ResourceLoaderUserOptionsModule,
	
	/* Skins */
	
	'skins.vector' => new ResourceLoaderFileModule(
		array( 'styles' => array( 'skins/vector/screen.css' => array( 'media' => 'screen' ) ) )
	),
	'skins.monobook' => new ResourceLoaderFileModule(
		array( 'styles' => array(
				'skins/monobook/main.css' => array( 'media' => 'screen' ),
				// Honor $wgHandheldStyle. This is kind of evil
				//$GLOBALS['wgHandheldStyle'] => array( 'media' => 'handheld' )
			)
		)
	),
	
	/* jQuery */
	
	'jquery' => new ResourceLoaderFileModule( 
		array( 'scripts' => 'resources/jquery/jquery.js', 'debugRaw' => false ) 
	),
	
	/* jQuery Plugins */
	
	'jquery.async' => new ResourceLoaderFileModule(
		array( 'scripts' => 'resources/jquery/jquery.async.js' )
	),
	'jquery.autoEllipsis' => new ResourceLoaderFileModule(
		array( 'scripts' => 'resources/jquery/jquery.autoEllipsis.js' )
	),
	'jquery.checkboxShiftClick' => new ResourceLoaderFileModule(
		array( 'scripts' => 'resources/jquery/jquery.checkboxShiftClick.js' )
	),
	'jquery.client' => new ResourceLoaderFileModule(
		array( 'scripts' => 'resources/jquery/jquery.client.js' )
	),
	'jquery.collapsibleTabs' => new ResourceLoaderFileModule(
		array( 'scripts' => 'resources/jquery/jquery.collapsibleTabs.js' )
	),
	'jquery.color' => new ResourceLoaderFileModule(
		array( 'scripts' => 'resources/jquery/jquery.color.js' )
	),
	'jquery.cookie' => new ResourceLoaderFileModule(
		array( 'scripts' => 'resources/jquery/jquery.cookie.js' )
	),
	'jquery.delayedBind' => new ResourceLoaderFileModule(
		array( 'scripts' => 'resources/jquery/jquery.delayedBind.js' )
	),
	'jquery.expandableField' => new ResourceLoaderFileModule(
		array( 'scripts' => 'resources/jquery/jquery.expandableField.js' )
	),
	'jquery.highlightText' => new ResourceLoaderFileModule(
		array( 'scripts' => 'resources/jquery/jquery.highlightText.js' )
	),
	'jquery.placeholder' => new ResourceLoaderFileModule(
		array( 'scripts' => 'resources/jquery/jquery.placeholder.js' )
	),
	'jquery.suggestions' => new ResourceLoaderFileModule(
		array(
			'scripts' => 'resources/jquery/jquery.suggestions.js',
			'styles' => 'resources/jquery/jquery.suggestions.css',
		)
	),
	'jquery.tabIndex' => new ResourceLoaderFileModule(
		array( 'scripts' => 'resources/jquery/jquery.tabIndex.js' )
	),
	'jquery.textSelection' => new ResourceLoaderFileModule(
		array( 'scripts' => 'resources/jquery/jquery.textSelection.js' )
	),
	
	/* jQuery UI */
	
	// Core
	'jquery.ui.core' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/jquery.ui/jquery.ui.core.js',
		'skinStyles' => array(
			'default' => array(
				'resources/jquery.ui/themes/default/jquery.ui.core.css',
				'resources/jquery.ui/themes/default/jquery.ui.theme.css',
			),
			'vector' => array(
				'resources/jquery.ui/themes/vector/jquery.ui.core.css',
				'resources/jquery.ui/themes/vector/jquery.ui.theme.css',
			),
		),
		'dependencies' => 'jquery',
	) ),
	'jquery.ui.widget' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/jquery.ui/jquery.ui.widget.js',
	) ),
	'jquery.ui.mouse' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/jquery.ui/jquery.ui.mouse.js',
		'dependencies' => 'jquery.ui.widget',
	) ),
	'jquery.ui.position' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/jquery.ui/jquery.ui.position.js',
	) ),
	// Interactions
	'jquery.ui.draggable' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/jquery.ui/jquery.ui.draggable.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.mouse', 'jquery.ui.widget' ),
	) ),
	'jquery.ui.droppable' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/jquery.ui/jquery.ui.droppable.js',
		'dependencies' => array( 
			'jquery.ui.core', 'jquery.ui.mouse', 'jquery.ui.widget', 'jquery.ui.draggable' 
		),
	) ),
	'jquery.ui.resizable' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/jquery.ui/jquery.ui.resizable.js',
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.resizable.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.resizable.css',
		),
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget', 'jquery.ui.mouse' ),
	) ),
	'jquery.ui.selectable' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/jquery.ui/jquery.ui.selectable.js',
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.selectable.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.selectable.css',
		),
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget', 'jquery.ui.mouse' ),
	) ),
	'jquery.ui.sortable' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/jquery.ui/jquery.ui.sortable.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget', 'jquery.ui.mouse' ),
	) ),
	// Widgets
	'jquery.ui.accordion' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/jquery.ui/jquery.ui.accordion.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget' ),
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.accordion.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.accordion.css',
		),
	) ),
	'jquery.ui.autocomplete' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/jquery.ui/jquery.ui.autocomplete.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget', 'jquery.ui.position' ),
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.autocomplete.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.autocomplete.css',
		),
	) ),
	'jquery.ui.button' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/jquery.ui/jquery.ui.button.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget' ),
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.button.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.button.css',
		),
	) ),
	'jquery.ui.datepicker' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/jquery.ui/jquery.ui.datepicker.js',
		'dependencies' => 'jquery.ui.core',
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.datepicker.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.datepicker.css',
		),
		'languageScripts' => array(
			'af' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-af.js',
			'ar' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-ar.js',
			'az' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-az.js',
			'bg' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-bg.js',
			'bs' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-bs.js',
			'ca' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-ca.js',
			'cs' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-cs.js',
			'da' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-da.js',
			'de' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-de.js',
			'el' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-el.js',
			'en-gb' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-en-GB.js',
			'eo' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-eo.js',
			'es' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-es.js',
			'et' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-et.js',
			'eu' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-eu.js',
			'fa' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-fa.js',
			'fi' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-fi.js',
			'fo' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-fo.js',
			'fr-ch' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-fr-CH.js',
			'fr' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-fr.js',
			'he' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-he.js',
			'hr' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-hr.js',
			'hu' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-hu.js',
			'hy' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-hy.js',
			'id' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-id.js',
			'is' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-is.js',
			'it' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-it.js',
			'ja' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-ja.js',
			'ko' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-ko.js',
			'lt' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-lt.js',
			'lv' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-lv.js',
			'ms' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-ms.js',
			'nl' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-nl.js',
			'no' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-no.js',
			'pl' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-pl.js',
			'pt-br' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-pt-BR.js',
			'ro' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-ro.js',
			'ru' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-ru.js',
			'sk' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-sk.js',
			'sl' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-sl.js',
			'sq' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-sq.js',
			'sr-sr' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-sr-SR.js',
			'sr' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-sr.js',
			'sv' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-sv.js',
			'ta' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-ta.js',
			'th' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-th.js',
			'tr' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-tr.js',
			'uk' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-uk.js',
			'vi' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-vi.js',
			'zh-cn' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-zh-CN.js',
			'zh-hk' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-zh-HK.js',
			'zh-tw' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-zh-TW.js'
		),
	) ),
	'jquery.ui.dialog' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/jquery.ui/jquery.ui.dialog.js',
		'dependencies' => array(
			'jquery.ui.core',
			'jquery.ui.widget',
			'jquery.ui.button',
			'jquery.ui.draggable',
			'jquery.ui.mouse',
			'jquery.ui.position',
			'jquery.ui.resizable',
		),
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.dialog.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.dialog.css',
		),
	) ),
	'jquery.ui.progressbar' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/jquery.ui/jquery.ui.progressbar.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget' ),
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.progressbar.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.progressbar.css',
		),
	) ),
	'jquery.ui.slider' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/jquery.ui/jquery.ui.slider.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget', 'jquery.ui.mouse' ),
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.slider.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.slider.css',
		),
	) ),
	'jquery.ui.tabs' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/jquery.ui/jquery.ui.tabs.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget' ),
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.tabs.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.tabs.css',
		),
	) ),
	// Effects
	'jquery.effects.core' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/jquery.effects/jquery.effects.core.js',
		'dependencies' => 'jquery',
	) ),
	'jquery.effects.blind' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/jquery.effects/jquery.effects.blind.js',
		'dependencies' => 'jquery.effects.core',
	) ),
	'jquery.effects.bounce' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/jquery.effects/jquery.effects.bounce.js',
		'dependencies' => 'jquery.effects.core',
	) ),
	'jquery.effects.clip' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/jquery.effects/jquery.effects.clip.js',
		'dependencies' => 'jquery.effects.core',
	) ),
	'jquery.effects.drop' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/jquery.effects/jquery.effects.drop.js',
		'dependencies' => 'jquery.effects.core',
	) ),
	'jquery.effects.explode' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/jquery.effects/jquery.effects.explode.js',
		'dependencies' => 'jquery.effects.core',
	) ),
	'jquery.effects.fold' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/jquery.effects/jquery.effects.fold.js',
		'dependencies' => 'jquery.effects.core',
	) ),
	'jquery.effects.highlight' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/jquery.effects/jquery.effects.highlight.js',
		'dependencies' => 'jquery.effects.core',
	) ),
	'jquery.effects.pulsate' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/jquery.effects/jquery.effects.pulsate.js',
		'dependencies' => 'jquery.effects.core',
	) ),
	'jquery.effects.scale' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/jquery.effects/jquery.effects.scale.js',
		'dependencies' => 'jquery.effects.core',
	) ),
	'jquery.effects.shake' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/jquery.effects/jquery.effects.shake.js',
		'dependencies' => 'jquery.effects.core',
	) ),
	'jquery.effects.slide' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/jquery.effects/jquery.effects.slide.js',
		'dependencies' => 'jquery.effects.core',
	) ),
	'jquery.effects.transfer' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/jquery.effects/jquery.effects.transfer.js',
		'dependencies' => 'jquery.effects.core',
	) ),
	
	/* MediaWiki */
	
	'mediawiki' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/mediawiki/mediawiki.js',
		'debugScripts' => 'resources/mediawiki/mediawiki.log.js',
		'debugRaw' => false
	) ),
	'mediawiki.advanced.rightclickedit' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/mediawiki.advanced/mediawiki.advanced.rightclickedit.js',
	) ),
	'mediawiki.specials.preferences' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/mediawiki.specials/mediawiki.specials.preferences.js',
		'styles' => 'resources/mediawiki.specials/mediawiki.specials.preferences.css',
	) ),
	'mediawiki.specials.search' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/mediawiki.specials/mediawiki.specials.search.js',
	) ),
	'mediawiki.views.history' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/mediawiki.views/mediawiki.views.history.js',
		'dependencies' => 'mediawiki.legacy.history',
	) ),
	'mediawiki.language' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/mediawiki.language/mediawiki.language.js',
		'languageScripts' => array(
			'am' => 'resources/mediawiki.language/languages/am.js',
			'ar' => 'resources/mediawiki.language/languages/ar.js',
			'bat-smg' => 'resources/mediawiki.language/languages/bat-smg.js',
			'be' => 'resources/mediawiki.language/languages/be.js',
			'be-tarask' => 'resources/mediawiki.language/languages/be-tarask.js',
			'bh' => 'resources/mediawiki.language/languages/bh.js',
			'bs' => 'resources/mediawiki.language/languages/bs.js',
			'cs' => 'resources/mediawiki.language/languages/cs.js',
			'cu' => 'resources/mediawiki.language/languages/cu.js',
			'cy' => 'resources/mediawiki.language/languages/cy.js',
			'dsb' => 'resources/mediawiki.language/languages/dsb.js',
			'fr' => 'resources/mediawiki.language/languages/fr.js',
			'ga' => 'resources/mediawiki.language/languages/ga.js',
			'gd' => 'resources/mediawiki.language/languages/gd.js',
			'gv' => 'resources/mediawiki.language/languages/gv.js',
			'he' => 'resources/mediawiki.language/languages/he.js',
			'hi' => 'resources/mediawiki.language/languages/hi.js',
			'hr' => 'resources/mediawiki.language/languages/hr.js',
			'hsb' => 'resources/mediawiki.language/languages/hsb.js',
			'hy' => 'resources/mediawiki.language/languages/hy.js',
			'ksh' => 'resources/mediawiki.language/languages/ksh.js',
			'ln' => 'resources/mediawiki.language/languages/ln.js',
			'lt' => 'resources/mediawiki.language/languages/lt.js',
			'lv' => 'resources/mediawiki.language/languages/lv.js',
			'mg' => 'resources/mediawiki.language/languages/mg.js',
			'mk' => 'resources/mediawiki.language/languages/mk.js',
			'mo' => 'resources/mediawiki.language/languages/mo.js',
			'mt' => 'resources/mediawiki.language/languages/mt.js',
			'nso' => 'resources/mediawiki.language/languages/nso.js',
			'pl' => 'resources/mediawiki.language/languages/pl.js',
			'pt-br' => 'resources/mediawiki.language/languages/pt-br.js',
			'ro' => 'resources/mediawiki.language/languages/ro.js',
			'ru' => 'resources/mediawiki.language/languages/ru.js',
			'se' => 'resources/mediawiki.language/languages/se.js',
			'sh' => 'resources/mediawiki.language/languages/sh.js',
			'sk' => 'resources/mediawiki.language/languages/sk.js',
			'sl' => 'resources/mediawiki.language/languages/sl.js',
			'sma' => 'resources/mediawiki.language/languages/sma.js',
			'sr-ec' => 'resources/mediawiki.language/languages/sr-ec.js',
			'sr-el' => 'resources/mediawiki.language/languages/sr-el.js',
			'sr' => 'resources/mediawiki.language/languages/sr.js',
			'ti' => 'resources/mediawiki.language/languages/ti.js',
			'tl' => 'resources/mediawiki.language/languages/tl.js',
			'uk' => 'resources/mediawiki.language/languages/uk.js',
			'wa' => 'resources/mediawiki.language/languages/wa.js',
		),
	) ),
	'mediawiki.util' => new ResourceLoaderFileModule( array(
		'scripts' => 'resources/mediawiki.util/mediawiki.util.js',
		'dependencies' => array( 'jquery.checkboxShiftClick', 'jquery.client' ),
		'debugScripts' => 'resources/mediawiki.util/mediawiki.util.test.js',
	) ),
	
	/* mediawiki Legacy */
	
	'mediawiki.legacy.ajax' => new ResourceLoaderFileModule( array(
		'scripts' => 'skins/common/ajax.js',
		'messages' => array( 
			'watch', 'unwatch', 'watching', 'unwatching', 'tooltip-ca-watch', 
			'tooltip-ca-unwatch' 
		),
		'dependencies' => 'mediawiki.legacy.wikibits',
	) ),
	'mediawiki.legacy.ajaxwatch' => new ResourceLoaderFileModule( array(
		'scripts' => 'skins/common/ajaxwatch.js',
		'dependencies' => 'mediawiki.legacy.wikibits',
	) ),
	'mediawiki.legacy.block' => new ResourceLoaderFileModule( array(
		'scripts' => 'skins/common/block.js',
		'dependencies' => 'mediawiki.legacy.wikibits',
	) ),
	'mediawiki.legacy.changepassword' => new ResourceLoaderFileModule( array(
		'scripts' => 'skins/common/changepassword.js',
		'dependencies' => 'mediawiki.legacy.wikibits',
	) ),
	'mediawiki.legacy.commonPrint' => new ResourceLoaderFileModule( array(
		'styles' => array( 'skins/common/commonPrint.css' => array( 'media' => 'print' ) ),
	) ),
	'mediawiki.legacy.config' => new ResourceLoaderFileModule( array(
		'scripts' => 'skins/common/config.js',
		'styles' => array( 'skins/common/config.css', 'skins/common/config-cc.css' ),
		'dependencies' => 'mediawiki.legacy.wikibits',
	) ),
	'mediawiki.legacy.diff' => new ResourceLoaderFileModule( array(
		'scripts' => 'skins/common/diff.js',
		'styles' => 'skins/common/diff.css',
		'dependencies' => 'mediawiki.legacy.wikibits',
	) ),
	'mediawiki.legacy.edit' => new ResourceLoaderFileModule( array(
		'scripts' => 'skins/common/edit.js',
		'dependencies' => 'mediawiki.legacy.wikibits',
	) ),
	'mediawiki.legacy.enhancedchanges' => new ResourceLoaderFileModule( array(
		'scripts' => 'skins/common/enhancedchanges.js',
		'dependencies' => 'mediawiki.legacy.wikibits',
	) ),
	'mediawiki.legacy.history' => new ResourceLoaderFileModule( array(
		'scripts' => 'skins/common/history.js',
		'dependencies' => 'mediawiki.legacy.wikibits',
	) ),
	'mediawiki.legacy.htmlform' => new ResourceLoaderFileModule( array(
		'scripts' => 'skins/common/htmlform.js',
		'dependencies' => 'mediawiki.legacy.wikibits',
	) ),
	'mediawiki.legacy.IEFixes' => new ResourceLoaderFileModule( array(
		'scripts' => 'skins/common/IEFixes.js',
		'dependencies' => 'mediawiki.legacy.wikibits',
	) ),
	'mediawiki.legacy.metadata' => new ResourceLoaderFileModule( array(
		'scripts' => 'skins/common/metadata.js',
		'dependencies' => 'mediawiki.legacy.wikibits',
		'messages' => array( 'metadata-expand', 'metadata-collapse' ),
	) ),
	'mediawiki.legacy.mwsuggest' => new ResourceLoaderFileModule( array(
		'scripts' => 'skins/common/mwsuggest.js',
		'dependencies' => 'mediawiki.legacy.wikibits',
		'messages' => array( 'search-mwsuggest-enabled', 'search-mwsuggest-disabled' ),
	) ),
	'mediawiki.legacy.password' => new ResourceLoaderFileModule( array(
		'scripts' => 'skins/common/password.js',
		'styles' => 'skins/common/password.css',
		'dependencies' => 'mediawiki.legacy.wikibits',
	) ),
	'mediawiki.legacy.prefs' => new ResourceLoaderFileModule( array(
		'scripts' => 'skins/common/prefs.js',
		'dependencies' => array( 'mediawiki.legacy.wikibits', 'mediawiki.legacy.htmlform' ),
	) ),
	'mediawiki.legacy.preview' => new ResourceLoaderFileModule( array(
		'scripts' => 'skins/common/preview.js',
		'dependencies' => 'mediawiki.legacy.wikibits',
	) ),
	'mediawiki.legacy.protect' => new ResourceLoaderFileModule( array(
		'scripts' => 'skins/common/protect.js',
		'dependencies' => 'mediawiki.legacy.wikibits',
	) ),
	'mediawiki.legacy.search' => new ResourceLoaderFileModule( array(
		'scripts' => 'skins/common/search.js',
		'styles' => 'skins/common/search.css',
		'dependencies' => 'mediawiki.legacy.wikibits',
	) ),
	'mediawiki.legacy.shared' => new ResourceLoaderFileModule( array(
		'styles' => array( 'skins/common/shared.css' => array( 'media' => 'screen' ) ),
	) ),
	'mediawiki.legacy.oldshared' => new ResourceLoaderFileModule( array(
		'styles' => array( 'skins/common/oldshared.css' => array( 'media' => 'screen' ) ),
	) ),
	'mediawiki.legacy.upload' => new ResourceLoaderFileModule( array(
		'scripts' => 'skins/common/upload.js',
		'dependencies' => 'mediawiki.legacy.wikibits',
	) ),
	'mediawiki.legacy.wikibits' => new ResourceLoaderFileModule( array(
		'scripts' => 'skins/common/wikibits.js',
		'dependencies' => 'mediawiki.language',
		'messages' => array( 'showtoc', 'hidetoc' ),
	) ),
	'mediawiki.legacy.wikiprintable' => new ResourceLoaderFileModule( array(
		'styles' => array( 'skins/common/wikiprintable.css' => array( 'media' => 'print' ) ),
	) ),
);
