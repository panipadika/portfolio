import { Lock, ShieldCheck } from 'lucide-react';
import type { AuthLayoutProps } from '@/types';

export default function AuthSimpleLayout({
    children,
    title,
    description,
}: AuthLayoutProps) {
    return (
        <div className="relative flex min-h-svh flex-col items-center justify-center overflow-hidden bg-[#0B0F17] px-4 py-6 text-slate-50 sm:px-6">
            {/* Ambient Deep-Space Backdrop */}
            <div
                className="pointer-events-none absolute inset-0 overflow-hidden"
                aria-hidden="true"
            >
                <div className="absolute -top-44 left-1/2 h-[420px] w-[720px] -translate-x-1/2 rounded-full bg-indigo-600/20 blur-[130px]" />
                <div className="absolute -bottom-36 -left-24 h-[340px] w-[340px] rounded-full bg-[#262f6b]/50 blur-[115px]" />
                <div className="absolute -right-20 -bottom-28 h-[320px] w-[320px] rounded-full bg-cyan-500/12 blur-[115px]" />
                <div className="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.03)_1px,transparent_1px)] [mask-image:radial-gradient(ellipse_75%_65%_at_50%_45%,#000_35%,transparent_100%)] bg-[size:44px_44px]" />
            </div>

            {/* Glassmorphism Main Card */}
            <div className="relative z-10 w-full max-w-[440px]">
                <div
                    className="pointer-events-none absolute -inset-px rounded-[26px] bg-gradient-to-b from-indigo-400/35 via-white/10 to-cyan-400/15 opacity-90 blur-[0.5px]"
                    aria-hidden="true"
                />

                <div className="relative overflow-hidden rounded-[25px] border border-white/10 bg-[#0d1322]/90 p-6 shadow-[0_24px_80px_rgba(0,0,0,0.7)] backdrop-blur-2xl sm:p-8">
                    {/* Prismatic Top Hairline & Soft Corner Sheen */}
                    <div
                        className="pointer-events-none absolute inset-x-10 top-0 h-px bg-gradient-to-r from-transparent via-[#7185e8]/80 via-cyan-400/60 to-transparent"
                        aria-hidden="true"
                    />
                    <div
                        className="pointer-events-none absolute -top-24 -right-24 h-48 w-48 rounded-full bg-indigo-500/10 blur-3xl"
                        aria-hidden="true"
                    />

                    {/* Brand Identity & Header */}
                    <div className="mb-7 flex flex-col items-center text-center">
                        <div className="mb-4 flex h-14 w-14 items-center justify-center overflow-hidden rounded-2xl border border-white/15 bg-black p-1.5 shadow-[0_10px_30px_rgba(99,102,241,0.28)]">
                            <img
                                src="/assets/images/admin-logo.jpg"
                                alt="Pani Padika Logo"
                                className="h-full w-full rounded-xl object-contain"
                            />
                        </div>

                        <span className="mb-2.5 inline-flex items-center gap-1.5 rounded-full border border-indigo-500/25 bg-indigo-500/10 px-3 py-0.5 font-mono text-[10px] font-semibold tracking-[0.16em] text-[#8ca0ff] uppercase">
                            <ShieldCheck className="size-3 text-cyan-400" />
                            Pani Padika • Control Center
                        </span>

                        <h1 className="font-display text-2xl font-bold tracking-tight text-white sm:text-[26px]">
                            {title}
                        </h1>
                        {description && (
                            <p className="mt-1.5 max-w-xs text-xs leading-relaxed text-slate-400 sm:text-[13px]">
                                {description}
                            </p>
                        )}
                    </div>

                    {children}
                </div>
            </div>

            {/* Subtle Footer Security Line */}
            <div className="relative z-10 mt-4 flex items-center justify-center gap-2 font-mono text-[11px] text-slate-500">
                <Lock className="size-3 text-slate-500" />
                <span>Protected Session • Pani Padika Portfolio</span>
            </div>
        </div>
    );
}
