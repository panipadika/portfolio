import React, { useState, useRef, useEffect, useCallback } from 'react';
import { motion, useReducedMotion } from 'framer-motion';
import {
    FileText,
    Code2,
    ArrowUpRight,
    User,
    GraduationCap,
    Briefcase,
} from 'lucide-react';
import BorderGlow from '../ui/BorderGlow';

export default function AboutSection({
    stats,
    education = [],
    experience = [],
}) {
    const [imgError, setImgError] = useState(false);
    const [isHovered, setIsHovered] = useState(false);
    const shouldReduceMotion = useReducedMotion();
    const photoRef = useRef(null);
    const rafRef = useRef(null);

    const updateBrushPosition = useCallback((clientX, clientY) => {
        if (!photoRef.current) return;
        const rect = photoRef.current.getBoundingClientRect();
        const x = clientX - rect.left;
        const y = clientY - rect.top;

        if (rafRef.current) {
            cancelAnimationFrame(rafRef.current);
        }

        rafRef.current = requestAnimationFrame(() => {
            if (photoRef.current) {
                photoRef.current.style.setProperty('--x', `${x}px`);
                photoRef.current.style.setProperty('--y', `${y}px`);
            }
        });
    }, []);

    const handlePointerMove = (e) => {
        updateBrushPosition(e.clientX, e.clientY);
        if (!isHovered) setIsHovered(true);
    };

    const handlePointerDown = (e) => {
        updateBrushPosition(e.clientX, e.clientY);
        setIsHovered(true);
    };

    const handlePointerLeave = () => {
        if (rafRef.current) {
            cancelAnimationFrame(rafRef.current);
        }
        setIsHovered(false);
    };

    useEffect(() => {
        return () => {
            if (rafRef.current) {
                cancelAnimationFrame(rafRef.current);
            }
        };
    }, []);

    return (
        <section
            id="About"
            className="relative overflow-hidden border-t border-white/5 bg-transparent py-20 lg:py-28"
            aria-label="About"
        >
            {/* Semantic anchor alias for Indonesian navigation */}
            <div id="TentangSaya" className="sr-only" aria-hidden="true" />

            {/* Ambient Deep Space Ambience per DESIGN.md */}
            <div
                className="pointer-events-none absolute inset-0 -z-10"
                aria-hidden="true"
            >
                {/* Deep Space Radial Glows */}
                <div className="absolute top-1/4 left-1/4 h-96 w-96 rounded-full bg-indigo-600/10 blur-[140px]" />
                <div className="absolute top-1/3 right-1/4 h-96 w-96 rounded-full bg-indigo-600/10 blur-[140px]" />
                <div className="absolute bottom-1/4 left-1/2 h-80 w-80 -translate-x-1/2 rounded-full bg-[#4e63d9]/10 blur-[120px]" />
            </div>

            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                {/* Section Header with Indonesian Badge & Title matching reference */}
                <div className="mx-auto mb-12 max-w-3xl text-center sm:mb-16">
                    <h2 className="font-display text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
                        Tentang Saya
                    </h2>
                </div>

                {/* Profile Composition matching Reference Screenshot */}
                <div className="mb-20 grid grid-cols-1 items-center gap-10 sm:mb-24 lg:grid-cols-12 lg:gap-8">
                    {/* Left Column: Greeting, Name, Primary Contact Action */}
                    <div className="order-1 flex flex-col items-center text-center sm:items-start sm:text-left lg:col-span-4">
                        <span className="font-display text-3xl font-bold tracking-tight text-[#7185e8] sm:text-4xl md:text-5xl">
                            Hi, I'm
                        </span>
                        <h3 className="font-display mt-1 mb-6 text-3xl font-extrabold tracking-tight text-white sm:mb-50 sm:text-4xl md:text-5xl">
                            Pani Padika
                        </h3>
                    </div>

                    {/* Center Column: Floating Cutout Portrait with Interactive Spider-Man Alter-Ego */}
                    <div className="relative order-2 flex flex-col items-center justify-center py-6 lg:col-span-4 lg:py-0">
                        {/* Orbital concentric rings */}
                        <div
                            className="pointer-events-none absolute h-64 w-64 rounded-full border border-white/5 sm:h-72 sm:w-72 md:h-80 md:w-80"
                            aria-hidden="true"
                        />
                        <div
                            className="pointer-events-none absolute h-72 w-72 rounded-full border border-[#4e63d9]/20 sm:h-80 sm:w-80 md:h-96 md:w-96"
                            aria-hidden="true"
                        />
                        {/* Accent status dot per DESIGN.md status-emerald */}
                        <div
                            className={`pointer-events-none absolute top-1/2 right-2 h-2.5 w-2.5 -translate-y-1/2 rounded-full transition-all duration-500 sm:right-6 md:right-8 ${
                                isHovered
                                    ? 'bg-rose-500 shadow-[0_0_12px_#f43f5e]'
                                    : 'bg-emerald-400 shadow-[0_0_10px_#34d399]'
                            }`}
                            aria-hidden="true"
                        />
                        {/* Dynamic Soft Cosmic Aura / Spider-Man Red Glow */}
                        <div
                            className={`pointer-events-none absolute h-72 w-72 rounded-full blur-[80px] transition-all duration-700 ${
                                isHovered
                                    ? 'bg-gradient-to-t from-rose-600/30 via-red-500/20 to-blue-500/15'
                                    : 'bg-gradient-to-t from-[#262f6b]/45 via-[#4e63d9]/30 to-indigo-500/15'
                            }`}
                            aria-hidden="true"
                        />
                        <div
                            className={`pointer-events-none absolute -bottom-4 h-48 w-48 rounded-full blur-[60px] transition-all duration-700 ${
                                isHovered ? 'bg-rose-500/25' : 'bg-[#4e63d9]/20'
                            }`}
                            aria-hidden="true"
                        />

                        {/* Floating Cutout Subject with Cursor Brush Spotlight Reveal */}
                        <motion.div
                            animate={shouldReduceMotion ? undefined : { y: [0, -8, 0] }}
                            transition={{ duration: 4.5, repeat: Infinity, ease: 'easeInOut' }}
                            className="relative z-10 flex items-center justify-center select-none"
                        >
                            {!imgError ? (
                                <div
                                    ref={photoRef}
                                    style={{
                                        WebkitMaskImage:
                                            'linear-gradient(to bottom, black 35%, rgba(0, 0, 0, 0.85) 60%, rgba(0, 0, 0, 0.3) 82%, transparent 100%)',
                                        maskImage:
                                            'linear-gradient(to bottom, black 35%, rgba(0, 0, 0, 0.85) 60%, rgba(0, 0, 0, 0.3) 82%, transparent 100%)',
                                    }}
                                    className="relative h-auto w-64 sm:w-72 md:w-80 max-h-[500px] touch-none cursor-crosshair"
                                    onPointerMove={handlePointerMove}
                                    onPointerDown={handlePointerDown}
                                    onPointerUp={handlePointerLeave}
                                    onPointerCancel={handlePointerLeave}
                                    onPointerLeave={handlePointerLeave}
                                >
                                    {/* Layer 1: Base Real Photo (Always Visible) */}
                                    <img
                                        src="/assets/images/profile-transparent.png"
                                        alt="Foto profil Pani Padika"
                                        className={`h-full w-full object-contain pointer-events-none transition-all duration-300 ${
                                            isHovered
                                                ? 'drop-shadow-[0_8px_20px_rgba(239,68,68,0.2)]'
                                                : 'drop-shadow-[0_8px_20px_rgba(78,99,217,0.2)]'
                                        }`}
                                        loading="lazy"
                                        onError={() => setImgError(true)}
                                    />

                                    {/* Layer 2: Transformed Spider-Man Photo (Revealed via Spotlight Mask) */}
                                    <img
                                        src="/assets/images/spiderman-transformed.png"
                                        alt="Spider-Man Alter-Ego Reveal"
                                        className={`pointer-events-none absolute inset-0 h-full w-full object-contain transition-opacity duration-200 ease-out ${
                                            isHovered ? 'opacity-100' : 'opacity-0'
                                        }`}
                                        style={{
                                            WebkitMaskImage:
                                                'radial-gradient(circle 115px at var(--x, -999px) var(--y, -999px), black 0%, black 45px, rgba(0, 0, 0, 0.5) 80px, transparent 115px)',
                                            maskImage:
                                                'radial-gradient(circle 115px at var(--x, -999px) var(--y, -999px), black 0%, black 45px, rgba(0, 0, 0, 0.5) 80px, transparent 115px)',
                                        }}
                                        loading="lazy"
                                    />
                                </div>
                            ) : (
                                <div className="flex h-72 w-64 flex-col items-center justify-center p-6 text-center rounded-2xl border border-white/10 bg-slate-900/60 backdrop-blur-md">
                                    <div className="mb-3 flex h-16 w-16 items-center justify-center rounded-2xl border border-indigo-500/30 bg-indigo-500/10 text-indigo-400">
                                        <User className="h-8 w-8" />
                                    </div>
                                    <span className="font-display text-base font-semibold text-white">
                                        Pani Padika
                                    </span>
                                    <span className="mt-1 font-mono text-xs text-[#7185e8]">
                                        Full-Stack Developer
                                    </span>
                                </div>
                            )}
                        </motion.div>
                    </div>

                    {/* Right Column: Bio Paragraphs & Portfolio Action */}
                    <div className="order-3 flex flex-col items-center text-center sm:items-start sm:text-left lg:col-span-4 lg:pl-2">
                        <p className="font-sans text-sm leading-relaxed text-slate-300 sm:text-base">
                            Saya adalah Mahasiswa{' '}
                            <strong className="font-semibold text-white">
                                Sistem Informasi
                            </strong>{' '}
                           yang memiliki ketertarikan dalam dunia teknologi dan pengembangan web. 
                           Saya senang merancang dan membangun aplikasi web yang tidak hanya menarik secara visual, 
                           tetapi juga fungsional, responsif, dan mudah digunakan.
                        </p>
                        <p className="mt-4 font-sans text-sm leading-relaxed text-slate-400 sm:text-base">
                           Bagi saya, setiap proyek merupakan kesempatan untuk mengasah kemampuan, 
                           mengeksplorasi teknologi baru, dan menciptakan solusi digital yang bermanfaat.
                        </p>
                    </div>
                </div>

                {/* Timeline Grid: Education & Experience matching Reference */}
                <div className="grid grid-cols-1 gap-10 lg:grid-cols-2 lg:gap-12">
                    {/* Education Column */}
                    <div>
                        <h3 className="font-display mb-6 text-2xl font-bold tracking-tight text-white sm:text-3xl">
                            Education
                        </h3>

                        <div className="space-y-4">
                            {education && education.length > 0 ? (
                                education.map((item) => {
                                    const instKey = (item.institution || '').toLowerCase();
                                    const logoUrl = (instKey.includes('bsi') || instKey.includes('bina sarana informatika'))
                                        ? '/assets/images/institutions/logo-ubsi.png'
                                        : (instKey.includes('smkn 1 rajadesa') || instKey.includes('smkn1 rajadesa') || instKey.includes('smk negeri 1 rajadesa'))
                                        ? '/assets/images/institutions/logo-smkn1rajadesa.png'
                                        : (instKey.includes('smpn 1 rajadesa') || instKey.includes('smpn1 rajadesa') || instKey.includes('smp negeri 1 rajadesa'))
                                        ? '/assets/images/institutions/logo-smpn1rajadesa.png'
                                        : instKey.includes('texmaco')
                                        ? '/assets/images/institutions/logo-texmaco.png'
                                        : instKey.includes('muhammadiyah')
                                        ? '/assets/images/institutions/logo-muhammadiyah.png'
                                        : null;

                                    return (
                                        <BorderGlow
                                            key={item.id}
                                            edgeSensitivity={30}
                                            glowColor="40 80 80"
                                            backgroundColor="#0d1322"
                                            borderRadius={20}
                                            glowRadius={35}
                                            glowIntensity={1.0}
                                            coneSpread={25}
                                            animated={false}
                                            colors={['#c084fc', '#f472b6', '#38bdf8']}
                                            className="group transition-all duration-300 hover:-translate-y-1 hover:shadow-xl cursor-default"
                                        >
                                            <div className="flex w-full items-center gap-4 p-4.5 sm:p-5">
                                                {/* Institution Logo */}
                                                {logoUrl ? (
                                                    <img
                                                        src={logoUrl}
                                                        alt={`Logo ${item.institution}`}
                                                        className="h-12 w-12 sm:h-14 sm:w-14 shrink-0 object-contain drop-shadow transition-transform duration-300 motion-safe:group-hover:scale-105"
                                                        loading="lazy"
                                                    />
                                                ) : (
                                                    <div className="flex h-12 w-12 sm:h-14 sm:w-14 shrink-0 items-center justify-center">
                                                        <GraduationCap className="h-7 w-7 text-indigo-400" />
                                                    </div>
                                                )}

                                                {/* Details */}
                                                <div className="min-w-0 flex-1">
                                                    <h4 className="font-display truncate text-base font-bold text-white transition-colors group-hover:text-cyan-300 sm:text-lg">
                                                        {item.institution}
                                                    </h4>
                                                    <p className="mt-0.5 truncate text-xs font-medium text-slate-300 sm:text-sm">
                                                        {item.role_or_major}
                                                    </p>
                                                    <div className="mt-1 font-mono text-xs font-semibold text-cyan-400">
                                                        {item.period}
                                                    </div>
                                                </div>
                                            </div>
                                        </BorderGlow>
                                    );
                                })
                            ) : (
                                <div className="rounded-2xl border border-white/5 bg-white/[0.02] p-6 text-center text-xs text-slate-500 italic">
                                    Data pendidikan sedang dimuat...
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Experience Column */}
                    <div>
                        <h3 className="font-display mb-6 text-2xl font-bold tracking-tight text-white sm:text-3xl">
                            Experience
                        </h3>

                        <div className="space-y-4">
                            {experience && experience.length > 0 ? (
                                experience.map((item) => {
                                    const instKey = (item.institution || '').toLowerCase();
                                    const logoUrl = instKey.includes('bappelitbangda')
                                        ? '/assets/images/institutions/logo-bappelitbangda.png'
                                        : (instKey.includes('ascom') || instKey.includes('artha sarana'))
                                        ? '/assets/images/institutions/logo-ascom.png'
                                        : null;

                                    return (
                                        <BorderGlow
                                            key={item.id}
                                            edgeSensitivity={30}
                                            glowColor="40 80 80"
                                            backgroundColor="#0d1322"
                                            borderRadius={20}
                                            glowRadius={35}
                                            glowIntensity={1.0}
                                            coneSpread={25}
                                            animated={false}
                                            colors={['#c084fc', '#f472b6', '#38bdf8']}
                                            className="group transition-all duration-300 hover:-translate-y-1 hover:shadow-xl cursor-default"
                                        >
                                            <div className="flex w-full items-center gap-4 p-4.5 sm:p-5">
                                                {/* Institution Logo */}
                                                {logoUrl ? (
                                                    <img
                                                        src={logoUrl}
                                                        alt={`Logo ${item.institution}`}
                                                        className="h-12 w-12 sm:h-14 sm:w-14 shrink-0 object-contain drop-shadow transition-transform duration-300 motion-safe:group-hover:scale-105"
                                                        loading="lazy"
                                                    />
                                                ) : (
                                                    <div className="flex h-12 w-12 sm:h-14 sm:w-14 shrink-0 items-center justify-center">
                                                        <Briefcase className="h-7 w-7 text-[#7185e8]" />
                                                    </div>
                                                )}

                                                {/* Details */}
                                                <div className="min-w-0 flex-1">
                                                    <h4 className="font-display truncate text-base font-bold text-white transition-colors group-hover:text-cyan-300 sm:text-lg">
                                                        {item.institution}
                                                    </h4>
                                                    <p className="mt-0.5 truncate text-xs font-medium text-slate-300 sm:text-sm">
                                                        {item.role_or_major}
                                                    </p>
                                                    <div className="mt-1 font-mono text-xs font-semibold text-cyan-400">
                                                        {item.period}
                                                    </div>
                                                </div>
                                            </div>
                                        </BorderGlow>
                                    );
                                })
                            ) : (
                                <div className="rounded-2xl border border-white/5 bg-white/[0.02] p-6 text-center text-xs text-slate-500 italic">
                                    Data pengalaman sedang dimuat...
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}
