import React from 'react';
import {
    Award,
    Edit2,
    Trash2,
    Plus,
    Calendar,
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

interface RiwayatPangkat {
    id: number;
    pangkat_id: number | null;
    pangkat?: { nama_pangkat: string; golongan: string };
    tmt_pangkat: string;
    nomor_sk: string;
    file_sk: string | null; // <-- Menampung path berkas dari database
}

interface PangkatTableProps {
    data: RiwayatPangkat[];
    onAdd: () => void;
    onEdit: (item: RiwayatPangkat) => void;
    onDelete: (id: number) => void;
}

export default function PangkatTable({
    data,
    onAdd,
    onEdit,
    onDelete,
}: PangkatTableProps) {
    const formatDate = (dateString: string) => {
        if (!dateString) return '-';
        try {
            const date = new Date(dateString);
            if (isNaN(date.getTime())) return dateString;

            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();

            return `${day}-${month}-${year}`;
        } catch {
            return dateString;
        }
    };

    return (
        <Card className="overflow-hidden rounded-2xl border-slate-200/80 shadow-xs transition-all duration-200 hover:shadow-md/5">
            <CardHeader className="border-b border-slate-100 bg-slate-50/50 p-6">
                <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div className="flex items-center gap-3">
                        <div className="flex h-10 w-10 items-center justify-center rounded-xl border border-amber-100/50 bg-amber-50 text-amber-600">
                            <Award className="h-5 w-5" />
                        </div>
                        <div>
                            <CardTitle className="text-base font-bold text-slate-900">
                                Riwayat Golongan & Kepangkatan
                            </CardTitle>
                            <CardDescription className="mt-0.5 text-xs text-slate-500">
                                Rekam jejak kenaikan pangkat, penyesuaian
                                golongan, dan masa kerja berkala pegawai.
                            </CardDescription>
                        </div>
                    </div>
                    <Button
                        onClick={onAdd}
                        className="inline-flex h-9 items-center gap-1.5 rounded-xl border border-amber-700/10 bg-amber-600 px-4 text-xs font-semibold text-white shadow-xs transition-colors hover:bg-amber-700 sm:w-auto"
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
                                Pangkat / Golongan
                            </TableHead>
                            <TableHead className="w-[150px] text-center text-xs font-bold text-slate-600">
                                TMT Pangkat
                            </TableHead>
                            <TableHead className="text-xs font-bold text-slate-600">
                                Nomor SK
                            </TableHead>
                            <TableHead className="w-[140px] text-center text-xs font-bold text-slate-600">
                                Berkas SK
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
                                    colSpan={6}
                                    className="h-48 text-center"
                                >
                                    <div className="flex flex-col items-center justify-center gap-2 text-slate-400">
                                        <AlertCircle className="h-8 w-8 stroke-[1.5] text-slate-300" />
                                        <p className="text-xs font-medium text-slate-500">
                                            Belum ada riwayat kepangkatan
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
                                    <TableCell className="text-center text-xs font-medium text-slate-500">
                                        {index + 1}
                                    </TableCell>

                                    <TableCell className="text-xs font-semibold text-slate-900">
                                        <div className="flex items-center gap-2">
                                            <span>
                                                {item.pangkat?.nama_pangkat || (
                                                    <span className="font-normal text-slate-400 italic">
                                                        Pangkat Terhapus
                                                    </span>
                                                )}
                                            </span>
                                            -
                                            {item.pangkat?.golongan && (
                                                <Badge
                                                    variant="outline"
                                                    className="rounded-md border-amber-200/60 bg-amber-50/40 px-1.5 py-0.5 text-[10px] font-bold text-amber-700"
                                                >
                                                    {item.pangkat.golongan}
                                                </Badge>
                                            )}
                                        </div>
                                    </TableCell>

                                    <TableCell className="text-center">
                                        <div className="inline-flex items-center gap-1.5 rounded-lg border border-slate-200/30 bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                                            <Calendar className="h-3 w-3 text-slate-400" />
                                            {formatDate(item.tmt_pangkat)}
                                        </div>
                                    </TableCell>

                                    <TableCell className="font-mono text-xs text-slate-700">
                                        <div className="flex items-center gap-1.5">
                                            <FileText className="h-3.5 w-3.5 stroke-[1.5] text-slate-400" />
                                            <span>{item.nomor_sk}</span>
                                        </div>
                                    </TableCell>

                                    {/* Kolom Berkas SK Baru */}
                                    <TableCell className="py-3.5">
                                        {item.file_sk ? (
                                            <a
                                                href={`/storage/${item.file_sk}`}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="shadow-3xs inline-flex cursor-pointer items-center gap-1.5 rounded-lg border border-blue-200 bg-blue-50 px-2.5 py-1 text-[11px] font-bold text-blue-700 transition-all hover:bg-blue-100 hover:text-blue-800 active:scale-98"
                                                title="Buka Berkas SK di Tab Baru"
                                            >
                                                <FileText className="h-3.5 w-3.5 text-blue-600" />
                                                Terlampir
                                                <ExternalLink className="h-3 w-3 text-blue-500" />
                                            </a>
                                        ) : (
                                            <span className="inline-flex items-center gap-1.5 rounded-lg border border-slate-200/60 bg-slate-50 px-2.5 py-1 text-[11px] font-semibold text-slate-400 select-none">
                                                <AlertCircle className="h-3.5 w-3.5 text-slate-300" />
                                                Tidak Terlampir
                                            </span>
                                        )}
                                    </TableCell>

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
