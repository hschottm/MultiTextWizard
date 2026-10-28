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


## Contao 5.7 LTS

Requires PHP 8.3+ and Contao 5.7.x. Tested against Contao 5.7.15.
Install the development branch with `composer require hschottm/contao-multitextwizard:dev-contao57`.
The Contao Manager plugin registers the bundle and the `multitextWizard` input type automatically.
Run `vendor/bin/contao-console contao:migrate` after adding a DCA field that needs a database column.

The widget stores a two-dimensional array (rows, then zero-based column indexes); existing serialized values and the DCA input type are preserved. `columns` accepts a column-definition array or a positive column count; `labels` can supply separate labels. Column options include `label`, `name` (CSS class), `mandatory`, `rgxp`, `unique`, `maxlength`, `width` and `style`. Explicit column settings override global defaults, independently of DCA option order. Uniqueness is checked after core value normalization.

Rendering uses `be_multitext.html.twig`; existing custom `.html5` overrides need migration. Values, labels, button titles and errors are escaped by Twig. Row actions use native JavaScript and real buttons, work after Turbo navigation, and keep field names/IDs synchronized. Changes are saved only when submitting the backend form; the obsolete GET-based database updates have been removed. JavaScript is required for row editing. Custom `buttonTitles` remain supported, with German and English defaults.

No Contao parent method bodies are copied: individual cells are validated by `Widget::validator()`.

## Checks

- `MULTITEXT_TEST_AUTOLOAD=/path/to/vendor/autoload.php php tests/run.php`: actual Contao classes and Twig, isolated application services.
- `node tests/javascript.cjs`: row commands, boundary moves, field indexing and repeat initialization.
- `MULTITEXT_TEST_APP=/path/to/contao php tests/integration.php`: real Contao kernel in a separate temporary cache, bundle registration and template rendering; no database writes.

Row action icons are bundled SVGs from Lucide 0.468.0 (https://github.com/lucide-icons/lucide/tree/0.468.0). License notices are included in `src/Resources/public/icons/lucide/LICENSE`. No CDN or icon JavaScript runtime is required.
