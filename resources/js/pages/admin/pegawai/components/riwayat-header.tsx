import React from 'react';
import { Link } from '@inertiajs/react';
import { ArrowLeft, FileDown, User, ShieldCheck } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';

interface RiwayatHeaderProps {
    pegawai: {
        id: number;
        nama: string;
        nip: string;
        jenis_kelamin: 'l' | 'p';
        user?: { email: string };
        bidang?: { nama_jabatan: string };
        jabatan?: { nama_bidang: string };
    };
}

export default function RiwayatHeader({ pegawai }: RiwayatHeaderProps) {
    // Singkatan nama untuk fallback avatar (maksimal 2 huruf)
    const avatarFallback = pegawai.nama
        ? pegawai.nama
              .split(' ')
              .map((n) => n[0])
              .slice(0, 2)
              .join('')
              .toUpperCase()
        : 'U';

    return (
        <div className="flex flex-col gap-5 border-b border-slate-100 pb-6">
            {/* Jalur Aksi Atas: Judul Halaman dan Tombol Kontrol */}
            <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 className="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                        Arsip Kedudukan & Riwayat SK
                    </h1>
                    <p className="mt-0.5 text-sm text-slate-500">
                        Kelola data mutasi instansi, pengangkatan berkas
                        golongan, dan riwayat ijazah akademik.
                    </p>
                </div>

                <div className="flex items-center gap-2.5">
                    <Link
                        href="/admin/pegawai"
                        className="group flex h-10 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-xs font-bold text-slate-700 shadow-xs transition-all hover:bg-slate-50"
                    >
                        <ArrowLeft className="h-4 w-4 text-slate-500 transition-transform group-hover:-translate-x-0.5" />
                        Kembali
                    </Link>
                </div>
            </div>

            {/* Banner Informasi Identitas Pegawai Aktif (UI Jelas & Informatif) */}
            <div className="flex flex-col gap-4 rounded-2xl border border-slate-200/70 bg-linear-to-r from-slate-50 via-white to-slate-50/40 p-5 shadow-xs sm:flex-row sm:items-center sm:gap-6">
                {/* Avatar Identitas */}
                <Avatar className="h-14 w-14 rounded-xl border border-slate-200/80 bg-slate-100 text-base font-bold text-slate-700 shadow-inner">
                    <AvatarFallback className="rounded-xl bg-slate-100">
                        {avatarFallback}
                    </AvatarFallback>
                </Avatar>

                {/* Ringkasan Profil */}
                <div className="flex-1 space-y-1">
                    <div className="flex flex-wrap items-center gap-2.5">
                        <h2 className="text-lg font-bold tracking-tight text-slate-800">
                            {pegawai.nama}
                        </h2>
                        <Badge
                            variant="outline"
                            className="h-5 rounded-md border-blue-100 bg-blue-50/50 px-2 text-[10px] font-bold text-blue-700 uppercase"
                        >
                            NIP: {pegawai.nip}
                        </Badge>
                        {pegawai.user?.email && (
                            <span className="hidden text-xs font-medium text-slate-400 md:inline">
                                • {pegawai.user.email}
                            </span>
                        )}
                    </div>

                    <div className="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs font-medium text-slate-500">
                        <div className="flex items-center gap-1.5">
                            <span className="text-slate-400">
                                Jabatan Sekarang:
                            </span>
                            <span className="font-semibold text-slate-700">
                                {pegawai.jabatan?.nama_jabatan ||
                                    'Belum diatur'}
                            </span>
                        </div>
                        <span className="hidden text-slate-300 sm:inline">
                            |
                        </span>
                        <div className="flex items-center gap-1.5">
                            <span className="text-slate-400">Bidang:</span>
                            <span className="font-semibold text-slate-600">
                                {pegawai.bidang?.nama_bidang || 'Belum diatur'}
                            </span>
                        </div>
                    </div>
                </div>

                {/* Indikator Status Aktif dari Admin */}
                <div className="hidden items-center gap-2 border-l border-slate-200 pl-6 text-xs text-slate-400 lg:flex">
                    <ShieldCheck className="h-5 w-5 text-emerald-500" />
                    <div>
                        <div className="font-bold text-slate-700">
                            Mode Peninjauan
                        </div>
                        <div className="text-[10px] font-medium text-slate-400">
                            Data Terverifikasi Sistem
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
