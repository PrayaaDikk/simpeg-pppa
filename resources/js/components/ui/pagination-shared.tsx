import React from 'react';
import { Link2 } from 'lucide-react';

interface PaginationProps {
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
    from?: number | null;
    to?: number | null;
    total?: number;
}

// Helper untuk membersihkan entity HTML bawaan Laravel Paginate seperti &laquo; atau &raquo;
const cleanLabel = (label: string) => {
    return label
        .replace('&laquo;', '«')
        .replace('&raquo;', '»')
        .replace('Previous', 'Sebelumnya')
        .replace('Next', 'Selanjutnya');
};

export default function Pagination({ links, from, to, total }: PaginationProps) {
    const showLinks = links.length > 3; // Jangan tampilkan tombol jika hanya ada 1 halaman
    const showSummary = typeof total === 'number';

    if (!showLinks && !showSummary) return null;

    return (
        <div className="flex flex-col items-center justify-between gap-3 border-t border-slate-50 bg-white p-4 rounded-b-xl sm:flex-row">
            {showSummary ? (
                <p className="text-xs text-slate-500">
                    Menampilkan{' '}
                    <span className="font-semibold text-slate-700">
                        {from ?? 0}
                    </span>{' '}
                    -{' '}
                    <span className="font-semibold text-slate-700">
                        {to ?? 0}
                    </span>{' '}
                    dari{' '}
                    <span className="font-semibold text-slate-700">
                        {total}
                    </span>{' '}
                    data
                </p>
            ) : (
                <div />
            )}

            {showLinks && (
                <div className="flex flex-wrap items-center justify-center gap-1.5 sm:justify-end">
                    {links.map((link, key) => {
                        if (link.url === null) {
                            return (
                                <div
                                    key={key}
                                    className="px-3 py-1.5 text-xs font-medium text-slate-300 bg-slate-50 border border-slate-100 rounded-lg cursor-not-allowed select-none"
                                >
                                    {cleanLabel(link.label)}
                                </div>
                            );
                        }

                        return (
                            <a
                                key={key}
                                href={link.url}
                                className={`px-3 py-1.5 text-xs font-semibold rounded-lg border transition-all duration-200 select-none ${
                                    link.active
                                        ? 'bg-blue-600 text-white border-blue-600 shadow-xs'
                                        : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50 hover:text-slate-900'
                                }`}
                            >
                                {cleanLabel(link.label)}
                            </a>
                        );
                    })}
                </div>
            )}
        </div>
    );
}