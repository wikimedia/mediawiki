Local copies of external schemas that MediaWiki references, so that tests can resolve them
without network access. Each copy lives at the path of its URL without "https://", e.g.
`schema.wikimedia.org/repositories/mediawiki/api/fragments/error-v1.json` is a copy of
https://schema.wikimedia.org/repositories/mediawiki/api/fragments/error-v1.json.
Only `.json` and `.yaml` files are synced.

* Update all copies: `php maintenance/run.php syncExternalSchemas`
* Check for outdated copies: `php maintenance/run.php syncExternalSchemas --check`
* Add or update one copy: `php maintenance/run.php syncExternalSchemas <https:// URL>`

When working on a new or changed schema, edit the copy here and publish the schema
alongside the change.
