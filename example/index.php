<?php

use Echo511\TextbookGen\Generator\Generator;
use Echo511\TextbookGen\Index\Index;
use Echo511\TextbookGen\OutputGeneratorFactory\FileTemplateLatteOutputGeneratorFactory;
use Echo511\TextbookGen\OutputStorage\BrowserOutputStorage;
use Echo511\TextbookGen\OutputStorage\FileOutputStorage;
use Echo511\TextbookGen\Plugin\Image\ImageIndex;
use Echo511\TextbookGen\Plugin\Image\ImagePlugin;
use Echo511\TextbookGen\Plugin\Includer\IncludedSnippetsTracker;
use Echo511\TextbookGen\Plugin\Includer\Includer;
use Echo511\TextbookGen\Plugin\Link\LinkPlugin;
use Echo511\TextbookGen\Plugin\Metadata\Analyzator;
use Echo511\TextbookGen\Plugin\Metadata\Manipulator;
use Echo511\TextbookGen\Plugin\RelationMap\RelationMap;
use Echo511\TextbookGen\Plugin\TexyPlugin;
use Echo511\TextbookGen\Snippet\FileSnippet;
use Echo511\TextbookGen\Snippet\Snippet;
use Echo511\TextbookGen\Utils\Filesystem;
use Nette\Configurator;
use Nette\Templating\ITemplate;
use Nette\Utils\Finder;


// Composer autoloader
require __DIR__ . '/../vendor/autoload.php';

// Nette setup
$configurator = new Configurator;

// Enable Nette Debugger for error visualisation & logging
// $configurator->setDebugMode(TRUE);
$configurator->enableDebugger(__DIR__ . '/../log');

// Enable RobotLoader - this will load all classes automatically
$configurator->setTempDirectory(__DIR__ . '/../temp');
$configurator->createRobotLoader()
	->addDirectory(__DIR__ . '/../Echo511')
	->register();

// Create Dependency Injection container from config.neon file
$container = $configurator->createContainer();


// Step 1: fill index
$index = new Index();
foreach (Finder::findFiles('*')->in(__DIR__ . '/input') as $filename) {
	$index->add(new FileSnippet($filename));
}


// Step 2: Initialize generator
$location = __DIR__ . '/assets/templates/conditioning.latte';
$outputGeneratorFactory = new FileTemplateLatteOutputGeneratorFactory($location, $container);


// Step 3: Initialize storage
//$outputStorage = new BrowserOutputStorage;
$outputStorage = new FileOutputStorage(__DIR__ . '/output', 'html', new Analyzator);


// Step 4: Initialize generator
$generator = new Generator($index, $outputGeneratorFactory, $outputStorage);


// Step 5: Initialize plugins

//// Metadata
$analyzator = new Analyzator;
$manipulator = new Manipulator;

//// RelationMap
$relationMap = new RelationMap($analyzator, $index);

//// Includer
$includedSnippetsTracker = new IncludedSnippetsTracker;
$includer = new Includer($manipulator, $includedSnippetsTracker, $outputGeneratorFactory, $relationMap);

//// Link
$link = new LinkPlugin($analyzator, $manipulator, $relationMap, $outputStorage);

//// Image
$imageIndex = new ImageIndex();
$imageIndex->basePath = 'images';
foreach (Finder::findFiles('*')->in(__DIR__ . '/input/images') as $filename => $spl) {
	$imageIndex->add($filename);
}
$image = new ImagePlugin($analyzator, $manipulator, $relationMap, $imageIndex);

//// Texy
$texy = new TexyPlugin();

//// Pass objects to template
$outputGeneratorFactory->onTemplate[] = function(ITemplate $template) use ($analyzator, $index, $manipulator, $relationMap, $includedSnippetsTracker, $includer, $link, $image, $texy) {
		$template->analyzator = $analyzator;
		$template->index = $index;
		$template->manipulator = $manipulator;
		$template->relationMap = $relationMap;
		$template->includedSnippetsTracker = $includedSnippetsTracker;
		$template->includer = $includer;
		$template->link = $link;
		$template->image = $image;
		$template->texy = $texy;
	};


// Step 6: Hook plugins' callbacks
$generator->onStartup[] = callback($relationMap, 'createRelationMap');
$generator->onBeforeSnippetGenerate[] = callback($includedSnippetsTracker, 'markAsFirst');
$generator->onAfterSnippetGenerate[] = callback($includedSnippetsTracker, 'reset');


// Step 7: Run
$generator->run();
Filesystem::copy(__DIR__ . '/assets', __DIR__ . '/output/assets');
Filesystem::copy(__DIR__ . '/input/images', __DIR__ . '/output/images');


// Step 8: Generate custom maps
$snippetList = new Snippet('termList');
$outputGeneratorFactory->location = __DIR__ . '/assets/templates/snippetList.latte';
$outputGenerator = $outputGeneratorFactory->create($snippetList);
file_put_contents(__DIR__ . '/output/__snippetlist.html', $outputGenerator->generate());