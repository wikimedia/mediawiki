<template>
	<cdx-accordion
		:class="`mw-block-log mw-block-log__type-${ blockLogType }`"
		:open="open || ( blockLogType.includes( 'active' ) && alreadyBlocked )"
	>
		<template #title>
			{{ title }}
			<cdx-info-chip :icon="infoChip.icon" :status="infoChip.status">
				{{ logEntriesCount }}
			</cdx-info-chip>
		</template>
		<cdx-table
			:caption="title"
			:columns="!!logEntries.length ? columns : []"
			:data="logEntries"
			:use-row-headers="false"
			:hide-caption="true"
			:show-vertical-borders="true"
		>
			<template #tbody>
				<tbody v-if="logEntries.length">
					<tr
						v-for="( item, index ) in logEntries"
						:key="index"
						:class="blockLogType === 'active' ? { 'cdx-selected-block-row': item.id === blockId } : ''"
					>
						<!-- Type -->
						<td v-if="blockLogType === 'recent' || blockLogType === 'suppress'">
							{{ util.getBlockActionMessage( item.action ) }}
						</td>
						<!-- Expires -->
						<td>
							<span v-if="item.action !== 'unblock'">
								{{ util.formatTimestamp( item.expiry ) }}
							</span>
						</td>
						<!-- Block parameters -->
						<td class="mw-block-log__parameters">
							<ul v-if="item.action !== 'unblock'">
								<!-- target -->
								<li v-if="blockLogType === 'active-ranges'">
									<a :href="mw.Title.makeTitle( -1, `Contributions/${ item.target }` ).getUrl()">
										{{ item.target }}
									</a>
								</li>
								<!-- block duration -->
								<li v-if="!!item.duration">
									{{ item.duration }}
								</li>
								<!-- partial block -->
								<li v-if="Object.keys( item.restrictions || {} ).length">
									{{ mw.message( 'blocklist-editing' ).text() }}
									<ul>
										<li v-for="( parameter, restrictionType ) in item.restrictions" :key="restrictionType">
											<div v-if="restrictionType === 'pages'">
												{{ mw.message( 'blocklist-editing-page' ).text() }}
												<ul class="mw-block-parameters">
													<li v-for="page in parameter" :key="page">
														<a v-if="page.title" :href="mw.util.getUrl( page.title )">
															{{ page.title }}
														</a>
														<a v-else-if="page.page_title" :href="mw.util.getUrl( page.page_title )">
															{{ page.page_title }}
														</a>
													</li>
												</ul>
											</div>
											<div v-else-if="restrictionType === 'namespaces'">
												{{ mw.message( 'blocklist-editing-ns' ).text() }}
												<ul class="mw-block-parameters">
													<li v-for="namespace in parameter" :key="namespace">
														<a :href="mw.util.getUrl( 'Special:AllPages', { namespace } )">
															{{ mwNamespaces[ namespace ] }}
														</a>
													</li>
												</ul>
											</div>
											<div v-else-if="restrictionType === 'actions'">
												{{ mw.message( 'blocklist-editing-action' ).text() }}
												<ul class="mw-block-parameters">
													<li v-for="action in parameter" :key="action">
														<!-- Potential messages: -->
														<!-- * ipb-action-create -->
														<!-- * ipb-action-move -->
														<!-- * ipb-action-upload -->
														{{ mw.message( 'ipb-action-' + action ).text() }}
													</li>
												</ul>
											</div>
										</li>
									</ul>
								</li>
								<!-- sitewide block -->
								<li v-else>
									{{ mw.message( 'blocklist-editing-sitewide' ).text() }}
								</li>
								<!-- parameters (stuff from the "Block details" section) -->
								<template v-for="( parameter, indexFlag ) in item.flags" :key="indexFlag">
									<li v-if="shouldBlockFlagBeVisible( parameter )">
										{{ util.getBlockFlagMessage( parameter ) }}
									</li>
								</template>
							</ul>
						</td>
						<!-- Reason -->
						<td>
							<!-- eslint-disable-next-line vue/no-v-html -->
							<span v-html="item.parsedreason"></span>
						</td>
						<!-- Blocking admin -->
						<td>
							<a :href="mw.Title.makeTitle( 2, item.blockedby ).getUrl()">
								{{ item.blockedby }}
							</a>
							<span class="mw-usertoollinks">
								{{ $i18n( 'parentheses-start' ).text() }}<a :href="mw.Title.makeTitle( 3, item.blockedby ).getUrl()">
									{{ $i18n( 'talkpagelinktext' ).text() }}
								</a>
								<span>
									{{ $i18n( 'pipe-separator' ).text() }}
									<a :href="mw.Title.makeTitle( -1, `Contributions/${ item.blockedby }` ).getUrl()">
										{{ $i18n( 'contribslink' ).text() }}
									</a>
								</span>{{ $i18n( 'parentheses-end' ).text() }}
							</span>
						</td>
						<!-- Timestamp -->
						<td>
							<a
								v-if="item.logid"
								:href="mw.util.getUrl( 'Special:Log', { logid: item.logid } )"
							>
								{{ util.formatTimestamp( item.timestamp ) }}
							</a>
							<a
								v-else-if="item.id"
								:href="mw.util.getUrl( 'Special:BlockList', { wpTarget: `#${ item.id }` } )"
							>
								{{ util.formatTimestamp( item.timestamp ) }}
							</a>
							<span v-else>
								{{ util.formatTimestamp( item.timestamp ) }}
							</span>
						</td>
						<!-- Actions -->
						<td v-if="blockLogType === 'active'">
							<span class="mw-block-log__actions">
								<cdx-button
									type="button"
									:aria-label="$i18n( 'block-item-edit' ).text()"
									action="default"
									weight="quiet"
									data-test="edit-block-button"
									@click="$emit( 'edit-block', item.modify )"
								>
									<cdx-icon :icon="cdxIconEdit" :icon-label="$i18n( 'block-item-edit' ).text()"></cdx-icon>
								</cdx-button>
								<cdx-button
									type="button"
									:aria-label="$i18n( 'block-item-remove' ).text()"
									action="default"
									weight="quiet"
									@click="$emit( 'remove-block', item.modify.id )"
								>
									<cdx-icon :icon="cdxIconTrash" :icon-label="$i18n( 'block-item-remove' ).text()"></cdx-icon>
								</cdx-button>
							</span>
						</td>
						<td v-else-if="blockLogType === 'active-ranges'">
							<a
								class="mw-block-log__actions"
								:href="mw.util.getUrl( 'Special:Block', { wpTarget: item.target } )"
								@click="( e ) => onViewIPRange( e, item.target )"
							>
								{{ $i18n( 'block-view-target' ).text() }}
							</a>
						</td>
						<td v-else-if="!blockLogType.includes( 'active' ) && canDeleteLogEntry">
							<a
								class="mw-block-log__actions"
								:href="mw.util.getUrl( 'Special:RevisionDelete', { type: 'logging', [`ids[${ item.logid }]`]: 1 } )"
							>
								{{ $i18n( 'block-change-visibility' ).text() }}
							</a>
						</td>
					</tr>
				</tbody>
				<tbody v-else>
					<tr class="cdx-table__table__empty-state">
						<td colspan="0" class="cdx-table__table__empty-state-content">
							{{ emptyState }}
						</td>
					</tr>
				</tbody>
			</template>
		</cdx-table>
		<div v-if="moreBlocks" class="mw-block-log-fulllog">
			<a
				:href="mw.util.getUrl( 'Special:Log', { page: 'User:' + targetUser, type: blockLogType === 'suppress' ? 'suppress' : 'block' } )"
			>
				{{ $i18n( 'log-fulllog' ).text() }}
			</a>
		</div>
	</cdx-accordion>
</template>

<script>
const util = require( '../util.js' );
const { computed, defineComponent, ref, watch } = require( 'vue' );
const { CdxAccordion, CdxTable, CdxButton, CdxInfoChip, CdxIcon } = require( '@wikimedia/codex' );
const { storeToRefs } = require( 'pinia' );
const useBlockStore = require( '../stores/block.js' );
const { cdxIconInfoFilled, cdxIconAlert, cdxIconEdit, cdxIconTrash } = require( '../icons.json' );

module.exports = exports = defineComponent( {
	name: 'BlockLog',
	components: { CdxAccordion, CdxTable, CdxButton, CdxInfoChip, CdxIcon },
	props: {
		open: {
			type: Boolean,
			default: false
		},
		blockLogType: {
			type: String,
			default: 'recent'
		},
		canDeleteLogEntry: {
			type: Boolean,
			default: false
		}
	},
	emits: [
		'edit-block',
		'remove-block'
	],
	setup( props ) {
		const store = useBlockStore();
		const { alreadyBlocked, blockId, targetUser } = storeToRefs( store );
		let title = mw.message( 'block-user-previous-blocks' ).text();
		const emptyState = ref( mw.message( 'block-user-no-previous-blocks' ).text() );
		if ( props.blockLogType === 'active' ) {
			title = mw.message( 'block-user-active-blocks' ).text();
			emptyState.value = mw.message( 'block-user-no-active-blocks' ).text();
		} else if ( props.blockLogType === 'active-ranges' ) {
			title = mw.message( 'block-user-active-range-blocks' ).text();
			emptyState.value = mw.message( 'block-user-no-active-range-blocks' ).text();
		} else if ( props.blockLogType === 'suppress' ) {
			title = mw.message( 'block-user-suppressed-blocks' ).text();
			emptyState.value = mw.message( 'block-user-no-suppressed-blocks' ).text();
		}

		const columns = [];
		if ( props.blockLogType === 'recent' || props.blockLogType === 'suppress' ) {
			columns.push(
				{ id: 'type', label: mw.message( 'blocklist-type-header' ).text() }
			);
		}
		columns.push(
			{ id: 'expiry', label: mw.message( 'blocklist-expiry' ).text(), width: '15%' },
			{ id: 'parameters', label: mw.message( 'blocklist-params' ).text() },
			{ id: 'reason', label: mw.message( 'blocklist-reason' ).text() },
			{ id: 'blockedby', label: mw.message( 'blocklist-by' ).text() },
			{ id: 'timestamp', label: mw.message( 'blocklist-timestamp' ).text(), width: '15%' },
			...( props.blockLogType.includes( 'active' ) || props.canDeleteLogEntry ) ?
				[ {
					id: 'actions',
					label: mw.message( 'blocklist-actions-header' )
				} ] :
				[]
		);

		const logEntries = ref( [] );
		const moreBlocks = ref( false );
		const FETCH_LIMIT = 10;

		const logEntriesCount = computed( () => {
			if ( moreBlocks.value ) {
				return mw.msg(
					'block-user-label-count-exceeds-limit',
					mw.language.convertNumber( FETCH_LIMIT )
				);
			}
			return mw.language.convertNumber( logEntries.value.length );
		} );

		const infoChip = computed(
			() => logEntries.value.length > 0 ?
				{ icon: cdxIconAlert, status: 'warning' } :
				{ icon: cdxIconInfoFilled, status: 'notice' }
		);
		const mwNamespaces = mw.config.get( 'wgFormattedNamespaces' );
		mwNamespaces[ '0' ] = mw.msg( 'blanknamespace' );

		const shouldBlockFlagBeVisible = ( param ) => !( param === 'noautoblock' && mw.util.isIPAddress( targetUser.value, true ) );

		/**
		 * Get the duration to be displayed from the block parameters.
		 *
		 * @param {Object} params
		 * @return {string}
		 * @private
		 */
		function getDurationFromParams( params ) {
			if ( Date.parse( params.duration ) ) {
				return util.formatTimestamp( params.duration );
			}
			return params[ 'duration-l10n' ];
		}

		/**
		 * Construct the data object needed for a template row, from a logentry API response.
		 *
		 * @param {Object[]} logevents
		 * @return {Object[]}
		 */
		function logentriesToRows( logevents ) {
			return logevents.map( ( logevent ) => ( {
				timestamp: logevent.timestamp,
				logid: logevent.logid,
				action: logevent.action,
				expiry: logevent.params.expiry,
				duration: getDurationFromParams( logevent.params ),
				blockedby: logevent.user,
				flags: logevent.params.flags,
				restrictions: logevent.params.restrictions,
				parsedreason: logevent.parsedcomment
			} ) );
		}

		/**
		 * Load the given IP range as the target user and reset the form.
		 *
		 * @param {Event} e
		 * @param {string} target
		 */
		function onViewIPRange( e, target ) {
			e.preventDefault();
			targetUser.value = target;
			store.resetForm();
		}

		watch( targetUser, ( newValue ) => {
			if ( newValue ) {
				store.getBlockLogData( props.blockLogType ).then( ( responses ) => {
					let newData = [];
					const data = responses[ 0 ].query;

					if ( props.blockLogType === 'recent' ) {
						// List of recent block entries.
						newData = logentriesToRows( data.logevents );
						moreBlocks.value = newData.length >= FETCH_LIMIT;

					} else if ( props.blockLogType === 'suppress' ) {
						// List of suppress/block or suppress/reblock log entries.
						newData.push( ...logentriesToRows( data.logevents ) );
						newData.push( ...logentriesToRows( responses[ 1 ].query.logevents ) );
						newData.sort( ( a, b ) => b.timestamp.logid - a.timestamp.logid );
						moreBlocks.value = newData.length >= FETCH_LIMIT;
						// Re-apply limit, as each may have been longer.
						newData = newData.slice( 0, FETCH_LIMIT );

					} else {
						// List of active blocks.
						for ( const block of data.blocks ) {
							const isRangeBlock = !!block.rangestart;
							const isCurrentTarget = block.user === targetUser.value;
							// Skip range blocks for 'active', or non-range blocks for 'active-ranges'.
							if ( ( props.blockLogType === 'active' && isRangeBlock && !isCurrentTarget ) ||
								( props.blockLogType === 'active-ranges' && ( !isRangeBlock || isCurrentTarget ) )
							) {
								continue;
							}

							newData.push( {
								// Store the entire API response, for passing in when editing the block.
								modify: block,
								// Data as needed by the template.
								id: block.id,
								timestamp: block.timestamp,
								target: block.user,
								partial: block.partial,
								expiry: block.expiry,
								duration: getDurationFromParams( block ),
								blockedby: block.by,
								flags: [
									block.anononly ? 'anononly' : null,
									block.nocreate ? 'nocreate' : null,
									block.autoblock ? null : 'noautoblock',
									block.noemail ? 'noemail' : null,
									block.allowusertalk ? null : 'nousertalk',
									block.hidden ? 'hiddenname' : null
								].filter( ( e ) => e !== null ),
								restrictions: block.restrictions,
								parsedreason: block.parsedreason
							} );
						}
					}

					logEntries.value = newData;
				} ).catch( ( error ) => {
					emptyState.value = mw.message( 'api-error-unknownerror', error ).text();
				} );
			} else {
				moreBlocks.value = false;
				logEntries.value = [];
			}
		}, { immediate: true } );

		return {
			mw,
			util,
			title,
			emptyState,
			columns,
			cdxIconEdit,
			cdxIconTrash,
			logEntries,
			moreBlocks,
			alreadyBlocked,
			blockId,
			targetUser,
			logEntriesCount,
			infoChip,
			shouldBlockFlagBeVisible,
			mwNamespaces,
			onViewIPRange
		};
	}
} );
</script>

<style lang="less">
@import 'mediawiki.skin.variables.less';

.mw-block-log {
	word-break: auto-phrase;

	// Align the new-block button to the left, because there's no table caption.
	.cdx-table__header {
		justify-content: flex-start;
	}

	// Override margin styles may have been added to tables, such as in Minerva.
	.cdx-table__table {
		margin: 0;
	}

	.cdx-accordion__content {
		font-size: unset;
	}

	.mw-usertoollinks {
		white-space: nowrap;
	}

	.mw-block-log__parameters > ul {
		margin-left: @spacing-75;
		// Overrides for skins that mess with padding.
		padding-left: 0;
		padding-inline: 0;
	}

	tr.cdx-selected-block-row {
		background: @background-color-progressive-subtle--hover;
	}

	.mw-block-log__actions {
		display: flex;
	}
}

.mw-block-log-fulllog {
	margin-top: @spacing-50;
}
</style>
