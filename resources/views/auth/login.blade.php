<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Skanic | Sistem Informasi Jadwal Pelajaran SMKN 1</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <style>
        /* ============================================================
           TEMA PORTAL AKADEMIK SEKOLAH SMKN 1 (BERSIH, SEDERHANA & RAMAH MATA)
           Tidak terlalu gelap (bukan dark mode), tidak menyilaukan (soft slate)
           ============================================================ */
        :root {
            --bg-canvas: #f4f7fb;          /* Soft cool mist - nyaman & sejuk di mata */
            --bg-canvas-subtle: #edf2f8;
            --surface-card: #ffffff;
            --surface-elevated: #ffffff;
            --surface-input: #ffffff;
            --border-base: #e2e8f0;
            --border-focus: #2563eb;
            --primary-navy: #1e3a5f;        /* Calm Academic Steel-Navy */
            --primary-accent: #2563eb;      /* Clean Modern Royal Blue */
            --primary-hover: #1d4ed8;
            --primary-light: #eff6ff;
            --gold-accent: #d97706;
            --emerald-accent: #059669;
            --text-heading: #0f172a;
            --text-body: #334155;
            --text-muted: #64748b;
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;
            --font-main: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            --shadow-card: 0 1px 3px rgba(15, 23, 42, 0.04), 0 4px 14px rgba(15, 23, 42, 0.03);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: var(--font-main);
            background: var(--bg-canvas);
            color: var(--text-body);
            min-height: 100vh;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        /* Subtle Neutral Scrollbars */
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

        /* Top Navigation Bar */
        .portal-nav {
            background: linear-gradient(90deg, #1e3a5f 0%, #193254 100%);
            color: #ffffff;
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 12px rgba(15, 23, 42, 0.12);
        }

        .brand-link {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #ffffff;
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
        }

        .brand-meta h1 {
            font-size: 16px;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.2;
            letter-spacing: -0.2px;
        }

        .brand-meta span {
            font-size: 11px;
            color: #93c5fd;
            font-weight: 600;
            letter-spacing: 0.2px;
        }

        /* Nav Links (3 Fitur Interaktif) */
        .nav-links {
            display: flex;
            align-items: center;
            gap: 6px;
            list-style: none;
        }

        .nav-links a {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #dbeafe;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            padding: 7px 14px;
            border-radius: 8px;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.18s ease;
            white-space: nowrap;
        }

        .nav-links a:hover {
            color: #ffffff;
            background: rgba(147, 197, 253, 0.14);
        }

        .nav-links a.active {
            color: #ffffff;
            background: rgba(59, 130, 246, 0.28);
            border-color: rgba(147, 197, 253, 0.45);
            font-weight: 700;
        }

        /* TOMBOL LOGIN ADMIN RESMI (PERMANEN & JELAS TERLIHAT) */
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-admin-top {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #2563eb;
            color: #ffffff;
            border: 1px solid rgba(147, 197, 253, 0.45);
            padding: 8px 15px;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.18s ease;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.26);
            white-space: nowrap;
        }

        .btn-admin-top:hover {
            background: #1d4ed8;
            border-color: #93c5fd;
            transform: translateY(-1px);
        }

        .badge-restricted {
            background: #fef3c7;
            color: #92400e;
            font-size: 10px;
            font-weight: 800;
            padding: 2px 6px;
            border-radius: 5px;
            text-transform: uppercase;
        }

        /* Container */
        .portal-container {
            max-width: 1220px;
            margin: 0 auto;
            padding: 24px 20px 48px;
        }

        /* Notification Banner Beranda */
        .homepage-notification-banner {
            background: linear-gradient(135deg, #f5f9ff 0%, #ffffff 100%);
            border: 1px solid #dbeafe;
            border-left: 4px solid #2563eb;
            border-radius: var(--radius-lg);
            padding: 16px 20px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            box-shadow: var(--shadow-card);
        }

        .hp-notif-left {
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        .hp-notif-icon-box {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #dbeafe;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .hp-notif-tag-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 4px;
            flex-wrap: wrap;
        }

        .hp-badge-live {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #d1fae5;
            padding: 2px 8px;
            border-radius: 99px;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .hp-pulse-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #10b981;
        }

        .hp-notif-date {
            font-size: 11.5px;
            color: #475569;
            font-weight: 600;
        }

        .hp-notif-title {
            font-size: 15px;
            font-weight: 800;
            color: var(--primary-navy);
            margin: 0 0 3px;
        }

        .hp-notif-desc {
            font-size: 13px;
            color: var(--text-body);
            margin: 0;
            line-height: 1.45;
        }

        .hp-notif-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #2563eb;
            color: #ffffff;
            padding: 8px 16px;
            border-radius: 9px;
            font-size: 12.5px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.18s ease;
            box-shadow: 0 1px 3px rgba(37, 99, 235, 0.22);
        }

        .hp-notif-action-btn:hover {
            background: #1d4ed8;
            color: #ffffff;
            transform: translateY(-1px);
        }

        /* Hero / Pengenalan Sistem Card */
        .hero-section {
            background: var(--surface-card);
            border: 1px solid var(--border-base);
            border-radius: var(--radius-lg);
            padding: 26px 28px;
            margin-bottom: 24px;
            box-shadow: var(--shadow-card);
        }

        .hero-header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            flex-wrap: wrap;
        }

        .hero-main-col {
            flex: 1;
            min-width: 300px;
        }

        .hero-badge-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }

        .pill-badge {
            background: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11.5px;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .pill-badge.active-year {
            background: #ecfdf5;
            color: #047857;
            border-color: #a7f3d0;
        }

        .hero-title {
            font-size: 24px;
            font-weight: 800;
            color: var(--primary-navy);
            letter-spacing: -0.4px;
            margin-bottom: 10px;
            line-height: 1.3;
        }

        .hero-desc {
            font-size: 13.5px;
            color: var(--text-body);
            line-height: 1.6;
            margin-bottom: 18px;
        }

        /* Intro Mini Cards */
        .intro-cards-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            margin-top: 16px;
        }

        .hero-section,
        .majors-showcase-section,
        .schedule-viewer-section {
            scroll-margin-top: 84px;
        }

        .intro-card {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: var(--radius-md);
            padding: 14px 16px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            cursor: pointer;
            text-align: left;
            font-family: inherit;
            width: 100%;
            transition: all 0.2s ease;
        }

        .intro-card:hover {
            background: #f5f9ff;
            border-color: #93c5fd;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.08);
        }

        .intro-card-icon {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: #eff6ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.18s ease;
        }

        .intro-card:hover .intro-card-icon {
            background: #2563eb;
            color: #ffffff;
        }

        .intro-card-content h4 {
            font-size: 13.5px;
            font-weight: 800;
            color: var(--primary-navy);
            margin-bottom: 2px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
        }

        .intro-card-content p {
            font-size: 12px;
            color: var(--text-muted);
            margin: 0;
            line-height: 1.45;
        }

        /* PROGRAM KEAHLIAN SHOWCASE SECTION (5 JURUSAN INTERAKTIF) */
        .majors-showcase-section {
            background: var(--surface-card);
            border: 1px solid var(--border-base);
            border-radius: var(--radius-lg);
            padding: 20px 24px;
            margin-bottom: 22px;
            box-shadow: var(--shadow-card);
        }

        .majors-showcase-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .majors-showcase-head h3 {
            font-size: 16.5px;
            font-weight: 800;
            color: var(--primary-navy);
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
        }

        .majors-showcase-head span {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 600;
        }

        .majors-showcase-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
        }

        .major-feature-card {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 14px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
            cursor: pointer;
            text-align: left;
            font-family: inherit;
            transition: all 0.2s ease;
            width: 100%;
        }

        .major-feature-card:hover {
            background: #f5f9ff;
            border-color: #93c5fd;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.09);
        }

        .major-feature-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .major-feature-num {
            width: 24px;
            height: 24px;
            border-radius: 7px;
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #dbeafe;
            font-size: 11.5px;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.18s ease;
        }

        .major-feature-card:hover .major-feature-num {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
        }

        .major-feature-arrow {
            color: #94a3b8;
            font-size: 13px;
            font-weight: 700;
            transition: transform 0.2s ease, color 0.2s ease;
        }

        .major-feature-card:hover .major-feature-arrow {
            color: #2563eb;
            transform: translateX(3px);
        }

        .major-feature-card h4 {
            font-size: 13.5px;
            font-weight: 800;
            color: var(--primary-navy);
            margin: 0;
        }

        .major-feature-card p {
            font-size: 11.5px;
            color: var(--text-muted);
            margin: 0;
            line-height: 1.35;
        }

        /* SCHEDULE VIEWER SECTION */
        .schedule-viewer-section {
            background: var(--surface-card);
            border: 1px solid var(--border-base);
            border-radius: var(--radius-lg);
            padding: 24px;
            box-shadow: var(--shadow-card);
        }

        .viewer-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
            flex-wrap: wrap;
            gap: 12px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 16px;
        }

        .viewer-title h3 {
            font-size: 18px;
            font-weight: 800;
            color: var(--primary-navy);
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0 0 2px;
        }

        .viewer-title p {
            font-size: 12.5px;
            color: var(--text-muted);
            font-weight: 600;
            margin: 0;
        }

        .viewer-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        /* Tombol Cetak Khusus Siswa / Pelajaran */
        .btn-print-subject {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #2563eb;
            color: #ffffff;
            border: none;
            padding: 8px 15px;
            border-radius: 9px;
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.18s ease;
            box-shadow: 0 1px 3px rgba(37, 99, 235, 0.22);
        }

        .btn-print-subject:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        .btn-pdf-subject {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #f1f5f9;
            color: #334155;
            border: 1px solid #cbd5e1;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-pdf-subject:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        /* Filter Controls */
        .filter-controls {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: var(--radius-md);
            padding: 14px 16px;
            margin-bottom: 18px;
        }

        .filter-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .day-pills-bar {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .pill-day {
            background: #ffffff;
            color: #475569;
            border: 1.5px solid #cbd5e1;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .pill-day:hover {
            border-color: #93c5fd;
            color: #2563eb;
            background: #eff6ff;
        }

        .pill-day.active {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.22);
        }

        .filter-dropdowns {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .custom-select {
            background: #ffffff;
            color: var(--text-heading);
            border: 1.5px solid var(--border-base);
            border-radius: 8px;
            padding: 7px 32px 7px 12px;
            font-size: 13px;
            font-weight: 600;
            font-family: inherit;
            outline: none;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%230284c7' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            transition: border-color 0.2s ease;
        }

        .custom-select:focus {
            border-color: var(--border-focus);
            box-shadow: 0 0 0 2px rgba(2, 132, 199, 0.15);
        }

        .search-input-box {
            position: relative;
        }

        .search-input-box input {
            background: #ffffff;
            color: var(--text-heading);
            border: 1.5px solid var(--border-base);
            border-radius: 8px;
            padding: 7px 12px 7px 32px;
            font-size: 13px;
            font-family: inherit;
            outline: none;
            width: 220px;
            transition: all 0.2s ease;
        }

        .search-input-box input:focus {
            border-color: var(--border-focus);
            width: 260px;
            box-shadow: 0 0 0 2px rgba(2, 132, 199, 0.15);
        }

        .search-input-box svg {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 15px;
            height: 15px;
            color: var(--text-muted);
            pointer-events: none;
        }

        .btn-filter-reset {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #ffffff;
            color: #64748b;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            padding: 7px 12px;
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-filter-reset:hover {
            color: #0f172a;
            border-color: #94a3b8;
            background: #f1f5f9;
        }

        /* Badges */
        .badge-major-pill {
            font-size: 10.5px;
            font-weight: 800;
            padding: 2px 7px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            white-space: nowrap;
        }

        .badge-major-pill.major-pplg { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
        .badge-major-pill.major-to { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
        .badge-major-pill.major-animasi { background: #f3e8ff; color: #7e22ce; border: 1px solid #e9d5ff; }
        .badge-major-pill.major-tpfl { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .badge-major-pill.major-bcf { background: #ffe4e6; color: #be123c; border: 1px solid #fecdd3; }

        .btn-portal-edit {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #f0f9ff;
            color: #0284c7;
            border: 1px solid #bae6fd;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11.5px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .btn-portal-edit:hover {
            background: #0284c7;
            color: #ffffff;
            border-color: #0284c7;
        }

        .mobile-table-hint {
            display: none;
            align-items: center;
            gap: 6px;
            font-size: 11.5px;
            color: #0284c7;
            margin-bottom: 8px;
            padding: 6px 12px;
            background: #e0f2fe;
            border-radius: 6px;
            border: 1px dashed #7dd3fc;
        }

        /* 5 Jurusan Quick Tabs (Grid 6 Kolom Rapi & Bebas Scrollbar) */
        .portal-major-tabs {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 6px;
            margin-bottom: 12px;
            overflow: hidden;
            width: 100%;
        }

        .portal-major-tab {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            padding: 7px 8px;
            font-size: 12px;
            font-weight: 700;
            color: #334155;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
        }

        .portal-major-tab:hover {
            border-color: #93c5fd;
            color: #2563eb;
            background: #eff6ff;
        }

        .portal-major-tab.active {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.22);
        }

        .portal-major-tab .tab-num-badge {
            width: 20px;
            height: 20px;
            border-radius: 6px;
            background: #eff6ff;
            color: #2563eb;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 800;
        }

        .portal-major-tab.active .tab-num-badge {
            background: rgba(255, 255, 255, 0.25);
            color: #ffffff;
        }

        /* Sub-Pills Navigasi Kelas Cepat Portal Siswa */
        .portal-sub-class-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
            padding: 8px 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 9px;
            flex-wrap: wrap;
        }

        .sub-class-label {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            font-weight: 800;
            color: #1e3a5f;
            white-space: nowrap;
        }

        .sub-class-pills {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .portal-class-pill {
            background: #ffffff;
            color: #334155;
            border: 1.5px solid #cbd5e1;
            padding: 5px 12px;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.16s ease;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .pill-class-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 18px;
            height: 18px;
            border-radius: 5px;
            background: #eff6ff;
            color: #2563eb;
            font-size: 10.5px;
            font-weight: 800;
        }

        .portal-class-pill.active .pill-class-num {
            background: rgba(255, 255, 255, 0.25);
            color: #ffffff;
        }

        .portal-class-pill:hover {
            border-color: #93c5fd;
            color: #2563eb;
            background: #eff6ff;
        }

        .portal-class-pill.active {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.22);
        }

        /* Badge Nomor Urut Kelas dalam Jurusan */
        .portal-class-num-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 4px 10px;
            border-radius: 7px;
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            font-size: 11.5px;
            font-weight: 800;
            letter-spacing: 0.3px;
            white-space: nowrap;
        }

        .item-kbm-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 17px;
            height: 17px;
            border-radius: 4px;
            background: #1e3a5f;
            color: #ffffff;
            font-size: 10px;
            font-weight: 800;
            margin-right: 2px;
        }

        /* Container Kartu Kelas (Per Kelas Masing-Masing - Tampilan Terpisah Bersih) */
        .portal-classes-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 14px;
        }

        .portal-class-card {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
            transition: all 0.2s ease;
        }

        .portal-class-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.07);
        }

        .portal-class-header {
            padding: 14px 18px;
            background: linear-gradient(180deg, #fafbfd 0%, #f1f5f9 100%);
            border-bottom: 1.5px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .portal-class-title-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .portal-class-badge {
            padding: 6px 12px;
            border-radius: 8px;
            background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
            color: #ffffff;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.3px;
            white-space: nowrap;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.2);
        }

        .portal-class-title {
            margin: 0;
            font-size: 15px;
            font-weight: 800;
            color: #0f172a;
        }

        .portal-class-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 3px;
            flex-wrap: wrap;
        }

        .portal-class-total-kbm {
            font-size: 11.5px;
            color: #64748b;
            font-weight: 600;
        }

        /* GRID TEMPAT HARI (SENIN, SELASA, RABU, KAMIS, JUMAT) */
        .portal-days-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(200px, 1fr));
            gap: 14px;
            padding: 16px;
            background: #f8fafc;
            overflow-x: auto;
        }

        @media (max-width: 1100px) {
            .portal-days-grid {
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            }
        }

        @media (max-width: 640px) {
            .portal-days-grid {
                grid-template-columns: 1fr;
                padding: 12px;
                gap: 10px;
            }
        }

        /* BLOK TEMPAT HARI TERSENDIRI */
        .portal-day-box {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
            transition: border-color 0.18s ease;
        }

        .portal-day-box.is-today {
            border-color: #3b82f6;
            box-shadow: 0 0 0 1.5px rgba(59, 130, 246, 0.25), 0 2px 8px rgba(37, 99, 235, 0.08);
        }

        .portal-day-header {
            padding: 10px 12px;
            background: #fafbfd;
            border-bottom: 1.5px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
        }

        .portal-day-box.is-today .portal-day-header {
            background: #eff6ff;
            border-bottom-color: #bfdbfe;
        }

        .portal-day-title {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .badge-today-active {
            font-size: 10px;
            font-weight: 800;
            color: #0284c7;
            background: #e0f2fe;
            border: 1px solid #bae6fd;
            padding: 2px 6px;
            border-radius: 5px;
            letter-spacing: 0.2px;
        }

        .portal-day-count-badge {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            padding: 2px 6px;
            border-radius: 5px;
            white-space: nowrap;
        }

        .portal-day-box.is-today .portal-day-count-badge {
            background: #ffffff;
            border-color: #93c5fd;
            color: #1d4ed8;
        }

        /* LIST JADWAL DALAM HARI TERSEBUT */
        .portal-day-schedule-list {
            padding: 10px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex-grow: 1;
        }

        .portal-schedule-item {
            background: #ffffff;
            border: 1.5px solid #e5ebf3;
            border-radius: 8px;
            padding: 9px 11px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            transition: all 0.16s ease;
        }

        .portal-schedule-item:hover {
            border-color: #93c5fd;
            background: #f8fbff;
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.08);
        }

        .portal-item-time {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            font-weight: 800;
            color: #1e3a5f;
            background: #f1f5f9;
            padding: 3px 7px;
            border-radius: 5px;
            width: fit-content;
            font-family: var(--font-mono);
        }

        .portal-item-subject {
            font-size: 13px;
            font-weight: 800;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 5px;
            flex-wrap: wrap;
            line-height: 1.3;
        }

        .portal-item-teacher {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 11.5px;
            color: #475569;
            font-weight: 600;
            line-height: 1.3;
        }

        .portal-item-teacher svg {
            flex-shrink: 0;
            color: #64748b;
        }

        .portal-item-room {
            margin-top: 2px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .portal-item-room .room-pill {
            font-size: 11px;
            padding: 2px 7px;
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: #334155;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .portal-day-empty {
            padding: 24px 12px;
            text-align: center;
            color: #94a3b8;
            font-size: 12px;
            font-weight: 600;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            flex-grow: 1;
            justify-content: center;
            font-style: italic;
        }

        .portal-empty-card {
            background: #ffffff;
            border: 1.5px dashed #cbd5e1;
            border-radius: 12px;
            padding: 40px 20px;
            text-align: center;
            color: #64748b;
        }

        .portal-nav-badge-note {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 700;
            color: #047857;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            padding: 6px 14px;
            border-radius: 7px;
        }

        /* Pagination & Status Controls Bar */
        .portal-pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 16px;
            padding-top: 14px;
            border-top: 1px solid #e2e8f0;
            flex-wrap: wrap;
            gap: 12px;
        }

        .pagination-info {
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 12.5px;
            color: #64748b;
            font-weight: 600;
            flex-wrap: wrap;
        }

        .page-size-control {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .page-size-select {
            padding: 4px 28px 4px 10px !important;
            font-size: 12px !important;
            height: 32px;
        }

        .pagination-nav {
            display: flex;
            align-items: center;
            gap: 5px;
            flex-wrap: wrap;
        }

        .btn-page-nav {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            height: 32px;
            padding: 0 10px;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 700;
            border: 1.5px solid #cbd5e1;
            background: #ffffff;
            color: #334155;
            cursor: pointer;
            transition: all 0.15s ease;
            user-select: none;
        }

        .btn-page-nav:hover:not(:disabled) {
            border-color: #93c5fd;
            color: #2563eb;
            background: #eff6ff;
        }

        .btn-page-nav.active {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.22);
        }

        .btn-page-nav:disabled {
            opacity: 0.45;
            cursor: not-allowed;
            background: #f8fafc;
            border-color: #e2e8f0;
        }

        .page-ellipsis {
            padding: 0 4px;
            color: #94a3b8;
            font-weight: 700;
            font-size: 12px;
        }

        /* Schedule Table (Soft Slate Header - Bersih & Sejuk di Mata) */
        .table-wrap {
            overflow-x: auto;
            border: 1px solid #e5ebf3;
            border-radius: var(--radius-md);
            background: #ffffff;
        }

        table.portal-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            text-align: left;
        }

        table.portal-table thead th {
            background: #f1f5f9;
            color: #334155;
            font-weight: 700;
            padding: 12px 14px;
            border-bottom: 1.5px solid #e2e8f0;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            white-space: nowrap;
            font-size: 11.5px;
        }

        table.portal-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.14s ease;
        }

        table.portal-table tbody tr:nth-child(even) {
            background: #fafbfd;
        }

        table.portal-table tbody tr:hover {
            background: #f5f9ff;
        }

        table.portal-table tbody td {
            padding: 12px 14px;
            color: var(--text-body);
            vertical-align: middle;
        }

        .badge-day-label {
            display: inline-block;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 3px 8px;
            border-radius: 6px;
            letter-spacing: 0.3px;
        }

        .badge-day-monday { background: #eff6ff; color: #1d4ed8; border: 1px solid #dbeafe; }
        .badge-day-tuesday { background: #ecfdf5; color: #047857; border: 1px solid #d1fae5; }
        .badge-day-wednesday { background: #f0f9ff; color: #0369a1; border: 1px solid #e0f2fe; }
        .badge-day-thursday { background: #fffbeb; color: #b45309; border: 1px solid #fef3c7; }
        .badge-day-friday { background: #f5f3ff; color: #6d28d9; border: 1px solid #ede9fe; }

        .badge-time {
            font-family: var(--font-mono);
            font-size: 11.5px;
            font-weight: 600;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 3px 8px;
            border-radius: 6px;
            color: #334155;
            white-space: nowrap;
        }

        .subject-title {
            font-weight: 800;
            color: var(--text-heading);
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .subject-code-pill {
            font-size: 10.5px;
            font-family: var(--font-mono);
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            color: #475569;
            padding: 1px 6px;
            border-radius: 4px;
        }

        .teacher-name {
            font-weight: 700;
            color: #1e293b;
        }

        .inval-tag {
            display: inline-block;
            font-size: 10px;
            background: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
            padding: 1px 6px;
            border-radius: 4px;
            font-weight: 700;
            margin-top: 2px;
        }

        .room-pill {
            font-size: 12px;
            font-weight: 700;
            color: #334155;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 3px 8px;
            border-radius: 5px;
            display: inline-block;
            white-space: nowrap;
        }

        .empty-row-msg {
            text-align: center;
            padding: 36px 20px;
            color: var(--text-muted);
            font-weight: 600;
        }

        /* Modal Login Admin */
        .modal-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 999;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-backdrop.show {
            display: flex;
        }

        .modal-card {
            background: #ffffff;
            border-radius: var(--radius-lg);
            width: 100%;
            max-width: 440px;
            box-shadow: 0 16px 36px rgba(15, 23, 42, 0.25);
            overflow: hidden;
            animation: modalPop 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes modalPop {
            from { opacity: 0; transform: scale(0.95) translateY(-10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        .modal-head {
            background: var(--primary-navy);
            color: #ffffff;
            padding: 18px 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-head-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-lock-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: #0284c7;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
        }

        .modal-head-title h3 {
            font-size: 15px;
            font-weight: 800;
            margin: 0;
            color: #ffffff;
        }

        .modal-head-title span {
            font-size: 11px;
            color: #7dd3fc;
        }

        .btn-close-modal {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: #ffffff;
            width: 30px;
            height: 30px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 16px;
        }

        .btn-close-modal:hover {
            background: rgba(239, 68, 68, 0.8);
        }

        .modal-body {
            padding: 22px;
        }

        .modal-info-note {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 12px;
            color: #0369a1;
            margin-bottom: 18px;
            display: flex;
            gap: 8px;
            align-items: flex-start;
        }

        .form-group-modal {
            margin-bottom: 15px;
        }

        .form-group-modal label {
            display: block;
            font-size: 12.5px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 5px;
        }

        .form-group-modal input {
            width: 100%;
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            padding: 9px 12px;
            font-size: 13.5px;
            font-family: inherit;
            color: #0f172a;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-group-modal input:focus {
            border-color: #0284c7;
            box-shadow: 0 0 0 2px rgba(2, 132, 199, 0.18);
        }

        .btn-submit-admin {
            width: 100%;
            background: #0284c7;
            color: #ffffff;
            border: none;
            padding: 11px 18px;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 800;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 4px;
        }

        .btn-submit-admin:hover {
            background: #0369a1;
        }

        /* Footer */
        .portal-footer {
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            padding: 20px;
            text-align: center;
            font-size: 12.5px;
            color: #64748b;
            margin-top: 36px;
        }

        /* PRINT STYLING KHUSUS UNTUK LEMBAR JADWAL PELAJARAN SISWA */
        @media print {
            .portal-nav,
            .homepage-notification-banner,
            .hero-section,
            .majors-showcase-section,
            .portal-major-tabs,
            .filter-controls,
            .mobile-table-hint,
            .btn-portal-edit,
            .viewer-actions,
            .portal-pagination-container,
            .portal-footer,
            .modal-backdrop {
                display: none !important;
            }

            body {
                background: #ffffff !important;
                padding: 0 !important;
            }

            .portal-container {
                max-width: 100% !important;
                padding: 0 !important;
            }

            .schedule-viewer-section {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
            }

            .table-wrap {
                border: 1px solid #333 !important;
            }

            table.portal-table thead th {
                background: #1e293b !important;
                color: #ffffff !important;
                border: 1px solid #333 !important;
            }

            table.portal-table td {
                border: 1px solid #333 !important;
                color: #000000 !important;
            }
        }

        /* Responsive Breakpoints */
        @media (max-width: 860px) {
            .portal-nav {
                flex-wrap: wrap;
                gap: 10px;
                padding: 10px 16px;
            }
            .nav-links {
                order: 3;
                width: 100%;
                justify-content: center;
                padding-top: 8px;
                border-top: 1px solid rgba(255, 255, 255, 0.12);
                gap: 6px;
            }
            .nav-links a {
                font-size: 12px;
                padding: 6px 10px;
            }
            .hero-header-row { flex-direction: column; }
            .intro-cards-grid { grid-template-columns: 1fr; }
            .majors-showcase-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .portal-major-tabs {
                grid-template-columns: repeat(3, 1fr);
            }
            .filter-row { flex-direction: column; align-items: stretch; }
            .day-pills-bar {
                overflow-x: auto;
                flex-wrap: nowrap;
                padding-bottom: 6px;
                -webkit-overflow-scrolling: touch;
            }
            .pill-day { flex-shrink: 0; }
            .filter-dropdowns { flex-direction: column; width: 100%; }
            .custom-select, .search-input-box, .search-input-box input, .btn-filter-reset {
                width: 100% !important;
            }
            table.portal-table { min-width: 760px; }
            .mobile-table-hint { display: flex; }
        }

        @media (max-width: 580px) {
            .majors-showcase-grid {
                grid-template-columns: 1fr;
            }
            .portal-major-tabs {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <!-- Top Navigation Bar dengan Login Admin yang Permanen di Kanan Atas -->
    <header class="portal-nav">
        <a href="{{ url('/') }}" class="brand-link">
            <div class="brand-icon">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="brand-meta">
                <h1>Jadwal Skanic</h1>
                <span>Sistem Akademik SMKN 1</span>
            </div>
        </a>

        <!-- Menu Navigasi Tengah (3 Fitur Utama yang Dapat Diklik) -->
        <ul class="nav-links" id="portalNavLinks">
            <li>
                <a href="#pengenalan" class="nav-link-item active" data-target="pengenalan" onclick="navigateToSection(event, 'pengenalan')">
                    Pengenalan Sistem
                </a>
            </li>
            <li>
                <a href="#program-keahlian" class="nav-link-item" data-target="program-keahlian" onclick="navigateToSection(event, 'program-keahlian')">
                    Program Keahlian
                </a>
            </li>
            <li>
                <a href="#jadwal-pelajaran" class="nav-link-item" data-target="jadwal-pelajaran" onclick="navigateToSection(event, 'jadwal-pelajaran')">
                    Jadwal Pelajaran
                </a>
            </li>
        </ul>

        <!-- BAGIAN KANAN ATAS: Login Admin (Akses Terbuka & Tidak Pernah Hilang) -->
        <div class="nav-actions">
            <button type="button" class="btn-admin-top" onclick="openAdminModal()" title="Masuk ke Dashboard Kurikulum">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <span>Login Admin</span>
                <span class="badge-restricted">Kurikulum</span>
            </button>
        </div>
    </header>

    <main class="portal-container">
        <!-- Error Alert if validation fails -->
        @if ($errors->any())
            <div style="background:#fef2f2; border:1.5px solid #fca5a5; border-radius:8px; padding:12px 16px; margin-bottom:18px; color:#991b1b; font-size:13px; display:flex; align-items:center; gap:8px;">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <!-- NOTIFIKASI PENGUMUMAN UTAMA DI HALAMAN BERANDA / UTAMA -->
        <div class="homepage-notification-banner" id="notifikasi-beranda">
            <div class="hp-notif-left">
                <div class="hp-notif-icon-box">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
                <div class="hp-notif-text">
                    <div class="hp-notif-tag-row">
                        <span class="hp-badge-live">
                            <span class="hp-pulse-dot"></span>
                            Notifikasi Beranda
                        </span>
                        <span class="hp-notif-date">Tahun Ajaran 2026/2027 &bull; Semester Ganjil</span>
                    </div>
                    <h3 class="hp-notif-title">Jadwal Pelajaran SMKN 1 Resmi Aktif</h3>
                    <p class="hp-notif-desc">
                        Seluruh jadwal kegiatan belajar mengajar (KBM) untuk 10 kelas dan 5 kompetensi keahlian telah aktif. Siswa dapat langsung melihat jadwal kelas, ruang lab/bengkel, dan guru pengampu tanpa perlu login.
                    </p>
                </div>
            </div>
        </div>

        <!-- Section 1: Hero & Pengenalan Web & Card Portal Admin -->
        <section class="hero-section" id="pengenalan">
            <div class="hero-header-row">
                <div class="hero-main-col">
                    <div class="hero-badge-row">
                        <span class="pill-badge">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Pengenalan Sistem Web
                        </span>
                        <span class="pill-badge active-year">
                            SMKN 1 &bull; 2026/2027
                        </span>
                    </div>

                    <h2 class="hero-title">
                        Sistem Informasi Jadwal Pelajaran SMKN 1
                    </h2>

                    <p class="hero-desc">
                        Selamat datang di portal jadwal mata pelajaran sekolah. Platform ini menyajikan struktur kegiatan belajar
                        mengajar (KBM) harian dan mingguan secara terbuka, rapi, dan tepat waktu.
                        <strong>Peserta didik dapat langsung melihat dan mencari jadwal kelas mereka di bawah tanpa perlu login</strong>.
                    </p>

                    <div class="intro-cards-grid">
                        <button type="button" class="intro-card" onclick="jumpToScheduleSearch()" title="Langsung cari jadwal pelajaran di bawah">
                            <div class="intro-card-icon">
                                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                            <div class="intro-card-content">
                                <h4><span>Siswa Langsung Melihat</span> <span style="color:#0284c7;">&rarr;</span></h4>
                                <p>Klik untuk mencari jadwal kelas & jurusan di bawah tanpa login.</p>
                            </div>
                        </button>

                        <button type="button" class="intro-card" onclick="navigateToSection(event, 'program-keahlian')" title="Lihat 5 Program Keahlian SMKN 1">
                            <div class="intro-card-icon">
                                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div class="intro-card-content">
                                <h4><span>5 Jurusan & 10 Rombel</span> <span style="color:#0284c7;">&rarr;</span></h4>
                                <p>Mencakup PPLG, BCF, TPFL, Teknik Otomotif, dan Animasi.</p>
                            </div>
                        </button>

                        <button type="button" class="intro-card" onclick="navigateToSection(event, 'jadwal-pelajaran')" title="Lihat informasi ruang praktik dan jadwal aktif">
                            <div class="intro-card-icon">
                                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="intro-card-content">
                                <h4><span>Lab, Bengkel & Jadwal KBM</span> <span style="color:#0284c7;">&rarr;</span></h4>
                                <p>Informasi ruang praktik, guru pengampu, dan jadwal tercatat rapi.</p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 2: 5 Program Keahlian SMKN 1 (Dapat Diklik untuk Filter Jadwal Otomatis) -->
        <section class="majors-showcase-section" id="program-keahlian">
            <div class="majors-showcase-head">
                <h3>
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:#0284c7;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    5 Program Keahlian SMKN 1
                </h3>
                <span>Klik salah satu jurusan untuk langsung melihat jadwalnya</span>
            </div>

            <div class="majors-showcase-grid">
                <button type="button" class="major-feature-card" onclick="selectMajorAndScroll('PPLG')">
                    <div class="major-feature-top">
                        <span class="major-feature-num">1</span>
                        <span class="major-feature-arrow">&rarr;</span>
                    </div>
                    <h4>PPLG</h4>
                    <p>Pengembangan Perangkat Lunak &amp; Gim</p>
                </button>

                <button type="button" class="major-feature-card" onclick="selectMajorAndScroll('BCF')">
                    <div class="major-feature-top">
                        <span class="major-feature-num">2</span>
                        <span class="major-feature-arrow">&rarr;</span>
                    </div>
                    <h4>BCF</h4>
                    <p>Broadcasting &amp; Perfilman</p>
                </button>

                <button type="button" class="major-feature-card" onclick="selectMajorAndScroll('TPFL')">
                    <div class="major-feature-top">
                        <span class="major-feature-num">3</span>
                        <span class="major-feature-arrow">&rarr;</span>
                    </div>
                    <h4>TPFL</h4>
                    <p>Teknik Pengelasan &amp; Fabrikasi Logam</p>
                </button>

                <button type="button" class="major-feature-card" onclick="selectMajorAndScroll('TO')">
                    <div class="major-feature-top">
                        <span class="major-feature-num">4</span>
                        <span class="major-feature-arrow">&rarr;</span>
                    </div>
                    <h4>Teknik Otomotif</h4>
                    <p>Teknik Otomotif &amp; Kendaraan</p>
                </button>

                <button type="button" class="major-feature-card" onclick="selectMajorAndScroll('Animasi')">
                    <div class="major-feature-top">
                        <span class="major-feature-num">5</span>
                        <span class="major-feature-arrow">&rarr;</span>
                    </div>
                    <h4>Animasi</h4>
                    <p>Animasi &amp; Desain Visual</p>
                </button>
            </div>
        </section>

        <!-- Section 3: Tampilan Jadwal Pelajaran (Siswa Hanya Melihat) -->
        <section class="schedule-viewer-section" id="jadwal-pelajaran">
            <div class="viewer-header">
                <div class="viewer-title">
                    <h3>
                        <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:#0284c7;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Jadwal Pelajaran Sekolah
                    </h3>
                    <p id="scheduleCounterText">Memuat jadwal kegiatan belajar mengajar...</p>
                </div>
                <div class="viewer-actions">
                    <!-- Tombol Cetak Khusus Siswa / Lembar Jadwal Pelajaran (Satu Tombol Saja) -->
                    <button type="button" class="btn-print-subject" onclick="printSubjectSchedule()" title="Cetak Lembar Jadwal Pelajaran Siswa">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        <span>Cetak Lembar Jadwal</span>
                    </button>
                </div>
            </div>

            <!-- Bilah Tab 5 Jurusan Resmi SMKN 1 -->
            <div class="portal-major-tabs" id="portalMajorTabs">
                <button type="button" class="portal-major-tab active" data-major="PPLG" onclick="setPortalMajor('PPLG')" title="Program Keahlian 1: PPLG">
                    <span class="tab-num-badge">1</span>
                    <span>PPLG</span>
                </button>
                <button type="button" class="portal-major-tab" data-major="BCF" onclick="setPortalMajor('BCF')" title="Program Keahlian 2: BCF">
                    <span class="tab-num-badge">2</span>
                    <span>BCF</span>
                </button>
                <button type="button" class="portal-major-tab" data-major="TPFL" onclick="setPortalMajor('TPFL')" title="Program Keahlian 3: TPFL">
                    <span class="tab-num-badge">3</span>
                    <span>TPFL</span>
                </button>
                <button type="button" class="portal-major-tab" data-major="TO" onclick="setPortalMajor('TO')" title="Program Keahlian 4: Teknik Otomotif">
                    <span class="tab-num-badge">4</span>
                    <span>Teknik Otomotif</span>
                </button>
                <button type="button" class="portal-major-tab" data-major="Animasi" onclick="setPortalMajor('Animasi')" title="Program Keahlian 5: Animasi">
                    <span class="tab-num-badge">5</span>
                    <span>Animasi</span>
                </button>
                <button type="button" class="portal-major-tab" data-major="all" onclick="setPortalMajor('all')" title="Tampilkan Seluruh Jurusan">
                    <span class="tab-num-badge">★</span>
                    <span>Semua Jurusan</span>
                </button>
            </div>

            <!-- Bilah Sub-Pills Pemilih Kelas Cepat (HANYA 2 TOMBOL KELAS PER JURUSAN) -->
            <div class="portal-sub-class-bar" id="portalSubClassBar">
                <div class="sub-class-label">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span>Pilih Kelas:</span>
                </div>
                <div class="sub-class-pills" id="portalSubClassPills">
                    <!-- Tombol 2 Kelas Per Jurusan (Hanya 1 Kelas yang Aktif Terpisah) -->
                    @foreach ($classesWithMajor as $c)
                        @php
                            $isGradeX = str_starts_with(strtoupper(trim($c->name)), 'X ');
                            $isInitialActive = ($c->major_key === 'PPLG' && $isGradeX);
                        @endphp
                        <button
                            type="button"
                            class="portal-class-pill {{ $isInitialActive ? 'active' : '' }}"
                            data-class-target="{{ $c->id }}"
                            data-major="{{ $c->major_key }}"
                            data-grade="{{ $isGradeX ? 'X' : (str_starts_with(strtoupper(trim($c->name)), 'XI ') ? 'XI' : 'OTHER') }}"
                            onclick="selectPortalClass('{{ $c->id }}')"
                            style="{{ $c->major_key === 'PPLG' ? '' : 'display: none;' }}"
                            title="Kelas {{ $c->name }}"
                        >
                            <span>Kelas {{ $c->name }}</span>
                        </button>
                    @endforeach

                    <!-- 2 Opsi Tingkat Kelas (Hanya Muncul Jika Tab 'Semua Jurusan' Dipilih) -->
                    <button
                        type="button"
                        class="portal-class-pill active portal-level-pill"
                        data-level-target="X"
                        data-major="all"
                        onclick="selectPortalLevel('X')"
                        style="display: none;"
                        title="Tampilkan seluruh kelas tingkat X"
                    >
                        <span>Seluruh Kelas X</span>
                    </button>
                    <button
                        type="button"
                        class="portal-class-pill portal-level-pill"
                        data-level-target="XI"
                        data-major="all"
                        onclick="selectPortalLevel('XI')"
                        style="display: none;"
                        title="Tampilkan seluruh kelas tingkat XI"
                    >
                        <span>Seluruh Kelas XI</span>
                    </button>
                </div>
            </div>

            <!-- Filter Bar: Hari, Pencarian, dan Reset (Dropdown Jurusan/Kelas Disembunyikan agar Rapi) -->
            <div class="filter-controls">
                <div class="filter-row">
                    <!-- Day Pills -->
                    <div class="day-pills-bar" id="dayPillsContainer">
                        <button type="button" class="pill-day active" data-day="all" onclick="setFilterDay('all')">
                            Semua Hari
                        </button>
                        <button type="button" class="pill-day" data-day="monday" onclick="setFilterDay('monday')">
                            Senin
                        </button>
                        <button type="button" class="pill-day" data-day="tuesday" onclick="setFilterDay('tuesday')">
                            Selasa
                        </button>
                        <button type="button" class="pill-day" data-day="wednesday" onclick="setFilterDay('wednesday')">
                            Rabu
                        </button>
                        <button type="button" class="pill-day" data-day="thursday" onclick="setFilterDay('thursday')">
                            Kamis
                        </button>
                        <button type="button" class="pill-day" data-day="friday" onclick="setFilterDay('friday')">
                            Jumat
                        </button>
                    </div>

                    <!-- Dropdowns & Search Box (Dropdown disembunyikan agar tidak redundant dengan tab/pill di atas) -->
                    <div class="filter-dropdowns">
                        <!-- Filter Jurusan (Hidden) -->
                        <select id="majorFilterSelect" class="custom-select" onchange="onMajorFilterChange(this.value)" style="display: none;">
                            <option value="PPLG">PPLG</option>
                            <option value="all">Semua Jurusan</option>
                            @foreach ($majors ?? [] as $mKey => $mLabel)
                                <option value="{{ $mKey }}">{{ $mKey }}</option>
                            @endforeach
                        </select>

                        <!-- Filter Kelas (Hidden) -->
                        <select id="classFilterSelect" class="custom-select" onchange="onClassFilterChange(this.value)" style="display: none;">
                            <option value="all">Semua Kelas</option>
                            @foreach ($classesWithMajor as $c)
                                <option value="{{ $c->id }}" data-major="{{ $c->major_key }}">Kelas {{ $c->name }}</option>
                            @endforeach
                        </select>

                        <div class="search-input-box">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input
                                type="text"
                                id="keywordInput"
                                placeholder="Cari mapel / guru / ruang..."
                                oninput="applyPortalFilters()"
                            >
                        </div>

                        <!-- Reset Filter -->
                        <button type="button" class="btn-filter-reset" onclick="resetPortalFilters()" title="Kembalikan semua filter">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span>Reset</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Petunjuk Geser Layar untuk HP / Mobile -->
            <div class="mobile-table-hint">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
                <span>Geser ke samping untuk melihat seluruh jadwal hari &raquo;</span>
            </div>

            <!-- KONTAINER JADWAL KELAS MASING-MASING & DI SETIAP HARI PUNYA TEMPAT MASING-MASING -->
            <div class="portal-classes-container" id="portalClassesContainer">
                @php
                    $daysList = [
                        'monday' => ['label' => 'Senin', 'badge' => 'badge-day-monday'],
                        'tuesday' => ['label' => 'Selasa', 'badge' => 'badge-day-tuesday'],
                        'wednesday' => ['label' => 'Rabu', 'badge' => 'badge-day-wednesday'],
                        'thursday' => ['label' => 'Kamis', 'badge' => 'badge-day-thursday'],
                        'friday' => ['label' => 'Jumat', 'badge' => 'badge-day-friday'],
                    ];
                    $todayKey = strtolower(now()->englishDayOfWeek);
                    $todayDate = now()->format('Y-m-d');
                    $majorInfoMap = [
                        'PPLG' => ['name' => 'Pengembangan Perangkat Lunak dan Gim', 'num' => 1, 'badge' => 'major-pplg'],
                        'BCF' => ['name' => 'Broadcasting dan Perfilman', 'num' => 2, 'badge' => 'major-bcf'],
                        'TPFL' => ['name' => 'Teknik Pengelasan dan Fabrikasi Logam', 'num' => 3, 'badge' => 'major-tpfl'],
                        'TO' => ['name' => 'Teknik Otomotif', 'num' => 4, 'badge' => 'major-to'],
                        'Animasi' => ['name' => 'Animasi', 'num' => 5, 'badge' => 'major-animasi'],
                    ];
                @endphp

                @forelse ($classesWithMajor as $cls)
                    @php
                        $clsSchedules = $schedulesByClass->get($cls->id, collect());
                        $clsByDay = $clsSchedules->groupBy(function($item) {
                            return strtolower($item->day_of_week ?? $item->day ?? '');
                        });
                        $mKey = $cls->major_key;
                        $mInfo = $majorInfoMap[$mKey] ?? ['name' => $cls->major ?? 'Umum', 'num' => '', 'badge' => 'major-umum'];
                    @endphp

                    <div
                        class="portal-class-card"
                        data-class-id="{{ $cls->id }}"
                        data-major="{{ $mKey }}"
                        data-class-name="{{ strtolower($cls->name) }}"
                        id="portalClassCard_{{ $cls->id }}"
                    >
                        <!-- Header Kartu Kelas -->
                        <div class="portal-class-header">
                            <div class="portal-class-title-wrap">
                                <span class="portal-class-badge">{{ $cls->name }}</span>
                                <div>
                                    <h4 class="portal-class-title">Jadwal Kelas {{ $cls->name }}</h4>
                                    <div class="portal-class-meta">
                                        <span class="badge-major-pill {{ $mInfo['badge'] }}">
                                            {{ $mInfo['num'] ? $mInfo['num'] . '. ' : '' }}{{ $mKey }} &bull; {{ $mInfo['name'] }}
                                        </span>
                                        <span class="portal-class-total-kbm">
                                            Total: <strong class="cls-total-text">{{ $clsSchedules->count() }}</strong> Jam Pelajaran Terdaftar
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TEMPAT JADWAL DI SETIAP HARI (SENIN, SELASA, RABU, KAMIS, JUMAT) -->
                        <div class="portal-days-grid">
                            @foreach ($daysList as $dKey => $dCfg)
                                @php
                                    $dayItems = $clsByDay->get($dKey, collect());
                                    $isToday = ($dKey === $todayKey);
                                @endphp
                                <div
                                    class="portal-day-box {{ $isToday ? 'is-today' : '' }}"
                                    data-day="{{ $dKey }}"
                                    data-class-id="{{ $cls->id }}"
                                >
                                    <!-- Header Tempat Hari -->
                                    <div class="portal-day-header">
                                        <div class="portal-day-title">
                                            <span class="badge-day-label {{ $dCfg['badge'] }}">
                                                Hari {{ $dCfg['label'] }}
                                            </span>
                                            @if ($isToday)
                                                <span class="badge-today-active">★ Hari Ini</span>
                                            @endif
                                        </div>
                                        <span class="portal-day-count-badge">
                                            {{ $dayItems->count() }} Jam KBM
                                        </span>
                                    </div>

                                    <!-- Tempat Jadwal Hari Ini -->
                                    @if ($dayItems->isNotEmpty())
                                        <div class="portal-day-schedule-list">
                                            @foreach ($dayItems as $item)
                                                @php
                                                    $effectiveTeacher = $item->effectiveTeacherForDate($todayDate);
                                                    $isSubstituted = $effectiveTeacher->id !== $item->teacher_id;
                                                    $searchData = strtolower(
                                                        ($item->subject?->name ?? '') . ' ' .
                                                        ($item->subject?->code ?? '') . ' ' .
                                                        ($item->teacher?->name ?? '') . ' ' .
                                                        ($effectiveTeacher?->name ?? '') . ' ' .
                                                        ($cls->name ?? '') . ' ' .
                                                        ($cls->major ?? '') . ' ' .
                                                        ($item->roomModel?->name ?? $item->room ?? '')
                                                    );
                                                @endphp
                                                <div
                                                    class="portal-schedule-item"
                                                    data-day="{{ $dKey }}"
                                                    data-class-id="{{ $cls->id }}"
                                                    data-search="{{ $searchData }}"
                                                >
                                                    <div class="portal-item-time">
                                                        <span class="item-kbm-num">{{ $loop->iteration }}</span>
                                                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <span>{{ substr($item->start_time, 0, 5) }} - {{ substr($item->end_time, 0, 5) }}</span>
                                                    </div>
                                                    <div class="portal-item-main">
                                                        <div class="portal-item-subject">
                                                            <span>{{ $item->subject?->name ?? '-' }}</span>
                                                            @if ($item->subject?->code)
                                                                <span class="subject-code-pill">{{ $item->subject->code }}</span>
                                                            @endif
                                                        </div>
                                                        <div class="portal-item-teacher">
                                                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                            </svg>
                                                            @if ($isSubstituted)
                                                                <span style="color:#0284c7; font-weight:700;">{{ $effectiveTeacher->name }}</span>
                                                                <span class="inval-tag">Guru Inval</span>
                                                            @else
                                                                <span>{{ $item->teacher?->name ?? '-' }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="portal-item-room">
                                                        <span class="room-pill">
                                                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                            </svg>
                                                            {{ $item->roomModel?->name ?? $item->room ?? 'Ruang Kelas' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="portal-day-empty">
                                            <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span>Tidak ada jam pelajaran pada hari ini</span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="portal-empty-card">
                        Belum ada kelas yang terdaftar di sistem.
                    </div>
                @endforelse

                <div id="portalOverallEmptyState" style="display:none;" class="portal-empty-card">
                    <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin: 0 auto 10px; color:#94a3b8; display:block;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h4 style="margin: 0 0 4px; font-weight:800; color:#334155;">Tidak Ada Jadwal yang Cocok</h4>
                    <p style="margin: 0 0 12px; font-size:13px; color:#64748b;">Silakan ubah filter hari, kelas, jurusan, atau kata kunci pencarian Anda.</p>
                    <button type="button" class="btn-filter-reset" onclick="resetPortalFilters()">Reset Filter</button>
                </div>
            </div>

            <!-- Kontrol Status Jadwal Terstruktur (Menyajikan Informasi Lengkap Mingguan) -->
            <div class="portal-pagination-container" id="portalPaginationBar" style="display: none !important;">
                <div class="pagination-info">
                    <span id="paginationInfoText">Memuat status jadwal mingguan...</span>
                    <div class="page-size-control" style="display: none;">
                        <label for="pageSizeSelect">Tampilan:</label>
                        <select id="pageSizeSelect" class="custom-select page-size-select" autocomplete="off" onchange="applyPortalFilters()">
                            <option value="all" selected>Semua Jadwal Lengkap</option>
                            <option value="15">15 per halaman</option>
                        </select>
                    </div>
                </div>
                <div class="pagination-nav" id="paginationNavButtons">
                </div>
            </div>
        </section>
    </main>

    <!-- MODAL KHUSUS LOGIN ADMINISTRATOR (Akses Terbuka & Mudah Dijangkau) -->
    <div class="modal-backdrop" id="adminAuthModal" onclick="handleBackdropClick(event)">
        <div class="modal-card">
            <div class="modal-head">
                <div class="modal-head-title">
                    <div class="modal-lock-icon">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div>
                        <h3>Akses Administrator</h3>
                        <span>Kelola Jadwal & Kurikulum</span>
                    </div>
                </div>
                <button type="button" class="btn-close-modal" onclick="closeAdminModal()" title="Tutup">
                    &times;
                </button>
            </div>

            <div class="modal-body">
                <div class="modal-info-note">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="flex-shrink:0; margin-top:2px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div id="modalInfoNoteText">
                        Akses login ini khusus staf Administrator Kurikulum. Peserta didik hanya melihat jadwal di halaman utama tanpa perlu login.
                    </div>
                </div>

                <!-- Form Login Admin Resmi -->
                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf
                    <div class="form-group-modal">
                        <label for="adminUsernameInput">Username Administrator</label>
                        <input
                            type="text"
                            id="adminUsernameInput"
                            name="username"
                            value="{{ old('username', 'admin') }}"
                            placeholder="Masukkan username admin"
                            required
                            autocomplete="username"
                        >
                    </div>

                    <div class="form-group-modal">
                        <label for="adminPasswordInput">Kata Sandi (Password)</label>
                        <input
                            type="password"
                            id="adminPasswordInput"
                            name="password"
                            placeholder="Masukkan kata sandi administrator"
                            required
                            autocomplete="current-password"
                        >
                        <span style="font-size: 11.5px; color: #64748b; display: block; margin-top: 5px;">
                            Hak akses khusus Administrator Kurikulum SMKN 1
                        </span>
                    </div>

                    <button type="submit" class="btn-submit-admin">
                        Masuk ke Dashboard Admin &rarr;
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="portal-footer" id="informasi-sekolah">
        &copy; {{ date('Y') }} Jadwal Skanic &bull; SMK Negeri 1 &bull; Sistem Informasi Jadwal Pelajaran Terpadu
    </footer>

    <!-- Script Filter, Tab 5 Jurusan & Paginasi Cerdas -->
    <script>
        let currentDayFilter = 'all';
        let currentMajorFilter = 'PPLG'; // Default ke Jurusan 1 (PPLG)
        let currentClassFilter = 'all';  // Akan diisi ID Kelas X secara dinamis
        let currentLevelFilter = 'X';    // Khusus mode 'Semua Jurusan', default ke Tingkat X
        let currentPage = 1;
        let pageSize = 'all';

        function setPortalMajor(major) {
            currentMajorFilter = major;

            // Update tab jurusan yang aktif
            document.querySelectorAll('#portalMajorTabs .portal-major-tab').forEach(tab => {
                tab.classList.toggle('active', tab.getAttribute('data-major') === major);
            });

            if (major === 'all') {
                // Sembunyikan class pills per-jurusan
                document.querySelectorAll('#portalSubClassPills .portal-class-pill:not(.portal-level-pill)').forEach(pill => {
                    pill.style.display = 'none';
                    pill.classList.remove('active');
                });
                // Tampilkan 2 tombol tingkat, dan default HANYA tingkat X yang aktif (TERPISAH)
                currentLevelFilter = 'X';
                document.querySelectorAll('#portalSubClassPills .portal-level-pill').forEach(pill => {
                    pill.style.display = 'inline-flex';
                    pill.classList.toggle('active', pill.getAttribute('data-level-target') === 'X');
                });
            } else {
                // Sembunyikan 2 tombol tingkat
                document.querySelectorAll('#portalSubClassPills .portal-level-pill').forEach(pill => {
                    pill.style.display = 'none';
                    pill.classList.remove('active');
                });

                // Dapatkan pills untuk jurusan ini
                const majorPills = Array.from(document.querySelectorAll('#portalSubClassPills .portal-class-pill:not(.portal-level-pill)')).filter(pill => {
                    return pill.getAttribute('data-major') === major;
                });

                // Pilih Kelas X sebagai kelas aktif default (TIDAK DIGABUNG dengan Kelas XI)
                const gradeXPill = majorPills.find(p => p.getAttribute('data-grade') === 'X') || majorPills[0];
                const activeClassId = gradeXPill ? gradeXPill.getAttribute('data-class-target') : '';
                currentClassFilter = activeClassId;

                // Tampilkan hanya 2 pill jurusan ini, dan aktifkan HANYA Kelas X
                document.querySelectorAll('#portalSubClassPills .portal-class-pill:not(.portal-level-pill)').forEach(pill => {
                    const pillMajor = pill.getAttribute('data-major');
                    if (pillMajor === major) {
                        pill.style.display = 'inline-flex';
                        pill.classList.toggle('active', pill.getAttribute('data-class-target') === String(activeClassId));
                    } else {
                        pill.style.display = 'none';
                        pill.classList.remove('active');
                    }
                });
            }

            // Sync hidden dropdowns if present
            const majorSelect = document.getElementById('majorFilterSelect');
            if (majorSelect && majorSelect.value !== major) {
                majorSelect.value = major;
            }

            const classSelect = document.getElementById('classFilterSelect');
            if (classSelect) {
                classSelect.value = currentClassFilter;
            }

            applyPortalFilters();
        }

        // Memilih kelas secara terpisah (Jika pilih Kelas X, hanya Kelas X yang tampil; bukan digabung)
        function selectPortalClass(classId) {
            currentClassFilter = String(classId);

            // Perbarui status aktif tombol kelas di jurusan saat ini: HANYA kelas yang diklik yang aktif
            const majorPills = document.querySelectorAll('#portalSubClassPills .portal-class-pill:not(.portal-level-pill)');
            majorPills.forEach(pill => {
                if (pill.getAttribute('data-major') === currentMajorFilter) {
                    pill.classList.toggle('active', pill.getAttribute('data-class-target') === String(classId));
                }
            });

            const classSelect = document.getElementById('classFilterSelect');
            if (classSelect) {
                classSelect.value = classId;
            }

            applyPortalFilters();
        }

        // Alias kompatibilitas
        function togglePortalClass(classId) {
            selectPortalClass(classId);
        }

        // Memilih tingkat kelas pada mode 'Semua Jurusan' secara terpisah
        function selectPortalLevel(level) {
            currentLevelFilter = level;

            const levelPills = document.querySelectorAll('#portalSubClassPills .portal-level-pill');
            levelPills.forEach(pill => {
                pill.classList.toggle('active', pill.getAttribute('data-level-target') === level);
            });

            applyPortalFilters();
        }

        function togglePortalLevel(level) {
            selectPortalLevel(level);
        }

        function onMajorFilterChange(selectedMajor) {
            setPortalMajor(selectedMajor);
        }

        function onClassFilterChange(selectedClassId) {
            selectPortalClass(selectedClassId);
        }

        function setFilterDay(day) {
            currentDayFilter = day;
            document.querySelectorAll('#dayPillsContainer .pill-day').forEach(pill => {
                pill.classList.toggle('active', pill.getAttribute('data-day') === day);
            });
            applyPortalFilters();
        }

        function changePageSize(size) {
            pageSize = size;
            applyPortalFilters();
        }

        function goToPage(page) {
            currentPage = page;
            applyPortalFilters();
        }

        function resetPortalFilters() {
            currentDayFilter = 'all';
            currentMajorFilter = 'PPLG';
            currentLevelFilter = 'X';

            document.querySelectorAll('#dayPillsContainer .pill-day').forEach(pill => {
                pill.classList.toggle('active', pill.getAttribute('data-day') === 'all');
            });

            document.querySelectorAll('#portalMajorTabs .portal-major-tab').forEach(tab => {
                tab.classList.toggle('active', tab.getAttribute('data-major') === 'PPLG');
            });

            setPortalMajor('PPLG');

            const kw = document.getElementById('keywordInput');
            if (kw) kw.value = '';

            applyPortalFilters();
        }

        function applyPortalFilters() {
            const keyword = (document.getElementById('keywordInput')?.value || '').trim().toLowerCase();
            const classCards = document.querySelectorAll('.portal-class-card');
            const overallEmpty = document.getElementById('portalOverallEmptyState');
            const counterText = document.getElementById('scheduleCounterText');
            const paginationInfoText = document.getElementById('paginationInfoText');

            let totalVisibleKBM = 0;
            let visibleClassCards = 0;

            classCards.forEach(card => {
                const cardMajor = card.getAttribute('data-major');
                const cardClassId = card.getAttribute('data-class-id');
                const cardName = card.getAttribute('data-class-name') || '';

                const matchesMajor = (currentMajorFilter === 'all' || cardMajor === currentMajorFilter);
                
                // Kelas ditampilkan TERPISAH (hanya kelas yang dipilih yang tampil)
                let matchesClass = false;
                if (currentMajorFilter === 'all') {
                    if (currentLevelFilter === 'X') {
                        matchesClass = cardName.startsWith('x ');
                    } else if (currentLevelFilter === 'XI') {
                        matchesClass = cardName.startsWith('xi ');
                    } else {
                        matchesClass = true;
                    }
                } else {
                    matchesClass = (cardClassId === String(currentClassFilter));
                }

                if (!matchesMajor || !matchesClass) {
                    card.style.display = 'none';
                    return;
                }

                // Filter tempat hari di dalam kelas ini
                const dayBoxes = card.querySelectorAll('.portal-day-box');
                let cardVisibleKBM = 0;
                let dayMatchesCount = 0;

                dayBoxes.forEach(box => {
                    const boxDay = box.getAttribute('data-day');
                    const matchesDay = (currentDayFilter === 'all' || boxDay === currentDayFilter);

                    if (!matchesDay) {
                        box.style.display = 'none';
                        return;
                    }

                    // Filter items di dalam box hari ini
                    const items = box.querySelectorAll('.portal-schedule-item');
                    let boxMatchingItems = 0;

                    items.forEach(item => {
                        const searchData = item.getAttribute('data-search') || '';
                        const matchesKeyword = (!keyword || searchData.includes(keyword));

                        if (matchesKeyword) {
                            item.style.display = '';
                            boxMatchingItems++;
                            cardVisibleKBM++;
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    // Jika ada keyword pencarian dan box ini memiliki item tapi tak ada yg cocok
                    if (keyword && items.length > 0 && boxMatchingItems === 0) {
                        box.style.display = 'none';
                    } else {
                        box.style.display = '';
                        dayMatchesCount++;
                    }
                });

                if (dayMatchesCount > 0) {
                    card.style.display = '';
                    visibleClassCards++;
                    totalVisibleKBM += cardVisibleKBM;
                } else {
                    card.style.display = 'none';
                }
            });

            // Atur mode 1 kolom jika hanya 1 kartu kelas yang tampil
            if (container) {
                container.classList.toggle('single-mode', visibleClassCards === 1);
            }

            if (overallEmpty) {
                overallEmpty.style.display = (visibleClassCards === 0) ? '' : 'none';
            }

            if (counterText) {
                const dayLabelMap = {
                    'all': 'Semua Hari (Senin – Jumat)',
                    'monday': 'Hari Senin',
                    'tuesday': 'Hari Selasa',
                    'wednesday': 'Hari Rabu',
                    'thursday': 'Hari Kamis',
                    'friday': 'Hari Jumat'
                };
                const majorSuffix = (currentMajorFilter !== 'all') ? ` • Jurusan ${currentMajorFilter}` : '';
                counterText.textContent = `Menampilkan ${totalVisibleKBM} jam KBM terstruktur (${dayLabelMap[currentDayFilter] || currentDayFilter}${majorSuffix})`;
            }

            if (paginationInfoText) {
                paginationInfoText.innerHTML = `Menampilkan seluruh <strong>${totalVisibleKBM}</strong> jam KBM terstruktur di <strong>${visibleClassCards}</strong> kelas`;
            }
        }

        // Alias applyFilters to applyPortalFilters for compatibility
        function applyFilters() {
            applyPortalFilters();
        }

        // Dialog Modal Admin
        function openAdminModal(customNotice) {
            const modal = document.getElementById('adminAuthModal');
            if (modal) {
                modal.classList.add('show');
                const note = document.getElementById('modalInfoNoteText');
                if (note && customNotice) {
                    note.innerHTML = customNotice;
                }
                const input = document.getElementById('adminUsernameInput');
                if (input) {
                    setTimeout(() => input.focus(), 100);
                }
            }
        }

        function closeAdminModal() {
            const modal = document.getElementById('adminAuthModal');
            if (modal) {
                modal.classList.remove('show');
            }
        }

        function handleBackdropClick(e) {
            if (e.target.id === 'adminAuthModal') {
                closeAdminModal();
            }
        }

        function handlePortalEdit(data) {
            openAdminModal('<strong>Akses Edit Jadwal:</strong> Masuk sebagai Administrator Kurikulum untuk langsung mengubah jadwal ini di Dashboard Admin.');
        }

        // Navigasi 3 Fitur Interaktif (Pengenalan Sistem, Program Keahlian, Jadwal Pelajaran)
        function setActiveNav(sectionId) {
            document.querySelectorAll('#portalNavLinks .nav-link-item').forEach(link => {
                link.classList.toggle('active', link.getAttribute('data-target') === sectionId);
            });
        }

        function navigateToSection(event, sectionId) {
            if (event) {
                event.preventDefault();
            }
            const targetEl = document.getElementById(sectionId);
            if (targetEl) {
                setActiveNav(sectionId);
                targetEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                if (history.replaceState) {
                    history.replaceState(null, '', '#' + sectionId);
                }
            }
        }

        function jumpToScheduleSearch() {
            navigateToSection(null, 'jadwal-pelajaran');
            const kwInput = document.getElementById('keywordInput');
            if (kwInput) {
                setTimeout(() => kwInput.focus(), 400);
            }
        }

        function selectMajorAndScroll(major) {
            setPortalMajor(major);
            navigateToSection(null, 'jadwal-pelajaran');
        }

        // Sinkronkan highlight menu navigasi saat halaman digulir
        window.addEventListener('scroll', () => {
            const sections = ['pengenalan', 'program-keahlian', 'jadwal-pelajaran'];
            let activeId = 'pengenalan';
            const scrollPos = window.scrollY + 130;

            sections.forEach(id => {
                const el = document.getElementById(id);
                if (el && el.offsetTop <= scrollPos) {
                    activeId = id;
                }
            });

            setActiveNav(activeId);
        }, { passive: true });

        // Cetak Jadwal Pelajaran (Khusus Siswa / Pelajaran)
        function printSubjectSchedule() {
            window.print();
        }

        window.addEventListener('afterprint', () => {
            applyPortalFilters();
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeAdminModal();
            }
        });

        // Initialize schedule cards on load & check query param
        document.addEventListener('DOMContentLoaded', () => {
            setPortalMajor('PPLG');

            // Auto-buka modal admin jika URL meminta ?portal=admin atau hash #admin
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('portal') === 'admin' || window.location.hash === '#admin' || window.location.hash === '#admin-login') {
                openAdminModal();
            }
        });

        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', () => {
                openAdminModal();
            });
        @endif
    </script>
</body>
</html>
