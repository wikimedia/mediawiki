PHP Parallel Lint
=================

This tool check syntax of PHP files faster then serial check with fancier output.

Running parallel jobs in PHP inspired by Nette framework tests.

![Alt text](/tests/examples/example-images/use-success.png?raw=true "Example use of tool")

Install
-------

Just create a `composer.json` file and run the `php composer.phar install` command to install it:

```json
{
    "require-dev": {
        "jakub-onderka/php-parallel-lint": "0.*"
    }
}
```

For colored output install suggested package `jakub-onderka/php-console-highlighter`. After a `composer.json` file include:

```json
{
    "require-dev": {
        "jakub-onderka/php-parallel-lint": "0.*",
        "jakub-onderka/php-console-highlighter": "0.*"
    }
}
```

Example output
--------------

![Alt text](/tests/examples/example-images/use-error.png?raw=true "Example use of tool with error")

Recommended setting for usage with Symfony framework
--------------

For run from command line:

```
$ ./bin/parallel-lint --exclude app --exclude vendor .
```

or setting for ANT:

```xml
<condition property="parallel-lint" value="${basedir}/bin/parallel-lint.bat" else="${basedir}/bin/parallel-lint">
    <os family="windows"/>
</condition>

<target name="parallel-lint" description="Run PHP parallel lint">
    <exec executable="${parallel-lint}" failonerror="true">
        <arg line="--exclude" />
        <arg path="${basedir}/app/" />
        <arg line="--exclude" />
        <arg path="${basedir}/vendor/" />
        <arg path="${basedir}" />
    </exec>
</target>
```

------

[![Downloads this Month](https://img.shields.io/packagist/dm/jakub-onderka/php-parallel-lint.svg)](https://packagist.org/packages/jakub-onderka/php-parallel-lint)
[![Build Status](https://travis-ci.org/JakubOnderka/PHP-Parallel-Lint.svg?branch=master)](https://travis-ci.org/JakubOnderka/PHP-Parallel-Lint)
[![License](https://poser.pugx.org/jakub-onderka/php-parallel-lint/license.svg)](https://packagist.org/packages/jakub-onderka/php-parallel-lint)
