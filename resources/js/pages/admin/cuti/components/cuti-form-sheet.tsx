import React, { useEffect } from 'react';
import { useForm } from '@inertiajs/react';
import {
    Sheet,
    SheetContent,
    SheetHeader,
    SheetTitle,
    SheetDescription,
} from '@/components/ui/sheet';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Button } from '@/components/ui/button';
import InputError from '@/components/input-error';
import { CalendarDays } from 'lucide-react'; // Ditambahkan untuk ikon indikator jatah

interface CutiFormSheetProps {
    isOpen: boolean;
    onClose: () => void;
    pegawais: any[];
    editData?: any | null;
}

export default function CutiFormSheet({
    isOpen,
    onClose,
    pegawais,
    editData = null,
}: CutiFormSheetProps) {
    const isEditMode = !!editData;

    const { data, setData, post, put, processing, errors, reset } = useForm({
        pegawai_id: '',
        atasan_id: '',
        jenis_cuti: '',
        tanggal_mulai: '',
        tanggal_akhir: '',
        lama_cuti: 0,
        alamat: '',
        no_telp: '',
        alasan_cuti: '',
        status_cuti: 'menunggu',
    });

    // ─── LOGIC MENCARI DATA PEGAWAI YANG SEDANG DIPILIH ───
    const selectedPegawai = pegawais.find(
        (p) => String(p.id) === String(data.pegawai_id),
    );

    useEffect(() => {
        if (isEditMode && editData) {
            setData({
                pegawai_id: String(editData.pegawai_id || ''),
                atasan_id: String(editData.atasan_id || ''),
                jenis_cuti: editData.jenis_cuti || '',
                tanggal_mulai: editData.tanggal_mulai
                    ? editData.tanggal_mulai.substring(0, 10)
                    : '',
                tanggal_akhir: editData.tanggal_akhir
                    ? editData.tanggal_akhir.substring(0, 10)
                    : '',
                lama_cuti: editData.lama_cuti || 0,
                alamat: editData.alamat || '',
                no_telp: editData.no_telp || '',
                alasan_cuti: editData.alasan_cuti || '',
                status_cuti: editData.status_cuti || 'menunggu',
            });
        } else {
            reset();
        }
    }, [editData, isEditMode, isOpen]);

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        if (isEditMode && editData) {
            put(`/admin/cuti/${editData.id}`, {
                onSuccess: () => {
                    reset();
                    onClose();
                },
            });
        } else {
            post('/admin/cuti', {
                onSuccess: () => {
                    reset();
                    onClose();
                },
            });
        }
    };

    return (
        <Sheet open={isOpen} onOpenChange={(open) => !open && onClose()}>
            <SheetContent className="w-full overflow-y-auto border-l border-slate-200/60 bg-white p-6 sm:max-w-md">
                <SheetHeader className="border-b border-slate-100 pb-4">
                    <SheetTitle className="text-base font-bold text-slate-900">
                        {isEditMode
                            ? 'Edit Berkas Pengajuan'
                            : 'Tambah Pengajuan Baru'}
                    </SheetTitle>
                    <SheetDescription className="text-xs font-medium text-slate-400">
                        Isi form dokumen berikut untuk meregistrasikan pengajuan
                        hak cuti pegawai.
                    </SheetDescription>
                </SheetHeader>

                <form onSubmit={handleSubmit} className="space-y-4 pt-4">
                    <div className="space-y-1.5">
                        <Label className="text-xs font-bold text-slate-700">
                            Pegawai Pemohon
                        </Label>
                        <Select
                            required
                            disabled={isEditMode}
                            value={data.pegawai_id}
                            onValueChange={(val) => setData('pegawai_id', val)}
                        >
                            <SelectTrigger className="h-10 rounded-xl border-slate-200 text-xs font-semibold text-slate-700 focus:ring-1 focus:ring-slate-400">
                                <SelectValue placeholder="Pilih nama pegawai aktif..." />
                            </SelectTrigger>
                            <SelectContent className="rounded-xl border-slate-200 bg-white">
                                {pegawais.map((p) => (
                                    <SelectItem
                                        key={p.id}
                                        value={String(p.id)}
                                        className="text-xs font-medium hover:bg-slate-100"
                                    >
                                        {p.nama} — NIP. {p.nip}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                        <InputError message={errors.pegawai_id} />
                    </div>

                    {/* ─── ELEMEN INDIKATOR JATAH CUTI YANG BARU DITAMBAHKAN ─── */}
                    {selectedPegawai && (
                        <div className="animate-in fade-in slide-in-from-top-2 space-y-2 rounded-xl border border-blue-100 bg-blue-50/50 p-3.5 duration-200">
                            <div className="flex items-center gap-1.5 text-xs font-bold text-blue-900">
                                <CalendarDays className="h-3.5 w-3.5 text-blue-600" />
                                <span>Informasi Sisa Jatah Cuti Pegawai:</span>
                            </div>

                            <div className="grid grid-cols-3 gap-2 text-center">
                                <div className="rounded-lg border border-slate-200/70 bg-white p-2 shadow-2xs">
                                    <p className="text-[10px] font-bold tracking-tight text-slate-400 uppercase">
                                        2 Tahun Lalu
                                    </p>
                                    <p className="mt-0.5 text-sm font-extrabold text-slate-700">
                                        {selectedPegawai.jatah_cuti_dua_tahun_lalu ??
                                            0}{' '}
                                        <span className="text-[10px] font-medium text-slate-400">
                                            Hari
                                        </span>
                                    </p>
                                </div>
                                <div className="rounded-lg border border-slate-200/70 bg-white p-2 shadow-2xs">
                                    <p className="text-[10px] font-bold tracking-tight text-slate-400 uppercase">
                                        1 Tahun Lalu
                                    </p>
                                    <p className="mt-0.5 text-sm font-extrabold text-slate-700">
                                        {selectedPegawai.jatah_cuti_satu_tahun_lalu ??
                                            0}{' '}
                                        <span className="text-[10px] font-medium text-slate-400">
                                            Hari
                                        </span>
                                    </p>
                                </div>
                                <div className="rounded-lg border border-blue-200 bg-blue-50 p-2 shadow-2xs">
                                    <p className="text-[10px] font-bold tracking-tight text-blue-500 uppercase">
                                        Tahun Ini
                                    </p>
                                    <p className="mt-0.5 text-sm font-extrabold text-blue-700">
                                        {selectedPegawai.jatah_cuti_tahun_ini ??
                                            0}{' '}
                                        <span className="text-[10px] font-medium text-blue-500">
                                            Hari
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    )}

                    <div className="space-y-1.5">
                        <Label className="text-xs font-bold text-slate-700">
                            Pejabat Atasan Sah / Penyetuju
                        </Label>
                        <Select
                            required
                            value={data.atasan_id}
                            onValueChange={(val) => setData('atasan_id', val)}
                        >
                            <SelectTrigger className="h-10 rounded-xl border-slate-200 text-xs font-semibold text-slate-700 focus:ring-1 focus:ring-slate-400">
                                <SelectValue placeholder="Pilih atasan penyetuju berkas..." />
                            </SelectTrigger>
                            <SelectContent className="rounded-xl border-slate-200 bg-white">
                                {pegawais
                                    .filter(
                                        (p) =>
                                            String(p.id) !==
                                            String(data.pegawai_id),
                                    )
                                    .map((p) => (
                                        <SelectItem
                                            key={p.id}
                                            value={String(p.id)}
                                            className="text-xs font-medium hover:bg-slate-100"
                                        >
                                            {p.nama} —{' '}
                                            {p.jabatan?.nama_jabatan ||
                                                'Pejabat Penanggungjawab'}
                                        </SelectItem>
                                    ))}
                            </SelectContent>
                        </Select>
                        <InputError message={errors.atasan_id} />
                    </div>

                    <div className="space-y-1.5">
                        <Label className="text-xs font-bold text-slate-700">
                            Jenis Cuti
                        </Label>
                        <Select
                            required
                            value={data.jenis_cuti}
                            onValueChange={(val) => setData('jenis_cuti', val)}
                        >
                            <SelectTrigger className="h-10 rounded-xl border-slate-200 text-xs font-semibold text-slate-700 focus:ring-1 focus:ring-slate-400">
                                <SelectValue placeholder="Pilih klasifikasi jenis cuti..." />
                            </SelectTrigger>
                            <SelectContent className="rounded-xl border-slate-200 bg-white">
                                <SelectItem
                                    value="tahunan"
                                    className="text-xs font-medium"
                                >
                                    Cuti Tahunan
                                </SelectItem>
                                <SelectItem
                                    value="besar"
                                    className="text-xs font-medium"
                                >
                                    Cuti Besar
                                </SelectItem>
                                <SelectItem
                                    value="sakit"
                                    className="text-xs font-medium"
                                >
                                    Cuti Sakit
                                </SelectItem>
                                <SelectItem
                                    value="melahirkan"
                                    className="text-xs font-medium"
                                >
                                    Cuti Melahirkan
                                </SelectItem>
                                <SelectItem
                                    value="alasan penting"
                                    className="text-xs font-medium"
                                >
                                    Cuti Alasan Penting
                                </SelectItem>
                                <SelectItem
                                    value="diluar tanggungan negara"
                                    className="text-xs font-medium"
                                >
                                    Cuti Di Luar Tanggungan Negara
                                </SelectItem>
                            </SelectContent>
                        </Select>
                        <InputError message={errors.jenis_cuti} />
                    </div>

                    <div className="grid grid-cols-2 gap-3">
                        <div className="space-y-1.5">
                            <Label className="text-xs font-bold text-slate-700">
                                Mulai Tanggal
                            </Label>
                            <Input
                                required
                                type="date"
                                value={data.tanggal_mulai}
                                onChange={(e) =>
                                    setData('tanggal_mulai', e.target.value)
                                }
                                className="h-10 rounded-xl border border-slate-200 px-3 text-xs font-semibold text-slate-700 shadow-2xs focus:ring-1 focus:ring-slate-400"
                            />
                            <InputError message={errors.tanggal_mulai} />
                        </div>
                        <div className="space-y-1.5">
                            <Label className="text-xs font-bold text-slate-700">
                                Akhir Tanggal
                            </Label>
                            <Input
                                type="date"
                                value={data.tanggal_akhir}
                                onChange={(e) =>
                                    setData('tanggal_akhir', e.target.value)
                                }
                                className="h-10 rounded-xl border border-slate-200 px-3 text-xs font-semibold text-slate-700 shadow-2xs focus:ring-1 focus:ring-slate-400"
                            />
                            <InputError message={errors.tanggal_akhir} />
                        </div>
                    </div>

                    <div className="space-y-1.5">
                        <Label className="text-xs font-bold text-slate-700">
                            Durasi Lamanya Cuti (Hari Kerja)
                        </Label>
                        <Input
                            required
                            type="number"
                            min="0"
                            placeholder="0"
                            value={data.lama_cuti || ''}
                            onChange={(e) =>
                                setData(
                                    'lama_cuti',
                                    parseInt(e.target.value) || 0,
                                )
                            }
                            className="h-10 rounded-xl border border-slate-200 px-3 text-xs font-semibold text-slate-700 shadow-2xs focus:ring-1 focus:ring-slate-400"
                        />
                        <InputError message={errors.lama_cuti} />
                    </div>

                    <div className="space-y-1.5">
                        <Label className="text-xs font-bold text-slate-700">
                            Nomor Telepon Aktif
                        </Label>
                        <Input
                            required
                            type="text"
                            placeholder="Contoh: 0822xxxxxxxx"
                            value={data.no_telp}
                            onChange={(e) => setData('no_telp', e.target.value)}
                            className="h-10 rounded-xl border border-slate-200 px-3 text-xs font-semibold text-slate-700 shadow-2xs focus:ring-1 focus:ring-slate-400"
                        />
                        <InputError message={errors.no_telp} />
                    </div>

                    <div className="space-y-1.5">
                        <Label className="text-xs font-bold text-slate-700">
                            Alamat Selama Cuti
                        </Label>
                        <textarea
                            rows={2}
                            placeholder="Tuliskan alamat lengkap domisili sementara selama masa cuti..."
                            value={data.alamat}
                            onChange={(e) => setData('alamat', e.target.value)}
                            className="w-full rounded-xl border border-slate-200 p-3 text-xs font-semibold text-slate-700 shadow-2xs placeholder:font-medium placeholder:text-slate-400 focus:ring-1 focus:ring-slate-400 focus:outline-hidden"
                        />
                        <InputError message={errors.alamat} />
                    </div>

                    <div className="space-y-1.5">
                        <Label className="text-xs font-bold text-slate-700">
                            Alasan Mengajukan Cuti
                        </Label>
                        <textarea
                            required
                            rows={2}
                            placeholder="Tuliskan alasan detail pemohon..."
                            value={data.alasan_cuti}
                            onChange={(e) =>
                                setData('alasan_cuti', e.target.value)
                            }
                            className="w-full rounded-xl border border-slate-200 p-3 text-xs font-semibold text-slate-700 shadow-2xs placeholder:font-medium placeholder:text-slate-400 focus:ring-1 focus:ring-slate-400 focus:outline-hidden"
                        />
                        <InputError message={errors.alasan_cuti} />
                    </div>

                    <div className="flex items-center justify-end gap-2 pt-2">
                        <Button
                            type="button"
                            variant="outline"
                            onClick={onClose}
                            className="h-10 rounded-xl border-slate-200 px-4 text-xs font-bold text-slate-600 hover:bg-slate-50"
                        >
                            Batal
                        </Button>
                        <Button
                            type="submit"
                            disabled={processing}
                            className="h-10 rounded-xl bg-slate-900 px-4 text-xs font-bold text-white shadow-sm hover:bg-slate-800"
                        >
                            {processing
                                ? 'Menyimpan...'
                                : isEditMode
                                  ? 'Simpan Perubahan'
                                  : 'Simpan Berkas'}
                        </Button>
                    </div>
                </form>
            </SheetContent>
        </Sheet>
    );
}
