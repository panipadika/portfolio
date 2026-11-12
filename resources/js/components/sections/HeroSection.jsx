import React, { useState, useEffect, lazy, Suspense } from 'react';
import { ArrowRight, Send } from 'lucide-react';
import StarBorder from '../ui/StarBorder';

const LanyardCanvas = lazy(() => import('../../three/LanyardCanvas'));

const ROLES = [
    'Full-Stack Developer',
    'Laravel & React Developer',
    'AI Engineer',
    'Creative 3D Developer',
];

export default function HeroSection() {
    const [currentRoleIndex, setCurrentRoleIndex] = useState(0);
    const [displayedText, setDisplayedText] = useState('');
    const [isDeleting, setIsDeleting] = useState(false);
    const [isDesktop, setIsDesktop] = useState(() =>
        typeof window !== 'undefined' ? window.innerWidth >= 1024 : true,
    );

    useEffect(() => {
        const checkIsDesktop = () => {
            const matches = window.innerWidth >= 1024;
            setIsDesktop((prev) => (prev !== matches ? matches : prev));
        };
        window.addEventListener('resize', checkIsDesktop);
        return () => window.removeEventListener('resize', checkIsDesktop);
    }, []);

    useEffect(() => {
        const currentFullText = ROLES[currentRoleIndex];
        let timer;

        if (!isDeleting) {
            if (displayedText.length < currentFullText.length) {
                timer = setTimeout(() => {
                    setDisplayedText(
                        currentFullText.slice(0, displayedText.length + 1),
                    );
                }, 80);
            } else {
                timer = setTimeout(() => {
                    setIsDeleting(true);
                }, 1800);
            }
        } else {
            if (displayedText.length > 0) {
                timer = setTimeout(() => {
                    setDisplayedText(
                        currentFullText.slice(0, displayedText.length - 1),
                    );
                }, 40);
            } else {
                setIsDeleting(false);
                setCurrentRoleIndex((prev) => (prev + 1) % ROLES.length);
            }
        }

        return () => clearTimeout(timer);
    }, [displayedText, isDeleting, currentRoleIndex]);

    return (
        <section
            id="Home"
            className="relative overflow-visible pt-20 pb-8 sm:pt-28 sm:pb-10 lg:pt-36 lg:pb-12"
        >
            {/* Background radial gradients (zero-blur GPU-friendly halos so 3D lanyard runs at full framerate without compositing stalls) */}
            <div className="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
                <div
                    className="absolute top-[12%] -left-12 h-[420px] w-[420px] rounded-full opacity-80"
                    style={{
                        background:
                            'radial-gradient(circle, rgba(79,70,229,0.14) 0%, rgba(79,70,229,0) 70%)',
                    }}
                />
                <div
                    className="absolute top-[22%] -right-12 h-[380px] w-[380px] rounded-full opacity-80"
                    style={{
                        background:
                            'radial-gradient(circle, rgba(78,99,217,0.14) 0%, rgba(78,99,217,0) 70%)',
                    }}
                />
            </div>

            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div className="grid grid-cols-1 items-center gap-8 lg:grid-cols-12 lg:gap-8">
                    {/* Left Column: 7 Cols Typography & CTAs (order-2 on mobile so 3D Lanyard appears first) */}
                    <div className="pointer-events-none relative z-20 order-2 flex flex-col items-start text-left lg:order-1 lg:col-span-7">
                        {/* Main Headline (Welcome to My Portfolio) */}
                        <h1 className="font-display mb-3 text-4xl leading-[1.1] font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
                            <span className="block bg-gradient-to-r from-[#262f6b] via-[#4e63d9] to-[#7185e8] bg-clip-text text-transparent">
                                Welcome to
                            </span>
                            <span className="mt-1 block text-white">
                                My Portfolio
                            </span>
                        </h1>

                        {/* Typewriter Dynamic Role Bar */}
                        <div className="mb-6 flex min-h-[32px] items-center font-mono text-lg font-semibold text-[#7185e8] sm:text-xl">
                            <span className="tracking-wide">
                                {displayedText}
                            </span>
                            <span
                                className="ml-1 inline-block h-5 w-0.5 animate-pulse bg-[#7185e8]"
                                aria-hidden="true"
                            />
                        </div>

                        {/* Narrative Description (Personal & Engaging per reference) */}
                        <p className="mb-8 max-w-2xl font-sans text-base leading-relaxed text-slate-300 sm:text-lg">
                            Berawal dari rasa ingin tahu tentang bagaimana
                            teknologi bekerja, kini saya menjadikan dunia
                            pemrograman sebagai passion. Saya senang
                            mengeksplorasi teknologi baru, membangun aplikasi,
                            dan mengubah ide menjadi solusi digital yang nyata.
                        </p>

                        {/* Action Buttons with React Bits StarBorder */}
                        <div className="pointer-events-auto relative z-30 mb-8 flex w-full flex-wrap items-center gap-4 sm:mb-12 sm:w-auto lg:mb-20">
                            <StarBorder
                                as="a"
                                href="#Portfolio"
                                onClick={(e) => {
                                    e.preventDefault();
                                    document
                                        .getElementById('Portfolio')
                                        ?.scrollIntoView({
                                            behavior: 'smooth',
                                        });
                                }}
                                color="#ffffff"
                                trailColor="#7185e8"
                                duration={4}
                                direction="clockwise"
                                stars={1}
                                trailLength={0.3}
                                thickness={1}
                                radius={14}
                                glow={0.6}
                                hover="lap"
                                sparkle={false}
                                clickPulse
                                theme="dark"
                                backgroundColor="rgba(30, 41, 99, 0.85)"
                                textColor="#ffffff"
                                className="w-full text-sm font-semibold shadow-lg shadow-[#262f6b]/40 transition-transform duration-200 hover:scale-105 active:scale-95 sm:w-auto"
                            >
                                <span>Lihat Projek</span>
                                <ArrowRight className="h-4 w-4" />
                            </StarBorder>

                            <StarBorder
                                as="a"
                                href="#Contact"
                                onClick={(e) => {
                                    e.preventDefault();
                                    document
                                        .getElementById('Contact')
                                        ?.scrollIntoView({
                                            behavior: 'smooth',
                                        });
                                }}
                                color="#ffffff"
                                trailColor="#ffffff"
                                duration={4}
                                direction="clockwise"
                                stars={1}
                                trailLength={0.3}
                                thickness={1}
                                radius={14}
                                glow={0.6}
                                hover="lap"
                                sparkle={false}
                                clickPulse
                                theme="dark"
                                backgroundColor="rgba(12, 12, 15, 0.85)"
                                textColor="#e2e8f0"
                                className="w-full text-sm font-semibold transition-transform duration-200 hover:scale-105 active:scale-95 sm:w-auto"
                            >
                                <Send className="h-4 w-4" />
                                <span>Hubungi Saya</span>
                            </StarBorder>
                        </div>
                    </div>

                    {/* Right Column: desktop grid spacer + mobile layout spacer (order-1 on mobile so card hangs first) */}
                    <div className="relative order-1 lg:order-2 lg:col-span-5">
                        {/* Mobile layout spacer: reserves vertical breathing room so headline begins cleanly below the hanging card */}
                        <div
                            className="h-[320px] w-full sm:h-[360px] lg:hidden"
                            aria-hidden="true"
                        />
                    </div>
                </div>
            </div>

            {/* Mobile-only: Full Hero unclipped 3D stage (spans full viewport width & height, extending past navbar without boundary clipping) */}
            {!isDesktop && (
                <div className="pointer-events-none absolute inset-x-0 -top-6 bottom-0 z-10 overflow-visible lg:hidden">
                    <div className="pointer-events-auto h-full w-full">
                        <Suspense fallback={null}>
                            <LanyardCanvas
                                frontImage="/card-front.webp"
                                backImage="/card-back.webp"
                                strapImage="/band.png"
                                imageFit="cover"
                                cardColor="#ffffff"
                                orientation="portrait"
                                finish="glossy"
                                cornerRadius={0.3}
                                size={0.33}
                                anchor="center"
                                strapLength={0.08}
                                strapColor="#111111"
                                strapWidth={0.6}
                                metal="silver"
                                gravity={1.15}
                                damping={0.36}
                                elasticity={0.58}
                                breeze={0}
                                interactive={true}
                                intro={true}
                                className="h-full w-full"
                            />
                        </Suspense>
                    </div>
                </div>
            )}

            {/* Desktop-only: Full Hero unclipped 3D stage (z-10 so card glides behind text & navbar without blocking them) */}
            {isDesktop && (
                <div className="pointer-events-none absolute inset-0 z-10 hidden overflow-visible lg:block">
                    <div className="pointer-events-auto h-full w-full">
                        <Suspense fallback={null}>
                            <LanyardCanvas
                                frontImage="/card-front.webp"
                                backImage="/card-back.webp"
                                strapImage="/band.png"
                                imageFit="cover"
                                cardColor="#ffffff"
                                orientation="portrait"
                                finish="glossy"
                                cornerRadius={0.3}
                                size={0.53}
                                anchor="right"
                                strapLength={0.48}
                                strapColor="#111111"
                                strapWidth={0.6}
                                metal="silver"
                                gravity={1.08}
                                damping={0.32}
                                elasticity={0.62}
                                breeze={0.22}
                                interactive={true}
                                intro={true}
                                className="h-full w-full"
                            />
                        </Suspense>
                    </div>
                </div>
            )}
        </section>
    );
}
