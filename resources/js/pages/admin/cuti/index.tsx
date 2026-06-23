import React, { useState, useEffect, useCallback } from 'react';
import { Head, router, Link, usePage } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardHeader,
    CardTitle,
    CardDescription,
    CardContent,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Tabs, TabsList, TabsTrigger } from '@/components/ui/tabs';
import {
    Plus,
    Search,
    X,
    CalendarDays,
    ChevronLeft,
    ChevronRight,
    CheckCircle2,
    AlertCircle,
    SlidersHorizontal,
    BarChart3, // <-- Ditambahkan untuk ikon tab rekapitulasi
} from 'lucide-react';
import AppLayout from '@/layouts/app-layout';

import CutiTable from './components/cuti-table';
import CutiFormSheet from './components/cuti-form-sheet';
import DetailCutiSheet from './components/detail-cuti-sheet';
import DeleteCutiDialog from './components/delete-cuti-dialog';
import CutiRekapTab from './components/cuti-rekap-tab'; // <-- Import komponen sub-tab rekapitulasi
import { generateSuratCutiFromTemplate } from '@/utils/print-cuti';
import Pagination from '@/components/ui/pagination-shared';

interface PaginationLinks {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedData<T> {
    data: T[];
    links: PaginationLinks[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number;
    to: number;
}

interface CutiIndexProps {
    cutis: PaginatedData<any>;
    pegawaiList: any[];
    allApprovedCutis?: any[]; // <-- Tambahkan prop optional untuk menampung data rekap global dari backend
    filters: { search?: string; status_cuti?: string; jenis_cuti?: string };
}

const MAPPING_JENIS_CUTI: Record<string, string> = {
    tahunan: 'Cuti Tahunan',
    besar: 'Cuti Besar',
    sakit: 'Cuti Sakit',
    melahirkan: 'Cuti Melahirkan',
    'alasan penting': 'Cuti Alasan Penting',
    'diluar tanggungan negara': 'Cuti Di Luar Tanggungan Negara',
};

export default function CutiIndex({
    cutis,
    pegawaiList,
    allApprovedCutis = [], // <-- Default nilai array kosong jika data rekap belum dikirim oleh backend
    filters,
}: CutiIndexProps) {
    const { flash, errors } = usePage().props as any;

    const [activeTab, setActiveTab] = useState(
        filters.status_cuti || 'disetujui',
    );
    const [search, setSearch] = useState(filters.search || '');

    const [isFormOpen, setIsFormOpen] = useState(false);
    const [selectedEditCuti, setSelectedEditCuti] = useState<any | null>(null);

    const [isDetailOpen, setIsDetailOpen] = useState(false);
    const [isDeleteOpen, setIsDeleteOpen] = useState(false);
    const [selectedCuti, setSelectedCuti] = useState<any>(null);

    const handleSearch = useCallback(
        (value: string, tab: string) => {
            // Abaikan pencarian server-side jika pengguna sedang berada di tab rekapitulasi
            if (tab === 'rekapitulasi') return;

            router.get(
                '/admin/cuti',
                {
                    search: value,
                    status_cuti: tab,
                    jenis_cuti: filters.jenis_cuti,
                },
                { preserveState: true, replace: true },
            );
        },
        [filters.jenis_cuti],
    );

    useEffect(() => {
        if (flash?.success?.id || errors?.error?.id) {
            const timer = setTimeout(() => setAlert(null), 5000);
            return () => clearTimeout(timer);
        }

        const delayDebounce = setTimeout(() => {
            if (search !== (filters.search || '')) {
                handleSearch(search, activeTab);
            }
        }, 400);
        return () => clearTimeout(delayDebounce);
    }, [search, handleSearch, filters.search, activeTab]);

    const handleTabChange = (status: string) => {
        setActiveTab(status);

        if (status !== 'rekapitulasi') {
            router.get(
                '/admin/cuti',
                {
                    search: search,
                    status_cuti: status,
                    jenis_cuti: filters.jenis_cuti,
                },
                { preserveState: true },
            );
        }
    };

    const handleToggleStatus = (id: number, currentStatus: string) => {
        const targetStatus =
            currentStatus === 'disetujui' ? 'ditangguhkan' : 'disetujui';
        router.patch(
            `/admin/cuti/${id}`,
            { status_cuti: targetStatus },
            {
                preserveScroll: true,
            },
        );
    };

    const executeDelete = () => {
        if (!selectedCuti) return;
        router.delete(`/admin/cuti/${selectedCuti.id}`, {
            onSuccess: () => {
                setIsDeleteOpen(false);
                setSelectedCuti(null);
            },
        });
    };

    const handlePrintAction = (cuti: any) => {
        generateSuratCutiFromTemplate(cuti);
    };

    const openCreateMode = () => {
        setSelectedEditCuti(null);
        setIsFormOpen(true);
    };

    const openEditMode = (cuti: any) => {
        setSelectedEditCuti(cuti);
        setIsFormOpen(true);
    };

    return (
        <>
            <Head title="Manajemen Cuti Pegawai" />

            <div className="flex w-full flex-col gap-6 p-6">
                {/* ─── HEADER BARU ─── */}
                <div className="flex flex-col gap-4 px-1 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                            Daftar Pengajuan Cuti
                        </h1>
                        <p className="text-sm text-slate-500">
                            Kelola data permohonan, cetak lembar izin, serta
                            ubah status klasifikasi cuti pegawai secara
                            tersentralisasi.
                        </p>
                    </div>
                    {/* Tombol sembunyi secara aman jika pengguna di tab rekapitulasi */}
                    {activeTab !== 'rekapitulasi' && (
                        <Button
                            onClick={openCreateMode}
                            className="h-11 bg-blue-600 font-semibold text-white hover:bg-blue-700"
                        >
                            <Plus className="mr-2 size-4" />
                            Tambah Pengajuan Baru
                        </Button>
                    )}
                </div>

                {/* ─── PENCARIAN GLOBAL BARU ─── */}
                {activeTab !== 'rekapitulasi' && (
                    <div className="shadow-3xs flex w-full items-center gap-3 rounded-2xl border border-slate-200/80 bg-white p-4">
                        <div className="relative flex-1">
                            <Search className="absolute top-1/2 left-4 h-4 w-4 -translate-y-1/2 stroke-[2.5] text-slate-400" />
                            <Input
                                placeholder="Cari berdasarkan nama lengkap atau NIP..."
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                className="h-11 w-full rounded-xl border-slate-200/60 bg-transparent pr-10 pl-11 text-xs font-medium text-slate-700 shadow-none placeholder:text-slate-400 focus-visible:border-slate-300 focus-visible:ring-0"
                            />
                            {search && (
                                <button
                                    onClick={() => setSearch('')}
                                    className="absolute top-1/2 right-3.5 -translate-y-1/2 rounded-md p-0.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600"
                                >
                                    <X className="h-3.5 w-3.5 stroke-[2.5]" />
                                </button>
                            )}
                        </div>
                    </div>
                )}

                {/* Tabs Kategori Dokumen */}
                <Tabs
                    value={activeTab}
                    onValueChange={handleTabChange}
                    className="w-full"
                >
                    <TabsList className="h-11 rounded-xl border border-slate-200/40 bg-slate-100/80 p-1">
                        <TabsTrigger
                            value="disetujui"
                            className="flex items-center gap-2 rounded-lg px-4 py-2 text-xs font-bold text-slate-500 data-[state=active]:bg-white data-[state=active]:text-slate-900 data-[state=active]:shadow-2xs"
                        >
                            <CheckCircle2 className="h-3.5 w-3.5 text-emerald-500" />
                            Cuti Disetujui
                        </TabsTrigger>
                        <TabsTrigger
                            value="ditangguhkan"
                            className="flex items-center gap-2 rounded-lg px-4 py-2 text-xs font-bold text-slate-500 data-[state=active]:bg-white data-[state=active]:text-slate-900 data-[state=active]:shadow-2xs"
                        >
                            <AlertCircle className="h-3.5 w-3.5 text-amber-500" />
                            Cuti Ditangguhkan
                        </TabsTrigger>
                        {/* TAB TRIGGER BARU UNTUK REKAPITULASI */}
                        <TabsTrigger
                            value="rekapitulasi"
                            className="flex items-center gap-2 rounded-lg px-4 py-2 text-xs font-bold text-slate-500 data-[state=active]:bg-white data-[state=active]:text-slate-900 data-[state=active]:shadow-2xs"
                        >
                            <BarChart3 className="h-3.5 w-3.5 text-indigo-500" />
                            Rekapitulasi & Tren
                        </TabsTrigger>
                    </TabsList>
                </Tabs>

                {/* Table Card - Ditampilkan hanya jika bukan tab rekapitulasi */}
                {activeTab !== 'rekapitulasi' ? (
                    <Card className="shadow-3xs overflow-hidden rounded-xl border border-slate-200/70 bg-white">
                        <CardHeader className="flex flex-col gap-4 space-y-0 border-b border-slate-100 bg-slate-50/20 px-5 pb-5 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <CardTitle className="text-sm font-bold text-slate-700">
                                    Berkas Permohonan (
                                    {activeTab === 'disetujui'
                                        ? 'Disetujui'
                                        : 'Ditangguhkan'}
                                    )
                                </CardTitle>
                                <CardDescription className="mt-0.5 text-xs font-medium text-slate-400">
                                    Menampilkan {cutis.from || 0} -{' '}
                                    {cutis.to || 0} dari {cutis.total} total
                                    riwayat izin.
                                </CardDescription>
                            </div>
                        </CardHeader>

                        <CardContent className="p-0">
                            <div className="p-5">
                                <CutiTable
                                    data={cutis.data}
                                    currentPage={cutis.current_page}
                                    perPage={cutis.per_page}
                                    mappingJenisCuti={MAPPING_JENIS_CUTI}
                                    onDetail={(item) => {
                                        setSelectedCuti(item);
                                        setIsDetailOpen(true);
                                    }}
                                    onEdit={openEditMode}
                                    onToggleStatus={handleToggleStatus}
                                    onDelete={(item) => {
                                        setSelectedCuti(item);
                                        setIsDeleteOpen(true);
                                    }}
                                    onPrint={handlePrintAction}
                                />
                            </div>

                            {/* Pagination Terjaga Sesuai Struktur Asli */}
                            <Pagination
                                links={cutis.links}
                                from={cutis.from}
                                to={cutis.to}
                                total={cutis.total}
                            />
                        </CardContent>
                    </Card>
                ) : (
                    /* KONTEN TAB REKAPITULASI AKTIF */
                    <div className="animate-in fade-in slide-in-from-bottom-2 w-full duration-300">
                        <CutiRekapTab
                            allApprovedCutis={allApprovedCutis}
                            pegawaiList={pegawaiList}
                        />
                    </div>
                )}
            </div>

            {/* INTEGRATED SHEETS & DIALOGS */}
            <CutiFormSheet
                isOpen={isFormOpen}
                onClose={() => {
                    setIsFormOpen(false);
                    setSelectedEditCuti(null);
                }}
                pegawais={pegawaiList}
                editData={selectedEditCuti}
            />

            <DetailCutiSheet
                isOpen={isDetailOpen}
                onClose={() => {
                    setIsDetailOpen(false);
                    setSelectedCuti(null);
                }}
                cuti={selectedCuti}
                mappingJenisCuti={MAPPING_JENIS_CUTI}
            />

            <DeleteCutiDialog
                isOpen={isDeleteOpen}
                onClose={() => {
                    setIsDeleteOpen(false);
                    setSelectedCuti(null);
                }}
                onConfirm={executeDelete}
                cuti={selectedCuti}
                mappingJenisCuti={MAPPING_JENIS_CUTI}
            />
        </>
    );
}

CutiIndex.layout = (page: React.ReactNode) => (
    <AppLayout breadcrumbs={[{ title: 'Manajemen Cuti', href: '/admin/cuti' }]}>
        {page}
    </AppLayout>
);
