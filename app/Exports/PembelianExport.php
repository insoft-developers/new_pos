<?php

namespace App\Exports;

use App\Models\Pelanggan;
use App\Models\Pembelian;
use App\Models\Pengguna;
use App\Models\Penjualan;
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

class PembelianExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithEvents,
    WithCustomStartCell,
    ShouldAutoSize
{
    protected Request $request;
    protected $totalSubtotal;
    protected $totalDiscount;
    protected $totalPembelian;
    

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
     * Ambil data penjualan
     */
    public function collection()
    {
        $request = $this->request;

        $query = Pembelian::query();

        // ==========================
        // FILTER TANGGAL DARI
        // ==========================

        if ($request->filled('tanggal_dari')) {
            $query->whereDate(
                'tanggal',
                '>=',
                $request->tanggal_dari
            );
        }

        // ==========================
        // FILTER TANGGAL SAMPAI
        // ==========================

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate(
                'tanggal',
                '<=',
                $request->tanggal_sampai
            );
        }

        // ==========================
        // FILTER CUSTOMER
        // ==========================

        if ($request->filled('supplier')) {
            $query->where(
                'kd_supplier',
                $request->supplier
            );
        }

       
        // ==========================
        // FILTER KASIR
        // ==========================

        if ($request->filled('kasir')) {
            $query->where(
                'kd_user',
                $request->kasir
            );
        }

        return $query
            ->orderBy('tanggal', 'desc')
            ->get();
    }

    /**
     * Header tabel
     */
    public function headings(): array
    {
        return [
            'Tanggal',
            'Nota',
            'Supplier',
            'Subtotal',
            'Diskon',
            'Pembelian',
            'Kasir',
        ];
    }

    /**
     * Mapping data
     */
    public function map($row): array
    {

        $this->totalSubtotal = $this->totalSubtotal + $row->subtotal ?? 0;
        $this->totalDiscount = $this->totalDiscount + $row->total_discount ?? 0;
        $this->totalPembelian = $this->totalPembelian + $row->total_pembelian ?? 0;
        
        return [
            $row->tanggal,
            $row->nota,
            $row->supplier?->nm_supplier ?? '-',
            (float) ($row->subtotal ?? 0),
            (float) ($row->total_discount ?? 0),
            (float) ($row->total_pembelian ?? 0),
            $row->kasir?->nama ?? '-',
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

                $sheet->mergeCells('A1:G1');

                $sheet->setCellValue(
                    'A1',
                    env('NAMA_TOKO')
                );

                $sheet->mergeCells('A2:G2');

                $sheet->setCellValue(
                    'A2',
                    env('ALAMAT_TOKO1')
                );

                $sheet->mergeCells('A3:G3');

                $sheet->setCellValue(
                    'A3',
                    'DAFTAR PEMBELIAN'
                );


                // ==================================================
                // FILTER
                // ==================================================

                $tanggalDari = 'Semua';

                if ($request->filled('tanggal_dari')) {
                    $tanggalDari = date(
                        'd-m-Y',
                        strtotime($request->tanggal_dari)
                    );
                }

                $tanggalSampai = 'Semua';

                if ($request->filled('tanggal_sampai')) {
                    $tanggalSampai = date(
                        'd-m-Y',
                        strtotime($request->tanggal_sampai)
                    );
                }

                $periode =
                    $tanggalDari .
                    ' s/d ' .
                    $tanggalSampai;


                  
                $supplier = '';
                if($request->filled('supplier')){
                    $xsupplier = Supplier::where('kd_supplier',$request->supplier)->first(); 
                    $supplier = $xsupplier->nm_supplier ?? '';
                } else {
                    $supplier = 'Semua Supplier';
                }


               

                $kasir = '';
                if($request->filled('kasir')){
                    $xkasir = Pengguna::where('kd_pengguna',$request->kasir)->first(); 
                    $kasir = $xkasir->nama ?? '';
                } else {
                    $kasir = 'Semua Kasir';
                }


                // ==================================================
                // FILTER DISPLAY
                // ==================================================

                $sheet->mergeCells('A5:B5');
                $sheet->setCellValue(
                    'A5',
                    'Periode'
                );

                $sheet->mergeCells('C5:G5');
                $sheet->setCellValue(
                    'C5',
                    $periode
                );


                $sheet->mergeCells('A6:B6');
                $sheet->setCellValue(
                    'A6',
                    'Supplier'
                );

                $sheet->mergeCells('C6:G6');
                $sheet->setCellValue(
                    'C6',
                    $supplier
                );


                
                $sheet->mergeCells('A7:B7');
                $sheet->setCellValue(
                    'A7',
                    'Kasir'
                );

                $sheet->mergeCells('C7:G7');
                $sheet->setCellValue(
                    'C7',
                    $kasir
                );


                // ==================================================
                // STYLE JUDUL
                // ==================================================

                $sheet->getStyle('A1:G1')->applyFromArray([

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


                $sheet->getStyle('A2:G2')->applyFromArray([

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


                $sheet->getStyle('A3:G3')->applyFromArray([

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

                $sheet->getStyle('A5:G7')->applyFromArray([

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


                $sheet->getStyle('A5:B7')->applyFromArray([

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
                    "A{$headerRow}:G{$headerRow}"
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

                $totalRow =
                    $lastDataRow + 1;


                // ==================================================
                // BORDER DATA
                // ==================================================

                if ($totalData > 0) {

                    $sheet->getStyle(
                        "A11:G{$lastDataRow}"
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
                        "D11:H{$lastDataRow}"
                    )->getNumberFormat()
                        ->setFormatCode(
                            '"Rp" #,##0'
                        );
                }


                // ==================================================
                // TOTAL
                // ==================================================

                $sheet->setCellValue(
                    "C{$totalRow}",
                    'TOTAL'
                );

                $sheet->setCellValue(
                    "D{$totalRow}",
                    $this->totalSubtotal
                );

                $sheet->setCellValue(
                    "E{$totalRow}",
                    $this->totalDiscount
                );

                $sheet->setCellValue(
                    "F{$totalRow}",
                    $this->totalPembelian
                );

               

                $sheet->getStyle(
                    "C{$totalRow}:F{$totalRow}"
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
                    "D{$totalRow}:F{$totalRow}"
                )->getNumberFormat()
                    ->setFormatCode(
                        '"Rp" #,##0'
                    );


                // ==================================================
                // AUTOFILTER
                // ==================================================

                $sheet->setAutoFilter(
                    "A10:G{$lastDataRow}"
                );


                // ==================================================
                // FREEZE HEADER
                // ==================================================

                $sheet->freezePane('A11');


                // ==================================================
                // ALIGNMENT
                // ==================================================

                $sheet->getStyle(
                    "D11:F{$lastDataRow}"
                )->getAlignment()->setHorizontal(
                    Alignment::HORIZONTAL_RIGHT
                );


                $sheet->getStyle(
                    "A11:A{$lastDataRow}"
                )->getAlignment()->setHorizontal(
                    Alignment::HORIZONTAL_CENTER
                );


                $sheet->getStyle(
                    "I11:I{$lastDataRow}"
                )->getAlignment()->setHorizontal(
                    Alignment::HORIZONTAL_CENTER
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
                // PRINT AREA
                // ==================================================

                $sheet->getPageMargins()->setTop(0.5);
                $sheet->getPageMargins()->setRight(0.3);
                $sheet->getPageMargins()->setBottom(0.5);
                $sheet->getPageMargins()->setLeft(0.3);

                // $sheet->getPageSetup()->setPrintArea(
                //     "A1:J{$totalRow}"
                // );
            },

        ];
    }
}
