<?php
/**
 * The MIT License
 * Copyright (c) 2019 Ivan Klimchuk. https://klimchuk.com
 * Full license text placed in the LICENSE file in the repository or in the license.txt file in the package.
 */

$list = [
    'msDeliveryProps' => [
        'description' => 'Plugin for loading custom scripts for system settings and minishop2 delivery settings pages.',
        'events' => [
            'OnManagerPageBeforeRender'
        ]
    ]
];

$plugins = [];
foreach ($list as $k => $v) {
    $plugin = $xpdo->newObject(modPlugin::class);
    $plugin->fromArray([
        'id' => 0,
        'name' => $k,
        'category' => 0,
        'description' => $v['description'],
        'plugincode' => trim(str_replace(['<?php', '?>'], '', file_get_contents($sources['plugins'] . $k . '.php'))),
        'static' => false,
        'static_file' => 'core/components/' . PKG_NAME_LOWER . '/elements/plugins/' . $k . '.php',
        'source' => 1,
        'property_preprocess' => 0,
        'editor_type' => 0,
        'cache_type' => 0
    ], '', true, true);
    if (!empty($v['events'])) {
        $events = [];
        foreach ($v['events'] as $e) {
            $event = $xpdo->newObject(modPluginEvent::class);
            $event->fromArray([
                'pluginid' => 0,
                'event' => $e,
                'priority' => 0,
                'propertyset' => 0,
            ], '', true, true);
            $events[] = $event;
        }
        unset($v['events']);
        $plugin->addMany($events, 'PluginEvents');
    }
    $plugins[] = $plugin;
}

return $plugins;
