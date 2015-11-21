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

namespace Plugin\InstagramApi\Controller;

use Eccube\Application;
use Symfony\Component\HttpFoundation\Request;

class ConfigController
{

    public function index(Application $app, Request $request)
    {

      $apiData = $app['eccube.plugin.repository.instagram_api']->find(1);

      $builder = $app['form.factory']->createBuilder('instagram_api', $apiData);

      $form = $builder->getForm();

      if ('POST' === $request->getMethod()) {
          $form->handleRequest($request);
          if ($form->isValid()) {

            if ($apiData == null) {
              $apiDataArray = $form->getData();

              $apiData = new \Plugin\InstagramApi\Entity\InstagramApi();
              $apiData
                ->setId(1)
                ->setApiToken($apiDataArray['api_token'])
                ->setApiUser($apiDataArray['api_user']);

            } else {

              $apiData = $form->getData();

            }

              $app['orm.em']->persist($apiData);
              $app['orm.em']->flush();

              $app->addSuccess('admin.register.complete', 'admin');

              return $app->redirect($app->url('plugin_InstagramApi_config'));


          }
        }

      return $app->render('InstagramApi/Resource/template/Admin/instagram_api.twig',
            array(
                'form' => $form->createView(),
            )
        );


    }


}
