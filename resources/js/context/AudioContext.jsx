import React, {
    createContext,
    useContext,
    useState,
    useRef,
    useCallback,
    useMemo,
    useEffect,
} from 'react';

const AudioStateContext = createContext({
    isPlaying: false,
    isSpotifyReady: false,
});

const AudioDispatchContext = createContext({
    toggleAudio: () => {},
    registerSpotifyController: () => {},
    unregisterSpotifyController: () => {},
});

export function AudioProvider({ children }) {
    const [isPlaying, setIsPlaying] = useState(false);
    const [isSpotifyReady, setIsSpotifyReady] = useState(false);
    const spotifyControllerRef = useRef(null);
    const pendingActionRef = useRef(null);
    const lastPlayingUriRef = useRef(null);
    const lastIsPausedRef = useRef(null);

    // Web Audio API Ambient Fallback
    const audioCtxRef = useRef(null);
    const masterGainRef = useRef(null);
    const activeNodesRef = useRef([]);
    const timerRef = useRef(null);

    const initFallbackAudio = useCallback(() => {
        if (!audioCtxRef.current) {
            const AudioContextClass =
                window.AudioContext || window.webkitAudioContext;
            if (!AudioContextClass) return null;
            const ctx = new AudioContextClass();
            const masterGain = ctx.createGain();
            masterGain.gain.setValueAtTime(0, ctx.currentTime);
            masterGain.connect(ctx.destination);
            audioCtxRef.current = ctx;
            masterGainRef.current = masterGain;
        }
        return audioCtxRef.current;
    }, []);

    const playFallbackAmbient = useCallback(() => {
        const ctx = initFallbackAudio();
        if (!ctx) return;

        if (ctx.state === 'suspended') {
            void ctx.resume();
        }

        const chords = [
            [130.81, 196.0, 246.94, 293.66, 392.0],
            [174.61, 220.0, 261.63, 329.63, 440.0],
            [146.83, 220.0, 261.63, 329.63, 440.0],
            [196.0, 246.94, 293.66, 392.0, 493.88],
        ];

        let chordIndex = 0;

        const scheduleChord = () => {
            if (!masterGainRef.current) return;
            const now = ctx.currentTime;
            const currentChord = chords[chordIndex % chords.length];
            chordIndex++;

            const filter = ctx.createBiquadFilter();
            filter.type = 'lowpass';
            filter.frequency.setValueAtTime(450, now);
            filter.Q.setValueAtTime(1.2, now);

            const chordGain = ctx.createGain();
            chordGain.gain.setValueAtTime(0.001, now);
            chordGain.gain.exponentialRampToValueAtTime(0.06, now + 2.5);
            chordGain.gain.exponentialRampToValueAtTime(0.001, now + 5.8);

            chordGain.connect(filter);
            filter.connect(masterGainRef.current);

            currentChord.forEach((freq, i) => {
                const osc = ctx.createOscillator();
                osc.type = i % 2 === 0 ? 'sine' : 'triangle';
                osc.detune.setValueAtTime((i - 2) * 4, now);
                osc.frequency.setValueAtTime(freq, now);
                osc.connect(chordGain);
                osc.start(now);
                osc.stop(now + 6);
                activeNodesRef.current.push({ osc, chordGain, filter });
            });

            setTimeout(() => {
                activeNodesRef.current = activeNodesRef.current.filter(
                    (node) => node.chordGain !== chordGain,
                );
            }, 6500);

            timerRef.current = setTimeout(scheduleChord, 5200);
        };

        const now = ctx.currentTime;
        masterGainRef.current.gain.cancelScheduledValues(now);
        masterGainRef.current.gain.setValueAtTime(
            masterGainRef.current.gain.value,
            now,
        );
        masterGainRef.current.gain.linearRampToValueAtTime(0.8, now + 1.2);

        scheduleChord();
    }, [initFallbackAudio]);

    const stopFallbackAmbient = useCallback(() => {
        if (timerRef.current) {
            clearTimeout(timerRef.current);
            timerRef.current = null;
        }

        if (audioCtxRef.current && masterGainRef.current) {
            const now = audioCtxRef.current.currentTime;
            masterGainRef.current.gain.cancelScheduledValues(now);
            masterGainRef.current.gain.setValueAtTime(
                masterGainRef.current.gain.value,
                now,
            );
            masterGainRef.current.gain.linearRampToValueAtTime(
                0.001,
                now + 0.6,
            );
        }
    }, []);

    // Register controller from SpotifySection
    const registerSpotifyController = useCallback((controller) => {
        if (!controller) return;
        spotifyControllerRef.current = controller;
        setIsSpotifyReady(true);

        try {
            controller.addListener('playback_update', (e) => {
                if (e && e.data) {
                    const uriChanged =
                        Boolean(e.data.playingURI) &&
                        e.data.playingURI !== lastPlayingUriRef.current;
                    const pausedChanged =
                        typeof e.data.isPaused === 'boolean' &&
                        e.data.isPaused !== lastIsPausedRef.current;

                    if (uriChanged) {
                        if (lastPlayingUriRef.current !== null) {
                            window.dispatchEvent(
                                new CustomEvent('spotify:scroll-guard', {
                                    detail: { reason: 'track-change' },
                                }),
                            );
                        }
                        lastPlayingUriRef.current = e.data.playingURI;
                    }

                    if (pausedChanged) {
                        if (lastIsPausedRef.current !== null) {
                            window.dispatchEvent(
                                new CustomEvent('spotify:scroll-guard', {
                                    detail: { reason: 'playback-toggle' },
                                }),
                            );
                        }
                        lastIsPausedRef.current = e.data.isPaused;
                        const nextPlaying = !e.data.isPaused;
                        setIsPlaying((prev) =>
                            prev === nextPlaying ? prev : nextPlaying,
                        );
                    }
                }
            });
        } catch (err) {
            console.warn('Spotify playback_update listener error:', err);
        }

        // If user already clicked play before controller was ready
        if (pendingActionRef.current === 'play') {
            pendingActionRef.current = null;
            window.dispatchEvent(new CustomEvent('spotify:scroll-guard'));
            try {
                controller.togglePlay();
            } catch (err) {
                console.warn('Error executing pending play:', err);
            }
        }
    }, []);

    const unregisterSpotifyController = useCallback(() => {
        spotifyControllerRef.current = null;
        lastPlayingUriRef.current = null;
        lastIsPausedRef.current = null;
        setIsSpotifyReady(false);
    }, []);

    // Unified toggle function called by Navbar sound button
    const toggleAudio = useCallback(() => {
        window.dispatchEvent(new CustomEvent('spotify:request-load'));

        if (spotifyControllerRef.current) {
            window.dispatchEvent(new CustomEvent('spotify:scroll-guard'));
            try {
                spotifyControllerRef.current.togglePlay();
            } catch (err) {
                console.warn('Error toggling Spotify playback:', err);
            }
            return;
        }

        // If Spotify controller is not ready yet, queue the play action or use fallback
        if (!isSpotifyReady) {
            setIsPlaying((prev) => {
                const next = !prev;
                if (next) {
                    pendingActionRef.current = 'play';
                } else {
                    pendingActionRef.current = null;
                    stopFallbackAmbient();
                }
                return next;
            });
        }
    }, [isSpotifyReady, stopFallbackAmbient]);

    // Cleanup fallback audio context on unmount
    useEffect(() => {
        return () => {
            if (timerRef.current) {
                clearTimeout(timerRef.current);
            }
            if (audioCtxRef.current && audioCtxRef.current.state !== 'closed') {
                void audioCtxRef.current.close();
            }
        };
    }, []);

    // Separate state and dispatch to prevent re-rendering consumers of dispatch
    const stateValue = useMemo(
        () => ({
            isPlaying,
            isSpotifyReady,
        }),
        [isPlaying, isSpotifyReady],
    );

    const dispatchValue = useMemo(
        () => ({
            toggleAudio,
            registerSpotifyController,
            unregisterSpotifyController,
        }),
        [toggleAudio, registerSpotifyController, unregisterSpotifyController],
    );

    return (
        <AudioDispatchContext.Provider value={dispatchValue}>
            <AudioStateContext.Provider value={stateValue}>
                {children}
            </AudioStateContext.Provider>
        </AudioDispatchContext.Provider>
    );
}

export function useAudioState() {
    return useContext(AudioStateContext);
}

export function useAudioDispatch() {
    return useContext(AudioDispatchContext);
}

export function useAudio() {
    const state = useAudioState();
    const dispatch = useAudioDispatch();
    return useMemo(() => ({ ...state, ...dispatch }), [state, dispatch]);
}
