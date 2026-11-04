import React, { useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import {
    ExternalLink,
    Github,
    Award,
    CheckCircle,
    Code2,
    Layers,
    Info,
} from 'lucide-react';
import ProjectModal from '../ui/ProjectModal';

const TABS = [
    { id: 'projects', label: 'Projects', icon: Layers },
    { id: 'certificates', label: 'Certificates', icon: CheckCircle },
    { id: 'achievements', label: 'Achievements', icon: Award },
    { id: 'tech_stack', label: 'Tech Stack', icon: Code2 },
];

export default function PortfolioSection({
    projects = [],
    certificates = [],
    achievements = [],
    techStack = [],
}) {
    const [activeTab, setActiveTab] = useState('projects');
    const [selectedProject, setSelectedProject] = useState(null);

    return (
        <section
            id="Portfolio"
            className="relative border-t border-white/5 bg-[#0B0F17] py-20 lg:py-28"
        >
            <div id="Portofolio" className="sr-only" aria-hidden="true" />
            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                {/* Section Heading */}
                <div className="mx-auto mb-12 max-w-3xl text-center">
                    <div className="mb-3 inline-flex items-center gap-2 rounded-full border border-cyan-500/20 bg-cyan-500/10 px-3 py-1 font-mono text-xs font-medium text-cyan-400">
                        <span>Showcase Portofolio</span>
                    </div>
                    <h2 className="font-display mb-4 text-3xl font-bold tracking-tight text-white sm:text-4xl">
                        Karya, Sertifikasi & Kemampuan
                    </h2>
                    <p className="font-sans text-sm leading-relaxed text-slate-400 sm:text-base">
                        Jelajahi karya nyata, bukti kompetensi formal, dan
                        tumpukan teknologi yang saya kuasai secara mendalam.
                    </p>
                </div>

                {/* Dynamic Tab Bar with Framer Motion layoutId */}
                <div className="mb-14 flex justify-center">
                    <div className="inline-flex max-w-full overflow-x-auto rounded-full border border-white/10 bg-white/5 p-1.5 backdrop-blur-xl">
                        {TABS.map((tab) => {
                            const Icon = tab.icon;
                            const isActive = activeTab === tab.id;
                            return (
                                <button
                                    key={tab.id}
                                    onClick={() => setActiveTab(tab.id)}
                                    className={`relative flex cursor-pointer items-center gap-2 rounded-full px-4 py-2.5 text-xs font-medium whitespace-nowrap transition-colors sm:px-6 sm:text-sm ${
                                        isActive
                                            ? 'text-white'
                                            : 'text-slate-400 hover:text-slate-200'
                                    }`}
                                >
                                    <Icon className="h-4 w-4 shrink-0" />
                                    <span>{tab.label}</span>
                                    {isActive && (
                                        <motion.div
                                            layoutId="activeTab"
                                            className="absolute inset-0 -z-10 rounded-full border border-indigo-500/40 bg-gradient-to-r from-indigo-500/30 to-cyan-500/25 shadow-md"
                                            transition={{
                                                type: 'spring',
                                                stiffness: 450,
                                                damping: 35,
                                            }}
                                        />
                                    )}
                                </button>
                            );
                        })}
                    </div>
                </div>

                {/* Tab Content Panels */}
                <AnimatePresence mode="wait">
                    {/* 1. Projects Tab */}
                    {activeTab === 'projects' && (
                        <motion.div
                            key="projects-panel"
                            initial={{ opacity: 0, y: 15 }}
                            animate={{ opacity: 1, y: 0 }}
                            exit={{ opacity: 0, y: -15 }}
                            transition={{ duration: 0.3 }}
                            className="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
                        >
                            {projects && projects.length > 0 ? (
                                projects.map((project) => (
                                    <div
                                        key={project.id}
                                        className="bg-glass group flex flex-col overflow-hidden rounded-3xl border border-white/10 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:border-indigo-500/40"
                                    >
                                        {/* Thumbnail */}
                                        <div className="relative h-48 overflow-hidden border-b border-white/5 bg-slate-900 sm:h-52">
                                            <img
                                                src={
                                                    project.image_url ||
                                                    '/assets/images/projects/sample.webp'
                                                }
                                                alt={project.title}
                                                className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                                onError={(e) => {
                                                    e.currentTarget.src =
                                                        'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=800&auto=format&fit=crop&q=80';
                                                }}
                                            />
                                            <div className="absolute inset-0 bg-gradient-to-t from-[#0B0F17] via-transparent to-transparent opacity-60" />
                                        </div>

                                        {/* Card Body */}
                                        <div className="flex flex-1 flex-col justify-between p-6">
                                            <div>
                                                <h3 className="font-display mb-2 text-lg font-bold text-white transition-colors group-hover:text-cyan-400">
                                                    {project.title}
                                                </h3>
                                                <p className="mb-4 line-clamp-2 text-xs leading-relaxed text-slate-400 sm:text-sm">
                                                    {project.description}
                                                </p>

                                                {/* Tech tags */}
                                                {project.tech_tags &&
                                                    project.tech_tags.length >
                                                        0 && (
                                                        <div className="mb-6 flex flex-wrap gap-1.5">
                                                            {project.tech_tags
                                                                .slice(0, 3)
                                                                .map(
                                                                    (
                                                                        tag,
                                                                        idx,
                                                                    ) => (
                                                                        <span
                                                                            key={
                                                                                idx
                                                                            }
                                                                            className="rounded-full border border-white/10 bg-white/5 px-2.5 py-0.5 font-mono text-[11px] text-slate-300"
                                                                        >
                                                                            {
                                                                                tag
                                                                            }
                                                                        </span>
                                                                    ),
                                                                )}
                                                            {project.tech_tags
                                                                .length > 3 && (
                                                                <span className="rounded-full bg-white/5 px-2 py-0.5 font-mono text-[10px] text-slate-400">
                                                                    +
                                                                    {project
                                                                        .tech_tags
                                                                        .length -
                                                                        3}
                                                                </span>
                                                            )}
                                                        </div>
                                                    )}
                                            </div>

                                            {/* Card Footer Actions */}
                                            <div className="flex items-center gap-2 border-t border-white/5 pt-4">
                                                <button
                                                    onClick={() =>
                                                        setSelectedProject(
                                                            project,
                                                        )
                                                    }
                                                    className="inline-flex flex-1 cursor-pointer items-center justify-center gap-1.5 rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-xs font-medium text-slate-200 transition-colors hover:bg-white/10"
                                                >
                                                    <Info className="h-3.5 w-3.5 text-indigo-400" />
                                                    <span>Details</span>
                                                </button>

                                                {project.demo_url && (
                                                    <a
                                                        href={project.demo_url}
                                                        target="_blank"
                                                        rel="noreferrer"
                                                        className="rounded-xl border border-indigo-500/30 bg-indigo-600/20 p-2 text-indigo-300 transition-colors hover:bg-indigo-600/30"
                                                        aria-label="Live Demo"
                                                    >
                                                        <ExternalLink className="h-3.5 w-3.5" />
                                                    </a>
                                                )}

                                                {project.repo_url && (
                                                    <a
                                                        href={project.repo_url}
                                                        target="_blank"
                                                        rel="noreferrer"
                                                        className="rounded-xl border border-white/10 bg-white/5 p-2 text-slate-300 transition-colors hover:bg-white/10"
                                                        aria-label="Repository"
                                                    >
                                                        <Github className="h-3.5 w-3.5" />
                                                    </a>
                                                )}
                                            </div>
                                        </div>
                                    </div>
                                ))
                            ) : (
                                <div className="col-span-full py-12 text-center font-mono text-sm text-slate-500">
                                    Belum ada proyek yang ditampilkan.
                                </div>
                            )}
                        </motion.div>
                    )}

                    {/* 2. Certificates Tab */}
                    {activeTab === 'certificates' && (
                        <motion.div
                            key="certificates-panel"
                            initial={{ opacity: 0, y: 15 }}
                            animate={{ opacity: 1, y: 0 }}
                            exit={{ opacity: 0, y: -15 }}
                            transition={{ duration: 0.3 }}
                            className="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
                        >
                            {certificates && certificates.length > 0 ? (
                                certificates.map((cert) => (
                                    <div
                                        key={cert.id}
                                        className="bg-glass group flex flex-col justify-between rounded-3xl border border-white/10 p-6 shadow-lg transition-all duration-300 hover:border-cyan-500/40"
                                    >
                                        <div>
                                            <div className="mb-4 flex items-center justify-between">
                                                <span className="rounded-full border border-cyan-500/20 bg-cyan-500/10 px-2.5 py-0.5 font-mono text-[11px] text-cyan-300">
                                                    {cert.year || 'Certified'}
                                                </span>
                                                <div className="flex h-8 w-8 items-center justify-center rounded-lg bg-white/5 text-cyan-400">
                                                    <CheckCircle className="h-4 w-4" />
                                                </div>
                                            </div>

                                            <h3 className="font-display mb-1 text-base font-bold text-white transition-colors group-hover:text-cyan-400">
                                                {cert.title}
                                            </h3>
                                            <p className="mb-4 font-sans text-xs text-slate-400">
                                                Penerbit:{' '}
                                                <span className="font-medium text-slate-200">
                                                    {cert.issuer_or_level}
                                                </span>
                                            </p>
                                        </div>

                                        {cert.credential_url && (
                                            <a
                                                href={cert.credential_url}
                                                target="_blank"
                                                rel="noreferrer"
                                                className="inline-flex w-full cursor-pointer items-center justify-center gap-1.5 rounded-xl border border-white/10 bg-white/5 py-2 text-xs font-medium text-slate-300 transition-colors hover:bg-white/10 hover:text-white"
                                            >
                                                <span>
                                                    Lihat Bukti Kredensial
                                                </span>
                                                <ExternalLink className="h-3.5 w-3.5" />
                                            </a>
                                        )}
                                    </div>
                                ))
                            ) : (
                                <div className="col-span-full py-12 text-center font-mono text-sm text-slate-500">
                                    Belum ada sertifikat yang ditampilkan.
                                </div>
                            )}
                        </motion.div>
                    )}

                    {/* 3. Achievements Tab */}
                    {activeTab === 'achievements' && (
                        <motion.div
                            key="achievements-panel"
                            initial={{ opacity: 0, y: 15 }}
                            animate={{ opacity: 1, y: 0 }}
                            exit={{ opacity: 0, y: -15 }}
                            transition={{ duration: 0.3 }}
                            className="grid grid-cols-1 gap-6 md:grid-cols-2"
                        >
                            {achievements && achievements.length > 0 ? (
                                achievements.map((item) => (
                                    <div
                                        key={item.id}
                                        className="bg-glass group flex items-start gap-4 rounded-3xl border border-white/10 p-6 shadow-lg transition-all duration-300 hover:border-purple-500/40"
                                    >
                                        <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-purple-500/20 bg-purple-500/10 text-purple-400 transition-transform group-hover:scale-110">
                                            <Award className="h-6 w-6" />
                                        </div>
                                        <div>
                                            <div className="mb-1 flex items-center gap-2">
                                                <span className="font-mono text-xs text-purple-400">
                                                    {item.year}
                                                </span>
                                                <span className="text-slate-600">
                                                    •
                                                </span>
                                                <span className="text-xs text-slate-400">
                                                    {item.issuer_or_level}
                                                </span>
                                            </div>
                                            <h3 className="font-display text-lg font-bold text-white transition-colors group-hover:text-purple-300">
                                                {item.title}
                                            </h3>
                                            {item.credential_url && (
                                                <a
                                                    href={item.credential_url}
                                                    target="_blank"
                                                    rel="noreferrer"
                                                    className="mt-2 inline-flex items-center gap-1 font-mono text-xs text-indigo-400 hover:text-indigo-300"
                                                >
                                                    <span>Kredensial</span>
                                                    <ExternalLink className="h-3 w-3" />
                                                </a>
                                            )}
                                        </div>
                                    </div>
                                ))
                            ) : (
                                <div className="col-span-full py-12 text-center font-mono text-sm text-slate-500">
                                    Belum ada penghargaan yang ditampilkan.
                                </div>
                            )}
                        </motion.div>
                    )}

                    {/* 4. Tech Stack Tab */}
                    {activeTab === 'tech_stack' && (
                        <motion.div
                            key="tech-stack-panel"
                            initial={{ opacity: 0, y: 15 }}
                            animate={{ opacity: 1, y: 0 }}
                            exit={{ opacity: 0, y: -15 }}
                            transition={{ duration: 0.3 }}
                            className="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6"
                        >
                            {techStack && techStack.length > 0 ? (
                                techStack.map((tech) => (
                                    <div
                                        key={tech.id}
                                        className="bg-glass group flex flex-col items-center justify-center rounded-2xl border border-white/10 p-4 text-center transition-all hover:border-indigo-500/40 hover:bg-white/5"
                                    >
                                        <div className="mb-2.5 flex h-10 w-10 items-center justify-center rounded-xl border border-white/10 bg-white/5 font-mono text-sm font-bold text-slate-200 transition-all group-hover:scale-110 group-hover:text-cyan-400">
                                            {tech.title
                                                .substring(0, 2)
                                                .toUpperCase()}
                                        </div>
                                        <span className="font-display text-xs font-semibold text-white transition-colors group-hover:text-cyan-300">
                                            {tech.title}
                                        </span>
                                        <span className="mt-0.5 font-mono text-[10px] text-slate-400">
                                            {tech.issuer_or_level ||
                                                'Full-Stack'}
                                        </span>
                                    </div>
                                ))
                            ) : (
                                <div className="col-span-full py-12 text-center font-mono text-sm text-slate-500">
                                    Data tech stack sedang dimuat...
                                </div>
                            )}
                        </motion.div>
                    )}
                </AnimatePresence>

                {/* Project Detail Modal */}
                <ProjectModal
                    project={selectedProject}
                    isOpen={!!selectedProject}
                    onClose={() => setSelectedProject(null)}
                />
            </div>
        </section>
    );
}
