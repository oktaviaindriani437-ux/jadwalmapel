<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Jadwal Skanic')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <style>
        /* ============================================================
           SKANIC MODERN ACADEMIC PORTAL THEME (NYAMAN DI MATA & ELEGAN)
           Soft Cool Mist Canvas • Calm Academic Navy • Refined Royal Blue
           ============================================================ */
        :root {
            /* Refined Academic Blue Palette (Tenang, Bersih, Modern) */
            --primary: #2563eb;             /* Calm Modern Academic Blue */
            --primary-hover: #1d4ed8;       /* Deep Royal Blue */
            --primary-active: #1e40af;      /* Navy Blue */
            --primary-light: #eff6ff;       /* Airy Blue Mist */
            --primary-subtle: #f5f9ff;      /* Softest Tint */
            --primary-border: #bfdbfe;      /* Delicate Blue Outline */
            --primary-glow: rgba(37, 99, 235, 0.16);

            /* Harmonious Muted Accents */
            --accent-cyan: #0891b2;
            --accent-teal: #0d9488;
            --accent-gold: #d97706;
            --accent-emerald: #059669;
            --accent-purple: #4f46e5;
            --accent-coral: #dc2626;

            /* Sidebar Theme: Calm Academic Steel-Navy (Lembut, Berwibawa, Tidak Gelap Pekat) */
            --sidebar-bg: linear-gradient(180deg, #1e3a5f 0%, #193152 55%, #152844 100%);
            --sidebar-hover: rgba(147, 197, 253, 0.12);
            --sidebar-active: rgba(96, 165, 250, 0.2);
            --sidebar-border: rgba(147, 197, 253, 0.14);

            /* Page, Cards & Surfaces (Lembut di Mata, Kontras Seimbang) */
            --page-bg: #f4f7fb;             /* Soft cool mist canvas */
            --card-bg: #ffffff;
            --border-light: #e5ebf3;
            --border-medium: #cfd8e3;
            --border-strong: #94a3b8;
            --border-dark: #2563eb;

            /* Table Header (Soft Slate - Ringan & Mudah Dibaca) */
            --table-header-bg: #f1f5f9;
            --table-header-text: #334155;

            /* Typography */
            --text-main: #0f172a;
            --text-secondary: #334155;
            --text-muted: #64748b;

            /* Shadows & Radii */
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;
            --shadow-sm: 0 1px 2px rgba(15, 23, 42, 0.04);
            --shadow-md: 0 4px 14px -2px rgba(15, 23, 42, 0.06), 0 1px 3px rgba(15, 23, 42, 0.04);
            --shadow-lg: 0 10px 28px -4px rgba(15, 23, 42, 0.1), 0 4px 10px -2px rgba(15, 23, 42, 0.04);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            max-width: 100vw;
            overflow-x: hidden !important;
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--page-bg);
            color: var(--text-main);
            min-height: 100vh;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        /* Subtle Neutral Scrollbars (Tenang & Tidak Mencolok) */
        ::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 99px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .app-shell {
            display: flex;
            min-height: 100vh;
            width: 100%;
            max-width: 100vw;
            box-sizing: border-box;
            position: relative;
        }

        /* Calm Academic School Sidebar (Fixed di Desktop, Permanen Tidak Pernah Tergulung) */
        .sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            border-right: 1px solid var(--sidebar-border);
            box-shadow: 2px 0 16px rgba(15, 23, 42, 0.08);
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
        }

        .sidebar::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.15);
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(147, 197, 253, 0.35);
            border-radius: 4px;
        }
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(147, 197, 253, 0.55);
        }

        .brand {
            padding: 20px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.09);
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(15, 23, 42, 0.14);
        }

        .brand-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.28);
        }

        .brand-title {
            font-size: 16px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.2px;
        }

        .brand-subtitle {
            font-size: 11.5px;
            color: #93c5fd;
            font-weight: 600;
        }

        .nav-section {
            padding: 16px 12px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .nav-label {
            font-size: 10.5px;
            font-weight: 700;
            color: #93c5fd;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 10px 12px 6px 12px;
            opacity: 0.85;
        }

        .nav a {
            text-decoration: none;
            color: #dbeafe;
            padding: 10px 14px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            font-size: 13.5px;
            transition: all 0.18s ease;
            border-left: 3px solid transparent;
        }

        .nav a svg {
            width: 18px;
            height: 18px;
            stroke-width: 2;
            flex-shrink: 0;
            color: #93c5fd;
            transition: color 0.18s ease;
        }

        .nav a:hover {
            background: var(--sidebar-hover);
            color: #ffffff;
        }

        .nav a:hover svg {
            color: #ffffff;
        }

        .nav a.active {
            background: var(--sidebar-active);
            color: #ffffff;
            font-weight: 700;
            border-left-color: #60a5fa;
        }

        .nav a.active svg {
            color: #93c5fd;
        }

        .nav-footer {
            padding: 14px;
            border-top: 1px solid rgba(255, 255, 255, 0.09);
            background: rgba(15, 23, 42, 0.14);
        }

        .btn-sidebar-logout {
            width: 100%;
            font-size: 13px;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.07);
            color: #dbeafe;
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 8px;
            padding: 10px 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.18s ease;
        }

        .btn-sidebar-logout:hover {
            background: rgba(239, 68, 68, 0.18);
            border-color: rgba(248, 113, 113, 0.6);
            color: #fee2e2;
        }

        /* Main Workspace (Presisi Pas Lebar Layar, Bebas Scrollbar Horizontal Bawah) */
        .content {
            margin-left: 260px;
            flex: 1;
            min-width: 0;
            padding: 24px 32px;
            width: calc(100% - 260px);
            max-width: calc(100% - 260px);
            box-sizing: border-box;
            overflow-x: hidden;
        }

        /* Top Header Bar */
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ffffff;
            padding: 15px 22px;
            border-radius: 14px;
            border: 1px solid var(--border-light);
            box-shadow: var(--shadow-sm);
            margin-bottom: 20px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .topbar:hover {
            border-color: #cbd5e1;
            box-shadow: var(--shadow-md);
        }

        .topbar-title h2 {
            margin: 0;
            font-size: 18.5px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.3px;
        }

        .topbar-date {
            font-size: 12px;
            color: #2563eb;
            font-weight: 600;
            margin-top: 2px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .user-box {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 800;
            font-size: 15px;
            border: 2px solid #eff6ff;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.2);
        }

        /* Metric Grid & Soft Academic Cards */
        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        @media (max-width: 1200px) {
            .grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }

        /* Entrance Animation */
        @keyframes slideFadeUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .metric-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 18px 20px;
            border: 1px solid var(--border-light);
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
            opacity: 1 !important;
        }

        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 3.5px;
            background: #3b82f6;
            opacity: 0.75;
        }

        .grid .metric-card:nth-child(1)::before { background: #3b82f6; }
        .grid .metric-card:nth-child(2)::before { background: #10b981; }
        .grid .metric-card:nth-child(3)::before { background: #f59e0b; }
        .grid .metric-card:nth-child(4)::before { background: #6366f1; }

        .metric-card:hover {
            transform: translateY(-2px);
            border-color: #cbd5e1;
            box-shadow: var(--shadow-md);
        }

        .grid .metric-card:nth-child(1) { animation-delay: 0.03s; }
        .grid .metric-card:nth-child(2) { animation-delay: 0.07s; }
        .grid .metric-card:nth-child(3) { animation-delay: 0.11s; }
        .grid .metric-card:nth-child(4) { animation-delay: 0.15s; }

        .metric-info .label {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 5px;
            display: block;
        }

        .metric-info .value {
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
            font-variant-numeric: tabular-nums;
        }

        .metric-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #dbeafe;
            transition: transform 0.18s ease;
        }

        .grid .metric-card:nth-child(1) .metric-icon { background: #eff6ff; color: #2563eb; border-color: #dbeafe; }
        .grid .metric-card:nth-child(2) .metric-icon { background: #ecfdf5; color: #059669; border-color: #d1fae5; }
        .grid .metric-card:nth-child(3) .metric-icon { background: #fffbeb; color: #d97706; border-color: #fef3c7; }
        .grid .metric-card:nth-child(4) .metric-icon { background: #eef2ff; color: #4f46e5; border-color: #e0e7ff; }

        .metric-card:hover .metric-icon {
            transform: scale(1.05);
        }

        /* Structural Cards */
        .card {
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid var(--border-light);
            box-shadow: var(--shadow-sm);
            margin-bottom: 20px;
            overflow: hidden;
            opacity: 1 !important;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            border-color: #d5dfec;
            box-shadow: var(--shadow-md);
        }

        .grid-card {
            transition: transform 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
            border-radius: 10px !important;
            opacity: 1 !important;
        }

        .grid-card:hover {
            transform: translateY(-2px);
            border-color: #93c5fd !important;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.1) !important;
        }

        .card-header {
            padding: 16px 22px;
            background: #ffffff;
            border-bottom: 1px solid var(--border-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .card-header h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
        }

        .card-body {
            padding: 20px 22px;
        }

        /* ============================================================
           DATA TABLES (CLEAN SOFT SLATE - RINGAN & NYAMAN DIBACA)
           ============================================================ */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            border: 1px solid var(--border-light);
            border-radius: 10px;
            background: #ffffff;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 13.5px;
            text-align: left;
            background: #ffffff;
        }

        thead th {
            background: var(--table-header-bg);
            color: var(--table-header-text);
            font-weight: 700;
            font-size: 11.5px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 12px 16px;
            border-bottom: 1.5px solid #e2e8f0;
            white-space: nowrap;
        }

        thead th:first-child {
            border-top-left-radius: 8px;
        }

        thead th:last-child {
            border-top-right-radius: 8px;
        }

        tbody th {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-weight: 700;
            text-align: left;
            vertical-align: middle;
        }

        tbody td {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
            border-right: none;
            color: #1e293b;
            vertical-align: middle;
        }

        tbody tr:nth-child(even) {
            background-color: #fafbfd;
        }

        tbody tr:last-child td,
        tbody tr:last-child th {
            border-bottom: none;
        }

        tbody tr {
            transition: background-color 0.14s ease;
        }

        tbody tr:hover {
            background-color: #f5f9ff !important;
        }

        .col-number {
            width: 50px;
            text-align: center;
            font-weight: 700;
            color: #64748b;
            background: transparent;
        }

        /* Form Controls */
        label {
            display: block;
            margin-bottom: 6px;
            font-size: 12px;
            font-weight: 700;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        input[type="text"],
        input[type="time"],
        input[type="date"],
        input[type="password"],
        select,
        textarea {
            width: 100%;
            font-family: inherit;
            padding: 9px 13px;
            border: 1.5px solid #d5dfec;
            border-radius: 8px;
            font-size: 13.5px;
            background: #ffffff;
            color: #0f172a;
            outline: none;
            transition: border-color 0.18s ease, box-shadow 0.18s ease;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.14);
        }

        /* Action Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background: #2563eb;
            color: #ffffff;
            border: none;
            padding: 9px 16px;
            border-radius: 9px;
            text-decoration: none;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            white-space: nowrap;
            box-shadow: 0 1px 3px rgba(37, 99, 235, 0.24);
            transition: all 0.18s ease;
        }

        .btn:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.26);
        }

        .btn:active {
            transform: translateY(0);
            box-shadow: 0 1px 2px rgba(37, 99, 235, 0.2);
        }

        .btn.secondary {
            background: #ffffff;
            color: #334155;
            border: 1.5px solid #d5dfec;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        }

        .btn.secondary:hover {
            background: #f8fafc;
            border-color: #94a3b8;
            color: #0f172a;
            transform: translateY(-1px);
        }

        .btn.danger {
            background: #ef4444;
            border: none;
            color: #ffffff;
            box-shadow: 0 1px 3px rgba(239, 68, 68, 0.22);
        }

        .btn.danger:hover {
            background: #dc2626;
        }

        .btn.success {
            background: #10b981;
            border: none;
            color: #ffffff;
            box-shadow: 0 1px 3px rgba(16, 185, 129, 0.22);
        }

        .btn.success:hover {
            background: #059669;
        }

        /* Badges & Tags (Soft Pastel & Eye-Friendly) */
        .badge, .pill {
            display: inline-flex;
            align-items: center;
            padding: 3px 9px;
            border-radius: 6px;
            font-size: 11.5px;
            font-weight: 600;
            border: 1px solid transparent;
            line-height: 1.3;
        }

        .badge-code {
            font-family: 'JetBrains Mono', monospace;
            background: #f8fafc;
            color: #334155;
            border-color: #e2e8f0;
        }

        .badge-blue, .pill.blue {
            background: #eff6ff;
            color: #1d4ed8;
            border-color: #dbeafe;
        }

        .badge-green, .pill.green {
            background: #ecfdf5;
            color: #047857;
            border-color: #d1fae5;
        }

        .badge-purple, .pill.purple {
            background: #eef2ff;
            color: #4338ca;
            border-color: #e0e7ff;
        }

        .badge-amber, .pill.amber {
            background: #fffbeb;
            color: #b45309;
            border-color: #fef3c7;
        }

        form.inline {
            display: inline;
        }

        /* Alerts */
        .alert-success {
            background: #f0fdf4;
            border: 1.5px solid #86efac;
            color: #166534;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-weight: 700;
            font-size: 13.5px;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.1);
        }

        .alert-error {
            background: #fef2f2;
            border: 1.5px solid #fca5a5;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 13.5px;
            box-shadow: 0 2px 8px rgba(220, 38, 38, 0.1);
        }

        /* Delete Modal Effect */
        @keyframes deleteModalIn {
            0% {
                opacity: 0;
                transform: scale(0.92) translateY(-12px);
            }
            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        @keyframes rowDeleteSlideOut {
            0% {
                background-color: #fee2e2 !important;
                transform: translateX(0);
                opacity: 1;
            }
            20% {
                background-color: #fca5a5 !important;
                transform: translateX(-8px);
            }
            45% {
                transform: translateX(12px);
                opacity: 0.85;
            }
            100% {
                background-color: #fee2e2 !important;
                transform: translateX(70px);
                opacity: 0;
            }
        }

        .row-deleting {
            animation: rowDeleteSlideOut 0.4s cubic-bezier(0.2, 0, 0.2, 1) forwards !important;
            pointer-events: none;
        }

        @media (max-width: 900px) {
            .app-shell {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                height: auto;
                position: static;
                border-right: none;
                border-bottom: 1px solid rgba(56, 189, 248, 0.2);
            }
            .brand {
                padding: 14px 18px;
            }
            .nav-section {
                padding: 10px 14px;
                flex-direction: column;
                gap: 6px;
            }
            .nav {
                display: flex;
                flex-wrap: wrap;
                gap: 6px;
                width: 100%;
            }
            .nav a {
                padding: 8px 12px;
                font-size: 12.5px;
                border-left: none;
                border-bottom: 2.5px solid transparent;
            }
            .nav a.active {
                border-left: none;
                border-bottom-color: #38bdf8;
            }
            .nav-footer {
                padding: 10px 14px;
            }
            .content {
                margin-left: 0 !important;
                padding: 18px 16px;
                width: 100% !important;
                max-width: 100% !important;
            }
            .topbar {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                padding: 12px 18px;
                gap: 12px;
            }
        }

        @media print {
            .sidebar {
                display: none !important;
            }
            .content {
                margin-left: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }
        }

        @media (max-width: 640px) {
            .content {
                padding: 12px 10px;
            }
            .topbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
                padding: 12px 14px;
            }
            .user-box {
                width: 100%;
                justify-content: flex-start;
            }
            .grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            .metric-card {
                padding: 14px 16px;
            }
            .card-header {
                padding: 12px 14px;
            }
            .card-body {
                padding: 14px;
            }
            table {
                min-width: 580px;
            }
        }
    </style>
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <div class="brand">
                <div class="brand-icon">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="brand-text">
                    <div class="brand-title">Jadwal Skanic</div>
                    <div class="brand-subtitle">Sistem Akademik</div>
                </div>
            </div>

            <div class="nav-section">
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <div class="nav-label">Menu Admin</div>
                    <nav class="nav">
                        <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>
                        <a class="{{ request()->routeIs('admin.schedules*') ? 'active' : '' }}" href="{{ route('admin.schedules') }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Jadwal Pelajaran
                        </a>
                        <a class="{{ request()->routeIs('admin.subjects*') ? 'active' : '' }}" href="{{ route('admin.subjects') }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            Mata Pelajaran
                        </a>
                        <a class="{{ request()->routeIs('admin.teachers*') ? 'active' : '' }}" href="{{ route('admin.teachers') }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Data Guru
                        </a>
                        <a class="{{ request()->routeIs('admin.classes*') ? 'active' : '' }}" href="{{ route('admin.classes') }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Data Kelas
                        </a>
                        <a class="{{ request()->routeIs('admin.profile') ? 'active' : '' }}" href="{{ route('admin.profile') }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Profil Akun
                        </a>
                    </nav>
                @else
                    <div class="nav-label">Menu Siswa</div>
                    <nav class="nav">
                        <a class="{{ request()->routeIs('student.dashboard') ? 'active' : '' }}" href="{{ route('student.dashboard') }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>
                        <a class="{{ request()->routeIs('student.schedule*') ? 'active' : '' }}" href="{{ route('student.schedule') }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Jadwal Saya
                        </a>
                        <a class="{{ request()->routeIs('student.subjects*') ? 'active' : '' }}" href="{{ route('student.subjects') }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            Daftar Pelajaran
                        </a>
                        <a class="{{ request()->routeIs('student.profile') ? 'active' : '' }}" href="{{ route('student.profile') }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Profil Saya
                        </a>
                    </nav>
                @endif
            </div>

            <div class="nav-footer">
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button class="btn-sidebar-logout" type="submit">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Keluar (Logout)
                    </button>
                </form>
            </div>
        </aside>

        <main class="content">
            <div class="topbar">
                <div class="topbar-title">
                    <h2>@yield('page-title')</h2>
                    <span class="topbar-date">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:#0284c7;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ now()->translatedFormat('l, d F Y') }}
                    </span>
                </div>
                <div class="user-box">
                    <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
                    <div>
                        <div style="font-weight: 800; font-size: 13.5px; color: #0f172a;">{{ auth()->user()->name ?? 'User' }}</div>
                        <span class="badge {{ (auth()->user()->role ?? '') === 'admin' ? 'badge-purple' : 'badge-green' }}">
                            {{ (auth()->user()->role ?? '') === 'admin' ? 'Administrator' : 'Siswa' }}
                        </span>
                    </div>
                </div>
            </div>

            @yield('content')
        </main>
    </div>

    <!-- Global Delete Confirmation Modal (Modern Theme) -->
    <div id="skanicDeleteModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center; padding:16px;">
        <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:14px; max-width:440px; width:100%; box-shadow:0 20px 45px -10px rgba(15,23,42,0.25); overflow:hidden; animation:deleteModalIn 0.22s cubic-bezier(0.16,1,0.3,1);">
            <div style="background:#fef2f2; border-bottom:1px solid #fee2e2; padding:16px 20px; display:flex; align-items:center; gap:12px;">
                <div style="width:36px; height:36px; border-radius:10px; background:linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color:#ffffff; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:17px; flex-shrink:0; box-shadow:0 3px 8px rgba(220,38,38,0.3);">
                    !
                </div>
                <div>
                    <h4 style="margin:0; font-size:15px; font-weight:800; color:#991b1b;">Konfirmasi Penghapusan Data</h4>
                    <span style="font-size:11.5px; color:#b91c1c; font-weight:600;">Data akan dihapus permanen dari sistem</span>
                </div>
            </div>
            <div style="padding:18px 20px;">
                <p style="margin:0 0 12px 0; font-size:13.5px; color:#334155; line-height:1.5;">
                    Apakah Anda yakin ingin menghapus <strong id="deleteModalItemName" style="color:#0f172a; word-break:break-word;">data ini</strong>?
                </p>
                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:10px 14px; font-size:12px; color:#64748b; line-height:1.4;">
                    Tindakan ini tidak dapat dibatalkan. Baris data akan dihilangkan dengan animasi sesaat sebelum diproses oleh server.
                </div>
            </div>
            <div style="padding:14px 20px; background:#f8fafc; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end; gap:8px;">
                <button type="button" class="btn secondary" onclick="closeDeleteModal()" style="font-size:12.5px; padding:7px 14px; border-radius:8px;">
                    Batal
                </button>
                <button type="button" class="btn danger" id="deleteModalConfirmBtn" style="font-size:12.5px; padding:7px 16px; border-radius:8px;">
                    <span id="deleteModalBtnText">Ya, Hapus Sekarang</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        let activeDeleteForm = null;
        let activeDeleteRow = null;

        window.triggerDeleteEffect = function(event, element, itemName) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            activeDeleteForm = element.tagName === 'FORM' ? element : element.closest('form');
            activeDeleteRow = element.closest('tr') || (activeDeleteForm ? activeDeleteForm.closest('tr') : null);

            const modal = document.getElementById('skanicDeleteModal');
            const nameEl = document.getElementById('deleteModalItemName');
            const confirmBtn = document.getElementById('deleteModalConfirmBtn');
            const btnText = document.getElementById('deleteModalBtnText');

            if (nameEl) {
                nameEl.textContent = itemName || 'data terpilih';
            }
            if (confirmBtn) {
                confirmBtn.disabled = false;
            }
            if (btnText) {
                btnText.textContent = 'Ya, Hapus Sekarang';
            }
            if (modal) {
                modal.style.display = 'flex';
            }
            return false;
        };

        window.closeDeleteModal = function() {
            const modal = document.getElementById('skanicDeleteModal');
            if (modal) {
                modal.style.display = 'none';
            }
            activeDeleteForm = null;
            activeDeleteRow = null;
        };

        document.addEventListener('DOMContentLoaded', () => {
            // Setup delete modal confirm button
            const confirmBtn = document.getElementById('deleteModalConfirmBtn');
            const btnText = document.getElementById('deleteModalBtnText');
            if (confirmBtn) {
                confirmBtn.addEventListener('click', () => {
                    if (!activeDeleteForm) return;

                    if (btnText) btnText.textContent = 'Menghapus...';
                    confirmBtn.disabled = true;

                    if (activeDeleteRow) {
                        activeDeleteRow.classList.add('row-deleting');
                        setTimeout(() => {
                            if (activeDeleteForm) {
                                activeDeleteForm.submit();
                            }
                        }, 380);
                    } else {
                        activeDeleteForm.submit();
                    }
                });
            }

            // Close modal when clicking backdrop or pressing Escape
            const modal = document.getElementById('skanicDeleteModal');
            if (modal) {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        closeDeleteModal();
                    }
                });
            }

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    closeDeleteModal();
                }
            });
        });
    </script>
</body>
</html>
