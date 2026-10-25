<?php

/** Copyright (c) Helmut Schottmüller; LGPL-3.0-or-later. */
namespace Hschottm\MultiTextWizardBundle;

use Contao\BackendTemplate;
use Contao\StringUtil;
use Contao\Widget;

class MultiTextWizard extends Widget
{
    protected $blnSubmitInput = true;
    protected $strTemplate = 'be_widget';
    protected $strMultitextTemplate = 'be_multitext';
    protected $arrColumns = [];
    protected $arrLabels = [];
    protected $arrMultiErrors = [];

    public function __set($key, $value)
    {
        switch ($key) {
            case 'columns':
                $this->arrColumns = is_array($value) ? array_values($value) : array_fill(0, max(1, (int) $value), []);
                break;
            case 'labels':
                $this->arrLabels = is_array($value) ? array_values($value) : [];
                break;
            case 'value':
                $this->varValue = StringUtil::deserialize($value, true);
                break;
            default:
                parent::__set($key, $value);
        }
    }

    // Resolve defaults at use time so eval option order does not affect columns.
    private function columns(): array
    {
        $columns = $this->arrColumns ?: [[]];
        foreach ($columns as $index => &$column) {
            $column = (is_array($column) ? $column : []) + [
                'label' => $this->arrLabels[$index] ?? $this->strLabel,
                'mandatory' => (bool) $this->mandatory,
                'rgxp' => $this->rgxp,
                'width' => $this->width ?? '',
                'style' => '',
                'name' => $index,
                'unique' => false,
                'maxlength' => $this->maxlength,
            ];
            if (array_key_exists($index, $this->arrLabels)) {
                $column['label'] = $this->arrLabels[$index];
            }
            $column['class'] = is_numeric($column['name']) ? 'column'.$column['name'] : (string) $column['name'];
        }
        return $columns;
    }

    public function validate()
    {
        $columns = $this->columns();
        $input = $this->getPost($this->strName);
        $this->arrErrors = [];
        $this->arrMultiErrors = [];
        if (!is_array($input) && $input !== null) {
            $this->addError($GLOBALS['TL_LANG']['ERR']['multitextInvalid']);
        }
        $rows = is_array($input) && $input ? array_values($input) : [[]];
        $configuration = $this->arrConfiguration;
        $label = $this->strLabel;
        $result = [];
        $seen = [];
        try {
            foreach ($rows as $row => $values) {
                if (!is_array($values)) {
                    $this->addError($GLOBALS['TL_LANG']['ERR']['multitextInvalid']);
                    $values = [];
                }
                foreach ($columns as $col => $column) {
                    $value = $values[$col] ?? '';
                    $offset = count($this->arrErrors);
                    if (!is_scalar($value) && $value !== null) {
                        $this->addError($GLOBALS['TL_LANG']['ERR']['multitextInvalid']);
                        $value = '';
                    }
                    $this->arrConfiguration = array_replace($configuration, [
                        'rgxp' => $column['rgxp'], 'mandatory' => $column['mandatory'],
                        'maxlength' => $column['maxlength'], 'unique' => false,
                    ]);
                    $this->strLabel = (string) $column['label'];
                    $value = parent::validator((string) $value);
                    $result[$row][$col] = $value;
                    if ($column['unique'] && in_array($value, $seen[$col] ?? [], true)) {
                        $this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['notunique'], $this->strLabel));
                    }
                    $seen[$col][] = $value;
                    $this->arrMultiErrors[$row][$col] = array_slice($this->arrErrors, $offset);
                }
            }
        } finally {
            $this->arrConfiguration = $configuration;
            $this->strLabel = $label;
        }
        $this->varValue = $result;
    }

    public function generate()
    {
        $script = 'bundles/hschottmmultitextwizard/js/multitext.js';
        if (!in_array($script, $GLOBALS['TL_JAVASCRIPT'] ?? [], true)) {
            $GLOBALS['TL_JAVASCRIPT'][] = $script;
        }
        $stylesheet = 'bundles/hschottmmultitextwizard/css/multitext.css';
        if (!in_array($stylesheet, $GLOBALS['TL_CSS'] ?? [], true)) {
            $GLOBALS['TL_CSS'][] = $stylesheet;
        }
        $columns = $this->columns();
        $rows = is_array($this->varValue) && $this->varValue ? array_values($this->varValue) : [[]];
        foreach ($rows as &$row) {
            $values = is_array($row) ? $row : [];
            $row = [];
            foreach ($columns as $col => $column) {
                $row[] = is_scalar($values[$col] ?? '') ? (string) ($values[$col] ?? '') : '';
            }
        }
        $template = new BackendTemplate($this->strMultitextTemplate);
        $template->strId = $this->strId;
        $template->strName = $this->strName;
        $template->style = (string) $this->style;
        $template->arrColumns = $columns;
        $template->varValue = $rows;
        $template->arrMultiErrors = $this->arrMultiErrors;
        $buttons = [];
        foreach (['rnew' => 'new', 'rcopy' => 'copy', 'rdelete' => 'delete', 'drag' => 'drag'] as $action => $icon) {
            $buttons[] = ['action' => $action, 'icon' => $icon, 'image' => \Contao\Image::getHtml($icon.'.svg', '', 'aria-hidden="true"'), 'title' =>
                $this->arrConfiguration['buttonTitles'][$action] ?? $GLOBALS['TL_LANG'][$this->strTable][$action][0]
                ?? $GLOBALS['TL_LANG']['MSC']['multitextWizard'][$action] ?? $action];
        }
        $template->arrButtons = $buttons;
        return $template->parse();
    }
}

class_alias(MultiTextWizard::class, 'MultiTextWizard');
