# MediaWiki

MediaWiki is a free and open-source wiki software package written in PHP. It
serves as the platform for Wikipedia and the other Wikimedia projects, used
by hundreds of millions of people each month. MediaWiki is localised in over
350 languages and its reliability and robust feature set have earned it a large
and vibrant community of third-party users and developers.

MediaWiki is:

* feature-rich and extensible, both on-wiki and with hundreds of extensions;
* scalable and suitable for both small and large sites;
* simple to install, working on most hardware/software combinations; and
* available in your language.

For system requirements, installation, and upgrade details, see the files
RELEASE-NOTES, INSTALL, and UPGRADE.

* Ready to get started?
  * https://www.mediawiki.org/wiki/Special:MyLanguage/Download
* Setting up your local development environment?
  * https://www.mediawiki.org/wiki/Local_development_quickstart
* Looking for the technical manual?
  * https://www.mediawiki.org/wiki/Special:MyLanguage/Manual:Contents
* Seeking help from a person?
  * https://www.mediawiki.org/wiki/Special:MyLanguage/Communication
* Looking to file a bug report or a feature request?
  * https://bugs.mediawiki.org/
* Interested in helping out?
  * https://www.mediawiki.org/wiki/Special:MyLanguage/How_to_contribute

MediaWiki is the result of global collaboration and cooperation. The CREDITS
file lists technical contributors to the project. The COPYING file explains
MediaWiki's copyright and license (GNU General Public License, version 2 or
later). Many thanks to the Wikimedia community for testing and suggestions.

# <div align="center">
  <a href="https://github.com/webpack/webpack">
    <img width="200" height="200" src="https://webpack.js.org/assets/icon-square-big.svg">
  </a>
</div>

[![npm][npm]][npm-url]
[![node][node]][node-url]
[![tests][tests]][tests-url]
[![coverage][cover]][cover-url]
[![discussion][discussion]][discussion-url]
[![size][size]][size-url]

# val-loader

A webpack loader which executes a given module, and returns the result of the
execution at build-time, when the module is required in the bundle. In this way,
the loader changes a module from code to a result.

Another way to view `val-loader`, is that it allows a user a way to make their
own custom loader logic, without having to write a custom loader.

The target module is called with two arguments: `(options, loaderContext)`

- `options`: The loader options (for instance provided in the webpack config. See the [example](#examples) below).
- `loaderContext`: [The loader context](https://webpack.js.org/api/loaders/#the-loader-context).

## Getting Started

To begin, you'll need to install `val-loader`:

```console
npm install val-loader --save-dev
```

```console
yarn add -D val-loader
```

```console
pnpm add -D val-loader
```

Then add the loader to your `webpack` config. For example:

**target-file.js**

```js
module.exports = (options, loaderContext) => {
  return { code: "module.exports = 42;" };
};
```

**webpack.config.js**

```js
module.exports = {
  module: {
    rules: [
      {
        test: /target-file.js$/,
        use: [
          {
            loader: `val-loader`,
          },
        ],
      },
    ],
  },
};
```

**src/entry.js**

```js
const answer = require("target-file");
```

And run `webpack` via your preferred method.

## Options

- **[`executableFile`](#executableFile)**

### `executableFile`

Type:

```ts
type executableFile = string;
```

Default: `undefined`

Allows to specify path to the executable file

**data.json**

```json
{
  "years": "10"
}
```

**executable-file.js**

```js
module.exports = function yearsInMs(options, loaderContext, content) {
  const { years } = JSON.parse(content);
  const value = years * 365 * 24 * 60 * 60 * 1000;

  return {
    cacheable: true,
    code: "module.exports = " + value,
  };
};
```

**webpack.config.js**

```js
module.exports = {
  module: {
    rules: [
      {
        test: /\.(json)$/i,
        rules: [
          {
            loader: "val-loader",
            options: {
              executableFile: path.resolve(
                __dirname,
                "fixtures",
                "executableFile.js",
              ),
            },
          },
        ],
      },
      {
        test: /\.json$/i,
        type: "asset/resource",
      },
    ],
  },
};
```

## Return Object Properties

Targeted modules of this loader must export a `Function` that returns an object,
or a `Promise` resolving an object (e.g. async function), containing a `code` property at a minimum, but can
contain any number of additional properties.

### `code`

Type:

```ts
type code = string | Buffer;
```

Default: `undefined`
_Required_

Code passed along to webpack or the next loader that will replace the module.

### `sourceMap`

Type:

```ts
type sourceMap = object;
```

Default: `undefined`

A source map passed along to webpack or the next loader.

### `ast`

Type:

```ts
type ast = Array<object>;
```

Default: `undefined`

An [Abstract Syntax Tree](https://en.wikipedia.org/wiki/Abstract_syntax_tree)
that will be passed to the next loader. Useful to speed up the build time if the
next loader uses the same AST.

### `dependencies`

Type:

```ts
type dependencies = Array<string>;
```

Default: `[]`

An array of absolute, native paths to file dependencies that should be watched by webpack for changes.

Dependencies can also be added using [`loaderContext.addDependency(file: string)`](https://webpack.js.org/api/loaders/#thisadddependency).

### `contextDependencies`

Type:

```ts
type contextDependencies = Array<string>;
```

Default: `[]`

An array of absolute, native paths to directory dependencies that should be watched by webpack for changes.

Context dependencies can also be added using [`loaderContext.addContextDependency(directory: string)`](https://webpack.js.org/api/loaders/#thisaddcontextdependency).

### `buildDependencies`

Type:

```ts
type buildDependencies = Array<string>;
```

Default: `[]`

An array of absolute, native paths to directory dependencies that should be watched by webpack for changes.

Build dependencies can also be added using `loaderContext.addBuildDependency(file: string)`.

### `cacheable`

Type:

```ts
type cacheable = boolean;
```

Default: `false`

If `true`, specifies that the code can be re-used in watch mode if none of the
`dependencies` have changed.

## Examples

### Simple

In this example the loader is configured to operator on a file name of
`years-in-ms.js`, execute the code, and store the result in the bundle as the
result of the execution. This example passes `years` as an `option`, which
corresponds to the `years` parameter in the target module exported function:

**years-in-ms.js**

```js
module.exports = function yearsInMs({ years }) {
  const value = years * 365 * 24 * 60 * 60 * 1000;

  // NOTE: this return value will replace the module in the bundle
  return {
    cacheable: true,
    code: "module.exports = " + value,
  };
};
```

**webpack.config.js**

```js
module.exports = {
  module: {
    rules: [
      {
        test: require.resolve("src/years-in-ms.js"),
        use: [
          {
            loader: "val-loader",
            options: {
              years: 10,
            },
          },
        ],
      },
    ],
  },
};
```

In the bundle, requiring the module then returns:

```js
import tenYearsMs from "years-in-ms";

console.log(tenYearsMs); // 315360000000
```

### Modernizr

Example shows how to build [`modernizr`](https://www.npmjs.com/package/modernizr).

**entry.js**

```js
import modenizr from "./modernizr.js";
```

**modernizr.js**

```js
const modernizr = require("modernizr");

module.exports = function (options) {
  return new Promise(function (resolve) {
    // It is impossible to throw an error because modernizr causes the process.exit(1)
    modernizr.build(options, function (output) {
      resolve({
        cacheable: true,
        code: `var modernizr; var hadGlobal = 'Modernizr' in window; var oldGlobal = window.Modernizr; ${output} modernizr = window.Modernizr; if (hadGlobal) { window.Modernizr = oldGlobal; } else { delete window.Modernizr; } export default modernizr;`,
      });
    });
  });
};
```

**webpack.config.js**

```js
const path = require("path");
module.exports = {
  module: {
    rules: [
      {
        test: path.resolve(__dirname, "src", "modernizr.js"),
        use: [
          {
            loader: "val-loader",
            options: {
              minify: false,
              options: ["setClasses"],
              "feature-detects": [
                "test/css/flexbox",
                "test/es6/promises",
                "test/serviceworker",
              ],
            },
          },
        ],
      },
    ],
  },
};
```

### Figlet

Example shows how to build [`figlet`](https://www.npmjs.com/package/figlet).

**entry.js**

```js
import { default as figlet } from "./figlet.js";

console.log(figlet);
```

**figlet.js**

```js
const figlet = require("figlet");

function wrapOutput(output, config) {
  let figletOutput = "";

  if (config.textBefore) {
    figletOutput += encodeURI(`${config.textBefore}\n`);
  }

  output.split("\n").forEach((line) => {
    figletOutput += encodeURI(`${line}\n`);
  });

  if (config.textAfter) {
    figletOutput += encodeURI(`${config.textAfter}\n`);
  }

  return `module.exports = decodeURI("${figletOutput}");`;
}

module.exports = function (options) {
  const defaultConfig = {
    fontOptions: {
      font: "ANSI Shadow",
      horizontalLayout: "default",
      kerning: "default",
      verticalLayout: "default",
    },
    text: "FIGLET-LOADER",
    textAfter: null,
    textBefore: null,
  };

  const config = Object.assign({}, defaultConfig, options);

  return new Promise(function (resolve, reject) {
    figlet.text(config.text, config.fontOptions, (error, output) => {
      if (error) {
        return reject(error);
      }

      resolve({
        cacheable: true,
        code: "module.exports = " + wrapOutput(output, config),
      });
    });
  });
};
```

**webpack.config.js**

```js
const path = require("path");
module.exports = {
  module: {
    rules: [
      {
        test: path.resolve(__dirname, "src", "figlet.js"),
        use: [
          {
            loader: "val-loader",
            options: {
              text: "FIGLET",
            },
          },
        ],
      },
    ],
  },
};
```

## Contributing

Please take a moment to read our contributing guidelines if you haven't yet done so.

[CONTRIBUTING](./.github/CONTRIBUTING.md)

## License

[MIT](./LICENSE)

[npm]: https://img.shields.io/npm/v/val-loader.svg
[npm-url]: https://npmjs.com/package/val-loader
[node]: https://img.shields.io/node/v/val-loader.svg
[node-url]: https://nodejs.org
[tests]: https://github.com/webpack-contrib/val-loader/workflows/val-loader/badge.svg
[tests-url]: https://github.com/webpack-contrib/val-loader/actions
[cover]: https://codecov.io/gh/webpack-contrib/val-loader/branch/master/graph/badge.svg
[cover-url]: https://codecov.io/gh/webpack-contrib/val-loader
[discussion]: https://img.shields.io/github/discussions/webpack/webpack
[discussion-url]: https://github.com/webpack/webpack/discussions
[size]: https://packagephobia.now.sh/badge?p=val-loader
[size-url]: https://packagephobia.now.sh/result?p=val-loader

