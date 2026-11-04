import React from 'react';
import { motion } from 'framer-motion';
import {
    GraduationCap,
    Briefcase,
    Award,
    FolderGit2,
    CheckCircle2,
} from 'lucide-react';

export default function AboutSection({
    stats,
    education = [],
    experience = [],
}) {
    return (
        <section
            id="About"
            className="relative border-t border-white/5 bg-[#0B0F17] py-20 lg:py-28"
        >
            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                {/* Section Header */}
                <div className="mx-auto mb-16 max-w-3xl text-center">
                    <div className="mb-3 inline-flex items-center gap-2 rounded-full border border-indigo-500/20 bg-indigo-500/10 px-3 py-1 font-mono text-xs font-medium text-indigo-400">
                        <span>Tentang Saya</span>
                    </div>
                    <h2 className="font-display mb-4 text-3xl font-bold tracking-tight text-white sm:text-4xl">
                        Eksplorasi, Dedikasi & Kredibilitas
                    </h2>
                    <p className="font-sans text-sm leading-relaxed text-slate-400 sm:text-base">
                        Perjalanan saya dalam rekayasa perangkat lunak berfokus
                        pada integrasi arsitektur sistem backend berstandar
                        industri dengan pengalaman antarmuka web modern.
                    </p>
                </div>

                {/* 3 Stat Cards per DESIGN.md 4.2 */}
                <div className="mb-20 grid grid-cols-1 gap-6 md:grid-cols-3">
                    <motion.div
                        whileHover={{ y: -4 }}
                        className="bg-glass group relative overflow-hidden rounded-2xl border border-white/10 p-6 shadow-lg"
                    >
                        <div className="mb-4 flex h-10 w-10 items-center justify-center rounded-xl border border-indigo-500/20 bg-indigo-500/10 text-indigo-400 transition-transform group-hover:scale-110">
                            <FolderGit2 className="h-5 w-5" />
                        </div>
                        <div className="mb-1 font-mono text-3xl font-extrabold text-white sm:text-4xl">
                            {stats?.projects_count ?? 3}+
                        </div>
                        <h3 className="font-display mb-1 text-base font-semibold text-slate-200">
                            Projects Completed
                        </h3>
                        <p className="font-sans text-xs text-slate-400">
                            Karya aplikasi web & sistem yang telah dibangun
                        </p>
                    </motion.div>

                    <motion.div
                        whileHover={{ y: -4 }}
                        className="bg-glass group relative overflow-hidden rounded-2xl border border-white/10 p-6 shadow-lg"
                    >
                        <div className="mb-4 flex h-10 w-10 items-center justify-center rounded-xl border border-cyan-500/20 bg-cyan-500/10 text-cyan-400 transition-transform group-hover:scale-110">
                            <CheckCircle2 className="h-5 w-5" />
                        </div>
                        <div className="mb-1 font-mono text-3xl font-extrabold text-white sm:text-4xl">
                            {stats?.certificates_count ?? 3}+
                        </div>
                        <h3 className="font-display mb-1 text-base font-semibold text-slate-200">
                            Verified Certificates
                        </h3>
                        <p className="font-sans text-xs text-slate-400">
                            Keahlian teknis yang telah tervalidasi secara resmi
                        </p>
                    </motion.div>

                    <motion.div
                        whileHover={{ y: -4 }}
                        className="bg-glass group relative overflow-hidden rounded-2xl border border-white/10 p-6 shadow-lg"
                    >
                        <div className="mb-4 flex h-10 w-10 items-center justify-center rounded-xl border border-purple-500/20 bg-purple-500/10 text-purple-400 transition-transform group-hover:scale-110">
                            <Award className="h-5 w-5" />
                        </div>
                        <div className="mb-1 font-mono text-3xl font-extrabold text-white sm:text-4xl">
                            {stats?.awards_count ?? 1}+
                        </div>
                        <h3 className="font-display mb-1 text-base font-semibold text-slate-200">
                            Honors & Awards
                        </h3>
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
                                <h3 className="font-display text-xl font-bold text-white">
                                    Riwayat Pendidikan
                                </h3>
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
                                        <span className="absolute top-1.5 -left-[31px] h-3.5 w-3.5 rounded-full border-2 border-indigo-500 bg-[#0B0F17] transition-transform group-hover:scale-125" />

                                        <div className="mb-1.5 inline-block rounded-full border border-indigo-500/20 bg-indigo-500/10 px-2.5 py-0.5 font-mono text-[11px] text-indigo-300">
                                            {item.period}
                                        </div>
                                        <h4 className="font-display text-base font-semibold text-slate-100 transition-colors group-hover:text-cyan-400">
                                            {item.institution}
                                        </h4>
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
                                <h3 className="font-display text-xl font-bold text-white">
                                    Pengalaman Kerja & Organisasi
                                </h3>
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
                                        <span className="absolute top-1.5 -left-[31px] h-3.5 w-3.5 rounded-full border-2 border-cyan-400 bg-[#0B0F17] transition-transform group-hover:scale-125" />

                                        <div className="mb-1.5 inline-block rounded-full border border-cyan-500/20 bg-cyan-500/10 px-2.5 py-0.5 font-mono text-[11px] text-cyan-300">
                                            {item.period}
                                        </div>
                                        <h4 className="font-display text-base font-semibold text-slate-100 transition-colors group-hover:text-indigo-400">
                                            {item.institution}
                                        </h4>
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
