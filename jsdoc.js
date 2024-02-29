'use strict';
module.exports = {
	opts: {
		destination: 'docs/js',
		package: 'resources/package.json',
		pages: {
			modules: {
				longname: 'Modules',
				readme: 'resources/src/modules.md'
			},
			namespaces: {
				depth: 1,
				longname: 'Globals',
				readme: 'resources/src/README.md'
			}
		},
		pedantic: true,
		readme: 'resources/README.md',
		recurse: true,
		template: 'node_modules/jsdoc-wmf-theme'
	},
	plugins: [
		'node_modules/jsdoc-wmf-theme/plugins/allow-dots-in-modules',
		'plugins/markdown',
		'node_modules/jsdoc-wmf-theme/plugins/summarize',
		'node_modules/jsdoc-wmf-theme/plugins/betterlinks'
	],
	source: {
		include: [
			'resources/src/'
		],
		exclude: [
		]
	},
	templates: {
		cleverLinks: true,
		default: {
			useLongnameInNav: true
		},
		wmf: {
			siteMap: {
				sections: true
			},
			hideSections: [ 'Classes', 'Events' ],
			repository: 'https://gerrit.wikimedia.org/g/mediawiki/core/',
			linkMap: {
				Array: 'https://developer.mozilla.org/docs/Web/JavaScript/Reference/Global_Objects/Array',
				Blob: 'https://developer.mozilla.org/docs/Web/API/Blob',
				boolean: 'https://developer.mozilla.org/docs/Web/JavaScript/Reference/Global_Objects/Boolean',
				CSSStyleSheet: 'https://developer.mozilla.org/docs/Web/API/CSSStyleSheet',
				Date: 'https://developer.mozilla.org/docs/Web/JavaScript/Reference/Global_Objects/Date',
				Event: 'https://developer.mozilla.org/docs/Web/API/Event',
				File: 'https://developer.mozilla.org/docs/Web/API/File',
				Function: 'https://developer.mozilla.org/docs/Web/JavaScript/Reference/Global_Objects/Function',
				HTMLElement: 'https://developer.mozilla.org/docs/Web/API/HTMLElement',
				HTMLInputElement: 'https://developer.mozilla.org/docs/Web/API/HTMLInputElement',
				HTMLStyleElement: 'https://developer.mozilla.org/docs/Web/API/HTMLStyleElement',
				'JSON.parse': 'https://developer.mozilla.org/docs/Web/JavaScript/Reference/Global_Objects/JSON/parse',
				jQuery: 'https://api.jquery.com/jQuery/',
				'jQuery.fn': 'https://api.jquery.com/jQuery/',
				'jQuery.Deferred': 'https://api.jquery.com/Types/#Deferred',
				'jQuery.Event': 'https://api.jquery.com/Types/#Event',
				'jQuery.Promise': 'https://api.jquery.com/Types/#Promise',
				Node: 'https://developer.mozilla.org/docs/Web/API/Node',
				number: 'https://developer.mozilla.org/docs/Web/JavaScript/Reference/Global_Objects/Number',
				Object: 'https://developer.mozilla.org/docs/Web/JavaScript/Reference/Global_Objects/Object',
				'OO.EventEmitter': 'https://doc.wikimedia.org/oojs/master/OO.EventEmitter.html',
				'OO.Registry': 'https://doc.wikimedia.org/oojs/master/OO.Registry.html',
				'OO.ui.mixin.ClippableElement': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.mixin.ClippableElement.html',
				'OO.ui.mixin.FlaggedElement': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.mixin.FlaggedElement.html',
				'OO.ui.mixin.FloatableElement': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.mixin.FloatableElement.html',
				'OO.ui.mixin.GroupElement': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.mixin.GroupElement.html',
				'OO.ui.mixin.IconElement': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.mixin.IconElement.html',
				'OO.ui.mixin.IndicatorElement': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.mixin.IndicatorElement.html',
				'OO.ui.mixin.LookupElement': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.mixin.LookupElement.html',
				'OO.ui.mixin.PendingElement': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.mixin.PendingElement.html',
				'OO.ui.mixin.RequestManager': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.mixin.RequestManager.html',
				'OO.ui.mixin.TabIndexedElement': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.mixin.TabIndexedElement.html',
				'OO.ui.ActionFieldLayout': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.ActionFieldLayout.html',
				'OO.ui.ButtonGroupWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.ButtonGroupWidget.html',
				'OO.ui.ButtonOptionWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.ButtonOptionWidget.html',
				'OO.ui.ButtonMenuSelectWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.ButtonMenuSelectWidget.html',
				'OO.ui.BookletLayout': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.BookletLayout.html',
				'OO.ui.ButtonWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.ButtonWidget.html',
				'OO.ui.CheckboxInputWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.CheckboxInputWidget',
				'OO.ui.CopyTextLayout': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.CopyTextLayout.html',
				'OO.ui.DropdownInputWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.DropdownInputWidget.html',
				'OO.ui.DropdownWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.OO.ui.DropdownWidget.html',
				'OO.ui.FieldLayout': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.FieldLayout.html',
				'OO.ui.FormLayout': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.FormLayout.html',
				'OO.ui.InputWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.InputWidget.html',
				'OO.ui.MenuOptionWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.MenuOptionWidget.html',
				'OO.ui.MenuSectionOptionWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.MenuSectionOptionWidget.html',
				'OO.ui.MenuSelectWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.MenuSelectWidget.html',
				'OO.ui.MenuTagMultiselectWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.MenuTagMultiselectWidget.html',
				'OO.ui.MessageDialog': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.MessageDialog.html',
				'OO.ui.OptionWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.OptionWidget.html',
				'OO.ui.PopupButtonWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.PopupButtonWidget.html',
				'OO.ui.PopupWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.PopupWidget.html',
				'OO.ui.PageLayout': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.PageLayout.html',
				'OO.ui.ProcessDialog': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.ProcessDialog.html',
				'OO.ui.SearchWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.SearchWidget.html',
				'OO.ui.SearchInputWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.SearchInputWidget.html',
				'OO.ui.SelectFileInputWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.SelectFileInputWidget.html',
				'OO.ui.TagItemWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.TagItemWidget.html',
				'OO.ui.TagMultiselectWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.TagMultiselectWidget.html',
				'OO.ui.TextInputWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.TextInputWidget.html',
				'OO.ui.ToggleButtonWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.ToggleButtonWidget.html',
				'OO.ui.ToggleSwitchWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.ToggleSwitchWidget.html',
				'OO.ui.Widget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.Widget.html',
				'OO.ui.WindowManager': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.WindowManager.html',
				Promise: 'https://developer.mozilla.org/docs/Web/JavaScript/Reference/Global_Objects/Promise',
				RegExp: 'https://developer.mozilla.org/docs/Web/JavaScript/Reference/Global_Objects/RegExp',
				Set: 'https://developer.mozilla.org/docs/Web/JavaScript/Reference/Global_Objects/Set',
				string: 'https://developer.mozilla.org/docs/Web/JavaScript/Reference/Global_Objects/String',
				URLSearchParams: 'https://developer.mozilla.org/docs/Web/API/URLSearchParams'
			}
		}
	}
};
