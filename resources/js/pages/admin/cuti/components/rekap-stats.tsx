import React from 'react';
import { Users, CalendarDays, Clock } from 'lucide-react';
import { Card, CardContent } from '@/components/ui/card';

interface RekapStatsProps {
    totalPegawaiCuti: number;
    totalHariCuti: number;
    totalDokumen: number;
}

export default function RekapStats({
    totalPegawaiCuti,
    totalHariCuti,
    totalDokumen,
}: RekapStatsProps) {
    const stats = [
        {
            title: 'Pegawai Cuti',
            value: `${totalPegawaiCuti} Orang`,
            description: 'Aktif mengambil hak cuti',
            icon: Users,
            color: 'text-indigo-600 bg-indigo-50 border-indigo-100',
        },
        {
            title: 'Akumulasi Durasi',
            value: `${totalHariCuti} Hari`,
            description: 'Total ketidakhadiran kantor',
            icon: CalendarDays,
            color: 'text-amber-600 bg-amber-50 border-amber-100',
        },
        {
            title: 'Total Berkas',
            value: `${totalDokumen} Dokumen`,
            description: 'Telah diarsipkan & disetujui',
            icon: Clock,
            color: 'text-emerald-600 bg-emerald-50 border-emerald-100',
        },
    ];

    return (
        <div className="grid grid-cols-1 gap-4 sm:grid-cols-3">
            {stats.map((stat, i) => {
                const Icon = stat.icon;
                return (
                    <Card
                        key={i}
                        className="overflow-hidden border-slate-100 bg-white/60 shadow-xs backdrop-blur-md"
                    >
                        <CardContent className="p-5">
                            <div className="flex items-center justify-between">
                                <div className="space-y-1">
                                    <p className="text-xs font-bold tracking-wider text-slate-500 uppercase">
                                        {stat.title}
                                    </p>
                                    <h3 className="text-xl font-extrabold tracking-tight text-slate-800">
                                        {stat.value}
                                    </h3>
                                    <p className="text-[10px] font-medium text-slate-400">
                                        {stat.description}
                                    </p>
                                </div>
                                <div
                                    className={`rounded-xl border p-2.5 ${stat.color}`}
                                >
                                    <Icon className="h-5 w-5" />
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                );
            })}
        </div>
    );
}
