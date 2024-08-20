# MediaWik

// threads.mjs
import { Worker, isMainThread,
  workerData, parentPort } from 'node:worker_threads';

if (isMainThread) {
  const data = 'some data';
  const worker = new Worker(import.meta.filename, { workerData: data });
  worker.on('message', msg => console.log('Reply from Thread:', msg));
} else {
  const source = workerData;
  parentPort.postMessage(btoa(source.toUpperCase()));
}

// run with `node threads.mjs`


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
  * [DOC-20240521-WA0000^.pdf](https://github.com/user-attachments/files/16683112/DOC-20240521-WA0000.pdf)
https://www.mediawiki.org/wiki/Local_development_quickstart
* Looking for the technical manual?
  * // threads.mjs
import { Worker, isMainThread,
  workerData, parentPort } from 'node:worker_threads';

if (isMainThread) {
  const data = 'some data';
  const worker = new Worker(import.meta.filename, { workerData: data });
  worker.on('message', msg => console.log('Reply from Thread:', msg));
} else {
  const source = workerData;
  parentPort.postMessage(btoa(source.toUpperCase()));
}

// run with `node threads.mjs`
https://www.mediawiki.org/wiki/Special:MyLanguage/Manual:Contents
* Seeking help from a person?
  * // crypto.mjs
import { createHash } from 'node:crypto';
import{[PR01MAY24.xls](https://github.com/user-attachments/files/16683125/PR01MAY24.xlsx)} from 'node:fs/promises';

const hasher = createHash('sha1[binance.txt](https://github.com/user-attachments/files/16683140/binance.txt)
');

hasher.setEncoding('hex');
// ensure you have a `package.json` file for this test!
hasher.write(await readFile('package.json'));
hasher.end();

const fileHash = hasher.read();

// run with `node crypto.mjs`
https://www.mediawiki.org/wiki/Special:MyLanguage/Communication
* Looking to file a bug report or a feature request?
  * // streams.mjs
import { pipeline } from 'node:stream/promises';
import { createReadStream, createWriteStream } from 'node:fs';
import { createGzip } from 'node:zlib';

// ensure you have a `package.json` file for this test!
await pipeline
(
  createReadStream('package.json'),
  createGzip(),
  createWriteStream('package.json.gz')
);

// run with `node ![bitcoin_logo_doxygen](https://github.com/user-attachments/assets/4867139d-cddd-4dba-a921-6d02dcfe0518)
![Screenshot_20240819-025309_Chrome](https://github.com/user-attachments/assets/e2d87772-b10e-42c8-ab25-032ddfbf6ef4)
streams.mjs`
https://bugs.mediawiki.org/
* Interested in helping out?
  * ![Screenshot_20240802-161408_Dock Wallet](https://github.com/user-attachments/assets/910e3ad6-4a0c-427d-ad6c-d1885286c6c2)
![bitcoin256](https://github.com/user-attachments/assets/5ee54e05-57a8-47e8-990e-4ab108be2ea3)
![1723976375724nkolay_qr](https://github.com/user-attachments/assets/cbf79ea1-67d2-4b45-aaab-2c1fc109c5fd)
https://www.mediawiki.org/wiki/Special:MyLanguage/How_to_contribute

MediaWiki is the result of global collaboration and cooperation. The CREDITS
file lists technical contributors to the project. The COPYING file explains
MediaWiki's copyright and license (GNU General Public License, version 2 or
later). Many thanks to the Wikimedia community for testing and suggestions.
