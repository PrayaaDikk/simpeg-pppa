import React, { useState, useEffect } from 'react'; // Tambahkan useEffect
import { useForm, Head } from '@inertiajs/react';
import {
    FileText,
    Calendar,
    Clock,
    MapPin,
    Phone,
    ClipboardList,
    Send,
    Printer,
    ChevronRight,
} from 'lucide-react';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import InputError from '@/components/input-error';
import { printCutiPegawai } from '@/utils/print-cuti-pegawai';

interface Pangkat {
    nama_pangkat: string;
    golongan: string;
}

interface Jabatan {
    nama_jabatan: string;
}

interface Pegawai {
    id: number;
    nip: string;
    nama: string;
    masa_kerja?: string;
    pangkat?: Pangkat;
    jabatan?: Jabatan;
}

interface CutiRecord {
    id: number;
    pegawai_id: number;
    jenis_cuti: string;
    alasan_cuti: string;
    tanggal_mulai: string;
    tanggal_akhir: string;
    lama_cuti: number;
    alamat: string;
    no_telp: string;
    status_cuti: 'menunggu' | 'disetujui' | 'tidak disetujui';
    keputusan_atasan: string;
    created_at: string;
    pegawai: Pegawai;
    atasan?: { nama: string };
}

interface Props {
    pegawai: Pegawai;
    riwayatCuti: CutiRecord[];
}

export default function CutiPegawaiIndex({ pegawai, riwayatCuti }: Props) {
    const [activeTab, setActiveTab] = useState<'form' | 'riwayat'>('form');

    const { data, setData, post, processing, errors, reset, clearErrors } =
        useForm({
            jenis_cuti: '',
            alasan_cuti: '',
            tanggal_mulai: '',
            tanggal_akhir: '',
            lama_cuti: '',
            alamat: '',
            no_telp: '',
        });

    // Otomatisasi Perhitungan Lama Cuti Sisi Klien (Menghitung inklusif hari mulai dan akhir)
    useEffect(() => {
        if (data.tanggal_mulai && data.tanggal_akhir) {
            const mulai = new Date(data.tanggal_mulai);
            const akhir = new Date(data.tanggal_akhir);

            if (akhir >= mulai) {
                const selisihWaktu = akhir.getTime() - mulai.getTime();
                // Rumus: (Selisih Hari) + 1 agar hari pertama ikut terhitung
                const hitungHari =
                    Math.floor(selisihWaktu / (1000 * 60 * 60 * 24)) + 1;
                setData('lama_cuti', hitungHari.toString());
            } else {
                setData('lama_cuti', '');
            }
        } else {
            setData('lama_cuti', '');
        }
    }, [data.tanggal_mulai, data.tanggal_akhir]);

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/pegawai/cuti/store', {
            onSuccess: () => {
                reset();
                clearErrors();
                setActiveTab('riwayat');
            },
        });
    };

    const formatTanggalIndo = (dateString: string) => {
        if (!dateString) return '-';
        return new Date(dateString).toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
        });
    };

    return (
        <div className="min-h-screen bg-slate-50/60 p-6 font-inter md:p-10">
            <Head title="Manajemen Pengajuan Cuti Mandiri Pegawai" />

            <div className="mx-auto max-w-6xl space-y-8">
                {/* Atas Ringkasan Profil Singkat Pegawai */}
                <div className="flex flex-col gap-4 border-b border-slate-200/80 pb-6 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 className="font-plus-jakarta text-2xl font-bold tracking-tight text-slate-950 md:text-3xl">
                            Layanan Permohonan Cuti
                        </h1>
                        <p className="mt-1 text-sm text-slate-500">
                            Sistem Pengajuan Mandiri Formulir Permintaan dan
                            Pemberian Cuti Dinas PPPA Kota Kendari.
                        </p>
                    </div>
                    <div className="min-w-[280px] rounded-xl border border-slate-200/60 bg-white p-4 shadow-sm">
                        <div className="text-[11px] font-semibold tracking-wider text-slate-400 uppercase">
                            Pegawai Aktif
                        </div>
                        <div className="font-plus-jakarta mt-0.5 font-bold text-slate-900">
                            {pegawai.nama}
                        </div>
                        <div className="text-xs text-slate-500">
                            NIP. {pegawai.nip}
                        </div>
                        <div className="mt-1 text-xs text-slate-400">
                            {pegawai.jabatan?.nama_jabatan} | Gol.{' '}
                            {pegawai.pangkat?.golongan || '-'}
                        </div>
                    </div>
                </div>

                {/* Bilah Navigasi Tab */}
                <div className="flex border-b border-slate-200">
                    <button
                        onClick={() => setActiveTab('form')}
                        className={`flex items-center gap-2 border-b-2 px-6 py-3 text-sm font-semibold transition-all duration-200 ${
                            activeTab === 'form'
                                ? 'border-blue-600 text-blue-600'
                                : 'border-transparent text-slate-500 hover:text-slate-800'
                        }`}
                    >
                        <ClipboardList className="h-4 w-4" /> Formulir Pengajuan
                        Cuti
                    </button>
                    <button
                        onClick={() => setActiveTab('riwayat')}
                        className={`flex items-center gap-2 border-b-2 px-6 py-3 text-sm font-semibold transition-all duration-200 ${
                            activeTab === 'riwayat'
                                ? 'border-blue-600 text-blue-600'
                                : 'border-transparent text-slate-500 hover:text-slate-800'
                        }`}
                    >
                        <Clock className="h-4 w-4" /> Pantau Status Tracker (
                        {riwayatCuti.length})
                    </button>
                </div>

                {/* CONTAINER AREA: TAB FORMULIR PENGAJUAN */}
                {activeTab === 'form' && (
                    <div className="grid grid-cols-1 gap-8 lg:grid-cols-3">
                        <div className="rounded-xl border border-slate-200/60 bg-white p-6 shadow-sm lg:col-span-2">
                            <h2 className="font-plus-jakarta mb-4 flex items-center gap-2 text-lg font-bold text-slate-900">
                                <FileText className="h-5 w-5 text-blue-600" />{' '}
                                Rincian Pengisian Data Permohonan
                            </h2>

                            <form onSubmit={handleSubmit} className="space-y-5">
                                <div>
                                    <label className="text-xs font-bold tracking-wider text-slate-700 uppercase">
                                        Jenis Cuti Yang Diambil
                                    </label>
                                    <select
                                        value={data.jenis_cuti}
                                        onChange={(e) =>
                                            setData(
                                                'jenis_cuti',
                                                e.target.value,
                                            )
                                        }
                                        className="mt-1.5 h-11 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none"
                                    >
                                        <option value="">
                                            -- Pilih Salah Satu Kategori Jenis
                                            Cuti --
                                        </option>
                                        <option value="tahunan">
                                            Cuti Tahunan
                                        </option>
                                        <option value="besar">
                                            Cuti Besar
                                        </option>
                                        <option value="sakit">
                                            Cuti Sakit
                                        </option>
                                        <option value="melahirkan">
                                            Cuti Melahirkan
                                        </option>
                                        <option value="alasan penting">
                                            Cuti Karena Alasan Penting
                                        </option>
                                        <option value="diluar tanggungan negara">
                                            Cuti Di Luar Tanggungan Negara
                                        </option>
                                    </select>
                                    <InputError message={errors.jenis_cuti} />
                                </div>

                                <div>
                                    <label className="text-xs font-bold tracking-wider text-slate-700 uppercase">
                                        Alasan Lengkap Mengambil Cuti
                                    </label>
                                    <textarea
                                        rows={3}
                                        placeholder="Tuliskan alasan kontekstual pendukung pengajuan cuti secara deskriptif..."
                                        value={data.alasan_cuti}
                                        onChange={(e) =>
                                            setData(
                                                'alasan_cuti',
                                                e.target.value,
                                            )
                                        }
                                        className="mt-1.5 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none"
                                    />
                                    <InputError message={errors.alasan_cuti} />
                                </div>

                                <div className="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                    <div>
                                        <label className="text-xs font-bold tracking-wider text-slate-700 uppercase">
                                            Tanggal Mulai
                                        </label>
                                        <Input
                                            type="date"
                                            className="mt-1.5 h-11"
                                            value={data.tanggal_mulai}
                                            onChange={(e) =>
                                                setData(
                                                    'tanggal_mulai',
                                                    e.target.value,
                                                )
                                            }
                                        />
                                        <InputError
                                            message={errors.tanggal_mulai}
                                        />
                                    </div>
                                    <div>
                                        <label className="text-xs font-bold tracking-wider text-slate-700 uppercase">
                                            Tanggal Akhir
                                        </label>
                                        <Input
                                            type="date"
                                            className="mt-1.5 h-11"
                                            value={data.tanggal_akhir}
                                            onChange={(e) =>
                                                setData(
                                                    'tanggal_akhir',
                                                    e.target.value,
                                                )
                                            }
                                        />
                                        <InputError
                                            message={errors.tanggal_akhir}
                                        />
                                    </div>
                                    <div>
                                        <label className="text-xs font-bold tracking-wider text-slate-700 uppercase">
                                            Durasi (Hari Kerja)
                                        </label>
                                        <Input
                                            type="number"
                                            placeholder="Otomatis terhitung..."
                                            className="mt-1.5 h-11 cursor-not-allowed bg-slate-50 font-semibold text-slate-600"
                                            value={data.lama_cuti}
                                            readOnly // Dikunci agar tidak bisa dimanipulasi manual
                                        />
                                        <InputError
                                            message={errors.lama_cuti}
                                        />
                                    </div>
                                </div>

                                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <label className="flex items-center gap-1 text-xs font-bold tracking-wider text-slate-700 uppercase">
                                            <MapPin className="h-3.5 w-3.5" />{' '}
                                            Alamat Selama Menjalankan Cuti
                                        </label>
                                        <Input
                                            placeholder="Masukkan alamat domisili korespondensi selama cuti..."
                                            className="mt-1.5 h-11"
                                            value={data.alamat}
                                            onChange={(e) =>
                                                setData(
                                                    'alamat',
                                                    e.target.value,
                                                )
                                            }
                                        />
                                        <InputError message={errors.alamat} />
                                    </div>
                                    <div>
                                        <label className="flex items-center gap-1 text-xs font-bold tracking-wider text-slate-700 uppercase">
                                            <Phone className="h-3.5 w-3.5" />{' '}
                                            Nomor Telepon / WA Aktif
                                        </label>
                                        <Input
                                            placeholder="Contoh: 0821XXXXXXXX"
                                            className="mt-1.5 h-11"
                                            value={data.no_telp}
                                            onChange={(e) =>
                                                setData(
                                                    'no_telp',
                                                    e.target.value,
                                                )
                                            }
                                        />
                                        <InputError message={errors.no_telp} />
                                    </div>
                                </div>

                                <div className="flex justify-end border-t border-slate-100 pt-2">
                                    <Button
                                        type="submit"
                                        disabled={processing}
                                        className="h-12 min-w-[180px] rounded-xl bg-blue-600 font-semibold text-white hover:bg-blue-700"
                                    >
                                        <Send className="mr-2 h-4 w-4" /> Kirim
                                        Pengajuan Cuti
                                    </Button>
                                </div>
                            </form>
                        </div>

                        {/* Panel Samping */}
                        <div className="space-y-4">
                            <div className="rounded-xl border border-amber-200 bg-amber-50/50 p-5 text-sm text-amber-900">
                                <h3 className="font-plus-jakarta mb-1.5 flex items-center gap-1.5 font-bold text-amber-950">
                                    Ketentuan Penting BKN
                                </h3>
                                <p className="text-xs leading-relaxed text-amber-800">
                                    Berdasarkan Peraturan BKN Nomor 24 Tahun
                                    2017, seluruh berkas pengajuan yang terkirim
                                    akan melewati verifikasi atasan langsung
                                    sebelum disahkan oleh Kepala Dinas sebagai
                                    Pejabat yang Berwenang Memberikan Cuti.
                                </p>
                            </div>
                            <div className="rounded-xl border border-slate-200/60 bg-white p-5 shadow-sm">
                                <h3 className="font-plus-jakarta mb-3 text-sm font-bold text-slate-900">
                                    Panduan Pengisian
                                </h3>
                                <ul className="list-none space-y-2.5 pl-0 text-xs text-slate-500">
                                    <li className="flex items-start gap-2">
                                        <ChevronRight className="mt-0.5 h-3.5 w-3.5 shrink-0 text-blue-600" />{' '}
                                        Sistem menghitung durasi hari secara
                                        kalender penuh otomatis berdasarkan
                                        rentang yang dipilih.
                                    </li>
                                    <li className="flex items-start gap-2">
                                        <ChevronRight className="mt-0.5 h-3.5 w-3.5 shrink-0 text-blue-600" />{' '}
                                        Alamat domisili wajib ditulis lengkap
                                        demi kelancaran korespondensi dinas
                                        darurat.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                )}

                {/* CONTAINER AREA: TAB PANTAU STATUS / TRACKING LEDGER */}
                {activeTab === 'riwayat' && (
                    <div className="overflow-hidden rounded-xl border border-slate-200/60 bg-white shadow-sm">
                        <Table>
                            <TableHeader className="bg-slate-50/70">
                                <TableRow className="border-b border-slate-200/60">
                                    <TableHead className="h-14 font-semibold text-slate-900">
                                        Kategori Cuti
                                    </TableHead>
                                    <TableHead className="h-14 font-semibold text-slate-900">
                                        Alasan Permohonan
                                    </TableHead>
                                    <TableHead className="h-14 font-semibold text-slate-900">
                                        Rentang Pelaksanaan
                                    </TableHead>
                                    <TableHead className="h-14 text-center font-semibold text-slate-900">
                                        Durasi
                                    </TableHead>
                                    <TableHead className="h-14 text-center font-semibold text-slate-900">
                                        Status Kelayakan
                                    </TableHead>
                                    <TableHead className="h-14 text-right font-semibold text-slate-900">
                                        Aksi Cetak
                                    </TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {riwayatCuti.length === 0 ? (
                                    <TableRow>
                                        <TableCell
                                            colSpan={6}
                                            className="h-36 text-center text-sm text-slate-400"
                                        >
                                            Belum terdapat rekam jejak pengajuan
                                            berkas permohonan cuti dalam sistem
                                            SIMPEG.
                                        </TableCell>
                                    </TableRow>
                                ) : (
                                    riwayatCuti.map((cuti) => (
                                        <TableRow
                                            key={cuti.id}
                                            className="border-b border-slate-100 hover:bg-slate-50/30"
                                        >
                                            <TableCell className="py-4 font-semibold text-slate-900 capitalize">
                                                {cuti.jenis_cuti}
                                            </TableCell>
                                            <TableCell className="max-w-xs truncate py-4 text-sm text-slate-600">
                                                {cuti.alasan_cuti}
                                            </TableCell>
                                            <TableCell className="py-4 text-xs text-slate-500">
                                                <div className="font-medium text-slate-800">
                                                    {formatTanggalIndo(
                                                        cuti.tanggal_mulai,
                                                    )}
                                                </div>
                                                <div className="mt-0.5 text-[10px] text-slate-400">
                                                    s/d{' '}
                                                    {formatTanggalIndo(
                                                        cuti.tanggal_akhir,
                                                    )}
                                                </div>
                                            </TableCell>
                                            <TableCell className="py-4 text-center text-sm font-semibold text-slate-900">
                                                {cuti.lama_cuti} Hari
                                            </TableCell>
                                            <TableCell className="py-4 text-center">
                                                {cuti.status_cuti ===
                                                    'menunggu' && (
                                                    <span className="inline-flex items-center rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                                        Menunggu
                                                    </span>
                                                )}
                                                {cuti.status_cuti ===
                                                    'disetujui' && (
                                                    <span className="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                                        Disetujui
                                                    </span>
                                                )}
                                                {cuti.status_cuti ===
                                                    'tidak disetujui' && (
                                                    <span className="inline-flex items-center rounded-full border border-rose-200 bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700">
                                                        Ditolak / Ditangguhkan
                                                    </span>
                                                )}
                                            </TableCell>
                                            <TableCell className="py-4 text-right">
                                                {cuti.status_cuti ===
                                                'disetujui' ? (
                                                    <Button
                                                        onClick={() =>
                                                            printCutiPegawai(
                                                                cuti,
                                                            )
                                                        }
                                                        size="sm"
                                                        className="h-9 rounded-lg bg-blue-600 text-xs font-semibold text-white hover:bg-blue-700"
                                                    >
                                                        <Printer className="mr-1.5 h-3.5 w-3.5" />{' '}
                                                        Cetak Formulir Cuti
                                                    </Button>
                                                ) : (
                                                    <Button
                                                        disabled
                                                        size="sm"
                                                        className="h-9 cursor-not-allowed rounded-lg border border-slate-200 bg-slate-100 text-xs font-medium text-slate-400"
                                                    >
                                                        Aksi Terkunci
                                                    </Button>
                                                )}
                                            </TableCell>
                                        </TableRow>
                                    ))
                                )}
                            </TableBody>
                        </Table>
                    </div>
                )}
            </div>
        </div>
    );
}
