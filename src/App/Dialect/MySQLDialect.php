<?php namespace App\Dialect;

use App\Dialect\SQLDialect;
use Norm\Model;

class MySQLDialect extends SQLDialect
{

    public function grammarExpression($exp, $value, &$data) {

        $exp = explode('!', $exp);
        $key = $exp[0];
        $op = (isset($exp[1])) ? $exp[1] : '=';
        switch($op) {
            case 'ne':
                $op = '!=';
                break;
            case 'gt':
                $op = '>';
                break;
            case 'gte':
                $op = '>=';
                break;
            case 'lt':
                $op = '<';
                break;
            case 'lt':
                $op = '<=';
                break;
        }

        if (strtoupper($op) == 'LIKE') {
            $this->expressionCounter++;
            $data['f'.$this->expressionCounter] = $value;
            return "`" . $key . "`" . ' ' . $op . " '%" . ':f' . $this->expressionCounter . "%'";
        }

        if ($op == 'in') {
            $fgroup = array();
            foreach ($value as $k => $v) {
                $v1 = $v;
                if ($v instanceof Model) {
                    $v1 = $v['$id'];
                }
                $this->expressionCounter++;
                $data['f'.$this->expressionCounter] = $v1;
                $fgroup[] = ':f'.$this->expressionCounter;
            }
            if (empty($fgroup)) {
                return '(1)';
            }
            return $key . ' ' . $op . ' ('.implode(', ', $fgroup).')';
        } else {
            $this->expressionCounter++;
            $data['f'.$this->expressionCounter] = $value;
            return "`" . $key . "`" . ' ' . $op . ' :f' . $this->expressionCounter;
        }
    }

    public function execute($sql, $data) {
        $newData = array();

        foreach ($data as $key => $value) {
            if (in_array($key, array('id', '$id', '_id'))) {
                continue;
            }

            $newData[':'.$key] = $value;
        }

        $statement = $this->raw->prepare($sql);

        if (isset($data['id'])) {
            $newData[':id'] = $data['id'];
        }

        $result = $statement->execute($newData);
        return $this->raw->lastInsertId();
    }

    public function grammarInsert($collectionName, $data){

        $fields = array();
        $placeholders = array();

        foreach ($data as $key => $value) {
            if ($key === '$id') {
                continue;
            }

            if ($key[0] === '$') {
                $k = '_'.substr($key, 1);
                $data[$k] = $value;
                unset($data[$key]);
            } else {
                $k = $key;
                $sets[] = $k.' = :'.$k;
            }

            $fields[] = $k;
            $placeholders[] = ':'.$k;
        }

        $sql = 'INSERT INTO ' . "`" . $collectionName . "`" . ' ('."`".implode("`, `", $fields)."`".') VALUES ('.implode(', ', $placeholders).')';
        return $sql;
    }

}

