import React, { useState, useEffect } from 'react';
import { Head, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';

import CreatePegawaiSheet from './components/create-pegawai-sheet';
import EditPegawaiSheet from './components/edit-pegawai-sheet';
import PegawaiHeader from './components/pegawai-header';
import PegawaiFilterBar from './components/pegawai-filter-bar';
import PegawaiTable from './components/pegawai-table';
import PegawaiDeleteDialog from './components/pegawai-delete-dialog';
import type { Pegawai, PegawaiPaginator } from './components/pegawai-types';

interface PegawaiIndexProps {
    pegawaiList: PegawaiPaginator;
    bidangList: any[];
    jabatanList: any[];
    pangkatList: any[];
    stateFilters: {
        search: string;
        bidang_id: string[];
        jabatan_id: string[];
        pangkat_id: string[];
        pendidikans: string[];
        statuses: string[];
    };
}

export default function PegawaiIndex({
    pegawaiList,
    bidangList,
    jabatanList,
    pangkatList,
    stateFilters,
}: PegawaiIndexProps) {
    const [searchQuery, setSearchQuery] = useState(stateFilters.search || '');
    const [isCreateOpen, setIsCreateOpen] = useState(false);
    const [isEditOpen, setIsEditOpen] = useState(false);
    const [selectedPegawai, setSelectedPegawai] = useState<Pegawai | null>(
        null,
    );
    const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState(false);
    const [deletingPegawai, setDeletingPegawai] = useState<Pegawai | null>(
        null,
    );

    // Ambil nilai filter aktif dari prop stateFilters secara konsisten
    const activeBidang = stateFilters.bidang_id || [];
    const activeJabatan = stateFilters.jabatan_id || [];
    const activePangkat = stateFilters.pangkat_id || [];
    const activePendidikan = stateFilters.pendidikans || [];
    const activeStatuses = stateFilters.statuses || [];

    // Fungsi pengirim parameter filter array ke backend Laravel
    const applyArrayFilters = (updatedFilters: Record<string, any>) => {
        const params = {
            search: searchQuery || undefined,
            bidang_ids: activeBidang,
            jabatan_ids: activeJabatan,
            pangkat_ids: activePangkat,
            pendidikans: activePendidikan,
            statuses: activeStatuses,
            ...updatedFilters,
        };

        // Bersihkan parameter jika array kosong
        Object.keys(params).forEach((key) => {
            if (Array.isArray(params[key]) && params[key].length === 0) {
                delete params[key];
            }
        });

        router.get('/admin/pegawai', params, {
            preserveState: true,
            replace: true,
        });
    };

    // Logika Debounce untuk Input Pencarian Utama
    useEffect(() => {
        const delayDebounceFn = setTimeout(() => {
            if (searchQuery !== stateFilters.search) {
                applyArrayFilters({ search: searchQuery });
            }
        }, 400);
        return () => clearTimeout(delayDebounceFn);
    }, [searchQuery]);

    // Handler manipulasi item di dalam array filter kriteria individu
    const handleToggleFilter = (
        key: string,
        currentArray: string[],
        value: string,
    ) => {
        const newArray = currentArray.includes(value)
            ? currentArray.filter((item) => item !== value)
            : [...currentArray, value];
        applyArrayFilters({ [key]: newArray });
    };

    // Handler untuk aksi Select Semua (Pilih Semua) / Hapus Semua Kategori Tertentu
    const handleSelectAllCategory = (
        key: string,
        currentArray: string[],
        allValues: string[],
    ) => {
        const isAllSelected = currentArray.length === allValues.length;
        const newArray = isAllSelected ? [] : allValues;
        applyArrayFilters({ [key]: newArray });
    };

    const handleClearFilters = () => {
        setSearchQuery('');
        router.get('/admin/pegawai', {}, { replace: true });
    };

    const executeDelete = () => {
        if (!deletingPegawai) return;
        router.delete(`/admin/pegawai/${deletingPegawai.id}`, {
            onSuccess: () => {
                setIsDeleteDialogOpen(false);
                setDeletingPegawai(null);
            },
        });
    };

    const handleEditClick = (pegawai: Pegawai) => {
        setSelectedPegawai(pegawai);
        setIsEditOpen(true);
    };

    const handleDeleteClick = (pegawai: Pegawai) => {
        setDeletingPegawai(pegawai);
        setIsDeleteDialogOpen(true);
    };

    const listPendidikanMaster = ['SMA', 'D1', 'D2', 'D3', 'S1', 'S2', 'S3'];

    // Hitung total akumulasi filter yang sedang aktif digunakan
    const totalActiveFilterCount =
        activeBidang.length +
        activeJabatan.length +
        activePangkat.length +
        activePendidikan.length +
        activeStatuses.length;

    return (
        <>
            <Head title="Manajemen Pegawai" />

            <div className="flex flex-col gap-5 p-6">
                {/* Header Section */}
                <PegawaiHeader onAddClick={() => setIsCreateOpen(true)} />

                {/* Filter Panel Card (Popover Combo Search) */}
                <PegawaiFilterBar
                    searchQuery={searchQuery}
                    onSearchChange={setSearchQuery}
                    bidangList={bidangList}
                    jabatanList={jabatanList}
                    pangkatList={pangkatList}
                    listPendidikanMaster={listPendidikanMaster}
                    activeBidang={activeBidang}
                    activeJabatan={activeJabatan}
                    activePangkat={activePangkat}
                    activePendidikan={activePendidikan}
                    activeStatuses={activeStatuses}
                    totalActiveFilterCount={totalActiveFilterCount}
                    onToggleFilter={handleToggleFilter}
                    onSelectAllCategory={handleSelectAllCategory}
                    onClearFilters={handleClearFilters}
                />

                {/* Tabel Data Pegawai */}
                <PegawaiTable
                    pegawaiList={pegawaiList}
                    onEdit={handleEditClick}
                    onDelete={handleDeleteClick}
                />
            </div>

            <CreatePegawaiSheet
                isOpen={isCreateOpen}
                onOpenChange={setIsCreateOpen}
                bidangList={bidangList}
                jabatanList={jabatanList}
                pangkatList={pangkatList}
            />
            <EditPegawaiSheet
                isOpen={isEditOpen}
                onOpenChange={setIsEditOpen}
                pegawai={selectedPegawai}
                bidangList={bidangList}
                jabatanList={jabatanList}
                pangkatList={pangkatList}
            />

            <PegawaiDeleteDialog
                isOpen={isDeleteDialogOpen}
                onOpenChange={setIsDeleteDialogOpen}
                pegawai={deletingPegawai}
                onConfirm={executeDelete}
            />
        </>
    );
}

PegawaiIndex.layout = (page: React.ReactNode) => (
    <AppLayout
        breadcrumbs={[{ title: 'Manajemen Pegawai', href: '/admin/pegawai' }]}
    >
        {page}
    </AppLayout>
);
