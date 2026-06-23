import React, { useState } from 'react';
import { useForm } from '@inertiajs/react';
import { Shield, User, Mail, UserMinus, Loader2, KeyRound } from 'lucide-react';
import { Button } from '@/components/ui/button';
import {
    AlertDialog,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';

interface AdminUser {
    id: number;
    nama: string;
    nip: string;
    user?: {
        id: number;
        email: string;
    };
}

interface AdminListProps {
    adminList: AdminUser[];
}

export default function AdminList({ adminList }: AdminListProps) {
    const [selectedPegawai, setSelectedPegawai] = useState<AdminUser | null>(
        null,
    );
    const [isModalOpen, setIsModalOpen] = useState(false);

    const { setData, post, processing, reset } = useForm({
        pegawai_id: '',
    });

    const handleOpenModal = (admin: AdminUser) => {
        setSelectedPegawai(admin);
        setData('pegawai_id', admin.id.toString());
        setIsModalOpen(true);
    };

    const handleCloseModal = () => {
        setIsModalOpen(false);
        setSelectedPegawai(null);
        reset();
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/admin/master/delegasi-akses/revoke', {
            onSuccess: () => handleCloseModal(),
        });
    };

    return (
        <>
            <div className="w-full space-y-6">
                {/* 1. SEKSI HEADER YANG LEBIH MINIMALIS & PREMIUM */}
                <div className="flex flex-col justify-between gap-3 border-b border-slate-100 pb-5 sm:flex-row sm:items-center">
                    <div className="flex items-center gap-3">
                        <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-md shadow-blue-500/10">
                            <Shield className="h-5 w-5" />
                        </div>
                        <div>
                            <h2 className="text-slate-850 text-sm font-black tracking-tight">
                                Administrator Terotorisasi
                            </h2>
                            <p className="text-[11px] font-medium text-slate-400">
                                Pegawai dengan hak akses penuh ke panel kontrol
                                sistem
                            </p>
                        </div>
                    </div>

                    {/* BADGE TOTAL DATA */}
                    <div className="inline-flex items-center gap-1.5 self-start rounded-full border border-blue-100/50 bg-blue-50/70 px-3 py-1 text-[11px] font-bold text-blue-700 sm:self-auto">
                        <span className="h-1.5 w-1.5 animate-pulse rounded-full bg-blue-500" />
                        {adminList.length} Akun Terdaftar
                    </div>
                </div>

                {/* 2. AREA LAYOUT UTAMA */}
                {adminList.length === 0 ? (
                    <div className="flex h-44 flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-white p-6 text-center transition-colors">
                        <div className="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-100 bg-slate-50 text-slate-400">
                            <KeyRound className="h-5 w-5 opacity-60" />
                        </div>
                        <p className="text-slate-750 text-xs font-bold">
                            Daftar Administrator Kosong
                        </p>
                        <p className="mt-1 max-w-[280px] text-[11px] leading-relaxed font-medium text-slate-400">
                            Tidak ditemukan akun pegawai yang memiliki hak
                            istimewa tingkat Admin saat ini.
                        </p>
                    </div>
                ) : (
                    /* GRID ULTRA RESPONSIVE MULTI-COLUMN */
                    // <div className="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                    <div className="flex flex-wrap gap-4">
                        {adminList.map((admin) => (
                            <div
                                key={admin.id}
                                className="group relative flex flex-col justify-between overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-5 shadow-2xs transition-all duration-300 hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-md"
                            >
                                <div className="space-y-4">
                                    {/* BARIS ATAS: KARTU IDENTITAS */}
                                    <div className="flex items-center justify-between gap-2">
                                        <div className="flex min-w-0 items-center gap-3">
                                            {/* AVATAR DENGAN GRADASI HALUS */}
                                            <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200/60 bg-gradient-to-tr from-slate-100 to-slate-50 text-[11px] font-extrabold tracking-wider text-slate-600 uppercase">
                                                {admin.nama
                                                    ? admin.nama.substring(0, 2)
                                                    : 'AD'}
                                            </div>
                                            <div className="min-w-0">
                                                <h3
                                                    className="truncate text-xs font-extrabold text-slate-900 transition-colors group-hover:text-blue-600"
                                                    title={admin.nama}
                                                >
                                                    {admin.nama}
                                                </h3>
                                                <p className="mt-0.5 text-[10px] font-bold tracking-wide text-slate-400">
                                                    NIP. {admin.nip || '-'}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    {/* BARIS TENGAH: INFORMASI KONTAK */}
                                    <div className="flex items-center gap-2 rounded-xl border border-slate-100 bg-slate-50/50 px-3 py-2 text-[11px] font-medium text-slate-600 transition-colors group-hover:border-slate-200/60 group-hover:bg-slate-50">
                                        <Mail className="h-3.5 w-3.5 shrink-0 text-slate-400 transition-colors group-hover:text-slate-500" />
                                        <span
                                            className="truncate select-all"
                                            title={admin.user?.email}
                                        >
                                            {admin.user?.email || '-'}
                                        </span>
                                    </div>
                                </div>

                                {/* BARIS BAWAH: ACTION BUTTON DENGAN HOVER EFFECT LUXURY */}
                                <div className="mt-4 flex items-center justify-end border-t border-slate-50 pt-3">
                                    <Button
                                        type="button"
                                        variant="ghost"
                                        size="sm"
                                        onClick={() => handleOpenModal(admin)}
                                        className="h-8.5 w-full cursor-pointer rounded-xl border border-transparent bg-red-50/10 text-[11px] font-bold text-red-600 transition-all duration-200 hover:border-red-100 hover:bg-red-50 hover:text-red-700 active:scale-97 md:w-auto md:translate-y-1 md:px-4 md:opacity-0 md:group-hover:translate-y-0 md:group-hover:opacity-100"
                                    >
                                        <UserMinus className="h-3.5 w-3.5" />
                                        Revoke Akses
                                    </Button>
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </div>

            {/* INTEGRASI DIALOG KONFIRMASI PEMBATALAN PERAN */}
            <AlertDialog open={isModalOpen} onOpenChange={setIsModalOpen}>
                <AlertDialogContent className="animate-in fade-in-50 zoom-in-95 max-w-[380px] rounded-2xl border border-slate-100 bg-white p-6 shadow-2xl duration-150">
                    <form onSubmit={handleSubmit}>
                        <AlertDialogHeader className="space-y-3 text-left">
                            <div className="flex h-11 w-11 items-center justify-center rounded-2xl border border-red-100 bg-red-50 text-red-600">
                                <UserMinus className="h-4 w-4" />
                            </div>
                            <div className="space-y-1">
                                <AlertDialogTitle className="text-sm font-black text-slate-900">
                                    Cabut Wewenang Admin
                                </AlertDialogTitle>
                                <AlertDialogDescription className="text-xs leading-relaxed font-medium text-slate-500">
                                    Apakah Anda yakin ingin menurunkan peran{' '}
                                    <span className="font-bold text-slate-800">
                                        {selectedPegawai?.nama}
                                    </span>{' '}
                                    menjadi pegawai biasa? Tindakan ini langsung
                                    mencabut hak kontrol panel secara
                                    *real-time*.
                                </AlertDialogDescription>
                            </div>
                        </AlertDialogHeader>

                        <AlertDialogFooter className="mt-6 flex flex-col gap-2 sm:flex-row">
                            <AlertDialogCancel
                                type="button"
                                onClick={handleCloseModal}
                                className="h-9.5 rounded-xl border-slate-200 text-[11px] font-bold text-slate-600 hover:bg-slate-50"
                            >
                                Batalkan
                            </AlertDialogCancel>
                            <Button
                                type="submit"
                                disabled={processing}
                                className="h-9.5 cursor-pointer rounded-xl bg-red-600 px-4 text-[11px] font-extrabold text-white shadow-sm hover:bg-red-700 active:scale-97"
                            >
                                {processing ? (
                                    <>
                                        <Loader2 className="mr-1.5 h-3.5 w-3.5 animate-spin" />
                                        Mencabut...
                                    </>
                                ) : (
                                    'Ya, Cabut Akses'
                                )}
                            </Button>
                        </AlertDialogFooter>
                    </form>
                </AlertDialogContent>
            </AlertDialog>
        </>
    );
}
