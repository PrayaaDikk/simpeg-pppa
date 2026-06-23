import React from 'react';
import { AlertCircle, ArrowRight } from 'lucide-react';
import { Button } from '@/components/ui/button';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import Pagination from '@/components/ui/pagination-shared';

interface CurrentMonthKgbTableProps {
    kgbData: {
        data: any[];
        links: any[];
        current_page: number;
        per_page: number;
    };
    onProcessKgb: (kgb: any) => void;
    activeTab?: string;
}

export default function CurrentMonthKgbTable({
    kgbData,
    onProcessKgb,
    activeTab,
}: CurrentMonthKgbTableProps) {
    const records = kgbData?.data || [];
    const paginationLinks = kgbData?.links || [];
    const currentPage = kgbData?.current_page || 1;
    const perPage = kgbData?.per_page || 10;

    const formatTanggalShort = (dateString: string) => {
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return '-';
        return date.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'short',
            year: 'numeric',
        });
    };

    const formatRupiah = (angka: number) => {
        return `Rp ${(angka || 0).toLocaleString('id-ID')}`;
    };

    return (
        <div className="w-full bg-white">
            <div className="overflow-x-auto">
                <Table className="w-full border-collapse">
                    <TableHeader className="border-b border-slate-100 bg-slate-50/75">
                        <TableRow className="hover:bg-transparent">
                            <TableHead className="h-12 w-14 text-center text-xs font-bold tracking-wider text-slate-500 uppercase">
                                No
                            </TableHead>
                            <TableHead className="h-12 min-w-[240px] pl-4 text-left text-xs font-bold tracking-wider text-slate-500 uppercase">
                                Pegawai
                            </TableHead>
                            <TableHead className="h-12 min-w-[150px] text-left text-xs font-bold tracking-wider text-slate-500 uppercase">
                                Jabatan & Bidang
                            </TableHead>
                            <TableHead className="h-12 text-center text-xs font-bold tracking-wider text-slate-500 uppercase">
                                Gol. Baru
                            </TableHead>
                            <TableHead className="h-12 text-right text-xs font-bold tracking-wider text-slate-500 uppercase">
                                Gaji Pokok Baru
                            </TableHead>
                            <TableHead className="h-12 min-w-[160px] text-center text-xs font-bold tracking-wider text-slate-500 uppercase">
                                Jatuh Tempo KGB
                            </TableHead>
                            {!activeTab && activeTab !== 'rekap-bulanan' && (
                                <TableHead className="h-12 w-36 pr-6 text-right text-xs font-bold tracking-wider text-slate-500 uppercase">
                                    Aksi
                                </TableHead>
                            )}
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        {records.length === 0 ? (
                            <TableRow>
                                <TableCell
                                    colSpan={
                                        !activeTab &&
                                        activeTab !== 'rekap-bulanan'
                                            ? 7
                                            : 6
                                    }
                                    className="h-32 text-center"
                                >
                                    <div className="flex flex-col items-center justify-center gap-2 py-6 text-slate-400">
                                        <AlertCircle className="h-6 w-6 stroke-[1.5] text-slate-300" />
                                        <p className="text-xs font-medium">
                                            Tidak ada jadwal kenaikan berkala
                                            untuk periode ini.
                                        </p>
                                    </div>
                                </TableCell>
                            </TableRow>
                        ) : (
                            records.map((kgb: any, index: number) => (
                                <TableRow
                                    key={kgb.id || index}
                                    className="group h-16 border-b border-slate-100 transition-colors hover:bg-slate-50/50"
                                >
                                    {/* Nomor Urut */}
                                    <TableCell className="h-16 text-center text-xs font-semibold text-slate-400">
                                        {(currentPage - 1) * perPage +
                                            index +
                                            1}
                                    </TableCell>

                                    {/* Profil Utama Pegawai */}
                                    <TableCell className="h-16 pl-4">
                                        <div className="flex items-center gap-3">
                                            <Avatar className="h-9 w-9 border border-slate-100 bg-slate-50 shadow-xs transition-transform group-hover:scale-105">
                                                <AvatarFallback className="bg-slate-100 text-xs font-bold text-slate-600 uppercase">
                                                    {kgb.pegawai?.nama?.slice(
                                                        0,
                                                        2,
                                                    ) || '??'}
                                                </AvatarFallback>
                                            </Avatar>
                                            <div className="flex min-w-0 flex-col">
                                                <span className="truncate text-xs font-bold tracking-tight text-slate-800 transition-colors group-hover:text-blue-600">
                                                    {kgb.pegawai?.nama || '-'}
                                                </span>
                                                <span className="mt-0.5 font-mono text-[11px] font-medium text-slate-400">
                                                    NIP.{' '}
                                                    {kgb.pegawai?.nip || '-'}
                                                </span>
                                            </div>
                                        </div>
                                    </TableCell>

                                    {/* Informasi Jabatan & Bidang */}
                                    <TableCell className="h-16">
                                        <div className="flex min-w-0 flex-col">
                                            <span className="truncate text-xs font-semibold text-slate-700">
                                                {kgb.pegawai?.jabatan
                                                    ?.nama_jabatan || '-'}
                                            </span>
                                            <span className="mt-0.5 truncate text-[11px] font-medium text-slate-400">
                                                {kgb.pegawai?.bidang
                                                    ?.nama_bidang || '-'}
                                            </span>
                                        </div>
                                    </TableCell>

                                    {/* Badge Golongan */}
                                    <TableCell className="h-16 text-center">
                                        <span className="inline-flex min-w-[50px] items-center justify-center rounded-lg border border-slate-200/40 bg-slate-100 px-2.5 py-1 text-[11px] font-bold text-slate-700">
                                            {kgb.golongan_baru || '-'}
                                        </span>
                                    </TableCell>

                                    {/* Nominal Gaji Pokok */}
                                    <TableCell className="h-16 text-right">
                                        <span className="font-mono text-xs font-bold text-slate-800">
                                            {formatRupiah(
                                                Number(kgb.gaji_baru),
                                            )}
                                        </span>
                                    </TableCell>

                                    {/* Badge Tanggal Jatuh Tempo */}
                                    <TableCell className="h-16 text-center">
                                        <span className="inline-flex items-center gap-1.5 rounded-full border border-blue-100 bg-blue-50/70 px-2.5 py-1 text-[11px] font-bold text-blue-700 shadow-xs">
                                            <span className="h-1.5 w-1.5 animate-pulse rounded-full bg-blue-500"></span>
                                            {formatTanggalShort(
                                                kgb.kgb_berikutnya,
                                            )}
                                        </span>
                                    </TableCell>

                                    {/* Tombol Aksi */}
                                    {!activeTab &&
                                        activeTab !== 'rekap-bulanan' && (
                                            <TableCell className="h-16 pr-6 text-right">
                                                <Button
                                                    size="sm"
                                                    onClick={() =>
                                                        onProcessKgb(kgb)
                                                    }
                                                    className="inline-flex h-8 cursor-pointer items-center gap-1.5 rounded-xl bg-blue-600 px-3.5 text-xs font-bold text-white shadow-xs transition-all duration-200 hover:bg-blue-700 active:scale-95"
                                                >
                                                    Proses KGB
                                                    <ArrowRight className="h-3.5 w-3.5 transition-transform group-hover:translate-x-0.5" />
                                                </Button>
                                            </TableCell>
                                        )}
                                </TableRow>
                            ))
                        )}
                    </TableBody>
                </Table>
            </div>

            {/* Area Navigasi Halaman / Pagination */}
            {paginationLinks.length > 0 && (
                <div className="flex justify-end border-t border-slate-100 bg-slate-50/40 p-4">
                    <Pagination links={paginationLinks} />
                </div>
            )}
        </div>
    );
}
