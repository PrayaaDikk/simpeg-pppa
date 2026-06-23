import React, { useState, useEffect } from 'react';
import { useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Sheet,
    SheetContent,
    SheetHeader,
    SheetTitle,
    SheetDescription,
} from '@/components/ui/sheet';
import { Key, Users, Briefcase, Shield } from 'lucide-react';
import InputError from '@/components/input-error';
import { cn } from '@/lib/utils';

interface Pegawai {
    id: number;
    user_id: number;
    bidang_id: number | null;
    jabatan_id: number | null;
    pangkat_id: number;
    nip: string;
    nama: string;
    karpeg: string | null;
    jenis_kelamin: 'l' | 'p';
    agama: 'islam' | 'kristen' | 'katolik' | 'hindu' | 'buddha' | 'konghucu';
    tempat_lahir: string;
    tanggal_lahir: string;
    no_telp: string;
    kode_pos: string;
    alamat: string;
    status_kawin: 'belum kawin' | 'kawin' | 'cerai hidup' | 'cerai mati';
    nama_pasangan: string | null;
    status_kerja_pasangan: string | null;
    jumlah_anak: number | null;
    jenis_pegawai: 'pns' | 'cpns' | 'pppk';
    jatah_cuti_dua_tahun_lalu: number;
    jatah_cuti_satu_tahun_lalu: number;
    jatah_cuti_tahun_ini: number;
    tmt_pegawai: string;
    is_active: boolean;
    foto: string | null;
    user?: {
        id: number;
        email: string;
    } | null;
}

interface BidangItem {
    id: number;
    nama_bidang: string;
    akronim: string;
}
interface JabatanItem {
    id: number;
    nama_jabatan: string;
    is_singleton: boolean;
}
interface PangkatItem {
    id: number;
    nama_pangkat: string;
    golongan: string;
}

interface EditPegawaiSheetProps {
    isOpen: boolean;
    onOpenChange: (open: boolean) => void;
    pegawai: Pegawai | null;
    bidangList: BidangItem[];
    jabatanList: JabatanItem[];
    pangkatList: PangkatItem[];
}

export default function EditPegawaiSheet({
    isOpen,
    onOpenChange,
    pegawai,
    bidangList,
    jabatanList,
    pangkatList,
}: EditPegawaiSheetProps) {
    const [activeTab, setActiveTab] = useState<
        'utama' | 'pribadi' | 'keluarga' | 'kredensial'
    >('utama');

    const form = useForm({
        nip: '',
        nama: '',
        karpeg: '',
        jenis_kelamin: 'l' as 'l' | 'p',
        agama: 'islam' as
            | 'islam'
            | 'kristen'
            | 'katolik'
            | 'hindu'
            | 'buddha'
            | 'konghucu',
        tempat_lahir: '',
        tanggal_lahir: '',
        no_telp: '',
        kode_pos: '',
        alamat: '',
        status_kawin: 'belum kawin' as
            | 'belum kawin'
            | 'kawin'
            | 'cerai hidup'
            | 'cerai mati',
        nama_pasangan: '',
        status_kerja_pasangan: '',
        jumlah_anak: 0,
        jenis_pegawai: 'pns' as 'pns' | 'cpns' | 'pppk',
        pangkat_id: '',
        bidang_id: '',
        jabatan_id: '',
        jatah_cuti_dua_tahun_lalu: 0,
        jatah_cuti_satu_tahun_lalu: 0,
        jatah_cuti_tahun_ini: 12,
        tmt_pegawai: '',
        is_active: true,

        // Field Kredensial & Autentikasi Baru
        email: '',
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    // Sinkronisasi data ketika data pegawai terpilih berubah
    useEffect(() => {
        if (pegawai) {
            form.setData({
                nama: pegawai.nama || '',
                nip: pegawai.nip || '',
                karpeg: pegawai.karpeg || '',
                jenis_kelamin: pegawai.jenis_kelamin || 'l',
                agama: pegawai.agama || 'islam',
                tempat_lahir: pegawai.tempat_lahir || '',
                tanggal_lahir: pegawai.tanggal_lahir
                    ? pegawai.tanggal_lahir.substring(0, 10)
                    : '',
                no_telp: pegawai.no_telp || '',
                kode_pos: pegawai.kode_pos || '',
                alamat: pegawai.alamat || '',
                status_kawin: pegawai.status_kawin || 'belum kawin',
                nama_pasangan: pegawai.nama_pasangan || '',
                status_kerja_pasangan: pegawai.status_kerja_pasangan || '',
                jumlah_anak: pegawai.jumlah_anak || 0,
                jenis_pegawai: pegawai.jenis_pegawai || 'pns',
                pangkat_id: pegawai.pangkat_id
                    ? pegawai.pangkat_id.toString()
                    : '',
                bidang_id: pegawai.bidang_id
                    ? pegawai.bidang_id.toString()
                    : '',
                jatah_cuti_dua_tahun_lalu:
                    pegawai.jatah_cuti_dua_tahun_lalu || 0,
                jatah_cuti_satu_tahun_lalu:
                    pegawai.jatah_cuti_satu_tahun_lalu || 0,
                jatah_cuti_tahun_ini: pegawai.jatah_cuti_tahun_ini || 0,
                jabatan_id: pegawai.jabatan_id
                    ? pegawai.jabatan_id.toString()
                    : '',
                tmt_pegawai: pegawai.tmt_pegawai
                    ? pegawai.tmt_pegawai.substring(0, 10)
                    : '',
                is_active: pegawai.is_active,

                // Set data email dari relasi user, kosongkan form password keamanan
                email: pegawai.user?.email || '',
                current_password: '',
                password: '',
                password_confirmation: '',
            });
        }
    }, [pegawai]);

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (!pegawai) return;

        form.setData((prevData) => ({
            ...prevData,
            _method: 'PUT',
        }));

        form.post(`/admin/pegawai/${pegawai.id}`, {
            onSuccess: () => {
                onOpenChange(false);
                form.reset(
                    'current_password',
                    'password',
                    'password_confirmation',
                );
            },
            onError: (e) => {
                console.error(e);
            },
            preserveState: true,
            preserveScroll: true,
        });
    };

    return (
        <Sheet open={isOpen} onOpenChange={onOpenChange}>
            <SheetContent className="w-full overflow-y-auto border-l border-slate-200 bg-white p-6 sm:max-w-md">
                <SheetHeader className="space-y-1 border-b border-slate-100 pb-4">
                    <SheetTitle className="text-xl font-bold text-slate-900">
                        Ubah Informasi Pegawai
                    </SheetTitle>
                    <SheetDescription className="text-sm text-slate-500">
                        Perbarui informasi profil, data organisasi, beserta akun
                        akses sistem pegawai.
                    </SheetDescription>
                </SheetHeader>

                {/* Tab Navigation */}
                <div className="my-5 flex rounded-lg border-b border-slate-100 bg-slate-50/50 p-1">
                    {[
                        { id: 'utama', label: 'Utama', icon: Briefcase },
                        { id: 'pribadi', label: 'Profil', icon: Users },
                        { id: 'keluarga', label: 'Keluarga', icon: Shield },
                        { id: 'kredensial', label: 'Akun', icon: Key },
                    ].map((tab) => {
                        const Icon = tab.icon;
                        return (
                            <button
                                key={tab.id}
                                type="button"
                                onClick={() => setActiveTab(tab.id as any)}
                                className={cn(
                                    'flex flex-1 items-center justify-center gap-1.5 rounded-md py-2 text-xs font-semibold transition-all duration-200',
                                    activeTab === tab.id
                                        ? 'border border-slate-100 bg-white text-blue-600 shadow-sm'
                                        : 'text-slate-500 hover:text-slate-900',
                                )}
                            >
                                <Icon className="size-3.5" />
                                <span>{tab.label}</span>
                            </button>
                        );
                    })}
                </div>

                <form onSubmit={handleSubmit} className="space-y-4">
                    {/* ── Tab: DATA UTAMA ── */}
                    {activeTab === 'utama' && (
                        <div className="space-y-4">
                            <div className="space-y-1.5">
                                <Label htmlFor="nama_edit">Nama Lengkap</Label>
                                <Input
                                    required
                                    id="nama_edit"
                                    value={form.data.nama}
                                    onChange={(e) =>
                                        form.setData('nama', e.target.value)
                                    }
                                />
                                <InputError message={form.errors.nama} />
                            </div>
                            <div className="space-y-1.5">
                                <Label htmlFor="nip_edit">NIP</Label>
                                <Input
                                    required
                                    id="nip_edit"
                                    value={form.data.nip}
                                    className="bg-slate-50"
                                    onChange={(e) =>
                                        form.setData('nip', e.target.value)
                                    }
                                />
                            </div>
                            <div className="space-y-1.5">
                                <Label htmlFor="karpeg_edit">
                                    Kartu Pegawai
                                </Label>
                                <Input
                                    required
                                    id="karpeg_edit"
                                    value={form.data.karpeg}
                                    className="bg-slate-50"
                                    onChange={(e) =>
                                        form.setData('karpeg', e.target.value)
                                    }
                                />
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div className="space-y-1.5">
                                    <Label htmlFor="jenis_pegawai_edit">
                                        Jenis Pegawai
                                    </Label>
                                    <select
                                        required
                                        id="jenis_pegawai_edit"
                                        value={form.data.jenis_pegawai}
                                        onChange={(e) =>
                                            form.setData(
                                                'jenis_pegawai',
                                                e.target.value as any,
                                            )
                                        }
                                        className="flex h-10 w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none"
                                    >
                                        <option value="pns">PNS</option>
                                        <option value="cpns">CPNS</option>
                                        <option value="pppk">PPPK</option>
                                    </select>
                                </div>
                                <div className="space-y-1.5">
                                    <Label htmlFor="status_aktif_edit">
                                        Status Keaktifan
                                    </Label>
                                    <select
                                        required
                                        id="status_aktif_edit"
                                        value={
                                            form.data.is_active
                                                ? 'true'
                                                : 'false'
                                        }
                                        onChange={(e) =>
                                            form.setData(
                                                'is_active',
                                                e.target.value === 'true',
                                            )
                                        }
                                        className="flex h-10 w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none"
                                    >
                                        <option value="true">Aktif</option>
                                        <option value="false">Non-Aktif</option>
                                    </select>
                                </div>
                            </div>
                            <div className="space-y-1.5">
                                <Label htmlFor="bidang_id_edit">
                                    Bidang Kerja (Opsional)
                                </Label>
                                <select
                                    id="bidang_id_edit"
                                    value={form.data.bidang_id}
                                    onChange={(e) =>
                                        form.setData(
                                            'bidang_id',
                                            e.target.value,
                                        )
                                    }
                                    className="flex h-10 w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none"
                                >
                                    <option value="">
                                        Pilih Struktur Bidang
                                    </option>
                                    {bidangList.map((b) => (
                                        <option key={b.id} value={b.id}>
                                            {b.nama_bidang}
                                        </option>
                                    ))}
                                </select>
                                <InputError message={form.errors.bidang_id} />
                            </div>
                            <div className="space-y-1.5">
                                <Label htmlFor="jabatan_id_edit">Jabatan</Label>
                                <select
                                    id="jabatan_id_edit"
                                    value={form.data.jabatan_id}
                                    onChange={(e) =>
                                        form.setData(
                                            'jabatan_id',
                                            e.target.value,
                                        )
                                    }
                                    className="flex h-10 w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none"
                                >
                                    <option value="">Pilih Jabatan</option>
                                    {jabatanList.map((j) => (
                                        <option key={j.id} value={j.id}>
                                            {j.nama_jabatan}
                                        </option>
                                    ))}
                                </select>
                                <InputError message={form.errors.jabatan_id} />
                            </div>
                            <div className="space-y-1.5">
                                <Label htmlFor="pangkat_id_edit">
                                    Pangkat / Golongan
                                </Label>
                                <select
                                    id="pangkat_id_edit"
                                    value={form.data.pangkat_id}
                                    onChange={(e) =>
                                        form.setData(
                                            'pangkat_id',
                                            e.target.value,
                                        )
                                    }
                                    className="flex h-10 w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none"
                                >
                                    {pangkatList.map((p) => (
                                        <option key={p.id} value={p.id}>
                                            {p.nama_pangkat} ({p.golongan})
                                        </option>
                                    ))}
                                </select>
                                <InputError message={form.errors.pangkat_id} />
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div className="space-y-1.5">
                                    <Label htmlFor="jatah_cuti_dua_tahun_lalu">
                                        Jatah Cuti n-2
                                    </Label>
                                    <Input
                                        required
                                        id="jatah_cuti_dua_tahun_lalu"
                                        type="number"
                                        value={
                                            form.data.jatah_cuti_dua_tahun_lalu
                                        }
                                        onChange={(e) =>
                                            form.setData(
                                                'jatah_cuti_dua_tahun_lalu',
                                                parseInt(e.target.value) || 0,
                                            )
                                        }
                                    />
                                </div>
                                <div className="space-y-1.5">
                                    <Label htmlFor="jatah_cuti_satu_tahun_lalu">
                                        Jatah Cuti n-1
                                    </Label>
                                    <Input
                                        required
                                        id="jatah_cuti_satu_tahun_lalu"
                                        type="number"
                                        value={
                                            form.data.jatah_cuti_satu_tahun_lalu
                                        }
                                        onChange={(e) =>
                                            form.setData(
                                                'jatah_cuti_satu_tahun_lalu',
                                                parseInt(e.target.value) || 0,
                                            )
                                        }
                                    />
                                </div>
                            </div>
                            <div className="space-y-1.5">
                                <Label htmlFor="jatah_cuti_tahun_ini">
                                    Jatah Cuti Tahun Ini
                                </Label>
                                <Input
                                    required
                                    id="jatah_cuti_tahun_ini"
                                    type="number"
                                    value={form.data.jatah_cuti_tahun_ini}
                                    onChange={(e) =>
                                        form.setData(
                                            'jatah_cuti_tahun_ini',
                                            parseInt(e.target.value) || 0,
                                        )
                                    }
                                />
                            </div>
                        </div>
                    )}

                    {/* ── Tab: BIODATA PRIBADI ── */}
                    {activeTab === 'pribadi' && (
                        <div className="space-y-4">
                            <div className="grid grid-cols-2 gap-4">
                                <div className="space-y-1.5">
                                    <Label htmlFor="jk_edit">
                                        Jenis Kelamin
                                    </Label>
                                    <select
                                        required
                                        id="jk_edit"
                                        value={form.data.jenis_kelamin}
                                        onChange={(e) =>
                                            form.setData(
                                                'jenis_kelamin',
                                                e.target.value as any,
                                            )
                                        }
                                        className="flex h-10 w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none"
                                    >
                                        <option value="l">Laki-Laki</option>
                                        <option value="p">Perempuan</option>
                                    </select>
                                </div>
                                <div className="space-y-1.5">
                                    <Label htmlFor="agama_edit">Agama</Label>
                                    <select
                                        required
                                        id="agama_edit"
                                        value={form.data.agama}
                                        onChange={(e) =>
                                            form.setData(
                                                'agama',
                                                e.target.value as any,
                                            )
                                        }
                                        className="flex h-10 w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none"
                                    >
                                        <option value="islam">Islam</option>
                                        <option value="kristen">Kristen</option>
                                        <option value="katolik">Katolik</option>
                                        <option value="hindu">Hindu</option>
                                        <option value="buddha">Buddha</option>
                                        <option value="konghucu">
                                            Konghucu
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div className="space-y-1.5">
                                    <Label htmlFor="tl_edit">
                                        Tempat Lahir
                                    </Label>
                                    <Input
                                        required
                                        id="tl_edit"
                                        value={form.data.tempat_lahir}
                                        onChange={(e) =>
                                            form.setData(
                                                'tempat_lahir',
                                                e.target.value,
                                            )
                                        }
                                    />
                                    <InputError
                                        message={form.errors.tempat_lahir}
                                    />
                                </div>
                                <div className="space-y-1.5">
                                    <Label htmlFor="tgl_edit">
                                        Tanggal Lahir
                                    </Label>
                                    <Input
                                        required
                                        id="tgl_edit"
                                        type="date"
                                        value={form.data.tanggal_lahir}
                                        onChange={(e) =>
                                            form.setData(
                                                'tanggal_lahir',
                                                e.target.value,
                                            )
                                        }
                                    />
                                    <InputError
                                        message={form.errors.tanggal_lahir}
                                    />
                                </div>
                            </div>
                            <div className="space-y-1.5">
                                <Label htmlFor="telp_edit">
                                    No. HP / Telepon
                                </Label>
                                <Input
                                    required
                                    id="telp_edit"
                                    value={form.data.no_telp}
                                    onChange={(e) =>
                                        form.setData('no_telp', e.target.value)
                                    }
                                />
                                <InputError message={form.errors.no_telp} />
                            </div>
                            <div className="space-y-1.5">
                                <Label htmlFor="alamat_edit">
                                    Alamat Domisili
                                </Label>
                                <textarea
                                    id="alamat_edit"
                                    value={form.data.alamat}
                                    onChange={(e) =>
                                        form.setData('alamat', e.target.value)
                                    }
                                    className="flex min-h-[60px] w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none"
                                />
                                <InputError message={form.errors.alamat} />
                            </div>
                        </div>
                    )}

                    {/* ── Tab: KELUARGA ── */}
                    {activeTab === 'keluarga' && (
                        <div className="space-y-4">
                            <div className="space-y-1.5">
                                <Label htmlFor="sk_edit">
                                    Status Perkawinan
                                </Label>
                                <select
                                    required
                                    id="sk_edit"
                                    value={form.data.status_kawin}
                                    onChange={(e) =>
                                        form.setData(
                                            'status_kawin',
                                            e.target.value as any,
                                        )
                                    }
                                    className="flex h-10 w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none"
                                >
                                    <option value="belum kawin">
                                        Belum Kawin
                                    </option>
                                    <option value="kawin">Kawin</option>
                                    <option value="cerai hidup">
                                        Cerai Hidup
                                    </option>
                                    <option value="cerai mati">
                                        Cerai Mati
                                    </option>
                                </select>
                            </div>
                            {form.data.status_kawin === 'kawin' && (
                                <>
                                    <div className="space-y-1.5">
                                        <Label htmlFor="np_edit">
                                            Nama Pasangan
                                        </Label>
                                        <Input
                                            id="np_edit"
                                            value={form.data.nama_pasangan}
                                            onChange={(e) =>
                                                form.setData(
                                                    'nama_pasangan',
                                                    e.target.value,
                                                )
                                            }
                                        />
                                        <InputError
                                            message={form.errors.nama_pasangan}
                                        />
                                    </div>
                                    <div className="space-y-1.5">
                                        <Label htmlFor="ja_edit">
                                            Jumlah Anak
                                        </Label>
                                        <Input
                                            id="ja_edit"
                                            type="number"
                                            value={form.data.jumlah_anak}
                                            onChange={(e) =>
                                                form.setData(
                                                    'jumlah_anak',
                                                    parseInt(e.target.value) ||
                                                        0,
                                                )
                                            }
                                        />
                                        <InputError
                                            message={form.errors.jumlah_anak}
                                        />
                                    </div>
                                </>
                            )}
                        </div>
                    )}

                    {/* ── Tab: KREDENSIAL / SECURITY (UPDATED) ── */}
                    {activeTab === 'kredensial' && (
                        <div className="space-y-4">
                            <div className="space-y-1.5">
                                <Label htmlFor="email_edit">
                                    Alamat Email Kerja
                                </Label>
                                <Input
                                    id="email_edit"
                                    type="email"
                                    value={form.data.email}
                                    onChange={(e) =>
                                        form.setData('email', e.target.value)
                                    }
                                    placeholder="nama@simpeg.local"
                                />
                                <InputError message={form.errors.email} />
                            </div>

                            <div className="rounded-lg border border-amber-100 bg-amber-50/60 p-3 text-xs leading-relaxed text-amber-800">
                                Isikan kolom sandi di bawah ini{' '}
                                <strong>hanya jika</strong> Anda ingin mengganti
                                kata sandi login pegawai yang bersangkutan.
                            </div>

                            <div className="space-y-1.5">
                                <Label htmlFor="current_pass_edit">
                                    Kata Sandi Sekarang (Konfirmasi Admin)
                                </Label>
                                <Input
                                    id="current_pass_edit"
                                    type="password"
                                    value={form.data.current_password}
                                    onChange={(e) =>
                                        form.setData(
                                            'current_password',
                                            e.target.value,
                                        )
                                    }
                                    placeholder="Masukkan sandi Anda untuk verifikasi"
                                />
                                <InputError
                                    message={form.errors.current_password}
                                />
                            </div>

                            <div className="space-y-1.5">
                                <Label htmlFor="pass_edit">
                                    Kata Sandi Baru
                                </Label>
                                <Input
                                    id="pass_edit"
                                    type="password"
                                    value={form.data.password}
                                    onChange={(e) =>
                                        form.setData('password', e.target.value)
                                    }
                                    placeholder="Minimal 8 karakter"
                                />
                                <InputError message={form.errors.password} />
                            </div>

                            <div className="space-y-1.5">
                                <Label htmlFor="pass_confirm_edit">
                                    Konfirmasi Kata Sandi Baru
                                </Label>
                                <Input
                                    id="pass_confirm_edit"
                                    type="password"
                                    value={form.data.password_confirmation}
                                    onChange={(e) =>
                                        form.setData(
                                            'password_confirmation',
                                            e.target.value,
                                        )
                                    }
                                    placeholder="Ulangi kata sandi baru"
                                />
                                <InputError
                                    message={form.errors.password_confirmation}
                                />
                            </div>
                        </div>
                    )}

                    {/* Footer Buttons */}
                    <div className="mt-6 flex gap-3 border-t border-slate-100 pt-4">
                        <Button
                            type="button"
                            variant="outline"
                            onClick={() => onOpenChange(false)}
                            className="h-11 flex-1 border-slate-200 text-slate-700"
                        >
                            Batalkan
                        </Button>
                        <Button
                            type="submit"
                            disabled={form.processing}
                            className="h-11 flex-1 bg-blue-600 font-semibold text-white hover:bg-blue-700"
                        >
                            Simpan Perubahan
                        </Button>
                    </div>
                </form>
            </SheetContent>
        </Sheet>
    );
}
