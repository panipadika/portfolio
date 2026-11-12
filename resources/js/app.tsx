import { createInertiaApp } from '@inertiajs/react';
import { Toaster } from '@/components/ui/sonner';
import { TooltipProvider } from '@/components/ui/tooltip';
import { initializeTheme } from '@/hooks/use-appearance';
import AppLayout from '@/layouts/app-layout';
import AuthLayout from '@/layouts/auth-layout';
import SettingsLayout from '@/layouts/settings/layout';

declare global {
    interface Window {
        __portfolioActive?: boolean;
        __portfolioPathname?: string;
    }
}

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Intercept in-page project detail modal popstate BEFORE Inertia's EventHandler
// so pressing Back does not trigger preserveState: false (which remounts the SPA and stops Spotify).
if (typeof window !== 'undefined') {
    window.addEventListener(
        'popstate',
        (event: PopStateEvent) => {
            const state = event.state as Record<string, unknown> | null;
            if (
                window.__portfolioActive &&
                window.location.pathname === window.__portfolioPathname &&
                state &&
                typeof state === 'object' &&
                '__portfolioModal' in state
            ) {
                event.stopImmediatePropagation();
                window.dispatchEvent(new CustomEvent('portfolio:popstate'));
            }
        },
        true,
    );
}

void createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    layout: (name) => {
        switch (true) {
            case name === 'welcome':
                return null;
            case name.startsWith('auth/'):
                return AuthLayout;
            case name.startsWith('settings/'):
                return [AppLayout, SettingsLayout];
            default:
                return AppLayout;
        }
    },
    strictMode: true,
    withApp(app) {
        return (
            <TooltipProvider delayDuration={0}>
                {app}
                <Toaster />
            </TooltipProvider>
        );
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on load...
initializeTheme();
