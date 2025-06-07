<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait HasFilter
{
    /**
     * Scope a query to filter results by LIKE
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $searchText
     * @param array<int, string> $columns
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterLike(Builder $query, ?string $searchText, array $columns)
    {
        if (empty($searchText)) {
            return $query;
        }

        // search each word that separate by space
        return $query->where(function ($query) use ($searchText, $columns) {
            $words = explode(' ', $searchText);
            foreach ($words as $word) {
                $query->where(function ($query) use ($word, $columns) {
                    foreach ($columns as $column) {
                        $query->orWhere($column, 'like', '%' . $word . '%');
                    }
                });
            }
        });
    }

    /**
     * Scope a query to filter results by regex
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $searchText
     * @param array<int, string> $columns
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterWord(Builder $query, string $searchText, array $columns)
    {
        if (empty($searchText)) {
            return $query;
        }

        // search each word that separate by space
        return $query->where(function ($query) use ($searchText, $columns) {
            $words = explode(' ', $searchText);
            foreach ($words as $word) {
                $regexSafeSearch = preg_quote($word);
                $query->where(function ($query) use ($regexSafeSearch, $columns) {
                    foreach ($columns as $column) {
                        $query->orWhere($column, 'REGEXP', '[[:<:]]' . $regexSafeSearch . '[[:>:]]');
                    }
                });
            }
        });
    }
}
