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


namespace Plugin\InstagramApi\Entity;

class InstagramApi extends \Eccube\Entity\AbstractEntity
{

    /**
     * @var integer
     */
    private $id;

    private $api_token;

    private $api_user;

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getApiToken()
    {
        return $this->api_token;
    }

    public function setApiToken($api_token)
    {
        $this->api_token = $api_token;

        return $this;
    }

    public function getApiUser()
    {
        return $this->api_user;
    }

    public function setApiUser($api_user)
    {
        $this->api_user = $api_user;

        return $this;
    }

}
