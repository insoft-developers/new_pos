<?php

namespace App\Exports;

use App\Models\Barang;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class BarangExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithEvents,
    WithCustomStartCell,
    ShouldAutoSize
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Posisi awal header tabel
     */
    public function startCell(): string
    {
        return 'A10';
    }

    /**
     * Ambil data barang
     */
    public function collection()
    {
        $request = $this->request;

        $query = Barang::query();

        // FILTER KATEGORI
        if ($request->filled('kategori')) {
            $query->where(
                'kd_kategori',
                $request->kategori
            );
        }

        // FILTER SUPPLIER
        if ($request->filled('supplier')) {
            $query->where(
                'kd_supplier',
                $request->supplier
            );
        }

        // FILTER STOK HABIS
        if ($request->stok == 'habis') {
            $query->where('stok', '<=', 0);
        }

        return $query
            ->orderBy('nm_barang', 'asc')
            ->get();
    }

    /**
     * Header tabel
     */
    public function headings(): array
    {
        return [
            'Kode Barang',
            'Barcode',
            'Nama Barang',
            'Kode Kategori',
            'Harga Beli',
            'Harga Jual',
            'Satuan',
            'Stok',
            'Konversi',
            'Kode Supplier',
            'Harga Reseller',
        ];
    }

    /**
     * Mapping data
     */
    public function map($row): array
    {
        return [
            $row->kd_barang,
            $row->barcode,
            $row->nm_barang,
            $row->kd_kategori,
            (float) ($row->harga_beli ?? 0),
            (float) ($row->harga_jual ?? 0),
            $row->satuan,
            (float) ($row->stok ?? 0),
            (float) ($row->konversi ?? 0),
            $row->supplier?->nm_supplier ?? '',
            (float) ($row->harga_reseller ?? 0),
        ];
    }

    /**
     * Styling Excel
     */
    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $request = $this->request;

                // ==================================================
                // IDENTITAS TOKO
                // ==================================================

                $sheet->mergeCells('A1:K1');

                $sheet->setCellValue(
                    'A1',
                    env('NAMA_TOKO')
                );

                $sheet->mergeCells('A2:K2');

                $sheet->setCellValue(
                    'A2',
                    env('ALAMAT_TOKO1')
                );

                $sheet->mergeCells('A3:K3');

                $sheet->setCellValue(
                    'A3',
                    'DAFTAR MASTER BARANG'
                );


                // ==================================================
                // FILTER DISPLAY
                // ==================================================

                $kategori = 'Semua Kategori';

                if ($request->filled('kategori')) {
                    $kategori = $request->kategori;
                }

                $supplier = 'Semua Supplier';

                if ($request->filled('supplier')) {
                    $supp = Supplier::where('kd_supplier', $request->supplier)->first();
                    $supplier = $supp->nm_supplier;

                }

                $stok = 'Semua Stok';

                if ($request->stok == 'habis') {
                    $stok = 'Stok Habis';
                }


                // ==================================================
                // FILTER
                // ==================================================

                $sheet->mergeCells('A5:B5');

                $sheet->setCellValue(
                    'A5',
                    'Kategori'
                );

                $sheet->mergeCells('C5:K5');

                $sheet->setCellValue(
                    'C5',
                    $kategori
                );


                $sheet->mergeCells('A6:B6');

                $sheet->setCellValue(
                    'A6',
                    'Supplier'
                );

                $sheet->mergeCells('C6:K6');

                $sheet->setCellValue(
                    'C6',
                    $supplier
                );


                // ==================================================
                // STYLE JUDUL
                // ==================================================

                $sheet->getStyle('A1:K1')->applyFromArray([

                    'font' => [
                        'bold' => true,
                        'size' => 18,
                    ],

                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_CENTER,

                        'vertical' =>
                            Alignment::VERTICAL_CENTER,
                    ],

                ]);


                $sheet->getStyle('A2:K2')->applyFromArray([

                    'font' => [
                        'size' => 11,
                    ],

                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_CENTER,

                        'vertical' =>
                            Alignment::VERTICAL_CENTER,
                    ],

                ]);


                $sheet->getStyle('A3:K3')->applyFromArray([

                    'font' => [
                        'bold' => true,
                        'size' => 15,
                    ],

                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_CENTER,

                        'vertical' =>
                            Alignment::VERTICAL_CENTER,
                    ],

                ]);


                // ==================================================
                // STYLE FILTER
                // ==================================================

                $sheet->getStyle('A5:K6')->applyFromArray([

                    'borders' => [

                        'allBorders' => [

                            'borderStyle' =>
                                Border::BORDER_THIN,

                        ],

                    ],

                    'alignment' => [

                        'vertical' =>
                            Alignment::VERTICAL_CENTER,

                    ],

                ]);


                $sheet->getStyle('A5:B6')->applyFromArray([

                    'font' => [
                        'bold' => true,
                    ],

                    'fill' => [

                        'fillType' =>
                            Fill::FILL_SOLID,

                        'startColor' => [
                            'rgb' => 'E9ECEF',
                        ],

                    ],

                ]);


                // ==================================================
                // HEADER TABEL
                // ==================================================

                $headerRow = 10;

                $sheet->getStyle(
                    "A{$headerRow}:K{$headerRow}"
                )->applyFromArray([

                    'font' => [
                        'bold' => true,
                    ],

                    'fill' => [

                        'fillType' =>
                            Fill::FILL_SOLID,

                        'startColor' => [
                            'rgb' => 'D9EAF7',
                        ],

                    ],

                    'alignment' => [

                        'horizontal' =>
                            Alignment::HORIZONTAL_CENTER,

                        'vertical' =>
                            Alignment::VERTICAL_CENTER,

                    ],

                    'borders' => [

                        'allBorders' => [

                            'borderStyle' =>
                                Border::BORDER_THIN,

                        ],

                    ],

                ]);


                // ==================================================
                // JUMLAH DATA
                // ==================================================

                $data = $this->collection();

                $totalData = $data->count();

                $lastDataRow =
                    $headerRow +
                    $totalData;


                // ==================================================
                // BORDER DATA
                // ==================================================

                if ($totalData > 0) {

                    $sheet->getStyle(
                        "A11:K{$lastDataRow}"
                    )->applyFromArray([

                        'borders' => [

                            'allBorders' => [

                                'borderStyle' =>
                                    Border::BORDER_THIN,

                            ],

                        ],

                        'alignment' => [

                            'vertical' =>
                                Alignment::VERTICAL_CENTER,

                        ],

                    ]);

                }


                // ==================================================
                // FORMAT RUPIAH
                // ==================================================

                if ($totalData > 0) {

                    $sheet->getStyle(
                        "E11:F{$lastDataRow}"
                    )->getNumberFormat()
                        ->setFormatCode(
                            '"Rp" #,##0'
                        );


                    $sheet->getStyle(
                        "K11:K{$lastDataRow}"
                    )->getNumberFormat()
                        ->setFormatCode(
                            '"Rp" #,##0'
                        );

                }


                // ==================================================
                // TOTAL
                // ==================================================

                $totalRow =
                    $lastDataRow + 1;


                $totalHargaBeli =
                    $data->sum('harga_beli');

                $totalHargaJual =
                    $data->sum('harga_jual');

                $totalStok =
                    $data->sum('stok');

                $totalHargaReseller =
                    $data->sum('harga_reseller');


                $sheet->setCellValue(
                    "D{$totalRow}",
                    'TOTAL'
                );

                $sheet->setCellValue(
                    "E{$totalRow}",
                    $totalHargaBeli
                );

                $sheet->setCellValue(
                    "F{$totalRow}",
                    $totalHargaJual
                );

                $sheet->setCellValue(
                    "H{$totalRow}",
                    $totalStok
                );

                $sheet->setCellValue(
                    "K{$totalRow}",
                    $totalHargaReseller
                );


                $sheet->getStyle(
                    "D{$totalRow}:K{$totalRow}"
                )->applyFromArray([

                    'font' => [
                        'bold' => true,
                    ],

                    'fill' => [

                        'fillType' =>
                            Fill::FILL_SOLID,

                        'startColor' => [
                            'rgb' => 'E9ECEF',
                        ],

                    ],

                    'borders' => [

                        'allBorders' => [

                            'borderStyle' =>
                                Border::BORDER_THIN,

                        ],

                    ],

                ]);


                $sheet->getStyle(
                    "E{$totalRow}:F{$totalRow}"
                )->getNumberFormat()
                    ->setFormatCode(
                        '"Rp" #,##0'
                    );


                $sheet->getStyle(
                    "K{$totalRow}"
                )->getNumberFormat()
                    ->setFormatCode(
                        '"Rp" #,##0'
                    );


                // ==================================================
                // AUTOFILTER
                // ==================================================

                $sheet->setAutoFilter(
                    "A10:K{$lastDataRow}"
                );


                // ==================================================
                // FREEZE HEADER
                // ==================================================

                $sheet->freezePane('A11');


                // ==================================================
                // ALIGNMENT
                // ==================================================

                $sheet->getStyle(
                    "E11:F{$lastDataRow}"
                )->getAlignment()->setHorizontal(
                    Alignment::HORIZONTAL_RIGHT
                );


                $sheet->getStyle(
                    "K11:K{$lastDataRow}"
                )->getAlignment()->setHorizontal(
                    Alignment::HORIZONTAL_RIGHT
                );


                $sheet->getStyle(
                    "H11:I{$lastDataRow}"
                )->getAlignment()->setHorizontal(
                    Alignment::HORIZONTAL_RIGHT
                );


                // ==================================================
                // ROW HEIGHT
                // ==================================================

                $sheet->getRowDimension(1)->setRowHeight(28);

                $sheet->getRowDimension(2)->setRowHeight(20);

                $sheet->getRowDimension(3)->setRowHeight(25);


                // ==================================================
                // PRINT SETTING
                // ==================================================

                $sheet->getPageSetup()->setOrientation(
                    PageSetup::ORIENTATION_LANDSCAPE
                );

                $sheet->getPageSetup()->setPaperSize(
                    PageSetup::PAPERSIZE_A4
                );

                $sheet->getPageSetup()->setFitToWidth(1);

                $sheet->getPageSetup()->setFitToHeight(0);

                $sheet->getPageSetup()->setHorizontalCentered(
                    true
                );


                // ==================================================
                // PRINT MARGIN
                // ==================================================

                $sheet->getPageMargins()->setTop(0.5);

                $sheet->getPageMargins()->setRight(0.3);

                $sheet->getPageMargins()->setBottom(0.5);

                $sheet->getPageMargins()->setLeft(0.3);

            },

        ];
    }
}