import React, { useEffect, useRef } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { X, ExternalLink, Github, Layers } from 'lucide-react';
import TechBadge from './TechBadge';

export default function ProjectModal({ project, isOpen, onClose, techStack = [] }) {
    const modalRef = useRef(null);
    const closeBtnRef = useRef(null);
    const previousFocusRef = useRef(null);

    // Keyboard handling: Escape key to close & Focus Trapping
    useEffect(() => {
        if (!isOpen) return;

        // Save previously focused element to restore when modal closes
        previousFocusRef.current = document.activeElement;

        // Focus close button on mount
        const timer = setTimeout(() => {
            closeBtnRef.current?.focus();
        }, 50);

        const handleKeyDown = (e) => {
            if (e.key === 'Escape') {
                e.preventDefault();
                onClose();
                return;
            }

            if (e.key === 'Tab') {
                if (!modalRef.current) return;
                const focusables = modalRef.current.querySelectorAll(
                    'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])',
                );
                if (focusables.length === 0) return;

                const firstEl = focusables[0];
                const lastEl = focusables[focusables.length - 1];

                if (e.shiftKey) {
                    if (document.activeElement === firstEl) {
                        e.preventDefault();
                        lastEl.focus();
                    }
                } else {
                    if (document.activeElement === lastEl) {
                        e.preventDefault();
                        firstEl.focus();
                    }
                }
            }
        };

        window.addEventListener('keydown', handleKeyDown);

        // Prevent body scrolling when modal is open
        document.body.style.overflow = 'hidden';

        return () => {
            clearTimeout(timer);
            window.removeEventListener('keydown', handleKeyDown);
            document.body.style.overflow = '';
            // Restore focus
            if (
                previousFocusRef.current &&
                typeof previousFocusRef.current.focus === 'function'
            ) {
                previousFocusRef.current.focus();
            }
        };
    }, [isOpen, onClose]);

    if (!project) return null;

    return (
        <AnimatePresence>
            {isOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4 sm:p-6">
                    {/* Backdrop */}
                    <motion.div
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        exit={{ opacity: 0 }}
                        onClick={onClose}
                        className="fixed inset-0 bg-black/80 backdrop-blur-md"
                        aria-hidden="true"
                    />

                    {/* Modal Container */}
                    <motion.div
                        ref={modalRef}
                        role="dialog"
                        aria-modal="true"
                        aria-labelledby="project-modal-title"
                        aria-describedby="project-modal-desc"
                        initial={{ opacity: 0, scale: 0.95, y: 20 }}
                        animate={{ opacity: 1, scale: 1, y: 0 }}
                        exit={{ opacity: 0, scale: 0.95, y: 20 }}
                        transition={{ duration: 0.25, ease: 'easeOut' }}
                        className="border-space-800 bg-space-900 relative z-10 w-full max-w-2xl overflow-hidden rounded-3xl border p-6 shadow-2xl sm:p-8"
                    >
                        {/* Close Button */}
                        <button
                            ref={closeBtnRef}
                            onClick={onClose}
                            className="absolute top-5 right-5 cursor-pointer rounded-full border border-white/10 bg-white/5 p-2 text-slate-400 transition-colors hover:bg-white/10 hover:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                            aria-label="Tutup jendela modal projek"
                        >
                            <X className="h-5 w-5" />
                        </button>

                        {/* Thumbnail */}
                        {project.image_url && (
                            <div className="bg-[#080d1a] mb-6 aspect-[16/9] w-full overflow-hidden rounded-2xl border border-white/10 flex items-center justify-center">
                                <img
                                    src={project.image_url}
                                    alt={project.title}
                                    className="h-full w-full object-contain"
                                    onError={(e) => {
                                        e.currentTarget.src =
                                            'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=800&auto=format&fit=crop&q=80';
                                    }}
                                />
                            </div>
                        )}

                        {/* Title & Slug */}
                        <div className="mb-3 flex items-start justify-between gap-4">
                            <div>
                                <h3
                                    id="project-modal-title"
                                    className="font-display text-2xl font-bold text-white"
                                >
                                    {project.title}
                                </h3>
                                <span className="font-mono text-xs text-[#7185e8]">
                                    /{project.slug}
                                </span>
                            </div>
                        </div>

                        {/* Description */}
                        <p
                            id="project-modal-desc"
                            className="mb-6 text-sm leading-relaxed text-slate-300"
                        >
                            {project.description}
                        </p>

                        {/* Tech Tags */}
                        {project.tech_tags && project.tech_tags.length > 0 && (
                            <div className="mb-6">
                                <div className="mb-2 flex items-center gap-2 font-mono text-xs text-slate-400">
                                    <Layers className="h-3.5 w-3.5 text-indigo-400" />
                                    <span>Teknologi yang Digunakan:</span>
                                </div>
                                <div className="flex flex-wrap gap-2">
                                    {project.tech_tags.map((tag, idx) => (
                                        <TechBadge key={idx} name={tag} techStack={techStack} />
                                    ))}
                                </div>
                            </div>
                        )}

                        {/* Action Links */}
                        <div className="flex items-center gap-3 border-t border-white/10 pt-4">
                            {project.demo_url && (
                                <a
                                    href={project.demo_url}
                                    target="_blank"
                                    rel="noreferrer"
                                    className="inline-flex flex-1 cursor-pointer items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#262f6b] via-indigo-600 to-[#4e63d9] px-5 py-2.5 text-xs font-medium text-white shadow-lg shadow-[#262f6b]/30 transition-all hover:from-[#313c87] hover:to-[#5d72e4] focus:ring-2 focus:ring-[#4e63d9] focus:outline-none"
                                >
                                    <ExternalLink className="h-4 w-4" />
                                    <span>Live Preview</span>
                                </a>
                            )}

                            {project.repo_url && (
                                <a
                                    href={project.repo_url}
                                    target="_blank"
                                    rel="noreferrer"
                                    className="inline-flex flex-1 cursor-pointer items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/5 px-5 py-2.5 text-xs font-medium text-slate-200 transition-colors hover:bg-white/10 hover:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                >
                                    <Github className="h-4 w-4" />
                                    <span>Lihat Repository</span>
                                </a>
                            )}
                        </div>
                    </motion.div>
                </div>
            )}
        </AnimatePresence>
    );
}
