<?php
namespace Atlas\Orm\Table;

use Aura\SqlQuery\Common\Insert;
use Aura\SqlQuery\Common\Update;
use Aura\SqlQuery\Common\Delete;
use PDOStatement;

class TableEvents implements TableEventsInterface
{
    public function beforeInsert(TableInterface $table, RowInterface $row)
    {
    }

    public function modifyInsert(TableInterface $table, RowInterface $row, Insert $insert)
    {
    }

    public function afterInsert(TableInterface $table, RowInterface $row, Insert $insert, PDOStatement $pdoStatement)
    {
    }

    public function beforeUpdate(TableInterface $table, RowInterface $row)
    {
    }

    public function modifyUpdate(TableInterface $table, RowInterface $row, Update $update)
    {
    }

    public function afterUpdate(TableInterface $table, RowInterface $row, Update $update, PDOStatement $pdoStatement)
    {
    }

    public function beforeDelete(TableInterface $table, RowInterface $row)
    {
    }

    public function modifyDelete(TableInterface $table, RowInterface $row, Delete $delete)
    {
    }

    public function afterDelete(TableInterface $table, RowInterface $row, Delete $delete, PDOStatement $pdoStatement)
    {
    }

}
