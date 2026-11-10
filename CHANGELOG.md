# Changelog

Changes are grouped by release line, newest version first within each line.
This repository-wide history includes all release tags; entries for other lines
do not change the compatibility of the installed package. Historical entries
were reconstructed from tag diffs and the former README release history.

## Contao 5.7 — 1.4.x

### [1.4.4] - 2026-11-10

- Moved release history from the README into a complete changelog with dated entries and links for every release tag.
- Retained usage, update instructions and icon credits in the README. No runtime or dependency changes.

### [1.4.3] - 2026-11-10

- Added the shared SVG package icon, README preview and Contao Manager logo documentation.
- Normalized the package license and source notices to LGPL-3.0-or-later.

### [1.4.2] - 2026-10-28

- Added twelve language catalogues (Czech, Spanish, Persian, French, Italian, Japanese, Dutch, Polish, Russian, Slovenian, Swedish and Turkish), bringing coverage to fourteen languages including English and German.

### [1.4.1] - 2026-10-28

- Reorganized the README into feature history, update instructions and icon credits; clarified automatic widget registration and database migration usage.

### [1.4.0] - 2026-10-28

* Added support for Contao 5.7 and PHP 8.3 or newer.
* Preserved the `multitextWizard` input type and existing stored values.
* Migrated rendering to Twig, with escaped values, labels, button titles and error messages.
* Updated row controls to work after backend navigation and keep field names and IDs synchronized.
* Changed row editing so that changes are saved only when the backend form is submitted.
* Improved column validation: explicit settings take precedence regardless of option order, and uniqueness is checked after value normalization.
* Retained custom `buttonTitles` with German and English defaults.
* Updated row action icons to locally bundled SVGs.
* Refined row action icon rendering and positioning and updated the README screenshot link.

## Contao 4.13 — 1.2.x

### [1.2.1] - 2026-11-10

- Added the shared SVG package icon, README preview and Contao Manager logo documentation.

### [1.2.0] - 2022-08-24

- Updated requirements for Contao 4.13 and PHP 7.4/PHP 8.
- Updated Composer integration and removed obsolete development dependencies.

## Contao 4.4 — 1.0.x

### [1.0.1] - 2026-11-10

- Added the shared SVG package icon, README preview and Contao Manager logo documentation.

### [1.0.0] - 2020-06-19

- Initial tagged Contao 4.4 bundle release with Composer and Contao Manager integration.
- Provided the multitextWizard backend input type for editing rows of text fields and documented its DCA configuration.

## Legacy Contao 3

### [legacy-contao-3-icon-1] - 2026-11-10

- Added the shared SVG package icon, README preview and Contao Manager logo documentation.
- Asset/documentation release for the legacy Contao 3 branch.

[1.4.4]: https://github.com/hschottm/MultiTextWizard/releases/tag/1.4.4
[1.4.3]: https://github.com/hschottm/MultiTextWizard/releases/tag/1.4.3
[1.4.2]: https://github.com/hschottm/MultiTextWizard/releases/tag/1.4.2
[1.4.1]: https://github.com/hschottm/MultiTextWizard/releases/tag/1.4.1
[1.4.0]: https://github.com/hschottm/MultiTextWizard/releases/tag/1.4.0
[1.2.1]: https://github.com/hschottm/MultiTextWizard/releases/tag/1.2.1
[1.2.0]: https://github.com/hschottm/MultiTextWizard/releases/tag/1.2.0
[1.0.1]: https://github.com/hschottm/MultiTextWizard/releases/tag/1.0.1
[1.0.0]: https://github.com/hschottm/MultiTextWizard/releases/tag/1.0.0
[legacy-contao-3-icon-1]: https://github.com/hschottm/MultiTextWizard/releases/tag/legacy-contao-3-icon-1
