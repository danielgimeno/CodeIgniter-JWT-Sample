<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

/*
 * Changes:
 * 1. This project contains .htaccess file for windows machine.
 *    Please update as per your requirements.
 *    Samples (Win/Linux): http://stackoverflow.com/questions/28525870/removing-index-php-from-url-in-codeigniter-on-mandriva
 *
 * 2. Change 'encryption_key' in application\config\config.php
 *    Link for encryption_key: http://jeffreybarke.net/tools/codeigniter-encryption-key-generator/
 * 
 * 3. Change 'jwt_key' in application\config\jwt.php
 *
 */

class Auth extends REST_Controller {

    /**
     * URL: http://local.jwt/auth/token
     * Method: POST
     */
    public function token_post() {
        $user = $this->post('username');
        $pswd = $this->post('pswd');
        //verificacion de usuario y paswd 
        if ($this->valid_user_pswd($user,$pswd)){
            $tokenData = array();
            $tokenData['header'] = array("typ" => "JWT", "alg" => "HS256");
            $tokenData['payload'] = array(
                'sub' => $user,
                'exp' => time() + (7 * 24 * 60 * 60),
                'iat' => time(),
                'jti' => 1
            );

            $output['token'] = AUTHORIZATION::generateToken($tokenData);
            $this->set_response($output, REST_Controller::HTTP_OK);
        } else
        {
             $this->set_response("Unauthoised", REST_Controller::HTTP_UNAUTHORIZED);
        }    
    }

    /**
     * Fake method valid_user_pswd
     * return valid value
     */
    public function valid_user_pswd($user,$pswd){
        return true;
    }
    
    /**
     * URL: http://local.jwt/auth/tokenRetrieve
     * Method: POST
     * Header Key: Authorization
     * Value: Auth token generated in GET call
     */
    public function tokenRetrieve_post() {
        $headers = $this->input->request_headers();

        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) {
                $this->set_response($decodedToken, REST_Controller::HTTP_OK);
                return;
            }
        }
        $this->set_response("Unauthoised", REST_Controller::HTTP_UNAUTHORIZED);
    }

}
