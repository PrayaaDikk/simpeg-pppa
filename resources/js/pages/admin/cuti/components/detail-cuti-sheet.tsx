import React from 'react';
import {
    Sheet,
    SheetContent,
    SheetHeader,
    SheetTitle,
    SheetDescription,
} from '@/components/ui/sheet';
import { Badge } from '@/components/ui/badge';
import {
    User,
    Calendar,
    Clock,
    Phone,
    MapPin,
    FileText,
    CheckCircle2,
    AlertCircle,
    Pencil,
} from 'lucide-react';

interface DetailCutiSheetProps {
    cuti: any;
    isOpen: boolean;
    onClose: () => void;
    mappingJenisCuti: Record<string, string>;
}

export default function DetailCutiSheet({
    cuti,
    isOpen,
    onClose,
    mappingJenisCuti,
}: DetailCutiSheetProps) {
    if (!cuti) return null;

    const formatTanggalFull = (dateString: string) => {
        if (!dateString) return '-';
        return new Date(dateString).toLocaleDateString('id-ID', {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric',
        });
    };

    const isApproved = cuti.status_cuti === 'disetujui';

    return (
        <Sheet open={isOpen} onOpenChange={onClose}>
            <SheetContent className="border-slate-150 w-full overflow-y-auto border-l bg-white p-6 shadow-2xl sm:max-w-md">
                {/* ─── SHEET HEADER (DIPERTAHANKAN SESUAI PERMINTAAN) ─── */}
                <SheetHeader className="mb-5 space-y-1">
                    <SheetTitle className="text-lg font-bold text-slate-800">
                        Detail Riwayat Cuti
                    </SheetTitle>
                    <SheetDescription className="text-xs font-medium text-slate-400">
                        Informasi lengkap mengenai berkas permohonan izin cuti
                        pegawai yang bersangkutan.
                    </SheetDescription>
                </SheetHeader>

                {/* ─── KONTEN UTAMA YANG DIPERBAIK/LEBIH MENARIK ─── */}
                <div className="mt-5 space-y-5">
                    {/* Status Badge Banner */}
                    <div
                        className={`shadow-3xs flex items-center justify-between rounded-2xl border p-4 ${
                            isApproved
                                ? 'border-emerald-100 bg-emerald-50/40 text-emerald-800'
                                : 'border-amber-100 bg-amber-50/40 text-amber-800'
                        }`}
                    >
                        <div className="flex items-center gap-2.5">
                            {isApproved ? (
                                <CheckCircle2 className="h-5 w-5 stroke-[2.5] text-emerald-500" />
                            ) : (
                                <AlertCircle className="h-5 w-5 stroke-[2.5] text-amber-500" />
                            )}
                            <div className="flex flex-col">
                                <span className="text-[10px] font-bold tracking-wider text-slate-400 uppercase">
                                    Status Kelayakan
                                </span>
                                <span className="text-xs font-bold capitalize">
                                    {cuti.status_cuti}
                                </span>
                            </div>
                        </div>
                        <Badge
                            className={`shadow-3xs rounded-xl border px-3 py-1 text-[10px] font-extrabold uppercase ${
                                isApproved
                                    ? 'border-emerald-600 bg-emerald-500 text-white hover:bg-emerald-600'
                                    : 'border-amber-600 bg-amber-500 text-white hover:bg-amber-600'
                            }`}
                        >
                            {cuti.status_cuti}
                        </Badge>
                    </div>

                    {/* Profil Pemohon Card */}
                    <div className="shadow-3xs space-y-3.5 rounded-2xl border border-slate-100 bg-slate-50/50 p-4">
                        <div className="flex items-center gap-2 border-b border-slate-100 pb-2">
                            <User className="h-4 w-4 text-slate-400" />
                            <span className="text-xs font-bold text-slate-700">
                                Pegawai Pemohon
                            </span>
                        </div>
                        <div className="grid grid-cols-1 gap-3">
                            <div>
                                <span className="block text-[10px] font-bold tracking-wider text-slate-400 uppercase">
                                    Nama Pegawai
                                </span>
                                <span className="text-xs font-semibold text-slate-800">
                                    {cuti.pegawai?.nama || '-'}
                                </span>
                            </div>
                            <div>
                                <span className="block text-[10px] font-bold tracking-wider text-slate-400 uppercase">
                                    NIP / Nomor Induk
                                </span>
                                <span className="font-mono text-xs font-medium text-slate-600">
                                    {cuti.pegawai?.nip || '-'}
                                </span>
                            </div>
                        </div>
                    </div>

                    {/* Detail Permohonan Cuti Card */}
                    <div className="space-y-4 rounded-2xl border border-t-2 border-slate-100 border-t-slate-800 bg-white p-4 shadow-sm">
                        <div className="flex items-center gap-2 border-b border-slate-50 pb-2">
                            <FileText className="h-4 w-4 text-slate-400" />
                            <span className="text-xs font-bold text-slate-700">
                                Rincian Pengajuan
                            </span>
                        </div>

                        <div className="grid grid-cols-2 gap-4">
                            <div className="col-span-2">
                                <span className="block text-[10px] font-bold tracking-wider text-slate-400 uppercase">
                                    Jenis Cuti
                                </span>
                                <span className="mt-0.5 inline-block rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-700">
                                    {mappingJenisCuti[cuti.jenis_cuti || ''] ||
                                        cuti.jenis_cuti}
                                </span>
                            </div>

                            <div className="col-span-2 space-y-1">
                                <span className="block text-[10px] font-bold tracking-wider text-slate-400 uppercase">
                                    Masa Pelaksanaan
                                </span>
                                <div className="flex items-start gap-2 rounded-xl border border-slate-100/50 bg-slate-50 p-2.5">
                                    <Calendar className="mt-0.5 h-3.5 w-3.5 shrink-0 text-slate-400" />
                                    <div className="flex flex-col gap-1 text-[11px] font-semibold text-slate-600">
                                        <div>
                                            <span className="text-[10px] text-slate-400">
                                                Mulai:
                                            </span>{' '}
                                            {formatTanggalFull(
                                                cuti.tanggal_mulai,
                                            )}
                                        </div>
                                        <div className="my-0.5 border-t border-slate-100" />
                                        <div>
                                            <span className="text-[10px] text-slate-400">
                                                Akhir:
                                            </span>{' '}
                                            {formatTanggalFull(
                                                cuti.tanggal_akhir,
                                            )}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <span className="block text-[10px] font-bold tracking-wider text-slate-400 uppercase">
                                    Durasi Cuti
                                </span>
                                <div className="mt-1 flex items-center gap-1.5 text-xs font-bold text-slate-800">
                                    <Clock className="h-3.5 w-3.5 text-slate-400" />
                                    <span>
                                        {cuti.lama_cuti || '0'} Hari Kerja
                                    </span>
                                </div>
                            </div>

                            <div>
                                <span className="block text-[10px] font-bold tracking-wider text-slate-400 uppercase">
                                    Kontak
                                </span>
                                <div className="mt-1 flex items-center gap-1.5 text-xs font-bold text-slate-800">
                                    <Phone className="h-3.5 w-3.5 text-slate-400" />
                                    <span className="font-mono">
                                        {cuti.no_telp || '-'}
                                    </span>
                                </div>
                            </div>

                            <div className="col-span-2">
                                <span className="block text-[10px] font-bold tracking-wider text-slate-400 uppercase">
                                    Alamat Selama Cuti
                                </span>
                                <div className="mt-1 flex items-start gap-1.5 rounded-xl border border-slate-100/50 bg-slate-50/50 p-2.5 text-xs leading-relaxed font-medium text-slate-600">
                                    <MapPin className="mt-0.5 h-3.5 w-3.5 shrink-0 text-slate-400" />
                                    <p>{cuti.alamat || '-'}</p>
                                </div>
                            </div>

                            <div className="col-span-2">
                                <span className="block text-[10px] font-bold tracking-wider text-slate-400 uppercase">
                                    Alasan Cuti
                                </span>
                                <div className="mt-1 flex items-start gap-1.5 rounded-xl border border-slate-100/50 bg-slate-50/50 p-2.5 text-xs leading-relaxed font-medium text-slate-600">
                                    <Pencil className="mt-0.5 h-3.5 w-3.5 shrink-0 text-slate-400" />
                                    <p>{cuti.alasan_cuti || '-'}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Catatan Verifikator Card */}
                    <div className="space-y-1.5">
                        <span className="pl-1 text-[10px] font-bold tracking-wider text-slate-400 uppercase">
                            Catatan Resmi Verifikator
                        </span>
                        <div className="border-slate-150 rounded-2xl border bg-slate-50/30 p-3.5 text-xs leading-relaxed font-medium text-slate-500 italic shadow-2xs">
                            {cuti.catatan_cuti ||
                                'Tidak ada catatan khusus dari pimpinan atau admin verifikator.'}
                        </div>
                    </div>
                </div>
            </SheetContent>
        </Sheet>
    );
}
