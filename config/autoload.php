<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package MultiTextWizard
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Widgets
	'Contao\MultiTextWizard' => 'system/modules/MultiTextWizard/widgets/MultiTextWizard.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'be_multitext' => 'system/modules/MultiTextWizard/templates',
));
