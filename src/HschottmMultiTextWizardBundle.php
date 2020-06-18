<?php

declare(strict_types=1);

/*
 * @copyright  Helmut Schottmüller 2008-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    hschottm/contao-multitextwizard
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/MultiTextWizard
 */

namespace Hschottm\MultiTextWizardBundle;

use Hschottm\TextWizardBundle\DependencyInjection\MultiTextWizardExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class HschottmMultiTextWizardBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new MultiTextWizardExtension();
    }
}
