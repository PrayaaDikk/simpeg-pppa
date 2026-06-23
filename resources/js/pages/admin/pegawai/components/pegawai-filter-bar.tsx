import React from 'react';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import { Checkbox } from '@/components/ui/checkbox';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { Search, SlidersHorizontal } from 'lucide-react';

interface FilterCheckboxOption {
    value: string;
    label: React.ReactNode;
}

interface FilterCheckboxGroupProps {
    title: string;
    filterKey: string;
    options: FilterCheckboxOption[];
    activeValues: string[];
    gridCols?: 2 | 3;
    truncateLabel?: boolean;
    explicitButtonType?: boolean;
    onToggle: (key: string, currentArray: string[], value: string) => void;
    onSelectAll: (
        key: string,
        currentArray: string[],
        allValues: string[],
    ) => void;
}

// Sub-komponen generik untuk grup filter checkbox bertipe grid (Bidang, Jabatan, Golongan, Pendidikan)
function FilterCheckboxGroup({
    title,
    filterKey,
    options,
    activeValues,
    gridCols = 2,
    truncateLabel = true,
    explicitButtonType = false,
    onToggle,
    onSelectAll,
}: FilterCheckboxGroupProps) {
    return (
        <div className="space-y-2">
            <div className="flex items-center justify-between">
                <span className="text-xs font-bold tracking-wider text-slate-400 uppercase">
                    {title}
                </span>
                <Button
                    {...(explicitButtonType
                        ? { type: 'button' as const }
                        : {})}
                    variant="link"
                    size="sm"
                    className="h-auto p-0 text-xs font-semibold text-blue-600"
                    onClick={() =>
                        onSelectAll(
                            filterKey,
                            activeValues,
                            options.map((opt) => opt.value),
                        )
                    }
                >
                    {activeValues.length === options.length
                        ? 'Kosongkan'
                        : 'Pilih Semua'}
                </Button>
            </div>
            <div
                className={`grid gap-2 pt-1 ${
                    gridCols === 3 ? 'grid-cols-3' : 'grid-cols-2'
                }`}
            >
                {options.map((opt) => (
                    <label
                        key={opt.value}
                        className="flex cursor-pointer items-center gap-2 rounded-md border border-slate-50 p-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50/80"
                    >
                        <Checkbox
                            checked={activeValues.includes(opt.value)}
                            onCheckedChange={() =>
                                onToggle(filterKey, activeValues, opt.value)
                            }
                        />
                        <span
                            className={truncateLabel ? 'truncate' : undefined}
                        >
                            {opt.label}
                        </span>
                    </label>
                ))}
            </div>
        </div>
    );
}

interface StatusFilterGroupProps {
    activeStatuses: string[];
    onToggle: (key: string, currentArray: string[], value: string) => void;
    onSelectAll: (
        key: string,
        currentArray: string[],
        allValues: string[],
    ) => void;
}

// Sub-komponen khusus untuk filter Status Keaktifan (layout 2 kolom flex, bukan grid)
function StatusFilterGroup({
    activeStatuses,
    onToggle,
    onSelectAll,
}: StatusFilterGroupProps) {
    return (
        <div className="space-y-2">
            <div className="flex items-center justify-between">
                <span className="text-xs font-bold tracking-wider text-slate-400 uppercase">
                    Status Keaktifan
                </span>
                <Button
                    variant="link"
                    size="sm"
                    className="h-auto p-0 text-xs font-semibold text-blue-600"
                    onClick={() =>
                        onSelectAll('statuses', activeStatuses, [
                            'true',
                            'false',
                        ])
                    }
                >
                    {activeStatuses.length === 2 ? 'Kosongkan' : 'Pilih Semua'}
                </Button>
            </div>
            <div className="flex gap-4 pt-1">
                <label className="flex flex-1 cursor-pointer items-center gap-2 rounded-md border border-slate-100 p-2 text-xs font-medium text-slate-700 hover:bg-slate-50/80">
                    <Checkbox
                        checked={activeStatuses.includes('true')}
                        onCheckedChange={() =>
                            onToggle('statuses', activeStatuses, 'true')
                        }
                    />
                    <span>Aktif</span>
                </label>
                <label className="flex flex-1 cursor-pointer items-center gap-2 rounded-md border border-slate-100 p-2 text-xs font-medium text-slate-700 hover:bg-slate-50/80">
                    <Checkbox
                        checked={activeStatuses.includes('false')}
                        onCheckedChange={() =>
                            onToggle('statuses', activeStatuses, 'false')
                        }
                    />
                    <span>Non-Aktif</span>
                </label>
            </div>
        </div>
    );
}

interface PegawaiFilterBarProps {
    searchQuery: string;
    onSearchChange: (value: string) => void;
    bidangList: any[];
    jabatanList: any[];
    pangkatList: any[];
    listPendidikanMaster: string[];
    activeBidang: string[];
    activeJabatan: string[];
    activePangkat: string[];
    activePendidikan: string[];
    activeStatuses: string[];
    totalActiveFilterCount: number;
    onToggleFilter: (
        key: string,
        currentArray: string[],
        value: string,
    ) => void;
    onSelectAllCategory: (
        key: string,
        currentArray: string[],
        allValues: string[],
    ) => void;
    onClearFilters: () => void;
}

export default function PegawaiFilterBar({
    searchQuery,
    onSearchChange,
    bidangList,
    jabatanList,
    pangkatList,
    listPendidikanMaster,
    activeBidang,
    activeJabatan,
    activePangkat,
    activePendidikan,
    activeStatuses,
    totalActiveFilterCount,
    onToggleFilter,
    onSelectAllCategory,
    onClearFilters,
}: PegawaiFilterBarProps) {
    return (
        <Card className="border-slate-100 bg-white shadow-sm">
            <CardContent className="flex items-center gap-3 p-4">
                <div className="relative flex-1">
                    <Search className="absolute top-1/2 left-3 size-4 -translate-y-1/2 text-slate-400" />
                    <Input
                        value={searchQuery}
                        onChange={(e) => onSearchChange(e.target.value)}
                        placeholder="Cari berdasarkan nama lengkap atau NIP..."
                        className="h-11 border-slate-200 bg-white pl-9"
                    />
                </div>

                {/* POPOVER ADVANCED MULTI-SELECT FILTER */}
                <Popover>
                    <PopoverTrigger asChild>
                        <Button
                            variant="outline"
                            className="h-11 gap-2 border-slate-200 bg-white px-4 font-medium text-slate-700 hover:bg-slate-50"
                        >
                            <SlidersHorizontal className="size-4 text-slate-500" />
                            <span>Filter</span>
                            {totalActiveFilterCount > 0 && (
                                <Badge className="ml-1 rounded-full bg-blue-600 px-1.5 py-0.5 text-[10px] font-bold text-white">
                                    {totalActiveFilterCount}
                                </Badge>
                            )}
                        </Button>
                    </PopoverTrigger>
                    <PopoverContent
                        className="max-h-[520px] w-[340px] overflow-y-auto rounded-lg border-slate-100 bg-white p-4 shadow-xl sm:w-[480px]"
                        align="end"
                    >
                        <div className="mb-4 flex items-center justify-between border-b border-slate-100 pb-2.5">
                            <h3 className="text-sm font-bold text-slate-900">
                                Saring Data Spesifik
                            </h3>
                            {totalActiveFilterCount > 0 && (
                                <Button
                                    variant="ghost"
                                    size="sm"
                                    onClick={onClearFilters}
                                    className="h-7 p-1 text-xs font-semibold text-red-500 hover:bg-red-50"
                                >
                                    Reset Semua
                                </Button>
                            )}
                        </div>

                        <div className="space-y-5">
                            {/* 1. Kategori Multi-select Bidang */}
                            <FilterCheckboxGroup
                                title="Struktur Bidang"
                                filterKey="bidang_ids"
                                activeValues={activeBidang}
                                options={(bidangList || []).map((b) => ({
                                    value: b.id.toString(),
                                    label: b.nama_bidang,
                                }))}
                                onToggle={onToggleFilter}
                                onSelectAll={onSelectAllCategory}
                            />

                            {/* 2. Kategori Multi-select Jabatan */}
                            <FilterCheckboxGroup
                                title="Jabatan"
                                filterKey="jabatan_ids"
                                activeValues={activeJabatan}
                                options={(jabatanList || []).map((j) => ({
                                    value: j.id.toString(),
                                    label: j.nama_jabatan,
                                }))}
                                onToggle={onToggleFilter}
                                onSelectAll={onSelectAllCategory}
                            />

                            {/* 3. Kategori Multi-select Golongan */}
                            <FilterCheckboxGroup
                                title="Golongan / Pangkat"
                                filterKey="pangkat_ids"
                                activeValues={activePangkat}
                                options={(pangkatList || []).map((p) => ({
                                    value: p.id.toString(),
                                    label: (
                                        <>
                                            {p.golongan} - {p.nama_pangkat}
                                        </>
                                    ),
                                }))}
                                onToggle={onToggleFilter}
                                onSelectAll={onSelectAllCategory}
                            />

                            {/* 4. Kategori Multi-select Pendidikan Terakhir (Fixed Typo: pendidikans) */}
                            <FilterCheckboxGroup
                                title="Pendidikan Terakhir"
                                filterKey="pendidikans"
                                activeValues={activePendidikan}
                                options={listPendidikanMaster.map((edu) => ({
                                    value: edu,
                                    label: edu,
                                }))}
                                gridCols={3}
                                truncateLabel={false}
                                explicitButtonType
                                onToggle={onToggleFilter}
                                onSelectAll={onSelectAllCategory}
                            />

                            {/* 5. Kategori Multi-select Status Keaktifan */}
                            <StatusFilterGroup
                                activeStatuses={activeStatuses}
                                onToggle={onToggleFilter}
                                onSelectAll={onSelectAllCategory}
                            />
                        </div>
                    </PopoverContent>
                </Popover>
            </CardContent>
        </Card>
    );
}
