import React from 'react';
import { Filter } from 'lucide-react';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';

interface FilterPopoverProps {
    currentFilter: string | null | undefined;
    onFilterChange: (status: string) => void;
}

export default function FilterPopover({
    currentFilter,
    onFilterChange,
}: FilterPopoverProps) {
    // Menyesuaikan daftar opsi status dengan state machine pada KgbController.php
    const statuses = [
        { key: 'semua', label: 'Semua Status' },
        { key: 'menunggu', label: 'Menunggu Verifikasi' },
        { key: 'disetujui', label: 'Disetujui' },
        { key: 'tidak disetujui', label: 'Tidak Disetujui' },
        { key: 'telah diproses', label: 'Telah Diproses' },
    ];

    // Menentukan filter aktif saat ini untuk keperluan rendering label tombol
    const activeFilterKey = currentFilter || 'semua';

    return (
        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <Button
                    variant="outline"
                    className="h-11 rounded-xl border-slate-200 bg-white text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50"
                >
                    <Filter className="mr-2 h-4 w-4 text-slate-400" />
                    Status:{' '}
                    {statuses.find((s) => s.key === activeFilterKey)?.label ||
                        'Semua Status'}
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent
                align="end"
                className="w-52 rounded-xl border-slate-100 bg-white p-1 shadow-xl"
            >
                {statuses.map((item) => (
                    <DropdownMenuItem
                        key={item.key}
                        onClick={() => onFilterChange(item.key)}
                        className={`cursor-pointer rounded-lg px-3 py-2 text-xs font-semibold ${
                            activeFilterKey === item.key
                                ? 'bg-blue-50 text-blue-600 hover:bg-blue-50 hover:text-blue-600'
                                : 'text-slate-600 hover:text-slate-900'
                        }`}
                    >
                        {item.label}
                    </DropdownMenuItem>
                ))}
            </DropdownMenuContent>
        </DropdownMenu>
    );
}
