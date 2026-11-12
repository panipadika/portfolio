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
    isAudioPending: false,
    autoplayBlocked: false,
});

const AudioDispatchContext = createContext({
    toggleAudio: () => {},
    dismissAutoplayHint: () => {},
    registerSpotifyController: () => {},
    unregisterSpotifyController: () => {},
});

export function AudioProvider({ children }) {
    const [isPlaying, setIsPlaying] = useState(false);
    const [isSpotifyReady, setIsSpotifyReady] = useState(false);
    const [isAudioPending, setIsAudioPending] = useState(false);
    const [autoplayBlocked, setAutoplayBlocked] = useState(false);
    const spotifyControllerRef = useRef(null);
    const pendingActionRef = useRef(null);
    const playVerifyTimerRef = useRef(null);
    const hintTimerRef = useRef(null);
    const isPlayingRef = useRef(false);
    isPlayingRef.current = isPlaying;

    const clearVerificationTimers = useCallback(() => {
        if (playVerifyTimerRef.current) {
            clearTimeout(playVerifyTimerRef.current);
            playVerifyTimerRef.current = null;
        }
        if (hintTimerRef.current) {
            clearTimeout(hintTimerRef.current);
            hintTimerRef.current = null;
        }
    }, []);

    const dismissAutoplayHint = useCallback(() => {
        setAutoplayBlocked(false);
    }, []);

    const startPlayVerification = useCallback(() => {
        clearVerificationTimers();
        playVerifyTimerRef.current = setTimeout(() => {
            playVerifyTimerRef.current = null;
            setIsAudioPending(false);
            if (!isPlayingRef.current) {
                setAutoplayBlocked(true);
                hintTimerRef.current = setTimeout(() => {
                    setAutoplayBlocked(false);
                }, 4000);
            }
        }, 1600);
    }, [clearVerificationTimers]);

    // Register controller from SpotifySection once ready
    const registerSpotifyController = useCallback(
        (controller) => {
            if (!controller) return;
            spotifyControllerRef.current = controller;
            setIsSpotifyReady(true);

            try {
                controller.addListener('playback_update', (e) => {
                    if (e && e.data && typeof e.data.isPaused === 'boolean') {
                        const nextPlaying = !e.data.isPaused;
                        if (nextPlaying) {
                            clearVerificationTimers();
                            setIsAudioPending(false);
                            setAutoplayBlocked(false);
                        }
                        setIsPlaying((prev) =>
                            prev === nextPlaying ? prev : nextPlaying,
                        );
                    }
                });
            } catch (err) {
                console.warn('Spotify playback_update listener error:', err);
            }

            // If user already clicked play before controller was ready
            if (pendingActionRef.current === 'play') {
                pendingActionRef.current = null;
                try {
                    controller.togglePlay();
                    startPlayVerification();
                } catch (err) {
                    console.warn('Error executing pending play:', err);
                    setIsAudioPending(false);
                }
            }
        },
        [clearVerificationTimers, startPlayVerification],
    );

    const unregisterSpotifyController = useCallback(() => {
        clearVerificationTimers();
        spotifyControllerRef.current = null;
        pendingActionRef.current = null;
        setIsAudioPending(false);
        setIsPlaying(false);
        setIsSpotifyReady(false);
    }, [clearVerificationTimers]);

    // Unified toggle function called by Navbar sound button
    const toggleAudio = useCallback(() => {
        window.dispatchEvent(new CustomEvent('spotify:request-load'));
        setAutoplayBlocked(false);

        if (spotifyControllerRef.current) {
            try {
                const intendingPlay = !isPlayingRef.current;
                spotifyControllerRef.current.togglePlay();
                if (intendingPlay) {
                    setIsAudioPending(true);
                    startPlayVerification();
                } else {
                    clearVerificationTimers();
                    setIsAudioPending(false);
                }
            } catch (err) {
                console.warn('Error toggling Spotify playback:', err);
                setIsAudioPending(false);
            }
            return;
        }

        // Controller is still booting; queue play once ready (never falsify isPlaying=true)
        if (pendingActionRef.current === 'play') {
            pendingActionRef.current = null;
            clearVerificationTimers();
            setIsAudioPending(false);
        } else {
            pendingActionRef.current = 'play';
            setIsAudioPending(true);
            clearVerificationTimers();
            playVerifyTimerRef.current = setTimeout(() => {
                if (
                    !spotifyControllerRef.current &&
                    pendingActionRef.current === 'play'
                ) {
                    pendingActionRef.current = null;
                    setIsAudioPending(false);
                    setAutoplayBlocked(true);
                    hintTimerRef.current = setTimeout(() => {
                        setAutoplayBlocked(false);
                    }, 4000);
                }
            }, 4500);
        }
    }, [clearVerificationTimers, startPlayVerification]);

    // Cleanup verification timers on unmount
    useEffect(() => {
        return () => {
            clearVerificationTimers();
        };
    }, [clearVerificationTimers]);

    // Separate state and dispatch to prevent re-rendering consumers of dispatch
    const stateValue = useMemo(
        () => ({
            isPlaying,
            isSpotifyReady,
            isAudioPending,
            autoplayBlocked,
        }),
        [isPlaying, isSpotifyReady, isAudioPending, autoplayBlocked],
    );

    const dispatchValue = useMemo(
        () => ({
            toggleAudio,
            dismissAutoplayHint,
            registerSpotifyController,
            unregisterSpotifyController,
        }),
        [
            toggleAudio,
            dismissAutoplayHint,
            registerSpotifyController,
            unregisterSpotifyController,
        ],
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
