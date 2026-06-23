import React from 'react';
import { useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Lock, Loader2, UserPlus } from 'lucide-react';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

interface PegawaiOption {
    id: number;
    nama: string;
    nip: string;
}

interface DelegasiFormProps {
    pegawaiList: PegawaiOption[];
}

export default function DelegasiForm({ pegawaiList }: DelegasiFormProps) {
    const { data, setData, post, processing, errors, reset } = useForm({
        pegawai_id: '',
        password_konfirmasi: '',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/admin/master/delegasi-akses', {
            onSuccess: () => {
                reset();
            },
        });
    };

    return (
        <form
            onSubmit={handleSubmit}
            className="space-y-5 rounded-2xl border border-slate-100 bg-white p-5"
        >
            <div className="flex items-center gap-2 border-b border-slate-50 pb-3">
                <div className="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                    <UserPlus className="h-4 w-4" />
                </div>
                <h2 className="text-sm font-bold text-slate-800">
                    Beri Akses Admin Baru
                </h2>
            </div>

            {/* Pilihan Pegawai Menggunakan Shadcn UI Select */}
            <div className="space-y-2">
                <Label
                    htmlFor="pegawai_id"
                    className="text-xs font-bold text-slate-700"
                >
                    Pilih Staf Pegawai
                </Label>
                <Select
                    value={data.pegawai_id}
                    onValueChange={(value) => setData('pegawai_id', value)}
                >
                    <SelectTrigger className="h-11 w-full rounded-xl border-slate-200 bg-white text-xs font-medium text-slate-800 shadow-2xs transition-all focus:ring-1 focus:ring-blue-500">
                        <SelectValue placeholder="-- Pilih Pegawai Ber-Role Standar --" />
                    </SelectTrigger>
                    <SelectContent className="rounded-xl border-slate-200 bg-white shadow-md">
                        {pegawaiList.length === 0 ? (
                            <div className="p-2 text-center text-xs text-slate-400">
                                Tidak ada pegawai ber-role standar yang tersedia
                            </div>
                        ) : (
                            pegawaiList.map((p) => (
                                <SelectItem
                                    key={p.id}
                                    value={p.id.toString()}
                                    className="cursor-pointer rounded-lg py-2.5 text-xs"
                                >
                                    {p.nama} — NIP. {p.nip}
                                </SelectItem>
                            ))
                        )}
                    </SelectContent>
                </Select>
                {errors.pegawai_id && (
                    <p className="text-[11px] font-semibold text-red-600">
                        {errors.pegawai_id}
                    </p>
                )}
            </div>

            {/* Verifikasi Sandi Master Superadmin */}
            <div className="space-y-2">
                <Label
                    htmlFor="password_konfirmasi"
                    className="text-xs font-bold text-slate-700"
                >
                    Kata Sandi Konfirmasi Superadmin{' '}
                    <span className="text-red-500">*</span>
                </Label>
                <div className="relative">
                    <Lock className="absolute top-3.5 left-3.5 h-4 w-4 text-slate-400" />
                    <Input
                        id="password_konfirmasi"
                        type="password"
                        placeholder="Masukkan sandi akun Superadmin Anda untuk memverifikasi tindakan"
                        value={data.password_konfirmasi}
                        onChange={(e) =>
                            setData('password_konfirmasi', e.target.value)
                        }
                        className="h-11 rounded-xl border-slate-200 pl-10 text-xs font-medium focus-visible:ring-blue-500"
                    />
                </div>
                {errors.password_konfirmasi && (
                    <p className="text-[11px] font-semibold text-red-600">
                        {errors.password_konfirmasi}
                    </p>
                )}
            </div>

            {/* Aksi Kirim */}
            <div className="flex justify-end pt-2">
                <Button
                    type="submit"
                    disabled={processing}
                    className="inline-flex h-11 w-full cursor-pointer items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 text-xs font-bold text-white shadow-xs transition-all duration-200 hover:bg-blue-700 active:scale-98 sm:w-auto"
                >
                    {processing ? (
                        <>
                            <Loader2 className="h-4 w-4 animate-spin" />
                            Mengubah Hak Akses...
                        </>
                    ) : (
                        'Simpan & Berikan Akses Admin'
                    )}
                </Button>
            </div>
        </form>
    );
}
