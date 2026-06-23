import React, { useEffect } from 'react';
import { useForm, router } from '@inertiajs/react';
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
import { formatToInputDate } from '@/utils/helper';
import { FileText, ExternalLink, Trash2 } from 'lucide-react';

interface FormPangkatProps {
    pegawaiId: number;
    editId: number | null;
    initialData: any;
    masterPangkat: Array<{
        id: number;
        nama_pangkat: string;
        golongan: string;
    }>;
    onClose: () => void;
}

export default function FormPangkat({
    pegawaiId,
    editId,
    initialData,
    masterPangkat,
    onClose,
}: FormPangkatProps) {
    // Tambahkan key file_sk untuk penanganan upload multipart form data
    const { data, setData, post, put, processing, errors, reset } = useForm({
        pangkat_id: '',
        tmt_pangkat: '',
        nomor_sk: '',
        file_sk: null as File | null, // <-- State file input
    });

    useEffect(() => {
        if (editId && initialData) {
            setData({
                pangkat_id: initialData.pangkat_id?.toString() || '',
                tmt_pangkat: formatToInputDate(initialData.tmt_pangkat),
                nomor_sk: initialData.nomor_sk || '',
                file_sk: null, // Reset input file visual saat edit dimuat
            });
        } else {
            reset('pangkat_id', 'tmt_pangkat', 'nomor_sk', 'file_sk');
        }
    }, [editId, initialData]);

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        // Gunakan method POST melompati limitasi spoofing berkas biner multipart PHP
        if (editId) {
            put(
                `/admin/pegawai/${pegawaiId}/riwayat/riwayat-pangkat/${editId}`,
                {
                    onSuccess: () => {
                        onClose();
                        reset();
                    },
                },
            );
        } else {
            post(`/admin/pegawai/${pegawaiId}/riwayat/riwayat-pangkat`, {
                onSuccess: () => {
                    onClose();
                    reset();
                },
            });
        }
    };

    return (
        <form onSubmit={handleSubmit} className="space-y-4 pt-4">
            {/* Input Pangkat / Golongan */}
            <div className="space-y-1.5">
                <Label htmlFor="pangkat_id">Pangkat / Golongan</Label>
                <Select
                    key={data.pangkat_id}
                    value={data.pangkat_id || ''}
                    onValueChange={(val) => setData('pangkat_id', val)}
                >
                    <SelectTrigger
                        id="pangkat_id"
                        className="h-10 rounded-xl border-slate-200 text-xs"
                    >
                        <SelectValue placeholder="Pilih Pangkat & Golongan" />
                    </SelectTrigger>
                    <SelectContent className="bg-white">
                        {masterPangkat.map((pangkat) => (
                            <SelectItem
                                key={pangkat.id}
                                value={pangkat.id.toString()}
                                className="hover:bg-slate-100"
                            >
                                {pangkat.nama_pangkat} - ({pangkat.golongan})
                            </SelectItem>
                        ))}
                    </SelectContent>
                </Select>
                <InputError message={errors.pangkat_id} />
            </div>

            {/* Input TMT Pangkat */}
            <div className="space-y-1.5">
                <Label htmlFor="tmt_pangkat">
                    Terhitung Mulai Tanggal (TMT)
                </Label>
                <Input
                    required
                    id="tmt_pangkat"
                    type="date"
                    value={data.tmt_pangkat}
                    onChange={(e) => setData('tmt_pangkat', e.target.value)}
                    className="block h-10 rounded-xl border-slate-200 text-xs"
                />
                <InputError message={errors.tmt_pangkat} />
            </div>

            {/* Input Nomor SK */}
            <div className="space-y-1.5">
                <Label htmlFor="nomor_sk">Nomor Surat Keputusan (SK)</Label>
                <Input
                    required
                    id="nomor_sk"
                    value={data.nomor_sk}
                    onChange={(e) => setData('nomor_sk', e.target.value)}
                    className="h-10 rounded-xl border-slate-200 font-mono text-xs"
                    placeholder="Contoh: 800/SK-PANGKAT/2026"
                />
                <InputError message={errors.nomor_sk} />
            </div>

            {/* BARU: Input Dokumen File SK */}
            <div className="space-y-2">
                <Label
                    htmlFor="file_sk"
                    className="text-xs font-semibold text-slate-700"
                >
                    Unggah Berkas SK Pangkat
                </Label>
                <Input
                    id="file_sk"
                    type="file"
                    accept=".pdf, .jpg, .jpeg, .png"
                    onChange={(e) =>
                        setData('file_sk', e.target.files?.[0] || null)
                    }
                    className="h-10 rounded-xl border-slate-200 pt-2 text-xs file:mr-2 file:font-semibold file:text-blue-600"
                />

                {/* MODERN CLEAN-CARD COMPONENT PREVIEW & ACTION */}
                {editId && initialData?.file_sk && (
                    <div className="shadow-3xs mt-3 overflow-hidden rounded-2xl border border-slate-200 bg-slate-50/50 p-4">
                        {/* BARIS UTAMA: INFO DOKUMEN */}
                        <div className="flex flex-col justify-between gap-3 border-b border-slate-100 pb-3 sm:flex-row sm:items-center">
                            <div className="flex items-start gap-2.5">
                                <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl border border-amber-100 bg-amber-50 text-amber-600">
                                    <FileText className="h-4 w-4" />
                                </div>
                                <div className="space-y-0.5">
                                    <h4 className="text-xs font-bold text-slate-800">
                                        Dokumen SK Aktif
                                    </h4>
                                    <p className="flex items-center gap-1 text-[11px] font-medium text-emerald-600">
                                        <span className="h-1.5 w-1.5 animate-pulse rounded-full bg-emerald-500" />
                                        Berkas SK saat ini sudah aman tersimpan
                                        di sistem
                                    </p>
                                </div>
                            </div>
                        </div>

                        {/* BARIS KEDUA: GRUP TOMBOL AKSI & PETUNJUK */}
                        <div className="mt-3 flex flex-col justify-between gap-4 sm:items-center">
                            <p className="max-w-[280px] text-[10px] leading-relaxed font-medium text-slate-400">
                                * Abaikan atau kosongkan input file di atas jika
                                Anda tidak berniat mengubah berkas SK yang sudah
                                ada.
                            </p>

                            {/* BUTTON CONTAINER - LEBIH LUAS */}
                            <div className="flex items-center gap-2 self-end sm:self-auto">
                                {/* TOMBOL: LIHAT BERKAS */}
                                <a
                                    href={`/storage/${initialData.file_sk}`}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="shadow-3xs inline-flex h-9 cursor-pointer items-center gap-2 rounded-xl border border-amber-200 bg-amber-50/60 px-3.5 text-xs font-bold text-amber-700 transition-all duration-200 hover:bg-amber-100 hover:text-amber-800 active:scale-95"
                                    title="Buka Berkas SK di Tab Baru"
                                >
                                    <FileText className="h-4 w-4 text-amber-600" />
                                    Lihat Berkas
                                    <ExternalLink className="h-3.5 w-3.5 text-amber-400" />
                                </a>

                                {/* TOMBOL: HAPUS BERKAS */}
                                <button
                                    type="button"
                                    onClick={() => {
                                        if (
                                            confirm(
                                                'Apakah Anda yakin ingin menghapus berkas dokumen SK ini saja dari sistem?',
                                            )
                                        ) {
                                            router.delete(
                                                `/admin/pegawai/${pegawaiId}/riwayat/riwayat-pangkat/${editId}/hapus-sk`,
                                                {
                                                    preserveState: true,
                                                    preserveScroll: true,
                                                    onSuccess: () => {
                                                        setData(
                                                            'file_sk',
                                                            null,
                                                        );
                                                        if (initialData) {
                                                            initialData.file_sk =
                                                                null;
                                                        }
                                                    },
                                                },
                                            );
                                        }
                                    }}
                                    className="shadow-3xs inline-flex h-9 cursor-pointer items-center gap-2 rounded-xl border border-red-200 bg-red-50/60 px-3.5 text-xs font-bold text-red-700 transition-all duration-200 hover:bg-red-100 hover:text-red-800 active:scale-95"
                                    title="Hapus berkas SK saja dari server"
                                >
                                    <Trash2 className="h-4 w-4 text-red-600" />
                                    Hapus Berkas
                                </button>
                            </div>
                        </div>
                    </div>
                )}
                <InputError message={errors.file_sk} />
            </div>

            {/* Tombol Aksi */}
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
                    className="h-11 flex-1 bg-amber-600 text-white shadow-sm hover:bg-amber-700"
                >
                    {editId ? 'Perbarui Golongan' : 'Simpan Pendidikan'}
                </Button>
            </SheetFooter>
        </form>
    );
}
