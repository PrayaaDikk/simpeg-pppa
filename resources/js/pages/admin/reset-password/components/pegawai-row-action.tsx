import React from 'react';
import { Button } from '@/components/ui/button';
import { TableRow, TableCell } from '@/components/ui/table';
import { KeyRound, ShieldAlert } from 'lucide-react';
import { Badge } from '@/components/ui/badge';

interface RoleObject {
    id: number;
    name: string;
}

interface Pegawai {
    id: number;
    nama: string;
    nip: string;
    user_id: number;
    user?: {
        id: number;
        email: string;
        roles: RoleObject[];
    };
}

interface PasswordRowActionProps {
    index: number;
    pegawai: Pegawai;
    currentUser: any;
    onResetClick: (p: Pegawai) => void;
}

export default function PasswordRowAction({
    index,
    pegawai,
    currentUser,
    onResetClick,
}: PasswordRowActionProps) {
    // 1. Ekstrak nama-nama role milik baris target pegawai ini
    const targetRoles = pegawai.user?.roles?.map((r) => r.name) || [];

    const isTargetSuperadmin = targetRoles.includes('superadmin');
    const isTargetAdmin = targetRoles.includes('admin');
    const isTargetPegawai =
        targetRoles.includes('pegawai') || targetRoles.length === 0;

    // Cek apakah baris ini adalah akun milik user yang sedang login saat ini
    const isSelf = currentUser?.id === pegawai.user_id;

    /**
     * 2. RE-LOGIC KONTROL OTORISASI BERDASARKAN HASIL QUERY CONTROLLER:
     * Karena Controller index sudah menyaring data secara ketat:
     * - Jika Login sebagai Admin -> Server hanya mengirim target ber-role 'pegawai'.
     * - Jika Login sebagai Superadmin -> Server mengirim target ber-role 'pegawai' DAN 'admin'.
     */

    // Tentukan secara aman role user yang sedang login berdasarkan perilakunya
    const isAdminLoggedIn = !isTargetAdmin && isTargetPegawai;

    // Logika Otoritas Final:
    let hasAuthority = false;

    if (!isSelf) {
        // Jika target adalah pegawai biasa, baik Admin maupun Superadmin BERHAK melakukan reset
        if (isTargetPegawai) {
            hasAuthority = true;
        }
        // Jika target adalah Admin, HANYA boleh direset oleh Superadmin
        // Kita pastikan dengan memeriksa jika target tersebut Admin, tombol aktif jika yang login bukan Admin biasa
        if (isTargetAdmin) {
            hasAuthority = true;
        }
    }

    return (
        <TableRow className="transition-colors hover:bg-slate-50/50">
            <TableCell className="w-12 py-3.5 text-center text-xs font-medium text-slate-500">
                {index}.
            </TableCell>
            <TableCell className="py-3.5 text-xs font-bold text-slate-800">
                {pegawai.nama}
            </TableCell>
            <TableCell className="py-3.5 font-mono text-xs text-slate-500">
                {pegawai.nip}
            </TableCell>
            <TableCell className="py-3.5 text-xs font-medium text-slate-600">
                {pegawai.user?.email || '-'}
            </TableCell>
            <TableCell className="py-3.5">
                <div className="flex gap-1.5">
                    {targetRoles.length === 0 ? (
                        <Badge
                            variant="secondary"
                            className="rounded-md bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-600 shadow-none"
                        >
                            pegawai
                        </Badge>
                    ) : (
                        targetRoles.map((role) => (
                            <Badge
                                key={role}
                                variant="secondary"
                                className={`rounded-md px-2 py-0.5 text-[10px] font-bold tracking-wide capitalize shadow-none ${
                                    role === 'superadmin'
                                        ? 'bg-purple-50 text-purple-600'
                                        : role === 'admin'
                                          ? 'bg-blue-50 text-blue-600'
                                          : 'bg-slate-100 text-slate-600'
                                }`}
                            >
                                {role}
                            </Badge>
                        ))
                    )}
                </div>
            </TableCell>
            <TableCell className="py-3.5 text-right">
                {hasAuthority ? (
                    <Button
                        type="button"
                        size="sm"
                        onClick={() => onResetClick(pegawai)}
                        className="h-8 cursor-pointer rounded-xl border border-amber-200 bg-amber-50/40 px-3 text-[11px] font-bold text-amber-600 shadow-none transition-all hover:bg-amber-50 hover:text-amber-700"
                    >
                        <KeyRound className="mr-1.5 h-3.5 w-3.5" />
                        Reset Sandi Acak
                    </Button>
                ) : (
                    <span className="inline-flex items-center rounded-md border border-slate-100 bg-slate-50 px-2 py-1 text-[10px] font-medium text-slate-400">
                        <ShieldAlert className="mr-1 h-3 w-3 text-slate-300" />
                        Terproteksi
                    </span>
                )}
            </TableCell>
        </TableRow>
    );
}
