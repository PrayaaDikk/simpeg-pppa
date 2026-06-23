import React, { useEffect } from 'react';
import { router, useForm } from '@inertiajs/react';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { SheetFooter } from '@/components/ui/sheet';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import InputError from '@/components/input-error';
import { ExternalLink, FileText, Trash2 } from 'lucide-react';

interface FormPendidikanProps {
    pegawaiId: number;
    editId: number | null;
    initialData: any;
    onClose: () => void;
}

export default function FormPendidikan({
    pegawaiId,
    editId,
    initialData,
    onClose,
}: FormPendidikanProps) {
    // Inisialisasi useForm dipetakan secara presisi ke kolom skema database & rules controller
    const { data, setData, post, put, processing, errors, reset } = useForm({
        tingkat: '',
        institusi: '',
        jurusan: '',
        tahun_lulus: '',
        ijazah: null as File | null,
    });

    // FIX BUG EDIT: Sinkronisasi data ketika tombol edit ditekan (initialData terisi)
    useEffect(() => {
        if (editId && initialData) {
            setData({
                tingkat: initialData.tingkat || '',
                institusi: initialData.institusi || '',
                jurusan: initialData.jurusan || '',
                tahun_lulus: initialData.tahun_lulus?.toString() || '',
                ijazah: initialData.ijazah || null, // Berkas ijazah baru tetap null sampai user memilih file baru
            });
        } else {
            // Jika mode tambah baru, kosongkan kembali form
            reset('tingkat', 'institusi', 'jurusan', 'tahun_lulus', 'ijazah');
        }
    }, [editId, initialData]);

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        // 1. Siapkan objek data yang akan dikirim
        const sendData: any = {
            tingkat: data.tingkat,
            institusi: data.institusi,
            jurusan: data.jurusan,
            tahun_lulus: data.tahun_lulus,
        };

        if (editId) {
            // 2. Jika user memilih file baru (berupa objek File), masukkan ke data yang dikirim
            if (data.ijazah instanceof File) {
                sendData.ijazah = data.ijazah;

                // PENTING: Jika ada upload file pada metode UPDATE (PUT/PATCH),
                // Inertia & Laravel membutuhkan spoofing method melalui POST agar file tidak hilang/corrupt
                sendData._method = 'put';
                post(
                    `/admin/pegawai/${pegawaiId}/riwayat/riwayat-pendidikan/${editId}`,
                    {
                        data: sendData,
                        forceFormData: true,
                        onSuccess: () => {
                            onClose();
                            reset();
                        },
                    },
                );
            } else {
                // 3. Jika tidak ganti file ijazah, gunakan rute PUT murni bawaan Inertia (lebih aman untuk teks JSON)
                post(
                    `/admin/pegawai/${pegawaiId}/riwayat/riwayat-pendidikan/${editId}`,
                    {
                        ...sendData,
                        onSuccess: () => {
                            onClose();
                            reset();
                        },
                    },
                );
            }
        } else {
            // Mode Simpan Baru (Tetap seperti kode asli Anda)
            if (data.ijazah) {
                sendData.ijazah = data.ijazah;
            }
            post(`/admin/pegawai/${pegawaiId}/riwayat/riwayat-pendidikan`, {
                data: sendData,
                onSuccess: () => {
                    onClose();
                    reset();
                },
            });
        }
    };

    return (
        <form onSubmit={handleSubmit} className="space-y-4 pt-4">
            <div className="space-y-1.5">
                <Label htmlFor="tingkat">Tingkat Pendidikan</Label>
                <Select
                    key={data.tingkat}
                    value={data.tingkat || ''}
                    onValueChange={(val) => setData('tingkat', val)}
                >
                    <SelectTrigger
                        id="tingkat"
                        className="h-10 rounded-xl border-slate-200 text-xs"
                    >
                        <SelectValue placeholder="Pilih Tingkat" />
                    </SelectTrigger>
                    <SelectContent className="bg-white">
                        <SelectItem className="hover:bg-slate-100" value="SMA">
                            SMA / Sederajat
                        </SelectItem>
                        <SelectItem className="hover:bg-slate-100" value="D1">
                            Diploma I (D1)
                        </SelectItem>
                        <SelectItem className="hover:bg-slate-100" value="D2">
                            Diploma II (D2)
                        </SelectItem>
                        <SelectItem className="hover:bg-slate-100" value="D3">
                            Diploma III (D3)
                        </SelectItem>
                        <SelectItem className="hover:bg-slate-100" value="D4">
                            Diploma IV (D4)
                        </SelectItem>
                        <SelectItem className="hover:bg-slate-100" value="S1">
                            Sarjana Strata I (S1)
                        </SelectItem>
                        <SelectItem className="hover:bg-slate-100" value="S2">
                            Magister Strata II (S2)
                        </SelectItem>
                        <SelectItem className="hover:bg-slate-100" value="S3">
                            Doktor Strata III (S3)
                        </SelectItem>
                    </SelectContent>
                </Select>
                <InputError message={errors.tingkat} />
            </div>

            <div className="space-y-1.5">
                <Label htmlFor="institusi">Nama Institusi / Universitas</Label>
                <Input
                    required
                    id="institusi"
                    value={data.institusi}
                    onChange={(e) => setData('institusi', e.target.value)}
                    className="h-10 rounded-xl border-slate-200 text-xs"
                    placeholder="Contoh: Universitas Halu Oleo"
                />
                <InputError message={errors.institusi} />
            </div>

            <div className="space-y-1.5">
                <Label htmlFor="jurusan">Jurusan / Program Studi</Label>
                <Input
                    required
                    id="jurusan"
                    value={data.jurusan}
                    onChange={(e) => setData('jurusan', e.target.value)}
                    className="h-10 rounded-xl border-slate-200 text-xs"
                    placeholder="Contoh: Teknik Informatika"
                />
                <InputError message={errors.jurusan} />
            </div>

            <div className="space-y-1.5">
                <Label htmlFor="tahun_lulus">Tahun Lulus</Label>
                <Input
                    required
                    id="tahun_lulus"
                    type="number"
                    value={data.tahun_lulus}
                    onChange={(e) => setData('tahun_lulus', e.target.value)}
                    className="h-10 rounded-xl border-slate-200 text-xs"
                    placeholder="Contoh: 2024"
                />
                <InputError message={errors.tahun_lulus} />
            </div>

            <div className="space-y-1.5">
                <Label htmlFor="ijazah">
                    Berkas Dokumen Ijazah (PDF/JPG/PNG, Max 2MB)
                </Label>
                <Input
                    id="ijazah"
                    type="file"
                    accept=".pdf, .jpg, .jpeg, .png"
                    onChange={(e) =>
                        setData('ijazah', e.target.files?.[0] || null)
                    }
                    className="h-10 rounded-xl border-slate-200 pt-2 text-xs"
                />

                {editId && initialData?.ijazah && (
                    <div className="shadow-3xs mt-3 overflow-hidden rounded-2xl border border-slate-200 bg-slate-50/50 p-4">
                        {/* BARIS UTAMA: INFO DOKUMEN */}
                        <div className="flex flex-col justify-between gap-3 border-b border-slate-100 pb-3 sm:flex-row sm:items-center">
                            <div className="flex items-start gap-2.5">
                                <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl border border-blue-100 bg-blue-50 text-blue-600">
                                    <FileText className="h-4 w-4" />
                                </div>
                                <div className="space-y-0.5">
                                    <h4 className="text-xs font-bold text-slate-800">
                                        Dokumen Ijazah Aktif
                                    </h4>
                                    <p className="flex items-center gap-1 text-[11px] font-medium text-emerald-600">
                                        <span className="h-1.5 w-1.5 animate-pulse rounded-full bg-emerald-500" />
                                        Berkas saat ini sudah aman tersimpan di
                                        sistem
                                    </p>
                                </div>
                            </div>
                        </div>

                        {/* BARIS KEDUA: GRUP TOMBOL AKSI & PETUNJUK */}
                        <div className="mt-3 flex flex-col justify-between gap-4 sm:items-center">
                            <p className="max-w-[280px] text-[10px] leading-relaxed font-medium text-slate-400">
                                * Abaikan atau kosongkan input file di atas jika
                                Anda tidak berniat mengubah berkas ijazah yang
                                sudah ada.
                            </p>

                            {/* BUTTON CONTAINER - LEBIH LUAS */}
                            <div className="flex items-center gap-2 self-end sm:self-auto">
                                {/* TOMBOL: LIHAT BERKAS */}
                                <a
                                    href={`/storage/${initialData.ijazah}`}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="shadow-3xs inline-flex h-9 cursor-pointer items-center gap-2 rounded-xl border border-blue-200 bg-blue-50/60 px-3.5 text-xs font-bold text-blue-700 transition-all duration-200 hover:bg-blue-100 hover:text-blue-800 active:scale-95"
                                    title="Buka Berkas Ijazah di Tab Baru"
                                >
                                    <FileText className="h-4 w-4 text-blue-600" />
                                    Lihat Berkas
                                    <ExternalLink className="h-3.5 w-3.5 text-blue-400" />
                                </a>

                                {/* TOMBOL: HAPUS BERKAS */}
                                <button
                                    type="button"
                                    onClick={() => {
                                        if (
                                            confirm(
                                                'Apakah Anda yakin ingin menghapus berkas ijazah ini saja dari sistem?',
                                            )
                                        ) {
                                            router.delete(
                                                `/admin/pegawai/${pegawaiId}/riwayat/riwayat-pendidikan/${editId}/hapus-ijazah`,
                                                {
                                                    preserveState: true,
                                                    preserveScroll: true,
                                                    onSuccess: () => {
                                                        setData('ijazah', null);
                                                        if (initialData) {
                                                            initialData.ijazah =
                                                                null;
                                                        }
                                                    },
                                                },
                                            );
                                        }
                                    }}
                                    className="shadow-3xs inline-flex h-9 cursor-pointer items-center gap-2 rounded-xl border border-red-200 bg-red-50/60 px-3.5 text-xs font-bold text-red-700 transition-all duration-200 hover:bg-red-100 hover:text-red-800 active:scale-95"
                                    title="Hapus berkas saja dari server"
                                >
                                    <Trash2 className="h-4 w-4 text-red-600" />
                                    Hapus Berkas
                                </button>
                            </div>
                        </div>
                    </div>
                )}

                {!editId && initialData?.ijazah && (
                    <p className="mt-1 text-[11px] font-medium text-emerald-600">
                        * Berkas ijazah sudah tersimpan dalam sistem.
                    </p>
                )}
                <InputError message={errors.ijazah} />
            </div>

            <SheetFooter className="mt-6 flex gap-3 border-t border-slate-100 pt-4">
                <Button
                    type="button"
                    variant="outline"
                    onClick={onClose}
                    className="h-11 flex-1 border-slate-200 text-slate-700"
                >
                    Batal
                </Button>
                <Button
                    type="submit"
                    disabled={processing}
                    className="h-11 flex-1 bg-blue-600 text-white shadow-sm hover:bg-blue-700"
                >
                    {editId ? 'Perbarui Pendidikan' : 'Simpan Pendidikan'}
                </Button>
            </SheetFooter>
        </form>
    );
}
