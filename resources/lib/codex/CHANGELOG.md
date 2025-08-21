# 2.3.1 / 2025-08-21

## Styles
- InputChip, styles: Fix horizontal spacing (Volker E.)

## Icons
- icons: Add 'bookmarkList' icon (Volker E.)

## Code
- TypeaheadSearch: Fix unavoidable deprecation warning in mobile view mode (Roan Kattouw)
- TypeaheadSearch: Disable Menu teleportation (Roan Kattouw)
- Menu: Make teleportation optional, triggered by CdxTeleportMenus (Roan Kattouw)

## Build
- build: sync node and npm versions across packages with ''.nvmrc' (Volker E.)
- build: sync node and npm versions across packages with '.nvmrc' II (Volker E.)
- tests: Remove getMenuRoot helper, no longer needed (Roan Kattouw)

## Documentation
- docs: Set up teleport target for docs site components (Roan Kattouw)
- docs: Add support for Matomo pageview analytics (Roan Kattouw)
- docs: Fix 'ARIA' acronym case in documentation (Volker E.)
- docs: Better document teleportation and styling pitfalls (Roan Kattouw)

# 2.3.0 / 2025-08-14

## Features
- Dialog, Popover: Support template ref as teleport target (Roan Kattouw)
- useFloatingMenu: Pass in reference HTMLElement to autoUpdate() directly (Roan Kattouw)
- Menu, Dialog: Teleport menus by default (Roan Kattouw)

## Styles
- Table, MenuButton: Ensure menu can overflow table (Anne Tomasevich)
- Link, docs: Use new `color-link` token for Links and docs application (Volker E.)
- tokens, docs: Update Source Serif Pro to Source Serif 4 (Derek Torsani)
- tokens: Update Yellow and Blue option tokens (Derek Torsani)

### New design tokens
- tokens: Add default link tokens including all interaction and visited states (lmora)
- tokens: Add `border-color-warning--*` interaction states (Volker E.)
- tokens, InputChip: Add clear button component token (Volker E.)

## Build
- build: Enable ES modules for tests in all packages (Roan Kattouw)
- build: Remove preserved output comments from types and constants files (Volker E.)
- eslint: Use modern names for profiles, so this can upgrade (James D. Forrester)
- build: Upgrade eslint-config-wikimedia from 0.28.2 to 0.31.0 (James D. Forrester)
- build: Remove superfluous linting override (Volker E.)
- build: Upgrade Vite to v7.0.4, bump Node requirement to v20.19.0 & upgrade dependencies (Roan Kattouw)
- build: update browserslist db to v1.0.30001731 (Volker E.)

## Documentation
- docs: Update link to docs in icons.ts code comment (Roan Kattouw)
- docs: Replace '/main' with '/latest' in links to Codex documentation (Volker E.)
- docs: Fix "New components" link on Contributing overview (Derek Torsani)
- docs: Add quiet progressive link fake button example (Volker E.)
- docs, Image: Fix class references to proper placeholder image icon sizes (Volker E.)
- docs: Fix z-index equivalence in comment (Volker E.)
- docs: Fix internal Slack channel reference and expand Foundation acronym (Volker E.)

# 2.2.1 / 2025-07-22

## Features
- Select: Add hidden HTML form element when a name is provided (Nick Garnsworthy)

## Styles
- Button, styles: Remove padding for icon-only Buttons (Derek Torsani)
- Message, styles: Ensure icon never shrinks (Anne Tomasevich)
- styles: Add WIP responsive Grid layout system with Less mixins (Volker E.)
- styles, sandbox: apply typography mixin to Sandbox demo pages (Lauralyn Watson)
- styles: add responsive styles to the Sandbox (Lauralyn Watson)
- styles: Simplify calcs introduced in font modes (Derek Torsani)
- TypeaheadSearch, styles: Improve footer styles for mobile view (Anne Tomasevich)
- docs, styles: remove unused custom styles in contributing-icons.md (Lauralyn Watson)
- TypeaheadSearch: Fix spacing in footer without thumbnails (Formasitchijoh)
- Message: Update line-height of content (Derek Torsani)

## Tokens
- InputChip, tokens: Update max-height of chips (Derek Torsani)

## Code
- demo, sandbox: fix console warnings in the Sandbox (Lauralyn Watson)
- demo: add Select with hidden input for forms to Sandbox (Lauralyn Watson)
- Breadcrumb: Add WIP Breadcrumb component (Doğu Abaris)
- Breadcrumb: Standardize and simplify code and docs (Anne Tomasevich)
- Breadcrumb, demo: add demos and i18n translatable string (Lauralyn Watson)
- Popover: Don't close when viewport scrollbar is clicked (Roan Kattouw)
- Table: Remove nonexistent 'last' event (Roan Kattouw)
- MenuButton: Enter key should expand the menu (Lauralyn Watson)

## Build
- build: Update build-if-missing script in icons package (Anne Tomasevich)
- build: Set "moduleResolution" TypeScript config to "bundler" (Roan Kattouw)
- build: Update 'stylelint' dependencies (Volker E.)
- build: Update 'svglint' and 'svgo' dependencies (Volker E.)

## Documentation
- docs: Improve Lookup and MultiselectLookup demos with fetching (Anne Tomasevich)
- docs: add favicon (Lauralyn Watson)
- docs: Fix broken link to Wikifunctions (Timo Tijhof)
- docs: Fix display of example icons on "Contibuting icons" page (Roan Kattouw)
- docs: add favicon types (Lauralyn Watson)
- docs: Minor optimizations to new favicon files (Thiemo Kreuz)
- docs: Fix typo and use default fill shorthand color for icon example (Volker E.)
- docs: Fix number props and type links with brackets (Anne Tomasevich)
- docs: Expand on NVM for simpler onboarding and unify on "npm" term (Volker E.)
- docs: Consolidate imports in contributing-icons.md (Roan Kattouw)

# 2.2.0 / 2025-06-24

## Deprecating changes
- [DEPRECATING CHANGE] Throw deprecation warnings for old APIs (Anne Tomasevich)

Some usages of component props are now officially deprecated. Check the console for warnings from
the Dialog, Field, Label, Message, SearchInput, and TypeaheadSearch components.

## Features
- Image: move component out of WIP (Lauralyn Watson)
- Button: create small size (Daphne Smit)
- MenuButton: Fix selected prop type (Dillon)

## Styles
- Image, styles: unify placeholder, loading, and error states (Lauralyn Watson)
- styles: Improve word-breaking and remove most hyphenation (Anne Tomasevich)

## Tokens
- token: Add new border color token (Derek Torsani)

## Icons
- icons: Fix 'logo-codex.svg' so it doesn't need `fill-rule` attribute (Thiemo Kreuz)

## Build
- build: prepare-release script sorts new commits in changelog (Lauralyn Watson)
- build: Alias `lint:scripts` command to Wikimedia pseudo standard command (Volker E.)
- tests: Reorganize unit tests (Anne Tomasevich)
- Tests: Suppress some expected console warnings for better output (Eric Gardner)
- tests, Combobox, MenuButton: Add snapshot tests (Anne Tomasevich)
- tests: Fix snapshot test props, reorganize tests (Anne Tomasevich)

## Documentation
- docs: add guidelines for Image component (bmartinezcalvo)
- docs: Redesign home page (Anne Tomasevich)
- docs: Add initially selected chip to MultiselectLookupField example (Roan Kattouw)
- docs: align ImageAspectRatios docs with colon separator change (Doğu Abaris)
- docs: Include instructions to document types and constants (Anne Tomasevich)
- docs: Add general data vizualization guidelines (Derek Torsani)
- docs: Update RELEASING.md (Anne Tomasevich)
- docs: update images in Codex homepage (Lauralyn Watson)
- docs: Update Image guidelines for consistency (Anne Tomasevich)
- docs: Document breaking changes process (Anne Tomasevich)
- docs, Button: Reformat button size docs (Anne Tomasevich)

# Code
- Menu: Avoid scrolling the entire page when the menu opens (Roan Kattouw)
- useResizeObserver: Check for valid ResizeObserver entry (bernardwang)

# 2.1.0 / 2025-06-10

## Features
- TypeaheadSearch: Add support for mobile view TypeaheadSearch (bernardwang)
- InfoChip, InputChip: add tooltip to display entire label (theprotonade)

## Styles
- Image, styles: update component based on design review (Lauralyn Watson)
- Popover: adjust min-width and minClipWidth for small devices (Sergio Gimeno)

## New design tokens
- `@size-1200`

## Icons
- icons: Fix typo "anonynmous" → "anonymous" in "userAnonymous.svg" (Bartosz Dziewoński)

## Code
- TypeaheadSearch: Add mobile view TAHS sandbox demo (bernardwang)
- TypeaheadSearch: fix box shadow showing when theres no empty search recommendations (bernardwang)
- tests: Reorganize tests and remove unneeded ones (Anne Tomasevich)
- Menu: Don't close when scrollbar is clicked (Roan Kattouw)
- TypeaheadSearch: Ensure keyboard navigation works for empty search results (bernardwang)

## Documentation
- docs: make the home hero full width (theprotonade)
- docs: update design contribution guidelines (bmartinezcalvo)
- docs: update contribution guidelines (bmartinezcalvo)
- docs: Re-architect usage and contributing sections (Anne Tomasevich)
- docs: Update various docs pages part 2 (Derek Torsani)
- docs: Update and simplify developer contribution docs (Anne Tomasevich)
- docs: Update and improve icon documentation (Anne Tomasevich)
- docs: Update various docs pages part 1 (Derek Torsani)
- docs: Minor followup patch to I3a584dee62c96bfe05bf5b94cb2cf00659af16b7 to add details to AUTHORS.txt (theprotonade)
- docs: remove unused files (Lauralyn Watson)
- docs: Clean up recent changes (Anne Tomasevich)

# 2.0.0 / 2025-06-02

## Code
- tests: Update tests in docs package (Anne Tomasevich)
- tests: Update tests for composables (Anne Tomasevich)

## Documentation
- docs: Update linting command descriptions (Volker E.)

# 2.0.0-rc.2 / 2025-05-21

Codex 2.0 RC2 is a minor update to RC1 that includes a new design token
`line-height-content` to ensure that ([user-provided content is styled
appropriately](https://phabricator.wikimedia.org/T394722)),
as well as a few other minor changes.

## Styles
- styles: Unify on `-mixin` prefix for Less mixins (Volker E.)
- tokens: introduce `line-height-content` token (Eric Gardner)
- typography: Update type mixins (Derek Torsani)

## New design tokens
- `line-height-content`

## Icons
- icons: Add 'logo-Codex' icon (Volker E.)
- icons: Add UserTemporaryLocation icon (Thalia)
- icons: remove unnecessary `clip-rule` in 'logoCodex' (Volker E.)
- icons: remove unnecessary `fill-rule` and `clip-rule` in 'userTemporaryLocation' (Volker E.)

## Code
- Checkbox, Radio, styles: Fix selector for applying `cursor: pointer` (Ed Sanders)
- build: Add `lint:clear-cache` command (Volker E.)
- build: Enable eslint and stylelint caching (Umherirrender)
- build: Use common Wikimedia linting command names (Volker E.)
- Fix bug where clicking on the svg of an action button closes the Accordion instead of firing the action button click event (Daphne Smit)
- Update TAHS to work with empty search recommendations through new showEmptyQueryResults prop (bernardwang)
- docs, ProgressBar: Fix `aria-label` controlsConfig prop name (Volker E.)

## Documentation
- docs: Sync VitePress's `isDark` ref with color mode switcher (Anne Tomasevich)
- docs: fix headings in component pages (Lauralyn Watson)
- docs: improve tokens guidelines to clarify on component tokens (bmartinezcalvo)

# 2.0.0-rc.1 / 2025-04-29

## Breaking changes
- [BREAKING CHANGE] Lookup: remove initialInputValue prop (Lauralyn Watson)
- [BREAKING CHANGE] icons: remove deprecated icons (Lauralyn Watson)
- [BREAKING CHANGE] types: remove DialogAction and PrimaryDialogAction (Lauralyn Watson)
- [BREAKING CHANGE] tokens, styles: Introduce font modes and redesign type scale system (Derek Torsani)
- [BREAKING CHANGE] tokens: Add new Less mixin files and remove legacy file (Eric Gardner)

View how to handle these changes in the
[Codex 2.0 migration guide](https://www.mediawiki.org/wiki/Codex/Release_Timeline/2.0/Breaking_changes).

## Styles
- Dialog: fix padding in header, body, and footer (Derek Torsani)
- Popover, styles: ensure header icon does not shrink (Lauralyn Watson)
- Accordion, styles: Fix action button position when open (Volker E.)
- Accordion, styles: Don't let flexbox shrink the indicator icon (Eric Gardner)

### New design tokens
- `spacing-6`
- `spacing-65`
- `line-height-large`
- `line-height-x-large`
- `line-height-xx-large`
- `line-height-xxx-large`
- `size-toggle-switch-grip`
- `width-toggle-switch`
- `min-height-toggle-switch`
- `height-toggle-switch`
- `spacing-toggle-switch-grip-start`
- `spacing-toggle-switch-grip-end`

### Deprecated design tokens
- `line-height-xxx-small`
- `line-height-xx-small`

### Un-deprecated tokens
- `line-height-x-small`

## Documentation
- docs: Fix 'node_modules' file import path in documentation (Volker E.)
- docs, icons: Add an appearance menu to the docs site & 'configure' icon (Anne Tomasevich)
- docs: Adjust styles and format on Style Guide Typography page (Anne Tomasevich)
- docs: Remove sub-1.0.0 versioning comments (Volker E.)
- docs: Clarify usage of custom properties carrying design tokens (Volker E.)

# 1.23.0 / 2025-04-17

## Features
- Accordion: add support for optional v-model prop (Eric Gardner)

## Styles
- styles, tokens, Checkbox: Replace non-option token SVG image with pure CSS (Volker E.)
- styles, Checkbox: Add correct disabled color (Volker E.)

## New design tokens
- `@border-color-inverted-fixed`
- `@transform-checkbox-tick--checked`

## Documentation
- docs: Align Less bracket whitespace to Wikimedia coding guidelines (Volker E.)
- docs: Update Design Principles (Derek Torsani)

# 1.22.0 / 2025-04-01

## Styles
- styles, docs: Harden design token usage in calculations (Volker E.)
- styles: update InfoChip and InputChip `max-width` (bmartinezcalvo)
- tokens: Express transformed tokens in pixels instead of rems (Roan Kattouw)

## Code
- Thumbnail: Ensure that prop updates trigger image change when necessary (Eric Gardner)
- useFloatingMenu: Ensure flip padding is less than size padding (Eric Gardner)

## Documentation
- docs: Update to clarify introduction of Popover component (Volker E.)
- demos: Add typography demo sandbox page (Volker E.)

# 1.21.1 / 2025-03-19

## Code
- code: Update Popover imports (Anne Tomasevich)

# 1.21.0 / 2025-03-19

## Features
- Popover: add arrow tip (Lauralyn Watson)
- container: Add initial Container component (Volker E.)
- ProgressIndicator: Take component out of WIP (Volker E.)
- Popover: take component out of WIP (Lauralyn Watson)

## Styles
- styles, Popover: address design review (Lauralyn Watson)
- token, Popover: add `z-index` token (Lauralyn Watson)
- tokens: `accent-color` should also feature color mode token (Volker E.)

## New design tokens
- `z-index-popover`

## Code
- Popover: Make `anchor` loosely required (Anne Tomasevich)
- Popover: apply the i18n message key for the close button label (Lauralyn Watson)
- tests, Image: Remove redundant 'correctly' in snapshot test descriptions (Doğu Abaris)
- Popover: fix console warning (Lauralyn Watson)
- Popover: Remove Transition and mock `useFloating` in tests (Anne Tomasevich)
- Popover: Change "positionConfig" prop to "placement" (Eric Gardner)
- Popover: Update logic and tests that handle mouse interaction (Anne Tomasevich)
- Popover: Set max-width instead of width (Anne Tomasevich)
- Tooltip: Improve ID generation (Eric Gardner)
- Popover: change header button wrapper's class name (Lauralyn Watson)
- Popover: Adjust anchor prop type (Anne Tomasevich)

## Documentation
- docs: add design tokens introduced in v1.20.3 (Lauralyn Watson)
- docs, i18n: update docs about adding new message keys (Lauralyn Watson)
- demos: Fix Container demo selectors (Volker E.)
- docs: Add Popover guidelines (Derek Torsani)
- Popover: add demos (Lauralyn Watson)
- docs: Support anchor links to icons in AllIcons table (Anne Tomasevich)
- docs: Fix typos on 'TypeScript' (Volker E.)
- demo, Popover: add action button controls to config demo (Lauralyn Watson)

# 1.20.3 / 2025-03-04

## Deprecating Changes
- [DEPRECATING CHANGE] types: Generalize dialog action types (Anne Tomasevich)

## Features
- Popover: Add WIP component (Lauralyn Watson)
- Popover: Add header and footer content (Anne Tomasevich)
- ProgressIndicator, tokens: add initial implementation of component (Volker E.)

## Styles
- Popover: Update header styles per design feedback (Anne Tomasevich)
- Popover: add Floating UI behavior (Lauralyn Watson)
- Popover: handle keynav and clicks outside (Lauralyn Watson)
- styles: Consistently import tokens in Less as references (Volker E.)

## New design tokens
- `animation-duration-fast`
- `transform-progress-indicator-spinner-start`
- `transform-progress-indicator-spinner-end`

## Code
- Menu: Add `tabindex="-1"` to `ul` element (Anne Tomasevich)
- Dialog: Properly await onDialogOpen (Roan Kattouw)
- utils: add `unwrapElement` utility function (Lauralyn Watson)
- build: Stop CI from failing if Netlify deployment fails (Anne Tomasevich)
- build: Add `--glob` flag to 'rimraf' command (Volker E.)

## Documentation
- docs: Update Codex logo (Derek Torsani)
- docs: Don't break everything when there's a generic type (Anne Tomasevich)
- docs: Remove superfluous JSDoc Boolean `@values` annotations (Volker E.)
- docs: update links to Vue Test Utils (Lauralyn Watson)
- docs: include recommendation on maximum number if items in Menu (bmartinezcalvo)
- docs: Create ADR for font modes (Derek Torsani)
- docs: fix RTL issues in bidirectionality guidelines (bmartinezcalvo)
- docs: Fix hero tagline margin (Derek Torsani)
- docs: Remove client-only tag from MW examples (Eric Gardner)
- docs: Amend ProgressBar documentation for clarity with ProgressIndicator (Volker E.)

# 1.20.2 / 2025-02-19

## Deprecating Changes
- [DEPRECATING CHANGE] tokens, components: Add new and update box-shadow tokens and apply (Derek Torsani)

## Features
- MultiselectLookup: support multiple selection based on same search (Lauralyn Watson)

## Styles
- ToggleButton: update `quiet` enabled color (Derek Torsani)
- MenuItem: Update color of supporting text (Derek Torsani)
- Select: Instead of relying on hardcoded values, rely on tokens directly (Volker E.)
- tokens: Add opacity color option tokens (Derek Torsani)
- tokens, Tooltip: Add `min`, `fit` and `max` content width tokens & apply (Volker E.)

## New design tokens
- `size-content-min`
- `size-content-fit`
- `size-content-max`
- `box-shadow-small`
- `box-shadow-small-top`
- `box-shadow-small-bottom`
- `box-shadow-medium`
- `box-shadow-large`
Only used as general `box-shadow` token constructors:
- `box-shadow-outset-small-bottom`
- `box-shadow-outset-medium-below`
- `box-shadow-outset-medium-around`
- `box-shadow-outset-large-below`
- `box-shadow-outset-large-around`

## Icons
- icons: Add more language-specific text icons for Norwegian and Swedish (Jon Harald Søby)

## Code
- MultiselectLookup: Change behavior on chip click (Anne Tomasevich)
- ChipInput: Add CSS class to input chip data (Anne Tomasevich)
- build: Remove `prepare` script (Volker E.)
- build: Update 'style-dictionary' (Volker E.)
- build: Limit color references only to `box-shadow` colors, not to all tokens (Volker E.)
- build: upgrade browserslist-db to 1.0.30001700 (Volker E.)

## Documentation
- docs: Update Codex logo (Derek Torsani)
- docs: Update AUTHORS.txt (Roan Kattouw)
- docs: Conduct various clean up on the docs site (Derek Torsani)
- docs: It's 'Less', not more, not less (Volker E.)
- docs: Change icon names to be unwrapped from code markup (Volker E.)
- docs: update usage of icons (Lauralyn Watson)
- docs: Fix contributor name to unified format (Volker E.)
- demo: Add custom RCFilters MultiselectLookup demo (Anne Tomasevich)
- demos: add `id` HTML attribute (Lauralyn Watson)
- demo: Add new sandbox page for experiments + RCFilters demo (Anne Tomasevich)
- demos: Remove obsolete Less variable (Volker E.)

# 1.20.1 / 2025-02-04

## Styles
- styles: Implement `:focus-visible` in Card and Accordion components (Gauri)
- styles, mixins: Remove outdated `-webkit` vendor prefix (Volker E.)
- styles, TextInput: Remove obsolete `-ms-clear` disabling vendor rule (Volker E.)
- Revert "mixins: amend hyphens to use more solid `overflow-wrap: anywhere`" (Anne Tomasevich)
- styles, Button: fix the progressive primary button's icon (Lauralyn Watson)

## Code
- Dialog: Don't close when backdrop click started in the dialog (Daphne Smit)
- build: Clean up .gitignore files (Roan Kattouw)
- Menu: Re-work how IDs are generated (Eric Gardner)

## Documentation
- demos: Make `tbody` explicit in `table`s (Volker E.)
- docs: Fix `<table>` HTML semantics (Volker E.)
- docs: Fix link syntax (apaskulin)
- docs: Revise Using links and buttons page (Derek Torsani)

# 1.20.0 / 2025-01-22

## Deprecating changes
- useGenerateId: Deprecate, replace usage with useId() (Roan Kattouw)

## Styles
- styles, TextArea: Add `min-width: 256px` to TextArea component (millmason)
- styles: Consolidate MenuItem icon styles (Roan Kattouw)
- mixins: Reduce output of 'css-icon' when not a button icon (Volker E.)
- mixins: Reduce to shorthand hex color in 'css-icon' mixin (Volker E.)
- mixins: amend hyphens to use more solid `overflow-wrap: anywhere` (Volker E.)

## Code
- build: Bump Node version to 20.18.1 (Jon Robson)
- build: Disallow setting xlink:namespace on the `svg` element (Volker E.)
- build: Include `mask` property in the properties order list and apply (Volker E.)
- build: Relax the peerDependency on vue (Roan Kattouw)
- build: Update Vite to 6.0.7 (Roan Kattouw)
- build: Update Vue to 3.5.13 (Roan Kattouw)
- build: Update netlify-cli to 18.0.0 (Eric Gardner)
- tests: Make jsdom tricks in Tabs tests more robust (Roan Kattouw)

## Icons
- icons, docs: Remove `xmlns:xlink` namespace where unnecessary (Thiemo Kreuz)
- icons: Add 'lightbulb' to provided ones (Volker E.)
- icons: add ArrowUp and ArrowDown icons to Codex (Lauralyn Watson)
- icons: add azerbaijani definitions and new bold Q (Nemoralis)

## Documentation
- docs: Add component groups in the sidebar (Anne Tomasevich)
- docs: Add instructions to RELEASING.md for Phab tagging release commit (Roan Kattouw)
- docs: Explain vendor prefixes in 'css-icon' public mixin (Volker E.)
- docs: Revise and add resources from home page to others (Derek Torsani)
- docs: Revise mentions of color option tokens in docs (Derek Torsani)
- docs: Revise verbiage used in docs to follow content guidelines (Derek Torsani)
- docs: Update limiting statement to only use ES2016 in component demos (Volker E.)
- docs: floating button to navigate to top of component pages (Lauralyn Watson)
- docs: improve floating button (Lauralyn Watson)
- docs: include content recommendations in ToggleButton page (bmartinezcalvo)
- docs: minor edit to releasing doc (Lauralyn Watson)
- docs: update AUTHORS.txt and .mailmap (Volker E.)
- Provide a .mailmap file to consolidate where people have multiple git addresses (James D. Forrester)
- Remove Lucas Werkmeister from .mailmap (Lucas Werkmeister)

# 1.19.1 / 2025-01-07
Dummy release to fix a publishing problem with 1.19.0.

# 1.19.0 / 2025-01-07

## Styles
- MenuItem: fix selected destructive icon color (Derek Torsani)
- component: Fix icon colors in MenuItem (Derek Torsani)

## Code
- InputChip: Disable the delete button in read-only mode (Roan Kattouw)
- nvmrc: Update to Node 20 (Volker E.)
- build: Update 'browserslist-db' to 1.0.30001687 (Volker E.)
- build: Update 'prismjs' and '@types/prismjs' packages to latest (Volker E.)
- build: Update 'postcss' and plugins to latest (Volker E.)
- build: Update 'less', 'rtlcss' and 'autoprefixer' dependencies (Volker E.)
- build: Update 'glob' to latest (Volker E.)
- build: update 'npm-watch' to latest (Volker E.)
- build: Update 'netlify-cli' to v17.38.0 (Volker E.)
- build: Update 'change-case' to latest (Volker E.)
- build: update Node and npm respectively to CI (Volker E.)
- build: Raise target to ES2016 and make use of `includes()` (Volker E.)
- build: Fix build-if-missing command in the codex package (Roan Kattouw)
- build: Update ESLint config for ES2016 build target (Roan Kattouw)
- build: Update 'stylelint-config-wikimedia' and plugins to latest (Volker E.)
- tests: Add a test for expandDeprecationMessage (Roan Kattouw)
- build: Update Vue to 3.4.28 (Roan Kattouw)
- build: Update TypeScript to 5.7.2 (Roan Kattouw)
- build: Make updating codex-icons dependency work again (Roan Kattouw)
- build: Update 'postcss-rtlcss' to 5.6.0 (Volker E.)
- build: Update 'concurrently' to 9.1.0 (Volker E.)
- build: Update further '@types' packages (Volker E.)
- build: Update 'netlify-cli' to 17.38.1 (Volker E.)
- build: Update 'vue-docgen-cli' to 4.79.0 (Volker E.)
- build: Update 'concurrently' to 9.1.2 (Volker E.)
- build: Update '@rollup/plugin-typescript' to 12.1.2 (Volker E.)
- build: Update `@typescript-eslint` plugins to 8.19.0 (Volker E.)
- build: Use new @import directive in jsdoc comments (Roan Kattouw)

## Documentation
- docs: Update component page styles to prepare for simplification (Anne Tomasevich)
- docs: Simplify Checkbox, Radio, and ToggleSwitch pages (Derek Torsani)
- docs: Simplify Combobox, SearchInput, Select, and TypeaheadSearch (Anne Tomasevich)
- docs: Simplify Button, ButtonGroup, Link, ToggleButton, and ToggleButtonGroup pages (Derek Torsani)
- docs: Simplify Tooltip, Tab, and Tabs pages (Derek Torsani)
- docs: Simplify Accordion, Card, InfoChip, Message, and ProgressBar pages (Derek Torsani)
- docs: Simplify Icon, Image, and Thumbnail pages (Derek Torsani)
- docs: Simplify Dialog and Table demo pages (Anne Tomasevich)
- docs: Simplify Menu, MenuItem, and MenuButton pages (Anne Tomasevich)
- docs: Simplify Field, Label, Lookup, and MultiselectLookup pages (Anne Tomasevich)
- docs: Simplify demo pages for TextInput, TextArea, and ChipInput (Anne Tomasevich)
- docs: Update Button, ToggleButton, and group demos (Anne Tomasevich)
- docs: Delete removed component page images (Anne Tomasevich)
- docs: Update and standardize anchor links (Anne Tomasevich)
- docs: Add changes to MenuItem page back in (Anne Tomasevich)
- docs: Remove references to deleted demo files (Anne Tomasevich)
- docs: Remove remaining demo text (Anne Tomasevich)
- docs: Enable local search (Anne Tomasevich)
- docs: Update ADR page titles to be clearer (Volker E.)
- docs, images: remove metadata from embedded images (Volker E.)

## Icons
- icons: Minor SVG optimization of 'logo-Wikiquote' (Volker E.)
- icons: Rename to 'fullscreen' and deprecate cdxIconFullScreen (Volker E.)

# 1.18.0 / 2024-12-10

## Features
- MultiselectLookup, ChipInput, InputChip: add readonly and disabled support (lwatson)

## Styles
- styles: Adjust decorative icons to use `@color-subtle` (Derek Torsani)
- Image: Add rootClasses and rootStyles (Anne Tomasevich)
- tokens, components: Add new tokens to support button styles also being updated (Derek Torsani)
- tokens: Add new tokens to support changes also made to unify interactive styles phase 2 (Derek Torsani)
- Select: unify form element styles (Eric Gardner)
- Select, MenuItem: Minor style adjustments (Derek Torsani)

## Code
- components: Unify console log messages to a single style (Volker E.)
- MultiselectLookup: Remove unneeded prop (Anne Tomasevich)
- Tooltip: Add guard clauses for undefined and empty string values (Doğu Abaris)

## Documentation
- Image: Add WIP Image and component (Doğu Abaris)
- docs: Add recent authors (>1 commit) and other contributors (Volker E.)

## New design tokens
- `mix-blend-mode-base`
- `mix-blend-mode-blend`
- `background-color-interactive--hover`
- `background-color-interactive--active`
- `background-color-progressive-subtle--hover`
- `background-color-progressive-subtle--active`
- `background-color-destructive-subtle--hover`
- `background-color-destructive-subtle--active`

# 1.17.0 / 2024-11-26

## Features
- TextArea: Expose native validation methods and events (Anne Tomasevich)
- TextInput: Emit the native DOM 'invalid' event (Itamar Givon)
- TextInput: Expose native constraint Validation API DOM methods (Itamar Givon)

## Styles
- styles, images: update outdated error color to match new color palette (Volker E.)

## Code
- Table: Simplify condition in getRowHeaderScope function (Doğu Abaris)
- build: Commit 'npm audit fix' changes to at least fix the criticals (Volker E.)
- build: Point to browserslist-config-wikimedia modern, not deprecated modern-es6-only (James D. Forrester)
- build: Upgrade eslint-config-wikimedia from 0.26.0 to 0.28.2, & auto-fix to pass (James D. Forrester)
- build: update 'svglint' to latest v3.1.0 (Volker E.)
- build: update 'svgo' to latest v3.3.2 (Volker E.)

## Documentation
- IconLookup: Clarify reactive data flow and update watcher (Eric Gardner)
- docs: Document native validation for TextInput (Anne Tomasevich)
- docs: Revise colors used for top graphic on Style Guide colors page (Derek Torsani)

## Icons
- icons: Add 'power' icon (Volker E.)


# 1.16.0 / 2024-11-13

## Features
- MenuButton: add support for custom menu items (lwatson)
- Field: add custom status message slots (lwatson)

## Styles
- tokens: Add new tokens for more interactive link styles (Derek Torsani)
- tokens: Adjust hover and active states for progressive and destructive (Derek Torsani)
- TablePager: Support CSS-only icons for pager buttons (Anne Tomasevich)
- Table: Support CSS-only sort icons (Anne Tomasevich)
- Table: Use colspan and basic table styles for empty state (Eric Gardner)
- tokens: Unify interactive styles phase 1 (Derek Torsani)

## New design tokens
- `color-visited--hover`
- `color-visited--active`
- `color-destructive--visited--hover`
- `color-destructive--visited--active`
- `background-color-interactive-subtle--hover`
- `background-color-interactive-subtle--active`
- `background-color-error-subtle--hover`
- `background-color-error-subtle--active`
- `border-color-interactive--hover`
- `border-color-interactive--active`
- `color-error--hover`
- `color-error--active`

## Code
- Lookup: Ensure input event on selection has correct value (Anne Tomasevich)

## Documentation
- docs: update the Figma library links (bmartinezcalvo)
- docs: Fix typo in field demo header (Anne Tomasevich)

# 1.15.0 / 2024-10-29

## Features
- Menu: Enable groupings of menu items (Anne Tomasevich)
- Menu components: Enable use of menu groups (Anne Tomasevich)

## Styles
- tokens: Apply new icon-status application tokens to infochip (Derek Torsani)
- tokens: Apply new icon-status application tokens to message (Derek Torsani)
- tokens: Update disabled tokens and add color-disabled-emphasized (Derek Torsani)
- tokens: Update red600 value and replace red600 application tokens with red700 (Derek Torsani)
- tokens: add and adjust status tokens (Derek Torsani)
- tokens: create border-color-error--active token (Derek Torsani)

## New design tokens
- `color-icon-error`
- `color-icon-warning`
- `color-icon-success`
- `color-icon-notice`
- `color-disabled-emphasized`

## Code
- IconLookup: Use Lookup component's `inputValue` prop (Anne Tomasevich)
- Table, i18n: improve caption message for a11y (lwatson)
- ToggleButton: add button type (lwatson)

## Documentation
- InfoChip: CSS-only demo with icon (lwatson)
- docs: ADR for native constraint validation (Anne Tomasevich)
- docs: Link to the menu group docs (Anne Tomasevich)
- docs: Set correct background color (Anne Tomasevich)
- docs: Update Lookup with suggestions demos to use groups (Anne Tomasevich)
- docs: implement new design for tokens table (lwatson)
- docs: include guidelines for grouping of items in a menu (bmartinezcalvo)
- docs: remove width and height from `thumbnail` type (lwatson)
- docs: reorganize VitePress override styles (lwatson)
- docs: update images using menus in the guidelines (bmartinezcalvo)

# 1.14.0 / 2024-10-16

## Features
- Menu: disable Tab keynav selection from multi-select menus (lwatson)
- Lookup: Add ARIA `role` to no result Lookup item (Arthur Taylor)
- LookupMultiselect, ChipInput: Improve floating menu UX (Anne Tomasevich)
- LookupMultiselect: Clear the input on selection (Anne Tomasevich)
- ChipInput: On chip click, show chip label or value (Anne Tomasevich)
- MultiselectLookup: Rename component (Anne Tomasevich)
- MultiselectLookup: Export component for use (Anne Tomasevich)
- Button: trigger actual click on key interaction (Anne Tomasevich)

## Styles
- styles, ChipInput: fix separate input's disabled state (lwatson)
- tokens: Amend focus colors for dark mode (Volker E.)
- ChipInput: Make input background transparent (Anne Tomasevich)

## Code
- Menu: minor update test file (lwatson)
- MultiselectLookup: Handle race condition when editing chip (Anne Tomasevich)

## Documentation
- Lookup, LookupMultiselect: Refactor demo pages (Anne Tomasevich)
- docs: include MultiselectLookup guidelines (bmartinezcalvo)
- docs: update Lookup guidelines (bmartinezcalvo)
- docs: Fix typo in Menu keyboard nav docs (Anne Tomasevich)
- docs: Add error and warning messages to LookupField (Anne Tomasevich)
- docs: Fix style class name in Developing Components guide (Volker E.)
- docs: Add error and warning messages to MultiselectLookupField (Anne Tomasevich)
- docs: Fix typo in MultiselectLookup field demo (Anne Tomasevich)

# 1.13.1 / 2024-10-01

## Features
- LookupMultiselect: Add WIP component (Anne Tomasevich)

## Styles
- tokens: Fix `outline-color-progressive--focus` value (Volker E.)

## Icons
- icons: Add new external link icon (lwatson)

## Code
- Menu: key nav scrolls menu items into view (lwatson)

## Documentation
- docs: Remove 'color update' banner from Colors page (Volker E.)
- Radio, Checkbox: Update status prop documentation (Anne Tomasevich)
- docs: minor updates to releasing.md (lwatson)

# 1.13.0 / 2024-09-17

## Features
- MenuItem: multiselect check icon (lwatson)
- Lookup: Only clear selection on input if there is one (Anne Tomasevich)
- Radio: Add error handling (Volker E.)
- tokens: Expand color palette (Derek Torsani)

## Styles
- Message: Prevent content from being wider than the container (Bartosz Dziewoński)
- styles, Thumbnail: set background color for visibility in dark mode (lwatson)

## Code
- build: Fix npm-shrinkwrap dependency (Volker E.)
- build: upgrade browserslist-db to 1.0.30001660 (Volker E.)

## Documentation
- docs: reorder MenuItem's config demo props (lwatson)
- docs: update menu item guidelines (bmartinezcalvo)
- docs: Update Colors page of the Style Guide (Anne Tomasevich)
- docs: Link to WebAIM using HTTPS (Lucas Werkmeister)
- demo, Radio, Checkbox: custom input CSS-only version (lwatson)
- docs: Add basic usage instructions for dark mode tokens (Eric Gardner)
- docs: Update RELEASING.md to include core Jest dependency updates (Volker E.)
- docs: Fix whitespace according to coding conventions and HTML5 syntax (Volker E.)
- docs: include error states in Combobox, Lookup, and Radio (bmartinezcalvo)
- docs: Update all color mentions to use the new color names (Volker E.)
- docs: Update images and illustrations to new colors (Volker E.)

# 1.12.0 / 2024-09-04

## Deprecating Changes
- This release deprecates the `initialInputValue` prop of the Lookup component.
  This prop will be removed in the next major release of Codex (v2.0). In the
  meantime it is recommended that users stop using this prop in their own code;
  the new `inputValue` prop should be used instead. See the Lookup component's
  documentation for more information.

## Features
- ChipInput: Add optional modelValue and chipValidator props (Anne Tomasevich)
- ChipInput: Add ARIA live region (Volker E.)
- Lookup: always clear the selection when the input changes (Anne Tomasevich)
- Lookup: Add optional inputValue and deprecate initialInputValue (Anne Tomasevich)
- demo, Combobox, Lookup: error state (lwatson)

## Styles
- Link: Don't accidentally style thumbnail placeholder icons (Anne Tomasevich)
- Table: Improve column headings with sort (Anne Tomasevich)
- Table: Align column heading content to the bottom (Anne Tomasevich)
- Message: Set icon height in ems (Anne Tomasevich)
- Generate bi-directional Codex stylesheets using PostCSS RTLCSS (Eric Gardner)

## Code
- Fix variable passing in branch-deploy.sh (Roan Kattouw)
- tests: Don't use .trigger( 'keydown.enter' ) (Roan Kattouw)
- Tabs: make 2-way binding of active tab optional (Eric Gardner)
- Lookup: Make unit tests more realistic when inputting text (Anne Tomasevich)

## Documentation
- RELEASING.md: Update instructions to add make-cdx command (Roan Kattouw)
- docs: update releasing docs (lwatson)
- docs: update MenuItem interaction states (bmartinezcalvo)
- docs: update Field guidelines (bmartinezcalvo)
- docs: Also protect against VitePress styles in dialogs (Roan Kattouw)
- docs: update design contribution guidelines (bmartinezcalvo)
- docs: Highlight deprecated props in the props table (Anne Tomasevich)
- docs: Fix typos in types.ts: correct minor documentation errors (Doğu Abaris)
- docs: Fix typo in constants.ts: correct "avialable" to "available" (Doğu Abaris)
- docs: Add ADR 11 (Eric Gardner)


# 1.11.1 / 2024-08-20

## Features
- tokens: Add color-base-fixed application token (Derek Torsani)
- Table: CSS-only Pagination (NunyaKlah)
- ButtonGroup, ToggleButtonGroup: handle arrow key navigation (Anne Tomasevich)

## Styles
- Table: sortable columns inherit font styles (Lauralyn Watson)

## Code
- i18n: Build a JSON file for use in MW from Codex message keys (Eric Gardner)
- Radio, Checkbox: clean up HTML (Lauralyn Watson)
- ChipInput.vue: rootElement.value can be undefined, use nullcheck (Daphne Smit)

## Documentation
- docs: Use Codex colors for links within content (Anne Tomasevich)
- docs: Add test for getFormattedDate (Anne Tomasevich)
- docs: Update ValidationMessages type to include new properties (Anne Tomasevich)
- docs: Add support for @default tag for props, and use in Table (Roan Kattouw)
- docs: Don't attempt to link to NaN as if it's a type (Roan Kattouw)
- docs: Limit width of some tables (Anne Tomasevich)
- docs: Use new `@default` tag for object props (Anne Tomasevich)
- docs: update the Config Demo to support Select/Dropdown (Lauralyn Watson)
- docs: Use select control in Tooltip demo (Anne Tomasevich)
- docs: Use "placement" in Tooltip docs (Anne Tomasevich)
- docs: Enable dark mode and mode switching (Anne Tomasevich)
- docs: Show dark mode colors in the color tokens demo (Anne Tomasevich)
- docs: Remove unneeded colors in Rules usage (Anne Tomasevich)

# 1.11.0 / 2024-08-07

## Features
- Basic Table pagination and TablePager component (Eric Gardner)
- Table: Server-side pagination (Eric Gardner)
- Radio, Checkbox: enable nested custom inputs (lwatson)

## Styles
- Table: Fix sort icon spacing (Anne Tomasevich)
- Table: Fix alignment of sort button contents (Anne Tomasevich)
- Button: Remove min-width for non-icon-only buttons (Anne Tomasevich)

## Code
- useI18nWithOverride: Add documentation comment (Roan Kattouw)
- useI18n: Use WatchSource instead of MaybeRef for parameters (Roan Kattouw)
- i18n: Make the system more type-safe (Eric Gardner)
- FloatingUI: Use default fallbackStrategy (Anne Tomasevich)

## Documentation
- docs: include interaction states guidelines for Table's pagination (bmartinezcalvo)
- Reapply "docs: include guidelines for custom input within Radio and Checkbox" (LWatson)
- Reapply "docs: include table pagination guidelines" (LWatson)
- demo, Checkbox, Radio: add validation to demos (lwatson)
- docs: Add test cases for button flex behavior (Anne Tomasevich)
- demo: Fix Combobox section markup to be valid (Volker E.)

## New i18n Messages
- cdx-table-pager-button-first-page
- cdx-table-pager-button-last-page
- cdx-table-pager-button-next-page
- cdx-table-pager-button-prev-page
- cdx-table-pager-items-per-page-current
- cdx-table-pager-items-per-page-default
- cdx-table-pagination-status-message-determinate-long
- cdx-table-pagination-status-message-determinate-short
- cdx-table-pagination-status-message-indeterminate-short
- cdx-table-pagination-status-message-indeterminate-long
- cdx-table-pagination-status-message-indeterminate-final
- cdx-table-pagination-status-message-pending


# 1.10.0 / 2024-07-25

## Features
- useI18n: Add useI18nWithOverride and use in SearchInput (Roan Kattouw)

## Styles
- Link: Use `:focus-visible` when links are focused by keyboard (Volker E.)

## Code
- Button, ToggleButton: improve keypress event handling (Eric Gardner)
- Dialog: restore focus to previously-focused element on close (Eric Gardner)
- Dialog, Label, Message: use useI18nWithOverride composable (lwatson)
- useI18n: Simplify unit tests (Roan Kattouw)

## Documentation
- Message: fix demo's usage of `allowUserDismiss` prop (lwatson)
- docs: clarify usage of ToggleButtonGroup and Tabs (bmartinezcalvo)
- demo: Remove wrong disabled Accordion example (Volker E.)
- docs: Fix links to TextInput props docs (Anne Tomasevich)
- docs: include guidelines for combobox types (bmartinezcalvo)
- docs: Add developer docs on i18n system (Anne Tomasevich)
- demo: Add Table demo with row actions (Anne Tomasevich)

# 1.9.0 / 2024-07-10

## Features
- useI18n: Add basic i18n system (Roan Kattouw)

## Styles
- tokens: Update diff colors (Derek Torsani)

## Code
- Tooltip: add snapshot tests (lwatson)
- TypeaheadSearch: replace `searchResultsLabel` prop with i18n (lwatson)
- Table: replace string props with i18n (lwatson)
- Dialog: Use i18n, add `useCloseButton` prop (Roan Kattouw)
- ChipInput, InputChip: Remove chipAriaDescription prop, replace with i18n (Roan Kattouw)
- Message: Add useI18n and update dismissButtonLabel prop (Anne Tomasevich)
- TypeaheadSearch, Search Input: add `useButton` prop (lwatson)
- Field, Label: use i18n, add `optional` prop (lwatson)
- css-icon: Add basic support for custom icons (Roan Kattouw)
- build: Add SVGO plugin to remove whitespace in icons `title` tag (Volker E.)

## Icons
- icons: Drop `fill-rule` argument from 'appearance' icon (thiemowmde)
- icons: Re-draw 'appearance' icon for most minimal file size (thiemowmde)

## Documentation
- docs: add i18n function type (lwatson)
- docs: Update Field guidelines (bmartinezcalvo)
- docs: Standardize descriptions of props with default strings (Anne Tomasevich)
- docs: clarify field's placeholder content (bmartinezcalvo)
- docs: add link to Codex Figma spec in the Table guidelines (bmartinezcalvo)
- docs: Elaborate on Tooltip guidelines (Derek Torsani)
- docs: Add ADR for i18n support (Anne Tomasevich)

# 1.8.0 / 2024-06-25

## Breaking Changes
- [BREAKING] tokens: Remove remaining deprecated `width-*` breakpoint tokens (Volker E.)
- [BREAKING] tokens: Remove CSS variables for border- tokens (Roan Kattouw)

## Features
- Table: add empty state (lwatson)
- Tooltip: Introduce WIP component (directive) (Eric Gardner)
- Menu, MenuItem: Add multiselect mode (Anne Tomasevich)
- Tooltip: Allow tooltip to persist on hover (Eric Gardner)
- Tooltip: add line-height (lwatson)
- Tooltip: move out of WIP (lwatson)

## Styles
- tokens: Update dark mode text tokens (Derek Torsani)
- styles, Tooltip: add animation and max-width (lwatson)
- css-icon: Don't crash when `@param-size-icon` is a non-standard value (Roan Kattouw)
- tokens: Use variable references in Sass, Less and full CSS outputs (Roan Kattouw)

## Code
- Remove redundant ‘https’ term in card.md (Cyndy)
- build: Update diff-release.sh for the .mjs -> .js rename (Roan Kattouw)
- ChipInput: Make chip descriptions optional (Anne Tomasevich)
- Menu: Refactor tests using given-when-then structure (Anne Tomasevich)
- Table: minor follow-ups (Anne Tomasevich)
- tokens: Use transform instead of custom formatter for Less with CSS vars (Roan Kattouw)

## Documentation
- docs, ChipInput: Remove remove-button-label prop from examples (Roan Kattouw)
- docs: Add recent authors (>1 commit) (Anne Tomasevich)
- docs: Document Jest pitfall around optional parameters (Roan Kattouw)
- docs, Tooltip: update demos (lwatson)
- Tooltip: Add basic guidelines (Eric Gardner)
- docs: Fix typo in Jest pitfall docs (Roan Kattouw)


# 1.7.0 / 2024-06-11

## Deprecating changes
- [DEPRECATING CHANGE] icons: Unify capitalization (Winston Sung)

This release renames `cdxIconWikiText` to `cdxIconWikitext`, and
`cdxIconNoWikiText` to `cdxIconNoWikitext`. The old names are deprecated,
but can still be used.

## Features
- MenuButton: Publish the MenuButton component (Lauralyn Watson)
- Radio: Add disabled+selected state styles (akiel)
- tokens: Update `content-added` and `content-removed` token values (Derek Torsani)

## Styles
- tokens: Make CSS variable output include references (Roan Kattouw)
- tokens: Don't reformat tokens without references (Roan Kattouw)

## Code
- build: Remove unneeded filename prefix (Anne Tomasevich)
- demos: Add Vue version of disabled Radio group (Anne Tomasevich)
- tokens: Move font-family theme tokens to work around SD bug (Roan Kattouw)
- generateMWExamples: Fix script on Windows (Bartosz Dziewoński)
- ToggleButton: Use component name in icon-only warning (Anne Tomasevich)
- MenuButton: update the maximum width (Lauralyn Watson)
- MenuButton: refactor the API for consistency (Lauralyn Watson)
- MenuButton: Remove unnecessary `../components/` in import paths (Roan Kattouw)

## Documentation
- docs: remove `export` from public types (Lauralyn Watson)
- docs: add MenuButton to Menu docs (Lauralyn Watson)
- docs: add MenuButton demos (Lauralyn Watson)
- docs: update "Content" recommendations (Bárbara Martínez Calvo)
- docs: clean up MenuButton demo files and demo order (Anne Tomasevich)
- docs: make small corrections in “Bidirectionality” guidelines (Bárbara Martínez Calvo)
- docs, MenuButton: demo a selection triggering an action (Lauralyn Watson)
- docs, MenuButton: update demos based on design feedback (Lauralyn Watson)
- docs: update MenuButton max-width in the guidelines (Bárbara Martínez Calvo)
- docs: update MenuButton's "Component limitations" guidelines (Bárbara Martínez Calvo)

# 1.6.1 / 2024-05-29

## Features
- Table: Update indeterminate behavior of "select all rows" checkbox (lwatson)
- Table: Update a11y text for sort order (Anne Tomasevich)
- Table: update select row label (lwatson)
- Table: update checkbox label in CSS-only version (lwatson)
- MenuButton: implement new component (Dan Andreescu)
- MenuButton: Minor code cleanup (Roan Kattouw)

## Styles
- Table, styles: Add transition properties to the sort button (lwatson)
- Table: Improve multiline sortable column headings (Anne Tomasevich)
- Table: fix border when hidden caption and vertical borders are enabled (lwatson)
- Table: Improve wrapping behavior of Table header (Anne Tomasevich)

## Code
- build: Make the prepare-release script pin the icons dependency (Roan Kattouw)
- build: Update eslint config for the correct Vue version (Roan Kattouw)
- build: Update Vue, Vite and Vitepress, and related dependencies (Eric Gardner)
- build: Publish the sandbox on doc.wikimedia.org (Roan Kattouw)
- build: Move "npm run branch-deploy" into its own bash script (Roan Kattouw)
- build: Upgrade browserslist-config-wikimedia to 0.7.0 (James D. Forrester)
- components: Remove unnecessary `../components/` in import paths (Roan Kattouw)

## Documentation
- demos: Clean up DemoBaseLayout code (Roan Kattouw)
- demos: Use DemoBaseLayout for the table demos page (Roan Kattouw)
- docs: include "Content overflow" guidelines (bmartinezcalvo)
- docs: improve titles within components guidelines (bmartinezcalvo)
- docs: Clean up borders post-VitePress-update (Anne Tomasevich)
- docs: Add syntax highlighting to code examples that didn't have it (Roan Kattouw)
- docs: Remove double `dir` attribute on sandbox subpages (Roan Kattouw)
- docs: update info in the design contribution guidelines (bmartinezcalvo)
- docs: fix image in the fade don't example (bmartinezcalvo)
- docs: include MenuButton guidelines (bmartinezcalvo)

# 1.6.0 / 2024-05-15

## Deprecating Changes
- [DEPRECATING CHANGE] Checkbox, Radio, ToggleSwitch: Always use Label component in CSS-only version (Roan Kattouw)

## Features
- Table: Move out of WIP (Anne Tomasevich)
- Table: Add slots for custom table elements (Anne Tomasevich)
- Table: Add basic CSS-only component and clean up markup (Anne Tomasevich)
- Table: CSS-only row header, vertical borders, hidden caption (lwatson)
- Table: Add guidelines (Anne Tomasevich)
- Table: sortable table headers wrap a button (lwatson)
- Table: Add `textAlign` option for numbers (Anne Tomasevich)
- Table: Enable sorting with row selection (Anne Tomasevich)

## Styles
- ChipInput, styles: Implement error-hover state (Volker E.)
- Table, styles: Use `color-subtle` for all sorting arrow icons (Volker E.)
- Table, styles: Apply `text-align` to `th` elements only (Volker E.)
- styles: prog/dest prim buttons should not invert icons in dark mode (Derk-Jan Hartman)
- styles: Don't wrap `kbd` text (Volker E.)
- tokens: Revise component tokens for Link to work with dark mode (Derek Torsani)
- tokens: Update blue800 value (Derek Torsani)

## Code
- Checkbox: Add name prop for submittable forms (akiel)
- Table: Clean up types and styles (Anne Tomasevich)
- Table: Fix sandbox demos using default slot (Anne Tomasevich)
- Table: Add demo page (Anne Tomasevich)
- Table: Add demo of CSS-only row selection (Anne Tomasevich)
- Table: Clean up styles and CSS classes (Anne Tomasevich)
- Table: Clean up alignment classes (Anne Tomasevich)
- Table: Miscellaneous cleanup (Roan Kattouw)
- Table: Hide visible caption from assistive technology (Anne Tomasevich)
- Table: fix caption when column headers with buttons are sortable (lwatson)
- Revert "Table: Add special class to force vertical borders" (Anne Tomasevich)
- demos: Remove `// @vue/component` from MW demos (Roan Kattouw)
- demos: add `scope` to table headers in TableWithSlots example (lwatson)
- sandbox: Centralize the header and layout into DemoBaseLayout (Derk-Jan Hartman)
- build: update vue-tsc dependency (Eric Gardner)
- build: Update @vitejs/plugin-vue (Eric Gardner)
- build: Introduce a general build command with reference (Volker E.)

## Documentation
- docs: update docker command in releasing doc (lwatson)
- docs, styles: Increase `z-index` on open flyout menu (nbarnabee)
- docs: include bidirectionality guidelines (bmartinezcalvo)
- docs: Expand BEM section on child element block naming logic (Volker E.)
- docs: Add spacing between paragraphs and lists in custom blocks (Anne Tomasevich)
- docs: Unify on "sub-components" (Volker E.)
- docs: include error-hover state in Select, ChipInput and TextArea guidelines (bmartinezcalvo)
- docs: Amend guideline references and AT notes (Volker E.)
- docs, vscode: add rewrap to recommended extensions (Volker E.)
- docs: fix "Interaction states" images in Select, ChipInput and TextArea (bmartinezcalvo)
- docs: Add reference to Table guidelines (Anne Tomasevich)
- RELEASING.md: LibraryUpgrader config repo has just moved to GitLab (James D. Forrester)

# 1.5.0 / 2024-04-30

## Features

### CSS variables and dark mode support
Codex design tokens and component styles now use CSS variables for all
color-related design tokens. This change has been made in order to provide
support for an alternate "dark mode" color palette. In
@wikimedia/codex-design-tokens, new files have been introduced to provide
default (light mode) and dark versions of Codex color tokens:
theme-wikimedia-ui-root.css and theme-wikimedia-ui-mode-dark.css, respectively.
The theme-wikimedia-ui.less file can continue to be used independently of these
new CSS variable files.

### Other features
- Table: add sort icon (lwatson)
- Checkbox: Allow label to be visually hidden (Anne Tomasevich)
- Table: Add prop to show vertical borders (Volker E.)
- Table: Add special class to force vertical borders (Anne Tomasevich)
- Table: Add width and minWidth to column definitions (Anne Tomasevich)
- Table: Add row selection (Anne Tomasevich)
- Table: add single sort (lwatson)

## Styles
- tokens: Add decision tokens for content added and removed (Derek Torsani)
- Select, TextArea, TextInput: Implement error hover feedback (Volker E.)

## Code
- build: update 'svglint' to latest (Volker E.)
- build, tokens: Remove all logic for the legacy build (Eric Gardner)
- build, tokens: Fold the "experimental" build into the standard build (Eric Gardner)
- build, tokens: provide a "mode reset" mixin (Eric Gardner)
- demo: Add dark mode toggle to sandbox (Roan Kattouw)
- demo: Add a framed toggle to the Tabs sandbox (Roan Kattouw)

## Documentation
- docs: Include new error-hover state in the “Interaction states“ image (bmartinezcalvo)
- docs: expand commands in RELEASING.md and build scripts (Volker E.)

# 1.4.0 / 2024-04-16

## BREAKING CHANGES
- [BREAKING] tokens: Remove deprecated breakpoint tokens (Volker E.)
- [BREAKING] tokens: Remove deprecated tokens without replacement (Volker E.)

This release removes the following deprecated design tokens:
- `background-color-primary--hover`
- `position-offset-input-radio--focus`
- `wmui-color-modifier-blue600-translucent`
- `width-breakpoint-desktop-wide`
- `width-breakpoint-desktop-extrawide`

## Features
- Field: Add additional class if it's a `fieldset` (Volker E.)

## Styles
- styles: adjust component colors for dark mode to work (lwatson)
- tokens, docs: Change any red focus color to progressive blue (Derek Torsani)
- tokens: Add `40rem` sizing token (Derek Torsani)
- tokens: Add dark mode decision tokens (lwatson)

## Code
- build: Remove needless Stylelint disable (Volker E.)
- build: Remove the demos build (Roan Kattouw)
- build: Update .nvmrc version from 18.17.0 to 18.20.2 (James D. Forrester)
- build: Wrap sandbox in a separate `<div dir="...">` (Roan Kattouw)

## Documentation
- docs, Dialog: Adjust spacing and guidance between buttons from 8 to 12px (Derek Torsani)
- docs: Add `kbd` styles to custom.css (Volker E.)
- docs: Add keyboard navigation in all components guidelines (bmartinezcalvo)
- docs: Unify alternative text across documentation (Volker E.)
- docs: Update "Guidelines" section in the "Contributing design" (bmartinezcalvo)
- docs: Update image in the InfoChip best practices (bmartinezcalvo)
- docs: fix comment on MediaWiki skin environment (Volker E.)
- docs: fix nested Fields vertical alignment (Volker E.)

# 1.3.6 / 2024-04-02
## Styles
- Field: Update spacing between fields from 24px to 16px (Roan Kattouw)
- [DEPRECATING CHANGE] tokens: Deprecate unused position-offset-input-radio--focus (Roan Kattouw)
- tokens: Add a new format for experimental CSS output (Eric Gardner)
- tokens: Use the built-in CSS formatter for the experimental build (Roan Kattouw)
- tokens: Rename experimental output to theme-codex-wikimedia-experimental (Roan Kattouw)
- tokens: Produce CSS vars file with overrides for dark mode (Eric Gardner)
- tokens: Add Less mixin version of dark mode build (Roan Kattouw)

## Icons
- icons: Add 'sortVertical' (Volker E.)

## Code
- Table: Make it possible to set text alignment per column (Anne Tomasevich)
- build: Fix bug in diff-release.sh caused by prepack/postpack scripts (Roan Kattouw)
- build: Make "legacy" build redundant (Eric Gardner)
- build: Update floating-ui to 1.0.6 (Roan Kattouw)
- build: Update vite to 4.5.2 (Roan Kattouw)
- build: Silence nonsensical CSS syntax errors (Roan Kattouw)

## Documentation
- demos: Remove superflous self-closing syntax (Volker E.)
- docs: Update components guidelines for Link, Button, and ButtonGroup components. (Bárbara Martínez Calvo)
- docs: Update components guidelines for Select, Combobox, TextInput, and TextArea. (Bárbara Martínez Calvo)
- docs: Remove the using.svg images from Button, ButtonGroup, and Link. (Bárbara Martínez Calvo)
- docs: Change spacing guidance on forms from 24 to 16 between fields (Derek Torsani)
- docs: Update components guidelines for Checkbox, Radio, and ToggleSwitch. (Bárbara Martínez Calvo)
- docs: Update components guidelines for SearchInput and TypeaheadSearch. (Bárbara Martínez Calvo)
- docs: Update components guidelines for Field, Label, Lookup, and ChipInput. (Bárbara Martínez Calvo)
- docs: Update components guidelines for Accordion, Card, Icon, InfoChip, and Thumbnail. (Bárbara Martínez Calvo)
- docs: Update components guidelines for ToggleButton and ToggleButtonGroup. (Bárbara Martínez Calvo)
- docs: Update components guidelines for Message and Dialog. (Bárbara Martínez Calvo)
- docs: Update components guidelines for Menu, MenuItem, Tabs, Tab, ProgressBar. (Bárbara Martínez Calvo)
- docs: Add note about limited use of SearchInput in Field (Volker E.)
- docs: Fix links to validation section in Field docs (Roan Kattouw)

# 1.3.5 / 2024-03-19
## Styles
- styles: spacing between adjacent Field components (lwatson)
- tokens: Add area background color tokens and apply (Volker E.)

## Code
- build: update browserslist-config-wikimedia to latest version 0.6.1 (Volker E.)
- build: Token reorganization part 2 (Eric Gardner)
- Table: Add initial, WIP component (Anne Tomasevich)
- Table: Set min-height on footer (Anne Tomasevich)
- tokens: expand the option tokens color palette (lwatson)
- tokens: Make sure tokens in the JS output are always strings (Roan Kattouw)

## Documentation
- Field: Remove unused status class from CSS-only Field (Roan Kattouw)
- Field: Remove unused cdx-field__control--has-help-text CSS class (Roan Kattouw)
- docs: Add ADR for  CSS icon system (Anne Tomasevich)
- docs: add Patchdemo instructions to the releasing doc (lwatson)
- docs: unify link name (Volker E.)
- docs: Unify casing of component descriptions (Volker E.)

# 1.3.4 / 2024-03-05

## Features
- Lookup: menu is closed if there are no menu items (lwatson)

## Styles
- Select: Use background rules for the CSS-only icon (Anne Tomasevich)
- Icon, Select: Use escape() for escaping colors, now that Less.php supports it (Roan Kattouw)

## Code
- build: add .npmrc to all packages (Volker E.)
- deps: shrinkwrap dependencies (Eric Gardner)
- build: Remove codex-search build (Ty Hopp)
- build: use prepack/postpack to manage extra files (Eric Gardner)
- build: Re-organize Codex design tokens (Eric Gardner)
- Revert "docs: Fix mobile menu presentation" (Eric Gardner)

## Documentation
- docs: Remove internal links from "Additional Resources" (Volker E.)
- docs: add guidelines for Lookup with initial suggestions (bmartinezcalvo)
- ADR 8: Color modes and token organization (Eric Gardner)
- docs: Add forms guidelines to Codex style guide (Derek Torsani)

# 1.3.3 / 2024-02-20

## Styles
- buttons: Use `inline-flex` for content (Anne Tomasevich)
- Icon, styles: Use mask-image for all CSS icons (Anne Tomasevich)
- styles: Remove uses of Less's fade() function (Anne Tomasevich)
- build, tokens: Add "experimental" tokens and stylesheets (Eric Gardner)

## Code
- Lookup: Enable an initial list of suggestions (Anne Tomasevich)
- tokens: Remove leading space in tab-list-item tokens (Roan Kattouw)
- build: Respect CODEX_DOC_ROOT when building the sandbox (Roan Kattouw)
- build: Updating netlify-cli to 15.11.0 (libraryupgrader)
- build: update 'browserslist-config-wikimedia' to latest v0.6.0 (Volker E.)
- build: Unify SVGO configurations (Volker E.)
- build: update 'less', 'postcss' and 'autoprefixer' to latest versions (Volker E.)
- build: update 'style-dictionary' to latest 3.9.2 (Volker E.)
- build: Automatically add all demos/*.html files to the sandbox build (Roan Kattouw)

## Documentation
- docs: add Components Guidelines when designing components (bmartinezcalvo)
- docs: Add ES6 vars usage ability to formats (Volker E.)
- docs: Add other statuses to the Field configurable demo (Anne Tomasevich)
- docs: Make really long text in InfoChip demo longer (Bartosz Dziewoński)
- docs: Fix mobile nav bar font size (Volker E.)
- docs: Add "no results" message to Lookup demo (Anne Tomasevich)
- docs: Fix mobile menu presentation (Volker E.)
- docs: Fix mobile 'on this page' navigation styling (Volker E.)
- docs: Add conclusion to Wikipedia Apps guidelines (Volker E.)
- docs: Style tables in the demo pane (Anne Tomasevich)
- docs: Document the CSS-only InfoChip (C. Scott Ananian)

# 1.3.2 / 2024-02-06

## Styles
- Accordion: Update focus styles and docs image alt text (Eric Gardner)
- TextInput, Select: reduce base height (lwatson)
- styles, Field: Fix and simplify spacing of help text (Anne Tomasevich)

## Code
- build, tokens: Add a custom "camelCaseNegative" transformer (Eric Gardner)
- build: Reorganize custom style-dictionary methods (Eric Gardner)
- build: Make diff-css.sh show uncommitted changes (Roan Kattouw)
- build: Update rtlcss and postcss-rtlcss (Roan Kattouw)
- build: Upgrade Stylelint dependencies and 'postcss-html' to latest (Volker E.)

## Documentation
- docs: Align h2 anchor links with heading text (Anne Tomasevich)
- docs: Add interaction guidelines in style guide (Derek Torsani)
- docs: Rename to Design System Team (singular) (Volker E.)
- docs: Style Accordion content image (Anne Tomasevich)

# 1.3.1 / 2024-01-24

## Code
- build: Point "main" to the CJS file rather than the ESM file (Roan Kattouw)

# 1.3.0 / 2024-01-23

## Note
As of this release, all the Codex packages have been migrated over to
ESM (and have "type: module" set in their respective package.json files).

## Features
- Accordion: Use <details> element for markup (Eric Gardner)
- Accordion: Enable and demonstrate CSS-only usage (Eric Gardner)
- Accordion: Remove click handler, work around test brokenness (Roan Kattouw)
- Field: Enable use of warning and success messages (Anne Tomasevich)
- TextArea: Add CSS-only version (lwatson)
- TextArea: CSS-only version follow-up (lwatson)

## Styles
- TypeaheadSearch, styles: Increase specificity of menu CSS overrides (Eric Gardner)
- styles, Card: Fix CSS-only Card with icon (lwatson)
- styles, token: Add new border color token for "media" borders (lwatson)
- styles: Apply border-color-muted in Codex (lwatson)
- styles: Fix boldening of Labels (Volker E.)

## Code
- build, tokens: Export design tokens as ES6 variables (Anne Tomasevich)
- build: Migrate @wikimedia/codex package to ESM (Eric Gardner)
- build: Migrate codex-design-tokens package to ESM (Ty Hopp)
- build: Migrate codex-icons package to ESM (Eric Gardner)
- build: Pin dependencies to exact versions (Volker E.)
- build: Update 'vite' to latest 4.n version (Volker E.)
- build: Update .browerslistrc reference to upstream (James D. Forrester)

## Documentation
- docs: Fix code color regression and contrast issue (Volker E.)
- docs: Remove VRT from releasing docs (Anne Tomasevich)
- docs: fix typo and expand abbreviations in Radio & Voice and tone (Volker E.)
- docs: improve progress bar elevated asset (Volker E.)
- docs: update URL to token typography in Codex (lwatson)

# 1.2.1 / 2024-01-09

## Styles
- styles: Increase CSS specificity of subcomponents (lwatson)

## Code
- build: bump expected node from 16 to 18 everywhere (Volker E.)
- build: Update 'svgo' to v3.0.5 (Volker E.)
- deps: Update vitepress and vue-docgen-cli (Eric Gardner)
- build: Update custom SVGO plugin to conform to public API (Eric Gardner)
- build: Remove `<!-- comments -->` from build output (Roan Kattouw)
- build: Update 'svgo' to latest v3.2.0 (Volker E.)
- build: Update 'browserslist-config-wikimedia' to latest (Volker E.)
- tokens, build: Remove deprecated WikimediaUI Base aliases and its build (Volker E.)

## Documentation
- docs: Redirect from old "adding icons" URL to the new one (Roan Kattouw)
- docs: Add v-model to TextInput demos (lwatson)
- docs, Field: Flatten fieldset examples and cross-link them (Roan Kattouw)
- docs: Standardize InfoChip demo page (Anne Tomasevich)
- docs: Isolate styles in the demo pane (Anne Tomasevich)
- docs: Improve the "adding new icons" docs (Anne Tomasevich)
- docs: Move the "adding new icons" page (Anne Tomasevich)

# 1.2.0 / 2023-12-19

## Features
- docs: Add illustration guidelines (Derek Torsani)

## Styles
- styles: Replace falsely applied `font-size-base` with `font-size-medium` (Volker E.)

## Code
- build: Migrate codex-docs package to ESM (Eric Gardner)
- build: Upgrade expected node from 16 to 18 (James D. Forrester)
- build: Make modular build entries explicit (Ty Hopp)
- build: Make icons explicit runtime dep of codex package (Ty Hopp)
- build: Automatically update codex-icons dependency for new releases (Roan Kattouw)
- build: Rerun npm install after switching branches in diff-css.sh (Roan Kattouw)
- nvmrc: Update to Node 18 now we've moved CI (James D. Forrester)

## Documentation
- docs: Adjust sidebar outline marker size (Ty Hopp)
- docs: Fix minor issues in 4 component images (Volker E.)
- docs: Use Less variable in order to have rules applied (Volker E.)

# 1.1.1 / 2023-12-06

## Code
- build: Don't remove manifest-rtl.json etc from the output (Roan Kattouw)
- build: Pin style-dictionary to 3.8.0 (Eric Gardner)

# 1.1.0 / 2023-12-05

## Features
- Select: Prevent Space key from scrolling the page (lwatson)
- useFloatingMenu: Add clipping and flipping (Roan Kattouw)
- Menu: Improve scrolling behavior to play nice with useFloatingMenu (Roan Kattouw)

## Styles
- TextInput: Move border-radius from `<input>` to root element (Roan Kattouw)
- Menu: Update border-radius when Menu flips (Roan Kattouw)
- Menu: Fix footer item being too wide (Roan Kattouw)
- useFloatingMenu: Also apply padding to flip() (Roan Kattouw)

## Icons
- icons: Add 'appearance' icon (Derek Torsani)

## Code
- build: Deduplicate build emissions (Ty Hopp)
- build: update 'svgo' & 'svglint' dependencies (Volker E.)
- build: Update eslint-config-wikimedia 0.25.1 -> 0.26.0 (Roan Kattouw)
- deps: Update Vue to 3.3.9 and lock key package versions (Eric Gardner)

## Documentation
- docs: Add Rules component for dos and dont's (Anne Tomasevich)
- docs: Add Codex CSS import methods to usage page (Ty Hopp)
- docs: Clarify required packages to install (Ty Hopp)
- docs: Update readme files to reference usage docs (Ty Hopp)
- docs: Add content guidelines (Anne Tomasevich)
- docs: Update build products for icons package (Roan Kattouw)
- Menu, docs: Use useFloatingMenu in all Menu demos (Roan Kattouw)
- demo: Add menu with footer to the DialogDemo (Anne Tomasevich)
- docs: Misc fixes for content guidelines (Derek Torsani)
- docs: Update AUTHORS.txt (Anne Tomasevich)

# 1.0.1 / 2023-11-07

## Features
- MenuItem: Ensure proper hyphenation and wrapping of long words (Anne Tomasevich)

## Styles
- Tabs: Update margin of frameless/quiet tabs (lwatson)
- tokens: Add explanatory comment for `font-size-base` (Volker E.)
- tokens: fix self-references in deprecated `box-shadow-` aliases (Volker E.)

## Icons
- icons: optimize 'qrCode' and 'userRights' code (Volker E.)

## Code
- build: Update browserslist database to latest (Volker E.)
- build: Prepare codex-icons for Jest migration (Roan Kattouw)
- build: Update TypeScript, eslint, jest, etc (Roan Kattouw)
- build, tokens: add custom file header representing “Codex Design Tokens” (Volker E.)

## Documentation
- docs: Hide the social link flyout menu (Anne Tomasevich)
- docs: Improve tokens table name cell layout on mobile (Anne Tomasevich)
- docs: Add ID to each token's name cell (Anne Tomasevich)
- docs, styles: Replace fallback sans with Codex font stack choice (Volker E.)
- docs: Optimize formerly oversized and binary containing SVG images (Volker E.)
- docs: Add CSS grid layout to Codex (lwatson)
- docs: Move Apps guidelines section from DSG to Codex (lwatson)
- docs: Optimize Apps section's PNGs (Volker E.)
- docs: improve readability of Wikimedia Apps guidelines (Volker E.)
- docs: Reduce size of 'text-area-types-*.svg' (Volker E.)
- docs: Update contributing page Phabricator links (Ty Hopp)
- docs: Reference to “Apps” as “Wikipedia Apps” (Volker E.)

# 1.0.0 / 2023-10-24

## Code
- build: Don't minify the ESM library build (Roan Kattouw)

## Documentation
- docs: Fix broken Dialog demo (lwatson)
- docs: Remove actions from Field type (Volker E.)
- docs: Use CSS multi-column layout to display interaction states (lwatson)
- docs: Export useFloatingMenu composable (lwatson)
- docs: Document Codex Composables (lwatson)
- docs: Update AUTHORS.txt for 1.0 release (Volker E.)

# 1.0.0-rc.1 / 2023-10-12

## Code
- build: Update 'svglint' and 'glob' packages (Volker E.)
- build: Make svglint check for correct XML declaration (Volker E.)
- build: Add SVGO & svglint to 'codex-docs' (Volker E.)

## Documentation
- docs: Remove "Usage" heading from component pages (Anne Tomasevich)
- docs: Move Vue-specific component documentation (Anne Tomasevich)
- docs: Move Vue-specific component documentation, part II (Volker E.)
- docs: Move Vue-specific component documentation, part III (Volker E.)
- docs: Move Vue-specific component documentation, part IV (lwatson)
- docs: Rename "states" to "interaction states" in docs (Volker E.)
- docs: Expand abbreviations in ADR 07 (Volker E.)
- docs: Add 'Fira Code' monospace font to typography guide (Volker E.)
- docs: Add existing component guidelines image assets (Volker E.)
- docs: Add new component guidelines image assets (Volker E.)
- docs: Add background shade to asset images (Volker E.)
- docs: Don't use SFC comments in component documentation pages (Eric Gardner)
- docs: Move existing guidelines of Combobox, TextInput, Message, and Dialog (lwatson)
- docs: Create design guidelines for ToggleButton, ToggleButtonGroup, and TypeaheadSearch (lwatson)
- docs: Create design guidelines for Tab, Tabs, TextArea and Thumbnail (Volker E.)
- docs: Create design guidelines for Label, Lookup and Menu (Volker E.)
- docs: Create design guidelines for MenuItem, ProgressBar and SearchInput (Volker E.)
- docs: Move existing guidelines of Checkbox, Radio, Select, and ToggleSwitch (lwatson)
- docs: Create design guidelines for Accordion, Card and ChipInput (Volker E.)
- docs: Move existing guidelines of Button, ButtonGroup and Link (Volker E.)
- docs: Fix minor layout issues and missing links (Volker E.)
- docs: Create design guidelines for Field, Icon and InfoChip (Volker E.)
- docs: Update Menu and Lookup component assets (Volker E.)
- docs: Fix component guideline images by removing backgrounds and borders (Volker E.)
- docs: Follow-up fixes including more links, consistent style (Volker E.)
- docs: Asset images hot fixes for component guidelines (Volker E.)

# 0.20.0 / 2023-09-26

## Features
- Combobox, Lookup, Select: Use FloatingUI (Anne Tomasevich)

## Styles
- Add cdx-no-invert class to thumbnails (Ed Sanders)
- Checkbox: Add error-active state (lwatson)
- TextArea: Update CSS display property (lwatson)
- icons: Remove unnecessary `standalone` SVG attribute (Volker E.)

## Code
- More consistently use async/await with nextTick() (Roan Kattouw)
- ChipInput: Add input text as chip when focus leaves component, not input (Roan Kattouw)
- ToggleSwitch, docs: Remove leftover classes from label content (Volker E.)
- build: Update versions of packages (Roan Kattouw)
- build: Update 'stylelint-config-wikimedia' and 'stylelint' plugins (Volker E.)
- build: Update 'eslint-config-wikimedia' and remove 'eslint' (Volker E.)
- build: Disable security/detect-non-literal-fs-filename (Eric Gardner)
- build: Prefer CJS build to UMD for most usage (Eric Gardner)
- build: Generate Codex bundles for every component (Eric Gardner)
- build: Configure eslint to lint .mjs files properly (Roan Kattouw)
- build: Remove codex-search workspace (lwatson)
- build: Make the build config the primary tsconfig.json file (Roan Kattouw)

## Documentation
- docs: Style tweaks to character counter example for Field component (Eric Gardner)
- docs: Update token table style and add caption (Volker E.)
- docs: Fix designing icons Phabricator template link (Volker E.)
- docs: Limit headings in page nav to level 2 and 3 in components (Volker E.)
- docs: Add ADR 07: Floating UI (Eric Gardner)

# 0.19.0 / 2023-09-12

## Features
- FilterChip, FilterChipInput: Rename to ChipInput and InputChip (Anne Tomasevich)
- ChipInput: Enable in Field and add form field demo (lwatson)
- ChipInput: Make inputChips prop required (Roan Kattouw)
- ChipInput: Add ARIA attributes (Roan Kattouw)
- ChipInput: Add keyboard navigation (Roan Kattouw)
- ChipInput: Release MVP component (Anne Tomasevich)
- InputChip: Remove now-unused generated ID on the remove button (Roan Kattouw)
- Checkbox: Implement the error state (lwatson)
- Dialog: Put all on-open code together, and run it on mount if needed (Roan Kattouw)
- Dialog: Set aria-hidden and inert on the rest of the page when open (Roan Kattouw)

## Styles
- Field: Add CSS-only version (Anne Tomasevich)
- Label: Add CSS-only version (Anne Tomasevich)
- TextInput, Combobox: Set consistent min-width regardless of icons (Roan Kattouw)
- TextInput: Fix padding when both clear button and end icon appear (Roan Kattouw)
- styles, ChipInput: Increase gap between chips (Anne Tomasevich)
- styles, Field: Unset margin on the root element (Anne Tomasevich)
- tokens: Rename error hover tokens to stay consistent (Volker E.)

## Code
- sandbox: Add ChipInput (Roan Kattouw)
- build: Make mw-examples:clean clean up better (Roan Kattouw)

## Documentation
- docs, Checkbox, Radio: Use CSS-only Field and Label (Anne Tomasevich)
- docs: Add a Field demo showing a custom character counter (Eric Gardner)
- docs: Fix styles for custom Dialog example (Roan Kattouw)
- docs: Improve accessibility purpose statements (Volker E.)
- docs: Move Wrapper teleport target to work around VitePress bug (Roan Kattouw)
- docs: Use deep outline on component pages (Anne Tomasevich)
- docs: add `sandbox:` prefix to contributing code commits docs (Volker E.)

# 0.18.0 / 2023-08-29

## Breaking changes
- [BREAKING CHANGE] Radio: Made the name prop of the Radio component required (didier-ds)
    - Previously, the CdxRadio component could be used without passing in the
      "name" prop. This prop is now required, and a warning will be emitted
      if it is not passed in.
- [Breaking] Dialog: incorporate Vue's <teleport> feature (Eric Gardner)
    - Previously, dialogs were rendered in-place, and centered on the page
      using absolute positioning. Now, all dialogs are teleported to the
      bottom of the <body> by default. This may cause styling differences,
      because the styles that would apply to a dialog rendered in place may
      not apply to a teleported dialog. To address styling issues, applications
      may have to change CSS selectors that target dialog contents, and/or
      provide() a teleport target container for dialogs to be teleported into.

## Features
- ToggleButton: Add icon-only detection (Roan Kattouw)
- ProgressBar: Add `aria-label` to component and demos (Volker E.)
- Menu: Add ARIA live region when menu items are pending (Volker E.)

## Styles
- Radio: Add focus styles for unchecked radios (Anne Tomasevich)
- FilterChip: Add interactive styles (Anne Tomasevich)

## Code
- Checkbox: Remove enter keypress handler to trigger click event (Volker E.)
- codex, utils: Fix typo in stringTypeValidator (Volker E.)
- Button, Tabs: Factor out common slot content analysis code (Roan Kattouw)
- Button: Move icon-only check into its own composable (Roan Kattouw)
- useIconOnlyButton: Factor out warn-once logic into new useWarnOnce composable (Roan Kattouw)
- useLabelChecker: Reimplement using useWarnOnce and useSlotContents (Roan Kattouw)
- Combobox: Fix typo in screen reader comment (Volker E.)
- FilterChip: Add click-chip event and change keyboard behavior (Anne Tomasevich)
- FilterChipInput: Make chips editable; add chip on blur (Anne Tomasevich)
- FilterChipInput: Ensure error state is unset (Anne Tomasevich)
- Field: Improve reactivity for input ID and description ID (Roan Kattouw)

## Documentation
- Dialog: Fix target types and clarify teleport documentation (Roan Kattouw)
- docs: Use consistent casing for "design tokens" (Anne Tomasevich)
- docs: Update and improve php commands in RELEASING (Anne Tomasevich)
- docs: Unify on “TypeScript” (Volker E.)
- docs: Update broken links (Martin Urbanec)
- docs: Fix "Copy code" button, make it work with code groups (Roan Kattouw)
- docs: Fix reference to origin/master in Codex release docs (Catrope)

# 0.17.0 / 2023-08-16

## Features
- Menu: Don't set aria-activedescendant when the menu is closed (Roan Kattouw)
- Menu: Always clear the highlighted state when the menu closes (Roan Kattouw)
- Menu, Select: Allow keyboard navigation through typing (Roan Kattouw)
- Dialog: Add `aria-modal="true"` to the dialog element (Volker E.)

## Styles
- tokens: Remove min-size-base, make it a deprecated alias (Roan Kattouw)

## Code
- Menu: Simplify logic for highlighting selected item (Roan Kattouw)

## Documentation
- docs, Field: Fix Label usage in demo (Anne Tomasevich)
- Menu, docs: Document ARIA attributes and add them to all examples (Roan Kattouw)
- docs, ToggleButton: Remove dynamic label examples (Anne Tomasevich)
- docs, Menu, MenuItem: Use proper HTML structure for demos (Anne Tomasevich)
- docs, Link: Improve demo text (Anne Tomasevich)
- docs, Button: Add docs on role="button" for fake buttons (Anne Tomasevich)
- demo: Add an exhaustive table of all icons (Roan Kattouw)
- demo: Move grid of all buttons to a separate page (Roan Kattouw)
- docs: Fix alphabetical order of sidebar items (Anne Tomasevich)
- docs: Clarify use of role="button" and remove from label demos (Anne Tomasevich)
- docs: Brand the main site as "beta" (Anne Tomasevich)
- docs: Change background color of beta tag (Anne Tomasevich)
- Checkbox, Radio, ToggleSwitch: Improve group docs and demo pages (Anne Tomasevich)
- docs, Checkbox: Remove CSS-only indeterminate checkbox (Anne Tomasevich)
- docs: Add MediaWiki versions of inlined Less examples (Roan Kattouw)
- docs: Add more descriptive `aria-label` to GitHub link (Volker E.)
- docs: Improve beta tag design (Anne Tomasevich)
- docs: Standardize design tokens and components overview format (Anne Tomasevich)
- docs: Improve CSS-only button and checkbox docs (Anne Tomasevich)
- docs: Standardize capitalization of "design tokens" (Anne Tomasevich)
- docs: Shorten link text on design tokens overview page (Anne Tomasevich)
- docs: Improve release process docs for visual regression tests (Roan Kattouw)

# 0.16.1 / 2023-08-01
## Styles
- Tabs: Override browser default styles for <button> (Roan Kattouw)

# 0.16.0 / 2023-08-01

## Breaking
- Tabs: Align markup closer to APG example (Eric Gardner)
  - This changes the HTML markup for the CSS-only version of the Tabs component uses.
    Users of the CSS-only Tabs component must update the HTML they output to the
    new version to continue to get the correct styling.

## Features
- ToggleSwitch: Fix label hover behavior (Anne Tomasevich)
- Label, Field: Do not render description unless it exists (Anne Tomasevich)
- Checkbox, Radio, ToggleSwitch: Warn when input is not labelled (Anne Tomasevich)
- Message: Don't allow error messages to be auto-dismissed (Roan Kattouw)

## Styles
- tokens: Slightly darken color-red600 to improve contrast (Roan Kattouw)
- tokens: Use the color-link-red tokens for red links (Roan Kattouw)
- tokens: Consistently use -error tokens for error state (Roan Kattouw)

## Code
- build: Also check .mjs files with TypeScript (Roan Kattouw)
- Checkbox, Radio, ToggleSwitch: Use Label internally (Anne Tomasevich)
- Label: Make <legend> the root element (Anne Tomasevich)
- ToggleSwitch: Use role="switch" (Anne Tomasevich)
- Icon: Remove click handler (Anne Tomasevich)
- Combobox, Lookup, Select, TypeaheadSearch: Clean up ARIA attributes (Roan Kattouw)
- code: Add component name to warnings (Anne Tomasevich)

## Documentation
- docs: Limit code examples to plain JS (no TypeScript) and ES6 (Roan Kattouw)
- docs: Remove margin and border-radius above code groups (Anne Tomasevich)
- docs, build: Generate MediaWiki-targeted versions of every code example (Roan Kattouw)
- docs, Radio: Don't use the same name attribute for multiple demos (Roan Kattouw)

# 0.15.0 / 2023-07-18

## Features
- Accordion: Remove "disabled" property for now (Eric Gardner)
- Accordion: Move from WIP components to public components (Roan Kattouw)
- FilterChip, FilterChipInput: Add WIP components to Codex (Julia Kieserman)
- FilterChipInput: Add variant with separate input (Anne Tomasevich)

## Styles
- Message: Remove top and bottom margins from first/last content children (Eric Gardner)
- Add horizontal centering to fake button styles (bwang)

## Code
- Accordion: Improve tests (Roan Kattouw)
- Accordion: Make tests not throw warning about missing icon label (Roan Kattouw)

## Documentation
- docs: Add demo table for icon-only link buttons with small icons (Anne Tomasevich)

# 0.14.0 / 2023-07-05

## Features
- ToggleSwitch: Fix layout of CSS-only version (Anne Tomasevich)
- TextInput: Add `readonly` styling and config demo (Volker E.)
- Icon: Make <use> work in CSS-only icons (Roan Kattouw)

## Styles
- styles, Button, Message: Remove some `:not()` selectors (Anne Tomasevich)
- Button, styles: Remove :not() selectors for button size (Roan Kattouw)
- Accordion: Simplify styles and add comments (Roan Kattouw)

## Icons
- Icons: add QR code icon (MusikAnimal)
- Icons: Add user rights icon to Codex (LWatson)

## Code
- build: Don't make stylelint require tokens where they don't exist (Roan Kattouw)
- demo: Refactor ButtonDemo and improve coverage (Roan Kattouw)
- Accordion: changes after design review (szymonswiergosz)
- Accordion: ARIA fixes and improvements (Eric Gardner)

## Documentation
- TextArea: Add resize browser support warning to Codex docs (LWatson)
- docs, ToggleSwitch: Use unique IDs in CSS-only ToggleSwitch examples (Roan Kattouw)
- Icon, docs: Use "code" instead of "tag" in CSS-only icon demo (Roan Kattouw)
- docs: Remove unused/unnecessary styles in demos (Roan Kattouw)
- lib: Export IconSize type (Roan Kattouw)
- docs: Rewrite useModelWrapper docs (Roan Kattouw)

# 0.13.0 / 2023-06-20

## Features
- Label: Add Label component (Anne Tomasevich)
- Field: Add Field component and enable use with input components (Anne Tomasevich)
- Field: Set help text line height and improve demos (Anne Tomasevich)
- Label: Change line height and add a rich text demo (Anne Tomasevich)
- TextArea: Change icon in demo (LWatson)
- Field, Label: Set smaller line height (Anne Tomasevich)
- TextArea: Move from WIP components to public components (LWatson)
- Field et al: Add demos for supported components (Anne Tomasevich)
- TextArea: Enable use with Field component (Anne Tomasevich)

## Styles
- Button: Organize and correct padding styles (Anne Tomasevich)
- Dialog: Simplify padding styles (Roan Kattouw)
- TextArea: Remove error styles from readonly (LWatson)
- TextArea: Move CSS overrides to mixin (LWatson)
- Button, styles: Fix bare :not() selector (Roan Kattouw)

## Code
- build: Update doc comment in codex-docs postcss config (Roan Kattouw)
- build: Make svglint rules stricter (Roan Kattouw)

## Documentation
- docs: Update release instructions for foreign-resources version field (Roan Kattouw)
- Tabs: Don't set role="tab" on <li>s in CSS-only markup (Roan Kattouw)
- Tabs, docs: Don't show Vue code in the CSS-only HTML example (Roan Kattouw)
- Tabs: Fix CSS-only example for disabled tabs (Roan Kattouw)
- docs: Show a more realistic link button example (Anne Tomasevich)
- TextArea: Demonstrate more examples on the Codex docs (LWatson)
- docs: Add section about linting to the "Contributing code" docs (Roan Kattouw)
- docs: Remove label button example (Anne Tomasevich)
- docs: Apply rtlcss ([dir] selectors) to component demo examples (Roan Kattouw)
- Fix tokens package name (Lucas Werkmeister)

# 0.12.0 / 2023-06-06
## Features
- Accordion: initial implementation of accordion component (szymonswiergosz)
- Accordion: Add ARIA-labels for icon-only buttons (Eric Gardner)
- Accordion: Remove unused CSS rule (Roan Kattouw)
- Button: Add classes to support CSS-only non-button buttons (bwang)
- Combobox, Lookup, SearchInput: emit input events (Anne Tomasevich)
- Dialog: automatically display dividers when content is scrollable (Eric Gardner)
- Select: Add status prop (Anne Tomasevich)
- Tab: Don't output `aria-hidden` attribute  with "false" values (Eric Gardner)
- Tabs, styles: Deduplicate tab styles (Roan Kattouw)
- TextArea: Add config demo to docs page (LWatson)
- TextArea: Add startIcon and endIcon props (LWatson)
- TextArea: Refactor autosize (LWatson)
- TextInput: expose a public blur() method (Eric Gardner)
- ToggleSwitch: Enable and demonstrate switch groups (Anne Tomasevich)

## Styles
- tokens: Move `min-size-interactive*` and deprecate `min-size-base` token (Volker E.)

## Icons
- icons: Update 'userTemporary' to latest design (Volker E.)

## Code
- Remove unnecessary uses of toRefs() and toRef() (Roan Kattouw)
- build, styles: Add `grid` properties to 'properties-order' (Volker E.)
- build, styles: Enable `*width*` and `*height*` declaration strict value (Volker E.)
- build: Add `gap` to 'declaration-strict-value' (Volker E.)
- build: Add `justify-items` and `justify-self` to 'properties-order' (Volker E.)
- build: Enable `box-sizing` declaration strict value (Volker E.)
- build: Update netlify-cli from 10.10.2 to 15.1.1 (Roan Kattouw)
- build: Use correct name for `isolation` property (Volker E.)

## Documentation
- demo: Hide empty table headers from AT (Volker E.)
- docs: Add ADR for Field component implementation (Anne Tomasevich)
- docs: Add coded typography example (Anne Tomasevich)
- docs: Fix typo in “VitePress” (Volker E.)
- docs: Fix typos in 'CHANGELOG.md' and 'RELEASING.md' (Volker E.)
- docs: Make CSS Tabs demo require scroll (Anne Tomasevich)
- docs: Make box-shadow-color demo more consistent with box-shadow demo (Roan Kattouw)
- docs: Replace deprecated `line-height-x-small` token (Volker E.)
- docs: Replace protocol-relative URLs with 'https://' only (Volker E.)

# 0.11.0 / 2023-05-23
## Features
- Button: Add `size` prop (Anne Tomasevich)
- TextArea: Add fixedHeight and autosize props (LWatson)

## Styles
- Button: Update flush mixin to handle button sizes (Anne Tomasevich)
- Combobox, styles: Apply combined minimum width to Combobox (Volker E.)
- Icon: Fix flush mixin and CSS-only icon button mask styles (Anne Tomasevich)
- link, styles: Replace SFC variables with tokens (Volker E.)
- Select: Set opacity of 1 for disabled CSS select (Anne Tomasevich)
- Tabs, styles: Use appropriate box-shadow color tokens (Volker E.)
- tokens: Add `outline-color-progressive--focus` token (Volker E.)
- tokens, binary-input: Replace SFC vars with tokens and add min size (Volker E.)
- Minimize and fix Wikisource logo (thiemowmde)
- icons: Manually optimize MediaWiki/Wikinews/Wiktionary logos (thiemowmde)
- icons: Add 'userTemporary' (Volker E.)

## Code
- CSS components: Implement design and docs improvements (Anne Tomasevich)
- TextInput: Emit a "clear" event when clear button is clicked (Eric Gardner)
- TypeaheadSearch: Use menu item classes for the search footer (bwang)
- build: Update 'style-dictionary' to latest version (Volker E.)
- build, styles: Enable `z-index` declaration strict value (Volker E.)
- build, styles: Add `transition-property` to declaration strict value (Volker E.)

## Documentation
- docs: Amend `box-shadow` color demo (Volker E.)
- docs: Add `size` prop to Button configurable demo (Anne Tomasevich)
- docs: Fix CSS tabs demo (Anne Tomasevich)
- docs: Explain which Message features aren't supported in CSS version (Anne Tomasevich)
- docs, Button: Don't set `@click` on Icons (Roan Kattouw)
- docs: Add ADR for CSS Components (Anne Tomasevich)
- docs: Make font size relative (Volker E.)
- docs: Add `aria-label` to CSS-only icon only Button demo (Volker E.)

# 0.10.0 / 2023-05-10

## Features
- Icon: Support icons in CSS-only buttons in Chrome (Anne Tomasevich)
- Tabs: Add CSS-only version (Anne Tomasevich)

## Styles
- Button, tokens: Correct button padding values and add tokens (Anne Tomasevich)
- styles: Add mixins for flush layouts (Anne Tomasevich)
- tokens: Remove trailing zero (Volker E.)
- TextArea: Add error state styles (LWatson)
- styles, tokens: Apply correct border color tokens (Volker E.)
- ToggleSwitch, styles: Use slower transition for background color change (Eric Gardner)
- TextInput: Fix error status styles for a disabled TextInput (LWatson)
- styles, mixins: Name icon fallback mixin more appropriately (Volker E.)
- InfoChip, styles: Use design specification border color (Volker E.)
- Tabs, styles: Replace SFC vars with tokens (Volker E.)
- tokens, styles: Use min-size appropriately in Select and TextArea (Volker E.)

## Code
- Simplify Codex build process via a JS build script (Eric Gardner)
- Tabs: Remove getLabelClasses, use aria-* for styling (Eric Gardner)

## Documentation
- docs: Add "Toolkit" nav item and nested "Composables" item (Anne Tomasevich)
- docs: Style main nav dropdown menu with Codex tokens (Anne Tomasevich)
- docs: Add style guide overview and update language around Codex (Anne Tomasevich)
- docs: Add click handlers to all Button examples (Roan Kattouw)
- docs: Style dropdown nav to look like Codex Menu (Anne Tomasevich)
- docs: Add link to Phab task template to style guide overview (Anne Tomasevich)
- docs: Update component docs to include CSS-only components (Anne Tomasevich)
- docs: Add Visual Styles section of the Style Guide (Anne Tomasevich)
- docs: Add Design Principles section of the style guide (Anne Tomasevich)
- docs: Use relative sizes for th/td and fix token demo presentation (Volker E.)

# 0.9.1 / 2023-04-25
## Styles
- binary inputs: Move `border-color` to enabled and transitions to mixin (Volker E.)
- TextArea: Add base and state styles (LWatson)
- tokens: Add 'Green 500', shuffle value of 'Green 600' and new 'Green 700' (Volker E.)
- tokens, Message, InfoChip: Use darker border colors (Volker E.)
- icons: Fix 'function', 'functionArgument', 'instance' and 'literal' fill (Volker E.)
- icons: Remove unnecessary code from .svg icon files (thiemowmde)

## Code
- tests: Add test asserting that all icon .svg files are used (Roan Kattouw)
- tests: Import component files directly, not from lib.ts (Roan Kattouw)
- build: Update Vite, VitePress, and related libraries (Eric Gardner)
- build: Updating node version equal to CI's (Volker E.)
- build: Add svglint for icon files (Roan Kattouw)
- build: Remove dependency on @rollup/pluginutils (Roan Kattouw)
- build: Add documentation comments to build-related files in codex-docs (Roan Kattouw)
- build, docs: Fix references to .es.js files that now have .mjs names (Roan Kattouw)

## Documentation
- docs: Add missing mixin import to CSS-only TextInput examples (Roan Kattouw)
- docs: Update RELEASING.md (Eric Gardner)
- docs: Unbreak RTL demos broken by VitePress upgrade (Roan Kattouw)
- Lookup: Add docs for CSS-only support (Anne Tomasevich)
- docs: Remove outline on home page (Anne Tomasevich)
- docs: Update tokens docs and remove warning about Codex status (Anne Tomasevich)
- docs: document useModelWrapper composable (Sergio Gimeno)

# 0.9.0 / 2023-04-11

## Breaking
- Button: Remove `type` prop and all workarounds (Anne Tomasevich)
  - Previously the Button component could accept a `type` prop with
    the following values: `normal`, `primary`, and `quiet`. The name
    of this prop has been changed to `weight` to avoid conflicts with
    the native `type` attribute on HTML `<button>` elements. Any
    existing uses of `<cdx-button>` with the old `type` prop values
    should be updated; this code will continue to function but buttons
    will render in the default style until the prop name is updated to
    `weight`.

## Features
- Dialog: Ensure correct line-height is used in subtitle, footer (Eric Gardner)
- Card, Thumbnail: Add CSS-only versions (Anne Tomasevich)
- ProgressBar: Add CSS-only version (Anne Tomasevich)
- ToggleSwitch: Add CSS-only version (Anne Tomasevich)
- TextArea: Set up WIP component (LWatson)
- TextArea: Set up modelValue prop via v-model (LWatson)
- TextArea: pass most attributes down to the `<textarea>` element (LWatson)

## Styles
- tokens: Add common `border` shorthand tokens (Volker E.)

## Code
- build: Don't delete built icon paths file in postpublish (Roan Kattouw)
- build: Update 'browserslist-db' to latest (Volker E.)

## Documentation
- docs: Add a wrapped Dialog example (Eric Gardner)
- docs: Remove unneeded class from CSS icon-only button demo (Anne Tomasevich)
- docs: Remove warning about CSS-only components (Anne Tomasevich)

# 0.8.0 / 2023-03-28

## Features
- TextInput: Add additional input types (LWatson)
- Re-introduce Dialog header and footer customization (Eric Gardner)

## Styles
- tokens, styles: Add and apply `z-index` category (Volker E.)
- tokens, styles: Expand `z-index` token category by `z-index-stacking-0` (Volker E.)
- tokens: Move `line-height-heading` to deprecated aliases (Volker E.)
- tokens: Remove `padding-vertical-menu` deprecated alias token (Volker E.)
- tokens: Move `*search-figure` to base tokens (Volker E.)

## Code
- mixins: Add a file that imports all mixins (Roan Kattouw)
- Icon: Remove unnecessary variable interpolation (Anne Tomasevich)
- build: Remove 'dist/' from import path for codex-icon-paths.less (Roan Kattouw)

## Documentation
- docs: Slightly amend deprecated token headline (Volker E.)
- Buttons, docs: use `weight` prop and set appropriate `type` (Anne Tomasevich)

# 0.7.0 / 2023-03-14

## Breaking
- mixins: Remove tokens import (Roan Kattouw)

## Deprecating
- Button: Change `type` prop and add `weight` prop (Anne Tomasevich)

## Features
- MenuItem: Change highlight behavior (Anne Tomasevich)

## Styles
- Button, styles: Use design-first background color tokens for active (Volker E.)
- Card, styles: Use correct token on supporting text (Volker E.)
- Dialog, styles: Update title font-size (Volker E.)
- styles: Replace deprecated tokens with their non-deprecated equivalents (Roan Kattouw)
- tokens, TextInput: Add `opacity-icon-placeholder` and apply to TextInput (Volker E.)
- tokens, styles: Move further SFC tokens to components (Volker E.)
- tokens: Add `text-overflow` tokens (Volker E.)
- tokens: Clean up deprecation messages (Roan Kattouw)
- tokens: Move most deprecated tokens to separate aliases file (Roan Kattouw)
- tokens: Replace `opacity-icon-accessory` with `opacity-icon-subtle` (Volker E.)
- tokens: Use `50%` for `border-radius-circle` value (Volker E.)

## Code
- Thumbnail, styles: Remove obsolete SFC token (Volker E.)
- build: Update Style Dictionary to latest (Volker E.)
- build: Upgrade VitePress to 1.0.0-alpha.48 (Eric Gardner)
- css-icon: Make Less code compatible with MediaWiki's older Less compiler (Roan Kattouw)
- tokens: Move Style Dictionary transforms and formats into config file (Roan Kattouw)

## Documentation
- docs: Add instructions for configuring VS Code (Anne Tomasevich)
- docs: Display auto-generated token deprecation messages on docs site (Roan Kattouw)
- docs: Exclude VitePress cache from linting (Eric Gardner)
- docs: Improve VS Code setup docs (Anne Tomasevich)
- docs: Unify on 'Less' term (Volker E.)


# 0.6.2 / 2023-02-28
## Styles
- icons: Add 'function', 'functionArgument', 'instance' and 'literal' (Volker E.)

## Code
- code: Clean up TextInput files (Anne Tomasevich)
- code: Deduplicate TextInput native event tests (Roan Kattouw)
- build: Upgrade VitePress from 1.0.0.alpha.29 to 1.0.0.alpha.47 (Roan Kattouw)
- build: Update 'stylelint' dependencies (Volker E.)
- Revert "build: Upgrade VitePress from 1.0.0.alpha.29 to 1.0.0.alpha.47" (Catrope)
- build: Remove `double` colon override (Volker E.)
- build: Add `margin` group to strict value declaration rule (Volker E.)
- build: Add `padding` group to strict value declaration rule (Volker E.)
- build: Add Stylelint strict value rule for top, left, bottom, right (Volker E.)

## Documentation
- docs: Add link to planned components page (Anne Tomasevich)
- docs: Add pre-release coordination instructions to RELEASING.md (Roan Kattouw)
- docs: Update component descriptions (Anne Tomasevich)

# 0.6.1 / 2023-02-21

## Features
- TextInput, SearchInput: Add CSS-only versions (Anne Tomasevich)
- TypeaheadSearch: Add CSS-only version (Anne Tomasevich)
- build: Expose ES module build correctly, rename to .mjs (Sergio Gimeno)

## Styles
- Message, styles: Fix padding on user-dismissable (Volker E.)

## Code
- build: Enable `declaration-strict-value` on `background-position` (Volker E.)
- tokens: Undeprecate legacy opacity tokens and introduce 1 token (Volker E.)

## Documentation
- docs: Amend Sandbox styles (Volker E.)
- docs: Provide `aria-label` to all Lookup demos (Volker E.)
- docs: Provide `aria-label` to all SearchInput demos (Volker E.)
- docs: Provide `aria-label` to all TextInput demos (Volker E.)
- docs: Provide `aria-label` to ToggleSwitch demo (Volker E.)
- docs: Refine visual hierarchy and use Codex tokens (continued) (Volker E.)
- docs: Change CSS-only icon demo to remove size/color combo (Anne Tomasevich)
- docs: Update new component task template links (Anne Tomasevich)
- docs: Update for codex.es.js -> codex.mjs rename (Roan Kattouw)

# 0.6.0 / 2023-02-14

## Features
- Icon: support pre-defined Icon sizes (Eric Gardner)
- Icon: refactor CSS icon mixins and introduce sizes (Anne Tomasevich)
- Button: Add CSS-only version (Anne Tomasevich)
- Menu, TypeaheadSearch: Don't select item highlighted via mouse (Anne Tomasevich)

## Styles
- Tabs, styles: Replace SFC vars with Codex tokens (Volker E.)
- TypeaheadSearch, styles: Prevent footer icon container from shrinking (Volker E.)
- tokens: Add `background-position` token (Volker E.)
- ToggleSwitch, tokens: Amend size and replace SFC vars with tokens (Volker E.)
- tokens: Introduce new component tokens for search figure (Volker E.)

## Icons
- icons: Minimize MediaWiki logo (Volker E.)
- icons: Amend 'Wikinews' and 'Wiktionary' logo (Volker E.)

## Code
- build: Updating @sideway/formula to 3.0.1 (libraryupgrader)

## Documentation
- demos: Use a more explicit label for the "Toggle" section in the sandbox (Roan Kattouw)
- docs: Refine visual hierarchy and use Codex tokens (Volker E.)
- docs: Add Pixel testing to the releasing docs (Anne Tomasevich)
- docs: Refine visual hierarchy and use design-first heading styles (Volker E.)

# 0.5.0 / 2023-01-31

## Features
- InfoChip, Message: Introduce InfoChip component and StatusType constant (Julia Kieserman)
- Icon: Add Less mixin for CSS-only icons (Anne Tomasevich)
- Message: Add CSS-only version (Anne Tomasevich)
- Checkbox, Radio: Add CSS-only versions (Anne Tomasevich)
- Select: Add CSS-only version (Anne Tomasevich)
- Message: Replace 'check' with 'success' icon on success type messages (Volker E.)
- build: Build legacy versions of the Codex styles (Roan Kattouw)

## Styles
- build, tokens, styles: Introduce simple stylesheet unit transform (Roan Kattouw)
- tokens, styles: Introduce design-first `font-size` tokens (Volker E.)
- tokens: Make `position-offset` token relative & replace offset SFC vars (Volker E.)
- tokens: Rename icon token sizes keys according to design (Volker E.)
- docs, binary inputs: Improve docs and use a token for label padding (Anne Tomasevich)
- binary input, styles: Use spacing token for padding (Volker E.)
- binary input: Remove obsolete `size-icon-small` token (Volker E.)
- styles, pending state: Replace relative font size SFCs with token (Volker E.)

## Icons
- icons: Add 'success' (Volker E.)
- icons: Add Wikimedia logos (Volker E.)

## Code
- Combobox, Lookup: Add aria-controls attribute (Lucas Werkmeister)
- Tab: Expose disabled state with `aria-disabled` (Volker E.)
- Icon: Don't add aria-hidden=false (Kosta Harlan)
- InfoChip: Follow-up fixes (Eric Gardner)
- InfoChip: Re-name component to InfoChip (Eric Gardner)
- build: Updating eslint to 8.31.0 (libraryupgrader)
- Add .idea (JetBrains IDEs) to .gitignore (Kosta Harlan)
- build: Use shared Vite config for the demos build (Roan Kattouw)
- build: Update 'eslint' dependency and its dependencies (Volker E.)

## Documentation
- docs: Move SVGO preset JSON file to the public/ directory (Roan Kattouw)
- README: Add note about running unit tests for a workspace (Kosta Harlan)
- docs: Remove oversized relative size token demos from table (Volker E.)
- docs: Improve documentation of CSS-only components (Anne Tomasevich)
- docs: Align Menu demos with keyboard navigation standards (Anne Tomasevich)
- docs: Update types and constants docs to reflect new StatusType (Anne Tomasevich)
- docs: Unify token headings (Volker E.)
- docs: Add warning about CSS-only components (Anne Tomasevich)

# 0.4.3 / 2023-01-10

## Styles
- styles, Tabs: Increase specificity of list item margin rule (Anne Tomasevich)
- styles, Dialog: Set heading padding to 0 (Anne Tomasevich)
- ToggleSwitch, styles: Replace SFC absolute positioning var with token (Volker E.)
- Card, styles: Remove wrongly applied SFC variable (Volker E.)
- styles: Unify to `.cdx-mixin-` as used elsewhere (Volker E.)
- tokens: Group absolute dimension and size tokens (Volker E.)
- tokens: Add `accent-color` (Volker E.)
- tokens: Rename drop shadow token to `box-shadow-drop-xx-large` (Volker E.)
- tokens, styles: Add `min-width-medium` to TextInputs and Select (Volker E.)
- tokens: Introduce small icon and min size component tokens (Volker E.)
- tokens: Use calculated value instead of `calc()` (Volker E.)

## Code
- build: Update typescript-eslint (Roan Kattouw)
- build: Add diff-release.sh for previewing release diffs (Roan Kattouw)
- build: Updating json5 to 2.2.2 (libraryupgrader)

## Documentation
- docs: Add mw.org update and thanking contributors to RELEASING.md (Anne Tomasevich)
- docs: Reorder “Designing…” navigation items (Volker E.)
- docs: Fix slot binding docs (Anne Tomasevich)
- docs, tokens: Apply `line-height` tokens everywhere (Volker E.)

# 0.4.2 / 2022-12-13

## Code
- build: Fix ID prefixing in icons (Roan Kattouw)

# 0.4.1 / 2022-12-13

## Features
- Lookup: Prevent the spacebar from opening the lookup dropdown menu, but only ever having the default behavior of adding a space character. (ddw)
- Combobox, TypeaheadSearch: Always allow default behavior of space key for menu components with text inputs. (ddw)

## Code
- build: Update SVGO to v3.0.2 (Volker E.)
- build: Enable `transition-duration` token only linting (Volker E.)
- build: Enable checkJs in the icons package (Roan Kattouw)
- build: Update TypeScript and vue-tsc (Roan Kattouw)

## Documentation
- docs: Add tokens package to releasing and Codex documentation (Volker E.)
- docs: Update intro card icons (Volker E.)
- docs: Add “Designing new components” documentation (Volker E.)
- docs: Amend “Designing Icons” page with latest designer feedback (Volker E.)
- docs: Add “Redesigning existing components” documentation (Volker E.)
- docs: Add “Designing tokens” documentation (Volker E.)
- docs: Expand Design System Design Tokens overview (Volker E.)
- docs: Update design contribution docs for consistency (Anne Tomasevich)
- docs: Amend design tokens overview with latest comments (Volker E.)
- docs: Put “Reset demo” button at bottom (Volker E.)

# 0.4.0 / 2022-12-06

## Features
- MenuItem: Add supportingText prop (Anne Tomasevich)

## Styles
- Dialog: Prevent skins from breaking header styles (Eric Gardner)
- Menu: Remove -1px margin-top from menu (Anne Tomasevich)
- MenuItem, docs: Fix CSS workaround for Menu absolute positioning (Roan Kattouw)
- styles: Make mixin imports consistent (Roan Kattouw)
- tokens, styles: Add `animation-iteration-count*` tokens and apply (Volker E.)
- tokens: Add `tab-size` token (Volker E.)
- tokens: Add tests for getTokenType (Roan Kattouw)
- tokens: Amend Yellow 600 color option token and add Yellow 500 (Volker E.)

## Icons
- icons: Optimize 'palette' icon's file size (Volker E.)

## Code
- build, tokens: Rename index.json to theme-wikimedia-ui.json (Roan Kattouw)
- build: Add the design-tokens package to the RELEASING.md docs (Roan Kattouw)
- build: Correct file extension in tokens README (Anne Tomasevich)
- build: Correctly align "engine" requirements with Wikimedia CI (Roan Kattouw)
- build: Ensure build-demos script resolves token paths correctly (Eric Gardner)
- build: Remove 'dist/' from import paths for mixin and tokens files (Roan Kattouw)
- build: Updating decode-uri-component to 0.2.2 (libraryupgrader)

## Documentation
- docs, Dialog: Use normal configurable setup for Dialog demo (Anne Tomasevich)
- docs: Add banners for main branch and deployment previews (Anne Tomasevich)
- docs: Amending “Designing icons” (Volker E.)
- docs: Demonstrate good TypeScript practices in LookupWithFetch example (Roan Kattouw)
- docs: Document the output of the tokens package, and add a README (Roan Kattouw)
- docs: Link to the latest docs site instead of the main branch one (Roan Kattouw)
- docs: Move SVGO preset JSON file out of assets/ directory (Roan Kattouw)
- docs: Prevent VitePress from treating the link to latest as internal (Roan Kattouw)
- docs: Spell out directions and improve radio controls (Anne Tomasevich)
- docs: Update VitePress to 1.0.0-alpha.29 (Roan Kattouw)
- docs: Update one more link to /main to /latest (Anne Tomasevich)

# 0.3.0 / 2022-11-22

## Features
- Menu, TypeaheadSearch: Dedicated sticky footer in the Menu component (Michael Große)
- ProgressBar: Add disabled state, refine styles and demos (Volker E.)
- Treat submits with selected search result like clicks (Lucas Werkmeister)
- TextInput: Add error state to TextInput component (Julia Kieserman)
- Button: Unify active state behavior (Anne Tomasevich)
- ToggleButton: Unify active state behavior (Anne Tomasevich)
- Dialog: prepare basic Dialog for release (Eric Gardner)

## Styles
- Remove property with deprecated value `break-word` (Volker E.)
- Tabs, styles: Make Tab list styles more specific (Volker E.)
- Tabs, styles: Don't let external list item `margin`s bleed in (Volker E.)
- Message, styles: Replace SFC variable with design token (Volker E.)
- Link, docs: Apply design fixes (Volker E.)
- styles: Replace SFC variables with design-first size tokens (Volker E.)
- styles: Replace SFC variables with design-first spacing tokens (Volker E.)
- Apply `line-height` design-first tokens (Volker E.)
- MenuItem: Reset line-height to @line-height-x-small (Anne Tomasevich)
- Message: Replace SFC var and align content vertically (Volker E.)
- CdxTextInput: error status uses progressive palette for focus (Julia Kieserman)
- styles, docs: Standardize Less mixin parameters naming (Volker E.)
- styles: Replace and remove obsolete SFC variables (Volker E.)
- styles, Menu: Move footer border-top to the Menu component (Anne Tomasevich)
- styles: Use consistently `list-style` shorthand (Volker E.)
- styles: Remove `z-index` option token usage (Volker E.)
- tokens, styles: Add `size` and `spacing` design-first tokens (Volker E.)
- tokens: Amend design-first `line-height` tokens to latest (Volker E.)
- tokens: Put deprecated tokens at the bottom of CSS/Less/Sass output (Roan Kattouw)
- tokens: Replace `max-width.breakpoint` token refs with `size.absolute-1` (Volker E.)
- tokens, Button: Rename to `background-color-button-quiet*` tokens (Volker E.)
- tokens: Add `spacing-400` token (Volker E.)
- tokens: Add `spacing-30` and `spacing-35` decision tokens and apply (Volker E.)
- tokens: Introduce `font-family` (legacy) tokens (Volker E.)
- tokens: Don't output theme tokens in CSS/Less/Sass output (Roan Kattouw)

## Code
- Dialog: Defer access to window.innerHeight until mount time (Roan Kattouw)
- Dialog: Ensure border-box is used for layout (Eric Gardner)
- Icon, docs: Move `fill: currentColor` to CSS (Volker E.)
- build: Support HMR for public mixins in the docs site (Roan Kattouw)
- build: Set Style Dictionary `basePxFontSize` to 16px for all web outputs (Volker E.)
- Build: Bundle component demos using Vite library mode (Eric Gardner)
- build: Expand Stylelint 'properties-order' property list (Volker E.)
- build: Update root '.editorconfig' and remove single package one (Volker E.)
- build: Change Stylelint configuration file format to JS (Volker E.)
- build, styles: Introduce 'stylelint-declaration-strict-value' plugin (Volker E.)
- build: Updating stylelint to 14.14.0 (libraryupgrader)
- build: Actually eslint files in hidden directories (Roan Kattouw)
- build: Update codex package to TypeScript 4.8.2 (Roan Kattouw)
- build: Enable TypeScript in the tokens package (Roan Kattouw)
- build: Update 'stylelint-*' packages and 'postcss-html' (Volker E.)
- build: Update 'node' and 'npm' versions in 'engine' (Volker E.)
- Upgrade vue-tsc to a more recent version (Eric Gardner)

## Documentation
- docs, Link: Add page for the Link mixin (Anne Tomasevich)
- docs, Link: Add wrapper class to code sample (Anne Tomasevich)
- docs: Fix typo and expand abbreviations (Volker E.)
- docs: Reset VitePress' opinionated anchor styles (Volker E.)
- docs: Use <style lang="less">, not <style>, for Less blocks (Roan Kattouw)
- docs: Deduplicate results from Wikidata in demos (Michael Große)
- docs, Menu: Add more docs on the new footer prop (Anne Tomasevich)
- docs: Remove obsolete token comment in Thumbnail (Volker E.)
- docs: Update opacity token demo (Volker E.)
- docs: Expand explanation on component tokens (Volker E.)
- docs: Replace calculation with spacing token (Volker E.)
- docs: Add dir to controls, and apply to every component page (Anne Tomasevich)
- docs: Fix direction control for configurable Dialog demo (Anne Tomasevich)
- docs: Add extra padding to the bottom of the demo pane (Anne Tomasevich)
- docs, styles: Replace non-existent token for nav spacing (Anne Tomasevich)

# 0.2.2 / 2022-10-25
## Features
- Link: implement Codex link styles as Less mixins (Eric Gardner)
- Link: Make selectors flexible (Roan Kattouw)

## Styles
- Combobox, styles: Fix expand button icon size (Volker E.)
- Dialog, styles: Use design spec `transition` tokens (Volker E.)
- Tabs, styles: Remove obsolete `@text-decoration-none` SFC var (Volker E.)
- ToggleSwitch, styles: Fix disabled label color (Volker E.)
- ToggleButtonGroup, styles: Combine focus shadow and white border shadow (Roan Kattouw)
- styles, components: Replace falsely applied or deprecated tokens (Volker E.)
- styles: Use `outline` override on all of `:focus` (Volker E.)
- styles, docs: Apply `code` styling (Volker E.)
- styles, docs: Replace static `text-decoration` values with Codex tokens (Volker E.)
- tokens: Add comments to legacy opacity icon tokens on color equivalents (Volker E.)
- tokens: Amend `background-color-framed--active` (Volker E.)
- tokens: Add design-first `line-height` tokens (Volker E.)
- tokens: Deprecate component `background-color-primary*` tokens (Volker E.)
- tokens: Add design-first `text-decoration` tokens (Volker E.)
- tokens, docs: Replace deprecated background-color-primary token (Volker E.)
- tokens, docs: Replace deprecated design tokens (Volker E.)
- tokens: Use proper theme reference to `text-decoration` values & demo (Volker E.)
- tokens: Add design-first Dialog backdrop background color tokens (Volker E.)

## Code
- Combobox: bubble up the `load-more` event (Noa wmde)
- Dialog: Introduce Dialog component, useResizeObserver (Eric Gardner)
- Dialog, docs: Clarify Safari hack documentation (Volker E.)
- Dialog: MVP Design Fixes (Eric Gardner)
- LookupFetchDemo: refactor to be more extensible (Michael Große)
- Menu: Add configurable scroll behavior (Michael Große)
- Menu: Emit 'load-more' event when close to the end (Michael Große)
- Select: bubble up the `load-more` event (Noa wmde)
- TextInput: Suppress keydown events for Home/End (Roan Kattouw)
- TypeaheadSearch: adjust to support configurable scroll & load more (Noa wmde)
- TypeaheadSearch: Allow customization of autocapitalize attribute (Jon Robson)
- TypeaheadSearchWikidataDemo: refactor to make fetch more reusable (Michael Große)
- TypeaheadSearchWikidataDemo: add infinite scroll behavior (Michael Große)
- build: Allow async/await in TypeScript and Vue code (Roan Kattouw)
- build: Point docs tsconfig to lib-wip.ts, not the .d.ts file (Roan Kattouw)
- build: Disable Cypress tests for now (Roan Kattouw)
- build: Exclude WIP code from test coverage threshold (Roan Kattouw)
- build: Enable stylelint for Markdown files (Volker E.)
- build: Bump .nvmrc to node v16.16.0 (Volker E.)
- build: Move Vite plugin for copying mixins into its own file (Roan Kattouw)
- build: Restructure shared Vite config between codex and codex-search (Roan Kattouw)
- build: Convert Vite config files back to TypeScript (Roan Kattouw)
- tests: Add cypress browser test for Menu component scroll functionality (Michael Große)

## Documentation
- docs: Make top section headings consistent (Anne Tomasevich)
- docs: Add link to code review process (Anne Tomasevich)
- docs: Link to Codex docs on mediawiki.org (Anne Tomasevich)
- docs: Call out icons package in README (Anne Tomasevich)
- docs: Move generated components demos and add an overview page (Anne Tomasevich)
- docs: Fix WIP component detection in sidebar (Roan Kattouw)
- docs: Add links to “Maintainers” section (Volker E.)
- docs: Update WIP components docs (Roan Kattouw)
- docs: Add contact section to “About” (Volker E.)
- docs: Rename navigation label to “Architecture Decisions” (Volker E.)
- docs: Add note on what a 'breaking change' means (Volker E.)
- docs: Restructure Codex docs introduction page 'index.md' (Volker E.)
- docs: Use note for invisible label (Volker E.)
- docs: Unset link styles (Anne Tomasevich)
- docs, Menu: Separate menu scroll into its own demo (Anne Tomasevich)
- docs: Clean up Menu example files (Anne Tomasevich)
- docs, Menu: Improve display of number input (Anne Tomasevich)
- docs: Document types and constants (Anne Tomasevich)
- docs: Add “Designing Icons” to “Contributing” section (Volker E.)
- docs: Reposition deprecation “tag” of design tokens (Volker E.)
- docs, Dialog: Add Dialog types to docs page (Anne Tomasevich)
- docs: Add visibleItemLimit to MenuConfig docs (Anne Tomasevich)
- docs: Add more scrolling demos and load-more docs (Anne Tomasevich)
- docs: Add a link to usage bidirectionality support to all icons list (Volker E.)
- docs: Use Card styles (and its design tokens) on footer pager cards (Volker E.)

# 0.2.1 / 2022-09-13

## Styles
- tokens: Fix `background-color-quiet` and also deprecate (Volker E.)

# 0.2.0 / 2022-09-13
## Features
- TypeaheadSearch: Expand input on menu open (Anne Tomasevich)
- TypeaheadSearch: Remove active class (Anne Tomasevich)

## Styles
- styles, docs: Use and document solely `--has-` and `--is-` prefixes (Volker E.)
- tokens: Add 'maroon' color option token and Red Link component tokens (Volker E.)

# Icons
- icons: Add 'palette' to collection (Volker E.)
- icons: Minimize search icon (Thiemo Kreuz)

## Code
- build: Fix bug list steps to actually work (Roan Kattouw)
- build: Update TypeScript to 4.8 (Roan Kattouw)
- build: Update VitePress from 1.0.0-alpha.10 to 1.0.0-alpha.13 (Roan Kattouw)

## Documentation
- docs: ADR 04 - Visual styles as Less mixins (Eric Gardner)
- docs: Add `alt` attribute to docs logo (Volker E.)
- docs: Add announcement to releasing docs (Anne Tomasevich)
- docs: Document WIP components and contribution pathways (Anne Tomasevich)
- docs: Fix landing page links (Anne Tomasevich)
- docs: Hide direction switcher (Anne Tomasevich)
- docs: Refactor site architecture (Anne Tomasevich)
- docs: Split contributing code docs into multiple pages (Anne Tomasevich)

# 0.1.1 / 2022-08-31

## Code
- build: Don't build .d.ts files for demos and WIP components (Roan Kattouw)
- build: Add bug list and LibraryUpgrader steps to RELEASING.md (Roan Kattouw)
- build: Skip diff-css.sh when not running in CI (Roan Kattouw)
- build: Upgrade Vite to v3.0.9 (Roan Kattouw)

# 0.1.0 / 2022-08-30

## Features
- Lookup: When input is empty, clear pending state, and don't reopen menu (Roan Kattouw)
- ButtonGroup: Use box-shadow instead of border between disabled buttons (Roan Kattouw)

## Styles
- ButtonGroup: Increase z-indexes to avoid using z-index: -1; (Roan Kattouw)
- styles, Tabs: Don't emphasise being clickable on already selected Tab (Volker E.)
- styles, Card: Unset text-decoration on focus (Anne Tomasevich)
- styles, docs: Rename and clarify icon-wrapper-padding mixin (Volker E.)
- styles, docs: Expand on pending-state mixin usage and replace vars (Volker E.)
- styles, demo: Use Codex breakpoint token (Volker E.)
- styles, docs: Improve more styles after the VitePress update (Anne Tomasevich)
- styles: Unify on `cdx-mixin-` Less mixin prefix (Volker E.)
- tokens: Add small top and start box-shadow decision tokens (Volker E.)
- tokens: Add design-first breakpoints tokens (Volker E.)

## Code
- types: Export MenuState type, reorder types in lib.ts (Roan Kattouw)
- build: Add separate entry point for components in development (Roan Kattouw)
- tests: Reorganize Checkbox tests per new standards (Anne Tomasevich)
- tests: reorganize Lookup tests per new standards (Anne Tomasevich)

## Documentation
- docs, Thumbnail: Update "placeholder" language (Anne Tomasevich)
- docs: Don't error when a component-demos .md file doesn't exist (Roan Kattouw)
- docs: Use TypeScript for VitePress config and theme files (Roan Kattouw)
- docs: Use better TypeScript types for vue-docgen templates (Roan Kattouw)
- docs: Use IconLookup component for Select's defaultIcon prop (Anne Tomasevich)
- docs: Flag development components, hide them in release docs (Roan Kattouw)
- docs: Standardize JEST unit test names and structure (Simone This Dot)
- docs, tokens: Show deprecated tag even if there is no token demo (Roan Kattouw)
- docs, tokens: Exclude breakpoint tokens from the size token docs (Roan Kattouw)
- docs: Reword alpha warning (Roan Kattouw)
- docs: Update VitePress (Anne Tomasevich)
- docs: Remove VitePress list style in the demo pane (Anne Tomasevich)

# 0.1.0-alpha.10 / 2022-08-16

## Features
- TypeaheadSearch: Open menu on new results, even if empty (Roan Kattouw)
- ButtonGroup: Initial implementation (Roan Kattouw)
- ToggleButtonGroup: Initial implementation (Roan Kattouw)
- DirectionSwitcher: Use ToggleButtonGroup now that it exists (Roan Kattouw)
- ButtonGroup: Add overflowing demo, fix styling (Roan Kattouw)
- ToggleButtonGroup: Add maximum example, icon-only example (Roan Kattouw)
- ButtonGroup, ToggleButtonGroup: Straighten white lines between buttons (Roan Kattouw)
- ButtonGroup: Apply rounded corners to groups, not buttons (Roan Kattouw)
- icons: Update icons to the latest optimizations (Volker E.)
- CopyTextButton: Use Clipboard API when available to copy code (Abijeet Patro)
- icons: Update 'info' icon to newest design (Volker E.)

## Styles
- styles, tokens: Replace SFC `border-color` tokens (Volker E.)
- styles, tokens: Introduce `border-color-subtle` and replace SFC token (Volker E.)
- styles: Remove SVG title from background image (Volker E.)
- styles, Card: Add background color (Anne Tomasevich)
- tokens, styles: Add further cursor tokens on theme option and base level (Volker E.)
- tokens, demos: Mark deprecated tokens loud and clear (Volker E.)
- tokens: As demo features “Deprecated” prefix now, don't repeat yourself (Volker E.)
- tokens, demos: Put deprecated tokens always at bottom (Volker E.)
- tokens: Use design-first Color decision tokens (Volker E.)
- tokens: Use design-first Border Color decision tokens (Volker E.)
- tokens: Amend `color-notice` value (Volker E.)
- tokens: Amend `modifier-gray200-translucent` value (Volker E.)
- tokens: Use design-first Background Color decision tokens (Volker E.)
- tokens, styles, ToggleSwitch: Cleanup tokens and styles applied (Volker E.)

## Code
- Tabs: Improve tests (Roan Kattouw)
- Re-organize and improve component sandbox page (Eric Gardner)
- build: Update Vue from 3.2.33 to 3.2.37 (Roan Kattouw)
- build: Upgrade eslint to 0.23.0 and make pass (Roan Kattouw)
- build: Run build-all-if-missing in "npm coverage" (Roan Kattouw)
- build: Publish the sandbox alongside Netlify deployment previews (Roan Kattouw)
- build: Add script to generate a CSS diff for a change (Roan Kattouw)
- build: Run diff-css.sh in npm test (Roan Kattouw)
- build: Add "style" field to package.json (Roan Kattouw)
- build: Make Vite port configurable, listen on all IPs (Roan Kattouw)

## Documentation
- docs: Add links to task templates and explain component scoping process (Anne Tomasevich)
- docs, utils: factor out getIconByName() utility (DannyS712)
- docs: Clarify how search query highlighting works (Anne Tomasevich)
- docs, component demos: add getEventLogger() utility (DannyS712)
- docs, Controls: simplify splitting of props and slot controls (DannyS712)
- docs, tests: add relevant types, nonexistent keys Thumbnail objects (DannyS712)

# 0.1.0-alpha.9 / 2022-07-28

## Features
- Button: Add full support for icon-only buttons (Simone This Dot)
- Thumbnail: Add Thumbnail component (Anne Tomasevich)
- MenuItem: Use the Thumbnail component (Anne Tomasevich)
- icons: Add 'copy'/'cut'/'paste' (Volker E.)
- TypeaheadSearch: Remove space when no-results slot is empty (Steven Sun)
- Card: Add initial Card component (Anne Tomasevich)
- Menu: Add Home/End keyboard button support (Simone This Dot)
- TypeaheadSearch: Remove border-top on footer when it's the only menu item (Steven Sun)
- Tabs: Make icon-only scroll buttons `aria-hidden` (Anne Tomasevich)

## Styles
- styles, ProgressBar: Fix border radius overflow in Safari (Anne Tomasevich)
- styles, Checkbox, ToggleSwitch: Simplify state styles hierarchy (Volker E.)
- styles, TypeaheadSearch: Correct padding of footer's icon (Simone Cuomo)
- tokens: Introduce `box-shadow-color` decision tokens (Volker E.)
- tokens: Replace legacy `@box-shadow` tokens with new combination tokens (Volker E.)
- tokens: Add new typographic system fonts to stack (Volker E.)

## Code
- MenuItem: Remove unused tokens (Anne Tomasevich)
- Thumbnail: clean up testThumbnail in tests (DannyS712)
- Button: Improve icon-only button detection, and add tests (Roan Kattouw)
- Button: Ignore whitespace-only text nodes when detecting icon-only-ness (Roan Kattouw)
- Button: Unify on “icon-only” label for that type (Volker E.)
- build: Update 'style-dictionary' to latest v3.7.1 (Volker E.)
- build: Update package-lock.json for style-dictionary upgrade (Roan Kattouw)
- build: Update 'less', 'postcss*' and 'autoprefixer' dependencies (Volker E.)
- Build: Update netlify-cli, update minimist vulnerability (Eric Gardner)
- build: gitignore .vscode directory for settings files (Anne Tomasevich)
- build: Update netlify-cli to v10.10.2 (Roan Kattouw)

## Documentation
- docs: Clarify commit message category order (Volker E.)
- docs: Expand on marking deprecating and breaking changes in commit msg (Volker E.)
- docs, Controls: reduce duplication using `<component>` and `:is` (DannyS712)
- docs: Correct language about deprecating/breaking change prefix (Roan Kattouw)
- docs: Update CSS class names on tokens demos (Volker E.)
- docs: Update border-radius-pill demo (Volker E.)
- docs, composables: factor out useCurrentComponentName() composable (DannyS712)
- docs: Guide users to the repo (Kosta Harlan)
- docs: Separate search-no-results-text in TypeaheadSearch demo (Steven Sun)
- docs: Add step to releasing docs to document breaking changes (Anne Tomasevich)

# 0.1.0-alpha.8 / 2022-06-23

## Breaking changes
- refactor: Fix inconsistencies across components with menu items (Simone This Dot)

## Features
- Menu: Highlight the selected item when menu is opened (Anne Tomasevich)
- Menu, Typeahead: Apply MenuItem selected styles to Menu footer (Simone This Dot)
- TextInput: Remove focus tracking state, replace with --has-value class (Roan Kattouw)
- MenuItem: Remove highlighted and active styles on mouseleave (Anne Tomasevich)
- ToggleSwitch: Component is squashed when it has a long default label (Simone This Dot)
- Combobox: Apply `aria-hidden` on button (Volker E.)
- MenuItem: Display placeholder thumbnails before images are loaded (Simone This Dot)
- MenuItem: Align icon and thumbnail to top with description (Anne Tomasevich)
- MenuItem: Reduce transition duration of thumbnail (Anne Tomasevich)
- Menu: Refine `active` binding of default slot (Anne Tomasevich)

## Styles
- styles, buttons: Unify whitespace and property order (Volker E.)
- Select, TextInput, styles: Unify `outline` values (Volker E.)
- styles, TextInput: Add border width to icon position offset values (Anne Tomasevich)
- Button, styles: Reorder and cleanup focus styles (Volker E.)
- Button, styles: Removing default button `:active` selector (Volker E.)
- styles, ToggleSwitch: Unify applying pointer `cursor` (Volker E.)
- styles: Apply design-first `box-shadow` tokens (Volker E.)
- styles, TextInput: Use `min-height-base` instead of `height-base` (Volker E.)
- styles, TypeaheadSearch: Reduce footer font size (Anne Tomasevich)
- tokens: Fix quiet active background-color value (Volker E.)
- tokens, styles: Use `transition-property-base` for ToggleSwitch focus (Volker E.)
- tokens, styles: Replace `animation` property values with tokens (Volker E.)
- tokens: Use design-first `box-shadow` tokens (Volker E.)
- tokens: Add `transition-property-toggle-switch-grip` token and apply (Volker E.)

## Code
- tests: Fix Tabs down arrow test by using `attachTo: 'body'` (Roan Kattouw)
- build: Lower target browsers to include Edge 18 (Volker E.)
- build: Update Vue to 3.2.33 (Anne Tomasevich)

## Documentation
- docs: Tidy up CHANGELOG a bit (DannyS712)
- docs: Expand on using Vite (Volker E.)
- docs: Add 'AUTHORS.txt' (Volker E.)
- docs: Add `aria-label` to slot and prop `input`s (Volker E.)
- docs: Update intro and contributing guidelines (Anne Tomasevich)
- docs, IconLookup: Add `aria-label` to icon lookup props and slots (Anne Tomasevich)

# 0.1.0-alpha.7 / 2022-06-09

## Features
- Button, ToggleButton: Text overflow from button is larger than max width (Simone This Dot)
- Combobox: Remove useless tabindex="0" on the input (Roan Kattouw)
- Lookup: Update menu items when item is selected (Simone This Dot)
- Select: Remove arrow indicator direction change when menu is expanded (Volker E.)
- Tabs: Add scroll buttons (Roan Kattouw)
- TextInput: Allow clear and end icons to coexist (Anne Tomasevich)
- TypeaheadSearch: Remove hover effect from button (Roan Kattouw)

## Styles
- Style: Refactor icon positioning in TypeaheadSearch using mixin (Simone This Dot)
- styles, docs: Enforce specific CSS properties over shorthand (Volker E.)
- Tabs, styles: Consistently apply margins to `<a>` elements (Roan Kattouw)
- styles: Use consistent border-bottom on item with dropdown menus (Simone This Dot)
- Select, styles: Introduce `.cdx-select--enabled` class and align states (Volker E.)
- TypeaheadSearch, styles: Fix auto-expand distance (Anne Tomasevich)
- MenuItem, TypeaheadSearch, styles: Fix link style overrides (Anne Tomasevich)
- MenuItem - Fix style for Menu with custom menu item (Simone This Dot)
- SearchResultTitle, styles: remove properties for consistency with MenuItem label (Anne Tomasevich)
- Tabs, tokens, styles: Use `rgba()` over transparent for background color (Volker E.)
- tokens: Follow new color palette naming scheme for design-first tokens (Volker E.)
- tokens: Use design-first `border` tokens (Volker E.)

## Code
- useGeneratedId: no need to return a reference (DannyS712)
- useStringHelpers: export helpers directly instead of in a function (DannyS712)
- codex, utils: create directory and rename utils.ts and useStringHelpers.ts (DannyS712)
- utils, tests: add tests for stringTypeValidator.ts (DannyS712)
- build: Add codex-search package to prepare-release.sh (Roan Kattouw)
- build: Add a new script to simplify the creation of snapshot (Simone This Dot)

## Documentation
- docs: Clarify that Vue needs to be installed to use Codex (Roan Kattouw)
- docs, Wrapper: simplify highlighting of generated code (DannyS712)
- docs, Tabs: Update Tabs demos (Anne Tomasevich)
- docs: add generic configurable component for using v-model (DannyS712)
- docs, ConfigurableGeneric: `<component>` can be self closing (DannyS712)
- docs, Wrapper: minor simplification and cleanup (DannyS712)
- docs, SlotIcon: avoid `as` for typescript type of iconsByName (DannyS712)
- docs, Wrapper: include v-model in generated code sample (DannyS712)
- docs: Restructure tokens overview documentation and inter-link (Volker E.)
- docs, sidebar: fix design tokens order (DannyS712)
- docs, SizeDemo: document css property values, simplify (DannyS712)
- docs, codegen: default slot never requires `<template>` wrapper (DannyS712)
- docs, Button: remove a number of selection demo variations (DannyS712)
- docs, CdxDocsFontDemo: remove unneeded less import (DannyS712)
- docs, RELEASING: update explanation of creating tag patch (DannyS712)
- docs, tests: add tests for ConfigurableGeneric (DannyS712)
- docs: Remove link override styles for demos (Anne Tomasevich)
- docs: separate 'default' property values from initial values to use (DannyS712)
- docs: Expand “ADRs” menu label slightly (Volker E.)
- docs, tokens: add generic CdxDocsTokenDemo for demonstrations (DannyS712)
- docs, tokens: deduplicate styles in CdxDocsTokenDemo (DannyS712)
- docs, MenuItem: add configurable menu item demo (DannyS712)
- docs: Add instructions for updating MediaWiki to RELEASING.md (Roan Kattouw)
- docs: Clarify browser support (Volker E.)
- Combobox, docs: Add disabled demo (Roan Kattouw)

# 0.1.0-alpha.6 / 2022-05-12

## Features
- Lookup: Use pending and focus states to decide whether to open the menu (Roan Kattouw)
- Menu, TypeaheadSearch: Add inline progress bar (Anne Tomasevich)
- Menu, TypeaheadSearch: Remove selectHighlighted prop (Eric Gardner)
- Menu: Change footer slot to no-results (Anne Tomasevich)
- Menu: Fix keyboard navigation after expanding menu by click (Steven Sun)
- MenuItem: Reorganize and improve color styles (Anne Tomasevich)
- MenuItem: Support language attributes (Anne Tomasevich)
- Message: Update component to meet design spec (Anne Tomasevich)
- Message: Add auto-dismiss functionality and improve demos (Anne Tomasevich)
- ProgressBar: add progress bar component with indeterminate state (DannyS712)
- ProgressBar: add inline variant (Anne Tomasevich)
- Tabs: Introduce Tab and Tabs components, useIntersectionObserver (Eric Gardner)
- ToggleButton: add quiet type (DannyS712)

## Styles
- binary inputs, styles: Fix hover cursor behavior (Volker E.)
- tokens, Button: Fix applied quiet progressive border token (Volker E.)
- Checkbox: Don't apply checked styles to indeterminate inputs (Anne Tomasevich)
- Checkbox: Vertically center indeterminate icon (line) (Volker E.)
- MenuItem: Update thumbnail styles (Anne Tomasevich)
- Message: Use opacity-transparent token now that it exists (Roan Kattouw)
- Message: Fix mobile padding and transition styles (Anne Tomasevich)
- ProgressBar: Update indeterminate animation (Anne Tomasevich)
- SearchInput: Fix border radius and button border behavior (Anne Tomasevich)
- Tabs: Fix broken header hover styles in Chrome (Eric Gardner)
- Tabs: Adjust styles to follow design and simplify selector logic (Volker E.)
- Tabs: Apply correct borders on hover/active (Roan Kattouw)
- Tabs, styles: Add `frameless` variant CSS class (Volker E.)
- Tabs, styles: Rename 'frameless' to 'quiet' (Volker E.)
- TextInput: Update TextInput styles to match design spec (Anne Tomasevich)
- ToggleButton: Update quiet styles and make focusable (Roan Kattouw)
- TypeaheadSearch, style: Remove border-top for no-results text (Steven Sun)
- styles: Introduce `screen-reader-text()` mixin (Volker E.)
- styles: Add `.text-overflow()` mixin and use in MenuItem (Volker E.)
- styles: Add `hyphens` mixin and apply (Volker E.)
- styles: Use CSS 3 notation for pseudo-elements (Volker E.)
- styles: Don't use transition-duration: @transition-base (Roan Kattouw)
- styles: Use comment style consistently (Volker E.)
- styles: Centralize "start icon padding" style logic (Simone This Dot)
- styles: Replace obsolete notation of keyframes (Volker E.)
- tokens, ToggleSwitch: Remove `box-shadow-input-binary` (Volker E.)
- tokens: Add token type to JSON attributes (Roan Kattouw)
- tokens: Don't refer to theme tokens in deprecation comments (Roan Kattouw)
- tokens: Use correct color in 'modifier-base80-translucent' (Volker E.)
- tokens: Add legacy `opacity` tokens (Volker E.)
- tokens: Add `0.30` valued opacity token and update naming (Volker E.)
- tokens: Use 'user' as name for human initiated timing function token (Volker E.)
- tokens: Update `border-radius` design-first tokens (Volker E.)
- tokens: Add `@position-offset-border-width-base` (Anne Tomasevich)
- tokens: Remove conflicting token comment (Anne Tomasevich)
- tokens, styles: Add design-first transition tokens (Volker E.)
- tokens: Add design-first animation tokens (Volker E.)

## Code
- Button: simplify rootClasses definition (DannyS712)
- Combobox: simplify onInputBlur() logic (DannyS712)
- Combobox: don't retrieve the entire context for setup() (DannyS712)
- Checkbox, Radio: remove unneeded !! for boolean props (DannyS712)
- Menu: Add global event listener for mouseup to clear active (Anne Tomasevich)
- Menu: simplify handleKeyNavigation() cases (DannyS712)
- Menu: simplify highlightPrev() with early return (DannyS712)
- MenuItem: Only set active state on main mouse button mousedown (Anne Tomasevich)
- ProgressBar: Set height on root element (Anne Tomasevich)
- Select: Apply design review feedback (Simone This Dot)
- Select: document why `return undefined` is needed in computing start icon (DannyS712)
- TypeaheadSearch: Add snapshot for "no results" message (Anne Tomasevich)
- TypeaheadSearch: Don't use refs for timeout handles (Roan Kattouw)
- Components: don't retrieve the entire context for setup() (DannyS712)
- useIntersectionObserver: Make reactive to templateRef changing (Roan Kattouw)
- useIntersectionObserver: Don't observe elements before they're mounted (Roan Kattouw)
- useModelWrapper: Support typed event parameters (Roan Kattouw)
- build: Add shell script for preparing a release (Roan Kattouw)
- build: Add "npm run coverage" command (Roan Kattouw)
- build: Enable type checking rules for typescript-eslint (Roan Kattouw)
- build: Upgrade TypeScript 4.4.3 -> 4.6.2 (Roan Kattouw)
- build: Upgrade vue-tsc 0.28.3 -> 0.33.6 (Roan Kattouw)
- build: Disable "restrict-template-expressions" linting rule in tests (Eric Gardner)
- build: Upgrade postcss-rtlcss 3.5.1 -> 3.5.4 (Roan Kattouw)
- build: Check .js files with TypeScript in the Codex package (Roan Kattouw)
- build: Check .js with TypeScript in the codex-docs package (Roan Kattouw)
- build: Use rtlcss to generate codex.style-rtl.css, by running Vite twice (Roan Kattouw)
- build: Upgrade eslint and its plugins (Roan Kattouw)
- build: Upgrade @vue/test-utils and use VueWrapper correctly (Roan Kattouw)
- build: Type check the VitePress config (Roan Kattouw)
- build: Increase stylelint max-nesting-depth to 3 (Anne Tomasevich)
- build: Put icon type definitions in dist/types (Roan Kattouw)
- build: Use vue-tsc to generate type definitions (Roan Kattouw)
- build: Add the @wikimedia/codex-search package (Roan Kattouw)
- build: Update 'browserslist-config-wikimedia' to v0.4.0 (Volker E.)
- build: Export all composables (Catrope)
- build: Enable stylelint in hidden directories (Roan Kattouw)
- build: Actually make stylelint work in the .vitepress/ directory (Roan Kattouw)
- build: Update 'stylelint' and 'stylelint-config-wikimedia' to latest (Volker E.)
- build: Remove useless eslint-disable (Roan Kattouw)
- lib: Don't export the TabData type (Roan Kattouw)
- docs, tests: add dedicated tests for CopyTextButton (DannyS712)
- tests: add tests for flattenDesignTokensTree() method (DannyS712)

## Documentation
- docs, Wrapper: minor cleanup and organization (DannyS712)
- docs, CopyTextButton: improvements to success logic (DannyS712)
- docs: Add example usage of useComputedDir() (Roan Kattouw)
- docs: Add button examples with icons (Roan Kattouw)
- docs, TokensTable: import missing CdxDocsCursorDemo component (DannyS712)
- docs, Wrapper: Add dynamic sample code generation with controls (DannyS712)
- docs: Fix typo on `processKeyFrames` in postcss.config.js (Roan Kattouw)
- docs: Unbreak navigating away from component pages with generated code (Roan Kattouw)
- docs: Simplify breakpoint documenting sentences. (Volker E.)
- docs, component.js: avoid unneeded template string interpolation (DannyS712)
- docs: avoid empty "Values" column for properties when unused (DannyS712)
- docs: Work around VitePress click handling behavior (Roan Kattouw)
- docs: Ensure generated code samples can handle self-closing tags (Anne Tomasevich)
- docs, Controls: don't show "slots" heading if there aren't any (Anne Tomasevich)
- TextInput: Add configurable demo (Anne Tomasevich)
- Wrapper: Revert changes to Wrapper styles (Eric Gardner)
- docs: Manually set link styles for Message demos (Anne Tomasevich)
- docs: Update CSS conventions (Anne Tomasevich)
- demo: Add ToggleButton, ToggleSwitch and Message to sandbox demo (Roan Kattouw)
- docs: Hide theme tokens in the tokens documentation (Roan Kattouw)
- docs: Use design tokens within codex-docs custom theme (Anne Tomasevich)
- docs, Wrapper: use ToggleButton for show/hide code (DannyS712)
- docs, changelog: Organize 'CHANGELOG.md' release notes (DannyS712)
- DirectionSwitcher: use ToggleButton for direction options (DannyS712)
- docs, codex: Remove 'wikimedia-ui-base' from codex package as well (Volker E.)
- styles, docs: Use `lang="less" attribute for style block everywhere (Volker E.)
- docs: Allow configuring placeholder text for TextInput demo (DannyS712)
- docs: Use v-bind for boolean `forceReset` prop (Anne Tomasevich)
- docs, Menu: Remove outdated slot (Anne Tomasevich)
- docs: Set VitePress text color to `color-base` (Anne Tomasevich)
- docs: Allow configuring icon properties and generate correct code (DannyS712)
- ToggleButton: Add icon-only demo (Roan Kattouw)
- docs: Allow configuring icons used as slot contents (DannyS712)
- docs: Add paragraph about dealing/organizing 'CHANGELOG.md' (Volker E.)
- docs: move more code generation logic from Wrapper.vue to codegen.ts (DannyS712)
- docs, styles: Improve interaction of code button borders (Anne Tomasevich)


# 0.1.0-alpha.5 / 2022-03-15
## Features
- Replace useMenu composable with Menu component (Roan Kattouw)
- MenuItem: Change Option to MenuItem (Anne Tomasevich)
- Menu, MenuItem: Add menuConfig, enable boldLabel & hideDescriptionOverflow (Anne Tomasevich)
- MenuItem: Merge in ListTile and reflect updated designs (Anne Tomasevich)
- ToggleButton: add ToggleButton component (DannyS712)
- SearchInput: Add the SearchInput component (Anne Tomasevich)
- build, tokens: Add deprecation functionality to tokens (Volker E.)

## Styles
- Button, styles: Replace attribute with `:enabled`/`:disabled` pseudo classes (Volker E.)
- Combobox, styles: Replace menu styles with `options-menu` mixin (Volker E.)
- Checkbox, Radio, styles: Unify enabled and disabled CSS logic and fix `:active` (Volker E.)
- Button, styles: Remove Button `:focus` outline reset (Volker E.)
- TextInput, styles: Replace attribute with `:enabled`/`:disabled` pseudo classes (Volker E.)
- ToggleSwitch, styles: Unify disabled and enabled CSS logic (Volker E.)
- ToggleSwitch, styles: Remove unused `margin-left` transition (Roan Kattouw)
- styles: Fix `transform` value on center aligned menu item (Volker E.)
- styles: Add button styles mixin to avoid style duplication (DannyS712)
- styles: Remove element selectors (Volker E.)
- Lookup, tokens: Make Lookup component use Codex tokens (Volker E.)
- Message, tokens: Make Message component use Codex tokens (Volker E.)
- Select, tokens: Make Select component use Codex tokens (Volker E.)
- Combobox, tokens: Make Combobox component use Codex tokens (Volker E.)
- Button, tokens: Make Button component use Codex tokens (Volker E.)
- TextInput, tokens: Use `transition-property-base` (Volker E.)
- ListTile, ListTileLabel, tokens: Make ListTile components use Codex tokens (Volker E.)
- Checkbox, Radio, tokens: Make binary input components use Codex tokens (Volker E.)
- ToggleSwitch, tokens: Make toggle switch component use Codex tokens (Volker E.)
- TypeaheadSearch, tokens: Make typeahead search component use Codex tokens (Volker E.)
- styles: Use common file for non-component specific mixins (Volker E.)
- styles: Fix fixed transform on Combobox use of 'menu-icon.less' (Volker E.)
- tokens: Add `transition-property.base` and `.icon` (Volker E.)
- tokens: Explain usage of `position.offset` tokens (Volker E.)
- tokens: Add `color` and `border-color` for message components & validation (Volker E.)
- tokens: Add `margin-top.options-menu` for Options menu (Volker E.)
- tokens: Add binary components specific tokens (Volker E.)
- tokens: Remove `border-radius-rounder` (Volker E.)
- tokens: Add `border-binary-input` shorthand (Volker E.)
- tokens: Add `cursor` property tokens (Volker E.)
- styles, tokens: Replace SFC `cursor` tokens with Codex design tokens (Volker E.)
- tokens: Convert remaining deprecated tokens to new style (Roan Kattouw)
- tokens: Move `color-primary` from base to components (Volker E.)
- tokens: Add `margin-offset-border-width-base` and remove menu component token (Volker E.)
- icons: Skew 'italic-arab-keheh-jeem' and bolden 'bold-arab-dad' icons (Volker E.)

## Code
- Combobox: Remove superfluos `aria-disabled` attribute (Volker E.)
- Select: Set `aria-multiselectable="false"` (Roan Kattouw)
- Lookup: Simplify code (Roan Kattouw)
- useMenu: Remove inputValue feature, replace with updateSelectionOnHighlight (Roan Kattouw)
- useMenu: Remove footerCallback feature (Roan Kattouw)
- TypeaheadSearch: Simplify input change handling (Anne Tomasevich)
- Menu: Fix selectedValue documentation rendering (Roan Kattouw)
- binary inputs: Remove `aria-disabled` overtaken by input's `disabled` (Volker E.)
- binary-input: Remove use of `[ class$='...' ]` selector (Roan Kattouw)
- build: Removing remaining references to 'WikimediaUI Base' and uninstall (Volker E.)
- build: Add "npm run build-all" command, clean up other commands (Roan Kattouw)
- build: Explicitly set stylelint to modern support (Volker E.)
- build: Require all CSS classes to start with `cdx-` (Roan Kattouw)
- build: Update Stylelint packages to latest (Volker E.)
- build: Update 'style-dictionary' to latest (Volker E.)
- build: Enable eslint in hidden directories (Roan Kattouw)
- build, tokens: Make style-dictionary config.js config-only (Roan Kattouw)

## Documentation
- docs: Make tokens table copy button quiet again (Anne Tomasevich)
- demo: Use ToggleSwitch for boolean props in controls (Anne Tomasevich)
- docs: Restructure and provide more details on SVG optimization (Volker E.)
- docs: Standardize on cdx-docs prefix (Anne Tomasevich)
- docs: Normalize component demo formatting and language (Anne Tomasevich)
- docs: Use kebab-case for component names in *.md files (Roan Kattouw)
- docs: Add import statement to imported code snippet example (Roan Kattouw)
- docs: Rename `<Wrapper>` to `<cdx-demo-wrapper>` (Roan Kattouw)
- docs: Replace WikimediaUI Base with Codex design tokens reference (Volker E.)
- docs: Overwrite VitePress theme default html, body font size to `initial` (Volker E.)
- docs: Improve generated events and methods docs (Anne Tomasevich)
- docs, Controls.vue: remove unneeded uses of `<template>` wrappers (DannyS712)
- docs: Use Special:MyLanguage for Code of Conduct link (DannyS712)
- docs: Change "a Code of Conduct" to "the Code of Conduct" (Roan Kattouw)
- docs: Improve demos of components that use menus (Anne Tomasevich)
- docs: Set dir="ltr" on all non-component docs pages (Roan Kattouw)
- docs, ToggleButton: remove unneeded `ref` import from markdown page (DannyS712)
- docs: Normalize to writing “Less” (Volker E.)
- docs, Wrapper: add a "reset" button (DannyS712)
- docs, Wrapper: add a "copy" button for code samples (DannyS712)


# v0.1.0-alpha.4 / 2022-02-18
## Styles
- tokens: Fix `background-color-framed--hover` to set to `#fff` (Volker E.)
- tokens: Update input padding token to match WMUI value (Anne Tomasevich)

## Code
- build: Add 'branch-deploy' npm script, for WMF CI to call (Roan Kattouw)
- build: Bump .nvmrc to 16.9.1 (Roan Kattouw)
- build, icons: Rename LICENSE-MIT to LICENSE (Roan Kattouw)

## Documentation
- docs: Set CODEX_DOC_ROOT default to '/' not '' (James D. Forrester)
- docs: Explain that icons are monochrome, add SVG conventions (Roan Kattouw)
- docs: Make CODEX_DOC_ROOT default to / instead of /codex/main (Roan Kattouw)
- docs: Make VitePress base URL configurable as an environment variable (Roan Kattouw)
- docs: Explicitly set dir="ltr" on direction switcher (Roan Kattouw)

# v0.1.0-alpha.3 / 2022-02-17
## Features
- ToggleSwitch: Add ToggleSwitch component (Anne Tomasevich)
- TypeaheadSearch: Add `auto-expand-width` prop (Nicholas Ray)
- TypeaheadSearch: Add initial iteration of TypeaheadSearch (Anne Tomasevich)

## Styles
- TextInput, tokens: Make TextInput component use Codex tokens (Volker E.)
- tokens: Add 'input' and 'input-binary' component 'border-color' tokens (Volker E.)
- tokens: Fix `background-color-base--disabled` value (Volker E.)
- tokens: Add 'size-indicator' (Volker E.)
- icons, license: Set to MIT license (Volker E.)

## Code
- build: Change icons CJS build to UMD (Roan Kattouw)
- build, styles: Add further properties to 'stylelint-order' & align code (Volker E.)
- build: Update package-lock.json (Roan Kattouw)
- build: Enable safeBothPrefix for postcss-rtlcss (Roan Kattouw)
- build: Change browserslistrc to `modern-es6-only` (Lucas Werkmeister)
- build: Turn on 'lint:eslint' for JSON configuration files (Volker E.)
- build: Remove trailing whitespace from Codex's README.md (Roan Kattouw)
- build: Update 'package-lock.json' (Lucas Werkmeister)


# v0.1.0-alpha.2 / 2022-02-14
## Code
- build: Un-pin postcss, update to 8.4.6 (Roan Kattouw)
- build: Add LICENSE files to each package (Roan Kattouw)
- build: Copy SVGs to dist/images at the right time (Roan Kattouw)

## Documentation
- docs: Add a README.md file for the Codex package (Roan Kattouw)

# v0.1.0-alpha.1 / 2022-02-14
- Initial release
