<?php namespace App\Cursor;

use Norm\Collection;
use Norm\Cursor\ICursor;
use PDO;
use Exception;

class PDOCursor implements ICursor
{

    /**
     * [$criteria description]
     *
     * @var [type]
     */
    protected $criteria;

    protected $match;

    /**
     * PDO statement
     *
     * @var \PDOStatement
     */
    protected $statement;

    /**
     * [$row description]
     *
     * @var [type]
     */
    protected $row = 0;

    /**
     * Single fetch of current row from PDO statement
     *
     * @var array
     */
    protected $current;

    /**
     * Sort the collection
     *
     * @var array
     */
    protected $sort = array();

    /**
     * Construct cursor for particular statement
     *
     * @param \PDOStatement $statement PDO statement
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
        $this->criteria = $this->prepareCriteria($collection->criteria ?: array());
    }

    /**
     * Get valid next row if available
     * @return array NULL if not available
     */
    public function getNext()
    {
        if ($this->valid()) {
            return $this->current();
        }
    }

    /**
     * Get current row
     *
     * @return array
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * Move to next row
     */
    public function next()
    {
        $this->row++;
    }

    /**
     * Get current key for row
     * @return int Current row key
     */
    public function key()
    {
        return $this->row;
    }

    /**
     * Check if current row is available
     * @return bool
     */
    public function valid()
    {
        $statement     = $this->getStatement();
        $this->current = $statement->fetch(PDO::FETCH_ASSOC);
        $valid         = ($this->current !== false);

        return $valid;
    }

    /**
     * Limit
     *
     * @param string $num
     *
     * @return PDOCursor
     */
    public function limit($num = null)
    {
        if (func_num_args() === 0) {
            return $this->limit;
        }
        $this->limit = (int) $num;
        return $this;
    }

    /**
     * Skip
     *
     * @param string $offset
     *
     * @return PDOCursor
     */
    public function skip($offset = null)
    {
        if (func_num_args() === 0) {
            return $this->skip;
        }
        $this->skip = (int) $offset;
        return $this;
    }

    /**
     * Match
     *
     * @param string $query
     *
     * @return PDOCursor
     */
    // public function match($fields)
    // {
    //     $this->match = $fields;

    //     return $this;
    // }

    public function match($q) {
        $this->match = $q;
        return $this;
    }

    /**
     * Prepare Criteria
     *
     * @param array $criteria
     *
     * @return array
     */
    public function prepareCriteria($criteria)
    {
        if (isset($criteria['$id'])) {
            $criteria['id'] = $criteria['$id'];
            unset($criteria['$id']);
        }

        return $criteria;
    }

    /**
     * Get current statement
     *
     * @return PDOStatement
     */
    public function getStatement()
    {
        if (is_null($this->statement)) {

            $sql = 'SELECT * FROM '. "`" . $this->collection->name . "`";

            $wheres = array();
            $matches = array();
            $data = array();
            $matchOrs = array();

            foreach ($this->criteria as $key => $value) {
                $wheres[] = $this->collection->connection->getDialect()->grammarExpression($key, $value, $data);
            }

            if (! is_null($this->match)) {
                $schema = $this->collection->schema();

                $i = 0;
                foreach ($schema as $key => $value) {
                    $matchOrs[] = $key.' LIKE :m'.$i;
                    $data['m'.$i] = '%'.$this->match.'%';
                    $i++;
                }

                $wheres[] = '('.implode(' OR ', $matchOrs).')';
            }

            if (! empty($matches) or ! empty($wheres)) {
                $sql .= ' WHERE ';
            }

            if (! empty($matches)) {
                $sql .= implode(' AND ', $matches);
            }

            if (count($wheres)) {
                $sql .= implode(' AND ', $wheres);
            }

            if (! empty($this->sort)) {
                $sql .= $this->buildSort($this->sort);
            }
            
            if (! empty($this->limit)) {
                $sql .= ' LIMIT ' . $this->limit;
            }

            if (! empty($this->skip)) {
                $sql .= ' OFFSET ' . $this->skip;
            }
            $this->statement = $this->collection->connection->getRaw()->prepare($sql);
            $this->statement->execute($data);
            
        }
        return $this->statement;
    }

    protected function buildWhere($wheres, $sql)
    {
        if (count($wheres)) {
            $sql .= ' WHERE '.implode(' AND ', $wheres);
        }

        return $sql;
    }

    protected function buildSort($sort)
    {
        $this->sort = array();
        $sql = ' ORDER BY ';
        $sorts = array();

        foreach ($sort as $field => $method) {
            if ($method > 0) { // ascending
                $method = 'ASC';
            }

            if ($method < 0) { // descending
                $method = 'DESC';
            }

            $sorts[] = '`' . $field . '` ' . $method;
        }

        return $sql . implode(', ', $sorts);
    }

    /**
     * Rewind to the first row
     *
     * Do nothing because PDOStatement cannot be rewinded
     */
    public function rewind()
    {
        // noop
    }

    /**
     * Sort
     *
     * @param array $fields
     *
     * @return PDOCursor
     */
    public function sort(array $fields = array())
    {
        $this->sort = $fields;

        return $this;
    }

    /**
     * Count
     *
     * @param  boolean $foundOnly
     *
     * @return int
     */
    public function count($foundOnly = false)
    {
        $sql = 'SELECT COUNT(1) AS c FROM '. $this->collection->name;

        $wheres = array();
        $data = array();
        $matchOrs = array();

        // if ($foundOnly) {
            foreach ($this->criteria as $key => $value) {
                $wheres[] = $this->collection->connection->getDialect()->grammarExpression($key, $value, $data);
            }

            if (! is_null($this->match)) {
                $schema = $this->collection->schema();

                $i = 0;
                foreach ($schema as $key => $value) {
                    $matchOrs[] = $key.' LIKE :m'.$i;
                    $data['m'.$i] = '%'.$this->match.'%';
                    $i++;
                }

                $wheres[] = '('.implode(' OR ', $matchOrs).')';
            }

            if (count($wheres)) {
                $sql .= ' WHERE '.implode(' AND ', $wheres);
            }
        // }
        $statement = $this->collection->connection->getRaw()->prepare($sql);

        $statement->execute($data);

        $fetched = $statement->fetch(PDO::FETCH_OBJ);
            // var_dump($fetched);
            // exit;

        return $fetched->c;
    }

}
