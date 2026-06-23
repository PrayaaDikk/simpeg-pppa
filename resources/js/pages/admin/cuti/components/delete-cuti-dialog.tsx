import React from 'react';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogDescription,
    DialogFooter,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';

interface DeleteCutiDialogProps {
    isOpen: boolean;
    onClose: () => void;
    onConfirm: () => void;
    cuti: any;
    mappingJenisCuti: Record<string, string>;
}

export default function DeleteCutiDialog({
    isOpen,
    onClose,
    onConfirm,
    cuti,
    mappingJenisCuti,
}: DeleteCutiDialogProps) {
    return (
        <Dialog open={isOpen} onOpenChange={onClose}>
            <DialogContent className="rounded-2xl border-slate-100 bg-white p-5 shadow-2xl sm:max-w-[420px]">
                <DialogHeader className="space-y-2">
                    <DialogTitle className="text-base font-bold text-slate-800">
                        Hapus Dokumen Cuti
                    </DialogTitle>
                    <DialogDescription className="text-xs leading-relaxed font-medium text-slate-500">
                        Apakah Anda yakin ingin menghapus data cuti{' '}
                        <strong className="font-bold text-slate-700">
                            (
                            {mappingJenisCuti[cuti?.jenis_cuti || ''] ||
                                cuti?.jenis_cuti}
                            )
                        </strong>{' '}
                        milik{' '}
                        <strong className="font-bold text-slate-700">
                            {cuti?.pegawai?.nama}
                        </strong>{' '}
                        secara permanen?
                        <br />
                        <br />
                        Tindakan ini tidak dapat dibatalkan.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter className="mt-4 gap-2">
                    <Button
                        variant="outline"
                        onClick={onClose}
                        className="h-9 rounded-xl border-slate-200 text-xs font-bold text-slate-700"
                    >
                        Batal
                    </Button>
                    <Button
                        variant="destructive"
                        onClick={onConfirm}
                        className="h-9 rounded-xl bg-red-600 text-xs font-bold text-white shadow-xs hover:bg-red-700"
                    >
                        Hapus Permanen
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}
