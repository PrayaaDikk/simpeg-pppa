import React, { useState } from 'react';
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

interface CreatePegawaiSheetProps {
    isOpen: boolean;
    onOpenChange: (open: boolean) => void;
    bidangList: BidangItem[];
    jabatanList: JabatanItem[];
    pangkatList: PangkatItem[];
}

export default function CreatePegawaiSheet({
    isOpen,
    onOpenChange,
    bidangList,
    jabatanList,
    pangkatList,
}: CreatePegawaiSheetProps) {
    const [activeTab, setActiveTab] = useState<
        'kredensial' | 'utama' | 'pribadi' | 'keluarga'
    >('kredensial');

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
        tmt_pegawai: '',
        jatah_cuti_dua_tahun_lalu: null,
        jatah_cuti_satu_tahun_lalu: null,
        jatah_cuti_tahun_ini: 12,
        is_active: true,
        email: '',
        password: '',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        form.post('/admin/pegawai/store', {
            onSuccess: () => {
                form.reset();
                onOpenChange(false);
                setActiveTab('kredensial');
            },
        });
    };

    return (
        <Sheet open={isOpen} onOpenChange={onOpenChange}>
            <SheetContent className="w-full overflow-y-auto border-l border-slate-200 bg-white p-6 sm:max-w-md">
                <SheetHeader className="space-y-1 border-b border-slate-100 pb-4">
                    <SheetTitle className="text-xl font-bold text-slate-900">
                        Registrasi Pegawai Baru
                    </SheetTitle>
                    <SheetDescription className="text-sm text-slate-500">
                        Masukkan data lengkap untuk mendaftarkan pegawai dan
                        membuat akun sistem baru.
                    </SheetDescription>
                </SheetHeader>

                {/* Tab Navigation */}
                <div className="my-5 flex rounded-lg border-b border-slate-100 bg-slate-50/50 p-1">
                    {[
                        { id: 'kredensial', label: 'Akun', icon: Key },
                        { id: 'utama', label: 'Utama', icon: Briefcase },
                        { id: 'pribadi', label: 'Profil', icon: Users },
                        { id: 'keluarga', label: 'Keluarga', icon: Shield },
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
                    {/* ── Tab: KREDENSIAL AKUN ── */}
                    {activeTab === 'kredensial' && (
                        <div className="space-y-4">
                            <div className="space-y-1.5">
                                <Label htmlFor="email">
                                    Alamat Email Kerja (Opsional)
                                </Label>
                                <Input
                                    id="email"
                                    type="email"
                                    value={form.data.email}
                                    onChange={(e) =>
                                        form.setData('email', e.target.value)
                                    }
                                    placeholder="contoh: nama@simpeg.local"
                                />
                                <InputError message={form.errors.email} />
                            </div>
                            <div className="space-y-1.5">
                                <Label htmlFor="password">
                                    Kata Sandi Baru
                                </Label>
                                <Input
                                    id="password"
                                    type="password"
                                    value={form.data.password}
                                    onChange={(e) =>
                                        form.setData('password', e.target.value)
                                    }
                                    placeholder="Kosongkan untuk otomatis menggunakan p3a[NIP]"
                                />
                                <InputError message={form.errors.password} />
                            </div>
                        </div>
                    )}

                    {/* ── Tab: DATA UTAMA ── */}
                    {activeTab === 'utama' && (
                        <div className="space-y-4">
                            <div className="space-y-1.5">
                                <Label htmlFor="nama">Nama Lengkap</Label>
                                <Input
                                    required
                                    id="nama"
                                    value={form.data.nama}
                                    onChange={(e) =>
                                        form.setData('nama', e.target.value)
                                    }
                                    placeholder="Contoh: John Doe"
                                />
                                <InputError message={form.errors.nama} />
                            </div>
                            <div className="space-y-1.5">
                                <Label htmlFor="nip">Nomor Induk Pegawai</Label>
                                <Input
                                    required
                                    id="nip"
                                    value={form.data.nip}
                                    onChange={(e) =>
                                        form.setData('nip', e.target.value)
                                    }
                                    placeholder="Masukkan 18 digit NIP resmi"
                                />
                                <InputError message={form.errors.nip} />
                            </div>
                            <div className="space-y-1.5">
                                <Label htmlFor="karpeg">
                                    Kartu Pegawai (Opsional)
                                </Label>
                                <Input
                                    id="karpeg"
                                    value={form.data.karpeg}
                                    onChange={(e) =>
                                        form.setData('karpeg', e.target.value)
                                    }
                                    placeholder="Masukkan nomor Kartu Pegawai resmi"
                                />
                                <InputError message={form.errors.karpeg} />
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div className="space-y-1.5">
                                    <Label htmlFor="jenis_pegawai">
                                        Jenis Kepegawaian
                                    </Label>
                                    <select
                                        id="jenis_pegawai"
                                        value={form.data.jenis_pegawai}
                                        onChange={(e) =>
                                            form.setData(
                                                'jenis_pegawai',
                                                e.target.value as any,
                                            )
                                        }
                                        className="flex h-10 w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm"
                                    >
                                        <option value="pns">PNS</option>
                                        <option value="cpns">CPNS</option>
                                        <option value="pppk">PPPK</option>
                                    </select>
                                </div>
                                <div className="space-y-1.5">
                                    <Label htmlFor="tmt_pegawai">
                                        TMT Kepegawaian
                                    </Label>
                                    <Input
                                        required
                                        id="tmt_pegawai"
                                        type="date"
                                        value={form.data.tmt_pegawai}
                                        onChange={(e) =>
                                            form.setData(
                                                'tmt_pegawai',
                                                e.target.value,
                                            )
                                        }
                                    />
                                    <InputError
                                        message={form.errors.tmt_pegawai}
                                    />
                                </div>
                            </div>
                            <div className="space-y-1.5">
                                <Label htmlFor="bidang_id">
                                    Penempatan Bidang Kerja
                                </Label>
                                <select
                                    id="bidang_id"
                                    value={form.data.bidang_id}
                                    onChange={(e) =>
                                        form.setData(
                                            'bidang_id',
                                            e.target.value,
                                        )
                                    }
                                    className="flex h-10 w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm"
                                >
                                    <option value="">
                                        Pilih Struktur Bidang (Opsional)
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
                                <Label htmlFor="jabatan_id">
                                    Jabatan Struktural/Fungsional
                                </Label>
                                <select
                                    id="jabatan_id"
                                    value={form.data.jabatan_id}
                                    onChange={(e) =>
                                        form.setData(
                                            'jabatan_id',
                                            e.target.value,
                                        )
                                    }
                                    className="flex h-10 w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm"
                                >
                                    <option value="">Pilih Jabatan</option>
                                    {jabatanList.map((j) => (
                                        <option key={j.id} value={j.id}>
                                            {j.nama_jabatan}
                                        </option>
                                    ))}
                                </select>
                            </div>
                            <div className="space-y-1.5">
                                <Label htmlFor="pangkat_id">
                                    Golongan Ruang / Pangkat
                                </Label>
                                <select
                                    required
                                    id="pangkat_id"
                                    value={form.data.pangkat_id}
                                    onChange={(e) =>
                                        form.setData(
                                            'pangkat_id',
                                            e.target.value,
                                        )
                                    }
                                    className="flex h-10 w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm"
                                >
                                    <option value="">
                                        Pilih Golongan Pangkat
                                    </option>
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
                                    <Label htmlFor="jenis_kelamin">
                                        Jenis Kelamin
                                    </Label>
                                    <select
                                        required
                                        id="jenis_kelamin"
                                        value={form.data.jenis_kelamin}
                                        onChange={(e) =>
                                            form.setData(
                                                'jenis_kelamin',
                                                e.target.value as any,
                                            )
                                        }
                                        className="flex h-10 w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm"
                                    >
                                        <option value="l">Laki-Laki</option>
                                        <option value="p">Perempuan</option>
                                    </select>
                                </div>
                                <div className="space-y-1.5">
                                    <Label htmlFor="agama">Agama</Label>
                                    <select
                                        required
                                        id="agama"
                                        value={form.data.agama}
                                        onChange={(e) =>
                                            form.setData(
                                                'agama',
                                                e.target.value as any,
                                            )
                                        }
                                        className="flex h-10 w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm"
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
                                    <Label htmlFor="tempat_lahir">
                                        Tempat Lahir
                                    </Label>
                                    <Input
                                        required
                                        id="tempat_lahir"
                                        value={form.data.tempat_lahir}
                                        onChange={(e) =>
                                            form.setData(
                                                'tempat_lahir',
                                                e.target.value,
                                            )
                                        }
                                        placeholder="Kota / Kabupaten"
                                    />
                                    <InputError
                                        message={form.errors.tempat_lahir}
                                    />
                                </div>
                                <div className="space-y-1.5">
                                    <Label htmlFor="tanggal_lahir">
                                        Tanggal Lahir
                                    </Label>
                                    <Input
                                        required
                                        id="tanggal_lahir"
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
                            <div className="grid grid-cols-2 gap-4">
                                <div className="space-y-1.5">
                                    <Label htmlFor="no_telp">
                                        No. Telepon / HP
                                    </Label>
                                    <Input
                                        required
                                        id="no_telp"
                                        value={form.data.no_telp}
                                        onChange={(e) =>
                                            form.setData(
                                                'no_telp',
                                                e.target.value,
                                            )
                                        }
                                        placeholder="Contoh: 081234..."
                                    />
                                    <InputError message={form.errors.no_telp} />
                                </div>
                                <div className="space-y-1.5">
                                    <Label htmlFor="kode_pos">Kode Pos</Label>
                                    <Input
                                        required
                                        id="kode_pos"
                                        value={form.data.kode_pos}
                                        onChange={(e) =>
                                            form.setData(
                                                'kode_pos',
                                                e.target.value,
                                            )
                                        }
                                        placeholder="5 digit"
                                    />
                                </div>
                            </div>
                            <div className="space-y-1.5">
                                <Label htmlFor="alamat">
                                    Alamat Tinggal Sesuai KTP
                                </Label>
                                <textarea
                                    required
                                    id="alamat"
                                    value={form.data.alamat}
                                    onChange={(e) =>
                                        form.setData('alamat', e.target.value)
                                    }
                                    className="flex min-h-[60px] w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900"
                                    placeholder="Nama jalan, RT/RW, Dusun, Kecamatan..."
                                />
                            </div>
                        </div>
                    )}

                    {/* ── Tab: RIWAYAT KELUARGA ── */}
                    {activeTab === 'keluarga' && (
                        <div className="space-y-4">
                            <div className="space-y-1.5">
                                <Label htmlFor="status_kawin">
                                    Status Pernikahan
                                </Label>
                                <select
                                    required
                                    id="status_kawin"
                                    value={form.data.status_kawin}
                                    onChange={(e) =>
                                        form.setData(
                                            'status_kawin',
                                            e.target.value as any,
                                        )
                                    }
                                    className="flex h-10 w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm"
                                >
                                    <option value="belum kawin">
                                        Belum Kawin
                                    </option>
                                    <option value="kawin">
                                        Kawin / Menikah
                                    </option>
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
                                        <Label htmlFor="nama_pasangan">
                                            Nama Suami / Istri
                                        </Label>
                                        <Input
                                            id="nama_pasangan"
                                            value={form.data.nama_pasangan}
                                            onChange={(e) =>
                                                form.setData(
                                                    'nama_pasangan',
                                                    e.target.value,
                                                )
                                            }
                                            placeholder="Nama lengkap pasangan"
                                        />
                                    </div>
                                    <div className="grid grid-cols-2 gap-4">
                                        <div className="space-y-1.5">
                                            <Label htmlFor="status_kerja_pasangan">
                                                Pekerjaan Pasangan
                                            </Label>
                                            <Input
                                                id="status_kerja_pasangan"
                                                value={
                                                    form.data
                                                        .status_kerja_pasangan
                                                }
                                                onChange={(e) =>
                                                    form.setData(
                                                        'status_kerja_pasangan',
                                                        e.target.value,
                                                    )
                                                }
                                                placeholder="Contoh: Karyawan Swasta, PNS"
                                            />
                                        </div>
                                        <div className="space-y-1.5">
                                            <Label htmlFor="jumlah_anak">
                                                Tanggungan Jumlah Anak
                                            </Label>
                                            <Input
                                                id="jumlah_anak"
                                                type="number"
                                                value={form.data.jumlah_anak}
                                                onChange={(e) =>
                                                    form.setData(
                                                        'jumlah_anak',
                                                        parseInt(
                                                            e.target.value,
                                                        ) || 0,
                                                    )
                                                }
                                            />
                                        </div>
                                    </div>
                                </>
                            )}
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
                            className="h-11 flex-1 bg-slate-900 font-semibold text-white hover:bg-slate-800"
                        >
                            Daftarkan Pegawai
                        </Button>
                    </div>
                </form>
            </SheetContent>
        </Sheet>
    );
}
