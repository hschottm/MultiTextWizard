<?php

declare(strict_types=1);

/*
 * @copyright  Helmut Schottmüller 2020-2024 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    hschottm/contao-multitextwizard
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/MultiTextWizard
 */

namespace Hschottm\MultiTextWizardBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Hschottm\MultiTextWizardBundle\HschottmMultiTextWizardBundle;

/**
 * Plugin for the Contao Manager.
 *
 * @author Helmut Schottmüller (hschottm)
 */
class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser): array
    {
        return [
             BundleConfig::create(HschottmMultiTextWizardBundle::class)
              ->setLoadAfter([ContaoCoreBundle::class])
              ->setReplace(['multitextwizard']),
         ];
    }
}
