<?php

namespace AwxV2\Oauth;

/**
 * 
 * 
 */
class Oauth2
{
  private $clientId;
  private $clientSecret;
  private $username;
  private $password;
  private $sslVerify;
  private $apiUrl;
  
  public function __construct(array $awxVars = [])
  {
    if (isset($awxVars['clientId'])) {
      $this->clientId = $awxVars['clientId'];
    }
    if (isset($awxVars['clientSecret'])) {
      $this->clientSecret = $awxVars['clientSecret'];
    }
    if (isset($awxVars['username'])) {
      $this->username = $awxVars['username'];
    }
    if (isset($awxVars['password'])) {
      $this->password = $awxVars['password'];
    }
    if (isset($awxVars['sslVerify'])) {
      $this->sslVerify = $awxVars['sslVerify'];
    }
    if (isset($awxVars['apiUrl'])) {
      $this->apiUrl = $awxVars['apiUrl'];
    }
  }
  
  public function awxProvider()
    {
      $provider = new \Awx\OAuth2\Client\Provider\GenericProvider([
          'clientId'                => $this->clientId,
          'clientSecret'            => $this->clientSecret,
          //'redirectUri'             => 'http://example.com/your-redirect-url/',
          'urlAccessToken'          => $this->apiUrl . '/o/token/',
          'urlAuthorize'            => null,
          'urlResourceOwnerDetails' => null,
          //'proxy'                   => \Awx\Awx::getConnectBase() . '/o/token',
          'verify'                  => $this->sslVerify,
      ]);

      return $provider;
    }

    public function passCredGrant()
    {
      try {

        $provider = $this->awxProvider();
        // Try get tokens using the resource owner password credentials grant.
        $tokens = $provider->getAccessToken('password', [
            'username' => $this->username,
            'password' => $this->password,
        ]);
       
      } catch (\Awx\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

        // Failed to token or user details.
        exit($e->getMessage());
      }

        return $tokens;
    }
}
