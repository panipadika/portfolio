import React, { useState } from 'react';
import { motion, useReducedMotion } from 'framer-motion';
import {
    FileText,
    Code2,
    ArrowUpRight,
    User,
    GraduationCap,
    Briefcase,
    Award,
    FolderGit2,
    CheckCircle2,
} from 'lucide-react';

const STARS = [
    { top: '8%', left: '10%', size: 'h-1 w-1', opacity: 'opacity-40' },
    { top: '14%', left: '88%', size: 'h-1.5 w-1.5', opacity: 'opacity-60' },
    { top: '20%', left: '46%', size: 'h-0.5 w-0.5', opacity: 'opacity-30' },
    { top: '26%', left: '22%', size: 'h-1 w-1', opacity: 'opacity-50' },
    { top: '32%', left: '74%', size: 'h-1 w-1', opacity: 'opacity-40' },
    { top: '40%', left: '6%', size: 'h-1.5 w-1.5', opacity: 'opacity-50' },
    { top: '46%', left: '94%', size: 'h-1 w-1', opacity: 'opacity-35' },
    { top: '54%', left: '36%', size: 'h-0.5 w-0.5', opacity: 'opacity-40' },
    { top: '62%', left: '16%', size: 'h-1 w-1', opacity: 'opacity-60' },
    { top: '70%', left: '82%', size: 'h-1.5 w-1.5', opacity: 'opacity-40' },
    { top: '78%', left: '52%', size: 'h-1 w-1', opacity: 'opacity-50' },
    { top: '86%', left: '28%', size: 'h-0.5 w-0.5', opacity: 'opacity-30' },
    { top: '92%', left: '70%', size: 'h-1 w-1', opacity: 'opacity-50' },
    { top: '18%', left: '64%', size: 'h-1 w-1', opacity: 'opacity-45' },
    { top: '10%', left: '34%', size: 'h-1.5 w-1.5', opacity: 'opacity-35' },
];

export default function AboutSection({
    stats,
    education = [],
    experience = [],
}) {
    const [imgError, setImgError] = useState(false);
    const shouldReduceMotion = useReducedMotion();

    return (
        <section
            id="About"
            className="relative overflow-hidden border-t border-white/5 bg-[#0B0F17] py-20 lg:py-28"
            aria-label="Tentang Saya"
        >
            {/* Semantic anchor alias for Indonesian navigation */}
            <div id="TentangSaya" className="sr-only" aria-hidden="true" />

            {/* Ambient Starfield & Deep Space Ambience per DESIGN.md */}
            <div
                className="pointer-events-none absolute inset-0 -z-10"
                aria-hidden="true"
            >
                {/* Deep Space Radial Glows */}
                <div className="absolute top-1/4 left-1/4 h-96 w-96 rounded-full bg-indigo-600/10 blur-[140px]" />
                <div className="absolute top-1/3 right-1/4 h-96 w-96 rounded-full bg-indigo-600/10 blur-[140px]" />
                <div className="absolute bottom-1/4 left-1/2 -translate-x-1/2 h-80 w-80 rounded-full bg-cyan-500/10 blur-[120px]" />

                {/* Subtle Star Particles */}
                {STARS.map((star, idx) => (
                    <span
                        key={idx}
                        className={`absolute rounded-full bg-white ${star.size} ${star.opacity}`}
                        style={{ top: star.top, left: star.left }}
                    />
                ))}
            </div>

            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                {/* Section Header with Indonesian Badge & Title matching reference */}
                <div className="mx-auto mb-12 max-w-3xl text-center sm:mb-16">
                    <div className="mb-4 inline-flex items-center gap-2 rounded-full border border-indigo-500/20 bg-indigo-500/10 px-4 py-1.5 font-mono text-xs font-medium text-indigo-400 backdrop-blur-md">
                        <span className="h-1.5 w-1.5 rounded-full bg-cyan-400 motion-safe:animate-pulse" />
                        <span>Tentang Saya</span>
                    </div>
                    <h2 className="font-display text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
                        About Me
                    </h2>
                </div>

                {/* Profile Composition matching Reference Screenshot */}
                <div className="mb-20 grid grid-cols-1 items-center gap-10 sm:mb-24 lg:grid-cols-12 lg:gap-8">
                    {/* Left Column: Greeting, Name, Primary Contact Action */}
                    <div className="order-1 flex flex-col items-center text-center sm:items-start sm:text-left lg:col-span-4">
                        <span className="font-display text-3xl font-bold tracking-tight text-sky-400 sm:text-4xl md:text-5xl">
                            Hi, I'm
                        </span>
                        <h3 className="font-display mt-1 mb-6 text-3xl font-extrabold tracking-tight text-white sm:mb-8 sm:text-4xl md:text-5xl">
                            Pani Padika
                        </h3>
                        <a
                            href="#Contact"
                            className="group inline-flex items-center gap-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-cyan-500 px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-indigo-600/30 transition-all duration-200 motion-safe:hover:scale-105 motion-safe:hover:from-indigo-500 motion-safe:hover:to-cyan-400 motion-safe:active:scale-95 motion-reduce:transform-none motion-reduce:transition-none focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-400 focus-visible:ring-offset-2 focus-visible:ring-offset-[#0B0F17]"
                        >
                            <FileText className="h-4 w-4 transition-transform motion-safe:group-hover:rotate-6 motion-reduce:transform-none" />
                            <span>Hubungi Saya</span>
                        </a>
                    </div>

                    {/* Center Column: Portrait with Orbital Ambient Background */}
                    <div className="order-2 relative flex items-center justify-center py-6 lg:col-span-4 lg:py-0">
                        {/* Orbital concentric rings */}
                        <div
                            className="pointer-events-none absolute h-64 w-64 rounded-full border border-white/5 sm:h-72 sm:w-72 md:h-80 md:w-80"
                            aria-hidden="true"
                        />
                        <div
                            className="pointer-events-none absolute h-72 w-72 rounded-full border border-cyan-500/15 sm:h-80 sm:w-80 md:h-96 md:w-96"
                            aria-hidden="true"
                        />
                        {/* Accent status dot per DESIGN.md status-emerald */}
                        <div
                            className="pointer-events-none absolute top-1/2 right-2 sm:right-6 md:right-8 h-2 w-2 -translate-y-1/2 rounded-full bg-emerald-400 shadow-[0_0_10px_#34d399]"
                            aria-hidden="true"
                        />
                        {/* Radial aura behind portrait */}
                        <div
                            className="pointer-events-none absolute h-60 w-60 rounded-full bg-indigo-600/15 blur-3xl"
                            aria-hidden="true"
                        />

                        {/* Portrait Frame */}
                        <div className="relative z-10 overflow-hidden rounded-3xl border border-white/10 bg-slate-900/60 p-2 shadow-2xl backdrop-blur-md transition-all duration-300 hover:border-indigo-500/30 hover:shadow-indigo-500/10">
                            <div className="relative aspect-[3/4] w-56 sm:w-64 md:w-72 overflow-hidden rounded-2xl bg-[#0F172A]">
                                {!imgError ? (
                                    <img
                                        src="/card-front.png"
                                        alt="Foto profil Pani Padika"
                                        className="h-full w-full object-cover object-top transition-transform duration-500 motion-safe:hover:scale-105 motion-reduce:transform-none"
                                        loading="lazy"
                                        onError={() => setImgError(true)}
                                    />
                                ) : (
                                    <div className="flex h-full w-full flex-col items-center justify-center p-6 text-center">
                                        <div className="mb-3 flex h-16 w-16 items-center justify-center rounded-2xl border border-indigo-500/30 bg-indigo-500/10 text-indigo-400">
                                            <User className="h-8 w-8" />
                                        </div>
                                        <span className="font-display text-base font-semibold text-white">
                                             Pani Padika
                                        </span>
                                        <span className="mt-1 font-mono text-xs text-cyan-400">
                                            Full-Stack Developer
                                        </span>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* Right Column: Bio Paragraphs & Portfolio Action */}
                    <div className="order-3 flex flex-col items-center text-center sm:items-start sm:text-left lg:col-span-4 lg:pl-2">
                        <p className="font-sans text-sm leading-relaxed text-slate-300 sm:text-base">
                            Saya adalah{' '}
                            <strong className="font-semibold text-white">
                                Junior Developer
                            </strong>{' '}
                            yang berfokus pada pengembangan teknologi yang fungsional sekaligus menarik. Saya senang memadukan kode, desain, dan pengalaman 3D untuk membangun produk digital yang berkesan.
                        </p>
                        <p className="font-sans mt-4 mb-6 text-sm leading-relaxed text-slate-400 sm:mb-8 sm:text-base">
                            Dengan React, Three.js, dan teknologi web modern, saya mengubah ide menjadi pengalaman digital yang intuitif, responsif, dan berdampak.
                        </p>
                        <a
                            href="#Portfolio"
                            className="group inline-flex items-center gap-2.5 rounded-xl border border-indigo-500/30 bg-indigo-500/10 px-5 py-3 text-sm font-semibold text-indigo-300 shadow-sm transition-all duration-200 motion-safe:hover:scale-105 motion-safe:hover:border-indigo-400 motion-safe:hover:bg-indigo-500/20 motion-safe:hover:text-white motion-safe:active:scale-95 motion-reduce:transform-none motion-reduce:transition-none focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-400 focus-visible:ring-offset-2 focus-visible:ring-offset-[#0B0F17]"
                        >
                            <Code2 className="h-4 w-4 transition-transform motion-safe:group-hover:-translate-x-0.5 motion-reduce:transform-none" />
                            <span>Lihat Projek</span>
                            <ArrowUpRight className="h-4 w-4 transition-transform motion-safe:group-hover:translate-x-0.5 motion-safe:group-hover:-translate-y-0.5 motion-reduce:transform-none" />
                        </a>
                    </div>
                </div>

                {/* Section Sub-heading for Stats & Credentials */}
                <div className="mx-auto mb-16 max-w-3xl text-center">
                    <h3 className="font-display mb-4 text-2xl font-bold tracking-tight text-white sm:text-3xl">
                        Eksplorasi, Dedikasi & Kredibilitas
                    </h3>
                    <p className="font-sans text-sm leading-relaxed text-slate-400 sm:text-base">
                        Perjalanan saya dalam rekayasa perangkat lunak berfokus
                        pada integrasi arsitektur sistem backend berstandar
                        industri dengan pengalaman antarmuka web modern.
                    </p>
                </div>

                {/* 3 Stat Cards per DESIGN.md 4.2 */}
                <div className="mb-20 grid grid-cols-1 gap-6 md:grid-cols-3">
                    <motion.div
                        whileHover={shouldReduceMotion ? undefined : { y: -4 }}
                        className="bg-glass group relative overflow-hidden rounded-2xl border border-white/10 p-6 shadow-lg"
                    >
                        <div className="mb-4 flex h-10 w-10 items-center justify-center rounded-xl border border-indigo-500/20 bg-indigo-500/10 text-indigo-400 transition-transform motion-safe:group-hover:scale-110 motion-reduce:transform-none">
                            <FolderGit2 className="h-5 w-5" />
                        </div>
                        <div className="mb-1 font-mono text-3xl font-extrabold text-white sm:text-4xl">
                            {stats?.projects_count ?? 3}+
                        </div>
                        <h4 className="font-display mb-1 text-base font-semibold text-slate-200">
                            Projects Completed
                        </h4>
                        <p className="font-sans text-xs text-slate-400">
                            Karya aplikasi web & sistem yang telah dibangun
                        </p>
                    </motion.div>

                    <motion.div
                        whileHover={shouldReduceMotion ? undefined : { y: -4 }}
                        className="bg-glass group relative overflow-hidden rounded-2xl border border-white/10 p-6 shadow-lg"
                    >
                        <div className="mb-4 flex h-10 w-10 items-center justify-center rounded-xl border border-cyan-500/20 bg-cyan-500/10 text-cyan-400 transition-transform motion-safe:group-hover:scale-110 motion-reduce:transform-none">
                            <CheckCircle2 className="h-5 w-5" />
                        </div>
                        <div className="mb-1 font-mono text-3xl font-extrabold text-white sm:text-4xl">
                            {stats?.certificates_count ?? 3}+
                        </div>
                        <h4 className="font-display mb-1 text-base font-semibold text-slate-200">
                            Verified Certificates
                        </h4>
                        <p className="font-sans text-xs text-slate-400">
                            Keahlian teknis yang telah tervalidasi secara resmi
                        </p>
                    </motion.div>

                    <motion.div
                        whileHover={shouldReduceMotion ? undefined : { y: -4 }}
                        className="bg-glass group relative overflow-hidden rounded-2xl border border-white/10 p-6 shadow-lg"
                    >
                        <div className="mb-4 flex h-10 w-10 items-center justify-center rounded-xl border border-purple-500/20 bg-purple-500/10 text-purple-400 transition-transform motion-safe:group-hover:scale-110 motion-reduce:transform-none">
                            <Award className="h-5 w-5" />
                        </div>
                        <div className="mb-1 font-mono text-3xl font-extrabold text-white sm:text-4xl">
                            {stats?.awards_count ?? 1}+
                        </div>
                        <h4 className="font-display mb-1 text-base font-semibold text-slate-200">
                            Honors & Awards
                        </h4>
                        <p className="font-sans text-xs text-slate-400">
                            Pencapaian dan prestasi yang berhasil diraih
                        </p>
                    </motion.div>
                </div>

                {/* Timeline Grid: Education & Experience */}
                <div className="grid grid-cols-1 gap-12 lg:grid-cols-2">
                    {/* Education Column */}
                    <div className="bg-glass-card rounded-3xl border border-white/10 p-8">
                        <div className="mb-8 flex items-center gap-3">
                            <div className="flex h-10 w-10 items-center justify-center rounded-xl border border-indigo-500/20 bg-indigo-500/10 text-indigo-400">
                                <GraduationCap className="h-5 w-5" />
                            </div>
                            <div>
                                <h4 className="font-display text-xl font-bold text-white">
                                    Riwayat Pendidikan
                                </h4>
                                <p className="text-xs text-slate-400">
                                    Jejak akademik dan fondasi keilmuan
                                </p>
                            </div>
                        </div>

                        <div className="relative space-y-8 border-l border-white/10 pl-6">
                            {education && education.length > 0 ? (
                                education.map((item) => (
                                    <div
                                        key={item.id}
                                        className="group relative"
                                    >
                                        {/* Timeline Dot */}
                                        <span className="absolute top-1.5 -left-[31px] h-3.5 w-3.5 rounded-full border-2 border-indigo-500 bg-[#0B0F17] transition-transform motion-safe:group-hover:scale-125 motion-reduce:transform-none" />

                                        <div className="mb-1.5 inline-block rounded-full border border-indigo-500/20 bg-indigo-500/10 px-2.5 py-0.5 font-mono text-[11px] text-indigo-300">
                                            {item.period}
                                        </div>
                                        <h5 className="font-display text-base font-semibold text-slate-100 transition-colors group-hover:text-cyan-400">
                                            {item.institution}
                                        </h5>
                                        <p className="mt-0.5 text-xs font-medium text-slate-300">
                                             {item.role_or_major}
                                        </p>
                                        {item.description && (
                                            <p className="mt-2 text-xs leading-relaxed text-slate-400">
                                                {item.description}
                                            </p>
                                        )}
                                    </div>
                                ))
                            ) : (
                                <div className="text-xs text-slate-500 italic">
                                    Data pendidikan sedang dimuat...
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Experience Column */}
                    <div className="bg-glass-card rounded-3xl border border-white/10 p-8">
                        <div className="mb-8 flex items-center gap-3">
                            <div className="flex h-10 w-10 items-center justify-center rounded-xl border border-cyan-500/20 bg-cyan-500/10 text-cyan-400">
                                <Briefcase className="h-5 w-5" />
                            </div>
                            <div>
                                <h4 className="font-display text-xl font-bold text-white">
                                    Pengalaman Kerja & Organisasi
                                </h4>
                                <p className="text-xs text-slate-400">
                                    Praktik industri dan kontribusi profesional
                                </p>
                            </div>
                        </div>

                        <div className="relative space-y-8 border-l border-white/10 pl-6">
                            {experience && experience.length > 0 ? (
                                experience.map((item) => (
                                    <div
                                        key={item.id}
                                        className="group relative"
                                    >
                                        {/* Timeline Dot */}
                                        <span className="absolute top-1.5 -left-[31px] h-3.5 w-3.5 rounded-full border-2 border-cyan-400 bg-[#0B0F17] transition-transform motion-safe:group-hover:scale-125 motion-reduce:transform-none" />

                                        <div className="mb-1.5 inline-block rounded-full border border-cyan-500/20 bg-cyan-500/10 px-2.5 py-0.5 font-mono text-[11px] text-cyan-300">
                                            {item.period}
                                        </div>
                                        <h5 className="font-display text-base font-semibold text-slate-100 transition-colors group-hover:text-indigo-400">
                                            {item.institution}
                                        </h5>
                                        <p className="mt-0.5 text-xs font-medium text-slate-300">
                                            {item.role_or_major}
                                        </p>
                                        {item.description && (
                                            <p className="mt-2 text-xs leading-relaxed text-slate-400">
                                                {item.description}
                                            </p>
                                        )}
                                    </div>
                                ))
                            ) : (
                                <div className="text-xs text-slate-500 italic">
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
