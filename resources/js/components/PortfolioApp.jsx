import React, { Component, useState, useEffect, useCallback, useRef } from 'react';
import { AlertCircle, RefreshCw } from 'lucide-react';
import { useAssetPreloader } from '../hooks/useAssetPreloader';
import { usePortfolioData } from '../hooks/usePortfolioData';
import { AudioProvider } from '../context/AudioContext';
import Preloader from './common/Preloader';
import Navbar from './common/Navbar';
import Footer from './common/Footer';
import Galaxy from './ui/Galaxy';
import HeroSection from './sections/HeroSection';
import AboutSection from './sections/AboutSection';
import SpotifySection from './sections/SpotifySection';
import PortfolioSection from './sections/PortfolioSection';
import ProjectDetailView from './sections/ProjectDetailView';
import ContactSection from './sections/ContactSection';

class GlobalErrorBoundary extends Component {
    constructor(props) {
        super(props);
        this.state = { hasError: false };
    }

    static getDerivedStateFromError() {
        return { hasError: true };
    }

    componentDidCatch(error, errorInfo) {
        console.error('PortfolioApp Error Boundary caught:', error, errorInfo);
    }

    render() {
        if (this.state.hasError) {
            return (
                <div className="flex min-h-screen flex-col items-center justify-center bg-space-950 p-6 text-center text-white">
                    <h2 className="mb-2 text-xl font-bold">
                        Terjadi Kendala Memuat Halaman
                    </h2>
                    <p className="mb-4 text-sm text-slate-400">
                        Klik tombol di bawah untuk menyegarkan tampilan.
                    </p>
                    <button
                        onClick={() => window.location.reload()}
                        className="cursor-pointer rounded-full bg-gradient-to-r from-[#262f6b] via-indigo-600 to-[#4e63d9] px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-[#262f6b]/30 transition-transform hover:scale-105 hover:from-[#313c87] hover:to-[#5d72e4] active:scale-95"
                    >
                        Muat Ulang Halaman
                    </button>
                </div>
            );
        }
        return this.props.children;
    }
}

export default function PortfolioApp() {
    const { progress, isLoaded, failedAssets, retry, skip } = useAssetPreloader(
        [
            '/card-front.webp',
            '/card-back.webp',
            '/band.png',
            '/logo-pp.png',
            '/assets/images/profile-transparent.png',
            '/assets/images/spiderman-transformed.png',
        ],
    );

    const {
        data: portfolioData,
        error: apiError,
        refetch: retryApi,
    } = usePortfolioData();

    const [selectedProject, setSelectedProject] = useState(null);
    const previousProjectRef = useRef(null);

    const scrollToPortfolio = useCallback(() => {
        const performScroll = () => {
            const el =
                document.getElementById('Portfolio') ||
                document.getElementById('Portofolio');
            if (el) {
                const navOffset = 80;
                const elementTop =
                    el.getBoundingClientRect().top + window.pageYOffset;
                window.scrollTo({
                    top: Math.max(0, elementTop - navOffset),
                    behavior: 'smooth',
                });
            }
        };

        requestAnimationFrame(performScroll);
        setTimeout(performScroll, 50);
        setTimeout(performScroll, 150);
    }, []);

    // Watch transition from selectedProject -> null (user clicked Back or Projects breadcrumb)
    useEffect(() => {
        if (previousProjectRef.current && !selectedProject) {
            scrollToPortfolio();
        }
        previousProjectRef.current = selectedProject;
    }, [selectedProject, scrollToPortfolio]);

    // Sync state with URL query parameter (?project=slug)
    useEffect(() => {
        const syncFromUrl = () => {
            const params = new URLSearchParams(window.location.search);
            const slug = params.get('project');
            if (slug && portfolioData.projects && portfolioData.projects.length > 0) {
                const found = portfolioData.projects.find(
                    (p) => p.slug === slug || String(p.id) === slug
                );
                if (found) {
                    setSelectedProject(found);
                    return;
                }
            }
            setSelectedProject(null);
        };

        syncFromUrl();
        window.addEventListener('popstate', syncFromUrl);
        return () => window.removeEventListener('popstate', syncFromUrl);
    }, [portfolioData.projects]);

    const handleSelectProject = useCallback((project) => {
        setSelectedProject(project);
        const url = new URL(window.location.href);
        url.searchParams.set('project', project.slug || project.id);
        window.history.pushState({ projectSlug: project.slug }, '', url.toString());
        window.scrollTo({ top: 0, behavior: 'instant' });
    }, []);

    const handleBackToHome = useCallback(() => {
        setSelectedProject(null);
        const url = new URL(window.location.href);
        url.searchParams.delete('project');
        window.history.pushState({}, '', url.pathname);
        scrollToPortfolio();
    }, [scrollToPortfolio]);

    return (
        <GlobalErrorBoundary>
            <AudioProvider>
                <div
                    className="relative min-h-screen bg-[#0B0F17] text-slate-50 selection:bg-indigo-500/30 selection:text-indigo-200"
                    style={{ backgroundColor: '#0B0F17' }}
                >
                    {/* Dedicated Project Detail View (No Galaxy 3D Background, No Navbar, No Footer) */}
                    {selectedProject && (
                        <div className="relative z-40 min-h-screen bg-[#050811]">
                            <ProjectDetailView
                                project={selectedProject}
                                techStack={portfolioData.tech_stack}
                                onBack={handleBackToHome}
                            />
                        </div>
                    )}

                    {/* Main Portfolio SPA View (Kept mounted to preserve Three.js state and instant scroll) */}
                    <div
                        className={selectedProject ? 'hidden' : 'block'}
                        aria-hidden={selectedProject ? 'true' : 'false'}
                    >
                        {/* Global Interactive Galaxy WebGL Background */}
                        <div
                            className="fixed inset-0 z-0 pointer-events-none overflow-hidden"
                            aria-hidden="true"
                        >
                            <Galaxy
                                dpr={1}
                                density={0.8}
                                glowIntensity={0.25}
                                flareIntensity={0.8}
                                starSpeed={0.4}
                                mouseRepulsion
                                clickWarp
                                starColors={['#ffffff', '#e2e8f0', '#7185e8', '#4e63d9']}
                                hueShift={140}
                                saturation={0}
                                starSize={1}
                                twinkleIntensity={0.25}
                                speed={0.8}
                                rotationSpeed={0.08}
                                layers={3}
                                direction="forward"
                                repulsionStrength={2}
                                mouseInteraction
                                intro={false}
                                lightMode={false}
                                transparent={true}
                                backgroundColor="#0B0F17"
                            />
                        </div>

                        <div className="relative z-10 isolate">
                            {/* 3D Asset Preloader */}
                            <Preloader
                                progress={progress}
                                isLoaded={isLoaded}
                                failedCount={failedAssets.length}
                                onRetry={retry}
                                onSkip={skip}
                            />

                            {/* Main Single Page Application Navbar */}
                            <Navbar isVisible={isLoaded && !selectedProject} />

                            {/* API Error Notification Banner (non-intrusive) */}
                            {apiError && (
                                <div className="relative z-30 mx-auto max-w-7xl px-4 pt-28 sm:px-6 lg:px-8">
                                    <div className="flex flex-col items-start justify-between gap-3 rounded-2xl border border-amber-500/30 bg-amber-500/10 p-4 text-sm text-amber-200 backdrop-blur-md sm:flex-row sm:items-center">
                                        <div className="flex items-center gap-2.5">
                                            <AlertCircle className="h-5 w-5 shrink-0 text-amber-400" />
                                            <span>
                                                Gagal terhubung ke server portofolio (
                                                {apiError}). Menampilkan data cadangan
                                                lokal.
                                            </span>
                                        </div>
                                        <button
                                            onClick={retryApi}
                                            className="inline-flex cursor-pointer items-center gap-1.5 rounded-full border border-amber-400/30 bg-amber-500/20 px-3.5 py-1.5 text-xs font-semibold text-amber-100 transition-colors hover:bg-amber-500/30"
                                        >
                                            <RefreshCw className="h-3.5 w-3.5" />
                                            <span>Coba Lagi</span>
                                        </button>
                                    </div>
                                </div>
                            )}

                            <main>
                                <HeroSection />
                                <AboutSection
                                    stats={portfolioData.stats}
                                    education={portfolioData.education}
                                    experience={portfolioData.experience}
                                />
                                <SpotifySection playlistUrl="https://open.spotify.com/playlist/0oIazSH9k7vJrpx4qlnAwX?si=baY2yGzlS76Z2slFx4_90A" />
                                <PortfolioSection
                                    projects={portfolioData.projects}
                                    certificates={portfolioData.certificates}
                                    achievements={portfolioData.achievements}
                                    techStack={portfolioData.tech_stack}
                                    onSelectProject={handleSelectProject}
                                />
                                <ContactSection settings={portfolioData.settings} />
                            </main>

                            <Footer />
                        </div>
                    </div>
                </div>
            </AudioProvider>
        </GlobalErrorBoundary>
    );
}
