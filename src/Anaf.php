<?php

namespace Danvaly\AnafSearch;

use Danvaly\AnafSearch\Models\Company;
use Illuminate\Support\Collection;

/**
 * Implementare API ANAF V6
 * https://webservicesp.anaf.ro/PlatitorTvaRest/api/v6/
 *
 * @package Danvaly\AnafSearch
 */
class Anaf
{
    /** @var array CIFs List */
    protected array $cifs = [];


    /**
     * Add one or more cifs
     *
     * @param array|string $cifs
     * @param string|null  $date
     *
     * @return $this
     */
    public function addCif(array|string $cifs, string $date = null): Anaf
    {
        // If not have set date return today
        if (is_null($date)) {
            $date = date('Y-m-d');
        }

        // Convert to array
        if (!is_array($cifs)) {
            $cifs = [$cifs];
        }

        foreach ($cifs as $cif) {
            // Keep only numbers from CIF
            $cif = preg_replace('/\D/', '', $cif);

            // Add cif to list
            $this->cifs[] = [
                "cui"  => $cif,
                "data" => $date,
            ];
        }

        return $this;
    }

    /**
     * @return Collection
     * @throws Exceptions\LimitExceeded
     * @throws Exceptions\RequestFailed
     * @throws Exceptions\ResponseFailed
     */
    public function get(): Collection
    {
        $companies = [];
        $results = Http::call($this->cifs);
        foreach ($results as $result) {
            $companies[] = new Company(new Parser($result));
        }
        return collect($companies);
    }

    /**
     * @return Company
     * @throws Exceptions\LimitExceeded
     * @throws Exceptions\RequestFailed
     * @throws Exceptions\ResponseFailed
     */
    public function first(): Company
    {
        $results = Http::call($this->cifs);
        return new Company(new Parser($results[0]));
    }

    /**
     * @return Collection
     * @throws Exceptions\LimitExceeded
     * @throws Exceptions\RequestFailed
     * @throws Exceptions\ResponseFailed
     */
    public static function search($cifs, string $date = null): Collection
    {
        $anaf = new self;
        $anaf->addCif($cifs, $date);
        return $anaf->get();
    }
}
