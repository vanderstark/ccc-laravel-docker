<?php
namespace App\Services\Impact\Contracts;

interface ImpactInterface
{
    /**
     * @param string $disasterCode kode tipe bencana
     * @param array  $input        input simulasi
     * @return array [impact(array), affected(int), deaths(int), injured(int),
     *                displaced(int), damaged(int), destroyed(int),
     *                economic_m(float), severity(float)]
     */
    public function calculate(string $disasterCode, array $input): array;
}