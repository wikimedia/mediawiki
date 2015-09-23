# How to Contribute

## Reporting Issues

Submit your issue here: https://github.com/zordius/lightncandy/issues/new

Proper sample input data with template is prefered. If you can provide the LightnCandy version (or commit hash) and some sample code of your setup/helpers it will be better.

## Pull Requests

Pull request is another good way. Before you submit your patch, please ensure you run full tests:

```sh
git submodule init
git submodule update
build/runphp build/gen_test.php
phpunit
```
