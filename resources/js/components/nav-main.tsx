import { Link } from '@inertiajs/react';
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { useCurrentUrl } from '@/hooks/use-current-url';
import type { NavItem } from '@/types';

interface NavMainProps {
    title: string;
    items?: NavItem[];
}

export function NavMain({ title, items = [] }: NavMainProps) {
    const { isCurrentUrl } = useCurrentUrl();

    // Mencegah rendering komponen SidebarGroup kosong jika tidak ada item di dalamnya
    if (items.length === 0) return null;

    return (
        <SidebarGroup className="px-2 py-0">
            <SidebarGroupLabel className="text-[10px] font-semibold tracking-wider text-slate-500 uppercase">
                {title}
            </SidebarGroupLabel>
            <SidebarMenu>
                {items.map((item) => (
                    <SidebarMenuItem key={item.title}>
                        <SidebarMenuButton
                            asChild
                            isActive={isCurrentUrl(item.href)}
                            tooltip={{ children: item.title }}
                            className="text-slate-300 transition-colors hover:bg-[#0F172A]/50 hover:text-white data-[active=true]:bg-[#0F172A] data-[active=true]:text-white"
                        >
                            <Link href={item.href} prefetch>
                                {item.icon && (
                                    <item.icon className="text-slate-400 group-hover:text-white group-data-[active=true]:text-white" />
                                )}
                                <span>{item.title}</span>
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                ))}
            </SidebarMenu>
        </SidebarGroup>
    );
}
