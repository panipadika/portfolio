import React, { useState } from 'react';
import { motion } from 'framer-motion';
import {
    Send,
    Mail,
    MapPin,
    Github,
    Linkedin,
    Instagram,
    ArrowUpRight,
    CheckCircle2,
    AlertCircle,
    Loader2,
} from 'lucide-react';
import { submitContact } from '../../services/api';

const SOCIAL_LINKS = [
    {
        name: 'GitHub',
        handle: '@panipadika',
        url: 'https://github.com',
        icon: Github,
        color: 'hover:text-white',
    },
    {
        name: 'LinkedIn',
        handle: 'Pani Padika',
        url: 'https://linkedin.com',
        icon: Linkedin,
        color: 'hover:text-cyan-400',
    },
    {
        name: 'Instagram',
        handle: '@pani.padika',
        url: 'https://instagram.com',
        icon: Instagram,
        color: 'hover:text-pink-400',
    },
    {
        name: 'TikTok',
        handle: '@panipadika_dev',
        url: 'https://tiktok.com',
        icon: ({ className }) => (
            <svg className={className} viewBox="0 0 24 24" fill="currentColor">
                <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 3 15.68 6.34 6.34 0 0 0 9.35 22a6.33 6.33 0 0 0 6.34-6.32V8.6a8.19 8.19 0 0 0 4.9 1.6V6.75a4.87 4.87 0 0 1-1-.06z" />
            </svg>
        ),
        color: 'hover:text-cyan-300',
    },
];

export default function ContactSection() {
    const [formData, setFormData] = useState({
        name: '',
        email: '',
        message: '',
    });

    const [loading, setLoading] = useState(false);
    const [successMessage, setSuccessMessage] = useState('');
    const [errorMessage, setErrorMessage] = useState('');
    const [fieldErrors, setFieldErrors] = useState({});

    const handleChange = (e) => {
        setFormData((prev) => ({
            ...prev,
            [e.target.name]: e.target.value,
        }));
        if (fieldErrors[e.target.name]) {
            setFieldErrors((prev) => ({ ...prev, [e.target.name]: null }));
        }
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        setSuccessMessage('');
        setErrorMessage('');
        setFieldErrors({});

        try {
            const res = await submitContact(formData);
            setSuccessMessage(
                res.message ||
                    'Pesan Anda berhasil dikirim! Terima kasih telah menghubungi saya.',
            );
            setFormData({ name: '', email: '', message: '' });
        } catch (err) {
            if (err.response?.status === 422 && err.response?.data?.errors) {
                setFieldErrors(err.response.data.errors);
                setErrorMessage(
                    'Mohon periksa kembali input formulir di bawah ini.',
                );
            } else if (err.response?.status === 429) {
                setErrorMessage(
                    'Terlalu banyak permintaan pesan. Silakan tunggu 1 menit sebelum mengirim pesan lagi.',
                );
            } else {
                setErrorMessage(
                    err.response?.data?.message ||
                        'Terjadi kesalahan sistem saat mengirim pesan. Silakan coba lagi.',
                );
            }
        } finally {
            setLoading(false);
        }
    };

    return (
        <section
            id="Contact"
            className="relative border-t border-white/5 bg-[#0B0F17] py-20 lg:py-28"
        >
            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                {/* Section Heading */}
                <div className="mx-auto mb-16 max-w-3xl text-center">
                    <div className="mb-3 inline-flex items-center gap-2 rounded-full border border-indigo-500/20 bg-indigo-500/10 px-3 py-1 font-mono text-xs font-medium text-indigo-400">
                        <span>Hubungi Saya</span>
                    </div>
                    <h2 className="font-display mb-4 text-3xl font-bold tracking-tight text-white sm:text-4xl">
                        Mulai Diskusi & Kolaborasi Proyek
                    </h2>
                    <p className="font-sans text-sm leading-relaxed text-slate-400 sm:text-base">
                        Punya ide proyek, tawaran kerja, atau sekadar ingin
                        berdiskusi seputar rekayasa web? Kirimkan pesan melalui
                        form atau terhubung melalui media sosial saya.
                    </p>
                </div>

                {/* 12-Column Grid: 7 cols Form, 5 cols Find Me */}
                <div className="grid grid-cols-1 items-start gap-8 lg:grid-cols-12">
                    {/* Form Container (7 cols) */}
                    <div className="bg-glass relative rounded-3xl border border-white/10 p-6 shadow-2xl sm:p-8 lg:col-span-7">
                        <h3 className="font-display mb-2 text-xl font-bold text-white">
                            Kirimkan Pesan Langsung
                        </h3>
                        <p className="mb-6 text-xs text-slate-400 sm:text-sm">
                            Pesan Anda akan langsung tersimpan secara aman ke
                            sistem dan segera saya tanggapi.
                        </p>

                        {/* Success Banner */}
                        {successMessage && (
                            <motion.div
                                initial={{ opacity: 0, y: -10 }}
                                animate={{ opacity: 1, y: 0 }}
                                className="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-500/30 bg-emerald-500/10 p-4 text-sm text-emerald-300"
                            >
                                <CheckCircle2 className="h-5 w-5 shrink-0 text-emerald-400" />
                                <span>{successMessage}</span>
                            </motion.div>
                        )}

                        {/* Error Banner */}
                        {errorMessage && (
                            <motion.div
                                initial={{ opacity: 0, y: -10 }}
                                animate={{ opacity: 1, y: 0 }}
                                className="mb-6 flex items-center gap-3 rounded-2xl border border-rose-500/30 bg-rose-500/10 p-4 text-sm text-rose-300"
                            >
                                <AlertCircle className="h-5 w-5 shrink-0 text-rose-400" />
                                <span>{errorMessage}</span>
                            </motion.div>
                        )}

                        <form onSubmit={handleSubmit} className="space-y-4">
                            {/* Name */}
                            <div>
                                <label
                                    htmlFor="contact-name"
                                    className="mb-1.5 block font-mono text-xs text-slate-300"
                                >
                                    Nama Lengkap{' '}
                                    <span className="text-rose-400">*</span>
                                </label>
                                <input
                                    id="contact-name"
                                    type="text"
                                    name="name"
                                    value={formData.name}
                                    onChange={handleChange}
                                    required
                                    placeholder="Contoh: Budi Santoso"
                                    className={`w-full rounded-xl border bg-white/5 px-4 py-3 ${
                                        fieldErrors.name
                                            ? 'border-rose-500'
                                            : 'border-white/10'
                                    } text-sm text-white placeholder-slate-500 transition-all focus:border-transparent focus:ring-2 focus:ring-indigo-500 focus:outline-none`}
                                />
                                {fieldErrors.name && (
                                    <span className="mt-1 block text-xs text-rose-400">
                                        {fieldErrors.name[0]}
                                    </span>
                                )}
                            </div>

                            {/* Email */}
                            <div>
                                <label
                                    htmlFor="contact-email"
                                    className="mb-1.5 block font-mono text-xs text-slate-300"
                                >
                                    Alamat Email{' '}
                                    <span className="text-rose-400">*</span>
                                </label>
                                <input
                                    id="contact-email"
                                    type="email"
                                    name="email"
                                    value={formData.email}
                                    onChange={handleChange}
                                    required
                                    placeholder="name@example.com"
                                    className={`w-full rounded-xl border bg-white/5 px-4 py-3 ${
                                        fieldErrors.email
                                            ? 'border-rose-500'
                                            : 'border-white/10'
                                    } text-sm text-white placeholder-slate-500 transition-all focus:border-transparent focus:ring-2 focus:ring-indigo-500 focus:outline-none`}
                                />
                                {fieldErrors.email && (
                                    <span className="mt-1 block text-xs text-rose-400">
                                        {fieldErrors.email[0]}
                                    </span>
                                )}
                            </div>

                            {/* Message */}
                            <div>
                                <label
                                    htmlFor="contact-message"
                                    className="mb-1.5 block font-mono text-xs text-slate-300"
                                >
                                    Pesan Anda{' '}
                                    <span className="text-rose-400">*</span>
                                </label>
                                <textarea
                                    id="contact-message"
                                    name="message"
                                    rows={4}
                                    value={formData.message}
                                    onChange={handleChange}
                                    required
                                    minLength={10}
                                    placeholder="Tuliskan detail proyek, pertanyaan, atau tawaran kerja sama..."
                                    className={`w-full rounded-xl border bg-white/5 px-4 py-3 ${
                                        fieldErrors.message
                                            ? 'border-rose-500'
                                            : 'border-white/10'
                                    } resize-none text-sm text-white placeholder-slate-500 transition-all focus:border-transparent focus:ring-2 focus:ring-indigo-500 focus:outline-none`}
                                />
                                {fieldErrors.message && (
                                    <span className="mt-1 block text-xs text-rose-400">
                                        {fieldErrors.message[0]}
                                    </span>
                                )}
                            </div>

                            {/* Submit Button */}
                            <button
                                type="submit"
                                disabled={loading}
                                className="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-cyan-500 px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-indigo-600/30 transition-all duration-200 hover:scale-[1.01] hover:from-indigo-500 hover:to-cyan-400 active:scale-[0.99] disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                {loading ? (
                                    <>
                                        <Loader2 className="h-4 w-4 animate-spin" />
                                        <span>Sedang Mengirim Pesan...</span>
                                    </>
                                ) : (
                                    <>
                                        <Send className="h-4 w-4" />
                                        <span>Kirim Pesan Sekarang</span>
                                    </>
                                )}
                            </button>
                        </form>
                    </div>

                    {/* Find Me Panel (5 cols) */}
                    <div className="flex flex-col gap-4 lg:col-span-5">
                        {/* Quick Contact Info Card */}
                        <div className="bg-glass rounded-3xl border border-white/10 p-6">
                            <h3 className="font-display mb-4 text-lg font-bold text-white">
                                Informasi Kontak
                            </h3>
                            <div className="space-y-3">
                                <div className="flex items-center gap-3 text-xs text-slate-300 sm:text-sm">
                                    <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-indigo-500/10 text-indigo-400">
                                        <Mail className="h-4 w-4" />
                                    </div>
                                    <span>panipadika@gmail.com</span>
                                </div>
                                <div className="flex items-center gap-3 text-xs text-slate-300 sm:text-sm">
                                    <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-cyan-500/10 text-cyan-400">
                                        <MapPin className="h-4 w-4" />
                                    </div>
                                    <span>Indonesia • UTC+07:00</span>
                                </div>
                            </div>
                        </div>

                        {/* Social Media Link Cards with Shift on Hover */}
                        <div className="bg-glass rounded-3xl border border-white/10 p-6">
                            <h3 className="font-display mb-2 text-lg font-bold text-white">
                                Find Me On Social Media
                            </h3>
                            <p className="mb-4 text-xs text-slate-400">
                                Terhubung dan ikuti perkembangan portofolio saya
                                di berbagai kanal resmi:
                            </p>

                            <div className="space-y-2.5">
                                {SOCIAL_LINKS.map((item) => {
                                    const Icon = item.icon;
                                    return (
                                        <a
                                            key={item.name}
                                            href={item.url}
                                            target="_blank"
                                            rel="noreferrer"
                                            className="group flex items-center justify-between rounded-2xl border border-white/5 bg-white/5 p-3.5 transition-all duration-200 hover:translate-x-1 hover:border-indigo-500/40 hover:bg-white/10"
                                        >
                                            <div className="flex items-center gap-3">
                                                <div className="flex h-8 w-8 items-center justify-center rounded-xl bg-white/5 text-slate-300 transition-colors group-hover:text-cyan-400">
                                                    <Icon className="h-4 w-4" />
                                                </div>
                                                <div>
                                                    <div className="font-display text-xs font-semibold text-white">
                                                        {item.name}
                                                    </div>
                                                    <div className="font-mono text-[10px] text-slate-400">
                                                        {item.handle}
                                                    </div>
                                                </div>
                                            </div>
                                            <ArrowUpRight className="h-4 w-4 text-slate-500 transition-colors group-hover:text-cyan-400" />
                                        </a>
                                    );
                                })}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}
