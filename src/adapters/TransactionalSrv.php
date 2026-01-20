<?php

namespace App\adapters;

use App\domain\utils\TransactionalInterface;

class TransactionalSrv implements TransactionalInterface
{

    public function commit(): void
    {
        // TODO: Implement commit() method.
    }

    public function startTransaction(): void
    {
        // TODO: Implement startTransaction() method.
    }

    public function rollback(): void
    {
        // TODO: Implement rollback() method.
    }
}