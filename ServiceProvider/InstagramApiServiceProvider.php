<?php
/*
* This file is part of EC-CUBE
*
* Copyright(c) 2000-2015 LOCKON CO.,LTD. All Rights Reserved.
* http://www.lockon.co.jp/
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Plugin\InstagramApi\ServiceProvider;

use Eccube\Application;
use Silex\Application as BaseApplication;
use Silex\ServiceProviderInterface;

class InstagramApiServiceProvider implements ServiceProviderInterface
{
    public function register(BaseApplication $app)
    {

        //Formの定義
        $app['form.types'] = $app->share($app->extend('form.types', function ($types) use ($app) {
            $types[] = new \Plugin\InstagramApi\Form\Type\InstagramApiType($app);
            return $types;
        }));


        //Repository
        $app['eccube.plugin.repository.instagram_api'] = $app->share(function () use ($app) {
            return $app['orm.em']->getRepository('\Plugin\InstagramApi\Entity\InstagramApi');
        });


        //Controllerの追加
        $app->match('/' . $app["config"]["admin_route"] . '/plugin/InstagramApi/config', '\\Plugin\\InstagramApi\\Controller\\ConfigController::index')->bind('plugin_InstagramApi_config');

        $app->match('/block/instagram_api', '\\Plugin\\InstagramApi\\Controller\\InstagramApiController::index')->bind('block_instagram_api');



    }

    public function boot(BaseApplication $app)
    {
    }
}
