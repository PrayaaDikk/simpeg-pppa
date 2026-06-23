import { jsPDF } from 'jspdf';

interface CutiRecord {
    id: number;
    jenis_cuti: string;
    alasan_cuti: string;
    tanggal_mulai: string;
    tanggal_akhir: string;
    lama_cuti: number;
    alamat: string;
    no_telp: string;
    status_cuti: string;
    created_at: string;
    pegawai: {
        nip: string;
        nama: string;
        masa_kerja?: string;
        jabatan?: { nama_jabatan: string };
        pangkat?: { nama_pangkat: string; golongan: string };
    };
}

export function printCutiPegawai(cuti: CutiRecord): void {
    // Inisialisasi dokumen A4 Vertikal (Sesuai parameter instruksi)
    const doc = new jsPDF({
        orientation: 'p',
        unit: 'mm',
        format: 'a4',
    });

    const formatTanggalIndo = (dateStr: string) => {
        if (!dateStr) return '-';
        const d = new Date(dateStr);
        return d.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
        });
    };

    const leftMargin = 15;
    const rightBoundary = 195;
    const widthTotal = rightBoundary - leftMargin; // 180mm
    let currentY = 15;

    // --- TOP HEADERS BLOCK (Metadata Atas Kanan) ---
    doc.setFont('Helvetica', 'normal');
    doc.setFontSize(8);
    const headerLines = [
        'ANAK LAMPIRAN 1.b PERATURAN BADAN KEPEGAWAIAN NEGARA',
        'NOMOR 24 TAHUN 2017 TENTANG TATA CARA PEMBERIAN CUTI',
        'PEGAWAI NEGERI SIPIL',
    ];
    headerLines.forEach((line) => {
        doc.text(line, rightBoundary, currentY, { align: 'right' });
        currentY += 4;
    });

    currentY += 4;
    doc.setFont('Helvetica', 'bold');
    doc.setFontSize(11);
    doc.text('FORMULIR PERMINTAAN DAN PEMBERIAN CUTI', 105, currentY, {
        align: 'center',
    });

    // Tanggal pengajuan surat
    currentY += 6;
    doc.setFont('Helvetica', 'normal');
    doc.setFontSize(10);
    doc.text(
        `Kendari, ${formatTanggalIndo(cuti.created_at)}`,
        rightBoundary,
        currentY,
        { align: 'right' },
    );

    // Helper fungsi menggambar judul section kotak tabel induk
    const drawSectionTitle = (title: string) => {
        doc.setFont('Helvetica', 'bold');
        doc.setFontSize(9);
        doc.rect(leftMargin, currentY, widthTotal, 6);
        doc.text(`  ${title}`, leftMargin, currentY + 4.5);
        currentY += 6;
    };

    // --- I. DATA PEGAWAI ---
    currentY += 6;
    drawSectionTitle('I. DATA PEGAWAI');

    // Gambar Grid Tabel Struktur Data Pegawai
    doc.rect(leftMargin, currentY, widthTotal, 14);
    doc.line(leftMargin + 45, currentY, leftMargin + 45, currentY + 14); // Vertikal pembatas tengah kiri
    doc.line(105, currentY, 105, currentY + 14); // Vertikal pembatas tengah utama
    doc.line(105 + 35, currentY, 105 + 35, currentY + 14); // Vertikal pembatas tengah kanan
    doc.line(leftMargin, currentY + 7, rightBoundary, currentY + 7); // Horizontal pembatas baris 1 & 2

    doc.setFont('Helvetica', 'normal');
    doc.setFontSize(9);
    // Baris 1
    doc.text(' Nama', leftMargin + 1, currentY + 5);
    doc.setFont('Helvetica', 'bold');
    doc.text(` ${cuti.pegawai.nama}`, leftMargin + 46, currentY + 5);
    doc.setFont('Helvetica', 'normal');
    doc.text(' NIP', 105 + 1, currentY + 5);
    doc.text(` ${cuti.pegawai.nip}`, 105 + 36, currentY + 5);

    // Baris 2
    doc.text(' Jabatan', leftMargin + 1, currentY + 12);
    doc.text(
        ` ${cuti.pegawai.jabatan?.nama_jabatan || '-'}`,
        leftMargin + 46,
        currentY + 12,
    );
    doc.text(' Masa Kerja', 105 + 1, currentY + 12);
    doc.text(
        ` ${cuti.pegawai.masa_kerja || '10 Tahun'}`,
        105 + 36,
        currentY + 12,
    );

    currentY += 14;
    // Sub Baris Unit Kerja (Full Width Bottom Data Pegawai)
    doc.rect(leftMargin, currentY, widthTotal, 7);
    doc.line(leftMargin + 45, currentY, leftMargin + 45, currentY + 7);
    doc.text(' Unit Kerja', leftMargin + 1, currentY + 5);
    doc.text(
        ' Dinas Pemberdayaan Perempuan dan Perlindungan Anak Kota Kendari',
        leftMargin + 46,
        currentY + 5,
    );
    currentY += 7;

    // --- II. JENIS CUTI YANG DIAMBIL ---
    currentY += 4;
    drawSectionTitle('II. JENIS CUTI YANG DIAMBIL');

    doc.rect(leftMargin, currentY, widthTotal, 21);
    // Buat sekat matriks 2 Kolom x 3 Baris
    doc.line(105, currentY, 105, currentY + 21); // Vertikal tengah
    doc.line(leftMargin, currentY + 7, rightBoundary, currentY + 7);
    doc.line(leftMargin, currentY + 14, rightBoundary, currentY + 14);

    // Batas kotak checkbox pilihan (sebelah kanan kolom)
    doc.line(105 - 12, currentY, 105 - 12, currentY + 21);
    doc.line(rightBoundary - 12, currentY, rightBoundary - 12, currentY + 21);

    const listCuti = [
        { name: '1. Cuti Tahunan', match: 'Cuti Tahunan' },
        { name: '2. Cuti Besar', match: 'Cuti Besar' },
        { name: '3. Cuti Sakit', match: 'Cuti Sakit' },
        { name: '4. Cuti Melahirkan', match: 'Cuti Melahirkan' },
        {
            name: '5. Cuti Karena Alasan Penting',
            match: 'Cuti Karena Alasan Penting',
        },
        {
            name: '6. Cuti Di Luar Tanggungan Negara',
            match: 'Cuti Di Luar Tanggungan Negara',
        },
    ];

    // Render teks matriks jenis cuti berserta indikator tanda silang/centang otomatis [✓]
    doc.setFont('Helvetica', 'normal');
    listCuti.forEach((item, index) => {
        const isMatched = cuti.jenis_cuti === item.match;
        const marker = isMatched ? '[ ✓ ]' : '[   ]';

        if (index < 3) {
            // Sisi Kolom Kiri
            doc.text(`  ${item.name}`, leftMargin, currentY + 5 + index * 7);
            doc.text(marker, 105 - 9, currentY + 5 + index * 7);
        } else {
            // Sisi Kolom Kanan
            const subIndex = index - 3;
            doc.text(`  ${item.name}`, 105, currentY + 5 + subIndex * 7);
            doc.text(marker, rightBoundary - 9, currentY + 5 + subIndex * 7);
        }
    });
    currentY += 21;

    // --- III. ALASAN CUTI ---
    currentY += 4;
    drawSectionTitle('III. ALASAN CUTI');
    doc.rect(leftMargin, currentY, widthTotal, 12);
    doc.setFont('Helvetica', 'normal');
    doc.text(`  ${cuti.alasan_cuti}`, leftMargin, currentY + 7, {
        maxWidth: widthTotal - 4,
    });
    currentY += 12;

    // --- IV. LAMANYA CUTI ---
    currentY += 4;
    drawSectionTitle('IV. LAMANYA CUTI');
    doc.rect(leftMargin, currentY, widthTotal, 10);
    doc.line(leftMargin + 45, currentY, leftMargin + 45, currentY + 10); // Sekat Vertikal Tengah
    doc.text('  Selama', leftMargin + 1, currentY + 6);
    doc.setFont('Helvetica', 'bold');
    doc.text(`  ${cuti.lama_cuti} Hari Kerja`, leftMargin + 46, currentY + 6);
    doc.setFont('Helvetica', 'normal');
    doc.text('  Mulai Tanggal', 105 + 1, currentY + 6);
    doc.setFont('Helvetica', 'bold');
    doc.text(
        `  ${formatTanggalIndo(cuti.tanggal_mulai)}  s/d  ${formatTanggalIndo(cuti.tanggal_akhir)}`,
        105 + 28,
        currentY + 6,
    );
    currentY += 10;

    // --- V. CATATAN CUTI (Historical Balances Template) ---
    currentY += 4;
    drawSectionTitle('V. CATATAN CUTI');
    doc.rect(leftMargin, currentY, widthTotal, 18);
    doc.line(leftMargin + 70, currentY, leftMargin + 70, currentY + 18);
    doc.setFont('Helvetica', 'normal');
    doc.text(
        '  1. Cuti Tahunan Sisa Tahun Berjalan',
        leftMargin + 1,
        currentY + 5,
    );
    doc.text('  2. Cuti Besar Kepegawaian', leftMargin + 1, currentY + 11);
    doc.text('  3. Hak Cuti Sakit Terpakai', leftMargin + 1, currentY + 16);

    doc.text(' - Tersedia Penuh', leftMargin + 72, currentY + 5);
    doc.text(' - Belum Pernah Diambil', leftMargin + 72, currentY + 11);
    doc.text(' - 0 Hari', leftMargin + 72, currentY + 16);
    currentY += 18;

    // --- VI. ALAMAT SELAMA MENJALANKAN CUTI ---
    currentY += 4;
    drawSectionTitle('VI. ALAMAT SELAMA MENJALANKAN CUTI');
    doc.rect(leftMargin, currentY, widthTotal, 14);
    doc.line(rightBoundary - 50, currentY, rightBoundary - 50, currentY + 14); // Sekat Telpon

    doc.setFont('Helvetica', 'normal');
    doc.text('  Alamat Lengkap:', leftMargin + 1, currentY + 5);
    doc.setFont('Helvetica', 'bold');
    doc.text(`  ${cuti.alamat}`, leftMargin + 1, currentY + 10, {
        maxWidth: widthTotal - 54,
    });

    doc.setFont('Helvetica', 'normal');
    doc.text('  Telepon / WA:', rightBoundary - 49, currentY + 5);
    doc.setFont('Helvetica', 'bold');
    doc.text(`  ${cuti.no_telp}`, rightBoundary - 49, currentY + 10);
    currentY += 14;

    // --- VII & VIII. PERTIMBANGAN ATASAN DAN KEPUTUSAN PEJABAT ---
    currentY += 4;
    doc.setFont('Helvetica', 'bold');
    doc.rect(leftMargin, currentY, 90, 6);
    doc.text('  VII. PERTIMBANGAN ATASAN LANGSUNG', leftMargin, currentY + 4.5);

    doc.rect(leftMargin + 90, currentY, 90, 6);
    doc.text(
        '  VIII. KEPUTUSAN PEJABAT BERWENANG',
        leftMargin + 90,
        currentY + 4.5,
    );
    currentY += 6;

    // Draw Kotak Isian Kembar Utama Pertimbangan & Keputusan
    doc.rect(leftMargin, currentY, 90, 48);
    doc.rect(leftMargin + 90, currentY, 90, 48);

    // Isian Kolom Kiri (Pertimbangan Atasan)
    doc.setFont('Helvetica', 'bold');
    doc.text('  [ ✓ ] DISETUJUI', leftMargin + 2, currentY + 6);
    doc.setFont('Helvetica', 'normal');
    doc.text('  [     ] DITANGGUHKAN', leftMargin + 2, currentY + 12);
    doc.text('  [     ] TIDAK DISETUJUI', leftMargin + 2, currentY + 18);

    // Tanda Tangan Atasan Langsung
    doc.text('Atasan Langsung,', leftMargin + 45, currentY + 28, {
        align: 'center',
    });
    doc.setFont('Helvetica', 'bold');
    doc.text(
        `( ${cuti.atasan?.nama || 'Kasubag Kepegawaian'} )`,
        leftMargin + 45,
        currentY + 44,
        { align: 'center' },
    );

    // Isian Kolom Kanan (Keputusan Pejabat - Kepala Dinas)
    doc.setFont('Helvetica', 'bold');
    doc.text('  [ ✓ ] DISETUJUI', leftMargin + 92, currentY + 6);
    doc.setFont('Helvetica', 'normal');
    doc.text('  [     ] DITANGGUHKAN', leftMargin + 92, currentY + 12);
    doc.text('  [     ] TIDAK DISETUJUI', leftMargin + 92, currentY + 18);

    // Tanda Tangan Utama Kepala Dinas (Hardcoded Sesuai Regulasi Mandat PRD)
    doc.text(
        'KEPALA DINAS PPPA KOTA KENDARI,',
        leftMargin + 135,
        currentY + 28,
        { align: 'center' },
    );
    doc.setFont('Helvetica', 'bold');
    doc.text('FITRIANI SINAPOY, A.Pi., MP', leftMargin + 135, currentY + 41, {
        align: 'center',
    });
    doc.setFont('Helvetica', 'normal');
    doc.setFontSize(8);
    doc.text('Pembina Tk. 1, Gol. IV/b', leftMargin + 135, currentY + 44, {
        align: 'center',
    });
    doc.text('NIP. 197609102000032003', leftMargin + 135, currentY + 47, {
        align: 'center',
    });

    // Eksekusi Pemicu Download File PDF Secara Instan Sisi Client Browser
    doc.save(`FORMULIR_CUTI_${cuti.pegawai.nip}_${cuti.id}.pdf`);
}
