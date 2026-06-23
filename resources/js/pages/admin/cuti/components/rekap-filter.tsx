import React from 'react';
import { CalendarDays, Users, Search } from 'lucide-react';

interface RekapFilterProps {
    selectedBulan: number;
    setSelectedBulan: (bulan: number) => void;
    selectedTahun: number;
    setSelectedTahun: (tahun: number) => void;
    selectedPegawaiId: string;
    setSelectedPegawaiId: (id: string) => void;
    pegawaiList: any[];
    namaBulan: string[];
}

export default function RekapFilter({
    selectedBulan,
    setSelectedBulan,
    selectedTahun,
    setSelectedTahun,
    selectedPegawaiId,
    setSelectedPegawaiId,
    pegawaiList,
    namaBulan,
}: RekapFilterProps) {
    return (
        <div className="grid grid-cols-1 gap-4 sm:grid-cols-3">
            {/* Filter Pegawai */}
            <div className="relative">
                <label className="mb-1.5 block text-xs font-bold tracking-wide text-slate-600">
                    Pegawai
                </label>
                <div className="relative">
                    <Users className="absolute top-3 left-3 h-4 w-4 text-slate-400" />
                    <select
                        value={selectedPegawaiId}
                        onChange={(e) => setSelectedPegawaiId(e.target.value)}
                        className="w-full rounded-xl border border-slate-200 bg-white p-2.5 pr-3 pl-9 text-xs font-semibold text-slate-700 shadow-xs transition-all focus:border-slate-400 focus:ring-1 focus:ring-slate-400 focus:outline-hidden"
                    >
                        <option value="all">Semua Pegawai</option>
                        {pegawaiList.map((p) => (
                            <option key={p.id} value={p.id}>
                                {p.nama}
                            </option>
                        ))}
                    </select>
                </div>
            </div>

            {/* Filter Bulan */}
            <div>
                <label className="mb-1.5 block text-xs font-bold tracking-wide text-slate-600">
                    Bulan Rekap
                </label>
                <div className="relative">
                    <CalendarDays className="absolute top-3 left-3 h-4 w-4 text-slate-400" />
                    <select
                        value={selectedBulan}
                        onChange={(e) => {
                            const val = e.target.value;
                            setSelectedBulan(
                                val === 'all' ? 'all' : Number(val),
                            );
                        }}
                        className="w-full rounded-xl border border-slate-200 bg-white p-2.5 pr-3 pl-9 text-xs font-semibold text-slate-700 shadow-xs transition-all focus:border-slate-400 focus:ring-1 focus:ring-slate-400 focus:outline-hidden"
                    >
                        <option value="all">Semua Bulan</option>
                        {namaBulan.map((bln, idx) => (
                            <option key={idx} value={idx + 1}>
                                {bln}
                            </option>
                        ))}
                    </select>
                </div>
            </div>

            {/* Filter Tahun (Custom Input Modern) */}
            <div>
                <label className="mb-1.5 block text-xs font-bold tracking-wide text-slate-600">
                    Tahun
                </label>
                <div className="relative">
                    <input
                        type="number"
                        placeholder="Pilih Tahun"
                        value={selectedTahun || ''}
                        onChange={(e) => {
                            const val = e.target.value;
                            setSelectedTahun(
                                val === ''
                                    ? new Date().getFullYear()
                                    : Number(val),
                            );
                        }}
                        className="w-full [appearance:textfield] rounded-xl border border-slate-200 bg-white p-2.5 text-xs font-semibold text-slate-700 shadow-xs transition-all focus:border-slate-400 focus:ring-1 focus:ring-slate-400 focus:outline-hidden [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none"
                    />
                    <span className="pointer-events-none absolute top-2.5 right-3 rounded-md bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-400">
                        YYYY
                    </span>
                </div>
            </div>
        </div>
    );
}
