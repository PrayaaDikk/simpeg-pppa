import React, { useEffect } from 'react';
import { useForm } from '@inertiajs/react';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { SheetFooter } from '@/components/ui/sheet';
import InputError from '@/components/input-error';

interface FormJabatanProps {
    pegawaiId: number;
    editId: number | null;
    initialData: any;
    onClose: () => void;
}

// Helper lokal untuk memastikan format input tanggal murni yyyy-MM-dd saat render di form
function formatToInputDate(dateSource: string | null | undefined): string {
    if (!dateSource) return '';
    try {
        const date = new Date(dateSource);
        if (isNaN(date.getTime())) return '';
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    } catch {
        return '';
    }
}

export default function FormJabatan({
    pegawaiId,
    editId,
    initialData,
    onClose,
}: FormJabatanProps) {
    // Menyesuaikan state form secara presisi dengan struktur field database migration
    const { data, setData, post, put, processing, errors, reset } = useForm({
        nama_jabatan: '',
        tmt_jabatan: '',
        nomor_sk: '', // Diubah dari no_sk -> nomor_sk
        tanggal_sk: '',
    });

    // Efek sinkronisasi penanganan siklus muat data edit/tambah baru
    useEffect(() => {
        if (editId && initialData) {
            setData({
                nama_jabatan: initialData.nama_jabatan || '',
                tmt_jabatan: formatToInputDate(initialData.tmt_jabatan),
                nomor_sk: initialData.nomor_sk || '',
                tanggal_sk: formatToInputDate(initialData.tanggal_sk),
            });
        } else {
            reset('nama_jabatan', 'tmt_jabatan', 'nomor_sk', 'tanggal_sk');
        }
    }, [editId, initialData]);

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        // Disarankan memakai POST route passthrough demi konsistensi penanganan unggahan data riwayat admin
        if (editId) {
            put(
                `/admin/pegawai/${pegawaiId}/riwayat/riwayat-jabatan/${editId}`,
                {
                    onSuccess: () => {
                        onClose();
                        reset();
                    },
                },
            );
        } else {
            post(`/admin/pegawai/${pegawaiId}/riwayat/riwayat-jabatan`, {
                onSuccess: () => {
                    onClose();
                    reset();
                },
            });
        }
    };

    return (
        <form onSubmit={handleSubmit} className="space-y-4 pt-4">
            {/* Input Nama Jabatan */}
            <div className="space-y-1.5">
                <Label
                    htmlFor="nama_jabatan"
                    className="text-xs font-semibold text-slate-700"
                >
                    Nama Jabatan Kedudukan
                </Label>
                <Input
                    required
                    id="nama_jabatan"
                    value={data.nama_jabatan}
                    onChange={(e) => setData('nama_jabatan', e.target.value)}
                    className="h-10 rounded-xl border-slate-200 text-xs"
                    placeholder="Contoh: Kepala Sub Bagian Kepegawaian"
                />
                <InputError message={errors.nama_jabatan} />
            </div>

            {/* Input TMT Jabatan */}
            <div className="space-y-1.5">
                <Label
                    htmlFor="tmt_jabatan"
                    className="text-xs font-semibold text-slate-700"
                >
                    Terhitung Mulai Tanggal (TMT) Jabatan
                </Label>
                <Input
                    required
                    id="tmt_jabatan"
                    type="date"
                    value={data.tmt_jabatan}
                    onChange={(e) => setData('tmt_jabatan', e.target.value)}
                    className="h-10 rounded-xl border-slate-200 text-xs"
                />
                <InputError message={errors.tmt_jabatan} />
            </div>

            {/* Input Nomor SK */}
            <div className="space-y-1.5">
                <Label
                    htmlFor="nomor_sk"
                    className="text-xs font-semibold text-slate-700"
                >
                    Nomor SK Jabatan
                </Label>
                <Input
                    required
                    id="nomor_sk"
                    value={data.nomor_sk}
                    onChange={(e) => setData('nomor_sk', e.target.value)}
                    className="h-10 rounded-xl border-slate-200 font-mono text-xs"
                    placeholder="Contoh: 821/SK-JABATAN/2026"
                />
                <InputError message={errors.nomor_sk} />
            </div>

            {/* Input Tanggal SK */}
            <div className="space-y-1.5">
                <Label
                    htmlFor="tanggal_sk"
                    className="text-xs font-semibold text-slate-700"
                >
                    Tanggal SK Diterbitkan
                </Label>
                <Input
                    required
                    id="tanggal_sk"
                    type="date"
                    value={data.tanggal_sk}
                    onChange={(e) => setData('tanggal_sk', e.target.value)}
                    className="h-10 rounded-xl border-slate-200 text-xs"
                />
                <InputError message={errors.tanggal_sk} />
            </div>

            {/* Tombol Aksi Sheet Footer */}
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
                    className="h-11 flex-1 bg-purple-600 text-white shadow-sm hover:bg-purple-700"
                >
                    {editId ? 'Perbarui Jabatan' : 'Simpan Jabatan'}
                </Button>
            </SheetFooter>
        </form>
    );
}
