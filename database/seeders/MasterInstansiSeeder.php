<?php

namespace Database\Seeders;

use App\Models\Instansi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterInstansiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Memulai seeding data master instansi...');

        $companies = [
            // BUMN - Keuangan & Perbankan
            'PT Bank Rakyat Indonesia (Persero) Tbk',
            'PT Bank Mandiri (Persero) Tbk',
            'PT Bank Negara Indonesia (Persero) Tbk (BNI)',
            'PT Bank Tabungan Negara (Persero) Tbk (BTN)',
            'PT Pegadaian (Persero)',
            'PT Permodalan Nasional Madani (Persero)',

            // BUMN - Energi & Pertambangan
            'PT Pertamina (Persero)',
            'PT Perusahaan Listrik Negara (Persero) (PLN)',
            'PT Perusahaan Gas Negara Tbk (PGN)',
            'PT Aneka Tambang Tbk (Antam)',
            'PT Bukit Asam Tbk',
            'PT Timah Tbk',
            'PT Inalum (Persero)',

            // BUMN - Konstruksi & Infrastruktur
            'PT Wijaya Karya (Persero) Tbk (WIKA)',
            'PT Waskita Karya (Persero) Tbk',
            'PT Adhi Karya (Persero) Tbk',
            'PT PP (Persero) Tbk',
            'PT Jasa Marga (Persero) Tbk',
            'PT Semen Indonesia (Persero) Tbk',

            // BUMN - Transportasi & Logistik
            'PT Garuda Indonesia (Persero) Tbk',
            'PT Kereta Api Indonesia (Persero) (KAI)',
            'PT Pelabuhan Indonesia (Persero) (Pelindo)',
            'PT Angkasa Pura I',
            'PT Angkasa Pura II',
            'Perum DAMRI',
            'PT POS Indonesia (Persero)',

            // BUMN - Lainnya
            'PT Telekomunikasi Indonesia (Persero) Tbk (Telkom)',
            'PT Pupuk Indonesia (Persero)',
            'PT Bio Farma (Persero)',
            'Perum BULOG',

            // Instansi & Lembaga Pemerintah
            'Bank Indonesia',
            'Otoritas Jasa Keuangan (OJK)',
            'Kementerian Keuangan Republik Indonesia',
            'Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi',
            'Kementerian BUMN',
            'Kementerian Kesehatan Republik Indonesia',
            'Kementerian Pekerjaan Umum dan Perumahan Rakyat (PUPR)',
            'Kementerian Komunikasi dan Informatika (Kominfo)',
            'Badan Perencanaan Pembangunan Nasional (Bappenas)',
            'Pemerintah Provinsi DKI Jakarta',
            'Pemerintah Provinsi Jawa Timur',
            'Pemerintah Provinsi Jawa Tengah',
            'Pemerintah Provinsi Jawa Barat',
            'Pemerintah Kota Surabaya',
            'Pemerintah Kabupaten Tuban',
            'Pemerintah Kabupaten Bojonegoro',
            'Pemerintah Kabupaten Lamongan',

            // Swasta - Perbankan & Keuangan
            'PT Bank Central Asia Tbk (BCA)',
            'PT Bank CIMB Niaga Tbk',
            'PT Bank Danamon Indonesia Tbk',
            'PT Bank Panin Tbk',
            'PT Bank OCBC NISP Tbk',

            // Swasta - Konglomerat & Grup Besar
            'PT Astra International Tbk',
            'Sinar Mas Group',
            'Salim Group',
            'Lippo Group',
            'Djarum Group',
            'Wings Group',
            'Japfa Comfeed Indonesia',

            // Swasta - Barang Konsumsi (FMCG)
            'PT Unilever Indonesia Tbk',
            'PT Indofood Sukses Makmur Tbk',
            'PT Mayora Indah Tbk',
            'PT Gudang Garam Tbk',
            'PT HM Sampoerna Tbk',
            'PT Santos Jaya Abadi (Kapal Api)',
            'PT Garudafood Putra Putri Jaya Tbk',
            'Danone Indonesia',

            // Swasta - Teknologi & E-commerce
            'PT GoTo Gojek Tokopedia Tbk',
            'Shopee Indonesia',
            'Lazada Indonesia',
            'Traveloka',
            'Bukalapak',
            'Ruangguru',
            'PT DCI Indonesia Tbk',
            'Blibli',
            'Grab Indonesia',

            // Swasta - Media
            'Kompas Gramedia Group',
            'MNC Media',
            'Emtek Group (SCTV, Indosiar)',
            'Trans Media (Trans TV, Trans7)',

            // Swasta - Otomotif & Manufaktur
            'PT Toyota Motor Manufacturing Indonesia',
            'PT Astra Honda Motor',
            'PT Yamaha Indonesia Motor Manufacturing',

            // Swasta - Sumber Daya Alam
            'PT Adaro Energy Tbk',
            'PT Freeport Indonesia',
            'PT Vale Indonesia Tbk',

            // Swasta - Ritel
            'PT Sumber Alfaria Trijaya Tbk (Alfamart)',
            'PT Indomarco Prismatama (Indomaret)',
            'PT Trans Retail Indonesia (Transmart)',
            'PT Hero Supermarket Tbk',
            'PT Matahari Department Store Tbk',
        ];

        foreach ($companies as $companyName) {
            // Gunakan firstOrCreate untuk menghindari duplikasi data
            Instansi::firstOrCreate(['nama' => $companyName]);
        }

        $this->command->info(count($companies) . ' data instansi berhasil ditambahkan atau sudah ada.');
    }
}

