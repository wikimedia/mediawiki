<?php

return array(

	/* Special resources who have their own classes */

	'site' => array( 'class' => 'ResourceLoaderSiteModule' ),
	'startup' => array( 'class' => 'ResourceLoaderStartUpModule' ),
	'user' => array( 'class' => 'ResourceLoaderUserModule' ),
	'user.options' => array( 'class' => 'ResourceLoaderUserOptionsModule' ),
	'user.groups' => array( 'class' => 'ResourceLoaderUserGroupsModule' ),

	/* Skins */

	'skins.vector' => array(
		'styles' => array( 'vector/screen.css' => array( 'media' => 'screen' ) ),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => "{$GLOBALS['IP']}/skins",
	),
	'skins.monobook' => array(
		'styles' => array(
			'monobook/main.css' => array( 'media' => 'screen' ),
		),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => "{$GLOBALS['IP']}/skins",
	),
	'skins.simple' => array(
		'styles' => array( 'simple/main.css' => array( 'media' => 'screen' ) ),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => "{$GLOBALS['IP']}/skins",
	),
	'skins.chick' => array(
		'styles' => array( 'chick/main.css' => array( 'media' => 'screen,handheld' ) ),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => "{$GLOBALS['IP']}/skins",
	),
	'skins.modern' => array(
		'styles' => array( 'modern/main.css' => array( 'media' => 'screen' ),
				   'modern/print.css' => array( 'media' => 'print' ) ),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => "{$GLOBALS['IP']}/skins",
	),
	'skins.cologneblue' => array(
		'styles' => array( 'common/cologneblue.css' => array( 'media' => 'screen' ) ),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => "{$GLOBALS['IP']}/skins",
	),
	'skins.nostalgia' => array(
		'styles' => array( 'common/nostalgia.css' => array( 'media' => 'screen' ) ),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => "{$GLOBALS['IP']}/skins",
	),
	'skins.standard' => array(
		'styles' => array( 'common/wikistandard.css' => array( 'media' => 'screen' ) ),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => "{$GLOBALS['IP']}/skins",
	),

	/* jQuery */

	'jquery' => array(
		'scripts' => 'resources/jquery/jquery.js',
		'debugRaw' => false
	),

	/* jQuery Plugins */

	'jquery.async' => array(
		'scripts' => 'resources/jquery/jquery.async.js',
	),
	'jquery.autoEllipsis' => array(
		'scripts' => 'resources/jquery/jquery.autoEllipsis.js',
		'dependencies' => 'jquery.highlightText',
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
	'jquery.colorUtil' => array(
		'scripts' => 'resources/jquery/jquery.colorUtil.js',
	),
	'jquery.color' => array(
		'scripts' => 'resources/jquery/jquery.color.js',
		'dependencies' => 'jquery.colorUtil',
	),
	'jquery.cookie' => array(
		'scripts' => 'resources/jquery/jquery.cookie.js',
	),
	'jquery.delayedBind' => array(
		'scripts' => 'resources/jquery/jquery.delayedBind.js',
	),
	'jquery.expandableField' => array(
		'scripts' => 'resources/jquery/jquery.expandableField.js',
	),
	'jquery.form' => array(
		'scripts' => 'resources/jquery/jquery.form.js',
	),
	'jquery.highlightText' => array(
		'scripts' => 'resources/jquery/jquery.highlightText.js',
	),
	'jquery.hoverIntent' => array(
		'scripts' => 'resources/jquery/jquery.hoverIntent.js',
	),
	'jquery.messageBox' => array(
		'scripts' => 'resources/jquery/jquery.messageBox.js',
		'styles' => 'resources/jquery/jquery.messageBox.css',
	),
	'jquery.placeholder' => array(
		'scripts' => 'resources/jquery/jquery.placeholder.js',
	),
	'jquery.localize' => array(
		'scripts' => 'resources/jquery/jquery.localize.js',
	),
	'jquery.makeCollapsible' => array(
		'scripts' => 'resources/jquery/jquery.makeCollapsible.js',
		'styles' => 'resources/jquery/jquery.makeCollapsible.css',
		'messages' => array( 'collapsible-expand', 'collapsible-collapse' ),
	),
	'jquery.suggestions' => array(
		'scripts' => 'resources/jquery/jquery.suggestions.js',
		'styles' => 'resources/jquery/jquery.suggestions.css',
	),
	'jquery.tabIndex' => array(
		'scripts' => 'resources/jquery/jquery.tabIndex.js',
	),
	'jquery.textSelection' => array(
		'scripts' => 'resources/jquery/jquery.textSelection.js',
	),
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
	),
	'jquery.ui.widget' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.widget.js',
	),
	'jquery.ui.mouse' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.mouse.js',
		'dependencies' => 'jquery.ui.widget',
	),
	'jquery.ui.position' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.position.js',
	),
	// Interactions
	'jquery.ui.draggable' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.draggable.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.mouse', 'jquery.ui.widget' ),
	),
	'jquery.ui.droppable' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.droppable.js',
		'dependencies' => array(
			'jquery.ui.core', 'jquery.ui.mouse', 'jquery.ui.widget', 'jquery.ui.draggable',
		),
	),
	'jquery.ui.resizable' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.resizable.js',
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.resizable.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.resizable.css',
		),
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget', 'jquery.ui.mouse' ),
	),
	'jquery.ui.selectable' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.selectable.js',
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.selectable.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.selectable.css',
		),
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget', 'jquery.ui.mouse' ),
	),
	'jquery.ui.sortable' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.sortable.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget', 'jquery.ui.mouse' ),
	),
	// Widgets
	'jquery.ui.accordion' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.accordion.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget' ),
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.accordion.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.accordion.css',
		),
	),
	'jquery.ui.autocomplete' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.autocomplete.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget', 'jquery.ui.position' ),
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.autocomplete.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.autocomplete.css',
		),
	),
	'jquery.ui.button' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.button.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget' ),
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.button.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.button.css',
		),
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
			'zh-tw' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-zh-TW.js',
		),
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
	),
	'jquery.ui.progressbar' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.progressbar.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget' ),
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.progressbar.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.progressbar.css',
		),
	),
	'jquery.ui.slider' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.slider.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget', 'jquery.ui.mouse' ),
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.slider.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.slider.css',
		),
	),
	'jquery.ui.tabs' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.tabs.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget' ),
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.tabs.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.tabs.css',
		),
	),
	// Effects
	'jquery.effects.core' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.core.js',
		'dependencies' => 'jquery',
	),
	'jquery.effects.blind' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.blind.js',
		'dependencies' => 'jquery.effects.core',
	),
	'jquery.effects.bounce' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.bounce.js',
		'dependencies' => 'jquery.effects.core',
	),
	'jquery.effects.clip' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.clip.js',
		'dependencies' => 'jquery.effects.core',
	),
	'jquery.effects.drop' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.drop.js',
		'dependencies' => 'jquery.effects.core',
	),
	'jquery.effects.explode' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.explode.js',
		'dependencies' => 'jquery.effects.core',
	),
	'jquery.effects.fold' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.fold.js',
		'dependencies' => 'jquery.effects.core',
	),
	'jquery.effects.highlight' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.highlight.js',
		'dependencies' => 'jquery.effects.core',
	),
	'jquery.effects.pulsate' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.pulsate.js',
		'dependencies' => 'jquery.effects.core',
	),
	'jquery.effects.scale' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.scale.js',
		'dependencies' => 'jquery.effects.core',
	),
	'jquery.effects.shake' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.shake.js',
		'dependencies' => 'jquery.effects.core',
	),
	'jquery.effects.slide' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.slide.js',
		'dependencies' => 'jquery.effects.core',
	),
	'jquery.effects.transfer' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.transfer.js',
		'dependencies' => 'jquery.effects.core',
	),

	/* MediaWiki */

	'mediawiki' => array(
		'scripts' => 'resources/mediawiki/mediawiki.js',
		'debugScripts' => 'resources/mediawiki/mediawiki.log.js',
		'debugRaw' => false,
	),
	'mediawiki.util' => array(
		'scripts' => 'resources/mediawiki.util/mediawiki.util.js',
		'dependencies' => array(
			'jquery.checkboxShiftClick',
			'jquery.client',
			'jquery.cookie',
			'jquery.messageBox',
			'jquery.makeCollapsible',
			'jquery.placeholder',
		),
		'debugScripts' => 'resources/mediawiki.util/mediawiki.util.test.js',
	),
	'mediawiki.util.jpegmeta' => array(
		'scripts' => 'resources/mediawiki.util/mediawiki.util.jpegmeta.js',
	),
	'mediawiki.action.history' => array(
		'scripts' => 'resources/mediawiki.action/mediawiki.action.history.js',
		'dependencies' => 'mediawiki.legacy.history',
		'group' => 'mediawiki.action.history',
	),
	'mediawiki.action.edit' => array(
		'scripts' => 'resources/mediawiki.action/mediawiki.action.edit.js',
	),
	'mediawiki.action.view.rightClickEdit' => array(
		'scripts' => 'resources/mediawiki.action/mediawiki.action.view.rightClickEdit.js',
	),
	'mediawiki.action.watch.ajax' => array(
		'scripts' => 'resources/mediawiki.action/mediawiki.action.watch.ajax.js',
		'dependencies' => 'mediawiki.util',
	),
	'mediawiki.special.preferences' => array(
		'scripts' => 'resources/mediawiki.special/mediawiki.special.preferences.js',
		'styles' => 'resources/mediawiki.special/mediawiki.special.preferences.css',
		'messages' => array( 'email-address-validity-valid', 'email-address-validity-invalid' ),
	),
	'mediawiki.special.changeslist' => array(
		'styles' => 'resources/mediawiki.special/mediawiki.special.changeslist.css',
		'dependencies' => array( 'jquery.makeCollapsible' ),
	),
	'mediawiki.special.search' => array(
		'scripts' => 'resources/mediawiki.special/mediawiki.special.search.js',
	),
	'mediawiki.special.block' => array(
		'scripts' => 'resources/mediawiki.special/mediawiki.special.block.js',
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
		'dependencies' => array( 'mediawiki.util.jpegmeta' ),
	),
	'mediawiki.language' => array(
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
	),

	/* mediawiki Legacy */

	'mediawiki.legacy.ajax' => array(
		'scripts' => 'common/ajax.js',
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => "{$GLOBALS['IP']}/skins",
		'messages' => array(
			'watch',
			'unwatch',
			'watching',
			'unwatching',
			'tooltip-ca-watch',
			'tooltip-ca-unwatch',
		),
		'dependencies' => 'mediawiki.legacy.wikibits',
	),
	'mediawiki.legacy.commonPrint' => array(
		'styles' => array( 'common/commonPrint.css' => array( 'media' => 'print' ) ),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => "{$GLOBALS['IP']}/skins",
	),
	'mediawiki.legacy.config' => array(
		'scripts' => 'common/config.js',
		'styles' => array( 'common/config.css', 'common/config-cc.css' ),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => "{$GLOBALS['IP']}/skins",
		'dependencies' => 'mediawiki.legacy.wikibits',
	),
	'mediawiki.legacy.diff' => array(
		'scripts' => 'common/diff.js',
		'styles' => 'common/diff.css',
		'group' => 'mediawiki.action.history',
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => "{$GLOBALS['IP']}/skins",
		'dependencies' => 'mediawiki.legacy.wikibits',
	),
	'mediawiki.legacy.edit' => array(
		'scripts' => 'common/edit.js',
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => "{$GLOBALS['IP']}/skins",
		'dependencies' => 'mediawiki.legacy.wikibits',
	),
	'mediawiki.legacy.history' => array(
		'scripts' => 'common/history.js',
		'group' => 'mediawiki.action.history',
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => "{$GLOBALS['IP']}/skins",
		'dependencies' => 'mediawiki.legacy.wikibits',
	),
	'mediawiki.legacy.htmlform' => array(
		'scripts' => 'common/htmlform.js',
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => "{$GLOBALS['IP']}/skins",
		'dependencies' => 'mediawiki.legacy.wikibits',
	),
	'mediawiki.legacy.IEFixes' => array(
		'scripts' => 'common/IEFixes.js',
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => "{$GLOBALS['IP']}/skins",
		'dependencies' => 'mediawiki.legacy.wikibits',
	),
	'mediawiki.legacy.metadata' => array(
		'scripts' => 'common/metadata.js',
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => "{$GLOBALS['IP']}/skins",
		'dependencies' => 'mediawiki.legacy.wikibits',
		'messages' => array( 'metadata-expand', 'metadata-collapse' ),
	),
	'mediawiki.legacy.mwsuggest' => array(
		'scripts' => 'common/mwsuggest.js',
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => "{$GLOBALS['IP']}/skins",
		'dependencies' => array( 'mediawiki.legacy.wikibits', 'jquery.client' ),
		'messages' => array( 'search-mwsuggest-enabled', 'search-mwsuggest-disabled' ),
	),
	'mediawiki.legacy.password' => array(
		'scripts' => 'common/password.js',
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => "{$GLOBALS['IP']}/skins",
		'styles' => 'common/password.css',
		'dependencies' => 'mediawiki.legacy.wikibits',
	),
	'mediawiki.legacy.prefs' => array(
		'scripts' => 'common/prefs.js',
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => "{$GLOBALS['IP']}/skins",
		'dependencies' => array( 'mediawiki.legacy.wikibits', 'mediawiki.legacy.htmlform' ),
	),
	'mediawiki.legacy.preview' => array(
		'scripts' => 'common/preview.js',
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => "{$GLOBALS['IP']}/skins",
		'dependencies' => 'mediawiki.legacy.wikibits',
	),
	'mediawiki.legacy.protect' => array(
		'scripts' => 'common/protect.js',
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => "{$GLOBALS['IP']}/skins",
		'dependencies' => 'mediawiki.legacy.wikibits',
	),
	'mediawiki.legacy.search' => array(
		'scripts' => 'common/search.js',
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => "{$GLOBALS['IP']}/skins",
		'styles' => 'common/search.css',
		'dependencies' => 'mediawiki.legacy.wikibits',
	),
	'mediawiki.legacy.shared' => array(
		'styles' => array( 'common/shared.css' => array( 'media' => 'screen' ) ),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => "{$GLOBALS['IP']}/skins",
	),
	'mediawiki.legacy.oldshared' => array(
		'styles' => array( 'common/oldshared.css' => array( 'media' => 'screen' ) ),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => "{$GLOBALS['IP']}/skins",
	),
	'mediawiki.legacy.upload' => array(
		'scripts' => 'common/upload.js',
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => "{$GLOBALS['IP']}/skins",
		'dependencies' => 'mediawiki.legacy.wikibits',
	),
	'mediawiki.legacy.wikibits' => array(
		'scripts' => 'common/wikibits.js',
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => "{$GLOBALS['IP']}/skins",
		'dependencies' => 'mediawiki.language',
		'messages' => array( 'showtoc', 'hidetoc' ),
	),
	'mediawiki.legacy.wikiprintable' => array(
		'styles' => array( 'common/wikiprintable.css' => array( 'media' => 'print' ) ),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => "{$GLOBALS['IP']}/skins",
	),
);
