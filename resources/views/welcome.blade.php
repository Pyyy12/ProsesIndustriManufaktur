<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Industri Manufaktur | Politeknik STMI</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    {{-- CSS INLINE DARI KODE ANDA (TIDAK ADA PERUBAHAN DI SINI) --}}
    <style>
        /* =========================================================
    SCROLL TO TOP BUTTON
========================================================= */
        #scrollToTopBtn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #0d6efd;
            color: white;
            border: none;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            font-size: 20px;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            z-index: 999;
        }

        #scrollToTopBtn:hover {
            background-color: #084298;
            transform: translateY(-5px);
        }

        /* =========================================================
    NAVBAR & HEADER
========================================================= */
        .main-nav {
            width: 100%;
            background: #ffffff;
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .main-nav .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-weight: 700;
            font-size: 22px;
            color: #0d6efd;
            text-decoration: none;
        }

        /* NAV LINKS DESKTOP */
        .nav-links {
            display: flex;
            gap: 25px;
        }

        .nav-links li {
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: #0d6efd;
            font-weight: 500;
        }

        .nav-links a:hover {
            color: #084298;
        }

        /* Hamburger */
        .menu-toggle {
            display: none;
            font-size: 28px;
            cursor: pointer;
            color: #0d6efd;
        }

        /* MOBILE: hide nav, show hamburger */
        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }

            .nav-links {
                display: none;
            }
        }

        /* =========================================================
    RIGHT SIDEBAR (MOBILE MENU)
========================================================= */
        .right-sidebar {
            position: fixed;
            top: 0;
            right: -260px;
            width: 260px;
            height: 100%;
            background: white;
            color: #0d6efd;
            padding-top: 80px;
            transition: 0.3s ease;
            z-index: 9999;
            box-shadow: -4px 0 10px rgba(0, 0, 0, 0.1);
        }

        .right-sidebar.show {
            right: 0;
        }

        .right-sidebar ul {
            list-style: none;
        }

        .right-sidebar ul li {
            padding: 15px 20px;
        }

        .right-sidebar ul li a {
            color: #0d6efd;
            text-decoration: none;
            font-size: 18px;
        }

        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: 9998;
        }

        .overlay.show {
            display: block;
        }

        /* =========================================================
    HERO SLIDER (OPSI B)
========================================================= */
        .hero-section {
            position: relative;
            width: 100%;
            height: 70vh;
            min-height: 420px;
            overflow: hidden;
        }

        @media (max-width: 768px) {
            .hero-section {
                height: 45vh;
                min-height: 260px;
            }
        }

        .slider-container .slide {
            width: 100%;
            height: 100%;
            background-size: cover !important;
            background-position: center !important;
            background-repeat: no-repeat;
        }

        /* =========================================================
    ABOUT SLIDESHOW
========================================================= */
        .about-slideshow {
            position: relative;
            width: 100%;
            height: 350px;
            border-radius: 15px;
            overflow: hidden;
        }

        @media (max-width: 768px) {
            .about-slideshow {
                height: 220px;
            }
        }

        .about-slide {
            position: absolute;
            inset: 0;
            background-size: cover !important;
            background-position: center !important;
            opacity: 0;
            transition: opacity 0.8s ease-in-out;
        }

        .about-slide.active {
            opacity: 1;
        }

        /* =========================================================
    TEAM SECTION — Gambar rapih
========================================================= */
        .team-img-wrapper img {
            width: 100%;
            height: 260px;
            object-fit: cover;
            border-radius: 10px;
        }

        @media (max-width: 768px) {
            .team-img-wrapper img {
                height: 180px;
            }
        }

        /* =========================================================
    GENERIC RESPONSIVE IMAGE
========================================================= */
        img {
            width: 100%;
            height: auto;
            object-fit: cover;
            display: block;
        }

        /* =========================================================
    FIX DESKTOP LAYOUT UNTUK ABOUT (2 KOLOM RAPIH)
========================================================= */
        /* ====== Layout dua kolom (desktop) ====== */
        .two-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: start;
        }

        /* wrapper kanan untuk menumpuk slideshow */
        .about-right-column {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* ukuran slideshow default (desktop/tablet) */
        .about-right-column .about-slideshow {
            width: 100%;
            height: 350px;
            border-radius: 12px;
            overflow: hidden;
        }

        /* setiap slide background cover */
        .about-slide {
            background-size: cover !important;
            background-position: center !important;
        }

        /* ====== Perilaku pada layar kecil (mobile) ====== */
        @media (max-width: 992px) {
            /* ubah menjadi satu kolom: teks di atas, gambar di bawah */
            .two-columns {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            /* pastikan urutan: teks dulu, kanan (gambar) kedua */
            .about-content {
                order: 1; /* tetap di atas */
                padding-bottom: 10px;
            }

            .about-right-column {
                order: 2; /* tampil di bawah teks */
            }

            /* kurangi tinggi slideshow agar muat di layar kecil */
            .about-right-column .about-slideshow {
                height: 220px;
                min-height: 180px;
            }

            /* ringkas teks jika perlu agar tidak terlalu panjang pada mobile */
            .about-content p {
                font-size: 15px;
                line-height: 1.6;
            }
        }

        /* Jika ingin slideshow kedua lebih pendek (opsional) */
        .about-right-column .about-slideshow.small {
            height: 220px;
        }

        /* ===================================
    RESET DAN PENGATURAN DASAR (Dari style.css)
================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: #555;
            background-color: #fff;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        a {
            text-decoration: none;
            color: inherit;
            transition: color 0.3s;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        /* ===================================
    TOP BAR & NAVIGASI (Dari style.css)
=================================== */
        .top-bar {
            background-color: #0d2d52;
            color: #a9c1de;
            padding: 10px 0;
            font-size: 14px;
        }

        .contact-info span {
            margin-right: 20px;
        }

        .social-icons a {
            margin-left: 15px;
        }

        .social-icons a:hover {
            color: #fff;
        }

        .main-nav {
            background-color: #fff;
            padding: 20px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .top-bar .container,
        .main-nav .container {
            max-width: 100%;
            padding: 0 30px;
        }

        .main-nav .logo {
            font-size: 28px;
            font-weight: 700;
            color: #0d2d52;
            position: relative;
            padding-bottom: 8px;
        }

        .main-nav .logo i {
            margin-right: 8px;
        }

        @keyframes loading-bar {
            0% {
                width: 0;
                left: 0;
            }

            50% {
                width: 100%;
                left: 0;
            }

            100% {
                width: 0;
                left: 100%;
            }
        }

        .main-nav .logo::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background-color: #007bff;
            animation: loading-bar 2.5s linear infinite;
        }

        .nav-links {
            list-style: none;
            display: flex;
        }

        .nav-links li a {
            padding: 10px 15px;
            font-weight: 500;
            color: #0d2d52;
            position: relative;
            text-decoration: none;
        }

        .nav-links li a.active,
        .nav-links li a:hover {
            color: #007bff;
        }

        .nav-links li a::after {
            content: '';
            position: absolute;
            width: 60%;
            height: 2px;
            bottom: 0;
            left: 20%;
            background-color: #007bff;
            transform: scaleX(0);
            transform-origin: center;
            transition: transform 0.3s ease-out;
        }

        .nav-links li a:hover::after,
        .nav-links li a.active::after {
            transform: scaleX(1);
            transform-origin: center;
        }

        .nav-partner-logos {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .nav-partner-logos img {
            height: 40px;
            width: auto;
            opacity: 0.7;
            transition: opacity 0.3s ease;
        }

        .nav-partner-logos img:hover {
            opacity: 1;
        }

        /* ===================================
    HERO SECTION (SLIDER) (Dari style.css)
=================================== */
        @keyframes kenburns-zoom {
            from {
                background-size: 100% 100%;
            }

            to {
                background-size: 110% 110%;
            }
        }


        .hero-section {
            position: relative;
            height: 90vh;
            overflow: hidden;
            color: #fff;
        }

        .slider-container {
            width: 100%;
            height: 100%;
        }

        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: 100% 100%;
            background-position: center;
            opacity: 0;
            transition: opacity 0.7s ease-in-out;
        }

        .slide.active {
            opacity: 1;
            animation: kenburns-zoom 10s ease-out forwards;
        }

        .slide::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .slide-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            z-index: 2;
            width: 90%;
        }

        .tagline {
            font-size: 18px;
            font-weight: 500;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }

        .slide-content h1 {
            font-size: 64px;
            font-weight: 700;
            margin-bottom: 30px;
            line-height: 1.2;
        }

        .slider-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 123, 255, 0.7);
            color: #fff;
            border: none;
            padding: 15px;
            font-size: 20px;
            cursor: pointer;
            z-index: 10;
            transition: background-color 0.3s;
        }

        .slider-nav:hover {
            background-color: rgba(0, 123, 255, 1);
        }

        .prev {
            left: 20px;
        }

        .next {
            right: 20px;
        }

        .slide .slide-content .tagline,
        .slide .slide-content h1,
        .slide .slide-content .hero-buttons {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        .slide.active .slide-content .tagline {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 0.3s;
        }

        .slide.active .slide-content h1 {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 0.5s;
        }

        .slide.active .slide-content .hero-buttons {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 0.7s;
        }

        /* ===================================
    STYLING UMUM & TOMBOL (Dari style.css)
=================================== */
        .section-padding {
            padding: 80px 0;
        }

        .bg-light {
            background-color: #f8f9fa;
        }

        .section-tagline {
            color: #007bff;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .section-title {
            font-size: 36px;
            color: #0d2d52;
            margin-bottom: 40px;
        }

        .container.column-center {
            flex-direction: column;
            text-align: center;
        }

        .dynamic-color-text {
            transition: color 0.3s ease-in-out;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.3s;
            text-align: center;
            border: 2px solid transparent;
        }

        .hero-buttons .btn {
            margin: 0 10px;
            font-size: 16px;
            padding: 15px 0;
            position: relative;
            overflow: hidden;
            z-index: 1;
            width: 200px;
        }

        .hero-buttons .btn span {
            position: relative;
            z-index: 2;
        }

        .hero-buttons .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            transition: transform 0.4s ease;
        }

        .hero-buttons .btn-primary {
            border-color: #007bff;
            background-color: #007bff;
            color: #fff;
        }

        .hero-buttons .btn-primary::before {
            background-color: #fff;
            transform: scaleX(0);
            transform-origin: right;
        }

        .hero-buttons .btn-primary:hover::before {
            transform: scaleX(1);
            transform-origin: left;
        }

        .hero-buttons .btn-primary:hover {
            color: #007bff;
        }

        .hero-buttons .btn-secondary {
            border-color: #fff;
            background-color: transparent;
            color: #fff;
        }

        .hero-buttons .btn-secondary::before {
            background-color: #fff;
            transform: scaleX(0);
            transform-origin: right;
        }

        .hero-buttons .btn-secondary:hover::before {
            transform: scaleX(1);
            transform-origin: left;
        }

        .hero-buttons .btn-secondary:hover {
            color: #0d2d52;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        /* ===================================
    ABOUT, SERVICES, TEAM (Dari style.css)
=================================== */
        #about .two-columns {
            align-items: flex-start;
            gap: 50px;
        }

        #about .about-content {
            flex-basis: 55%;
        }

        #about .about-image-new {
            flex-basis: 45%;
            position: relative;
            margin-top: 20px;
        }

        #about .about-image-new img {
            border-radius: 10px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            width: 100%;
        }

        #about .about-image-new::before {
            content: '';
            position: absolute;
            top: -20px;
            left: -20px;
            width: 100%;
            height: 100%;
            background-color: #eaf3ff;
            border-radius: 10px;
            z-index: -1;
            transition: all 0.3s ease;
        }

        #about .about-image-new:hover::before {
            transform: translate(10px, 10px);
        }

        #about .about-features {
            margin-top: 30px;
            margin-bottom: 30px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .feature-box {
            display: flex;
            align-items: flex-start;
            gap: 20px;
        }

        .feature-icon {
            background-color: #007bff;
            color: #fff;
            font-size: 22px;
            width: 50px;
            height: 50px;
            min-width: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .feature-text h4 {
            color: #0d2d52;
            margin-bottom: 5px;
            font-size: 18px;
        }

        .feature-text p {
            margin: 0;
            line-height: 1.5;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            width: 100%;
        }

        .service-card {
            background: #fff;
            padding: 40px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .service-icon {
            font-size: 40px;
            color: #007bff;
            margin-bottom: 20px;
        }

        .service-card h3 {
            color: #0d2d52;
            margin-bottom: 15px;
        }

        #team .section-title {
            margin-bottom: 50px;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
            width: 100%;
        }

        .team-card {
            text-align: center;
            background: #fff;
        }

        .team-img-wrapper {
            position: relative;
            width: 180px;
            height: 180px;
            margin: 0 auto 20px;
            border-radius: 50%;
            overflow: hidden;
            border: 6px solid #eaf3ff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .team-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .team-social-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-color: rgba(0, 123, 255, 0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .team-img-wrapper:hover img {
            transform: scale(1.1);
        }

        .team-img-wrapper:hover .team-social-overlay {
            opacity: 1;
        }

        .team-info h4 {
            color: #0d2d52;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .team-info p {
            color: #777;
            font-style: italic;
        }

        /* ===================================
    FOOTER (Dari style.css)
=================================== */
        .footer {
            background: #0d2d52;
            color: #a9c1de;
            padding: 70px 0 0;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            align-items: flex-start;
        }

        .footer-col h4 {
            display: inline-block;
            color: #fff;
            margin-bottom: 25px;
            font-size: 18px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-col h4::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            height: 2px;
            background-color: #007bff;
            animation: loading-bar 2.5s linear infinite;
        }

        .footer-col ul {
            list-style: none;
            padding: 0;
        }

        .footer-col ul li {
            margin-bottom: 12px;
            display: flex;
            align-items: flex-start;
        }

        .footer-col ul li a {
            position: relative;
            transition: all 0.3s ease;
        }

        .footer-col ul li a:hover {
            color: #fff;
            padding-left: 8px;
        }

        .footer-col ul li a::before {
            content: '\f105';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            left: -15px;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .footer-col ul li a:hover::before {
            left: -5px;
            opacity: 1;
        }

        .footer-col ul li i {
            margin-right: 12px;
            color: #007bff;
            margin-top: 4px;
        }

        .footer-col .social-icons {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            padding: 0;
            justify-content: flex-start;
            margin-left: -15px;
            /* DIUBAH: Menggeser ikon lebih jauh ke kiri */
        }

        .footer-col .social-icons a {
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            color: rgba(255, 255, 255, 0.7);
            transition: all 0.3s ease;
        }

        .footer-col .social-icons a:hover {
            background-color: #007bff;
            border-color: #007bff;
            color: #fff;
        }

        .map-container {
            width: 100%;
            height: 200px;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .footer-bottom {
            text-align: center;
            padding: 25px 0;
            margin-top: 50px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 14px;
        }

        /* ===================================
    ANIMASI SAAT SCROLL (Dari style.css)
=================================== */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .animate-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .service-card.animate-on-scroll:nth-child(2),
        .team-card.animate-on-scroll:nth-child(2),
        .footer-col.animate-on-scroll:nth-child(2) {
            transition-delay: 0.2s;
        }

        .service-card.animate-on-scroll:nth-child(3),
        .team-card.animate-on-scroll:nth-child(3),
        .footer-col.animate-on-scroll:nth-child(3) {
            transition-delay: 0.4s;
        }

        .feature-box.animate-on-scroll:nth-child(2) {
            transition-delay: 0.2s;
        }

        /* ===================================
    RESPONSIVE DESIGN (Dari style.css)
=================================== */
        @media (max-width: 992px) {
            #about .two-columns {
                flex-direction: column;
            }

            .services-grid,
            .team-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .footer-grid {
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            }
        }

        @media (max-width: 768px) {
            body {
                font-size: 15px;
            }

            .container {
                padding: 0 15px;
                flex-direction: column;
            }

            .top-bar {
                display: none;
            }

            .main-nav .container {
                flex-direction: row;
            }

            .main-nav .nav-links,
            .nav-partner-logos {
                display: none;
            }

            .hero-section {
                height: auto;
                min-height: 80vh;
                padding: 100px 0;
            }

            .slide-content h1 {
                font-size: 38px;
            }

            .hero-buttons {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 15px;
            }

            .hero-buttons .btn {
                width: 80%;
                max-width: 250px;
            }

            .section-padding {
                padding: 60px 0;
            }

            .section-title {
                font-size: 30px;
            }

            .services-grid,
            .team-grid {
                grid-template-columns: 1fr;
            }

            .service-card {
                padding: 30px;
            }

            .team-img-wrapper {
                width: 150px;
                height: 150px;
            }

            .footer-grid {
                text-align: center;
            }

            .footer-col .social-icons {
                justify-content: center;
                margin-left: 0;
            }

            .footer-col ul {
                padding: 0;
            }
        }

        /* Tombol Ke Atas */
        .scroll-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #0d6efd;
            color: white;
            border: none;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            font-size: 20px;
            cursor: pointer;
            display: none;
            /* disembunyikan dulu */
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            z-index: 999;
        }

        .scroll-to-top:hover {
            background-color: #084298;
            transform: translateY(-5px);
        }
    </style>
</head>

<body>

    <div id="preloader">
        <div class="spinner"></div>
    </div>

    <div class="top-bar">
        <div class="container">
            <div class="contact-info">
                <span><i class="fas fa-map-marker-alt"></i> Jl. Letjen Suprapto No.26, Cemp. Putih Tim. DKI Jakarta
                    10510</span>
                <span><i class="fas fa-phone"></i> (021)42886064</span>
                <span><i class="fas fa-envelope"></i> humas@stmi.ac.id</span>
            </div>
            <div class="social-icons">
                <a href="https://www.facebook.com/PoliteknikSTMIJakarta/?locale=id_ID"><i
                        class="fab fa-facebook-f"></i></a>
                <a href="https://id.linkedin.com/school/politeknik-stmi-jakarta/"><i class="fab fa-linkedin-in"></i></a>
                <a href="https://www.instagram.com/stmijakarta/"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>

    <nav class="main-nav">
        <div class="container">
            <a href="#hero" class="logo"><i class="fas fa-user-tie"></i>STMI Jakarta</a>
            <div class="menu-toggle" id="menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
            <div class="overlay" id="overlay"></div>

            <ul class="nav-links" id="nav-links">
                <li><a href="#hero" class="active">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#team">Features</a></li>
                <li><a href="{{ route('login') }}">Login</a></li> 
            </ul>

            <div class="overlay" id="overlay"></div>

            <div class="right-sidebar" id="right-sidebar">
                <ul>
                    <li><a href="#hero">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#team">Team</a></li>
                    <li><a href="{{ route('login') }}">Login</a></li> 
                </ul>
            </div>

            <div class="nav-partner-logos">
    <img src="{{ asset('images/Logo_STMI.png') }}" alt="Logo Klien 1">
    <img src="{{ asset('images/Logo - Kemenperin.png') }}" alt="Logo Klien 2">
</div>
        </div>
    </nav>

    <header class="hero-section" id="hero">
        <div class="slider-container">
            <div class="slide active" style="background-image: url('images/STMI2.jpg');">
                <div class="slide-content">
                    <p class="tagline">Manufacturing Industrial Processes</p>
                    <h1><span class="dynamic-color-text">Creative</span> & Innovative Digital</h1>
                </div>
            </div>
            <div class="slide" style="background-image: url('images/TIO.jpg');">
                <div class="slide-content">
                    <p class="tagline">WE ARE THE BEST</p>
                    <h1><span class="dynamic-color-text">For The Future</span> education </h1>
                </div>
            </div>
            <div class="slide" style="background-image: url('images/MANPRO 2.png');">
                <div class="slide-content">
                    <p class="tagline">FOR YOUR INFORMATION</p>
                    <h1><span class="dynamic-color-text">Grow</span> Future Experience</h1>
                </div>
            </div>
        </div>
        <button class="slider-nav prev"><i class="fas fa-chevron-left"></i></button>
        <button class="slider-nav next"><i class="fas fa-chevron-right"></i></button>
    </header>

    <main>
        <section id="about" class="section-padding">
            <div class="container two-columns">

                <div class="about-content">
                    <h5 class="section-tagline animate-on-scroll">ABOUT US</h5>
                    <h2 class="section-title animate-on-scroll">
                        Teknologi & Inovasi Otomotif dari Politeknik STMI Jakarta
                    </h2>
                    <p class="animate-on-scroll">
                        Program Studi <strong>Teknik Industri Otomotif (TIO)</strong> dan
                        <strong>Teknologi Rekayasa Otomotif (TRO)</strong> Politeknik STMI Jakarta
                        berkomitmen mencetak tenaga profesional dan inovatif di bidang industri otomotif.
                        Melalui pembelajaran vokasi, riset terapan, dan kerja sama industri, kami menyiapkan
                        lulusan yang siap bersaing di era transformasi digital otomotif.
                    </p>

                    <div class="about-features">
                        <div class="feature-box animate-on-scroll">
                            <div class="feature-icon"><i class="fas fa-cogs"></i></div>
                            <div class="feature-text">
                                <h4>Fokus Industri Otomotif</h4>
                                <p>Menyiapkan SDM unggul dalam desain, produksi, dan manajemen sistem otomotif modern.</p>
                            </div>
                        </div>
                        <div class="feature-box animate-on-scroll">
                            <div class="feature-icon"><i class="fas fa-graduation-cap"></i></div>
                            <div class="feature-text">
                                <h4>Pendidikan Vokasi Terapan</h4>
                                <p>Kombinasi teori dan praktik nyata melalui sistem pembelajaran berbasis industri.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="about-right-column">
                    <div class="about-slideshow animate-on-scroll">
                        <div class="about-slide active" style="background-image: url('images/IMG20240325090553.jpg');"></div>
                        <div class="about-slide" style="background-image: url('images/TIO 1.jpeg');"></div>
                        <div class="about-slide" style="background-image: url('images/TIO 2.jpeg');"></div>
                    </div>

                    <div class="about-slideshow animate-on-scroll" style="margin-top:20px;">
                        <div class="about-slide active" style="background-image: url('images/TRO Praktek.jpeg');"></div>
                        <div class="about-slide" style="background-image: url('images/TRO Praktek 2.jpeg');"></div>
                        <div class="about-slide" style="background-image: url('images/TRO Praktek 3.jpeg');"></div>
                    </div>
                </div>

            </div>
        </section>

        <section id="team" class="section-padding bg-light">
    <div class="container column-center">
        <h5 class="section-tagline animate-on-scroll">WHY CHOOSE US</h5>
        <h2 class="section-title animate-on-scroll">Keunggulan Pendidikan Kami</h2>
        
        <div class="services-grid"> <div class="service-card animate-on-scroll">
                <div class="service-icon">
                    <i class="fas fa-microchip"></i>
                </div>
                <h3>Kurikulum Industri 4.0</h3>
                <p>Materi pembelajaran yang selalu diperbarui sesuai dengan kebutuhan teknologi manufaktur dan digitalisasi otomotif terkini.</p>
            </div>

            <div class="service-card animate-on-scroll">
                <div class="service-icon">
                    <i class="fas fa-tools"></i>
                </div>
                <h3>Laboratorium Modern</h3>
                <p>Fasilitas praktik dengan standar industri untuk memastikan mahasiswa memiliki keahlian teknis yang nyata dan siap pakai.</p>
            </div>

            <div class="service-card animate-on-scroll">
                <div class="service-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <h3>Koneksi Luas</h3>
                <p>Jejaring kerja sama yang kuat dengan berbagai perusahaan otomotif multinasional untuk program magang dan penyerapan lulusan.</p>
            </div>

        </div>
    </div>
</section>
    </main>

    <footer class="footer">
        <div class="container footer-grid">
            <div class="footer-col animate-on-scroll">
                <h4>STMI Jakarta</h4>
                <div class="social-icons">
                    <a href="https://www.facebook.com/PoliteknikSTMIJakarta/?locale=id_ID"><i
                            class="fab fa-facebook-f"></i></a>
                    <a href="https://id.linkedin.com/school/politeknik-stmi-jakarta/"><i
                            class="fab fa-linkedin-in"></i></a>
                    <a href="https://www.instagram.com/stmijakarta/"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="footer-col animate-on-scroll">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="#about">About Us</a></li>

                    <li><a href="#team">Our Features</a></li>
                </ul>
            </div>
            <div class="footer-col animate-on-scroll">
                <h4>Contact Info</h4>
                <ul>
                    <li><i class="fas fa-map-marker-alt"></i>Jl. Letjen Suprapto No.26, Cemp. Putih Tim. DKI Jakarta
                        10510</li>
                    <li><i class="fas fa-phone"></i> (021)42886064</li>
                    <li><i class="fas fa-envelope"></i> humas@stmi.ac.id</li>
                </ul>
            </div>
            <div class="footer-col animate-on-scroll">
                <h4>Our Location</h4>
                <div class="map-container">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d63467.2591516384!2d106.867853!3d-6.170415!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5043973ff63%3A0xc125c1242e567fd1!2sPolytechnic%20STMI%20Jakarta!5e0!3m2!1sen!2sid!4v1760433946009!5m2!1sen!2sid"
                        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2025 STMI Jakarta. All Rights Reserved. Designed by You.</p>
        </div>
    </footer>

    <button id="scrollToTopBtn" class="scroll-to-top"><i class="fas fa-arrow-up"></i></button>

    <script>
        // Pastikan posisi halaman selalu di atas saat direfresh
        window.onbeforeunload = function () {
            window.scrollTo(0, 0);
        };

        // Tombol scroll ke atas
        const scrollToTopBtn = document.getElementById("scrollToTopBtn");

        window.addEventListener("scroll", () => {
            if (window.scrollY > 300) {
                scrollToTopBtn.style.display = "flex";
            } else {
                scrollToTopBtn.style.display = "none";
            }
        });

        scrollToTopBtn.addEventListener("click", () => {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });
    </script>

   
    <script>
        // Hamburger Menu & Overlay
        const menuToggle = document.getElementById('menu-toggle');
        const sidebar = document.getElementById('right-sidebar');
        const overlay = document.getElementById('overlay');

        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });

        // Klik overlay untuk menutup menu
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
    </script>

    <script>
        // ===== AUTO SLIDESHOW UNTUK SEMUA .about-slideshow (Disesuaikan) =====
        const allSlideshows = document.querySelectorAll('.about-slideshow');

        allSlideshows.forEach(slideshow => {
            let slides = slideshow.querySelectorAll('.about-slide');
            let index = 0;

            // Amankan jika hanya ada 1 slide
            if (slides.length <= 1) return;

            // Pastikan slide pertama aktif saat load
            slides[0].classList.add('active'); 

            setInterval(() => {
                slides[index].classList.remove('active');
                index = (index + 1) % slides.length;
                slides[index].classList.add('active');
            }, 3000); // 3 detik
        });
        
        // ===== HERO SLIDER NAVIGASI (Tambahan Logic) =====
        const heroSlides = document.querySelectorAll('.hero-section .slide');
        const prevBtn = document.querySelector('.slider-nav.prev');
        const nextBtn = document.querySelector('.slider-nav.next');
        let currentHeroIndex = 0;

        function updateHeroSlider(n) {
            heroSlides[currentHeroIndex].classList.remove('active');
            currentHeroIndex = (n + heroSlides.length) % heroSlides.length;
            heroSlides[currentHeroIndex].classList.add('active');
        }

        prevBtn.addEventListener('click', () => {
            updateHeroSlider(currentHeroIndex - 1);
        });

        nextBtn.addEventListener('click', () => {
            updateHeroSlider(currentHeroIndex + 1);
        });

        // Auto slide Hero
        // setInterval(() => {
        //     updateHeroSlider(currentHeroIndex + 1);
        // }, 8000); // 8 detik

        // ===== ANIMATION ON SCROLL LOGIC =====
        const scrollElements = document.querySelectorAll(".animate-on-scroll");

        const elementInView = (el, dividend = 1) => {
            const elementTop = el.getBoundingClientRect().top;
            return (
                elementTop <= (window.innerHeight || document.documentElement.clientHeight) / dividend
            );
        };

        const displayScrollElement = (element) => {
            element.classList.add("visible");
        };

        const handleScrollAnimation = () => {
            scrollElements.forEach((el) => {
                if (elementInView(el, 1.25)) { // Tampilkan saat 80% terlihat
                    displayScrollElement(el);
                }
            });
        };

        window.addEventListener("scroll", handleScrollAnimation);

        // Panggil saat load untuk elemen yang sudah terlihat
        handleScrollAnimation(); 
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

    // --- FUNGSI UNTUK TEKS BERUBAH WARNA (Diperbarui untuk semua slide) ---
    const dynamicColorTexts = document.querySelectorAll('.dynamic-color-text');
    if (dynamicColorTexts.length > 0) {
        const colors = ['#00aaff', '#ffc107', '#28a745', '#f8f9fa'];
        let colorIndex = 0;
        setInterval(() => {
            // Terapkan warna ke semua elemen yang ditemukan
            dynamicColorTexts.forEach(textElement => {
                textElement.style.color = colors[colorIndex];
            });
            colorIndex = (colorIndex + 1) % colors.length;
        }, 1500);
    }

    // --- FUNGSI UNTUK SLIDER GAMBAR ---
    const slides = document.querySelectorAll('.slide');
    if (slides.length > 0) {
        const nextBtn = document.querySelector('.next');
        const prevBtn = document.querySelector('.prev');
        let currentSlide = 0;
        function showSlide(index) {
            slides.forEach(slide => slide.classList.remove('active'));
            slides[index].classList.add('active');
        }
        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }
        function prevSlide() {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(currentSlide);
        }
        nextBtn.addEventListener('click', nextSlide);
        prevBtn.addEventListener('click', prevSlide);
        setInterval(nextSlide, 5000);
        showSlide(currentSlide);
    }

    // --- FUNGSI UNTUK SMOOTH SCROLLING ---
    const navLinks = document.querySelectorAll('.nav-links a[href^="#"]');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // --- FUNGSI UNTUK ACTIVE LINK ON SCROLL ---
    const sections = document.querySelectorAll('section, header');
    const mainNavLinks = document.querySelectorAll('.main-nav .nav-links a');
    window.addEventListener('scroll', () => {
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            if (window.pageYOffset >= sectionTop - 150) {
                current = section.getAttribute('id');
            }
        });
        mainNavLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + current) {
                link.classList.add('active');
            }
        });
    });

    // --- FUNGSI UNTUK ANIMASI SAAT SCROLL ---
    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1
    });
    animatedElements.forEach(el => {
        observer.observe(el);
    });

});

</script>
</body>

</html>