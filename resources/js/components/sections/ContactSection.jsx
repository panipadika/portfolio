import React, { useState } from 'react';
import { motion } from 'framer-motion';
import {
    Send,
    Mail,
    ArrowUpRight,
    CheckCircle2,
    AlertCircle,
    Loader2,
} from 'lucide-react';
import { submitContact } from '../../services/api';
import { SOCIAL_LINKS as APP_SOCIAL_LINKS } from '../../config/socials';

// Official Brand SVGs with Authentic Colors & Standardized Optical Dimensions
const GitHubBrandIcon = ({ className = 'h-6 w-6' }) => (
    <svg viewBox="0 0 24 24" className={className} fill="#FFFFFF">
        <path
            fillRule="evenodd"
            clipRule="evenodd"
            d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
        />
    </svg>
);

const LinkedInBrandIcon = ({ className = 'h-6 w-6' }) => (
    <svg viewBox="0 0 24 24" className={className} fill="none">
        <rect x="2" y="2" width="20" height="20" rx="4" fill="#0A66C2" />
        <path
            fill="#FFFFFF"
            d="M6.2 9.5h2.6v8.5H6.2V9.5zm1.3-4.2c.85 0 1.55.7 1.55 1.55s-.7 1.55-1.55 1.55-1.55-.7-1.55-1.55.7-1.55 1.55-1.55zm3.8 4.2h2.5v1.2h.04c.35-.7 1.25-1.4 2.56-1.4 2.75 0 3.25 1.8 3.25 4.15v4.55h-2.6v-4.05c0-.95-.02-2.15-1.32-2.15-1.32 0-1.52 1.02-1.52 2.08v4.12h-2.6V9.5z"
        />
    </svg>
);

const InstagramBrandIcon = ({ className = 'h-6 w-6' }) => (
    <svg viewBox="0 0 24 24" className={className}>
        <defs>
            <linearGradient id="ig-contact-grad" x1="0%" y1="100%" x2="100%" y2="0%">
                <stop offset="0%" stopColor="#fdf497" />
                <stop offset="5%" stopColor="#fdf497" />
                <stop offset="45%" stopColor="#fd5949" />
                <stop offset="60%" stopColor="#d6249f" />
                <stop offset="90%" stopColor="#285AEB" />
            </linearGradient>
        </defs>
        <path
            fill="url(#ig-contact-grad)"
            d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"
        />
    </svg>
);

const TikTokBrandIcon = ({ className = 'h-6 w-6' }) => (
    <svg viewBox="0 0 24 24" className={className}>
        <path
            fill="#25F4EE"
            d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 3 15.68 6.34 6.34 0 0 0 9.35 22a6.33 6.33 0 0 0 6.34-6.32V8.6a8.19 8.19 0 0 0 4.9 1.6V6.75a4.87 4.87 0 0 1-1-.06z"
            transform="translate(-0.5, -0.5)"
            opacity="0.9"
        />
        <path
            fill="#FE2C55"
            d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 3 15.68 6.34 6.34 0 0 0 9.35 22a6.33 6.33 0 0 0 6.34-6.32V8.6a8.19 8.19 0 0 0 4.9 1.6V6.75a4.87 4.87 0 0 1-1-.06z"
            transform="translate(0.5, 0.5)"
            opacity="0.9"
        />
        <path
            fill="#FFFFFF"
            d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 3 15.68 6.34 6.34 0 0 0 9.35 22a6.33 6.33 0 0 0 6.34-6.32V8.6a8.19 8.19 0 0 0 4.9 1.6V6.75a4.87 4.87 0 0 1-1-.06z"
        />
    </svg>
);

const WhatsAppBrandIcon = ({ className = 'h-4 w-4' }) => (
    <svg viewBox="0 0 24 24" className={className} fill="#25D366">
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
    </svg>
);

export default function ContactSection({ settings = {} }) {
    const contactEmail = settings?.contact_email || 'panipadika123@gmail.com';
    const contactPhone = settings?.contact_phone || APP_SOCIAL_LINKS.whatsapp.number;
    const contactWhatsApp = settings?.contact_whatsapp || APP_SOCIAL_LINKS.whatsapp.url;
    const headline = settings?.bio_headline || 'Punya ide menarik, peluang proyek, atau ingin berdiskusi? Tinggalkan pesan di sini, saya akan senang mendengarnya.';

    const socialItems = [
        {
            name: APP_SOCIAL_LINKS.github.name,
            handle: `@${APP_SOCIAL_LINKS.github.username}`,
            url: settings?.social_github || APP_SOCIAL_LINKS.github.url,
            icon: GitHubBrandIcon,
            hoverBorder: 'hover:border-white/30',
            brandText: 'group-hover:text-white',
        },
        {
            name: APP_SOCIAL_LINKS.linkedin.name,
            handle: 'Pani Padika',
            url: settings?.social_linkedin || APP_SOCIAL_LINKS.linkedin.url,
            icon: LinkedInBrandIcon,
            hoverBorder: 'hover:border-[#0A66C2]/40',
            brandText: 'group-hover:text-[#0A66C2]',
        },
        {
            name: APP_SOCIAL_LINKS.instagram.name,
            handle: APP_SOCIAL_LINKS.instagram.username,
            url: settings?.social_instagram || APP_SOCIAL_LINKS.instagram.url,
            icon: InstagramBrandIcon,
            hoverBorder: 'hover:border-[#E4405F]/40',
            brandText: 'group-hover:text-[#E4405F]',
        },
        {
            name: APP_SOCIAL_LINKS.tiktok.name,
            handle: APP_SOCIAL_LINKS.tiktok.username,
            url: settings?.social_tiktok || APP_SOCIAL_LINKS.tiktok.url,
            icon: TikTokBrandIcon,
            hoverBorder: 'hover:border-[#25F4EE]/40',
            brandText: 'group-hover:text-[#25F4EE]',
        },
    ];

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
            className="relative border-t border-white/5 bg-[#0B0F17]/30 backdrop-blur-[2px] py-20 lg:py-28"
        >
            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                {/* Section Heading */}
                <div className="mx-auto mb-16 max-w-3xl text-center">
                    <h2 className="font-display text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
                        Hubungi Saya
                    </h2>
                </div>

                {/* 12-Column Grid: 7 cols Form, 5 cols Find Me */}
                <div className="grid grid-cols-1 items-start gap-8 lg:grid-cols-12">
                    {/* Form Container (7 cols) */}
                    <div className="bg-glass relative rounded-3xl border border-white/10 p-6 shadow-2xl sm:p-8 lg:col-span-7">
                        <h3 className="font-display mb-2 text-xl font-bold text-white">
                            Mari Berkolaborasi
                        </h3>
                        <p className="mb-6 text-xs text-slate-400 sm:text-sm">
                            {headline}
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
                                    Nama{' '}
                                    <span className="text-rose-400"></span>
                                </label>
                                <input
                                    id="contact-name"
                                    type="text"
                                    name="name"
                                    value={formData.name}
                                    onChange={handleChange}
                                    required
                                    placeholder="Masukkan Nama Anda"
                                    className={`w-full rounded-xl border bg-white/5 px-4 py-3 ${fieldErrors.name
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
                                    Email{' '}
                                    <span className="text-rose-400"></span>
                                </label>
                                <input
                                    id="contact-email"
                                    type="email"
                                    name="email"
                                    value={formData.email}
                                    onChange={handleChange}
                                    required
                                    placeholder="Masukkan Email Anda"
                                    className={`w-full rounded-xl border bg-white/5 px-4 py-3 ${fieldErrors.email
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
                                    Pesan{' '}
                                    <span className="text-rose-400"></span>
                                </label>
                                <textarea
                                    id="contact-message"
                                    name="message"
                                    rows={8}
                                    value={formData.message}
                                    onChange={handleChange}
                                    required
                                    minLength={10}
                                    placeholder="Tuliskan Detail Proyek, Pertanyaan, atau Tawaran Kerjasama..."
                                    className={`w-full rounded-xl border bg-white/5 px-4 py-3 ${fieldErrors.message
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
                                className="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#262f6b] via-indigo-600 to-[#4e63d9] px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-[#262f6b]/30 transition-all duration-200 hover:scale-[1.01] hover:from-[#313c87] hover:to-[#5d72e4] active:scale-[0.99] disabled:cursor-not-allowed disabled:opacity-50"
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
                                {/* Email */}
                                <a
                                    href={`mailto:${contactEmail}`}
                                    className="group flex items-center justify-between rounded-2xl border border-white/5 bg-white/[0.03] p-3 transition-all duration-200 hover:border-indigo-500/40 hover:bg-white/[0.07]"
                                >
                                    <div className="flex items-center gap-3 text-xs text-slate-300 sm:text-sm">
                                        <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-500/15 text-indigo-400 border border-indigo-500/30 transition-transform group-hover:scale-105">
                                            <Mail className="h-4 w-4" />
                                        </div>
                                        <div className="flex flex-col">
                                            <span className="font-medium text-white group-hover:text-indigo-300 transition-colors">
                                                Email
                                            </span>
                                            <span className="text-[11px] text-slate-400">
                                                {contactEmail}
                                            </span>
                                        </div>
                                    </div>
                                    <ArrowUpRight className="h-4 w-4 text-slate-500 transition-colors group-hover:text-indigo-400" />
                                </a>

                                {/* WhatsApp (Menggantikan Lokasi & Mengarahkan ke WhatsApp) */}
                                <a
                                    href={contactWhatsApp}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="group flex items-center justify-between rounded-2xl border border-[#25D366]/20 bg-[#25D366]/[0.04] p-3 transition-all duration-200 hover:border-[#25D366]/50 hover:bg-[#25D366]/[0.08]"
                                >
                                    <div className="flex items-center gap-3 text-xs text-slate-300 sm:text-sm">
                                        <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#25D366]/20 text-[#25D366] border border-[#25D366]/40 transition-transform group-hover:scale-105 shadow-[0_0_15px_rgba(37,211,102,0.25)]">
                                            <WhatsAppBrandIcon className="h-4 w-4" />
                                        </div>
                                        <div className="flex flex-col">
                                            <span className="font-medium text-white group-hover:text-[#25D366] transition-colors">
                                                WhatsApp
                                            </span>
                                            <span className="text-[11px] text-[#25D366]/80 font-mono">
                                                {contactPhone}
                                            </span>
                                        </div>
                                    </div>
                                    <ArrowUpRight className="h-4 w-4 text-[#25D366]/70 transition-colors group-hover:text-[#25D366]" />
                                </a>
                            </div>
                        </div>

                        {/* Social Media Link Cards with Brand Authentic Colors */}
                        <div className="bg-glass rounded-3xl border border-white/10 p-6">
                            <h3 className="font-display mb-2 text-lg font-bold text-white">
                                Find Me On Social Media
                            </h3>

                            <div className="space-y-2.5">
                                {socialItems.map((item) => {
                                    const Icon = item.icon;
                                    return (
                                        <a
                                            key={item.name}
                                            href={item.url}
                                            target="_blank"
                                            rel="noreferrer"
                                            className={`group flex items-center justify-between rounded-2xl border border-white/5 bg-white/5 p-3.5 transition-all duration-200 hover:translate-x-1 ${item.hoverBorder} hover:bg-white/10`}
                                        >
                                            <div className="flex items-center gap-3.5">
                                                <div className="flex h-6 w-6 shrink-0 items-center justify-center transition-transform duration-200 group-hover:scale-110">
                                                    <Icon className="h-6 w-6" />
                                                </div>
                                                <div>
                                                    <div
                                                        className={`font-display text-xs font-semibold text-white transition-colors ${item.brandText}`}
                                                    >
                                                        {item.name}
                                                    </div>
                                                    <div className="font-mono text-[10px] text-slate-400">
                                                        {item.handle}
                                                    </div>
                                                </div>
                                            </div>
                                            <ArrowUpRight className="h-4 w-4 text-slate-500 transition-colors group-hover:text-white" />
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
