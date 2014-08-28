<?php namespace App\Connection;

use Norm\Collection;
use Norm\Model;
use App\Cursor\PDOCursor;
use Norm\Schema\DateTime;
use Norm\Schema\Object;
use Norm\Connection;
use PDO;

class PDOConnection extends Connection
{

    protected $dialect;

    public function initialize($options) {
        $this->options = $options;

        if (isset($options['dsn'])) {
            $dsn = $options['dsn'];
        }

        if ($options['prefix'] === 'sqlite') {
            $dsn = 'sqlite:'.$options['database'];
        } else {
            $dsnArray = array();

            foreach ($options as $key => $value) {
                if ($key === 'driver' || $key === 'prefix' || $key === 'username' || $key === 'password' || $key === 'name' || $key === 'dialect') {
                    continue;
                }
                $dsnArray[] = "$key=$value";
            }

            $dsn = $options['prefix'].':'.implode(';', $dsnArray);
        }

        if (isset($options['username'])) {
            $this->raw = new PDO($dsn, $options['username'], $options['password']);
        } else {
            $this->raw = new PDO($dsn);
        }

        $this->raw->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->raw->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        if (isset($options['dialect'])) {
            $Dialect = $options['dialect'];
        } else {
            $Dialect = '\\Norm\\Dialect\\SQLDialect';
        }

        $this->dialect = new $Dialect($this);

    }

    public function listCollections() {
        return $this->dialect->listCollections();
    }

    /**
     * [save description]
     * @param  Collection $collection [description]
     * @param  Model      $model      [description]
     * @return bool        if success return true else return false
     */
    public function save(Collection $collection, Model $model) {
        if (!empty($this->options['autocreate'])) {
            $this->dialect->prepareCollection($collection);
        }

        $collectionName = $collection->name;
        $data = $this->marshall($model->dump());
        $result = false;

        if (is_null($model->getId())) {
            $id = $this->dialect->insert($collectionName, $data);

            if ($id) {
                $model->setId($id);
                $result = $model;
            }
        } else {
            $data['id'] = $model->getId();
            $result = $this->dialect->update($collectionName, $data);

            if ($result) {
                $result = $model;
            }
        }

        return $result;
    }

    public function query(Collection $collection) {
        if (!empty($this->options['autocreate'])) {
            $this->dialect->prepareCollection($collection);
        }

        return new PDOCursor($collection);
    }

    public function prepare(Collection $collection, $object) {
        $newObject = array();

        if (isset($object['id'])) {
            $newObject['$id'] = $object['id'];
        }

        foreach ($object as $key => $value) {
            if ($key === 'id') continue;
            if ($key[0] === '_') $key[0] = '$';

            $newObject[$key] = $value;
        }

        return $newObject;
    }

    public function remove(Collection $collection, $model) {
        if (!empty($this->options['autocreate'])) {
            $this->dialect->prepareCollection($collection);
        }

        $collectionName = $collection->name;

        $sql = 'DELETE FROM '.$collectionName.' WHERE ' . "`" . 'id' . "`" . '= :id';

        $statement = $this->getRaw()->prepare($sql);
        $result = $statement->execute(array(
            'id' => $model->getId()
        ));

        return $result;
    }

    public function getDialect() {
        return $this->dialect;
    }
}
