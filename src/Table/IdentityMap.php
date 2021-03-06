<?php
namespace Atlas\Orm\Table;

use Atlas\Orm\Exception;
use SplObjectStorage;

class IdentityMap
{
    /**
     * @var array
     */
    protected $serialToRow = [];

    /**
     * @var SplObjectStorage
     */
    protected $rowToSerial;

    /**
     * @var SplObjectStorage
     */
    protected $initial;

    public function __construct()
    {
        $this->rowToSerial = new SplObjectStorage();
        $this->initial = new SplObjectStorage();
    }

    /**
     * @param RowInterface $row
     */
    public function setRow(RowInterface $row, array $initial, array $primaryKey)
    {
        if ($this->hasRow($row)) {
            throw Exception::rowAlreadyMapped();
        }

        $primary = [];
        foreach ($primaryKey as $primaryCol) {
            $primary[$primaryCol] = $row->$primaryCol;
        }

        $serial = $this->getSerial($primary);
        $this->serialToRow[$serial] = $row;
        $this->rowToSerial[$row] = $serial;
        $this->initial[$row] = $initial;
    }

    /**
     * @param RowInterface $row
     * @return boolean
     */
    public function hasRow(RowInterface $row)
    {
        return isset($this->rowToSerial[$row]);
    }

    /**
     * @param mixed $primary
     * @return Row
     */
    public function getRow(array $primary)
    {
        $serial = $this->getSerial($primary);
        if (! isset($this->serialToRow[$serial])) {
            return false;
        }

        return $this->serialToRow[$serial];
    }

    /**
     *
     * This is a ghetto hack to serialize a composite primary key to a string,
     * so it can be used for array key lookups. It works just as well for
     * single-value keys as well.
     *
     * All it does it implode() the primary values with a pipe (to make it
     * easier for people to see the separator) and an ASCII "unit separator"
     * character (to include something that is unlikely to be used in a real
     * primary-key value, and thus help prevent the serial string from being
     * subverted).
     *
     * WARNING: You should sanitize your primary-key values to disallow ASCII
     * character 31 (hex 1F) to keep the lookup working properly. This is only
     * a problem with non-integer keys.
     *
     * WARNING: Null, false, and empty-string key values are treated as
     * identical by this algorithm. That means these values are interchangeable
     * and are not differentiated. You should sanitize your primary-key values
     * to disallow null, false, and empty-string values. This is only a problem
     * with non-integer keys.
     *
     * WARNING: The serial string version of the primary key depends on the
     * values always being in the same order. E.g., `['foo' => 1, 'bar' => 2]`
     * will result in a different serial than `['bar' => 2, 'foo' => 1]`, even
     * though the key-value pairs themselves are the same.
     *
     */
    public function getSerial(array $primary)
    {
        $sep = "|\x1F"; // a pipe, and ASCII 31 ("unit separator")
        return $sep . implode($sep, $primary). $sep;
    }

    public function setInitial(RowInterface $row)
    {
        if (! $this->hasRow($row)) {
            throw Exception::rowNotMapped();
        }

        $this->initial[$row] = $row->getArrayCopy();
    }

    public function getInitial(RowInterface $row)
    {
        if (! $this->hasRow($row)) {
            throw Exception::rowNotMapped();
        }

        return $this->initial[$row];
    }
}
