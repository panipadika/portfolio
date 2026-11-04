import React, { Component } from 'react';
import { useAssetPreloader } from '../hooks/useAssetPreloader';
import { usePortfolioData } from '../hooks/usePortfolioData';
import Preloader from './common/Preloader';
import Navbar from './common/Navbar';
import Footer from './common/Footer';
import HeroSection from './sections/HeroSection';
import AboutSection from './sections/AboutSection';
import SpotifySection from './sections/SpotifySection';
import PortfolioSection from './sections/PortfolioSection';
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
                <div className="flex min-h-screen flex-col items-center justify-center bg-[#0B0F17] p-6 text-center text-white">
                    <h2 className="mb-2 text-xl font-bold">
                        Terjadi Kendala Memuat Halaman
                    </h2>
                    <p className="mb-4 text-sm text-slate-400">
                        Klik tombol di bawah untuk menyegarkan tampilan.
                    </p>
                    <button
                        onClick={() => window.location.reload()}
                        className="cursor-pointer rounded-full bg-gradient-to-r from-indigo-600 to-cyan-500 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-600/30 transition-transform hover:scale-105 active:scale-95"
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
        ['/card-front.png', '/band.png'],
    );

    const { data: portfolioData } = usePortfolioData();

    return (
        <GlobalErrorBoundary>
            <div className="min-h-screen bg-[#0B0F17] text-slate-50 selection:bg-indigo-500/30 selection:text-indigo-200">
                {/* 3D Asset Preloader */}
                <Preloader
                    progress={progress}
                    isLoaded={isLoaded}
                    failedCount={failedAssets.length}
                    onRetry={retry}
                    onSkip={skip}
                />

                {/* Main Single Page Application */}
                <Navbar />

                <main>
                    <HeroSection />
                    <AboutSection
                        stats={portfolioData.stats}
                        education={portfolioData.education}
                        experience={portfolioData.experience}
                    />
                    <SpotifySection />
                    <PortfolioSection
                        projects={portfolioData.projects}
                        certificates={portfolioData.certificates}
                        achievements={portfolioData.achievements}
                        techStack={portfolioData.tech_stack}
                    />
                    <ContactSection />
                </main>

                <Footer />
            </div>
        </GlobalErrorBoundary>
    );
}
