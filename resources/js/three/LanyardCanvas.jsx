import React, { Component, Suspense, lazy, useEffect, useState } from 'react';

const Lanyard = lazy(() => import('./Lanyard.tsx'));

class LanyardErrorBoundary extends Component {
    constructor(props) {
        super(props);
        this.state = { hasError: false };
    }

    static getDerivedStateFromError() {
        return { hasError: true };
    }

    componentDidCatch(error, info) {
        console.error('Lanyard 3D Error:', error, info);
    }

    render() {
        if (this.state.hasError) {
            return (
                <div className="flex h-full w-full items-center justify-center">
                    <img
                        src="/card-front.png"
                        alt="ID Card"
                        className="max-h-[80%] max-w-[80%] rounded-2xl object-contain shadow-2xl"
                    />
                </div>
            );
        }
        return this.props.children;
    }
}

/**
 * LanyardCanvas — 3D interactive ID Card container
 * Complies with AGENTS.md & React Bits specification:
 * - Isolated inside resources/js/three/
 * - Wrapped inside <Suspense fallback={null}> and ErrorBoundary
 * - Client-only mounting guard to protect Inertia SSR
 * - Ambient radial gradient glow behind canvas (z-0 per DESIGN.md)
 */
export default function LanyardCanvas({
    frontImage = '/card-front.png',
    backImage = '/card-back.png',
    strapImage = '/band.png',
    imageFit = 'cover',
    cardColor = '#94a3b8',
    orientation = 'portrait',
    finish = 'glossy',
    cornerRadius = 0.3,
    size = 0.6,
    anchor = 'right',
    strapLength = 0.5,
    strapColor = '#111111',
    strapWidth = 0.8,
    metal = 'silver',
    gravity = 1,
    damping = 0.5,
    elasticity = 0.5,
    breeze = 0.5,
    interactive = true,
    intro = true,
    className = '',
}) {
    const [isMounted, setIsMounted] = useState(false);

    useEffect(() => {
        setIsMounted(true);
    }, []);

    return (
        <div
            className={`relative flex w-full items-center justify-center select-none ${className || 'h-[480px] sm:h-[540px] lg:h-[600px]'}`}
            style={{ width: '100%' }}
        >
            {/* Ambient glow blobs behind 3D canvas (z-0 per DESIGN.md) */}
            <div
                className="pointer-events-none absolute top-1/2 left-1/2 z-0 h-72 w-72 -translate-x-1/2 -translate-y-1/2 rounded-full bg-gradient-to-tr from-indigo-600/25 via-cyan-500/20 to-purple-600/25 blur-3xl sm:h-96 sm:w-96"
                aria-hidden="true"
            />
            <div
                className="pointer-events-none absolute top-1/3 left-1/2 z-0 h-48 w-48 -translate-x-1/2 -translate-y-1/2 rounded-full bg-cyan-400/15 blur-2xl"
                aria-hidden="true"
            />

            {/* 3D Canvas Layer (z-10 per DESIGN.md) */}
            <div className="relative z-10 h-full w-full">
                {isMounted ? (
                    <LanyardErrorBoundary>
                        <Suspense
                            fallback={
                                <div className="flex h-full w-full items-center justify-center">
                                    <div className="h-12 w-12 animate-spin rounded-full border-2 border-indigo-500/20 border-t-indigo-500" />
                                </div>
                            }
                        >
                            <Lanyard
                                frontImage={frontImage}
                                backImage={backImage}
                                strapImage={strapImage}
                                imageFit={imageFit}
                                cardColor={cardColor}
                                orientation={orientation}
                                finish={finish}
                                cornerRadius={cornerRadius}
                                size={size}
                                anchor={anchor}
                                strapLength={strapLength}
                                strapColor={strapColor}
                                strapWidth={strapWidth}
                                metal={metal}
                                gravity={gravity}
                                damping={damping}
                                elasticity={elasticity}
                                breeze={breeze}
                                interactive={interactive}
                                intro={intro}
                                className="h-full w-full"
                            />
                        </Suspense>
                    </LanyardErrorBoundary>
                ) : (
                    <div className="flex h-full w-full items-center justify-center">
                        <div className="h-12 w-12 animate-spin rounded-full border-2 border-indigo-500/20 border-t-indigo-500" />
                    </div>
                )}
            </div>

            {/* Interactive Dragging Prompt Badge */}
            <div className="pointer-events-none absolute bottom-2 left-1/2 z-20 -translate-x-1/2">
                <span className="bg-space-900/80 inline-flex items-center gap-1.5 rounded-full border border-white/10 px-3 py-1 font-mono text-xs font-medium text-slate-300 shadow-lg backdrop-blur-md">
                    <span className="h-1.5 w-1.5 animate-pulse rounded-full bg-cyan-400" />
                    Tarik atau klik kartu untuk memutar
                </span>
            </div>
        </div>
    );
}
