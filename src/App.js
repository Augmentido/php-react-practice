import React, { useState, useEffect } from "react";
import $ from "jquery";
import logo from './logo.svg';
import './App.css';

const apiServer = 'http://localhost:8000/tasks';
var setTasksListProc = null;

function getTasksList(){
  $.ajax({
    type: "GET",
    url: apiServer,
    dataType: "json",
    success(data) {
      setTasksListProc(data.list);
    },
  });
}

function addClick(e){
  $('#task_id').val(0);
  $('#task_content').val('');
  //$('#task_done').prop( "checked", false );
  $('#task_not_done').prop( "checked", true );
  $('.edit-task-cont').addClass('add').removeClass('edit').show();
}

function editClick(e){
  $.ajax({
    type: "GET",
    url: apiServer + '/' + $(e.target).closest('.task').data('id'),
    dataType: "json",
    success(data) {
      $('#task_id').val(data.task.id);
      $('#task_content').val(data.task.content);
      $('#task_done').prop( "checked", data.task.done );
      $('#task_not_done').prop( "checked", !data.task.done );
      $('.edit-task-cont').addClass('edit').removeClass('add').show();
    },
  });
}

function editSubmit(e){
  e.preventDefault();
  let ajaxArgs = {
    type: "POST",
    url: apiServer,
    dataType: "json",
    contentType: "application/json; charset=utf-8",
    data: JSON.stringify({
      "content": $('#task_content').val(),
      "done": $('#task_done').is(':checked'),
    }),
    success() {
      $('.edit-task-cont').hide();
      getTasksList();
    },
  }
  let id = $('#task_id').val();
  if(id == 0){
    // add new task
    $.ajax(ajaxArgs);
  }else{
    // update edited task
    ajaxArgs.type = "PATCH";
    ajaxArgs.url = apiServer + '/' + id;
    $.ajax(ajaxArgs);
  }
}

function pureAddFormSubmit(e){
  editSubmit(e);
  return false;
}

function closeEditor(e){
  $('.edit-task-cont').hide();
}

function deleteClick(e){
  $.ajax({
    type: "DELETE",
    url: apiServer + '/' + $(e.target).closest('.task').data('id'),
    dataType: "json",
    success(data) {
      getTasksList();
    },
  });
}

function TaskNode(task){
  return (
    <div className="task" key={task.id} data-id={task.id.toString()} done={task.done.toString()}>
      <div className="task-id">#{task.id}</div>
      <pre>{task.content}</pre>
      <button className="task-btn-edit" onClick={editClick}>Edit</button>
      <button className="task-btn-delete" onClick={deleteClick}>Delete</button>
      </div>
  );
}

function TasksList(tasks){
  const items = tasks.map(task => TaskNode(task));
  return (
  <div className="tasks-list">
    {items}
  </div>
  );
}

function App() {
  const [tasks, setTasksList] = useState([]);
  setTasksListProc = setTasksList;

  useEffect(() => {
    const onPageLoad = () => {
      getTasksList();
    };
    if (document.readyState === 'complete') {
      onPageLoad();
    } else {
      window.addEventListener('load', onPageLoad);
      return () => window.removeEventListener('load', onPageLoad);
    }
  }, []);

  return (
    <div className="App">
      <header className="App-header">
        <img src={logo} className="App-logo" alt="logo" />
      </header>
      <div className="App-body">
        {TasksList(tasks)}
        <button className="task-btn-add" onClick={addClick}>Add a new task</button>
      </div>
      <div className="edit-task-cont">
        <form className="edit-task-wnd" onSubmit={pureAddFormSubmit}>
          <h3 className="show-add">Add new task</h3>
          <h3 className="show-edit">Edit your task</h3>
          <label htmlFor="task_content">Your task</label><br />
          <textarea id="task_content"></textarea><br />
          <input type="radio" id="task_not_done" name="task_done" value="0" />
          <label htmlFor="task_not_done">Not done</label>
          <input type="radio" id="task_done" name="task_done" value="1" />
          <label htmlFor="task_done">Done</label>
          <input type="hidden" id="task_id" name="task_id" value="" /><br />
          <button onClick={editSubmit}>Save</button> 
          <button onClick={closeEditor}>Cancel</button>
        </form>
      </div>
    </div>
  );
}


export default App;