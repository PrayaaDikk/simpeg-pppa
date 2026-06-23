import React, { useEffect } from 'react';
import { useForm } from '@inertiajs/react';
import { Calendar } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetHeader,
    SheetTitle,
    SheetFooter,
} from '@/components/ui/sheet';
import InputError from '@/components/input-error';
import { formatDateToISO } from '@/lib/utils';

interface EditKgbSheetProps {
    isOpen: boolean;
    onClose: () => void;
    pegawaiList: any[];
    kgbData: any | null; // Diselaraskan dengan kgbData={editingKgb}
}

export default function EditKgbSheet({
    isOpen,
    onClose,
    pegawaiList = [],
    kgbData,
}: EditKgbSheetProps) {
    // Inisialisasi internal form menggunakan Inertia useForm
    const form = useForm({
        gaji_lama: '',
        gaji_baru: '',
        golongan_lama: '',
        golongan_baru: '',
        masa_kerja_lama: '',
        masa_kerja_baru: '',
        tmt_gaji_lama: '',
        tmt_gaji_baru: '',
        kgb_berikutnya: '',
    });

    // Melakukan sinkronisasi data ketika kgbData berubah (saat tombol edit diklik)
    useEffect(() => {
        if (isOpen && kgbData) {
            form.setData({
                gaji_lama: kgbData.gaji_lama || '',
                gaji_baru: kgbData.gaji_baru || '',
                golongan_lama: kgbData.golongan_lama || '',
                golongan_baru: kgbData.golongan_baru || '',
                masa_kerja_lama: kgbData.masa_kerja_lama || '',
                masa_kerja_baru: kgbData.masa_kerja_baru || '',
                tmt_gaji_lama: formatDateToISO(kgbData.tmt_gaji_lama) || '',
                tmt_gaji_baru: formatDateToISO(kgbData.tmt_gaji_baru) || '',
                kgb_berikutnya: formatDateToISO(kgbData.kgb_berikutnya) || '',
            });
        } else if (!isOpen) {
            form.reset();
            form.clearErrors();
        }
    }, [kgbData, isOpen]);

    // Handler Kirim Perubahan Data ke Backend Laravel via PUT
    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (!kgbData?.id) return;

        form.put(`/admin/kgb/${kgbData.id}`, {
            onSuccess: () => {
                onClose(); // Tutup sheet secara otomatis saat sukses diperbarui
            },
        });
    };

    return (
        <Sheet
            open={isOpen}
            onOpenChange={(open) => {
                if (!open) onClose();
            }}
        >
            <SheetContent className="w-full overflow-y-auto border-l border-slate-100 bg-white p-6 shadow-2xl sm:max-w-md">
                <SheetHeader className="border-b border-slate-100 pb-4">
                    <SheetTitle className="text-lg font-bold text-slate-900">
                        Lengkapi Dokumen SK Resmi
                    </SheetTitle>
                    <SheetDescription className="text-xs text-slate-500">
                        Edit berkas data kenaikan gaji berkala untuk pegawai{' '}
                        <span className="font-semibold text-slate-700">
                            {kgbData?.pegawai?.nama || 'Terpilih'}
                        </span>
                        . Pastikan nominal dan penanggalan valid.
                    </SheetDescription>
                </SheetHeader>

                <form
                    onSubmit={handleSubmit}
                    className="mt-5 flex flex-col gap-4"
                >
                    {/* GOLONGAN LAMA & BARU */}
                    <div className="grid grid-cols-2 gap-4">
                        <div className="flex flex-col gap-1.5">
                            <label className="text-[11px] font-bold tracking-wider text-slate-500 uppercase">
                                Golongan Lama
                            </label>
                            <Input
                                required
                                type="text"
                                placeholder="Penata Muda, III/a"
                                value={form.data.golongan_lama}
                                onChange={(e) =>
                                    form.setData(
                                        'golongan_lama',
                                        e.target.value,
                                    )
                                }
                                className="h-11 rounded-xl border-slate-200 text-xs font-medium focus-visible:ring-blue-500"
                            />
                            <InputError message={form.errors.golongan_lama} />
                        </div>
                        <div className="flex flex-col gap-1.5">
                            <label className="text-[11px] font-bold tracking-wider text-slate-500 uppercase">
                                Golongan Baru
                            </label>
                            <Input
                                required
                                type="text"
                                placeholder="Penata Muda Tingkat I, III/b"
                                value={form.data.golongan_baru}
                                onChange={(e) =>
                                    form.setData(
                                        'golongan_baru',
                                        e.target.value,
                                    )
                                }
                                className="h-11 rounded-xl border-slate-200 text-xs font-medium focus-visible:ring-blue-500"
                            />
                            <InputError message={form.errors.golongan_baru} />
                        </div>
                    </div>

                    {/* GAJI POKOK LAMA & BARU */}
                    <div className="grid grid-cols-2 gap-4">
                        <div className="flex flex-col gap-1.5">
                            <label className="text-[11px] font-bold tracking-wider text-slate-500 uppercase">
                                Gaji Pokok Lama
                            </label>
                            <Input
                                required
                                type="number"
                                placeholder="3000000"
                                value={form.data.gaji_lama}
                                onChange={(e) =>
                                    form.setData('gaji_lama', e.target.value)
                                }
                                className="h-11 rounded-xl border-slate-200 text-xs font-medium focus-visible:ring-blue-500"
                            />
                            <InputError message={form.errors.gaji_lama} />
                        </div>
                        <div className="flex flex-col gap-1.5">
                            <label className="text-[11px] font-bold tracking-wider text-slate-500 uppercase">
                                Gaji Pokok Baru
                            </label>
                            <Input
                                required
                                type="number"
                                placeholder="3200000"
                                value={form.data.gaji_baru}
                                onChange={(e) =>
                                    form.setData('gaji_baru', e.target.value)
                                }
                                className="h-11 rounded-xl border-slate-200 text-xs font-medium focus-visible:ring-blue-500"
                            />
                            <InputError message={form.errors.gaji_baru} />
                        </div>
                    </div>

                    {/* MASA KERJA LAMA & BARU */}
                    <div className="grid grid-cols-2 gap-4">
                        <div className="flex flex-col gap-1.5">
                            <label className="text-[11px] font-bold tracking-wider text-slate-500 uppercase">
                                Masa Kerja Lama
                            </label>
                            <Input
                                required
                                type="text"
                                placeholder="02 Tahun"
                                value={form.data.masa_kerja_lama}
                                onChange={(e) =>
                                    form.setData(
                                        'masa_kerja_lama',
                                        e.target.value,
                                    )
                                }
                                className="h-11 rounded-xl border-slate-200 text-xs font-medium focus-visible:ring-blue-500"
                            />
                            <InputError message={form.errors.masa_kerja_lama} />
                        </div>
                        <div className="flex flex-col gap-1.5">
                            <label className="text-[11px] font-bold tracking-wider text-slate-500 uppercase">
                                Masa Kerja Baru
                            </label>
                            <Input
                                required
                                type="text"
                                placeholder="04 Tahun"
                                value={form.data.masa_kerja_baru}
                                onChange={(e) =>
                                    form.setData(
                                        'masa_kerja_baru',
                                        e.target.value,
                                    )
                                }
                                className="h-11 rounded-xl border-slate-200 text-xs font-medium focus-visible:ring-blue-500"
                            />
                            <InputError message={form.errors.masa_kerja_baru} />
                        </div>
                    </div>

                    {/* TMT BERLAKU GAJI LAMA */}
                    <div className="flex flex-col gap-1.5">
                        <label className="text-[11px] font-bold tracking-wider text-slate-500 uppercase">
                            TMT GAJI LAMA
                        </label>
                        <div className="relative">
                            <Calendar className="absolute top-3.5 left-3 h-4 w-4 text-slate-400" />
                            <Input
                                required
                                type="date"
                                value={form.data.tmt_gaji_lama}
                                onChange={(e) =>
                                    form.setData(
                                        'tmt_gaji_lama',
                                        e.target.value,
                                    )
                                }
                                className="h-11 rounded-xl border-slate-200 pl-10 text-xs font-medium focus-visible:ring-blue-500"
                            />
                        </div>
                        <InputError message={form.errors.tmt_gaji_lama} />
                    </div>

                    {/* TMT BERLAKU GAJI BARU */}
                    <div className="flex flex-col gap-1.5">
                        <label className="text-[11px] font-bold tracking-wider text-slate-500 uppercase">
                            TMT GAJI BARU
                        </label>
                        <div className="relative">
                            <Calendar className="absolute top-3.5 left-3 h-4 w-4 text-slate-400" />
                            <Input
                                required
                                type="date"
                                value={form.data.tmt_gaji_baru}
                                onChange={(e) =>
                                    form.setData(
                                        'tmt_gaji_baru',
                                        e.target.value,
                                    )
                                }
                                className="h-11 rounded-xl border-slate-200 pl-10 text-xs font-medium focus-visible:ring-blue-500"
                            />
                        </div>
                        <InputError message={form.errors.tmt_gaji_baru} />
                    </div>

                    {/* TANGGAL KGB BERIKUTNYA */}
                    <div className="flex flex-col gap-1.5">
                        <label className="text-[11px] font-bold tracking-wider text-slate-500 uppercase">
                            Tanggal KGB Berikutnya
                        </label>
                        <div className="relative">
                            <Calendar className="absolute top-3.5 left-3 h-4 w-4 text-slate-400" />
                            <Input
                                required
                                type="date"
                                value={form.data.kgb_berikutnya}
                                onChange={(e) =>
                                    form.setData(
                                        'kgb_berikutnya',
                                        e.target.value,
                                    )
                                }
                                className="h-11 rounded-xl border-slate-200 pl-10 text-xs font-medium focus-visible:ring-blue-500"
                            />
                        </div>
                        <InputError message={form.errors.kgb_berikutnya} />
                    </div>

                    <SheetFooter className="pt-4">
                        <Button
                            type="submit"
                            disabled={form.processing || !kgbData?.id}
                            className="h-11 w-full cursor-pointer rounded-xl bg-blue-600 text-xs font-semibold text-white shadow-xs transition-all hover:bg-blue-700"
                        >
                            {form.processing
                                ? 'Menyimpan Perubahan...'
                                : 'Simpan Perubahan Data'}
                        </Button>
                    </SheetFooter>
                </form>
            </SheetContent>
        </Sheet>
    );
}
