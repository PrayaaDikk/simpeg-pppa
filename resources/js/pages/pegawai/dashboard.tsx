import React from 'react';
import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
    CardDescription,
} from '@/components/ui/card';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import {
    Calendar,
    User,
    Award,
    Clock,
    ArrowUpRight,
    CheckCircle2,
    AlertTriangle,
    XCircle,
} from 'lucide-react';
import { cn } from '@/lib/utils';

interface PegawaiData {
    nama: string;
    nip: string;
    jabatan: string;
    pangkat: string;
    golongan: string;
    bidang: string;
}

interface MetrikData {
    total_hari_cuti: number;
    cuti_terakhir: {
        jenis: string;
        status: 'menunggu' | 'disetujui' | 'tidak disetujui';
        tanggal: string;
    } | null;
    kgb_berikutnya: string;
    kgb_countdown: string | null;
}

interface TimelineItem {
    id: string;
    tipe: 'CUTI' | 'KGB';
    judul: string;
    deskripsi: string;
    status: 'menunggu' | 'disetujui' | 'tidak disetujui';
    tanggal: string;
}

interface Props {
    pegawai: PegawaiData;
    metrik: MetrikData;
    timelineAktivitas: TimelineItem[];
}

export default function PegawaiDashboard({
    pegawai,
    metrik,
    timelineAktivitas,
}: Props) {
    // Fungsi pembantu untuk merender badge status sesuai aturan warna ketat di DESIGN.md
    const renderStatusBadge = (
        status: 'menunggu' | 'disetujui' | 'tidak disetujui',
    ) => {
        switch (status) {
            case 'disetujui':
                return (
                    <span className="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                        <CheckCircle2 className="h-3.5 w-3.5 text-emerald-600" />
                        Disetujui / Selesai
                    </span>
                );
            case 'menunggu':
                return (
                    <span className="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                        <Clock className="h-3.5 w-3.5 text-amber-600" />
                        Menunggu / Proses
                    </span>
                );
            case 'tidak disetujui':
                return (
                    <span className="inline-flex items-center gap-1.5 rounded-full border border-rose-200 bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700">
                        <XCircle className="h-3.5 w-3.5 text-rose-600" />
                        Tidak Disetujui / Ditolak
                    </span>
                );
            default:
                return null;
        }
    };

    return (
        <>
            <Head title="Dashboard Pegawai" />

            <div className="space-y-8 p-1">
                {/* UCAPAN SELAMAT DATANG & PROFIL SINGKAT */}
                <div className="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 className="text-2xl font-bold tracking-tight text-slate-900">
                            Selamat Datang, {pegawai.nama}
                        </h2>
                        <p className="text-sm text-slate-500">
                            Pantau informasi profil kepegawaian, ajenda kgb, dan
                            riwayat permohonan cuti Anda secara terpadu.
                        </p>
                    </div>
                    <div className="self-start rounded-lg border border-slate-200/60 bg-slate-100/70 px-3 py-1.5 text-xs text-slate-400 md:self-auto">
                        NIP:{' '}
                        <span className="font-mono font-medium text-slate-700">
                            {pegawai.nip}
                        </span>
                    </div>
                </div>

                {/* GRID BLOK KARTU ANALITIK UTAMA (GLASSMORPHIC CARDS) */}
                <div className="grid gap-6 md:grid-cols-3">
                    {/* CARD 1: RINGKASAN PROFIL STRUKTURAL */}
                    <Card className="overflow-hidden border-slate-200/80 bg-white/70 shadow-sm backdrop-blur-md">
                        <CardHeader className="border-b border-slate-100 bg-slate-50/50 pb-4">
                            <div className="flex items-center gap-3">
                                <div className="rounded-lg border border-blue-100 bg-blue-50 p-2.5 text-blue-600">
                                    <User className="h-5 w-5" />
                                </div>
                                <div>
                                    <CardTitle className="text-base font-bold text-slate-800">
                                        Profil Kepegawaian
                                    </CardTitle>
                                    <CardDescription className="text-xs">
                                        Data jabatan struktural aktif
                                    </CardDescription>
                                </div>
                            </div>
                        </CardHeader>
                        <CardContent className="space-y-4 pt-5">
                            <div>
                                <label className="block text-[11px] font-bold tracking-wider text-slate-400 uppercase">
                                    Jabatan
                                </label>
                                <span className="text-sm font-semibold text-slate-700">
                                    {pegawai.jabatan}
                                </span>
                            </div>
                            <div className="grid grid-cols-2 gap-2">
                                <div>
                                    <label className="block text-[11px] font-bold tracking-wider text-slate-400 uppercase">
                                        Pangkat / Gol
                                    </label>
                                    <span className="text-sm font-semibold text-slate-700">
                                        {pegawai.pangkat} ({pegawai.golongan})
                                    </span>
                                </div>
                                <div>
                                    <label className="block text-[11px] font-bold tracking-wider text-slate-400 uppercase">
                                        Bidang / Unit Kerja
                                    </label>
                                    <span className="text-sm font-semibold text-slate-700">
                                        {pegawai.bidang}
                                    </span>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    {/* CARD 2: RINGKASAN METRIK CUTI */}
                    <Card className="overflow-hidden border-slate-200/80 bg-white/70 shadow-sm backdrop-blur-md">
                        <CardHeader className="border-b border-slate-100 bg-slate-50/50 pb-4">
                            <div className="flex items-center gap-3">
                                <div className="rounded-lg border border-emerald-100 bg-emerald-50 p-2.5 text-emerald-600">
                                    <Calendar className="h-5 w-5" />
                                </div>
                                <div>
                                    <CardTitle className="text-base font-bold text-slate-800">
                                        Rekapitulasi Cuti
                                    </CardTitle>
                                    <CardDescription className="text-xs">
                                        Informasi pemakaian cuti tahunan
                                    </CardDescription>
                                </div>
                            </div>
                        </CardHeader>
                        <CardContent className="space-y-4 pt-5">
                            <div className="flex items-baseline justify-between">
                                <div>
                                    <label className="block text-[11px] font-bold tracking-wider text-slate-400 uppercase">
                                        Total Hari Diambil
                                    </label>
                                    <span className="text-3xl font-black tracking-tight text-slate-900">
                                        {metrik.total_hari_cuti}{' '}
                                        <span className="text-xs font-normal text-slate-400">
                                            Hari
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <div className="border-t border-slate-100 pt-3">
                                <label className="mb-1 block text-[11px] font-bold tracking-wider text-slate-400 uppercase">
                                    Pengajuan Terakhir
                                </label>
                                {metrik.cuti_terakhir ? (
                                    <div className="flex items-center justify-between text-xs">
                                        <span className="font-medium text-slate-600">
                                            {metrik.cuti_terakhir.jenis}
                                        </span>
                                        {renderStatusBadge(
                                            metrik.cuti_terakhir.status,
                                        )}
                                    </div>
                                ) : (
                                    <span className="block text-xs text-slate-400 italic">
                                        Belum pernah mengajukan cuti
                                    </span>
                                )}
                            </div>
                        </CardContent>
                    </Card>

                    {/* CARD 3: STATUS AGENDA KGB NEXT MILESTONE */}
                    <Card className="overflow-hidden border-slate-200/80 bg-white/70 shadow-sm backdrop-blur-md">
                        <CardHeader className="border-b border-slate-100 bg-slate-50/50 pb-4">
                            <div className="flex items-center gap-3">
                                <div className="rounded-lg border border-indigo-100 bg-indigo-50 p-2.5 text-indigo-600">
                                    <Award className="h-5 w-5" />
                                </div>
                                <div>
                                    <CardTitle className="text-base font-bold text-slate-800">
                                        Agenda KGB Berkala
                                    </CardTitle>
                                    <CardDescription className="text-xs">
                                        Kenaikan Gaji Berkala berikutnya
                                    </CardDescription>
                                </div>
                            </div>
                        </CardHeader>
                        <CardContent className="space-y-4 pt-5">
                            <div>
                                <label className="block text-[11px] font-bold tracking-wider text-slate-400 uppercase">
                                    Tanggal TMT Selanjutnya
                                </label>
                                <span className="text-lg font-bold text-slate-800">
                                    {metrik.kgb_berikutnya}
                                </span>
                            </div>
                            <div className="border-t border-slate-100 pt-3">
                                <label className="mb-0.5 block text-[11px] font-bold tracking-wider text-slate-400 uppercase">
                                    Estimasi Hitung Mundur
                                </label>
                                <span
                                    className={cn(
                                        'text-xs font-semibold',
                                        metrik.kgb_countdown ===
                                            'Sudah Waktunya Pengajuan'
                                            ? 'animate-pulse text-blue-600'
                                            : 'text-indigo-600',
                                    )}
                                >
                                    {metrik.kgb_countdown ??
                                        'Data riwayat pengangkatan awal belum tercatat'}
                                </span>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                {/* TABEL AKTIVITAS & TIMELINE LINIMASA (MIN-HEIGHT ROW 56PX) */}
                <Card className="border-slate-200/80 bg-white/90 shadow-sm">
                    <CardHeader className="border-b border-slate-100 pb-5">
                        <CardTitle className="text-lg font-bold text-slate-800">
                            Riwayat Pengajuan & Aktivitas Anda
                        </CardTitle>
                        <CardDescription className="text-xs">
                            Daftar rekaman kronologis administrasi Cuti dan KGB
                            yang terafiliasi dengan profil Anda
                        </CardDescription>
                    </CardHeader>
                    <CardContent className="p-0">
                        {timelineAktivitas.length > 0 ? (
                            <Table>
                                <TableHeader className="bg-slate-50/70">
                                    <TableRow className="border-b border-slate-100">
                                        <TableHead className="w-[140px] px-6 font-bold text-slate-700">
                                            Tanggal
                                        </TableHead>
                                        <TableHead className="w-[120px] font-bold text-slate-700">
                                            Kategori
                                        </TableHead>
                                        <TableHead className="font-bold text-slate-700">
                                            Detail Aktivitas
                                        </TableHead>
                                        <TableHead className="w-[200px] px-6 text-right font-bold text-slate-700">
                                            Status
                                        </TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {timelineAktivitas.map((item) => (
                                        <TableRow
                                            key={item.id}
                                            className="h-[56px] border-b border-slate-100 transition-colors hover:bg-slate-50/50"
                                        >
                                            <TableCell className="px-6 text-sm font-medium text-slate-500">
                                                {item.tanggal}
                                            </TableCell>
                                            <TableCell>
                                                <span
                                                    className={cn(
                                                        'inline-flex items-center rounded px-2 py-0.5 text-[10px] font-bold tracking-wider uppercase',
                                                        item.tipe === 'CUTI'
                                                            ? 'border border-blue-100 bg-blue-50 text-blue-700'
                                                            : 'border border-indigo-100 bg-indigo-50 text-indigo-700',
                                                    )}
                                                >
                                                    {item.tipe}
                                                </span>
                                            </TableCell>
                                            <TableCell>
                                                <div className="flex flex-col">
                                                    <span className="text-sm font-semibold text-slate-700">
                                                        {item.judul}
                                                    </span>
                                                    <span className="mt-0.5 text-xs text-slate-400">
                                                        {item.deskripsi}
                                                    </span>
                                                </div>
                                            </TableCell>
                                            <TableCell className="px-6 text-right">
                                                {renderStatusBadge(item.status)}
                                            </TableCell>
                                        </TableRow>
                                    ))}
                                </TableBody>
                            </Table>
                        ) : (
                            <div className="flex flex-col items-center justify-center px-4 py-12 text-center">
                                <div className="mb-3 rounded-full border border-slate-100 bg-slate-50 p-3 text-slate-400">
                                    <Clock className="h-6 w-6" />
                                </div>
                                <h3 className="text-sm font-bold text-slate-700">
                                    Belum Ada Riwayat Aktivitas
                                </h3>
                                <p className="mt-1 max-w-xs text-xs text-slate-400">
                                    Seluruh log pengajuan urusan berkala dan
                                    cuti Anda akan otomatis terekam secara
                                    realtime di sini.
                                </p>
                            </div>
                        )}
                    </CardContent>
                </Card>
            </div>
        </>
    );
}

// ─── CONFIGURATION LAYOUT BOUNDARY (SIDEBAR NAVIGASI TETAP AKTIF) ───
PegawaiDashboard.layout = (page: React.ReactNode) => (
    <AppLayout
        breadcrumbs={[
            {
                title: 'Dashboard',
                href: '/dashboard',
            },
        ]}
    >
        <div className="max-w-full p-6 md:p-8">{page}</div>
    </AppLayout>
);
