import React, { useState, useEffect, useRef } from 'react';
import { Volume2, VolumeX, Menu, X, Loader2 } from 'lucide-react';
import { useBackgroundAudio } from '../../hooks/useBackgroundAudio';
import { optimizeImageSrc } from '../../lib/media';

const NAV_LINKS = [
    { id: 'Home', label: 'Home', href: '#Home' },
    { id: 'About', label: 'About', href: '#About' },
    { id: 'Portfolio', label: 'Portfolio', href: '#Portfolio' },
    { id: 'Contact', label: 'Contact', href: '#Contact' },
];

export default function Navbar({ isVisible = true }) {
    const [activeSection, setActiveSection] = useState('Home');
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
    const {
        isPlaying,
        isAudioPending,
        autoplayBlocked,
        toggleAudio,
        dismissAutoplayHint,
    } = useBackgroundAudio();

    const audioButtonLabel = isPlaying
        ? 'Jeda musik Spotify'
        : isAudioPending
          ? 'Menghubungkan ke Spotify...'
          : 'Putar playlist Spotify';

    const renderAudioIcon = (idleColorClass) => {
        if (isPlaying) {
            return <Volume2 className="h-4 w-4 animate-pulse text-[#7185e8]" />;
        }
        if (isAudioPending) {
            return <Loader2 className="h-4 w-4 animate-spin text-amber-300" />;
        }
        return <VolumeX className={`h-4 w-4 ${idleColorClass}`} />;
    };
    const navContainerRef = useRef(null);

    useEffect(() => {
        const handleScroll = () => {
            for (const link of NAV_LINKS.slice().reverse()) {
                const el = document.getElementById(link.id);
                if (el) {
                    const rect = el.getBoundingClientRect();
                    if (rect.top <= 240) {
                        setActiveSection(link.id);
                        break;
                    }
                }
            }
        };

        window.addEventListener('scroll', handleScroll, { passive: true });
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    // Close mobile menu on click outside or on ESC key
    useEffect(() => {
        if (!isMobileMenuOpen) return;

        const handleClickOutside = (e) => {
            if (
                navContainerRef.current &&
                !navContainerRef.current.contains(e.target)
            ) {
                setIsMobileMenuOpen(false);
            }
        };

        const handleKeyDown = (e) => {
            if (e.key === 'Escape') {
                setIsMobileMenuOpen(false);
            }
        };

        document.addEventListener('mousedown', handleClickOutside);
        document.addEventListener('touchstart', handleClickOutside, {
            passive: true,
        });
        document.addEventListener('keydown', handleKeyDown);

        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
            document.removeEventListener('touchstart', handleClickOutside);
            document.removeEventListener('keydown', handleKeyDown);
        };
    }, [isMobileMenuOpen]);

    const handleNavClick = (e, id, href) => {
        e.preventDefault();
        setActiveSection(id);
        setIsMobileMenuOpen(false);
        const targetId = href.replace('#', '');
        const targetEl = document.getElementById(targetId);
        if (targetEl) {
            targetEl.scrollIntoView({ behavior: 'smooth' });
        }
    };

    if (!isVisible) {
        return null;
    }

    return (
        <header className="pointer-events-none fixed top-4 right-0 left-0 z-50 flex justify-center px-4 transition-opacity duration-300 sm:top-5">
            <div
                ref={navContainerRef}
                className="pointer-events-auto flex w-full max-w-sm flex-col items-center md:w-auto md:max-w-none"
            >
                {/* ================= DESKTOP NAVBAR (md and up) ================= */}
                <nav
                    className="relative hidden items-center gap-1.5 rounded-full border border-white/[0.15] bg-white/[0.03] p-2 shadow-[0_8px_32px_rgba(0,0,0,0.35)] ring-1 ring-white/5 backdrop-blur-[6px] transition-colors duration-300 md:flex"
                    aria-label="Main Navigation"
                >
                    {/* Prismatic glass edge glint & top sheen */}
                    <div
                        className="pointer-events-none absolute inset-x-8 top-0 h-px bg-gradient-to-r from-transparent via-[#7185e8]/25 via-emerald-400/20 via-rose-500/25 via-white/25 to-transparent"
                        aria-hidden="true"
                    />

                    {/* Nav Links */}
                    {NAV_LINKS.map((link) => {
                        const isActive = activeSection === link.id;
                        return (
                            <a
                                key={link.id}
                                href={link.href}
                                onClick={(e) =>
                                    handleNavClick(e, link.id, link.href)
                                }
                                className={`relative rounded-full px-5 py-2 text-sm transition-all duration-300 ${
                                    isActive
                                        ? 'border border-white/20 bg-white/10 font-semibold text-white shadow-inner backdrop-blur-sm'
                                        : 'font-medium text-slate-300 hover:bg-white/[0.06] hover:text-white'
                                }`}
                            >
                                {link.label}
                            </a>
                        );
                    })}

                    {/* Vertical Divider */}
                    <div
                        className="mx-1.5 h-4 w-px bg-white/15"
                        aria-hidden="true"
                    />

                    {/* Ambient / Spotify Audio Toggle Button */}
                    <button
                        type="button"
                        onClick={toggleAudio}
                        className={`flex h-9 w-9 cursor-pointer items-center justify-center rounded-full border text-slate-300 shadow-sm transition-all duration-200 hover:bg-white/15 hover:text-white active:scale-95 ${
                            isPlaying
                                ? 'border-[#7185e8]/50 bg-[#7185e8]/15'
                                : 'border-white/15 bg-white/[0.05]'
                        }`}
                        aria-label={audioButtonLabel}
                        title={audioButtonLabel}
                    >
                        {renderAudioIcon('text-slate-300')}
                    </button>
                </nav>

                {/* ================= MOBILE NAVBAR (< md) ================= */}
                <div className="flex w-full items-center justify-between rounded-full border border-white/[0.15] bg-[#0c1222]/85 px-3 py-1.5 shadow-[0_8px_32px_rgba(0,0,0,0.45)] ring-1 ring-white/10 backdrop-blur-xl md:hidden">
                    {/* Logo PP di Sisi Kiri */}
                    <a
                        href="#Home"
                        onClick={(e) => handleNavClick(e, 'Home', '#Home')}
                        className="group flex cursor-pointer items-center gap-2 pl-1"
                        aria-label="Kembali ke atas"
                    >
                        <img
                            src={optimizeImageSrc('/logo-pp.png', 128)}
                            alt="PP Logo"
                            width={32}
                            height={32}
                            decoding="async"
                            className="h-8 w-8 rounded-full border border-white/20 object-cover shadow-md transition-transform duration-200 group-hover:scale-105"
                            onError={(e) => {
                                if (
                                    e.currentTarget.src.includes('/media-opt/')
                                ) {
                                    e.currentTarget.src = '/logo-pp.png';
                                    return;
                                }
                                // Fallback monogram if image fails
                                e.currentTarget.style.display = 'none';
                                if (e.currentTarget.nextSibling) {
                                    e.currentTarget.nextSibling.style.display =
                                        'flex';
                                }
                            }}
                        />
                        <div className="hidden h-8 w-8 items-center justify-center rounded-full border border-white/20 bg-gradient-to-br from-[#262f6b] to-[#4e63d9] text-xs font-bold text-white shadow-md">
                            PP
                        </div>
                    </a>

                    {/* Tombol Kontrol Kanan: Audio & Hamburger */}
                    <div className="flex items-center gap-1.5">
                        {/* Audio Toggle Button */}
                        <button
                            type="button"
                            onClick={toggleAudio}
                            className={`flex h-8 w-8 cursor-pointer items-center justify-center rounded-full border text-slate-300 shadow-sm transition-all duration-200 hover:bg-white/15 hover:text-white active:scale-95 ${
                                isPlaying
                                    ? 'border-[#7185e8]/50 bg-[#7185e8]/15'
                                    : 'border-white/15 bg-white/[0.05]'
                            }`}
                            aria-label={audioButtonLabel}
                            title={audioButtonLabel}
                        >
                            {renderAudioIcon('text-slate-400')}
                        </button>

                        {/* Hamburger Toggle Button */}
                        <button
                            type="button"
                            onClick={() => setIsMobileMenuOpen((prev) => !prev)}
                            aria-label={
                                isMobileMenuOpen
                                    ? 'Tutup navigasi'
                                    : 'Buka navigasi'
                            }
                            aria-expanded={isMobileMenuOpen}
                            className="flex h-8 w-8 cursor-pointer items-center justify-center rounded-full border border-white/15 bg-white/[0.05] text-slate-200 shadow-sm transition-all duration-200 hover:bg-white/15 hover:text-white active:scale-95"
                        >
                            {isMobileMenuOpen ? (
                                <X className="h-4 w-4 text-white" />
                            ) : (
                                <Menu className="h-4 w-4 text-white" />
                            )}
                        </button>
                    </div>
                </div>

                {/* Floating Glass Card Dropdown Menu on Mobile */}
                {isMobileMenuOpen && (
                    <nav
                        className="mt-2 w-full rounded-2xl border border-white/[0.12] bg-[#0c1222]/95 p-2 shadow-2xl ring-1 ring-white/10 backdrop-blur-2xl transition-all duration-200 md:hidden"
                        aria-label="Mobile Navigation Menu"
                    >
                        <div className="flex flex-col gap-1">
                            {NAV_LINKS.map((link) => {
                                const isActive = activeSection === link.id;
                                return (
                                    <a
                                        key={link.id}
                                        href={link.href}
                                        onClick={(e) =>
                                            handleNavClick(
                                                e,
                                                link.id,
                                                link.href,
                                            )
                                        }
                                        className={`flex items-center justify-between rounded-xl px-4 py-2.5 text-sm font-medium transition-all duration-200 ${
                                            isActive
                                                ? 'border border-[#7185e8]/30 bg-gradient-to-r from-[#262f6b]/70 to-[#4e63d9]/40 font-semibold text-white shadow-inner'
                                                : 'text-slate-300 hover:bg-white/[0.06] hover:text-white'
                                        }`}
                                    >
                                        <span>{link.label}</span>
                                        {isActive && (
                                            <span className="h-1.5 w-1.5 rounded-full bg-[#7185e8] shadow-[0_0_8px_#7185e8]" />
                                        )}
                                    </a>
                                );
                            })}
                        </div>

                        {/* Mobile Menu Footer Info */}
                    </nav>
                )}

                {autoplayBlocked && !isPlaying && (
                    <div className="mt-2 flex items-center justify-center">
                        <div className="pointer-events-auto flex items-center gap-2 rounded-full border border-amber-400/30 bg-[#0c1222]/95 px-3 py-1.5 text-xs text-slate-200 shadow-lg ring-1 ring-white/10">
                            <button
                                type="button"
                                onClick={() => {
                                    dismissAutoplayHint();
                                    const targetEl =
                                        document.getElementById('Spotify');
                                    if (targetEl) {
                                        targetEl.scrollIntoView({
                                            behavior: 'smooth',
                                            block: 'center',
                                        });
                                    }
                                }}
                                className="cursor-pointer text-left font-medium text-amber-300 transition-colors hover:text-amber-200"
                            >
                                Ketuk Play di kartu Spotify di bawah
                            </button>
                            <button
                                type="button"
                                onClick={dismissAutoplayHint}
                                aria-label="Tutup notifikasi"
                                className="flex h-4 w-4 cursor-pointer items-center justify-center rounded-full text-slate-400 transition-colors hover:bg-white/10 hover:text-white"
                            >
                                <X className="h-3 w-3" />
                            </button>
                        </div>
                    </div>
                )}
            </div>
        </header>
    );
}
