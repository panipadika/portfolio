import React from 'react';
import { Radio, Headphones } from 'lucide-react';

export default function SpotifySection() {
    return (
        <section className="relative border-t border-white/5 bg-[#0B0F17]/90 py-16">
            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div className="mx-auto max-w-4xl">
                    {/* Header */}
                    <div className="mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                        <div className="flex items-center gap-3">
                            <div className="flex h-10 w-10 items-center justify-center rounded-xl border border-emerald-500/20 bg-emerald-500/10 text-emerald-400">
                                <Headphones className="h-5 w-5 animate-pulse" />
                            </div>
                            <div>
                                <div className="flex items-center gap-2">
                                    <span className="font-display text-lg font-bold text-white sm:text-xl">
                                        Daily Rotation
                                    </span>
                                    <span className="inline-flex items-center gap-1 rounded-full border border-emerald-500/30 bg-emerald-500/15 px-2 py-0.5 font-mono text-[10px] text-emerald-400">
                                        <span className="h-1.5 w-1.5 animate-ping rounded-full bg-emerald-400" />
                                        Listening Mode
                                    </span>
                                </div>
                                <p className="mt-0.5 font-sans text-xs text-slate-400">
                                    Kompilasi musik pengiring sesi ngoding dan
                                    fokus harian
                                </p>
                            </div>
                        </div>

                        <div className="flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1.5 font-mono text-xs text-slate-400">
                            <Radio className="h-3.5 w-3.5 text-emerald-400" />
                            <span>Spotify Playlist</span>
                        </div>
                    </div>

                    {/* Responsive Spotify Embed Container per DESIGN.md Section 5 */}
                    <div className="bg-glass w-full max-w-full overflow-hidden rounded-2xl border border-white/10 shadow-2xl">
                        <iframe
                            title="Spotify Daily Coding Playlist"
                            style={{ borderRadius: '16px' }}
                            src="https://open.spotify.com/embed/playlist/37i9dQZF1DXdLEN7aqioXM?utm_source=generator&theme=0"
                            width="100%"
                            height="352"
                            frameBorder="0"
                            allowFullScreen=""
                            allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
                            loading="lazy"
                            className="block w-full max-w-full"
                        />
                    </div>
                </div>
            </div>
        </section>
    );
}
