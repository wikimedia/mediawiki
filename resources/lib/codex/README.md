# Codex

Codex is a toolkit for building user interfaces within the Wikimedia Design System. Codex contains:
- Vue components, in this package
- Icons, in the `@wikimedia/codex-icons` package
<!-- Once ready, add design tokens to this list -->

For more details, read the [Codex documentation](https://doc.wikimedia.org/codex/main/).

## Using Codex components
To use a component, import it from this package:
```
// If using ES modules:
import { CdxButton } from '@wikimedia/codex';

// or, if using CommonJS:
const { CdxButton } = require( '@wikimedia/codex' );
```

then pass it into the `components` option of your Vue component:

```
<template>
	<div>
		<cdx-button action="progressive" type="primary">
			Click me!
		</cdx-button>
	</div>
</template>

<script>
import { defineComponent } from 'vue';
import { CdxButton } from '@wikimedia/codex';

export default defineComponent( {
	components: {
		CdxButton
	},
	// ...
} );
</script>
```
