import React from 'react';
import { motion } from 'framer-motion';
import {
    ArrowRight,
    Send,
    Github,
    Linkedin,
    Instagram,
    Sparkles,
} from 'lucide-react';
import LanyardCanvas from '../../three/LanyardCanvas';
import { SOCIAL_LINKS } from '../../config/socials';

export default function HeroSection() {
    return (
        <section
            id="Home"
            className="relative overflow-hidden pt-32 pb-20 md:py-36"
        >
            {/* Background radial gradients */}
            <div className="pointer-events-none absolute top-1/4 left-10 -z-10 h-96 w-96 rounded-full bg-indigo-600/10 blur-[140px]" />
            <div className="pointer-events-none absolute top-1/3 right-10 -z-10 h-80 w-80 rounded-full bg-cyan-500/10 blur-[120px]" />

            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div className="grid grid-cols-1 items-center gap-12 lg:grid-cols-12 lg:gap-8">
                    {/* Left Column: 7 Cols Typography & CTAs */}
                    <motion.div
                        initial={{ opacity: 0, y: 25 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.7, ease: [0.16, 1, 0.3, 1] }}
                        className="relative z-10 flex flex-col items-start text-left lg:col-span-7"
                    >
                        {/* Tech Badge */}
                        <div className="mb-6 inline-flex items-center gap-2 rounded-full border border-indigo-500/20 bg-indigo-500/10 px-3.5 py-1.5 font-mono text-xs font-medium text-indigo-300 backdrop-blur-md">
                            <span className="h-2 w-2 animate-pulse rounded-full bg-cyan-400" />
                            <span>Full-Stack Developer • React & Laravel</span>
                            <Sparkles className="h-3.5 w-3.5 text-indigo-400" />
                        </div>

                        {/* Main Headline */}
                        <h1 className="font-display mb-6 text-4xl leading-[1.15] font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
                            Halo, Saya{' '}
                            <span className="bg-gradient-to-r from-indigo-400 via-cyan-400 to-indigo-300 bg-clip-text text-transparent">
                                Pani Padika
                            </span>
                        </h1>

                        {/* Sub-headline */}
                        <p className="mb-8 max-w-2xl font-sans text-base leading-relaxed text-slate-300 sm:text-lg">
                            Membangun aplikasi web performa tinggi dengan
                            perpaduan arsitektur backend yang kokoh, basis data
                            terstruktur, serta pengalaman antarmuka visual 3D
                            interaktif yang memukau.
                        </p>

                        {/* Action Buttons */}
                        <div className="mb-10 flex w-full flex-wrap items-center gap-4 sm:w-auto">
                            <a
                                href="#Portofolio"
                                className="inline-flex w-full cursor-pointer items-center justify-center gap-2.5 rounded-full bg-gradient-to-r from-indigo-600 to-cyan-500 px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-indigo-600/30 transition-all duration-200 hover:scale-105 hover:from-indigo-500 hover:to-cyan-400 active:scale-95 sm:w-auto"
                            >
                                <span>Lihat Projek</span>
                                <ArrowRight className="h-4 w-4" />
                            </a>

                            <a
                                href="#Contact"
                                className="inline-flex w-full cursor-pointer items-center justify-center gap-2.5 rounded-full border border-white/10 bg-white/5 px-6 py-3.5 text-sm font-semibold text-slate-200 backdrop-blur-md transition-all duration-200 hover:scale-105 hover:bg-white/10 hover:text-white active:scale-95 sm:w-auto"
                            >
                                <Send className="h-4 w-4" />
                                <span>Hubungi Saya</span>
                            </a>
                        </div>

                        {/* Social Media Shortcut Pills */}
                        <div className="flex w-full items-center gap-3 border-t border-white/5 pt-4">
                            <span className="font-mono text-xs text-slate-500">
                                Connect:
                            </span>
                            <a
                                href={SOCIAL_LINKS.github.url}
                                target="_blank"
                                rel="noreferrer"
                                className="rounded-full border border-white/10 bg-white/5 p-2 text-slate-400 transition-colors hover:bg-white/10 hover:text-white"
                                aria-label={SOCIAL_LINKS.github.label}
                            >
                                <Github className="h-4 w-4" />
                            </a>
                            <a
                                href={SOCIAL_LINKS.linkedin.url}
                                target="_blank"
                                rel="noreferrer"
                                className="rounded-full border border-white/10 bg-white/5 p-2 text-slate-400 transition-colors hover:bg-white/10 hover:text-white"
                                aria-label={SOCIAL_LINKS.linkedin.label}
                            >
                                <Linkedin className="h-4 w-4" />
                            </a>
                            <a
                                href={SOCIAL_LINKS.instagram.url}
                                target="_blank"
                                rel="noreferrer"
                                className="rounded-full border border-white/10 bg-white/5 p-2 text-slate-400 transition-colors hover:bg-white/10 hover:text-white"
                                aria-label={SOCIAL_LINKS.instagram.label}
                            >
                                <Instagram className="h-4 w-4" />
                            </a>
                        </div>
                    </motion.div>

                    {/* Right Column: 3D Lanyard Interactive Stage */}
                    <motion.div
                        initial={{ opacity: 0, scale: 0.95 }}
                        animate={{ opacity: 1, scale: 1 }}
                        transition={{
                            duration: 0.8,
                            delay: 0.2,
                            ease: [0.16, 1, 0.3, 1],
                        }}
                        className="relative flex items-center justify-center lg:col-span-5"
                    >
                        <div className="relative flex h-[520px] w-full items-center justify-center sm:h-[580px] lg:h-[600px]">
                            <LanyardCanvas
                                frontImage="/card-front.png"
                                backImage="/card-back.png"
                                strapImage="/band.png"
                                imageFit="cover"
                                cardColor="#ffffff"
                                orientation="portrait"
                                finish="glossy"
                                cornerRadius={0.3}
                                size={0.6}
                                anchor="center"
                                strapLength={0.5}
                                strapColor="#111111"
                                strapWidth={0.65}
                                metal="silver"
                                gravity={1}
                                damping={0.5}
                                elasticity={0.5}
                                breeze={0.5}
                                interactive={true}
                                intro={true}
                                className="h-full w-full"
                            />
                        </div>
                    </motion.div>
                </div>
            </div>
        </section>
    );
}
