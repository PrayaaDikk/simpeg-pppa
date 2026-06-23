import React, { useState, useMemo } from 'react';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
    CardDescription,
} from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { FileText, CalendarX, FileSpreadsheet } from 'lucide-react';
import { generateSuratCutiFromTemplate } from '@/utils/print-cuti';

// Import sub-komponen baru
import RekapFilter from './rekap-filter';
import RekapStats from './rekap-stats';
import { exportRekapCutiToExcel } from '@/utils/excel-rekap-cuti';

interface CutiRekapTabProps {
    allApprovedCutis: any[];
    pegawaiList: any[];
}

export default function CutiRekapTab({
    allApprovedCutis = [],
    pegawaiList = [],
}: CutiRekapTabProps) {
    const currentYear = new Date().getFullYear();
    const currentMonth = new Date().getMonth() + 1;

    // Local states untuk filter
    const [selectedBulan, setSelectedBulan] = useState<number | string>(
        currentMonth,
    );
    const [selectedTahun, setSelectedTahun] = useState<number>(currentYear);
    const [selectedPegawaiId, setSelectedPegawaiId] = useState<string>('all');

    // Mappings bulan Indonesia
    const namaBulan = [
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember',
    ];

    // Helper format Tanggal Indo
    const formatTanggalIndo = (dateString: string) => {
        if (!dateString) return '-';
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return '-';
        return date.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'short',
            year: 'numeric',
        });
    };

    // Filter Logic
    const filteredCutis = useMemo(() => {
        return allApprovedCutis.filter((cuti) => {
            const startRoute = cuti.tanggal_mulai
                ? new Date(cuti.tanggal_mulai)
                : null;
            if (!startRoute) return false;

            // 1. Filter Tahun
            const matchTahun = startRoute.getFullYear() === selectedTahun;

            // 2. Filter Bulan (Perbaikan penanganan string 'all' atau angka)
            const matchBulan =
                selectedBulan === 'all' ||
                String(selectedBulan) === 'all' ||
                startRoute.getMonth() + 1 === Number(selectedBulan);

            // 3. Filter Pegawai
            const matchPegawai =
                selectedPegawaiId === 'all' ||
                String(cuti.pegawai_id) === selectedPegawaiId;

            return matchTahun && matchBulan && matchPegawai;
        });
    }, [allApprovedCutis, selectedBulan, selectedTahun, selectedPegawaiId]);

    // Statistics Logic
    const statsData = useMemo(() => {
        const uniquePegawai = new Set(filteredCutis.map((c) => c.pegawai_id));
        const totalHari = filteredCutis.reduce(
            (acc, curr) => acc + (Number(curr.lama_cuti) || 0),
            0,
        );
        return {
            totalPegawaiCuti: uniquePegawai.size,
            totalHariCuti: totalHari,
            totalDokumen: filteredCutis.length,
        };
    }, [filteredCutis]);

    const handleExportExcel = () => {
        const textBulan =
            selectedBulan === 'all'
                ? 'Tahun'
                : namaBulan[Number(selectedBulan) - 1];
        exportRekapCutiToExcel(filteredCutis, textBulan, selectedTahun);
    };

    return (
        <div className="space-y-6">
            {/* Bagian Statistik Ringkasan */}
            <RekapStats
                totalPegawaiCuti={statsData.totalPegawaiCuti}
                totalHariCuti={statsData.totalHariCuti}
                totalDokumen={statsData.totalDokumen}
            />

            {/* Bagian Filter Kontrol */}
            <Card className="border-slate-100 bg-slate-50/40 shadow-xs">
                <CardContent className="p-4">
                    <RekapFilter
                        selectedBulan={selectedBulan as any}
                        setSelectedBulan={setSelectedBulan}
                        selectedTahun={selectedTahun}
                        setSelectedTahun={setSelectedTahun}
                        selectedPegawaiId={selectedPegawaiId}
                        setSelectedPegawaiId={setSelectedPegawaiId}
                        pegawaiList={pegawaiList}
                        namaBulan={namaBulan}
                    />
                </CardContent>
            </Card>

            {/* Bagian Utama Tabel Rekap menggunakan Shadcn */}
            <Card className="overflow-hidden border-slate-100 bg-white shadow-sm">
                <CardHeader className="border-b border-slate-50 p-5">
                    <div className="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <CardTitle className="text-sm font-bold tracking-tight text-slate-800">
                                Berkas Riwayat Cetak Rekapitulasi
                            </CardTitle>
                            <CardDescription className="text-xs font-medium text-slate-400">
                                Daftar seluruh permohonan resmi cuti pegawai
                                yang telah disetujui pimpinan.
                            </CardDescription>
                        </div>
                        <Button
                            disabled={filteredCutis.length === 0}
                            onClick={handleExportExcel}
                            variant="default"
                            className="h-9 gap-2 rounded-xl bg-emerald-600 px-4 text-xs font-bold text-white shadow-xs transition-all hover:bg-emerald-700 disabled:opacity-50"
                        >
                            <FileSpreadsheet className="h-4 w-4" />
                            Unduh Rekap (.xlsx)
                        </Button>
                    </div>
                </CardHeader>
                <CardContent className="p-0">
                    <div className="relative w-full overflow-auto">
                        <Table>
                            <TableHeader className="bg-slate-50/70 hover:bg-slate-50/70">
                                <TableRow className="border-slate-100">
                                    <TableHead className="w-[60px] text-center text-xs font-bold text-slate-600">
                                        No
                                    </TableHead>
                                    <TableHead className="text-xs font-bold text-slate-600">
                                        Pegawai
                                    </TableHead>
                                    <TableHead className="text-xs font-bold text-slate-600">
                                        Jenis Cuti
                                    </TableHead>
                                    <TableHead className="text-center text-xs font-bold text-slate-600">
                                        Durasi
                                    </TableHead>
                                    <TableHead className="text-xs font-bold text-slate-600">
                                        Rentang Waktu
                                    </TableHead>
                                    <TableHead className="pr-6 text-right text-xs font-bold text-slate-600">
                                        Aksi Berkas
                                    </TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {filteredCutis.length === 0 ? (
                                    <TableRow>
                                        <TableCell
                                            colSpan={6}
                                            className="h-40 text-center"
                                        >
                                            <div className="flex flex-col items-center justify-center space-y-2">
                                                <div className="rounded-full border border-slate-100 bg-slate-50 p-3">
                                                    <CalendarX className="h-6 w-6 text-slate-400" />
                                                </div>
                                                <p className="text-xs font-bold text-slate-500">
                                                    Data Rekapitulasi Kosong
                                                </p>
                                                <p className="text-[11px] font-medium text-slate-400">
                                                    Tidak ditemukan arsip cuti
                                                    disetujui pada filter
                                                    terpilih.
                                                </p>
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                ) : (
                                    filteredCutis.map((cuti, index) => (
                                        <TableRow
                                            key={cuti.id}
                                            className="border-slate-50 transition-colors hover:bg-slate-50/40"
                                        >
                                            <TableCell className="text-center text-xs font-semibold text-slate-500">
                                                {index + 1}
                                            </TableCell>
                                            <TableCell>
                                                <div className="space-y-0.5">
                                                    <p className="text-xs font-bold text-slate-800">
                                                        {cuti.pegawai?.nama ||
                                                            'Nama Tidak Ditemukan'}
                                                    </p>
                                                    <p className="text-[10px] font-medium text-slate-400">
                                                        NIP.{' '}
                                                        {cuti.pegawai?.nip ||
                                                            '-'}
                                                    </p>
                                                </div>
                                            </TableCell>
                                            <TableCell>
                                                <span className="inline-flex items-center rounded-lg border border-slate-200 bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-600 capitalize">
                                                    {cuti.jenis_cuti?.replace(
                                                        /_/g,
                                                        ' ',
                                                    ) || '-'}
                                                </span>
                                            </TableCell>
                                            <TableCell className="text-center text-xs font-bold text-indigo-600">
                                                {cuti.lama_cuti} Hari
                                            </TableCell>
                                            <TableCell>
                                                <div className="flex items-center gap-1.5 text-xs font-medium text-slate-600">
                                                    <span>
                                                        {formatTanggalIndo(
                                                            cuti.tanggal_mulai,
                                                        )}
                                                    </span>
                                                    <span className="text-[10px] font-bold text-slate-300">
                                                        s/d
                                                    </span>
                                                    <span>
                                                        {formatTanggalIndo(
                                                            cuti.tanggal_akhir,
                                                        )}
                                                    </span>
                                                </div>
                                            </TableCell>
                                            <TableCell className="pr-6 text-right">
                                                <Button
                                                    onClick={() =>
                                                        generateSuratCutiFromTemplate(
                                                            cuti,
                                                        )
                                                    }
                                                    variant="outline"
                                                    className="h-8 gap-1.5 rounded-xl border-slate-200 text-[11px] font-bold text-slate-700 shadow-xs transition-all hover:bg-slate-50 hover:text-slate-900"
                                                >
                                                    <FileText className="h-3.5 w-3.5 text-indigo-500" />
                                                    Cetak Ulang
                                                </Button>
                                            </TableCell>
                                        </TableRow>
                                    ))
                                )}
                            </TableBody>
                        </Table>
                    </div>
                </CardContent>
            </Card>
        </div>
    );
}
