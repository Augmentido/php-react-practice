<?php
namespace Controller\Tasks;

require_once ROOT . '/Model/Task.php';
use \Model\Task as Task;

class Tasks{
    /**
     * Method for getting a list of taks
     * @return array of taskNodes
     */
    public function list(){
        $t = new Task();
        $tasks = $t->getList();
        return Responder::respond('list', $tasks);
    }

    /**
     * Add a new task
     * @return id
     */
    public function add(){
        $input = json_decode(file_get_contents('php://input'),1);
        if(is_null($input)){
            return Responder::e400('Input data does not received or have invalid format.');
        }

        if(!isset($input['content']) OR !isset($input['done'])){
            return Responder::e400('Input data does not contain required fields.');
        }

        $t = new Task();
        $id = $t->add([
            'done' => $input['done'] ? true : false,
            'content' => $input['content'],
        ]);
        if($id === false){
            return Responder::e500('Can`t add your task! Try again later.');
        }
        return Responder::respond('operation_result', [
            'operation_type' => 'add',
            'id' => $id,
        ]);
    }

    /**
     * Get a single task by ID
     * @param array $params = [id:int]
     * 
     * @return taskNode
     */
    public function get($params){
        if(!isset($params['id'])){
            return Responder::e400('Your request does not contain an ID.');
        }
        $params['id'] = intval($params['id']);
        $t = new Task();
        $taskSign = $t->get($params['id']);
        if(is_null($taskSign)){
            return Responder::e400('Item with your ID does not exist!');
        }
        return Responder::respond('task', [
            'data' => $taskSign,
        ]);
    }

    /**
     * Update a task by ID
     * @param array $params = [id:int]
     * 
     * @return id:int
     */
    public function update($params){
        $input = json_decode(file_get_contents('php://input'),1);
        if(is_null($input)){
            return Responder::e400('Input data does not received or have invalid format.');
        }

        if(!isset($input['content']) OR !isset($input['done']) OR !isset($params['id'])){
            return Responder::e400('Input data does not contain required fields.');
        }
        $params['id'] = intval($params['id']);
        $t = new Task();
        $oldTaskSign = $t->get($params['id']);
        if(is_null($oldTaskSign)){
            return Responder::e400('Item with your ID does not exist!');
        }

        $res = $t->update([
            'id' => $params['id'],
            'done' => $input['done'] ? true : false,
            'content' => $input['content'],
        ]);
        if(!$res){
            return Responder::e500('Can`t update your task! Try again later.');
        }
        return Responder::respond('operation_result', [
            'operation_type' => 'update',
            'id' => $params['id'],
        ]);
    }

    /**
     * Delete task by ID
     * @param array $params = [id:int]
     * 
     * @return [status:string, id:int]
     */
    public function delete($params){
        if(!isset($params['id'])){
            return Responder::e400('Your request does not contain an ID.');
        }
        $params['id'] = intval($params['id']);
        $t = new Task();
        $taskSign = $t->get($params['id']);
        if(is_null($taskSign)){
            return Responder::e400('Item with your ID does not exist!');
        }
        $res = $t->remove($params['id']);
        if(!$res){
            return Responder::e500('Can`t remove your task! Try again later.');
        }
        return Responder::respond('operation_result', [
            'operation_type' => 'delete',
            'status' => 'success',
            'id' => $params['id']
        ]);
    }
}