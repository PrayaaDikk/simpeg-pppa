import React from 'react';
import {
    Briefcase,
    Calendar,
    Edit2,
    Trash2,
    Plus,
    FileText,
    AlertCircle,
} from 'lucide-react';
import { Button } from '@/components/ui/button';
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

interface RiwayatJabatan {
    id: number;
    nama_jabatan: string;
    tmt_jabatan: string; // Format dari DB/Server
    nomor_sk: string; // Sesuai migration: nomor_sk
    tanggal_sk: string; // Sesuai migration: tanggal_sk
}

interface JabatanTableProps {
    data: RiwayatJabatan[];
    onAdd: () => void;
    onEdit: (item: RiwayatJabatan) => void;
    onDelete: (id: number) => void;
}

export default function JabatanTable({
    data,
    onAdd,
    onEdit,
    onDelete,
}: JabatanTableProps) {
    // Helper function untuk memformat tanggal menjadi dd-MM-yyyy
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
                        <div className="flex h-10 w-10 items-center justify-center rounded-xl border border-purple-100/50 bg-purple-50 text-purple-600">
                            <Briefcase className="h-5 w-5" />
                        </div>
                        <div>
                            <CardTitle className="text-base font-bold text-slate-900">
                                Riwayat Kedudukan Jabatan
                            </CardTitle>
                            <CardDescription className="mt-0.5 text-xs text-slate-500">
                                Rekam jejak penempatan jabatan struktural,
                                fungsional, maupun pelaksana pegawai.
                            </CardDescription>
                        </div>
                    </div>
                    <Button
                        onClick={onAdd}
                        className="inline-flex h-9 items-center gap-1.5 rounded-xl border border-purple-700/10 bg-purple-600 px-4 text-xs font-semibold text-white shadow-xs transition-colors hover:bg-purple-700 sm:w-auto"
                    >
                        <Plus className="h-4 w-4" />
                        Tambah Jabatan
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
                                Nama Jabatan
                            </TableHead>
                            <TableHead className="w-[160px] text-center text-xs font-bold text-slate-600">
                                TMT Jabatan
                            </TableHead>
                            <TableHead className="text-xs font-bold text-slate-600">
                                Nomor SK
                            </TableHead>
                            <TableHead className="w-[160px] text-center text-xs font-bold text-slate-600">
                                Tanggal SK
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
                                            Belum ada riwayat kedudukan jabatan
                                        </p>
                                        <p className="text-[11px] text-slate-400">
                                            Silakan klik tombol "Tambah Jabatan"
                                            untuk merekam data baru.
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
                                    {/* Kolom Nomor */}
                                    <TableCell className="text-center text-xs font-medium text-slate-500">
                                        {index + 1}
                                    </TableCell>

                                    {/* Nama Jabatan */}
                                    <TableCell className="text-xs font-semibold text-slate-900">
                                        {item.nama_jabatan}
                                    </TableCell>

                                    {/* TMT Jabatan Formatted */}
                                    <TableCell className="text-center">
                                        <div className="inline-flex items-center gap-1.5 rounded-lg border border-slate-200/30 bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                                            <Calendar className="h-3 w-3 text-slate-400" />
                                            {formatDate(item.tmt_jabatan)}
                                        </div>
                                    </TableCell>

                                    {/* Nomor SK */}
                                    <TableCell className="font-mono text-xs text-slate-700">
                                        <div className="flex items-center gap-1.5">
                                            <FileText className="h-3.5 w-3.5 stroke-[1.5] text-slate-400" />
                                            <span>{item.nomor_sk}</span>
                                        </div>
                                    </TableCell>

                                    {/* Tanggal SK Formatted */}
                                    <TableCell className="text-center">
                                        <div className="inline-flex items-center gap-1.5 rounded-lg border border-slate-200/30 bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                                            <Calendar className="h-3 w-3 text-slate-400" />
                                            {formatDate(item.tanggal_sk)}
                                        </div>
                                    </TableCell>

                                    {/* Menu Aksi */}
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
