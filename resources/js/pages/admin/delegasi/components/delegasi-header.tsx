import React from 'react';
import { ShieldAlert } from 'lucide-react';

export default function DelegasiHeader() {
    return (
        <div className="flex items-start gap-4 border-b border-slate-100 pb-5">
            <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                <ShieldAlert className="h-6 w-6" />
            </div>
            <div>
                <h1 className="text-xl font-bold tracking-tight text-slate-900">
                    Manajemen & Alokasi Otoritas Admin
                </h1>
                <p className="mt-1 text-xs leading-relaxed font-medium text-slate-500">
                    Halaman khusus{' '}
                    <span className="font-semibold text-amber-600">
                        Superadmin Mode
                    </span>
                    . Anda dapat memantau staf yang memegang akses administrator
                    serta mengalihkan atau memberikan pemberian instruksi{' '}
                    <span className="font-semibold text-slate-700">
                        Role Admin
                    </span>{' '}
                    baru kepada pegawai lainnya melalui Spatie guard.
                </p>
            </div>
        </div>
    );
}
