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
            className="relative min-h-screen bg-[#050811] text-white flex flex-col justify-center px-4 sm:px-6 lg:px-12 py-12 lg:py-20 overflow-hidden"
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

            <div className="relative z-10 mx-auto w-full max-w-7xl">
                {/* Top Bar: Back Button & Breadcrumbs */}
                <div className="mb-10 sm:mb-14 flex flex-wrap items-center gap-4">
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
                </div>

                {/* Two-Column Detail Layout Matching Reference */}
                <div className="grid grid-cols-1 items-center gap-10 lg:grid-cols-12 lg:gap-14">
                    {/* Left Column: Title, Full Description, Technologies Used Badges */}
                    <div className="flex flex-col items-start justify-center lg:col-span-6">
                        <h1 className="font-display text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white leading-[1.1]">
                            {project.title}
                        </h1>

                        <p className="mt-6 max-w-xl font-sans text-sm sm:text-base lg:text-lg leading-relaxed text-slate-300">
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

                        {/* Technologies Used Section */}
                        <div className="mt-10 w-full">
                            <h2 className="mb-5 flex items-center gap-2.5 font-display text-lg sm:text-xl font-bold text-white">
                                <Code2 className="h-5 w-5 text-blue-400" />
                                <span>Technologies Used</span>
                            </h2>

                            <div className="flex flex-wrap gap-2.5 sm:gap-3">
                                {project.tech_tags && project.tech_tags.length > 0 ? (
                                    project.tech_tags.map((tag) => (
                                        <TechBadge key={tag} name={tag} techStack={techStack} />
                                    ))
                                ) : (
                                    <span className="text-xs text-slate-500">
                                        Teknologi standar web
                                    </span>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* Right Column: Full Photo Showcase to Blue Border */}
                    <div className="w-full lg:col-span-6">
                        <div className="relative mx-auto w-full max-w-3xl overflow-hidden rounded-[28px] sm:rounded-[36px] md:rounded-[40px] border border-blue-500/30 bg-[#070b14] shadow-[0_0_60px_rgba(37,99,235,0.2)]">
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
                                        className="w-full h-auto block object-cover object-top transition-transform duration-500 group-hover:scale-[1.02]"
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
                                        className="w-full h-auto block object-cover object-top transition-transform duration-500 hover:scale-[1.02]"
                                        onError={(e) => {
                                            e.currentTarget.src =
                                                project.image_url ||
                                                '/assets/images/projects/cyber-gyzn.png';
                                        }}
                                    />
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </motion.div>
    );
}

