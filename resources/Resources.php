<?php

return array(

	/* Special modules who have their own classes */

	// Scripts managed by the local wiki (stored in the MediaWiki namespace)
	'site' => array( 'class' => 'ResourceLoaderSiteModule' ),
	'noscript' => array( 'class' => 'ResourceLoaderNoscriptModule' ),
	'startup' => array( 'class' => 'ResourceLoaderStartUpModule' ),
	'filepage' => array( 'class' => 'ResourceLoaderFilePageModule' ),
	'user.groups' => array( 'class' => 'ResourceLoaderUserGroupsModule' ),

	// Scripts managed by the current user (stored in their user space)
	'user' => array( 'class' => 'ResourceLoaderUserModule' ),

	// Scripts generated based on the current user's preferences
	'user.cssprefs' => array( 'class' => 'ResourceLoaderUserCSSPrefsModule' ),

	// Populate mediawiki.user placeholders with information about the current user
	'user.options' => array( 'class' => 'ResourceLoaderUserOptionsModule' ),
	'user.tokens' => array( 'class' => 'ResourceLoaderUserTokensModule' ),

	// Scripts for the dynamic language specific data, like grammar forms.
	'mediawiki.language.data' => array( 'class' => 'ResourceLoaderLanguageDataModule' ),

	/* Skins */

	'skins.chick' => array(
		'styles' => array( 'chick/main.css' => array( 'media' => 'screen, handheld' ) ),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
	),
	'skins.cologneblue' => array(
		'styles' => array( 'cologneblue/screen.css' => array( 'media' => 'screen' ) ),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
	),
	'skins.modern' => array(
		'styles' => array(
			'modern/main.css' => array( 'media' => 'screen' ),
			'modern/print.css' => array( 'media' => 'print' ),
		),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
	),
	'skins.monobook' => array(
		'styles' => array(
			'common/commonElements.css' => array( 'media' => 'screen' ),
			'common/commonContent.css' => array( 'media' => 'screen' ),
			'common/commonInterface.css' => array( 'media' => 'screen' ),
			'monobook/main.css' => array( 'media' => 'screen' ),
		),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
	),
	'skins.nostalgia' => array(
		'styles' => array( 'nostalgia/screen.css' => array( 'media' => 'screen' ) ),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
	),
	'skins.simple' => array(
		'styles' => array( 'simple/main.css' => array( 'media' => 'screen' ) ),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
	),
	'skins.standard' => array(
		'styles' => array( 'standard/main.css' => array( 'media' => 'screen' ) ),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
	),
	'skins.vector' => array(
		// Keep in sync with WebInstallerOutput::getCSS()
		'styles' => array(
			'common/commonElements.css' => array( 'media' => 'screen' ),
			'common/commonContent.css' => array( 'media' => 'screen' ),
			'common/commonInterface.css' => array( 'media' => 'screen' ),
			'vector/screen.css' => array( 'media' => 'screen' ),
			'vector/screen-hd.css' => array( 'media' => 'screen and (min-width: 982px)' ),
		),
		'scripts' => 'vector/vector.js',
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
	),

	/* jQuery */

	'jquery' => array(
		'scripts' => 'resources/jquery/jquery.js',
		'debugRaw' => false,
	),

	/* jQuery Plugins */

	'jquery.appear' => array(
		'scripts' => 'resources/jquery/jquery.appear.js',
	),
	'jquery.arrowSteps' => array(
		'scripts' => 'resources/jquery/jquery.arrowSteps.js',
		'styles' => 'resources/jquery/jquery.arrowSteps.css',
	),
	'jquery.async' => array(
		'scripts' => 'resources/jquery/jquery.async.js',
	),
	'jquery.autoEllipsis' => array(
		'scripts' => 'resources/jquery/jquery.autoEllipsis.js',
		'dependencies' => 'jquery.highlightText',
	),
	'jquery.badge' => array(
		'scripts' => 'resources/jquery/jquery.badge.js',
		'styles' => 'resources/jquery/jquery.badge.css',
	),
	'jquery.byteLength' => array(
		'scripts' => 'resources/jquery/jquery.byteLength.js',
	),
	'jquery.byteLimit' => array(
		'scripts' => 'resources/jquery/jquery.byteLimit.js',
		'dependencies' => 'jquery.byteLength',
	),
	'jquery.checkboxShiftClick' => array(
		'scripts' => 'resources/jquery/jquery.checkboxShiftClick.js',
	),
	'jquery.client' => array(
		'scripts' => 'resources/jquery/jquery.client.js',
	),
	'jquery.collapsibleTabs' => array(
		'scripts' => 'resources/jquery/jquery.collapsibleTabs.js',
	),
	'jquery.color' => array(
		'scripts' => 'resources/jquery/jquery.color.js',
		'dependencies' => 'jquery.colorUtil',
	),
	'jquery.colorUtil' => array(
		'scripts' => 'resources/jquery/jquery.colorUtil.js',
	),
	'jquery.cookie' => array(
		'scripts' => 'resources/jquery/jquery.cookie.js',
	),
	'jquery.delayedBind' => array(
		'scripts' => 'resources/jquery/jquery.delayedBind.js',
	),
	'jquery.expandableField' => array(
		'scripts' => 'resources/jquery/jquery.expandableField.js',
		'dependencies' => 'jquery.delayedBind',
	),
	'jquery.farbtastic' => array(
		'scripts' => 'resources/jquery/jquery.farbtastic.js',
		'styles' => 'resources/jquery/jquery.farbtastic.css',
		'dependencies' => 'jquery.colorUtil',
	),
	'jquery.footHovzer' => array(
		'scripts' => 'resources/jquery/jquery.footHovzer.js',
		'styles' => 'resources/jquery/jquery.footHovzer.css',
	),
	'jquery.form' => array(
		'scripts' => 'resources/jquery/jquery.form.js',
	),
	'jquery.getAttrs' => array(
		'scripts' => 'resources/jquery/jquery.getAttrs.js',
	),
	'jquery.highlightText' => array(
		'scripts' => 'resources/jquery/jquery.highlightText.js',
		'dependencies' => 'jquery.mwExtension',
	),
	'jquery.hoverIntent' => array(
		'scripts' => 'resources/jquery/jquery.hoverIntent.js',
	),
	'jquery.json' => array(
		'scripts' => 'resources/jquery/jquery.json.js',
	),
	'jquery.localize' => array(
		'scripts' => 'resources/jquery/jquery.localize.js',
	),
	'jquery.makeCollapsible' => array(
		'scripts' => 'resources/jquery/jquery.makeCollapsible.js',
		'styles' => 'resources/jquery/jquery.makeCollapsible.css',
		'messages' => array( 'collapsible-expand', 'collapsible-collapse' ),
	),
	'jquery.mockjax' => array(
		'scripts' => 'resources/jquery/jquery.mockjax.js',
	),
	'jquery.mw-jump' => array(
		'scripts' => 'resources/jquery/jquery.mw-jump.js',
	),
	'jquery.mwExtension' => array(
		'scripts' => 'resources/jquery/jquery.mwExtension.js',
	),
	'jquery.placeholder' => array(
		'scripts' => 'resources/jquery/jquery.placeholder.js',
	),
	'jquery.qunit' => array(
		'scripts' => 'resources/jquery/jquery.qunit.js',
		'styles' => 'resources/jquery/jquery.qunit.css',
		'position' => 'top',
	),
	'jquery.qunit.completenessTest' => array(
		'scripts' => 'resources/jquery/jquery.qunit.completenessTest.js',
		'dependencies' => 'jquery.qunit',
	),
	'jquery.spinner' => array(
		'scripts' => 'resources/jquery/jquery.spinner.js',
		'styles' => 'resources/jquery/jquery.spinner.css',
	),
	'jquery.jStorage' => array(
		'scripts' => 'resources/jquery/jquery.jStorage.js',
		'dependencies' => 'jquery.json',
	),
	'jquery.suggestions' => array(
		'scripts' => 'resources/jquery/jquery.suggestions.js',
		'styles' => 'resources/jquery/jquery.suggestions.css',
		'dependencies' => 'jquery.autoEllipsis',
	),
	'jquery.tabIndex' => array(
		'scripts' => 'resources/jquery/jquery.tabIndex.js',
	),
	'jquery.tablesorter' => array(
		'scripts' => 'resources/jquery/jquery.tablesorter.js',
		'styles' => 'resources/jquery/jquery.tablesorter.css',
		'messages' => array( 'sort-descending', 'sort-ascending' ),
		'dependencies' => 'jquery.mwExtension',
	),
	'jquery.textSelection' => array(
		'scripts' => 'resources/jquery/jquery.textSelection.js',
		'dependencies' => 'jquery.client',
	),
	'jquery.validate' => array(
		'scripts' => 'resources/jquery/jquery.validate.js',
	),
	'jquery.xmldom' => array(
		'scripts' => 'resources/jquery/jquery.xmldom.js',
	),

	/* jQuery Tipsy */

	'jquery.tipsy' => array(
		'scripts' => 'resources/jquery.tipsy/jquery.tipsy.js',
		'styles' => 'resources/jquery.tipsy/jquery.tipsy.css',
	),

	/* jQuery UI */

	// Core
	'jquery.ui.core' => array(
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
		'group' => 'jquery.ui',
	),
	'jquery.ui.widget' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.widget.js',
		'group' => 'jquery.ui',
	),
	'jquery.ui.mouse' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.mouse.js',
		'dependencies' => 'jquery.ui.widget',
		'group' => 'jquery.ui',
	),
	'jquery.ui.position' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.position.js',
		'group' => 'jquery.ui',
	),
	// Interactions
	'jquery.ui.draggable' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.draggable.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.mouse', 'jquery.ui.widget' ),
		'group' => 'jquery.ui',
	),
	'jquery.ui.droppable' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.droppable.js',
		'dependencies' => array(
			'jquery.ui.core', 'jquery.ui.mouse', 'jquery.ui.widget', 'jquery.ui.draggable',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.resizable' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.resizable.js',
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.resizable.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.resizable.css',
		),
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget', 'jquery.ui.mouse' ),
		'group' => 'jquery.ui',
	),
	'jquery.ui.selectable' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.selectable.js',
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.selectable.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.selectable.css',
		),
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget', 'jquery.ui.mouse' ),
		'group' => 'jquery.ui',
	),
	'jquery.ui.sortable' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.sortable.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget', 'jquery.ui.mouse' ),
		'group' => 'jquery.ui',
	),
	// Widgets
	'jquery.ui.accordion' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.accordion.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget' ),
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.accordion.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.accordion.css',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.autocomplete' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.autocomplete.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget', 'jquery.ui.position' ),
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.autocomplete.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.autocomplete.css',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.button' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.button.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget' ),
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.button.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.button.css',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.datepicker' => array(
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
			'fr' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-fr.js',
			'gl' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-gl.js',
			'he' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-he.js',
			'hi' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-hi.js',
			'hr' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-hr.js',
			'hu' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-hu.js',
			'hy' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-hy.js',
			'id' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-id.js',
			'is' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-is.js',
			'it' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-it.js',
			'ja' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-ja.js',
			'ka' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-ka.js',
			'kk' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-kk.js',
			'km' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-km.js',
			'ko' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-ko.js',
			'lb' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-lb.js',
			'lt' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-lt.js',
			'lv' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-lv.js',
			'mk' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-mk.js',
			'ml' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-ml.js',
			'ms' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-ms.js',
			'nl' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-nl.js',
			'no' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-no.js',
			'pl' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-pl.js',
			'pt' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-pt.js',
			'pt-br' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-pt-BR.js',
			'rm' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-rm.js',
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
			'zh-tw' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-zh-TW.js',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.dialog' => array(
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
		'group' => 'jquery.ui',
	),
	'jquery.ui.progressbar' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.progressbar.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget' ),
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.progressbar.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.progressbar.css',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.slider' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.slider.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget', 'jquery.ui.mouse' ),
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.slider.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.slider.css',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.tabs' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.tabs.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget' ),
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.tabs.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.tabs.css',
		),
		'group' => 'jquery.ui',
	),
	// Effects
	'jquery.effects.core' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.core.js',
		'dependencies' => 'jquery',
		'group' => 'jquery.ui',
	),
	'jquery.effects.blind' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.blind.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.bounce' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.bounce.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.clip' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.clip.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.drop' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.drop.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.explode' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.explode.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.fade' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.fade.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.fold' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.fold.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.highlight' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.highlight.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.pulsate' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.pulsate.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.scale' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.scale.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.shake' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.shake.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.slide' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.slide.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.transfer' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.transfer.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),

	/* MediaWiki */

	'mediawiki' => array(
		'scripts' => 'resources/mediawiki/mediawiki.js',
		'debugScripts' => 'resources/mediawiki/mediawiki.log.js',
		'debugRaw' => false,
	),
	'mediawiki.api' => array(
		'scripts' => 'resources/mediawiki.api/mediawiki.api.js',
		'dependencies' => 'mediawiki.util',
	),
	'mediawiki.api.category' => array(
		'scripts' => 'resources/mediawiki.api/mediawiki.api.category.js',
		'dependencies' => array(
			'mediawiki.api',
			'mediawiki.Title',
		),
	),
	'mediawiki.api.edit' => array(
		'scripts' => 'resources/mediawiki.api/mediawiki.api.edit.js',
		'dependencies' => array(
			'mediawiki.api',
			'mediawiki.Title',
		),
	),
	'mediawiki.api.parse' => array(
		'scripts' => 'resources/mediawiki.api/mediawiki.api.parse.js',
		'dependencies' => 'mediawiki.api',
	),
	'mediawiki.api.titleblacklist' => array(
		'scripts' => 'resources/mediawiki.api/mediawiki.api.titleblacklist.js',
		'dependencies' => array(
			'mediawiki.api',
			'mediawiki.Title',
		),
	),
	'mediawiki.api.watch' => array(
		'scripts' => 'resources/mediawiki.api/mediawiki.api.watch.js',
		'dependencies' => array(
			'mediawiki.api',
			'user.tokens',
		),
	),
	'mediawiki.debug' => array(
		'scripts' => 'resources/mediawiki/mediawiki.debug.js',
		'styles' => 'resources/mediawiki/mediawiki.debug.css',
		'dependencies' => 'jquery.footHovzer',
		'position' => 'bottom',
	),
	'mediawiki.debug.init' => array(
		'scripts' => 'resources/mediawiki/mediawiki.debug.init.js',
		'dependencies' => 'mediawiki.debug',
		// Uses a custom mw.config variable that is set in debughtml,
		// must be loaded on the bottom
		'position' => 'bottom',
	),
	'mediawiki.feedback' => array(
		'scripts' => 'resources/mediawiki/mediawiki.feedback.js',
		'styles' => 'resources/mediawiki/mediawiki.feedback.css',
		'dependencies' => array(
			'mediawiki.api.edit',
			'mediawiki.Title',
			'mediawiki.jqueryMsg',
			'jquery.ui.dialog',
		),
		'messages' => array(
			'feedback-bugornote',
			'feedback-subject',
			'feedback-message',
			'feedback-cancel',
			'feedback-submit',
			'feedback-adding',
			'feedback-error1',
			'feedback-error2',
			'feedback-error3',
			'feedback-thanks',
			'feedback-close',
			'feedback-bugcheck',
			'feedback-bugnew',
		),
	),
	'mediawiki.htmlform' => array(
		'scripts' => 'resources/mediawiki/mediawiki.htmlform.js',
	),
	'mediawiki.notification' => array(
		'styles' => 'resources/mediawiki/mediawiki.notification.css',
		'scripts' => 'resources/mediawiki/mediawiki.notification.js',
		'dependencies' => array(
			'mediawiki.page.startup',
		),
	),
	'mediawiki.notify' => array(
		'scripts' => 'resources/mediawiki/mediawiki.notify.js',
	),
	'mediawiki.searchSuggest' => array(
		'scripts' => 'resources/mediawiki/mediawiki.searchSuggest.js',
		'messages' => array(
			'searchsuggest-search',
			'searchsuggest-containing',
		),
		'dependencies' => array(
			'jquery.autoEllipsis',
			'jquery.client',
			'jquery.placeholder',
			'jquery.suggestions',
		),
	),
	'mediawiki.Title' => array(
		'scripts' => 'resources/mediawiki/mediawiki.Title.js',
		'dependencies' => 'mediawiki.util',
	),
	'mediawiki.Uri' => array(
		'scripts' => 'resources/mediawiki/mediawiki.Uri.js',
	),
	'mediawiki.user' => array(
		'scripts' => 'resources/mediawiki/mediawiki.user.js',
		'dependencies' => array(
			'jquery.cookie',
			'mediawiki.api',
		),
	),
	'mediawiki.util' => array(
		'scripts' => 'resources/mediawiki/mediawiki.util.js',
		'dependencies' => array(
			'jquery.client',
			'jquery.cookie',
			'jquery.mwExtension',
			'mediawiki.notify',
		),
		'messages' => array( 'showtoc', 'hidetoc' ),
		'position' => 'top', // For $wgPreloadJavaScriptMwUtil
	),

	/* MediaWiki Action */

	'mediawiki.action.edit' => array(
		'scripts' => 'resources/mediawiki.action/mediawiki.action.edit.js',
		'dependencies' => array(
			'jquery.textSelection',
			'jquery.byteLimit',
		),
		'position' => 'top',
	),
	'mediawiki.action.edit.preview' => array(
		'scripts' => 'resources/mediawiki.action/mediawiki.action.edit.preview.js',
		'dependencies' => array(
			'jquery.form',
			'jquery.spinner',
		),
	),
	'mediawiki.action.history' => array(
		'scripts' => 'resources/mediawiki.action/mediawiki.action.history.js',
		'group' => 'mediawiki.action.history',
	),
	'mediawiki.action.history.diff' => array(
		'styles' => 'resources/mediawiki.action/mediawiki.action.history.diff.css',
		'group' => 'mediawiki.action.history',
	),
	'mediawiki.action.view.dblClickEdit' => array(
		'scripts' => 'resources/mediawiki.action/mediawiki.action.view.dblClickEdit.js',
		'dependencies' => 'mediawiki.util',
	),
	'mediawiki.action.view.metadata' => array(
		'scripts' => 'resources/mediawiki.action/mediawiki.action.view.metadata.js',
		'messages' => array(
			'metadata-expand',
			'metadata-collapse',
		),
	),
	'mediawiki.action.view.rightClickEdit' => array(
		'scripts' => 'resources/mediawiki.action/mediawiki.action.view.rightClickEdit.js',
	),
	// Alias for backwards compatibility
	'mediawiki.action.watch.ajax' => array(
		'dependencies' => 'mediawiki.page.watch.ajax'
	),

	/* MediaWiki Language */

	'mediawiki.language' => array(
		'scripts' => 'resources/mediawiki.language/mediawiki.language.js',
		'languageScripts' => array(
			'bs' => 'resources/mediawiki.language/languages/bs.js',
			'dsb' => 'resources/mediawiki.language/languages/dsb.js',
			'fi' => 'resources/mediawiki.language/languages/fi.js',
			'ga' => 'resources/mediawiki.language/languages/ga.js',
			'he' => 'resources/mediawiki.language/languages/he.js',
			'hsb' => 'resources/mediawiki.language/languages/hsb.js',
			'hu' => 'resources/mediawiki.language/languages/hu.js',
			'hy' => 'resources/mediawiki.language/languages/hy.js',
			'la' => 'resources/mediawiki.language/languages/la.js',
			'os' => 'resources/mediawiki.language/languages/os.js',
			'ru' => 'resources/mediawiki.language/languages/ru.js',
			'sl' => 'resources/mediawiki.language/languages/sl.js',
			'uk' => 'resources/mediawiki.language/languages/uk.js',
		),
		'dependencies' => array(
				'mediawiki.language.data',
				'mediawiki.cldr'
			),
	),

	'mediawiki.cldr' => array(
		'scripts' => 'resources/mediawiki.language/mediawiki.cldr.js',
		'dependencies' => array(
			'mediawiki.libs.pluralruleparser',
		),
	),

	'mediawiki.libs.pluralruleparser' => array(
		'scripts' => 'resources/mediawiki.libs/CLDRPluralRuleParser.js',
	),

	'mediawiki.language.init' => array(
		'scripts' => 'resources/mediawiki.language/mediawiki.language.init.js',
	),

	'mediawiki.jqueryMsg' => array(
		'scripts' => 'resources/mediawiki/mediawiki.jqueryMsg.js',
		'dependencies' => array(
			'mediawiki.util',
			'mediawiki.language',
		),
	),

	/* MediaWiki Libs */

	'mediawiki.libs.jpegmeta' => array(
		'scripts' => 'resources/mediawiki.libs/mediawiki.libs.jpegmeta.js',
	),

	/* MediaWiki Page */

	'mediawiki.page.ready' => array(
		'scripts' => 'resources/mediawiki.page/mediawiki.page.ready.js',
		'dependencies' => array(
			'jquery.checkboxShiftClick',
			'jquery.makeCollapsible',
			'jquery.placeholder',
			'jquery.mw-jump',
			'mediawiki.util',
		),
	),
	'mediawiki.page.startup' => array(
		'scripts' => 'resources/mediawiki.page/mediawiki.page.startup.js',
		'dependencies' => array(
			'jquery.client',
			'mediawiki.util',
		),
		'position' => 'top',
	),
	'mediawiki.page.watch.ajax' => array(
		'scripts' => 'resources/mediawiki.page/mediawiki.page.watch.ajax.js',
		'dependencies' => array(
			'mediawiki.page.startup',
			'mediawiki.api.watch',
			'mediawiki.util',
			'mediawiki.notify',
			'jquery.mwExtension',
		),
		'messages' => array(
			'watch',
			'unwatch',
			'watching',
			'unwatching',
			'tooltip-ca-watch',
			'tooltip-ca-unwatch',
			'watcherrortext',
		),
	),

	/* MediaWiki Special pages */

	'mediawiki.special' => array(
		'scripts' => 'resources/mediawiki.special/mediawiki.special.js',
		'styles' => 'resources/mediawiki.special/mediawiki.special.css',
	),
	'mediawiki.special.block' => array(
		'scripts' => 'resources/mediawiki.special/mediawiki.special.block.js',
		'dependencies' => array(
			'mediawiki.util',
		),
	),
	'mediawiki.special.changeemail' => array(
		'scripts' => 'resources/mediawiki.special/mediawiki.special.changeemail.js',
		'styles' => 'resources/mediawiki.special/mediawiki.special.changeemail.css',
		'dependencies' => array(
			'mediawiki.util',
		),
		'messages' => array(
			'email-address-validity-valid',
			'email-address-validity-invalid',
		),
	),
	'mediawiki.special.changeslist' => array(
		'styles' => 'resources/mediawiki.special/mediawiki.special.changeslist.css',
		'dependencies' => array( 'jquery.makeCollapsible' ),
	),
	'mediawiki.special.movePage' => array(
		'scripts' => 'resources/mediawiki.special/mediawiki.special.movePage.js',
		'dependencies' => 'jquery.byteLimit',
	),
	'mediawiki.special.preferences' => array(
		'scripts' => 'resources/mediawiki.special/mediawiki.special.preferences.js',
		'styles'  => 'resources/mediawiki.special/mediawiki.special.preferences.css',
	),
	'mediawiki.special.recentchanges' => array(
		'scripts' => 'resources/mediawiki.special/mediawiki.special.recentchanges.js',
		'dependencies' => array( 'mediawiki.special' ),
		'position' => 'top',
	),
	'mediawiki.special.search' => array(
		'scripts' => 'resources/mediawiki.special/mediawiki.special.search.js',
		'styles' => 'resources/mediawiki.special/mediawiki.special.search.css',
		'messages' => array(
			'powersearch-togglelabel',
			'powersearch-toggleall',
			'powersearch-togglenone',
		),
	),
	'mediawiki.special.undelete' => array(
		'scripts' => 'resources/mediawiki.special/mediawiki.special.undelete.js',
	),
	'mediawiki.special.upload' => array(
		// @TODO: merge in remainder of mediawiki.legacy.upload
		'scripts' => 'resources/mediawiki.special/mediawiki.special.upload.js',
		'messages' => array(
			'widthheight',
			'size-bytes',
			'size-kilobytes',
			'size-megabytes',
			'size-gigabytes',
			'largefileserver',
		),
		'dependencies' => array( 'mediawiki.libs.jpegmeta', 'mediawiki.util' ),
	),
	'mediawiki.special.javaScriptTest' => array(
		'scripts' => 'resources/mediawiki.special/mediawiki.special.javaScriptTest.js',
		'messages' => array_merge( Skin::getSkinNameMessages(), array(
			'colon-separator',
			'javascripttest-pagetext-skins',
		) ),
		'dependencies' => array( 'jquery.qunit' ),
		'position' => 'top',
	),

	/* MediaWiki Tests */

	'mediawiki.tests.qunit.testrunner' => array(
		'scripts' => 'tests/qunit/data/testrunner.js',
		'dependencies' => array(
			'jquery.qunit',
			'jquery.qunit.completenessTest',
			'mediawiki.page.startup',
			'mediawiki.page.ready',
		),
		'position' => 'top',
	),

	/* MediaWiki Legacy */

	'mediawiki.legacy.ajax' => array(
		'scripts' => 'common/ajax.js',
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
		'dependencies' => array(
			'mediawiki.util',
			'mediawiki.legacy.wikibits',
		),
		'position' => 'top', // Temporary hack for legacy support
	),
	'mediawiki.legacy.commonPrint' => array(
		'styles' => array( 'common/commonPrint.css' => array( 'media' => 'print' ) ),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
	),
	'mediawiki.legacy.config' => array(
		'scripts' => 'common/config.js',
		'styles' => array( 'common/config.css', 'common/config-cc.css' ),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
		'dependencies' => 'mediawiki.legacy.wikibits',
	),
	'mediawiki.legacy.IEFixes' => array(
		'scripts' => 'common/IEFixes.js',
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
		'dependencies' => 'mediawiki.legacy.wikibits',
	),
	'mediawiki.legacy.protect' => array(
		'scripts' => 'common/protect.js',
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
		'dependencies' => array(
			'mediawiki.legacy.wikibits',
			'jquery.byteLimit',
		),
		'position' => 'top',
	),
	'mediawiki.legacy.shared' => array(
		'styles' => array( 'common/shared.css' => array( 'media' => 'screen' ) ),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
	),
	'mediawiki.legacy.oldshared' => array(
		'styles' => array( 'common/oldshared.css' => array( 'media' => 'screen' ) ),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
	),
	'mediawiki.legacy.upload' => array(
		'scripts' => 'common/upload.js',
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
		'dependencies' => array(
			'mediawiki.legacy.wikibits',
			'mediawiki.util',
		),
	),
	'mediawiki.legacy.wikibits' => array(
		'scripts' => 'common/wikibits.js',
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
		'dependencies' => array(
			'mediawiki.util',
		),
		'position' => 'top',
	),
	'mediawiki.legacy.wikiprintable' => array(
		'styles' => array( 'common/wikiprintable.css' => array( 'media' => 'print' ) ),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
	),
);
