<?php

namespace App\Mixins;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

/** @mixin \Illuminate\Support\Collection */
class CollectionMixin
{

    public function paginate()
    {
        return function ($perPage = 15, $page = null, $options = []) {
            /** @var \Illuminate\Support\Collection $this */
            $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
            return (new LengthAwarePaginator(
                $this->forPage($page, $perPage)->values(),
                $this->count(),
                $perPage,
                $page,
                $options
            ))->withPath('');
        };
    }


    public function orderBy()
    {
        return function ($needle, $orientation) {
            /** @var \Illuminate\Support\Collection $this */
            if ($orientation)
                if ($orientation == 'desc')
                    return $this->sortByDesc($needle);
            return $this->sortBy($needle);
        };
    }

    public function recursive()
    {
        return function () {
            /** @var \Illuminate\Support\Collection $this */

            return $this->map(function ($value) {
                if (is_array($value)) {
                    return collect($value)->recursive();
                }
                if (is_object($value)) {
                    return collect($value)->recursive();
                }
                return $value;
            });
        };
    }
}
