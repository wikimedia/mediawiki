# @wikimedia/codex-design-tokens

Codex is the design system for Wikimedia.

This package contains design tokens that express the visual style of the Codex design system. Tokens are exported as style variables, and are available in CSS, Less and SASS.

See the [design tokens overview documentation](https://doc.wikimedia.org/codex/latest/design-tokens/overview.html) for links to a complete list of tokens with demos and guidelines.

## Usage

See the [installation](https://doc.wikimedia.org/codex/main/using-codex/usage.html#installation) and [using design tokens](https://doc.wikimedia.org/codex/main/using-codex/usage.html#using-design-tokens) documentation for information about how to use Codex design tokens in your application.

### Available Files

The following files are included in the `dist/` directory when you install this package or run the build commands locally:

#### Main Token Files
- `theme-wikimedia-ui.less`: This is the main tokens file used in MediaWiki. All
  tokens are defined as Less variables. Tokens for color-modes or font-modes
  reference CSS custom properties so that their values can be dynamically updated
  at runtime.
- `theme-wikimedia-ui.css`: Defines all design tokens as CSS custom properties. Experimental.
- `theme-wikimedia-ui.scss`: Defines all design tokens as Sass variables. Experimental.
- `theme-wikimedia-ui.js`: Exports all tokens in JavaScript format (ESM). Experimental.
- `theme-wikimedia-ui.json`: JSON data for all tokens from the style-dictionary source.

#### CSS Custom Properties
- `theme-wikimedia-ui-root.css`: This is a CSS file which contains a `:root {}` declaration
  that sets all mode-specific custom properties to their default values. If you want to support
  the various "modes" that Codex provides (alternate token values that can be changed at runtime),
  load this file as a starting point. This file is intended to be used alongside the
  `theme-wikimedia-ui.less` file listed above.

#### Mode Files
These CSS files re-define specific custom properties on the `:root {}` declaration with alternate
values particular to a specific "mode" (a dark color scheme, a larger base font size, etc.). They
can be loaded as needed and are intended to override the default values defined in
`theme-wikimedia-ui-root.css` or `theme-wikimedia-ui.css`.

- `theme-wikimedia-ui-mode-dark.css`: Dark mode color CSS custom properties
- `theme-wikimedia-ui-mode-small.css`: Small font mode CSS custom properties
- `theme-wikimedia-ui-mode-large.css`: Large font mode CSS custom properties
- `theme-wikimedia-ui-mode-x-large.css`: Extra large font mode CSS custom properties

#### Less Mixins
Each of these files defines a specific Less mixin (i.e. `cdx-mode-dark()`) that
loads an alternate set of token values but limits the scope to whatever selector
it is called in.  Use these mixins if you need more granular control over how
modes are used in your application (for example, if only some sections of the
page should change based on user font-mode preference, these mixins could be used).

- `theme-wikimedia-ui-mixin-dark.less`: Dark mode as a Less mixin
- `theme-wikimedia-ui-mixin-light.less`: Light mode as a Less mixin (aka color mode reset)
- `theme-wikimedia-ui-mixin-small.less`: Small font mode as a Less mixin
- `theme-wikimedia-ui-mixin-medium.less`: Medium font mode as a Less mixin (aka font mode reset)
- `theme-wikimedia-ui-mixin-large.less`: Large font mode as a Less mixin
- `theme-wikimedia-ui-mixin-x-large.less`: Extra large font mode as a Less mixin
- `theme-wikimedia-ui-mixin-mode-reset.less`: Reset all modes (both color and font modes) using the `.cdx-mode-reset()` mixin.

## Building from source

For information about building design tokens from the source code, see [BUILDING.md](./BUILDING.md).