import type { UrlMethodPair } from '@inertiajs/core';
import { router } from '@inertiajs/react';
import { usePasskeyVerify } from '@laravel/passkeys/react';
import { KeyRound } from 'lucide-react';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { Spinner } from '@/components/ui/spinner';

type Props = {
    routes?: {
        options: UrlMethodPair;
        submit: UrlMethodPair;
    };
    label?: string;
    loadingLabel?: string;
    separator?: string;
};

export default function PasskeyVerify({
    routes,
    label,
    loadingLabel,
    separator,
}: Props = {}) {
    const { verify, isLoading, error, isSupported } = usePasskeyVerify({
        ...(routes && {
            routes: {
                options: routes.options.url,
                submit: routes.submit.url,
            },
        }),
        onSuccess: (response) => {
            router.visit(response.redirect ?? '/dashboard');
        },
    });

    if (!isSupported) {
        return null;
    }

    return (
        <>
            <div className="grid gap-2">
                <Button
                    type="button"
                    variant="outline"
                    className="h-11 w-full cursor-pointer rounded-xl border-white/10 bg-white/[0.04] text-slate-100 transition-all hover:border-indigo-400/40 hover:bg-white/[0.08] hover:text-white"
                    onClick={verify}
                    disabled={isLoading}
                >
                    {isLoading ? (
                        <Spinner className="size-4" />
                    ) : (
                        <KeyRound className="size-4 text-cyan-400" />
                    )}
                    {isLoading
                        ? (loadingLabel ?? 'Authenticating...')
                        : (label ?? 'Sign in with a passkey')}
                </Button>
                {error && (
                    <InputError
                        message={error}
                        className="text-center text-xs text-rose-400"
                    />
                )}
            </div>

            <div className="my-5 flex items-center gap-3">
                <Separator className="flex-1 bg-white/10" />
                <span className="font-mono text-[10px] tracking-wider text-slate-400 uppercase">
                    {separator ?? 'Or continue with email'}
                </span>
                <Separator className="flex-1 bg-white/10" />
            </div>
        </>
    );
}
