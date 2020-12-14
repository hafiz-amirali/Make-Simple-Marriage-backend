<?php

use Slim\App;

return function (App $app) {
    $container = $app->getContainer();

    $settings = $container->get('settings');
    // view renderer
    $container['languageResources'] = getLanguageResources($settings['language']);
};

function getLanguageResources($langauge){
    $defaultLanguage = 'en';
    
    $langaugeFilePath = __DIR__ . '/../src/resources/languages/language.'.$langauge.'.json';
    if (!file_exists($langaugeFilePath)){
        $langaugeFilePath = __DIR__ . '/../src/resources/languages/language.en.json';
    }

    $strJsonFileContents = file_get_contents($langaugeFilePath);
    return json_decode($strJsonFileContents, true);
}
