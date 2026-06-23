import React from 'react';
import { Card } from '@/components/ui/card';
import {
    Table,
    TableHeader,
    TableBody,
    TableHead,
    TableRow,
    TableCell,
} from '@/components/ui/table';
import Pagination from '@/components/ui/pagination-shared';
import PegawaiTableRow from './pegawai-table-row';
import type { Pegawai, PegawaiPaginator } from './pegawai-types';

interface PegawaiTableProps {
    pegawaiList: PegawaiPaginator;
    onEdit: (pegawai: Pegawai) => void;
    onDelete: (pegawai: Pegawai) => void;
}

export default function PegawaiTable({
    pegawaiList,
    onEdit,
    onDelete,
}: PegawaiTableProps) {
    return (
        <Card className="overflow-hidden rounded-xl border border-slate-100 bg-white shadow-sm">
            <Table>
                <TableHeader className="bg-slate-50/70 backdrop-blur-sm">
                    <TableRow className="border-b border-slate-100 hover:bg-transparent">
                        <TableHead className="w-[60px] pl-6 text-center text-xs font-semibold tracking-wider text-slate-500 uppercase">
                            No
                        </TableHead>
                        <TableHead className="w-[280px] text-xs font-semibold tracking-wider text-slate-500 uppercase">
                            Pegawai
                        </TableHead>
                        <TableHead className="text-xs font-semibold tracking-wider text-slate-500 uppercase">
                            Jabatan & Bidang
                        </TableHead>
                        <TableHead className="text-xs font-semibold tracking-wider text-slate-500 uppercase">
                            Pangkat & Golongan
                        </TableHead>
                        <TableHead className="text-xs font-semibold tracking-wider text-slate-500 uppercase">
                            Pendidikan Terakhir
                        </TableHead>
                        <TableHead className="w-[120px] text-center text-xs font-semibold tracking-wider text-slate-500 uppercase">
                            Status
                        </TableHead>
                        <TableHead className="w-[180px] pr-6 text-right text-xs font-semibold tracking-wider text-slate-500 uppercase">
                            Aksi
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    {pegawaiList.data.length === 0 ? (
                        <TableRow>
                            <TableCell
                                colSpan={7}
                                className="h-40 pr-6 pl-6 text-center text-sm text-slate-400"
                            >
                                <div className="flex flex-col items-center justify-center gap-2">
                                    <span className="font-medium text-slate-500">
                                        Data Tidak Ditemukan
                                    </span>
                                    <p className="text-xs text-slate-400">
                                        Tidak ditemukan data pegawai yang
                                        cocok dengan kriteria filter tersebut.
                                    </p>
                                </div>
                            </TableCell>
                        </TableRow>
                    ) : (
                        pegawaiList.data.map((pegawai, index) => (
                            <PegawaiTableRow
                                key={pegawai.id}
                                pegawai={pegawai}
                                rowNumber={
                                    (pegawaiList.current_page - 1) *
                                        pegawaiList.per_page +
                                    index +
                                    1
                                }
                                onEdit={onEdit}
                                onDelete={onDelete}
                            />
                        ))
                    )}
                </TableBody>
            </Table>

            <Pagination
                links={pegawaiList.links}
                from={pegawaiList.from}
                to={pegawaiList.to}
                total={pegawaiList.total}
            />
        </Card>
    );
}
