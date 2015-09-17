<?php

namespace filsh\wamp\locator;

use Yii;
use yii\di\Instance;
use filsh\wamp\components\Collection;
use filsh\wamp\locator\runners\Timezones;

class Locator extends \filsh\yii2\runner\RunnerComponent
{
    const GET_TIMEZONES = 'wms.geonames.timezones';
    
    const GET_IDENTITY_BY_ID = 'wms.oauth2.identity-by-id';
    const GET_IDENTITY_BY_ACCESS_TOKEN = 'wms.oauth2.identity-by-access-token';
    const GET_IDENTITY_BY_CREDENTIALS = 'wms.oauth2.identity-by-credentials';
    
    const CREATE_IDENTITY = 'wms.oauth2.create-identity';
    
    const GET_ACCOUNT_BY_ID = 'wms.oauth2.account-by-id';
    
    const GET_USER_BY_IDENTITY = 'wms.oauth2.user-by-identity';
    
    const UPLOAD_IMAGE_FILE = 'wms.storage.upload-image-file';
    
    public $router;
    
    /**
     * @inheritdoc
     */
    public $runners = [
        self::GET_TIMEZONES => Timezones::class,
        self::GET_IDENTITY_BY_ID => \filsh\wamp\locator\runners\identity\Id::class,
        self::GET_IDENTITY_BY_ACCESS_TOKEN => \filsh\wamp\locator\runners\identity\AccessToken::class,
        self::GET_IDENTITY_BY_CREDENTIALS => \filsh\wamp\locator\runners\identity\Credentials::class,
        
        self::CREATE_IDENTITY => \filsh\wamp\locator\runners\identity\Create::class,
        
        self::GET_ACCOUNT_BY_ID => \filsh\wamp\locator\runners\account\Id::class,
        
        self::GET_USER_BY_IDENTITY => \filsh\wamp\locator\runners\user\Identity::class,
        
        self::UPLOAD_IMAGE_FILE => \filsh\wamp\locator\runners\file\Upload::class,
    ];
    
    public $runnersMap = [
        'getTimezones' => self::GET_TIMEZONES,
        
        'getIdentityById' => self::GET_IDENTITY_BY_ID,
        'getIdentityByAccessToken' => self::GET_IDENTITY_BY_ACCESS_TOKEN,
        'getIdentityByCredentials' => self::GET_IDENTITY_BY_CREDENTIALS,
        
        'createIdentity' => self::CREATE_IDENTITY,
        
        'getAccountById' => self::GET_ACCOUNT_BY_ID,
        
        'getUserByIdentity' => self::GET_USER_BY_IDENTITY,
        
        'uploadImageFile' => self::UPLOAD_IMAGE_FILE
    ];
    
    public function createRunner($name, $config = array())
    {
        $runner = parent::createRunner($name, $config);
        return \Yii::configure($runner, [
            'name' => $name,
            'router' => $this->router
        ]);
    }
}