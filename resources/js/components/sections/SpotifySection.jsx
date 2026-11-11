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

    // Prevent Spotify embed track changes, focus steals, or cross-frame scrollIntoView
    // from hijacking page scroll—even while user is scrolling, holding, or clicking elsewhere
    useEffect(() => {
        const containerEl = containerRef.current;
        const wrapperEl = embedWrapperRef.current || containerEl;
        if (!containerEl || !wrapperEl) return;

        let isSectionVisible = false;
        let isOutsideHolding = false;
        let outsideHoldReleaseTimer = 0;

        let safeScrollY = window.scrollY;
        let guardTargetY = window.scrollY;
        let guardUntil = 0;

        let restoreRaf1 = 0;
        let restoreRaf2 = 0;
        let restoreTimeout1 = 0;
        let restoreTimeout2 = 0;

        const scrollHistory = [{ t: performance.now(), y: window.scrollY }];
        const activeOutsideTouches = new Map();
        let outsidePointerDown = false;

        let lastWheelAt = -Infinity;
        let lastWheelDir = 0;
        let lastWheelStep = 0;

        let lastTouchSwipeAt = -Infinity;
        let lastTouchSwipeDir = 0;
        let lastTouchStep = 0;
        let touchFlingUntil = -Infinity;

        let lastKeyNavAt = -Infinity;
        let lastScrollbarDragAt = -Infinity;
        let allowSmoothNavUntil = -Infinity;
        let lastDirectSpotifyInteractAt = -Infinity;

        const recordHistory = (t, y) => {
            scrollHistory.push({ t, y });
            while (
                scrollHistory.length > 30 ||
                (scrollHistory.length > 2 && t - scrollHistory[0].t > 650)
            ) {
                scrollHistory.shift();
            }
        };

        const syncInert = () => {
            if ('inert' in containerEl) {
                containerEl.inert = !isSectionVisible || isOutsideHolding;
            }
        };

        const checkInitialVisibility = () => {
            const rect = wrapperEl.getBoundingClientRect();
            isSectionVisible = rect.bottom > 0 && rect.top < window.innerHeight;
            syncInert();
        };
        checkInitialVisibility();

        const isInsideWrapper = (target) =>
            target instanceof Node && wrapperEl.contains(target);

        const isOnScrollbar = (clientX) =>
            typeof clientX === 'number' &&
            clientX >= document.documentElement.clientWidth - 4;

        const isStationaryOutsideHold = () => {
            if (outsidePointerDown) return true;
            if (activeOutsideTouches.size === 0) return false;
            let maxMove = 0;
            activeOutsideTouches.forEach((touch) => {
                if (touch.maxMove > maxMove) maxMove = touch.maxMove;
            });
            return maxMove < 10;
        };

        const isEmbedJumpingIntoView = (fromY, toY) => {
            const rect = wrapperEl.getBoundingClientRect();
            const vh = window.innerHeight || 800;
            const centerNow = rect.top + rect.height / 2;
            const centerBefore = centerNow + (toY - fromY);
            const distBefore = Math.abs(centerBefore - vh / 2);
            const distNow = Math.abs(centerNow - vh / 2);
            const visibleNow = rect.bottom > 0 && rect.top < vh;
            return visibleNow && distNow < distBefore - 36;
        };

        const getPreJumpScrollY = () => {
            const now = performance.now();
            if (scrollHistory.length >= 2) {
                for (let i = scrollHistory.length - 1; i >= 1; i--) {
                    const curr = scrollHistory[i];
                    if (now - curr.t > 320) break;
                    const prev = scrollHistory[i - 1];
                    const delta = curr.y - prev.y;
                    if (
                        Math.abs(delta) > 90 &&
                        curr.t > allowSmoothNavUntil &&
                        isEmbedJumpingIntoView(prev.y, curr.y)
                    ) {
                        return prev.y;
                    }
                }
            }
            return safeScrollY;
        };

        const clearScheduledRestores = () => {
            if (restoreRaf1) cancelAnimationFrame(restoreRaf1);
            if (restoreRaf2) cancelAnimationFrame(restoreRaf2);
            if (restoreTimeout1) clearTimeout(restoreTimeout1);
            if (restoreTimeout2) clearTimeout(restoreTimeout2);
            restoreRaf1 = 0;
            restoreRaf2 = 0;
            restoreTimeout1 = 0;
            restoreTimeout2 = 0;
        };

        const enforceTargetY = (targetY) => {
            const clamped = Math.max(0, targetY);
            if (Math.abs(window.scrollY - clamped) > 2) {
                window.scrollTo({ top: clamped, behavior: 'instant' });
            }
        };

        const lockAndRestore = (targetY, durationMs = 1100) => {
            const now = performance.now();
            if (now < allowSmoothNavUntil) return;

            guardTargetY = Math.max(0, targetY);
            safeScrollY = guardTargetY;
            guardUntil = Math.max(guardUntil, now + durationMs);

            enforceTargetY(guardTargetY);
            clearScheduledRestores();

            const checkAndRestore = () => {
                if (
                    performance.now() < guardUntil &&
                    performance.now() >= allowSmoothNavUntil
                ) {
                    enforceTargetY(guardTargetY);
                }
            };

            restoreRaf1 = requestAnimationFrame(() => {
                checkAndRestore();
                restoreRaf2 = requestAnimationFrame(checkAndRestore);
            });
            restoreTimeout1 = setTimeout(checkAndRestore, 55);
            restoreTimeout2 = setTimeout(checkAndRestore, 140);
        };

        const blurIframeIfStolen = () => {
            const now = performance.now();
            const active = document.activeElement;
            if (!active || !containerEl.contains(active)) return false;

            const userFocusedElsewhere =
                !isSectionVisible ||
                isOutsideHolding ||
                now - lastTouchSwipeAt < 500 ||
                now - lastWheelAt < 500;

            if (
                userFocusedElsewhere &&
                now - lastDirectSpotifyInteractAt > 800
            ) {
                active.blur?.();
                window.focus();
                return true;
            }
            return false;
        };

        const isPlausibleUserStep = (step, now, strictGuard) => {
            const absStep = Math.abs(step);
            if (absStep === 0) return true;

            if (now < allowSmoothNavUntil) return true;
            if (now - lastKeyNavAt < 260) return true;
            if (now - lastScrollbarDragAt < 320) return true;

            if (isStationaryOutsideHold()) {
                return absStep <= 12;
            }

            const recentWheel = now - lastWheelAt < 180;
            const recentTouch =
                now - lastTouchSwipeAt < 180 || now < touchFlingUntil;

            if (!recentWheel && !recentTouch) {
                return strictGuard ? absStep <= 16 : absStep <= 45;
            }

            if (recentWheel) {
                const dirOk =
                    lastWheelDir === 0 || Math.sign(step) === lastWheelDir;
                const maxWheel = strictGuard
                    ? Math.max(135, lastWheelStep * 2.2)
                    : Math.max(240, lastWheelStep * 2.5);
                if (dirOk && absStep <= maxWheel) return true;
            }

            if (recentTouch) {
                const dirOk =
                    lastTouchSwipeDir === 0 ||
                    Math.sign(step) === lastTouchSwipeDir;
                const maxTouch = strictGuard
                    ? Math.max(115, lastTouchStep * 2.4 + 40)
                    : Math.max(220, lastTouchStep * 2.8 + 80);
                if (dirOk && absStep <= maxTouch) return true;
            }

            return false;
        };

        const handleWindowScroll = () => {
            const now = performance.now();
            const y = window.scrollY;

            if (now < allowSmoothNavUntil) {
                safeScrollY = y;
                guardTargetY = y;
                guardUntil = 0;
                recordHistory(now, y);
                return;
            }

            if (now < guardUntil) {
                const step = y - guardTargetY;
                if (isPlausibleUserStep(step, now, true)) {
                    guardTargetY = y;
                    safeScrollY = y;
                    recordHistory(now, y);
                } else {
                    enforceTargetY(guardTargetY);
                }
                return;
            }

            const delta = y - safeScrollY;
            if (
                Math.abs(delta) > 12 &&
                !isPlausibleUserStep(delta, now, false) &&
                (isStationaryOutsideHold() ||
                    isEmbedJumpingIntoView(safeScrollY, y) ||
                    Math.abs(delta) > 220)
            ) {
                blurIframeIfStolen();
                lockAndRestore(safeScrollY, 1000);
                return;
            }

            safeScrollY = y;
            recordHistory(now, y);
        };

        const handleSpotifyGuardEvent = () => {
            const now = performance.now();
            if (now < allowSmoothNavUntil) return;
            const restoreY = getPreJumpScrollY();
            blurIframeIfStolen();
            lockAndRestore(restoreY, 1100);
        };

        const handleWindowBlur = () => {
            setTimeout(() => {
                if (blurIframeIfStolen()) {
                    const restoreY = getPreJumpScrollY();
                    lockAndRestore(restoreY, 1100);
                }
            }, 0);
        };

        const setOutsideHold = (holding) => {
            if (outsideHoldReleaseTimer) {
                clearTimeout(outsideHoldReleaseTimer);
                outsideHoldReleaseTimer = 0;
            }
            if (holding) {
                isOutsideHolding = true;
                syncInert();
            } else {
                outsideHoldReleaseTimer = setTimeout(() => {
                    isOutsideHolding = false;
                    syncInert();
                }, 160);
            }
        };

        const onDocTouchStart = (e) => {
            if (isInsideWrapper(e.target)) {
                lastDirectSpotifyInteractAt = performance.now();
                return;
            }
            for (const touch of e.changedTouches) {
                activeOutsideTouches.set(touch.identifier, {
                    startY: touch.clientY,
                    lastY: touch.clientY,
                    maxMove: 0,
                });
            }
            setOutsideHold(true);
        };

        const onDocTouchMove = (e) => {
            const now = performance.now();
            for (const touch of e.changedTouches) {
                const prev = activeOutsideTouches.get(touch.identifier);
                if (!prev) continue;
                const dy = touch.clientY - prev.lastY;
                prev.lastY = touch.clientY;
                prev.maxMove = Math.max(
                    prev.maxMove,
                    Math.abs(touch.clientY - prev.startY),
                );
                if (prev.maxMove >= 8 && Math.abs(dy) >= 1) {
                    lastTouchSwipeAt = now;
                    lastTouchSwipeDir = Math.sign(-dy);
                    lastTouchStep = Math.abs(dy);
                }
            }
        };

        const onDocTouchEnd = (e) => {
            const now = performance.now();
            let hadSwipe = false;
            for (const touch of e.changedTouches) {
                const prev = activeOutsideTouches.get(touch.identifier);
                if (prev && prev.maxMove >= 10) {
                    hadSwipe = true;
                }
                activeOutsideTouches.delete(touch.identifier);
            }
            if (hadSwipe && lastTouchSwipeDir !== 0) {
                touchFlingUntil = now + 950;
            }
            if (activeOutsideTouches.size === 0 && !outsidePointerDown) {
                setOutsideHold(false);
            }
        };

        const onDocPointerDown = (e) => {
            if (e.pointerType === 'touch') return;
            if (isInsideWrapper(e.target)) {
                lastDirectSpotifyInteractAt = performance.now();
                return;
            }
            if (isOnScrollbar(e.clientX)) {
                lastScrollbarDragAt = performance.now();
                return;
            }
            outsidePointerDown = true;
            setOutsideHold(true);
        };

        const onDocPointerUp = (e) => {
            if (e.pointerType === 'touch') return;
            outsidePointerDown = false;
            if (activeOutsideTouches.size === 0) {
                setOutsideHold(false);
            }
        };

        const onDocWheel = (e) => {
            const now = performance.now();
            lastWheelAt = now;
            if (e.deltaY !== 0) {
                lastWheelDir = Math.sign(e.deltaY);
                lastWheelStep = Math.min(360, Math.abs(e.deltaY));
            }
        };

        const onDocKeyDown = (e) => {
            if (
                e.key === 'ArrowUp' ||
                e.key === 'ArrowDown' ||
                e.key === 'PageUp' ||
                e.key === 'PageDown' ||
                e.key === 'Home' ||
                e.key === 'End' ||
                e.key === ' '
            ) {
                lastKeyNavAt = performance.now();
            }
        };

        const onDocClick = (e) => {
            const target = e.target;
            if (!(target instanceof Element)) return;
            const navTrigger = target.closest(
                'a[href^="#"], [data-scroll-nav]',
            );
            if (navTrigger && !isInsideWrapper(navTrigger)) {
                allowSmoothNavUntil = performance.now() + 1400;
                guardUntil = 0;
                clearScheduledRestores();
            }
        };

        const onNavScrollEvent = () => {
            allowSmoothNavUntil = performance.now() + 1400;
            guardUntil = 0;
            clearScheduledRestores();
        };

        let observer = null;
        if ('IntersectionObserver' in window) {
            observer = new IntersectionObserver(
                (entries) => {
                    isSectionVisible = entries.some(
                        (entry) => entry.isIntersecting,
                    );
                    syncInert();
                },
                { threshold: 0 },
            );
            observer.observe(wrapperEl);
        }

        document.addEventListener('touchstart', onDocTouchStart, {
            capture: true,
            passive: true,
        });
        document.addEventListener('touchmove', onDocTouchMove, {
            capture: true,
            passive: true,
        });
        document.addEventListener('touchend', onDocTouchEnd, {
            capture: true,
            passive: true,
        });
        document.addEventListener('touchcancel', onDocTouchEnd, {
            capture: true,
            passive: true,
        });
        document.addEventListener('pointerdown', onDocPointerDown, {
            capture: true,
            passive: true,
        });
        document.addEventListener('pointerup', onDocPointerUp, {
            capture: true,
            passive: true,
        });
        document.addEventListener('pointercancel', onDocPointerUp, {
            capture: true,
            passive: true,
        });
        document.addEventListener('wheel', onDocWheel, {
            capture: true,
            passive: true,
        });
        document.addEventListener('keydown', onDocKeyDown, {
            capture: true,
            passive: true,
        });
        document.addEventListener('click', onDocClick, { capture: true });

        window.addEventListener('scroll', handleWindowScroll, {
            passive: true,
        });
        window.addEventListener('blur', handleWindowBlur);
        window.addEventListener(
            'spotify:scroll-guard',
            handleSpotifyGuardEvent,
        );
        window.addEventListener('portfolio:nav-scroll', onNavScrollEvent);
        window.addEventListener('popstate', onNavScrollEvent);

        return () => {
            clearScheduledRestores();
            if (outsideHoldReleaseTimer) clearTimeout(outsideHoldReleaseTimer);
            observer?.disconnect();
            if ('inert' in containerEl) {
                containerEl.inert = false;
            }
            document.removeEventListener('touchstart', onDocTouchStart, {
                capture: true,
            });
            document.removeEventListener('touchmove', onDocTouchMove, {
                capture: true,
            });
            document.removeEventListener('touchend', onDocTouchEnd, {
                capture: true,
            });
            document.removeEventListener('touchcancel', onDocTouchEnd, {
                capture: true,
            });
            document.removeEventListener('pointerdown', onDocPointerDown, {
                capture: true,
            });
            document.removeEventListener('pointerup', onDocPointerUp, {
                capture: true,
            });
            document.removeEventListener('pointercancel', onDocPointerUp, {
                capture: true,
            });
            document.removeEventListener('wheel', onDocWheel, {
                capture: true,
            });
            document.removeEventListener('keydown', onDocKeyDown, {
                capture: true,
            });
            document.removeEventListener('click', onDocClick, {
                capture: true,
            });

            window.removeEventListener('scroll', handleWindowScroll);
            window.removeEventListener('blur', handleWindowBlur);
            window.removeEventListener(
                'spotify:scroll-guard',
                handleSpotifyGuardEvent,
            );
            window.removeEventListener(
                'portfolio:nav-scroll',
                onNavScrollEvent,
            );
            window.removeEventListener('popstate', onNavScrollEvent);
        };
    }, []);

    // Defer loading Spotify's heavy third-party embed script until near viewport or explicit play toggle
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
                { rootMargin: '650px 0px' },
            );
            observer.observe(target);
        }

        window.addEventListener('spotify:request-load', triggerLoad);
        return () => {
            observer?.disconnect();
            window.removeEventListener('spotify:request-load', triggerLoad);
        };
    }, [shouldLoadEmbed]);

    // Initialize Spotify IFrame API Controller
    useEffect(() => {
        if (!shouldLoadEmbed) {
            return;
        }

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
                {/* Main Glassmorphic Card Container matching reference */}
                <motion.div
                    initial={{ opacity: 0, y: 35, scale: 0.98 }}
                    whileInView={{ opacity: 1, y: 0, scale: 1 }}
                    viewport={{ once: true, amount: 0.2 }}
                    transition={{ duration: 0.7, ease: [0.16, 1, 0.3, 1] }}
                    className="relative overflow-hidden rounded-3xl border border-white/10 bg-[#0d1322]/60 p-8 shadow-2xl backdrop-blur-xl sm:p-10 lg:p-12"
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
                                delay: 0.15,
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
                            transition={{
                                duration: 0.6,
                                delay: 0.25,
                                ease: [0.16, 1, 0.3, 1],
                            }}
                            className="w-full lg:col-span-6"
                        >
                            <div
                                ref={embedWrapperRef}
                                className="relative min-h-[352px] w-full max-w-full overflow-hidden rounded-2xl border border-white/10 bg-black/40 shadow-2xl [contain:paint_layout] [overflow-anchor:none]"
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
                                    className="h-full min-h-[352px] w-full [transform:translate3d(0,0,0)] [contain:strict] [overflow-anchor:none]"
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
