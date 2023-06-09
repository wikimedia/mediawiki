Vector Skin
========================

Installation
------------

See <https://www.mediawiki.org/wiki/Skin:Vector>.

### Configuration options

See [skin.json](skin.json).

Development
-----------

### Node version

It is recommended to use [nvm](https://github.com/nvm-sh/nvm) to use the version of node defined
in `.nvmrc` during local development. This ensures consistency amongst development environments.

### Coding conventions

We strive for compliance with MediaWiki conventions:

<https://www.mediawiki.org/wiki/Manual:Coding_conventions>

Additions and deviations from those conventions that are more tailored to this
project are noted at:

<https://www.mediawiki.org/wiki/Reading/Web/Coding_conventions>

### Pre-commit tests

A pre-commit hook is installed when executing `npm install`. By default, it runs
`npm test` which is useful for automatically validating everything that can be
in a reasonable amount of time. If you wish to defer these tests to be executed
by continuous integration only, set the `PRE_COMMIT` environment variable to `0`:

```bash
$ export PRE_COMMIT=0
$ git commit
```

Or more succinctly:

```bash
$ PRE_COMMIT=0 git commit
```

Skipping the pre-commit tests has no impact on Gerrit change identifier hooks.

### Hooks
See [hooks.txt](hooks.txt).
