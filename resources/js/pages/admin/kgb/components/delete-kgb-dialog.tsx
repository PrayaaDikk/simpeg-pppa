import React from 'react';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';

interface DeleteKgbDialogProps {
    isOpen: boolean;
    onClose: () => void;
    deletingKgb: any;
    onConfirm: () => void;
}

export default function DeleteKgbDialog({
    isOpen,
    onClose,
    deletingKgb,
    onConfirm,
}: DeleteKgbDialogProps) {
    return (
        <Dialog open={isOpen} onOpenChange={onClose}>
            <DialogContent className="rounded-2xl border-slate-100 p-6 shadow-2xl sm:max-w-[425px]">
                <DialogHeader>
                    <DialogTitle className="text-base font-semibold text-red-600">
                        Hapus Riwayat KGB
                    </DialogTitle>
                    <DialogDescription className="pt-2 text-sm text-slate-500">
                        Apakah Anda yakin ingin menghapus berkas data KGB milik{' '}
                        <strong className="font-semibold text-slate-800">
                            {deletingKgb?.pegawai?.nama}
                        </strong>{' '}
                        secara permanen? Tindakan ini bersifat irreversible dan
                        data tidak dapat dipulihkan.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter className="mt-4 flex items-center justify-end gap-2">
                    <Button
                        variant="outline"
                        onClick={onClose}
                        className="rounded-xl border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50"
                    >
                        Batal
                    </Button>
                    <Button
                        variant="destructive"
                        onClick={onConfirm}
                        className="rounded-xl bg-red-600 text-xs font-semibold text-white shadow-xs hover:bg-red-700"
                    >
                        Hapus Permanen
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}
