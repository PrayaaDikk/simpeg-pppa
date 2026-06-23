import React from 'react';
import { Button } from '@/components/ui/button';
import { Plus } from 'lucide-react';

interface PegawaiHeaderProps {
    onAddClick: () => void;
}

export default function PegawaiHeader({ onAddClick }: PegawaiHeaderProps) {
    return (
        <div className="flex flex-col gap-4 px-1 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 className="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                    Daftar Pegawai
                </h1>
                <p className="text-sm text-slate-500">
                    Kelola data pegawai institusi secara tersentralisasi.
                </p>
            </div>
            <Button
                onClick={onAddClick}
                className="h-11 bg-blue-600 font-semibold text-white hover:bg-blue-700"
            >
                <Plus className="mr-2 size-4" /> Tambah Pegawai Baru
            </Button>
        </div>
    );
}
