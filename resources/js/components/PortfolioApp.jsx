import React, {
    Component,
    Suspense,
    lazy,
    useState,
    useEffect,
    useLayoutEffect,
    useCallback,
    useRef,
} from 'react';
import { AlertCircle, RefreshCw } from 'lucide-react';
import { usePortfolioData } from '../hooks/usePortfolioData';
import { AudioProvider } from '../context/AudioContext';
import Navbar from './common/Navbar';
import Galaxy from './ui/Galaxy';
import HeroSection from './sections/HeroSection';

const AboutSection = lazy(() => import('./sections/AboutSection'));
const SpotifySection = lazy(() => import('./sections/SpotifySection'));
const PortfolioSection = lazy(() => import('./sections/PortfolioSection'));
const ContactSection = lazy(() => import('./sections/ContactSection'));
const Footer = lazy(() => import('./common/Footer'));
const ProjectDetailView = lazy(() => import('./sections/ProjectDetailView'));

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
    const {
        data: portfolioData,
        error: apiError,
        refetch: retryApi,
    } = usePortfolioData();

    const [selectedProject, setSelectedProject] = useState(null);
    const [isMobile, setIsMobile] = useState(() =>
        typeof window !== 'undefined' ? window.innerWidth < 1024 : false,
    );
    const previousProjectRef = useRef(null);
    const savedReturnRef = useRef(null);
    const detailOverlayRef = useRef(null);

    useEffect(() => {
        const checkIsMobile = () => {
            const mobile = window.innerWidth < 1024;
            setIsMobile((prev) => (prev !== mobile ? mobile : prev));
        };
        window.addEventListener('resize', checkIsMobile, { passive: true });
        return () => window.removeEventListener('resize', checkIsMobile);
    }, []);

    // Keep background window.scrollY & Spotify/Contact/Portfolio DOM untouched when detail overlay opens/closes
    useLayoutEffect(() => {
        const prevProject = previousProjectRef.current;
        previousProjectRef.current = selectedProject;

        if (!prevProject || selectedProject) {
            return;
        }

        window.dispatchEvent(new CustomEvent('portfolio:nav-scroll'));

        const saved = savedReturnRef.current;
        const targetSlug = String(
            saved?.slug || prevProject.slug || prevProject.id || '',
        );

        const findCardElement = () => {
            if (!targetSlug) return null;
            const cards = document.querySelectorAll('[data-project-card]');
            for (const card of cards) {
                if (card.getAttribute('data-project-card') === targetSlug) {
                    return card;
                }
            }
            return null;
        };

        const resolveTargetY = () => {
            // Normal navigation from list keeps underlying window at saved.scrollY
            if (saved && typeof saved.scrollY === 'number') {
                return Math.max(0, saved.scrollY);
            }
            // Direct-URL entry (?project=slug): locate card or section immediately
            const cardEl = findCardElement();
            if (cardEl) {
                return Math.max(
                    0,
                    cardEl.getBoundingClientRect().top + window.scrollY - 104,
                );
            }
            const portfolioEl =
                document.getElementById('Portfolio') ||
                document.getElementById('Portofolio');
            if (portfolioEl) {
                return Math.max(
                    0,
                    portfolioEl.getBoundingClientRect().top +
                        window.scrollY -
                        80,
                );
            }
            return 0;
        };

        const resolvedY = resolveTargetY();
        if (resolvedY > 0 && Math.abs(window.scrollY - resolvedY) > 4) {
            window.scrollTo({ top: resolvedY, behavior: 'instant' });
            document.documentElement.scrollTop = resolvedY;
        }

        const rafId = requestAnimationFrame(() => {
            const postLayoutY = resolveTargetY();
            if (postLayoutY > 0 && Math.abs(window.scrollY - postLayoutY) > 8) {
                window.scrollTo({ top: postLayoutY, behavior: 'instant' });
            }
            savedReturnRef.current = null;
            if ('scrollRestoration' in window.history) {
                window.history.scrollRestoration = 'auto';
            }
        });

        return () => cancelAnimationFrame(rafId);
    }, [selectedProject]);

    // Sync state with URL query parameter (?project=slug)
    useEffect(() => {
        const syncFromUrl = () => {
            const params = new URLSearchParams(window.location.search);
            const slug = params.get('project');
            if (
                slug &&
                portfolioData.projects &&
                portfolioData.projects.length > 0
            ) {
                const found = portfolioData.projects.find(
                    (p) => p.slug === slug || String(p.id) === slug,
                );
                if (found) {
                    if ('scrollRestoration' in window.history) {
                        window.history.scrollRestoration = 'manual';
                    }
                    setSelectedProject(found);
                    return;
                }
            }
            window.dispatchEvent(new CustomEvent('portfolio:nav-scroll'));
            setSelectedProject(null);
        };

        syncFromUrl();
        window.addEventListener('popstate', syncFromUrl);
        return () => window.removeEventListener('popstate', syncFromUrl);
    }, [portfolioData.projects]);

    const handleSelectProject = useCallback((project) => {
        const slug = String(project.slug || project.id);
        savedReturnRef.current = {
            scrollY: window.scrollY,
            slug,
        };
        if ('scrollRestoration' in window.history) {
            window.history.scrollRestoration = 'manual';
        }
        window.dispatchEvent(new CustomEvent('portfolio:nav-scroll'));
        setSelectedProject(project);
        const url = new URL(window.location.href);
        url.searchParams.set('project', slug);
        window.history.pushState(
            { fromPortfolioList: true, projectSlug: project.slug },
            '',
            url.toString(),
        );
    }, []);

    const handleBackToHome = useCallback(() => {
        window.dispatchEvent(new CustomEvent('portfolio:nav-scroll'));
        if (window.history.state?.fromPortfolioList) {
            window.history.back();
            return;
        }
        setSelectedProject(null);
        const url = new URL(window.location.href);
        url.searchParams.delete('project');
        const nextUrl = `${url.pathname}${url.search}${url.hash}`;
        window.history.replaceState({}, '', nextUrl);
    }, []);

    // Lock body scroll and reset overlay scrollTop while viewing a project detail
    useEffect(() => {
        if (!selectedProject) return;

        const prevOverflow = document.body.style.overflow;
        document.body.style.overflow = 'hidden';
        if (detailOverlayRef.current) {
            detailOverlayRef.current.scrollTop = 0;
        }

        const handleKeyDown = (e) => {
            if (e.key === 'Escape') {
                handleBackToHome();
            }
        };
        window.addEventListener('keydown', handleKeyDown);

        return () => {
            document.body.style.overflow = prevOverflow;
            window.removeEventListener('keydown', handleKeyDown);
        };
    }, [selectedProject, handleBackToHome]);

    return (
        <GlobalErrorBoundary>
            <AudioProvider>
                <div
                    className="relative min-h-screen bg-[#0B0F17] text-slate-50 selection:bg-indigo-500/30 selection:text-indigo-200"
                    style={{ backgroundColor: '#0B0F17' }}
                >
                    {/* Dedicated Fullscreen Overlay Project Detail View (preserves underlying Spotify playback & scroll) */}
                    {selectedProject && (
                        <div
                            ref={detailOverlayRef}
                            className="fixed inset-0 z-50 overflow-y-auto overscroll-y-contain bg-[#050811]"
                            role="dialog"
                            aria-modal="true"
                            aria-label={`Project Detail: ${selectedProject.title}`}
                        >
                            <Suspense
                                fallback={
                                    <div className="min-h-screen bg-[#050811]" />
                                }
                            >
                                <ProjectDetailView
                                    project={selectedProject}
                                    techStack={portfolioData.tech_stack}
                                    onBack={handleBackToHome}
                                />
                            </Suspense>
                        </div>
                    )}

                    {/* Main Portfolio SPA View (always mounted & visible underneath so audio & sections never reload) */}
                    <div
                        className={
                            selectedProject
                                ? 'pointer-events-none select-none'
                                : 'block'
                        }
                        aria-hidden={selectedProject ? 'true' : 'false'}
                    >
                        {/* Global Interactive Galaxy WebGL Background */}
                        <div
                            className="pointer-events-none fixed inset-0 z-0 overflow-hidden"
                            aria-hidden="true"
                        >
                            <Galaxy
                                dpr={isMobile ? 0.8 : 1}
                                density={isMobile ? 0.55 : 0.8}
                                glowIntensity={0.25}
                                flareIntensity={0.8}
                                starSpeed={0.4}
                                disableAnimation={isMobile}
                                mouseRepulsion={!isMobile}
                                clickWarp={!isMobile}
                                starColors={[
                                    '#ffffff',
                                    '#e2e8f0',
                                    '#7185e8',
                                    '#4e63d9',
                                ]}
                                hueShift={140}
                                saturation={0}
                                starSize={1}
                                twinkleIntensity={0.25}
                                speed={0.8}
                                rotationSpeed={0.08}
                                layers={isMobile ? 2 : 3}
                                direction="forward"
                                repulsionStrength={2}
                                mouseInteraction={!isMobile}
                                intro={false}
                                lightMode={false}
                                transparent={true}
                                backgroundColor="#0B0F17"
                            />
                        </div>

                        <div className="relative isolate z-10">
                            {/* Main Single Page Application Navbar */}
                            <Navbar isVisible={!selectedProject} />

                            {/* API Error Notification Banner (non-intrusive) */}
                            {apiError && (
                                <div className="relative z-30 mx-auto max-w-7xl px-4 pt-28 sm:px-6 lg:px-8">
                                    <div className="flex flex-col items-start justify-between gap-3 rounded-2xl border border-amber-500/30 bg-amber-500/10 p-4 text-sm text-amber-200 backdrop-blur-md sm:flex-row sm:items-center">
                                        <div className="flex items-center gap-2.5">
                                            <AlertCircle className="h-5 w-5 shrink-0 text-amber-400" />
                                            <span>
                                                Gagal terhubung ke server
                                                portofolio ({apiError}).
                                                Menampilkan data cadangan lokal.
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
                                <Suspense
                                    fallback={
                                        <section
                                            id="About"
                                            className="min-h-[760px] border-t border-white/5"
                                            aria-hidden="true"
                                        />
                                    }
                                >
                                    <AboutSection
                                        stats={portfolioData.stats}
                                        education={portfolioData.education}
                                        experience={portfolioData.experience}
                                    />
                                </Suspense>
                                <Suspense
                                    fallback={
                                        <section
                                            id="Spotify"
                                            className="min-h-[520px]"
                                            aria-hidden="true"
                                        />
                                    }
                                >
                                    <SpotifySection playlistUrl="https://open.spotify.com/playlist/0oIazSH9k7vJrpx4qlnAwX?si=baY2yGzlS76Z2slFx4_90A" />
                                </Suspense>
                                <Suspense
                                    fallback={
                                        <section
                                            id="Portfolio"
                                            className="min-h-[760px] border-t border-white/5 bg-[#070b14]/50"
                                            aria-hidden="true"
                                        />
                                    }
                                >
                                    <PortfolioSection
                                        projects={portfolioData.projects}
                                        certificates={
                                            portfolioData.certificates
                                        }
                                        achievements={
                                            portfolioData.achievements
                                        }
                                        techStack={portfolioData.tech_stack}
                                        onSelectProject={handleSelectProject}
                                    />
                                </Suspense>
                                <Suspense
                                    fallback={
                                        <section
                                            id="Contact"
                                            className="min-h-[680px] border-t border-white/5"
                                            aria-hidden="true"
                                        />
                                    }
                                >
                                    <ContactSection
                                        settings={portfolioData.settings}
                                    />
                                </Suspense>
                            </main>

                            <Suspense fallback={null}>
                                <Footer />
                            </Suspense>
                        </div>
                    </div>
                </div>
            </AudioProvider>
        </GlobalErrorBoundary>
    );
}
