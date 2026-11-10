import { useState, useEffect, useCallback, useRef } from 'react';

/**
 * Hook to preload critical visual and 3D assets with progress percentage tracking,
 * failure detection, retry mechanism, and skip/continue fallback.
 *
 * @param {string[]} assetUrls Array of image/model URLs to monitor.
 * @param {number} timeoutMs Maximum wait time before triggering fallback controls (default: 6000ms).
 */
export function useAssetPreloader(
    assetUrls = ['/card-front.webp', '/card-back.webp', '/band.png'],
    timeoutMs = 6000,
) {
    const [progress, setProgress] = useState(0);
    const [isLoaded, setIsLoaded] = useState(false);
    const [failedAssets, setFailedAssets] = useState([]);
    const [retryCount, setRetryCount] = useState(0);
    const isCancelledRef = useRef(false);
    const timeoutRef = useRef(null);

    const loadAsset = (url) => {
        return new Promise((resolve, reject) => {
            const img = new Image();
            let timer = null;

            const cleanup = () => {
                if (timer) clearTimeout(timer);
                img.onload = null;
                img.onerror = null;
            };

            timer = setTimeout(() => {
                cleanup();
                reject(new Error(`Timeout loading asset: ${url}`));
            }, 5000);

            if (/^https?:\/\/|^\/\//i.test(url)) {
                img.crossOrigin = 'anonymous';
            }

            img.onload = () => {
                cleanup();
                resolve(url);
            };

            img.onerror = () => {
                cleanup();
                reject(new Error(`Failed loading asset: ${url}`));
            };

            img.src = url;
        });
    };

    const startPreloading = useCallback(async () => {
        isCancelledRef.current = false;
        setProgress(15);
        setFailedAssets([]);

        if (timeoutRef.current) {
            clearTimeout(timeoutRef.current);
        }

        // Global safety timeout (6s) ensures preloader never hangs indefinitely
        timeoutRef.current = setTimeout(() => {
            if (!isCancelledRef.current) {
                setIsLoaded((currentLoaded) => {
                    if (!currentLoaded) {
                        setFailedAssets((prev) =>
                            prev.length > 0
                                ? prev
                                : ['Waktu pemuatan melebihi 6 detik'],
                        );
                        setProgress((p) => Math.min(p, 90));
                    }
                    return currentLoaded;
                });
            }
        }, timeoutMs);

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

        if (timeoutRef.current) {
            clearTimeout(timeoutRef.current);
            timeoutRef.current = null;
        }

        if (failedList.length > 0) {
            setFailedAssets(failedList);
            setProgress((prev) => Math.min(prev, 90));
        } else {
            setProgress(100);
            setTimeout(() => {
                if (!isCancelledRef.current) {
                    setIsLoaded(true);
                }
            }, 500);
        }
    }, [assetUrls, timeoutMs, retryCount]);

    useEffect(() => {
        void startPreloading();
        return () => {
            isCancelledRef.current = true;
            if (timeoutRef.current) {
                clearTimeout(timeoutRef.current);
            }
        };
    }, [startPreloading]);

    const retry = useCallback(() => {
        setRetryCount((c) => c + 1);
    }, []);

    const skip = useCallback(() => {
        if (timeoutRef.current) {
            clearTimeout(timeoutRef.current);
        }
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
