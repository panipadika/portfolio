import React, { useState, useEffect } from 'react';
import { Volume2, VolumeX } from 'lucide-react';
import { useBackgroundAudio } from '../../hooks/useBackgroundAudio';

const NAV_LINKS = [
    { name: 'Home', href: '#Home' },
    { name: 'Tentang Saya', href: '#About' },
    { name: 'Portfolio', href: '#Portfolio' },
    { name: 'Contact', href: '#Contact' },
];

export default function Navbar() {
    const [activeSection, setActiveSection] = useState('Home');
    const { isPlaying, toggleAudio } = useBackgroundAudio();

    useEffect(() => {
        const handleScroll = () => {
            for (const link of NAV_LINKS.slice().reverse()) {
                const targetId = link.href.replace('#', '');
                const el = document.getElementById(targetId);
                if (el) {
                    const rect = el.getBoundingClientRect();
                    if (rect.top <= 240) {
                        setActiveSection(link.name);
                        break;
                    }
                }
            }
        };

        window.addEventListener('scroll', handleScroll, { passive: true });
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    const handleNavClick = (e, name, href) => {
        e.preventDefault();
        setActiveSection(name);
        const targetId = href.replace('#', '');
        const targetEl = document.getElementById(targetId);
        if (targetEl) {
            targetEl.scrollIntoView({ behavior: 'smooth' });
        }
    };

    return (
        <header className="pointer-events-none fixed top-5 right-0 left-0 z-50 flex justify-center px-4">
            <nav
                className="pointer-events-auto relative flex items-center gap-1 rounded-full border border-white/10 bg-[#0B0F17]/80 px-2 py-1.5 shadow-[0_12px_40px_rgba(0,0,0,0.65)] ring-1 ring-white/5 backdrop-blur-2xl sm:gap-2 sm:px-2.5"
                aria-label="Main Navigation"
            >
                {/* Subtle top edge sheen / chromatic aberration accent */}
                <div
                    className="pointer-events-none absolute inset-x-6 top-0 h-px bg-gradient-to-r from-transparent via-cyan-400/40 via-purple-500/30 to-transparent"
                    aria-hidden="true"
                />

                {/* Nav Links */}
                {NAV_LINKS.map((link) => {
                    const isActive = activeSection === link.name;
                    return (
                        <a
                            key={link.name}
                            href={link.href}
                            onClick={(e) =>
                                handleNavClick(e, link.name, link.href)
                            }
                            className={`relative rounded-full px-3.5 py-1.5 text-xs font-medium transition-all duration-300 sm:px-4 sm:py-2 sm:text-sm ${
                                isActive
                                    ? 'border border-white/10 bg-white/10 font-bold text-white shadow-inner'
                                    : 'text-slate-300 hover:bg-white/5 hover:text-white'
                            }`}
                        >
                            {link.name}
                        </a>
                    );
                })}

                {/* Vertical Divider */}
                <div
                    className="mx-0.5 h-4 w-px bg-white/20 sm:mx-1"
                    aria-hidden="true"
                />

                {/* Ambient Lo-fi Audio Toggle Button */}
                <button
                    type="button"
                    onClick={toggleAudio}
                    className="flex h-8 w-8 items-center justify-center rounded-full text-slate-300 transition-colors hover:bg-white/10 hover:text-white sm:h-9 sm:w-9"
                    aria-label={
                        isPlaying
                            ? 'Matikan musik latar ambient'
                            : 'Putar musik latar ambient'
                    }
                    title={
                        isPlaying
                            ? 'Matikan musik santai'
                            : 'Putar musik latar fokus santai'
                    }
                >
                    {isPlaying ? (
                        <Volume2 className="h-4 w-4 animate-pulse text-cyan-400" />
                    ) : (
                        <VolumeX className="h-4 w-4 text-slate-400" />
                    )}
                </button>
            </nav>
        </header>
    );
}
