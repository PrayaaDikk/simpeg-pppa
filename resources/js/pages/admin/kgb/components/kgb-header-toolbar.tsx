import React from 'react';
import {
    Search,
    CalendarClock,
    List,
    FileSpreadsheet,
    CalendarDays,
} from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { TabsList, TabsTrigger } from '@/components/ui/tabs';
import FilterPopover from './filter-popover';

interface KgbHeaderToolbarProps {
    search: string;
    setSearch: (val: string) => void;
    statusKgb: string;
    setStatusKgb: (val: string) => void;
    bulanKgb: string;
    setBulanKgb: (val: string) => void;
    activeTab: string;
    handleTabChange: (value: string) => void;
    rekapBulan: string;
    setRekapBulan: (val: string) => void;
    onExportExcel: () => void;
    onOpenCreate: () => void;
}

export default function KgbHeaderToolbar({
    search,
    setSearch,
    statusKgb,
    setStatusKgb,
    bulanKgb,
    setBulanKgb,
    activeTab,
    handleTabChange,
    rekapBulan,
    setRekapBulan,
    onExportExcel,
    onOpenCreate,
}: KgbHeaderToolbarProps) {
    return (
        <div className="space-y-6">
            {/* Heading Utama */}
            <div className="flex flex-col gap-1 border-b border-slate-100 pb-5 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
                <div className="space-y-1">
                    <h1 className="text-xl font-bold tracking-tight text-slate-900">
                        Kenaikan Gaji Berkala (KGB)
                    </h1>
                    <p className="text-xs text-slate-500">
                        Pantau jadwal ambang batas, verifikasi berkas rujukan,
                        serta cetak laporan akumulasi data kenaikan gaji berkala
                        pegawai.
                    </p>
                </div>
                <Button
                    onClick={onOpenCreate}
                    className="h-10 rounded-xl bg-blue-600 px-4 text-xs font-semibold text-white shadow-xs transition-colors hover:bg-blue-700"
                >
                    Buat Pengajuan KGB
                </Button>
            </div>

            {/* Kontrol Baris Filter & Tab Switcher */}
            <div className="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                <TabsList className="grid h-11 w-full grid-cols-3 gap-1 rounded-xl bg-slate-100/80 p-1 sm:inline-flex sm:w-auto">
                    <TabsTrigger
                        value="semua-riwayat"
                        onClick={() => handleTabChange('semua-riwayat')}
                        className="rounded-lg px-4 py-2 text-xs font-medium tracking-tight data-[state=active]:bg-white data-[state=active]:text-slate-900 data-[state=active]:shadow-xs"
                    >
                        <List className="mr-2 h-3.5 w-3.5" /> Semua Arsip KGB
                    </TabsTrigger>
                    <TabsTrigger
                        value="bulan_ini"
                        onClick={() => handleTabChange('bulan_ini')}
                        className="rounded-lg px-4 py-2 text-xs font-medium tracking-tight data-[state=active]:bg-white data-[state=active]:text-slate-900 data-[state=active]:shadow-xs"
                    >
                        <CalendarClock className="mr-2 h-3.5 w-3.5" /> Antrean
                        Bulan Ini
                    </TabsTrigger>
                    <TabsTrigger
                        value="rekap-bulanan"
                        onClick={() => handleTabChange('rekap-bulanan')}
                        className="rounded-lg px-4 py-2 text-xs font-medium tracking-tight data-[state=active]:bg-white data-[state=active]:text-slate-900 data-[state=active]:shadow-xs"
                    >
                        <FileSpreadsheet className="mr-2 h-3.5 w-3.5" /> Rekap
                        Bulanan
                    </TabsTrigger>
                </TabsList>

                {/* Toolbar Sisi Kanan Dinamis Berdasarkan Tab */}
                <div className="flex flex-wrap items-center gap-2.5 sm:flex-nowrap">
                    {activeTab !== 'rekap-bulanan' ? (
                        <>
                            <div className="relative w-full sm:w-64">
                                <Search className="absolute top-1/2 left-3.5 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" />
                                <Input
                                    type="text"
                                    placeholder="Cari nama atau NIP pegawai..."
                                    value={search}
                                    onChange={(e) => setSearch(e.target.value)}
                                    className="h-10 rounded-xl border-slate-200 pr-4 pl-9 text-xs placeholder:text-slate-400 focus-visible:ring-blue-500"
                                />
                            </div>
                            <FilterPopover
                                statusKgb={statusKgb}
                                setStatusKgb={setStatusKgb}
                                bulanKgb={bulanKgb}
                                setBulanKgb={setBulanKgb}
                            />
                        </>
                    ) : (
                        <div className="flex w-full items-center gap-2 sm:w-auto">
                            <div className="relative w-full sm:w-48">
                                <CalendarDays className="absolute top-1/2 left-3.5 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" />
                                <Input
                                    type="month"
                                    value={rekapBulan}
                                    onChange={(e) =>
                                        setRekapBulan(e.target.value)
                                    }
                                    className="h-10 rounded-xl border-slate-200 pr-4 pl-9 text-xs text-slate-700"
                                />
                            </div>
                            <Button
                                onClick={onExportExcel}
                                variant="outline"
                                className="h-10 rounded-xl border-slate-200 bg-white px-4 text-xs font-medium text-slate-600 shadow-2xs hover:bg-slate-50 hover:text-slate-800"
                            >
                                <FileSpreadsheet className="mr-2 h-3.5 w-3.5 text-emerald-600" />
                                Cetak Laporan
                            </Button>
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}
