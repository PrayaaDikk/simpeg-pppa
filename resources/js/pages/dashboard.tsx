import React, { useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import {
    Card,
    CardHeader,
    CardTitle,
    CardDescription,
    CardContent,
} from '@/components/ui/card';
import {
    Table,
    TableHeader,
    TableBody,
    TableHead,
    TableRow,
    TableCell,
} from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import {
    Users,
    FileText,
    Shield,
    Eye,
    Calendar,
    ArrowUpRight,
    LayoutGrid,
    Briefcase,
    Activity,
    GraduationCap,
} from 'lucide-react';
import {
    ResponsiveContainer,
    BarChart,
    Bar,
    PieChart,
    Pie,
    Cell,
    XAxis,
    YAxis,
    Tooltip,
    CartesianGrid,
    Legend,
} from 'recharts';
import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';

interface DashboardProps {
    totalPegawai: number;
    cutiMenunggu: number;
    kgbUpdate: number;
    chartData: Array<{ month: string; count: number }>;
    distribusiBidang: Array<{
        nama_bidang: string;
        akronim: string;
        jumlah: number;
    }>;
    distribusiGender: Array<{ name: string; value: number }>;
    distribusiPendidikan: Array<{ pendidikan: string; jumlah: number }>;
    recentValidations: Array<{
        id: number;
        pegawai: {
            nama: string;
            nip: string;
            initials: string;
            pendidikan_terakhir: string;
        };
        jenis: string;
        tanggal: string;
        status: string;
        tipe: 'CUTI' | 'KGB';
    }>;
}

const GENDER_COLORS = ['#0F172A', '#3B82F6'];
const AMBIENT_BAR_COLOR = '#3B82F6';
const EDUCATION_BAR_COLOR = '#0F172A';

export default function Dashboard({
    totalPegawai,
    cutiMenunggu,
    kgbUpdate,
    chartData,
    distribusiBidang = [],
    distribusiGender = [],
    distribusiPendidikan = [],
    recentValidations = [],
}: DashboardProps) {
    const [activeTab, setActiveTab] = useState<
        'bidang' | 'gender' | 'pendidikan'
    >('bidang');

    return (
        <>
            <Head title="Dashboard Admin" />

            <div className="space-y-6 p-6">
                {/* HEADER */}
                <div className="flex flex-col gap-2">
                    <h1 className="text-3xl font-bold tracking-tight text-slate-900">
                        Sistem Informasi Kepegawaian (SIMPEG)
                    </h1>
                    <p className="text-sm text-slate-500">
                        Selamat datang kembali. Berikut adalah ringkasan
                        analitik data kepegawaian hari ini.
                    </p>
                </div>

                {/* METRICS */}
                <div className="grid gap-4 md:grid-cols-3">
                    <Card className="border-slate-200/80 shadow-sm">
                        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle className="text-sm font-medium text-slate-600">
                                Total Pegawai Aktif
                            </CardTitle>
                            <div className="rounded-md bg-slate-100 p-2 text-slate-700">
                                <Users className="size-4" />
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-bold text-slate-900">
                                {totalPegawai}
                            </div>
                            <p className="text-xs text-slate-500">
                                Pegawai terdata aktif di sistem
                            </p>
                        </CardContent>
                    </Card>

                    <Card className="border-slate-200/80 shadow-sm">
                        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle className="text-sm font-medium text-slate-600">
                                Cuti Menunggu Persetujuan
                            </CardTitle>
                            <div className="rounded-md bg-amber-50 p-2 text-amber-600">
                                <FileText className="size-4" />
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-bold text-amber-600">
                                {cutiMenunggu}
                            </div>
                            <p className="text-xs text-slate-500">
                                Permohonan izin memerlukan verifikasi
                            </p>
                        </CardContent>
                    </Card>

                    <Card className="border-slate-200/80 shadow-sm">
                        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle className="text-sm font-medium text-slate-600">
                                Pembaruan KGB Terjadwal
                            </CardTitle>
                            <div className="rounded-md bg-blue-50 p-2 text-blue-600">
                                <Activity className="size-4" />
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-bold text-blue-600">
                                {kgbUpdate}
                            </div>
                            <p className="text-xs text-slate-500">
                                Kenaikan Gaji Berkala bulan ini
                            </p>
                        </CardContent>
                    </Card>
                </div>

                {/* CHARTS GRAPH SECTION */}
                <div className="grid gap-6 lg:grid-cols-3">
                    {/* BAR CHART TREN */}
                    <Card className="border-slate-200/80 shadow-sm lg:col-span-2">
                        <CardHeader>
                            <div className="flex items-center gap-2">
                                <LayoutGrid className="size-4 text-blue-600" />
                                <CardTitle className="text-lg font-bold text-slate-900">
                                    Tren Pertumbuhan Pegawai (2026)
                                </CardTitle>
                            </div>
                            <CardDescription>
                                Visualisasi akumulasi data pegawai baru yang
                                terdaftar per bulan
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div className="h-[320px] w-full">
                                <ResponsiveContainer width="100%" height="100%">
                                    <BarChart
                                        data={chartData}
                                        margin={{
                                            top: 10,
                                            right: 10,
                                            left: -20,
                                            bottom: 0,
                                        }}
                                    >
                                        <CartesianGrid
                                            strokeDasharray="3 3"
                                            vertical={false}
                                            stroke="#F1F5F9"
                                        />
                                        <XAxis
                                            dataKey="month"
                                            stroke="#94A3B8"
                                            fontSize={12}
                                            tickLine={false}
                                        />
                                        <YAxis
                                            stroke="#94A3B8"
                                            fontSize={12}
                                            tickLine={false}
                                            axisLine={false}
                                        />
                                        <Tooltip
                                            contentStyle={{
                                                backgroundColor: '#ffffff',
                                                borderRadius: '8px',
                                                border: '1px solid #E2E8F0',
                                            }}
                                            labelStyle={{
                                                fontWeight: 'bold',
                                                color: '#0F172A',
                                            }}
                                        />
                                        <Bar
                                            dataKey="count"
                                            fill={AMBIENT_BAR_COLOR}
                                            radius={[4, 4, 0, 0]}
                                            name="Jumlah Pegawai Baru"
                                        />
                                    </BarChart>
                                </ResponsiveContainer>
                            </div>
                        </CardContent>
                    </Card>

                    {/* INTERFACE TABBED DEMOGRAFI */}
                    <Card className="flex flex-col justify-between border-slate-200/80 shadow-sm">
                        <CardHeader className="pb-3">
                            <div className="flex items-center gap-2">
                                <Shield className="size-4 text-blue-600" />
                                <CardTitle className="text-lg font-bold text-slate-900">
                                    Analisis Demografi
                                </CardTitle>
                            </div>
                            <CardDescription>
                                Distribusi pegawai berdasarkan variabel kluster
                                internal
                            </CardDescription>

                            <div className="mt-4 flex rounded-lg border border-slate-200/50 bg-slate-100 p-1">
                                <button
                                    onClick={() => setActiveTab('bidang')}
                                    className={`flex flex-1 items-center justify-center gap-1.5 rounded-md py-1.5 text-xs font-semibold transition-all duration-200 ${
                                        activeTab === 'bidang'
                                            ? 'bg-white text-slate-900 shadow-sm'
                                            : 'text-slate-500 hover:bg-slate-50/50 hover:text-slate-900'
                                    }`}
                                >
                                    <Briefcase className="size-3.5" />
                                    Bidang
                                </button>
                                <button
                                    onClick={() => setActiveTab('gender')}
                                    className={`flex flex-1 items-center justify-center gap-1.5 rounded-md py-1.5 text-xs font-semibold transition-all duration-200 ${
                                        activeTab === 'gender'
                                            ? 'bg-white text-slate-900 shadow-sm'
                                            : 'text-slate-500 hover:bg-slate-50/50 hover:text-slate-900'
                                    }`}
                                >
                                    <Users className="size-3.5" />
                                    Gender
                                </button>
                                <button
                                    onClick={() => setActiveTab('pendidikan')}
                                    className={`flex flex-1 items-center justify-center gap-1.5 rounded-md py-1.5 text-xs font-semibold transition-all duration-200 ${
                                        activeTab === 'pendidikan'
                                            ? 'bg-white text-slate-900 shadow-sm'
                                            : 'text-slate-500 hover:bg-slate-50/50 hover:text-slate-900'
                                    }`}
                                >
                                    <GraduationCap className="size-3.5" />
                                    Pendidikan
                                </button>
                            </div>
                        </CardHeader>

                        <CardContent className="flex flex-1 flex-col justify-center pb-6">
                            <div className="flex h-[250px] w-full items-center justify-center">
                                {activeTab === 'bidang' &&
                                    (distribusiBidang.length > 0 ? (
                                        <ResponsiveContainer
                                            width="100%"
                                            height="100%"
                                        >
                                            <BarChart
                                                data={distribusiBidang}
                                                layout="vertical" // <--- Menentukan chart horizontal
                                                margin={{
                                                    top: 10,
                                                    right: 30, // Ditambah agar angka di ujung bar tidak terpotong text
                                                    left: 10, // Diubah dari -10 ke nilai positif agar label akronim tidak keluar area
                                                    bottom: 10,
                                                }}
                                            >
                                                <CartesianGrid
                                                    strokeDasharray="3 3"
                                                    horizontal={false} // Hanya grid vertikal yang muncul
                                                    stroke="#F1F5F9"
                                                />

                                                {/* Sumbu X bertindak sebagai penampung NILAI ANGKA */}
                                                <XAxis
                                                    type="number"
                                                    stroke="#94A3B8"
                                                    fontSize={11}
                                                    tickLine={false}
                                                />

                                                {/* Sumbu Y bertindak sebagai KATEGORI TEXT */}
                                                <YAxis
                                                    dataKey="akronim" // <--- Menggunakan akronim dari backend
                                                    type="category" // <--- Wajib dipastikan tipenya category
                                                    stroke="#94A3B8"
                                                    fontSize={11}
                                                    width={55} // <--- Lebar area teks disesuaikan dengan panjang akronim
                                                    tickLine={false}
                                                />

                                                <Tooltip
                                                    contentStyle={{
                                                        backgroundColor:
                                                            '#ffffff',
                                                        borderRadius: '8px',
                                                        border: '1px solid #E2E8F0',
                                                    }}
                                                    // KUNCI UX: Menampilkan Nama Bidang Lengkap saat kursor mengarah ke akronim
                                                    labelFormatter={(value) => {
                                                        const item =
                                                            distribusiBidang.find(
                                                                (d) =>
                                                                    d.akronim ===
                                                                    value,
                                                            );
                                                        return item
                                                            ? item.nama_bidang
                                                            : value;
                                                    }}
                                                />

                                                <Bar
                                                    dataKey="jumlah" // <--- Mengambil key jumlah (integer)
                                                    fill={AMBIENT_BAR_COLOR}
                                                    radius={[0, 4, 4, 0]} // Sudut membulat di sisi kanan bar saja
                                                    name="Total Pegawai"
                                                />
                                            </BarChart>
                                        </ResponsiveContainer>
                                    ) : (
                                        <p className="text-sm text-slate-400">
                                            Tidak ada data bidang tersedia
                                        </p>
                                    ))}

                                {activeTab === 'gender' &&
                                    (distribusiGender.length > 0 ? (
                                        <ResponsiveContainer
                                            width="100%"
                                            height="100%"
                                        >
                                            <PieChart>
                                                <Pie
                                                    data={distribusiGender}
                                                    dataKey="value"
                                                    nameKey="name"
                                                    cx="50%"
                                                    cy="45%"
                                                    innerRadius={55}
                                                    outerRadius={85}
                                                    paddingAngle={4}
                                                >
                                                    {distribusiGender.map(
                                                        (entry, index) => (
                                                            <Cell
                                                                key={`cell-${index}`}
                                                                fill={
                                                                    GENDER_COLORS[
                                                                        index %
                                                                            GENDER_COLORS.length
                                                                    ]
                                                                }
                                                            />
                                                        ),
                                                    )}
                                                </Pie>
                                                <Tooltip
                                                    contentStyle={{
                                                        backgroundColor:
                                                            '#ffffff',
                                                        borderRadius: '8px',
                                                    }}
                                                />
                                                <Legend
                                                    iconSize={10}
                                                    iconType="circle"
                                                    wrapperStyle={{
                                                        bottom: 0,
                                                        fontSize: '12px',
                                                    }}
                                                />
                                            </PieChart>
                                        </ResponsiveContainer>
                                    ) : (
                                        <p className="text-sm text-slate-400">
                                            Tidak ada data jenis kelamin
                                            tersedia
                                        </p>
                                    ))}

                                {activeTab === 'pendidikan' &&
                                    (distribusiPendidikan.length > 0 ? (
                                        <ResponsiveContainer
                                            width="100%"
                                            height="100%"
                                        >
                                            <BarChart
                                                data={distribusiPendidikan}
                                                margin={{
                                                    top: 10,
                                                    right: 10,
                                                    left: -25,
                                                    bottom: 10,
                                                }}
                                            >
                                                <CartesianGrid
                                                    strokeDasharray="3 3"
                                                    vertical={false}
                                                    stroke="#F1F5F9"
                                                />
                                                {/* 1. SINKRONISASI XAXIS MENGGUNAKAN TICKFORMATTER */}
                                                <XAxis
                                                    dataKey="pendidikan"
                                                    stroke="#94A3B8"
                                                    fontSize={11}
                                                    tickLine={false}
                                                    tickFormatter={(value) => {
                                                        const labelMap: Record<
                                                            string,
                                                            string
                                                        > = {
                                                            '1': 'SMA',
                                                            '2': 'D1',
                                                            '3': 'D2',
                                                            '4': 'D3',
                                                            '5': 'D4',
                                                            '6': 'S1',
                                                            '7': 'S2',
                                                            '8': 'S3',
                                                        };
                                                        return (
                                                            labelMap[value] ||
                                                            value
                                                        );
                                                    }}
                                                />
                                                <YAxis
                                                    stroke="#94A3B8"
                                                    fontSize={11}
                                                    tickLine={false}
                                                    axisLine={false}
                                                />
                                                <Tooltip
                                                    contentStyle={{
                                                        backgroundColor:
                                                            '#ffffff',
                                                        borderRadius: '8px',
                                                    }}
                                                    // 2. SINKRONISASI TOOLTIP AGAR SAAT DIHOVER JUGA MUNCUL TEKS (BUKAN ANGKA)
                                                    labelFormatter={(value) => {
                                                        const labelMap: Record<
                                                            string,
                                                            string
                                                        > = {
                                                            '1': 'SMA',
                                                            '2': 'D1',
                                                            '3': 'D2',
                                                            '4': 'D3',
                                                            '5': 'D4',
                                                            '6': 'S1',
                                                            '7': 'S2',
                                                            '8': 'S3',
                                                        };
                                                        return (
                                                            labelMap[value] ||
                                                            value
                                                        );
                                                    }}
                                                />
                                                <Bar
                                                    dataKey="jumlah"
                                                    fill={EDUCATION_BAR_COLOR}
                                                    radius={[4, 4, 0, 0]}
                                                    name="Jumlah Pegawai"
                                                />
                                            </BarChart>
                                        </ResponsiveContainer>
                                    ) : (
                                        <p className="text-sm text-slate-400">
                                            Tidak ada data riwayat pendidikan
                                            tertinggi
                                        </p>
                                    ))}
                            </div>
                        </CardContent>
                    </Card>
                </div>

                {/* DATA VALIDASI RECENT ACTION */}
                <div className="grid gap-6 md:grid-cols-3">
                    <Card className="border-slate-200/80 shadow-sm md:col-span-2">
                        <CardHeader>
                            <CardTitle className="text-lg font-bold text-slate-900">
                                Validasi Tindakan Kepegawaian Terbaru
                            </CardTitle>
                            <CardDescription>
                                Daftar permohonan Cuti atau KGB yang membutuhkan
                                verifikasi administrasi
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <Table>
                                <TableHeader>
                                    <TableRow className="border-slate-100 hover:bg-transparent">
                                        <TableHead className="text-slate-600">
                                            Pegawai
                                        </TableHead>
                                        <TableHead className="text-slate-600">
                                            Jenis Berkas
                                        </TableHead>
                                        <TableHead className="text-slate-600">
                                            Masa Berlaku / TMT
                                        </TableHead>
                                        <TableHead className="text-slate-600">
                                            Status
                                        </TableHead>
                                        <TableHead className="text-right text-slate-600">
                                            Aksi
                                        </TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {recentValidations.length === 0 ? (
                                        <TableRow>
                                            <TableCell
                                                colSpan={5}
                                                className="h-24 text-center text-sm text-slate-400"
                                            >
                                                Tidak ada berkas yang menunggu
                                                tindakan saat ini.
                                            </TableCell>
                                        </TableRow>
                                    ) : (
                                        recentValidations.map((item) => (
                                            <TableRow
                                                key={`${item.tipe}-${item.id}`}
                                                className="border-slate-100 transition-colors hover:bg-slate-50/50"
                                            >
                                                <TableCell className="py-3">
                                                    <div className="flex items-center gap-3">
                                                        <Avatar className="size-9 border border-slate-800 bg-slate-900">
                                                            <AvatarFallback className="bg-slate-900 text-xs font-bold text-white">
                                                                {
                                                                    item.pegawai
                                                                        .initials
                                                                }
                                                            </AvatarFallback>
                                                        </Avatar>
                                                        <div className="flex flex-col">
                                                            <span className="text-sm leading-none font-semibold text-slate-900">
                                                                {
                                                                    item.pegawai
                                                                        .nama
                                                                }
                                                            </span>
                                                            <span className="mt-1 text-xs text-slate-500">
                                                                NIP:{' '}
                                                                {
                                                                    item.pegawai
                                                                        .nip
                                                                }{' '}
                                                                • Pendidikan:{' '}
                                                                {
                                                                    item.pegawai
                                                                        .pendidikan_terakhir
                                                                }
                                                            </span>
                                                        </div>
                                                    </div>
                                                </TableCell>
                                                <TableCell className="py-3">
                                                    <span className="text-sm font-medium text-slate-800">
                                                        {item.jenis}
                                                    </span>
                                                </TableCell>
                                                <TableCell className="py-3">
                                                    <div className="flex items-center gap-1.5 text-xs font-medium text-slate-600">
                                                        <Calendar className="size-3.5 text-slate-400" />
                                                        {item.tanggal}
                                                    </div>
                                                </TableCell>
                                                <TableCell className="py-3">
                                                    <Badge
                                                        className={`text-2xs rounded-full border-none px-2.5 py-0.5 font-bold tracking-wider transition-colors ${
                                                            item.tipe === 'CUTI'
                                                                ? 'bg-amber-100 text-amber-800'
                                                                : 'bg-blue-100 text-blue-800'
                                                        }`}
                                                    >
                                                        {item.status}
                                                    </Badge>
                                                </TableCell>
                                                <TableCell className="py-3 text-right">
                                                    <Link
                                                        href={
                                                            item.tipe === 'CUTI'
                                                                ? '/admin/cuti'
                                                                : '/admin/kgb'
                                                        }
                                                    >
                                                        <Button
                                                            variant="outline"
                                                            size="sm"
                                                            className="h-8 gap-1.5 border-slate-200 text-xs font-medium text-slate-700 hover:bg-slate-50"
                                                        >
                                                            <Eye className="size-3.5" />
                                                            Periksa
                                                        </Button>
                                                    </Link>
                                                </TableCell>
                                            </TableRow>
                                        ))
                                    )}
                                </TableBody>
                            </Table>
                        </CardContent>
                    </Card>

                    {/* SHORTCUTS */}
                    <Card className="border-slate-200/80 shadow-sm">
                        <CardHeader>
                            <CardTitle className="text-lg font-bold text-slate-900">
                                Akses Navigasi Cepat
                            </CardTitle>
                            <CardDescription>
                                Pintasan langsung ke modul kontrol utama
                                administrasi
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="grid gap-3">
                            <Link
                                href="/admin/pegawai"
                                className="group flex items-center justify-between rounded-xl border border-slate-100 p-3 shadow-2xs transition-all duration-200 hover:bg-slate-50"
                            >
                                <div className="flex items-center gap-3">
                                    <div className="rounded-lg bg-slate-900 p-2 text-white shadow-xs">
                                        <Users className="size-5" />
                                    </div>
                                    <div className="flex flex-col">
                                        <span className="text-sm font-semibold text-slate-800">
                                            Manajemen Pegawai
                                        </span>
                                        <span className="text-xs text-slate-500">
                                            Kelola master profil biodata resmi
                                        </span>
                                    </div>
                                </div>
                                <Button
                                    variant="ghost"
                                    size="icon"
                                    className="size-8 opacity-0 transition-opacity group-hover:opacity-100"
                                >
                                    <ArrowUpRight className="size-4 text-slate-500" />
                                </Button>
                            </Link>

                            <Link
                                href="/admin/cuti"
                                className="group flex items-center justify-between rounded-xl border border-slate-100 p-3 shadow-2xs transition-all duration-200 hover:bg-slate-50"
                            >
                                <div className="flex items-center gap-3">
                                    <div className="rounded-lg bg-slate-900 p-2 text-white shadow-xs">
                                        <Calendar className="size-5" />
                                    </div>
                                    <div className="flex flex-col">
                                        <span className="text-sm font-semibold text-slate-800">
                                            Izin Cuti
                                        </span>
                                        <span className="text-xs text-slate-500">
                                            Administrasi formulir persetujuan
                                            cuti
                                        </span>
                                    </div>
                                </div>
                                <Button
                                    variant="ghost"
                                    size="icon"
                                    className="size-8 opacity-0 transition-opacity group-hover:opacity-100"
                                >
                                    <ArrowUpRight className="size-4 text-slate-500" />
                                </Button>
                            </Link>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </>
    );
}

Dashboard.layout = (page: React.ReactNode) => (
    <AppLayout
        breadcrumbs={[
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ]}
    >
        {page}
    </AppLayout>
);
