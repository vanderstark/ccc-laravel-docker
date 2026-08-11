<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KurikulumSeeder extends Seeder
{
    public function run(): void
    {
        // Levels (Sespimmen → Sespimti sesuai PDF poin 2g)
        $levels = [
            ['nama' => 'Sespimmen', 'tingkat' => 'menengah', 'deskripsi' => 'Sekolah Staf dan Pimpinan Menengah Polri — pengembangan kepemimpinan tingkat menengah', 'durasi_hari' => 90],
            ['nama' => 'Sespimti', 'tingkat' => 'tinggi', 'deskripsi' => 'Sekolah Staf dan Pimpinan Tinggi Polri — pengembangan kepemimpinan tingkat tinggi', 'durasi_hari' => 120],
            ['nama' => 'Sespim', 'tingkat' => 'pertama', 'deskripsi' => 'Sekolah Staf dan Pimpinan Polri — dasar kepemimpinan strategis', 'durasi_hari' => 60],
        ];

        foreach ($levels as $l) {
            DB::table('kurikulum_levels')->updateOrInsert(
                ['nama' => $l['nama']],
                $l
            );
        }

        // Mapping skenario per level
        $menengah = DB::table('kurikulum_levels')->where('nama', 'Sespimmen')->value('id');
        $tinggi = DB::table('kurikulum_levels')->where('nama', 'Sespimti')->value('id');
        $pertama = DB::table('kurikulum_levels')->where('nama', 'Sespim')->value('id');

        $mappings = [
            // Sespim (dasar)
            [$pertama, 'bencana', 'banjir', 'Banjir Bandang', 4, 'Koordinasi dasar penanganan bencana & evakuasi'],
            [$pertama, 'bencana', 'gempa', 'Gempa Bumi', 4, 'Komunikasi krisis & perintah evakuasi'],
            // Sespimmen (menengah)
            [$menengah, 'siber', 'serangan-siber', 'Serangan Siber Infrastruktur', 6, 'Menilai dampak siber & koordinasi BSSN/Polri'],
            [$menengah, 'sosial', 'disinformasi', 'Disinformasi & Hoax Viral', 6, 'Manajemen rumor, klarifikasi, dan counter-narrative'],
            [$menengah, 'bencana', 'tsunami', 'Tsunami Pesisir', 6, 'Komando evakuasi multi-kabupaten & logistik'],
            [$menengah, 'militer', 'terorisme', 'Serangan Terorisme', 8, 'Pengamanan VIP & respon krisis teror'],
            // Sespimti (tinggi)
            [$tinggi, 'kepemimpinan', 'krisis-kepercayaan', 'Krisis Kepercayaan Publik', 8, 'Strategi pemulihan kepercayaan institusi'],
            [$tinggi, 'sosial', 'agenda-nasional', 'Pengamanan Agenda Nasional', 8, 'Pengamanan event nasional & sinergi TNI-Polri'],
            [$tinggi, 'militer', 'konflik', 'Konflik Sosial Skala Besar', 8, 'Operasi pengendalian massa & negosiasi'],
            [$tinggi, 'bencana', 'multi-bencana', 'Bencana Ganda Beruntun', 10, 'Manajemen krisis multi-titik skala nasional'],
        ];

        foreach ($mappings as $m) {
            DB::table('kurikulum_mappings')->updateOrInsert(
                ['kurikulum_level_id' => $m[0], 'kode_skenario' => $m[2]],
                [
                    'tipe_skenario' => $m[1],
                    'nama_skenario' => $m[3],
                    'jam_pelatihan' => $m[4],
                    'objektif' => $m[5],
                ]
            );
        }
    }
}
