<div class="task-list">
    <div class="task-list_heading">
        List of tasks
    </div>
    <div class="task-list_toolbar">
        <div class="tool-item">
            <form method="GET" action="/calendar/">
                <div class="form-group">
                    <select class="form-group_control" name="status" onchange="this.form.submit()">
                        <option value="all" <?= array_get($_GET, 'status') === 'all' ? 'selected' : '' ?>>All tasks</option>
                        <option value="pending" <?= array_get($_GET, 'status') === 'pending' ? 'selected' : '' ?>>Current tasks</option>
                        <option value="failed" <?= array_get($_GET, 'status') === 'failed' ? 'selected' : '' ?>>Failed tasks</option>
                        <option value="done" <?= array_get($_GET, 'status') === 'done' ? 'selected' : '' ?>>Done tasks</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="tool-item">
            <form method="GET" action="/calendar/">
                <div class="form-group">
                    <input type="date" name="date" value="<?= array_get($_GET, 'date') ?>" class="form-group_control" onchange="this.form.submit()">
                </div>
            </form>
        </div>
        <div class="tool-item">
            <div class="tool-list">
                <div class="tool-list_item">
                    <a href="/calendar/?<?= array_get($_GET, 'status') ? 'status=' . array_get($_GET, 'status') . '&' : '' ?>date=today">today</a>
                </div>
                <div class="tool-list_item">
                    <a href="/calendar/?<?= array_get($_GET, 'status') ? 'status=' . array_get($_GET, 'status') . '&' : '' ?>date=tomorrow">tomorrow</a>
                </div>
                <div class="tool-list_item">
                    <a href="/calendar/?<?= array_get($_GET, 'status') ? 'status=' . array_get($_GET, 'status') . '&' : '' ?>date=thisweek">this week</a>
                </div>
                <div class="tool-list_item">
                    <a href="/calendar/?<?= array_get($_GET, 'status') ? 'status=' . array_get($_GET, 'status') . '&' : '' ?>date=nextweek">next week</a>
                </div>
            </div>
        </div>
    </div>

    <div class="task-list_table">
        <table class="table">
            <thead>
                <tr>
                    <th></th>
                    <th>type</th>
                    <th>task</th>
                    <th>place</th>
                    <th>date&time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task) : ?>

                    <tr>
                        <td>
                            <form method="post" action="/makedone.php">
                                <input type="hidden" name="id" value="<?= $task->id ?>">
                                <input type="checkbox" <?= $task->isDone() ? 'checked' : '' ?> name="checked" onchange="this.form.submit()">
                            </form>
                        </td>
                        <td><?= $task->getTextType() ?></td>
                        <td><a href="/calendar/?task_id=<?= $task->id ?>"><?= $task->subject ?></a>
                        </td>
                        <td><?= $task->place ?></td>
                        <td class="<?= $task->isFailed() ? 'failed' : '' ?>"><?= $task->datetime ?></td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    </div>

</div>