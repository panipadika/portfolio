import React, { useEffect, useRef, memo } from 'react';
import { motion } from 'framer-motion';
import { Music, ExternalLink } from 'lucide-react';
import { useAudioDispatch } from '../../context/AudioContext';

const SpotifySection = memo(function SpotifySection({
    playlistUrl = 'https://open.spotify.com/playlist/0oIazSH9k7vJrpx4qlnAwX?si=baY2yGzlS76Z2slFx4_90A',
}) {
    const containerRef = useRef(null);
    const initializedRef = useRef(false);
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

    // Initialize Spotify IFrame API Controller
    useEffect(() => {
        let isMounted = true;
        let createdController = null;

        if (initializedRef.current) {
            return;
        }

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
                // Disable programmatic scrollIntoView triggers from Spotify iframe
                frame.scrollIntoView = () => {};
            });
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
                    registerSpotifyController(controller);
                    secureIframe();
                });
            } catch (err) {
                console.warn('Spotify IFrame createController error:', err);
                mountFallback();
            }
        };

        const mountFallback = () => {
            if (
                !containerRef.current ||
                containerRef.current.children.length > 0
            ) {
                return;
            }
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

        // Fallback timer: if Spotify IFrame script fails or is blocked after 2.5s
        const fallbackTimer = setTimeout(() => {
            if (!initializedRef.current && isMounted) {
                mountFallback();
            }
        }, 2500);

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
            clearTimeout(fallbackTimer);
            unregisterSpotifyController();
            if (createdController) {
                try {
                    createdController.destroy?.();
                } catch {}
            }
            initializedRef.current = false;
        };
    }, [
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
                {/* Main Glassmorphic Card Container matching reference */}
                <motion.div
                    initial={{ opacity: 0, y: 35, scale: 0.98 }}
                    whileInView={{ opacity: 1, y: 0, scale: 1 }}
                    viewport={{ once: true, amount: 0.2 }}
                    transition={{ duration: 0.7, ease: [0.16, 1, 0.3, 1] }}
                    className="relative overflow-hidden rounded-3xl border border-white/10 bg-[#0d1322]/60 p-8 sm:p-10 lg:p-12 shadow-2xl backdrop-blur-xl"
                >
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
                        className="pointer-events-none absolute -top-20 left-1/4 h-72 w-72 rounded-full bg-indigo-600/15 blur-[100px]"
                        aria-hidden="true"
                    />
                    <div
                        className="pointer-events-none absolute -bottom-20 right-1/4 h-72 w-72 rounded-full bg-blue-600/10 blur-[100px]"
                        aria-hidden="true"
                    />

                    <div className="relative z-10 grid grid-cols-1 items-center gap-10 lg:grid-cols-12 lg:gap-12">
                        {/* Left Column: Heading, Description, and Pill Button (Original requested copy) */}
                        <motion.div
                            initial={{ opacity: 0, x: -25 }}
                            whileInView={{ opacity: 1, x: 0 }}
                            viewport={{ once: true, amount: 0.2 }}
                            transition={{ duration: 0.6, delay: 0.15, ease: [0.16, 1, 0.3, 1] }}
                            className="flex flex-col items-start justify-center lg:col-span-6"
                        >
                            <h2 className="font-display text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-[1.15]">
                                Rhythm in Beats. <br />
                                <span className="text-[#5c7cfa] sm:text-[#7185e8]">
                                    Logic in Code.
                                </span>
                            </h2>

                            <p className="mt-6 max-w-lg font-sans text-sm sm:text-base leading-relaxed text-slate-300">
                                Setiap baris kode punya ritmenya tersendiri. Dari
                                eksplorasi larut malam hingga merancang arsitektur web,
                                inilah playlist music yang menjaga fokus dan kreativitas
                                saya tetap mengalir.
                            </p>

                            <div className="mt-8">
                                <a
                                    href={playlistUrl}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="group inline-flex items-center gap-2.5 rounded-full border border-indigo-500/30 bg-indigo-950/40 px-6 py-3 font-sans text-sm font-semibold text-indigo-300 shadow-lg backdrop-blur-md transition-all duration-300 hover:border-indigo-400 hover:bg-indigo-900/50 hover:text-white hover:shadow-[0_0_20px_rgba(92,124,250,0.35)] active:scale-95"
                                >
                                    <Music className="h-4 w-4 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-12" />
                                    <span>Masuk ke Playlist Saya</span>
                                    <ExternalLink className="h-3.5 w-3.5 transition-transform duration-300 group-hover:translate-x-0.5 group-hover:-translate-y-0.5" />
                                </a>
                            </div>
                        </motion.div>

                        {/* Right Column: Spotify Embed Player (Isolated layout-contained container) */}
                        <motion.div
                            initial={{ opacity: 0, x: 25 }}
                            whileInView={{ opacity: 1, x: 0 }}
                            viewport={{ once: true, amount: 0.2 }}
                            transition={{ duration: 0.6, delay: 0.25, ease: [0.16, 1, 0.3, 1] }}
                            className="w-full lg:col-span-6"
                        >
                            <div className="w-full max-w-full overflow-hidden rounded-2xl border border-white/10 shadow-2xl bg-black/40 min-h-[352px] [contain:paint_layout]">
                                <div
                                    ref={containerRef}
                                    className="w-full h-full min-h-[352px] [contain:strict] [transform:translate3d(0,0,0)]"
                                />
                            </div>
                        </motion.div>
                    </div>
                </motion.div>
            </div>
        </section>
    );
});

export default SpotifySection;
