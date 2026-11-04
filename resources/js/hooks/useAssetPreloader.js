import { useState, useEffect, useCallback, useRef } from 'react';

/**
 * Hook to preload critical visual and 3D assets with progress percentage tracking,
 * failure detection, retry mechanism, and skip/continue fallback.
 *
 * @param {string[]} assetUrls Array of image/model URLs to monitor.
 */
export function useAssetPreloader(
    assetUrls = [
        '/assets/images/lanyard-card.png',
        '/assets/images/lanyard-strap.png',
    ],
) {
    const [progress, setProgress] = useState(0);
    const [isLoaded, setIsLoaded] = useState(false);
    const [failedAssets, setFailedAssets] = useState([]);
    const [retryCount, setRetryCount] = useState(0);
    const isCancelledRef = useRef(false);

    const loadAsset = (url) => {
        return new Promise((resolve, reject) => {
            const img = new Image();
            if (/^https?:\/\/|^\/\//i.test(url)) {
                img.crossOrigin = 'anonymous';
            }
            img.onload = () => resolve(url);
            img.onerror = () => reject(url);
            img.src = url;
        });
    };

    const startPreloading = useCallback(async () => {
        isCancelledRef.current = false;
        setProgress(15);
        setFailedAssets([]);

        const totalAssets = assetUrls.length;
        let loadedCount = 0;
        const failedList = [];

        const promises = assetUrls.map(async (url) => {
            try {
                await loadAsset(url);
                loadedCount += 1;
                if (!isCancelledRef.current) {
                    const calculated = Math.min(
                        95,
                        Math.round((loadedCount / totalAssets) * 85) + 15,
                    );
                    setProgress(calculated);
                }
            } catch {
                failedList.push(url);
            }
        });

        await Promise.all(promises);

        if (isCancelledRef.current) return;

        if (failedList.length > 0) {
            setFailedAssets(failedList);
            // Keep progress visible so user can retry or continue
            setProgress((prev) => Math.min(prev, 90));
        } else {
            // Smoothly advance to 100%
            setProgress(100);
            setTimeout(() => {
                if (!isCancelledRef.current) {
                    setIsLoaded(true);
                }
            }, 500);
        }
    }, [assetUrls, retryCount]);

    useEffect(() => {
        void startPreloading();
        return () => {
            isCancelledRef.current = true;
        };
    }, [startPreloading]);

    const retry = useCallback(() => {
        setRetryCount((c) => c + 1);
    }, []);

    const skip = useCallback(() => {
        setProgress(100);
        setIsLoaded(true);
    }, []);

    return {
        progress,
        isLoaded,
        failedAssets,
        hasFailed: failedAssets.length > 0,
        retry,
        skip,
    };
}
