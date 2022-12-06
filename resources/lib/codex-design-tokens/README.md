# Codex design tokens

This package contains the Codex design tokens and tools needed to build them. These tokens express
the visual style of the Codex design system.

## Using tokens
The tokens are exported as style variables, and are available in CSS, Less and SASS.

### CSS
```css
@import '@wikimedia/codex-design-tokens/theme-wikimedia-ui.css';

.some-class {
	color: var( --color-subtle );
}
```

### Less
```less
@import ( reference ) '@wikimedia/codex-design-tokens/theme-wikimedia-ui.less';

.some-class {
	color: @color-subtle;
}
```

### SASS
```scss
@import '@wikimedia/codex-design-tokens/theme-wikimedia-ui.scss';

.some-class {
	color: $color-subtle;
}
```

## Token documentation
For more information about the tokens, including previews of the tokens' values and information
about how this package is structured, see the
[section about tokens on the documentation website](https://doc.wikimedia.org/codex/latest/design-tokens/overview.html).

## Building the tokens
You will need to build the tokens in this package to be able to build the `codex`
and `codex-docs` packages, and to run their development modes. To build the tokens, run
`npm run build` in the `packages/codex-design-tokens` directory (or run
`npm run -w @wikimedia/codex-design-tokens build` in the root directory).

### Build products
The build process outputs the following files in the `dist/` directory:
- `theme-wikimedia-ui.css`: The tokens as CSS variables (e.g. `--color-placeholder: #72777d;`)
- `theme-wikimedia-ui.less`: The tokens as Less variables (e.g. `@color-placeholder: #72777d;`)
- `theme-wikimedia-ui.scss`: The tokens as SASS variables (e.g. `$color-placeholder: #72777d;`)
- `theme-wikimedia-ui.json`: A JSON structure with detailed data about each token
