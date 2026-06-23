import React from 'react';
import {
    Table,
    TableHeader,
    TableBody,
    TableHead,
    TableRow,
    TableCell,
} from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import {
    Trash2,
    Printer,
    FileText,
    CheckCircle2,
    XCircle,
    Edit2,
} from 'lucide-react';

interface CutiTableProps {
    data: any[];
    currentPage: number;
    perPage: number;
    onDetail: (cuti: any) => void;
    onEdit: (cuti: any) => void; // Tambahkan callback ini
    onDelete: (cuti: any) => void;
    onPrint: (cuti: any) => void;
    onToggleStatus: (id: number, currentStatus: string) => void;
    mappingJenisCuti: Record<string, string>;
}

export default function CutiTable({
    data,
    currentPage = 1,
    perPage = 10,
    onDetail,
    onEdit,
    onDelete,
    onPrint,
    onToggleStatus,
    mappingJenisCuti,
}: CutiTableProps) {
    const formatTanggal = (dateString: string) => {
        if (!dateString) return '-';
        return new Date(dateString).toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
        });
    };

    const cutiList = data || [];

    return (
        <div className="overflow-hidden rounded-xl border border-slate-100 bg-white shadow-xs">
            <Table>
                <TableHeader className="bg-slate-50/70">
                    <TableRow className="border-slate-100 hover:bg-transparent">
                        <TableHead className="h-11 w-[60px] text-center text-xs font-bold tracking-wider text-slate-500 uppercase">
                            No
                        </TableHead>
                        <TableHead className="h-11 w-[280px] text-xs font-bold tracking-wider text-slate-500 uppercase">
                            Pegawai
                        </TableHead>
                        <TableHead className="h-11 text-xs font-bold tracking-wider text-slate-500 uppercase">
                            Jenis Cuti
                        </TableHead>
                        <TableHead className="h-11 text-xs font-bold tracking-wider text-slate-500 uppercase">
                            Masa Cuti
                        </TableHead>
                        <TableHead className="h-11 text-center text-xs font-bold tracking-wider text-slate-500 uppercase">
                            Durasi
                        </TableHead>
                        <TableHead className="h-11 text-xs font-bold tracking-wider text-slate-500 uppercase">
                            Status
                        </TableHead>
                        <TableHead className="h-11 w-[160px] text-right text-xs font-bold tracking-wider text-slate-500 uppercase">
                            Aksi
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    {cutiList.length === 0 ? (
                        <TableRow>
                            <TableCell
                                colSpan={7}
                                className="h-32 text-center text-sm font-medium text-slate-400"
                            >
                                Tidak ada data pengajuan cuti pada kategori ini
                            </TableCell>
                        </TableRow>
                    ) : (
                        cutiList.map((item, index) => {
                            const nomorUrut =
                                (currentPage - 1) * perPage + index + 1;
                            const isDisetujui =
                                item.status_cuti === 'disetujui';

                            return (
                                <TableRow
                                    key={item.id}
                                    className="border-slate-100 transition-colors hover:bg-slate-50/40"
                                >
                                    <TableCell className="py-3.5 text-center text-xs font-bold text-slate-400">
                                        {nomorUrut}
                                    </TableCell>
                                    <TableCell className="py-3.5">
                                        <div className="flex items-center gap-3">
                                            <Avatar className="h-9 w-9 rounded-xl border border-slate-100 shadow-2xs">
                                                <AvatarFallback className="rounded-xl bg-slate-100 text-xs font-bold text-slate-600">
                                                    {item.pegawai?.nama
                                                        ?.substring(0, 2)
                                                        .toUpperCase()}
                                                </AvatarFallback>
                                            </Avatar>
                                            <div className="flex min-w-0 flex-col">
                                                <span className="truncate text-sm font-semibold text-slate-700">
                                                    {item.pegawai?.nama}
                                                </span>
                                                <span className="truncate text-xs font-medium text-slate-400">
                                                    NIP.{' '}
                                                    {item.pegawai?.nip || '-'}
                                                </span>
                                            </div>
                                        </div>
                                    </TableCell>
                                    <TableCell className="py-3.5">
                                        <span className="text-sm font-medium text-slate-600">
                                            {mappingJenisCuti[
                                                item.jenis_cuti
                                            ] || item.jenis_cuti}
                                        </span>
                                    </TableCell>
                                    <TableCell className="py-3.5">
                                        <div className="flex flex-col">
                                            <span className="text-sm font-semibold text-slate-600">
                                                {formatTanggal(
                                                    item.tanggal_mulai,
                                                )}
                                            </span>
                                            <span className="text-xs font-medium text-slate-400">
                                                s.d{' '}
                                                {formatTanggal(
                                                    item.tanggal_akhir,
                                                )}
                                            </span>
                                        </div>
                                    </TableCell>
                                    <TableCell className="py-3.5 text-center">
                                        <Badge
                                            variant="secondary"
                                            className="shadow-3xs rounded-lg border-0 bg-slate-100 px-2.5 py-0.5 text-xs font-bold text-slate-700"
                                        >
                                            {item.lama_cuti} Hari
                                        </Badge>
                                    </TableCell>
                                    <TableCell className="py-3.5">
                                        <Badge
                                            className={`shadow-3xs rounded-lg border-0 px-2.5 py-0.5 text-xs font-bold ${
                                                isDisetujui
                                                    ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-50'
                                                    : 'bg-amber-50 text-amber-700 hover:bg-amber-50'
                                            }`}
                                        >
                                            {isDisetujui
                                                ? 'Disetujui'
                                                : 'Ditangguhkan'}
                                        </Badge>
                                    </TableCell>
                                    <TableCell className="py-3.5 text-right">
                                        <div className="flex items-center justify-end gap-1">
                                            {isDisetujui ? (
                                                <>
                                                    {/* TAB DISETUJUI: Detail, Cetak, Tangguhkan */}
                                                    <Button
                                                        variant="ghost"
                                                        size="icon"
                                                        onClick={() =>
                                                            onDetail(item)
                                                        }
                                                        className="h-8 w-8 rounded-lg text-slate-400 hover:bg-slate-100/70 hover:text-slate-600"
                                                        title="Detail Cuti"
                                                    >
                                                        <FileText className="h-4 w-4" />
                                                    </Button>
                                                    <Button
                                                        variant="ghost"
                                                        size="icon"
                                                        onClick={() =>
                                                            onPrint(item)
                                                        }
                                                        className="h-8 w-8 rounded-lg text-blue-500 hover:bg-blue-50 hover:text-blue-600"
                                                        title="Cetak Surat Izin Cuti"
                                                    >
                                                        <Printer className="h-4 w-4" />
                                                    </Button>
                                                    <Button
                                                        variant="ghost"
                                                        size="icon"
                                                        onClick={() =>
                                                            onToggleStatus(
                                                                item.id,
                                                                item.status_cuti,
                                                            )
                                                        }
                                                        className="h-8 w-8 rounded-lg text-amber-500 hover:bg-amber-50 hover:text-amber-600"
                                                        title="Tangguhkan Permohonan Cuti"
                                                    >
                                                        <XCircle className="h-4 w-4" />
                                                    </Button>
                                                </>
                                            ) : (
                                                <>
                                                    {/* TAB DITANGGUHKAN: Edit (Form), Setujui, Hapus */}
                                                    <Button
                                                        variant="ghost"
                                                        size="icon"
                                                        onClick={() =>
                                                            onEdit(item)
                                                        }
                                                        className="h-8 w-8 rounded-lg text-blue-500 hover:bg-blue-50 hover:text-blue-600"
                                                        title="Ubah Data Cuti"
                                                    >
                                                        <Edit2 className="h-4 w-4" />
                                                    </Button>
                                                    <Button
                                                        variant="ghost"
                                                        size="icon"
                                                        onClick={() =>
                                                            onToggleStatus(
                                                                item.id,
                                                                item.status_cuti,
                                                            )
                                                        }
                                                        className="h-8 w-8 rounded-lg text-emerald-600 hover:bg-emerald-50 hover:text-emerald-700"
                                                        title="Setujui Permohonan Cuti"
                                                    >
                                                        <CheckCircle2 className="h-4 w-4" />
                                                    </Button>
                                                    <Button
                                                        variant="ghost"
                                                        size="icon"
                                                        onClick={() =>
                                                            onDelete(item)
                                                        }
                                                        className="h-8 w-8 rounded-lg text-rose-500 hover:bg-rose-50 hover:text-rose-600"
                                                        title="Hapus Data Cuti"
                                                    >
                                                        <Trash2 className="h-4 w-4" />
                                                    </Button>
                                                </>
                                            )}
                                        </div>
                                    </TableCell>
                                </TableRow>
                            );
                        })
                    )}
                </TableBody>
            </Table>
        </div>
    );
}
