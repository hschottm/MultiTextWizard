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

## Release history

### Contao 5.7 support

* Added support for Contao 5.7 and PHP 8.3 or newer.
* Preserved the `multitextWizard` input type and existing stored values.
* Migrated rendering to Twig, with escaped values, labels, button titles and error messages.
* Updated row controls to work after backend navigation and keep field names and IDs synchronized.
* Changed row editing so that changes are saved only when the backend form is submitted.
* Improved column validation: explicit settings take precedence regardless of option order, and uniqueness is checked after value normalization.
* Retained custom `buttonTitles` with German and English defaults.
* Updated row action icons to locally bundled SVGs.

## Updating existing installations

Custom `.html5` template overrides must be converted to Twig. The supplied
template is `be_multitext.html.twig`. JavaScript must be enabled to edit rows.

## Credits

Row action icons are from [Lucide 0.468.0](https://github.com/lucide-icons/lucide/tree/0.468.0).
License notices are included in `src/Resources/public/icons/lucide/LICENSE`.
