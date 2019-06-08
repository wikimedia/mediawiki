# OOUI Release History
## v0.31.3 / 2019-04-03
### Features
* SelectFileInputWidget: Support multiple files (Ed Sanders)
* WikimediaUI theme: Allow inverted icons to appear anywhere (Roan Kattouw)

### Styles
* CheckboxMultiselect- & RadioselectInputWidget: Fix infusion reflow (Volker E.)
* DropdownInputWidget: Make WikimediaUI version useable for non-JS users (Volker E.)
* WikimediaUI theme: Increase and unify widget `line-height` (Volker E.)
* WikimediaUI theme: Reduce accessory icon's opacity in non-focussed state (Volker E.)
* WikimediaUI theme: Unify inlined FieldLayout padding (Volker E.)
* icons: Add 'error' icon to 'alerts' pack (Volker E.)
* icons: Amend 'helpNotice' filename (Volker E.)
* icons: Make 'error' octagon regular (Ed Sanders)

### Code
* SelectFile(Input)Widget: Remove addInput and inline setupInput (Ed Sanders)
* SelectFileInputWidget: Make 'title' behaviour consistent (Ed Sanders)
* build: Bump non-qunit devDependencies to latest where possible (Volker E.)
* build: Do not duplicate localisation messages and their docs in JS code (Bartosz Dziewoński)
* build: Have 'quick-build' use 'build-code' to include messages (Ed Sanders)
* build: Remove unnecessary 'enable-source-maps' task (Bartosz Dziewoński)
* build: We distribute icon/indicator/texture manifests, too (James D. Forrester)
* demos: Add Vietnamese labels (Volker E.)
* demos: Add `title` to LTR/RTL ButtonWidgets (Volker E.)
* demos: Re-order PHP TextInput demo to align with the JS demo (Volker E.)
* docs: Fix syntax errors in MenuLayout (Huji Lee)


## v0.31.2 / 2019-03-26
### Features
* CheckboxInputWidget: Add support for indeterminate state (Ed Sanders & Bartosz Dziewoński)

### Code
* DropdownInputWidget: Fix typo in Apex border styles (Ed Sanders)
* SelectFileInputWidget: Apply IE11 scrolling fix (Ed Sanders)
* TextInputWidget: Remove proprietary vendor UI extensions (Volker E.)
* PHP: Tag: Use strict comparison for `array_search` (Ed Sanders)
* icons: Identical optimization to both newspaper-ltr… and …rtl.svg icons (Thiemo Kreuz)
* icons: Make use of the auto-closing feature in SVG `<path>`s (Thiemo Kreuz)
* icons: Remove non-standard offset from web.svg icon (Thiemo Kreuz)
* demo: Match PHP toolbar to JS (Ed Sanders)
* build: Update package-lock.json (Ed Sanders)
* build: Upgrade js-yaml sub-dependency from 3.12.1 to 3.13.0 for DoS fix (James D. Forrester)
* packages: Massively trim down which files are in npm and composer packages (James D. Forrester)


## v0.31.1 / 2019-03-21
### Deprecations
* [DEPRECATING CHANGE] core: Remove unused Date.now fallback (Timo Tijhof)
* [DEPRECATING CHANGE] textures: Deprecate 'pending.gif' (Volker E.)
* [DEPRECATING CHANGE] textures: Deprecate unused 'transparency' (Volker E.)

### Features
* MenuTagMultiselectWidget: `hideOnChoose` should be set to false (Moriel Schottlender)
* MenuTagMultiselectWidget: `highlightOnFilter` only if not `allowArbitrary` (Moriel Schottlender)
* MenuTagMultiselectWidget: Fix highlight and scrolling to item behavior (Moriel Schottlender)
* SearchInputWidget: Use click handler for indicator (Ed Sanders)
* SelectWidget: Allow multiselect mode, add to MenuTagMultiselectWidget (Moriel Schottlender)
* SelectFileWidget: Support a button-only mode (Ed Sanders)
* SelectFileWidget: Suppress misleading browser default tooltips (Bartosz Dziewoński)
* SelectFileInputWidget: Create as a super-class of SelectFileWidget (Ed Sanders)
* SelectFileInputWidget: Allow button config to be passed (Ed Sanders)
* TagMultiselectWidget: Edit by item label, not data (Moriel Schottlender)

### Styles
* Separate SelectFileWidget and SelectFileInputWidget styles (Ed Sanders)
* themes: Provide `background` needed for PendingElement on inputs (Volker E.)
* themes: Replace 'pending.gif' with CSS animation (Volker E.)
* icons: Manually rewrite paths of tableMove….svg icons (Thiemo Kreuz)
* icons: Recreate settings.svg icon with shorter syntax (Thiemo Kreuz)
* icons: Remove invisible parts from web.svg icon (Thiemo Kreuz)
* icons: Remove unused dotted borders from imageLayout….svg icons (Thiemo Kreuz)
* icons: Use rounded <rect> elements to optimize some SVG icons (Thiemo Kreuz)

### Code
* MenuSectionOptionWidget: Avoid select events (Gabriel Birke)
* SelectFileInputWidget: Rewrite as an ActionFieldLayout (Ed Sanders)
* testsuitegenerator: Reduce PHP test count by 40% (Ed Sanders)
* testsuitegenerator: Reduce some code duplication (Bartosz Dziewoński)
* testsuitegenerator: Use normal methods more instead of lambdas (Bartosz Dziewoński)
* docs: Clarify some types in documentation (Bartosz Dziewoński)
* docs: Fix missing `;` and typos in documentation examples (Volker E.)
* demos: Make demo toolbar narrower (Ed Sanders)
* build: Specify library entry (Stephen Niedzielski)
* Grunt: Add a quick-build-code task for JS-only quick builds (Ed Sanders)


## v0.31.0 / 2019-03-13
### Breaking changes
* [BREAKING CHANGE] Remove FlaggedElement from InputWidget (Ed Sanders)
* [BREAKING CHANGE] Remove method names deprecated in 0.28.3 (Ed Sanders)
* [BREAKING CHANGE] indicators: Drop 'search', deprecated in v0.30.0 (James D. Forrester)
* [BREAKING CHANGE]: Drop `iconTitle` and `indicatorTitle`, deprecated in v0.30.0 (James D. Forrester)

### Features
* Add 'success' message type (Volker E.)
* Make mixin configs extendable (Ed Sanders)
* PanelLayout: Create preserveContent config (Ed Sanders)
* SelectFileWidget: Be consistent with showDropTarget requiring droppable (Ed Sanders)
* SelectFileWidget: Mixin TabIndexedElement (Ed Sanders)
* PHP: Added server-side version of IndexLayout (Cormac Parle)
* PHP: Implement MenuLayout (Ed Sanders)
* PHP: Implement StackLayout (Ed Sanders)
* PHP: Implement TabPanelLayout (Ed Sanders)
* PHP: Implement TabSelectWidget/TabOptionWidget (Ed Sanders)
* PHP: Preserve content inside PanelLayout and test (Ed Sanders)

### Styles
* WikimediaUI theme: Fix ComboBoxInputWidget rounded corners (Bartosz Dziewoński)
* WikimediaUI theme: Fix toolbar tools' `padding` (Volker E.)

### Code
* MenuLayout.php: Fix visiblity of properties and default config values (Ed Sanders)
* Tag.php: Fix (ap/pre)pendContent to behave like JS DOM (Ed Sanders)
* PHP tests: Only test ltr/rtl for 'dir', remove value='b' tests (Ed Sanders)
* PHP tests: Only test one string for inputId (Ed Sanders)
* demo: Unify demo navigation toolbars (Ed Sanders)
* docs: Change docblock style for array elements in $config (Daimona Eaytoy)
* build: Upgrade grunt-svg2png to 0.2.7-wmf.2 for audit fixes (James D. Forrester)
* build: Upgrade imagemin-zopfli to 6.0.0 for audit fix (James D. Forrester)
* build: Upgrade javascript-stringify to 2.0.0 for audit fix (James D. Forrester)
* eslint: Enable cache (Ed Sanders)


## v0.30.4 / 2019-03-06
### Deprecations
* [DEPRECATING CHANGE] SelectWidget: Rename '-depressed' to '-unpressed' (Ed Sanders)
* [DEPRECATING CHANGE] icons: Deprecate 'web' from 'editing-citation' (Volker E.)

### Features
* Implement 'error' flag and 'warning' type messages (Volker E.)
* MenuSelectWidget: Add 'filterMode' (Moriel Schottlender)

### Styles
* Apex theme: Bring icons and layout styles from WikimediaUI theme (Volker E.)
* ButtonElement: Add styling for disabled active framed buttons (Bartosz Dziewoński)
* icons: Snap 'camera' icon's frame to pixel grid (Ed Sanders)
* icons: Add 'articleAdd' to 'content' pack (Volker E.)
* icons: Add 'imageLayout…' icons to 'editing-advanced' pack (Volker E.)
* WikimediaUI theme: De-emphasize `opacity` on TextInputWidget icons (Volker E.)
* WikimediaUI theme: Give user messages more whitespace (Volker E.)
* WikimediaUI theme: Place icons at top of message (Volker E.)
* themes: Fix TagItemWidget's vertical alignment in Safari (Volker E.)
* themes: Fix `padding` of label in DropdownWidget (Volker E.)
* themes: Provide 'emphasized' color for messages (Volker E.)

### Code
* Consistently spell "access key" (Bartosz Dziewoński)
* Follow-up I5991001e257: Add missing function call to normalize query (Ed Sanders)
* Follow-up I5991001e: Do not filter item if query is empty (Moriel Schottlender)
* MenuTagMultiselectWidget: Use 'highlightOnFilter' flag in MenuSelectWidget (Ed Sanders)
* SelectWidget: Rewrite getItemMatcher without regular expressions (Ed Sanders)
* Tag.php: Prevent duplicates in class list (Ed Sanders)
* TextInputWidget: Reduce selector where applicable (Volker E.)
* themes: Unify TextInput selector code (Volker E.)
* build: Consistently indent .eslintrc.json files with tabs (Bartosz Dziewoński)
* build: Enable eslint 'max-len' in code and fix (James D. Forrester)
* build: Remove obsolete stylelint overrides (Volker E.)
* build: Update eslint-config-wikimedia to 0.11.0 (Ed Sanders)
* docs: Unify key names in documentation (Volker E.)
* icons: Manually optimize the SVG code of some icons (Thiemo Kreuz)
* icons: Re-crush with 'svgmin' build task (Volker E.)
* icons: Remove redundant `ry="…"` SVG attribute when identical to `rx="…"` (Thiemo Kreuz)


## v0.30.3 / 2019-02-20
### Styles
* WikimediaUI theme: Align TagItemWidget's close icon correctly (Volker E.)
* WikimediaUI theme: Provide single-line TextInputWidgets with a distinct height (Volker E.)
* WikimediaUI theme: Unify `padding-top` and `padding-bottom` values (Volker E.)
* WikimediaUI theme: Use consistent base size for TagMultiselectWidget's input (Volker E.)
* WikimediaUI theme: Use distinct `height` for NumberInputWidget's widgets (Volker E.)

### Code
* Deprecation warnings for this.$ (Bartosz Dziewoński)
* ComboBoxInputWidget: Disable controls when widget is set to read-only (Ed Sanders)
* MenuSelectWidget: Documentation fix (Ed Sanders)
* ProgressBarWidget: Fix irregularities in indeterminate styling (Bartosz Dziewoński)
* TagMultiselectWidget: Populate input with item label on Backspace key press (Thalia Chan)
* Update getScrollLeft from upstream (Ed Sanders)
* themes: Replace element by class attribute selector (Volker E.)
* WikimediaUI theme: Remove variables with duplicated values (Volker E.)
* build: Enforce selector prefixes in tutorials by stylelint (Ed Sanders)
* build: Update eslint-config-wikimedia from 0.10.0 to 0.10.1 (James D. Forrester)
* build: Updating mediawiki/mediawiki-codesniffer to 24.0.0 (libraryupgrader)
* demos: Address oversized ButtonWidget (icon-only) in IE & Edge (Volker E.)
* demos: Render demo header cleaner from top (Volker E.)
* demos: Use `demo-root` class in PHP demos as well (Volker E.)
* docs: Fix URI in description (Volker E.)


## v0.30.2 / 2019-01-22
### Features
* Allow dropdown menu items to be disabled (Sam Wilson)

### Styles
* Align new icons to pixel grid (Bartosz Dziewoński)
* Fix transparency of 'unFlag' icon in RTL (Bartosz Dziewoński)
* themes: Use 'clear' icon for clearing SelectFileWidget's input (Volker E.)
* icons: Add Wikidata logo to 'Wikimedia' pack (James D. Forrester)
* icons: Add Wikimedia logo to 'Wikimedia' pack (James D. Forrester)
* icons: Use complete glyph for 'musicalScore' icon (Ed Sanders)
* icons: Update 'referenceExisting' and 'references' (Volker E.)

### Code
* DropdownWidget: `$handle` needs to carry `type="button"` (Volker E.)
* GroupElement: Make add/remove operations no-ops if items is empty (Kosta Harlan)
* WikimediaUI theme: Prevent z-index leaks for radios and checkboxes (Bartosz Dziewoński)
* build: Fix colorize SVG regression on icon `title` elements (Volker E.)
* build: Enable eslint-plugin-html to lint JS in HTML files (Ed Sanders)
* build: Enable eslint reportUnusedDisableDirectives (Ed Sanders)
* build: Enforce stylelint selector prefixes in code and demos (Ed Sanders)
* build: Update package-lock.json (James D. Forrester)
* icons: Add missing `<title>` to 'web' icon (Bartosz Dziewoński)
* icons: Enable invert & progressive flag on 'editing-citation' pack (Volker E.)
* icons: Remove `fill` from 'robot' to enable colorizing it (Volker E.)
* icons: Remove invisible path from 'unBlock' icon (Bartosz Dziewoński)
* icons: Remove unnecessary `fill-rule` attribute from icon code (Bartosz Dziewoński)
* icons: Remove unused code from 'camera' icon (Bartosz Dziewoński)


## v0.30.1 / 2019-01-09
### Deprecations
* [DEPRECATING CHANGE]: Deprecate `iconTitle` and `indicatorTitle` (Volker E.)

### Styles
* icons: Decrease 'close' size marginally (Volker E.)
* themes: Fine tune library 'close' icon usages (Volker E.)

### Code
* Add TitledElement mixin to all main widgets where useful (Volker E.)
* Clean up handling of `aria-expanded` attribute (Bartosz Dziewoński)
* DropdownInputWidget: Fix mixing in TitledElement twice (Bartosz Dziewoński)
* MultilineTextInputWidget: Move `styleHeight` property into widget from parent (Volker E.)
* Replace double TitledElement mixins in several widgets (Volker E.)
* build: Commit package-lock.json (James D. Forrester)
* build: Bump various devDependencies to latest (Volker E.)
* build: Update eslint-config-wikimedia to 0.10.0 (Volker E.)
* build: Upgrade grunt-banana-checker from 0.6.0 to 0.7.0 (James D. Forrester)
* demos: Replace most unicode LTR markers with CSS rule (Volker E.)
* docs: Bump copyright year for 2019 (James D. Forrester)
* docs: Unify code examples and describe MultilineText- & SearchInputWidget (Volker E.)
* tests: Make JS/PHP comparison tests async (Bartosz Dziewoński)
* tests: Reduce code duplication in JS/PHP comparison tests (Bartosz Dziewoński)
* tests: Unbreak JS/PHP tests for DropdownInputWidget (Bartosz Dziewoński)


## v0.30.0 / 2018-12-19
### Breaking changes
* [BREAKING CHANGE] Make non-continuous StackLayouts non-scrollable (Ed Sanders)
* [BREAKING CHANGE] icons: Drop 'advanced' icon, deprecated in v0.28.1 (Volker E.)

### Features
* DropdownInputWidget: Add `title` config option to handle (Volker E.)

### Deprecations
* [DEPRECATING CHANGE] Deprecate passing string IDs to infuse (Ed Sanders)
* [DEPRECATING CHANGE] PopupTagMultiselectWidget: Deprecate widget (Volker E.)
* [DEPRECATING CHANGE] indicators: Flag unused 'search' indicator as to be removed (Volker E.)

### Styles
* WikimediaUI theme: Make up for inner 'down' indicator distance (Volker E.)
* Apex theme: Align functionality of ComboBoxInputWidget with WikimediaUI theme (Volker E.)
* Apex theme: DropdownWidget align CSS code to WikimediaUI theme (Volker E.)
* Apex theme: Unify distance on icon and label TextInputWidget (Volker E.)
* Apex theme: Use variable for `text-shadow` and unify (Volker E.)
* icons: Add 'robot' icon to 'content' pack (Volker E.)
* icons: Add localized 'bold' and 'italic' for Urdu (Tulsi Bhagat)
* build: Update 'wikimedia-ui-base' to latest (Volker E.)

### Code
* Avoid HTML parsing (Ed Sanders)
* Avoid deprecated OO.ui.infuse( id ) (Ed Sanders)
* Use `-webkit-overflow-scrolling: touch` for scrollable things (Bartosz Dziewoński)
* ComboBoxInputWidget: Add 'label' and `aria-controls` attribute to button (Volker E.)
* ComboBoxInputWidget: `aria-expanded` needs to be set from intialization (Volker E.)
* DropDownWidget: Turn handle into `button` and add ARIA attribute (Volker E.)
* LookupElement: `aria-expanded` needs to be set from intialization (Volker E.)
* MenuTagMultiselectWidget: Clear input before adding tag (Thalia Chan)
* TagMultiselectWidget: Resize input when enabling (Thalia Chan)
* WindowManager: Move inline CSS to a class (Bartosz Dziewoński)
* Hygiene: Don't put a space after mixin names when defining them (Bartosz Dziewoński)
* i18n: Fix 'tooltip' in qqq descriptions (Volker E.)
* build: Bump various devDependencies to latest (James D. Forrester)
* build: Fix case of 'LESS' in comments (Volker E.)
* tests: Unbreak unit tests (Bartosz Dziewoński)
* demos: Add ARIA `role="main"` to PHP demo (Volker E.)
* demos: Add labels to remaining DropdownWidgets (Volker E.)
* demos: Don't showcase 'indicator' only buttons explicitly (Volker E.)
* demos: Ensure color contrast on special, non-production summary example (Volker E.)
* demos: Avoid implicit globals in infusion demo (Ed Sanders)
* demos: Let buttons in PHP demo carry screen reader labels (Volker E.)
* demos: Make the interface usable on mobile (Bartosz Dziewoński)
* demos: Reorder icons and indicators (Volker E.)
* demos: Use appropriate 'helpNotice' icon for location (Volker E.)
* demos: Use system monospace font stack following Style Guide (Volker E.)
* tutorials: Center box shadows (Ed Sanders)
* tutorials: Replace $(document).ready with $(fn) (Ed Sanders)
* tutorials: Select current page in dropdown (Ed Sanders)
* tutorials: Use CSS transitions for scroller (Ed Sanders)
* tutorials: Use system monospace font stack following Style Guide (Volker E.)


## v0.29.6 / 2018-12-04
### Styles
* Match BookletLayout menu's width and animations to Dialog's (Bartosz Dziewoński)
* WikimediaUI theme: Ensure `transition` of PopupToolGroup in actions toolbar (Volker E.)
* icons: Union the paths in 'undo' and 'redo' (Ed Sanders)
* icons: Use correct 'settings' title (Volker E.)

### Code
* BrokenDialog: Remove superfluous and broken second parent call (Roan Kattouw)
* MenuTagMultiselectWidget: Allow adding arbitrary values (Moriel Schottlender)
* ProcessDialog: Fit label (dialog title) when it changes (Bartosz Dziewoński)
* Remove 'jQuery' alias (Ed Sanders)
* TagItemWidget: Fix operator precendence (James D. Forrester)
* eslint: Drop 'dot-notation' rule (James D. Forrester)
* eslint: Enable jquery/no-(show/hide/toggle) rules (Ed Sanders)
* eslint: Fix config extends, and move 'no-void' rule overrides inline (Ed Sanders)
* build: Enable 'at-rule-empty-line-before' stylelint rule and make pass (Volker E.)
* build: Reintroduce icons to dist images-theme CSS files (Volker E.)
* build: Remove over-ride for max-len in build code (James D. Forrester)
* build: Update 'eslint-config-wikimedia' to v0.9.0 and make pass (Volker E.)
* build: Update mediawiki/mediawiki-codesniffer to 23.0.0 (libraryupgrader)
* build: Update stylelint-config-wikimedia to 0.5.0 and make pass (James D. Forrester)
* docs: JSDuck: Use same font-size as elsewhere (Volker E.)
* demos: Add `rel="noopener"` to accessibility explanation links (Volker E.)
* demos: CSS fixes for mobile dialogs demo (Bartosz Dziewoński)
* demos: Use better icons (Volker E.)


## v0.29.5 / 2018-11-08
### Code
* MenuTagMultiselectWidget: Clear input if adding valid tag (Thalia Chan)
* TagMultiselectWidget: Rename `limit` config to `tagLimit` (Thalia Chan)


## v0.29.4 / 2018-11-06
### Features
* TagMultiSelectWidget: Add a `limit` configuration option (Moriel Schottlender)
* TagMultiselectWidget: Make widget invalid if there's text in input (Moriel Schottlender)

### Styles
* PopupTool: Prevent flipping the popup opposite to the toolbar position (Bartosz Dziewoński)
* WindowManager: Better avoid content shifting when disabling page scrollbars (Bartosz Dziewoński)
* WikimediaUI theme: Tame cut-off letter issue (Volker E.)

### Code
* Dialog.detachActions: Make this method chainable, as documented (James D. Forrester)
* FloatableElement: Remove check for `needsCustomPosition` (Bartosz Dziewoński)
* build: Enable `valid-jsdoc` (James D. Forrester)
* demos: Use consistent options descriptions (Volker E.)
* Fix errors flagged by ESLint's `valid-jsdoc` option (Volker E.)
* doc: Add documentation for event handlers (James D. Forrester)
* doc: ButtonWidget.setHref: Add chainable documentation (James D. Forrester)
* doc: Duplicate `@chainable` with manual `@return` comment (James D. Forrester)
* doc: Ensure consistent PHP-DOC annotation (Volker E.)
* doc: TagItemWidget.isFixed: Add return documentation (James D. Forrester)
* doc: WindowManager.openWindow: Explcitly disable `valid-jsdoc` for private parameters (Volker E.)


## v0.29.3 / 2018-10-31
### Features
* LabelElement: Allow invisible accessibility labels (Bartosz Dziewoński)
* PanelLayouts: Add #resetScroll method, and implement in complex layouts (Ed Sanders)

### Styles
* Allow ButtonGroupWidget/SelectWidget buttons to spill on to new lines (Bartosz Dziewoński)
* icons: Visually center 'next' and 'previous' horizontally (Volker E.)
* themes: Use base color for DecoratedOption-/MenuOptionWidget (Volker E.)
* WikimediaUI theme: Make TagItemWidgets slightly less obstrusive (Volker E.)

### Code
* Add missing default for a localisation message (Bartosz Dziewoński)
* Allow setting the label to "0" in PHP (Bartosz Dziewoński)
* DropdownWidget: Fix keypress handling when menu is closed (Bartosz Dziewoński)
* DropdownWidget: Fix vertical alignment with other widgets in some layouts (Volker E.)
* FloatableElement: Fix typo in a condition causing it to always be true (Bartosz Dziewoński)
* WikimediaUI theme: Fix specificity of IndexLayout override (Ed Sanders)
* demos: Add ActionLayout with DropdownWidget demo (Volker E.)
* demos: Restore lost PHP demos (Bartosz Dziewoński)
* demos: Use 'previous' icon for one of the ToggleButtonWidgets (Volker E.)
* tutorials: Fix navigation items position (Volker E.)
* tutorials: Follow Wikimedia color in body default choice (Volker E.)


## v0.29.2 / 2018-10-08
### Code
* Follow-up Ib00d6720: Fix KeyDown listener name in MenuSelectWidget (Ed Sanders)
* Pass panels to MenuLayout (Ed Sanders)
* demos: Fix selected values in MenuTagMultiselectWidget demo (Bartosz Dziewoński)


## v0.29.1 / 2018-10-03
### Styles
* TabOptionWidget: Increase contrast between normal & selected states (Volker E.)

### Code
* MultilineTextInputWidget: Fix fatal (Bartosz Dziewoński)
* build: Fail in CI if there are uncommited build artefacts (James D. Forrester)
* tests: Commit JS/PHP comparison test suite (Bartosz Dziewoński)
* tests: Ensure consistent order in JSPHP-suite.json (Bartosz Dziewoński)
* tests: Ensure we write LF newlines to JSPHP-suite.json, even on Windows (Bartosz Dziewoński)
* tests: Fix generation of JS/PHP comparison test suite (Bartosz Dziewoński)
* tests: Increase Karma tests timeout so that they actually finish (Bartosz Dziewoński)


## v0.29.0 / 2018-10-02
### Breaking changes
* [BREAKING CHANGE] Consistently name document listeners (Ed Sanders)
* [BREAKING CHANGE] Drop CapsuleMultiselectWidget, deprecated since v0.27.5 (James D. Forrester)
* [BREAKING CHANGE] Formally require PHP 7 (5.6.99+) (James D. Forrester)
* [BREAKING CHANGE] TextInputWidget: Drop support for `multiline: true` (James D. Forrester)
* [BREAKING CHANGE] Upgrade jQuery from 3.2.1 to 3.3.1 (James D. Forrester)
* [BREAKING CHANGE] Use PHP 5.6 variadic function syntax (Bartosz Dziewoński)
* [BREAKING CHANGE] Use PHP 7 "\u{NNNN}" Unicode codepoint escapes (Bartosz Dziewoński)
* [BREAKING CHANGE] Use PHP 7 '??' operator instead of '?:' with 'isset()' (Bartosz Dziewoński)

### Features
* Use jQuery 3.3.x class feature (Ed Sanders)

### Styles
* icons: Refine 'userAvatar' slightly (Volker E.)

### Code
* Avoid including the `/**` comment from WikimediaUI Base in our output (Bartosz Dziewoński)
* Centralize the definition of which classes belong in which module (Bartosz Dziewoński)
* Remove unnecessary empty-theme.less (Bartosz Dziewoński)
* WikimediaUI theme: Correct several code comments (Volker E.)
* WikimediaUI theme: Remove vars covered by WikimediaUI Base vars (Volker E.)
* build: Bump eslint-config and grunt-karma devDependencies to latest (James D. Forrester)
* build: Updating mediawiki/mediawiki-codesniffer to 22.0.0 (James D. Forrester)
* demos: Unbreak from reliance on removed CapsuleMultiselectWidget (James D. Forrester)


## v0.28.2 / 2018-09-11
### Deprecations
* [DEPRECATING CHANGE]: icons: Rename 'advanced' to 'settings' (Volker E.)

### Features
* NumberInputWidget: Rethink 'step' semantics (Bartosz Dziewoński)

### Styles
* WikimediaUI theme: Slightly reduce 'close' icon in PopupWidget's popup (Volker E.)
* icons: Add 'globe' to 'location' pack (Volker E.)
* icons: Add 'helpNotice' to 'interactions' pack (Volker E.)

### Code
* build: Bump devDependencies to latest where possible (James D. Forrester)
* docs: Revert "docs: Don't refer to a renamed icon 'settings', use 'advanced'" (James D. Forrester)


## v0.28.1 / 2018-09-04
### Styles
* icons: Add several 'editing-advanced' and 'media' pack icons (Volker E.)
* icons: Make 'camera' visible in demos (Volker E.)
* icons: Swap LTR and RTL versions of 'stripeToC' (Roan Kattouw)
* icons: Use 'lightbulb' in Arabic in place of 'info' (Volker E.)
* Apex theme: Fix NumberInputWidget button width (Volker E.)

### Code
* Improve PHPCS performance by not listing ignored files (Bartosz Dziewoński)
* Restore missing icons and fix broken docs link in OOUI tutorials toolbar (Hagar Shilo)
* themes: Cleanup `@min-size` & remove `*-numberinput` variables (Volker E.)
* build: Bump wikimedia-ui-base (James D. Forrester)
* icons: Update 'pageSettings' SVG title (Volker E.)
* docs: Correct documentation for Window#open and Window#close (Bartosz Dziewoński)
* docs: Don't refer to a renamed icon 'settings', use 'advanced' (James D. Forrester)
* demos: Don't try to use the removed 'comment' icon (Bartosz Dziewoński)
* demos: Remove some irrelevant icons in toolbars demo (Bartosz Dziewoński)
* demos: Use renamed 'pageSettings' icon (Volker E.)
* tests: Add tests for Tag::appendContent, Tag::prependContent, Tag::clearContent (Bartosz Dziewoński)


## v0.28.0 / 2018-08-14
### Breaking changes
* [BREAKING CHANGE] icons: Drop 'find' icon, deprecated in v0.26.2 (James D. Forrester)
* [BREAKING CHANGE] icons: Drop 'settings' icon, deprecated in v0.27.0 (James D. Forrester)
* [BREAKING CHANGE] icons: Drop cite icons, renamed and deprecated in v0.27.0 (James D. Forrester)
* [BREAKING CHANGE] icons: Remove 'clip' & 'unClip', deprecated in v0.26.1 (Volker E.)
* [BREAKING CHANGE] icons: Remove 'comment', deprecated in v0.26.1 (James D. Forrester)
* [BREAKING CHANGE] icons: Remove deprecated 'userActive'/'userInactive' (Volker E.)

### Styles
* FieldLayout inline help: Move help after field when align=top (Ed Sanders)

### Code
* DropdownInputWidget: Add support for $overlay (Alangi Derick)
* LookupElement: Fix empty search result menu (Tim Eulitz)
* PopupTools & ToolGroupTools: Emit active events from PopupTools & ToolGroupTools (Ed Sanders)
* Toolbar: Emit events to let user know if toolbar popups are visible (Ed Sanders)
* Revert "FieldLayout: Avoid unclickable gap between widget and label in 'inline' align" (Bartosz Dziewoński)
* Apex theme: Align `@transition` vars naming with WikimediaUI theme (Volker E.)
* Apex theme: Rename `@destructive` var to naming convention (Volker E.)
* Apex theme: Rename `@progressive*` vars to naming convention (Volker E.)
* WikimediaUI theme: Fix regression on SelectFileWidget icon/indicator visibility (Volker E.)
* WikimediaUI theme: Make use of further WikimediaUI Base variables (Volker E.)
* docs: Always use the correct casing for MediaWiki (James D. Forrester)
* tutorials Create 2 OOUI tutorials and an index page (Hagar Shilo)
* tutorials: Don't load duplicate CSS (Bartosz Dziewoński)
* tutorials: Fix CSS links (Moriel Schottlender)
* build: Bump eslint-config-wikimedia to v0.7.2, disabling failing rules (James D. Forrester)
* build: Bump grunt-contrib-less to v2.0.0 and enable javascriptEnabled (James D. Forrester)
* build: Bump non-qunit devDependencies to latest where possible (James D. Forrester)
* build: Bump OOjs to v2.2.2 (James D. Forrester)
* build: Bump qunit-related devDependencies to latest (James D. Forrester)
* build: Bump wikimedia-ui-base to v0.11.0 (Volker E.)
* tests: Enable `qunit/no-assert-equal` and make pass (James D. Forrester)
* tests: Enable `qunit/no-negated-ok` and make pass (James D. Forrester)
* tests: Enable `qunit/no-ok-equality` and make pass (James D. Forrester)
* tests: Enable `qunit/require-expect` and make pass (James D. Forrester)


## v0.27.6 / 2018-08-01
### Styles
* WikimediaUI theme: Fix styling for focussed multiline text inputs in invalid state (Bartosz Dziewoński)
* Apex theme: Fix regression on ToggleSwitchWidget `border` (Volker E.)
* Apex theme: Further unify `border-radius` (Volker E.)

### Code
* MenuTagMultiselectWidget: Cascade disable state to menu (Moriel Schottlender)
* MultilineTextInputWidget: Remove 'name' and 'id' from $clone (Prateek Saxena)


## v0.27.5 / 2018-07-11
### Deprecations
* [DEPRECATING CHANGE] CapsuleMultiselectWidget: Deprecate widget (Volker E.)

### Styles
* CheckboxInputWidget, RadioInputWidget: Use `display: inline-block` in all themes (Bartosz Dziewoński)
* MessageDialog: Replace special button treatment with framed buttons (Volker E.)
* WikimediaUI theme: Apply new `ease-out` variable to dialogs (Volker E.)
* WikimediaUI theme: Replace and remove cubic bezier `transition` option (Volker E.)
* WikimediaUI theme: Replace hard-coded value with var (Volker E.)
* Apex theme: Make button faux 3D effect more subtle (Volker E.)
* Apex theme: Restore space between inline FieldLayout field and label (Bartosz Dziewoński)
* Apex theme: Unify `border` values (Volker E.)
* Apex theme: Unify close `border-radius` values (Volker E.)

### Code
* Add taint annotations for phan-taint-check (Brian Wolff)
* Ensure window ready process runs after window is made visible (Bartosz Dziewoński)
* FieldLayout: Avoid unclickable gap between widget and label in 'inline' align (Bartosz Dziewoński)
* IndexLayout (TabPanelLayouts): Apply correct ARIA roles & attributes (Volker E.)
* MenuSelectWidget: Remove checks for unchanged input from updateItemVisibility() (Bartosz Dziewoński)
* build: Update eslint config to 0.6.0 (Ed Sanders)


## v0.27.4 / 2018-06-27
### Styles
* icons: Add destructive variant for subtract icon (Sam Wilson)
* WikimediaUI theme: Remove some unused CSS (Bartosz Dziewoński)
* Apex theme: Actually display the icon of MenuToolGroup tools (Bartosz Dziewoński)
* Apex theme: Don't hide icons in elements nested in selected MenuOptionWidget (Bartosz Dziewoński)
* Apex theme: Fix placement of icon in DecoratedOptionWidget (Bartosz Dziewoński)

### Code
* Allow JS/PHP comparison tests for FieldLayout 'help' config option (Bartosz Dziewoński)
* Avoid mentioning 'iconTitle' config option in doc examples (Bartosz Dziewoński)
* Dialog: Create getActionWidget(Config) to simplify customisation (Ed Sanders)
* FieldLayout: Add `for` attribute to inline help label (Prateek Saxena)
* FieldLayout: Reduce clutter in initialization function (Prateek Saxena)
* Follow-up I90a0a787: Add 'helpInline' to PHP FieldLayout (Ed Sanders)
* IconElement/IndicatorElement: Reduce specificity of basic styles (Bartosz Dziewoński)
* MenuSelectWidget: Move 'highlight first item' to end of operation (Moriel Schottlender)
* PopupWidget: Add setter for $autoCloseIgnore (Roan Kattouw)
* PopupWidget: Allow automatic width (not hardcoded) (Bartosz Dziewoński)
* PopupWidget: Listen to 'click' for 'mousedown' events in iOS (Moriel Schottlender)
* ProcessDialog: Use cached value of isMobile (Ed Sanders)
* Refactor how we apply `display: none` to unused icons and indicators (Bartosz Dziewoński)
* WindowManager: Only set `aria-hidden="true"` for modal managers (Bartosz Dziewoński)
* build: Exclude 'demos/vendor' from stylelint (Volker E.)
* build: Fix 'copy:fastcomposerdemos' task (Bartosz Dziewoński)
* styles: Remove proprietary IE 8 & 9 `-ms-filter` properties (Volker E.)
* themes: Improve top `padding` and `line-height` in MessageDialogs (Volker E.)


## v0.27.3 / 2018-06-07 (special release)
### Styles
* ActionFieldLayout: Improve `z-index` overrides on focus/hover (Bartosz Dziewoński)
* WikimediaUI theme: Remove label baseline dissonance (Volker E.)
* WikimediaUI theme: Reset SelectFileWidget's LabelElement-label (Volker E.)

### Code
* PopupButtonWidget: Remove `aria-haspopup` attribute (Volker E.)


## v0.27.2 / 2018-06-05
### Features
* Allow passing config objects to OO.ui.infuse (Ed Sanders)
* FieldLayout: Add 'helpInline' config (Prateek Saxena)
* LookupElement: Allow menu config to be passed in (Ed Sanders)
* MenuSelectWidget: Support starting positions other than 'below' (Ed Sanders)
* MenuTagMultiselectWidget: Allow icons in dropdown menus (Volker E.)
* TagMultiselectWidget: Make sure 'fixed' items can't be removed (Moriel Schottlender)

### Styles
* ActionFieldLayout: Visually combine inputs and their buttons (Volker E.)
* MenuLayout: Avoid `transition: all`, be precise (Bartosz Dziewoński)
* icons: Make bold-cyrl-palochka.svg perfectly symmetrical (Bartosz Dziewoński)
* WikimediaUI theme: Apply distinct “pill” appearance to tags (Volker E.)
* WikimediaUI theme: Improve TagMultiselect spacing & distance code (Volker E.)
* WikimediaUI theme: Move label `line-height` to LabelElement (Volker E.)
* WikimediaUI theme: Reduce `line-height` varieties across widgets (Volker E.)
* WikimediaUI theme: Use 'progressive' icons for pressed/selected MenuOptionWidget (Bartosz Dziewoński)
* Apex theme: Remove vertical padding from label widget (Ed Sanders)


## v0.27.1 / 2018-05-29
### Deprecations
* [DEPRECATING CHANGE] Toolbar: Add a required 'name' property to toolgroup configs (Ed Sanders)

### Styles
* Add bold icon for Chechen language (Ed Sanders)
* FieldLayout: Give help icon space when align=left (Prateek Saxena)
* MenuSelectWidget: Allow dropdown menus to be larger than their handles (Ed Sanders)
* themes: Clarify and align focus on TabselectWidget's selected tab (Volker E.)
* WikimediaUI theme: Replace fixed value with dedicated LESS var (Volker E.)
* WikimediaUI theme: Align DecoratedOptionWidget's icon opacity to other widgets (Volker E.)
* WikimediaUI theme: Fix PopupButtonWidget position (Volker E.)
* WikimediaUI theme: Fix regression on Safari bug (Volker E.)
* WikimediaUI theme: Fix unbalanced focus state in action toolbar (Volker E.)
* Apex theme: Reduce accumulated white-space in form fields (Volker E.)

### Code
* Don't auto-focus a booklet layout page when scrolling (Ed Sanders)
* OutlineControlsWidget: Remove 'add' icon (Bartosz Dziewoński)
* PopupToolGroup: Allow tabbing to the tools in the popup again (Bartosz Dziewoński)
* PopupToolGroup: Fix disappearing dropdown on very narrow screens (Bartosz Dziewoński)
* Toolbar: Remove unused .groups property (Ed Sanders)
* Toolbar: Rename a variable from 'group' to 'groupConfig' (Ed Sanders)
* build: Amend 'grunt-svgmin' options and re-crush SVGs (Volker E.)
* build: Updating mediawiki/mediawiki-codesniffer to 19.0.0 (libraryupgrader)
* build: Use .map.json extension for source maps (Bartosz Dziewoński)
* demos: Remove deprecated 'comment' icon (Volker E.)


## v0.27.0 / 2018-05-08
### Breaking changes
* [BREAKING CHANGE] GroupElement: Remove getItem(s)FromData (Prateek Saxena)
* [BREAKING CHANGE] MultiSelectWidget: Remove getSelectedItems and getSelectedItemsData (Prateek Saxena)
* [BREAKING CHANGE] SelectWidget: Remove getSelectedItem (Prateek Saxena)
* [BREAKING CHANGE] TagItemWidget: Replace 'disabled' items with 'fixed' (Moriel Schottlender)
* [BREAKING CHANGE] indicators: Remove 'alert', deprecated in v0.25.2 (James D. Forrester)

### Deprecations
* [DEPRECATING CHANGE] icons: Deprecate 'editing-citation' icons from 'content' (Volker E.)
* [DEPRECATING CHANGE] icons: Rename 'settings' to 'pageSettings' (Volker E.)

### Features
* Add an infusable PHP implementation of the NumberInputWidget (mainframe98)

### Styles
* TextInputWidget: Hide IE10+'s clear button when it conflicts with labels (Thiemo Kreuz)
* WikimediaUI theme: Don't add icon `padding` to menu tools with no icons (Ed Sanders)
* WikimediaUI theme: Fix TagItem's label and close position (Volker E.)
* WikimediaUI theme: Fix visual regression on toolbar menu border (Volker E.)
* Apex theme: Fix position of help icon in FieldLayout align=top (Ed Sanders)
* icons: Add 'editing-citation' pack (Volker E.)
* icons: Add `title` elements to new icons in 'editing-citation' pack (Volker E.)

### Code
* FieldLayout: Provide label to 'help' PopupButtonWidget in JS (Volker E.)
* MenuTagMultiselect: Use default onTagSelect if allowArbitrary (Daimona Eaytoy)
* NumberInputWidget: Add `aria-hidden` to buttons (Volker E.)
* ProcessDialog: Fix footer height when actions or dialog size changes (Bartosz Dziewoński)
* SelectFileWidget: Use `<label>` for select ButtonElement (Moriel Schottlender)
* Harmonize icon JSONs code (Volker E.)
* Improve test output in case of failures (Bartosz Dziewoński)
* demos: Add ellipsis to “Publish changes” to follow production (Volker E.)


## v0.26.5 / 2018-04-24
### Styles
* Add `overflow:hidden;` to dialog content (Ed Sanders)
* TagItemWidget: Make applying cutoff and ellipsis actually work (Bartosz Dziewoński)
* Use `vertical-align:top;` for check/radio label alignment (Ed Sanders)
* WikimediaUI theme: De-emphasize toolgroup borders (Volker E.)
* Apex theme: Ensure consistent height of PopupToolGroup handle (not zero) (Bartosz Dziewoński)
* Apex theme: Remove drop shadow from framed PanelLayout (Ed Sanders)

### Code
* MenuSelectWidget: Start positioning before starting to handle events (Bartosz Dziewoński)
* NumberInputWidget: Set inputs to empty if their DOM value is empty (Sam Wilson)
* PopupTool: Set active state depending on whether popup is open (Bartosz Dziewoński)
* Toolbar: Put all popups (from PopupToolGroup and PopupTool) into an overlay (Bartosz Dziewoński)
* build: Switch QUnit package from deprecated 'qunitjs' to 'qunit' (James D. Forrester)


## v0.26.4 / 2018-04-17
### Code
* Apex theme: Point pending.gif texture to a directory that exists (Kunal Mehta)
* Remove white canvases from table move icons (Ed Sanders)
* WindowManager: Return focus to element after resize (Prateek Saxena)
* build: Updating mediawiki/mediawiki-codesniffer to 18.0.0 (libraryupgrader)


## v0.26.3 / 2018-04-10
### Styles
* WikimediaUI theme: Restore background-size transition when checking a checkbox (Bartosz Dziewoński)
* icons: Add 'tableMoveColumn*' & 'tableMoveRow*' icons (Volker E.)

### Code
* CheckboxInputWidget: Don't specify icon in CSS (Bartosz Dziewoński)
* DropdownInput-/RadioSelectInputWidget: Fix support for 'tabIndex' (Bartosz Dziewoński)
* MenuOptionWidget: Don't specify icon in CSS (Bartosz Dziewoński)
* MenuToolGroup: Don't specify icon in CSS (Bartosz Dziewoński)
* PopupTagMultiselectWidget: Use `padding` in popup by default (Ed Sanders)
* Remove icon overrides for 'en-ca', 'en-gb' when 'en' suffices (Bartosz Dziewoński)
* Apex icons: Replace entire set with WikimediaUI theme's (Ed Sanders)
* WikimediaUI theme: Don't override selected MenuToolGroup tools' icon (Bartosz Dziewoński)
* build: Bump devDependencies to latest (James D. Forrester)
* demos: Update word processor toolbar styling from VisualEditor (Bartosz Dziewoński)


## v0.26.2 / 2018-04-04
### Deprecations
* [DEPRECATING CHANGE] icons: Add 'userAnonymous', and deprecate 'userActive'/'userInactive' (Volker E.)
* [DEPRECATING CHANGE] icons: Deprecate 'find' of 'editing-advanced' pack (Volker E.)

### Styles
* Blank theme: Use sizes of default theme WikimediaUI (Volker E.)
* WikimediaUI theme: Fix FieldSetLayout & FieldLayout's help icon position (Volker E.)
* WikimediaUI theme: Fix FieldLayout with help and align left/right (Bartosz Dziewoński)
* WikimediaUI theme: Fix miscalculated frameless button's icon position (Volker E.)
* WikimediaUI theme: Fix tool icons in popup toolgroups (Bartosz Dziewoński)
* WikimediaUI theme: Replace fixed spacing values with vars (Volker E.)
* WikimediaUI theme: Simplify SelectFileWidget's CSS (Volker E.)

### Code
* MultilineTextInputWidget: Allow `resize` except for on autosize (Prateek Saxena)
* TagMultiselectWidget: Fix arrow movement in inline input (Moriel Schottlender)
* Update OOjs to v2.2.0 (James D. Forrester)
* build: Updating mediawiki/mediawiki-codesniffer to 17.0.0 (libraryupgrader)
* build: colorize-svg.js – reorder functions to avoid forward references (Fomafix)
* demos: Add theme body classes in PHP demo (Volker E.)
* demos: Fix icon wrapping (Ed Sanders)
* icons: Fix size and position of most language variant styling icons (Ed Sanders)
* icons: Provide RTL 'help' icon for Arabic scripts (Volker E.)
* icons: Use correct glyphs for bold-a, italic-a, and strikethrough-a (Ed Sanders)
* icons: Use 'underline-u' in German (Ed Sanders)
* themes: Remove dash from variable prefix `@ooui` (Volker E.)


## v0.26.1 / 2018-03-23
### Deprecations
* [DEPRECATING CHANGE] icons: Flag 'comment' as to be removed (James D. Forrester)
* [DEPRECATING CHANGE] icons: Rename 'clip'/'unClip' to 'bookmark'/'bookmarkOutline' (Volker E.)

### Styles
* ButtonElement (framed): Remove `padding` on icon + indicator variant (Volker E.)
* WikimediaUI theme: Reduce distance of Tools in BarToolGroup (Volker E.)
* WikimediaUI theme: Reduce necessary widths for narrow toolbar elements (Volker E.)
* WikimediaUI icons: Amend 'help' icon to address feedback (Volker E.)
* WikimediaUI icons: Fix 'speechBubbles' icons (Volker E.)
* WikimediaUI icons: Fix 'underline-a' icon to be an 'a', not a 'u' (Ed Sanders)
* WikimediaUI icons: Slightly adapted size of 'clip'/'unClip' for algnment to other icons (Volker E.)
* WikimediaUI icons: Swap 'advanced' and 'settings' icons (Volker E.)

### Code
* WikimediaUI theme: Remove unused RTL variants of alignLeft/Right icons (Ed Sanders)
* WikimediaUI theme: Fix/remove unused icon files (Bartosz Dziewoński)
* demos: Add alert popout to toolbars demos (Volker E.)
* demos: Add specialCharacter terminal tool to toolbars demos (James D. Forrester)
* docs: Add Demos to JSDuck navigation menu (Timo Tijhof)
* build: Replace grunt-image with grunt-imagemin (James D. Forrester)
* icons: Re-crush SVGs (James D. Forrester)

## v0.26.0 / 2018-03-20
### Breaking changes
* [BREAKING CHANGE] WikimediaUI: Unify available variants across icon packs (Ed Sanders)
* [BREAKING CHANGE] icons: Remove 'alignCentre', renamed in v0.24.2 (James D. Forrester)
* [BREAKING CHANGE] icons: Remove 'arrowLast', deprecated since v0.25.0 (James D. Forrester)
* [BREAKING CHANGE] icons: Remove 'bellOn', deprecated in v0.25.0 (James D. Forrester)
* [BREAKING CHANGE] icons: Remove 'quotesAdd', deprecated in v0.24.4 (James D. Forrester)
* [BREAKING CHANGE] icons: Remove 'redirect', renamed in v0.24.4 (James D. Forrester)
* [BREAKING CHANGE] indicators: Remove 'next' and 'previous', deprecated in v0.25.0 (James D. Forrester)

### Features
* FieldLayout: Use better icons for warning/error messages (Bartosz Dziewoński)
* MenuTagMultiselectWidget: Check for empty inputValue in addTagFromInput (Prateek Saxena)
* TagMultiselectWidget: Handle disabled items (Moriel Schottlender)

### Styles
* WikimediaUI theme: Add additional 'interactions' & 'media' pack icons (Volker E.)
* WikimediaUI theme: Align refined WikimediaUI icons in size and position (Volker E.)
* WikimediaUI theme: Apply `translateZ` hack to full canvas icons (Volker E.)
* WikimediaUI theme: Fix regression on accelerator key alignment (Volker E.)
* WikimediaUI theme: Fix toolbar buttonGroup (Ed Sanders)
* WikimediaUI theme: Harmonize `padding` on FieldLayout messages (Volker E.)
* WikimediaUI theme: Unify and refine WikimediaUI icons (Volker E.)
* WikimediaUI theme: Use `14px` base font size & amend positioning/sizing (Volker E.)
* Apex theme: Fix toolbar buttonGroup (Ed Sanders)
* Apex theme: Make Apex also use 20px canvas icons (Bartosz Dziewoński)

### Code
* Use theme rules to define which tools should get blue icons, not flags (Ed Sanders)
* build: Make the copy task for the WikimediaUI less vars less confusing (James D. Forrester)
* build: Stop using 'grunt-image' for optimising PNGs, at least for now (James D. Forrester)
* build: Switch SVG optimization to 'grunt-svgmin' (Volker E.)
* build: Temporarily disable running unit tests in Firefox due to timeouts (James D. Forrester)
* build: Update devDependencies to latest (James D. Forrester)
* build: Updating jakub-onderka/php-parallel-lint to 1.0.0 (libraryupgrader)
* build: Acknowledge in package.json that grunt-exec 3.0.0 exists, but we don't want it (Bartosz Dziewoński)
* demos: Include editor switch menu in toolbars menu (Volker E.)
* demos: Increase base `font-size` to `14px` (Volker E.)
* demos: Re-enable bigger base size on mobile breakpoint (Volker E.)
* demos: Use `0.8em` body font size for Apex (Bartosz Dziewoński)
* dist: Distribute History.md so people can see what's changed (James D. Forrester)


## v0.25.3 / 2018-03-06
### Features
* DropdownInputWidget: Extract menu item creation (Gabriel Birke)
* MenuTagMultiselectWidget: Highlight first item when filtering (Moriel Schottlender)
* demos: Use individual oojs-ui-* JS files for sourcemap support (Moriel Schottlender)

### Styles
* WikimediaUI theme: Align action toolbar primary button focus state (Volker E.)
* WikimediaUI theme: Align toolbar items' focus to widgets elsewhere (Volker E.)

### Code
* Imply `inline-block` on toolbar item labels (Volker E.)
* CheckboxMultiselectInputWidget: Fix handling of 'name' config option in JS (Bartosz Dziewoński)
* TagMultiselectWidget: Only apply `onMouseDown` if not in input (Moriel Schottlender)
* Gruntfile: Remove reference to long-absent at-ease PHP library (James D. Forrester)
* build: Add jakub-onderka/php-console-highlighter (Umherirrender)
* build: Adding MinusX (Kunal Mehta)
* build: Updating mediawiki/mediawiki-codesniffer to 16.0.1 (libraryupgrader)
* build: Updating phpunit/phpunit to 4.8.36 || ^6.5 (libraryupgrader)
* build: pass --ansi --no-progress to composer (Antoine Musso)
* demos: Add monospace hack for `code` element (Volker E.)
* demos: Only claim ARIA `main` role on the first toolbar demo (Volker E.)
* demos: Replace “Save” by “Publish changes” (Volker E.)


## v0.25.2 / 2018-02-06
### Deprecations
* [DEPRECATING CHANGE] icons: Flag indicator 'alert' as to be removed (Volker E.)

### Features
* Element: Fix infusion edge case (Bartosz Dziewoński)
* InputWidget and subclasses: Remember original value when creating the widget (Bartosz Dziewoński)
* MultilineTextInputWidget: Emit 'enter' for Ctrl+Enter (Ed Sanders)
* MenuTagMultiselectWidget: Erase the input when a menu option is chosen (Prateek Saxena)
* OptionWidget: Option is still selectable/highlightable/pressable if its parent is disabled (Bartosz Dziewoński)
* RadioSelectInputWidget: Prevent exceptions when trying to set unavailable options (Bartosz Dziewoński)

### Styles
* FieldLayout: Fix help icon negative margin in Apex (Ed Sanders)
* LabelElement: Switch `box-sizing` to `border-box` (srishakatux)
* ListToolGroup: Correctly point the collapse/expand icon on bottom toolbars (Volker E.)
* RadioSelectInputWidget, CheckboxMultiselectInputWidget: Fix spacing between options in PHP (Apex theme) (Bartosz Dziewoński)

### Code
* Avoid having to call `.setValue()` in some widgets' constructors multiple times (Bartosz Dziewoński)
* CheckboxMultiselectInputWidget: Turn inline event handler into a method (Bartosz Dziewoński)
* DraggableElement: Replace 'OOjs-UI' with 'OOUI' for code hygiene (Volker E.)
* TextInputWidget: Move previously forgotten methods to Multiline (Bartosz Dziewoński)
* Follow-up b28e99712: Remove `mediawiki/at-ease` dependancy (Sam Reed)
* Reduce code duplication between `#setValue` and `#setOptions` (Bartosz Dziewoński)
* Remove duplicate documentation between TextInputWidget and Multiline (Bartosz Dziewoński)
* TextInputWidget: Document that 'maxLength' counts UTF-16 code units (Bartosz Dziewoński)
* Toolbars: Replace `$.width` with `clientWidth`/`offsetWidth` (Ed Sanders)
* Use child selectors for menuLayout (Ed Sanders)
* build: Don't lint a generated JSON file for validity before it's rebuilt (James D. Forrester)
* build: Update Rubocop config for deprecations (Bartosz Dziewoński)
* demos, docs: Replace 'alert' indicator, as it's deprecated (Volker E.)
* demos: Bring “Word processor toolbar” demos closer to VE (Volker E.)
* demos: Provide more space at bottom of page (Volker E.)
* tests: Do not use obviously fake data when testing infusion (Bartosz Dziewoński)
* testsuitegenerator: Test some 'value' parameters that match 'options' parameters (Bartosz Dziewoński)


## v0.25.1 / 2018-01-16
### Code
* Allow other stuff to handle the event when we call `simulateLabelClick()` (Bartosz Dziewoński)
* Follow-Up I0f1d9c1f: Update usages of `getSelectedItem` -> `findSelectedItem` (Ed Sanders)
* PanelLayout: Remove buggy `translateZ` performance hack (Volker E.)
* PopupToolGroup: Revert "Fix popup direction changing…" (Bartosz Dziewoński)
* Rename prefixes of unique IDs to not mention "OOjs" (Bartosz Dziewoński)
* build, demos, docs: Use “OOUI” as unified name (Volker E.)
* demos: Use MultilineTextInputWidget in PHP demos (Ed Sanders)
* docs: Clarify `required` true handling with `indicator: 'required'` (Volker E.)
* docs: Use “OOUI” as unified name in code comments (Volker E.)


## v0.25.0 / 2018-01-09
### Breaking changes
* [BREAKING CHANGE] Drop the `constructive` flag entirely (James D. Forrester)
* [BREAKING CHANGE] Remove `BookletLayout#getClosestPage` (James D. Forrester)
* [BREAKING CHANGE] SelectWidget: Remove `getFirstSelectableItem` (Prateek Saxena)
* [BREAKING CHANGE] SelectWidget: Remove `getHighlightedItem` (Prateek Saxena)
* [BREAKING CHANGE] SelectWidget: Remove `getRelativeSelectableItem` (Prateek Saxena)
* [BREAKING CHANGE] icons: Drop 'watchlist', deprecated in v0.23.1 (James D. Forrester)

### Deprecations
* [DEPRECATING CHANGE] GroupElement: Rename getItem(s)FromData to findItem(s)FromData (Prateek Saxena)
* [DEPRECATING CHANGE] MultiSelectWidget: Rename getters (Prateek Saxena)
* [DEPRECATING CHANGE] SelectWidget: Rename `getSelectedItem` to `findSelectedItem` (Prateek Saxena)
* [DEPRECATING CHANGE] icons: Flag indicators 'previous' & 'next' as to be removed (Volker E.)
* [DEPRECATING CHANGE] icons: Rename 'arrowLast' to 'arrowPrevious' (James D. Forrester)

### Features
* MenuTagMultiselectWidget: Erase the input when tag is selected if filtering (Moriel Schottlender)

### Styles
* Add `margin-bottom` for widgets which are part of OOUI HorizontalLayout (Phantom42)
* FieldLayout: Improve alignment of multiline labels with 'help' button (Bartosz Dziewoński)
* WikimediaUI theme: Align 'transparency' icon to WikimedaUI color palette (Volker E.)
* WikimediaUI theme: Remove obsolete global flag for 'layout' icon pack (Volker E.)
* WikimediaUI theme: Remove obsolete icon flags (Volker E.)
* Apex theme: Align readonly TextInputWidget across themes (Volker E.)
* Apex theme: Apply `opacity` button transition and ensure Chrome support (Volker E.)
* Apex theme: Remove unused, obsolete 'logo-wikimediaDiscovery' icon (Volker E.)
* icons: Remove obsolete 'bookmark' icon remainders (Volker E.)
* icons: Remove obsolete 'watchlist' icon remainders (Volker E.)
* icons: Shorten 'accessibility' pack invert hex color (Volker E.)

### Code
* Clarify `.oo-ui-force-gpu-composite-layer()` mixin comment (Volker E.)
* Fix blurry text on PanelLayout promoted to GPU in Safari (Volker E.)
* Fix popup direction changing when the "anchor" is partially offscreen (Bartosz Dziewoński)
* MenuTagMultiselectWidget: Don't use overlay for `$autoCloseIgnore` (Moriel Schottlender)
* MultilineTextInputWidget: Correct documentation for `config.maxRows` (Roan Kattouw)
* PHP TextInputWidget: Remove remaining type 'search' specific code (Volker E.)
* Use findItem(s)FromData instead of getItem(s)FromData (Prateek Saxena)
* demos: Override OO.ui.getViewportSpacing in infused PHP demo too (Bartosz Dziewoński)
* demos: Promote icons page IndicatorWidget to GPU layer (Volker E.)
* docs: Bump copyright year (James D. Forrester)
* docs: TagMultiselectWidget: Remove wrong link to MediaWiki documentation (Prateek Saxena)
* build: Update .gitattributes for .phpcs.xml file move (Kunal Mehta)
* build: Add rake to Gemfile (Antoine Musso)
* build: Don't include Gemfile* in composer zipballs (Kunal Mehta)
* build: Update RuboCop Ruby gem (Željko Filipin)
* build: Updating mediawiki/mediawiki-codesniffer to 15.0.0 (libraryupgrader)
* build: Use SVGO option of 'grunt-image' for distribution (Volker E.)


## v0.24.4 / 2017-12-20 special release
### Deprecations
* [DEPRECATING CHANGE] icons: Flag unused 'bellOn' icon as to be removed (Volker E.)
* [DEPRECATING CHANGE] icons: Flag unused 'quotesAdd' & 'redirect' as to be removed (Volker E.)

### Features
* Introduce `OO.ui.getDefaultOverlay` (Bartosz Dziewoński)
* Put menus/popups of infused PHP widgets into the default overlay (Bartosz Dziewoński)

### Styles
* icons: Add 'lightbulb' icon (Prateek Saxena)
* icons: Add 'stop' icon to Apex theme (Volker E.)

### Code
* ClippableElement: Fix JS error when Floatable is mixed in but disabled (Roan Kattouw)
* DropdownWidget: Remove stray use of `this.$()` (Bartosz Dziewoński)


## v0.24.3 / 2017-11-28
### Features
* Allow adding virtual viewport spacing (Bartosz Dziewoński)
* ClippableElement: Allow clipping with top or left edge (Bartosz Dziewoński)
* DropdownInputWidget: Generate a hidden `<select>` in JS (Bartosz Dziewoński)
* FieldsetLayout: Hide header when there is no icon or label (Bartosz Dziewoński)
* MenuSelectWidget, PopupWidget: Automatically change popup direction if there is no space (Bartosz Dziewoński)
* PopupToolGroup: Set clipping edges to fix clipping edge (heh) cases (Bartosz Dziewoński)
* TextInputWidget: support spellcheck attribute (David Lynch)

### Styles
* themes: Fix PHP ComboboxInputWidget indicator position (Volker E.)
* WikimediaUI theme: Restore `:hover:focus` border color on TextInputWidgets (Volker E.)
* oo-ui-background-image: Drop `-o-linear-gradient` fallback (James D. Forrester)
* oo-ui-background-image: Drop `-webkit-linear-gradient` fallback (James D. Forrester)

### Code
* PHP DropdownInputWidget: Workaround for Firefox 57 ignoring attr selector with whitespace (Volker E.)
* DraggableGroupElement: Don't try to access non-existent property (Bartosz Dziewoński)
* DropdownInputWidget: Remove duplicate TitledElement mixin (Bartosz Dziewoński)
* README: Add "Community" section (Prateek Saxena)
* README: Re-arrange intro section (Prateek Saxena)
* build: Bump wikimedia-ui-base (Volker E.)
* git.wikimedia.org -> phab (Zach)


## v0.24.2 / 2017-11-07
### Deprecations
* [DEPRECATING CHANGE] Use en-US spelling for icon names for consistency (Ed Sanders)

### Code
* README: Consistently refer to OOUI as library (Volker E.)
* README: Fix Doxygen rendering (Volker E.)
* README: Simplify “Quick start” and “Contributing” section (Volker E.)
* demos: Correct and simplify SimpleWidget styles (Bartosz Dziewoński)
* docs: onMenuToggle: `isVisible` is the state of the menu (Prateek Saxena)


## v0.24.1 / 2017-10-31
### Features
* DropdownWidget: Allow pressing Space to close the widget, as well as open (Bartosz Dziewoński)

### Styles
* WikimediaUI theme: Visually improve MenuSectionOptionWidget MenuOptions (Volker E.)

### Code
* ComboBoxInputWidget: Add `.oo-ui-comboBoxInputWidget-open` class to widget (Volker E.)
* Generate clover.xml with code coverage results (Kunal Mehta)
* WikimediaUI theme: Use child selectors for styling toolbar action buttons (Bartosz Dziewoński)
* README: Simplify and move “Versioning” section (Volker E.)
* README: Simplify “Contributing” section slightly and add LESS lint hint (Volker E.)
* build: Bump stylelint devDependencies (James D. Forrester)
* build: Bump various devDependencies to latest (James D. Forrester)
* build: Downgrade 'grunt-exec' to 1.0.1 (again) (Bartosz Dziewoński)
* build: Update grunt-image to version 4.0.0 (Ed Sanders)
* build: Update mediawiki/mediawiki-codesniffer to 14.1.0 (libraryupgrader)
* icons: Unify SVG markup (Volker E.)


## v0.24.0 / 2017-10-17
### Breaking changes
* [BREAKING CHANGE] Drop 'MediaWiki' backwards-compatibility theme (James D. Forrester)
* [BREAKING CHANGE] icons: Drop 'stripeSideMenu', renamed in v0.22.2 (James D. Forrester)
* [BREAKING CHANGE] icons: Remove 'eye'/'eyeClosed' icons, deprecated in v0.23.0 (Volker E.)
* [BREAKING CHANGE] icons: Remove 'signature' icon, deprecated in v0.23.0 (Volker E.)
* [BREAKING CHANGE] icons: Remove 'sun', deprecated in v0.23.0 (James D. Forrester)

### Styles
* themes: Unify icon/indicator visibility (Volker E.)
* WikimediaUI theme: Ensure hover feedback on TextInputWidget & descendants (Volker E.)

### Code
* Fix `.oo-ui-selectable()` mixin to actually undo `.oo-ui-unselectable()` (Bartosz Dziewoński)
* WikimediaUI theme: Fix selector in PopupWidget styles (Bartosz Dziewoński)


## v0.23.5 / 2017-10-12
### Code
* PHP MultilineTextInputWidget, SearchInputWidget: Remove duplicate `use` statements (Bartosz Dziewoński)
* PHP Theme: Fix check for IconElement/IndicatorElement for inherited traits (Bartosz Dziewoński)


## v0.23.4 / 2017-10-11
### Styles
* IndexLayout: Handle long lists of tabs (Bartosz Dziewoński)
* icons: Provide a 'reload' icon in the 'interactions' pack (Ed Sanders)
* Apex theme: Fix PopupToolGroup active box size (Volker E.)
* Apex theme: Fix SelectFileWidget (no browser support)‎ `padding` (Volker E.)
* Generalize icon and indicator positioning & visibility (Volker E.)
* WikimediaUI theme: Reduce Checkbox*- & RadioSelectInputWidget vertical space (Volker E.)
* WikimediaUI theme: Reduce FieldLayout `margin-top` slightly (Volker E.)
* WikimediaUI theme: Streamlining icon/indicator visibility (Volker E.)

### Code
* Only store initialConfig in demo mode (Ed Sanders)
* SearchInputWidget: Prevent extra `oo-ui-textInputWidget-type-text` class (Bartosz Dziewoński)
* TextInputWidget: Use child selector for icons/indicators/labels (Ed Sanders)
* Do not call `.offset()` on `$( 'html' )` (Bartosz Dziewoński)
* PHP: Implement MultilineTextInputWidget, deprecate multiline option (Prateek Saxena)
* PHP: Implement SearchInputWidget, deprecate search option (Bartosz Dziewoński)
* build: Downgrade 'grunt-exec' to 1.0.1 (Bartosz Dziewoński)
* demos: Adding missing `:hover` (Volker E.)


## v0.23.3 / 2017-10-03
### Styles
* PopupToolGroup: Move accelerator keys `padding` to themes (Volker E.)
* WikimediaUI theme: Align PopupToolGroup header styles (Volker E.)
* WikimediaUI theme: Fix border on narrow bottom toolbars (Volker E.)
* WikimediaUI theme: Fix flagged elements' icon `opacity` (Volker E.)
* WikimediaUI theme: Improve PopupToolGroup's indicator vertical alignment (Volker E.)
* WikimediaUI theme: Make toolbar active element highlights visually equal (Volker E.)
* WikimediaUI theme: Remove `box-shadow` not in design (Volker E.)
* WikimediaUI theme: Replace BookletLayout menu `border-color` (Volker E.)
* WikimediaUI theme: Unify positioning and sizing of tools, toolgroups and menus (Volker E.)
* WindowManager: Remove `overflow: hidden` to enhance styling flexibility (Volker E.)

### Code
* Follow-up I576f3175: highlightQuery: Handle case when query is not found (Ed Sanders)
* IndexLayout, BookletLayout: Don't scroll panels if not scrollable (Bartosz Dziewoński)
* LabelElement: Add tests for setHighlightedQuery (Ed Sanders)
* SelectWidget: Allow focussing things inside OptionWidget labels (Bartosz Dziewoński)
* WikimediaUI theme: Simplify action toolbar buttons selectors (Volker E.)
* demos: Remove unnecessary button demo widgets (Volker E.)


## v0.23.2 / 2017-09-26
### Deprecations
* [DEPRECATING CHANGE]: Apex theme: Begin killing `constructive` flag (James D. Forrester)

### Features
* LabelElement#highlightQuery: Support locale comparison (Ed Sanders)
* MenuLayout, BookletLayout, IndexLayout: Support `expanded: false` (Bartosz Dziewoński)
* WindowManager: Set `aria-hidden` by default and change toggleAriaIsolation behavior (Prateek Saxena)

### Code
* MenuLayout: Rewrite support for `expanded: false` (Bartosz Dziewoński)
* TextInputWidget: Reduce CSS output by enhancing unselectable behaviour (Volker E.)
* themes: Align DropdownWidget `&-handle` selectors for code hygiene (Volker E.)
* Apex theme: Simplify Radio- & Checkbox*optionWidget label rules (Volker E.)
* Remove duplicated `outline` property (Volker E.)
* Remove LESS vars covered by WikimediaUI Base (Volker E.)
* demos: Expand long dialog title to actually test things (James D. Forrester)
* demos: Restrict `opacity` to non-flagged icons only (Volker E.)


## v0.23.1 / 2017-09-19
### Deprecations
* [DEPRECATING CHANGE] SelectWidget: Rename `getFirstSelectableItem` to `findFirstSelectableItem` (Prateek Saxena)
* [DEPRECATING CHANGE] SelectWidget: Rename `getHighlightedItem` to `findHighlightedItem` (Prateek Saxena)
* [DEPRECATING CHANGE] SelectWidget: Rename `getRelativeSelectableItem` to `findRelativeSelectableItem` (Prateek Saxena)
* [DEPRECATING CHANGE] icons: Flag unused 'watchlist' icon as to be removed (Volker E.)

### Styles
* RadioOptionWidget, CheckboxMultioptionWidget: Support very long labels (Bartosz Dziewoński)
* WikimediaUI theme: Harmonize toolbar icon/indicator opacity (Volker E.)
* WikimediaUI theme: Improve ListToolGroup's color and opacity handling (Volker E.)
* WikimediaUI theme: Simplify disabled tool opacity rules (Volker E.)

### Code
* BookletLayout#getClosestPage: Fix version number of deprecation (Prateek Saxena)
* HtmlSnippet: Throw exception if given non-string content (Bartosz Dziewoński)
* Use `findFirstSelectableItem` instead of `getFirstSelectableItem` (Prateek Saxena)
* Use `findHighlightedItem` instead of `getHighlightedItem` (Prateek Saxena)
* Use `findRelativeSelectableItem` instead of `getRelativeSelectableItem` (Prateek Saxena)
* WikimediaUI theme: Concatenate constructive & progressive selectors (Volker E.)
* WikimediaUI theme: Remove unnecessary properties (Volker E.)
* demos: Add examples of FieldLayout with very long labels (Bartosz Dziewoński)
* demos: Avoid menu's `box-shadow` from lurkin into toolbar (Volker E.)


## v0.23.0 / 2017-09-05
### Breaking changes
* [BREAKING CHANGE] Remove CardLayout and references in IndexLayout (Volker E.)
* [BREAKING CHANGE] Remove FloatingMenuSelectWidget (Volker E.)
* [BREAKING CHANGE] Remove back-compat `OO.ui` prefix assumption in infusion code (Prateek Saxena)
* [BREAKING CHANGE] icons: Remove 'caret' icons, deprecated in v0.21.3 (James D. Forrester)
* [BREAKING CHANGE] icons: Remove 'wikitrail' icon, renamed in v0.20.1 (James D. Forrester)

### Deprecations
* [DEPRECATING CHANGE] BookletLayout: Rename `getClosestPage()` to `findClosestPage()` (Prateek Saxena)
* [DEPRECATING CHANGE] icons: Flag unused 'sun' icon as to be removed (James D. Forrester)
* [DEPRECATING CHANGE] icons: Move 'eye'/'eyeClosed' to 'accessibility' (Volker E.)
* [DEPRECATING CHANGE] icons: Move 'signature' to 'editing-advanced' (Volker E.)

### Features
* Element: Improve error message when the widget being infused is missing (Bartosz Dziewoński)

### Styles
* Apex theme: Only apply `margin` to label if visible (Ed Sanders)
* WikimediaUI theme: Fix frameless indicator combination buttons' appearance (Volker E.)
* ButtonInputWidget: Fix Safari-specific intrinsic `margin` (Volker E.)

### Code
* Ensure only options belonging to the SelectWidget can be clicked (Ed Sanders)
* SelectFileWidget: Rename `getTargetItem()` to `findTargetItem()` (Prateek Saxena)
* Toolgroup: Rename `getTargetTool()` to `findTargetTool()` (Prateek Saxena)
* WikimediaUI theme: Simplify `transition` code and remove obsolete (Volker E.)
* build: Add 'accessibility' icon pack in Apex to build module definition (Volker E.)
* build: Update eslint-config-wikimedia 0.4->0.5 (Ed Sanders)
* build: Updating mediawiki/mediawiki-codesniffer to 0.12.0 (libraryupgrader)
* tests: Make MockWidget filename match class name (Kunal Mehta)


## v0.22.5 / 2017-08-22
### Features
* Add `title` attribute to the 'remove' button in TagItemWidget (Moriel Schottlender)

### Styles
* WikimediaUI theme: Fix regression on disabled border (Volker E.)

### Code
* Align vars to WikimediaUI Base and remove them as OOjs UI vars (Volker E.)
* DraggableElement: Make toggling draggability consistent (Bartosz Dziewoński)
* Follow-up 022f532: Don't crash if TitledElement initializes before AccessKeyedElement (Roan Kattouw)
* WikimediaUI theme: Make checkbox/radio code leaner (Volker E.)
* WikimediaUI theme: Remove unnecessary selector in CheckboxInputWidget (Volker E.)
* docs: Align code comment references to Phabricator tasks (Volker E.)
* build: Upgrade devDependencies to latest and make pass (James D. Forrester)
* build: Update mediawiki-codesniffer to v0.10.1 and fix issues (WMDE-Fisch)
* build: Update mediawiki-codesniffer to v0.11.0 and fix issues (WMDE-Fisch)
* tests: Prepare for qunit 2.x (James D. Forrester)


## v0.22.4 / 2017-08-01
### Features
* CheckboxMultiselectInputWidget: setValue when CheckboxMultiselect changes (Prateek Saxena)
* FieldLayout: Show widget's access key in our title (Bartosz Dziewoński)
* TextInputWidget: When positioning label, don't clear padding if we will set it again (Bartosz Dziewoński)
* TitledElement: When an AccessKeyedElement, show access key in the title (Bartosz Dziewoński)

### Styles
* icons: Vertically align 'play' & 'stop' icons (Volker E.)
* Apex theme: Add focus styles to Tag-/CapsuleMultiselectWidget (Volker E.)
* Apex theme: Add focus styles to frameless buttons (Volker E.)
* Apex theme: Add play icon (copied from WikimediaUI theme) (Roan Kattouw)
* Apex theme: Align ButtonGroup-/ButtonSelectWidget focus logic to WikimediaUI (Volker E.)
* Apex theme: Align Dropdown*Widget's focus state with other widgets (Volker E.)
* Apex theme: Align TextInputWidget focus to variablized way (Volker E.)
* Apex theme: Align ToggleSwitchWidget focus style to other widgets (Volker E.)
* Apex theme: Improve alignment of TextInputWidget and its elements (Volker E.)
* Apex theme: Introduce framed button focus indication (Volker E.)
* Apex theme: Replace and unify `border-radius` with variables (Volker E.)
* WikimediaUI theme: Set ButtonElement's height per default (Volker E.)
* WikimediaUI theme: Work around a Firefox rendering bug for checkboxes and radios (Bartosz Dziewoński)

### Code
* DraggableGroupElement: Remove ARIA roles & attributes (Volker E.)
* FieldsetLayout: Use `<legend>` now that Chrome 55 bug is less important (James D. Forrester)
* Apex theme: Align remaining values to coding convention (Volker E.)
* WikimediaUI theme: Align `*-fallback` var with notation elsewhere (Volker E.)
* WikimediaUI theme: Code comment hygiene (Volker E.)
* WikimediaUI theme: Directly use the Less values rather than via copy-paste (James D. Forrester)
* demos: Add examples of TextInputWidget with dynamic label (Bartosz Dziewoński)
* demos: Demo.DraggableItemWidget should not inherit from OO.ui.OptionWidget (Bartosz Dziewoński)
* demos: Show example link on `:focus` (Volker E.)
* docs: Fix some PHPDoc `@return` tags (Ricordisamoa)
* build: Add a script to print the dependency tree of everything (Bartosz Dziewoński)


## v0.22.3 / 2017-07-11
### Features
* Tag-/CapsuleMultiselectWidget: Avoid visual focusTrap feedback (Volker E.)
* WindowManager: Avoid inconsistent state due to asynchronous promise resolution (Bartosz Dziewoński)
* WindowManager: fix closing promise state check (David Lynch)

### Styles
* icons: Align ongoingConversation to grid (Ed Sanders)
* icons: Replace the puzzle icon, using the one from VisualEditor (James D. Forrester)
* icons: Vertically center mapPin icon (Volker E.)
* Apex theme: Add 'article' icon, copied from WikimediaUI (Moriel Schottlender)

### Code
* DropdownWidget, MenuSelectWidget: Set `aria-expanded` attribute (Prateek Saxena)
* FieldLayout: Add `role='alert'` for error messages (Prateek Saxena)
* FieldLayout: Set `aria-describedby` on the fieldWidget (Prateek Saxena)
* PopupWidget: Update function name in a comment (Bartosz Dziewoński)
* TagMultiselectWidget: Skip `updateInputSize()` for invisible inputs (Roan Kattouw)
* Toolbar: Add comment for greppability of dynamic CSS classes (Bartosz Dziewoński)
* themes: Align read-only variable names to pseudo-class selector scheme (Volker E.)
* themes: Align variable names to WikimediaUI Base scheme (Volker E.)
* WikimediaUI theme: Align `@opacity-icon*` variable names to WikimediaUI Base (Volker E.)
* WikimediaUI theme: Align checked variable names to pseudo-class scheme (Volker E.)
* WikimediaUI theme: Align disabled variable names to pseudo-class scheme (Volker E.)
* WikimediaUI theme: Align variable pseudo classes names to WikimediaUI Base (Volker E.)
* WikimediaUI theme: Replace `@color-base-light` with `@color-base--inverted` (Volker E.)
* WikimediaUI theme: Variablize PopupWidget values (Volker E.)
* WikimediaUI theme: Pull in the upstream WikimediaUI package (James D. Forrester)
* build: Updating mediawiki/mediawiki-codesniffer to 0.10.0 (Kunal Mehta)
* phpcs: Enable more rules, or document why they are disabled (Bartosz Dziewoński)
* testsuitegenerator: Blacklist deprecated `multiline` config option (Bartosz Dziewoński)


## v0.22.2 / 2017-06-28
### Deprecations
* [DEPRECATING CHANGE] TextInputWidget: Move multi-line support out (Prateek Saxena)
* [DEPRECATING CHANGE] icons: Move and rename 'stripeSideMenu' to 'draggable' (Volker E.)

### Features
* DropdownInputWidget: Unbreak setting 'value' via config options (Bartosz Dziewoński)
* Element: Work around browsers that set fractional scrollTop values (Roan Kattouw)

### Styles
* BookletLayout: Workaround for horizontal scrollbars on menu when editable (Bartosz Dziewoński)
* icons: Let's stop referring to removed icons, hmm? (James D. Forrester)
* Rewrite all styling for "outline controls" (Bartosz Dziewoński)
* Apex theme: Align appearance of tags' close icon to WikimediaUI theme (Volker E.)
* Apex theme: Fix HorizontalLayout containing FieldLayouts (Bartosz Dziewoński)
* WikimediaUI theme: Remove default DraggableElement styling (Ed Sanders)
* WikimediaUI theme: Use icon instead of indicator in Tag-/CapsuleItemWidget (Volker E.)
* WikimediaUI: Strengthen Radio*Widget's `:checked` state (Volker E.)

### Code
* MenuSelectWidget: Fix item hiding when menu contents change (Roan Kattouw)
* MultilineTextInputWidget: Fix autosizing (Bartosz Dziewoński)
* PopupWidget: Replace CSS with Less comments for smaller dist (Volker E.)
* SearchInputWidget: Fix ability to clear the input (Bartosz Dziewoński)
* TabIndexedElement: Fix validation and make consistent in PHP and JS (Bartosz Dziewoński)
* Use javascript-stringify instead of JSON.stringify (Ed Sanders)
* Apex theme: Fix order of selectors for :first-child FieldLayout (Bartosz Dziewoński)
* demos: Add links to documentation from code examples (Prateek Saxena)
* demos: Allow linking to specific widgets (Bartosz Dziewoński)
* demos: Indicate code toggle clearer (Volker E.)
* demos: Pull out all links to docs/sources to the top of the code (Bartosz Dziewoński)
* demos: Simplify code generation, now that we use javascript-stringify (Bartosz Dziewoński)
* demos: Use URL 'query' part for linking to demo sections rather than URL 'fragment' (Bartosz Dziewoński)
* docs: Fix some typos in documentation (Bartosz Dziewoński)
* docparser: Fix handling for fake trait constructors (Bartosz Dziewoński)
* docparser: Make matching '(default: ...)' case-insensitive (Bartosz Dziewoński)
* docparser: Tighter check for 'use' statements in PHP (Bartosz Dziewoński)


## v0.22.1 / 2017-05-31
### Code
* WindowManager: Do not use return value of `#closeWindow` as promise (Bartosz Dziewoński)
* WindowManager: Fix check for a window already closing (Bartosz Dziewoński)
* WindowManager: Fix error handling for `#openWindow` with string argument (Bartosz Dziewoński)
* WindowManager: Fix important typo in deprecation warning (Bartosz Dziewoński)
* WindowManager: Fix incorrect checks for promise state (Bartosz Dziewoński)
* WindowManager: Provide other `jQuery.Promise` methods on the b/c promise too (Bartosz Dziewoński)
* demos: Clarify code comment (Bartosz Dziewoński)
* demos: Clean up the global window manager too when destroying (Bartosz Dziewoński)
* demos: Load icon packs in the PHP demo (Bartosz Dziewoński)
* demos: Replace abandoned icon name 'remove' to current one 'trash' (Volker E.)

## v0.22.0 / 2017-05-30
### Breaking changes
* [BREAKING CHANGE] TextInputWidget: Remove search related methods (Prateek Saxena)
* [BREAKING CHANGE] icons: Drop the core icon pack (James D. Forrester)
* [BREAKING CHANGE] icons: Remove unused 'bookmark' icon (Volker E.)
* [BREAKING CHANGE] Depend on OOjs v2.1.0, up from v2.0.0 (James D. Forrester)

### Deprecations
* [DEPRECATING CHANGE] Rename the 'MediaWiki' theme to 'WikimediaUI' (James D. Forrester)
* [DEPRECATING CHANGE] WindowManager: Deprecate using `openWindow`/`closeWindow` returns as promises (Bartosz Dziewoński)

### Features
* Add HiddenInputWidget to generate hidden input (Victor Barbu)
* InputWidget: Introduce `#setInputId` and `inputId` config option (Bartosz Dziewoński)
* MenuTagMultiselectWidget: Clear text field after adding an item from it (Bartosz Dziewoński)
* MenuTagMultiselectWidget: Handle the 'selected' config option (Bartosz Dziewoński)
* NumberInputWidget: Use icons instead of labels (Volker E.)
* PopupButtonWidget: Handle empty configuration (Bartosz Dziewoński)
* PopupWidget: Position close button in head absolutely (David Lynch)
* PopupWidget: Sensibly position anchor-less popups (Roan Kattouw)
* WindowManager: Add `WindowInstance` - a Promise-based lifecycle object (Timo Tijhof)
* WindowManager: Handle errors better in `#closeWindow` (Bartosz Dziewoński)

* Allow *even more* widgets to be focussed programatically (Bartosz Dziewoński)
* Only cancel mouse down event if tool in toolgroup clicked on (Ed Sanders)
* Re-introduce `.simulateLabelClick()` as a separate method from .focus() (Bartosz Dziewoński)

### Styles
* themes: Field*Layout help position perfectly aligned (Volker E.)
* themes: Improve frameless button in size and behaviour (Volker E.)
* themes: Increase FieldsetLayout header's `font-size` (Volker E.)
* Apex theme: Ensure vertical centering of ButtonElement's icon (Volker E.)
* Apex theme: Make OptionWidget icon override more specific (Moriel Schottlender)
* Apex theme: Start Apex's 'user' icon pack, with just 'userAvatar' for now (Ed Sanders)
* WikimediaUI theme: Align `@background-color-destructive` to WikimediaUI Base (Volker E.)
* WikimediaUI theme: Align ButtonInputWidget's `line-height` to ButtonWidget (Volker E.)
* WikimediaUI theme: Align inline label's position (Volker E.)
* WikimediaUI theme: Ensure icon aligns in dropdown menu (Volker E.)
* WikimediaUI theme: Remove incorrect comments (Volker E.)

### Code
* MenuTagMultiselectWidget: Add test for 'selected' config option (Bartosz Dziewoński)
* windows: Add tests for OO.ui.alert/confirm/prompt (Timo Tijhof)
* AUTHORS: Update for the past two years' work (James D. Forrester)
* build: Add the README/AUTHORS/LICENCE files to dist (James D. Forrester)
* demos: Add TextInputWidget examples with inline labels but no indicators (Ed Sanders)
* demos: Add viewport meta tag to PHP demo too (Volker E.)
* demos: Avoid inline CSS for the overlay (Bartosz Dziewoński)
* demos: Fix code generation for more complicated cases (Bartosz Dziewoński)
* demos: Fix up a couple of minor things in demo widgets (Bartosz Dziewoński)
* demos: Fix `z-index` with fixed demo header (Volker E.)
* demos: Increase and strengthen responsive support (Volker E.)
* demos: Indicate widgets clearer by sections (Volker E.)
* demos: Make disabled progress bar in demo determinate (Ed Sanders)
* demos: Show code that can be used to create the widget (Prateek Saxena)
* testsuitegenerator: Handle classes with no constructor (Bartosz Dziewoński)


## v0.21.4 / 2017-05-16
### Features
* Allow more widgets to be focussed programatically (Bartosz Dziewoński)
* Generalize `.getInputId()` for all widgets (Bartosz Dziewoński)
* Use `.focus()` method when possible instead of looking inside widgets (Bartosz Dziewoński)
* TagMultiselectWidget: Fix Control+Backspace keys to delete last item (Bartosz Dziewoński)
* TagMultiselectWidget: Fix order of checks for `allowArbitrary`/`allowDuplicates` (Bartosz Dziewoński)

### Styles
* MediaWiki theme: Separate two active ToggleButton siblings visually (Volker E)

### Code
* LabelWidget: Fix label click handling (Bartosz Dziewoński)
* RadioSelectInputWidget: When generating a unique 'name', don't make it random (Bartosz Dziewoński)
* Use glaringly wrong tags for elements that are supposed to be unused (Bartosz Dziewoński)
* README: Clarify and simplify descriptions (Volker E)
* build: Upgrade eslint-config-wikimedia from 0.3.0 to 0.4.0 and make pass (James D. Forrester)
* demos: Add ARIA roles (Volker E)
* demos: Clean up the window manager when destroying the dialogs demo (Bartosz Dziewoński)
* demos: Preserve scroll position when changing non-page options (Bartosz Dziewoński)
* demos: Rename deprecated Card to current TabPanel (Volker E)
* demos: Tame buggy mobile browser behaviour on `position: fixed` (Volker E)
* demos: Turn the menu into a fixed header (Bartosz Dziewoński)
* docs: Fix `OO.ui.IndexLayout` example (Volker E)
* tests: Order the `attributes` object keys, for less noisy diffs (Bartosz Dziewoński)


## v0.21.3 / 2017-05-09
### Deprecations
* [DEPRECATING CHANGE] Merge functionality of FloatingMenuSelectWidget into MenuSelectWidget (Bartosz Dziewoński)
* [DEPRECATING CHANGE] Rename CardLayout to TabPanelLayout (Prateek Saxena)
* [DEPRECATING CHANGE] icons: Deprecate 'bookmark' icon (Volker E)
* [DEPRECATING CHANGE] icons: Merge 'caret' into regular movement icons (James D. Forrester)

### Styles
* OptionWidget: Use parent selector for icon/indicator/label styles (Roan Kattouw)
* Apex theme: Follow same FieldLayout `margin` logic as MediaWiki theme (Volker E)
* MediaWiki theme: Bring styling to design spec in Safari/iOS (Volker E)
* MediaWiki theme: Fix ButtonInputWidget appearance in Saf/iOS (Volker E)
* MediaWiki theme: Fix `padding` for frameless buttons in ProcessDialogs (Ed Sanders)
* MediaWiki theme: Provide focus indicator to TagMultiselectWidget (Volker E)
* MediaWiki theme: Unify and harmonize `padding`/position of Tag*Widgets (Volker E)

### Code
* Fix some errors flagged by ESLint's 'valid-jsdoc' option (Bartosz Dziewoński)
* NumberInputWidget: Followup db801c55f0 – clean up backward compat vars (Moriel Schottlender)
* MenuSectionOptionWidget: Remove unsupported ARIA attribute (Volker E)
* MenuSelectWidget: Scroll to the top if filtering and no exact match (David Lynch)
* MenuSelectWidgets: Don't unconditionally hide all descendant inputs (Roan Kattouw)
* TagMultiselectWidget: Actually use the focus trapping element (Bartosz Dziewoński)
* TagMultiselectWidget: Fix `#addTag` return value to match docs (Bartosz Dziewoński)
* TagMultiselectWidget: Fix keyboard navigation between items (Bartosz Dziewoński)
* ToggleButtonWidget: Remove misleading `aria-checked` attribute (Volker E)
* Unbreak FloatingMenuSelectWidget when `$container` is not given (Bartosz Dziewoński)
* build: Fix invalid ecmaVersion setting (Timo Tijhof)
* build: Use source maps in coverage report (James D. Forrester)
* icons: Add first/last to complement previous/next in movement pack (Ed Sanders)
* icons: Provide 'clip', 'unClip', and 'pushPin' in moderation (James D. Forrester)
* tests: Do not set `QUnit.config.requireExpects = true` (Bartosz Dziewoński)


## v0.21.2 / 2017-04-25
### Features
* Element: New method `#getElementId` (Bartosz Dziewoński)
* NumberInputWidget: Remake as an actual TextInputWidget child (Moriel Schottlender)

### Styles
* ProgressBarWidget: Switch to `box-sizing: border-box` (Volker E)
* TabOptionWidget: Cleanup & align paddings/position to dialog environment (Volker E)
* MediaWiki theme: Decrease selector specificity and fix invalid appearance (Volker E)
* MediaWiki theme: Fix IE 7 oversized buttons (Volker E)
* MediaWiki theme: Improve SearchWidget design (Volker E)

### Code
* Do not use `role=menu`/`menuitem` for MenuSelectWidget/MenuOptionWidget (Bartosz Dziewoński)
* PopupTagMultiselectWidget: Update popup position on resize (Prateek Saxena)
* ProcessDialog: Display error messages on top of footer action buttons (Bartosz Dziewoński)
* SelectWidget/MenuSelectWidget: Maintain `aria-activedescendant` attribute on focus owner (Bartosz Dziewoński)
* Set ARIA `role=combobox` on DropdownWidget and LookupElement too (Bartosz Dziewoński)
* Set `aria-owns` for everything with a dropdown list (ARIA `role=combobox`) (Bartosz Dziewoński)
* Follow-up d22d23311: Don't reference OO.ui.ToolGroup blindly (James D. Forrester)
* build: Bump grunt-stylelint, bring in stylelint explicitly (James D. Forrester)
* demos: Add some more examples with 'accessKey' (Bartosz Dziewoński)
* docs: Document Window#$overlay property (Bartosz Dziewoński)
* tests: Drop unnecessary hints to qunit about the number of tests (James D. Forrester)


## v0.21.1 / 2017-04-18
### Styles
* PopupWidget: Do not leave space for anchor if there's no anchor (Bartosz Dziewoński)
* MediaWiki theme: Ensure WCAG level AA contrast on unsupported SelectFileWidget (Volker E)
* MediaWiki theme: Fit icon/indicator & label in DecoratedOptionWidget (Volker E)
* MediaWiki theme: Fix standalone, disabled sibling ButtonWidgets (Volker E)
* MediaWiki theme: Fix white `border-color` of frameless buttons (Volker E)
* MediaWiki theme: Make readonly TextInputWidget appearance clearer (Volker E)
* MediaWiki theme: TagMultiselectWidget outlined UI improvements (Volker E)
* MenuOptionWidget: Remove theme-independent 'check' icon (Prateek Saxena)

### Code
* environment: Upgrade jQuery from 1.11.3 to 3.2.1 (James D. Forrester)
* DropdownInput-/RadioSelectInputWidget: Remove unnecessary ARIA attributes (Volker E)
* Element: Use `JSON.parse` rather than the deprecated `$.parseJSON` (James D. Forrester)
* Fix typo in frameless button mixin (David Lynch)
* FloatingMenuSelectWidget: Add 'ready' event after menu is clipped (Moriel Schottlender)
* MediaWiki theme: Clarify `@min-height-widget-default` usage (Volker E)
* PopupToolGroup: Mixin flaggable (David Lynch)
* TagMultiselectWidget: Allow preset InputWidget (Moriel Schottlender)
* TagMultiselectWidget: Redo data validation for Tag* and Menu* (Moriel Schottlender)
* themes: Align `@size-*-min` variable to naming scheme and rename (Volker E)
* build: Drop the csscomb task (James D. Forrester)
* docs: Fix numbering in Quick start (Kartik Mistry)
* demos: Polish demo labels, styles and add frameless button tests (Volker E)
* tests: Update OO.ui.Process tests for jQuery 3 compatibility (Bartosz Dziewoński)


## v0.21.0 / 2017-04-11
### Breaking changes
* [BREAKING CHANGE] ActionWidget: Remove resize event (IvanFon)
* [BREAKING CHANGE] dependencies: Drop support for ES3 browsers via es5-shim (James D. Forrester)

### Features
* Create a TagMultiselectWidget (Moriel Schottlender)
* FloatingMenuSelectWidget: Add `width` config option (Moriel Schottlender)
* MenuSelectWidget: Add `config.$autoCloseIgnore` (Roan Kattouw)

### Styles
* PopupWidget: Center the anchor for vertical (above/below) popups too (Bartosz Dziewoński)
* MediaWiki theme: Add separator when toolbar items break on narrow (Volker E)
* MediaWiki theme: Fix IE < 11 icon/indicator position in SelectFileWidget (Volker E)
* MediaWiki theme: Fix overflow ellipsis on small DropdownWidget sizes (Volker E)
* MediaWiki theme: Fix selector regression on DraggableElement (Volker E)
* MediaWiki theme: Fix Toolbars containing ButtonGroups (David Lynch)
* MediaWiki theme: Replace arrows with chevrons and increase contrast (Volker E)
* MediaWiki theme: Unify `padding` across widgets and variablize (Volker E)
* MediaWiki theme: Unify `padding` on ButtonElement (Volker E)
* MediaWiki theme: Unify `padding` on DecoratedOptionWidget and descendants (Volker E)
* Follow-up eceb6f20: MediaWiki theme: Remove unused indicator flags (Volker E)

### Code
* Remove remnants of PHP-5.3-style `array()` literals (Bartosz Dziewoński)
* ClippableElement: Fix progressive width loss bug (Roan Kattouw)
* ComboBoxInputWidget: Fix minor JS/PHP differences (Bartosz Dziewoński)
* ComboBoxInputWidget: Redo the 'down' indicator in PHP (Bartosz Dziewoński)
* DraggableElement: Only apply focus when widget is not disabled (Moriel Schottlender)
* DraggableElement: Toggle style on `$handle`, not `$element` (Andrew Green)
* DropdownInputWidget: Only allow setting values actually in the dropdown (Bartosz Dziewoński)
* MenuSelectWidget: Highlight the first result when searching (Moriel Schottlender)
* MessageDialog: Accept proposed size dialog on `getSetupProcess` (Ebrahim Byagowi)
* TextInputWidget: Reduce unnecessary duplicated CSS output (Volker E)
* TextInputWidget: Use `.prop()` rather than `.attr()` for 'required' (Bartosz Dziewoński)
* Apex theme: Align coding style to conventions (Volker E)
* Apex theme: Simplify color usage through Less variables (Volker E)
* demos: Remove scaling restrictions (Volker E)
* docparser: Improve trait/mixin handling (Bartosz Dziewoński)
* docparser: Properly handle default values in PHP (Bartosz Dziewoński)
* docs: Add detail to documentation of core.js utilities (Ed Sanders)
* docs: Minor documentation tweaks (Bartosz Dziewoński)
* tests: Comparison tests for infusing previously untestable classes (Bartosz Dziewoński)


## v0.20.2 / 2017-03-30
### Styles
* DraggableElement: Fix regression on selectors (Volker E)

### Code
* GroupElement: Fix insertion bugs (Bartosz Dziewoński)
* icons: Drop unused 'invert' variant from Apex 'icons-interactions' pack (Bartosz Dziewoński)
* build: Add exec:composer and add it to `_ci` (Prateek Saxena)


## v0.20.1 / 2017-03-28
### Deprecations
* [DEPRECATING CHANGE] icons: Deprecate and/or move all the core icons (James D. Forrester)
* [DEPRECATING CHANGE] icons: Rename 'wikitrail' to 'mapTrail' (Volker E)
* Follow-up b12205ac: Add deprecation notices to icons moved in v0.16.2 (James D. Forrester)
* Follow-up da8d99af: Add deprecation notice to icon moved in v0.14.0 (James D. Forrester)

### Features
* DraggableGroupElement: Make draggable conditional (Moriel Schottlender)
* build: Implement `grunt add-theme` task to ease theme creation (Bartosz Dziewoński)

### Styles
* ButtonElement: Normalize appearance in Firefox (Volker E)
* Blank theme: Fix up the 'blank' theme (Bartosz Dziewoński)
* MediaWiki theme: Position PopupToolGroup indicator similar to other widgets (Volker E)

### Code
* Element: Add special case for document root in getClosestScrollableContainer (Bartosz Dziewoński)
* FloatableElement: Abort positioning if no longer attached (David Lynch)
* GroupElement: Transform to be an OO.EmitterList mixin (Moriel Schottlender)
* MenuOptionWidget: Remove inherited, duplicated property (Volker E)
* OO.ui.isFocusableElement: Update for jQuery 3 deprecations (Bartosz Dziewoński)
* PopupWidget: Add 'ready' event when the popup is ready (Moriel Schottlender)
* Use Node.DOCUMENT_NODE rather than magic number (Bartosz Dziewoński)
* Follow-up 4bc67351c5: Unbreak FloatableElement positioning (Roan Kattouw)
* Follow-up Iaa7dffc13: *Actually* allow `$returnFocusTo` to be `null` (Ed Sanders)
* themes: Reorder Less rules alphabetically (Volker E)
* MediaWiki theme: Remove obsolete ButtonOptionWidget styles (Volker E)
* MediaWiki theme: Remove unnecessary OptionWiget `border` property (Volker E)
* build: Add a new jenkins script (Prateek Saxena)
* build: Bump grunt-cssjanus to master (Volker E)
* build: Match file order between tests/index and karma (Timo Tijhof)
* build/demos: Generalize demos and build so that it's easier to add new themes (Bartosz Dziewoński)


## v0.20.0 / 2017-03-15
### Breaking changes
* [BREAKING CHANGE] Element#scrollIntoView: Drop `complete` config option (James D. Forrester)
* [BREAKING CHANGE] Element#scrollIntoView: Remove deprecated `complete` config parameter (James D. Forrester)
* [BREAKING CHANGE] LabelElement: Remove deprecated `fitLabel` function (James D. Forrester)
* [BREAKING CHANGE] MessageDialog: Drop the deprecated '`verbose`' flag (James D. Forrester)
* [BREAKING CHANGE] PopupWidget#setAlignment: Remove backwards-compatibility (James D. Forrester)
* [BREAKING CHANGE] Remove CapsuleMultiSelectWidget (James D. Forrester)
* [BREAKING CHANGE] Remove TextInputMenuSelectWidget (James D. Forrester)
* [BREAKING CHANGE] TextInputWidget: Remove `type=date`/`month` support (Geoffrey Mon)
* [BREAKING CHANGE] icons: Drop '…Undo' icons, deprecated in 0.18.3 (James D. Forrester)
* [BREAKING CHANGE] icons: Drop 'beta' and 'ribbonPrize', deprecated in 0.18.3 (James D. Forrester)
* [BREAKING CHANGE] icons: Drop 'betaLaunch', deprecated in 0.18.3 (James D. Forrester)
* [BREAKING CHANGE] icons: Drop status flags from Wikimedia (logos) icon pack (Volker E)

### Deprecations
* [DEPRECATING CHANGE] ActionWidget/Set: Warn for methods using the `resize` event (Prateek Saxena)

### Features
* Use `<span>` rather than `<div>` for inline-ish widgets (Bartosz Dziewoński)
* CapsuleMultiselectWidget: Call `updateInputSize` when adding, removing items (Prateek Saxena)
* DropdownInputWidget: Add support for `optgroup` (Prateek Saxena)
* FieldLayout: Use `<span>` rather than `<div>` when possible (Bartosz Dziewoński)

### Styles
* DropdownInputWidget: Tweak PHP widget's disabled styling (Bartosz Dziewoński)
* NumberInputWidget: Set input to 100% height (Volker E)
* MediaWiki theme: Add unit to `line-height` for Chrome (Volker E)
* MediaWiki theme: Align “framed” ButtonWidgets cross-browser (Volker E)
* MediaWiki theme: Ensure theme color in disabled TextInputWidget on Safari (Volker E)
* MediaWiki theme: Ensure vertical alignment of dialog top bar items (Volker E)
* MediaWiki theme: Fix TextInputWidget's IconElement `max-height` (Volker E)
* MediaWiki theme: Fix appearance of ComboBoxInputWidget PHP (Volker E)
* MediaWiki theme: Use color palette color for dialog top bar (Volker E)
* MediaWiki theme: Vertically align label in SelectFileWidget (Volker E)

### Code
* DropdownInputWidget: Remove accidental patterned background in PHP (Bartosz Dziewoński)
* MediaWiki theme: Align WindowManager to CSS Coding Guidelines (Volker E)
* MediaWiki theme: Indicators shouldn't provide global `progressive` flag (Volker E)
* MediaWiki theme: Simplify Radio- & Checkbox…optionWidget label rules (Volker E)
* build: Bump various devDependencies to master (James D. Forrester)
* build: Exclude demos/vendor from composer test too (James D. Forrester)
* demos: Add ButtonGroupWidget (icon and text) demo (Volker E)
* demos: Add disabled DropdownInputWidget demo (Bartosz Dziewoński)


## v0.19.5 / 2017-03-07
### Deprecations
* [DEPRECATING CHANGE] icons: Move 'add' from core to 'interactions' pack (James D. Forrester)

### Features
* FloatableElement: Add config for `hideWhenOutOfView` (Moriel Schottlender)

### Styles
* MediaWiki theme: Add visual feedback on focussed Outlined Booklet Dialog (Volker E)
* OutlinedBookletDialog: Bring visual order into levels (Volker E)
* icons: Add 'highlight' to 'editing-styling' pack (Moriel Schottlender)
* icons: Add 'substract' icon, in interactions pack (Volker E)
* icons: Fix vertical alignment of 'journal' (Volker E)
* icons: Remove 'teardrop' from MediaWiki theme 'close' icon (Volker E)

### Code
* CapsuleMultiselectWidget: Update popup position if height changed (Prateek Saxena)
* ComboBoxInputWidget: Improve documentation example (Bartosz Dziewoński)
* ListToolGroup: Re-clip when expanding/collapsing (Roan Kattouw)
* MenuSelectWidget#filterFromInput: Clear MenuSectionOptionWidgets if empty (Roan Kattouw)
* PopupElement: Set `$floatableContainer` to `this.$element` by default (Roan Kattouw)
* PopupTool: For bottom toolbars, make the popup go up, like toolgroups (Bartosz Dziewoński)
* PopupWidget: Make popups able to actually pop *up*, as well as sideways (Roan Kattouw)
* PopupWidget: Position anchor relative to popup, not popup relative to anchor (Roan Kattouw)
* TextInputWidget: Fix documentation for 'maxRows' type (Bartosz Dziewoński)
* Use `options` in ComboBoxInputWidget demo (Moriel Schottlender)
* Follow-up 442ffe73, 7f21350d, 9dfa5dd5: Mention in icon definitions they're deprecated (James D. Forrester)
* demos: Make demo consoles LTR, even in the RTL demo (Roan Kattouw)
* demos: Add demo/test for PopupWidget/PopupButtonWidget placements (Bartosz Dziewoński)
* demos: Add sections to dialogs demo (Bartosz Dziewoński)
* demos: Extract widgets, dialogs and layouts from dialogs.js (Bartosz Dziewoński)
* demos: Reuse some widgets in the dialogs demo (Bartosz Dziewoński)
* styles: Replace stylelint block with inline comments everywhere (Volker E)


## v0.19.4 / 2017-02-28
### Features
* Add `OO.ui.Element.static.getScrollLeft` (Bartosz Dziewoński)
* FloatableElement: Support positioning relative to all edges (Roan Kattouw)

### Styles
* MediaWiki theme: Align DraggableElement focus with standard appearance (Volker E)
* MediaWiki theme: Align appearance of PHP DropdownInputWidget to JS (Volker E)
* MediaWiki theme: Fix TextInputWidget inline label misalignment (Volker E)
* MediaWiki theme: Fix ToolGroupTool's label alignment (Volker E)
* MediaWiki theme: Fix button layout in ButtonGroup-/SelectWidgets in IE 9 (Volker E)
* MediaWiki theme: Fix styling for FieldLayout inside HorizontalLayout (Bartosz Dziewoński)
* styles: Improve vertical alignment of elements' & widgets' icons (Ed Sanders/Volker E)
* icons: Add 'feedback' icon, in interactions pack (Roan Kattouw)
* icons: Add 'searchDiacritic' icon, in editing-advanced pack (Ed Sanders)

### Code
* Make generic placeholder pseudo-class browser-prefix mixin (Ed Sanders)
* BookletLayout: When continuous, properly make the inner PageLayouts non-scrollable (Bartosz Dziewoński)
* Element: Fix `scrollLeft()` for body/html/window (Roan Kattouw)
* OutlineOptionWidget: Remove unused and misplaced values (Volker E)
* PopupWidget: Remove `left: 0;` breaking floatable popups (Roan Kattouw)
* MediaWiki theme: Remove obsolete ComboBoxInputWidget selectors (Volker E)
* README: Encourage direct release in the instructions (James D. Forrester)
* build: Test the 'minify' task in CI (James D. Forrester)
* demos: Add 'label' to ToolGroupTool example (Bartosz Dziewoński)
* demos: Extract ButtonStyleShowcaseWidget from the demo code (Bartosz Dziewoński)
* demos: Extract CapsuleNumberPopupMultiselectWidget from the demo code (Bartosz Dziewoński)
* demos: Extract remaining widgets from widgets.js (Bartosz Dziewoński)


## v0.19.3 / 2017-02-21
### Features
* FieldLayout, FieldsetLayout: Add support for `$overlay` for help popups (Bartosz Dziewoński)
* MenuSelectWidget: Add config option to not close on choose (Roan Kattouw)

### Styles
* MediaWiki theme: Make CapsuleItemWidget behave similar to other widgets (Volker E)
* MediaWiki theme: SelectFileWidget drop target aligned to UX patterns (Volker E)

### Code
* BookletLayout: Remove unnecessary overrides (Bartosz Dziewoński)
* Element#getClosestScrollableContainer: Update code comment (Bartosz Dziewoński)
* FieldLayout, LabelWidget: If input has no ID, focus on element on label click (Prateek Saxena)
* PopupWidget (and similar): Document why it is unwise to show unattached widgets, and emit warnings (Bartosz Dziewoński)
* build: Bump stylelint and make pass (James D. Forrester)
* demos: Add DropdownWidget (with MenuSectionOptionWidget) (Prateek Saxena)
* demos: Further improve responsive layout (Volker E)
* demos: Minor tweaks for button style showcase code (Bartosz Dziewoński)
* demos: Rename OO.ui.Demo to just Demo (Bartosz Dziewoński)
* demos: Replace `table` in button style showcase with responsive layout (Volker E)
* demos: Set the default page in demo.js (Bartosz Dziewoński)


## v0.19.2 / 2017-02-14
### Features
* CapsuleMultiselectWidget: Make labels work (Prateek Saxena)
* FloatableElement, PopupWidget: Do positioning from the right in RTL (Roan Kattouw)
* TextInputWidget: getValidity: Check browser validation first (Prateek Saxena)

### Styles
* icons: Fix vertical alignment of eye icon (Ed Sanders)

### Code
* core: Do not clear unrelated flags when clearing 'progressive' (Bartosz Dziewoński)
* ActionWidget: Remove event listening code for widget's 'resize' event (Prateek Saxena)
* ClippableElement: Order matters (inexplicably) (Bartosz Dziewoński)
* demos: Use longer text in popup in $overlay demo (Bartosz Dziewoński)


## v0.19.1 / 2017-02-07
### Features
* Dialog: Support Meta as well as Control for modifier on Enter key (David Lynch)

### Styles
* FieldLayout: Fix styling for disabled widgets in PHP (Bartosz Dziewoński)
* MediaWiki theme: Align tab navigation to color palette (Volker E)
* MediaWiki theme: Fix RTL version of largerText icon to be, well, RTL (James D. Forrester)
* MediaWiki theme: Fix direction of shadow on position:bottom toolbars (Ed Sanders)
* MediaWiki theme: Use correct `border-color` on PopupWidget anchor (Volker E)
* MediaWiki theme: Fix focus inset to overlap scrollbars (Volker E)
* icons: Provide a 'halfStar' vertical split star (codynguyen1116)

### Code
* CheckboxMultiselectInputWidget: Allow disabling specific options (Huji Lee)
* DraggableGroupElement: Add mandatory ARIA role (Volker E)
* FieldLayout: Move `<label>` from `$body` to `$label` (Bartosz Dziewoński)
* FieldLayout: Remove the need for `simulateLabelClick` (Prateek Saxena)
* InputWidget: Fix 'id' attribute setting for `<label>` (Bartosz Dziewoński)
* LabelWidget: Remove the need for `simulateLabelClick` (Prateek Saxena)
* Toolbar: Make toolbar position selectors more specific (Ed Sanders)
* WindowManager: Clarify `#addWindows` documentation (Bartosz Dziewoński)
* Windows: Use the "recommended" `WindowManager#addWindows` usage (Bartosz Dziewoński)
* Apex theme: Get rid of toolbar-shadow div (only used by Apex) (Ed Sanders)
* MediaWiki theme: Remove unnecessary `font-weight` property (Volker E)
* build: Bump various dev dependencies to latest (James D. Forrester)
* colorize-svg: Colorize using a method compatible with rsvg (Bartosz Dziewoński)
* demos: Load icons stylesheets with correct directionality (LTR/RTL) (Bartosz Dziewoński)
* demos: Follow-up a02979ad: Load the icons-content pack in the PHP demo (James D. Forrester)
* demos: Remove 'Constructive' button from the icons page (Prateek Saxena)
* demos: Add link to documentation (Prateek Saxena)
* demos: Fix regression on toolbars demo (Volker E)
* docs: Add quotes around `PROJECT_NAME` setting (Ricordisamoa)
* docs: Document for JSDuck various overridden inherited properties (Bartosz Dziewoński)
* docs: Fix `OO.ui.prompt()` documentation (Bartosz Dziewoński)
* docs: Set `.static.name` in all dialog examples that need it (Bartosz Dziewoński)


## v0.19.0 / 2017-01-31
### Breaking changes
* [BREAKING CHANGE] ButtonWidget: Switch `box-sizing` over to `border-box` (Volker E)
* [BREAKING CHANGE] LabelElement: Drop no-op fitLabel() method. (James D. Forrester)
* [BREAKING CHANGE] WindowManager: Error if `.static.name` is not defined when adding a window (Bartosz Dziewoński)

### Features
* PopupButtonWidget: Add `$overlay` config option (Bartosz Dziewoński)
* SelectWidget: Allow OptionWidget subclasses to provide custom match text (Roan Kattouw)
* Toolbar: Support `position:bottom` (Ed Sanders)

### Styles
* CapsuleMultiselectWidget: Fix focussing when inside BookletLayout with popup (Bartosz Dziewoński)
* CapsuleMultiselectWidget: Styling tweaks related to popups (Bartosz Dziewoński)
* MenuSelectWidget: Override ClippableElement's `min-height` (Bartosz Dziewoński)
* PopupWidgets: Unify paddings and line-height (Bartosz Dziewoński)
* TextInputWidget/MediaWiki theme: Revert "Improve Less code and align labels" (Bartosz Dziewoński)
* PanelLayout/Apex theme: Revert regression (Volker E)

### Code
* CapsuleMultiSelectWidget: Call correct parent constructor (Ricordisamoa)
* CapsuleMultiselectWidget: Make popup really work with $overlay (Bartosz Dziewoński)
* FieldsetLayout: Swap 'max-width' and 'width' (Bartosz Dziewoński)
* FloatableElement: More correctly decide if we need custom position (Bartosz Dziewoński)
* MenuSelectWidget: Hide menu if all items are hidden (Bartosz Dziewoński)
* ProcessDialog: Account for `config.flags` being undefined (Ed Sanders)
* Follow-up 1dc6a45: {Booklet,Index}Layout: Avoid deprecated `config.complete` (Roan Kattouw)
* Follow-up d21cf8a: unbreak popups with no $floatableContainer (Roan Kattouw)
* PHP: Avoid unique ID conflicts between PHP and JS code (Bartosz Dziewoński)
* demos: Failing demo for DropdownWidget with an overlay (Roan Kattouw)
* demos: Fix vertical spacing in icons demo (Bartosz Dziewoński)
* demos: Improve layout on mobile and fix various glitches (Volker E)
* demos: Make the icon page easier to use (Prateek Saxena)
* demos: Use longer text in PopupWidgets to showcase line wrapping (Bartosz Dziewoński)


## v0.18.4 / 2017-01-17
### Deprecations
* [DEPRECATING CHANGE] MessageDialog: Default 'verbose' option to true (James D. Forrester)
* Follow-up 1dc6a45: Emit deprecations from Element#scrollIntoView callback (James D. Forrester)
* Follow-up 4518bcf: Emit deprecation warnings for LabelElement#fitLabel (James D. Forrester)
* Follow-up 574fd34: Emit deprecations for use of CapsuleMultiSelectWidget (James D. Forrester)
* Follow-up ea9a4ac: Throw deprecation warnings for TextInputMenuSelectWidget (James D. Forrester)
* Follow-up f69a2ad: Emit deprecations for old PopupWidget#setAlignment values (James D. Forrester)

### Features
* CapsuleMultiSelectWidget: Add allowDuplicates option (Brad Jorsch)
* CapsuleMultiSelectWidget: Remove onFocusForPopup, call focus directly (Roan Kattouw)
* ClippableElement: Add `min-height` for usability in edge cases (Volker E)
* TextInputWidget: Disable hiding focus when clicking indicator/label (Volker E)

### Styles
* ActionFieldLayout: Limit the 'max-width: 50em' to align: top (Bartosz Dziewoński)
* ButtonGroupWidget: Limit default cursor to active ButtonWidgets (Volker E)
* FieldLayout, FieldsetLayout: Limit width of label+help to 50em (Bartosz Dziewoński)
* FieldLayout: Correct styling regressions for align: 'inline' (Bartosz Dziewoński)
* FieldLayout: Fix positioning of 'help' with align: left/right (Bartosz Dziewoński)
* MediaWiki theme: Unify box-shadows to one visual appearance (Volker E)
* PanelLayout: Remove 3D appearance of framed panels and harmonise padding (Volker E)
* PopupWidget: Change margins to prevent click blocking (Ed Sanders)

### Code
* ClippableElement: Also clean up `maxWidth`, `maxHeight` when turning clipping off (Bartosz Dziewoński)
* Element#updateThemeClasses: Batch `setTimeout()` calls (Bartosz Dziewoński)
* MediaWiki theme: Use variable for disabled ProgressBar (Volker E)
* PopupWidget#setAlignment: Tweak docs to indicate default parameter value (James D. Forrester)
* PHP: Add method Tag::generateElementId() to match JS OO.ui.generateElementId() (Bartosz Dziewoński)
* styles: Improve and clarify GPU composite layer mixin (Volker E)
* demos: Add a LabelWidget that has a corresponding TextInputWidget (Prateek Saxena)
* demos: Add lots more FieldLayout demos (Bartosz Dziewoński)
* demos: Add test for ClippableElements at the bottom of their containers (Prateek Saxena)
* docs: Use 'an' instead of 'a' before 'HTML' (Prateek Saxena)
* docs: Include an i18n example in OO.ui.msg documentation (David Lynch)
* tests: Improve ignoring expected differences in JS/PHP comparison tests (Bartosz Dziewoński)
* tests: Tweaks to the display of failed tests (Bartosz Dziewoński)
* testsuitegenerator: Allow testing LabelWidget's 'input' (Bartosz Dziewoński)
* testsuitegenerator: Specify sensible values to test for 'align' (Bartosz Dziewoński)
* testsuitegenerator: Test FieldLayout etc. also with TextInputWidget (Bartosz Dziewoński)


## v0.18.3 / 2017-01-03
### Deprecations
* [DEPRECATING CHANGE] icons: Deprecate the 'beta' and 'ribbonPrize' icons (James D. Forrester)
* [DEPRECATING CHANGE] icons: Rename '*Undo' to 'un*' (James D. Forrester)
* [DEPRECATING CHANGE] icons: Rename 'betaLaunch' to 'logoWikimediaDiscovery', move pack (James D. Forrester)

### Features
* ComboBoxInputWidget: Make it impossible to set `multiline` to true (Prateek Saxena)
* Introduce `OO.ui.isMobile()` (Ed Sanders)
* Provide `OO.ui.prompt()` method to complement `confirm()`/`alert()` (Ed Sanders)

### Styles
* FloatableElement: Replace superfluous class with general one (Volker E)
* MediaWiki theme: Change custom error border color to `destructive` (Volker E)
* MediaWiki theme: Change error/invalid color to alias of `destructive` (Volker E)
* MediaWiki theme: Fix PHP CheckboxMultiselectInputWidget/RadioSelectInputWidget option spacing (Bartosz Dziewoński)
* MediaWiki theme: Indicate normal, flagged ButtonWidgets' `:hover` clearer (Volker E)
* MediaWiki theme: Set `line-height` explicitly on legends and labels (Volker E)

### Code
* BarToolGroup: Remove obsolete CSS selectors (Volker E)
* ClippableElement: Compatibility with jQuery 3 (Bartosz Dziewoński)
* Element: Do not try to scroll invisible/unattached elements into view (Bartosz Dziewoński)
* LabelWidget: Properly hide labels if they are set to null (Ed Sanders)
* NumberInputWidget: Avoid bitwise tricks when checking for integers (Bartosz Dziewoński)
* PopupButtonWidget: Remove unnecessary CSS property (Volker E)
* ProgressBarWidget: Use CSS transforms for indeterminate widget (Bartosz Dziewoński)
* TextInputWidget: Do nothing in `#adjustSize`/`#positionLabel` if not attached (Bartosz Dziewoński)
* TextInputWidget: Only call `#onElementAttach` on focus if it wasn't called (Bartosz Dziewoński)
* TextInputWidget: Use `Element#isElementAttached` (Bartosz Dziewoński)
* styles: Replace `transform` with dedicated mixin (Volker E)
* MediaWiki theme: Make `box-shadow` LESS vars follow naming scheme (Volker E)
* MediaWiki theme: Simplify frameless ButtonWidget selectors (Volker E)
* performance: Apply webkit GPU hack to scrollable panels (Ed Sanders)
* demos: Add disabled Progress bar (Volker E)
* demos: Add examples for `OO.ui.alert()`/`confirm()`/`prompt()` (Bartosz Dziewoński)
* demos: Avoid using 'required' as a test indicator (Ed Sanders)
* build: Bump file copyright notices for 2017 (James D. Forrester)
* docs: Fix small typo (Amir Sarabadani)


## v0.18.2 / 2016-12-06
### Styles
* MediaWiki theme: Address sub-pixel rendering issues of RadioInputWidgets (Volker E)
* MediaWiki theme: Improve `:active:focus` states on ButtonElements (Volker E)
* MediaWiki theme: Reduce MapPin icons' hole for better recognisability (Volker E)

### Code
* FieldsetLayout: Temporarily remove use of `<legend>` due to Chrome 55 bug (Bartosz Dziewoński)
* TextInputWidget/MediaWiki theme: Improve Less code and align labels (Volker E)


## v0.18.1 / 2016-11-29
### Features
* PopupElement: Allow $autoCloseIgnore to be overridden (Roan Kattouw)
* WindowManager: Allow $returnFocusTo to be null (Ed Sanders)

### Styles
* MediaWiki theme: Reduce, align `margin` and `padding` of form elements (Volker E)
* MediaWiki theme: Replace color function with palette color (Volker E)
* MediaWiki theme: Standard placeholder colours for CapsuleMultiselectWidget too (Bartosz Dziewoński)
* MediaWiki theme: Tweak destructive red for background-independent contrast (Volker E)

### Code
* Field & Fieldset: Make help popup code consistent (Ed Sanders)
* PopupWidget: Consistently use OO.ui.contains() for auto-closing (Roan Kattouw)
* build: Bump eslint-config-wikimedia to v0.3.0 and make pass (James D. Forrester)
* eslint: Re-enable wrap-iife and partially enable dot-notation (Ed Sanders)


## v0.18.0 / 2016-11-08
### Breaking changes
* [BREAKING CHANGE] ComboBoxWidget: Remove this deprecated alias for ComboBoxInputWidget (James D. Forrester)
* [BREAKING CHANGE] core: Remove {add|remove}CaptureEventListener (James D. Forrester)
* [BREAKING CHANGE] icons: Remove deprecated alias 'photoGallery' (Ed Sanders)
* [BREAKING CHANGE] InputWidget: Remove deprecated #setRTL function (James D. Forrester)
* [BREAKING CHANGE] MediaWiki theme: Remove deprecated `constructive` variables (Volker E)
* [BREAKING CHANGE] TextInputWidget: remove isValid() method, deprecated since v0.12.3 (Ricordisamoa)

### Deprecations
* [DEPRECATING CHANGE] Break out parts of TextInputWidget into a new SearchInputWidget (Prateek Saxena)

### Features
* ButtonElement: Add `role="button"` only when needed (Prateek Saxena)
* ButtonWidget: Remove code to not let the button get focus after clicking (Prateek Saxena)
* CapsuleMultiselectWidget: Add placeholder option (Prateek Saxena)
* CapsuleMultiselectWidget: Don't discard current input value when editing an item (Bartosz Dziewoński)
* ComboBoxInputWidget: Hide dropdown indicator when there is no dropdown (Volker E)
* TextInputWidget: Add methods #setRequired / #isRequired (Bartosz Dziewoński)
* TextInputWidget: Allow type="month" (Geoffrey Mon)
* WindowManager: Add a $returnFocusTo property (Prateek Saxena)
* Add OO.ui.warnDeprecation method (Prateek Saxena)

### Styles
* ButtonElement: Normalize `:focus` appearance in Firefox (Volker E)
* ButtonGroupWidget: Change `cursor` on `.oo-ui-buttonElement-active` (Volker E)
* CapsuleItemWidget: Make interactivity of label clearer (Volker E)
* ComboBoxInputWidget: Align to design specification (Volker E)
* PopupToolGroup: Fix border colour (Ed Sanders)
* MessageDialog: Improve `-actions` buttons by resetting `border-radius` (Volker E)
* SelectFileWidget: Don't show action-indicating cursor on empty state (Volker E)
* MediaWiki theme: Fix border colours in toolbar (Ed Sanders)
* MediaWiki theme: Address subpixel rendering errors in buttoned widgets (Volker E)
* MediaWiki theme: Align readonly TextInputWidget to overhauled color palette (Volker E)
* MediaWiki theme: Fix `:hover` in ComboBoxInput- & CapsuleMultiselectWidget (Volker E)
* MediaWiki theme: Fix ButtonElement's `:active:focus` state visually (Volker E)
* MediaWiki theme: Fix FieldsetLayouts' icon position (Volker E)
* MediaWiki theme: Fix SelectFileWidget's label visibility in IE11 (Volker E)
* MediaWiki theme: Fix visual glitch CheckboxInputWidget's `:active` state (Volker E)
* MediaWiki theme: Fix visual glitch on `:active:focus` widgets state (Volker E)
* MediaWiki theme: Fix wrong colored `box-shadow` on ToggleSwitchWidget (Volker E)
* MediaWiki theme: Make colors follow color palette (Volker E)
* MediaWiki theme: Make placeholder follow WCAG 2.0 level AA contrast ratio (Volker E)
* MediaWiki theme: Replace abandoned color from early palette iteration (Volker E)
* MediaWiki theme: Use `@color-progressive` for progress bar (Volker E)
* MediaWiki theme: Use `color-progressive` for switched-on binary inputs (Volker E)
* icons: Replace bigger/smaller with more obvious forms (Ed Sanders)

### Code
* CapsuleMultiSelectWidget: Always keep input as wide as placeholder text (Prateek Saxena)
* CapsuleMultiselectWidget: Fix crash on right-click when no input (Moriel Schottlender)
* OutlineOptionWidget: Follow-up de9058299f: don't duplicate parent's logic (Roan Kattouw)
* Toolbar: Defer computation of the narrow threshold (Roan Kattouw)
* Window: Update `-content` CSS so that child elements can give it focus (Prateek Saxena)
* Window#withoutSizeTransitions: Build transition property using sub-properties (Prateek Saxena)
* WindowManager: Warn if .static.name is not defined when adding a window (Bartosz Dziewoński)
* Tag: Generate valid HTML for self-closing tags (Bartosz Dziewoński)
* OO.ui.warnDeprecation: Fix how we use getProp (Prateek Saxena)
* MediaWiki theme: Add W3C Standards Notation for placeholder pseudo class (Volker E)
* MediaWiki theme: Clarify usage of `@max-width-*` Less variables (Volker E)
* MediaWiki theme: Refactor z-index inside ButtonSelectWidget/ButtonGroupWidget (Bartosz Dziewoński)
* demo: Add FieldsetLayout with icon (Bartosz Dziewoński)
* demo: Align to color palette (Volker E)
* demo: Fix for IE 9 (Bartosz Dziewoński)
* demo: Remove deprecated TextInputWidget (type=search) (Volker E)
* demo: Fix PHP demo directionality (Bartosz Dziewoński)
* demo: Remove PHP 5.3 compatibility, version check and PHPCS exception (Bartosz Dziewoński)
* build: Make MediaWiki the default theme in doc live previews (Ed Sanders)
* build: Remove obsolete csscomb rules (Volker E)
* build: Remove upstreamed rules and fix documentation (Ed Sanders)
* build: Update eslint-config-wikimedia to v0.2.0 (Ed Sanders)

## v0.17.10 / 2016-10-03 (special release)
### Styles
* FieldsetLayout: Styling fixes for `<legend>` labels (Bartosz Dziewoński)
* FieldsetLayout: Work around positioning problems in Firefox (Bartosz Dziewoński)

## v0.17.9 / 2016-09-13
### Features
* DropdownWidget: Add CSS class to widgets with open dropdown menus (Volker E)
* SelectFileWidget: Remove MIME type information (Volker E)
* TextInputWidget: Make disabled fields' inner labels unselectable (Volker E)

### Styles
* ActionToolGroup: Show left border, instead of right (Ed Sanders)
* ButtonElement: Centralize styling properties (Volker E)
* ButtonOptionWidget: Make active state carry default cursor (Volker E)
* Radio- and CheckboxInputWidget: Fix visual disabled state on labels (Volker E)
* ToggleButtonWidget: Use inverted variant when initially active (Leszek Manicki)

* MediaWiki theme: Adjust CheckboxInputWidget to match M30 design (Volker E)
* MediaWiki theme: Adjust RadioInputWidget to match M29 design (Volker E)
* MediaWiki theme: Align Dropdown- & CapsuleMultiSelectWidget `:focus` state (Volker E)
* MediaWiki theme: Align disabled text contrast to WCAG compliance (Volker E)
* MediaWiki theme: Enhance button styles and align them to new color palette (Volker E)
* MediaWiki theme: Fix ButtonElement-active on flagged & primary buttons (Volker E)
* MediaWiki theme: Fix `:hover` state of ComboBoxInputWidget (Volker E)
* MediaWiki theme: Fix regression on `border` of active (selected) buttons (Volker E)
* MediaWiki theme: Improve appearance of CapsuleMultiselectWidget with child (Volker E)
* MediaWiki theme: Make ToggleSwitchWidget's disabled state follow enabled (Volker E)
* MediaWiki theme: Make colors' contrast compliant to WCAG 2.0 level AA (Volker E)
* MediaWiki theme: Toolbar: Use progressive colors for active and active-hover (Prateek Saxena)
* MediaWiki theme: Unify `-pressed` and `-emphasized` color var (Volker E)
* MediaWiki theme: Unify different widgets' selected menu state (Volker E)
* MediaWiki theme: Use a solid border for disabled SelectFile drop target (Volker E)

### Code
* FieldsetLayout: Make use of `<fieldset>` and `<legend>` tags (Volker E)
* NumberInputWidget: Clean-up Less code & remove style properties (Volker E)
* NumberInputWidget: Simplify CSS selectors & fix button text alignment (Volker E)
* TextInputWidget: Treat `rows: 0` the same in PHP and in JS (Bartosz Dziewoński)
* Toolbar: Simplify and concatenate selectors (Volker E)
* MediaWiki theme: Align tools' variables to common vars naming convention (Volker E)
* MediaWiki theme: Clean-up unnecessary properties in ToolGroup (Volker E)
* build: Align csscomb configuration with CSS coding conventions (Volker E)
* build: Introduce eslint to replace jshint and jscs (James D. Forrester)
* build: Limit the file list of jsonlint (Ed Sanders)
* build: Remove jshint and jscs, now done in eslint (James D. Forrester)
* docs: IndexLayout: Fix ReferenceError in code sample (Prateek Saxena)
* git: Add .idea directory to .gitignore (Florian)
* testsuitegenerator: Also support 'int' and 'bool' (Bartosz Dziewoński)
* testsuitegenerator: Simplify code generating all possible config options (Bartosz Dziewoński)


## v0.17.8 / 2016-08-16
### Features
* ProgressBarWidget: Do not make zero progress indeterminate (Leszek Manicki)
* ProgressBarWidget: Add PHP version (Leszek Manicki)
* TextInputWidget: Show state as valid (no matter the case) on focus (Prateek Saxena)

### Styles
* ButtonElement: Fix 'active' state icon variants in MediaWiki theme (Bartosz Dziewoński)
* FieldLayout: Use saner line-height for errors/notices (Bartosz Dziewoński)
* SelectFileWidget: Improve thumbnail appearance (Volker E)
* styles: Inherit specific `font` properties, not all (Volker E)
* MediaWiki theme: Clear border on selected framed buttons (Volker E)
* MediaWiki theme: Fix ButtonWidget (frameless, indicator) `:focus` appearance (Volker E)
* MediaWiki theme: Fix ToggleSwitchWidget's sub-pixel rounding errors (Volker E)


### Code
* MediaWiki theme: Improve CapsuleMultiselectWidget Less code and behaviour (Volker E)
* MediaWiki theme: Improve DropdownWidget Less code and behaviour (Volker E)
* MediaWiki theme: Removing never applied styles on BarToolGroup (Volker E)
* MediaWiki theme: Simplify ToolGroup selectors (Volker E)
* testsuitegenerator: Specify sensible values to test for 'progress' (Bartosz Dziewoński)


## v0.17.7 / 2016-08-03
### Styles
* MediaWiki theme: Apply `border-color` on `:hover` to textInputWidgets (Volker E)
* MediaWiki theme: Decrease `margin`/`padding` on `legend` replacement (Volker E)
* MediaWiki theme: Decrease distance between label and Checkbox*-/Radio*Widget (Volker E)
* MediaWiki theme: Improve UX on ToggleSwitchWidget (Volker E)
* icons: Fix vertical alignment of 'bell' by moving up 1px (Ed Sanders)
* icons: Provide a 'tray' icon in alerts pack (James D. Forrester)
* icons: Provide the alerts pack for Apex theme too (James D. Forrester)

### Code
* CheckboxMultiselectWidget: Rewrite Shift-clicking code (Bartosz Dziewoński)
* NumberInputWidget: Merge object literals being passed as config for buttons (Prateek Saxena)
* SelectFileWidget: Reduce div soup when 'showDropTarget' is enabled (Prateek Saxena)
* styles: Replace unprefixed `box-sizing` property with mixin (Volker E)
* MediaWiki theme: Disable vendor UI extensions on every `type=number` input (Volker E)
* MediaWiki theme: Remove unnecessary toolGroup selector (Volker E)
* MediaWiki theme: Replace `border` property values with Less variables (Volker E)
* MediaWiki theme: Replace static `color` value with Less variable (Volker E)
* build: Add 'prep-test' task to be run before running tests in the browser (Prateek Saxena)
* build: Align to stylelint-config-wikimedia for `!important` (James D. Forrester)
* build: Align to stylelint-config-wikimedia for string quotes (James D. Forrester)
* build: Bump stylelint-related devDependencies to latest (James D. Forrester)
* build: Downgrade grunt-jscs to 2.8.0 to avoid cst bug (James D. Forrester)
* docs: Correct some code comments in PHP mixins (Bartosz Dziewoński)
* standalone tests: Correct error message (Bartosz Dziewoński)


## v0.17.6 / 2016-07-12
### Features
* CapsuleMultiselectWidget: Allow ignoring user input for 'allowArbitrary' widgets (Bartosz Dziewoński)
* Dialog: Set the 'title' attribute on the title LabelWidget (Prateek Saxena)
* ToolFactory: Allow '\*' as an item in a toolgroup include list (Ed Sanders)
* Window: make the focus trap smarter (David Lynch)

### Styles
* Add aria-hidden to several Layouts (David Lynch)
* Add dialog transition duration to theme JS file (Ed Sanders)
* ButtonGroupWidget: Fix border on button's CSS states (Volker E)
* MediaWiki theme: Normalize [placeholder] appearance x-browser and ensure a11y (Volker E)
* MediaWiki theme: Unify ButtonWidget focus `border-radius` values (Volker E)
* styles: Set `line-height` to unitless values to follow best practice (Volker E)
* icons: Give "Stop" a filled background, aligned with others in the pack (Volker E)
* icons: Unify cross-out lines direction to top-left/bottom-right (Volker E)

### Code
* README: Replace git.wikimedia.org URL with Phabricator one (Paladox)
* build: Bump stylelint devDependencies to latest (James D. Forrester)
* build: Update karma and karma-coverage to latest (Paladox)
* demo: Dialogs: Removing title from SimpleDialog as it'll never show (Prateek Saxena)
* docs: Remove self-closing tag syntax in comments and demos (Volker E)
* docs: LabelWidget: Add TitledElement mixin (Prateek Saxena)
* package: Replace git.wikimedia.org url with diffusion url (Paladox)


## v0.17.5 / 2016-06-19
### Styles
* Dropdown,SelectFileWidget: Improve user experience on disabled widgets (Volker E)
* MediaWiki theme: Fix ToggleSwitchWidget's grip circle shape (Volker E)
* MediaWiki theme: Fix focus states of ActionWidget's buttons (Volker E)
* MediaWiki theme: Improve focus states of primary buttons & ToggleSwitchWidget (Volker E)

### Code
* DraggableGroupWidget: Remove unnecessary `cursor` property (Volker E)
* GroupElement#removeItems: Fix to actually unbind events (Ed Sanders)
* ProcessDialog: Change DOM ordering of actions (David Lynch)
* MediaWiki theme: Remove `line-height` from TextInputWidget `input` (Volker E)
* MediaWiki theme: Remove obsolete `color` property, which never gets applied (Volker E)
* build: Bump devDependencies to latest and make pass (James D. Forrester)
* composer: Exclude copied demo PHP from phpcs test (James D. Forrester)
* demos: Add descriptive hints on navigation types to dialog names (Volker E)


## v0.17.4 / 2016-05-31
### Features
* DropdownWidget: Handle type-to-search when menu is not expanded (Bartosz Dziewoński)
* Implement MultiselectWidget, CheckboxMultiselectWidget and CheckboxMultiselectInputWidget (Bartosz Dziewoński)
* SelectWidget: Improve focus behaviour (Bartosz Dziewoński)

### Styles
* icons: Use B/I/S/U icons for British and Candian English variants (Ed Sanders)
* MediaWiki theme: Provide an adjacent disabled ButtonGroup/SelectWidget button border (Volker E)
* MediaWiki theme: Make iconed and non-iconed buttons have the same height (Roan Kattouw)

### Code
* ButtonElement: Remove unnecessary inheritance duplication of `display` (Volker E)
* GroupWidget: Mix in GroupElement, rather than inherit from it (Bartosz Dziewoński)
* LookupElement: Add missing `@mixins` documentation (Bartosz Dziewoński)
* SelectWidget: Implement `#getFirstSelectableItem` in terms of `#getRelativeSelectableItem` (Bartosz Dziewoński)
* SelectWidget: Optimize `#getRelativeSelectableItem` without filter (Bartosz Dziewoński)
* styles: Remove unnecessary CSS rules on disabled buttons (Volker E)
* styles: Simplify disabled `.oo-ui-tool-link` rules (Volker E)


## v0.17.3 / 2016-05-24
### Deprecations
* [DEPRECATING CHANGE] CapsuleMultiSelectWidget: Rename to CapsuleMultiselectWidget (Bartosz Dziewoński)

### Features
* SelectWidget/OptionWidget: Implement selecting by access key (Bartosz Dziewoński)
* TextInputWidget: Stop returning 'multiline' from 'getSaneType' (Prateek Saxena)

### Styles
* SelectFileWidget: Improve consistency to other widgets (Volker E)
* MediaWiki theme: Align styles of normal and not-supported SelectFileWidgets (Volker E)

### Code
* CapsuleMultiselectWidget: Prefer Array#map to jQuery.map (Bartosz Dziewoński)
* CapsuleMultiselectWidget: Use OO.ui.findFocusable() (Bartosz Dziewoński)
* dependencies: Update es5-shim to v4.5.8 (James D. Forrester)
* build: Bump grunt-stylelint to v0.3.0 (James D. Forrester)
* build: Bump various devDependencies to latest (James D. Forrester)
* build: Fix watch path for css (Ed Sanders)
* build: Remove grunt-cli (Ed Sanders)
* build: Upgrade stylelint-config-wikimedia to 0.2.0 and make pass (James D. Forrester)
* build: Use stylelint instead of csslint (Volker E)
* docs: Add some missing @mixins documentation (Bartosz Dziewoński)
* stylelint: Add `@` whitespace and name case rules (Volker E)
* stylelint: Add `@media` whitespace rules (Volker E)
* stylelint: Add block formatting rules (Volker E)
* stylelint: Add font rules (Volker E)
* stylelint: Add no duplicate property rule (Volker E)
* stylelint: Add selector whitespace (Volker E)
* stylelint: Add whitespace rules (Volker E)
* stylelint: Change to use central Wikimedia configuration (Volker E)
* stylelint: Use null instead of false to disable rules (Ed Sanders)


## v0.17.2 / 2016-05-10
### Features
* ButtonWidget: Implement, document and demonstrate the 'active' config option (Bartosz Dziewoński)

### Styles
* ToggleSwitchWidget: Align focus state with other widgets (Volker E)
* MediaWiki theme: Remove `border-radius` from disabled numberInputWidget buttons (Volker E)

### Code
* TextInputWidget: Remove proprietary `<input results>` attribute styles (Volker E)
* MediaWiki theme: Align `input` & `textarea` coding style to Less way (Volker E)


## v0.17.1 / 2016-05-03
### Styles
* CapsuleMultiSelectWidget: Fix cross-browser inconsistencies and improve UX (Volker E)
* SelectFileWidget: Add `no-drop` cursor where it belongs (Volker E)
* MediaWiki theme: Align focus state of capsuleItemWidget with other widgets (Volker E)
* MediaWiki theme: Custom `:focus` state for SelectWidgets (Bartosz Dziewoński)
* MediaWiki theme: Standardize `:focus` states of ButtonWidgets (Volker E)

### Code
* DraggableGroupElement: Simplify and improve drag logic (Ed Sanders)


## v0.17.0 / 2016-04-26
### Breaking changes
* [BREAKING CHANGE] PHP: Use traits instead of custom mixin system (Kunal Mehta)
* [BREAKING CHANGE] TitledElement.php: Remove $element::$title fallback (Kunal Mehta)

### Styles
* MenuToolGroup: Correct display of checkmarks (Bartosz Dziewoński)
* OutlineOptionWidget: Correct the size of the icons (David Lynch)
* OutlineOptionWidget: Don't apply italics to "placeholder" status (James D. Forrester)
* SelectFileWidget: Fix UI glitches on over-long filenames (Volker E)
* TabOptionWidget: Disabled OptionWidget should receive default cursor (Volker E)
* styles: Add fullScreen icon to media group (Ed Sanders)

### Code
* ButtonElement.php: Fix toggleFramed() to actually be chainable (Bartosz Dziewoński)
* GroupElement::$targetPropertyName: Remove, no longer needed (Kunal Mehta)
* IconElement.php: Rename protected "icon" property (Kunal Mehta)
* IndicatorElement.php: Rename protected "indicator" property (Kunal Mehta)
* LabelElement.php: Rename protected "label" property (Kunal Mehta)
* build: Update grunt-svg2png to v0.2.7-wmf.1 (Paladox)
* demos: Split off demos.php from widgets.php (Bartosz Dziewoński)
* docparser: Remove commented-out line of code (Bartosz Dziewoński)
* styles: Factor out `max-width-input-default` variable (Volker E)


## v0.16.6 / 2016-04-19
### Features
* ButtonOptionWidget: Inherit OptionWidget, not DecoratedOptionWidget (Bartosz Dziewoński)
* ClippableElement: Gracefully handle failure to call clip() after natural height change (Roan Kattouw)
* NumberInputWidget: Disable onWheel action unless the widget has focus (Bartosz Dziewoński)
* NumberInputWidget: Disable onWheel action when the widget is disabled (Prateek Saxena)
* NumberInputWidget: Use input type="number" (Prateek Saxena)
* TextInputWidget: Allow type="number" (Prateek Saxena)
* TextInputWidget: Set step to 'any' if the type is set to 'number' (Prateek Saxena)
* styles: Give icons, indicators `min-width/-height` for cross-browser support (Volker E)

### Styles
* Apex, MediaWiki themes: Properly center PopupButtonWidget anchors (Roan Kattouw)
* MediaWiki theme: Use disabled color variable for disabled label (Volker E)
* styles: Use transparent rather than white in icons (Bartosz Dziewoński)

### Code
* SelectFileWidget: Merge identical CSS rules (Volker E)
* SelectFileWidget: Simplify CSS selector specificity (Volker E)
* TextInputWidget: Clarify comment about affected browsers (Volker E)
* TextInputWidget: Consolidate selectors with the same property rules (Volker E)
* TextInputWidget: Stop claiming to fire non existent events in the documentation (Prateek Saxena)
* styles: Centralise the width/height properties of icons and indicators (Volker E)
* Apex theme: Change variable names to match MediaWiki theme (Bartosz Dziewoński)
* README: Update with new build process (Matthew Flaschen)
* typo: texfield -> textfield (Derk-Jan Hartman)


## v0.16.5 / 2016-04-07
### Styles
* Prevent modal windows from exceeding available height on Firefox (Bartosz Dziewoński)
* Apex, MediaWiki themes: Add "articles" icon (Marc A. Pelletier)
* DropdownInputWidget: Give un-infused widget cursor:pointer (Ed Sanders)
* RadioSelectInputWidget: Match PHP styling to JS (Bartosz Dziewoński)

### Code
* ComboBoxInputWidget: Disable autocomplete by default (James D. Forrester)
* GroupElement: Add change event (Prateek Saxena)
* GroupElement.php: Use strict mode in array_search (James D. Forrester)
* styles: Lower specifity of CSS type attribute selectors (Volker E)
* styles: Minor cleanup and unification of values and comments (Volker E)
* styles: Remove unnecessary `resize` property from `select` (Volker E)
* MediaWiki theme: Exchange `rgba()` with hex CSS colors to support IE 8 (Volker E)
* MediaWiki theme: Replace fixed CSS property values with variables (Volker E)
* RadioSelectInputWidget: Don't try to reuse DOM when infusing (Bartosz Dziewoński)
* TextInputWidget: Use getValidity in demos (Ricordisamoa)
* Window: Correct documentation (Bartosz Dziewoński)
* build: Add browserNoActivityTimeout to karma (Paladox)
* build: For grunt-svg2png use a tag instead of git hash (Paladox)
* build: Update demos script to also run grunt publish-build (Paladox)
* build: Use a version of grunt-svg2png without a rate-limited CDN (Paladox)


## v0.16.4 / 2016-03-22
### Features
* NumberInputWidget: Optionally don't show the increment buttons (Thalia Chan)

### Styles
* NumberInputWidget: Fix rounded corners when showButtons=false (Ed Sanders)

### Code
* core: Add tests for throttle (David Lynch)
* Tag: Allow appendContent and prependContent to accept an array (Moriel Schottlender)
* LabelElement: Cast label to string before check if it is empty (Florian)
* README.md: Add note about needing composer, clean up more generally (James D. Forrester)
* build: Bump grunt-karma to 0.12.2 (Paladox)
* build: Drop the 'npm prepublish' task which runs pre-install as well (James D. Forrester)
* demos: Restore constructive widgets (James D. Forrester)
* rubocop: Re-run todos, upgrade to newer rule names (James D. Forrester)
* rubocop: Review todos (Bartosz Dziewoński)


## v0.16.3 / 2016-03-16
### Features
* core: Add `#throttle` to complement `#debounce` (David Lynch)
* ClippableElement: Never exceed the dimensions of the browser viewport (Bartosz Dziewoński)
* FloatableElement: Hide if the anchor element is outside viewport (Bartosz Dziewoński)

### Styles
* Apex, MediaWiki themes: Fix vertical alignment of close icon (Ed Sanders)
* MediaWiki theme: Disabled ButtonElement icon should not be colored (Bartosz Dziewoński)

### Code
* ButtonInputWidget: Actually disallow non-plaintext labels in 'useInputTag' mode (Bartosz Dziewoński)
* Element: Preserve `OOUI\HtmlSnippet( '' )` when infusing (Bartosz Dziewoński)
* InputWidget: Actually reuse parts of the DOM when infusing (Bartosz Dziewoński)
* MediaWiki theme: Remove broken remnant of d6b05bc0 (Bartosz Dziewoński)
* TextInputWidget: Treat empty placeholder the same in PHP and JS (Bartosz Dziewoński)
* TitledElement: Treat empty title the same in PHP and JS (Bartosz Dziewoński)
* build: Bump devDependencies to latest (James D. Forrester)
* build: Bump devDependencies to latest (Paladox)
* build: Update grunt-svg2png to commit 2fe1dad07eaec4b655263f8b487a672df4b668b4 (Paladox)
* demo: Expand the dialog $overlay demo for testing scrolling things off-screen (Bartosz Dziewoński)
* tests: Emulated setTimeout for unit testing (David Chan)
* testsuitegenerator: Always test empty values for 'string' type, not just for 'label' (Bartosz Dziewoński)
* testsuitegenerator: Change values tested for 'flags' config options (Bartosz Dziewoński)
* testsuitegenerator: Test 'HtmlSnippet' type (mostly for labels) (Bartosz Dziewoński)


## v0.16.2 / 2016-03-08
### Deprecations
* [DEPRECATING CHANGE] MediaWiki theme: Scrap `constructive` flag (Volker E)
* [DEPRECATING CHANGE] Move some editing icons from core to editing-* (James D. Forrester)

### Features
* Dialog: trigger the primary action with Control+Enter (David Lynch)
* TextInputWidget: Allow type="date" (Geoffrey Mon)

### Styles
* Apex, MediaWiki themes: Add markup '<>' icon in editing-advanced (Ed Sanders)
* Apex, MediaWiki themes: Drop padding from buttons in MessageDialogs (James D. Forrester)
* editing-styling pack: Have uk fallback to use ru bold and italic icons (Paladox)
* styles: Remove superflous pseudo-class and unitize comments (Volker E)

### Code
* CapsuleMultiSelectWidget: Emit 'resize' when widget height changes (Bartosz Dziewoński)
* TextInputWidget: Prevent uncaught errors when using #selectRange in IE (Ed Sanders)
* TextInputWidget: Update comment about Blink height miscalculation (Bartosz Dziewoński)
* Follow-up I0667fbc: Fix draggable element CSS (Ed Sanders)
* Add Element::configFromHtmlAttributes() helper method (Bartosz Dziewoński)
* Clean-up duplicate properties across widgets (Volker E)
* docs: Clarify the lack of `.oo-ui-box-shadow()` mixin (Volker E)


## v0.16.1 / 2016-03-01
### Styles
* CapsuleItemWidget: Revert regression on "remove" button in Firefox (Volker E)

### Code
* ActionFieldLayout: Add max-width: 50em; (Florian)
* DraggableGroupElement: Don't emit reorder event when action is a no-op (Ed Sanders)
* Element: Fix #gatherPreInfuseState called incorrectly, causing TypeErrors (Thiemo Kreuz)
* NumberInputWidget: fix example (Ricordisamoa)
* SelectWidget: fix incorrect `@return` that should be `@param` (Ricordisamoa)
* build: Compress PNGs with Zopfli etc. after they are built (James D. Forrester)
* build: Enable all passing jscs jsDoc rules (Ricordisamoa)
* build: Enable jscs jsDoc rule 'checkAnnotations' and make pass (Ricordisamoa)
* build: Enable jscs jsDoc rule 'checkParamNames' and make pass (Ricordisamoa)
* build: Enable jscs jsDoc rule 'checkTypes' and make pass (Ricordisamoa)
* build: Enable jscs jsDoc rule 'requireNewlineAfterDescription' and make pass (Ricordisamoa)
* build: Enable jscs jsDoc rule 'requireReturnTypes' and make pass (Ricordisamoa)
* demos: Display a nicer error message on old PHP versions (Kunal Mehta)


## v0.16.0 / 2016-02-22
### Breaking changes
* [BREAKING CHANGE] DraggableGroupElement: Add default implementation of reorder (Ed Sanders)
* [BREAKING CHANGE] Remove 'noimages' distribution (Bartosz Dziewoński)
* [BREAKING CHANGE] Require PHP 5.5.9+; drop old array syntax (James D. Forrester)
* [BREAKING CHANGE] SelectFileWidget: Remove deprecated config 'dragDropUI' (Prateek Saxena)

### Deprecations
* [DEPRECATING CHANGE] MenuOptionsWidgets: Drop jQuery autoEllipsis support (Bartosz Dziewoński)

### Features
* core#debounce: If an immediate timeout is already waiting, don't re-set it (Bartosz Dziewoński)
* LabelElement: Bring in highlightQuery method from VE (Ed Sanders)
* DraggableElement: Defer adding of -dragging class so it isn't applied to copy (Ed Sanders)
* DraggableElement: Introduce $handle config option (Ed Sanders)
* DraggableGroupElement: Live reorder list while dragging (Ed Sanders)
* DraggableGroupElement: Only show meaningful drop positions (Ed Sanders)

### Styles
* CapsuleItemWidget: Tweak styles for the "remove" button (Bartosz Dziewoński)
* MenuSelectWidget: Bring some sanity styling when inside different widgets (Bartosz Dziewoński)
* NumberInputWidget: Apex: Round the correct corners in the disabled state (Prateek Saxena)
* styles: Use block rather than inline-block to avoid line height issues (Bartosz Dziewoński)
* MediaWiki theme: Restore non-broken version of eye.svg (Bartosz Dziewoński)

### Code
* Avoid parsing HTML when creating <input> nodes (Bartosz Dziewoński)
* tests: Actually run core test suite in standalone mode (Bartosz Dziewoński)
* Compress PNGs with zopflipng (Ori Livneh)
* DraggableGroupElement: Cache directionality (Ed Sanders)
* DraggableGroupElement: Fix offset calculation (Ed Sanders)
* DraggableGroupElement: Reduce flicker when dragging (Ed Sanders)
* TextInputWidget: Don't call #updatePosition if there's no label to position (Bartosz Dziewoński)
* PHP: Take advantage of PHP 5.5 understanding ( new Foo )->foo (Bartosz Dziewoński)
* README: Update Phabricator URL broken by upgrade (James D. Forrester)
* build: Bump grunt-svg2png to a newer (still personal) version; lots faster (James D. Forrester)

## v0.15.4 / 2016-02-16
### Deprecations
* [DEPRECATING CHANGE] Element#scrollIntoView: Replace callback with promise (Ed Sanders)

### Features
* SelectWidget: Prevent mouse highlighting while typing-to-select (Bartosz Dziewoński)

### Styles
* PHP DropdownInputWidget: Match height of <option> to JS MenuOptionWidget (Bartosz Dziewoński)

### Code
* DraggableElement: Remove 'HACK' comment, this isn't a hack (Bartosz Dziewoński)
* Element: Expand variable names in scrollIntoView (Ed Sanders)
* Element, ListToolGroup: Add some missing documentation (Ed Sanders)
* Element#scrollIntoView: Make the promise version actually work (Bartosz Dziewoński)
* PopupWidget: Only build head and footer if we're going to use it (Bartosz Dziewoński)
* PopupWidget: Tweak some comments (Bartosz Dziewoński)
* styles: Remove initial value `ease` from `transition` (Volker E)

## v0.15.3 / 2016-02-09
### Features
* CapsuleItemWidget: Let user tab through items, edit and delete them (Prateek Saxena)
* CapsuleMultiSelectWidget: Edit instead of remove on Backspace (Prateek Saxena)
* CapsuleWidgets: Edit on click and remove on Ctrl+Backspace (Prateek Saxena)
* CapsuleWidgets: Toggle through capsules and the input with arrow keys (Prateek Saxena)
* DropdownWidget: Open menu on up and down arrow keys (Prateek Saxena)
* MenuSelectWidget: Ensure currently selected element is visible when menu opens (Bartosz Dziewoński)
* SelectFileWidget: Show thumbnail when dropTarget is shown (Prateek Saxena)
* Really preserve dynamic state of widgets when infusing (Bartosz Dziewoński)

### Styles
* MediaWiki, Apex themes: Replace 'language' icon with tweaked version (Mun May Tee)

### Code
* CapsuleItemWidget: Use Button instead of an Indicator (Prateek Saxena)
* CapsuleMultiSelectWidget: Extend config instead of when setting property (Prateek Saxena)
* InputWidget: Remove the 'setAccessKey' method (Prateek Saxena)
* SelectWidget: Really prevent default action during type-to-select (Bartosz Dziewoński)
* Put '@keyframes' rules inside a mixin to avoid duplicating them (Bartosz Dziewoński)
* Apex theme: Remove unnecessary '@keyframes' prefixing (Bartosz Dziewoński)
* MediaWiki theme: Align button mixins/states to CSS guidelines/standard (Volker E)
* Correct code using plain DOM events documented as jQuery events (Bartosz Dziewoński)
* demo: Add a long DropdownInputWidget demo (Bartosz Dziewoński)
* demo: In PHP demo, load oojs-ui-core only instead of whole oojs-ui (Bartosz Dziewoński)
* demo: Measure time needed to construct the demo (Bartosz Dziewoński)
* demo: widgets: OO.ui.CapsuleMultiSelectWidget: Remove non-existent 'values' config (Prateek Saxena)
* docparser: Recognize and ignore '@uses' (Bartosz Dziewoński)
* docs: OO.ui.CapsuleMultiSelectWidget: Config options (Prateek Saxena)
* docs: OO.ui.CapsuleMultiSelectWidget: Link to the widget it uses (Prateek Saxena)
* docs: OO.ui.SelectFileWidget: Minor language change (Prateek Saxena)


## v0.15.2 / 2016-02-02
### Features
* DropdownWidget: Prevent label from overflowing the handle (Bartosz Dziewoński)

### Styles
* Ensure gradient filter rendering on IE 8&9 (Bartosz Dziewoński)
* Remove unused CSS classes .oo-ui-ltr and .oo-ui-rtl (Bartosz Dziewoński)
* Update `.oo-ui-vertical-gradient` mixin to modern times (Volker E)

### Code
* Unify SVG icon color values to CSS/Less coding standards (Volker E)
* ComboBoxInputWidget: Don't make the 'datalist' infusable (Bartosz Dziewoński)
* Move OO.ui.alert and OO.ui.confirm methods to separate file (Bartosz Dziewoński)
* README: Add "Loading the library" wherein we apologise for the mess that is the dist/ directory (Bartosz Dziewoński)
* build: Actually check that all required files are not missing (Bartosz Dziewoński)
* build: Add intro.js.txt and outro.js.txt to all distribution JS files (Bartosz Dziewoński)
* build: De-duplicate per-theme modules lists (Bartosz Dziewoński)
* build: Only define one 'less' task, not one per-distribution (Bartosz Dziewoński)
* build: Remove unused 'ieCompat' options from 'less' (Bartosz Dziewoński)
* build: Remove unused 'report' options from 'less' (Bartosz Dziewoński)
* build: Small modules.yaml tweaks (Bartosz Dziewoński)
* build: Split the library into four parts (Bartosz Dziewoński)
* build: Switch modules.json to YAML to document some of the weird stuff we've put in there (Bartosz Dziewoński)
* build: Unbreak `grunt build --graphics=vector` (Bartosz Dziewoński)
* build: Update phpunit/phpunit to 4.8 (Paladox)
* docparser: Parse '@class Foo' annotations, not just '@class' (Bartosz Dziewoński)


## v0.15.1 / 2016-01-26
### Features
* Really filter out unsafe URLs, but don't throw silly exceptions (Bartosz Dziewoński)
* ClippableElement: Try to prevent unnecessary scrollbars (Bartosz Dziewoński)
* Dialog: Don't set `overflow:hidden;` on `.oo-ui-window-body` elements (Alex Monk)
* TextInputWidget: Don't fail if 'validate' function returns null (Bartosz Dziewoński)

### Styles
* WindowManager: Only apply `top: 1em; bottom: 1em;` to non-fullscreen windows (Bartosz Dziewoński)
* Align mixin whitespace to CSS/Less coding guidelines (Volker E)
* Enable `cursor: pointer` just on enabled widgets (Volker E)
* Apex, MediaWiki themes: Fix size of templateAdd icon (Ed Sanders)
* Apex, MediaWiki themes: Quotes icon fixes (Ed Sanders)
* Apex, MediaWiki themes: Re-crush SVGs, removing useless ID values and empty groups (James D. Forrester)
* Apex theme: Fix FieldLayout padding in inline mode (Ed Sanders)
* Apex theme: NumberInputWidget: Fix width of +/- buttons (Ed Sanders)
* MediaWiki theme: Add invert variant to 'accessibility' icon pack icons (Bartosz Dziewoński)
* MediaWiki theme: Align `@input-*` vars to coding guidelines (Volker E)
* MediaWiki theme: Align `rgba()` values to CSS/Less guidelines (Volker E)
* MediaWiki theme: Align size variables to CSS/Less guidelines (Volker E)
* MediaWiki theme: Consolidate emphasized color values into variable (Volker E)
* MediaWiki theme: Establish new `@border-default` variable (Volker E)
* MediaWiki theme: Make icon variants actually work for all icons (Bartosz Dziewoński)
* MediaWiki theme: Make transition of text input fields smoother (Volker E)
* MediaWiki theme: Merge `@oo-ui-toolbar-bar-text` & `@color-default` vars (Volker E)
* MediaWiki theme: Replace fixed & consolidate disabled values with vars (Volker E)
* MediaWiki theme: Update avatar icon (Pau Giner)

### Code
* NumberInputWidget: Replace `box-sizing` property with mixin as anywhere else (Volker E)
* SelectFileWidget: Order name and type spans in the order they are shown (Prateek Saxena)
* TextInputWidget: Simplify `#getValidity` (Bartosz Dziewoński)
* README: Add a 'Contributing' section (James D. Forrester)
* build: Don't generate .min.js and .min.css files by default (Bartosz Dziewoński)
* build: Only build one graphics distribution (mixed/vector/raster), not all (Bartosz Dziewoński)
* build: Update jakub-onderka/php-parallel-lint to 0.9.2 (Paladox)
* build: Update mediawiki/mediawiki-codesniffer to 0.5.1 (Paladox)
* demo: Extend compounded form in widget.js demo (Volker E)

## v0.15.0 / 2016-01-12
### Breaking changes
* [BREAKING CHANGE] Drop Internet Explorer 8 support from JavaScript code (Ricordisamoa)
* [BREAKING CHANGE] Delete deprecated aliases 'picture' and 'insert' (Ed Sanders)

### Deprecations
* [DEPRECATING CHANGE] Create single icon for language/translation (Ed Sanders)
* [DEPRECATING CHANGE] Move 'redirect' icon to 'articleRedirect' and cleanup (Ed Sanders)
* [DEPRECATING CHANGE] core: Deprecate add/removeCaptureEventListener (Bartosz Dziewoński)

### Features
* Send Escape key cancel events through action handler (Alex Monk)

### Styles
* MediaWiki theme: Align variable values & properties to CSS/Less guidelines (Volker E)
* MediaWiki theme: Align `@neutral-button-border` to CSS/Less guidelines (Volker E)
* MediaWiki theme: Align `transition` variables with coding guidelines (Volker E)
* MediaWiki theme: Change color value to Less variable (Volker E)
* MediaWiki theme: Clarify `@active` variable by renaming it (Volker E)
* MediaWiki theme: Clarify `@background` var by renaming it (Volker E)
* MediaWiki theme: Clarify `@select` variable by renaming it (Volker E)
* MediaWiki theme: Clarify `@text` variable by renaming it (Volker E)
* MediaWiki theme: Consolidate stray `margin` and `padding` properties (Volker E)
* MediaWiki theme: Remove unnecessary `@-ms-keyframes` vendor rule (Volker E)
* MediaWiki theme: Replace fixed `invalid` color value with variable (Volker E)
* MediaWiki theme: Unify `border` property values (Volker E)
* MediaWiki theme: Unify `border-radius` values (Volker E)
* MediaWiki theme: Unify `-disabled` variables usage (Volker E)

### Code
* core: Add constants for MouseEvent.which button codes (Ed Sanders)
* demo: Remove IE 8 support (Bartosz Dziewoński)
* build: Fix typos ("overridden") (Ed Sanders)
* build: Bump file copyright notices for 2016 (James D. Forrester)
* build: Update most devDependencies to latest (James D. Forrester)
* build: Updating development dependencies (Kunal Mehta)

## v0.14.1 / 2015-12-08

### Features
* Implement OO.ui.alert() and OO.ui.confirm() (Bartosz Dziewoński)

### Styles
* CapsuleMultiSelectWidget: Interface tweaks (Bartosz Dziewoński)
* CapsuleMultiSelectWidget: Make the text field span all available area (Bartosz Dziewoński)
* CapsuleMultiSelectWidget: Update menu position when typing (Bartosz Dziewoński)
* HorizontalLayout: Synchronise behaviour between themes (Bartosz Dziewoński)
* Apex theme: Enlarge 'search' icon (Bartosz Dziewoński)
* MediaWiki theme: Correct text color in MessageDialog, TabOptionWidget (Volker E)

### Code
* Tool*: Consolidate and cross-link some documentation (Bartosz Dziewoński)
* Tool*: Expand, correct docs for #onUpdateState and the related event (Bartosz Dziewoński)
* core.js: Extract a large chunk of the file incorrectly in a closure (Bartosz Dziewoński)
* Apex, MediaWiki themes: Standardize XML structure for various 'search' images (Bartosz Dziewoński)
* MediaWiki theme: Add missing theme mixin placeholder (no-op) (Bartosz Dziewoński)
* build: Test PHP documentation with Doxygen via composer and make pass (James D. Forrester)
* demo: Quit using the 'image' icon in documentation examples (Bartosz Dziewoński)

## v0.14.0 / 2015-11-24
### Breaking changes
* [BREAKING CHANGE] Depend on OOjs v1.1.10, up from v1.1.9 (James D. Forrester)
* [BREAKING CHANGE] TextInputWidget: Remove old deprecated alias #setPosition (Ed Sanders)

### Deprecations
* [DEPRECATING CHANGE] De-duplicate 'trash' and 'remove' icons (James D. Forrester)

### Features
* TextInputWidget: Add insertContent method (Thalia Chan)
* TextInputWidget: Add encapsulateContent method to insert new content around a selection (Thalia Chan)

### Styles
* Apex theme: Provide the 'interactions' icon pack (James D. Forrester)
* MediaWiki theme: Make dialog and panel box-shadows outset rather than inset (Ed Sanders)

### Code
* FlaggedElement.php: Fix type hint (Reedy)
* SelectFileWidget: Remove sometimes-incorrect 'title' on the <input> (Bartosz Dziewoński)
* SelectFileWidget: Use i18n string for button label (Ed Sanders)
* TextInputWidget: Fix documentation of insertContent method (Thalia Chan)
* \*.php: Replace `@chainable` jsduck-ism with `@return` $this (Reedy)
* .gitattributes: Ignore both `/doc` and `/docs` directories (James D. Forrester)
* AUTHORS: Update for the past few months' work (James D. Forrester)
* build: Added Rakefile (Željko Filipin)

## v0.13.3 / 2015-11-17
### Deprecations
* [DEPRECATING CHANGE] Duplicate icons: Unify 'picture' and 'image' (Ed Sanders)

### Features
* RequestManager: Introduce a mixin for widgets that need to do API calls (David Lynch)
* TextInputWidget: Add getRange method (Ed Sanders)
* WindowManager: Allow getSetup/ReadyProcess to reject (Ed Sanders)
* WindowManager: Fade in overlay after 'setup' not 'ready' (Ed Sanders)

### Styles
* MediaWiki, Apex themes: Remove small 0.1em vertical margin from buttons (Bartosz Dziewoński)
* MediaWiki theme: Add destructive variant to the 'cancel' icon (James D. Forrester)
* MediaWiki theme: Reduce whitespace between FieldLayouts (Bartosz Dziewoński)

### Code
* TitledElement: Behave like its docs say it should (David Lynch)
* Use null for abstract methods and correct documentation (Ed Sanders)
* demo: Make button style showcase a table (Bartosz Dziewoński)

## v0.13.2 / 2015-11-10
### Deprecations
* [DEPRECATING CHANGE] ComboBoxWidget: Refactor into ComboBoxInputWidget (Bartosz Dziewoński)
* [DEPRECATING CHANGE] MediaWiki, Apex themes: Unify add/insert icons (Ed Sanders)

### Features
* ComboBoxInputWidget: Implement PHP version (Bartosz Dziewoński)
* LookupElement: Make auto-highlighting the first term configurable (Florian)

### Styles
* Add some missing white backgrounds and use variables when possible (Bartosz Dziewoński)
* MediaWiki theme: Make the menu icon identical to Apex's (Ed Sanders)
* MediaWiki theme: Specify 'line-height' for DropdownWidget's handle (Bartosz Dziewoński)
* WikiText icon: Make slightly narrower (Ed Sanders)

### Code
* Apex theme: Remove dead styles for ComboBoxWidget (Bartosz Dziewoński)
* build: Make copy:fastcomposerdemos work again (Bartosz Dziewoński)

## v0.13.1 / 2015-11-03
### Deprecations
* [DEPRECATING CHANGE] InputWidget: Replace `#setRTL` with `#setDir` (Ed Sanders)

### Features
* Allow widgets to re-use parts of the DOM when infusing; use for InputWidget's `$input` (Bartosz Dziewoński)
* FieldLayout: Allow setting errors and notices dynamically (Bartosz Dziewoński)
* InputWidget: Add '`dir`' to config (Ed Sanders)

### Styles
* TextInputWidget: Account for scroll bar width when positioning indicators/labels (Ed Sanders)
* TextInputWidget: Ensure icon+indicator+label are top aligned in multi-line mode (Ed Sanders)

### Code
* FieldLayout: Mark `#makeMessage` as `@protected` (Bartosz Dziewoński)
* History.md: wrap `<select>` tag in backticks (Ricordisamoa)
* tests: Refactor property->attribute copying (Ed Sanders)

## v0.13.0 / 2015-10-27
### Breaking changes
* [BREAKING CHANGE] Remove aliases for OO.ui.mixins, deprecated in 0.11.4 (C. Scott Ananian)
* [BREAKING CHANGE] Turn Element#gatherPreInfuseState into a static method (Bartosz Dziewoński)

### Features
* Update outline widget when current item is scrolled out of view (Ed Sanders)
* TextInputWidget: Emit 'resize' events (Ed Sanders)
* TextInputWidget: Fix scrollbars in `<textarea>`s in IE8-11 (Ed Sanders)
* TextInputWidget: Improve selection API (Ed Sanders)

### Styles
* MediaWiki theme: Adjust ToggleSwitchWidget to match M61 design (Volker E)
* Follow-up I54f1e3c92: Fix placement of cursors on checkbox/radio widgets (Volker E)
* Follow-up I598e7b25a: Apply MenuToolGroup missing styles fix to Apex theme (Ed Sanders)

### Code
* Consistently use '`//`' rather than '`/* */`' for Less comments (Bartosz Dziewoński)
* Remove obsolete Opera<12.1 vendor prefixes (Volker E)
* Remove unnecessary IE10beta vendor-prefixes from OOjs UI (Volker E)
* build: Switch back to upstream version of grunt-contrib-concat (Timo Tijhof)
* build: Updating development dependencies (Kunal Mehta)
* build: Use my Gmail address for attribution (Timo Tijhof)

## v0.12.12 / 2015-10-13
### Features
* CapsuleMultiSelectWidget: When 'allowArbitrary' is true, don't require 'Enter' to confirm (Bartosz Dziewoński)
* SelectFileWidget: Add a focus method (Ed Sanders)

### Styles
* CapsuleMultiSelectWidget: Set 'background-color' rather than 'background' (Bartosz Dziewoński)
* DropdownWidget: Fix vertical alignment of handle's text (Volker E)
* MediaWiki theme: Get transitions on ButtonWidget's `:hover` states in sync (Volker E)
* MediaWiki theme: Unbreak checkbox/radio 'cursor: pointer' (Bartosz Dziewoński)
* MediaWiki theme: Use inverted icon for 'active' buttons (Ed Sanders)

### Code
* ButtonElement: Actually use 'active' property and add getter (Ed Sanders)
* Element: Document $element config option (Thalia)
* composer.json: Add author names & e-mails (Alangi Derick)
* demo: Correct some typos (Bartosz Dziewoński)

## v0.12.11 / 2015-10-06
### Styles
* MediaWiki theme: Make shadows translucent black instead of light grey (Ed Sanders)
* MediaWiki theme: Make PHP DropdownInputWidget look closer to JS version (Bartosz Dziewoński)

### Code
* Follow-up I4acbe69420: BookletLayout: Fix focus of page switching (Ed Sanders)
* IndexLayout: Fix focus of panel switching (Ed Sanders)
* TextInputWidget: Remove 'autocomplete' attribute on page navigation (Bartosz Dziewoński)
* build: Bump es5-shim and various devDependencies to master (James D. Forrester)

## v0.12.10 / 2015-09-29
### Styles
* Fix icon/indicator padding on TextInputWidget/SelectFileWidget (Ed Sanders)

### Code
* CapsuleItemWidget: Remove 'click' event preventing (Bartosz Dziewoński)
* FloatableElement: Don't try unbinding events before we bind them (Bartosz Dziewoński)
* SelectWidget: Ensure 'choose' never emits null (Ed Sanders)
* Remove old textInputWidget-decorated classes (Ed Sanders)
* build: Upgrade MediaWiki-Codesniffer to 0.4.0 (Kunal Mehta)

## v0.12.9 / 2015-09-22
### Features
* BookletLayout, IndexLayout: Make autoFocus and focussing more reliable (Bartosz Dziewoński)
* CapsuleMultiSelectWidget: Allow using CapsuleItemWidget subclasses (Bartosz Dziewoński)
* CardLayout: Add a 'label' config option (Ed Sanders)
* FloatableElement: Introduce mixin (Bartosz Dziewoński)
* FloatingMenuSelectWidget: Update position of menus within overlay while scrolling (Bartosz Dziewoński)
* IndexLayout: Add 'expanded' option, passed through to StackLayout (Ed Sanders)
* MenuLayout: Use child selectors to allow nesting menus (Ed Sanders)
* Re-attempt I31ab2bace4: Try to stop user from tabbing outside of open dialog box (Ed Sanders)

### Styles
* SelectFileWidget: Move file type over to the right in secondary text colour (Ed Sanders)
* Fix focus styles on disabled widgets (Volker E)
* Apex, MediaWiki themes: Make most borders on table icon thinner (Ed Sanders)
* Apex, MediaWiki themes: Make picture icon border thinner (Ed Sanders)
* MediaWiki theme: Alter buttons' padding and position icons absolutely (nirzar)
* MediaWiki theme: Fix height of IndexLayout tab widget (Ed Sanders)
* MediaWiki theme: Unify box-shadows for PopupWidget and DropdownWidget (Volker E)

### Code
* #isFocusableElement: Rewrite for performance and correctness (Ed Sanders)
* BookletLayout: Remove unnecessary JSHint override (Bartosz Dziewoński)
* DropdownWidget: Update example doc to show #getMenu usage (Ed Sanders)
* Follow-up bf1497be: Fix PopupToolGroup use of renamed Clippable property (Ed Sanders)
* PopupWidget: Add missing `@mixins` doc entry (Bartosz Dziewoński)
* SelectFileWidget: Fix DOM order of file type label (Ed Sanders)
* Widget: Fix docs for disable event (Ed Sanders)
* docs: Remove excess empty lines in comments (Bartosz Dziewoński)
* docs: Add quotes around PROJECT_BRIEF setting (Timo Tijhof)

## v0.12.8.1 / 2015-09-18 (special release)
### Code
* build: Update version requirement for mediawiki/at-ease: 1.0.0 → 1.1.0 (Ori Livneh)

## v0.12.8 / 2015-09-08
### Styles
* SelectFileWidget: Overflow and ellipsis for label (Ed Sanders)
* Apex theme: Move transition timing to common variables (Prateek Saxena)
* MediaWiki theme: Move window transition to `@medium-ease` variable (Prateek Saxena)
* MediaWiki theme: Add missing `width` and `height` attributes to icons (Ed Sanders)
* Clean up CSS values in .oo-ui-transition calls (Timo Tijhof)
* Use 'ease' instead of 'ease-in-out' for CSS transitions (Timo Tijhof)

### Code
* Toolbar: Prevent double initialization (Roan Kattouw)
* build: Bump grunt-contrib-jshint from 0.11.2 to 0.11.3 to fix upstream issue (James D. Forrester)
* build: Upgrade grunt-banana-checker to v0.3.0 (James D. Forrester)

## v0.12.7 / 2015-09-01
### Deprecations
* [DEPRECATING CHANGE] SelectFileWidget: Re-design to use a clearly clickable button (Ed Sanders)

### Styles
* FieldLayout: Don't add `margin-bottom` when in a HorizontalLayout (Florian)
* SelectFileWidget: Use gray for hover and `@progressive-fade` for drop active (Prateek Saxena)
* Apex, MediaWiki themes: Fix scale of external link icon (Ed Sanders)
* Apex, MediaWiki themes: Re-crush all SVG files with SVGO (James D. Forrester)
* Apex, MediaWiki themes: Reduce size of 'close' icon by 1px (Ed Sanders)
* Apex, MediaWiki themes: Remove Inkscape-ism from SVG files (James D. Forrester)
* Apex, MediaWiki themes: Standardise XML prolog for SVG files (Bartosz Dziewoński)
* MediaWiki theme: Fix viewBox of arrow indicators (Ed Sanders)
* MediaWiki theme: Fix viewBox of several icons (James D. Forrester)

### Code
* LookupElement: Really disallow editing of `readOnly` TextInputWidgets (Bartosz Dziewoński)
* SelectFileWidget: Fix drop and drop hover exception in Firefox (Ed Sanders)
* SelectFileWidget: Improve type checking (Ed Sanders)

## v0.12.6 / 2015-08-25
### Features
* AccessKeyedElement: Introduce (Florian)
* ButtonOptionWidget: Mixin TitledElement (Bartosz Dziewoński)
* ClippableElement: Allow $clippableContainer to be different from $clippable (Roan Kattouw)
* Dialog: Listen for Escape key on $element, not document (Roan Kattouw)
* InputWidget: Add TitledElement and AccessKeyedElement mixins (Florian)
* PopupWidget: Make it possible to add static footers (Moriel Schottlender)
* SelectFileWidget: Add drag drop UI as a config (Prateek Saxena)
* TextInputWidget: Add moveCursorToEnd() (Roan Kattouw)

### Styles
* MenuToolGroup: Add some missing styles for tools' 'check' icons (Bartosz Dziewoński)
* PopupWidget: don't apply header styles to footer (Roan Kattouw)
* SelectFileWidget: Mute the drag and drop design (Ed Sanders)
* Add colour to neutral state of MW frameless buttons (Ed Sanders)
* Editing-advanced icon pack: Add 'calendar' (Bartosz Dziewoński)

### Code
* DropdownInputWidget: Allow users to pass config options to DropdownWidget (Alex Monk)
* Theme: Add theme classes to $icon and $indicator only (Bartosz Dziewoński)
* Use OO.ui.debounce() for Element#updateThemeClasses (Roan Kattouw)
* Document browser-specific code with support comments (Timo Tijhof)
* Update OOjs to v1.1.9 (James D. Forrester)
* Fix file permissions (Southparkfan)
* Fix inArray test in drag handler (Ed Sanders)
* Prefer ES5 over jQuery methods (Bartosz Dziewoński)
* build: Enable jscs rule 'requireSpacesInsideBrackets' and make pass (James D. Forrester)
* build: Enable jscs rule 'requireVarDeclFirst' and make pass (James D. Forrester)
* build: Make `quick-build` build the 'mixed' distribution (James D. Forrester)
* build: Update jscs devDependency from 1.8.0 to 2.1.0 (James D. Forrester)
* build: Update various devDependencies to latest (James D. Forrester)
* core: Remove spurious "[description]" placeholder from documentation (Timo Tijhof)
* demos, tests: Use es5-shim for IE8 compatibility (Bartosz Dziewoński)
* phpcs.xml: Ignore JS demo files in the PHP distribution (James D. Forrester)
* testsuitegenerator: Do not generate nonsensical tests for 'maxLength' (Bartosz Dziewoński)

## v0.12.5 / 2015-08-18
### Features
* CapsuleMultiSelectWidget: Unbreak $overlay config option (Bartosz Dziewoński)
* FloatingMenuSelectWidget: Introduce, based on TextInputMenuSelectWidget (Bartosz Dziewoński)
* FieldLayout: Throw an error if no widget is provided (Prateek Saxena)
* MessageDialog: Focus primary action button when the dialog opens (Prateek Saxena)

### Styles
* DropdownWidget: Remove additional vertical margin, for consistency (Bartosz Dziewoński)
* FieldLayout: Correct rendering of multiline messages in MediaWiki theme (Bartosz Dziewoński)
* Move base icon/indicator styles out of themes (Roan Kattouw)
* MediaWiki theme: Correct styling of nested buttons (Bartosz Dziewoński)

### Code
* DropdownWidget: Add $overlay config option (Bartosz Dziewoński)
* IconElement, IndicatorElement: Apply base styles to the right selector (Bartosz Dziewoński)
* Add background-repeat: no-repeat; to default icon/indicator styles (Roan Kattouw)
* Remove redundant background rules for icons/indicators (Roan Kattouw)
* Revert "TextInputWidget: Update doc'ed requirements for validate function" (Prtksxna)
* Don't directly use #addEventListener for compatibility with IE 8 (Bartosz Dziewoński)
* demos: Add a demo of the $overlay config option of various widgets (Bartosz Dziewoński)

## v0.12.4 / 2015-08-13
### Styles
* CapsuleMultiSelectWidget: Style tweaks (Ed Sanders)

### Code
* MenuSelectWidget: Call #updateItemVisibility in more cases (Bartosz Dziewoński)
* PopupWidget: Remove 'focusout' handling again, limit to CapsuleMultiSelectWidget (Bartosz Dziewoński)

## v0.12.3 / 2015-08-11
### Deprecations
* [DEPRECATING CHANGE] TextInputWidget: Add getValidity function, deprecate isValid (Prateek Saxena)

### Features
* Add OO.ui.isSafeUrl() to make sure url targets are safe client-side (Kunal Mehta)
* CapsuleMultiSelectWidget: Introduce (Brad Jorsch)
* FieldLayout: Allow displaying errors or notices next to fields (Bartosz Dziewoński)
* HorizontalLayout: Introduce (Bartosz Dziewoński)
* If ProcessDialog#fitLabel is called before dialog is open, defer (Ed Sanders)
* Mixin TitledElement into DropdownInputWidget and FieldLayout (Florian)
* Preserve dynamic state of widgets when infusing (Bartosz Dziewoński)
* TextInputWidget: Don't forget to positionLabel() after it's been unset (Bartosz Dziewoński)

### Styles
* FieldLayout: Kill 'list-style-image' too for messages list (Bartosz Dziewoński)
* PopupToolGroup: Handle popup position on very narrow screens (Ed Sanders)
* ToggleSwitchWidget: Update according to spec (Prateek Saxena)
* MediaWiki, Apex themes: Fix height of frameless toolbar button (Ed Sanders)
* Apex theme: Correct disabled iconed button tool's text colour (Ed Sanders)
* Revert "Dialog: Increase z-index of .oo-ui-dialog to 1000+" (Ed Sanders)

### Code
* ButtonOptionWidget: Make it more difficult to set an inappropriate 'tabIndex' (Bartosz Dziewoński)
* TextInputWidget: Update doc'ed requirements for validate function (Prateek Saxena)
* TextInputWidget: Use getValidity in setValidityFlag (Prateek Saxena)
* Element: DWIM when repeatedly infusing the same node (Bartosz Dziewoński)
* Element: Preserve 'classes' config option through infusion (Bartosz Dziewoński)
* demo: Make compatible with IE 8 (Bartosz Dziewoński)
* build: Exclude irrelevant files from Composer PHP package (Timo Tijhof)
* build: Move phpcs config from composer.json to phpcs.xml (Timo Tijhof)
* build: Output doxygen to "doc" for consistency with other PHP libraries (Kunal Mehta)
* build: Switch svg2png to personal build which fixes long lines (James D. Forrester)
* demos, tests: Use `.parent` instead of `.super` (Bartosz Dziewoński)
* docparser: Add rudimentary error handling (Bartosz Dziewoński)
* doxygen: Use default directory for HTML_OUTPUT (Kunal Mehta)
* tests: Twist the time in comparison tests in a different manner (Bartosz Dziewoński)
* testsuitegenerator: Output the number of generated test cases (Bartosz Dziewoński)

## v0.12.2 / 2015-07-28

### Styles
* Dialog: Increase z-index of .oo-ui-dialog to 1000+ (Prateek Saxena)
* MediaWiki theme: Create new 'accessibility' icon pack (Violetto)

### Code
* SelectWidget: Fix @mixins documentation (Roan Kattouw)
* Update OOjs to v1.1.8 (James D. Forrester)

## v0.12.1 / 2015-07-22

### Features
* PendingElement: Make this actually useful (Roan Kattouw)
* TextInputWidget: Handle required: true better (Bartosz Dziewoński)
* TextInputWidget: Handle type: 'search' better (Bartosz Dziewoński)

### Styles
* PanelLayout: Add some vertical margin when 'padded' and 'framed' (Bartosz Dziewoński)
* MediaWiki, Apex themes: Add 'clear' indicator (Bartosz Dziewoński)
* MediaWiki theme: Align colour of toolbar and dropdown buttons (Prateek Saxena)

### Code
* Window: Compute directionality only when needed (Roan Kattouw)
* Standardise some common comments (Bartosz Dziewoński)
* build: Add clean:demos task (Bartosz Dziewoński)
* build: Add clean:tests task (Bartosz Dziewoński)
* build: Have copyright header reference "OOjs UI" team (Kunal Mehta)
* build: Use new grunt-tyops package rather than local original (James D. Forrester)
* Gruntfile: Fix 'pgk' to 'pkg' and add to typos list (James D. Forrester)
* package.json: Use proper SPDX license notation (Derk-Jan Hartman)

## v0.12.0 / 2015-07-13
### Breaking changes
* [BREAKING CHANGE] SearchWidget: Remove deprecated event re-emission (Ed Sanders)

### Features
* Allow infusion of widgets in other namespaces (Kunal Mehta)
* Only allow construction of classes that extend OO.ui.Element in infusion (Kunal Mehta)
* ButtonInputWidget: Disable generating `<label>` elements (Bartosz Dziewoński)
* FieldLayout: Support HTML help messages through HtmlSnippet (Kunal Mehta)
* RadioSelectWidget: Improve accessibility (Bartosz Dziewoński)
* SelectWidget: Call #chooseItem instead of #selectItem when enter is pressed (Ed Sanders)

### Styles
* MediaWiki, Apex themes: Add a 'notice' icon, same as the 'alert' indicator (James D. Forrester)
* MediaWiki, Apex themes: Re-crush with svgo 0.5.3 (James D. Forrester)
* PopupWidget: Use child selectors to apply rules correctly (Ed Sanders)
* TextInputWidget: Use 'text' cursor for icon/indicator rather than 'pointer' (Bartosz Dziewoński)
* Set Scots to use bold-b and italic-i (baud/italeec) (Ed Sanders)

### Code
* ClippableElement: Fix horizontal clipping in nested scrollable elements (Roan Kattouw)
* ClippableElement: Only call reconsiderScrollbars() if we actually *stopped* clipping (Roan Kattouw)
* Follow-up 3ddb3603: unbreak nesting of autosizing or labeled TextInputWidgets (Roan Kattouw)
* InputWidget: Add additional `<span/>` only for subclasses that need it (Bartosz Dziewoński)
* LookupElement: Disallow editing of readOnly TextInputWidgets (Bartosz Dziewoński)
* History: Re-write into new B/D/F/S/C format and clean up (James D. Forrester)
* build: Don't run phpcs over demos/php (Kunal Mehta)
* build: Update development dependencies (James D. Forrester)
* build: Update watch rules (Kunal Mehta)

## v0.11.8 / 2015-07-07
### Features
* DropdownInputWidget, RadioSelectInputWidget: Consistently call `#cleanUpValue` (Bartosz Dziewoński)
* TextInputWidget: Allow setting the HTML autocomplete attribute (Florian)
* TextInputWidget: Support `rows` option when in multiline mode (Kunal Mehta)
* Make scroll into view work in scrollable divs in Firefox (Roan Kattouw)

### Styles
* MediaWiki theme: Remove support for frameless primary buttons (Bartosz Dziewoński)

### Code
* Use at-ease instead of PHP's @ (Kunal Mehta)
* Use composer's autoloader in exec:phpGenerateJSPHPForKarma (Kunal Mehta)
* build: Don't lint demos/{dist,node_modules,vendor} (Kunal Mehta)
* build: Build demos as part of `grunt build` too (Kunal Mehta)
* build: Build demos as part of `grunt quick-build` (Kunal Mehta)
* build: Only build test files (`build-tests` task) when going to run tests (Bartosz Dziewoński)
* demos: Make self-contained in demos/ directory (Kunal Mehta)
* tests: Provide better output when running infusion test under Karma (Bartosz Dziewoński)

## v0.11.7 / 2015-07-01
### Features
* Element.php: Strip all namespaces from infused PHP widgets (Kunal Mehta)
* OptionWidget: Explicitly set aria-selected to `false` on init (Bartosz Dziewoński)

### Styles
* MediaWiki theme: Add support for frameless primary buttons (Ed Sanders)
* MediaWiki theme: Align and center the advanced icon (Roan Kattouw)
* MediaWiki, Apex themes: Fix styling for frameless process dialog actions (Ed Sanders)

### Code
* Element.php: Add test case to verify class name in infused widgets (Kunal Mehta)
* Element.php: Only variables may be passed by reference (Kunal Mehta)
* Theme.php: Actually make abstract in PHP (Kunal Mehta)
* Theme.php: Add missing doc comments (Kunal Mehta)
* documentation: Use bold in comments instead of h4 (Ed Sanders)

## v0.11.6 / 2015-06-23
### Features
* NumberInputWidget: Don't use `Math.sign()` (Brad Jorsch)
* SelectWidget: Fix invalid escape sequence `\s` (Roan Kattouw)

### Styles
* DropdownWidget: Add white background in MediaWiki theme (Prateek Saxena)
* SelectFileWidget: Add white background in MediaWiki theme (Prateek Saxena)
* MediaWiki theme: Add constructive variants for star and unStar icons (Roan Kattouw)
* MediaWiki theme: Add invert variant to all icons (Roan Kattouw)
* MediaWiki theme: Add progressive variant to ongoingConversation icon (Stephane Bisson)

### Code
* Use `.parent` instead of `.super` (Stephane Bisson)
* build: Updating development dependencies (Kunal Mehta)

## v0.11.5 / 2015-06-16
### Features
* ButtonInputWidget: Render frameless button correctly (Bartosz Dziewoński)
* ComboBoxWidget: Add a getter method for text inputs (Mr. Stradivarius)
* FieldsetLayout: Make rule for disabled label color more precise (Bartosz Dziewoński)
* MenuSelectWidget: Explain what the widget config option is for (Roan Kattouw)
* RadioSelectInputWidget: Unbreak form submission in JS version (Bartosz Dziewoński)

### Styles
* MediaWiki theme: Add destructive variant to check icon (Matthew Flaschen)
* MediaWiki, Apex themes: Add ongoingConversation icon (Matthew Flaschen)

### Code
* build: Configure jsonlint (Kunal Mehta)

## v0.11.4 / 2015-06-09
### Deprecations
* [DEPRECATING CHANGE] Introduce oo.ui.mixin namespace for mixins, and put them src/mixins (C. Scott Ananian)

### Features
* ActionFieldLayout: Add PHP version (Bartosz Dziewoński)
* ButtonWidget: Fix not having tabindex updated when enabled/disabled (Brad Jorsch)
* ClippableElement: Fix behavior of clippables in nested scrollables (Bartosz Dziewoński)
* ClippableElement: Fix behavior of long clippables (Bartosz Dziewoński)
* Dialog: Label in aria terms (Prateek Saxena)
* DropdownWidget: Adjust height to other widgets (Bartosz Dziewoński)
* DropdownWidget: Blank widget when no item is selected (Brad Jorsch)
* Element#reconsiderScrollbars: Preserve scroll position (Bartosz Dziewoński)
* GroupElement: pass correct event name to disconnect() from aggregate() (Roan Kattouw)
* NumberInputWidget: Create, for numeric input (Brad Jorsch)
* NumberInputWidget: Use keydown, not keypress (Brad Jorsch)
* ProcessDialog: Don't center the title label if there's not enough space (Bartosz Dziewoński)
* RadioOptionWidget: Control focus more strictly (Bartosz Dziewoński)
* RadioSelectInputWidget: Create (Bartosz Dziewoński)
* SelectFileWidget: Create (Brad Jorsch)
* SelectWidget: Listen to keypresses and jump to matching items (Brad Jorsch)
* TextInputWidget: Adjust height to other widgets (Bartosz Dziewoński)
* Widget: Add `#supportsSimpleLabel` static property to control `<label>` usage (Bartosz Dziewoński)
* Window: Clear margins for actions in horizontal/vertical groups (Ed Sanders)
* `OOUI\Tag`: Avoid 'Potentially unsafe "href" attribute value' exceptions for relative URLs (Bartosz Dziewoński)

### Styles
* MessageDialog: Remove unintentional action button margin (Bartosz Dziewoński)
* styles: Change gradient mixin syntax to W3C standards' syntax (Volker E)
* styles: Remove obsolete "-ms-linear-gradient" declaration (Volker E)
* Apex theme: Use matching 'lock' and 'unLock' icons (Bartosz Dziewoński)
* MediaWiki and Apex themes: Force background color of `<select>` to white (Ed Sanders)
* MediaWiki and Apex themes: Re-crush SVG files (James D. Forrester)

### Code
* ActionFieldLayout: Dead code removal and cleanup (Bartosz Dziewoński)
* BarToolGroup: Add description and example (Kirsten Menger-Anderson)
* ButtonInputWidget and TextInputWidget: Document and enforce allowed types (Bartosz Dziewoński)
* DropdownInputWidget: Tweak documentation (Bartosz Dziewoński)
* InputWidget#getInputElement: Mark as `@protected`, not `@private` (Bartosz Dziewoński)
* ListToolGroup: Add description and example (Kirsten Menger-Anderson)
* MenuToolGroup: Add description, example and mark private method (Kirsten Menger-Anderson)
* PendingElement: Add description (Kirsten Menger-Anderson)
* PopupTool: Add description and example (Kirsten Menger-Anderson)
* PopupToolGroup: Add description and mark protected methods (Kirsten Menger-Anderson)
* Tool: Add description (Kirsten Menger-Anderson)
* ToolFactory: Add description (Kirsten Menger-Anderson)
* ToolGroup: Add description and mark protected methods (Kirsten Menger-Anderson)
* ToolGroupFactory: Add description (Kirsten Menger-Anderson)
* ToolGroupTool: Add description and example (Kirsten Menger-Anderson)
* Toolbar: Add description (Kirsten Menger-Anderson)
* `OOUI\Element::mixins`: Improve doc comment (Kunal Mehta)
* `OOUI\Tag`: Add basic phpunit tests (Kunal Mehta)
* build: Update MediaWiki codesniffer to 0.2.0 (Kunal Mehta)
* build: Updating development dependencies (James D. Forrester)
* demo: Add 'layout' variable to the consoles (Bartosz Dziewoński)
* demo: Link JS and PHP demos (Bartosz Dziewoński)
* docs: Update name of upstream OOjs project in jsduck documentation (C. Scott Ananian)
* mailmap: Add an additional e-mail for Bartosz per request (James D. Forrester)
* test: Use -p option to phpcs instead of -v (Kunal Mehta)

## v0.11.3 / 2015-05-12
### Features
* BarToolGroup: Don't use "pointer" cursor for disabled tools in enabled toolgroups (Bartosz Dziewoński)
* Tool: Support icon+label in bar tool groups (Bartosz Dziewoński)
* ToolGroupTool: Correct opacity of disabled nested tool group handle (Bartosz Dziewoński)
* ToolGroupTool: Synchronize inner ToolGroup disabledness state (Bartosz Dziewoński)

### Styles
* MediaWiki theme: Add a powerful default text color for tools (Trevor Parscal)
* MediaWiki theme: Adjust quotes icon to match other icons (nirzar)
* MediaWiki theme: Give names to some more toolbar colours (Bartosz Dziewoński)
* MediaWiki theme: Provide all variants of the 'tag' icon (James D. Forrester)
* MediaWiki theme: Rejigger some toolbar coloring (Bartosz Dziewoński)
* MediaWiki theme: Remove box-shadow from nested toolbars (Bartosz Dziewoński)
* MediaWiki theme: Remove unusued toolbar shadow (Trevor Parscal)
* MediaWiki theme: Update button specification (nirzar)

## v0.11.2 / 2015-05-11
### Features
* Don't select lookup items on initialize (Ed Sanders)
* ListToolGroup, MenuToolGroup: Set accelTooltips = false (Bartosz Dziewoński)
* PopupWidget: Add setAlignment (Moriel Schottlender)
* Simplify default action prevention in buttons and forms (Bartosz Dziewoński)
* TextInputWidget: Allow override of #setValidityFlag (Ed Sanders)
* TextInputWidget: Use aria-required along with the required attribute (Prateek Saxena)

### Styles
* TabOptionWidget: Fix disabled styles to not react to hover/select (Ed Sanders)
* Toolbar: Fix shadow styling (Bartosz Dziewoński)
* Toolbar: Remove some useless code from the example (Bartosz Dziewoński)
* Toolbar: Rework example and add 'menu' tool group example (Bartosz Dziewoński)
* MediaWiki theme: Change highlight color for selected menu option (nirzar)
* MediaWiki theme: Polish the toolbar design (nirzar)
* MediaWiki theme: Remove accidentally duplicated styles for SelectWidget (Bartosz Dziewoński)

### Code
* SelectWidget: Mark as @abstract, which it is (Bartosz Dziewoński)
* Toolbar: Move some tweaks from demo to actual implementation (Bartosz Dziewoński)

## v0.11.1 / 2015-05-04
### Features
* Add IndexLayout (Trevor Parscal)
* SelectWidget: Add #selectItemByData method (Moriel Schottlender)
* TextInputWidget: Annotate input validation with aria-invalid (Prateek Saxena)
* TextInputWidget: Don't set 'invalid' flag on first focus, even if invalid (Bartosz Dziewoński)
* TextInputWidget: Support 'required' config option in PHP (Bartosz Dziewoński)

### Styles
* MediaWiki theme: Add 'destructive' variant to block icon (Moriel Schottlender)
* MediaWiki theme: Better vertical alignment of 'search' icon (Ed Sanders)
* MediaWiki theme: Tweak 'search' icon size (Ed Sanders)
* MediaWiki theme: Use variable for transition time and easing function (Prateek Saxena)
* MediaWiki theme: input: Use variable for transition time and easing function (Prateek Saxena)
* MediaWiki theme: radio/checkbox: Use variable for transition time and easing function (Prateek Saxena)
* MediaWiki, Apex themes: Switch icons: clear → cancel, closeInput → clear (Bartosz Dziewoński)
* MediaWiki, Apex themes: Switch over 'magnifyingGlass' icon to be 'search' (James D. Forrester)

### Code
* CardLayout: Fix typo (Kirsten Menger-Anderson)
* LabelElement: Document that label config option can take an HtmlSnippet (Roan Kattouw)
* PopupButtonWidget: Update align config in example (Kirsten Menger-Anderson)
* Remove GridLayout remnants (Bartosz Dziewoński)
* TabOptionWidget: Change link to card layout (Kirsten Menger-Anderson)
* build: Add clean:doc task (Bartosz Dziewoński)
* build: Bump grunt-jscs to latest version (James D. Forrester)
* core: Add OO.ui.debounce() utility (Roan Kattouw)
* demo: Add icons with variants to icons demo (Bartosz Dziewoński)

## v0.11.0 / 2015-04-29
### Breaking changes
* [BREAKING CHANGE] Do not set font-size: 0.8em anywhere in the library (Bartosz Dziewoński)

### Deprecations
* [DEPRECATING CHANGE] Create rtl-ready alignments in PopupWidget (Moriel Schottlender)

### Features
* MediaWiki theme: Adding variants to several icons (Moriel Schottlender)
* TextInputWidget: Allow functions to be passed as 'validate' config option (Bartosz Dziewoński)

### Styles
* TextInputWidget: Styles for 'invalid' flag (Bartosz Dziewoński)

### Code
* Update OOjs to v1.1.7 (James D. Forrester)
* Update jQuery from v1.11.1 to v1.11.3 (James D. Forrester)
* build: Use jquery and oojs from npm instead of embedded lib (Timo Tijhof)

## v0.10.1 / 2015-04-27
### Features
* Correct `tabindex` attribute setting (Bartosz Dziewoński)
* Make toolbars keyboard-accessible (Bartosz Dziewoński)

### Code
* ToggleButtonWidget: Unbreak horizontal alignment (Bartosz Dziewoński)

## v0.10.0 / 2015-04-22
### Breaking changes
* [BREAKING CHANGE] ButtonWidget: remove deprecated `nofollow` option alias (C. Scott Ananian)
* [BREAKING CHANGE] Convert ToggleWidget from a mixin to an abstract class (Bartosz Dziewoński)
* [BREAKING CHANGE] MenuLayout: Reimplement without inline styles (Bartosz Dziewoński)

### Deprecations

### Features
* BarToolGroup: Allow tools with labels instead of icons (Bartosz Dziewoński)
* BookletLayout: Find first focusable element and add focusable utility (Moriel Schottlender)
* ButtonWidget: Remove href to make unclickable when disabled (Bartosz Dziewoński)

### Styles
* MediaWiki, Apex themes: Add viewCompact, viewDetails, visionSimulator icons (Mun May Tee)

### Code
* ButtonInputWidget: Don't double-mixin FlaggedElement (Bartosz Dziewoński)
* ButtonWidget: Remove pointless #isHyperlink property (Bartosz Dziewoński)
* FormLayout: Better document how this works with InputWidgets (Bartosz Dziewoński)
* MenuLayout: Add example (Kirsten Menger-Anderson)
* MenuLayout: Fix initialization order (Bartosz Dziewoński)
* PHP: More useful debugging information on unsafe tag attributes (Chad Horohoe)
* SelectWidget#getTargetItem: Simplify (Ed Sanders)
* Toolbar: Add example (Bartosz Dziewoński)
* demo: Remove VisualEditor references from toolbar demo, use generic icons (Ed Sanders)
* demo: Remove outline controls from outlined BookletLayout demo (Bartosz Dziewoński)
* demo: Simplify ButtonGroupWidget and ButtonSelectWidget examples (Bartosz Dziewoński)

## v0.9.8 / 2015-04-12
### Features
* BookletLayout: Allow focus on any item (Moriel Schottlender)

### Styles
* Apex theme: Correctly position popups in RTL; follows-up v0.9.5 (Moriel Schottlender)
* Apex, MediaWiki themes: Correct or delete unused SVG files (James D. Forrester)

### Code
* Error: Add description (Kirsten Menger-Anderson)
* ProcessDialog: Remove stray `this.$` from documentation code example (Roan Kattouw)
* ProgressBarWidget: Remove spurious styles from CSS output (Bartosz Dziewoński)

* build: Add explicit dependency upon grunt-cli (Kunal Mehta)
* build: Move coverage output from "/dist/coverage" to "/coverage" (Timo Tijhof)
* build: Run lint before build in grunt-test (Timo Tijhof)
* colorize-svg: Generate language-specific rules for images even if equal to default ones (Bartosz Dziewoński)
* colorize-svg: Sprinkle `/* @noflip */` on language-specific rules (Bartosz Dziewoński)
* demo: Change html dir property when direction changes (Moriel Schottlender)

## v0.9.7 / 2015-04-03
### Code
* build: Generate correct paths to fallback images (Bartosz Dziewoński)

## v0.9.5 / 2015-04-02
### Deprecations
* [DEPRECATING CHANGE] Deprecate search widget event re-emission (Ed Sanders)

### Features
* Process: Allow rejecting with single Error (Matthew Flaschen)
* Correctly position popups in RTL (Moriel Schottlender)

### Styles
* ButtonElement: Increase specificity of icon and indicator styles (Bartosz Dziewoński)
* DecoratedOptionWidget: Fix opacity of icons/indicators when disabled (Ed Sanders)

* Balance padding now that focus highlight is balanced (Ed Sanders)
* Remove line height reset for windows (Ed Sanders)
* Restore font family definitions to form elements (Ed Sanders)

* Apex theme: Tweak `check.svg` syntax (Bartosz Dziewoński)
* MediaWiki, Apex themes: Bring in remaining VisualEditor icons (James D. Forrester)
* MediaWiki, Apex themes: Provide an RTL variant for the help icon (James D. Forrester)
* MediaWiki theme: Add vertical spacing to RadioSelectWidget (Ed Sanders)
* MediaWiki theme: Allow intention flags for non-buttons (Andrew Garrett)
* MediaWiki theme: Fix icon opacity for disabled ButtonOptionWidgets (Bartosz Dziewoński)
* MediaWiki theme: Revert "Syncing some button styles with MediaWiki UI" (Bartosz Dziewoński)
* MediaWiki theme: Use checkbox icon per mockups (Bartosz Dziewoński)

### Code
* ActionFieldLayout: Add description and example (Kirsten Menger-Anderson)
* BookletLayout: Add description and example (Kirsten Menger-Anderson)
* IconWidget: Mix in FlaggedElement (Bartosz Dziewoński)
* MenuLayout: Correct documentation (Bartosz Dziewoński)
* OutlineOption: Add description (Kirsten Menger-Anderson)
* PageLayout: Add description (Kirsten Menger-Anderson)
* Process: Add description (Kirsten Menger-Anderson)
* StackLayout: Add description and example (Kirsten Menger-Anderson)
* Choose can't emit with a null item (Ed Sanders)
* Refactor icon handling again (Bartosz Dziewoński)
* build: Add a 'generated automatically' banner to demo.rtl.css (Bartosz Dziewoński)
* build: Generate prettier task names for 'colorizeSvg' (Bartosz Dziewoński)
* build: Have separate 'cssjanus' target for demo.rtl.css (Bartosz Dziewoński)
* build: Make colorize-svg.js actually work more often (Bartosz Dziewoński)
* build: Properly support LTR/RTL icon versions in colorize-svg.js (Bartosz Dziewoński)
* build: Simplify 'fileExists' task configuration (Bartosz Dziewoński)
* build: Support (poorly) per-language icon versions in colorize-svg.js (Bartosz Dziewoński)
* build: Update grunt-banana-checker to v0.2.1 (James D. Forrester)

## v0.9.4 / 2015-03-25
### Breaking changes

### Deprecations

### Features
* ProcessDialog#executeAction: Don't eat parent's return value (Roan Kattouw)
* Compensate for loss of margin when opening modals (Ed Sanders)
* Make outline controls' abilities configurable (Trevor Parscal)

### Styles
* MediaWiki theme: Reduce thickness of toolbar border (Ed Sanders)

### Code
* ButtonElement: Clarify description (Kirsten Menger-Anderson)
* ButtonElement: Disable line wrapping on buttons (Ed Sanders)
* FieldLayout: Clarify description and mark private methods (Kirsten Menger-Anderson)
* FieldsetLayout: Add description and example (Kirsten Menger-Anderson)
* FormLayout: Add description, example, and mark private method (Kirsten Menger-Anderson)
* Layout: Add description (Kirsten Menger-Anderson)
* LookupElement: Add description and mark private and protected methods (Kirsten Menger-Anderson)
* LookupElement: Fix typo in docs (Bartosz Dziewoński)
* MenuLayout: Reorder styles (Bartosz Dziewoński)
* MenuSectionOptionWidget: Add description and example (Kirsten Menger-Anderson)
* PanelLayout: Add description and example (Kirsten Menger-Anderson)
* SearchWidget: Add description and mark private methods (Kirsten Menger-Anderson)
* TabIndexElement: Mark private method (Kirsten Menger-Anderson)

## v0.9.3 / 2015-03-19
### Features
* LookupElement: Add optional config field for suggestions when empty (Matthew Flaschen)
* ProcessDialog: send an array to showErrors in failed executeAction (Moriel Schottlender)

### Code
* Dialog: Fix links to static properties (Kirsten Menger-Anderson)
* DraggableGroupElement: Clarify description and mark private methods (Kirsten Menger-Anderson)
* Fix code style in `@examples` (Ed Sanders)
* FlaggedElement: Add example and clarify description (Kirsten Menger-Anderson)
* GroupElement: Clarify description (Kirsten Menger-Anderson)
* IndicatorElement: Clarify description (Kirsten Menger-Anderson)
* MenuSelectWidget: Clarify description (Kirsten Menger-Anderson)
* TabIndexedElement: Clarify description (Kirsten Menger-Anderson)
* TitledElement: Clarify description (Kirsten Menger-Anderson)
* Widget: Clarify description (Kirsten Menger-Anderson)
* Window: Clarify description of setDimensions method (Kirsten Menger-Anderson)
* WindowManager: Clarify description and mark private methods (Kirsten Menger-Anderson)
* Update OOjs to v1.1.6 (James D. Forrester)
* Add .mailmap file (Roan Kattouw)
* Add Kirsten to AUTHORS.txt (Roan Kattouw)
* demo: Add one more toolbars demo (Bartosz Dziewoński)

## v0.9.2 / 2015-03-12
### Styles
* Toolbar: Be less aggressive with `white-space: nowrap` (Bartosz Dziewoński)

### Code
* Window: Revert changes from 521061dd (Bartosz Dziewoński)

## v0.9.1 / 2015-03-11
### Features
* PanelLayout: Add `framed` config option (Bartosz Dziewoński)
* TextInputWidget: Use MutationObserver for #onElementAttach support (Bartosz Dziewoński)
* Only prevent default for handled keypresses (Brad Jorsch)

### Styles
* Toolbar: Tighten whitespace on narrow displays (Bartosz Dziewoński)
* MediaWiki theme: Add the progressive variant to the check icon (Prateek Saxena)
* MediaWiki theme: Add warning variant to icon set (Mark Holmquist)
* MediaWiki theme: Add "Wikicon" icons (Mun May Tee)
* MediaWiki theme: Synchronise button styles between OOJS and MW (nirzar)
* MediaWiki theme: Syncing some button styles with MediaWiki UI (kaldari)
* MediaWiki theme: textInputWidget: Update focus state (Prateek Saxena)

### Code
* ActionSet: Add description for events and clarify method descriptions (Kirsten Menger-Anderson)
* ActionSet: Clarify description (Kirsten Menger-Anderson)
* ActionWidget: Clarify description and mark private method (Kirsten Menger-Anderson)
* ActionWidget: Fix bad copy-paste in documentation (Bartosz Dziewoński)
* ButtonElement: Use #setButtonElement correctly (Bartosz Dziewoński)
* ButtonInputWidget: Clarify description of configs and methods (Kirsten Menger-Anderson)
* Dialog: Mark private methods and add description of methods and configs (Kirsten Menger-Anderson)
* InputWidget: Clarify description (Kirsten Menger-Anderson)
* MessageDialog: Add description, example, and mark private methods (Kirsten Menger-Anderson)
* OutlineControlsWidget: Add description (Kirsten Menger-Anderson)
* OutlineSelectWidget: Add description (Kirsten Menger-Anderson)
* ProcessDialog: Add description and example and mark private methods (Kirsten Menger-Anderson)
* TextInputMenuSelectWidget: Add description and mark private methods (Kirsten Menger-Anderson)
* TextInputWidget: Adjust size and label on first focus, too (Bartosz Dziewoński)
* Window: Clarify descriptions of methods and configs (Kirsten Menger-Anderson)
* WindowManager: Documentation typo (Ed Sanders)
* Icon width should only be applied if there is an icon (Moriel Schottlender)
* Remove half-baked touch event handling (Bartosz Dziewoński)
* Remove remnants of window isolation (Bartosz Dziewoński)
* AUTHORS: Add Derk-Jan Hartman (Derk-Jan Hartman)
* build: Implement basic image flipping support in colorize-svg (Bartosz Dziewoński)
* build: Move pre/post 'doc' task into package.json (Timo Tijhof)
* build: Remove obsolete 'build' task from grunt-doc (Timo Tijhof)
* build: Set 'generateExactDuplicates: true' for CSSJanus (Bartosz Dziewoński)
* demo: Fix typo in toolbars demo (Bartosz Dziewoński)
* demo: Load styles before building demo widgets (not asynchronously) (Bartosz Dziewoński)
* demo: Simplify `@media` styles (Bartosz Dziewoński)
* demo: Use popup with head in the toolbars demo (Bartosz Dziewoński)
* jsduck: Add MouseEvent and KeyboardEvent to externals (Timo Tijhof)
* jsduck: Set --processes=0 to fix warnings-exit-nonzero (Timo Tijhof)
* package.json: Bump grunt-svg2png to 0.2.7 (Bartosz Dziewoński)

## v0.9.0 / 2015-03-04
### Breaking changes
* [BREAKING CHANGE] Remove innerOverlay (Ed Sanders)
* [BREAKING CHANGE] TextInputWidget: Remove `icon` and `indicator` events (Bartosz Dziewoński)
* [BREAKING CHANGE] Remove deprecated LookupInputWidget (Bartosz Dziewoński)
* [BREAKING CHANGE] Remove deprecated GridLayout (Bartosz Dziewoński)

### Features
* Move `OO.ui.infuse` to `OO.ui.Element.static.infuse`. (C. Scott Ananian)
* Fake toolbar group nesting (Bartosz Dziewoński)
* Infer retry button action flags from symbolic name (Trevor Parscal)
* InputWidget: Focus checkboxes and radios, too, when the label is clicked (Bartosz Dziewoński)
* ProcessDialog: Dismiss errors on teardown (Moriel Schottlender)

### Styles
* Make icon and indicator container sizes consistent (Ed Sanders)
* Restore previous toolbar items margins and padding (Bartosz Dziewoński)
* Use the correct color for gray buttons (Prateek Saxena)

### Code
* CheckboxInputWidget: Add description and example (Kirsten Menger-Anderson)
* ComboBoxWidget: Add description, example, and mark private methods (Kirsten Menger-Anderson)
* DecoratedOptionWidget: Add description and example (Kirsten Menger-Anderson)
* DropdownInputWidget: Add description, example, and mark private method (Kirsten Menger-Anderson)
* FieldLayout: Fix display of documentation's bulleted list (Kirsten Menger-Anderson)
* GroupWidget and ItemWidget: Mark `private` (Kirsten Menger-Anderson)
* IndicatorWidget: Add description and example (Kirsten Menger-Anderson)
* LabelElement: Don't call constructor twice for ActionFieldLayouts (Roan Kattouw)
* LabelWidget: Add description, example, and mark private method (Kirsten Menger-Anderson)
* PopupElement: Add description (Kirsten Menger-Anderson)
* PopupTool: Tool constructor takes a toolGroup, not a toolbar (Bartosz Dziewoński)
* PopupWidget: Add description, example, and mark private methods (Kirsten Menger-Anderson)
* PopupWidget: Add keydown listener and hide popup on ESC (Prateek Saxena)
* ProgressBar: Add description and example (Kirsten Menger-Anderson)
* RadioInputWidget: Add description and example (Kirsten Menger-Anderson)
* SelectWidget: Add example and link to decorated option widget (Kirsten Menger-Anderson)
* SelectWidget: Marked protected methods and clarified choose/press descriptions (Kirsten Menger-Anderson)
* TextInputWidget: Add description, example, and mark private methods (Kirsten Menger-Anderson)
* ToggleButtonWidget: Add description, example, and mark private method (Kirsten Menger-Anderson)
* ToggleSwitchWidget: Add description, example, and mark private methods (Kirsten Menger-Anderson)
* ToggleWidget: Add description (Kirsten Menger-Anderson)
* Fix invalid use of border shorthand syntax (Timo Tijhof)
* Only modify body class when first/last window opens/closes (Ed Sanders)
* Use only two variables each for each semantic color (Prateek Saxena)
* build: Add disconnect tolerance to karma config (James D. Forrester)
* build: Remove footer override from jsduck (Timo Tijhof)
* demo: Add PopupTool to toolbar demo (Bartosz Dziewoński)
* demo: Call Toolbar#initialize in toolbar demo (Bartosz Dziewoński)
* tests: Add infusion tests (Bartosz Dziewoński)
* tests: Run JS/PHP tests for widgets with required parameters, too (Bartosz Dziewoński)

## v0.8.3 / 2015-02-26
### Features
* Revert "Unbreak form submission in JavaScript" (Bartosz Dziewoński)

## v0.8.2 / 2015-02-26
### Features
* PHP TitledElement: Actually set $this->title (Bartosz Dziewoński)
* PHP PanelLayout: Fix getConfig() for `expanded` config option (Bartosz Dziewoński)

### Code
* testsuitegenerator: Exclude 'text' parameter from tests, like 'content' (Bartosz Dziewoński)
* WindowManager: Don't pass `this` to window factory method (Bartosz Dziewoński)

## v0.8.1 / 2015-02-25
### Deprecations
* [DEPRECATING CHANGE] Rename setPosition to setLabelPosition (Ed Sanders)

### Features
* Allow passing positional parameters inside the config object (Bartosz Dziewoński)
* ComboBox: Use combobox role (Derk-Jan Hartman)
* Element.php: Add "data" property (C. Scott Ananian)
* Element.php: Add "text" configuration option (C. Scott Ananian)
* Element: Add `content` config option, matching PHP side. (C. Scott Ananian)
* FormLayout: Allow adding child layouts via config (Bartosz Dziewoński)
* Implement OO.ui.infuse to reconstitute PHP widgets in client-side JS (C. Scott Ananian)
* Serialize PHP widget state into data-ooui attribute (C. Scott Ananian)
* TextInputWidget: Fix appearance of icons and labels when disabled (Ed Sanders)
* Unbreak form submission in JavaScript (Bartosz Dziewoński)

### Styles
* Set proper spacing between interleaved FieldsetLayouts and FormLayouts (Bartosz Dziewoński)
* MediaWiki theme: Drop unnecessary pseudo-element of CheckboxInputWidget (Timo Tijhof)
* MediaWiki theme: Drop unnecessary pseudo-element of RadioInputWidget (Timo Tijhof)
* MediaWiki theme: Simplify spacing of checkboxes/radios in FieldLayouts (Bartosz Dziewoński)

### Code
* ButtonOptionWidget: Add description (Kirsten Menger-Anderson)
* ButtonSelectWidget: Add description and example (Kirsten Menger-Anderson)
* DraggableElement: Mark private methods and add description to events (Kirsten Menger-Anderson)
* Element.php: Tweak docs (Bartosz Dziewoński)
* Element: Add description for configs and static property (Kirsten Menger-Anderson)
* Error: Fix function name (Bartosz Dziewoński)
* Fix typo: contian → contain (Bartosz Dziewoński)
* FlaggedElement: Add description of event and config option (Kirsten Menger-Anderson)
* Follow-up bade83bfdfc: actually remove ../ (Roan Kattouw)
* IconElement: Add description for config options (Kirsten Menger-Anderson)
* IconElement: Add description of methods (Kirsten Menger-Anderson)
* IndicatorElement: Add description for configs and static properties (Kirsten Menger-Anderson)
* LabelElement: Add description, config description, static property description (Kirsten Menger-Anderson)
* MenuOptionWidget: Add description (Kirsten Menger-Anderson)
* MenuSelectWidget: Add description and mark protected method (Kirsten Menger-Anderson)
* Move toggle() from Widget to Element (Moriel Schottlender)
* OptionWidget: Add description and descriptions of methods (Kirsten Menger-Anderson)
* PopupButtonWidget: Add description and example and mark private method (Kirsten Menger-Anderson)
* Prefer OO.isPlainObject to $.isPlainObject (Bartosz Dziewoński)
* RadioOptionWidget: Add description (Kirsten Menger-Anderson)
* RadioOptionWidget: Make disabling single options work (Bartosz Dziewoński)
* RadioSelectWidget: Add description and example (Kirsten Menger-Anderson)
* Remove '$: this.$' from code examples (Bartosz Dziewoński)
* Remove loop length check (Ed Sanders)
* SelectWidget: Add description for config, methods, events (Kirsten Menger-Anderson)
* TabIndexelement: Add description, example, and mark private method (Kirsten Menger-Anderson)
* TitledElement: Add description and config and static descriptions (Kirsten Menger-Anderson)
* Update OOjs to v1.1.5 (James D. Forrester)
* Work around Safari 8 mis-rendering checkboxes in SVG-only distribution (Bartosz Dziewoński)
* build: Give docparser.rb Ruby 1.9.3 compatibility (Bartosz Dziewoński)
* build: Include 'lib' and 'dist' in jsduck output (Timo Tijhof)
* build: Teach docparser about `@member`, `@see`, and PHP pass-by-reference (`&$foo`). (C. Scott Ananian)
* build: Unbreak docparser.rb (Bartosz Dziewoński)
* build: Use grunt-contrib-copy instead of custom 'copy' task (Timo Tijhof)
* composer.json: Add description field (Kunal Mehta)
* demo: Add disabled RadioInputWidget to demo (Bartosz Dziewoński)
* tests: Add "composer test" command to lint PHP files and run phpcs (Kunal Mehta)
* tests: Reduce timeout in Process test from 100 to 10 (Timo Tijhof)
* tests: Run JS/PHP comparison tests using karma (Bartosz Dziewoński)

## v0.8.0 / 2015-02-18
### Breaking changes
* [BREAKING CHANGE] Make default distribution provide SVG with PNG fallback (Bartosz Dziewoński)

### Deprecations
* [DEPRECATING CHANGE] ButtonWidget: Rename nofollow config option to noFollow (C. Scott Ananian)
* [DEPRECATING CHANGE] TextInputWidget: Deprecate `icon` and `indicator` events (Bartosz Dziewoński)

### Features
* TabIndexedElement: Allow tabIndex property to be null (C. Scott Ananian)
* TextInputWidget: Allow maxLength of 0 in JS (matching PHP) (Bartosz Dziewoński)

### Styles
* MediaWiki theme: Add focus state for frameless button (Prateek Saxena)
* MediaWiki theme: Fix border width for frameless buttons' focus state (Prateek Saxena)
* MediaWiki theme: Resynchronize PHP with JS (Bartosz Dziewoński)
* MediaWiki theme: Use white icons for disabled buttons (Bartosz Dziewoński)

### Code
* ActionSet: Add `@private` to onActionChange method (Kirsten Menger-Anderson)
* ActionSet: Add description and example (Kirsten Menger-Anderson)
* ActionSet: Add description for specialFlags property (Kirsten Menger-Anderson)
* ActionWidget: Add description (Kirsten Menger-Anderson)
* Add missing ButtonInputWidget.less and corresponding mixin (Bartosz Dziewoński)
* ButtonElement: Add description (Kirsten Menger-Anderson)
* ButtonElement: add `protected` to event handlers (Kirsten Menger-Anderson)
* ButtonGroupWidget: Add description and example (Kirsten Menger-Anderson)
* ButtonInputWidget: Add description and example (Kirsten Menger-Anderson)
* ButtonWidget: Add example and link (Kirsten Menger-Anderson)
* Dialog: Add description and example (Kirsten Menger-Anderson)
* DraggableElement: Add description (Kirsten Menger-Anderson)
* DraggableGroupElement: Add description (Kirsten Menger-Anderson)
* DropdownWidget: Add `@private` to private methods (Kirsten Menger-Anderson)
* DropdownWidget: Add description and example (Kirsten Menger-Anderson)
* DropdownWidget: Simplify redundant code (Bartosz Dziewoński)
* Element: Add description (Kirsten Menger-Anderson)
* FieldLayout: Add description (Kirsten Menger-Anderson)
* FieldLayout: Clean up and remove lies (Bartosz Dziewoński)
* FlaggedElement: Add description (Kirsten Menger-Anderson)
* Follow-up 6a6bb90ab: Update CSS file path in eg-iframe.html (Roan Kattouw)
* Follow-up c762da42: fix ProcessDialog error handling (Roan Kattouw)
* GroupElement: Add description (Kirsten Menger-Anderson)
* IconElement: Add description (Kirsten Menger-Anderson)
* IconElement: Add description and fix display of static properties (Kirsten Menger-Anderson)
* IconWidget: Add description and example (Kirsten Menger-Anderson)
* IndicatorElement: Add description (Kirsten Menger-Anderson)
* InputWidget: Add description (Kirsten Menger-Anderson)
* PHP: Remove redundant documentation for getInputElement() (Bartosz Dziewoński)
* Refactor keyboard accessibility of SelectWidgets (Bartosz Dziewoński)
* SelectWidget: Add description (Kirsten Menger-Anderson)
* Some documentation tweaks (Bartosz Dziewoński)
* TextInputWidget: Add missing LabelElement mixin documentation (Ed Sanders)
* TextInputWidget: Don't add label position classes when there's no label (Bartosz Dziewoński)
* TextInputWidget: Hide mixin components when unused (Ed Sanders)
* TextInputWidget: Only put $label in the DOM if needed (Bartosz Dziewoński)
* TextInputWidget: Use margins for moving the label (Ed Sanders)
* Update PHP widgets for accessibility-related changes in JS widgets (Bartosz Dziewoński)
* Use Array.isArray instead of $.isArray (C. Scott Ananian)
* Various fixes to the PHP implementation (C. Scott Ananian)
* Widget: Add description (Kirsten Menger-Anderson)
* Window: Add description (Kirsten Menger-Anderson)
* WindowManager: Add description (Kirsten Menger-Anderson)
* build: Pass RuboCop, customize settings (Bartosz Dziewoński)
* demo: Add horizontal alignment test (Bartosz Dziewoński)
* PHP demo: Correct path to CSS files (Bartosz Dziewoński)
* tests: Update JS/PHP comparison test suite (Bartosz Dziewoński)
* docparser: Add support for `protected` methods (Bartosz Dziewoński)
* docs: Make `@example` documentation tag work (Roan Kattouw)
* tests: Fix the check for properties (Bartosz Dziewoński)
* testsuitegenerator: Only test every pair of config options rather than every triple (Bartosz Dziewoński)

## v0.7.0 / 2015-02-11
### Breaking changes
* [BREAKING CHANGE] Remove window isolation (Trevor Parscal)

### Deprecations
* [DEPRECATING CHANGE] GridLayout should no longer be used, instead use MenuLayout (Bartosz Dziewoński)

### Features
* ButtonWidget: Add `nofollow` option (C. Scott Ananian)
* ButtonWidget: Better handle non-string parameters in setHref/setTarget (C. Scott Ananian)
* PopupWidget: Set $clippable only once, correctly (Bartosz Dziewoński)
* SelectWidget: `listbox` wrapper role, `aria-selected` state on contents (Derk-Jan Hartman)
* TabIndexedElement: Actually allow tabIndex of -1 (Bartosz Dziewoński)
* TextInputWidget: Add required attribute on the basis of required config (Prateek Saxena)
* TextInputWidget: Use aria-hidden for extra autosize textarea (Prateek Saxena)
* ToggleSwitchWidget: Accessibility improvements (Bartosz Dziewoński)

### Styles
* FieldsetLayout: Tweak positioning of help icon (Bartosz Dziewoński)
* Fade in window frames separately from window overlays (Ed Sanders)
* MediaWiki theme: Consistent toggle button `active` state (Bartosz Dziewoński)
* MediaWiki theme: Correct flagged primary button text color when pressed (Bartosz Dziewoński)
* MediaWiki theme: Fix background color for disabled buttons (Prateek Saxena)
* MediaWiki theme: Fix non-clickability of radios and checkboxes (Bartosz Dziewoński)
* MediaWiki theme: Rename `@active` to `@pressed` in button mixins (Prateek Saxena)
* MediaWiki theme: Rename `@highlight` to `@active` (Prateek Saxena)
* MediaWiki theme: Rename active-* variables to pressed-* (Prateek Saxena)
* MediaWiki theme: Use darker color for frameless buttons (Prateek Saxena)
* MediaWiki theme: Use distribution's image type for backgrounds (Bartosz Dziewoński)

### Code
* ButtonWidget: Add documentation (Kirsten Menger-Anderson)
* {Checkbox,Radio}InputWidget: Add missing configuration initialization (Bartosz Dziewoński)
* DraggableGroupElement: Cleanup unreachable code (Moriel Schottlender)
* DraggableGroupElement: Make sure it supports button widgets (Moriel Schottlender)
* DraggableGroupElement: Unset dragged item when dropped (Moriel Schottlender)
* Delete unused src/themes/apex/{raster,vector}.less (Bartosz Dziewoński)
* DropdownInputWidget: Fix undefined variable in PHP (Bartosz Dziewoński)
* DropdownWidget, ComboBoxWidget: Make keyboard-accessible (Bartosz Dziewoński)
* Fix initialisation of window visible (Ed Sanders)
* Fix text input auto-height calculation (Ed Sanders)
* ListToolGroup: Remove hack for jQuery's .show()/.hide() (Bartosz Dziewoński)
* MenuSelectWidget: Codify current behavior of Tab closing the menu (Bartosz Dziewoński)
* MenuSelectWidget: Don't clobber other events when unbinding (Bartosz Dziewoński)
* MenuSelectWidget: Remove dead code (Bartosz Dziewoński)
* OptionWidgets: Make better use of `scrollIntoViewOnSelect` (Bartosz Dziewoński)
* PopupElement: Correct documentation (Bartosz Dziewoński)
* RadioOptionWidget: Make it a `<label />` (Bartosz Dziewoński)
* Refactor clickability of buttons (Bartosz Dziewoński)
* Remove usage of `this.$` and `config.$` (Trevor Parscal)
* Stop treating ApexTheme class unfairly and make it proper (Bartosz Dziewoński)
* TextInputMenuSelectWidget: Correct documentation (Bartosz Dziewoński)
* build: Bump various devDependencies (James D. Forrester)
* demo: Add button style showcase from PHP demo (Bartosz Dziewoński)
* demo: Reorder widgets into somewhat logical groupings (Bartosz Dziewoński)
* demo: Stop inline consoles from generating white space (Bartosz Dziewoński)
* demo: Use properties instead of attributes for `<link>` (Timo Tijhof)
* PHP demo: Add Vector/Raster and MediaWiki/Apex controls (Bartosz Dziewoński)
* PHP demo: Just echo the autoload error message, don't trigger_error() (Bartosz Dziewoński)
* PHP demo: Resynchronize with JS demo (Bartosz Dziewoński)
* History: Fix date typos (James D. Forrester)
* tests: Just echo the autoload error message, don't trigger_error() (Bartosz Dziewoński)
* tools.less: Use distribution's image type and path for background (Prateek Saxena)

## v0.6.6 / 2015-02-04
### Features
* BookletLayout#toggleOutline: Fix to use MenuLayout method (Ed Sanders)
* Remove disabled elements from keyboard navigation flow (Derk-Jan Hartman)
* TextInputWidget: Mostly revert "Don't try adjusting size when detached" (Bartosz Dziewoński)
* Use CSS overriding trick to support RTL in menu layouts (Ed Sanders)

### Styles
* Use standard border colours for progress bars (Ed Sanders)

### Code
* Use css class instead of jQuery .show()/hide()/toggle() (Moriel Schottlender)
* build: Use karma to v0.12.31 (Timo Tijhof)

## v0.6.5 / 2015-02-01
### Code
* ButtonElement: Unbreak 'pressed' state (Bartosz Dziewoński)
* Make BookletLayout inherit from MenuLayout instead of embedding a GridLayout (Ed Sanders)

## v0.6.4 / 2015-01-30
### Features
* Add inline labels to text widgets (Ed Sanders)
* BookletLayout: Make sure there is a page before focusing (Moriel Schottlender)
* DropdownInputWidget: Introduce (Bartosz Dziewoński)
* InputWidget: Resynchronize our internal .value with DOM .value in #getValue (eranroz)
* Seriously work around the Chromium scrollbar bug for good this time (Bartosz Dziewoński)
* TabIndexedElement: Introduce and use (Bartosz Dziewoński)
* TextInputWidget: Accept `maxLength` configuration option (Bartosz Dziewoński)
* MenuLayout: Introduce (Ed Sanders)
* Window#updateSize: Add simpler API (Ed Sanders)

### Styles
* ActionFieldLayout: Add `nowrap` to the button (Moriel Schottlender)
* FieldsetLayout: Add help icon (Moriel Schottlender)
* Fix opening/closing animation on windows (Roan Kattouw)
* OptionWidget: Unbreak 'pressed' state (Bartosz Dziewoński)
* Provide default margins for buttons and other widgets (Bartosz Dziewoński)
* MenuSelectWidget and OptionWidget: Remove the 'flash' feature (Bartosz Dziewoński)
* MediaWiki theme: Adjust ButtonSelectWidget, ButtonGroupWidget highlights (Prateek Saxena)
* MediaWiki theme: Adjust MenuOptionWidget selected state (Bartosz Dziewoński)
* MediaWiki theme: Fix background issues with disabled buttons (Roan Kattouw)
* MediaWiki theme: Reduce size of checkboxes and radio buttons by 20% (Ed Sanders)
* MediaWiki theme: Remove SearchWidget's border now dialogs have outline (Ed Sanders)
* MediaWiki theme: Tweak some more border-radii (Bartosz Dziewoński)
* MediaWiki theme: Unbreak disabled buttons (Bartosz Dziewoński)

### Code
* ButtonOptionWidget: Add the TabIndexedElement mixin (Derk-Jan Hartman)
* InputWidget: Clarify documentation of #getInputElement (Bartosz Dziewoński)
* PopupButtonWidget: Set aria-haspopup to true (Prateek Saxena)
* Remove labelPosition check (Ed Sanders)
* Set input direction in html prop rather than css rule (Moriel Schottlender)
* TextInputWidget: Don't try adjusting size when detached (Bartosz Dziewoński)
* TextInputWidget: Remove superfluous role=textbox (Derk-Jan Hartman)
* ToggleButtonWidget: Set aria-pressed when changing value (Derk-Jan Hartman)
* ToggleWidget: Use aria-checked (Prateek Saxena)
* Twiddle things (Ed Sanders)
* Update OOjs to v1.1.4 and switch to the jQuery-optimised version (James D. Forrester)
* Widget: Set aria-disabled too in #setDisabled (Derk-Jan Hartman)
* AUTHORS: Update for the last six months' work (James D. Forrester)
* build: Bump devDependencies and fix up (James D. Forrester)
* demo: Have multiline text in multiline widgets (Bartosz Dziewoński)
* demo: Remove nonexistent 'align' config option for a DropdownWidget (Bartosz Dziewoński)

## v0.6.3 / 2015-01-14
### Deprecations
* [DEPRECATING CHANGE] LookupInputWidget should no longer be used, instead use LookupElement

### Features
* Add an ActionFieldLayout (Moriel Schottlender)
* Replace old&busted LookupInputWidget with new&hot LookupElement (Bartosz Dziewoński)

### Styles
* dialog: Provide a 'larger' size for things for which 'large' isn't enough (James D. Forrester)
* Synchronize ComboBoxWidget and DropdownWidget styles (Bartosz Dziewoński)
* MediaWiki theme: Adjust toolbar popups' border and shadows (Bartosz Dziewoński)
* MediaWiki theme: Don't use 'box-shadow' to produce thin grey lines in dialogs (Bartosz Dziewoński)

### Code
* Toolbar: Update #initialize docs (Bartosz Dziewoński)
* demo: Switch the default theme from 'Apex' to 'MediaWiki' (Ricordisamoa)

## v0.6.2 / 2015-01-09
### Features
* Clear windows when destroying window manager (Ed Sanders)
* Element: Add support for 'id' config option (Bartosz Dziewoński)
* TextInputWidget: Add support for 'autofocus' config option (Bartosz Dziewoński)

### Styles
* Add 'lock' icon (Trevor Parscal)
* Make `@anchor-size` a LESS variable and calculate borders from it (Ed Sanders)
* MediaWiki theme: Slightly reduce size of indicator arrows (Ed Sanders)
* MediaWiki theme: Remove text-shadow on  button (Prateek Saxena)
* MediaWiki theme: Fix focus state for buttons (Prateek Saxena)
* MediaWiki theme: Add state change transition to checkbox (Prateek Saxena)
* MediaWiki theme: Fix disabled state of buttons (Prateek Saxena)
* MediaWiki theme: Fix overlap between hover and active states (Prateek Saxena)

### Code
* Don't test abstract classes (Bartosz Dziewoński)
* PHP LabelElement: Actually allow non-plaintext labels (Bartosz Dziewoński)
* Synchronize `@abstract` class annotations between PHP and JS (Bartosz Dziewoński)
* WindowManager#removeWindows: Documentation fix (Ed Sanders)
* tests: Don't overwrite 'id' attribute (Bartosz Dziewoński)
* testsuitegenerator.rb: Handle inheritance chains (Bartosz Dziewoński)

## v0.6.1 / 2015-01-05
### Styles
* FieldsetLayout: Shrink size of label and bump the weight to compensate (James D. Forrester)

### Code
* Remove use of `Math.round()` for offset and position pixel values (Bartosz Dziewoński)
* ButtonElement: Inherit all 'font' styles, not only 'font-family' (Bartosz Dziewoński)
* IndicatorElement: Fix 'indicatorTitle' config option (Bartosz Dziewoński)
* Error: Unmark as `@abstract` (Bartosz Dziewoński)
* JSPHP-suite.json: Update (Bartosz Dziewoński)
* build: Update various devDependencies (James D. Forrester)
* readme: Update badges (Timo Tijhof)
* readme: No need to put the same heading in twice (James D. Forrester)

## v0.6.0 / 2014-12-16
### Breaking changes
* [BREAKING CHANGE] PopupToolGroup and friends: Pay off technical debt (Bartosz Dziewoński)

### Features
* Prevent parent window scroll in modal mode using overflow hidden (Ed Sanders)
* ClippableElement: Handle clipping with left edge (Bartosz Dziewoński)

### Styles
* ButtonGroupWidget: Remove weird margin-bottom: -1px; from theme styles (Bartosz Dziewoński)
* MediaWiki theme: RadioInputWidget tweaks (Bartosz Dziewoński)

### Code
* Sprinkle some child selectors around in BookletLayout styles (Roan Kattouw)

## v0.5.0 / 2014-12-12
### Breaking changes
* [BREAKING CHANGE] FieldLayout: Handle 'inline' alignment better (Bartosz Dziewoński)
* [BREAKING CHANGE] Split primary flag into primary and progressive (Trevor Parscal)
* [BREAKING CHANGE] CheckboxInputWidget: Allow setting HTML 'value' attribute (Bartosz Dziewoński)

### Features
* Element.getClosestScrollableContainer: Use 'body' or 'documentElement' based on browser (Prateek Saxena)
* Give non-isolated windows a tabIndex for selection holding (Ed Sanders)
* Call .off() correctly in setButtonElement() (Roan Kattouw)

### Styles
* FieldLayout: In styles, don't assume that label is given (Bartosz Dziewoński)
* PopupWidget: Remove box-shadow rule that generates invisible shadow (Bartosz Dziewoński)
* TextInputWidget: Set vertical-align: middle, like buttons (Bartosz Dziewoński)
* MediaWiki theme: Add hover state to listToolGroup (Trevor Parscal)
* MediaWiki theme: Add radio buttons (Prateek Saxena)
* MediaWiki theme: Add state transition to radio buttons (Prateek Saxena)
* MediaWiki theme: Add thematic border to the bottom of toolbars (Bartosz Dziewoński)
* MediaWiki theme: Copy .theme-oo-ui-outline{Controls,Option}Widget from Apex (Bartosz Dziewoński)
* MediaWiki theme: Extract @active-color variable (Bartosz Dziewoński)
* MediaWiki theme: Improve search widget styling (Trevor Parscal)
* MediaWiki theme: Make button sizes match Apex (Trevor Parscal)
* MediaWiki theme: Use gray instead of blue for select and highlight (Trevor Parscal)
* MediaWiki theme: checkbox: Fix states according to spec (Prateek Saxena)

### Code
* Account for `<html>` rather than `<body>` being the scrollable root in Chrome (Bartosz Dziewoński)
* ClippableElement: 7 is a better number than 10 (Bartosz Dziewoński)
* Don't set line-height of unset button labels (Bartosz Dziewoński)
* FieldLayout: Synchronise PHP with JS (Bartosz Dziewoński)
* FieldLayout: Use `<label>` for this.$body, not this.$element (Bartosz Dziewoński)
* Fix primary button description text (Niklas Laxström)
* GridLayout: Don't round to 1% (Bartosz Dziewoński)
* Kill the Escape keydown event after handling a window close (Ed Sanders)
* RadioInputWidget: Remove documentation lies (Bartosz Dziewoński)
* Temporarily remove position:absolute on body when resizing (Ed Sanders)
* build: Use String#slice instead of discouraged String#substr (Timo Tijhof)
* testsuitegenerator: Actually filter out non-unique combinations (Bartosz Dziewoński)
* README.md: Drop localisation update auto-commits from release notes (James D. Forrester)
* README.md: Point to Phabricator, not Bugzilla (James D. Forrester)

## v0.4.0 / 2014-12-05
### Breaking changes
* [BREAKING CHANGE] Remove deprecated Element#onDOMEvent and #offDOMEvent (Bartosz Dziewoński)
* [BREAKING CHANGE] Make a number of Element getters static (Bartosz Dziewoński)
* [BREAKING CHANGE] Rename BookletLayout#getPageName → #getCurrentPageName (Bartosz Dziewoński)

### Features
* IconElement: Add missing #getIconTitle (Bartosz Dziewoński)

### Styles
* Follow-up I859ff276e: Add cursor files to repo (Trevor Parscal)

### Code
* SelectWidget: Rewrite #getRelativeSelectableItem (Bartosz Dziewoński)
* demo: Don't put buttons in a FieldsetLayout without FieldLayouts around them (Bartosz Dziewoński)

## v0.3.0 / 2014-12-04
### Breaking changes
* [BREAKING CHANGE] ButtonWidget: Don't default 'target' to 'blank' (Bartosz Dziewoński)

### Features
* InputWidget: Update DOM value before firing 'change' event (Bartosz Dziewoński)

### Styles
* MediaWiki theme: Reduce indentation in theme-oo-ui-checkboxInputWidget (Prateek Saxena)

### Code
* Adding DraggableGroupElement and DraggableElement mixins (Moriel Schottlender)
* Remove window even if closing promise rejects (Ed Sanders)
* TextInputWidget: Reuse a single clone instead of appending and removing new ones (Prateek Saxena)
* Fix lies in documentation (Trevor Parscal)
* build: Have grunt watch run 'quick-build' instead of 'build' (Prateek Saxena)

## v0.2.4 / 2014-12-02
### Features
* MessageDialog: Fit actions again when the dialog is resized (Bartosz Dziewoński)
* Window: Avoid height flickering when resizing dialogs (Bartosz Dziewoński)

### Code
* TextInputWidget: Use .css( propertyName, value ) instead of .css( properties) for single property (Prateek Saxena)
* TextInputWidget: Stop adjustSize if the value of the textarea is the same (Prateek Saxena)

## v0.2.3 / 2014-11-26
### Features
* BookletLayout: Make #focus not crash when there are zero pages or when there is no outline (Roan Kattouw)
* Dialog: Only handle escape events when open (Alex Monk)
* Pass original event with TextInputWidget#enter (Ed Sanders)
* MessageDialog: Add Firefox hack for scrollbars when sizing dialogs (Bartosz Dziewoński)
* MessageDialog: Actually correctly calculate and set height (Bartosz Dziewoński)
* Window: Disable transitions when changing window height to calculate content height (Bartosz Dziewoński)

### Code
* Add missing documentation to ToolFactory (Ed Sanders)
* Fix RadioOptionWidget demos (Trevor Parscal)
* RadioOptionWidget: Remove lies from documentation (Trevor Parscal)
* RadioOptionWidget: Increase rule specificity to match OptionWidget (Bartosz Dziewoński)

## v0.2.2 / 2014-11-25
### Features
* MessageDialog: Fit actions after updating window size, not before (Bartosz Dziewoński)
* ProcessDialog, MessageDialog: Support iconed actions (Bartosz Dziewoński)

### Styles
* Remove padding from undecorated option widgets (Ed Sanders)

### Code
* LabelWidget: Add missing documentation for input configuration option (Ed Sanders)
* MessageDialog: Use the right superclass (Bartosz Dziewoński)
* build: Add .npmignore (Timo Tijhof)

## v0.2.1 / 2014-11-24

### Features
* Add focus method to BookletLayout (Roan Kattouw)
* Start the window opening transition before ready, not after (Roan Kattouw)

### Code
* LabelElement: Kill inline styles (Bartosz Dziewoński)
* Add missing History.md file now we're a proper repo (James D. Forrester)
* readme: Update introduction, badges, advice (James D. Forrester)
* composer: Rename package to 'oojs-ui' and require php 5.3.3 (Timo Tijhof)

## v0.2.0 / 2014-11-17
* First versioned release

## v0.1.0 / 2013-11-13
* Initial export of repo
