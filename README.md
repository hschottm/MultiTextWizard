# MultiTextWizard
contao-multitextwizard is a backend widget that can be used to add an array of text input fields to Contao backend forms
[![Latest Version on Packagist](http://img.shields.io/packagist/v/hschottm/contao-textwizard.svg?style=flat)](https://packagist.org/packages/hschottm/contao-textwizard)
[![Installations via composer per month](http://img.shields.io/packagist/dm/hschottm/contao-textwizard.svg?style=flat)](https://packagist.org/packages/hschottm/contao-textwizard)
[![Installations via composer total](http://img.shields.io/packagist/dt/hschottm/contao-textwizard.svg?style=flat)](https://packagist.org/packages/hschottm/contao-textwizard)

# contao-multitextwizard
Contao backend widget for text list input

contao-textwizard is a backend widget that can be used to add and edit an array of text input fields to Contao backend forms.

![textwizard](https://user-images.githubusercontent.com/873113/49338631-0781c200-f624-11e8-96a6-9567e19a178e.png)

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
