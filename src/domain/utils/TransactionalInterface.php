<?php

namespace App\domain\utils;

interface TransactionalInterface
{
    public function commit(): void;
    public function startTransaction(): void;
    public function rollback(): void;
}