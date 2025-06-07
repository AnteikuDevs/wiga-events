<?php

function WigaTableResponse($data,$draw, $total)
{
    return [
        'data' => $data,
        'draw' => $draw,
        'recordsTotal' => $total,
        'recordsFiltered' => $total,
        'status' => $total > 0,
        'message' => $total > 0 ? 'OK' : 'Tidak ada data'
    ];
}

function WigaTable($class, $request, callable $callbackFilterLike, array $columns = [])
{
    if (is_string($class)) {
        if (!class_exists($class)) {
            throw new Exception("Class $class tidak ditemukan");
        }
        $instance = new $class;
    } elseif (is_object($class) && method_exists($class, 'get')) {
        $instance = $class;
    } else {
        throw new Exception("Parameter class tidak valid");
    }

    $query = $instance->newQuery();

    if ($searchValue = $request->input('search.value')) {
        $query->where(function ($query) use ($searchValue, $callbackFilterLike) {
            $callbackFilterLike($query, $searchValue);
        });
    }

    $orderColumnIndex = $request->input('order.0.column');
    $orderDirection = $request->input('order.0.dir', 'asc');

    if ($orderColumnIndex !== null && isset($columns[$orderColumnIndex])) {
        $orderColumn = $columns[$orderColumnIndex];
        $query->orderBy($orderColumn, $orderDirection);
    } else {
        $query->orderBy('id', 'asc');
    }

    $totalFiltered = $query->count();

    $start = (int) $request->input('start', 0);
    $length = (int) $request->input('length', 10);

    if ($length == -1) {
        $data = $query->get();
    } else {
        $data = $query->offset($start)->limit($length)->get();
    }

    return WigaTableResponse($data, (int) $request->input('draw', 1), $totalFiltered);
}
