import React from 'react';
import { Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { TableRow, TableCell } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { Edit2, History, Trash2 } from 'lucide-react';
import type { Pegawai } from './pegawai-types';

interface PegawaiTableRowProps {
    pegawai: Pegawai;
    rowNumber: number;
    onEdit: (pegawai: Pegawai) => void;
    onDelete: (pegawai: Pegawai) => void;
}

export default function PegawaiTableRow({
    pegawai,
    rowNumber,
    onEdit,
    onDelete,
}: PegawaiTableRowProps) {
    return (
        <TableRow className="group border-b border-slate-100 transition-colors hover:bg-slate-50/30">
            {/* 1. Kolom No */}
            <TableCell className="py-4 pl-6 text-center text-sm font-medium text-slate-400">
                {rowNumber}
            </TableCell>

            {/* 2. Kolom Pegawai */}
            <TableCell className="py-4">
                <div className="flex items-center gap-3">
                    <Avatar className="size-10 border border-slate-200/80 shadow-sm transition-transform group-hover:scale-105">
                        <AvatarFallback className="bg-gradient-to-br from-blue-50 to-indigo-50 text-xs font-bold text-blue-600">
                            {pegawai.nama
                                ?.split(' ')
                                .map((n) => n[0])
                                .slice(0, 2)
                                .join('')
                                .toUpperCase()}
                        </AvatarFallback>
                    </Avatar>
                    <div className="flex min-w-0 flex-col">
                        <span className="truncate text-sm font-semibold text-slate-900 transition-colors group-hover:text-blue-600">
                            {pegawai.nama}
                        </span>
                        <span className="mt-0.5 text-xs font-medium text-slate-400">
                            NIP. {pegawai.nip}
                        </span>
                    </div>
                </div>
            </TableCell>

            {/* 3. Kolom Jabatan & Bidang */}
            <TableCell className="py-4">
                <div className="flex flex-col">
                    <span className="text-sm font-medium text-slate-700">
                        {pegawai.jabatan?.nama_jabatan || (
                            <span className="font-normal text-slate-400 italic">
                                -
                            </span>
                        )}
                    </span>
                    <span className="mt-0.5 text-xs font-normal text-slate-400">
                        {pegawai.bidang?.nama_bidang || 'Belum Ditentukan'}
                    </span>
                </div>
            </TableCell>

            {/* 4. Kolom Pangkat & Golongan */}
            <TableCell className="py-4">
                {pegawai.pangkat ? (
                    <div className="flex flex-col">
                        <span className="text-sm font-medium text-slate-700">
                            {pegawai.pangkat.nama_pangkat}
                        </span>
                        <span className="mt-0.5 text-xs font-normal text-slate-400">
                            Gol. {pegawai.pangkat.golongan}
                        </span>
                    </div>
                ) : (
                    <span className="text-sm font-normal text-slate-400 italic">
                        -
                    </span>
                )}
            </TableCell>

            <TableCell>
                {pegawai.pendidikan ? (
                    <div className="flex flex-col">
                        <span className="text-sm font-medium text-slate-700">
                            {pegawai.pendidikan.tingkat} -{' '}
                            {pegawai.pendidikan.jurusan}
                        </span>
                    </div>
                ) : (
                    <span className="text-sm font-normal text-slate-400 italic">
                        -
                    </span>
                )}
            </TableCell>

            {/* 5. Kolom Status */}
            <TableCell className="py-4 text-center">
                <Badge
                    variant={pegawai.is_active ? 'success' : 'destructive'}
                    className={`rounded-full border px-2.5 py-0.5 text-xs font-semibold tracking-wide ${
                        pegawai.is_active
                            ? 'border-emerald-200/60 bg-emerald-50 text-emerald-700 hover:bg-emerald-50'
                            : 'border-rose-200/60 bg-rose-50 text-rose-700 hover:bg-rose-50'
                    }`}
                >
                    {pegawai.is_active ? 'Aktif' : 'Non-Aktif'}
                </Badge>
            </TableCell>

            {/* 6. Kolom Aksi */}
            <TableCell className="py-4 pr-6 text-right">
                <div className="flex items-center justify-end gap-2">
                    {/* Tombol Lainnya (Menggantikan Icon History) */}
                    <Link
                        href={`/admin/pegawai/${pegawai.id}/riwayat`}
                        className="inline-flex h-8 items-center justify-center rounded-lg border border-slate-200 bg-white px-3 text-xs font-medium text-slate-600 shadow-sm transition-all hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900"
                    >
                        <History className="mr-1.5 size-3.5 text-slate-400" />
                        Lainnya
                    </Link>

                    {/* Tombol Edit */}
                    <Button
                        variant="ghost"
                        size="icon"
                        onClick={() => onEdit(pegawai)}
                        className="size-8 rounded-lg text-slate-400 transition-colors hover:bg-blue-50 hover:text-blue-600"
                        title="Edit Data"
                    >
                        <Edit2 className="size-3.5" />
                    </Button>

                    {/* Tombol Hapus */}
                    <Button
                        variant="ghost"
                        size="icon"
                        onClick={() => onDelete(pegawai)}
                        className="size-8 rounded-lg text-slate-400 transition-colors hover:bg-rose-50 hover:text-rose-600"
                        title="Hapus Pegawai"
                    >
                        <Trash2 className="size-3.5" />
                    </Button>
                </div>
            </TableCell>
        </TableRow>
    );
}
