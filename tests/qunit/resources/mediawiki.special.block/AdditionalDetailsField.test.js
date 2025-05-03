const { shallowMount } = require( 'vue-test-utils' );
const { createPinia } = require( 'pinia' );
const AdditionalDetailsField = require( 'mediawiki.special.block.codex/components/AdditionalDetailsField.vue' );
const useBlockStore = require( 'mediawiki.special.block.codex/stores/block.js' );

QUnit.module( 'mediawiki.special.block.AdditionalDetailsField', QUnit.newMwEnvironment( {
	beforeEach() {
		this.isIPAddress = sinon.stub( mw.util, 'isIPAddress' );
		this.historyReplaceState = sinon.stub( history, 'replaceState' );
	},
	afterEach() {
		this.isIPAddress.restore();
		this.historyReplaceState.restore();
	}
} ) );

QUnit.test( 'should set hardBlockVisible when blocking an IP address', function ( assert ) {
	const wrapper = shallowMount( AdditionalDetailsField, {
		global: { plugins: [ createPinia() ] }
	} );
	const store = useBlockStore();
	// A username should not have the hardBlock option shown.
	store.targetUser = 'ExampleUser';
	this.isIPAddress.returns( true );
	assert.true( wrapper.vm.hardBlockVisible );
	// An IP address should have hardBlock shown.
	store.targetUser = '192.0.2.34';
	this.isIPAddress.returns( true );
	assert.true( wrapper.vm.hardBlockVisible );
} );
