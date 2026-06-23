import React from 'react';
import {
    MoreVertical,
    FileText,
    Edit3,
    Trash2,
    CheckCircle2,
    XCircle,
    AlertCircle,
} from 'lucide-react';
import { Button } from '@/components/ui/button';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { generateSuratKgbFromTemplate } from '@/utils/print-kgb';
import { router } from '@inertiajs/react';
import Pagination from '@/components/ui/pagination-shared';

interface KgbTableProps {
    kgbData: {
        data: any[];
        links: any[];
        current_page: number;
        per_page: number;
    };
    onEditAction: (kgb: any) => void;
    onDeleteAction: (kgb: any) => void;
}

export default function KgbTable({
    kgbData,
    onEditAction,
    onDeleteAction,
}: KgbTableProps) {
    // Definisikan data record & links dari objek pagination Laravel secara aman
    const records = kgbData?.data || [];
    const paginationLinks = kgbData?.links || [];
    const currentPage = kgbData?.current_page || 1;
    const perPage = kgbData?.per_page || 10;

    const handleUpdateStatus = (
        kgbId: number,
        newStatus: 'disetujui' | 'tidak disetujui',
    ) => {
        router.put(
            `/admin/kgb/${kgbId}/status`,
            { status_kgb: newStatus },
            { preserveScroll: true },
        );
    };

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

    const getStatusBadgeClass = (status: string) => {
        switch (status) {
            case 'telah diproses':
                return 'border-blue-100 bg-blue-50 text-blue-700';
            case 'disetujui':
                return 'border-emerald-100 bg-emerald-50 text-emerald-700';
            case 'tidak disetujui':
                return 'border-rose-100 bg-rose-50 text-rose-700';
            default:
                return 'border-amber-100 bg-amber-50 text-amber-700';
        }
    };

    return (
        <div className="w-full overflow-hidden rounded-xl border border-slate-100 bg-white shadow-xs">
            <Table>
                <TableHeader className="bg-slate-50/70">
                    <TableRow className="border-b border-slate-100 hover:bg-transparent">
                        <TableHead className="w-[60px] pl-6 text-center text-xs font-semibold text-slate-500">
                            No
                        </TableHead>
                        <TableHead className="w-[250px] text-xs font-semibold text-slate-500">
                            Pegawai
                        </TableHead>
                        <TableHead className="w-[150px] text-xs font-semibold text-slate-500">
                            Gaji Pokok
                        </TableHead>
                        <TableHead className="w-[150px] text-xs font-semibold text-slate-500">
                            TMT Berlaku
                        </TableHead>
                        <TableHead className="w-[160px] text-xs font-semibold text-slate-500">
                            KGB Selanjutnya
                        </TableHead>
                        <TableHead className="w-[130px] text-xs font-semibold text-slate-500">
                            Status
                        </TableHead>
                        <TableHead className="w-[60px] pr-6 text-right text-xs font-semibold text-slate-500">
                            Aksi
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    {records.length === 0 ? (
                        <TableRow className="hover:bg-transparent">
                            <TableCell colSpan={7} className="h-44 text-center">
                                <div className="flex flex-col items-center justify-center gap-2 text-slate-400">
                                    <AlertCircle className="h-8 w-8 stroke-[1.5] text-slate-300" />
                                    <p className="text-sm font-medium text-slate-500">
                                        Tidak ada riwayat berkas KGB ditemukan
                                    </p>
                                </div>
                            </TableCell>
                        </TableRow>
                    ) : (
                        records.map((kgb, index) => (
                            <TableRow
                                key={kgb.id}
                                className="border-b border-slate-100/60 transition-colors duration-200 hover:bg-slate-50/40"
                            >
                                <TableCell className="pl-6 text-center text-xs font-medium text-slate-400">
                                    {(currentPage - 1) * perPage + index + 1}
                                </TableCell>
                                <TableCell className="py-3.5">
                                    <div className="flex items-center gap-3">
                                        <Avatar className="shadow-3xs h-9 w-9 border border-slate-100">
                                            <AvatarFallback className="bg-blue-50 text-xs font-bold text-blue-600">
                                                {kgb.pegawai?.nama
                                                    ?.substring(0, 2)
                                                    .toUpperCase() || 'PG'}
                                            </AvatarFallback>
                                        </Avatar>
                                        <div className="flex min-w-0 flex-col">
                                            <span className="truncate font-semibold tracking-tight text-slate-700">
                                                {kgb.pegawai?.nama || '-'}
                                            </span>
                                            <span className="mt-0.5 text-[11px] font-medium text-slate-400">
                                                NIP. {kgb.pegawai?.nip || '-'}
                                            </span>
                                        </div>
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <div className="flex flex-col">
                                        <span className="text-xs font-semibold text-emerald-600">
                                            {formatRupiah(
                                                Number(kgb.gaji_baru),
                                            )}
                                        </span>
                                        <span className="mt-0.5 text-[11px] font-medium text-slate-400">
                                            Semula:{' '}
                                            {formatRupiah(
                                                Number(kgb.gaji_lama),
                                            )}
                                        </span>
                                    </div>
                                </TableCell>
                                <TableCell className="text-xs font-medium text-slate-600">
                                    {formatTanggalShort(kgb.tmt_gaji_baru)}
                                </TableCell>
                                <TableCell>
                                    <span className="inline-flex items-center gap-1 text-xs font-semibold text-slate-600">
                                        {formatTanggalShort(kgb.kgb_berikutnya)}
                                    </span>
                                </TableCell>
                                <TableCell>
                                    <span
                                        className={`shadow-3xs inline-flex items-center rounded-full border px-2.5 py-0.5 text-[11px] font-bold tracking-wide capitalize ${getStatusBadgeClass(kgb.status_kgb)}`}
                                    >
                                        {kgb.status_kgb}
                                    </span>
                                </TableCell>
                                <TableCell className="pr-6 text-right">
                                    <DropdownMenu>
                                        <DropdownMenuTrigger asChild>
                                            <Button
                                                variant="ghost"
                                                className="h-8 w-8 rounded-lg p-0 hover:bg-slate-100"
                                            >
                                                <MoreVertical className="h-4 w-4 text-slate-500" />
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent
                                            align="end"
                                            className="w-48 rounded-xl border border-slate-100 bg-white p-1 shadow-md"
                                        >
                                            {kgb.status_kgb === 'menunggu' && (
                                                <>
                                                    <DropdownMenuItem
                                                        onClick={() =>
                                                            handleUpdateStatus(
                                                                kgb.id,
                                                                'disetujui',
                                                            )
                                                        }
                                                        className="flex cursor-pointer items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-emerald-600 hover:bg-emerald-50"
                                                    >
                                                        <CheckCircle2 className="h-4 w-4 text-emerald-500" />{' '}
                                                        Setujui Berkas
                                                    </DropdownMenuItem>
                                                    <DropdownMenuItem
                                                        onClick={() =>
                                                            handleUpdateStatus(
                                                                kgb.id,
                                                                'tidak disetujui',
                                                            )
                                                        }
                                                        className="flex cursor-pointer items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50"
                                                    >
                                                        <XCircle className="h-4 w-4 text-rose-500" />{' '}
                                                        Tolak Berkas
                                                    </DropdownMenuItem>
                                                </>
                                            )}
                                            {kgb.status_kgb === 'disetujui' && (
                                                <DropdownMenuItem
                                                    onClick={() =>
                                                        generateSuratKgbFromTemplate(
                                                            kgb,
                                                        )
                                                    }
                                                    className="flex cursor-pointer items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-blue-600 hover:bg-blue-50"
                                                >
                                                    <FileText className="h-4 w-4 text-blue-500" />{' '}
                                                    Cetak Surat KGB
                                                </DropdownMenuItem>
                                            )}
                                            <DropdownMenuItem
                                                onClick={() =>
                                                    onEditAction(kgb)
                                                }
                                                className="flex cursor-pointer items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50"
                                            >
                                                <Edit3 className="h-4 w-4 text-slate-400" />{' '}
                                                Edit Berkas SK
                                            </DropdownMenuItem>
                                            <DropdownMenuItem
                                                onClick={() =>
                                                    onDeleteAction(kgb)
                                                }
                                                className="flex cursor-pointer items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50"
                                            >
                                                <Trash2 className="h-4 w-4 text-rose-400" />{' '}
                                                Hapus Record
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>
                            </TableRow>
                        ))
                    )}
                </TableBody>
            </Table>

            {paginationLinks.length > 0 && (
                <div className="border-t border-slate-100 bg-slate-50/30 p-3.5">
                    <Pagination links={paginationLinks} />
                </div>
            )}
        </div>
    );
}
