import React, { useEffect } from 'react';
import { useForm } from '@inertiajs/react';
import { Calendar, User } from 'lucide-react';
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
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import InputError from '@/components/input-error';
import { formatDateToISO } from '@/lib/utils';

interface CreateKgbSheetProps {
    isOpen: boolean;
    onClose: () => void; // Diselaraskan dengan pemanggilan dari index.tsx
    pegawaiList: any[];
    initialData?: any | null; // Menerima lemparan data dari tombol "Proses KGB"
}

export default function CreateKgbSheet({
    isOpen,
    onClose,
    pegawaiList = [],
    initialData,
}: CreateKgbSheetProps) {
    // Inisialisasi useForm dipindahkan ke dalam internal komponen agar enkapsulasi data aman
    const form = useForm({
        parent_id: '',
        pegawai_id: '',
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

    // Sinkronisasi data otomatis jika form di-trigger dari tombol "Proses KGB" bulan berjalan
    useEffect(() => {
        if (isOpen) {
            if (initialData) {
                form.setData({
                    parent_id: initialData.id?.toString() || '',
                    pegawai_id: initialData.pegawai_id?.toString() || '',
                    gaji_lama: initialData.gaji_lama?.toString() || '',
                    gaji_baru: initialData.gaji_baru?.toString() || '',
                    golongan_lama: initialData.golongan_lama || '',
                    golongan_baru: initialData.golongan_baru || '',
                    masa_kerja_lama: initialData.masa_kerja_lama || '',
                    masa_kerja_baru: initialData.masa_kerja_baru || '',
                    tmt_gaji_lama:
                        formatDateToISO(initialData.tmt_gaji_lama) || '',
                    tmt_gaji_baru:
                        formatDateToISO(initialData.tmt_gaji_baru) || '',
                    kgb_berikutnya:
                        formatDateToISO(initialData.kgb_berikutnya) || '',
                });
            } else {
                form.reset();
            }
            form.clearErrors();
        }
    }, [isOpen, initialData]);

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        form.post('/admin/kgb/store', {
            onSuccess: () => {
                form.reset();
                onClose();
            },
            onError: (e) => {
                console.error(e);
            },
        });
    };

    return (
        <Sheet open={isOpen} onOpenChange={(open) => !open && onClose()}>
            <SheetContent className="w-full overflow-y-auto border-l border-slate-100 bg-white p-6 shadow-2xl sm:max-w-md">
                <SheetHeader className="border-b border-slate-100 pb-4">
                    <SheetTitle className="text-lg font-bold text-slate-900">
                        {initialData
                            ? 'Proses Kenaikan Berkala Baru'
                            : 'Tambah Berkas Baru KGB'}
                    </SheetTitle>
                    <SheetDescription className="text-xs text-slate-500">
                        Isi lembar formulir berikut untuk mendaftarkan keputusan
                        riwayat KGB pegawai.
                    </SheetDescription>
                </SheetHeader>

                <form
                    onSubmit={handleSubmit}
                    className="mt-5 flex flex-col gap-4"
                >
                    <input type="hidden" value={form.data.parent_id} />

                    {/* Input Pilih Pegawai */}
                    <div className="flex flex-col gap-1.5">
                        <label className="text-xs font-bold tracking-wide text-slate-500 uppercase">
                            Pegawai Terkait
                        </label>
                        <Select
                            value={form.data.pegawai_id}
                            onValueChange={(value) =>
                                form.setData('pegawai_id', value)
                            }
                            disabled={!!initialData} // Kunci pilihan jika memproses dari data bulan berjalan
                        >
                            <SelectTrigger className="h-11 border-slate-200 bg-white text-xs font-medium focus:ring-blue-500">
                                <SelectValue placeholder="Pilih Pegawai Aktif" />
                            </SelectTrigger>
                            <SelectContent className="max-h-60 rounded-xl bg-white shadow-lg">
                                {pegawaiList && pegawaiList.length > 0 ? (
                                    pegawaiList.map((p) => (
                                        <SelectItem
                                            key={p.id}
                                            value={p.id.toString()}
                                            className="cursor-pointer text-xs font-medium"
                                        >
                                            {p.nama} — NIP. {p.nip}
                                        </SelectItem>
                                    ))
                                ) : (
                                    <div className="p-2 text-center text-xs text-slate-400">
                                        Tidak ada data pegawai aktif
                                    </div>
                                )}
                            </SelectContent>
                        </Select>
                        <InputError message={form.errors.pegawai_id} />
                    </div>

                    {/* Grid Golongan Ruang */}
                    <div className="flex flex-col gap-1.5">
                        <label className="text-xs font-bold tracking-wide text-slate-500 uppercase">
                            Golongan Lama
                        </label>
                        <Input
                            required
                            type="text"
                            placeholder="Contoh: Penata Muda, III/a"
                            value={form.data.golongan_lama}
                            onChange={(e) =>
                                form.setData('golongan_lama', e.target.value)
                            }
                            className="h-11 border-slate-200 text-xs font-medium"
                        />
                        <InputError message={form.errors.golongan_lama} />
                    </div>
                    <div className="flex flex-col gap-1.5">
                        <label className="text-xs font-bold tracking-wide text-slate-500 uppercase">
                            Golongan Baru
                        </label>
                        <Input
                            required
                            type="text"
                            placeholder="Contoh: Penata Muda Tingkat I, III/b"
                            value={form.data.golongan_baru}
                            onChange={(e) =>
                                form.setData('golongan_baru', e.target.value)
                            }
                            className="h-11 border-slate-200 text-xs font-medium"
                        />
                        <InputError message={form.errors.golongan_baru} />
                    </div>

                    {/* Grid Gaji Pokok */}
                    <div className="grid grid-cols-2 gap-4">
                        <div className="flex flex-col gap-1.5">
                            <label className="text-xs font-bold tracking-wide text-slate-500 uppercase">
                                Gaji Pokok Lama
                            </label>
                            <Input
                                required
                                type="number"
                                placeholder="Nominal Rp"
                                value={form.data.gaji_lama}
                                onChange={(e) =>
                                    form.setData('gaji_lama', e.target.value)
                                }
                                className="h-11 border-slate-200 text-xs font-medium"
                            />
                            <InputError message={form.errors.gaji_lama} />
                        </div>
                        <div className="flex flex-col gap-1.5">
                            <label className="text-xs font-bold tracking-wide text-slate-500 uppercase">
                                Gaji Pokok Baru
                            </label>
                            <Input
                                required
                                type="number"
                                placeholder="Nominal Rp"
                                value={form.data.gaji_baru}
                                onChange={(e) =>
                                    form.setData('gaji_baru', e.target.value)
                                }
                                className="h-11 border-slate-200 text-xs font-medium"
                            />
                            <InputError message={form.errors.gaji_baru} />
                        </div>
                    </div>

                    {/* Grid Masa Kerja */}
                    <div className="grid grid-cols-2 gap-4">
                        <div className="flex flex-col gap-1.5">
                            <label className="text-xs font-bold tracking-wide text-slate-500 uppercase">
                                MKG Lama (Tahun/Bulan)
                            </label>
                            <Input
                                type="text"
                                placeholder="Contoh: 10 Tahun 02 Bulan"
                                value={form.data.masa_kerja_lama}
                                onChange={(e) =>
                                    form.setData(
                                        'masa_kerja_lama',
                                        e.target.value,
                                    )
                                }
                                className="h-11 border-slate-200 text-xs font-medium"
                            />
                            <InputError message={form.errors.masa_kerja_lama} />
                        </div>
                        <div className="flex flex-col gap-1.5">
                            <label className="text-xs font-bold tracking-wide text-slate-500 uppercase">
                                MKG Baru (Tahun/Bulan)
                            </label>
                            <Input
                                type="text"
                                placeholder="Contoh: 12 Tahun 02 Bulan"
                                value={form.data.masa_kerja_baru}
                                onChange={(e) =>
                                    form.setData(
                                        'masa_kerja_baru',
                                        e.target.value,
                                    )
                                }
                                className="h-11 border-slate-200 text-xs font-medium"
                            />
                            <InputError message={form.errors.masa_kerja_baru} />
                        </div>
                    </div>

                    {/* Tanggal Mulai Berlaku SK (TMT) */}
                    <div className="flex flex-col gap-1.5">
                        <label className="text-xs font-bold tracking-wide text-slate-500 uppercase">
                            Tanggal Mulai Terhitung (TMT) Sebelumnya
                        </label>
                        <div className="relative">
                            <Calendar className="absolute top-3.5 left-3 h-4 w-4 text-slate-400" />
                            <Input
                                type="date"
                                value={form.data.tmt_gaji_lama}
                                onChange={(e) =>
                                    form.setData(
                                        'tmt_gaji_lama',
                                        e.target.value,
                                    )
                                }
                                className="h-11 border-slate-200 pl-10 text-xs font-medium focus-visible:ring-blue-500"
                            />
                        </div>
                        <InputError message={form.errors.tmt_gaji_lama} />
                    </div>

                    {/* Tanggal Mulai Berlaku SK (TMT) */}
                    <div className="flex flex-col gap-1.5">
                        <label className="text-xs font-bold tracking-wide text-slate-500 uppercase">
                            Tanggal Mulai Terhitung (TMT) Baru
                        </label>
                        <div className="relative">
                            <Calendar className="absolute top-3.5 left-3 h-4 w-4 text-slate-400" />
                            <Input
                                type="date"
                                value={form.data.tmt_gaji_baru}
                                onChange={(e) =>
                                    form.setData(
                                        'tmt_gaji_baru',
                                        e.target.value,
                                    )
                                }
                                className="h-11 border-slate-200 pl-10 text-xs font-medium focus-visible:ring-blue-500"
                            />
                        </div>
                        <InputError message={form.errors.tmt_gaji_baru} />
                    </div>

                    {/* Tanggal Jadwal Berkala Selanjutnya */}
                    <div className="flex flex-col gap-1.5">
                        <label className="text-xs font-bold tracking-wide text-slate-500 uppercase">
                            Tanggal KGB Berikutnya
                        </label>
                        <div className="relative">
                            <Calendar className="absolute top-3.5 left-3 h-4 w-4 text-slate-400" />
                            <Input
                                type="date"
                                value={form.data.kgb_berikutnya}
                                onChange={(e) =>
                                    form.setData(
                                        'kgb_berikutnya',
                                        e.target.value,
                                    )
                                }
                                className="h-11 border-slate-200 pl-10 text-xs font-medium focus-visible:ring-blue-500"
                            />
                        </div>
                        <InputError message={form.errors.kgb_berikutnya} />
                    </div>

                    <SheetFooter className="mt-4 pt-2">
                        <Button
                            type="submit"
                            disabled={form.processing}
                            className="h-11 w-full cursor-pointer rounded-xl bg-blue-600 text-xs font-semibold text-white hover:bg-blue-700"
                        >
                            {form.processing
                                ? 'Menyimpan...'
                                : 'Simpan & Daftarkan Usulan'}
                        </Button>
                    </SheetFooter>
                </form>
            </SheetContent>
        </Sheet>
    );
}
