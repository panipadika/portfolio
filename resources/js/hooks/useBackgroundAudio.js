import { useAudio } from '../context/AudioContext';

/**
 * useBackgroundAudio
 * Unified audio hook delegating to AudioContext for synchronized
 * Spotify IFrame Player API playback and Navbar sound toggle.
 */
export function useBackgroundAudio() {
    return useAudio();
}
