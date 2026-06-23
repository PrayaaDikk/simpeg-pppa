import PizZip from 'pizzip';
import Docxtemplater from 'docxtemplater';
import { saveAs } from 'file-saver';

export const generateSuratKgbFromTemplate = async (kgb: any) => {
    if (!kgb) return;

    // Helper format Rupiah resmi
    const formatRupiah = (nominal: any) => {
        if (!nominal) return 'Rp. 0';
        return (
            'Rp. ' +
            Number(nominal).toLocaleString('id-ID', {
                minimumFractionDigits: 0,
            })
        );
    };

    // Helper format Tanggal Indonesia (Contoh: 15 Juni 2026)
    const formatTanggalIndo = (dateString: string) => {
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return '-';
        return date.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
        });
    };

    try {
        // 1. Ambil file template asli dari direktori public
        const response = await fetch('/templates/KGB_Template.docx');
        if (!response.ok)
            throw new Error(
                'Gagal memuat berkas template KGB_Template.docx dari folder public.',
            );

        const arrayBuffer = await response.arrayBuffer();

        // 2. Load struktur binary berkas menggunakan PizZip
        const zip = new PizZip(arrayBuffer);
        const doc = new Docxtemplater(zip, {
            paragraphLoop: true,
            linebreaks: true,
        });

        // 3. Masukkan data rows ke dalam tag {} di Word
        doc.render({
            tanggal_surat: formatTanggalIndo(
                kgb.created_at || new Date().toISOString(),
            ),
            nama: kgb.pegawai?.nama || '-',
            nip: kgb.pegawai?.nip || '-',
            golongan_lama: kgb.golongan_lama || '-',
            golongan_baru: kgb.golongan_baru || '-',
            jabatan: kgb.pegawai?.jabatan?.nama_jabatan || '-',
            unit_kerja:
                kgb.pegawai?.bidang?.nama ||
                'Dinas Pemberdayaan Perempuan dan Perlindungan Anak Kota Kendari',
            gaji_lama: formatRupiah(kgb.gaji_lama),
            gaji_baru: formatRupiah(kgb.gaji_baru),
            masa_kerja_lama: kgb.masa_kerja_lama || '-',
            masa_kerja_baru: kgb.masa_kerja_baru || '-',
            tmt_gaji_lama: formatTanggalIndo(kgb.tmt_gaji_lama),
            tmt_gaji_baru: formatTanggalIndo(kgb.tmt_gaji_baru),
            kgb_berikutnya: formatTanggalIndo(kgb.kgb_berikutnya),
        });

        // 4. Generate berkas setelah semua placeholder diganti
        const outputBlob = doc.getZip().generate({
            type: 'blob',
            mimeType:
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        });

        // 5. Unduh dokumen secara otomatis ke perangkat pengguna dengan nama dinamis
        const namaFile = `Surat_KGB_${kgb.pegawai?.nama?.replace(/\s+/g, '_') || 'Pegawai'}.docx`;
        saveAs(outputBlob, namaFile);
    } catch (error) {
        console.error('Error saat memproses template Word:', error);
        alert(
            'Gagal mencetak dokumen. Pastikan file KGB_Template.docx berada di folder public/templates/',
        );
    }
};
