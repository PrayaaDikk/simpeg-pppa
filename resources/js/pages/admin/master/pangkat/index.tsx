import React, { useState } from 'react';
import { Head, useForm } from '@inertiajs/react';
import { Plus, Edit2, Trash2, Award, Search } from 'lucide-react';
import AppLayout from '@/layouts/app-layout';
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
import {
    Sheet,
    SheetContent,
    SheetHeader,
    SheetTitle,
    SheetDescription,
    SheetFooter,
} from '@/components/ui/sheet';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/input-error';

interface PangkatData {
    id: number;
    nama_pangkat: string;
    golongan: string;
}

interface Props {
    pangkat: PangkatData[];
}

export default function PangkatIndex({ pangkat }: Props) {
    const [isOpenSheet, setIsOpenSheet] = useState(false);
    const [editId, setEditId] = useState<number | null>(null);
    const [searchQuery, setSearchQuery] = useState('');

    const {
        data,
        setData,
        post,
        put,
        delete: destroy,
        processing,
        errors,
        reset,
    } = useForm({
        nama_pangkat: '',
        golongan: '',
    });

    const handleOpenCreate = () => {
        reset();
        setEditId(null);
        setIsOpenSheet(true);
    };

    const handleOpenEdit = (item: PangkatData) => {
        setData({
            nama_pangkat: item.nama_pangkat,
            golongan: item.golongan,
        });
        setEditId(item.id);
        setIsOpenSheet(true);
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (editId) {
            put(`/admin/master/pangkat/${editId}`, {
                onSuccess: () => setIsOpenSheet(false),
            });
        } else {
            post('/admin/master/pangkat/store', {
                onSuccess: () => setIsOpenSheet(false),
            });
        }
    };

    const handleDelete = (id: number) => {
        if (confirm('Apakah Anda yakin ingin menghapus data pangkat ini?')) {
            destroy(`/admin/master/pangkat/${id}`);
        }
    };

    const filteredData = pangkat.filter(
        (item) =>
            item.nama_pangkat
                .toLowerCase()
                .includes(searchQuery.toLowerCase()) ||
            item.golongan.toLowerCase().includes(searchQuery.toLowerCase()),
    );

    return (
        <>
            <Head title="Manajemen Pangkat & Golongan" />

            <div className="space-y-6 p-6">
                <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-bold tracking-tight text-slate-900">
                            Pangkat & Golongan
                        </h1>
                        <p className="text-sm text-slate-500">
                            Kelola daftar pangkat kepegawaian beserta golongan
                            ruang ASN.
                        </p>
                    </div>
                    <Button
                        onClick={handleOpenCreate}
                        className="h-12 rounded-lg bg-blue-600 px-5 font-semibold text-white shadow-sm hover:bg-blue-700"
                    >
                        <Plus className="mr-2 h-5 w-5" /> Tambah Pangkat
                    </Button>
                </div>

                <Card className="rounded-xl border-slate-100 shadow-sm">
                    <CardHeader className="border-b border-slate-50 p-6">
                        <div className="relative max-w-sm">
                            <Search className="absolute top-3.5 left-3 h-5 w-5 text-slate-400" />
                            <Input
                                required
                                placeholder="Cari pangkat atau golongan..."
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                                className="h-11 rounded-lg border-slate-200 pl-10 text-slate-700 placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                            />
                        </div>
                    </CardHeader>
                    <CardContent className="p-0">
                        <Table>
                            <TableHeader className="bg-slate-50/70">
                                <TableRow className="border-b border-slate-100">
                                    <TableHead className="w-16 text-center font-bold text-slate-700">
                                        No
                                    </TableHead>
                                    <TableHead className="font-bold text-slate-700">
                                        Nama Pangkat
                                    </TableHead>
                                    <TableHead className="font-bold text-slate-700">
                                        Golongan / Ruang
                                    </TableHead>
                                    <TableHead className="w-32 text-center font-bold text-slate-700">
                                        Aksi
                                    </TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {filteredData.length === 0 ? (
                                    <TableRow>
                                        <TableCell
                                            colSpan={4}
                                            className="h-24 text-center text-slate-400"
                                        >
                                            Data pangkat tidak ditemukan.
                                        </TableCell>
                                    </TableRow>
                                ) : (
                                    filteredData.map((item, index) => (
                                        <TableRow
                                            key={item.id}
                                            className="h-14 border-b border-slate-100 transition-colors hover:bg-slate-50/50"
                                        >
                                            <TableCell className="text-center font-medium text-slate-500">
                                                {index + 1}
                                            </TableCell>
                                            <TableCell className="font-semibold text-slate-900">
                                                {item.nama_pangkat}
                                            </TableCell>
                                            <TableCell>
                                                <span className="inline-flex items-center gap-1.5 rounded-full border border-blue-100 bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                                    {item.golongan}
                                                </span>
                                            </TableCell>
                                            <TableCell className="text-center">
                                                <div className="flex items-center justify-center gap-2">
                                                    <Button
                                                        variant="ghost"
                                                        size="icon"
                                                        onClick={() =>
                                                            handleOpenEdit(item)
                                                        }
                                                        className="h-9 w-9 rounded-lg text-slate-600 hover:bg-blue-50 hover:text-blue-600"
                                                    >
                                                        <Edit2 className="h-4 w-4" />
                                                    </Button>
                                                    <Button
                                                        variant="ghost"
                                                        size="icon"
                                                        onClick={() =>
                                                            handleDelete(
                                                                item.id,
                                                            )
                                                        }
                                                        className="h-9 w-9 rounded-lg text-slate-600 hover:bg-red-50 hover:text-red-600"
                                                    >
                                                        <Trash2 className="h-4 w-4" />
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
            </div>

            <Sheet open={isOpenSheet} onOpenChange={setIsOpenSheet}>
                <SheetContent
                    side="right"
                    className="w-full border-l border-slate-100 bg-white p-6 shadow-xl sm:max-w-md"
                >
                    <SheetHeader className="border-b border-slate-100 pb-4">
                        <SheetTitle className="text-lg font-bold text-slate-900">
                            {editId
                                ? 'Perbarui Data Pangkat'
                                : 'Tambah Pangkat Baru'}
                        </SheetTitle>
                        <SheetDescription className="text-slate-500">
                            Isi formulir berikut dengan benar untuk memproses
                            konfigurasi data master.
                        </SheetDescription>
                    </SheetHeader>

                    <form onSubmit={handleSubmit} className="space-y-5 pt-5">
                        <div className="space-y-2">
                            <Label
                                htmlFor="nama_pangkat"
                                className="text-sm font-semibold text-slate-700"
                            >
                                Nama Pangkat
                            </Label>
                            <Input
                                required
                                id="nama_pangkat"
                                value={data.nama_pangkat}
                                onChange={(e) =>
                                    setData('nama_pangkat', e.target.value)
                                }
                                placeholder="Contoh: Pembina Utama Muda"
                                className="h-11 border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                            />
                            <InputError message={errors.nama_pangkat} />
                        </div>

                        <div className="space-y-2">
                            <Label
                                htmlFor="golongan"
                                className="text-sm font-semibold text-slate-700"
                            >
                                Golongan / Ruang
                            </Label>
                            <Input
                                required
                                id="golongan"
                                value={data.golongan}
                                onChange={(e) =>
                                    setData('golongan', e.target.value)
                                }
                                placeholder="Contoh: IV/c"
                                className="h-11 border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                            />
                            <InputError message={errors.golongan} />
                        </div>

                        <SheetFooter className="gap-2 border-t border-slate-100 pt-4">
                            <Button
                                type="button"
                                variant="outline"
                                onClick={() => setIsOpenSheet(false)}
                                className="h-11 flex-1 border-slate-200 font-semibold text-slate-700"
                            >
                                Batal
                            </Button>
                            <Button
                                type="submit"
                                disabled={processing}
                                className="h-11 flex-1 bg-blue-600 font-semibold text-white hover:bg-blue-700"
                            >
                                {editId ? 'Perbarui Data' : 'Simpan Data'}
                            </Button>
                        </SheetFooter>
                    </form>
                </SheetContent>
            </Sheet>
        </>
    );
}

PangkatIndex.layout = (page: React.ReactNode) => (
    <AppLayout breadcrumbs={[{ title: 'Pangkat', href: '#' }]}>
        {page}
    </AppLayout>
);
