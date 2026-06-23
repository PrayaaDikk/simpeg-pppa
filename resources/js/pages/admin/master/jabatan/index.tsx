import React, { useState } from 'react';
import { Head, useForm } from '@inertiajs/react';
import { Plus, Edit2, Trash2, Search, ShieldAlert, Users } from 'lucide-react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Switch } from '@/components/ui/switch'; // Jika menggunakan primitive switch shadcn, atau pakai checkbox biasa
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

interface JabatanData {
    id: number;
    nama_jabatan: string;
    is_singleton: boolean | number; // SQLite mengembalikan 0 atau 1
}

interface Props {
    jabatan: JabatanData[];
}

export default function JabatanIndex({ jabatan }: Props) {
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
        clearErrors,
    } = useForm({
        nama_jabatan: '',
        is_singleton: false,
    });

    const handleOpenCreate = () => {
        clearErrors();
        reset();
        setEditId(null);
        setIsOpenSheet(true);
    };

    const handleOpenEdit = (item: JabatanData) => {
        clearErrors();
        setEditId(item.id);
        setData({
            nama_jabatan: item.nama_jabatan,
            is_singleton: Boolean(item.is_singleton),
        });
        setIsOpenSheet(true);
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (editId) {
            put(`/admin/master/jabatan/${editId}`, {
                onSuccess: () => setIsOpenSheet(false),
            });
        } else {
            post('/admin/master/jabatan/store', {
                onSuccess: () => {
                    setIsOpenSheet(false);
                    reset();
                },
            });
        }
    };

    const handleDelete = (id: number) => {
        if (confirm('Apakah Anda yakin ingin menghapus data jabatan ini?')) {
            destroy(`/admin/master/jabatan/${id}`);
        }
    };

    const filteredJabatan = jabatan.filter((item) =>
        item.nama_jabatan.toLowerCase().includes(searchQuery.toLowerCase()),
    );

    return (
        <>
            <Head title="Manajemen Jabatan" />

            <div className="flex flex-col gap-6 p-6">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold tracking-tight text-slate-900">
                            Jabatan
                        </h1>
                        <p className="text-sm text-slate-500">
                            Kelola daftar jabatan struktural maupun fungsional
                            pegawai.
                        </p>
                    </div>
                    <Button
                        onClick={handleOpenCreate}
                        className="h-11 bg-blue-600 px-4 font-medium text-white hover:bg-blue-700"
                    >
                        <Plus className="mr-2 h-4 w-4" /> Tambah Jabatan
                    </Button>
                </div>

                <Card className="border-slate-200/80 shadow-sm">
                    <CardHeader className="border-b border-slate-100 bg-slate-50/50 pb-4">
                        <div className="flex items-center gap-4">
                            <div className="relative max-w-md flex-1">
                                <Search className="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-slate-400" />
                                <Input
                                    required
                                    type="text"
                                    placeholder="Cari nama jabatan..."
                                    value={searchQuery}
                                    onChange={(e) =>
                                        setSearchQuery(e.target.value)
                                    }
                                    className="h-10 border-slate-200 pl-9"
                                    autoFocus
                                />
                            </div>
                        </div>
                    </CardHeader>
                    <CardContent className="p-0">
                        <Table>
                            <TableHeader className="bg-slate-50/70">
                                <TableRow className="border-b border-slate-100">
                                    <TableHead className="w-[80px] font-semibold text-slate-700">
                                        No
                                    </TableHead>
                                    <TableHead className="font-semibold text-slate-700">
                                        Nama Jabatan
                                    </TableHead>
                                    <TableHead className="w-[200px] font-semibold text-slate-700">
                                        Tipe Pengisian
                                    </TableHead>
                                    <TableHead className="w-[120px] text-right font-semibold text-slate-700">
                                        Aksi
                                    </TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {filteredJabatan.length === 0 ? (
                                    <TableRow>
                                        <TableCell
                                            colSpan={4}
                                            className="h-32 text-center text-slate-400"
                                        >
                                            Tidak ada data master jabatan yang
                                            ditemukan.
                                        </TableCell>
                                    </TableRow>
                                ) : (
                                    filteredJabatan.map((item, index) => (
                                        <TableRow
                                            key={item.id}
                                            className="h-14 border-b border-slate-100 hover:bg-slate-50/50"
                                        >
                                            <TableCell className="font-medium text-slate-600">
                                                {index + 1}
                                            </TableCell>
                                            <TableCell className="font-semibold text-slate-900">
                                                {item.nama_jabatan}
                                            </TableCell>
                                            <TableCell>
                                                {Boolean(item.is_singleton) ? (
                                                    <Badge className="gap-1 rounded-full border-amber-200 bg-amber-50 px-2.5 py-1 font-medium text-amber-700 shadow-none">
                                                        <ShieldAlert className="h-3.5 w-3.5 text-amber-600" />
                                                        1 Pegawai (Singleton)
                                                    </Badge>
                                                ) : (
                                                    <Badge className="gap-1 rounded-full border-blue-200 bg-blue-50 px-2.5 py-1 font-medium text-blue-700 shadow-none">
                                                        <Users className="h-3.5 w-3.5 text-blue-600" />
                                                        Multi Pegawai
                                                    </Badge>
                                                )}
                                            </TableCell>
                                            <TableCell className="text-right">
                                                <div className="flex justify-end gap-2">
                                                    <Button
                                                        variant="ghost"
                                                        size="icon"
                                                        onClick={() =>
                                                            handleOpenEdit(item)
                                                        }
                                                        className="h-9 w-9 text-slate-500 hover:bg-blue-50 hover:text-blue-600"
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
                                                        className="h-9 w-9 text-slate-500 hover:bg-red-50 hover:text-red-600"
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

            {/* Form Sheet */}
            <Sheet open={isOpenSheet} onOpenChange={setIsOpenSheet}>
                <SheetContent
                    side="right"
                    className="flex w-full flex-col justify-between border-l border-slate-200 bg-white p-6 sm:max-w-md"
                >
                    <form
                        onSubmit={handleSubmit}
                        className="flex flex-1 flex-col justify-between space-y-6"
                    >
                        <div className="space-y-6">
                            <SheetHeader className="space-y-1 text-left">
                                <SheetTitle className="text-lg font-bold text-slate-900">
                                    {editId
                                        ? 'Ubah Data Master Jabatan'
                                        : 'Tambah Master Jabatan Baru'}
                                </SheetTitle>
                                <SheetDescription className="text-sm text-slate-500">
                                    Silakan isi form di bawah ini dengan nama
                                    jabatan yang valid.
                                </SheetDescription>
                            </SheetHeader>

                            <div className="space-y-5 pt-4">
                                <div className="space-y-2">
                                    <Label
                                        htmlFor="nama_jabatan"
                                        className="text-sm font-semibold text-slate-700"
                                    >
                                        Nama Jabatan Resmi{' '}
                                        <span className="text-red-500">*</span>
                                    </Label>
                                    <Input
                                        required
                                        id="nama_jabatan"
                                        type="text"
                                        value={data.nama_jabatan}
                                        onChange={(e) =>
                                            setData(
                                                'nama_jabatan',
                                                e.target.value,
                                            )
                                        }
                                        placeholder="Contoh: Kepala Dinas, Analis Kebijakan Ahli Pertama"
                                        className="h-11 border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                    />
                                    <InputError message={errors.nama_jabatan} />
                                </div>

                                {/* Field Is_Singleton Constraint */}
                                <div className="flex flex-col gap-3 rounded-lg border border-slate-200 bg-slate-50/50 p-4">
                                    <div className="flex items-center justify-between">
                                        <div className="space-y-0.5">
                                            <Label
                                                htmlFor="is_singleton"
                                                className="text-sm font-semibold text-slate-800"
                                            >
                                                Batasi Pengisian (Singleton)
                                            </Label>
                                            <p className="max-w-[260px] text-xs leading-normal text-slate-500">
                                                Aktifkan jika jabatan ini
                                                eksklusif dan hanya boleh
                                                ditempati oleh **1 orang
                                                pegawai** dalam satu waktu (cth:
                                                Kepala Sub Bagian).
                                            </p>
                                        </div>
                                        <Switch
                                            id="is_singleton"
                                            checked={data.is_singleton}
                                            onCheckedChange={(checked) =>
                                                setData('is_singleton', checked)
                                            }
                                        />
                                    </div>
                                    <InputError message={errors.is_singleton} />
                                </div>
                            </div>
                        </div>

                        <SheetFooter className="mt-auto gap-2 border-t border-slate-100 pt-4">
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

JabatanIndex.layout = (page: React.ReactNode) => (
    <AppLayout breadcrumbs={[{ title: 'Jabatan', href: '#' }]}>
        {page}
    </AppLayout>
);
