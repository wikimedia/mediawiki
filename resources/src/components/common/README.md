# Components / Common

This directory houses a set of shared Vue.js components for use within various MediaWiki features.
Components defined here are built with the [Codex Design System](https://doc.wikimedia.org/codex/latest/).

## Contents

### What belongs here

Components that:
- Have MediaWiki-specific functionality, depend on MediaWiki APIs, or cover a commonly-used pattern.
- Are shared between multiple features.

Examples: a Lookup that's wired up to search MediaWiki pages, a multi-step Dialog for onboarding
people to a new feature.

### What doesn't belong here

Components that:
- Are only used in a single feature, or can't be generalized for use across features (those belong
  in feature code).
- Are basic and MediaWiki-agnostic (those belong in Codex).

Examples: a grid of Cards that display reading list items, a floating button.

## Maintenance policy

All users of a component share responsibility for maintaining that component. Those doing active
development or making feature requests should expect to do most of the work themselves. If you
cause a bug, please help fix it.

## Contributor Guidelines

### Component design

Components should align with the following guidelines:
- Components should be as generalized and as simple as possible. This will increase the usefulness
  of each component across features. Start simple, then refine or add functionality later as needed.
- A new component, or added functionality to an existing component, should be driven by a product
  need. Don't add a component simply because another version existed in the past, for example.
- A component does not need to exactly replicate the behavior of a component it is replacing, e.g.
  one from a legacy component library. Components can and should evolve over time to meet current
  needs and standards.
- Try to follow existing patterns in this library and in Codex. Examples: component APIs like props
  and slots, naming conventions, and coding patterns. This makes components easier to use and
  maintain.

### Coding standards

- Document the use of each component via code comments in the Vue.js file.
- Follow MediaWiki's [Vue.js guidelines](https://www.mediawiki.org/wiki/Vue.js).
- Use [Codex design tokens](https://doc.wikimedia.org/codex/latest/design-tokens/overview.html) for
  styles.
