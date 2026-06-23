import React from 'react';
import {
    Table,
    TableHeader,
    TableBody,
    TableHead,
    TableRow,
    TableCell,
} from '@/components/ui/table';
import PasswordRowAction from './pegawai-row-action';

interface PegawaiTableProps {
    pegawaiList: any[];
    currentUser: any;
    onResetClick: (pegawai: any) => void;
}

export default function PegawaiTable({
    pegawaiList,
    currentUser,
    onResetClick,
}: PegawaiTableProps) {
    return (
        <div className="overflow-hidden rounded-xl border border-slate-100 bg-white shadow-2xs">
            <Table>
                <TableHeader className="bg-slate-50/70">
                    <TableRow>
                        <TableHead className="h-10 w-12 text-center text-xs font-bold text-slate-700">
                            No.
                        </TableHead>
                        <TableHead className="h-10 text-xs font-bold text-slate-700">
                            Nama Pegawai
                        </TableHead>
                        <TableHead className="h-10 text-xs font-bold text-slate-700">
                            NIP
                        </TableHead>
                        <TableHead className="h-10 text-xs font-bold text-slate-700">
                            Email Login
                        </TableHead>
                        <TableHead className="h-10 text-xs font-bold text-slate-700">
                            Hak Akses
                        </TableHead>
                        <TableHead className="h-10 text-right text-xs font-bold text-slate-700">
                            Tindakan
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    {pegawaiList.length === 0 ? (
                        <TableRow>
                            <TableCell
                                colSpan={6}
                                className="h-24 text-center text-xs font-medium text-slate-400"
                            >
                                Tidak ditemukan pegawai yang memiliki akun login
                                aktif.
                            </TableCell>
                        </TableRow>
                    ) : (
                        pegawaiList.map((pegawai, index) => (
                            <PasswordRowAction
                                key={pegawai.id}
                                index={index + 1} // Mengirimkan nomor urut dinamis
                                pegawai={pegawai}
                                currentUser={currentUser}
                                onResetClick={onResetClick}
                            />
                        ))
                    )}
                </TableBody>
            </Table>
        </div>
    );
}
