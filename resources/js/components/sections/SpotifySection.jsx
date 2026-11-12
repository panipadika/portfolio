import React, { useEffect, useRef, useState, memo } from 'react';
import { motion } from 'framer-motion';
import { Music, ExternalLink } from 'lucide-react';
import { useAudioDispatch } from '../../context/AudioContext';

const SpotifySection = memo(function SpotifySection({
    playlistUrl = 'https://open.spotify.com/playlist/0oIazSH9k7vJrpx4qlnAwX?si=baY2yGzlS76Z2slFx4_90A',
}) {
    const embedWrapperRef = useRef(null);
    const containerRef = useRef(null);
    const initializedRef = useRef(false);
    const [shouldLoadEmbed, setShouldLoadEmbed] = useState(false);
    // Consumes stable dispatch only; will NOT re-render on play/pause/track transition
    const { registerSpotifyController, unregisterSpotifyController } =
        useAudioDispatch();

    // Helper to format any Spotify playlist/album/track URL to an official embed URL
    const getEmbedUrl = (url) => {
        if (!url) {
            return 'https://open.spotify.com/embed/playlist/0oIazSH9k7vJrpx4qlnAwX?utm_source=generator&theme=0';
        }
        if (url.includes('/embed/')) {
            return url.includes('theme=0')
                ? url
                : `${url}${url.includes('?') ? '&' : '?'}theme=0`;
        }
        const match = url.match(
            /spotify\.com\/(playlist|album|track)\/([a-zA-Z0-9]+)/,
        );
        if (match) {
            return `https://open.spotify.com/embed/${match[1]}/${match[2]}?utm_source=generator&theme=0`;
        }
        return url;
    };

    // Helper to convert URL to Spotify URI required by Spotify IFrame API
    const toSpotifyUri = (url) => {
        if (!url) return 'spotify:playlist:0oIazSH9k7vJrpx4qlnAwX';
        if (url.startsWith('spotify:')) return url;
        const match = url.match(
            /spotify\.com\/(playlist|album|track)\/([a-zA-Z0-9]+)/,
        );
        if (match) {
            return `spotify:${match[1]}:${match[2]}`;
        }
        return url;
    };

    const embedUrl = getEmbedUrl(playlistUrl);
    const spotifyUri = toSpotifyUri(playlistUrl);

    // Prevent unintended auto-scroll jumps ONLY when #Spotify is off-screen during a track switch,
    // without touching normal vertical scrolling or toggling `inert` mid-swipe.
    useEffect(() => {
        const wrapperEl = embedWrapperRef.current;
        if (!wrapperEl) return;

        let safeOffscreenY = window.scrollY;
        let guardUntil = 0;
        let guardRestoreY = window.scrollY;
        let allowSmoothNavUntil = -Infinity;
        let restoreRaf = 0;

        const isOffscreen = () => {
            const rect = wrapperEl.getBoundingClientRect();
            const vh = window.innerHeight || 800;
            return rect.bottom < -30 || rect.top > vh + 30;
        };

        const isVisiblyInViewport = () => {
            const rect = wrapperEl.getBoundingClientRect();
            const vh = window.innerHeight || 800;
            return rect.bottom > 40 && rect.top < vh - 40;
        };

        const restoreOnceIfJumped = () => {
            if (
                performance.now() >= guardUntil ||
                performance.now() < allowSmoothNavUntil
            ) {
                return;
            }
            const jumpedDist = Math.abs(window.scrollY - guardRestoreY);
            if (jumpedDist > 120 && isVisiblyInViewport()) {
                window.scrollTo({ top: guardRestoreY, behavior: 'instant' });
                guardUntil = 0;
            }
        };

        const handleScroll = () => {
            const now = performance.now();
            if (now < guardUntil) {
                restoreOnceIfJumped();
                return;
            }
            if (isOffscreen()) {
                safeOffscreenY = window.scrollY;
            }
        };

        const armOffscreenGuard = () => {
            const now = performance.now();
            if (now < allowSmoothNavUntil || !isOffscreen()) {
                return;
            }
            guardRestoreY = isOffscreen() ? window.scrollY : safeOffscreenY;
            guardUntil = now + 320;
            if (restoreRaf) cancelAnimationFrame(restoreRaf);
            restoreRaf = requestAnimationFrame(() => {
                restoreOnceIfJumped();
            });
        };

        const handleNavAllow = () => {
            allowSmoothNavUntil = performance.now() + 1400;
            guardUntil = 0;
        };

        const handleDocClick = (e) => {
            if (
                e.target instanceof Element &&
                e.target.closest('a[href^="#"], [data-scroll-nav]')
            ) {
                handleNavAllow();
            }
        };

        const clearGuardOnWheelOrKey = () => {
            guardUntil = 0;
        };

        window.addEventListener('scroll', handleScroll, { passive: true });
        window.addEventListener('wheel', clearGuardOnWheelOrKey, {
            passive: true,
        });
        window.addEventListener('touchmove', clearGuardOnWheelOrKey, {
            passive: true,
        });
        window.addEventListener('keydown', clearGuardOnWheelOrKey, {
            passive: true,
        });
        window.addEventListener('spotify:scroll-guard', armOffscreenGuard);
        window.addEventListener('portfolio:nav-scroll', handleNavAllow);
        window.addEventListener('popstate', handleNavAllow);
        document.addEventListener('click', handleDocClick, { capture: true });

        return () => {
            if (restoreRaf) cancelAnimationFrame(restoreRaf);
            window.removeEventListener('scroll', handleScroll);
            window.removeEventListener('wheel', clearGuardOnWheelOrKey);
            window.removeEventListener('touchmove', clearGuardOnWheelOrKey);
            window.removeEventListener('keydown', clearGuardOnWheelOrKey);
            window.removeEventListener(
                'spotify:scroll-guard',
                armOffscreenGuard,
            );
            window.removeEventListener('portfolio:nav-scroll', handleNavAllow);
            window.removeEventListener('popstate', handleNavAllow);
            document.removeEventListener('click', handleDocClick, {
                capture: true,
            });
        };
    }, []);

    // Defer Spotify third-party embed on passive initial load (keeps Lighthouse fast),
    // but warm up immediately upon first user interaction or approaching #Spotify.
    useEffect(() => {
        if (shouldLoadEmbed) return;

        const target = embedWrapperRef.current || containerRef.current;
        const triggerLoad = () => setShouldLoadEmbed(true);

        let observer = null;
        if (target && 'IntersectionObserver' in window) {
            observer = new IntersectionObserver(
                (entries) => {
                    if (entries.some((entry) => entry.isIntersecting)) {
                        triggerLoad();
                        observer?.disconnect();
                    }
                },
                { rootMargin: '800px 0px' },
            );
            observer.observe(target);
        }

        const warmOnUserActivity = () => {
            triggerLoad();
        };

        window.addEventListener('spotify:request-load', triggerLoad);
        window.addEventListener('pointerdown', warmOnUserActivity, {
            once: true,
            passive: true,
        });
        window.addEventListener('touchstart', warmOnUserActivity, {
            once: true,
            passive: true,
        });
        window.addEventListener('wheel', warmOnUserActivity, {
            once: true,
            passive: true,
        });
        window.addEventListener('keydown', warmOnUserActivity, {
            once: true,
            passive: true,
        });

        return () => {
            observer?.disconnect();
            window.removeEventListener('spotify:request-load', triggerLoad);
            window.removeEventListener('pointerdown', warmOnUserActivity);
            window.removeEventListener('touchstart', warmOnUserActivity);
            window.removeEventListener('wheel', warmOnUserActivity);
            window.removeEventListener('keydown', warmOnUserActivity);
        };
    }, [shouldLoadEmbed]);

    // Initialize Spotify IFrame API Controller cleanly once without reloading on track changes
    useEffect(() => {
        if (!shouldLoadEmbed || initializedRef.current) {
            return;
        }

        let isMounted = true;
        let createdController = null;
        let fallbackTimer = 0;
        let readyTimer = 0;

        // Ensure Spotify IFrame API script is injected once
        let scriptTag = document.querySelector(
            'script[src="https://open.spotify.com/embed/iframe-api/v1"]',
        );
        if (!scriptTag) {
            scriptTag = document.createElement('script');
            scriptTag.src = 'https://open.spotify.com/embed/iframe-api/v1';
            scriptTag.async = true;
            document.body.appendChild(scriptTag);
        }

        const secureIframe = () => {
            if (!containerRef.current) return;
            const iframes = containerRef.current.querySelectorAll('iframe');
            iframes.forEach((frame) => {
                frame.tabIndex = -1;
                frame.scrollIntoView = () => {};
            });
        };

        const clearTimers = () => {
            if (fallbackTimer) {
                clearTimeout(fallbackTimer);
                fallbackTimer = 0;
            }
            if (readyTimer) {
                clearTimeout(readyTimer);
                readyTimer = 0;
            }
        };

        const mountFallback = () => {
            if (
                !isMounted ||
                !containerRef.current ||
                initializedRef.current ||
                containerRef.current.children.length > 0
            ) {
                return;
            }
            initializedRef.current = true;
            const fallbackIframe = document.createElement('iframe');
            fallbackIframe.title = 'Spotify Daily Coding Playlist';
            fallbackIframe.style.borderRadius = '16px';
            fallbackIframe.src = embedUrl;
            fallbackIframe.width = '100%';
            fallbackIframe.height = '352px';
            fallbackIframe.frameBorder = '0';
            fallbackIframe.allowFullscreen = true;
            fallbackIframe.allow =
                'autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture';
            fallbackIframe.loading = 'lazy';
            fallbackIframe.className = 'block w-full max-w-full';
            fallbackIframe.tabIndex = -1;
            fallbackIframe.scrollIntoView = () => {};
            containerRef.current.appendChild(fallbackIframe);
        };

        const initController = (IFrameAPI) => {
            if (
                !isMounted ||
                !containerRef.current ||
                !IFrameAPI?.createController ||
                initializedRef.current
            ) {
                return;
            }

            if (fallbackTimer) {
                clearTimeout(fallbackTimer);
                fallbackTimer = 0;
            }

            initializedRef.current = true;
            containerRef.current.innerHTML = '';
            const mountNode = document.createElement('div');
            mountNode.style.width = '100%';
            mountNode.style.height = '352px';
            mountNode.style.overflow = 'hidden';
            containerRef.current.appendChild(mountNode);

            const options = {
                uri: spotifyUri,
                width: '100%',
                height: 352,
            };

            try {
                IFrameAPI.createController(mountNode, options, (controller) => {
                    if (!isMounted) {
                        try {
                            controller?.destroy?.();
                        } catch {}
                        return;
                    }
                    createdController = controller;
                    secureIframe();

                    let registered = false;
                    const markReady = () => {
                        if (!isMounted || registered) return;
                        registered = true;
                        if (readyTimer) {
                            clearTimeout(readyTimer);
                            readyTimer = 0;
                        }
                        registerSpotifyController(controller);
                    };

                    controller.addListener?.('ready', markReady);
                    // Safety fallback if the embed omits a 'ready' event on cached init
                    readyTimer = setTimeout(markReady, 1200);
                });
            } catch (err) {
                console.warn('Spotify IFrame createController error:', err);
                initializedRef.current = false;
                mountFallback();
            }
        };

        // Fallback timer only triggers if API script fails to initialize within 5s
        fallbackTimer = setTimeout(() => {
            if (!initializedRef.current && isMounted) {
                mountFallback();
            }
        }, 5000);

        // Check if Spotify API is already initialized
        if (window.SpotifyIframeApi) {
            initController(window.SpotifyIframeApi);
        } else {
            const prevCallback = window.onSpotifyIframeApiReady;
            window.onSpotifyIframeApiReady = (IFrameAPI) => {
                window.SpotifyIframeApi = IFrameAPI;
                if (typeof prevCallback === 'function') {
                    try {
                        prevCallback(IFrameAPI);
                    } catch (e) {
                        console.warn(e);
                    }
                }
                initController(IFrameAPI);
            };
        }

        return () => {
            isMounted = false;
            clearTimers();
            unregisterSpotifyController();
            if (createdController) {
                try {
                    createdController.destroy?.();
                } catch {}
            }
            initializedRef.current = false;
        };
    }, [
        shouldLoadEmbed,
        spotifyUri,
        embedUrl,
        registerSpotifyController,
        unregisterSpotifyController,
    ]);

    return (
        <section
            id="Spotify"
            className="relative overflow-hidden py-16 sm:py-20 lg:py-24"
            aria-label="Daily Rotation Spotify"
        >
            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                {/* Main Card Container (avoid backdrop-blur / scale transforms around OOPIF iframe to prevent mobile scroll glitches) */}
                <div className="relative overflow-hidden rounded-3xl border border-white/10 bg-[#0b1120]/95 p-8 shadow-2xl sm:p-10 lg:p-12">
                    {/* Background Cosmic Orbital Rings Decoration */}
                    <div
                        className="pointer-events-none absolute -top-24 -left-24 h-96 w-96 rounded-full border border-white/[0.04]"
                        aria-hidden="true"
                    />
                    <div
                        className="pointer-events-none absolute -top-12 -left-12 h-[30rem] w-[30rem] rounded-full border border-[#4e63d9]/10"
                        aria-hidden="true"
                    />

                    {/* Ambient Glow */}
                    <div
                        className="pointer-events-none absolute -top-20 left-1/4 h-72 w-72 rounded-full"
                        style={{
                            background:
                                'radial-gradient(circle, rgba(79,70,229,0.16) 0%, rgba(79,70,229,0) 70%)',
                        }}
                        aria-hidden="true"
                    />
                    <div
                        className="pointer-events-none absolute right-1/4 -bottom-20 h-72 w-72 rounded-full"
                        style={{
                            background:
                                'radial-gradient(circle, rgba(37,99,235,0.12) 0%, rgba(37,99,235,0) 70%)',
                        }}
                        aria-hidden="true"
                    />

                    <div className="relative z-10 grid grid-cols-1 items-center gap-10 lg:grid-cols-12 lg:gap-12">
                        {/* Left Column: Heading, Description, and Pill Button (Original requested copy) */}
                        <motion.div
                            initial={{ opacity: 0, x: -25 }}
                            whileInView={{ opacity: 1, x: 0 }}
                            viewport={{ once: true, amount: 0.2 }}
                            transition={{
                                duration: 0.6,
                                delay: 0.1,
                                ease: [0.16, 1, 0.3, 1],
                            }}
                            className="flex flex-col items-start justify-center lg:col-span-6"
                        >
                            <h2 className="font-display text-4xl leading-[1.15] font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
                                Rhythm in Beats. <br />
                                <span className="text-[#5c7cfa] sm:text-[#7185e8]">
                                    Logic in Code.
                                </span>
                            </h2>

                            <p className="mt-6 max-w-lg font-sans text-sm leading-relaxed text-slate-300 sm:text-base">
                                Setiap baris kode punya ritmenya tersendiri.
                                Dari eksplorasi larut malam hingga merancang
                                arsitektur web, inilah playlist music yang
                                menjaga fokus dan kreativitas saya tetap
                                mengalir.
                            </p>

                            <div className="mt-8">
                                <a
                                    href={playlistUrl}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="group inline-flex items-center gap-2.5 rounded-full border border-indigo-500/30 bg-indigo-950/40 px-6 py-3 font-sans text-sm font-semibold text-indigo-300 shadow-lg transition-all duration-300 hover:border-indigo-400 hover:bg-indigo-900/50 hover:text-white hover:shadow-[0_0_20px_rgba(92,124,250,0.35)] active:scale-95"
                                >
                                    <Music className="h-4 w-4 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-12" />
                                    <span>Masuk ke Playlist Saya</span>
                                    <ExternalLink className="h-3.5 w-3.5 transition-transform duration-300 group-hover:translate-x-0.5 group-hover:-translate-y-0.5" />
                                </a>
                            </div>
                        </motion.div>

                        {/* Right Column: Spotify Embed Player (clean fixed height without contain:strict/translate3d tearing) */}
                        <div className="w-full lg:col-span-6">
                            <div
                                ref={embedWrapperRef}
                                className="relative h-[352px] w-full max-w-full overflow-hidden rounded-2xl border border-white/10 bg-[#090e1a] shadow-2xl [overflow-anchor:none]"
                            >
                                {!shouldLoadEmbed && (
                                    <div className="pointer-events-none absolute inset-0 flex flex-col items-center justify-center gap-3 bg-[#090e1a] text-slate-400">
                                        <Music className="h-7 w-7 text-[#7185e8]" />
                                        <span className="font-mono text-xs tracking-wider uppercase">
                                            Memuat pemutar Spotify...
                                        </span>
                                    </div>
                                )}
                                <div
                                    ref={containerRef}
                                    className="h-[352px] w-full [overflow-anchor:none]"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
});

export default SpotifySection;
