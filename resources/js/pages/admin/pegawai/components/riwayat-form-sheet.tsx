import React from 'react';
import { GraduationCap, Award, Briefcase } from 'lucide-react';
import {
    Sheet,
    SheetContent,
    SheetHeader,
    SheetTitle,
    SheetDescription,
} from '@/components/ui/sheet';

// Import sub-komponen form yang sudah di-refactor
import FormPendidikan from './form-pendidikan';
import FormPangkat from './form-pangkat';
import FormJabatan from './form-jabatan';

interface RiwayatFormSheetProps {
    isOpen: boolean;
    onClose: () => void;
    type: 'pendidikan' | 'pangkat' | 'jabatan' | null;
    editId: number | null;
    pegawaiId: number; // Dipastikan prop ini dikirim dari parent file riwayat.tsx
    initialData: any; // Menggantikan passing bundle forms lama ke single context data
    masterPangkat: Array<{ id: number; nama: string; golongan: string }>;
}

export default function RiwayatFormSheet({
    isOpen,
    onClose,
    type,
    editId,
    pegawaiId,
    initialData,
    masterPangkat,
}: RiwayatFormSheetProps) {
    if (!type) return null;

    // Mapping header metadata berdasarkan tipe
    const metaConfig = {
        pendidikan: {
            title: editId
                ? 'Perbarui Dokumen Pendidikan'
                : 'Tambah Arsip Pendidikan Formal',
            description:
                'Ubah atau tambahkan rekam jejak sertifikasi pendidikan kelulusan akademik pegawai.',
            icon: <GraduationCap className="h-5 w-5 text-blue-600" />,
        },
        pangkat: {
            title: editId
                ? 'Perbarui Golongan Pangkat'
                : 'Tambah Riwayat Golongan SK',
            description:
                'Input atau sesuaikan jenjang kepangkatan penyesuaian berkas masa kerja berkala.',
            icon: <Award className="h-5 w-5 text-amber-600" />,
        },
        jabatan: {
            title: editId
                ? 'Perbarui Kedudukan Jabatan'
                : 'Tambah Riwayat Jabatan Baru',
            description:
                'Manajemen berkas mutasi kedudukan penugasan struktural ataupun fungsional.',
            icon: <Briefcase className="h-5 w-5 text-purple-600" />,
        },
    }[type];

    return (
        <Sheet open={isOpen} onOpenChange={(open) => !open && onClose()}>
            <SheetContent className="w-full overflow-y-auto border-l border-slate-100 p-6 sm:max-w-md">
                <SheetHeader className="space-y-1 border-b border-slate-100 pb-4">
                    <div className="flex items-center gap-2.5">
                        <div className="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200/40 bg-slate-50">
                            {metaConfig.icon}
                        </div>
                        <SheetTitle className="text-base font-bold text-slate-900">
                            {metaConfig.title}
                        </SheetTitle>
                    </div>
                    <SheetDescription className="pt-1 text-xs leading-relaxed text-slate-500">
                        {metaConfig.description}
                    </SheetDescription>
                </SheetHeader>

                {/* Render Form secara kondisional berdasarkan tipe riwayat aktif */}
                {type === 'pendidikan' && (
                    <FormPendidikan
                        pegawaiId={pegawaiId}
                        editId={editId}
                        initialData={initialData}
                        onClose={onClose}
                    />
                )}

                {type === 'pangkat' && (
                    <FormPangkat
                        pegawaiId={pegawaiId}
                        editId={editId}
                        initialData={initialData}
                        masterPangkat={masterPangkat}
                        onClose={onClose}
                    />
                )}

                {type === 'jabatan' && (
                    <FormJabatan
                        pegawaiId={pegawaiId}
                        editId={editId}
                        initialData={initialData}
                        onClose={onClose}
                    />
                )}
            </SheetContent>
        </Sheet>
    );
}
