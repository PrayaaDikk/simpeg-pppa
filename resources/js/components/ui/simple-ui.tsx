import React from 'react';
import { CheckCircle } from 'lucide-react';

interface SimpleAlertProps {
    message: string | null | undefined;
    onClose?: () => void;
}

export default function SimpleAlert({ message, onClose }: SimpleAlertProps) {
    if (!message) return null;

    return (
        <div className="flex items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50/60 p-4 text-emerald-800 shadow-xs">
            {/* Ikon Sukses */}
            <CheckCircle className="h-5 w-5 shrink-0 text-emerald-600" />
            
            {/* Konten Teks */}
            <div className="flex-1 text-xs font-semibold leading-relaxed">
                {message}
            </div>

            {/* Tombol Tutup Manual (Opsional) */}
            {onClose && (
                <button
                    onClick={onClose}
                    className="rounded-lg p-1 text-emerald-500 hover:bg-emerald-100 hover:text-emerald-700 transition-colors cursor-pointer text-xs font-bold"
                >
                    Tutup
                </button>
            )}
        </div>
    );
}