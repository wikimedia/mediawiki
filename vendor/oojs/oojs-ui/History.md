# OOjs UI Release History

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
* Update version requirement for mediawiki/at-ease: 1.0.0 → 1.1.0 (Ori Livneh)

## v0.12.8.1 / 2015-09-18 special release
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
* Widget: Add `#supportsSimpleLabel` static property to control <label> usage (Bartosz Dziewoński)
* Window: Clear margins for actions in horizontal/vertical groups (Ed Sanders)
* `OOUI\Tag`: Avoid 'Potentially unsafe "href" attribute value' exceptions for relative URLs (Bartosz Dziewoński)

### Styles
* MessageDialog: Remove unintentional action button margin (Bartosz Dziewoński)
* styles: Change gradient mixin syntax to W3C standards' syntax (Volker E)
* styles: Remove obsolete "-ms-linear-gradient" declaration (Volker E)
* Apex theme: Use matching 'lock' and 'unLock' icons (Bartosz Dziewoński)
* MediaWiki and Apex themes: Force background color of <select> to white (Ed Sanders)
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
* demo: Use properties instead of attributes for <link> (Timo Tijhof)
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
* Account for <html> rather than <body> being the scrollable root in Chrome (Bartosz Dziewoński)
* ClippableElement: 7 is a better number than 10 (Bartosz Dziewoński)
* Don't set line-height of unset button labels (Bartosz Dziewoński)
* FieldLayout: Synchronise PHP with JS (Bartosz Dziewoński)
* FieldLayout: Use <label> for this.$body, not this.$element (Bartosz Dziewoński)
* Fix primary button description text (Niklas Laxström)
* GridLayout: Don't round to 1% (Bartosz Dziewoński)
* Kill the escape keydown event after handling a window close (Ed Sanders)
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
