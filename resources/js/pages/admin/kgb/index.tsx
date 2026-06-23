import React, { useState } from 'react';
import { useForm, router, Head } from '@inertiajs/react';
import {
    Search,
    Plus,
    X,
    List,
    CalendarClock,
    FileSpreadsheet,
    CalendarDays,
} from 'lucide-react';

// IMPORT EXCELJS & HANDLE VITE SSR FOR FILE-SAVER
import ExcelJS from 'exceljs';
import fileSaverPkg from 'file-saver';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import AppLayout from '@/layouts/app-layout';

// Memasukkan sub-komponen
import KgbTable from './components/kgb-table';
import CurrentMonthKgbTable from './components/current-month-kgb-table';
import FilterPopover from './components/filter-popover';
import CreateKgbSheet from './components/create-kgb-sheet';
import EditKgbSheet from './components/edit-kgb-sheet';

// Interface untuk struktur data terpaginasi (LengthAwarePaginator Laravel)
interface PaginatedData {
    data: any[];
    links: any[];
    current_page: number;
    per_page: number;
    total: number;
}

interface Props {
    kgbList: PaginatedData;
    currentMonthKgbList: PaginatedData;
    monthlyRekapKgbList: PaginatedData;
    pegawaiList: any[];
    filters: {
        search?: string;
        status_kgb?: string;
        bulan_kgb?: string;
        active_tab?: string;
        rekap_bulan?: string;
    };
}

export default function KgbIndex({
    kgbList,
    currentMonthKgbList,
    monthlyRekapKgbList,
    pegawaiList,
    filters,
}: Props) {
    // State lokal untuk manajemen UI
    const [searchQuery, setSearchQuery] = useState(filters.search || '');
    const [activeTab, setActiveTab] = useState(
        filters.active_tab || 'semua-riwayat',
    );
    const [rekapBulan, setRekapBulan] = useState(
        filters.rekap_bulan || new Date().toISOString().slice(0, 7),
    );

    // State untuk kontrol modal pembukaan form (Sheet)
    const [isCreateOpen, setIsCreateOpen] = useState(false);
    const [isEditOpen, setIsEditOpen] = useState(false);
    const [editingKgb, setEditingKgb] = useState<any>(null);

    // State untuk konfirmasi penghapusan data
    const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState(false);
    const [deletingKgb, setDeletingKgb] = useState<any>(null);

    // Form helper untuk proses penghapusan via Inertia
    const { delete: destroyRecord } = useForm();

    // Mengambil fungsi saveAs secara aman untuk lingkungan SSR Node & Browser
    const saveAsFunc =
        typeof fileSaverPkg === 'function'
            ? fileSaverPkg
            : (fileSaverPkg as any).saveAs;

    // Aksi pencarian & filter data ke backend
    const handleSearchSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        router.get(
            '/admin/kgb',
            {
                search: searchQuery,
                status_kgb: filters.status,
                bulan_kgb: filters.bulan_kgb,
                rekap_bulan: rekapBulan,
                active_tab: activeTab,
            },
            { preserveState: true },
        );
    };

    const handleStatusFilterChange = (status: string) => {
        // Ambil parameter kueri yang ada saat ini agar tidak hilang saat filter diganti
        const params: Record<string, any> = {
            ...filters,
            // Jika memilih 'semua', hapus key status_kgb atau set null agar controller membersihkan kueri
            status_kgb: status === 'semua' ? undefined : status,
            // Tetap pertahankan tab aktif saat ini
            active_tab: filters.active_tab || 'semua-riwayat',
        };

        // Trigger reload data via Inertia tanpa memicu full page reload browser
        router.get('/admin/kgb', params, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        });
    };

    const handleClearSearch = () => {
        setSearchQuery('');
        router.get(
            '/admin/kgb',
            {
                status_kgb: filters.status,
                bulan_kgb: filters.bulan_kgb,
                rekap_bulan: rekapBulan,
                active_tab: activeTab,
            },
            { preserveState: true },
        );
    };

    const handleFilterChange = (newFilters: {
        status?: string;
        bulan_kgb?: string;
    }) => {
        router.get(
            '/admin/kgb',
            {
                search: searchQuery,
                status_kgb: newFilters.status,
                bulan_kgb: newFilters.bulan_kgb,
                rekap_bulan: rekapBulan,
                active_tab: activeTab,
            },
            { preserveState: true },
        );
    };

    const handleRekapBulanChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const targetBulan = e.target.value;
        setRekapBulan(targetBulan);
        router.get(
            '/admin/kgb',
            {
                search: searchQuery,
                status_kgb: filters.status,
                bulan_kgb: filters.bulan_kgb,
                rekap_bulan: targetBulan,
                active_tab: 'rekap-bulanan',
            },
            { preserveState: true },
        );
    };

    const handleTabChange = (val: string) => {
        setActiveTab(val);
        router.get(
            '/admin/kgb',
            {
                search: searchQuery,
                status_kgb: filters.status,
                bulan_kgb: filters.bulan_kgb,
                rekap_bulan: rekapBulan,
                active_tab: val,
            },
            { preserveState: true, preserveScroll: true },
        );
    };

    // Aksi operasional form (Create / Edit / Delete)
    const handleEditKgb = (kgb: any) => {
        setEditingKgb(kgb);
        setIsEditOpen(true);
    };

    const handleDeleteClick = (kgb: any) => {
        setDeletingKgb(kgb);
        setIsDeleteDialogOpen(true);
    };

    const executeDelete = () => {
        if (!deletingKgb) return;
        destroyRecord(`/admin/kgb/${deletingKgb.id}`, {
            onSuccess: () => {
                setIsDeleteDialogOpen(false);
                setDeletingKgb(null);
            },
        });
    };

    const handleProcessNextKgb = (kgb: any) => {
        setEditingKgb({
            id: kgb.id,
            pegawai_id: kgb.pegawai_id,
            gaji_lama: kgb.gaji_baru,
            gaji_baru: '',
            golongan_lama: kgb.golongan_baru,
            golongan_baru: '',
            masa_kerja_lama: kgb.masa_kerja_baru,
            masa_kerja_baru: '',
            tmt_gaji_lama: kgb.tmt_gaji_baru,
            tmt_gaji_baru: kgb.kgb_berikutnya,
            kgb_berikutnya: '',
        });
        setIsCreateOpen(true);
    };

    // Fungsi Ekspor Rekap KGB ke file Microsoft Excel via ExcelJS
    const handleExportExcel = async () => {
        const workbook = new ExcelJS.Workbook();
        const worksheet = workbook.addWorksheet('Rekap KGB');

        worksheet.columns = [
            { header: 'NO', key: 'no', width: 6 },
            { header: 'NAMA PEGAWAI', key: 'nama', width: 30 },
            { header: 'NIP', key: 'nip', width: 22 },
            { header: 'GOLONGAN LAMA', key: 'gol_lama', width: 16 },
            { header: 'GAJI POKOK LAMA', key: 'gaji_lama', width: 20 },
            { header: 'GOLONGAN BARU', key: 'gol_baru', width: 16 },
            { header: 'GAJI POKOK BARU', key: 'gaji_baru', width: 20 },
            { header: 'TMT BERLAKU', key: 'tmt', width: 16 },
            { header: 'TANGGAL KGB BERIKUTNYA', key: 'next_kgb', width: 24 },
            { header: 'STATUS', key: 'status', width: 16 },
        ];

        // Pilih sumber data berdasarkan tab yang aktif saat ini
        let targetData = kgbList.data;
        let judulFile = 'Semua_Riwayat_KGB';

        if (activeTab === 'bulan-ini') {
            targetData = currentMonthKgbList.data;
            judulFile = `Jadwal_KGB_Bulan_Ini_${new Date().toISOString().slice(0, 7)}`;
        } else if (activeTab === 'rekap-bulanan') {
            targetData = monthlyRekapKgbList.data;
            judulFile = `Rekap_KGB_Periode_${rekapBulan}`;
        }

        targetData.forEach((item, index) => {
            worksheet.addRow({
                no: index + 1,
                nama: item.pegawai?.nama || '-',
                nip: item.pegawai?.nip ? `'${item.pegawai.nip}` : '-',
                gol_lama: item.golongan_lama || '-',
                gaji_lama: Number(item.gaji_lama) || 0,
                gol_baru: item.golongan_baru || '-',
                gaji_baru: Number(item.gaji_baru) || 0,
                tmt: item.tmt_gaji_baru || '-',
                next_kgb: item.kgb_berikutnya || '-',
                status: (item.status_kgb || 'menunggu').toUpperCase(),
            });
        });

        // Styling Header Tabel Excel biar lebih rapi
        worksheet.getRow(1).font = { bold: true, color: { argb: 'FFFFFF' } };
        worksheet.getRow(1).eachCell((cell) => {
            cell.fill = {
                type: 'pattern',
                pattern: 'solid',
                fgColor: { argb: '1E3A8A' },
            };
            cell.alignment = { vertical: 'middle', horizontal: 'center' };
        });

        const buffer = await workbook.xlsx.writeBuffer();

        // Eksekusi pengunduhan file di browser (Aman dari crash SSR)
        if (typeof window !== 'undefined' && saveAsFunc) {
            const blob = new Blob([buffer], {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            });
            saveAsFunc(
                blob,
                `${judulFile}_${new Date().toISOString().slice(0, 10)}.xlsx`,
            );
        }
    };

    return (
        <>
            <Head title="Manajemen Kenaikan Gaji Berkala" />

            <div className="flex flex-col gap-6 p-6">
                {/* Header Section */}
                <div className="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                    <div>
                        <h1 className="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                            Kenaikan Gaji Berkala (KGB)
                        </h1>
                        <p className="text-sm text-slate-500">
                            Kelola jadwal berkas, cetak surat keputusan, dan
                            riwayat kenaikan gaji berkala pegawai.
                        </p>
                    </div>

                    <div className="flex items-center gap-2.5">
                        {(activeTab === 'rekap-bulanan' ||
                            activeTab === 'semua-riwayat') &&
                            kgbList.data.length > 0 && (
                                <Button
                                    variant="outline"
                                    onClick={handleExportExcel}
                                    className="inline-flex h-10 cursor-pointer items-center gap-2 rounded-xl border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-xs hover:bg-slate-50 hover:text-slate-900"
                                >
                                    <FileSpreadsheet className="h-4 w-4 text-emerald-600" />
                                    Ekspor Excel
                                </Button>
                            )}

                        <Button
                            onClick={() => {
                                setEditingKgb(null);
                                setIsCreateOpen(true);
                            }}
                            className="inline-flex h-10 cursor-pointer items-center gap-2 rounded-xl bg-blue-600 px-4 text-sm font-semibold text-white shadow-xs hover:bg-blue-700"
                        >
                            <Plus className="h-4 w-4" />
                            Tambah Berkas KGB
                        </Button>
                    </div>
                </div>

                {/* Main Container Tabs */}
                <Tabs
                    value={activeTab}
                    onValueChange={handleTabChange}
                    className="w-full"
                >
                    <Card className="rounded-2xl border-slate-100 shadow-xs">
                        <CardHeader className="border-b border-slate-100 bg-slate-50/40 p-4">
                            <div className="flex flex-col justify-between gap-4 md:flex-row md:items-center">
                                {/* Tab Selectors */}
                                <TabsList className="grid h-10 w-full max-w-md grid-cols-3 rounded-xl bg-slate-100 p-1">
                                    <TabsTrigger
                                        value="semua-riwayat"
                                        className="inline-flex items-center gap-1.5 rounded-lg text-xs font-bold tracking-wide transition-all data-[state=active]:bg-white data-[state=active]:text-blue-600 data-[state=active]:shadow-2xs"
                                    >
                                        <List className="h-3.5 w-3.5" />
                                        Semua Riwayat
                                    </TabsTrigger>
                                    <TabsTrigger
                                        value="bulan-ini"
                                        className="inline-flex items-center gap-1.5 rounded-lg text-xs font-bold tracking-wide transition-all data-[state=active]:bg-white data-[state=active]:text-blue-600 data-[state=active]:shadow-2xs"
                                    >
                                        <CalendarClock className="h-3.5 w-3.5" />
                                        Bulan Ini
                                    </TabsTrigger>
                                    <TabsTrigger
                                        value="rekap-bulanan"
                                        className="inline-flex items-center gap-1.5 rounded-lg text-xs font-bold tracking-wide transition-all data-[state=active]:bg-white data-[state=active]:text-blue-600 data-[state=active]:shadow-2xs"
                                    >
                                        <CalendarDays className="h-3.5 w-3.5" />
                                        Rekap Bulanan
                                    </TabsTrigger>
                                </TabsList>

                                {/* Filters Area */}
                                <div className="flex items-center gap-2">
                                    {activeTab === 'rekap-bulanan' && (
                                        <div className="flex items-center gap-2">
                                            <span className="text-xs font-semibold whitespace-nowrap text-slate-500">
                                                Periode Rekap:
                                            </span>
                                            <Input
                                                type="month"
                                                value={rekapBulan}
                                                onChange={
                                                    handleRekapBulanChange
                                                }
                                                className="block h-9 w-40 rounded-lg border-slate-200 text-xs font-medium"
                                            />
                                        </div>
                                    )}

                                    {activeTab === 'semua-riwayat' && (
                                        <>
                                            <form
                                                onInput={handleSearchSubmit}
                                                className="relative flex items-center"
                                            >
                                                <Input
                                                    type="text"
                                                    placeholder="Cari nama / NIP..."
                                                    value={searchQuery}
                                                    onChange={(e) =>
                                                        setSearchQuery(
                                                            e.target.value,
                                                        )
                                                    }
                                                    className="h-9 w-64 rounded-lg border-slate-200 pr-8 text-xs placeholder:text-slate-400 focus-visible:ring-blue-500"
                                                />
                                                {searchQuery ? (
                                                    <button
                                                        type="button"
                                                        onClick={
                                                            handleClearSearch
                                                        }
                                                        className="absolute right-2.5 text-slate-400 hover:text-slate-600"
                                                    >
                                                        <X className="h-3.5 w-3.5" />
                                                    </button>
                                                ) : (
                                                    <Search className="absolute right-2.5 h-3.5 w-3.5 text-slate-400" />
                                                )}
                                            </form>

                                            <FilterPopover
                                                currentFilter={
                                                    filters.status_kgb ||
                                                    'semua'
                                                }
                                                onFilterChange={
                                                    handleStatusFilterChange
                                                }
                                            />
                                        </>
                                    )}
                                </div>
                            </div>
                        </CardHeader>

                        <CardContent className="p-0">
                            {/* Tab Konten 1: Semua Riwayat Berkas */}
                            <TabsContent
                                value="semua-riwayat"
                                className="mt-0 focus-visible:outline-hidden"
                            >
                                <KgbTable
                                    kgbData={kgbList}
                                    onEditAction={handleEditKgb}
                                    onDeleteAction={handleDeleteClick}
                                />
                            </TabsContent>

                            {/* Tab Konten 2: Jadwal Jatuh Tempo Bulan Berjalan */}
                            <TabsContent
                                value="bulan-ini"
                                className="mt-0 focus-visible:outline-hidden"
                            >
                                <CurrentMonthKgbTable
                                    kgbData={currentMonthKgbList}
                                    onProcessKgb={handleProcessNextKgb}
                                />
                            </TabsContent>

                            {/* Tab Konten 3: Rekap Bulanan Fleksibel (Dinamis) */}
                            <TabsContent
                                value="rekap-bulanan"
                                className="mt-0 focus-visible:outline-hidden"
                            >
                                <CurrentMonthKgbTable
                                    kgbData={monthlyRekapKgbList}
                                    onProcessKgb={handleProcessNextKgb}
                                    activeTab={activeTab}
                                />
                            </TabsContent>
                        </CardContent>
                    </Card>
                </Tabs>
            </div>

            {/* Modal Drawer Formulir (Sheets) */}
            <CreateKgbSheet
                isOpen={isCreateOpen}
                onClose={() => setIsCreateOpen(false)}
                pegawaiList={pegawaiList}
                initialData={editingKgb}
            />

            <EditKgbSheet
                isOpen={isEditOpen}
                onClose={() => {
                    setIsEditOpen(false);
                    setEditingKgb(null);
                }}
                pegawaiList={pegawaiList}
                kgbData={editingKgb}
            />

            {/* Dialog Konfirmasi Hapus Record */}
            <Dialog
                open={isDeleteDialogOpen}
                onOpenChange={setIsDeleteDialogOpen}
            >
                <DialogContent className="max-w-md rounded-2xl border-slate-100 bg-white p-6 shadow-xl">
                    <DialogHeader>
                        <DialogTitle className="text-base font-bold text-slate-900">
                            Konfirmasi Hapus Riwayat KGB
                        </DialogTitle>
                        <DialogDescription className="mt-1.5 text-sm leading-relaxed text-slate-500">
                            Apakah Anda yakin ingin menghapus data rekap berkas
                            KGB milik{' '}
                            <strong className="font-semibold text-slate-800">
                                {deletingKgb?.pegawai?.nama}
                            </strong>{' '}
                            secara permanen? Tindakan ini bersifat irreversible
                            dan data tidak dapat dipulihkan.
                        </DialogDescription>
                    </DialogHeader>
                    <DialogFooter className="mt-4 flex items-center justify-end gap-2">
                        <Button
                            variant="outline"
                            onClick={() => setIsDeleteDialogOpen(false)}
                            className="rounded-xl border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50"
                        >
                            Batal
                        </Button>
                        <Button
                            variant="destructive"
                            onClick={executeDelete}
                            className="rounded-xl bg-red-600 text-xs font-semibold text-white shadow-xs hover:bg-red-700"
                        >
                            Hapus Permanen
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </>
    );
}

KgbIndex.layout = (page: React.ReactNode) => (
    <AppLayout breadcrumbs={[{ title: 'Manajemen KGB', href: '/admin/kgb' }]}>
        {page}
    </AppLayout>
);
