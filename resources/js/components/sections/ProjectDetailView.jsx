import React, { useEffect } from 'react';
import { motion } from 'framer-motion';
import { ArrowLeft, ChevronRight, Code2, ExternalLink, Github, Globe } from 'lucide-react';
import TechBadge from '../ui/TechBadge';

export default function ProjectDetailView({ project, techStack = [], onBack }) {
    useEffect(() => {
        window.scrollTo({ top: 0, behavior: 'instant' });
    }, [project]);

    if (!project) return null;

    return (
        <motion.div
            key={project.id || project.slug}
            initial={{ opacity: 0, y: 15 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: -15 }}
            transition={{ duration: 0.35, ease: [0.16, 1, 0.3, 1] }}
            className="relative min-h-screen bg-[#050811] text-white flex flex-col justify-start px-4 sm:px-6 lg:px-12 pt-8 pb-20 sm:pt-10 sm:pb-24 overflow-hidden"
            aria-label={`Project Detail: ${project.title}`}
        >
            {/* Ambient Background Glow (Sleek dark gradient, no Galaxy 3D particles) */}
            <div
                className="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_80%_80%_at_50%_-20%,rgba(37,99,235,0.15),rgba(5,8,17,0))]"
                aria-hidden="true"
            />
            <div
                className="pointer-events-none absolute -bottom-40 -left-40 h-96 w-96 rounded-full bg-blue-600/10 blur-[120px]"
                aria-hidden="true"
            />
            <div
                className="pointer-events-none absolute -top-40 -right-40 h-96 w-96 rounded-full bg-indigo-600/10 blur-[120px]"
                aria-hidden="true"
            />

            <div className="relative z-10 mx-auto w-full max-w-5xl">
                {/* Top Bar: Back Button & Breadcrumbs */}
                <motion.div
                    initial={{ opacity: 0, y: -10 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.4, ease: [0.16, 1, 0.3, 1] }}
                    className="mb-6 sm:mb-8 flex flex-wrap items-center gap-4"
                >
                    <button
                        type="button"
                        onClick={onBack}
                        className="group inline-flex cursor-pointer items-center gap-2 rounded-xl border border-white/15 bg-white/[0.06] px-4 py-2.5 text-xs sm:text-sm font-semibold text-slate-200 shadow-md backdrop-blur-md transition-all duration-300 hover:border-white/30 hover:bg-white/15 hover:text-white active:scale-95"
                        aria-label="Kembali ke halaman project"
                    >
                        <ArrowLeft className="h-4 w-4 transition-transform duration-300 group-hover:-translate-x-1" />
                        <span>Back</span>
                    </button>

                    <div className="flex items-center gap-2 font-sans text-xs sm:text-sm font-medium text-slate-400">
                        <button
                            type="button"
                            onClick={onBack}
                            className="inline-flex cursor-pointer items-center font-semibold text-blue-400 hover:text-blue-300 transition-colors hover:underline focus:outline-none"
                            aria-label="Kembali ke halaman project"
                        >
                            Projects
                        </button>
                        <ChevronRight className="h-3.5 w-3.5 text-slate-600" />
                        <span className="font-semibold text-white">
                            {project.title}
                        </span>
                    </div>
                </motion.div>

                {/* Hero Showcase Photo (Centered at top) */}
                <motion.div
                    initial={{ opacity: 0, scale: 0.97, y: 15 }}
                    animate={{ opacity: 1, scale: 1, y: 0 }}
                    transition={{ duration: 0.5, delay: 0.1, ease: [0.16, 1, 0.3, 1] }}
                    className="w-full mb-10 sm:mb-14"
                >
                    <div className="relative mx-auto w-full overflow-hidden rounded-[24px] sm:rounded-[32px] md:rounded-[36px] border border-blue-500/30 bg-[#070b14] shadow-[0_0_50px_rgba(37,99,235,0.22)]">
                        {project.demo_url ? (
                            <a
                                href={project.demo_url}
                                target="_blank"
                                rel="noreferrer"
                                className="group relative block w-full cursor-pointer overflow-hidden"
                                title="Buka Live Demo"
                            >
                                <img
                                    src={
                                        project.slug === 'cyber-gyzn'
                                            ? '/assets/images/projects/cyber-gyzn-showcase.png'
                                            : project.image_url ||
                                              '/assets/images/projects/cyber-gyzn.png'
                                    }
                                    alt={project.title}
                                    className="w-full h-auto block object-cover object-top transition-transform duration-500 group-hover:scale-[1.01]"
                                    onError={(e) => {
                                        e.currentTarget.src =
                                            project.image_url ||
                                            '/assets/images/projects/cyber-gyzn.png';
                                    }}
                                />
                            </a>
                        ) : (
                            <div className="relative block w-full overflow-hidden">
                                <img
                                    src={
                                        project.slug === 'cyber-gyzn'
                                            ? '/assets/images/projects/cyber-gyzn-showcase.png'
                                            : project.image_url ||
                                              '/assets/images/projects/cyber-gyzn.png'
                                    }
                                    alt={project.title}
                                    className="w-full h-auto block object-cover object-top transition-transform duration-500 hover:scale-[1.01]"
                                    onError={(e) => {
                                        e.currentTarget.src =
                                            project.image_url ||
                                            '/assets/images/projects/cyber-gyzn.png';
                                    }}
                                />
                            </div>
                        )}
                    </div>
                </motion.div>

                {/* Two-Column Detail Layout Below Photo */}
                <motion.div
                    initial={{ opacity: 0, y: 20 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.55, delay: 0.2, ease: [0.16, 1, 0.3, 1] }}
                    className="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start"
                >
                    {/* Left Column: Title, Full Description, Action Buttons */}
                    <div className="flex flex-col items-start lg:col-span-7 xl:col-span-8">
                        <h1 className="font-display text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-white leading-[1.15]">
                            {project.title}
                        </h1>

                        <p className="mt-5 font-sans text-sm sm:text-base leading-relaxed text-slate-300">
                            {project.description}
                        </p>

                        {/* Action Links: Live Demo & Repository */}
                        {(project.demo_url || project.repo_url) && (
                            <div className="mt-8 flex flex-wrap items-center gap-3.5">
                                {project.demo_url && (
                                    <a
                                        href={project.demo_url}
                                        target="_blank"
                                        rel="noreferrer"
                                        className="group inline-flex cursor-pointer items-center gap-2.5 rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-cyan-500 px-6 py-3 text-xs sm:text-sm font-semibold text-white shadow-[0_0_25px_rgba(59,130,246,0.35)] transition-all duration-300 hover:scale-[1.03] hover:shadow-[0_0_35px_rgba(59,130,246,0.6)] active:scale-95"
                                    >
                                        <Globe className="h-4 w-4 transition-transform duration-300 group-hover:rotate-12" />
                                        <span>Live Demo URL</span>
                                        <ExternalLink className="h-3.5 w-3.5 opacity-80" />
                                    </a>
                                )}

                                {project.repo_url && (
                                    <a
                                        href={project.repo_url}
                                        target="_blank"
                                        rel="noreferrer"
                                        className="group inline-flex cursor-pointer items-center gap-2.5 rounded-2xl border border-white/15 bg-white/[0.06] px-5 py-3 text-xs sm:text-sm font-semibold text-slate-200 backdrop-blur-md transition-all duration-300 hover:border-white/30 hover:bg-white/10 hover:text-white active:scale-95"
                                    >
                                        <Github className="h-4 w-4 transition-transform duration-300 group-hover:scale-110" />
                                        <span>Source Code</span>
                                    </a>
                                )}
                            </div>
                        )}
                    </div>

                    {/* Right Column: Technologies Used in a Glassmorphic Panel */}
                    <div className="w-full lg:col-span-5 xl:col-span-4">
                        <div className="rounded-2xl border border-white/10 bg-white/[0.03] p-5 sm:p-6 backdrop-blur-md shadow-xl">
                            <div className="mb-4 flex items-center justify-between border-b border-white/5 pb-3">
                                <h2 className="flex items-center gap-2.5 font-display text-base sm:text-lg font-bold text-white">
                                    <Code2 className="h-5 w-5 text-blue-400" />
                                    <span>Technologies Used</span>
                                </h2>
                                {project.tech_tags && project.tech_tags.length > 0 && (
                                    <span className="rounded-full bg-blue-500/10 px-2.5 py-0.5 font-mono text-xs font-semibold text-blue-400 border border-blue-500/20">
                                        {project.tech_tags.length}
                                    </span>
                                )}
                            </div>

                            <div className="grid grid-cols-2 gap-2 sm:gap-2.5">
                                {project.tech_tags && project.tech_tags.length > 0 ? (
                                    project.tech_tags.map((tag, idx) => {
                                        const isLastOdd =
                                            project.tech_tags.length % 2 !== 0 &&
                                            idx === project.tech_tags.length - 1;
                                        return (
                                            <div
                                                key={tag}
                                                className={isLastOdd ? 'col-span-2' : 'col-span-1'}
                                            >
                                                <TechBadge
                                                    name={tag}
                                                    techStack={techStack}
                                                    fullWidth
                                                />
                                            </div>
                                        );
                                    })
                                ) : (
                                    <span className="col-span-2 py-2 text-xs text-slate-500">
                                        Teknologi standar web
                                    </span>
                                )}
                            </div>
                        </div>
                    </div>
                </motion.div>
            </div>
        </motion.div>
    );
}
