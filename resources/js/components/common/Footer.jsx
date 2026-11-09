import React from 'react';
import { ArrowUp, Heart } from 'lucide-react';

export default function Footer() {
    const scrollToTop = () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    return (
        <footer className="relative border-t border-white/10 bg-[#0B0F17]/40 backdrop-blur-md px-4 py-12 sm:px-6 lg:px-8">
            <div className="mx-auto flex max-w-7xl flex-col items-center justify-between gap-6 md:flex-row">
                {/* Brand & Tagline */}
                <div className="flex flex-col items-center text-center md:items-start md:text-left">
                    <div className="flex items-center gap-2">
                        <span className="font-display text-lg font-extrabold text-white">
                            Pandika_Dev
                        </span>
                    </div>
                    <p className="mt-1 max-w-sm text-xs text-slate-400 sm:text-sm">
                        Membangun pengalaman web modern dan interaktif.
                    </p>
                </div>

                {/* Back to Top */}
                <div className="flex items-center gap-4">
                    <button
                        onClick={scrollToTop}
                        className="cursor-pointer rounded-full border border-white/10 bg-white/5 p-2.5 text-slate-300 transition-all hover:bg-white/10 hover:text-white"
                        aria-label="Back to top"
                    >
                        <ArrowUp className="h-4 w-4" />
                    </button>
                </div>
            </div>

            <div className="mx-auto mt-8 flex max-w-7xl flex-col items-center justify-center gap-4 border-t border-white/5 pt-6 font-mono text-xs text-slate-500 sm:flex-row">
                <div>
                    © {new Date().getFullYear()} Pandika_Dev.
                </div>
            </div>
        </footer>
    );
}
