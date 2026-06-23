import { Head, useForm, usePage } from '@inertiajs/react';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/layout';
import type { Auth } from '@/types';
import { useEffect, useState } from 'react';

type PageProps = {
    auth: Auth & {
        user: {
            id: number;
            name: string;
            email: string;
            pegawai?: {
                id: number;
                nip: string;
                tempat_lahir: string;
                tanggal_lahir: string;
                jenis_kelamin: 'l' | 'p';
                agama: string;
                kode_pos: string;
                no_telp: string;
                alamat: string;
                status_kawin: 'belum kawin' | 'kawin' | 'janda' | 'duda';
                karpeg: string | null;
                nama_pasangan: string | null;
                status_kerja_pasangan: string | null;
                jumlah_anak: number;
            } | null;
        };
    };
};

export default function Profile({ status }: { status?: string }) {
    const { auth } = usePage<PageProps>().props;
    const pegawai = auth.user.pegawai;

    // State untuk mengantisipasi Hydration Mismatch pada komponen Select Primitives
    const [isMounted, setIsMounted] = useState(false);

    useEffect(() => {
        setIsMounted(true);
    }, []);

    // Menginisialisasi React Form Hook State Unlocking dengan Tipe Aman (Type Safe)
    const { data, setData, patch, processing, errors } = useForm({
        name: auth.user.name || '',
        email: auth.user.email || '',

        // Membuka kuncian properti NIP agar reaktif terhadap pembaruan user
        nip: pegawai?.nip || '',

        tempat_lahir: pegawai?.tempat_lahir || '',
        // Format ISO String dipotong agar kompatibel dengan standard HTML5 input element "yyyy-MM-dd"
        tanggal_lahir: pegawai?.tanggal_lahir
            ? pegawai.tanggal_lahir.split('T')[0]
            : '',
        jenis_kelamin: pegawai?.jenis_kelamin || 'l',
        agama: pegawai?.agama || '',
        kode_pos: pegawai?.kode_pos || '',
        no_telp: pegawai?.no_telp || '',
        alamat: pegawai?.alamat || '',
        status_kawin: pegawai?.status_kawin || 'belum kawin',
        karpeg: pegawai?.karpeg || '',
        nama_pasangan: pegawai?.nama_pasangan || '',
        status_kerja_pasangan: pegawai?.status_kerja_pasangan || '',
        jumlah_anak: pegawai?.jumlah_anak ?? 0,
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        // Mengirimkan request PATCH menuju named route profile.update via router bridge
        patch('/pengaturan/profil', {
            preserveScroll: true,
        });
    };

    return (
        <>
            <Head title="Pengaturan Profil" />

            <h1 className="sr-only">Pengaturan Profil</h1>

            <div className="w-full space-y-8" suppressHydrationWarning>
                <Heading
                    variant="small"
                    title="Profil Saya"
                    description="Perbarui informasi data personal, nomor induk, serta pengaturan domisili kedinasan Anda."
                />

                <form onSubmit={handleSubmit} className="space-y-8">
                    {/* BAGIAN I: UTAMA & KREDENSIAL */}
                    <div className="space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 className="border-b border-slate-100 pb-3 text-lg font-bold text-slate-900">
                            Kredensial Akun & Identitas Utama
                        </h2>

                        <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div className="space-y-2">
                                <Label htmlFor="name">Nama Lengkap</Label>
                                <Input
                                    id="name"
                                    className="h-11 border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                    value={data.name}
                                    onChange={(e) =>
                                        setData('name', e.target.value)
                                    }
                                    required
                                    autoComplete="name"
                                    placeholder="Masukkan nama lengkap beserta gelar"
                                />
                                <InputError message={errors.name} />
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="email">Alamat Email</Label>
                                <Input
                                    id="email"
                                    type="email"
                                    className="h-11 border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                    value={data.email}
                                    onChange={(e) =>
                                        setData('email', e.target.value)
                                    }
                                    required
                                    autoComplete="username"
                                    placeholder="contoh@instansi.go.id"
                                />
                                <InputError message={errors.email} />
                            </div>

                            {/* UI FIELD INTERACTIVITY UNLOCKED - NOMOR INDUK PEGAWAI (NIP) */}
                            <div className="space-y-2">
                                <Label htmlFor="nip">
                                    Nomor Induk Pegawai (NIP)
                                </Label>
                                <Input
                                    id="nip"
                                    type="text"
                                    className="h-11 border-slate-200 font-mono tracking-wider focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                    value={data.nip}
                                    onChange={(e) =>
                                        setData('nip', e.target.value)
                                    }
                                    required
                                    maxLength={20}
                                    placeholder="Masukkan 18 digit NIP resmi"
                                />
                                <InputError message={errors.nip} />
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="karpeg">
                                    Nomor Kartu Pegawai (KARPEG)
                                </Label>
                                <Input
                                    id="karpeg"
                                    className="h-11 border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                    value={data.karpeg}
                                    onChange={(e) =>
                                        setData('karpeg', e.target.value)
                                    }
                                    placeholder="Contoh: N. 123456"
                                />
                                <InputError message={errors.karpeg} />
                            </div>
                        </div>
                    </div>

                    {/* BAGIAN II: DATA KELAHIRAN & PERSONAL */}
                    <div className="space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 className="border-b border-slate-100 pb-3 text-lg font-bold text-slate-900">
                            Informasi Kelahiran & Personal
                        </h2>

                        <div className="grid grid-cols-1 gap-6 md:grid-cols-3">
                            <div className="space-y-2">
                                <Label htmlFor="tempat_lahir">
                                    Tempat Lahir
                                </Label>
                                <Input
                                    id="tempat_lahir"
                                    className="h-11 border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                    value={data.tempat_lahir}
                                    onChange={(e) =>
                                        setData('tempat_lahir', e.target.value)
                                    }
                                    required
                                    placeholder="Kota / Kabupaten"
                                />
                                <InputError message={errors.tempat_lahir} />
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="tanggal_lahir">
                                    Tanggal Lahir
                                </Label>
                                <Input
                                    id="tanggal_lahir"
                                    type="date"
                                    className="h-11 border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                    value={data.tanggal_lahir}
                                    onChange={(e) =>
                                        setData('tanggal_lahir', e.target.value)
                                    }
                                    required
                                />
                                <InputError message={errors.tanggal_lahir} />
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="jenis_kelamin">
                                    Jenis Kelamin
                                </Label>
                                {isMounted ? (
                                    <Select
                                        value={data.jenis_kelamin}
                                        onValueChange={(val) =>
                                            setData(
                                                'jenis_kelamin',
                                                val as 'l' | 'p',
                                            )
                                        }
                                    >
                                        <SelectTrigger
                                            id="jenis_kelamin"
                                            className="h-11 border-slate-200"
                                        >
                                            <SelectValue placeholder="Pilih jenis kelamin" />
                                        </SelectTrigger>
                                        <SelectContent className="bg-white">
                                            <SelectItem value="l">
                                                Laki-Laki
                                            </SelectItem>
                                            <SelectItem value="p">
                                                Perempuan
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                ) : (
                                    <div className="h-11 w-full rounded-md border border-slate-200 bg-slate-50" />
                                )}
                                <InputError message={errors.jenis_kelamin} />
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="agama">Agama</Label>
                                {isMounted ? (
                                    <Select
                                        value={data.agama}
                                        onValueChange={(val) =>
                                            setData('agama', val)
                                        }
                                    >
                                        <SelectTrigger
                                            id="agama"
                                            className="h-11 border-slate-200"
                                        >
                                            <SelectValue placeholder="Pilih Agama" />
                                        </SelectTrigger>
                                        <SelectContent className="bg-white">
                                            <SelectItem value="islam">
                                                Islam
                                            </SelectItem>
                                            <SelectItem value="kristen">
                                                Kristen
                                            </SelectItem>
                                            <SelectItem value="katolik">
                                                Katolik
                                            </SelectItem>
                                            <SelectItem value="hindu">
                                                Hindu
                                            </SelectItem>
                                            <SelectItem value="buddha">
                                                Budha
                                            </SelectItem>
                                            <SelectItem value="konghucu">
                                                Konghucu
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                ) : (
                                    <div className="h-11 w-full rounded-md border border-slate-200 bg-slate-50" />
                                )}
                                <InputError message={errors.agama} />
                            </div>

                            <div className="space-y-2 md:grid-cols-1">
                                <Label htmlFor="no_telp">
                                    Nomor Telepon / WA
                                </Label>
                                <Input
                                    id="no_telp"
                                    type="tel"
                                    className="h-11 border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                    value={data.no_telp}
                                    onChange={(e) =>
                                        setData('no_telp', e.target.value)
                                    }
                                    required
                                    placeholder="08xxxxxxxxxx"
                                />
                                <InputError message={errors.no_telp} />
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="kode_pos">Kode Pos</Label>
                                <Input
                                    id="kode_pos"
                                    className="h-11 border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                    value={data.kode_pos}
                                    onChange={(e) =>
                                        setData('kode_pos', e.target.value)
                                    }
                                    required
                                    placeholder="Contoh: 93121"
                                />
                                <InputError message={errors.kode_pos} />
                            </div>
                        </div>

                        <div className="space-y-2">
                            <Label htmlFor="alamat">
                                Alamat Lengkap Domisili
                            </Label>
                            <Input
                                id="alamat"
                                className="h-11 border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                value={data.alamat}
                                onChange={(e) =>
                                    setData('alamat', e.target.value)
                                }
                                required
                                placeholder="Nama jalan, RT/RW, lingkungan, kelurahan, dan kecamatan"
                            />
                            <InputError message={errors.alamat} />
                        </div>
                    </div>

                    {/* BAGIAN III: HUBUNGAN KELUARGA */}
                    <div className="space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 className="border-b border-slate-100 pb-3 text-lg font-bold text-slate-900">
                            Status Pernikahan & Keluarga
                        </h2>

                        <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div className="space-y-2">
                                <Label htmlFor="status_kawin">
                                    Status Perkawinan
                                </Label>
                                {isMounted ? (
                                    <Select
                                        value={data.status_kawin}
                                        onValueChange={(val) =>
                                            setData(
                                                'status_kawin',
                                                val as
                                                    | 'belum kawin'
                                                    | 'kawin'
                                                    | 'cerai hidup'
                                                    | 'cerai mati',
                                            )
                                        }
                                    >
                                        <SelectTrigger
                                            id="status_kawin"
                                            className="h-11 border-slate-200"
                                        >
                                            <SelectValue placeholder="Pilih status" />
                                        </SelectTrigger>
                                        <SelectContent className="bg-white">
                                            <SelectItem value="belum kawin">
                                                Belum Kawin
                                            </SelectItem>
                                            <SelectItem value="kawin">
                                                Kawin
                                            </SelectItem>
                                            <SelectItem value="cerai hidup">
                                                Cerai Hidup
                                            </SelectItem>
                                            <SelectItem value="cerai mati">
                                                Cerai Mati
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                ) : (
                                    <div className="h-11 w-full rounded-md border border-slate-200 bg-slate-50" />
                                )}
                                <InputError message={errors.status_kawin} />
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="jumlah_anak">
                                    Jumlah Anak Tanggungan
                                </Label>
                                <Input
                                    id="jumlah_anak"
                                    type="number"
                                    min={0}
                                    className="h-11 border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                    value={data.jumlah_anak}
                                    onChange={(e) =>
                                        setData(
                                            'jumlah_anak',
                                            parseInt(e.target.value) || 0,
                                        )
                                    }
                                    required
                                />
                                <InputError message={errors.jumlah_anak} />
                            </div>
                        </div>

                        {/* FIELD KONDISIONAL JIKA STATUS KAWIN */}
                        {data.status_kawin === 'kawin' && (
                            <div className="grid grid-cols-1 gap-6 border-t border-dashed border-slate-200 pt-4 md:grid-cols-2">
                                <div className="space-y-2">
                                    <Label htmlFor="nama_pasangan">
                                        Nama Suami / Istri
                                    </Label>
                                    <Input
                                        id="nama_pasangan"
                                        className="h-11 border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                        value={data.nama_pasangan}
                                        onChange={(e) =>
                                            setData(
                                                'nama_pasangan',
                                                e.target.value,
                                            )
                                        }
                                        placeholder="Nama lengkap pasangan sesuai akta"
                                        required={data.status_kawin === 'kawin'}
                                    />
                                    <InputError
                                        message={errors.nama_pasangan}
                                    />
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="status_kerja_pasangan">
                                        Pekerjaan Suami / Istri
                                    </Label>
                                    <Input
                                        id="status_kerja_pasangan"
                                        className="h-11 border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                        value={data.status_kerja_pasangan}
                                        onChange={(e) =>
                                            setData(
                                                'status_kerja_pasangan',
                                                e.target.value,
                                            )
                                        }
                                        placeholder="Contoh: PNS, Karyawan Swasta, Tidak Bekerja"
                                        required={data.status_kawin === 'kawin'}
                                    />
                                    <InputError
                                        message={errors.status_kerja_pasangan}
                                    />
                                </div>
                            </div>
                        )}
                    </div>

                    {/* ACTION SUBMIT */}
                    <div className="flex items-center justify-end gap-4">
                        <Button
                            disabled={processing}
                            className="h-12 bg-blue-600 px-8 font-semibold text-white shadow-sm transition-colors hover:bg-blue-700"
                            data-test="update-profile-button"
                        >
                            {processing
                                ? 'Menyimpan Perubahan...'
                                : 'Simpan Perubahan'}
                        </Button>
                    </div>
                </form>
            </div>
        </>
    );
}

// ─── NESTED LAYOUT CONFIGURATION (MENJAGA INTEGRITAS NAVIGATION SIDEBAR) ───
Profile.layout = (page: React.ReactNode) => (
    <AppLayout
        breadcrumbs={[
            { title: 'Pengaturan', href: '#' },
            { title: 'Profil', href: '#' },
        ]}
    >
        <SettingsLayout>
            <div className="w-full p-6 md:p-8">{page}</div>
        </SettingsLayout>
    </AppLayout>
);
