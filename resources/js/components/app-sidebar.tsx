import { Link, usePage } from '@inertiajs/react';
import {
    CalendarDays,
    LayoutGrid,
    TrendingUp,
    Users,
    CalendarClock,
    ShieldCheck,
    Briefcase,
    IdCard,
    LockOpen,
} from 'lucide-react';
import AppLogo from '@/components/app-logo';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import {
    dashboard,
    // cuti,
    pegawai,
    // kgb,
    pegawaiCuti,
    masterPangkat,
    masterBidang,
    masterJabatan,
} from '@/routes';

import { index as delegasi } from '@/routes/admin/delegasi';
import { index as cuti } from '@/routes/cuti';
import { index as kgb } from '@/routes/kgb';
import { index as resetPassword } from '@/routes/reset-password';

import type { NavItem } from '@/types';

export function AppSidebar() {
    // PART 2: COMPONENT ROLE EXTRACTION LAYER (CRITICAL)
    // Ekstraksi data autentikasi dan peran (role) dari session global Inertia.js
    const { auth } = usePage().props as any;
    const userRole = auth?.user?.role; // Bernilai 'admin' atau 'pegawai'

    // 1. Matriks Menu untuk Group Label: "Aplikasi"
    const aplikasiItems: NavItem[] = [
        {
            title: 'Dashboard',
            href: dashboard(),
            icon: LayoutGrid,
        },
    ];

    // Jika pengguna adalah Admin, tambahkan menu manajemen internal ke grup "Aplikasi"
    if (userRole === 'admin' || userRole === 'superadmin') {
        aplikasiItems.push(
            {
                title: 'Manajemen Pegawai',
                href: pegawai(),
                icon: Users,
            },
            {
                title: 'Manajemen Cuti',
                href: cuti(),
                icon: CalendarDays,
            },
            {
                title: 'Manajemen KGB',
                href: kgb(),
                icon: TrendingUp,
            },
        );
    }

    // 2. Matriks Menu untuk Group Label: "Pegawai"
    // Baik Admin maupun Pegawai memiliki akses ke menu ini berdasarkan spesifikasi PRD
    const pegawaiItems: NavItem[] = [
        {
            title: 'Ajukan Cuti',
            href: pegawaiCuti(),
            icon: CalendarClock,
        },
    ];

    const masterData: NavItem[] = [
        {
            title: 'Pangkat',
            href: masterPangkat(),
            icon: ShieldCheck,
        },
        {
            title: 'Bidang',
            href: masterBidang(),
            icon: Briefcase,
        },
        {
            title: 'Jabatan',
            href: masterJabatan(),
            icon: IdCard,
        },
    ];

    const userItems: NavItem[] = [
        {
            title: 'Atur Ulang Sandi',
            href: resetPassword(),
            icon: LockOpen,
        },
    ];

    if (auth.user.role === 'superadmin') {
        userItems.push({
            title: 'Delegasi Akun',
            href: delegasi(),
            icon: Users,
        });
    }

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={dashboard()} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                {/* Render Grup Pertama dengan Label Aplikasi */}
                <NavMain title="Aplikasi" items={aplikasiItems} />

                {/* Render Grup Kedua dengan Label Pegawai */}
                {/* <NavMain title="Pegawai" items={pegawaiItems} /> */}

                {(auth.user.role === 'admin' ||
                    auth.user.role === 'superadmin') && (
                    <NavMain title="Master Data" items={masterData} />
                )}

                {(auth.user.role === 'admin' ||
                    auth.user.role === 'superadmin') && (
                    <NavMain title="Pengguna" items={userItems} />
                )}
            </SidebarContent>

            <SidebarFooter>
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
