import { Link } from '@inertiajs/react';
import { ArrowLeft, Lock, ShieldCheck } from 'lucide-react';
import { home } from '@/routes';
import type { AuthLayoutProps } from '@/types';

export default function AuthSimpleLayout({
    children,
    title,
    description,
}: AuthLayoutProps) {
    return (
        <div className="relative flex min-h-svh flex-col items-center justify-center overflow-hidden bg-[#0B0F17] px-4 py-10 text-slate-50 sm:px-6">
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

            {/* Top Action & Portal Status Bar */}
            <div className="relative z-10 mb-5 flex w-full max-w-[440px] items-center justify-between gap-3">
                <Link
                    href={home()}
                    className="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/[0.04] px-3.5 py-1.5 text-xs font-medium text-slate-300 backdrop-blur-md transition-all hover:border-indigo-400/40 hover:bg-white/[0.08] hover:text-white"
                >
                    <ArrowLeft className="size-3.5" />
                    <span>Kembali ke Portofolio</span>
                </Link>

                <span className="inline-flex items-center gap-2 rounded-full border border-emerald-500/25 bg-emerald-500/10 px-3 py-1 font-mono text-[10px] font-semibold tracking-wider text-emerald-300 uppercase backdrop-blur-md">
                    <span className="relative flex size-1.5">
                        <span className="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75" />
                        <span className="relative inline-flex size-1.5 rounded-full bg-emerald-400" />
                    </span>
                    Admin Portal
                </span>
            </div>

            {/* Glassmorphism Main Card */}
            <div className="relative z-10 w-full max-w-[440px]">
                <div
                    className="pointer-events-none absolute -inset-px rounded-[26px] bg-gradient-to-b from-indigo-400/35 via-white/10 to-cyan-400/15 opacity-90 blur-[0.5px]"
                    aria-hidden="true"
                />

                <div className="relative overflow-hidden rounded-[25px] border border-white/10 bg-[#0d1322]/90 p-7 shadow-[0_24px_80px_rgba(0,0,0,0.7)] backdrop-blur-2xl sm:p-9">
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
                        <Link
                            href={home()}
                            className="group mb-4 inline-flex flex-col items-center"
                            aria-label="Kembali ke beranda portofolio"
                        >
                            <div className="flex h-14 w-14 items-center justify-center overflow-hidden rounded-2xl border border-white/15 bg-black p-1.5 shadow-[0_10px_30px_rgba(99,102,241,0.28)] transition-all duration-300 group-hover:scale-105 group-hover:border-indigo-400/50">
                                <img
                                    src="/assets/images/admin-logo.jpg"
                                    alt="Pani Padika Logo"
                                    className="h-full w-full rounded-xl object-contain"
                                />
                            </div>
                        </Link>

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
            <div className="relative z-10 mt-6 flex items-center justify-center gap-2 font-mono text-[11px] text-slate-500">
                <Lock className="size-3 text-slate-500" />
                <span>Protected Session • Pani Padika Portfolio</span>
            </div>
        </div>
    );
}
