<?php
namespace Atlas\Orm\Table;

use Aura\Sql\ExtendedPdo;
use Aura\SqlQuery\Common\SelectInterface;
use Aura\SqlQuery\Common\SubselectInterface;

class TableSelect implements SubselectInterface
{
    /**
     *
     * The SelectInterface being decorated.
     *
     * @var mixed
     *
     */
    protected $select;

    protected $connection;

    protected $colNames;

    protected $with = [];

    public function __construct(
        SelectInterface $select,
        ExtendedPdo $connection,
        array $colNames
    ) {
        $this->select = $select;
        $this->connection = $connection;
        $this->colNames = $colNames;
    }

    /**
     *
     * Decorate the underlying Select object's __toString() method so that
     * (string) casting works properly.
     *
     * @return string
     *
     */
    public function __toString()
    {
        return $this->select->__toString();
    }

    /**
     *
     * Forwards method calls to the underlying Select object.
     *
     * @param string $method The call to the underlying Select object.
     *
     * @param array $params Params for the method call.
     *
     * @return mixed If the call returned the underlying Select object (a fluent
     * method call) return *this* object instead to emulate the fluency;
     * otherwise return the result as-is.
     *
     */
    public function __call($method, $params)
    {
        $result = call_user_func_array([$this->select, $method], $params);
        return ($result === $this->select) ? $this : $result;
    }

    public function getColNames()
    {
        return $this->colNames;
    }

    // subselect interface
    public function getStatement()
    {
        return $this->select->getStatement();
    }

    // subselect interface
    public function getBindValues()
    {
        return $this->select->getBindValues();
    }

    /**
     *
     * Fetches a sequential array of rows from the database; the rows
     * are represented as associative arrays.
     *
     * @return array
     *
     */
    public function fetchAll()
    {
        return $this->connection->fetchAll(
            $this->select->getStatement(),
            $this->select->getBindValues()
        );
    }

    /**
     *
     * Fetches an associative array of rows from the database; the rows
     * are represented as associative arrays. The array of rows is keyed
     * on the first column of each row.
     *
     * N.b.: if multiple rows have the same first column value, the last
     * row with that value will override earlier rows.
     *
     * @return array
     *
     */
    public function fetchAssoc()
    {
        return $this->connection->fetchAssoc(
            $this->select->getStatement(),
            $this->select->getBindValues()
        );
    }

    /**
     *
     * Fetches the first column of rows as a sequential array.
     *
     * @return array
     *
     */
    public function fetchCol()
    {
        return $this->connection->fetchCol(
            $this->select->getStatement(),
            $this->select->getBindValues()
        );
    }

    /**
     *
     * Fetches one row from the database as an associative array.
     *
     * @return array
     *
     */
    public function fetchOne()
    {
        return $this->connection->fetchOne(
            $this->select->getStatement(),
            $this->select->getBindValues()
        );
    }

    /**
     *
     * Fetches an associative array of rows as key-value pairs (first
     * column is the key, second column is the value).
     *
     * @param array $values Values to bind to the query.
     *
     * @return array
     *
     */
    public function fetchPairs()
    {
        return $this->connection->fetchPairs(
            $this->select->getStatement(),
            $this->select->getBindValues()
        );
    }

    /**
     *
     * Fetches the very first value (i.e., first column of the first row).
     *
     * @return mixed
     *
     */
    public function fetchValue()
    {
        return $this->connection->fetchValue(
            $this->select->getStatement(),
            $this->select->getBindValues()
        );
    }
}
