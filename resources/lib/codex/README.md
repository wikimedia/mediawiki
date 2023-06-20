# Codex

Codex is the design system for Wikimedia. Codex contains:
- Vue and CSS-only components, in this package
- Design tokens, in the `@wikimedia/codex-design-tokens` package
- Icons, in the `@wikimedia/codex-icons` package

For more details, read the [Codex documentation](https://doc.wikimedia.org/codex/latest/).

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
		<cdx-button action="progressive" weight="primary">
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
