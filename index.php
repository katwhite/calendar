<?php

include 'autoload.php';

use app\task;

$task = new task;

if (getenv('REQUEST_METHOD') === 'POST') {
    $task->fill($_POST);
    if ($task->save()) {
        header('Status: 302 Found');
        header('HTTP/1.1 302 Found');
        header('Location: /calendar/');
        exit;
    }
} else {
    if (isset($_GET['task_id'])) {
        $id = $_GET['task_id'];
        $task = task::getById($id);
    }
}

$conditions = [];

if (isset($_GET['date'])) {
    switch ($_GET['date']) {
        case 'today':
        case 'tomorrow':
        case 'thisweek':
        case 'nextweek':
            $conditions['date'] = $_GET['date'];
            break;
        default:
            if (!empty($_GET['date'])) {

                $conditions['date'] = strtotime($_GET['date']);
            }
            break;
    }
}

if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case 'all':
            $tasks = task::getAll($conditions);
            break;

        case 'pending':
            $tasks = task::getAllPending($conditions);
            break;

        case 'done':
            $tasks = task::getAllDone($conditions);
            break;

        case 'failed':
            $tasks = task::getAllFailed($conditions);
            break;
    }
} else {
    $tasks = task::getAll($conditions);
}


include 'views/layout.php';
