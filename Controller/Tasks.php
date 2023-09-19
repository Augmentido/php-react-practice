<?php
namespace Controller\Tasks;

class Tasks{
    public function list($params){
        echo 'Hi! This is list method';
        print_r($params);

    }

    public function add($params){
        echo 'Hi! This is add method';
        print_r($params);

    }

    public function get($params){
        echo 'Hi! This is get method';
        print_r($params);

    }

    public function update($params){
        echo 'Hi! This is update method';
        print_r($params);

    }

    public function delete($params){
        echo 'Hi! This is delete method';
        print_r($params);

    }
}