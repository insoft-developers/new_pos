<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('generateKode')) {

    /**
     * Generate kode otomatis.
     *
     * @param string $table      Nama tabel
     * @param string $field      Field primary key
     * @param string $prefix     Prefix kode (BR, SPL, CUS, dll)
     * @param int    $digit      Jumlah digit angka
     *
     * @return string
     */
    function generateKode($table, $field, $prefix, $digit = 7)
    {
        $last = DB::table($table)
            ->orderBy($field, 'DESC')
            ->value($field);

        if (!$last) {
            $number = 1000000;
        } else {

            $explode = explode('-', $last);

            if (count($explode) == 2) {
                $number = intval($explode[1]) + 1;
            } else {
                $number = 1000000;
            }
        }

        return $prefix . '-' . str_pad($number, $digit, '0', STR_PAD_LEFT);
    }
}