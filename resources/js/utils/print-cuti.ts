import PizZip from 'pizzip';
import Docxtemplater from 'docxtemplater';
import { saveAs } from 'file-saver';
import { hitungMasaKerja } from './helper';

export const generateSuratCutiFromTemplate = async (cuti: any) => {
    if (!cuti) return;

    // Helper format Tanggal Indonesia (Contoh: 20 Juni 2026)
    const formatTanggalIndo = (dateString: string) => {
        if (!dateString) return '-';
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return '-';
        return date.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
        });
    };

    // Helper konversi angka ke terbilang sederhana untuk durasi hari
    const angkaKeTerbilang = (angka: number): string => {
        const bilangan = [
            '',
            'satu',
            'dua',
            'tiga',
            'empat',
            'lima',
            'enam',
            'tujuh',
            'delapan',
            'sembilan',
            'sepuluh',
            'sebelas',
        ];
        if (angka < 12) return bilangan[angka];
        if (angka < 20) return bilangan[angka - 10] + ' belas';
        if (angka < 100)
            return (
                bilangan[Math.floor(angka / 10)] +
                ' puluh ' +
                (angka % 10 !== 0 ? ' ' + bilangan[angka % 10] : '')
            );
        return 'banyak';
    };

    try {
        // 1. Ambil file template asli dari direktori public
        const response = await fetch('/templates/CUTI_template.docx');
        if (!response.ok) {
            throw new Error(
                'Gagal memuat berkas template CUTI_template.docx dari folder public.',
            );
        }

        const arrayBuffer = await response.arrayBuffer();

        // 2. Load struktur binary berkas zip dokumen word
        const zip = new PizZip(arrayBuffer);
        const doc = new Docxtemplater(zip, {
            paragraphLoop: true,
            linebreaks: true,
            // SUNTIKKAN KONFIGURASI DELIMITER BARU DI SINI
            delimiters: {
                start: '%',
                end: '%',
            },
        });

        const pegawai = cuti.pegawai || {};
        const jenis = (cuti.jenis_cuti || '').toLowerCase();

        // 3. Suntikkan object data ke placeholder variabel template
        doc.setData({
            // Alamat & Metadata Surat (Tanggal dari created_at data dibuat)
            tanggal_surat: `Kendari, ${formatTanggalIndo(cuti.created_at)}`,
            nama_atasan_penerima:
                cuti.atasan?.jabatan?.nama_jabatan ||
                'Kepala DP3A Kota Kendari',
            tempat_kedudukan_atasan: 'Kendari',

            // Blok I: Data Pegawai
            nama_pegawai: pegawai.nama || '-',
            nip_pegawai: pegawai.nip || '-',
            jabatan_pegawai: pegawai.jabatan?.nama_jabatan || '-',
            masa_kerja: hitungMasaKerja(pegawai.tmt_pegawai) || '-',
            unit_kerja:
                'Dinas Pemberdayaan Perempuan dan Perlindungan Anak Kota Kendari',

            // Blok II & V (Kanan): Checklist Jenis Cuti (Gunakan simbol '')
            v_tahunan: jenis === 'tahunan' ? 'P' : '',
            v_besar: jenis === 'besar' ? 'P' : '',
            v_sakit: jenis === 'sakit' ? 'P' : '',
            v_melahirkan: jenis === 'melahirkan' ? 'P' : '',
            v_alasan_penting: jenis === 'alasan penting' ? 'P' : '',
            v_luar_tanggungan: jenis === 'diluar tanggungan negara' ? 'P' : '',

            c_besar: jenis === 'besar' ? 'P' : '',
            c_sakit: jenis === 'sakit' ? 'P' : '',
            c_melahirkan: jenis === 'melahirkan' ? 'P' : '',
            c_alasan_penting: jenis === 'alasan penting' ? 'P' : '',
            c_luar_tanggungan: jenis === 'diluar tanggungan negara' ? 'P' : '',

            // Blok III & IV: Alasan & Waktu Cuti
            alasan_cuti: cuti.alasan_cuti || '-',
            lama_cuti_teks: `${cuti.lama_cuti || 0} (${angkaKeTerbilang(cuti.lama_cuti || 0)}) Hari Kerja`,
            tanggal_mulai: formatTanggalIndo(cuti.tanggal_mulai),
            tanggal_akhir: formatTanggalIndo(cuti.tanggal_akhir),

            // Blok V (Kiri): Catatan Kuota Sisa Jatah Cuti
            jatah_dua_tahun_lalu: pegawai.jatah_cuti_dua_tahun_lalu ?? '0',
            jatah_satu_tahun_lalu: pegawai.jatah_cuti_satu_tahun_lalu ?? '0',
            jatah_tahun_ini: pegawai.jatah_cuti_tahun_ini ?? '0',

            // Blok VI: Domisili Sementara
            alamat_cuti: cuti.alamat || '-',
            no_telp: cuti.no_telp || '-',

            // Blok VII & VIII: Area Tanda Tangan (Sesuai kesepakatan dokumen final disetujui)
            nama_pegawai_caps: (pegawai.nama || '').toUpperCase(),
            nama_atasan: cuti.atasan?.nama || '( WD. ST. SUPINAWATI, S.TP )',
            nip_atasan: cuti.atasan?.nip || '197101061997032005',
            nama_pejabat_sah: 'FITRIANI SINAPOY, A.Pi., MP',
            nip_pejabat_sah: '197609102000032003',
        });

        // 4. Compile dan render dokumen
        doc.render();

        // 5. Generate berkas menjadi blob binary word (.docx)
        const outputBlob = doc.getZip().generate({
            type: 'blob',
            mimeType:
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        });

        // 6. Unduh otomatis berkas ke perangkat user
        const namaFile = `Form_Cuti_${(pegawai.nama || 'Pegawai').replace(/\s+/g, '_')}.docx`;
        saveAs(outputBlob, namaFile);
    } catch (error) {
        console.error(
            'Gagal memproses pencetakan berkas cuti di frontend:',
            error,
        );
        alert('Terjadi kesalahan saat memproses data ke dalam template Word.');
    }
};
