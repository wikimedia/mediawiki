<?php
/**
 * Definition of core ResourceLoader modules.
 *
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

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

return array(

	/**
	 * Special modules who have their own classes
	 */

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

	/* MediaWiki base skinning modules */

	/**
	 * Common skin styles, grouped into three graded levels.
	 *
	 * Level 1 "elements":
	 *     The base level that only contains the most basic of common skin styles.
	 *     Only styles for single elements are included, no styling for complex structures like the
	 *     TOC is present. This level is for skins that want to implement the entire style of even
	 *     content area structures like the TOC themselves.
	 *
	 * Level 2 "content":
	 *     The most commonly used level for skins implemented from scratch. This level includes all
	 *     the single element styles from "elements" as well as styles for complex structures such
	 *     as the TOC that are output in the content area by MediaWiki rather than the skin.
	 *     Essentially this is the common level that lets skins leave the style of the content area
	 *     as it is normally styled, while leaving the rest of the skin up to the skin
	 *     implementation.
	 *
	 * Level 3 "interface":
	 *     The highest level, this stylesheet contains extra common styles for classes like
	 *     .firstHeading, #contentSub, et cetera which are not outputted by MediaWiki but are common
	 *     to skins like MonoBook, Vector, etc... Essentially this level is for styles that are
	 *     common to MonoBook clones. And since practically every skin that currently exists within
	 *     core is a MonoBook clone, all our core skins currently use this level.
	 *
	 * These modules are typically loaded by addModuleStyles(), which has absolutely no concept of
	 * dependency management. As a result they contain duplicate stylesheet references instead of
	 * setting 'dependencies' to the lower level the module is based on. For this reason avoid
	 * including more than one of them into your skin as this will result in duplicate CSS.
	 */
	'mediawiki.skinning.elements' => array(
		'styles' => array(
			'common/commonElements.css' => array( 'media' => 'screen' ),
		),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
	),
	'mediawiki.skinning.content' => array(
		'styles' => array(
			'common/commonElements.css' => array( 'media' => 'screen' ),
			'common/commonContent.css' => array( 'media' => 'screen' ),
		),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
	),
	'mediawiki.skinning.interface' => array(
		// Used in the web installer. Test it after modifying this definition!
		'styles' => array(
			'common/commonElements.css' => array( 'media' => 'screen' ),
			'common/commonContent.css' => array( 'media' => 'screen' ),
			'common/commonInterface.css' => array( 'media' => 'screen' ),
		),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
	),

	/**
	 * Skins
	 * Be careful not to add 'scripts' to these modules,
	 * since they are loaded with OutputPage::addModuleStyles so that the skin styles
	 * apply without javascript.
	 * If a skin needs custom js in the interface, register a separate module
	 * and add it to the load queue with OutputPage::addModules.
	 *
	 * See Vector for an example.
	 */
	'skins.cologneblue' => array(
		'styles' => array(
			'cologneblue/screen.css' => array( 'media' => 'screen' ),
			'cologneblue/print.css' => array( 'media' => 'print' ),
		),
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
	'skins.vector.styles' => array(
		// Used in the web installer. Test it after modifying this definition!
		'styles' => array(
			'vector/screen.less' => array( 'media' => 'screen' ),
			'vector/screen-hd.less' => array( 'media' => 'screen and (min-width: 982px)' ),
		),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
	),
	'skins.monobook.styles' => array(
		'styles' => array(
			'monobook/main.css' => array( 'media' => 'screen' ),
		),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
	),
	'skins.vector.js' => array(
		'scripts' => array(
			'vector/collapsibleTabs.js',
			'vector/vector.js',
		),
		'position' => 'top',
		'dependencies' => 'jquery.throttle-debounce',
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
	),
	'skins.vector.collapsibleNav' => array(
		'scripts' => array(
			'vector/collapsibleNav.js',
		),
		'messages' => array(
			'vector-collapsiblenav-more',
		),
		'dependencies' => array(
			'jquery.client',
			'jquery.cookie',
			'jquery.tabIndex',
		),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
		'position' => 'bottom',
	),

	/* jQuery */

	'jquery' => array(
		'scripts' => 'resources/lib/jquery/jquery.js',
		'debugRaw' => false,
		'targets' => array( 'desktop', 'mobile' ),
	),

	/* jQuery Plugins */

	'jquery.appear' => array(
		'scripts' => 'resources/lib/jquery/jquery.appear.js',
	),
	'jquery.arrowSteps' => array(
		'scripts' => 'resources/src/jquery/jquery.arrowSteps.js',
		'styles' => 'resources/src/jquery/jquery.arrowSteps.css',
	),
	'jquery.async' => array(
		'scripts' => 'resources/lib/jquery/jquery.async.js',
	),
	'jquery.autoEllipsis' => array(
		'scripts' => 'resources/src/jquery/jquery.autoEllipsis.js',
		'dependencies' => 'jquery.highlightText',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'jquery.badge' => array(
		'scripts' => 'resources/src/jquery/jquery.badge.js',
		'styles' => 'resources/src/jquery/jquery.badge.css',
		'dependencies' => 'mediawiki.language',
	),
	'jquery.byteLength' => array(
		'scripts' => 'resources/src/jquery/jquery.byteLength.js',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'jquery.byteLimit' => array(
		'scripts' => 'resources/src/jquery/jquery.byteLimit.js',
		'dependencies' => 'jquery.byteLength',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'jquery.checkboxShiftClick' => array(
		'scripts' => 'resources/src/jquery/jquery.checkboxShiftClick.js',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'jquery.chosen' => array(
		'scripts' => 'resources/lib/jquery.chosen/chosen.jquery.js',
		'styles' => 'resources/lib/jquery.chosen/chosen.css',
	),
	'jquery.client' => array(
		'scripts' => 'resources/src/jquery/jquery.client.js',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'jquery.color' => array(
		'scripts' => 'resources/src/jquery/jquery.color.js',
		'dependencies' => 'jquery.colorUtil',
	),
	'jquery.colorUtil' => array(
		'scripts' => 'resources/src/jquery/jquery.colorUtil.js',
	),
	'jquery.cookie' => array(
		'scripts' => 'resources/lib/jquery/jquery.cookie.js',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'jquery.delayedBind' => array(
		'scripts' => 'resources/src/jquery/jquery.delayedBind.js',
	),
	'jquery.expandableField' => array(
		'scripts' => 'resources/src/jquery/jquery.expandableField.js',
	),
	'jquery.farbtastic' => array(
		'scripts' => 'resources/src/jquery/jquery.farbtastic.js',
		'styles' => 'resources/src/jquery/jquery.farbtastic.css',
		'dependencies' => 'jquery.colorUtil',
	),
	'jquery.footHovzer' => array(
		'scripts' => 'resources/src/jquery/jquery.footHovzer.js',
		'styles' => 'resources/src/jquery/jquery.footHovzer.css',
	),
	'jquery.form' => array(
		'scripts' => 'resources/lib/jquery/jquery.form.js',
	),
	'jquery.fullscreen' => array(
		'scripts' => 'resources/lib/jquery/jquery.fullscreen.js',
	),
	'jquery.getAttrs' => array(
		'scripts' => 'resources/src/jquery/jquery.getAttrs.js',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'jquery.hidpi' => array(
		'scripts' => 'resources/src/jquery/jquery.hidpi.js',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'jquery.highlightText' => array(
		'scripts' => 'resources/src/jquery/jquery.highlightText.js',
		'dependencies' => 'jquery.mwExtension',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'jquery.hoverIntent' => array(
		'scripts' => 'resources/lib/jquery/jquery.hoverIntent.js',
	),
	'jquery.json' => array(
		'scripts' => 'resources/lib/jquery/jquery.json.js',
		'targets' => array( 'mobile', 'desktop' ),
	),
	'jquery.localize' => array(
		'scripts' => 'resources/src/jquery/jquery.localize.js',
	),
	'jquery.makeCollapsible' => array(
		'scripts' => 'resources/src/jquery/jquery.makeCollapsible.js',
		'styles' => 'resources/src/jquery/jquery.makeCollapsible.css',
		'messages' => array( 'collapsible-expand', 'collapsible-collapse' ),
		'targets' => array( 'desktop', 'mobile' ),
	),
	'jquery.mockjax' => array(
		'scripts' => 'resources/lib/jquery/jquery.mockjax.js',
	),
	'jquery.mw-jump' => array(
		'scripts' => 'resources/src/jquery/jquery.mw-jump.js',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'jquery.mwExtension' => array(
		'scripts' => 'resources/src/jquery/jquery.mwExtension.js',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'jquery.placeholder' => array(
		'scripts' => 'resources/src/jquery/jquery.placeholder.js',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'jquery.qunit' => array(
		'scripts' => 'resources/lib/jquery/jquery.qunit.js',
		'styles' => 'resources/lib/jquery/jquery.qunit.css',
		'position' => 'top',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'jquery.qunit.completenessTest' => array(
		'scripts' => 'resources/src/jquery/jquery.qunit.completenessTest.js',
		'dependencies' => 'jquery.qunit',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'jquery.spinner' => array(
		'scripts' => 'resources/src/jquery/jquery.spinner.js',
		'styles' => 'resources/src/jquery/jquery.spinner.css',
	),
	'jquery.jStorage' => array(
		'scripts' => 'resources/lib/jquery/jquery.jStorage.js',
		'dependencies' => 'jquery.json',
	),
	'jquery.suggestions' => array(
		'scripts' => 'resources/src/jquery/jquery.suggestions.js',
		'styles' => 'resources/src/jquery/jquery.suggestions.css',
		'dependencies' => 'jquery.highlightText',
	),
	'jquery.tabIndex' => array(
		'scripts' => 'resources/src/jquery/jquery.tabIndex.js',
	),
	'jquery.tablesorter' => array(
		'scripts' => 'resources/src/jquery/jquery.tablesorter.js',
		'styles' => 'resources/src/jquery/jquery.tablesorter.css',
		'messages' => array( 'sort-descending', 'sort-ascending' ),
		'dependencies' => array(
			'jquery.mwExtension',
			'mediawiki.language.months',
		),
	),
	'jquery.textSelection' => array(
		'scripts' => 'resources/src/jquery/jquery.textSelection.js',
		'dependencies' => 'jquery.client',
	),
	'jquery.throttle-debounce' => array(
		'scripts' => 'resources/lib/jquery/jquery.ba-throttle-debounce.js',
	),
	'jquery.validate' => array(
		'scripts' => 'resources/lib/jquery/jquery.validate.js',
	),
	'jquery.xmldom' => array(
		'scripts' => 'resources/lib/jquery/jquery.xmldom.js',
	),

	/* jQuery Tipsy */

	'jquery.tipsy' => array(
		'scripts' => 'resources/src/jquery.tipsy/jquery.tipsy.js',
		'styles' => 'resources/src/jquery.tipsy/jquery.tipsy.css',
	),

	/* jQuery UI */

	// Core
	'jquery.ui.core' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.core.js',
		'skinStyles' => array(
			'default' => array(
				'resources/lib/jquery.ui/themes/default/jquery.ui.core.css',
				'resources/lib/jquery.ui/themes/default/jquery.ui.theme.css',
			),
			'vector' => array(
				'resources/src/jquery.ui-themes/vector/jquery.ui.core.css',
				'resources/src/jquery.ui-themes/vector/jquery.ui.theme.css',
			),
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.widget' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.widget.js',
		'group' => 'jquery.ui',
	),
	'jquery.ui.mouse' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.mouse.js',
		'dependencies' => 'jquery.ui.widget',
		'group' => 'jquery.ui',
	),
	'jquery.ui.position' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.position.js',
		'group' => 'jquery.ui',
	),
	// Interactions
	'jquery.ui.draggable' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.draggable.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.mouse', 'jquery.ui.widget' ),
		'group' => 'jquery.ui',
	),
	'jquery.ui.droppable' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.droppable.js',
		'dependencies' => array(
			'jquery.ui.core', 'jquery.ui.mouse', 'jquery.ui.widget', 'jquery.ui.draggable',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.resizable' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.resizable.js',
		'skinStyles' => array(
			'default' => 'resources/lib/jquery.ui/themes/default/jquery.ui.resizable.css',
			'vector' => 'resources/src/jquery.ui-themes/vector/jquery.ui.resizable.css',
		),
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget', 'jquery.ui.mouse' ),
		'group' => 'jquery.ui',
	),
	'jquery.ui.selectable' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.selectable.js',
		'skinStyles' => array(
			'default' => 'resources/lib/jquery.ui/themes/default/jquery.ui.selectable.css',
			'vector' => 'resources/src/jquery.ui-themes/vector/jquery.ui.selectable.css',
		),
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget', 'jquery.ui.mouse' ),
		'group' => 'jquery.ui',
	),
	'jquery.ui.sortable' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.sortable.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget', 'jquery.ui.mouse' ),
		'group' => 'jquery.ui',
	),
	// Widgets
	'jquery.ui.accordion' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.accordion.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget' ),
		'skinStyles' => array(
			'default' => 'resources/lib/jquery.ui/themes/default/jquery.ui.accordion.css',
			'vector' => 'resources/src/jquery.ui-themes/vector/jquery.ui.accordion.css',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.autocomplete' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.autocomplete.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget', 'jquery.ui.position' ),
		'skinStyles' => array(
			'default' => 'resources/lib/jquery.ui/themes/default/jquery.ui.autocomplete.css',
			'vector' => 'resources/src/jquery.ui-themes/vector/jquery.ui.autocomplete.css',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.button' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.button.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget' ),
		'skinStyles' => array(
			'default' => 'resources/lib/jquery.ui/themes/default/jquery.ui.button.css',
			'vector' => 'resources/src/jquery.ui-themes/vector/jquery.ui.button.css',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.datepicker' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.datepicker.js',
		'dependencies' => 'jquery.ui.core',
		'skinStyles' => array(
			'default' => 'resources/lib/jquery.ui/themes/default/jquery.ui.datepicker.css',
			'vector' => 'resources/src/jquery.ui-themes/vector/jquery.ui.datepicker.css',
		),
		'languageScripts' => array(
			'af' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-af.js',
			'ar' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ar.js',
			'az' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-az.js',
			'bg' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-bg.js',
			'bs' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-bs.js',
			'ca' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ca.js',
			'cs' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-cs.js',
			'da' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-da.js',
			'de' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-de.js',
			'el' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-el.js',
			'en-gb' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-en-GB.js',
			'eo' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-eo.js',
			'es' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-es.js',
			'et' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-et.js',
			'eu' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-eu.js',
			'fa' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-fa.js',
			'fi' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-fi.js',
			'fo' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-fo.js',
			'fr' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-fr.js',
			'gl' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-gl.js',
			'he' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-he.js',
			'hi' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-hi.js',
			'hr' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-hr.js',
			'hu' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-hu.js',
			'hy' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-hy.js',
			'id' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-id.js',
			'is' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-is.js',
			'it' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-it.js',
			'ja' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ja.js',
			'ka' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ka.js',
			'kk' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-kk.js',
			'km' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-km.js',
			'ko' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ko.js',
			'lb' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-lb.js',
			'lt' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-lt.js',
			'lv' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-lv.js',
			'mk' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-mk.js',
			'ml' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ml.js',
			'ms' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ms.js',
			'nl' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-nl.js',
			'no' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-no.js',
			'pl' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-pl.js',
			'pt' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-pt.js',
			'pt-br' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-pt-BR.js',
			'rm' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-rm.js',
			'ro' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ro.js',
			'ru' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ru.js',
			'sk' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-sk.js',
			'sl' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-sl.js',
			'sq' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-sq.js',
			'sr-sr' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-sr-SR.js',
			'sr' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-sr.js',
			'sv' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-sv.js',
			'ta' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ta.js',
			'th' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-th.js',
			'tr' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-tr.js',
			'uk' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-uk.js',
			'vi' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-vi.js',
			'zh-cn' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-zh-CN.js',
			'zh-hk' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-zh-HK.js',
			'zh-tw' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-zh-TW.js',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.dialog' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.dialog.js',
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
			'default' => 'resources/lib/jquery.ui/themes/default/jquery.ui.dialog.css',
			'vector' => 'resources/src/jquery.ui-themes/vector/jquery.ui.dialog.css',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.progressbar' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.progressbar.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget' ),
		'skinStyles' => array(
			'default' => 'resources/lib/jquery.ui/themes/default/jquery.ui.progressbar.css',
			'vector' => 'resources/src/jquery.ui-themes/vector/jquery.ui.progressbar.css',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.slider' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.slider.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget', 'jquery.ui.mouse' ),
		'skinStyles' => array(
			'default' => 'resources/lib/jquery.ui/themes/default/jquery.ui.slider.css',
			'vector' => 'resources/src/jquery.ui-themes/vector/jquery.ui.slider.css',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.tabs' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.tabs.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget' ),
		'skinStyles' => array(
			'default' => 'resources/lib/jquery.ui/themes/default/jquery.ui.tabs.css',
			'vector' => 'resources/src/jquery.ui-themes/vector/jquery.ui.tabs.css',
		),
		'group' => 'jquery.ui',
	),
	// Effects
	'jquery.effects.core' => array(
		'scripts' => 'resources/lib/jquery.effects/jquery.effects.core.js',
		'group' => 'jquery.ui',
	),
	'jquery.effects.blind' => array(
		'scripts' => 'resources/lib/jquery.effects/jquery.effects.blind.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.bounce' => array(
		'scripts' => 'resources/lib/jquery.effects/jquery.effects.bounce.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.clip' => array(
		'scripts' => 'resources/lib/jquery.effects/jquery.effects.clip.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.drop' => array(
		'scripts' => 'resources/lib/jquery.effects/jquery.effects.drop.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.explode' => array(
		'scripts' => 'resources/lib/jquery.effects/jquery.effects.explode.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.fade' => array(
		'scripts' => 'resources/lib/jquery.effects/jquery.effects.fade.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.fold' => array(
		'scripts' => 'resources/lib/jquery.effects/jquery.effects.fold.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.highlight' => array(
		'scripts' => 'resources/lib/jquery.effects/jquery.effects.highlight.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.pulsate' => array(
		'scripts' => 'resources/lib/jquery.effects/jquery.effects.pulsate.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.scale' => array(
		'scripts' => 'resources/lib/jquery.effects/jquery.effects.scale.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.shake' => array(
		'scripts' => 'resources/lib/jquery.effects/jquery.effects.shake.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.slide' => array(
		'scripts' => 'resources/lib/jquery.effects/jquery.effects.slide.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.transfer' => array(
		'scripts' => 'resources/lib/jquery.effects/jquery.effects.transfer.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),

	/* Moment.js */

	'moment' => array(
		'scripts' => 'resources/lib/moment/moment.js',
		'languageScripts' => array(
			'ar-ma' => 'resources/lib/moment/lang/ar-ma.js',
			'ar' => 'resources/lib/moment/lang/ar.js',
			'bg' => 'resources/lib/moment/lang/bg.js',
			'br' => 'resources/lib/moment/lang/br.js',
			'bs' => 'resources/lib/moment/lang/bs.js',
			'ca' => 'resources/lib/moment/lang/ca.js',
			'cs' => 'resources/lib/moment/lang/cs.js',
			'cv' => 'resources/lib/moment/lang/cv.js',
			'cy' => 'resources/lib/moment/lang/cy.js',
			'da' => 'resources/lib/moment/lang/da.js',
			'de' => 'resources/lib/moment/lang/de.js',
			'el' => 'resources/lib/moment/lang/el.js',
			'en-au' => 'resources/lib/moment/lang/en-au.js',
			'en-ca' => 'resources/lib/moment/lang/en-ca.js',
			'en-gb' => 'resources/lib/moment/lang/en-gb.js',
			'eo' => 'resources/lib/moment/lang/eo.js',
			'es' => 'resources/lib/moment/lang/es.js',
			'et' => 'resources/lib/moment/lang/et.js',
			'eu' => 'resources/lib/moment/lang/eu.js',
			'fa' => 'resources/lib/moment/lang/fa.js',
			'fi' => 'resources/lib/moment/lang/fi.js',
			'fo' => 'resources/lib/moment/lang/fo.js',
			'fr-ca' => 'resources/lib/moment/lang/fr-ca.js',
			'fr' => 'resources/lib/moment/lang/fr.js',
			'gl' => 'resources/lib/moment/lang/gl.js',
			'he' => 'resources/lib/moment/lang/he.js',
			'hi' => 'resources/lib/moment/lang/hi.js',
			'hr' => 'resources/lib/moment/lang/hr.js',
			'hu' => 'resources/lib/moment/lang/hu.js',
			'hy-am' => 'resources/lib/moment/lang/hy-am.js',
			'id' => 'resources/lib/moment/lang/id.js',
			'is' => 'resources/lib/moment/lang/is.js',
			'it' => 'resources/lib/moment/lang/it.js',
			'ja' => 'resources/lib/moment/lang/ja.js',
			'ka' => 'resources/lib/moment/lang/ka.js',
			'ko' => 'resources/lib/moment/lang/ko.js',
			'lt' => 'resources/lib/moment/lang/lt.js',
			'lv' => 'resources/lib/moment/lang/lv.js',
			'mk' => 'resources/lib/moment/lang/mk.js',
			'ml' => 'resources/lib/moment/lang/ml.js',
			'mr' => 'resources/lib/moment/lang/mr.js',
			'ms-my' => 'resources/lib/moment/lang/ms-my.js',
			'nb' => 'resources/lib/moment/lang/nb.js',
			'ne' => 'resources/lib/moment/lang/ne.js',
			'nl' => 'resources/lib/moment/lang/nl.js',
			'nn' => 'resources/lib/moment/lang/nn.js',
			'pl' => 'resources/lib/moment/lang/pl.js',
			'pt-br' => 'resources/lib/moment/lang/pt-br.js',
			'pt' => 'resources/lib/moment/lang/pt.js',
			'ro' => 'resources/lib/moment/lang/ro.js',
			'rs' => 'resources/lib/moment/lang/rs.js',
			'ru' => 'resources/lib/moment/lang/ru.js',
			'sk' => 'resources/lib/moment/lang/sk.js',
			'sl' => 'resources/lib/moment/lang/sl.js',
			'sq' => 'resources/lib/moment/lang/sq.js',
			'sv' => 'resources/lib/moment/lang/sv.js',
			'ta' => 'resources/lib/moment/lang/ta.js',
			'th' => 'resources/lib/moment/lang/th.js',
			'tl-ph' => 'resources/lib/moment/lang/tl-ph.js',
			'tr' => 'resources/lib/moment/lang/tr.js',
			'tzm-la' => 'resources/lib/moment/lang/tzm-la.js',
			'tzm' => 'resources/lib/moment/lang/tzm.js',
			'uk' => 'resources/lib/moment/lang/uk.js',
			'uz' => 'resources/lib/moment/lang/uz.js',
			'vn' => 'resources/lib/moment/lang/vn.js',
			'zh-cn' => 'resources/lib/moment/lang/zh-cn.js',
			'zh-tw' => 'resources/lib/moment/lang/zh-tw.js',
		),
	),

	/* MediaWiki */

	'mediawiki' => array(
		'scripts' => 'resources/src/mediawiki/mediawiki.js',
		'debugScripts' => 'resources/src/mediawiki/mediawiki.log.js',
		'debugRaw' => false,
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.api' => array(
		'scripts' => 'resources/src/mediawiki.api/mediawiki.api.js',
		'dependencies' => 'mediawiki.util',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.api.category' => array(
		'scripts' => 'resources/src/mediawiki.api/mediawiki.api.category.js',
		'dependencies' => array(
			'mediawiki.api',
			'mediawiki.Title',
		),
	),
	'mediawiki.api.edit' => array(
		'scripts' => 'resources/src/mediawiki.api/mediawiki.api.edit.js',
		'dependencies' => array(
			'mediawiki.api',
			'mediawiki.Title',
			'user.tokens',
		),
	),
	'mediawiki.api.login' => array(
		'scripts' => 'resources/src/mediawiki.api/mediawiki.api.login.js',
		'dependencies' => array(
			'mediawiki.api',
		),
	),
	'mediawiki.api.parse' => array(
		'scripts' => 'resources/src/mediawiki.api/mediawiki.api.parse.js',
		'dependencies' => 'mediawiki.api',
	),
	'mediawiki.api.watch' => array(
		'scripts' => 'resources/src/mediawiki.api/mediawiki.api.watch.js',
		'dependencies' => array(
			'mediawiki.api',
			'user.tokens',
		),
	),
	'mediawiki.debug' => array(
		'scripts' => array(
			'resources/src/mediawiki/mediawiki.debug.js',
			'resources/src/mediawiki/mediawiki.debug.profile.js'
		),
		'styles' => array(
			'resources/src/mediawiki/mediawiki.debug.less',
			'resources/src/mediawiki/mediawiki.debug.profile.css'
		),
		'dependencies' => array(
			'jquery.footHovzer',
			'jquery.tipsy',
		),
		'position' => 'bottom',
	),
	'mediawiki.debug.init' => array(
		'scripts' => 'resources/src/mediawiki/mediawiki.debug.init.js',
		'dependencies' => 'mediawiki.debug',
		// Uses a custom mw.config variable that is set in debughtml,
		// must be loaded on the bottom
		'position' => 'bottom',
	),
	'mediawiki.feedback' => array(
		'scripts' => 'resources/src/mediawiki/mediawiki.feedback.js',
		'styles' => 'resources/src/mediawiki/mediawiki.feedback.css',
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
	'mediawiki.hidpi' => array(
		'scripts' => 'resources/src/mediawiki/mediawiki.hidpi.js',
		'dependencies' => array(
			'jquery.hidpi',
		),
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.hlist' => array(
		'styles' => 'resources/src/mediawiki/mediawiki.hlist.css',
		'scripts' => 'resources/src/mediawiki/mediawiki.hlist.js',
		'dependencies' => array(
			'jquery.client',
		),
	),
	'mediawiki.htmlform' => array(
		'scripts' => 'resources/src/mediawiki/mediawiki.htmlform.js',
		'messages' => array( 'htmlform-chosen-placeholder' ),
	),
	'mediawiki.icon' => array(
		'styles' => 'resources/src/mediawiki/mediawiki.icon.less',
	),
	'mediawiki.inspect' => array(
		'scripts' => 'resources/src/mediawiki/mediawiki.inspect.js',
		'dependencies' => array(
			'jquery.byteLength',
			'jquery.json',
		),
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.notification' => array(
		'styles' => array(
			'resources/src/mediawiki/mediawiki.notification.css',
			'resources/src/mediawiki/mediawiki.notification.hideForPrint.css'
				=> array( 'media' => 'print' ),
		),
		'scripts' => 'resources/src/mediawiki/mediawiki.notification.js',
		'dependencies' => array(
			'mediawiki.page.startup',
		),
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.notify' => array(
		'scripts' => 'resources/src/mediawiki/mediawiki.notify.js',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.searchSuggest' => array(
		'scripts' => 'resources/src/mediawiki/mediawiki.searchSuggest.js',
		'styles' => 'resources/src/mediawiki/mediawiki.searchSuggest.css',
		'messages' => array(
			'searchsuggest-search',
			'searchsuggest-containing',
		),
		'dependencies' => array(
			'jquery.client',
			'jquery.placeholder',
			'jquery.suggestions',
			'mediawiki.api',
		),
	),
	'mediawiki.Title' => array(
		'scripts' => 'resources/src/mediawiki/mediawiki.Title.js',
		'dependencies' => array(
			'jquery.byteLength',
			'mediawiki.util',
		),
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.toc' => array(
		'scripts' => 'resources/src/mediawiki/mediawiki.toc.js',
		'dependencies' => array(
			'jquery.cookie',
		),
		'messages' => array( 'showtoc', 'hidetoc' ),
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.Uri' => array(
		'scripts' => 'resources/src/mediawiki/mediawiki.Uri.js',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.user' => array(
		'scripts' => 'resources/src/mediawiki/mediawiki.user.js',
		'dependencies' => array(
			'jquery.cookie',
			'mediawiki.api',
			'user.options',
			'user.tokens',
		),
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.util' => array(
		'scripts' => 'resources/src/mediawiki/mediawiki.util.js',
		'dependencies' => array(
			'jquery.client',
			'jquery.mwExtension',
			'mediawiki.notify',
			'mediawiki.toc',
		),
		'position' => 'top', // For $wgPreloadJavaScriptMwUtil
		'targets' => array( 'desktop', 'mobile' ),
	),

	/* MediaWiki Action */

	'mediawiki.action.edit' => array(
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.edit.js',
		'dependencies' => array(
			'mediawiki.action.edit.styles',
			'jquery.textSelection',
			'jquery.byteLimit',
		),
		'position' => 'top',
	),
	'mediawiki.action.edit.styles' => array(
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.edit.styles.css',
		'position' => 'top',
	),
	'mediawiki.action.edit.collapsibleFooter' => array(
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.edit.collapsibleFooter.js',
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.edit.collapsibleFooter.css',
		'dependencies' => array(
			'jquery.makeCollapsible',
			'jquery.cookie',
			'mediawiki.icon',
		),
	),
	'mediawiki.action.edit.preview' => array(
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.edit.preview.js',
		'dependencies' => array(
			'jquery.form',
			'jquery.spinner',
			'mediawiki.action.history.diff',
		),
	),
	'mediawiki.action.history' => array(
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.history.js',
		'group' => 'mediawiki.action.history',
	),
	'mediawiki.action.history.diff' => array(
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.history.diff.css',
		'group' => 'mediawiki.action.history',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.action.view.dblClickEdit' => array(
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.view.dblClickEdit.js',
		'dependencies' => array(
			'mediawiki.util',
			'mediawiki.page.startup',
		),
	),
	'mediawiki.action.view.metadata' => array(
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.view.metadata.js',
		'messages' => array(
			'metadata-expand',
			'metadata-collapse',
		),
	),
	'mediawiki.action.view.postEdit' => array(
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.view.postEdit.js',
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.view.postEdit.css',
		'dependencies' => array(
			'jquery.cookie',
			'mediawiki.jqueryMsg'
		),
		'messages' => array(
			'postedit-confirmation',
		),
	),
	'mediawiki.action.view.redirectToFragment' => array(
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.view.redirectToFragment.js',
		'dependencies' => array(
			'jquery.client',
		),
		'position' => 'top',
	),
	'mediawiki.action.view.rightClickEdit' => array(
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.view.rightClickEdit.js',
	),
	'mediawiki.action.edit.editWarning' => array(
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.edit.editWarning.js',
		'dependencies' => array(
			'mediawiki.jqueryMsg'
		),
		'messages' => array(
			'editwarning-warning',
			'prefs-editing'
		),
	),
	// Alias for backwards compatibility
	'mediawiki.action.watch.ajax' => array(
		'dependencies' => 'mediawiki.page.watch.ajax'
	),

	/* MediaWiki Language */

	'mediawiki.language' => array(
		'scripts' => array(
			'resources/src/mediawiki.language/mediawiki.language.js',
			'resources/src/mediawiki.language/mediawiki.language.numbers.js'
		),
		'languageScripts' => array(
			'bs' => 'resources/src/mediawiki.language/languages/bs.js',
			'dsb' => 'resources/src/mediawiki.language/languages/dsb.js',
			'fi' => 'resources/src/mediawiki.language/languages/fi.js',
			'ga' => 'resources/src/mediawiki.language/languages/ga.js',
			'he' => 'resources/src/mediawiki.language/languages/he.js',
			'hsb' => 'resources/src/mediawiki.language/languages/hsb.js',
			'hu' => 'resources/src/mediawiki.language/languages/hu.js',
			'hy' => 'resources/src/mediawiki.language/languages/hy.js',
			'la' => 'resources/src/mediawiki.language/languages/la.js',
			'os' => 'resources/src/mediawiki.language/languages/os.js',
			'ru' => 'resources/src/mediawiki.language/languages/ru.js',
			'sl' => 'resources/src/mediawiki.language/languages/sl.js',
			'uk' => 'resources/src/mediawiki.language/languages/uk.js',
		),
		'dependencies' => array(
				'mediawiki.language.data',
				'mediawiki.cldr',
			),
		'targets' => array( 'desktop', 'mobile' ),
	),

	'mediawiki.cldr' => array(
		'scripts' => 'resources/src/mediawiki.language/mediawiki.cldr.js',
		'dependencies' => array(
			'mediawiki.libs.pluralruleparser',
		),
		'targets' => array( 'desktop', 'mobile' ),
	),

	'mediawiki.libs.pluralruleparser' => array(
		'scripts' => 'resources/src/mediawiki.libs/CLDRPluralRuleParser.js',
		'targets' => array( 'desktop', 'mobile' ),
	),

	'mediawiki.language.init' => array(
		'scripts' => 'resources/src/mediawiki.language/mediawiki.language.init.js',
		'targets' => array( 'desktop', 'mobile' ),
	),

	'mediawiki.jqueryMsg' => array(
		'scripts' => 'resources/src/mediawiki/mediawiki.jqueryMsg.js',
		'dependencies' => array(
			'mediawiki.util',
			'mediawiki.language',
		),
		'targets' => array( 'desktop', 'mobile' ),
	),

	'mediawiki.language.months' => array(
		'scripts' => 'resources/src/mediawiki.language/mediawiki.language.months.js',
		'dependencies' => 'mediawiki.language',
		'messages' => array_merge(
			Language::$mMonthMsgs,
			Language::$mMonthGenMsgs,
			Language::$mMonthAbbrevMsgs
		)
	),

	/* MediaWiki Libs */

	'mediawiki.libs.jpegmeta' => array(
		'scripts' => 'resources/src/mediawiki.libs/mediawiki.libs.jpegmeta.js',
	),

	/* MediaWiki Page */

	'mediawiki.page.gallery' => array(
		'scripts' => 'resources/src/mediawiki.page/mediawiki.page.gallery.js',
	),
	'mediawiki.page.ready' => array(
		'scripts' => 'resources/src/mediawiki.page/mediawiki.page.ready.js',
		'dependencies' => array(
			'jquery.checkboxShiftClick',
			'jquery.makeCollapsible',
			'jquery.placeholder',
			'jquery.mw-jump',
			'mediawiki.util',
		),
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.page.startup' => array(
		'scripts' => 'resources/src/mediawiki.page/mediawiki.page.startup.js',
		'dependencies' => array(
			'mediawiki.util',
		),
		'position' => 'top',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.page.patrol.ajax' => array(
		'scripts' => 'resources/src/mediawiki.page/mediawiki.page.patrol.ajax.js',
		'dependencies' => array(
			'mediawiki.page.startup',
			'mediawiki.api',
			'mediawiki.util',
			'mediawiki.Title',
			'mediawiki.notify',
			'jquery.spinner',
			'user.tokens'
		),
		'messages' => array(
			'markedaspatrollednotify',
			'markedaspatrollederrornotify',
			'markedaspatrollederror-noautopatrol'
		),
	),
	'mediawiki.page.watch.ajax' => array(
		'scripts' => 'resources/src/mediawiki.page/mediawiki.page.watch.ajax.js',
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
	'mediawiki.page.image.pagination' => array(
		'scripts' => 'resources/src/mediawiki.page/mediawiki.page.image.pagination.js',
		'dependencies' => array(
			'mediawiki.Uri',
			'mediawiki.util',
			'jquery.spinner',
		)
	),

	/* MediaWiki Special pages */

	'mediawiki.special' => array(
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.js',
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.css',
		'skinStyles' => array(
			'vector' => 'skins/vector/special.less',
		),
	),
	'mediawiki.special.block' => array(
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.block.js',
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.block.css',
		'dependencies' => array(
			'mediawiki.util',
		),
	),
	'mediawiki.special.changeemail' => array(
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.changeemail.js',
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.changeemail.css',
		'dependencies' => array(
			'mediawiki.util',
		),
		'messages' => array(
			'email-address-validity-valid',
			'email-address-validity-invalid',
		),
	),
	'mediawiki.special.changeslist' => array(
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.changeslist.css',
	),
	'mediawiki.special.changeslist.legend' => array(
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.changeslist.legend.css',
	),
	'mediawiki.special.changeslist.legend.js' => array(
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.changeslist.legend.js',
		'dependencies' => array(
			'jquery.makeCollapsible',
			'jquery.cookie',
		),
	),
	'mediawiki.special.changeslist.enhanced' => array(
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.changeslist.enhanced.css',
	),
	'mediawiki.special.movePage' => array(
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.movePage.js',
		'dependencies' => 'jquery.byteLimit',
	),
	'mediawiki.special.pagesWithProp' => array(
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.pagesWithProp.css',
	),
	'mediawiki.special.preferences' => array(
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.preferences.js',
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.preferences.css',
		'position' => 'top',
		'skinStyles' => array(
			'vector' => 'skins/vector/special.preferences.less',
		),
		'messages' => array(
			'prefs-tabs-navigation-hint',
		),
		'dependencies' => array(
			'mediawiki.language',
		),
	),
	'mediawiki.special.recentchanges' => array(
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.recentchanges.js',
		'dependencies' => array( 'mediawiki.special' ),
		'position' => 'top',
	),
	'mediawiki.special.search' => array(
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.search.js',
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.search.css',
		'messages' => array(
			'powersearch-togglelabel',
			'powersearch-toggleall',
			'powersearch-togglenone',
		),
	),
	'mediawiki.special.undelete' => array(
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.undelete.js',
	),
	'mediawiki.special.upload' => array(
		// @todo merge in remainder of mediawiki.legacy.upload
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.upload.js',
		'messages' => array(
			'widthheight',
			'size-bytes',
			'size-kilobytes',
			'size-megabytes',
			'size-gigabytes',
			'largefileserver',
		),
		'dependencies' => array(
			'mediawiki.libs.jpegmeta',
			'mediawiki.util',
		),
	),
	'mediawiki.special.userlogin.common.styles' => array(
		'styles' => array(
			'resources/src/mediawiki.special/mediawiki.special.userlogin.common.css',
		),
		'position' => 'top',
	),
	'mediawiki.special.userlogin.signup.styles' => array(
		'styles' => array(
			'resources/src/mediawiki.special/mediawiki.special.userlogin.signup.css',
		),
		'position' => 'top',
	),
	'mediawiki.special.userlogin.login.styles' => array(
		'styles' => array(
			'resources/src/mediawiki.special/mediawiki.special.userlogin.login.css',
		),
		'position' => 'top',
	),
	'mediawiki.special.userlogin.common.js' => array(
		'scripts' => array(
			'resources/src/mediawiki.special/mediawiki.special.userlogin.common.js',
		),
		'messages' => array(
			'createacct-captcha',
			'createacct-imgcaptcha-ph',
		),
	),
	'mediawiki.special.userlogin.signup.js' => array(
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.userlogin.signup.js',
		'messages' => array(
			'createacct-error',
			'createacct-emailrequired',
			'noname',
			'userexists',
		),
		'dependencies' => array(
			'mediawiki.api',
			'mediawiki.jqueryMsg',
			'jquery.throttle-debounce',
		),
	),
	'mediawiki.special.javaScriptTest' => array(
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.javaScriptTest.js',
		'messages' => array_merge( Skin::getSkinNameMessages(), array(
			'colon-separator',
			'javascripttest-pagetext-skins',
		) ),
		'dependencies' => array( 'jquery.qunit' ),
		'position' => 'top',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.special.version' => array(
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.version.css',
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
		// Used in the web installer. Test it after modifying this definition!
		'scripts' => 'common/config.js',
		'styles' => array( 'common/config.css' ),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
		'dependencies' => 'mediawiki.legacy.wikibits',
	),
	'mediawiki.legacy.protect' => array(
		'scripts' => 'common/protect.js',
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
		'dependencies' => array(
			'jquery.byteLimit',
		),
		'position' => 'top',
	),
	'mediawiki.legacy.shared' => array(
		// Used in the web installer. Test it after modifying this definition!
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
			'jquery.spinner',
			'mediawiki.api',
			'mediawiki.Title',
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

	/* MediaWiki UI */

	'mediawiki.ui' => array(
		'skinStyles' => array(
			'default' => 'resources/src/mediawiki.ui/default.less',
			'vector' => 'resources/src/mediawiki.ui/vector.less',
		),
		'position' => 'top',
		'targets' => array( 'desktop', 'mobile' ),
	),
	// Lightweight module for button styles
	'mediawiki.ui.button' => array(
		'skinStyles' => array(
			'default' => 'resources/src/mediawiki.ui/components/default/buttons.less',
			'vector' => 'resources/src/mediawiki.ui/components/vector/buttons.less',
		),
		'position' => 'top',
		'targets' => array( 'desktop', 'mobile' ),
	),

	/* OOjs */
	// WARNING: OOjs and OOjs-UI are NOT COMPATIBLE with older browsers and
	// WILL BREAK if loaded in browsers that don't support ES5
	'oojs' => array(
		'scripts' => array(
			'resources/lib/oojs/oojs.js',
		),
		'targets' => array( 'desktop', 'mobile' ),
	),
	'oojs-ui' => array(
		'scripts' => array(
			'resources/lib/oojs-ui/oojs-ui.js',
		),
		'styles' => array(
			'resources/lib/oojs-ui/oojs-ui.svg.css',
		),
		'skinStyles' => array(
			'default' => 'resources/lib/oojs-ui/oojs-ui-apex.css',
			'minerva' => 'resources/lib/oojs-ui/oojs-ui-agora.css',
		),
		'messages' => array(
			'ooui-dialog-action-close',
			'ooui-outline-control-move-down',
			'ooui-outline-control-move-up',
			'ooui-outline-control-remove',
			'ooui-toolbar-more',
		),
		'dependencies' => array(
			'oojs',
		),
		'targets' => array( 'desktop', 'mobile' ),
	),
);
