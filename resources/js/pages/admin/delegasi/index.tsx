import React from 'react';
import { Head, usePage } from '@inertiajs/react';
import { Card, CardContent } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';

import DelegasiHeader from './components/delegasi-header';
import DelegasiForm from './components/delegasi-form';
import AdminList from './components/admin-list';

interface AdminUser {
    id: number;
    nama: string;
    nip: string;
    user?: {
        id: number;
        email: string;
    };
}

interface PegawaiOption {
    id: number;
    nama: string;
    nip: string;
}

interface PageProps {
    adminList: AdminUser[];
    pegawaiList: PegawaiOption[];
    [key: string]: any;
}

export default function DelegasiIndex() {
    const { adminList, pegawaiList } = usePage<PageProps>().props;

    return (
        <>
            <Head title="Otorisasi Hak Akses Admin" />

            {/* Container Full-Width tanpa 'max-w-*' sesuai permintaan */}
            <div className="w-full p-1">
                <Card className="overflow-hidden rounded-2xl border border-slate-100 bg-white p-6 shadow-xs sm:p-8">
                    <CardContent className="space-y-6 p-0">
                        <DelegasiHeader />

                        {/* Grid layout responsif membagi daftar admin dan form manipulasi */}
                        <div className="grid grid-cols-1 items-start gap-6 lg:grid-cols-12">
                            {/* Kiri: Menampilkan pemegang wewenang admin saat ini */}
                            <div className="lg:col-span-5 xl:col-span-4">
                                <AdminList adminList={adminList || []} />
                            </div>

                            {/* Kanan: Komponen pengubah wewenang via role Spatie */}
                            <div className="lg:col-span-7 xl:col-span-8">
                                <DelegasiForm pegawaiList={pegawaiList || []} />
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}

DelegasiIndex.layout = (page: React.ReactNode) => (
    <AppLayout
        breadcrumbs={[
            { title: 'Master Data', href: '#' },
            { title: 'Delegasi Sistem', href: '/admin/master/delegasi-akses' },
        ]}
    >
        {page}
    </AppLayout>
);
