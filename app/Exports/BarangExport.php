<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BarangExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    protected $barangs;
    protected $rowNumber = 0;

    public function __construct($barangs)
    {
        $this->barangs = $barangs;
    }

    /**
     * Collection data yang akan di-export
     */
    public function collection()
    {
        return $this->barangs;
    }

    /**
     * Mapping data per row
     */
    public function map($barang): array
    {
        $this->rowNumber++;
        
        return [
            $this->rowNumber,                               // No
            $barang->nama_barang,                          // Nama Barang
            $barang->jenis_barang,                         // Jenis Barang
            $barang->panjang,                              // Panjang
            $barang->lebar,                                // Lebar
            $barang->tinggi,                               // Tinggi
            $barang->panjang . ' x ' . $barang->lebar . ' x ' . $barang->tinggi, // Dimensi
            $barang->stok_gudang,                          // Stok Gudang
            $barang->created_at ? $barang->created_at->format('d/m/Y H:i') : '-', // Dibuat
            $barang->updated_at ? $barang->updated_at->format('d/m/Y H:i') : '-', // Diupdate
        ];
    }

    /**
     * Headings untuk Excel
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Barang',
            'Jenis Barang',
            'Panjang (cm)',
            'Lebar (cm)',
            'Tinggi (cm)',
            'Dimensi',
            'Stok Gudang',
            'Dibuat Tanggal',
            'Diupdate Tanggal',
        ];
    }

    /**
     * Styling untuk Excel
     */
    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '059669'], // Green-600
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Auto-size untuk semua kolom
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Tinggi baris header
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Border untuk semua data
        $lastRow = $this->rowNumber + 1;
        $sheet->getStyle("A1:J{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        // Alignment untuk kolom tertentu
        $sheet->getStyle("A2:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // No
        $sheet->getStyle("D2:F{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Dimensi
        $sheet->getStyle("H2:H{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Stok

        // Zebra striping (baris genap abu-abu muda)
        for ($row = 2; $row <= $lastRow; $row++) {
            if ($row % 2 == 0) {
                $sheet->getStyle("A{$row}:J{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F3F4F6'], // Gray-100
                    ],
                ]);
            }
        }

        return [];
    }

    /**
     * Lebar kolom
     */
    public function columnWidths(): array
    {
        return [
            'A' => 6,   // No
            'B' => 30,  // Nama Barang
            'C' => 15,  // Jenis Barang
            'D' => 12,  // Panjang
            'E' => 12,  // Lebar
            'F' => 12,  // Tinggi
            'G' => 20,  // Dimensi
            'H' => 12,  // Stok Gudang
            'I' => 18,  // Dibuat
            'J' => 18,  // Diupdate
        ];
    }

    /**
     * Nama sheet
     */
    public function title(): string
    {
        return 'Daftar Barang & Stok';
    }
}