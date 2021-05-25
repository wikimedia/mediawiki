# Contribute to wdio-mediawiki

## Release process

1. Look for any outstanding bugs.
   Especially (unreleased) regressions should be addressed before a release.
   <https://phabricator.wikimedia.org/tag/mediawiki-core-testing/>

2. Create or reset your `release` branch to the latest head of the repository
   ```
   # From mediawiki-core
   git remote update && git checkout -B release -t origin/HEAD
   ```

3. Add release notes to [CHANGELOG.md](./CHANGELOG.md).
   Copy the output of the following as your starting point, and remove any entries that don't affect
   the public API. As well as remove prefixes that are redundant within the context of this package
   (no "selenium:" or "wdio-mediawiki:"). Entries that add, remove, or change public methods should
   be their own bullet point, start with the class or module file that they are a part of,
   for example "Util: Added `foo()` method".
   ```
   # From tests/selenium/wdio-mediawiki/
   export LAST_RELEASE=$(git log --format='%h' --grep='wdio-mediawiki: Release' -n1 .)
   git log --format='* %s.' --no-merges --reverse "${LAST_RELEASE}...HEAD" . | sort | grep -vE '^\* (build|eslint|docs?|tests?):'
   ```

4. Ensure the [README.md](./README.md) documentation is up-to-date with any methods that
   were added, changed, or removed.

5. Set the next release version in [package.json](./package.json).

6. Stage and save your commit, and submit it to Gerrit:
   ```
   # From mediawiki-core
   git add -p
   git commit -m "wdio-mediawiki: Release X.Y.Z"
   git review
   ```

After the release commit has been merged by CI, perform the actual release:

7. ```
   # From tests/selenium/wdio-mediawiki/
   npm publish
   ```
