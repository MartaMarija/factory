<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__);

$config = new PhpCsFixer\Config();
$config->setRules([
    'function_declaration' => false,
]);

return $config
    ->setFinder($finder);
