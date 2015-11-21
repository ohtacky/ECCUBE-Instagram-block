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


class InstagramApiController
{
  public function index(Application $app)
  {

    $accessInfo = $app['orm.em']->getRepository('\Plugin\InstagramApi\Entity\InstagramApi')
          ->findOneBy(
              array('id' => 1)
                    );

    if ($accessInfo !== null) {

      $instaAccessToken = $accessInfo['api_token'];
      $instaUser = $accessInfo['api_user'];

      $userApiUrl = 'https://api.instagram.com/v1/users/search?q=' . $instaUser . '&amp;access_token=' . $instaAccessToken;

      $http_response_header = array();

      if($instaJson = @file_get_contents($userApiUrl)){

            $instaJson = mb_convert_encoding($instaJson, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
            $instaArray = json_decode($instaJson,true);

              foreach ($instaArray['data'] as $insta) {
                  if ( $instaUser == $insta['username']) {
                    $userId = $insta['id'];
                  }
              }

            $photos_api_url = 'https://api.instagram.com/v1/users/'.$userId.'/media/recent?access_token=' . $instaAccessToken . "&count=12";

            $http_response_header = array();

            if($photosJson = @file_get_contents($photos_api_url)){

                $photosJson = mb_convert_encoding($photosJson, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
                $photos_data = json_decode($photosJson,true);

                if ($photos_data['data'] !== null) {

                  return $app->render('Block/instagram_api.twig', array(
                      'datas' => $photos_data['data'],
                      'error' => null
                  ));

                } else {
                  return $app->render('Block/instagram_api.twig', array(
                    'datas' => null,
                    'error' => null
                  ));
                }

            }else{
                //エラー処理
                if(count($http_response_header) > 0){
                    $status_code = explode(' ', $http_response_header[0]);

                    switch($status_code[1]){
                        case 404:
                            return $app->render('Block/instagram_api.twig', array(
                              'datas' => null,
                              'error' => '指定したページが見つかりませんでした'
                            ));
                            break;

                        case 500:
                            return $app->render('Block/instagram_api.twig', array(
                              'datas' => null,
                              'error' => '指定したページがあるサーバーにエラーがあります'
                            ));
                            break;

                        default:
                            return $app->render('Block/instagram_api.twig', array(
                              'datas' => null,
                              'error' => '何らかのエラーによって指定したページのデータを取得できませんでした'
                            ));

                    }
                }else{

                    return $app->render('Block/instagram_api.twig', array(
                      'datas' => null,
                      'error' => 'タイムエラー or URLが間違っています'
                    ));

                }
            }

      }else{

          if(count($http_response_header) > 0){

              $status_code = explode(' ', $http_response_header[0]);

              switch($status_code[1]){
                  case 404:
                      return $app->render('Block/instagram_api.twig', array(
                        'datas' => null,
                        'error' => '指定したページが見つかりませんでした'
                      ));
                      break;

                  case 500:
                      return $app->render('Block/instagram_api.twig', array(
                        'datas' => null,
                        'error' => '指定したページがあるサーバーにエラーがあります'
                      ));
                      break;

                  default:
                      return $app->render('Block/instagram_api.twig', array(
                        'datas' => null,
                        'error' => '何らかのエラーによって指定したページのデータを取得できませんでした'
                      ));

              }
          }else{

              return $app->render('Block/instagram_api.twig', array(
                'datas' => null,
                'error' => 'タイムエラー or URLが間違っています'
              ));

          }
      }


      } else {
        return $app->render('Block/instagram_api.twig', array(
          'datas' => null,
          'error' => 'API情報が設定されていません。'
        ));

    }
  }
}
