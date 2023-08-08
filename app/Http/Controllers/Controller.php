<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;



    public function addSerialNumber(LengthAwarePaginator $paginator)
    {
        $currentPage = $paginator->currentPage();
        $perPage = $paginator->perPage();
        $items = $paginator->items();

        $items = collect($items)->map(function ($item, $index) use ($currentPage, $perPage) {
            $item->serialNumber = ($currentPage - 1) * $perPage + $index + 1;
            return $item;
        });

        $paginator->setCollection($items);

        return $paginator;
    }
}
