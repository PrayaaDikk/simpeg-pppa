import { usePage } from '@inertiajs/react';
import { useEffect } from 'react';
import { toast, Toaster } from 'sonner';
import AppLayoutTemplate from '@/layouts/app/app-sidebar-layout';
import type { BreadcrumbItem } from '@/types';

interface FlashMessage {
    id: string;
    text: string;
}

interface PageProps {
    flash: {
        success: FlashMessage | null;
        error: FlashMessage | null;
    };
    [key: string]: any;
}

export default function AppLayout({
    breadcrumbs = [],
    children,
}: {
    breadcrumbs?: BreadcrumbItem[];
    children: React.ReactNode;
}) {
    const { flash } = usePage<PageProps>().props;

    useEffect(() => {
        if (flash.success?.text) {
            toast.success(flash.success.text, {
                id: flash.success.id,
            });
        }

        // Jika ada flash error baru
        if (flash.error?.text) {
            toast.error(flash.error.text, {
                id: flash.error.id,
            });
        }
    }, [flash.success, flash.error]);

    return (
        <AppLayoutTemplate breadcrumbs={breadcrumbs}>
            <Toaster position="top-right" richColors closeButton />
            {children}
        </AppLayoutTemplate>
    );
}
