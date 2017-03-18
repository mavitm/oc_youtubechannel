<?php namespace Mavitm\Youtubechannel;

use Backend;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{

    public function pluginDetails()
    {
        return [
            'name'          => 'mavitm.youtubechannel::lang.plugin.name',
            'description'   => 'mavitm.youtubechannel::lang.plugin.description',
            'author'        => 'Mavitm',
            'icon'          => 'icon-pencil',
            'homepage'      => 'https://github.com/MaviTm/youtubechannel'
        ];
    }

    public function registerComponents()
    {
        return [
            'Mavitm\Youtubechannel\Components\Ytlist'       => 'youtubechannelList',
            'Mavitm\Youtubechannel\Components\Ytplaylist'   => 'youtubechannelPlayer',
        ];
    }

    public function registerPermissions()
    {
    }

    public function registerNavigation()
    {
    }

    public function registerFormWidgets()
    {
    }

    public function registerSettings()
    {
    }
}
