import React, { useState, useEffect } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { Volume2, VolumeX } from 'lucide-react';
import { useBackgroundAudio } from '../../hooks/useBackgroundAudio';

const NAV_LINKS = [
    { id: 'Home', label: 'Home', href: '#Home' },
    { id: 'About', label: 'About', href: '#About' },
    { id: 'Portfolio', label: 'Portfolio', href: '#Portfolio' },
    { id: 'Contact', label: 'Contact', href: '#Contact' },
];

export default function Navbar({ isVisible = true }) {
    const [activeSection, setActiveSection] = useState('Home');
    const { isPlaying, toggleAudio } = useBackgroundAudio();

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

    const handleNavClick = (e, id, href) => {
        e.preventDefault();
        setActiveSection(id);
        const targetId = href.replace('#', '');
        const targetEl = document.getElementById(targetId);
        if (targetEl) {
            targetEl.scrollIntoView({ behavior: 'smooth' });
        }
    };

    return (
        <AnimatePresence>
            {isVisible && (
                <motion.header
                    initial={{ opacity: 0, y: -24 }}
                    animate={{ opacity: 1, y: 0 }}
                    exit={{ opacity: 0, y: -24 }}
                    transition={{ duration: 0.6, ease: [0.16, 1, 0.3, 1] }}
                    className="pointer-events-none fixed top-5 right-0 left-0 z-50 flex justify-center px-4"
                >
                    <nav
                        className="pointer-events-auto relative flex items-center gap-1 rounded-full border border-white/[0.15] bg-white/[0.03] p-1.5 shadow-[0_8px_32px_rgba(0,0,0,0.35)] ring-1 ring-white/5 backdrop-blur-[6px] transition-colors duration-300 sm:gap-1.5 sm:p-2"
                        aria-label="Main Navigation"
                    >
                        {/* Prismatic glass edge glint & top sheen per reference */}
                        <div
                            className="pointer-events-none absolute inset-x-8 top-0 h-px bg-gradient-to-r from-transparent via-white/25 via-[#7185e8]/25 via-emerald-400/20 via-rose-500/25 to-transparent"
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
                                    className={`relative rounded-full px-4 py-1.5 text-xs transition-all duration-300 sm:px-5 sm:py-2 sm:text-sm ${
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
                            className="mx-1 h-4 w-px bg-white/15 sm:mx-1.5"
                            aria-hidden="true"
                        />

                        {/* Ambient / Spotify Audio Toggle Button (Circular glass bubble per reference) */}
                        <button
                            type="button"
                            onClick={toggleAudio}
                            className="flex h-8 w-8 cursor-pointer items-center justify-center rounded-full border border-white/15 bg-white/[0.05] text-slate-300 shadow-sm transition-all duration-200 hover:bg-white/15 hover:text-white active:scale-95 sm:h-9 sm:w-9"
                            aria-label={
                                isPlaying
                                    ? 'Matikan musik Spotify'
                                    : 'Putar playlist Spotify'
                            }
                            title={
                                isPlaying
                                    ? 'Jeda musik Spotify'
                                    : 'Putar playlist Spotify'
                            }
                        >
                            {isPlaying ? (
                                <Volume2 className="h-4 w-4 animate-pulse text-[#7185e8]" />
                            ) : (
                                <VolumeX className="h-4 w-4 text-slate-300" />
                            )}
                        </button>
                    </nav>
                </motion.header>
            )}
        </AnimatePresence>
    );
}
