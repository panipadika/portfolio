import React from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { AlertCircle, RefreshCw, ArrowRight } from 'lucide-react';

export default function Preloader({
    progress = 0,
    isLoaded = false,
    failedCount = 0,
    onRetry,
    onSkip,
}) {
    return (
        <AnimatePresence>
            {!isLoaded && (
                <motion.div
                    key="preloader"
                    initial={{ opacity: 1 }}
                    exit={{
                        opacity: 0,
                        scale: 0.98,
                        transition: { duration: 0.6, ease: [0.16, 1, 0.3, 1] },
                    }}
                    className="fixed inset-0 z-50 flex flex-col items-center justify-center overflow-hidden bg-[#0B0F17] text-slate-50 select-none"
                >
                    {/* Subtle background glow */}
                    <div className="pointer-events-none absolute h-[500px] w-[500px] rounded-full bg-gradient-to-r from-indigo-500/10 to-cyan-500/10 blur-[120px]" />

                    <div className="relative z-10 flex w-full max-w-md flex-col items-center px-6">
                        {/* Logo / Monogram */}
                        <motion.div
                            initial={{ scale: 0.8, opacity: 0 }}
                            animate={{ scale: 1, opacity: 1 }}
                            transition={{ duration: 0.5 }}
                            className="mb-8 flex items-center gap-3"
                        >
                            <div className="h-12 w-12 rounded-2xl bg-gradient-to-tr from-indigo-600 to-cyan-500 p-0.5 shadow-[0_0_25px_rgba(99,102,241,0.4)]">
                                <div className="font-display flex h-full w-full items-center justify-center rounded-[14px] bg-[#0B0F17] text-xl font-extrabold text-white">
                                    PP
                                </div>
                            </div>
                            <div>
                                <span className="font-display block text-lg font-bold tracking-tight">
                                    Pani Padika
                                </span>
                                <span className="font-mono text-xs text-slate-400">
                                    portfolio.v2
                                </span>
                            </div>
                        </motion.div>

                        {/* Percentage Display */}
                        <div className="mb-6 text-center">
                            <span className="font-mono text-6xl font-extrabold tracking-tighter text-white sm:text-7xl">
                                {progress}%
                            </span>
                            <p className="mt-2 font-mono text-xs tracking-wider text-slate-400 uppercase sm:text-sm">
                                {failedCount > 0
                                    ? 'Menunggu tanggapan pengguna...'
                                    : 'Memuat aset antarmuka 3D...'}
                            </p>
                        </div>

                        {/* Progress Bar Container */}
                        <div className="mb-6 h-2 w-full overflow-hidden rounded-full border border-white/10 bg-white/5 p-0.5 shadow-inner">
                            <motion.div
                                className="h-full rounded-full bg-gradient-to-r from-indigo-500 via-cyan-400 to-indigo-500 shadow-[0_0_15px_#6366F1]"
                                style={{ width: `${progress}%` }}
                                transition={{ ease: 'easeOut', duration: 0.3 }}
                            />
                        </div>

                        {/* Error Notification & Fallback Action Buttons */}
                        {failedCount > 0 && (
                            <motion.div
                                initial={{ opacity: 0, y: 10 }}
                                animate={{ opacity: 1, y: 0 }}
                                className="mb-2 flex w-full flex-col gap-3 rounded-xl border border-amber-500/20 bg-amber-500/10 p-4 text-sm text-amber-200"
                            >
                                <div className="flex items-center gap-2 font-medium">
                                    <AlertCircle className="h-4 w-4 shrink-0 text-amber-400" />
                                    <span>
                                        {failedCount} aset 3D memerlukan waktu
                                        lebih lama atau gagal dimuat.
                                    </span>
                                </div>
                                <div className="flex items-center gap-2 pt-1">
                                    <button
                                        onClick={onRetry}
                                        className="inline-flex flex-1 cursor-pointer items-center justify-center gap-1.5 rounded-lg bg-indigo-600 px-4 py-2 text-xs font-medium text-white shadow-lg shadow-indigo-600/30 transition-colors hover:bg-indigo-500"
                                    >
                                        <RefreshCw className="h-3.5 w-3.5" />
                                        Coba Lagi
                                    </button>
                                    <button
                                        onClick={onSkip}
                                        className="inline-flex flex-1 cursor-pointer items-center justify-center gap-1.5 rounded-lg border border-white/20 px-4 py-2 text-xs font-medium text-slate-300 transition-colors hover:border-white/40"
                                    >
                                        Lanjutkan
                                        <ArrowRight className="h-3.5 w-3.5" />
                                    </button>
                                </div>
                            </motion.div>
                        )}

                        <div className="mt-4 font-mono text-[11px] text-slate-500">
                            Interactive 3D Portfolio • Powered by Three.js &
                            Laravel
                        </div>
                    </div>
                </motion.div>
            )}
        </AnimatePresence>
    );
}
