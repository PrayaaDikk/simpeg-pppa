export interface Pegawai {
    id: number;
    user_id: number;
    bidang_id: number | null;
    jabatan_id: number | null;
    pangkat_id: number;
    pendidikan: {
        tingkat: string;
        jurusan: string;
    };
    nip: string;
    nama: string;
    karpeg: string | null;
    jenis_kelamin: 'l' | 'p';
    is_active: boolean;
    bidang?: { nama_bidang: string } | null;
    jabatan?: { nama_jabatan: string } | null;
    pangkat?: { nama_pangkat: string; golongan: string } | null;
}

export interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

export interface PegawaiPaginator {
    data: Pegawai[];
    links: PaginationLink[];
    current_page: number;
    per_page: number;
    from: number | null;
    to: number | null;
    total: number;
}
