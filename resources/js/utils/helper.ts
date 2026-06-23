export const hitungMasaKerja = (
    tmtString: string | null | undefined,
): string => {
    if (!tmtString) return '-';

    const tmtDate = new Date(tmtString);
    // Validasi jika format tanggal tidak valid
    if (isNaN(tmtDate.getTime())) return '-';

    const today = new Date();

    let tahun = today.getFullYear() - tmtDate.getFullYear();
    let bulan = today.getMonth() - tmtDate.getMonth();

    // Penyesuaian jika hitungan bulan bernilai negatif
    if (bulan < 0) {
        tahun--;
        bulan += 12;
    }

    // Penyesuaian jika hari ini belum melewati hari tanggal TMT pada bulan berjalan
    if (today.getDate() < tmtDate.getDate() && bulan > 0) {
        // Kurangi 1 bulan jika hari ini belum mencapai tanggal TMT di bulan ini
        // Tapi jika bulan sudah 0 karena penyesuaian tahun di atas, biarkan atau sesuaikan lagi
        // Untuk standar kepegawaian sederhana, pembulatan bulan/tahun umumnya seperti ini:
    }

    // Format string agar selalu 2 digit untuk bulan (misal: 07 Bulan) jika diinginkan
    const stringBulan = String(bulan).padStart(2, '0');
    const stringTahun = String(tahun).padStart(2, '0'); // opsional jika tahun ingin 2 digit juga

    return `${tahun} Tahun ${stringBulan} Bulan`;
};

export function formatToInputDate(
    dateSource: string | Date | null | undefined,
): string {
    if (!dateSource) return '';

    try {
        const date =
            dateSource instanceof Date ? dateSource : new Date(dateSource);

        // Cek jika objek date tidak valid (asalkan string ngawur lolos parsed)
        if (isNaN(date.getTime())) return '';

        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    } catch {
        return '';
    }
}
