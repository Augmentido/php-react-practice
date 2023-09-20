<?php
namespace Model\Task;

/**
 * A Model class for handling Tasks operations.
 * Uses a file on the drive as a storage container.
 */
class Task{
    private $dataFileName = '/data-list.php';
    private $tasksList = [];
    private $lastId = 0;

    /**
     * Loads tasks data from the disk
     * @return void
     */
    private function loadData(){
        if(!file_exists(ROOT . $this->$dataFileName)){
            $this->tasksList = [];
            $this->lastId = 0;
            return;
        }
        include ROOT . $this->$dataFileName;
        $this->tasksList = $tasksData;
        $this->lastId = $tasksLastId;
    }

    /**
     * Saves tasks data to the disk
     * @return void
     */
    private function saveData(){
        file_put_contents(
            ROOT . $this->$dataFileName,  
            '<'."?php\nnamespace Model\\Task;\n\$tasksData = " . var_export($tasksList, 1)
            ."\n\$tasksLastId = " . $this->lastId .";\n"
        );
    }

    /**
     * Returns full list of tasks
     * @return array of [id:int, content:string, done:bool]
     */
    public function getList(){
        $this->loadData();
        $r = [];
        foreach($this->tasksList as $taskId => $taskData){
            $r[] = [
                'id' => $taskId,
                'content' => $taskData[1],
                'done' => $taskData[0],
            ];
        }
        return $r;
    }

    /**
     * Inserts new task to the list of tasks.
     * Returns an ID of the new element.
     * @param array $node
     * 
     * @return int
     */
    public function add($node){
        $this->loadData();
        $this->lastId++;
        $this->tasksList[$this->lastId] = [
            $node['done'], 
            $node['content']
        ];
        $this->saveData();
        return $this->lastId;
    }

    /**
     * Removes a task from the tasks list
     * @param int $id
     * 
     * @return bool
     */
    public function remove($id){
        $this->loadData();
        if(!isset($this->tasksList[$id])){
            return false;
        }
        unset($this->tasksList[$id]);
        $this->saveData();
        return true;
    }

    /**
     * Returns a task element or null on failure
     * @param int $id
     * 
     * @return node = [id:int, content:string, done:bool]
     * @return null
     */
    public function get($id){
        $this->loadData();
        if(!isset($this->tasksList[$id])){
            return null;
        }
        return [
            'id' => $id,
            'content' => $this->tasksList[$id][1],
            'done' => $this->tasksList[$id][0],
        ];
    }

    /**
     * Updates the task
     * @param [id:int, content:string, done:bool] $node
     * 
     * @return bool
     */
    public function update($node){
        $this->loadData();
        if(!isset($this->tasksList[$node['id']])){
            return false;
        }
        $this->tasksList[$node['id']] = [$node['done'], $node['content']];
        $this->saveData();
        return true;
    }
}