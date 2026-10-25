<?php
$app = getenv('MULTITEXT_TEST_APP') ?: '/var/www/html';
require $app.'/vendor/autoload.php';
$root=dirname(__DIR__);
$map=[];
foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root.'/src')) as $file) {
    if($file->getExtension()==='php' && preg_match('/namespace ([^;]+);/', file_get_contents($file), $m)) $map[trim($m[1]).'\\'.$file->getBasename('.php')]=(string)$file;
}
spl_autoload_register(static function($class)use($map){if(isset($map[$class])) require $map[$class];});
class MultiTextTestKernel extends Contao\ManagerBundle\HttpKernel\ContaoKernel {
    public function registerBundles(): array { return [...parent::registerBundles(), new Hschottm\MultiTextWizardBundle\HschottmMultiTextWizardBundle()]; }
    public function getCacheDir(): string { return '/tmp/contao-multitext-test-cache'; }
}
$kernel=MultiTextTestKernel::fromInput($app,new Symfony\Component\Console\Input\ArrayInput(['--env'=>'prod']));
$kernel->boot();
$c=$kernel->getContainer();
$request=Symfony\Component\HttpFoundation\Request::create('http://localhost/contao');
$request->attributes->set('_scope','backend');
$c->get('request_stack')->push($request);
$c->get('contao.framework')->initialize();

Contao\System::loadLanguageFile('default', 'en');
$widget = new Hschottm\MultiTextWizardBundle\MultiTextWizard(['id'=>'test','name'=>'rows','columns'=>[['label'=>'First','mandatory'=>true],['label'=>'Second']], 'value'=>[['C++','<unsafe>']]]);
$html = $widget->generate();
if (!str_contains($html, '&lt;unsafe&gt;') || !str_contains($html, 'rows[0][1]')) throw new RuntimeException('Real Twig rendering failed');
if (($GLOBALS['BE_FFL']['multitextWizard'] ?? '') !== get_class($widget)) throw new RuntimeException('Bundle registration failed');
echo "Real Contao kernel: bundle registration, language files and Twig rendering passed.\n";
