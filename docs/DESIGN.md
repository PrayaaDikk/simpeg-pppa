---
name: Sapphire Institutional System
colors:
  surface: '#f7f9fb'
  surface-dim: '#d8dadc'
  surface-bright: '#f7f9fb'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f2f4f6'
  surface-container: '#eceef0'
  surface-container-high: '#e6e8ea'
  surface-container-highest: '#e0e3e5'
  on-surface: '#191c1e'
  on-surface-variant: '#45464d'
  inverse-surface: '#2d3133'
  inverse-on-surface: '#eff1f3'
  outline: '#76777d'
  outline-variant: '#c6c6cd'
  surface-tint: '#565e74'
  primary: '#000000'
  on-primary: '#ffffff'
  primary-container: '#131b2e'
  on-primary-container: '#7c839b'
  inverse-primary: '#bec6e0'
  secondary: '#545f73'
  on-secondary: '#ffffff'
  secondary-container: '#d5e0f8'
  on-secondary-container: '#586377'
  tertiary: '#000000'
  on-tertiary: '#ffffff'
  tertiary-container: '#001a42'
  on-tertiary-container: '#3980f4'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#dae2fd'
  primary-fixed-dim: '#bec6e0'
  on-primary-fixed: '#131b2e'
  on-primary-fixed-variant: '#3f465c'
  secondary-fixed: '#d8e3fb'
  secondary-fixed-dim: '#bcc7de'
  on-secondary-fixed: '#111c2d'
  on-secondary-fixed-variant: '#3c475a'
  tertiary-fixed: '#d8e2ff'
  tertiary-fixed-dim: '#adc6ff'
  on-tertiary-fixed: '#001a42'
  on-tertiary-fixed-variant: '#004395'
  background: '#f7f9fb'
  on-background: '#191c1e'
  surface-variant: '#e0e3e5'
  border-slate: '#E2E8F0'
  surface-white: '#FFFFFF'
  text-primary: '#0F172A'
  text-muted: '#64748B'
typography:
  headline-xl:
    fontFamily: Plus Jakarta Sans
    fontSize: 40px
    fontWeight: '700'
    lineHeight: 52px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
    letterSpacing: -0.02em
  headline-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  headline-sm:
    fontFamily: Plus Jakarta Sans
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
  body-lg:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 28px
  body-md:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-sm:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  label-lg:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '600'
    lineHeight: 20px
    letterSpacing: 0.02em
  label-md:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '600'
    lineHeight: 16px
    letterSpacing: 0.04em
  headline-lg-mobile:
    fontFamily: Plus Jakarta Sans
    fontSize: 28px
    fontWeight: '700'
    lineHeight: 36px
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  unit: 8px
  container-padding: 32px
  gutter: 24px
  sidebar-width: 280px
  margin-mobile: 16px
  card-gap: 24px
---

## Brand & Style
The design system for the **Sistem Informasi Internal DP3A** is built upon a foundation of authority, modern governance, and extreme legibility. The brand personality is **Professional, Dependable, and Refined**, specifically tailored to Indonesian civil service workflows where clarity is paramount for an adult demographic.

The chosen design style is **Corporate Modern with Glassmorphic Accents**. It utilizes a deep, monochromatic blue palette to establish trust, while integrating frosted-glass effects in the sidebar to create a sense of depth and technical sophistication. High contrast is the non-negotiable standard, ensuring that every interaction point is unmistakable. The overall aesthetic balances the "stiffness" of traditional government software with the "fluidity" of modern SaaS, resulting in a workspace that feels premium yet accessible.

## Colors
This design system employs a **Deep Blue Monochromatic** hierarchy. The primary and secondary colors are reserved for structural integrity—sidebar, headers, and primary branding—creating a stable visual "anchor." 

- **Primary (#0F172A):** Used for the sidebar and high-level navigation backgrounds to establish a professional, "Midnight" workspace.
- **Secondary (#1E293B):** Used for secondary navigation elements and subtle background variations.
- **Tertiary/Interactive (#3B82F6):** A vibrant "Electric Blue" used exclusively for active states, call-to-action buttons, and progress indicators.
- **Background (#F8FAFC):** An off-white slate that reduces eye strain while maintaining high contrast against primary text.

All typography must adhere to a minimum **4.5:1 contrast ratio**. On white surfaces, use `#0F172A` for headings and `#334155` for body text to ensure maximum readability for senior users.

## Typography
The typographic strategy uses **Plus Jakarta Sans** for headlines to provide a welcoming, contemporary Indonesian feel, while **Inter** is used for body copy and data-heavy tables to ensure systematic clarity.

To accommodate older users, the base body size is set to **16px**, with **18px** used for primary content descriptions. Line heights are purposefully generous to prevent "text-crowding." For numerical data (important for salary/KGB tracking), Inter's tabular lining features should be enabled to ensure columns of figures align perfectly.

## Layout & Spacing
The layout follows a **Fixed-Fluid Hybrid** model. The sidebar remains at a fixed width of `280px`, while the main content area expands to fill the remaining viewport. 

- **Grid:** A 12-column grid is used for the main content area on desktop.
- **Rhythm:** An 8px base unit drives all spacing. For adult users, "Generous Whitespace" is the rule—avoiding dense clusters of information. 
- **Breakpoints:** 
  - **Mobile (<768px):** Sidebar collapses into a hamburger menu; margins reduce to 16px.
  - **Tablet (768px - 1024px):** Sidebar may collapse to an icon-only "rail" view.
  - **Desktop (>1024px):** Full expanded sidebar and 32px container padding.

## Elevation & Depth
Depth is used functionally to separate navigation from action. 

1.  **Sidebar (Level -1):** Uses a **Glassmorphic** approach with a `backdrop-filter: blur(12px)` and a semi-transparent `#0F172A` fill (85% opacity). This creates a sophisticated vertical anchor.
2.  **Surface (Level 0):** The main background (`#F8FAFC`) is flat.
3.  **Cards (Level 1):** Primary content containers use white backgrounds with a subtle `1px` border in `#E2E8F0` and a **Soft Ambient Shadow** (0px 4px 12px rgba(15, 23, 42, 0.05)).
4.  **Modals/Popovers (Level 2):** These use a more pronounced shadow (0px 12px 32px rgba(15, 23, 42, 0.15)) to command immediate attention for approvals and confirmation dialogs.

## Shapes
The design system uses a **16px (1rem)** standard corner radius for primary cards and containers to create an approachable, modern institutional feel. 

- **Buttons & Inputs:** Use a 12px radius to feel slightly more precise than the cards they sit within.
- **Selection Indicators:** (e.g., active sidebar menu) use a right-side "pill" shape (half-rounded) to indicate focus.
- **Icon Containers:** Use a soft 8px radius or full circles for status indicators.

## Components

### Buttons
Primary buttons use the Tertiary Blue (`#3B82F6`) with white text. They must have a minimum height of `48px` to provide a large hit-area for all users. Secondary buttons use a ghost style with a `#E2E8F0` border.

### Sidebar (Glassmorphic)
The sidebar is the system's centerpiece. Active items are highlighted with a semi-transparent white tint and a left-accent border in Electric Blue. Use large, recognizable icons (24px) paired with 14px Semi-Bold labels.

### Input Fields
Fields must have explicit labels in `label-lg` style. The "active" or "focused" state uses a `2px` Electric Blue border. Error messages must be written in plain Indonesian, avoiding technical codes.

### Cards & Tables
Cards house all data. Tables within cards should avoid vertical borders; use subtle horizontal dividers in `#E2E8F0`. Row heights should be `56px` or higher to maintain legibility.

### Status Chips
For "KGB" or "Cuti" status:
- **Approved:** Soft green background with dark green text.
- **Pending:** Soft amber background with dark brown text.
- **Rejected:** Soft red background with dark red text.
All chips use a "Pill" (rounded-full) shape.

### Modals
Modals for "Data Deletion" or "Approval" must have high-contrast buttons and a clear descriptive header. The background overlay should be a dark `#0F172A` with 40% opacity.