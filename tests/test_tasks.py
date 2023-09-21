import requests
import pytest

class TestTasksApi:
    server_addr = 'http://localhost/tasks'

    def test_task_truncate(self):
        r = requests.delete(self.server_addr + '/all')
        r = r.json()
        assert r['operation_type'] == 'tasks_truncate'


    @pytest.mark.parametrize('task_node',[({"content":"test task 1", "done":0}), ({"content":"test task 2", "done":1})])
    def test_task_add(self, task_node):
        r = requests.post(self.server_addr, json=task_node)
        r = r.json()
        assert r['operation_type'] == 'tasks_add'
        assert r['id'] is not None


    def test_task_list(self):
        r = requests.get(self.server_addr)
        r = r.json()
        assert r['operation_type'] == 'tasks_list'
        assert r['list'] is not None
        assert len(r['list']) > 0


    @pytest.mark.parametrize('task_node',[({"content":"test task 3", "done":1}), ({"content":"test task 4", "done":0})])
    def test_task_get(self, task_node):
        r = requests.post(self.server_addr, json=task_node)
        r = r.json()
        assert r['operation_type'] == 'tasks_add'
        assert r['id'] is not None

        r = requests.get(self.server_addr + '/' + str(r['id']))
        r = r.json()
        assert r['operation_type'] == 'tasks_get'
        assert r['task'] is not None
        assert r['task']['content'] == task_node['content']
        assert r['task']['done'] == task_node['done']


    @pytest.mark.parametrize('task_node,task_node_updated',\
                             [({"content":"test task 5", "done":1}, {"content":"test task 5-1", "done":0}),\
                             ({"content":"test task 6", "done":0}, {"content":"test task 6-1", "done":1})]\
                             )
    def test_task_update(self, task_node, task_node_updated):
        r = requests.post(self.server_addr, json=task_node)
        r = r.json()
        assert r['operation_type'] == 'tasks_add'
        assert r['id'] is not None
        id = r['id']

        r = requests.patch(self.server_addr + '/' + str(id), json=task_node_updated)
        r = r.json()
        assert r['operation_type'] == 'tasks_update'
        assert r['id'] == id

        r = requests.get(self.server_addr + '/' + str(id))
        r = r.json()
        assert r['operation_type'] == 'tasks_get'
        assert r['task'] is not None
        assert r['task']['content'] == task_node_updated['content']
        assert r['task']['done'] == task_node_updated['done']


    def test_task_delete(self):
        r = requests.post(self.server_addr, json={"content":"test task 7", "done":0})
        r = r.json()
        assert r['operation_type'] == 'tasks_add'
        assert r['id'] is not None
        id = r['id']

        r = requests.get(self.server_addr + '/' + str(id))
        r = r.json()
        assert r['operation_type'] == 'tasks_get'
        assert r['task'] is not None
        assert r['task']['content'] == 'test task 7'
        assert r['task']['done'] == 0

        r = requests.delete(self.server_addr + '/' + str(id))
        r = r.json()
        assert r['operation_type'] == 'tasks_delete'
        assert r['id'] == id

        r = requests.get(self.server_addr + '/' + str(id))
        assert r.status_code == 400
        r = r.json()
        assert r['message'] == 'Item with your ID does not exist!'