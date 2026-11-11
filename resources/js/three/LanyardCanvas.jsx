import React, { Component, Suspense, useEffect, useState } from 'react';
import Lanyard from './Lanyard.tsx';

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
                        src="/card-front.webp"
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
    frontImage = '/card-front.webp',
    backImage = '/card-back.webp',
    strapImage = '/band.png',
    imageFit = 'cover',
    cardColor = '#ffffff',
    orientation = 'portrait',
    finish = 'glossy',
    cornerRadius = 0.3,
    size = 0.53,
    anchor = 'right',
    strapLength = 0.48,
    strapColor = '#111111',
    strapWidth = 0.6,
    metal = 'silver',
    gravity = 1,
    damping = 0.6,
    elasticity = 0.4,
    breeze = 0.2,
    interactive = true,
    intro = true,
    className = '',
}) {
    const [isMounted, setIsMounted] = useState(
        () => typeof window !== 'undefined',
    );

    useEffect(() => {
        setIsMounted(true);
    }, []);

    return (
        <div
            className={`relative flex w-full items-center justify-center select-none ${className || 'h-[480px] sm:h-[540px] lg:h-[600px]'}`}
            style={{ width: '100%' }}
        >
            {/* Ambient radial gradient glow behind 3D canvas (zero-blur GPU compositor path) */}
            <div
                className={`pointer-events-none absolute top-1/2 z-0 h-80 w-80 -translate-x-1/2 -translate-y-1/2 rounded-full sm:h-[440px] sm:w-[440px] ${anchor === 'right' ? 'left-[77%]' : 'left-1/2'}`}
                style={{
                    background:
                        'radial-gradient(circle, rgba(78,99,217,0.28) 0%, rgba(38,47,107,0.16) 42%, rgba(38,47,107,0) 72%)',
                }}
                aria-hidden="true"
            />
            <div
                className={`pointer-events-none absolute top-1/3 z-0 h-56 w-56 -translate-x-1/2 -translate-y-1/2 rounded-full ${anchor === 'right' ? 'left-[77%]' : 'left-1/2'}`}
                style={{
                    background:
                        'radial-gradient(circle, rgba(113,133,232,0.18) 0%, rgba(78,99,217,0) 70%)',
                }}
                aria-hidden="true"
            />

            {/* 3D Canvas Layer (z-10 per DESIGN.md, isolated GPU layer) */}
            <div className="relative z-10 h-full w-full [transform:translateZ(0)]">
                {isMounted ? (
                    <LanyardErrorBoundary>
                        <Suspense fallback={null}>
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
                ) : null}
            </div>
        </div>
    );
}
