<?php

namespace App\Exports;

use App\Models\KuesionerAnswer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder; // [BARU] Interface untuk binding manual
use PhpOffice\PhpSpreadsheet\Cell\Cell; // [BARU] Class Cell
use PhpOffice\PhpSpreadsheet\Cell\DataType; // [BARU] Class DataType
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder; // [BARU] Class Default Binder
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Support\Facades\Schema;

// [PENTING] Tambahkan 'extends DefaultValueBinder' dan 'implements WithCustomValueBinder'
class RespondenExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting, WithCustomValueBinder
{
    protected $columns;

    public function __construct()
    {
        $this->columns = Schema::getColumnListing((new KuesionerAnswer())->getTable());
    }

    /**
    * Mengambil data dari database
    */
    public function collection()
    {
        return KuesionerAnswer::with(['alumni.user', 'alumni.prodi'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
    * Menentukan Judul Kolom
    */
    public function headings(): array
    {
        $customHeadings = [
            'No',
            'Kode Prodi', // Kolom B
            'NPM',        // Kolom C (Angka Panjang)
            'Nama Alumni',
            'NIK',        // Kolom E (Angka Panjang)
            'NPWP',       // Kolom F (Angka Panjang)
            'Nomor HP',   // Kolom G (Angka Panjang)
            'Email',
            'Tahun Lulus',
        ];

        return array_merge($customHeadings, $this->columns);
    }

    /**
    * Mapping data per baris
    */
    public function map($answer): array
    {
        static $no = 0;
        $no++;

        $alumni = $answer->alumni;
        $user = $alumni ? $alumni->user : null;
        $prodi = $alumni ? $alumni->prodi : null;

        $kodeProdi = $alumni->kode_prodi ?? ($prodi->kode_prodi ?? '-');

        // Pastikan semua data angka panjang dikirim sebagai string
        $customData = [
            $no,
            $kodeProdi, 
            (string) ($alumni->npm ?? '-'),
            $user->name ?? ($alumni->name ?? '-'),
            (string) ($alumni->nik ?? '-'),
            (string) ($alumni->npwp ?? '-'),
            (string) ($alumni->no_hp ?? ($alumni->telp ?? '-')),
            $user->email ?? ($alumni->email ?? '-'),
            $alumni->tahun_lulus ?? '-',
        ];

        $kuesionerData = [];
        foreach ($this->columns as $col) {
            $kuesionerData[] = $answer->{$col};
        }

        return array_merge($customData, $kuesionerData);
    }

    /**
     * [BARU] BINDING NILAI SECARA EKSPLISIT
     * Fungsi ini memaksa Excel menerima data sebagai TEXT murni (DataType::TYPE_STRING)
     * Ini mencegah Excel memotong angka digit ke-16 menjadi 0.
     */
    public function bindValue(Cell $cell, $value)
    {
        // Daftar Kolom yang berisi angka panjang (NPM, NIK, NPWP, HP)
        // C = NPM, E = NIK, F = NPWP, G = No HP
        if (in_array($cell->getColumn(), ['C', 'E', 'F', 'G'])) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);
            return true;
        }

        // Untuk kolom lain, gunakan cara standar
        return parent::bindValue($cell, $value);
    }

    /**
     * Format Tampilan Kolom
     */
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_TEXT,
        ];
    }
}