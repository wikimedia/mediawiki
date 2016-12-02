( function ( mw, $ ) {
	QUnit.module( 'mediawiki.special.recentchanges', QUnit.newMwEnvironment() );

	var Utils = {
		/**
		 * Get the unprocessed string from the PHP generated.
		 *
		 * @return {string}
         */
		getForm: function () {
			// Do not edit the returned value manually. It is server-side generated
			// and copy-pasted from the PHP-generated output in the browser.
			// It contains the entire PanelLayout with the infusion data
			// needed by the JavaScript module.

			// eslint-disable-next-line max-len
			return '<form method="GET" action="/index.php" id="ooui-0" class="oo-ui-layout oo-ui-formLayout" data-ooui="{&quot;_&quot;:&quot;OO.ui.FormLayout&quot;,&quot;method&quot;:&quot;GET&quot;,&quot;action&quot;:&quot;\/index.php&quot;,&quot;items&quot;:[{&quot;tag&quot;:&quot;ooui-1&quot;},{&quot;tag&quot;:&quot;ooui-2&quot;},{&quot;tag&quot;:&quot;ooui-3&quot;}]}"><div id="ooui-1" class="rcform-multicolumn-column" data-ooui="{&quot;_&quot;:&quot;Tag&quot;}"><fieldset class="oo-ui-layout oo-ui-labelElement oo-ui-fieldsetLayout"><legend class="oo-ui-labelElement-label">Filter</legend><span class="oo-ui-iconElement-icon"></span><div class="oo-ui-fieldsetLayout-group"><div class="oo-ui-fieldLayout-narrow oo-ui-layout oo-ui-labelElement oo-ui-fieldLayout oo-ui-fieldLayout-align-left"><label class="oo-ui-fieldLayout-body"><span class="oo-ui-labelElement-label">Namespace:</span><div class="oo-ui-fieldLayout-field"><div id="namespace" aria-disabled="false" class="oo-ui-widget oo-ui-widget-enabled oo-ui-inputWidget oo-ui-dropdownInputWidget mw-widget-namespaceInputWidget" data-ooui="{&quot;_&quot;:&quot;mw.widgets.NamespaceInputWidget&quot;,&quot;includeAllValue&quot;:&quot;&quot;,&quot;exclude&quot;:[],&quot;name&quot;:&quot;namespace&quot;}"><select tabindex="0" aria-disabled="false" name="namespace" class="oo-ui-inputWidget-input"><option value="" selected="selected">all</option><option value="0">(Main)</option><option value="1">Talk</option><option value="2">User</option><option value="3">User talk</option><option value="4">GCI Wiki</option><option value="5">GCI Wiki talk</option><option value="6">File</option><option value="7">File talk</option><option value="8">MediaWiki</option><option value="9">MediaWiki talk</option><option value="10">Template</option><option value="11">Template talk</option><option value="12">Help</option><option value="13">Help talk</option><option value="14">Category</option><option value="15">Category talk</option></select></div></div></label></div><div class="oo-ui-layout oo-ui-horizontalLayout"><div class="oo-ui-layout oo-ui-labelElement oo-ui-fieldLayout oo-ui-fieldLayout-align-inline"><label class="oo-ui-fieldLayout-body"><div class="oo-ui-fieldLayout-field"><div id="nsinvert" aria-disabled="false" class="oo-ui-widget oo-ui-widget-enabled oo-ui-inputWidget oo-ui-checkboxInputWidget" data-ooui="{&quot;_&quot;:&quot;OO.ui.CheckboxInputWidget&quot;,&quot;name&quot;:&quot;invert&quot;,&quot;value&quot;:&quot;1&quot;}"><input type="checkbox" tabindex="0" aria-disabled="false" name="invert" value="1" class="oo-ui-inputWidget-input"><span></span></div></div><span class="oo-ui-labelElement-label">Invert selection</span></label></div><div class="oo-ui-layout oo-ui-labelElement oo-ui-fieldLayout oo-ui-fieldLayout-align-inline"><label class="oo-ui-fieldLayout-body"><div class="oo-ui-fieldLayout-field"><div id="nsassociated" aria-disabled="false" class="oo-ui-widget oo-ui-widget-enabled oo-ui-inputWidget oo-ui-checkboxInputWidget" data-ooui="{&quot;_&quot;:&quot;OO.ui.CheckboxInputWidget&quot;,&quot;name&quot;:&quot;associated&quot;,&quot;value&quot;:&quot;1&quot;,&quot;title&quot;:&quot;Check this box to also include the talk or subject namespace associated with the selected namespace&quot;}"><input type="checkbox" tabindex="0" aria-disabled="false" title="Check this box to also include the talk or subject namespace associated with the selected namespace" name="associated" value="1" class="oo-ui-inputWidget-input"><span></span></div></div><span title="Check this box to also include the talk or subject namespace associated with the selected namespace" class="oo-ui-labelElement-label">Associated namespace</span></label></div></div><div class="oo-ui-fieldLayout-narrow oo-ui-layout oo-ui-labelElement oo-ui-fieldLayout oo-ui-fieldLayout-align-left"><label class="oo-ui-fieldLayout-body"><span class="oo-ui-labelElement-label"><a href="/index.php?title=Special:Tags" title="Special:Tags">Tag</a> filter:</span><div class="oo-ui-fieldLayout-field"><div id="tagfilter" aria-disabled="false" class="oo-ui-widget oo-ui-widget-enabled oo-ui-inputWidget oo-ui-textInputWidget oo-ui-textInputWidget-type-text"><input type="text" tabindex="0" aria-disabled="false" name="tagfilter" value="" class="oo-ui-inputWidget-input"><span class="oo-ui-iconElement-icon"></span><span class="oo-ui-indicatorElement-indicator"></span></div></div></label></div></div></fieldset><fieldset class="oo-ui-layout oo-ui-labelElement oo-ui-fieldsetLayout"><legend class="oo-ui-labelElement-label">Types of changes</legend><span class="oo-ui-iconElement-icon"></span><div class="oo-ui-fieldsetLayout-group"><div class="rcshowhideoption-container oo-ui-layout oo-ui-horizontalLayout"><span class="rcshowhideminor rcshowhideoption"><a href="/index.php?title=Special:RecentChanges&amp;hideminor=1" title="Special:RecentChanges">Hide</a> minor edits</span><span class="rcshowhidebots rcshowhideoption"><a href="/index.php?title=Special:RecentChanges&amp;hidebots=0" title="Special:RecentChanges">Show</a> bots</span><span class="rcshowhideanons rcshowhideoption"><a href="/index.php?title=Special:RecentChanges&amp;hideanons=1" title="Special:RecentChanges">Hide</a> anonymous users</span><span class="rcshowhideliu rcshowhideoption"><a href="/index.php?title=Special:RecentChanges&amp;hideliu=1" title="Special:RecentChanges">Hide</a> registered users</span><span class="rcshowhidepatr rcshowhideoption"><a href="/index.php?title=Special:RecentChanges&amp;hidepatrolled=1" title="Special:RecentChanges">Hide</a> patrolled edits</span><span class="rcshowhidemine rcshowhideoption"><a href="/index.php?title=Special:RecentChanges&amp;hidemyself=1" title="Special:RecentChanges">Hide</a> my edits</span></div></div></fieldset></div><div id="ooui-2" class="rcform-multicolumn-column" data-ooui="{&quot;_&quot;:&quot;Tag&quot;}"><fieldset class="oo-ui-layout oo-ui-labelElement oo-ui-fieldsetLayout"><legend class="oo-ui-labelElement-label">Display options</legend><span class="oo-ui-iconElement-icon"></span><div class="oo-ui-fieldsetLayout-group"><div id="limit-fieldlayout" class="oo-ui-fieldLayout-narrow oo-ui-layout oo-ui-labelElement oo-ui-fieldLayout oo-ui-fieldLayout-align-left" data-ooui="{&quot;_&quot;:&quot;OO.ui.FieldLayout&quot;,&quot;fieldWidget&quot;:{&quot;tag&quot;:&quot;limit&quot;},&quot;align&quot;:&quot;left&quot;,&quot;errors&quot;:[],&quot;notices&quot;:[],&quot;label&quot;:&quot;Maximum number of changes to show:&quot;,&quot;classes&quot;:[&quot;oo-ui-fieldLayout-narrow&quot;]}"><label class="oo-ui-fieldLayout-body"><span class="oo-ui-labelElement-label">Maximum number of changes to show:</span><div class="oo-ui-fieldLayout-field"><div id="limit" aria-disabled="false" class="oo-ui-widget oo-ui-widget-enabled oo-ui-inputWidget oo-ui-textInputWidget oo-ui-textInputWidget-type-number" data-ooui="{&quot;_&quot;:&quot;OO.ui.TextInputWidget&quot;,&quot;type&quot;:&quot;number&quot;,&quot;name&quot;:&quot;limit&quot;,&quot;value&quot;:&quot;80&quot;}"><input step="any" type="number" tabindex="0" aria-disabled="false" name="limit" value="80" class="oo-ui-inputWidget-input"><span class="oo-ui-iconElement-icon"></span><span class="oo-ui-indicatorElement-indicator"></span></div></div></label></div><div id="maxage-fieldlayout" class="oo-ui-fieldLayout-narrow oo-ui-layout oo-ui-labelElement oo-ui-fieldLayout oo-ui-fieldLayout-align-left" data-ooui="{&quot;_&quot;:&quot;OO.ui.FieldLayout&quot;,&quot;fieldWidget&quot;:{&quot;tag&quot;:&quot;maxage&quot;},&quot;align&quot;:&quot;left&quot;,&quot;errors&quot;:[],&quot;notices&quot;:[],&quot;label&quot;:&quot;Maximum age of the changes&quot;,&quot;classes&quot;:[&quot;oo-ui-fieldLayout-narrow&quot;]}"><label class="oo-ui-fieldLayout-body"><span class="oo-ui-labelElement-label">Maximum age of the changes</span><div class="oo-ui-fieldLayout-field"><div id="maxage" aria-disabled="false" class="oo-ui-widget oo-ui-widget-enabled oo-ui-inputWidget oo-ui-dropdownInputWidget" data-ooui="{&quot;_&quot;:&quot;OO.ui.DropdownInputWidget&quot;,&quot;options&quot;:[{&quot;data&quot;:&quot;3600&quot;,&quot;label&quot;:&quot;1 hour&quot;},{&quot;data&quot;:&quot;7200&quot;,&quot;label&quot;:&quot;2 hours&quot;},{&quot;data&quot;:&quot;21600&quot;,&quot;label&quot;:&quot;6 hours&quot;},{&quot;data&quot;:&quot;43200&quot;,&quot;label&quot;:&quot;12 hours&quot;},{&quot;data&quot;:&quot;86400&quot;,&quot;label&quot;:&quot;1 day&quot;},{&quot;data&quot;:&quot;259200&quot;,&quot;label&quot;:&quot;3 days&quot;},{&quot;data&quot;:&quot;604800&quot;,&quot;label&quot;:&quot;7 days&quot;},{&quot;data&quot;:&quot;2592000&quot;,&quot;label&quot;:&quot;30 days&quot;}],&quot;name&quot;:&quot;maxage&quot;,&quot;value&quot;:&quot;2592000&quot;}"><select tabindex="0" aria-disabled="false" name="maxage" class="oo-ui-inputWidget-input"><option value="3600">1 hour</option><option value="7200">2 hours</option><option value="21600">6 hours</option><option value="43200">12 hours</option><option value="86400">1 day</option><option value="259200">3 days</option><option value="604800">7 days</option><option value="2592000" selected="selected">30 days</option></select></div></div></label></div><div class="oo-ui-layout oo-ui-labelElement oo-ui-fieldLayout oo-ui-fieldLayout-align-inline"><label class="oo-ui-fieldLayout-body"><div class="oo-ui-fieldLayout-field"><div id="from" aria-disabled="false" class="oo-ui-widget oo-ui-widget-enabled oo-ui-inputWidget oo-ui-checkboxInputWidget" data-ooui="{&quot;_&quot;:&quot;OO.ui.CheckboxInputWidget&quot;,&quot;name&quot;:&quot;from&quot;,&quot;value&quot;:&quot;20161209170039&quot;}"><input type="checkbox" tabindex="0" aria-disabled="false" name="from" value="20161209170039" class="oo-ui-inputWidget-input"><span></span></div></div><span class="oo-ui-labelElement-label">Show new changes starting from 19:00, 9 December 2016</span></label></div></div></fieldset></div><div id="ooui-3" class="oo-ui-layout oo-ui-fieldLayout oo-ui-fieldLayout-align-top" data-ooui="{&quot;_&quot;:&quot;OO.ui.FieldLayout&quot;,&quot;fieldWidget&quot;:{&quot;tag&quot;:&quot;ooui-4&quot;},&quot;align&quot;:&quot;top&quot;,&quot;errors&quot;:[],&quot;notices&quot;:[]}"><div class="oo-ui-fieldLayout-body"><span class="oo-ui-labelElement-label"></span><div class="oo-ui-fieldLayout-field"><div aria-disabled="false" id="ooui-4" class="oo-ui-widget oo-ui-widget-enabled oo-ui-flaggedElement-primary oo-ui-flaggedElement-progressive oo-ui-inputWidget oo-ui-buttonElement oo-ui-buttonElement-framed oo-ui-labelElement oo-ui-buttonInputWidget" data-ooui="{&quot;_&quot;:&quot;OO.ui.ButtonInputWidget&quot;,&quot;type&quot;:&quot;submit&quot;,&quot;name&quot;:&quot;submit&quot;,&quot;flags&quot;:[&quot;primary&quot;,&quot;progressive&quot;],&quot;label&quot;:&quot;Filter changes&quot;}"><button type="submit" tabindex="0" aria-disabled="false" name="submit" value="" role="button" class="oo-ui-inputWidget-input oo-ui-buttonElement-button"><span class="oo-ui-iconElement-icon oo-ui-image-invert"></span><span class="oo-ui-labelElement-label">Filter changes</span><span class="oo-ui-indicatorElement-indicator oo-ui-image-invert"></span></button></div></div></div></div><input type="hidden" value="Special:RecentChanges" name="title"><input type="hidden" name="panel-collapsed"></form>';
		},

		/**
		 * Get the notice message and parse it with the known valid limit range.
		 *
		 * @return {string}
         */
		parseTheExpectedInvalidNumberNotice: function () {
			var messageName = 'recentchanges-limit-notice-invalidnumber';

			return mw.message( messageName, 0, 500 ).parse();
		},

		/**
		 * Assert that the given checkboxes are all enabled or disabled.
		 *
		 * @param assert
		 * @param checkboxes
		 * @param expectedDisabled
         */
		assertNamespaceCheckboxeDisabledState: function ( assert, checkboxes, expectedDisabled ) {
			checkboxes.forEach( function( checkbox ) {
				assert.equal( checkbox.isDisabled(), expectedDisabled );
			} );
		},

		/**
		 * Assert the presence/absence of the given notice message.
		 *
		 * @param assert
		 * @param {string} notice
		 * @param {OO.ui.FieldLayout} fieldLayout
		 * @param {boolean} expectedPresence
         */
		assertNoticePresence: function ( assert, notice, fieldLayout, expectedPresence ) {
			var notices = fieldLayout.notices;

			if ( expectedPresence === true ) {
				assert.notEqual( notices.indexOf( notice ), -1 );
			} else {
				assert.equal( notices.indexOf( notice ), -1 );
			}
		}
	};

	QUnit.test( '"all" namespace disables checkboxes', 8, function ( assert ) {
		var $env, $options,
			$form,
			namespace, namespaceCheckboxes,
			rc = require( 'mediawiki.special.recentchanges' );

		$form = Utils.getForm();
		$env = $( '<div />' ).html( $form ).appendTo( 'body' );

		namespace = OO.ui.infuse( 'namespace' );
		namespaceCheckboxes = [
			OO.ui.infuse( 'nsassociated' ),
			OO.ui.infuse( 'nsinvert' )
		];

		// At first checkboxes are enabled
		Utils.assertNamespaceCheckboxeDisabledState( assert, namespaceCheckboxes, false );

		rc.init();

		// By default, require them to be disabled
		Utils.assertNamespaceCheckboxeDisabledState( assert, namespaceCheckboxes, true );

		// select second option...
		namespace.setValue( '0' );

		// ... and checkboxes should be enabled again
		Utils.assertNamespaceCheckboxeDisabledState( assert, namespaceCheckboxes, false );

		// select first option ('all')...
		namespace.setValue( '' );

		// ... and checkboxes should now be disabled
		Utils.assertNamespaceCheckboxeDisabledState( assert, namespaceCheckboxes, true );

		// DOM cleanup
		$env.remove();
	} );

	QUnit.test( 'notice appears on invalid limit', function ( assert ) {
		var $env, $options,
			$form = Utils.getForm(),
			limit,
			limitFieldLayout,
			expectedNotice = Utils.parseTheExpectedInvalidNumberNotice(),
			rc = require( 'mediawiki.special.recentchanges' );

		console.log( 'test' );

		$env = $( '<div />' ).html( $form ).appendTo( 'body' );
		limit = OO.ui.infuse( 'limit' );
		limitFieldLayout = OO.ui.infuse( 'limit-fieldlayout' );

		rc.init();

		// Set a good value and expect the notice not to be there.
		limit.setValue( '50' );
		Utils.assertNoticePresence( assert, expectedNotice, limitFieldLayout, false );

		// Then set a negative value, which is unaccepted
		limit.setValue( '-1' );
		Utils.assertNoticePresence( assert, expectedNotice, limitFieldLayout, true );

		// Then set a value higher than 500, which is also unaccepted
		limit.setValue( '501' );
		Utils.assertNoticePresence( assert, expectedNotice, limitFieldLayout, true );

		$env.remove();
	} );
}( mediaWiki, jQuery ) );
