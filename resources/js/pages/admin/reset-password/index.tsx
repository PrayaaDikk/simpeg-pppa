import React, { useState, useEffect } from 'react';
import { Head, usePage } from '@inertiajs/react';
import { Card, CardContent } from '@/components/ui/card';
import { ShieldAlert } from 'lucide-react';
import AppLayout from '@/layouts/app-layout';
import PegawaiTable from './components/pegawai-table'; // Import komponen tabel baru
import ResetModal from './components/reset-modal';

interface PageProps {
    pegawaiList: any[];
    auth: { user: any };
    flash: {
        password_reset_success?: { password: string };
    };
    [key: string]: any;
}

export default function ResetPasswordIndex() {
    const { pegawaiList, auth, flash } = usePage<PageProps>().props;

    const [selectedPegawai, setSelectedPegawai] = useState<any | null>(null);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [generatedPassword, setGeneratedPassword] = useState<string | null>(
        null,
    );

    useEffect(() => {
        if (flash.password_reset_success?.password) {
            setGeneratedPassword(flash.password_reset_success.password);

            if (selectedPegawai) {
                setIsModalOpen(true);
            }
        }
    }, [flash.password_reset_success]);

    const handleOpenModal = (pegawai: any) => {
        setGeneratedPassword(null);
        setSelectedPegawai(pegawai);
        setIsModalOpen(true);
    };

    const handleCloseModal = () => {
        setIsModalOpen(false);
        setSelectedPegawai(null);
        setGeneratedPassword(null);
    };

    return (
        <>
            <Head title="Manajemen Kata Sandi Staf" />

            <div className="w-full p-1">
                <Card className="rounded-2xl border border-slate-100 bg-white p-6 shadow-xs sm:p-8">
                    <CardContent className="space-y-6 p-0">
                        {/* Header Panel */}
                        <div className="flex items-start gap-4 border-b border-slate-100 pb-5">
                            <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                                <ShieldAlert className="h-6 w-6" />
                            </div>
                            <div>
                                <h1 className="text-xl font-bold tracking-tight text-slate-900">
                                    Pusat Atur Ulang Sandi Sistem
                                </h1>
                                <p className="mt-1 text-xs leading-relaxed font-medium text-slate-500">
                                    Panel kontrol darurat untuk mereset kata
                                    sandi akun pegawai yang mengalami kendala
                                    masuk aplikasi.
                                </p>
                            </div>
                        </div>

                        {/* Komponen Tabel yang Telah Dipisah */}
                        <PegawaiTable
                            pegawaiList={pegawaiList || []}
                            currentUser={auth.user}
                            onResetClick={handleOpenModal}
                        />
                    </CardContent>
                </Card>
            </div>

            {/* Modal Dialog Berjenjang */}
            <ResetModal
                isOpen={isModalOpen}
                pegawai={selectedPegawai}
                onClose={handleCloseModal}
                generatedPasswordFromServer={generatedPassword}
            />
        </>
    );
}

ResetPasswordIndex.layout = (page: React.ReactNode) => (
    <AppLayout
        breadcrumbs={[
            { title: 'Sistem Keamanan', href: '#' },
            { title: 'Atur Ulang Sandi', href: '/atur-ulang-sandi' },
        ]}
    >
        {page}
    </AppLayout>
);
