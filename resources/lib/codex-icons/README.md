# Codex icons

This package contains the Codex icons, and utility functions needed to work with them.
The icons are applying “WikimediaUI” theme visual style of Wikimedia Foundation, following
[Design Style Guide's icon guidelines](https://doc.wikimedia.org/codex/latest/style-guide/icons.html)
## Using icons
To use an icon, import it from this package:
```
// If using ES modules:
import { cdxIconAlert } from '@wikimedia/codex-icons';

// or, if using CommonJS:
const { cdxIconAlert } = require( '@wikimedia/codex-icons' );
```
and pass it to the `CdxIcon` component from the `@wikimedia/codex` package:
`<cdx-icon :icon="cdxIconAlert">`.

For more detailed usage information, see the
[section about icons on the documentation website](https://doc.wikimedia.org/codex/latest/icons/overview.html).

## Building the icons
You will need to build the icons in this package to be able to build the `codex`
and `codex-docs` packages, and to run their development modes. To build the icons, run
`npm run build` in the `packages/codex-icons` directory (or run
`npm run -w @wikimedia/codex-icons build` in the root directory).

### Optimize icons
The SVG icon files are optimized for accessibility and to reduce data sent to client according to
the [Wikimedia SVG coding guidelines](https://www.mediawiki.org/wiki/Manual:Coding_conventions/SVG)
through '.svgo.config.js' based configuration. The `minify:svg` build script, that's also part of
the `build` step, takes care of this. The SVG icon files are part of the repository to simplify the
process of adding new or amending existing icons for both, designers and developers.
Additionally, when transforming the SVG files into CommonJS/ES module/JSON format via
'vite-plugin-raw-svg.ts', we add minor optimizations for client browser-only use instead of the
backwards-compatible SVG file for older [SVG render libraries compliance](https://github.com/svg/svgo/pull/1353).

### Build products
The build process outputs the following files in the `dist/` directory:
- `codex-icons.cjs.js`: All icons and utility functions, in CommonJS format
- `codex-icons.mjs`: All icons and utility functions, in ES module format
- `codex-icons.json`: All icons, in JSON format
- `index.d.ts` and other `.d.ts` files: TypeScript type definitions
- `images/*.svg`: All icons as raw SVG files

## License to adapt and remix
The icons are freely licensed under [Creative Commons Attribution 4.0 (CC BY 4.0)](https://creativecommons.org/licenses/by/4.0/). Please let the Wikimedia Design Systems team know when you're using those icons for projects
in and beyond the Wikimedia movement.