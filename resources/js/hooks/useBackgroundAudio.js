import { useState, useRef, useEffect, useCallback } from 'react';

/**
 * useBackgroundAudio
 * Provides soothing ambient background audio for coding / focus mode.
 * Uses Web Audio API synthesised pentatonic warm chords with smooth gain ramps,
 * ensuring zero external download dependencies and instant, reliable playback.
 */
export function useBackgroundAudio() {
    const [isPlaying, setIsPlaying] = useState(false);
    const audioCtxRef = useRef(null);
    const masterGainRef = useRef(null);
    const activeNodesRef = useRef([]);
    const timerRef = useRef(null);

    const initAudioContext = useCallback(() => {
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

    const playAmbientSoundscape = useCallback(() => {
        const ctx = initAudioContext();
        if (!ctx) return;

        if (ctx.state === 'suspended') {
            void ctx.resume();
        }

        // Frequencies for a warm, meditative pentatonic chord progression (Cmaj9 / Fmaj7)
        // Root notes and warm harmonics in Hz
        const chords = [
            [130.81, 196.0, 246.94, 293.66, 392.0], // C3, G3, B3, D4, G4
            [174.61, 220.0, 261.63, 329.63, 440.0], // F3, A3, C4, E4, A4
            [146.83, 220.0, 261.63, 329.63, 440.0], // D3, A3, C4, E4, A4
            [196.0, 246.94, 293.66, 392.0, 493.88], // G3, B3, D4, G4, B4
        ];

        let chordIndex = 0;

        const scheduleChord = () => {
            if (!masterGainRef.current) return;
            const now = ctx.currentTime;
            const currentChord = chords[chordIndex % chords.length];
            chordIndex++;

            // Create gentle oscillators for this chord with soft lowpass filter
            const filter = ctx.createBiquadFilter();
            filter.type = 'lowpass';
            filter.frequency.setValueAtTime(450, now);
            filter.Q.setValueAtTime(1.2, now);

            const chordGain = ctx.createGain();
            chordGain.gain.setValueAtTime(0.001, now);
            // Slow, soothing swell
            chordGain.gain.exponentialRampToValueAtTime(0.06, now + 2.5);
            chordGain.gain.exponentialRampToValueAtTime(0.001, now + 5.8);

            chordGain.connect(filter);
            filter.connect(masterGainRef.current);

            const oscs = currentChord.map((freq, i) => {
                const osc = ctx.createOscillator();
                osc.type = i % 2 === 0 ? 'sine' : 'triangle';
                // Subtle detune for lush analog warmth
                osc.detune.setValueAtTime((i - 2) * 4, now);
                osc.frequency.setValueAtTime(freq, now);
                osc.connect(chordGain);
                osc.start(now);
                osc.stop(now + 6);
                return osc;
            });

            activeNodesRef.current.push({ oscs, chordGain, filter });

            // Cleanup old nodes after chord ends
            setTimeout(() => {
                activeNodesRef.current = activeNodesRef.current.filter(
                    (node) => node.chordGain !== chordGain,
                );
            }, 6500);

            // Schedule next chord every 5.5s for seamless overlapping cross-fade
            timerRef.current = setTimeout(scheduleChord, 5200);
        };

        // Fade master gain up smoothly over 1.2s
        const now = ctx.currentTime;
        masterGainRef.current.gain.cancelScheduledValues(now);
        masterGainRef.current.gain.setValueAtTime(
            masterGainRef.current.gain.value,
            now,
        );
        masterGainRef.current.gain.linearRampToValueAtTime(0.8, now + 1.2);

        scheduleChord();
    }, [initAudioContext]);

    const stopAmbientSoundscape = useCallback(() => {
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
            // Smooth fade out
            masterGainRef.current.gain.linearRampToValueAtTime(
                0.001,
                now + 0.6,
            );
        }
    }, []);

    const toggleAudio = useCallback(() => {
        setIsPlaying((prev) => {
            const next = !prev;
            if (next) {
                playAmbientSoundscape();
            } else {
                stopAmbientSoundscape();
            }
            return next;
        });
    }, [playAmbientSoundscape, stopAmbientSoundscape]);

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

    return {
        isPlaying,
        toggleAudio,
    };
}
