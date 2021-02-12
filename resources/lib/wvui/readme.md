# 🧩 Wikimedia Vue UI

Wikimedia Vue UI (WVUI) components – [Wikimedia Foundation's](https://wikimediafoundation.org/)
Vue.js shared user-interface components for Wikipedia, MediaWiki, and beyond. See
**[quick start to contribute](#quick-start)**.

## Table of contents

<!--
    Markdown Preview Enhanced is used to automatically generate the table of contents. You don't
    have to use it but please leave these directives for those who choose to. It helps keeps the
    table of contents in sync.
-->
<!-- prettier-ignore-start -->
<!-- @import "[TOC]" {cmd="toc" depthFrom=2 depthTo=6 orderedList=false} -->
<!-- code_chunk_output -->

- [Table of contents](#table-of-contents)
- [Usage](#usage)
  - [Installation and version history](#installation-and-version-history)
  - [Integration](#integration)
  - [Different builds](#different-builds)
- [Development](#development)
  - [Quick start](#quick-start)
  - [NPM scripts](#npm-scripts)
  - [Docker](#Docker)
    - [Blubber](#blubber)
  - [Storybook workflow](#storybook-workflow)
  - [Vue.js](#vuejs)
    - [Conventions](#conventions)
  - [Templates](#templates)
    - [Conventions](#conventions-1)
  - [TypeScript](#typescript)
    - [Conventions](#conventions-2)
    - [Imports](#imports)
  - [Less & CSS](#less-css)
    - [Conventions](#conventions-3)
    - [Imports](#imports-1)
  - [Testing](#testing)
    - [Unit tests](#unit-tests)
      - [Coverage](#coverage)
  - [Integrated development workflow](#integrated-development-workflow)
  - [Changing dependencies](#changing-dependencies)
  - [Linting and formatting](#linting-and-formatting)
  - [Versioning](#versioning)
    - [Production release](#production-release)
    - [Pre-release (alpha, beta, or release candidate)](#pre-release-alpha-beta-or-release-candidate)
    - [Rolling development release](#rolling-development-release)
  - [Editor and IDE support](#editor-and-ide-support)
    - [Visual Studio Code](#visual-studio-code)
      - [Recommended extensions](#recommended-extensions)
  - [Git strategy](#git-strategy)
    - [Author guidelines](#author-guidelines)
    - [Reviewer guidelines](#reviewer-guidelines)
  - [Known issues](#known-issues)
  - [Compatibility](#compatibility)
    - [JavaScript](#javascript)
    - [Less](#less-1)
- [Performance](#performance)
  - [Bundle composition and source maps](#bundle-composition-and-source-maps)
  - [Bundle size](#bundle-size)
    - [bundlesize configuration](#bundlesize-configuration)
- [Library design goals](#library-design-goals)
- [License (GPL-2.0+)](#license-gpl-20)

<!-- /code_chunk_output -->
<!-- prettier-ignore-end -->

## Usage

### Installation and version history

Install the library and Vue.js v2:

```bash
npm i --save-prefix= vue@2 @wikimedia/wvui
```

WVUI is [semantically versioned](https://semver.org). See the [changelog](changelog.md) for release
notes.

We recommend pinning WVUI to an exact patch version. For example:

```json
  …,
  "dependencies": {
    "…": "…",
    "@wikimedia/wvui": "1.2.3",
    "…": "…"
  }
  …,
```

> WVUI is semantically versioned but bugs occasionally slip through. They're easier for consumers to
> identify when upgrades are tracked deliberately via package.json. If
> [semver ranges](https://docs.npmjs.com/misc/semver) are used instead, like `"^1.2.3"`, only the
> verbose and noisy package-lock.json will change on an upgrade which may go unnoticed.
> Additionally, new features are easier to consider and socialize at upgrade time when minor / major
> version upgrades are intentional and reflected in package.json.

> The recommendation to use exact patch versions like `"1.2.3"` may seem pedantic but if a project
> specifies dependencies with looser versioning instead, that project will be at the mercy of its
> dependencies instead of in control of them.

### Integration

The following example demonstrates an integration with the Vue root App that has access to the
entire WVUI component library and styles:

```html
<!-- App.vue -->
<template>
	<wvui-button>Hello WVUI</wvui-button>
</template>

<script lang="ts">
	import components from "@wikimedia/wvui";
	import "@wikimedia/wvui/dist/wvui.css";

	export default {
		name: "App",
		components, // App can compose any WVUI component.
	};
</script>
```

```ts
// index.ts
import Vue from "vue";
import App from "./App.vue";

new Vue({
	el: "#app",
	components: { App },
	render: (createElement) => createElement(App),
});
```

### Different builds

There is currently one bundle available:

-   **Combined**: the complete library. This bundle is the simplest to use because it contains all
    code but is not performant if only part is used or if different parts should be loaded at
    different times. ⚠️ This chunk is standalone and should not be loaded with split chunks.

    -   **wvui**.js/css: the complete library, excluding icons, and default export. No other chunks
        required unless additional icons not referenced by the core library are used.

    -   **wvui-icons**.js: the complete iconography (optional).

Each chunk is side-effect free. All chunks are fully compiled ES5 / CSS and require a Vue.js
runtime. See [peerDependencies](package.json).

See the [performance section](#performance) for related topics.

## Development

### Quick start

Get running on your host machine quickly with:

```bash
npm install
npm start
```

(See **[below](#docker)** to get setup with Docker instead)

### Docker

WVUI comes with a docker configuration for local development.

> Using Docker is not necessary, but strongly suggested. See **[quick start](#quick-start)** for
> developing without Docker. Containerizing WVUI with Docker makes it easy to have a standard,
> shared environment for local development among developers, as well as integration with automated
> CI pipelines.

To get started:

1. Install Docker and [Docker Compose](https://docs.docker.com/compose/install/).
2. Build Docker images

```bash
docker-compose build --build-arg UID=$(id -u) --build-arg GID=$(id -g) --build-arg HOST=$(uname -s)
# Build arguments needed so that we own Docker generated files
```

3. Install npm packages from host machine

```bash
npm install
```

4. Startup containers

```bash
docker-compose up
```

#### Container Configuration

WVUI's docker compose configuration will produce 2 separate docker containers each with their own
service: `node` and `storybook`. The rationale behind 2 containers is for separation of concerns, so
each container is responsible for one service only.

`storybook`<br> On container startup, `storybook` will be accessible on localhost:3003. This
container is intended for local development with [Storybook](#storybook-workflow).

`node`<br> On container startup, `node` is by default stopped. This service is for mounting project
files. Execute any ad-hoc commands inside the container ( e.g. any [NPM scripts](#npm-scripts) by
running:

```bash
docker-compose run --rm [node|storybook] npm run [script name]
```

If you need to install additional dependencies after container creation (e.g. adding any modules to
package.json), make sure you run `docker-compose up` again for the changes to take affect.

#### I/O performance on macOS

Docker containers run via Docker Desktop for Mac interact with the host's filesystem via a Hyperkit
hypervisor running in a LinuxKit Virtual Machine. The hypervisor and VM are hidden from the user but
they quickly become visible when performing I/O intensive operations like `npm i`. For example, an
unscientific benchmark has `docker run --rm node npm install` taking over **19 minutes**.

Fortunately, Docker Desktop for Mac supports NFS volumes.
[Jeff Geerling wrote an excellent summary of this issue](https://www.jeffgeerling.com/blog/2020/revisiting-docker-macs-performance-nfs-volumes)
along with a guide to sharing folders via NFS for use with Docker Desktop for Mac. Briefly:

1. `echo "nfs.server.mount.require_resv_port = 0" | sudo tee --append /etc/nfs.conf`
2. `echo "${PWD} -alldirs -mapall=$(id -u):$(id -g) 127.0.0.1" | sudo tee --append /etc/exports`
3. Create `docker-compose.override.yaml` and add the following:

```yaml
version: "3.8"

services:
    node:
        volumes:
            - "nfsmount:/app"

volumes:
    nfsmount:
        driver: local
        driver_opts:
            type: nfs
            o: addr=host.docker.internal,rw,nolock,hard,nointr,nfsvers=3
            device: ":${PWD}"
```

4. Rebuild the `node` container (see [Docker](#Docker))

With the above done, the unscientific benchmark above takes a little over **five minutes**.

#### Blubber

WVUI contains a [blubber.yaml](.pipeline/blubber.yaml) file, for use by the tool
[Blubber](https://wikitech.wikimedia.org/wiki/Blubber). Blubber is developed and used by Wikimedia
as an abstraction layer between a project and the creation of the Docker images that will build,
test, and deploy the project. When WVUI goes through Wikimedia's Jenkins CI pipeline, Blubber will
read the blubber.yaml, generate a Dockerfile, create the image per the blubber configuation, and
execute the command specified in the blubber.yaml `command` attribute. The blubber.yaml file should
be modified if you use Blubber in your CI pipeline. Otherwise, it can be ignored.

### NPM scripts

-   `install` / `i`: install project dependencies.
-   `start`: run [Storybook development workflow](#storybook-workflow).
-   `test` / `t`: build the project and execute all tests. Anything that can be validated
    automatically before publishing runs through this command. See [testing](#testing).
-   `run test:unit`: run the unit tests. Pass `-u` to update all Jest snapshots.
-   `run format`: apply lint fixes automatically where available.
-   `run build`: compile source inputs to bundle outputs under `dist/`.
-   `run doc`: generate all documentation under `doc/`.
-   `version`: increment the version. See [versioning](#versioning).
-   `publish`: publish the version to NPM. See [versioning](#versioning).

Scripts containing `:` delimiters in their names are sub-scripts. They are invoked by the outermost
delimited name (and possibly other scripts). For example, `test:unit` is executed by `test`.

Undocumented scripts are considered internal utilities and not expressly supported workflows.

💡 Tips:

-   Add `--` to pass arguments to the script command. For example, `npm run test:unit -- -u` to
    update snapshots or `npm run build -- -dw` to automatically rebuild a development output.
-   Add `-s` to omit verbose command echoing. For example, `npm -s i` or `npm -s run format`.

[NVM](http://nvm.sh) is recommended to configure the Node.js version used.

```bash
# Install the project's recommended Node.js version. This is a one-time installation command and
# does not need to be run again except when the project's .nvmrc is revised. `nvm use` will print an
# error message if this command needs to be run again.
nvm install "$(<.nvmrc)"

# Configure the current shell's environment to use the recommended Node.js version. This command
# should be run whenever opening a new shell to work on the project _prior_ to executing any of the
# project's NPM scripts, especially `npm install`.
nvm use

# Install the project's development and production dependencies. This is a one-time installation
# command and does not need to be run again except when the project's package.json `dependencies` or
# `devDependencies` are revised.
npm install

# All dependencies are now available. Execute any project scripts as wanted.
```

### Storybook workflow

As the primary development flow WVUI uses
[Storybook](https://storybook.js.org/docs/guides/guide-vue/) which allows developing UI components
in isolation without worrying about specific dependencies and requirements. Storybook uses so called
[stories](https://storybook.js.org/docs/basics/writing-stories/). For each SFC (single file
component) its story should be placed in the same directory:

```
|-- src
    |-- components
        |-- your-component
            |-- YourComponent.vue
            |-- YourComponent.stories.ts
```

Each story represents a single visual state of a component.

WVUI uses different Storybook [addons](https://storybook.js.org/addons/), namely:

-   [Knobs](https://github.com/storybookjs/storybook/tree/master/addons/knobs) that allows you to
    edit component props dynamically.
-   [Actions](https://github.com/storybookjs/storybook/tree/master/addons/actions) to retrieve data
    from event handlers.
-   [a11y](https://github.com/storybookjs/storybook/tree/master/addons/a11y) to analyze
    accessibility issues.
-   [links](https://github.com/storybookjs/storybook/tree/master/addons/links) which allows a
    developer to create links that navigate between different stories.
-   [backgrounds](https://github.com/storybookjs/storybook/tree/master/addons/backgrounds) to change
    background colors inside the preview
-   [viewport](https://github.com/storybookjs/storybook/tree/master/addons/viewport) to display UI
    components in different sizes and layouts
-   [storysource](https://github.com/storybookjs/storybook/tree/master/addons/storysource) to show
    story source in Storybook.

To start developing with Storybook, simply run `npm start` command (see
[NPM scripts](#npm-scripts)). This command will open Storybook in your browser.

### Vue.js

Vue.js Single File Components are used for all runtime components. The [Vue.js template explorer] is
useful for debugging.

#### Conventions

The [Vue.js Style Guide](https://vuejs.org/v2/style-guide) is adhered to where possible.

-   PascalCase multi-word component names are used per the Vue.js Style Guide. Since every component
    is prefixed with `Mw`, all components are multi-word just by keeping that pattern. E.g.: - ✓ Use
    `MwFoo` with a lowercase "w". - ✗ Do _not_ use `MWFoo` with a capital "W". This breaks
    kebab-cased HTML in templates.
-   Avoid making primitive base components complex. Make new components instead.

[vue.js template explorer]: https://template-explorer.vuejs.org

### Templates

#### Conventions

-   Static CSS class names should be included directly in the template while dynamic class names
    should come from a computed property that returns an object (not an array). This computed
    property should be named `rootClasses` for the outermost element.
-   If an element has both static and dynamic class names, the static classes should be listed
    first, then the dynamic classes should be included via `v-bind` on the next line.

### TypeScript

TypeScript is used for all runtime sources. The [TypeScript playground] is useful for debugging.

#### Conventions

-   All top-level file symbols should be fully typed. Seams should not have their types inferred
    because they are most likely to have subtle flaws.
-   All named functions and methods should have inputs and output typed. When functions are fully
    typed, their contents usually can be inferred.
-   Favor type inference for locals rather than explicit typing. Locals are unlikely to have
    incorrect typing assumptions and the verbosity of typing is usually a hindrance.
-   Use TypeScript typing where available, JSDoc typing where not. Avoid typing both as this is
    verbose and the docs may be incorrect.

#### Imports

-   TypeScript supports `import`. For example, `import Vue from 'vue';`.
-   Destructuring is supported. For example, `import { PropType } from 'vue';`. Destructuring can be
    combined with default imports. For example, `import Vue, { PropType } from 'vue';`.
-   According to the TypeScript `paths` and Webpack `alias` configurations, `@` references paths
    relative the source root (`src`) directory. For example,
    `import WvuiButton from '../../src/components/button/Button.vue` may be equivalent to
    `import WvuiButton from '@/components/button/Button.vue`.
-   Vue imports terminate in `.vue`. TypeScript imports are extensionless. A compilation error will
    occur otherwise.

[typescript playground]: https://www.typescriptlang.org/play/

### Less & CSS

Less is used for all runtime styles. The [Less playground] is useful for debugging.

#### Conventions

-   [BEM](http://getbem.com) naming conventions are adhered to where possible.
-   Components are consistently rendered across browsers, orienting on [normalize.css] and
    documented with “Support [affected browsers]: Normalize by …”. We can't expect component
    normalization being available in all places using the library. This may lead to minimal rule
    duplication, depending on application, but that's the lesser evil.
-   All components use a [box-sizing] of `border-box`.
-   Each component should be entirely independent and usable in any context. Parents can specify the
    presentation of their children (for example, `display: flex`) but no component should expect to
    only exist in a given container.
-   Storybook-specific styles are prefixed with sb-.
-   Storybook-specific styles have their own Less files that end in .stories.less.

[normalize.css]: https://github.com/necolas/normalize.css
[box-sizing]: https://developer.mozilla.org/docs/Web/CSS/box-sizing

#### Imports

Several [import options] are available. The two most relevant are:

-   `once`: the default. If no option is specified, the `once` option is implied. Use with care as
    this bundles one full copy of the specified file into the bundle. References are always
    preferred. For example, `@import "foo.less";`.
-   `reference`: When only symbols or mixins are necessary for Less to CSS compilation, use a
    `reference` import. Only the compiled output ships, not the definitions themselves or dead code.
    For example, `@import (reference) "foo.less";`.

Import paths are resolved using [less-loader]:

-   Relative paths are used for project files. For example, `@import ( reference ) './Foo.less';`.
-   Prepend `@/` for paths relative the source root (`src`) directory. For example,
    `@import ( reference ) '@/themes/wikimedia-ui.less';`.
-   Prepend a single `~` for NPM dependency files. For example,
    `@import ( reference ) '~wikimedia-ui-base/wikimedia-ui-base.less';`.

[less playground]: http://lesscss.org/less-preview/
[import options]: http://lesscss.org/features/#import-atrules-feature-import-options
[less-loader]: https://github.com/webpack-contrib/less-loader#imports

### Testing

To run tests, use `npm test` command (see [NPM scripts](#npm-scripts)).

#### Unit tests

-   WVUI uses [Vue Test Utils](https://vue-test-utils.vuejs.org/), the official unit testing utility
    library for Vue.js.
-   WVUI uses [Jest](https://jestjs.io) as a test runner.
-   Tests for every component should be colocated with the component itself:

```
|-- src
    |-- components
        |-- your-component
            |-- YourComponent.vue
            |-- YourComponent.test.ts
```

-   WVUI uses [snapshot testing](https://jestjs.io/docs/en/snapshot-testing), snapshot files are
    colocated with components as well:

```
|-- src
    |-- components
        |-- your-component
            |-- YourComponent.vue      <-- Functional code and test subject
            |-- YourComponent.test.ts  <-- Unit tests
            |-- YourComponent.snap.ts  <-- Jest snapshot rendered component HTML
```

-   WVUI uses `jest-fetch-mock` to mock API calls. Mocks can be disabled and run against live
    servers by setting the environment variable `TEST_LIVE_REQUESTS=true`.

##### Coverage

Coverage reports are generated automatically in the [docs/coverage] directory whenever unit tests
are executed.

Coverage thresholds are configured under [.jest/jest.config.json]. These are lower limits for the
entire repo and, as a convention, the number is rounded down to the nearest 10%. For example, if the
actual repository coverage is 89%, the threshold is configured to 80%. See [Jest documentation] for
details.

⚠️ `./src/entries/*.ts` is excluded from the coverage report and expected to be side-effect free.

[docs/coverage]: docs/coverage
[.jest/jest.config.json]: .jest/jest.config.json
[jest documentation]: https://jestjs.io/docs/en/configuration.html#coveragethreshold-object

### Integrated development workflow

_Example: I want to see my local WVUI library changes live in my app or MediaWiki skin._

Package linking is the primary _integrated_ development workflow for use when isolated development
is impractical. Tight coupling of WVUI to a specific implementation is strongly discouraged.
Nevertheless, it is often the case that changes tested live in the context of a particular use case
are wanted prior to publishing. For example, perhaps a bug only manifests easily in one target.

The steps are:

1. Clone the WVUI repository if you haven't already.
2. Enter the WVUI directory.
3. Install the WVUI dependencies if you haven't already (see [NPM scripts](#npm-scripts)).
4. Note WVUI's directory. For example, `wvuiDir="$PWD"`.
5. Enter your integration project's directory. For example, if you are integrating WVUI into Vector,
   the command might be `cd ~/dev/mediawiki/skins/Vector`. This location should contain a
   package.json with a `@wikimedia/wvui` dependency (either `dependency`, `devDependency`, or
   `peerDependency`).
6. Symbolically link the development WVUI into the integration project via `npm link "$wvuiDir"`
   where `$wvuiDir` is the location of WVUI. This swaps the published production WVUI library for a
   link to your local development copy.
7. Verify the link is correct by seeing where that it resolves to WVUI's location. For example,
   `readlink -m node_modules/@wikimedia/wvui` should match `$wvuiDir`.
8. Watch for changes and produce development build file outputs by executing `npm run build -- -dw`.
9. Perform all development and iteration wanted in WVUI and integration project.
10. Unlink the development WVUI via `npm unlink @wikimedia/wvui`. This deletes the _symlink_ to your
    development copy of WVUI.

The above process seems a little clumsy because it is initially. However, it's quite practical and
becomes easy with practice.

### Changing dependencies

-   Always configure your environment with NVM _prior_ to un/installing dependencies (not necessary
    when using Docker) as these operations modify the NPM lockfile. See [NPM scripts](#npm-scripts)
    for example usage.
-   Obviously, carefully consider any proposed new dependencies. Runtime dependencies that increase
    the bandwidth consumption should be given especial care and implicit dependencies should be
    avoided.
-   When adding or revising NPM dependencies, pin `dependencies` and `devDependencies` to exact
    patch versions for the same reasons pinning WVUI itself to patch version is recommended. See
    [Installation and version history](#installation-and-version-history) for details.
-   Dependencies are not transpiled and must be ES5. Additionally, dependencies must only use
    supported browser APIs.

### Linting and formatting

WVUI uses several linters and _formatters_. The former identify functional issues and the latter
identify nonfunctional presentational inconsistencies such as incorrect indentation. Both support
some measure of fixing or "formatting" problems automatically by executing `npm run format`.

-   [Prettier]: Markdown, JSON, and YAML files are _formatted_ by Prettier. When it comes to
    generating beautiful and extremely consistently styled code, Prettier's ability to accept utter
    garbage code in and automatically apply formatting changes is exceptional, far superior to
    ESLint, and may even change the way you write code. For example, the indentation of braceless
    loops is never misleading once prettified. However, Prettier can never replace ESLint as it
    doesn't support any functional linting, only nonfunctional formatting. ESLint integration and
    additional languages such as TypeScript and JavaScript are supported but currently unused in
    WVUI. See [.prettierrc.json](.prettierrc.json) and [.prettierignore](.prettierignore) for
    configuration.
-   [ESLint]: ESLint is used for linting _and_ formatting JavaScript, TypeScript, and Vue.js files.
    A hierarchy of overrides is used so that extends and rules can be separated. See
    [.eslintrc.json](.eslintrc.json) and [.eslintignore](.eslintignore) for details and
    configuration. An additional configuration is present in
    [dist/.eslintrc.json](dist/.eslintrc.json) for validating that only ES5 is shipped.
-   [Stylelint]: Stylelint is used for linting _and_ formatting Less and Vue.js files. See
    [.stylelintrc.json](.stylelintrc.json) and [.stylelintignore](.stylelintignore) for
    configuration.

[prettier]: https://prettier.io/playground/
[eslint]: https://eslint.org/demo
[stylelint]: https://stylelint.io/

### Versioning

#### Production release

To publish a new release:

1. Checkout the latest master branch: `git checkout master && git pull`.
2. Update the [changelog](changelog.md) with release notes.
3. Commit the changelog.
4. Execute `TYPE=<patch|minor|major> bin/release-prod`.
5. Perform a [rolling development release](#rolling-development-release).

Example commands:

```bash
# Checkout the latest master branch.
git checkout master && git pull

# Review the changes since the last release. For example,
# `git log "$(git describe --tags --abbrev=0)..@" --oneline`.

# Document a new feature and a couple bug fixes since the last release. (Emacs can also be used to
# edit the changelog.)
vim changelog.md

# Stage the changelog.
git add changelog.md

# Commit the changelog.
git commit -m '[docs][changelog] prepare release notes'

# Version, build, and test a release.
TYPE=patch bin/release-prod
```

The NPM scripts are configured to help ensure that only tested artifacts are published on gerrit and
npmjs.com.

> By executing `npm version`, the following scripts are invoked in this order:
>
> 1. `preversion`: test that the workspace contains no uncommitted changes.
> 2. **`version`**: increment the version, clean, build, and test the candidate, commit, and tag the
>    change.
>
> In detail, `version` is a built-in NPM script that increases the package.json's `version` property
> (`patch`, `minor`, or `major`) as specified, commits the result to version control, and adds a Git
> tag. Prior to committing the version bump, clean, build, and test the candidate artifact. See
> `npm help version` for further details.
>
> The `preversion` NPM script, which runs prior to `version`, is defined to test that Git's version
> control state is clean before that happens. No uncommitted changes are allowed! For example,
> imagine if a superfluous file containing a password was unintentionally in the workspace and
> published to npmjs.com.
>
> By executing `npm publish`, the following scripts are invoked in this order:
>
> 1. `prepublishOnly`: push the Git tag to the remote.
> 2. **`publish`**: push the artifacts to npmjs.com as per usual.
>
> Before `publish` is executed, `prepublishOnly` pushes the current commit and tag to the Git
> remote. If the push or publish fail due to connectivity, you should probably call `npm publish`
> directly which will re-push the tag and archive as needed.
>
> Finally, the `publish` script is executed which releases the raw files built into the wild at the
> [npm registry](https://www.npmjs.com). See `npm help publish` for further details.
>
> The intended result is:
>
> -   Uncommitted changes (both modifications and untracked files) are forbidden.
> -   Only clean and tested packages are published.
> -   Git tags are available for prerelease and production releases.
> -   Git tags pushed and NPM artifacts published are always in sync.
> -   NPM's `@latest` tag points to the current stable release and `@next` points to the latest
>     commit.
>
> See also:
>
> -   [NPM scripts](https://docs.npmjs.com/misc/scripts)
> -   [NPM version](https://docs.npmjs.com/cli/version)

#### Pre-release (alpha, beta, or release candidate)

To publish a new alpha, beta, or release candidate, execute
`TYPE=<prerelease|prepatch|preminor|premajor> PRE_ID=<alpha|beta|rc> bin/release-pre`. This will
create a new version commit on the current branch.

> `prerelease` is the safest choice. It always bumps the metadata number and _only_ bumps the patch
> number if a stable version exists. For example, given the current version is a stable v1.2.3,
> `TYPE=prerelease PRE_ID=alpha bin/release-pre` will create `v1.2.4-alpha.0`. Note that both the
> patch is bumped and metadata is added. If executed _again_, note that only the metadata number is
> bumped and the patch number stays the same: `v1.2.4-alpha.1`.
>
> `prerelease` can be slightly incorrect if the next release is known to be a minor or major
> release. In those cases, the correct initial alpha release would be
> `TYPE=preminor PRE_ID=alpha bin/release-pre` (or `premajor`) which would create `v1.3.0-alpha.0`.
> The subsequent alpha release would then be `TYPE=prerelease PRE_ID=alpha bin/release-pre` (note
> the command `TYPE` changes to `prerelease`) which creates `v1.3.0-alpha.1`.

#### Rolling development release

To publish the current `master` `HEAD`, execute `bin/release-dev`. If using the Docker setup,
execute these scripts from outside your Docker container.

Development releases can be installed by consumers via `npm install @wikimedia/wvui@next`. These
releases are useful for integration testing and development as well as for early adopters who don't
wish to build the WVUI library themselves.

### Editor and IDE support

Great workflows often require great tooling and those tools need to be configured. This section
describes how to optimize your editor or IDE for optimal usage.

#### Visual Studio Code

-   Configure your line length to 100. For example, add common widths:
    `"editor.rulers": [ 80, 100 ]`.

##### Recommended extensions

-   [Vetur](https://marketplace.visualstudio.com/items?itemName=octref.vetur) - Enable
    `vetur.experimental.templateInterpolationService` for HTML template type checking in single file
    components.
-   [Prettier](https://marketplace.visualstudio.com/items?itemName=esbenp.prettier-vscode)
-   [ESLint](https://marketplace.visualstudio.com/items?itemName=dbaeumer.vscode-eslint)
-   [stylelint](https://marketplace.visualstudio.com/items?itemName=stylelint.vscode-stylelint)
-   [Code Spell Checker](https://marketplace.visualstudio.com/items?itemName=streetsidesoftware.code-spell-checker) -
    Lower the logging level to informational by setting `cSpell.logLevel` to `"Information"`.
-   [Markdown Preview Enhanced](https://marketplace.visualstudio.com/items?itemName=shd101wyy.markdown-preview-enhanced)

### Git strategy

-   Authors should revise the changelog each commit so this work is not postponed to release.
-   Operating system and editor-specific [files](#files) are not considered.
-   The Git configuration should be precise and accurate like any other part of the codebase. The
    .gitignore file, for instance, should not become cluttered or vague.

#### OS and editor-specific files {#files}

> Different programmers use different editors and IDEs. WVUI will attempt to facilitate different
> workflows, especially in the form of documentation, but will avoid making changes specific to them
> such as ignoring Vim swap files.
>
> OS-specific files such as [.DS_Store](https://wikipedia.org/wiki/.DS_Store) and
> [Thumbs.db](https://wikipedia.org/wiki/Windows_thumbnail_cache) should be excluded by the user's
> global Git configuration as they're unwanted in every repository and not specific to WVUI. See
> [gitignore documentation](https://git-scm.com/docs/gitignore) for details.
>
> _Example:_
>
> 1. Add a global exclusions file by executing
>    `git config --global core.excludesfile '~/.gitignore'` or updating your `~/.gitconfig`
>    manually:
>
> ```gitconfig
> excludesfile = ~/.gitignore
> ```
>
> 2. Always ignore `.DS_Store` files by executing `echo .DS_Store >> ~/.gitignore` or updating your
>    `~/.gitignore` manually:
>
> ```gitignore
> .DS_Store
> ```

#### Author guidelines

The expectations for submitting a patch are:

-   Write your best work.
-   Functional changes compile, run, and pass tests.
-   Established patterns at least within the WVUI repository are considered.
-   Any submitted change is an overall improvement. The rationale is that if a patch is an overall
    improvement, it's obvious to merge. If it's not, why should should it be merged?
-   Smaller patches get better reviews.

#### Reviewer guidelines

-   The goal of code review is to help write great code, not only prevent bad code from being
    written. The distinction is that the former is helping to achieve whereas the latter is focused
    on prevention. Nourishing good ideas is better than extinguishing formative ideas.
-   Be specific when providing constructive feedback. Vague concerns, such as "there are many
    reasons" or "it's more nuanced than that," prevent further discussion and create invisible
    barriers to participation that cannot be overcome. Make your point and allow the author to
    address it. When possible, suggest an approach or reference with your request. The more clearly
    you express the changes you want, the easier it will be for the author to provide.
-   If you as a reviewer are making requests of the author, attempt to match their level of effort
    and timeliness. Everyone is busy and doing their best but differently abled.
-   Be open-minded. New ideas, especially standard ideas that are only new to you, are not
    inherently bad. It's ok to downvote to request improved documentation or clarification but not
    for an education in industry standard practice. You are responsible in part for creating the
    culture you want.

### Known issues

-   `Vue.extend()` is used for the type inference of components. This is anticipated to be replaced
    by `defineComponent()` in the Vue _v3_ Composition API.
-   [Storybook is incompatible with Vue Devtools]. Tap "Open canvas in a new tab" as a workaround.
-   "Download the React DevTools…" is printed to the browser console
    [when running Storybook](https://github.com/storybookjs/storybook/issues/4853).
-   If Storybook encounters an error when booting, it does not launch even after the error is
    resolved.
-   Code that is executed but never used (e.g. JavaScript configuration files or unused exports) is
    considered dead and is shaken out by Webpack on compile. As a result, dead code will not be type
    checked when building the library. All types can be tested manually via
    `npx --no-install tsc --noEmit --incremental false`.
-   The linter doesn't enforce tabs in TypeScript enumerations or module declarations.
-   Renaming test files may cause Jest to still try to open the old file name. In that case consider
    clearing the cache via `npm -s run test:unit -- --clearCache`.

[storybook is incompatible with vue devtools]:
	https://github.com/storybookjs/storybook/issues/1708#issuecomment-630262553

### Compatibility

WVUI uses [Browserslist] to help support and enforce browser compatibility. Supported targets are
configured in [.browserslistrc](.browserslistrc) according to [MediaWiki modern browsers
compatibility]. To see the current list, execute `npx --no-install browserslist`.

[browserslist]: https://github.com/browserslist/browserslist
[mediawiki modern browsers compatibility]:
	https://www.mediawiki.org/wiki/Compatibility#Browser_support_matrix

#### JavaScript

JavaScript build products are linted for ES5 compatibility.

#### Less

Less inputs are linted for compatibility and automatically prefixed for browser vendors according to
the Browserslist config via the [PostCSS][autoprefixer] plugin. The current configuration only adds
vendor prefixes like `-webkit-transition:all 1s; transition:all 1s`, _not_ polyfills. `#rgba` color
syntax, like `#0000` for `transparent`, are also replaced as needed by cssnano. The prefixes used
can be seen by executing `npx --no-install autoprefixer --info`.

[postcss]: https://github.com/postcss/postcss
[autoprefixer]: https://github.com/postcss/autoprefixer
[cssnano]: https://cssnano.co

## Performance

### Bundle composition and source maps

The contents of each bundle generated can be evaluated through its source map. [source-map-explorer]
and [Webpack Bundle Analyzer] are used to generate reports for minified and minified + gzipped
bundle breakdowns. The reports are similar but crosschecking may be useful.

[source-map-explorer]: https://github.com/danvk/source-map-explorer
[webpack bundle analyzer]: https://github.com/webpack-contrib/webpack-bundle-analyzer
[docs/sourcemaps]: docs/sourceMaps

### Bundle size

WVUI uses Webpack for bundling different library entry points into distinct build products or
"bundles". All JavaScript and CSS build product bandwidth performances are tracked and tested with
[bundlesize] and versioned in [bundlesize.config.json]. Reports are generated under
[docs/minGzipBundleSize.txt].

The rule of thumb is: identical data generally compresses well. It is recommended to evaluate
performance using the minified gzipped outputs. For example, some CSS selectors are distant but have
identical rules. This creates a large uncompressed CSS bundle when compiled. However, the compressed
size may be negligible. Use the bundlesize tests to evaluate gzipped sizes before making
optimizations that impede readability.

#### Manual evaluation:

> If a second opinion is wanted, consider using the gzip CLI:
>
> ```bash
> # Individual chunk sizes (min / min+gz).
> ls -1 dist/*.{js,css}|
> sort|
> while IFS= read filename; do
> 	printf \
> 		'%s: %sB / %sB\n' \
> 		"$filename" \
> 		"$(wc -c < "$filename"|numfmt --to=iec-i)" \
> 		"$(gzip -c "$filename"|wc -c|numfmt --to=iec-i)"
> done
>
> # All chunks concatenated (allows maximum possible compression). This makes sense if a request to
> # ResourceLoader will depend on multiple chunks.
> printf \
> 	'%s: %sB / %sB\n' \
> 	"Total" \
> 	"$(cat dist/*.{js,css}|wc -c|numfmt --to=iec-i)" \
> 	"$(cat dist/*.{js,css}|gzip -c|wc -c|numfmt --to=iec-i)"
> ```

[docs/mingzipbundlesize.txt]: docs/minGzipBundleSize.txt
[bundlesize]: https://github.com/siddharthkp/bundlesize
[bundlesize.config.json]: bundlesize.config.json

#### bundlesize configuration

When changing the [bundlesize configuration](bundlesize.config.json):

-   The values in the configuration are upper limits. As a convention, the number is rounded up to
    the nearest tenth of a kibibyte. For example, a new file added of size `4.15 KB` would have its
    initial limit set at `4.2 KB`. Whenever intentional changes causes its limit to increase or
    decrease beyond a tenth of a kibibyte boundary, the size should be revised.
-   bundlesize internally uses Bytes utility which [only supports base-2 units]. Case-insensitive
    decimal [JEDEC notation] is used in the config. This means 1.5 KB or 1.5 kb is 1536 bytes, _not_
    1500 bytes.
-   ⚠️ Warning: values that cannot be parsed are _silently ignored_! When making changes, verify
    that a comparison of two values is printed like `2.54KB < maxSize 2.6KB (gzip)`. If only one
    number is shown (e.g., `2.54KB (gzip)`), the number has been entered incorrectly.
-   ⚠️ Warning: values entered must have a leading units position specified. Sub-one sizes like
    `.5 KB` must be written with a leading zero like `0.5 KB` or they will not be parsed.
-   The bundlesize thresholds specify minified gzipped maximums. Outputs are minified as part of the
    build process and gzip is the most common HTTP compression.

[jedec notation]: https://en.wikipedia.org/wiki/Template:Quantities_of_bytes
[only supports base-2 units]:
	https://github.com/visionmedia/bytes.js#bytesparsestringnumber-value-numbernull

## Library design goals

-   Deploy search to all test wikis before August 31, 2020: frwiktionary, hewiki, ptwikiversity,
    frwiki, euwiki, fawiki.
-   Relevant, modern, efficient, iterative contributor workflows.
-   Delightful user experiences shareable as an NPM package and reusable everywhere with and without
    MediaWiki.
-   Fully typed. Accurate typing improves comprehension for tooling and programmers.
-   [Semantically versioned](https://semver.org).
-   Thoroughly documented for development and usage; everything needed to be productive is in the
    readme.
-   Well tested and robust.

## License (GPL-2.0+)

See [LICENSE](LICENSE).
