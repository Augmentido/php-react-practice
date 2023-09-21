<?php

class Responder{

    /**
     * Send responce to client
     * @param array $data
     * 
     * @return none
     */
    static function respond($data){
        global $_SERVER;

        if(isset($data['status_code']) AND isset($data['status_text'])){
            header($_SERVER["SERVER_PROTOCOL"].' '.$data['status_code'].' '.$data['status_text']);
            unset($data['status_code']);
            unset($data['status_text']);
        }
        if(count($data) > 0){
            echo json_encode($data);
        }

        exit;
    }

    /**
     * Shortland for error 404 responce
     * @param string $message
     * 
     * @return none
     */
    static function e404(){
        self::respond([
            'status_code'=>'404', 
            'status_text' => 'Page not found',
        ]);
    }

    /**
     * Shortland for error 400 responce
     * @param string $message
     * 
     * @return none
     */
    static function e400($message){
        self::respond([
            'status_code'=>'400', 
            'status_text' => 'Bad Request',
            'message' => $message,
        ]);
    }

    /**
     * Shortland for error 500 responce
     * @param string $message
     * 
     * @return none
     */
    static function e500($message){
        self::respond([
            'status_code'=>'500', 
            'status_text' => 'Internal Server Error',
            'message' => $message,
        ]);
    }

}