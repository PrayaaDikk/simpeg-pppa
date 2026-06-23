import React from 'react';
import {
    GraduationCap,
    Edit2,
    Trash2,
    Plus,
    FileText,
    AlertCircle,
    ExternalLink,
} from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import {
    Card,
    CardHeader,
    CardTitle,
    CardDescription,
    CardContent,
} from '@/components/ui/card';
import {
    Table,
    TableHeader,
    TableBody,
    TableHead,
    TableRow,
    TableCell,
} from '@/components/ui/table';

interface RiwayatPendidikan {
    id: number;
    tingkat: string;
    institusi: string;
    jurusan: string;
    tahun_lulus: string;
    ijazah: string | null; // Sesuai kolom database di berkas migrasi
}

interface PendidikanTableProps {
    data: RiwayatPendidikan[];
    onAdd: () => void;
    onEdit: (item: RiwayatPendidikan) => void;
    onDelete: (id: number) => void;
}

export default function PendidikanTable({
    data,
    onAdd,
    onEdit,
    onDelete,
}: PendidikanTableProps) {
    return (
        <Card className="overflow-hidden rounded-2xl border-slate-200/80 shadow-xs transition-all duration-200 hover:shadow-md/5">
            <CardHeader className="border-b border-slate-100 bg-slate-50/50 p-6">
                <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div className="flex items-center gap-3">
                        <div className="flex h-10 w-10 items-center justify-center rounded-xl border border-blue-100/50 bg-blue-50 text-blue-600">
                            <GraduationCap className="h-5 w-5" />
                        </div>
                        <div>
                            <CardTitle className="text-base font-bold text-slate-900">
                                Riwayat Pendidikan Formal
                            </CardTitle>
                            <CardDescription className="mt-0.5 text-xs text-slate-500">
                                Daftar riwayat kualifikasi tingkat pendidikan
                                akademik pegawai yang terdata.
                            </CardDescription>
                        </div>
                    </div>
                    <Button
                        onClick={onAdd}
                        className="inline-flex h-9 items-center gap-1.5 rounded-xl bg-blue-600 px-4 text-xs font-semibold text-white shadow-xs transition-colors hover:bg-blue-700 sm:w-auto"
                    >
                        <Plus className="h-4 w-4" />
                        Tambah Riwayat
                    </Button>
                </div>
            </CardHeader>
            <CardContent className="p-0">
                <Table>
                    <TableHeader className="bg-slate-50/70">
                        <TableRow className="border-b border-slate-100 hover:bg-transparent">
                            <TableHead className="w-[60px] text-center text-xs font-bold text-slate-600">
                                No
                            </TableHead>
                            <TableHead className="text-xs font-bold text-slate-600">
                                Nama Institusi
                            </TableHead>
                            <TableHead className="text-xs font-bold text-slate-600">
                                Jurusan / Program Studi
                            </TableHead>
                            <TableHead className="w-[100px] text-center text-xs font-bold text-slate-600">
                                Tingkat
                            </TableHead>
                            <TableHead className="w-[120px] text-center text-xs font-bold text-slate-600">
                                Tahun Lulus
                            </TableHead>
                            <TableHead className="w-[150px] text-center text-xs font-bold text-slate-600">
                                Ijazah
                            </TableHead>
                            <TableHead className="w-[100px] text-center text-xs font-bold text-slate-600">
                                Aksi
                            </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        {data.length === 0 ? (
                            <TableRow>
                                <TableCell
                                    colSpan={7}
                                    className="h-48 text-center"
                                >
                                    <div className="flex flex-col items-center justify-center gap-2 text-slate-400">
                                        <AlertCircle className="h-8 w-8 stroke-[1.5] text-slate-300" />
                                        <p className="text-xs font-medium text-slate-500">
                                            Belum ada riwayat pendidikan
                                        </p>
                                        <p className="text-[11px] text-slate-400">
                                            Silakan klik tombol "Tambah Riwayat"
                                            untuk memasukkan data.
                                        </p>
                                    </div>
                                </TableCell>
                            </TableRow>
                        ) : (
                            data.map((item, index) => (
                                <TableRow
                                    key={item.id}
                                    className="border-b border-slate-100/80 transition-colors hover:bg-slate-50/40"
                                >
                                    {/* Kolom 1: Nomor */}
                                    <TableCell className="text-center text-xs font-medium text-slate-500">
                                        {index + 1}
                                    </TableCell>

                                    {/* Kolom 2: Nama Institusi */}
                                    <TableCell className="max-w-[200px] truncate text-xs font-semibold text-slate-900">
                                        {item.institusi}
                                    </TableCell>

                                    {/* Kolom 3: Jurusan / Program Studi */}
                                    <TableCell className="max-w-[200px] truncate text-xs text-slate-600">
                                        {item.jurusan || (
                                            <span className="text-slate-400 italic">
                                                -
                                            </span>
                                        )}
                                    </TableCell>

                                    {/* Kolom 4: Tingkat */}
                                    <TableCell className="text-center">
                                        <Badge
                                            variant="outline"
                                            className="rounded-lg border-blue-200/50 bg-blue-50/30 px-2 py-0.5 text-[11px] font-bold text-blue-700"
                                        >
                                            {item.tingkat}
                                        </Badge>
                                    </TableCell>

                                    {/* Kolom 5: Tahun Lulus */}
                                    <TableCell className="text-center text-xs font-medium text-slate-600">
                                        {item.tahun_lulus}
                                    </TableCell>

                                    {/* Kolom 6: Berkas Ijazah (Kondisional Terlampir / Tidak) */}
                                    <TableCell className="py-3.5">
                                        {item.ijazah ? (
                                            <a
                                                href={`/storage/${item.ijazah}`}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="shadow-3xs inline-flex cursor-pointer items-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700 transition-all hover:bg-emerald-100 hover:text-emerald-800 active:scale-98"
                                                title="Buka Berkas Ijazah di Tab Baru"
                                            >
                                                <FileText className="h-3.5 w-3.5 text-emerald-600" />
                                                Terlampir
                                                <ExternalLink className="h-3 w-3 text-emerald-500" />
                                            </a>
                                        ) : (
                                            <span className="inline-flex items-center gap-1.5 rounded-lg border border-slate-200/60 bg-slate-50 px-2.5 py-1 text-[11px] font-semibold text-slate-400 select-none">
                                                Tidak Terlampir
                                            </span>
                                        )}
                                    </TableCell>

                                    {/* Kolom 7: Tombol Aksi */}
                                    <TableCell className="text-center">
                                        <div className="flex items-center justify-center gap-1">
                                            <Button
                                                onClick={() => onEdit(item)}
                                                variant="ghost"
                                                size="icon"
                                                className="h-8 w-8 rounded-lg text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-800"
                                                title="Edit Data"
                                            >
                                                <Edit2 className="h-3.5 w-3.5" />
                                            </Button>
                                            <Button
                                                onClick={() =>
                                                    onDelete(item.id)
                                                }
                                                variant="ghost"
                                                size="icon"
                                                className="h-8 w-8 rounded-lg text-red-500 transition-colors hover:bg-red-50 hover:text-red-600"
                                                title="Hapus Data"
                                            >
                                                <Trash2 className="h-3.5 w-3.5" />
                                            </Button>
                                        </div>
                                    </TableCell>
                                </TableRow>
                            ))
                        )}
                    </TableBody>
                </Table>
            </CardContent>
        </Card>
    );
}
