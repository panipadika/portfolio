/**
 * Route local portfolio images through Laravel's cached WebP thumbnail endpoint
 * while leaving external URLs, SVGs, and already-small assets untouched.
 *
 * @param {string | null | undefined} src
 * @param {128 | 640 | 960 | 1280} width
 * @returns {string}
 */
export function optimizeImageSrc(src, width = 640) {
    if (!src || typeof src !== 'string') {
        return src || '';
    }

    if (
        src.startsWith('http://') ||
        src.startsWith('https://') ||
        src.startsWith('data:') ||
        src.startsWith('/media-opt/')
    ) {
        return src;
    }

    const clean = src.replace(/^\/+/, '').split('?')[0];
    if (!/\.(png|jpe?g|webp)$/i.test(clean)) {
        return src;
    }

    const isAllowedLocal =
        clean.startsWith('storage/') ||
        clean.startsWith('assets/images/') ||
        clean === 'logo-pp.png' ||
        clean === 'profile-transparent.png';

    if (!isAllowedLocal) {
        return src;
    }

    return `/media-opt/${width}/${clean}`;
}
