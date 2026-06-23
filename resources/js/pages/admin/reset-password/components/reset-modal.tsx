import React, { useState } from 'react';
import { useForm } from '@inertiajs/react';
import { ShieldCheck, Check, Copy, Loader2, AlertTriangle } from 'lucide-react';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';

interface Pegawai {
    id: number;
    nama: string;
    nip: string;
    user?: { email: string };
}

interface ResetModalProps {
    pegawai: Pegawai | null;
    isOpen: boolean;
    onClose: () => void;
    generatedPasswordFromServer: string | null;
}

export default function ResetModal({
    pegawai,
    isOpen,
    onClose,
    generatedPasswordFromServer,
}: ResetModalProps) {
    const [copied, setCopied] = useState(false);
    const { post, processing } = useForm();

    if (!pegawai) return null;

    const handleConfirmReset = (e: React.FormEvent) => {
        e.preventDefault();
        post(`/admin/atur-ulang-sandi/${pegawai.id}`, {
            preserveScroll: true,
        });
    };

    const handleCopy = async () => {
        if (generatedPasswordFromServer) {
            await navigator.clipboard.writeText(generatedPasswordFromServer);
            setCopied(true);
            setTimeout(() => setCopied(false), 2000);
        }
    };

    return (
        <Dialog open={isOpen} onOpenChange={(open) => !open && onClose()}>
            <DialogContent className="rounded-2xl p-6 sm:max-w-[430px]">
                {/* TAHAP 2: BILA PASSWORD ACAK BARU SUDAH DI-GENERATE OLEH LARAVEL */}
                {generatedPasswordFromServer ? (
                    <div className="space-y-4">
                        <DialogHeader>
                            <div className="mx-auto mb-1 flex h-11 w-11 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                                <ShieldCheck className="h-5 w-5" />
                            </div>
                            <DialogTitle className="text-center text-sm font-bold text-slate-900">
                                Sandi Berhasil Diatur Ulang
                            </DialogTitle>
                            <DialogDescription className="text-center text-[11px] font-medium text-slate-500">
                                Hak masuk sistem milik{' '}
                                <span className="font-semibold text-slate-800">
                                    {pegawai.nama}
                                </span>{' '}
                                telah diperbarui.
                            </DialogDescription>
                        </DialogHeader>

                        {/* Detail Kredensial */}
                        <div className="space-y-2.5 rounded-xl border border-slate-100 bg-slate-50/80 p-4 text-xs font-medium text-slate-600">
                            <div className="flex justify-between border-b border-slate-200/40 pb-2">
                                <span className="text-slate-400">Pegawai:</span>
                                <span className="font-bold text-slate-800">
                                    {pegawai.nama}
                                </span>
                            </div>
                            <div className="flex justify-between border-b border-slate-200/40 pb-2">
                                <span className="text-slate-400">
                                    Email Utama:
                                </span>
                                <span className="font-semibold text-slate-700">
                                    {pegawai.user?.email || '-'}
                                </span>
                            </div>
                            <div className="flex items-center justify-between pt-1">
                                <span className="text-slate-400">
                                    Sandi Baru:
                                </span>
                                <div className="flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-2.5 py-1 font-mono text-xs font-bold text-blue-600 shadow-2xs">
                                    <span>{generatedPasswordFromServer}</span>
                                    <button
                                        type="button"
                                        onClick={handleCopy}
                                        className="cursor-pointer rounded p-0.5 text-slate-400 transition-colors hover:text-slate-600"
                                    >
                                        {copied ? (
                                            <Check className="h-3 w-3 text-emerald-600" />
                                        ) : (
                                            <Copy className="h-3 w-3" />
                                        )}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div className="rounded-lg border border-amber-100 bg-amber-50 p-3 text-[10px] leading-relaxed font-semibold text-amber-700">
                            ⚠️ Perhatian: Sandi acak ini bersifat temporer
                            rahasia dan hanya muncul sekali. Salin teks di atas
                            dan serahkan secara manual ke pegawai yang
                            bersangkutan.
                        </div>

                        <DialogFooter>
                            <Button
                                type="button"
                                onClick={onClose}
                                className="h-10 w-full cursor-pointer rounded-xl bg-slate-900 text-xs font-bold text-white hover:bg-slate-800"
                            >
                                Tutup Panel & Selesai
                            </Button>
                        </DialogFooter>
                    </div>
                ) : (
                    /* TAHAP 1: KONFIRMASI PERSETUJUAN */
                    <form onSubmit={handleConfirmReset} className="space-y-4">
                        <DialogHeader>
                            <div className="mx-auto mb-1 flex h-11 w-11 items-center justify-center rounded-full bg-amber-50 text-amber-600">
                                <AlertTriangle className="h-5 w-5" />
                            </div>
                            <DialogTitle className="text-center text-sm font-bold text-slate-900">
                                Setujui Atur Ulang Sandi?
                            </DialogTitle>
                            <DialogDescription className="text-center text-xs leading-relaxed font-medium text-slate-500">
                                Tindakan ini akan langsung mereset sandi masuk
                                untuk akun milik{' '}
                                <span className="font-bold text-slate-800">
                                    {pegawai.nama}
                                </span>{' '}
                                (NIP. {pegawai.nip}).
                            </DialogDescription>
                        </DialogHeader>

                        <DialogFooter className="mt-5 gap-2 sm:gap-0">
                            <Button
                                type="button"
                                variant="outline"
                                onClick={onClose}
                                className="h-10 rounded-xl border-slate-200 text-xs font-semibold"
                            >
                                Batalkan
                            </Button>
                            <Button
                                type="submit"
                                disabled={processing}
                                className="h-10 cursor-pointer rounded-xl bg-blue-600 text-xs font-bold text-white shadow-none hover:bg-blue-700"
                            >
                                {processing ? (
                                    <>
                                        <Loader2 className="mr-1.5 h-3.5 w-3.5 animate-spin" />
                                        Sedang Mengacak...
                                    </>
                                ) : (
                                    'Ya, Generate Sandi Baru'
                                )}
                            </Button>
                        </DialogFooter>
                    </form>
                )}
            </DialogContent>
        </Dialog>
    );
}
