import React, { useState, useEffect } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import {
    Code2,
    Award,
    Trophy,
    Cpu,
    ArrowRight,
    ExternalLink,
    X,
} from 'lucide-react';
import StarBorder from '../ui/StarBorder';
import BorderGlow from '../ui/BorderGlow';
import TechIcon from '../ui/TechIcon';
import { optimizeImageSrc } from '../../lib/media';

const TABS = [
    { id: 'projects', label: 'Projects', icon: Code2 },
    { id: 'certificates', label: 'Certificates', icon: Award },
    { id: 'achievements', label: 'Achievements', icon: Trophy },
    { id: 'tech_stack', label: 'Tech Stack', icon: Cpu },
];

const panelContainerVariants = {
    hidden: { opacity: 0 },
    show: {
        opacity: 1,
        transition: {
            staggerChildren: 0.07,
            delayChildren: 0.05,
        },
    },
};

const itemCardVariants = {
    hidden: { opacity: 0, y: 22 },
    show: {
        opacity: 1,
        y: 0,
        transition: { duration: 0.45, ease: [0.16, 1, 0.3, 1] },
    },
};

export default function PortfolioSection({
    projects = [],
    certificates = [],
    achievements = [],
    techStack = [],
    onSelectProject,
}) {
    const [activeTab, setActiveTab] = useState('projects');
    const [previewItem, setPreviewItem] = useState(null);

    useEffect(() => {
        const handleKeyDown = (e) => {
            if (e.key === 'Escape') {
                setPreviewItem(null);
            }
        };

        if (previewItem) {
            document.body.style.overflow = 'hidden';
            window.addEventListener('keydown', handleKeyDown);
        } else {
            document.body.style.overflow = '';
        }

        return () => {
            document.body.style.overflow = '';
            window.removeEventListener('keydown', handleKeyDown);
        };
    }, [previewItem]);

    const handleSelectProject = (project) => {
        if (onSelectProject) {
            onSelectProject(project);
        }
    };

    return (
        <section
            id="Portfolio"
            className="relative border-t border-white/5 bg-[#070b14]/50 py-20 lg:py-28"
            aria-label="Portfolio Showcase"
        >
            <div id="Portofolio" className="sr-only" aria-hidden="true" />
            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                {/* Section Heading */}
                <motion.div
                    initial={{ opacity: 0, y: 25 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    viewport={{ once: true, amount: 0.3 }}
                    transition={{ duration: 0.6, ease: [0.16, 1, 0.3, 1] }}
                    className="mx-auto mb-12 max-w-3xl text-center sm:mb-16"
                >
                    <h2 className="font-display text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
                        Portofolio
                    </h2>
                </motion.div>

                {/* Tabbed Catalog Grid View */}
                <div className="w-full">
                    {/* Top Dynamic Tab Navigation */}
                    <motion.div
                        initial={{ opacity: 0, y: 20 }}
                        whileInView={{ opacity: 1, y: 0 }}
                        viewport={{ once: true, amount: 0.3 }}
                        transition={{
                            duration: 0.5,
                            delay: 0.1,
                            ease: [0.16, 1, 0.3, 1],
                        }}
                        className="relative mb-10 w-full sm:mb-14"
                    >
                        {/* Horizontal scroll container with smooth touch scrolling and hidden scrollbar */}
                        <div className="flex w-full [scrollbar-width:none] justify-start overflow-x-auto scroll-smooth px-4 py-2 [-ms-overflow-style:none] sm:justify-center [&::-webkit-scrollbar]:hidden">
                            <div className="inline-flex shrink-0 items-center gap-1 rounded-2xl border border-white/10 bg-[#0d1322]/90 p-1.5 shadow-2xl backdrop-blur-xl sm:gap-1.5">
                                {TABS.map((tab) => {
                                    const Icon = tab.icon;
                                    const isActive = activeTab === tab.id;
                                    return (
                                        <button
                                            key={tab.id}
                                            type="button"
                                            onClick={() => setActiveTab(tab.id)}
                                            className={`relative flex shrink-0 cursor-pointer items-center gap-2 rounded-xl px-3.5 py-2 text-xs font-semibold whitespace-nowrap transition-all duration-300 sm:px-6 sm:py-2.5 sm:text-sm ${
                                                isActive
                                                    ? 'border border-[#7185e8]/40 bg-gradient-to-r from-[#262f6b] via-indigo-600 to-[#4e63d9] text-white shadow-[0_0_20px_rgba(78,99,217,0.35)]'
                                                    : 'text-slate-400 hover:bg-white/[0.04] hover:text-white'
                                            }`}
                                        >
                                            <Icon className="h-4 w-4 shrink-0" />
                                            <span>{tab.label}</span>
                                        </button>
                                    );
                                })}
                            </div>
                        </div>

                        {/* Subtle swipe affordance indicator on mobile */}
                        <div className="mt-2 flex items-center justify-center gap-1.5 font-mono text-[11px] text-slate-500 sm:hidden">
                            <span>←</span>
                            <span>geser tab kategori</span>
                            <span>→</span>
                        </div>
                    </motion.div>

                    {/* Tab Panels */}
                    <AnimatePresence mode="wait">
                        {/* 1. Projects Tab (StarBorder Cards) */}
                        {activeTab === 'projects' && (
                            <motion.div
                                key="projects-panel"
                                variants={panelContainerVariants}
                                initial="hidden"
                                animate="show"
                                exit={{
                                    opacity: 0,
                                    y: -15,
                                    transition: { duration: 0.2 },
                                }}
                                className="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
                            >
                                {projects && projects.length > 0 ? (
                                    projects.map((project) => (
                                        <motion.div
                                            key={project.id || project.slug}
                                            data-project-card={
                                                project.slug || project.id
                                            }
                                            variants={itemCardVariants}
                                        >
                                            <StarBorder
                                                as="div"
                                                color="#60a5fa"
                                                trailColor="#38bdf8"
                                                duration={4}
                                                direction="clockwise"
                                                stars={1}
                                                trailLength={0.3}
                                                thickness={1.2}
                                                radius={24}
                                                glow={0.7}
                                                hover="lap"
                                                sparkle={false}
                                                clickPulse={true}
                                                theme="dark"
                                                backgroundColor="#0d1322"
                                                borderColor="rgba(255, 255, 255, 0.1)"
                                                className="group flex cursor-default flex-col justify-between rounded-3xl border border-white/10 bg-[#0d1322] p-5 shadow-2xl backdrop-blur-xl transition-transform duration-300 hover:-translate-y-1.5 sm:p-6"
                                            >
                                                {/* 1. Gambar (Thumbnail) */}
                                                <div
                                                    onClick={() =>
                                                        handleSelectProject(
                                                            project,
                                                        )
                                                    }
                                                    className="relative mb-5 flex aspect-[16/9] w-full cursor-pointer items-center justify-center overflow-hidden rounded-2xl border border-white/10 bg-[#080d1a]"
                                                    title="Klik untuk melihat detail proyek"
                                                >
                                                    <img
                                                        src={optimizeImageSrc(
                                                            project.image_url ||
                                                                '/assets/images/projects/cyber-gyzn.png',
                                                            640,
                                                        )}
                                                        alt={project.title}
                                                        width={640}
                                                        height={360}
                                                        loading="lazy"
                                                        decoding="async"
                                                        className="h-full w-full rounded-xl object-contain transition-transform duration-500 group-hover:scale-105 sm:rounded-2xl"
                                                        onError={(e) => {
                                                            const raw =
                                                                project.image_url ||
                                                                '/assets/images/projects/cyber-gyzn.png';
                                                            if (
                                                                e.currentTarget.src.includes(
                                                                    '/media-opt/',
                                                                )
                                                            ) {
                                                                e.currentTarget.src =
                                                                    raw;
                                                                return;
                                                            }
                                                            e.currentTarget.src =
                                                                'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=800&auto=format&fit=crop&q=80';
                                                        }}
                                                    />
                                                </div>

                                                {/* 2. Nama (Title) & 3. Ringkasan (Summary) */}
                                                <div className="flex flex-1 flex-col justify-between">
                                                    <div>
                                                        <h3 className="font-display mb-2.5 text-xl font-extrabold tracking-tight text-white transition-colors group-hover:text-blue-400 sm:text-2xl">
                                                            {project.title}
                                                        </h3>
                                                        <p className="mb-6 line-clamp-3 font-sans text-xs leading-relaxed text-slate-400 sm:text-sm">
                                                            {
                                                                project.description
                                                            }
                                                        </p>
                                                    </div>

                                                    {/* 3. Detail Button Only */}
                                                    <div>
                                                        <button
                                                            type="button"
                                                            onClick={() =>
                                                                handleSelectProject(
                                                                    project,
                                                                )
                                                            }
                                                            className="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-white/10 bg-white/[0.05] px-4 py-2 text-xs font-medium text-slate-200 transition-all duration-300 hover:border-blue-500/40 hover:bg-blue-600/20 hover:text-white active:scale-95 sm:text-sm"
                                                        >
                                                            <span>Details</span>
                                                            <ArrowRight className="h-3.5 w-3.5 transition-transform duration-300 group-hover:translate-x-1" />
                                                        </button>
                                                    </div>
                                                </div>
                                            </StarBorder>
                                        </motion.div>
                                    ))
                                ) : (
                                    <div className="col-span-full py-16 text-center font-mono text-sm text-slate-500">
                                        Belum ada proyek yang ditampilkan.
                                    </div>
                                )}
                            </motion.div>
                        )}

                        {/* 2. Certificates Tab (Matching Reference 1 & 2) */}
                        {activeTab === 'certificates' && (
                            <motion.div
                                key="certificates-panel"
                                variants={panelContainerVariants}
                                initial="hidden"
                                animate="show"
                                exit={{
                                    opacity: 0,
                                    y: -15,
                                    transition: { duration: 0.2 },
                                }}
                                className="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
                            >
                                {certificates && certificates.length > 0 ? (
                                    certificates.map((cert) => (
                                        <motion.div
                                            key={cert.id}
                                            variants={itemCardVariants}
                                            onClick={() => setPreviewItem(cert)}
                                            className="group relative cursor-pointer overflow-hidden rounded-2xl border border-white/10 bg-[#0d1322]/80 shadow-2xl backdrop-blur-xl transition-all duration-300 hover:-translate-y-1.5 hover:border-blue-500/50 hover:shadow-[0_0_30px_rgba(37,99,235,0.25)] sm:rounded-3xl"
                                        >
                                            {/* Certificate Aspect Ratio Container */}
                                            <div className="relative aspect-[1.414/1] w-full overflow-hidden bg-black/40">
                                                <img
                                                    src={optimizeImageSrc(
                                                        cert.image_or_icon ||
                                                            '/assets/images/certificates/sertifikat-stekom.png',
                                                        640,
                                                    )}
                                                    alt={cert.title}
                                                    width={640}
                                                    height={452}
                                                    loading="lazy"
                                                    decoding="async"
                                                    className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                                    onError={(e) => {
                                                        const raw =
                                                            cert.image_or_icon ||
                                                            '/assets/images/certificates/sertifikat-stekom.png';
                                                        if (
                                                            e.currentTarget.src.includes(
                                                                '/media-opt/',
                                                            )
                                                        ) {
                                                            e.currentTarget.src =
                                                                raw;
                                                            return;
                                                        }
                                                        e.currentTarget.src =
                                                            '/assets/images/certificates/sertifikat-stekom.png';
                                                    }}
                                                />

                                                {/* Hover Overlay: Dark backdrop + centered "Lihat sertifikat ↗" (Matching Reference 2) */}
                                                <div className="absolute inset-0 flex items-center justify-center bg-black/55 opacity-0 backdrop-blur-[1px] transition-opacity duration-300 group-hover:opacity-100">
                                                    <div className="flex items-center gap-2 drop-shadow-[0_2px_8px_rgba(0,0,0,0.8)]">
                                                        <span className="font-sans text-base font-bold tracking-wide text-white sm:text-lg">
                                                            Lihat sertifikat
                                                        </span>
                                                        <ExternalLink className="h-5 w-5 shrink-0 text-white" />
                                                    </div>
                                                </div>
                                            </div>
                                        </motion.div>
                                    ))
                                ) : (
                                    <div className="col-span-full py-16 text-center font-mono text-sm text-slate-500">
                                        Belum ada sertifikat yang ditampilkan.
                                    </div>
                                )}
                            </motion.div>
                        )}

                        {/* 3. Achievements Tab (Matching Piagam Layout & Referensi) */}
                        {activeTab === 'achievements' && (
                            <motion.div
                                key="achievements-panel"
                                variants={panelContainerVariants}
                                initial="hidden"
                                animate="show"
                                exit={{
                                    opacity: 0,
                                    y: -15,
                                    transition: { duration: 0.2 },
                                }}
                                className="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
                            >
                                {achievements && achievements.length > 0 ? (
                                    achievements.map((item) => (
                                        <motion.div
                                            key={item.id}
                                            variants={itemCardVariants}
                                            onClick={() => setPreviewItem(item)}
                                            className="group relative cursor-pointer overflow-hidden rounded-2xl border border-white/10 bg-[#0d1322]/80 shadow-2xl backdrop-blur-xl transition-all duration-300 hover:-translate-y-1.5 hover:border-blue-500/50 hover:shadow-[0_0_30px_rgba(37,99,235,0.25)] sm:rounded-3xl"
                                        >
                                            {/* Piagam Aspect Ratio Container */}
                                            <div className="relative aspect-[1.414/1] w-full overflow-hidden bg-black/40">
                                                <img
                                                    src={optimizeImageSrc(
                                                        item.image_or_icon ||
                                                            '/assets/images/achievements/piagam-ksm-2023.png',
                                                        640,
                                                    )}
                                                    alt={item.title}
                                                    width={640}
                                                    height={452}
                                                    loading="lazy"
                                                    decoding="async"
                                                    className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                                    onError={(e) => {
                                                        const raw =
                                                            item.image_or_icon ||
                                                            '/assets/images/achievements/piagam-ksm-2023.png';
                                                        if (
                                                            e.currentTarget.src.includes(
                                                                '/media-opt/',
                                                            )
                                                        ) {
                                                            e.currentTarget.src =
                                                                raw;
                                                            return;
                                                        }
                                                        e.currentTarget.src =
                                                            '/assets/images/achievements/piagam-ksm-2023.png';
                                                    }}
                                                />

                                                {/* Hover Overlay: Dark backdrop + centered "Lihat piagam ↗" */}
                                                <div className="absolute inset-0 flex items-center justify-center bg-black/55 opacity-0 backdrop-blur-[1px] transition-opacity duration-300 group-hover:opacity-100">
                                                    <div className="flex items-center gap-2 drop-shadow-[0_2px_8px_rgba(0,0,0,0.8)]">
                                                        <span className="font-sans text-base font-bold tracking-wide text-white sm:text-lg">
                                                            Lihat piagam
                                                        </span>
                                                        <ExternalLink className="h-5 w-5 shrink-0 text-white" />
                                                    </div>
                                                </div>
                                            </div>
                                        </motion.div>
                                    ))
                                ) : (
                                    <div className="col-span-full py-16 text-center font-mono text-sm text-slate-500">
                                        Belum ada piagam/penghargaan yang
                                        ditampilkan.
                                    </div>
                                )}
                            </motion.div>
                        )}

                        {/* 4. Tech Stack Tab (React Bits Border Glow + Authentic Iconify Brand Logos) */}
                        {activeTab === 'tech_stack' && (
                            <motion.div
                                key="tech-stack-panel"
                                variants={panelContainerVariants}
                                initial="hidden"
                                animate="show"
                                exit={{
                                    opacity: 0,
                                    y: -15,
                                    transition: { duration: 0.2 },
                                }}
                                className="grid grid-cols-2 gap-4 sm:grid-cols-3 sm:gap-5 md:grid-cols-4 lg:grid-cols-6"
                            >
                                {techStack && techStack.length > 0 ? (
                                    techStack.map((tech) => (
                                        <motion.div
                                            key={tech.id || tech.title}
                                            variants={itemCardVariants}
                                        >
                                            <BorderGlow
                                                edgeSensitivity={30}
                                                glowColor="40 80 80"
                                                backgroundColor="#0d1322"
                                                borderRadius={24}
                                                glowRadius={40}
                                                glowIntensity={1.0}
                                                coneSpread={25}
                                                animated={false}
                                                colors={[
                                                    '#c084fc',
                                                    '#f472b6',
                                                    '#38bdf8',
                                                ]}
                                                className="group flex min-h-[145px] cursor-default flex-col items-center justify-center p-5 shadow-xl transition-all duration-300 hover:-translate-y-1.5 sm:min-h-[160px] sm:p-6"
                                            >
                                                <div className="flex h-full w-full flex-col items-center justify-center text-center">
                                                    {/* Brand Logo with authentic color */}
                                                    <div className="mb-3.5 flex h-11 w-11 items-center justify-center transition-transform duration-300 group-hover:scale-110 sm:h-12 sm:w-12">
                                                        <TechIcon
                                                            name={tech.title}
                                                            icon={
                                                                tech.image_or_icon
                                                            }
                                                            className="h-10 w-10 shrink-0 drop-shadow-md sm:h-11 sm:w-11"
                                                        />
                                                    </div>

                                                    {/* Tech Name */}
                                                    <span className="font-sans text-xs font-semibold tracking-wide text-white transition-colors duration-300 group-hover:text-amber-200 sm:text-sm">
                                                        {tech.title}
                                                    </span>
                                                </div>
                                            </BorderGlow>
                                        </motion.div>
                                    ))
                                ) : (
                                    <div className="col-span-full py-16 text-center font-mono text-sm text-slate-500">
                                        Data tech stack sedang dimuat...
                                    </div>
                                )}
                            </motion.div>
                        )}
                    </AnimatePresence>
                </div>
            </div>

            {/* Fullscreen Lightbox Modal (For Certificates & Achievements) */}
            <AnimatePresence>
                {previewItem && (
                    <motion.div
                        key="lightbox-backdrop"
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        exit={{ opacity: 0 }}
                        transition={{ duration: 0.25 }}
                        onClick={() => setPreviewItem(null)}
                        className="fixed inset-0 z-50 flex cursor-zoom-out items-center justify-center bg-black/85 p-2 backdrop-blur-md sm:p-4 md:p-6"
                    >
                        {/* Close Button Top Right (Prominent, High-Z-index, Glossy Glass) */}
                        <button
                            type="button"
                            onClick={() => setPreviewItem(null)}
                            className="absolute top-4 right-4 z-50 flex h-11 w-11 cursor-pointer items-center justify-center rounded-full border border-white/20 bg-black/60 text-white/90 shadow-2xl backdrop-blur-md transition-all duration-200 hover:scale-110 hover:bg-white/20 hover:text-white active:scale-95 sm:top-6 sm:right-6"
                            aria-label="Tutup pratinjau"
                        >
                            <X className="h-6 w-6" />
                        </button>

                        {/* Modal Image Box - Almost Full Screen (Hampir Full) */}
                        <motion.div
                            initial={{ opacity: 0, scale: 0.95, y: 10 }}
                            animate={{ opacity: 1, scale: 1, y: 0 }}
                            exit={{ opacity: 0, scale: 0.95, y: 10 }}
                            transition={{
                                duration: 0.25,
                                ease: [0.16, 1, 0.3, 1],
                            }}
                            onClick={(e) => e.stopPropagation()}
                            className="relative flex max-h-[92vh] max-w-[96vw] cursor-default items-center justify-center overflow-hidden rounded-2xl border border-white/20 bg-black/95 shadow-[0_0_80px_rgba(0,0,0,0.9)] sm:rounded-3xl"
                        >
                            <img
                                src={optimizeImageSrc(
                                    previewItem.image_or_icon ||
                                        (previewItem.category === 'achievement'
                                            ? '/assets/images/achievements/piagam-ksm-2023.png'
                                            : '/assets/images/certificates/sertifikat-stekom.png'),
                                    1280,
                                )}
                                alt={previewItem.title}
                                decoding="async"
                                className="h-auto max-h-[90vh] w-auto max-w-[95vw] rounded-2xl object-contain shadow-2xl sm:rounded-3xl"
                                onError={(e) => {
                                    const raw =
                                        previewItem.image_or_icon ||
                                        (previewItem.category === 'achievement'
                                            ? '/assets/images/achievements/piagam-ksm-2023.png'
                                            : '/assets/images/certificates/sertifikat-stekom.png');
                                    if (
                                        e.currentTarget.src.includes(
                                            '/media-opt/',
                                        )
                                    ) {
                                        e.currentTarget.src = raw;
                                    }
                                }}
                            />
                        </motion.div>
                    </motion.div>
                )}
            </AnimatePresence>
        </section>
    );
}
