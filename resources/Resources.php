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
	'startup' => array( 'class' => 'ResourceLoaderStartUpModule' ),

	// Scripts managed by the local wiki (stored in the MediaWiki namespace)
	'site' => array( 'class' => 'ResourceLoaderSiteModule' ),
	'noscript' => array(
		'class' => 'ResourceLoaderWikiModule',
		'styles' => array( 'MediaWiki:Noscript.css' ),
		'group' => 'noscript',
	),
	'filepage' => array(
		'class' => 'ResourceLoaderWikiModule',
		'styles' => array( 'MediaWiki:Filepage.css' ),
	),
	'user.groups' => array( 'class' => 'ResourceLoaderUserGroupsModule' ),

	// Scripts managed by the current user (stored in their user space)
	'user' => array( 'class' => 'ResourceLoaderUserModule' ),

	// Scripts generated based on the current user's preferences
	'user.cssprefs' => array( 'class' => 'ResourceLoaderUserCSSPrefsModule' ),

	// Populate mediawiki.user placeholders with information about the current user
	'user.defaults' => array( 'class' => 'ResourceLoaderUserDefaultsModule' ),
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
			'resources/src/mediawiki.skinning/elements.css' => array( 'media' => 'screen' ),
		),
	),
	'mediawiki.skinning.content' => array(
		'styles' => array(
			'resources/src/mediawiki.skinning/elements.css' => array( 'media' => 'screen' ),
			'resources/src/mediawiki.skinning/content.css' => array( 'media' => 'screen' ),
		),
	),
	'mediawiki.skinning.interface' => array(
		'class' => 'ResourceLoaderSkinModule',
		// Used in the web installer. Test it after modifying this definition!
		'styles' => array(
			'resources/src/mediawiki.skinning/elements.css' => array( 'media' => 'screen' ),
			'resources/src/mediawiki.skinning/content.css' => array( 'media' => 'screen' ),
			'resources/src/mediawiki.skinning/interface.css' => array( 'media' => 'screen' ),
		),
	),

	'mediawiki.skinning.content.parsoid' => array(
		// Style Parsoid HTML+RDFa output consistent with wikitext from PHP parser
		// with the interface.css styles; skinStyles should be used if your
		// skin over-rides common content styling.
		'skinStyles' => array(
			'default' => 'resources/src/mediawiki.skinning/content.parsoid.less',
		),
		'targets' => array( 'desktop', 'mobile' ),
	),

	'mediawiki.skinning.content.externallinks' => array(
		'styles' => array(
			'resources/src/mediawiki.skinning/content.externallinks.css' => array( 'media' => 'screen' ),
		),
	),

	/* jQuery */

	'jquery' => array(
		'scripts' => array(
			'resources/lib/jquery/jquery.js',
		),
		'raw' => true,
		'targets' => array( 'desktop', 'mobile' ),
	),

	/* jQuery Plugins */

	'jquery.accessKeyLabel' => array(
		'scripts' => 'resources/src/jquery/jquery.accessKeyLabel.js',
		'dependencies' => array(
			'jquery.client',
			'jquery.mwExtension',
		),
		'messages' => array( 'brackets', 'word-separator' ),
		'targets' => array( 'mobile', 'desktop' ),
	),
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
		'scripts' => 'resources/lib/jquery.client/jquery.client.js',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'jquery.color' => array(
		'scripts' => 'resources/src/jquery/jquery.color.js',
		'dependencies' => 'jquery.colorUtil',
	),
	'jquery.colorUtil' => array(
		'scripts' => 'resources/src/jquery/jquery.colorUtil.js',
	),
	'jquery.confirmable' => array(
		'scripts' => array(
			'resources/src/jquery/jquery.confirmable.js',
			'resources/src/jquery/jquery.confirmable.mediawiki.js',
		),
		'messages' => array(
			'confirmable-confirm',
			'confirmable-yes',
			'confirmable-no',
			'word-separator',
		),
		'styles' => 'resources/src/jquery/jquery.confirmable.css',
		'dependencies' => 'mediawiki.jqueryMsg',
	),
	// Use mediawiki.cookie in new code, rather than jquery.cookie.
	'jquery.cookie' => array(
		'scripts' => 'resources/lib/jquery/jquery.cookie.js',
		'targets' => array( 'desktop', 'mobile' ),
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
		'scripts' => 'resources/lib/qunitjs/qunit.js',
		'styles' => 'resources/lib/qunitjs/qunit.css',
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
		'dependencies' => 'json',
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
		'targets' => array( 'desktop', 'mobile' ),
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

	'jquery.ui.core' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.core.js',
		'skinStyles' => array(
			'default' => array(
				'resources/lib/jquery.ui/themes/smoothness/jquery.ui.core.css',
				'resources/lib/jquery.ui/themes/smoothness/jquery.ui.theme.css',
			),
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.accordion' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.accordion.js',
		'dependencies' => array(
			'jquery.ui.core',
			'jquery.ui.widget',
		),
		'skinStyles' => array(
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.accordion.css',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.autocomplete' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.autocomplete.js',
		'dependencies' => array(
			'jquery.ui.core',
			'jquery.ui.widget',
			'jquery.ui.position',
			'jquery.ui.menu',
		),
		'skinStyles' => array(
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.autocomplete.css',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.button' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.button.js',
		'dependencies' => array(
			'jquery.ui.core',
			'jquery.ui.widget',
		),
		'skinStyles' => array(
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.button.css',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.datepicker' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.datepicker.js',
		'dependencies' => 'jquery.ui.core',
		'skinStyles' => array(
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.datepicker.css',
		),
		'languageScripts' => array(
			'af' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-af.js',
			'ar' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ar.js',
			'ar-dz' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ar-DZ.js',
			'az' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-az.js',
			'bg' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-bg.js',
			'bs' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-bs.js',
			'ca' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ca.js',
			'cs' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-cs.js',
			'da' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-da.js',
			'de' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-de.js',
			'el' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-el.js',
			'en-au' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-en-AU.js',
			'en-gb' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-en-GB.js',
			'en-nz' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-en-NZ.js',
			'eo' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-eo.js',
			'es' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-es.js',
			'et' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-et.js',
			'eu' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-eu.js',
			'fa' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-fa.js',
			'fi' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-fi.js',
			'fo' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-fo.js',
			'fr' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-fr.js',
			'fr-ch' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-fr-CH.js',
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
			'nl-be' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-nl-BE.js',
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
			'tj' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-tj.js',
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
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.dialog.css',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.draggable' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.draggable.js',
		'dependencies' => array(
			'jquery.ui.core',
			'jquery.ui.mouse',
			'jquery.ui.widget',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.droppable' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.droppable.js',
		'dependencies' => array(
			'jquery.ui.core',
			'jquery.ui.mouse',
			'jquery.ui.widget',
			'jquery.ui.draggable',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.menu' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.menu.js',
		'dependencies' => array(
			'jquery.ui.core',
			'jquery.ui.widget',
			'jquery.ui.position',
		),
		'skinStyles' => array(
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.menu.css',
		),
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
	'jquery.ui.progressbar' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.progressbar.js',
		'dependencies' => array(
			'jquery.ui.core',
			'jquery.ui.widget',
		),
		'skinStyles' => array(
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.progressbar.css',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.resizable' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.resizable.js',
		'dependencies' => array(
			'jquery.ui.core',
			'jquery.ui.widget',
			'jquery.ui.mouse',
		),
		'skinStyles' => array(
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.resizable.css',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.selectable' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.selectable.js',
		'dependencies' => array(
			'jquery.ui.core',
			'jquery.ui.widget',
			'jquery.ui.mouse',
		),
		'skinStyles' => array(
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.selectable.css',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.slider' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.slider.js',
		'dependencies' => array(
			'jquery.ui.core',
			'jquery.ui.widget',
			'jquery.ui.mouse',
		),
		'skinStyles' => array(
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.slider.css',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.sortable' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.sortable.js',
		'dependencies' => array(
			'jquery.ui.core',
			'jquery.ui.mouse',
			'jquery.ui.widget',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.spinner' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.spinner.js',
		'dependencies' => array(
			'jquery.ui.core',
			'jquery.ui.widget',
			'jquery.ui.button',
		),
		'skinStyles' => array(
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.spinner.css',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.tabs' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.tabs.js',
		'dependencies' => array(
			'jquery.ui.core',
			'jquery.ui.widget',
		),
		'skinStyles' => array(
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.tabs.css',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.tooltip' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.tooltip.js',
		'dependencies' => array(
			'jquery.ui.core',
			'jquery.ui.widget',
			'jquery.ui.position',
		),
		'skinStyles' => array(
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.tooltip.css',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.widget' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.widget.js',
		'group' => 'jquery.ui',
	),
	// Effects
	'jquery.effects.core' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect.js',
		'group' => 'jquery.ui',
	),
	'jquery.effects.blind' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-blind.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.bounce' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-bounce.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.clip' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-clip.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.drop' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-drop.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.explode' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-explode.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.fade' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-fade.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.fold' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-fold.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.highlight' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-highlight.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.pulsate' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-pulsate.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.scale' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-scale.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.shake' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-shake.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.slide' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-slide.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.transfer' => array(
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-transfer.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),

	/* json2 */

	'json' => array(
		'scripts' => 'resources/lib/json2/json2.js',
		'targets' => array( 'desktop', 'mobile' ),
		'skipFunction' => 'resources/src/json-skip.js',
	),

	/* Moment.js */

	'moment' => array(
		'scripts' => 'resources/lib/moment/moment.js',
		'languageScripts' => array(
			'af' => 'resources/lib/moment/locale/af.js',
			'ar' => 'resources/lib/moment/locale/ar.js',
			'ar-ma' => 'resources/lib/moment/locale/ar-ma.js',
			'ar-sa' => 'resources/lib/moment/locale/ar-sa.js',
			'az' => 'resources/lib/moment/locale/az.js',
			'be' => 'resources/lib/moment/locale/be.js',
			'bg' => 'resources/lib/moment/locale/bg.js',
			'bn' => 'resources/lib/moment/locale/bn.js',
			'bo' => 'resources/lib/moment/locale/bo.js',
			'br' => 'resources/lib/moment/locale/br.js',
			'bs' => 'resources/lib/moment/locale/bs.js',
			'ca' => 'resources/lib/moment/locale/ca.js',
			'cs' => 'resources/lib/moment/locale/cs.js',
			'cv' => 'resources/lib/moment/locale/cv.js',
			'cy' => 'resources/lib/moment/locale/cy.js',
			'da' => 'resources/lib/moment/locale/da.js',
			'de' => 'resources/lib/moment/locale/de.js',
			'de-at' => 'resources/lib/moment/locale/de-at.js',
			'el' => 'resources/lib/moment/locale/el.js',
			'en-au' => 'resources/lib/moment/locale/en-au.js',
			'en-ca' => 'resources/lib/moment/locale/en-ca.js',
			'en-gb' => 'resources/lib/moment/locale/en-gb.js',
			'eo' => 'resources/lib/moment/locale/eo.js',
			'es' => 'resources/lib/moment/locale/es.js',
			'et' => 'resources/lib/moment/locale/et.js',
			'eu' => 'resources/lib/moment/locale/eu.js',
			'fa' => 'resources/lib/moment/locale/fa.js',
			'fi' => 'resources/lib/moment/locale/fi.js',
			'fo' => 'resources/lib/moment/locale/fo.js',
			'fr-ca' => 'resources/lib/moment/locale/fr-ca.js',
			'fr' => 'resources/lib/moment/locale/fr.js',
			'gl' => 'resources/lib/moment/locale/gl.js',
			'he' => 'resources/lib/moment/locale/he.js',
			'hi' => 'resources/lib/moment/locale/hi.js',
			'hr' => 'resources/lib/moment/locale/hr.js',
			'hu' => 'resources/lib/moment/locale/hu.js',
			'hy-am' => 'resources/lib/moment/locale/hy-am.js',
			'id' => 'resources/lib/moment/locale/id.js',
			'is' => 'resources/lib/moment/locale/is.js',
			'it' => 'resources/lib/moment/locale/it.js',
			'ja' => 'resources/lib/moment/locale/ja.js',
			'ka' => 'resources/lib/moment/locale/ka.js',
			'ko' => 'resources/lib/moment/locale/ko.js',
			'lt' => 'resources/lib/moment/locale/lt.js',
			'lv' => 'resources/lib/moment/locale/lv.js',
			'mk' => 'resources/lib/moment/locale/mk.js',
			'ml' => 'resources/lib/moment/locale/ml.js',
			'mr' => 'resources/lib/moment/locale/mr.js',
			'ms-my' => 'resources/lib/moment/locale/ms-my.js',
			'my' => 'resources/lib/moment/locale/my.js',
			'nb' => 'resources/lib/moment/locale/nb.js',
			'ne' => 'resources/lib/moment/locale/ne.js',
			'nl' => 'resources/lib/moment/locale/nl.js',
			'nn' => 'resources/lib/moment/locale/nn.js',
			'pl' => 'resources/lib/moment/locale/pl.js',
			'pt-br' => 'resources/lib/moment/locale/pt-br.js',
			'pt' => 'resources/lib/moment/locale/pt.js',
			'ro' => 'resources/lib/moment/locale/ro.js',
			'ru' => 'resources/lib/moment/locale/ru.js',
			'sk' => 'resources/lib/moment/locale/sk.js',
			'sl' => 'resources/lib/moment/locale/sl.js',
			'sq' => 'resources/lib/moment/locale/sq.js',
			'sr' => 'resources/lib/moment/locale/sr.js',
			'sr-ec' => 'resources/lib/moment/locale/sr-cyrl.js',
			'sv' => 'resources/lib/moment/locale/sv.js',
			'ta' => 'resources/lib/moment/locale/ta.js',
			'th' => 'resources/lib/moment/locale/th.js',
			'tl-ph' => 'resources/lib/moment/locale/tl-ph.js',
			'tr' => 'resources/lib/moment/locale/tr.js',
			'tzm' => 'resources/lib/moment/locale/tzm.js',
			'tzm-latn' => 'resources/lib/moment/locale/tzm-latn.js',
			'uk' => 'resources/lib/moment/locale/uk.js',
			'uz' => 'resources/lib/moment/locale/uz.js',
			'vi' => 'resources/lib/moment/locale/vi.js',
			'zh-cn' => 'resources/lib/moment/locale/zh-cn.js',
			'zh-tw' => 'resources/lib/moment/locale/zh-tw.js',
		),
		'targets' => array( 'desktop', 'mobile' ),
	),

	/* MediaWiki */

	'mediawiki' => array(
		// Keep maintenance/jsduck/eg-iframe.html in sync
		'scripts' => array(
			'resources/src/mediawiki/mediawiki.js',
			'resources/src/mediawiki/mediawiki.errorLogger.js',
			'resources/src/mediawiki/mediawiki.startUp.js',
		),
		'debugScripts' => 'resources/src/mediawiki/mediawiki.log.js',
		'raw' => true,
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.apihelp' => array(
		'styles' => 'resources/src/mediawiki/mediawiki.apihelp.css',
		'targets' => array( 'desktop' ),
		'dependencies' => 'mediawiki.hlist',
		'position' => 'top',
	),
	'mediawiki.template' => array(
		'scripts' => 'resources/src/mediawiki/mediawiki.template.js',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.template.mustache' => array(
		'scripts' => array(
			'resources/lib/mustache/mustache.js',
			'resources/src/mediawiki/mediawiki.template.mustache.js',
		),
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.apipretty' => array(
		'styles' => 'resources/src/mediawiki/mediawiki.apipretty.css',
		'targets' => array( 'desktop', 'mobile' ),
		'position' => 'top',
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
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.api.login' => array(
		'scripts' => 'resources/src/mediawiki.api/mediawiki.api.login.js',
		'dependencies' => 'mediawiki.api',
	),
	'mediawiki.api.options' => array(
		'scripts' => 'resources/src/mediawiki.api/mediawiki.api.options.js',
		'dependencies' => 'mediawiki.api',
		'targets' => array( 'desktop', 'mobile' ),
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
	'mediawiki.content.json' => array(
		'styles' => 'resources/src/mediawiki/mediawiki.content.json.css',
	),
	'mediawiki.confirmCloseWindow' => array(
		'scripts' => array(
			'resources/src/mediawiki/mediawiki.confirmCloseWindow.js',
		),
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.debug' => array(
		'scripts' => array(
			'resources/src/mediawiki/mediawiki.debug.js',
		),
		'styles' => array(
			'resources/src/mediawiki/mediawiki.debug.less',
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
			'mediawiki.messagePoster',
			'mediawiki.Title',
			'oojs-ui',
		),
		'messages' => array(
			'feedback-adding',
			'feedback-back',
			'feedback-bugcheck',
			'feedback-dialog-intro',
			'feedback-external-bug-report-button',
			'feedback-bugnew',
			'feedback-bugornote',
			'feedback-cancel',
			'feedback-close',
			'feedback-dialog-title',
			'feedback-error-title',
			'feedback-error1',
			'feedback-error2',
			'feedback-error3',
			'feedback-error4',
			'feedback-message',
			'feedback-subject',
			'feedback-submit',
			'feedback-terms',
			'feedback-termsofuse',
			'feedback-thanks',
			'feedback-thanks-title',
			'feedback-useragent'
		),
	),

	'mediawiki.filewarning' => array(
		'scripts' => 'resources/src/mediawiki/mediawiki.filewarning.js',
		'styles' => 'resources/src/mediawiki/mediawiki.filewarning.less',
		'dependencies' => array(
			'oojs-ui',
		),
	),

	'mediawiki.helplink' => array(
		'styles' => array(
			'resources/src/mediawiki/mediawiki.helplink.less',
		),
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.hidpi' => array(
		'scripts' => 'resources/src/mediawiki/mediawiki.hidpi.js',
		'dependencies' => 'jquery.hidpi',
		'skipFunction' => 'resources/src/mediawiki.hidpi-skip.js',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.hlist' => array(
		'styles' => 'resources/src/mediawiki/mediawiki.hlist.css',
		'scripts' => 'resources/src/mediawiki/mediawiki.hlist.js',
		'dependencies' => 'jquery.client',
	),
	'mediawiki.htmlform' => array(
		'scripts' => 'resources/src/mediawiki/mediawiki.htmlform.js',
		'dependencies' => array(
			'jquery.mwExtension',
			'jquery.byteLimit',
		),
		'messages' => array(
			'htmlform-chosen-placeholder',
			// @todo Load this message in content language
			'colon-separator',
		),
	),
	'mediawiki.icon' => array(
		'styles' => 'resources/src/mediawiki/mediawiki.icon.less',
	),
	'mediawiki.inspect' => array(
		'scripts' => 'resources/src/mediawiki/mediawiki.inspect.js',
		'dependencies' => array(
			'jquery.byteLength',
			'json',
		),
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.messagePoster' => array(
		'scripts' => array(
			'resources/src/mediawiki.messagePoster/mediawiki.messagePoster.factory.js',
			'resources/src/mediawiki.messagePoster/mediawiki.messagePoster.MessagePoster.js',
		),
		'dependencies' => array(
			'oojs',
			'mediawiki.api',
		),
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.messagePoster.wikitext' => array(
		'scripts' => array(
			'resources/src/mediawiki.messagePoster/mediawiki.messagePoster.WikitextMessagePoster.js',
		),
		'dependencies' => array(
			'mediawiki.api.edit',
			'mediawiki.messagePoster',
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
		'dependencies' => 'mediawiki.page.startup',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.notify' => array(
		'scripts' => 'resources/src/mediawiki/mediawiki.notify.js',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.pager.tablePager' => array(
		'styles' => 'resources/src/mediawiki/mediawiki.pager.tablePager.less',
		'position' => 'top',
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
			'jquery.getAttrs',
			'mediawiki.api',
		),
	),
	'mediawiki.sectionAnchor' => array(
		// Back-compat to hide it on cached pages (T18691; Ie9e334e973; 2015-03-17)
		'styles' => 'resources/src/mediawiki/mediawiki.sectionAnchor.css',
		'targets' => array( 'desktop', 'mobile' ),
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
		'dependencies' => 'jquery.cookie',
		'messages' => array( 'showtoc', 'hidetoc' ),
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.Uri' => array(
		'scripts' => 'resources/src/mediawiki/mediawiki.Uri.js',
		'dependencies' => 'mediawiki.util',
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
	'mediawiki.userSuggest' => array(
		'scripts' => 'resources/src/mediawiki/mediawiki.userSuggest.js',
		'dependencies' => array(
			'jquery.suggestions',
			'mediawiki.api'
		)
	),
	'mediawiki.util' => array(
		'scripts' => 'resources/src/mediawiki/mediawiki.util.js',
		'dependencies' => array(
			'jquery.accessKeyLabel',
			'jquery.mwExtension',
			'mediawiki.notify',
		),
		'position' => 'top', // For $wgPreloadJavaScriptMwUtil
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.cookie' => array(
		'scripts' => 'resources/src/mediawiki/mediawiki.cookie.js',
		'dependencies' => 'jquery.cookie',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.toolbar' => array(
		'class' => 'ResourceLoaderEditToolbarModule',
		'scripts' => 'resources/src/mediawiki.toolbar/toolbar.js',
		'styles' => 'resources/src/mediawiki.toolbar/toolbar.less',
		'position' => 'top',
	),

	/* MediaWiki Action */

	'mediawiki.action.edit' => array(
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.edit.js',
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.edit.css',
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
			'jquery.textSelection',
			'mediawiki.api',
			'mediawiki.action.history.diff',
			'mediawiki.util',
			'mediawiki.jqueryMsg',
		),
		'messages' => array(
			// Keep the uses message keys in sync with EditPage#setHeaders
			'creating',
			'editconflict',
			'editing',
			'editingcomment',
			'editingsection',
			'pagetitle',
			'otherlanguages',
			'tooltip-p-lang',
			'summary-preview',
			'subject-preview',
			'parentheses',
			'previewerrortext',
		),
	),
	'mediawiki.action.edit.stash' => array(
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.edit.stash.js',
		'dependencies' => array(
			'jquery.getAttrs',
			'mediawiki.api',
		),
	),
	'mediawiki.action.history' => array(
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.history.js',
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.history.css',
		'group' => 'mediawiki.action.history',
	),
	'mediawiki.action.history.diff' => array(
		'styles' => array(
			'resources/src/mediawiki.action/mediawiki.action.history.diff.css',
			'resources/src/mediawiki.action/mediawiki.action.history.diff.print.css' => array(
				'media' => 'print'
			),
		),
		'group' => 'mediawiki.action.history',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.action.view.dblClickEdit' => array(
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.view.dblClickEdit.js',
		'dependencies' => array(
			'mediawiki.util',
			'mediawiki.page.startup',
			'user.options',
		),
	),
	'mediawiki.action.view.metadata' => array(
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.view.metadata.css',
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.view.metadata.js',
		'messages' => array(
			'metadata-expand',
			'metadata-collapse',
		),
	),
	'mediawiki.action.view.categoryPage.styles' => array(
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.view.categoryPage.less',
		'targets' => array( 'desktop', 'mobile' )
	),
	'mediawiki.action.view.postEdit' => array(
		'templates' => array(
			'postEdit.html' => 'resources/src/mediawiki.action/templates/postEdit.html',
		),
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.view.postEdit.js',
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.view.postEdit.css',
		'dependencies' => array(
			'mediawiki.cookie',
			'mediawiki.jqueryMsg'
		),
		'messages' => array(
			'postedit-confirmation-created',
			'postedit-confirmation-restored',
			'postedit-confirmation-saved',
		),
	),
	'mediawiki.action.view.redirect' => array(
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.view.redirect.js',
		'dependencies' => 'jquery.client',
		'position' => 'top',
	),
	'mediawiki.action.view.redirectPage' => array(
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.view.redirectPage.css',
		'position' => 'top',
	),
	'mediawiki.action.view.rightClickEdit' => array(
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.view.rightClickEdit.js',
	),
	'mediawiki.action.edit.editWarning' => array(
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.edit.editWarning.js',
		'dependencies' => array(
			'jquery.textSelection',
			'mediawiki.jqueryMsg',
			'mediawiki.confirmCloseWindow',
			'user.options',
		),
		'messages' => array(
			'editwarning-warning',
			// editwarning-warning uses {{int:prefs-editing}}
			'prefs-editing'
		),
	),

	/* MediaWiki Language */

	'mediawiki.language' => array(
		'scripts' => array(
			'resources/src/mediawiki.language/mediawiki.language.js',
			'resources/src/mediawiki.language/mediawiki.language.numbers.js',
			'resources/src/mediawiki.language/mediawiki.language.fallback.js',
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
		'messages' => array(
			'and',
			'comma-separator',
			'word-separator'
		),
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

	'mediawiki.language.names' => array( 'class' => 'ResourceLoaderLanguageNamesModule' ),

	'mediawiki.language.specialCharacters' => array(
		'class' => 'ResourceLoaderSpecialCharacterDataModule'
	),

	/* MediaWiki Libs */

	'mediawiki.libs.jpegmeta' => array(
		'scripts' => 'resources/src/mediawiki.libs/mediawiki.libs.jpegmeta.js',
	),

	/* MediaWiki Page */

	'mediawiki.page.gallery' => array(
		'scripts' => 'resources/src/mediawiki.page/mediawiki.page.gallery.js',
		'dependencies' => array(
			'jquery.throttle-debounce',
		)
	),
	'mediawiki.page.ready' => array(
		'scripts' => 'resources/src/mediawiki.page/mediawiki.page.ready.js',
		'dependencies' => array(
			'jquery.accessKeyLabel',
			'jquery.checkboxShiftClick',
			'jquery.makeCollapsible',
			'jquery.placeholder',
			'jquery.mw-jump',
		),
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.page.startup' => array(
		'scripts' => 'resources/src/mediawiki.page/mediawiki.page.startup.js',
		'dependencies' => 'mediawiki.util',
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
			'mediawiki.api.watch',
			'mediawiki.notify',
			'mediawiki.page.startup',
			'mediawiki.util',
			'jquery.accessKeyLabel',
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
		),
	),

	/* MediaWiki Special pages */

	'mediawiki.special' => array(
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.js',
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.css',
	),
	'mediawiki.special.block' => array(
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.block.js',
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.block.css',
		'dependencies' => 'mediawiki.util',
	),
	'mediawiki.special.changeemail' => array(
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.changeemail.js',
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.changeemail.css',
		'dependencies' => 'mediawiki.util',
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
	'mediawiki.special.edittags' => array(
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.edittags.js',
		'dependencies' => array(
			'jquery.chosen',
		),
		'messages' => array(
			'tags-edit-chosen-placeholder',
			'tags-edit-chosen-no-results',
		),
	),
	'mediawiki.special.edittags.styles' => array(
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.edittags.css',
		'position' => 'top',
	),
	'mediawiki.special.import' => array(
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.import.js',
	),
	'mediawiki.special.movePage' => array(
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.movePage.js',
		'dependencies' => 'jquery.byteLimit',
	),
	'mediawiki.special.pageLanguage' => array(
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.pageLanguage.js',
	),
	'mediawiki.special.pagesWithProp' => array(
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.pagesWithProp.css',
	),
	'mediawiki.special.preferences' => array(
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.preferences.js',
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.preferences.css',
		'position' => 'top',
		'messages' => array(
			'prefs-tabs-navigation-hint',
			'prefswarning-warning',
			'saveprefs',
		),
		'dependencies' => array(
			'mediawiki.language',
			'mediawiki.confirmCloseWindow',
		),
	),
	'mediawiki.special.recentchanges' => array(
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.recentchanges.js',
		'dependencies' => 'mediawiki.special',
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
		'templates' => array(
			'thumbnail.html' => 'resources/src/mediawiki.special/templates/thumbnail.html',
		),
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.upload.js',
		'messages' => array(
			'widthheight',
			'size-bytes',
			'size-kilobytes',
			'size-megabytes',
			'size-gigabytes',
			'largefileserver',
			'editwarning-warning',
			// editwarning-warning uses {{int:prefs-editing}}
			'prefs-editing',
		),
		'dependencies' => array(
			'jquery.spinner',
			'mediawiki.jqueryMsg',
			'mediawiki.api',
			'mediawiki.libs.jpegmeta',
			'mediawiki.Title',
			'mediawiki.util',
			'mediawiki.confirmCloseWindow',
			'user.options',
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
	'mediawiki.special.unwatchedPages' => array(
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.unwatchedPages.js',
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.unwatchedPages.css',
		'messages' => array(
			'addedwatchtext-short',
			'removedwatchtext-short',
			'unwatch',
			'unwatching',
			'watch',
			'watcherrortext',
			'watching',
		),
		'dependencies' => array(
			'mediawiki.api',
			'mediawiki.api.watch',
			'mediawiki.notify',
			'mediawiki.Title',
			'mediawiki.util',
		),
	),
	'mediawiki.special.javaScriptTest' => array(
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.javaScriptTest.js',
		'messages' => array_merge( Skin::getSkinNameMessages(), array(
			'colon-separator',
			'javascripttest-pagetext-skins',
		) ),
		'dependencies' => 'mediawiki.Uri',
		'position' => 'top',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.special.version' => array(
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.version.css',
	),

	/* MediaWiki Installer */

	'mediawiki.legacy.config' => array(
		// Used in the web installer. Test it after modifying this definition!
		// These files are not actually loaded via ResourceLoader, so dependencies etc. won't work.
		'scripts' => 'mw-config/config.js',
		'styles' => 'mw-config/config.css',
	),

	/* MediaWiki Legacy */

	'mediawiki.legacy.ajax' => array(
		'scripts' => 'resources/src/mediawiki.legacy/ajax.js',
		'dependencies' => array(
			'mediawiki.util',
			'mediawiki.legacy.wikibits',
		),
		'position' => 'top',
	),
	'mediawiki.legacy.commonPrint' => array(
		'styles' => array(
			'resources/src/mediawiki.legacy/commonPrint.css' => array( 'media' => 'print' )
		),
	),
	'mediawiki.legacy.protect' => array(
		'scripts' => 'resources/src/mediawiki.legacy/protect.js',
		'dependencies' => 'jquery.byteLimit',
		'messages' => array( 'protect-unchain-permissions' )
	),
	'mediawiki.legacy.shared' => array(
		// Used in the web installer. Test it after modifying this definition!
		'styles' => array(
			'resources/src/mediawiki.legacy/shared.css' => array( 'media' => 'screen' )
		),
	),
	'mediawiki.legacy.oldshared' => array(
		'styles' => array(
			'resources/src/mediawiki.legacy/oldshared.css' => array( 'media' => 'screen' )
		),
	),
	'mediawiki.legacy.wikibits' => array(
		'scripts' => 'resources/src/mediawiki.legacy/wikibits.js',
		'dependencies' => 'mediawiki.util',
		'position' => 'top',
	),

	/* MediaWiki UI */

	'mediawiki.ui' => array(
		'skinStyles' => array(
			'default' => array(
				'resources/src/mediawiki.ui/default.less',
			),
		),
		'position' => 'top',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.ui.checkbox' => array(
		'skinStyles' => array(
			'default' => array(
				'resources/src/mediawiki.ui/components/checkbox.less',
			),
		),
		'position' => 'top',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.ui.radio' => array(
		'skinStyles' => array(
			'default' => array(
				'resources/src/mediawiki.ui/components/radio.less',
			),
		),
		'position' => 'top',
		'targets' => array( 'desktop', 'mobile' ),
	),
	// Lightweight module for anchor styles
	'mediawiki.ui.anchor' => array(
		'skinStyles' => array(
			'default' => array(
				'resources/src/mediawiki.ui/components/anchors.less',
			),
		),
		'position' => 'top',
		'targets' => array( 'desktop', 'mobile' ),
	),
	// Lightweight module for button styles
	'mediawiki.ui.button' => array(
		'skinStyles' => array(
			'default' => array(
				'resources/src/mediawiki.ui/components/buttons.less',
			),
		),
		'position' => 'top',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.ui.input' => array(
		'skinStyles' => array(
			'default' => array(
				'resources/src/mediawiki.ui/components/inputs.less',
			),
		),
		'position' => 'top',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.ui.icon' => array(
		'skinStyles' => array(
			'default' => array(
				'resources/src/mediawiki.ui/components/icons.less',
			),
		),
		'position' => 'top',
		'targets' => array( 'desktop', 'mobile' ),
	),
	// Lightweight module for text styles
	'mediawiki.ui.text' => array(
		'skinStyles' => array(
			'default' => array(
				'resources/src/mediawiki.ui/components/text.less',
			),
		),
		'position' => 'top',
		'targets' => array( 'desktop', 'mobile' ),
	),

	/* es5-shim */
	'es5-shim' => array(
		'scripts' => array(
			'resources/lib/es5-shim/es5-shim.js',
			'resources/src/polyfill-object-create.js',
		),
		'targets' => array( 'desktop', 'mobile' ),
		'skipFunction' => 'resources/src/es5-skip.js',
	),

	/* OOjs */
	'oojs' => array(
		'scripts' => array(
			'resources/lib/oojs/oojs.jquery.js',
		),
		'targets' => array( 'desktop', 'mobile' ),
		'dependencies' => array(
			'es5-shim',
			'json',
		),
	),

	/* OOjs UI */
	// WARNING: OOjs-UI is NOT TESTED with older browsers and is likely to break
	// if loaded in browsers that don't support ES5
	'oojs-ui' => array(
		'scripts' => array(
			'resources/lib/oojs-ui/oojs-ui.js',
		),
		'skinScripts' => array(
			'default' => 'resources/lib/oojs-ui/oojs-ui-mediawiki.js',
		),
		'dependencies' => array(
			'es5-shim',
			'oojs',
			'oojs-ui.styles',
		),
		'messages' => array(
			'ooui-dialog-message-accept',
			'ooui-dialog-message-reject',
			'ooui-dialog-process-continue',
			'ooui-dialog-process-dismiss',
			'ooui-dialog-process-error',
			'ooui-dialog-process-retry',
			'ooui-outline-control-move-down',
			'ooui-outline-control-move-up',
			'ooui-outline-control-remove',
			'ooui-toolbar-more',
			'ooui-toolgroup-collapse',
			'ooui-toolgroup-expand',
		),
		'targets' => array( 'desktop', 'mobile' ),
	),

	'oojs-ui.styles' => array(
		'position' => 'top',
		'skinStyles' => array(
			'default' => 'resources/lib/oojs-ui/oojs-ui-mediawiki.css',
		),
		'targets' => array( 'desktop', 'mobile' ),
	),

);
