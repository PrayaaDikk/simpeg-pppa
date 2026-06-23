import ExcelJS from 'exceljs';
import { saveAs } from 'file-saver';

// Helper hitung masa kerja secara dinamis dari tmt_pegawai
const hitungMasaKerja = (tmtString: string | null | undefined): string => {
    if (!tmtString) return '-';
    const tmtDate = new Date(tmtString);
    if (isNaN(tmtDate.getTime())) return '-';

    const today = new Date();
    let tahun = today.getFullYear() - tmtDate.getFullYear();
    let bulan = today.getMonth() - tmtDate.getMonth();

    if (bulan < 0) {
        tahun--;
        bulan += 12;
    }

    return `${tahun} Tahun ${String(bulan).padStart(2, '0')} Bulan`;
};

// Helper format tanggal Indonesia
const formatTanggalIndo = (dateString: string) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return '-';
    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    });
};

export const exportRekapCutiToExcel = async (
    filteredData: any[],
    namaBulanTerpilih: string,
    tahunTerpilih: number,
) => {
    // 1. Inisialisasi Workbook dan Worksheet
    const workbook = new ExcelJS.Workbook();
    const worksheet = workbook.addWorksheet('Rekap Cuti Pegawai');

    // Menampilkan grid lines agar pembatas cell terlihat resmi
    worksheet.views = [{ showGridLines: true }];

    // 2. Membuat Judul Dokumen (Header Lap)
    worksheet.mergeCells('A1:O1');
    const titleCell = worksheet.getCell('A1');
    titleCell.value = 'REKAPITULASI DATA PERMINTAAN DAN PEMBERIAN CUTI PEGAWAI';
    titleCell.font = { name: 'Arial', size: 14, bold: true };
    titleCell.alignment = { horizontal: 'center', vertical: 'middle' };

    worksheet.mergeCells('A2:O2');
    const subTitleCell = worksheet.getCell('A2');
    subTitleCell.value = `Periode: ${namaBulanTerpilih} ${tahunTerpilih}`;
    subTitleCell.font = { name: 'Arial', size: 11, italic: true };
    subTitleCell.alignment = { horizontal: 'center', vertical: 'middle' };

    worksheet.mergeCells('A3:O3');
    const instansiCell = worksheet.getCell('A3');
    instansiCell.value =
        'Dinas Pemberdayaan Perempuan dan Perlindungan Anak Kota Kendari';
    instansiCell.font = { name: 'Arial', size: 11, bold: true };
    instansiCell.alignment = { horizontal: 'center', vertical: 'middle' };

    worksheet.addRow([]); // Baris kosong pembatas

    // 3. Menentukan Struktur Kolom dan Group Header (Baris 5 & 6)
    // Kita buat dua baris header karena ada sub-kolom untuk 'Catatan Sisa Cuti' (N, N-1, N-2)
    const headerRow5 = worksheet.addRow([
        'No',
        'Nama Pegawai',
        'NIP',
        'Jabatan',
        'Masa Kerja',
        'Jenis Cuti',
        'Alasan Cuti',
        'Lama Cuti',
        'Tanggal Mulai',
        'Tanggal Akhir',
        'Catatan Sisa Cuti',
        '',
        '', // Untuk jatah N-2, N-1, N
        'Alamat Cuti',
        'No. Telp',
    ]);

    const headerRow6 = worksheet.addRow([
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        'N-2',
        'N-1',
        'Tahun Ini', // Sub-header jatah cuti
        '',
        '',
    ]);

    // 4. Penggabungan Cell (Merge) Vertikal untuk Header Utama
    const colsToMerge = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 14, 15];
    colsToMerge.forEach((colIdx) => {
        worksheet.mergeCells(5, colIdx, 6, colIdx);
    });

    // Penggabungan Horizontal untuk Group Catatan Cuti
    worksheet.mergeCells('K5:M5');

    // 5. Mewarnai dan Memberikan Border pada Header
    const borderStyle: Partial<ExcelJS.Borders> = {
        top: { style: 'thin', color: { argb: 'BFBFBF' } },
        left: { style: 'thin', color: { argb: 'BFBFBF' } },
        bottom: { style: 'thin', color: { argb: 'BFBFBF' } },
        right: { style: 'thin', color: { argb: 'BFBFBF' } },
    };

    [headerRow5, headerRow6].forEach((row) => {
        row.eachCell((cell) => {
            cell.fill = {
                type: 'pattern',
                pattern: 'solid',
                fgColor: { argb: 'F2F4F7' }, // Warna abu-abu soft modern (Tailwind slate-100)
            };
            cell.font = {
                name: 'Arial',
                size: 10,
                bold: true,
                color: { argb: '334155' },
            };
            cell.alignment = {
                horizontal: 'center',
                vertical: 'middle',
                wrapText: true,
            };
            cell.border = borderStyle;
        });
    });

    worksheet.getRow(5).height = 25;
    worksheet.getRow(6).height = 20;

    // 6. Memasukkan Baris Data Rows secara Dinamis
    filteredData.forEach((cuti, index) => {
        const rowData = [
            index + 1,
            cuti.pegawai?.nama || '-',
            cuti.pegawai?.nip || '-',
            cuti.pegawai?.jabatan?.nama_jabatan || '-',
            hitungMasaKerja(cuti.pegawai?.tmt_pegawai), // Menggunakan tmt_pegawai database
            cuti.jenis_cuti?.replace(/_/g, ' ').toUpperCase() || '-',
            cuti.alasan_cuti || '-',
            `${cuti.lama_cuti} Hari`,
            formatTanggalIndo(cuti.tanggal_mulai),
            formatTanggalIndo(cuti.tanggal_akhir),
            cuti.pegawai?.jatah_cuti_dua_tahun_lalu ?? 0, // N-2 sesuai CUTI_template
            cuti.pegawai?.jatah_cuti_satu_tahun_lalu ?? 0, // N-1 sesuai CUTI_template
            cuti.pegawai?.jatah_cuti_tahun_ini ?? 0, // N berjalan sesuai CUTI_template
            cuti.alamat || '-',
            cuti.no_telp || '-',
        ];

        const addedRow = worksheet.addRow(rowData);
        addedRow.height = 22;

        // Styling untuk tiap cell di baris data
        addedRow.eachCell((cell, colIdx) => {
            cell.font = { name: 'Arial', size: 9 };
            cell.border = borderStyle;

            // Atur posisi perataan teks (alignment)
            if ([1, 3, 8, 9, 10, 11, 12, 13, 15].includes(colIdx)) {
                cell.alignment = { horizontal: 'center', vertical: 'middle' };
            } else {
                cell.alignment = {
                    horizontal: 'left',
                    vertical: 'middle',
                    wrapText: true,
                };
            }
        });
    });

    // 7. Auto-fit Ukuran Lebar Kolom agar rapi tidak terpotong (###)
    worksheet.columns.forEach((column: any) => {
        let maxLen = 10;
        column.eachCell((cell: any) => {
            if (cell.value) {
                const len = cell.value.toString().length;
                if (len > maxLen) maxLen = len;
            }
        });
        column.width = maxLen < 35 ? maxLen + 4 : 35; // Batasi maksimal lebar 35 agar tidak terlalu melar
    });
    worksheet.getColumn(1).width = 6; // Kolom nomor diperkecil

    // 8. Generate dan Unduh Berkas secara Otomatis via Browser
    const buffer = await workbook.xlsx.writeBuffer();
    const blob = new Blob([buffer], {
        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    });

    const timestamp = new Date().toISOString().slice(0, 10).replace(/-/g, '');
    saveAs(blob, `Rekap_Cuti_Pegawai_${timestamp}.xlsx`);
};
