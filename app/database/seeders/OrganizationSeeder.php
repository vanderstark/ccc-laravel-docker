<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $this->createOrg('POLRI', 'POLRI - Kepolisian Negara Republik Indonesia', 'polri');
        $this->createOrg('HANKAM', 'HANKAM - Pertahanan Keamanan Negara', 'hankam');
        $this->createOrg('PEMDA', 'Pemerintah Daerah', 'pemda');
        $this->createOrg('BNPB', 'Badan Nasional Penanggulangan Bencana', 'instansi');
    }

    protected function createOrg(string $code, string $nama, string $jenis): void
    {
        DB::table('organizations')->insertOrIgnore([
            'code' => $code,
            'nama' => $nama,
            'jenis' => $jenis,
            'deskripsi' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
