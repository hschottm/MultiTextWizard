<?php

use Hschottm\MultiTextWizardBundle\MultiTextWizard;
use Symfony\Component\HttpFoundation\Request;
use Contao\System;

$GLOBALS['BE_FFL']['multitextWizard'] = MultiTextWizard::class;

if (System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest(System::getContainer()->get('request_stack')->getCurrentRequest() ?? Request::create(''))) 
{
	$GLOBALS['TL_JAVASCRIPT'][] = 'bundles/hschottmmultitextwizard/js/multitext.js';
}
