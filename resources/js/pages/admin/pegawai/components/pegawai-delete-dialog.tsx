import React from 'react';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import type { Pegawai } from './pegawai-types';

interface PegawaiDeleteDialogProps {
    isOpen: boolean;
    onOpenChange: (open: boolean) => void;
    pegawai: Pegawai | null;
    onConfirm: () => void;
}

export default function PegawaiDeleteDialog({
    isOpen,
    onOpenChange,
    pegawai,
    onConfirm,
}: PegawaiDeleteDialogProps) {
    return (
        <Dialog open={isOpen} onOpenChange={onOpenChange}>
            <DialogContent className="bg-white p-6 sm:max-w-md">
                <DialogHeader>
                    <DialogTitle className="text-lg font-bold text-slate-900">
                        Konfirmasi Hapus Data
                    </DialogTitle>
                    <DialogDescription className="pt-2 text-sm text-slate-500">
                        Apakah Anda yakin ingin menghapus data profil pegawai{' '}
                        <strong className="text-slate-700">
                            {pegawai?.nama}
                        </strong>{' '}
                        beserta sistem user secara permanen?
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter className="mt-4 gap-2">
                    <Button
                        variant="outline"
                        onClick={() => onOpenChange(false)}
                        className="border-slate-200 text-slate-700"
                    >
                        Batal
                    </Button>
                    <Button
                        variant="destructive"
                        onClick={onConfirm}
                        className="bg-red-600 text-white hover:bg-red-700"
                    >
                        Hapus Permanen
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}
