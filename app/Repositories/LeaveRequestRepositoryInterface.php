<?php

namespace App\Repositories;

use App\Models\LeaveRequest;

interface LeaveRequestRepositoryInterface
{
    public function index($request);

    public function create(array $data): ?LeaveRequest;

    public function update(array $data, int $id): int;
}