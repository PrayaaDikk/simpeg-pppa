import React, { useState } from 'react';
import { Head, useForm, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Tabs, TabsList, TabsTrigger, TabsContent } from '@/components/ui/tabs';
import { GraduationCap, Award, Briefcase } from 'lucide-react';

// Import sub-komponen
import RiwayatHeader from './components/riwayat-header';
import PendidikanTable from './components/pendidikan-table';
import PangkatTable from './components/pangkat-table';
import JabatanTable from './components/jabatan-table';
import RiwayatFormSheet from './components/riwayat-form-sheet';

interface Pangkat {
    id: number;
    nama: string;
    golongan: string;
}
interface Pegawai {
    id: number;
    nama: string;
    nip: string;
    jenis_kelamin: 'l' | 'p';
    user?: { email: string };
    bidang?: { nama: string };
    jabatan?: { nama: string };
}

interface RiwayatPegawaiIndexProps {
    pegawai: Pegawai;
    riwayatPendidikan: any[];
    riwayatPangkat: any[];
    riwayatJabatan: any[];
    masterPangkat: Pangkat[];
}

type ActiveFormType = 'pendidikan' | 'pangkat' | 'jabatan' | null;

export default function RiwayatPegawaiIndex({
    pegawai,
    riwayatPendidikan,
    riwayatPangkat,
    riwayatJabatan,
    masterPangkat,
}: RiwayatPegawaiIndexProps) {
    // States untuk kontrol modal form
    const [isOpenSheet, setIsOpenSheet] = useState(false);
    const [activeFormType, setActiveFormType] = useState<ActiveFormType>(null);
    const [editId, setEditId] = useState<number | null>(null);
    const [initialData, setInitialData] = useState<any>(null);

    // Inisialisasi Kumpulan Form State Hook bawaan Inertia
    const formPendidikan = useForm({
        tingkat: '',
        institusi: '',
        jurusan: '',
        tahun_lulus: '',
    });
    const formPangkat = useForm({
        pangkat_id: '',
        tmt_pangkat: '',
        no_sk: '',
        tanggal_sk: '',
    });
    const formJabatan = useForm({
        nama_jabatan: '',
        jenis_jabatan: '',
        tmt_jabatan: '',
        no_sk: '',
        tanggal_sk: '',
    });

    const activeForm = activeFormType
        ? {
              pendidikan: formPendidikan,
              pangkat: formPangkat,
              jabatan: formJabatan,
          }[activeFormType]
        : null;

    const handleOpenAdd = (type: 'pendidikan' | 'pangkat' | 'jabatan') => {
        setEditId(null);
        setInitialData(null); // Kosongkan data awal untuk mode tambah data baru
        setActiveFormType(type);

        const targetForm = {
            pendidikan: formPendidikan,
            pangkat: formPangkat,
            jabatan: formJabatan,
        }[type];
        targetForm.reset();
        targetForm.clearErrors();
        setIsOpenSheet(true);
    };

    const handleOpenEdit = (
        type: 'pendidikan' | 'pangkat' | 'jabatan',
        item: any,
    ) => {
        setEditId(item.id);
        setInitialData(item); // <-- SANGAT PENTING: Simpan object item utuh database ke state
        setActiveFormType(type);
        setIsOpenSheet(true);

        // Sinkronisasi awal payload form bawaan parent (opsional, sebagai backup)
        if (type === 'pendidikan') {
            formPendidikan.setData({
                tingkat: item.tingkat,
                institusi: item.institusi,
                jurusan: item.jurusan,
                tahun_lulus: item.tahun_lulus?.toString() || '',
                ijazah: null,
            });
        } else if (type === 'pangkat') {
            formPangkat.setData({
                pangkat_id: item.pangkat_id?.toString() || '',
                tmt_pangkat: item.tmt_pangkat,
                no_sk: item.no_sk,
                tanggal_sk: item.tanggal_sk,
            });
        } else if (type === 'jabatan') {
            formJabatan.setData({
                nama_jabatan: item.nama_jabatan,
                jenis_jabatan: item.jenis_jabatan,
                tmt_jabatan: item.tmt_jabatan,
                no_sk: item.no_sk,
                tanggal_sk: item.tanggal_sk,
            });
        }
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (!activeFormType || !activeForm) return;

        const baseUrl = `/admin/riwayat-${activeFormType}`;
        const targetUrl = editId
            ? `${baseUrl}/${editId}`
            : `${baseUrl}/${pegawai.id}`;

        const options = {
            onSuccess: () => {
                setIsOpenSheet(false);
                activeForm.reset();
            },
        };

        if (editId) {
            activeForm.put(targetUrl, options);
        } else {
            activeForm.post(targetUrl, options);
        }
    };

    const handleDelete = (
        type: 'pendidikan' | 'pangkat' | 'jabatan',
        id: number,
    ) => {
        if (
            confirm(
                'Apakah Anda yakin ingin menghapus arsip riwayat data ini secara permanen?',
            )
        ) {
            router.delete(
                `/admin/pegawai/${pegawai.id}/riwayat/riwayat-${type}/${id}`,
            );
        }
    };

    return (
        <>
            <Head title={`Arsip Riwayat - ${pegawai.nama}`} />
            <div className="animate-in fade-in slide-in-from-bottom-3 w-full space-y-7 p-6 duration-400">
                {/* 1. Header Profil Identitas Pegawai (UI Diperjelas) */}
                <RiwayatHeader pegawai={pegawai} />

                {/* 2. Navigasi Menggunakan Sistem Tabs Konten */}
                <Tabs defaultValue="pendidikan" className="w-full space-y-5">
                    <TabsList className="inline-flex h-11 items-center justify-start rounded-xl border border-slate-200/40 bg-slate-100 p-1 text-slate-500">
                        <TabsTrigger
                            value="pendidikan"
                            className="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-xs font-bold tracking-wide transition-all data-[state=active]:bg-white data-[state=active]:text-blue-600 data-[state=active]:shadow-xs"
                        >
                            <GraduationCap className="h-4 w-4" />
                            Pendidikan Formal
                        </TabsTrigger>
                        <TabsTrigger
                            value="pangkat"
                            className="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-xs font-bold tracking-wide transition-all data-[state=active]:bg-white data-[state=active]:text-amber-600 data-[state=active]:shadow-xs"
                        >
                            <Award className="h-4 w-4" />
                            Golongan / Pangkat
                        </TabsTrigger>
                        <TabsTrigger
                            value="jabatan"
                            className="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-xs font-bold tracking-wide transition-all data-[state=active]:bg-white data-[state=active]:text-purple-600 data-[state=active]:shadow-xs"
                        >
                            <Briefcase className="h-4 w-4" />
                            Kedudukan Jabatan
                        </TabsTrigger>
                    </TabsList>

                    {/* Isi Panel Berdasarkan Tab yang Aktif */}
                    <TabsContent
                        value="pendidikan"
                        className="animate-in fade-in duration-200 focus-visible:outline-hidden"
                    >
                        <PendidikanTable
                            data={riwayatPendidikan}
                            onAdd={() => handleOpenAdd('pendidikan')}
                            onEdit={(item) =>
                                handleOpenEdit('pendidikan', item)
                            }
                            onDelete={(id) => handleDelete('pendidikan', id)}
                        />
                    </TabsContent>

                    <TabsContent
                        value="pangkat"
                        className="animate-in fade-in duration-200 focus-visible:outline-hidden"
                    >
                        <PangkatTable
                            data={riwayatPangkat}
                            onAdd={() => handleOpenAdd('pangkat')}
                            onEdit={(item) => handleOpenEdit('pangkat', item)}
                            onDelete={(id) => handleDelete('pangkat', id)}
                        />
                    </TabsContent>

                    <TabsContent
                        value="jabatan"
                        className="animate-in fade-in duration-200 focus-visible:outline-hidden"
                    >
                        <JabatanTable
                            data={riwayatJabatan}
                            onAdd={() => handleOpenAdd('jabatan')}
                            onEdit={(item) => handleOpenEdit('jabatan', item)}
                            onDelete={(id) => handleDelete('jabatan', id)}
                        />
                    </TabsContent>
                </Tabs>
            </div>

            {/* 3. Dynamic Single Form Sheet */}
            <RiwayatFormSheet
                isOpen={isOpenSheet}
                onClose={() => {
                    setIsOpenSheet(false);
                    setEditId(null);
                    setInitialData(null);
                }}
                type={activeFormType}
                editId={editId}
                pegawaiId={pegawai.id} // <-- Kirim ID Pegawai untuk rujukan route request URL
                initialData={initialData} // <-- PROPS INI SEKARANG DIALIRKAN SECARA AMAN
                masterPangkat={masterPangkat}
                forms={{
                    pendidikan: formPendidikan,
                    pangkat: formPangkat,
                    jabatan: formJabatan,
                }}
                onSubmit={handleSubmit}
            />
        </>
    );
}

RiwayatPegawaiIndex.layout = (page: React.ReactNode) => (
    <AppLayout
        breadcrumbs={[
            { title: 'Manajemen Pegawai', href: '/admin/pegawai' },
            { title: 'Manajemen Multi-Riwayat', href: '#' },
        ]}
    >
        {page}
    </AppLayout>
);
