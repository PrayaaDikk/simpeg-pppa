import type { InertiaLinkProps } from '@inertiajs/react';
import { clsx } from 'clsx';
import type { ClassValue } from 'clsx';
import { jsPDF } from 'jspdf';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function toUrl(url: NonNullable<InertiaLinkProps['href']>): string {
    return typeof url === 'string' ? url : url.url;
}

export const formatDateToISO = (dateVal) => {
    if (!dateVal) return '';
    try {
        const d = new Date(dateVal);
        // Memastikan tanggal valid sebelum diformat
        if (!isNaN(d.getTime())) {
            return d.toISOString().split('T')[0];
        }
    } catch (e) {
        console.error('Gagal memformat tanggal:', e);
    }
    return dateVal;
};
