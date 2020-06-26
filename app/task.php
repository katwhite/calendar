<?php

namespace app;

use PDO;

class task
{
    const TYPE_MEETING = 'meeting';
    const TYPE_CALL = 'call';
    const TYPE_CONSULTATION = 'consultation';
    const TYPE_THING = 'thing';

    const STATUS_PENDING = 'pending';
    const STATUS_DONE = 'done';

    public $id;
    public $status = 'pending';
    public $type;
    public $subject;
    public $datetime;
    public $place;
    public $period;
    public $comment;

    protected static $pdo;

    public static function getType()
    {
        return [
            static::TYPE_MEETING => 'meeting',
            static::TYPE_CALL => 'call',
            static::TYPE_CONSULTATION => 'consult',
            static::TYPE_THING => 'thing',

        ];
    }

    public function getTextType()
    {
        $types = static::getType();
        if (isset($types[$this->type])) {
            return $types[$this->type];
        }
        return '-';
    }

    public static function getPeriod()
    {
        return [
            '0,5hr',
            '1hr',
            '2hr',
            '3hr',
            '4hr',

        ];
    }

    public static function getPdo()
    {
        if (empty(static::$pdo)) {


            $config = [
                'host' => '127.0.0.1',
                'dbname' => 'caldaruwu69',
                'user' => 'caldaruwu69',
                'passw' => 'F7yJaVhp',
            ];

            static::$pdo = new PDO('mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'], $config['user'], $config['passw']);

            static::$pdo->query('set names "utf8";');
        }
        return static::$pdo;
    }

    protected static function getWhereFromCondition(array $conditions = null)
    {
        $where = '';

        if ($conditions) {
            foreach ($conditions as $key => $value) {
                switch ($key) {
                    case 'date':
                        switch ($value) {
                            case 'today':
                                $where = 'DATE(`datetime`) = CURDATE()';
                                break;
                            case 'tomorrow':
                                $where = 'DATE(`datetime`) = CURDATE() + INTERVAL 1 DAY';
                                break;
                            case 'thisweek':
                                $where = 'WEEK(`datetime`) = WEEK(NOW())';
                                break;
                            case 'nextweek':
                                $where = 'WEEK(`datetime`) = WEEK(NOW() + INTERVAL 1 WEEK)';
                                break;
                            default:
                                $where = 'DATE(`datetime`) = "' . date('Y-m-d', $value) . '"';
                                break;
                        }
                        break;
                }
            }
        }

        return $where;
    }

    public static function getAll(array $conditions = null)
    {
        $where = static::getWhereFromCondition($conditions);

        $sql = static::getPdo()->prepare('select * from `tasks` ' . ($where ? 'where ' . $where : '') . ' order by `datetime` desc;');
        $sql->execute();
        $tasks = [];

        while ($task = $sql->fetchObject(task::class)) {
            $tasks[] = $task;
        }
        return $tasks;
    }

    public static function getAllPending(array $conditions = null)
    {

        $where = static::getWhereFromCondition($conditions);

        $sql = static::getPdo()->prepare('select * from `tasks` where `status` = :status ' . ($where ? 'and ' . $where : '') . ' order by `datetime` desc;');
        $sql->execute(
            [
                'status' => static::STATUS_PENDING,
            ]
        );
        $tasks = [];

        while ($task = $sql->fetchObject(task::class)) {
            $tasks[] = $task;
        }
        return $tasks;
    }

    public static function getAllDone(array $conditions = null)
    {

        $where = static::getWhereFromCondition($conditions);

        $sql = static::getPdo()->prepare('select * from `tasks` where `status` = :status ' . ($where ? 'and ' . $where : '') . ' order by `datetime` desc;');
        $sql->execute(
            [
                'status' => static::STATUS_DONE,
            ]
        );
        $tasks = [];

        while ($task = $sql->fetchObject(task::class)) {
            $tasks[] = $task;
        }
        return $tasks;
    }

    public static function getAllFailed(array $conditions = null)
    {

        $where = static::getWhereFromCondition($conditions);

        $sql = static::getPdo()->prepare('select * from `tasks` where `status` = "pending" and `datetime` < now() ' . ($where ? 'where ' . $where : '') . ' order by `datetime` desc;');
        $sql->execute();
        $tasks = [];

        while ($task = $sql->fetchObject(task::class)) {
            $tasks[] = $task;
        }
        return $tasks;
    }

    public static function getById($id)
    {
        $sql = static::getPdo()->prepare('select * from `tasks` where `id` = :id limit 1;');
        $sql->execute(
            [
                'id' => $id,
            ]
        );

        $task = $sql->fetchObject(task::class);
        return $task;
    }

    public function fill(array $values)
    {
        foreach ($values as $key => $value) {
            $this->$key = $value;
        }
    }

    public function save()
    {
        if ($this->validate()) {
            $data =  [
                'type' => $this->type,
                'status' => $this->status,
                'subject' => $this->subject,
                'place' => $this->place,
                'datetime' => $this->datetime,
                'period' => $this->period,
                'comment' => $this->comment,
            ];
            if ($this->id) {
                $data['id'] = $this->id;

                $sql = static::getPdo()->prepare('update `tasks` set 
                `type` = :type,
                `status` = :status,
                `subject` = :subject,
                `place` = :place,
                `datetime` = :datetime,
                `period` = :period,
                `comment` = :comment
                 where `id` = :id limit 1;');
            } else {
                $sql = static::getPdo()->prepare('insert into `tasks` set 
                `type` = :type,
                `status` = :status,
                `subject` = :subject,
                `place` = :place,
                `datetime` = :datetime,
                `period` = :period,
                `comment` = :comment;');
            }

            $sql->execute($data);
            return true;
        }
        return false;
    }

    public function validate()
    {
        return true;
    }

    public function isNew()
    {
        return !$this->id;
    }

    public function isDone()
    {
        return $this->status === static::STATUS_DONE;
    }

    public function isFailed()
    {
        $time = strtotime($this->datetime);
        return $this->status === static::STATUS_PENDING && $time < time();
    }
}
