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
    function generateKode($table, $field, $prefix, $digit = 3, $start = 1)
    {
        $last = DB::table($table)->where($field, 'like', $prefix . '-%')->orderByRaw(" CAST(SUBSTRING_INDEX($field, '-', -1) AS UNSIGNED) DESC ")->value($field);
        if (!$last) {
            $number = $start;
        } else {
            $explode = explode('-', $last);
            if (count($explode) == 2 && is_numeric($explode[1])) {
                $number = intval($explode[1]) + 1;
            } else {
                $number = $start;
            }
        }
        $number = str_pad($number, $digit, '0', STR_PAD_LEFT);
        return $prefix . '-' . $number;
    }
}
