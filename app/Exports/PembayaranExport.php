<?php

namespace App\Exports;

use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Pembelian;
use App\Models\Pengguna;
use App\Models\Penjualan;
use App\Models\Piutang;
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

class PembayaranExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithEvents,
    WithCustomStartCell,
    ShouldAutoSize
{
    protected Request $request;
    protected $totalNilai;
    protected $totalBayar;
    protected $totalSisa;


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

        $query = Pembayaran::query();

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

        if ($request->filled('customer')) {
            $query->where(
                'pelanggan',
                $request->customer
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
            'No Bayar',
            'Nota',
            'Pelanggan',
            'Nilai',
            'Bayar',
            'Sisa',
            'Kasir',
            'Keterangan'
        ];
    }

    /**
     * Mapping data
     */
    public function map($row): array
    {

        $this->totalNilai = $this->totalNilai + $row->nilai_nota ?? 0;
        $this->totalBayar = $this->totalBayar + $row->pembayaran ?? 0;
        $this->totalSisa = $this->totalSisa + $row->sisa ?? 0;

        return [
            $row->tanggal,
            $row->no_pembayaran,
            $row->nota,
            $row->customer?->nm_pelanggan ?? '-',
            (float) ($row->nilai_nota ?? 0),
            (float) ($row->pembayaran ?? 0),
            (float) ($row->sisa ?? 0),
            $row->kasir?->nama ?? '-',
            $row->keterangan ?? ''
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

                $sheet->mergeCells('A1:I1');

                $sheet->setCellValue(
                    'A1',
                    env('NAMA_TOKO')
                );

                $sheet->mergeCells('A2:I2');

                $sheet->setCellValue(
                    'A2',
                    env('ALAMAT_TOKO1')
                );

                $sheet->mergeCells('A3:I3');

                $sheet->setCellValue(
                    'A3',
                    'DAFTAR PIUTANG'
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



                $customer = '';
                if ($request->filled('customer')) {
                    $cust = Pelanggan::where('kd_pelanggan', $request->customer)->first();
                    $customer = $cust->nm_pelanggan ?? '';
                } else {
                    $customer = 'Semua Pelanggan';
                }


                // ==================================================
                // FILTER DISPLAY
                // ==================================================

                $sheet->mergeCells('A5:B5');
                $sheet->setCellValue(
                    'A5',
                    'Periode'
                );

                $sheet->mergeCells('C5:I5');
                $sheet->setCellValue(
                    'C5',
                    $periode
                );


                $sheet->mergeCells('A6:B6');
                $sheet->setCellValue(
                    'A6',
                    'Customer'
                );

                $sheet->mergeCells('C6:I6');
                $sheet->setCellValue(
                    'C6',
                    $customer
                );





                // ==================================================
                // STYLE JUDUL
                // ==================================================

                $sheet->getStyle('A1:I1')->applyFromArray([

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


                $sheet->getStyle('A2:I2')->applyFromArray([

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


                $sheet->getStyle('A3:I3')->applyFromArray([

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

                $sheet->getStyle('A5:I6')->applyFromArray([

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
                    "A{$headerRow}:I{$headerRow}"
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
                        "A11:I{$lastDataRow}"
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
                        "E11:G{$lastDataRow}"
                    )->getNumberFormat()
                        ->setFormatCode(
                            '"Rp" #,##0'
                        );
                }


                // ==================================================
                // TOTAL
                // ==================================================

                $sheet->setCellValue(
                    "D{$totalRow}",
                    'TOTAL'
                );

                $sheet->setCellValue(
                    "E{$totalRow}",
                    $this->totalNilai
                );

                $sheet->setCellValue(
                    "F{$totalRow}",
                    $this->totalBayar
                );

                $sheet->setCellValue(
                    "G{$totalRow}",
                    $this->totalSisa
                );



                $sheet->getStyle(
                    "D{$totalRow}:G{$totalRow}"
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
                    "E{$totalRow}:G{$totalRow}"
                )->getNumberFormat()
                    ->setFormatCode(
                        '"Rp" #,##0'
                    );


                // ==================================================
                // AUTOFILTER
                // ==================================================

                $sheet->setAutoFilter(
                    "A10:I{$lastDataRow}"
                );


                // ==================================================
                // FREEZE HEADER
                // ==================================================

                $sheet->freezePane('A11');


                // ==================================================
                // ALIGNMENT
                // ==================================================

                $sheet->getStyle(
                    "E11:G{$lastDataRow}"
                )->getAlignment()->setHorizontal(
                    Alignment::HORIZONTAL_RIGHT
                );


                $sheet->getStyle(
                    "A11:A{$lastDataRow}"
                )->getAlignment()->setHorizontal(
                    Alignment::HORIZONTAL_CENTER
                );


                $sheet->getStyle(
                    "H11:H{$lastDataRow}"
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
