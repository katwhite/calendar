<div class="add-task-form">
    <div class="add-task-form_heading">
        <?php if ($task->isNew()) : ?>
            New task
        <?php else : ?>
            Edit task
        <?php endif; ?>
    </div>
    <form method="post" action="">
        <?php if (!$task->isNew()) : ?>
            <input type="hidden" name="id" value="<?= $task->id ?>">
        <?php endif; ?>
        <div class="form-group">
            <label class="form-group_label">
                Topic
            </label>

            <input class="form-group_control" type="textbox" name="subject" value="<?= htmlspecialchars($task->subject) ?>">
        </div>
        <div class="form-group">
            <label class="form-group_label">
                Type
            </label>

            <select class="form-group_control" type="textbox" name="type">
                <?php foreach ($task::getType() as $type => $typeName) : ?>
                    <option value="<?= $type ?>" <?= $task->type === $type ? 'selected' : '' ?>><?= htmlspecialchars($typeName) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label class="form-group_label">
                Place
            </label>

            <input class="form-group_control" type="textbox" name="place" value="<?= htmlspecialchars($task->place) ?>">
        </div>
        <div class="form-group">
            <label class="form-group_label">
                Date&Time
            </label>

            <input class="form-group_control" type="datetime-local" name="datetime" value="<?= formatLocalTime($task->datetime) ?>">
        </div>
        <div class="form-group">
            <label class="form-group_label">
                Last
            </label>

            <select class="form-group_control" type="textbox" name="period">
                <?php foreach ($task::getPeriod() as $period) : ?>
                    <option value="<?= $period ?>" <?= $task->period === $period ? 'selected' : '' ?>><?= htmlspecialchars($period) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label class="form-group_label">
                Comment
            </label>

            <textarea class="form-group_control" name="comment" value="<?= htmlspecialchars($task->comment) ?>"></textarea>
        </div>
        <div class="form-group">
            <?php if ($task->isNew()) : ?>
                <button type="submit">
                    Add
                </button>
            <?php else : ?>
                <button type="submit">
                    Save
                </button>
                <button>
                    <a href="/calendar/">
                        Cancel
                    </a>
                </button>
            <?php endif; ?>

        </div>

    </form>
</div>