![Contao MultiTextWizard](logo.svg)

[![Latest Version on Packagist](http://img.shields.io/packagist/v/hschottm/contao-multitextwizard.svg?style=flat)](https://packagist.org/packages/hschottm/contao-multitextwizard)
[![Installations via composer per month](http://img.shields.io/packagist/dm/hschottm/contao-multitextwizard.svg?style=flat)](https://packagist.org/packages/hschottm/contao-multitextwizard)
[![Installations via composer total](http://img.shields.io/packagist/dt/hschottm/contao-multitextwizard.svg?style=flat)](https://packagist.org/packages/hschottm/contao-multitextwizard)

# contao-multitextwizard
Contao backend widget for text list input

contao-multitextwizard is a backend widget that can be used to add and edit an array of text input fields to Contao backend forms.

<img width="912" height="180" alt="multitextwizard" src="https://github.com/user-attachments/assets/9fc5fe88-7fb7-4f6a-96f8-f5251e7f5416" />

## Use in the data container array (DCA)

```php
'authors' => array
(
  'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['authors'],
  'inputType'               => 'multitextWizard',
  'save_callback'           => array(array('tl_extension', 'saveAuthors')),
  'load_callback'           => array(array('tl_extension', 'loadAuthors')),
  'eval'                    => 
    array(
      'mandatory' => false, 
      'doNotSaveEmpty'=>true, 
      'style' => 'width: 100%;', 
      'columns' => array
      (
        array
        (
          'label' => &$GLOBALS['TL_LANG']['tl_extension']['firstname'],
          'width' => '180px'
        ),
        array
        (
          'label' => &$GLOBALS['TL_LANG']['tl_extension']['lastname'],
        )
      ),
      'buttonTitles' => array(
        'rnew' => $GLOBALS['TL_LANG']['tl_extension']['buttontitle_author_new'], 
        'rcopy' => $GLOBALS['TL_LANG']['tl_extension']['buttontitle_author_copy'], 
        'rdelete' => $GLOBALS['TL_LANG']['tl_extension']['buttontitle_author_delete']
      )
    ),
  'sql'                     => "blob NULL"
),
```

The Contao Manager registers the bundle and the `multitextWizard` input type
automatically. After adding a field that requires a database column, update
the database using the Contao Manager or `vendor/bin/contao-console contao:migrate`.

The widget stores rows with zero-based column indexes. The `columns` option
accepts a column-definition array or a positive column count; `labels` can
supply separate labels. Column options include `label`, `name` (CSS class),
`mandatory`, `rgxp`, `unique`, `maxlength`, `width` and `style`.
Explicit column settings override global defaults.

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for changes grouped by release tag.

## Updating existing installations

Custom `.html5` template overrides must be converted to Twig. The supplied
template is `be_multitext.html.twig`. JavaScript must be enabled to edit rows.

## Credits

Row action icons are from [Lucide 0.468.0](https://github.com/lucide-icons/lucide/tree/0.468.0).
License notices are included in `src/Resources/public/icons/lucide/LICENSE`.

## License

This extension is licensed under the GNU Lesser General Public License, version 3
or (at your option) any later version (`LGPL-3.0-or-later`). See [LICENSE](LICENSE).

Bundled third-party assets retain their respective licenses and copyright notices.

## Package icon

The scalable [logo.svg](logo.svg) combines six input fields in two columns with a
magic wand and sparkle on an anthracite background with an orange corner.
For display in the Contao Manager, the logo is maintained separately in
[contao/package-metadata](https://github.com/contao/package-metadata) under
`meta/hschottm/contao-multitextwizard/logo.svg`.