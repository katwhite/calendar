<?php

include 'autoload.php';

use app\task;

$task = new task;

if (getenv('REQUEST_METHOD') === 'POST')
{
    $task = task::getById($_POST['id']);
    
    if (isset($_POST['checked']))
    {
        $task->status = task::STATUS_DONE;
    }
    else
    {
        $task->status = task::STATUS_PENDING;
    }
    if ($task->save())
    {
        header('Status: 302 Found');
        header('HTTP/1.1 302 Found');
        header('Location: /calendar/');
        exit;
    }
    
}
else{
    header('Status: 302 Found');
    header('HTTP/1.1 302 Found');
    header('Location: /calendar/');
    
}